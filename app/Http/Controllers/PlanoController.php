<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTc;
use App\Models\FotoTc;
use App\Models\ObraTc;
use App\Models\Plano;
use App\Models\PlanoActividad;
use App\Models\PlanoGrupo;
use App\Models\PlanoPapelera;
use App\Models\PlanoSubgrupo;
use App\Models\PlanoTcActividad;
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

        $actividadSubidas = $planos->map(function (Plano $plano) {
            $usuarioActividad = $plano->usuario;

            return [
                'accion' => 'subida',
                'usuario' => $usuarioActividad ? ($usuarioActividad->nombre_completo ?: $usuarioActividad->nombre) : 'Usuario desconocido',
                'detalle' => $plano->descripcion,
                'fecha' => $plano->created_at,
            ];
        });

        $actividadEdiciones = PlanoTcActividad::where('obra_id', $obraTc->id)
            ->with('usuario')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(function (PlanoTcActividad $item) {
                $usuarioActividad = $item->usuario;

                return [
                    'accion' => $item->accion,
                    'usuario' => $usuarioActividad ? ($usuarioActividad->nombre_completo ?: $usuarioActividad->nombre) : 'Usuario desconocido',
                    'detalle' => $item->detalle,
                    'fecha' => $item->created_at,
                ];
            });

        $actividad = $actividadSubidas->concat($actividadEdiciones)
            ->sortByDesc('fecha')
            ->take(40)
            ->values();

        return view('planos_tc.index', compact('obraTc', 'planos', 'arbol', 'pendientesCount', 'gruposExistentes', 'actividad'));
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

                if (($item['tool'] ?? null) === 'foto') {
                    foreach ($item['fotos'] ?? [] as $foto) {
                        $this->registrarFotoTc($plano, $item, $foto, $usuarioId);
                    }
                }
            }

            foreach ($idsEliminados as $id) {
                if (! $trazos->has($id)) continue; // ya lo había borrado otro guardado
                $item = $trazos->pull($id);
                $registros[] = $this->filaActividad($plano->id, $usuarioId, 'eliminar', $item, $ahora);

                if (! empty($item['fotos'])) {
                    FotoTc::where('plano_tc_id', $plano->id)
                        ->whereIn('archivo', $item['fotos'])
                        ->delete();
                }
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

                foreach ($fotosAgregadas as $foto) {
                    $this->registrarFotoTc($plano, $item, $foto, $usuarioId);
                }
                if (! empty($fotosEliminadas)) {
                    FotoTc::where('plano_tc_id', $plano->id)
                        ->whereIn('archivo', $fotosEliminadas)
                        ->delete();
                }

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

    /**
     * Para el tool "foto" (pin de fotografía suelto) no hay una
     * clasificación de daño asociada, así que se usa la etiqueta que le
     * haya puesto el usuario. Para el resto de las herramientas (fisura,
     * corrosión, etc.) la clasificación es el tool en sí.
     */
    private function registrarFotoTc(Plano $plano, array $item, string $foto, ?int $usuarioId): void
    {
        $tool = $item['tool'] ?? null;

        FotoTc::create([
            'obra_tc_id' => $plano->obra_id,
            'plano_tc_id' => $plano->id,
            'clasificacion' => $tool === 'foto' ? ($item['etiqueta'] ?? null) : $tool,
            'archivo' => $foto,
            'usuario_id' => $usuarioId,
        ]);
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

    public function actualizarGrupo(Request $request, ObraTc $obraTc, PlanoGrupo $grupo)
    {
        if ($grupo->obra_id !== $obraTc->id) {
            abort(404);
        }

        $request->validate([
            'descripcion' => [
                'required', 'string', 'max:255',
                Rule::unique('plano_grupo', 'descripcion')->where('obra_id', $obraTc->id)->ignore($grupo->id),
            ],
        ], [
            'descripcion.unique' => 'Ya existe un grupo con ese nombre en esta obra.',
        ]);

        $nombreAnterior = $grupo->descripcion;

        if ($nombreAnterior !== $request->descripcion) {
            $grupo->update(['descripcion' => $request->descripcion]);

            PlanoTcActividad::create([
                'obra_id' => $obraTc->id,
                'usuario_id' => session('usuario_id'),
                'accion' => 'grupo',
                'detalle' => "'{$nombreAnterior}' → '{$request->descripcion}'",
                'created_at' => now(),
            ]);
        }

        return redirect()->route('planos_tc.index', $obraTc->id)->with('success', 'Grupo actualizado correctamente.');
    }

    public function actualizarSubgrupo(Request $request, ObraTc $obraTc, PlanoSubgrupo $subgrupo)
    {
        if ($subgrupo->obra_id !== $obraTc->id) {
            abort(404);
        }

        $request->validate([
            'descripcion' => [
                'required', 'string', 'max:255',
                Rule::unique('plano_subgrupo', 'descripcion')
                    ->where('obra_id', $obraTc->id)
                    ->where('grupo_id', $subgrupo->grupo_id)
                    ->ignore($subgrupo->id),
            ],
        ], [
            'descripcion.unique' => 'Ya existe un subgrupo con ese nombre en este grupo.',
        ]);

        $nombreAnterior = $subgrupo->descripcion;

        if ($nombreAnterior !== $request->descripcion) {
            $subgrupo->update(['descripcion' => $request->descripcion]);

            PlanoTcActividad::create([
                'obra_id' => $obraTc->id,
                'usuario_id' => session('usuario_id'),
                'accion' => 'subgrupo',
                'detalle' => "'{$nombreAnterior}' → '{$request->descripcion}'",
                'created_at' => now(),
            ]);
        }

        return redirect()->route('planos_tc.index', $obraTc->id)->with('success', 'Subgrupo actualizado correctamente.');
    }

    public function actualizarPlano(Request $request, ObraTc $obraTc, Plano $plano)
    {
        if ($plano->obra_id !== $obraTc->id) {
            abort(404);
        }

        $request->validate([
            'descripcion' => [
                'required', 'string', 'max:255',
                Rule::unique('planos', 'descripcion')->where('obra_id', $obraTc->id)->ignore($plano->id),
            ],
        ], [
            'descripcion.unique' => 'Ya existe un plano con ese nombre en esta obra.',
        ]);

        $nombreAnterior = $plano->descripcion;

        if ($nombreAnterior !== $request->descripcion) {
            $plano->update(['descripcion' => $request->descripcion]);

            PlanoTcActividad::create([
                'obra_id' => $obraTc->id,
                'usuario_id' => session('usuario_id'),
                'accion' => 'plano',
                'detalle' => "'{$nombreAnterior}' → '{$request->descripcion}'",
                'created_at' => now(),
            ]);
        }

        return redirect()->route('planos_tc.index', $obraTc->id)->with('success', 'Plano actualizado correctamente.');
    }

    /**
     * Mueve el plano a la papelera: guarda una copia completa (incluido el
     * estado con sus trazos/fotos/ensayos/daños y un snapshot del registro
     * de actividad, que se perdería con el borrado en cascada) y recién
     * después borra la fila original. El PDF y las fotos en storage no se
     * tocan acá, quedan disponibles para una futura restauración.
     */
    public function eliminarPlano(ObraTc $obraTc, Plano $plano)
    {
        if ($plano->obra_id !== $obraTc->id) {
            abort(404);
        }

        $usuarioId = session('usuario_id');
        $descripcionPlano = $plano->descripcion ?? $plano->archivo_original ?? 'sin nombre';

        DB::transaction(function () use ($plano, $obraTc, $usuarioId) {
            $actividades = PlanoActividad::where('plano_id', $plano->id)
                ->orderBy('created_at')
                ->get()
                ->toArray();

            PlanoPapelera::create([
                'plano_id_original' => $plano->id,
                'descripcion' => $plano->descripcion,
                'obra_id' => $plano->obra_id,
                'grupo_id' => $plano->grupo_id,
                'subgrupo_id' => $plano->subgrupo_id,
                'archivo' => $plano->archivo,
                'archivo_original' => $plano->archivo_original,
                'usuario_id' => $plano->usuario_id,
                'rotacion' => $plano->rotacion,
                'estado' => $plano->estado,
                'actividades' => json_encode($actividades),
                'creado_originalmente_at' => $plano->created_at,
                'eliminado_por' => $usuarioId,
                'eliminado_at' => now(),
            ]);

            $plano->delete();
        });

        PlanoTcActividad::create([
            'obra_id' => $obraTc->id,
            'usuario_id' => $usuarioId,
            'accion' => 'eliminacion',
            'detalle' => $descripcionPlano,
            'created_at' => now(),
        ]);

        return redirect()->route('planos_tc.index', $obraTc->id)->with('success', 'Plano movido a la papelera.');
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
