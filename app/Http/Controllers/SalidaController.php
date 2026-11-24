<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use App\Models\SalidaPersonal;
use App\Models\SalidaProgramacion;
use App\Models\SalidaVehiculo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SalidaController extends Controller
{
    /** Calendario */
    public function index()
    {
        // Cantidad de salidas programadas por día, para marcarlas en el calendario
        $conteos = SalidaProgramacion::selectRaw('fecha, COUNT(*) as total')
            ->groupBy('fecha')
            ->pluck('total', 'fecha')
            ->mapWithKeys(fn ($total, $fecha) => [Carbon::parse($fecha)->format('Y-m-d') => (int) $total]);

        return view('salidas.index', compact('conteos'));
    }

    /** Tablero de un día */
    public function create(Request $request)
    {
        $fecha  = $this->fechaDesde($request->query('fecha'));
        $estado = $this->estado($fecha->format('Y-m-d'));

        // ?copiar=AAAA-MM-DD → precarga el tablero de otra fecha (sin guardar),
        // solo si el día todavía no tiene salidas y la fecha origen sí tiene.
        $copiadoDe = null;
        $copiar = $request->query('copiar');
        if ($copiar && preg_match('/^\d{4}-\d{2}-\d{2}$/', $copiar)
            && !SalidaProgramacion::whereDate('fecha', $fecha)->exists()
            && SalidaProgramacion::whereDate('fecha', $copiar)->exists()) {

            $origen = $this->fechaDesde($copiar);
            $estado = $this->estado($origen->format('Y-m-d'));
            // Todavía no las agregó nadie en esta fecha
            $estado['salidas'] = $estado['salidas']->map(fn ($s) => ['agregadoPor' => null] + $s)->values();
            $copiadoDe = $origen->locale('es')->translatedFormat('l j \d\e F');
        }

        return view('salidas.create', [
            'fecha'      => $fecha->format('Y-m-d'),
            'fechaTexto' => ucfirst($fecha->locale('es')->translatedFormat('l j \d\e F \d\e Y')),
            'estado'     => $estado,
            'copiadoDe'  => $copiadoDe,
        ]);
    }

    /**
     * Guarda el tablero de un día.
     *
     * Recibe:
     *  - catalogo: solo los elementos nuevos o modificados de cada catálogo
     *              (salida / vehiculo / personal). Los nuevos traen un id temporal "n…".
     *  - tablero:  las salidas que están en el tablero con sus vehículos y personal.
     *
     * Los catálogos son globales (sirven para cualquier fecha); el tablero
     * reemplaza la programación completa de esa fecha.
     */
    public function guardar(Request $request)
    {
        $datos = $request->validate([
            'fecha'                          => ['required', 'date_format:Y-m-d'],
            'catalogo'                       => ['array'],
            'catalogo.*'                     => ['array'],
            'catalogo.*.*.id'                => ['required'],
            'catalogo.*.*.nombre'            => ['required', 'string', 'max:150'],
            'catalogo.*.*.oculto'            => ['boolean'],
            'tablero'                        => ['array'],
            'tablero.*.salida_id'            => ['required'],
            'tablero.*.vehiculos'            => ['array'],
            'tablero.*.personal'             => ['array'],
            'tablero.*.viaje'                => ['array'],
            'tablero.*.viaje.*.personal'     => ['required'],
            'tablero.*.viaje.*.vehiculo'     => ['required'],
            'tablero.*.viaje.*.conductor'    => ['boolean'],
        ]);

        $fecha     = $datos['fecha'];
        $usuarioId = session('usuario_id');

        $modelos = [
            'salida'   => Salida::class,
            'vehiculo' => SalidaVehiculo::class,
            'personal' => SalidaPersonal::class,
        ];

        DB::transaction(function () use ($datos, $fecha, $usuarioId, $modelos) {
            // ── 1. Catálogos: crear nuevos / actualizar modificados ──
            // $mapa[tipo][idCliente] = idReal (para traducir los ids temporales "n…")
            $mapa = ['salida' => [], 'vehiculo' => [], 'personal' => []];

            foreach ($modelos as $tipo => $modelo) {
                foreach ($datos['catalogo'][$tipo] ?? [] as $item) {
                    $nombre = trim($item['nombre']);
                    $oculto = (bool) ($item['oculto'] ?? false);

                    if ($this->esTemporal($item['id'])) {
                        $nuevo = $modelo::create([
                            'nombre'     => $nombre,
                            'oculto'     => $oculto,
                            'usuario_id' => $usuarioId,
                        ]);
                        $mapa[$tipo][(string) $item['id']] = $nuevo->id;
                    } else {
                        $modelo::whereKey((int) $item['id'])->update([
                            'nombre' => $nombre,
                            'oculto' => $oculto,
                        ]);
                    }
                }
            }

            $resolver = function (string $tipo, $id) use (&$mapa, $modelos) {
                if ($this->esTemporal($id)) {
                    if (!isset($mapa[$tipo][(string) $id])) {
                        throw ValidationException::withMessages(['tablero' => 'Hay elementos nuevos que no se pudieron identificar.']);
                    }
                    return $mapa[$tipo][(string) $id];
                }
                $id = (int) $id;
                if (!$modelos[$tipo]::whereKey($id)->exists()) {
                    throw ValidationException::withMessages(['tablero' => 'Algún elemento ya no existe. Recargá la página.']);
                }
                return $id;
            };

            // ── 2. Tablero de la fecha: se reemplaza completo ──
            $tablero = [];
            $usadosVeh = [];
            $usadosPer = [];

            foreach ($datos['tablero'] ?? [] as $fila) {
                $salidaId = $resolver('salida', $fila['salida_id']);
                $veh = array_values(array_unique(array_map(fn ($id) => $resolver('vehiculo', $id), $fila['vehiculos'] ?? [])));
                $per = array_values(array_unique(array_map(fn ($id) => $resolver('personal', $id), $fila['personal'] ?? [])));

                // Un vehículo / una persona solo puede estar en una salida por día
                if (array_intersect($veh, $usadosVeh) || array_intersect($per, $usadosPer)) {
                    throw ValidationException::withMessages(['tablero' => 'Un vehículo o una persona está en más de una salida el mismo día.']);
                }
                $usadosVeh = array_merge($usadosVeh, $veh);
                $usadosPer = array_merge($usadosPer, $per);

                // Opcional: en qué vehículo va cada persona y quién lo conduce
                $viaje = [];
                $conductores = [];
                foreach ($fila['viaje'] ?? [] as $v) {
                    $perId = $resolver('personal', $v['personal']);
                    $vehId = $resolver('vehiculo', $v['vehiculo']);
                    if (!in_array($perId, $per, true) || !in_array($vehId, $veh, true)) {
                        throw ValidationException::withMessages(['tablero' => 'Una persona está en un vehículo que no pertenece a su salida.']);
                    }
                    $conductor = (bool) ($v['conductor'] ?? false);
                    if ($conductor) {
                        if (isset($conductores[$vehId])) {
                            throw ValidationException::withMessages(['tablero' => 'Un vehículo tiene más de un conductor.']);
                        }
                        $conductores[$vehId] = true;
                    }
                    $viaje[$perId] = ['salida_vehiculo_id' => $vehId, 'conductor' => $conductor];
                }

                $tablero[$salidaId] = ['vehiculos' => $veh, 'personal' => $per, 'viaje' => $viaje];
            }

            // Conservar quién creó cada programación que sigue en el tablero
            $existentes = SalidaProgramacion::whereDate('fecha', $fecha)->get()->keyBy('salida_id');

            // Quitar del día las salidas que ya no están en el tablero
            SalidaProgramacion::whereDate('fecha', $fecha)
                ->whereNotIn('salida_id', array_keys($tablero))
                ->delete();

            foreach ($tablero as $salidaId => $asig) {
                $prog = $existentes->get($salidaId) ?? SalidaProgramacion::create([
                    'fecha'      => $fecha,
                    'salida_id'  => $salidaId,
                    'usuario_id' => $usuarioId,
                ]);

                $pivot = ['fecha' => $fecha];
                $prog->vehiculos()->detach();
                $prog->personales()->detach();
                $prog->vehiculos()->attach(array_fill_keys($asig['vehiculos'], $pivot));
                $sinVehiculo = ['salida_vehiculo_id' => null, 'conductor' => false];
                $prog->personales()->attach(collect($asig['personal'])
                    ->mapWithKeys(fn ($id) => [$id => $pivot + ($asig['viaje'][$id] ?? $sinVehiculo)])
                    ->all());
                $prog->touch();
            }
        });

        return response()->json([
            'ok'     => true,
            'estado' => $this->estado($fecha),
        ]);
    }

    // ─────────────────────────────────────────────

    /** Estado completo para el tablero: catálogos + programación de la fecha */
    private function estado(string $fecha): array
    {
        $programaciones = SalidaProgramacion::with(['vehiculos', 'personales', 'usuario'])
            ->whereDate('fecha', $fecha)
            ->get()
            ->keyBy('salida_id');

        $item = fn ($m) => ['id' => $m->id, 'nombre' => $m->nombre, 'oculto' => (bool) $m->oculto];

        return [
            'salidas' => Salida::orderBy('nombre')->get()->map(function ($s) use ($programaciones, $item) {
                $prog = $programaciones->get($s->id);
                return $item($s) + [
                    'enTablero' => (bool) $prog,
                    'vehiculos' => $prog ? $prog->vehiculos->pluck('id')->values() : [],
                    'personal'  => $prog ? $prog->personales->pluck('id')->values() : [],
                    // Solo las personas con vehículo especificado
                    'viaje'     => $prog ? $prog->personales
                        ->filter(fn ($p) => $p->pivot->salida_vehiculo_id)
                        ->map(fn ($p) => [
                            'personal'  => $p->id,
                            'vehiculo'  => (int) $p->pivot->salida_vehiculo_id,
                            'conductor' => (bool) $p->pivot->conductor,
                        ])->values() : [],
                    // Usuario que agregó esta salida al tablero de la fecha
                    'agregadoPor' => $prog && $prog->usuario
                        ? ($prog->usuario->nombre_completo ?: $prog->usuario->nombre)
                        : null,
                ];
            })->values(),
            'vehiculos' => SalidaVehiculo::orderBy('nombre')->get()->map($item)->values(),
            'personal'  => SalidaPersonal::orderBy('nombre')->get()->map($item)->values(),
        ];
    }

    private function fechaDesde(?string $valor): Carbon
    {
        try {
            return $valor ? Carbon::createFromFormat('Y-m-d', $valor)->startOfDay() : Carbon::today();
        } catch (\Throwable $e) {
            return Carbon::today();
        }
    }

    private function esTemporal($id): bool
    {
        return is_string($id) && str_starts_with($id, 'n');
    }
}
