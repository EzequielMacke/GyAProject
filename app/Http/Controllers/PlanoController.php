<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTc;
use App\Models\ObraTc;
use App\Models\Plano;
use App\Models\PlanoActividad;
use App\Models\PlanoGrupo;
use App\Models\PlanoSubgrupo;
use App\Services\PermisoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PlanoController extends Controller
{
    /**
     * Nombre legible de cada herramienta del editor de planos, usado para
     * armar el detalle del registro de actividad (debe reflejar los
     * mismos nombres que usa el JS en resources/views/planos_tc/plano.blade.php).
     */
    private const NOMBRES_HERRAMIENTA = [
        'fisura' => 'Fisura',
        'corrosion' => 'Corrosión',
        'humedad' => 'Humedad',
        'coqueras' => 'Coqueras',
        'fisura_ducto' => 'Fisura por ducto',
        'junta_fria' => 'Junta fría',
        'armadura_expuesta' => 'Armadura expuesta',
        'eflorescencia' => 'Eflorescencia',
        'socavacion' => 'Socavación',
        'desprendimiento' => 'Desprendimiento',
        'exfoliacion' => 'Exfoliación',
        'desaplome' => 'Desaplome',
        'fisura_vertical' => 'Fisura vertical',
        'fisura_inclinada' => 'Fisura inclinada',
        'fisura_semiinclinada' => 'Fisura semi-inclinada',
        'esclerometria' => 'Esclerometría',
        'carbonatacion' => 'Carbonatación',
        'pachometria' => 'Pachometría',
        'testigos' => 'Testigos',
        'ultrasonido' => 'Ultrasonido',
        'resistividad' => 'Resistividad',
        'potencial' => 'Potencial',
        'cloruros' => 'Cloruros',
        'georradar' => 'Georradar',
        'computo_fisuras' => 'Cómputo de fisuras',
        'texto' => 'Texto',
        'dibujo_libre' => 'Dibujo a mano alzada',
        'dibujo_libre_relleno' => 'Mano alzada con relleno',
        'circulo' => 'Círculo',
        'circulo_relleno' => 'Círculo con relleno',
        'rectangulo' => 'Rectángulo',
        'rectangulo_relleno' => 'Rectángulo con relleno',
        'linea_recta' => 'Línea recta',
        'foto' => 'Fotografía',
    ];

    private function describirElemento(array $item): string
    {
        $nombre = self::NOMBRES_HERRAMIENTA[$item['tool'] ?? ''] ?? ($item['tool'] ?? 'Elemento');

        if (! empty($item['etiqueta'])) {
            return "{$nombre} ({$item['etiqueta']})";
        }

        if (($item['tipo'] ?? null) === 'texto' && ($item['tool'] ?? null) !== 'texto' && ! empty($item['texto'])) {
            return "{$nombre} ({$item['texto']})";
        }

        return $nombre;
    }

    public function index(ObraTc $obraTc)
    {
        $usuarioId = session('usuario_id');

        $enDirectorio = DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', $usuarioId)
            ->exists();

        if (! $enDirectorio) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $planos = Plano::where('obra_id', $obraTc->id)
            ->whereNotNull('descripcion')
            ->whereNotNull('subgrupo_id')
            ->with(['grupo', 'subgrupo', 'usuario'])
            ->orderBy('descripcion')
            ->get();

        $arbol = $planos->groupBy('grupo_id')
            ->map(function ($planosDelGrupo) {
                return [
                    'grupo' => $planosDelGrupo->first()->grupo,
                    'subgrupos' => $planosDelGrupo->groupBy('subgrupo_id')
                        ->map(function ($planosDelSubgrupo) {
                            return [
                                'subgrupo' => $planosDelSubgrupo->first()->subgrupo,
                                'planos' => $planosDelSubgrupo,
                            ];
                        })
                        ->sortBy('subgrupo.descripcion'),
                ];
            })
            ->sortBy('grupo.descripcion');

        $pendientesCount = Plano::where('obra_id', $obraTc->id)
            ->where(function ($query) {
                $query->whereNull('descripcion')->orWhereNull('subgrupo_id');
            })
            ->count();

        $gruposExistentes = PlanoGrupo::where('obra_id', $obraTc->id)
            ->orderBy('descripcion')
            ->pluck('descripcion')
            ->unique();

        return view('planos_tc.index', compact('obraTc', 'planos', 'arbol', 'pendientesCount', 'gruposExistentes'));
    }

    public function show(ObraTc $obraTc, Plano $plano)
    {
        $permisoService = app(PermisoService::class);
        $puedeEditar = $permisoService->puede('ano_pla', 'editar');
        $puedeEliminar = $permisoService->puede('ano_pla', 'eliminar');
        $estadoGuardado = $plano->estado ? json_decode($plano->estado, true) : null;

        return view('planos_tc.plano', compact('obraTc', 'plano', 'puedeEditar', 'puedeEliminar', 'estadoGuardado'));
    }

    /**
     * Recibe operaciones puntuales (no el plano entero) y las aplica sobre
     * el estado más reciente guardado en la base de datos, con lockForUpdate
     * dentro de una transacción: así, si dos personas guardan casi al mismo
     * tiempo, la segunda se aplica sobre el resultado de la primera en vez
     * de pisarlo. Cada operación además genera su fila de actividad.
     */
    public function guardarEstado(Request $request, ObraTc $obraTc, Plano $plano)
    {
        $request->validate([
            'agregados' => 'array',
            'agregados.*.id' => 'required|string',
            'eliminados' => 'array',
            'eliminados.*' => 'string',
            'movidos' => 'array',
            'movidos.*.id' => 'required|string',
            'fotosCambiadas' => 'array',
            'fotosCambiadas.*.id' => 'required|string',
            'fotosCambiadas.*.fotosAgregadas' => 'array',
            'fotosCambiadas.*.fotosAgregadas.*' => 'string',
            'fotosCambiadas.*.fotosEliminadas' => 'array',
            'fotosCambiadas.*.fotosEliminadas.*' => 'string',
            'escalas' => 'nullable|array',
        ]);

        $permisoService = app(PermisoService::class);
        $puedeEditar = $permisoService->puede('ano_pla', 'editar');
        $puedeEliminar = $permisoService->puede('ano_pla', 'eliminar');

        $agregados = $request->input('agregados', []);
        $idsEliminados = $request->input('eliminados', []);
        $movidos = $request->input('movidos', []);
        $fotosCambiadas = $request->input('fotosCambiadas', []);
        $escalasNuevas = $request->input('escalas');

        $hayFotoAgregada = collect($fotosCambiadas)->contains(fn ($c) => ! empty($c['fotosAgregadas'] ?? []));
        $hayFotoEliminada = collect($fotosCambiadas)->contains(fn ($c) => ! empty($c['fotosEliminadas'] ?? []));

        if ((! empty($agregados) || ! empty($movidos) || $hayFotoAgregada) && ! $puedeEditar) {
            abort(403, 'No tenés permiso para agregar o mover elementos.');
        }
        if ((! empty($idsEliminados) || $hayFotoEliminada) && ! $puedeEliminar) {
            abort(403, 'No tenés permiso para eliminar elementos.');
        }

        $usuarioId = session('usuario_id');
        $ahora = now()->toDateTimeString();
        $registros = [];

        $estadoFinal = DB::transaction(function () use (
            $plano, $agregados, $idsEliminados, $movidos, $fotosCambiadas, $escalasNuevas,
            $usuarioId, $ahora, &$registros
        ) {
            $planoBloqueado = Plano::whereKey($plano->id)->lockForUpdate()->firstOrFail();
            $estadoActual = $planoBloqueado->estado ? json_decode($planoBloqueado->estado, true) : [];
            $trazos = collect($estadoActual['trazos'] ?? [])->keyBy('id');

            foreach ($agregados as $item) {
                if ($trazos->has($item['id'])) continue; // ya estaba (reintento de un guardado anterior)
                $trazos->put($item['id'], $item);
                $registros[] = $this->filaActividad($plano->id, $usuarioId, 'agregar', $item, $ahora);
            }

            foreach ($idsEliminados as $id) {
                if (! $trazos->has($id)) continue; // ya lo había borrado otro guardado
                $item = $trazos->pull($id);
                $registros[] = $this->filaActividad($plano->id, $usuarioId, 'eliminar', $item, $ahora);
            }

            foreach ($movidos as $item) {
                if (! $trazos->has($item['id'])) continue; // otro usuario lo borró mientras tanto
                $trazos->put($item['id'], $item);
                $registros[] = $this->filaActividad($plano->id, $usuarioId, 'mover', $item, $ahora);
            }

            /* Se aplica un diff (agregadas/eliminadas) sobre el array de
               fotos que ya está en $trazos (el estado más reciente,
               bajo lock), en vez de reemplazarlo por lo que mandó el
               cliente: así, si dos dispositivos suben fotos distintas
               al mismo elemento casi al mismo tiempo, el segundo
               guardado no pisa la foto que subió el primero. */
            foreach ($fotosCambiadas as $cambio) {
                if (! $trazos->has($cambio['id'])) continue;
                $item = $trazos->get($cambio['id']);
                $fotosAgregadas = $cambio['fotosAgregadas'] ?? [];
                $fotosEliminadas = $cambio['fotosEliminadas'] ?? [];

                $item['fotos'] = array_values(array_unique(array_merge(
                    array_diff($item['fotos'] ?? [], $fotosEliminadas),
                    $fotosAgregadas
                )));
                $trazos->put($cambio['id'], $item);

                if (! empty($fotosAgregadas)) {
                    $registros[] = $this->filaActividad($plano->id, $usuarioId, 'agregar_foto', $item, $ahora);
                }
                if (! empty($fotosEliminadas)) {
                    $registros[] = $this->filaActividad($plano->id, $usuarioId, 'eliminar_foto', $item, $ahora);
                }
            }

            $estadoNuevo = [
                'escalas' => $escalasNuevas ?: ($estadoActual['escalas'] ?? []),
                'trazos' => $trazos->values()->all(),
            ];

            $planoBloqueado->update(['estado' => json_encode($estadoNuevo)]);

            return $estadoNuevo;
        });

        if (! empty($registros)) {
            PlanoActividad::insert($registros);
        }

        return response()->json(['success' => true, 'estado' => $estadoFinal]);
    }

    private function filaActividad(int $planoId, ?int $usuarioId, string $accion, array $item, string $fecha): array
    {
        return [
            'plano_id' => $planoId,
            'usuario_id' => $usuarioId,
            'accion' => $accion,
            'tool' => $item['tool'] ?? '?',
            'detalle' => $this->describirElemento($item),
            'created_at' => $fecha,
        ];
    }

    public function actividad(ObraTc $obraTc, Plano $plano)
    {
        $actividades = PlanoActividad::where('plano_id', $plano->id)
            ->with('usuario')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(200)
            ->get()
            ->map(function (PlanoActividad $actividad) {
                $usuario = $actividad->usuario;

                return [
                    'usuario' => $usuario ? ($usuario->nombre_completo ?: $usuario->nombre) : 'Usuario desconocido',
                    'accion' => $actividad->accion,
                    'detalle' => $actividad->detalle,
                    'fecha' => $actividad->created_at?->format('d/m/Y H:i'),
                ];
            });

        return response()->json($actividades);
    }

    public function subirFoto(Request $request, ObraTc $obraTc, Plano $plano)
    {
        $request->validate([
            'foto' => 'required|image|max:10240',
        ]);

        $carpeta = "fotos_planos/{$plano->id}";
        if (! Storage::disk('public')->exists($carpeta)) {
            Storage::disk('public')->makeDirectory($carpeta);
        }

        $ruta = $request->file('foto')->store($carpeta, 'public');

        return response()->json(['url' => Storage::url($ruta)]);
    }

    public function store(Request $request, ObraTc $obraTc)
    {
        $request->validate([
            'nombre_grupo' => 'required|string|max:255',
            'archivos' => 'required|array|min:1',
            'archivos.*' => 'required|file|mimes:pdf',
        ]);

        $usuarioId = session('usuario_id');

        $grupo = PlanoGrupo::firstOrCreate(
            [
                'obra_id' => $obraTc->id,
                'descripcion' => $request->nombre_grupo,
            ],
            [
                'usuario_id' => $usuarioId,
            ]
        );

        if (! Storage::disk('public')->exists('planos')) {
            Storage::disk('public')->makeDirectory('planos');
        }

        foreach ($request->file('archivos') as $archivo) {
            $ruta = $archivo->store('planos', 'public');
            $nombreArchivo = basename($ruta);

            Plano::create([
                'descripcion' => null,
                'obra_id' => $obraTc->id,
                'grupo_id' => $grupo->id,
                'subgrupo_id' => null,
                'archivo' => $nombreArchivo,
                'archivo_original' => $archivo->getClientOriginalName(),
                'usuario_id' => $usuarioId,
            ]);
        }

        return redirect()->route('planos_tc.index', $obraTc->id)->with('success', 'Planos cargados como pendientes de nombrar y clasificar.');
    }

    public function aprobar(ObraTc $obraTc)
    {
        $usuarioId = session('usuario_id');

        $enDirectorio = DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', $usuarioId)
            ->exists();

        if (! $enDirectorio) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $pendientes = Plano::where('obra_id', $obraTc->id)
            ->where(function ($query) {
                $query->whereNull('descripcion')->orWhereNull('subgrupo_id');
            })
            ->with('grupo')
            ->orderBy('created_at')
            ->get();

        $subgruposObra = PlanoSubgrupo::where('obra_id', $obraTc->id)
            ->orderBy('descripcion')
            ->pluck('descripcion')
            ->unique()
            ->values();

        $nombresPlanosObra = Plano::where('obra_id', $obraTc->id)
            ->whereNotNull('descripcion')
            ->orderBy('descripcion')
            ->pluck('descripcion')
            ->unique()
            ->values();

        return view('planos_tc.aprobar', compact('obraTc', 'pendientes', 'subgruposObra', 'nombresPlanosObra'));
    }

    public function aprobarStore(Request $request, ObraTc $obraTc, Plano $plano)
    {
        $request->validate([
            'nombre' => [
                'required', 'string', 'max:255',
                Rule::unique('planos', 'descripcion')->where('obra_id', $obraTc->id)->ignore($plano->id),
            ],
            'subgrupo' => 'required|string|max:255',
            'rotacion' => 'nullable|integer|in:0,90,180,270',
        ], [
            'nombre.unique' => 'Ya existe un plano con ese nombre en esta obra.',
        ]);

        $subgrupo = PlanoSubgrupo::firstOrCreate(
            [
                'obra_id' => $obraTc->id,
                'grupo_id' => $plano->grupo_id,
                'descripcion' => $request->subgrupo,
            ],
            [
                'usuario_id' => session('usuario_id'),
            ]
        );

        $plano->update([
            'descripcion' => $request->nombre,
            'subgrupo_id' => $subgrupo->id,
            'rotacion' => $request->rotacion ?? 0,
        ]);

        return response()->json(['success' => true]);
    }
}
