<?php

namespace App\Http\Controllers;

use App\Models\CarbonatacionTc;
use App\Models\DirectorioTc;
use App\Models\EsclerometriaTc;
use App\Models\ObraTc;
use App\Models\UltrasonidoIndirectoTc;
use App\Services\PermisoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanillaTcController extends Controller
{
    public function index(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $esclerometria = EsclerometriaTc::withCount('detalles')
            ->where('obra_tc_id', $obraTc->id)
            ->first();

        $ultrasonidoIndirecto = UltrasonidoIndirectoTc::withCount('detalles')
            ->where('obra_tc_id', $obraTc->id)
            ->first();

        $carbonatacion = CarbonatacionTc::withCount('detalles')
            ->where('obra_tc_id', $obraTc->id)
            ->first();

        $tiposPlanillas = [
            [
                'codigo' => 'esclerometria',
                'nombre' => 'Esclerometría',
                'icono' => 'fa-hammer',
                'ruta' => route('planilla_tc.esclerometria', $obraTc->id),
                'ruta_crear' => route('planilla_tc.esclerometria.crear', $obraTc->id),
                'ruta_eliminar' => route('planilla_tc.esclerometria.eliminar', $obraTc->id),
                'ruta_reporte' => route('planilla_tc.esclerometria.reporte', $obraTc->id),
                'cargada' => (bool) $esclerometria,
                'resumen' => $esclerometria
                    ? ($esclerometria->fecha
                        ? $esclerometria->detalles_count.' '.($esclerometria->detalles_count === 1 ? 'punto ensayado' : 'puntos ensayados').' · '.$esclerometria->fecha->format('d/m/Y')
                        : 'Todavía sin datos cargados')
                    : null,
            ],
            [
                'codigo' => 'ultrasonido_indirecto',
                'nombre' => 'Ultrasonido Indirecto',
                'icono' => 'fa-wave-square',
                'ruta' => route('planilla_tc.ultrasonido_indirecto', $obraTc->id),
                'ruta_crear' => route('planilla_tc.ultrasonido_indirecto.crear', $obraTc->id),
                'ruta_eliminar' => route('planilla_tc.ultrasonido_indirecto.eliminar', $obraTc->id),
                'ruta_reporte' => route('planilla_tc.ultrasonido_indirecto.reporte', $obraTc->id),
                'cargada' => (bool) $ultrasonidoIndirecto,
                'resumen' => $ultrasonidoIndirecto
                    ? ($ultrasonidoIndirecto->fecha
                        ? $ultrasonidoIndirecto->detalles_count.' '.($ultrasonidoIndirecto->detalles_count === 1 ? 'punto ensayado' : 'puntos ensayados').' · '.$ultrasonidoIndirecto->fecha->format('d/m/Y')
                        : 'Todavía sin datos cargados')
                    : null,
            ],
            [
                'codigo' => 'carbonatacion',
                'nombre' => 'Carbonatación',
                'icono' => 'fa-flask',
                'ruta' => route('planilla_tc.carbonatacion', $obraTc->id),
                'ruta_crear' => route('planilla_tc.carbonatacion.crear', $obraTc->id),
                'ruta_eliminar' => route('planilla_tc.carbonatacion.eliminar', $obraTc->id),
                'ruta_reporte' => route('planilla_tc.carbonatacion.reporte', $obraTc->id),
                'cargada' => (bool) $carbonatacion,
                'resumen' => $carbonatacion
                    ? ($carbonatacion->fecha
                        ? $carbonatacion->detalles_count.' '.($carbonatacion->detalles_count === 1 ? 'punto ensayado' : 'puntos ensayados').' · '.$carbonatacion->fecha->format('d/m/Y')
                        : 'Todavía sin datos cargados')
                    : null,
            ],
        ];

        return view('planilla_tc.index', compact('obraTc', 'tiposPlanillas'));
    }

    public function esclerometria(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $esclerometria = EsclerometriaTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }])->where('obra_tc_id', $obraTc->id)->first();

        $datosEsclerometria = $esclerometria ? [
            'puntos' => $esclerometria->detalles->map(function ($detalle) {
                return [
                    'elemento' => $detalle->elemento,
                    'direccion' => $detalle->direccion,
                    'impactos' => $detalle->impactos,
                ];
            })->values()->all(),
        ] : null;

        $puedeEditar = app(PermisoService::class)->puede('ens_tc', 'editar');

        return view('planilla_tc.esclerometria_pla', compact('obraTc', 'esclerometria', 'datosEsclerometria', 'puedeEditar'));
    }

    public function ultrasonidoIndirecto(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $ultrasonidoIndirecto = UltrasonidoIndirectoTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }])->where('obra_tc_id', $obraTc->id)->first();

        $datosUltrasonidoIndirecto = $ultrasonidoIndirecto ? [
            'puntos' => $ultrasonidoIndirecto->detalles->map(function ($detalle) {
                return [
                    'elemento' => $detalle->elemento,
                    'velocidades' => $detalle->velocidades,
                ];
            })->values()->all(),
        ] : null;

        $puedeEditar = app(PermisoService::class)->puede('ens_tc', 'editar');

        return view('planilla_tc.ultrasonido_indirecto_pla', compact('obraTc', 'ultrasonidoIndirecto', 'datosUltrasonidoIndirecto', 'puedeEditar'));
    }

    public function carbonatacion(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $carbonatacion = CarbonatacionTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }])->where('obra_tc_id', $obraTc->id)->first();

        $datosCarbonatacion = $carbonatacion ? [
            'puntos' => $carbonatacion->detalles->map(function ($detalle) {
                return [
                    'elemento' => $detalle->elemento,
                    'recubrimiento' => $detalle->recubrimiento,
                    'espesor_carbonatado' => $detalle->espesor_carbonatado,
                ];
            })->values()->all(),
        ] : null;

        $puedeEditar = app(PermisoService::class)->puede('ens_tc', 'editar');

        return view('planilla_tc.carbonatacion_pla', compact('obraTc', 'carbonatacion', 'datosCarbonatacion', 'puedeEditar'));
    }

    public function reporteEsclerometria(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $esclerometria = EsclerometriaTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }])->where('obra_tc_id', $obraTc->id)->first();

        $puntos = $esclerometria
            ? $esclerometria->detalles->values()->map(function ($detalle, $i) {
                return [
                    'identificacion' => 'E'.($i + 1),
                    'elemento' => $detalle->elemento ?: '-',
                    'direccion' => $detalle->direccion.'°',
                    'n_final' => $detalle->n_final !== null ? number_format(round((float) $detalle->n_final), 0, '', '.') : '-',
                ];
            })
            : collect();

        return response()->json([
            'obra' => $obraTc->descripcion,
            'fecha' => $esclerometria?->fecha?->format('d/m/Y'),
            'puntos' => $puntos,
        ]);
    }

    public function reporteUltrasonidoIndirecto(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $ultrasonidoIndirecto = UltrasonidoIndirectoTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }])->where('obra_tc_id', $obraTc->id)->first();

        $puntos = $ultrasonidoIndirecto
            ? $ultrasonidoIndirecto->detalles->values()->map(function ($detalle, $i) {
                $velocidad = $detalle->promedio !== null ? round((float) $detalle->promedio) : null;

                return [
                    'identificacion' => 'U'.($i + 1),
                    'elemento' => $detalle->elemento ?: '-',
                    'velocidad' => $velocidad !== null ? number_format($velocidad, 0, '', '.') : '-',
                    'compactacion' => $this->compactacionHormigon($velocidad),
                ];
            })
            : collect();

        return response()->json([
            'obra' => $obraTc->descripcion,
            'puntos' => $puntos,
        ]);
    }

    public function reporteCarbonatacion(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $carbonatacion = CarbonatacionTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }])->where('obra_tc_id', $obraTc->id)->first();

        $puntos = $carbonatacion
            ? $carbonatacion->detalles->values()->map(function ($detalle, $i) {
                $porcentaje = $detalle->porcentaje_afectado !== null ? round((float) $detalle->porcentaje_afectado, 1) : null;

                return [
                    'identificacion' => 'C'.($i + 1),
                    'elemento' => $detalle->elemento ?: '-',
                    'recubrimiento' => $detalle->recubrimiento !== null ? number_format((float) $detalle->recubrimiento, 2, ',', '.') : '-',
                    'espesor_carbonatado' => $detalle->espesor_carbonatado !== null ? number_format((float) $detalle->espesor_carbonatado, 2, ',', '.') : '-',
                    'porcentaje_afectado' => $porcentaje !== null ? number_format($porcentaje, 1, ',', '.').'%' : '-',
                ];
            })
            : collect();

        return response()->json([
            'obra' => $obraTc->descripcion,
            'fecha' => $carbonatacion?->fecha?->format('d/m/Y'),
            'puntos' => $puntos,
        ]);
    }

    private function compactacionHormigon(?float $velocidad): string
    {
        if ($velocidad === null) {
            return '-';
        }

        return match (true) {
            $velocidad > 4500 => 'Excelente',
            $velocidad > 3600 => 'Buena',
            $velocidad > 3000 => 'Aceptable',
            $velocidad >= 2100 => 'Mala',
            default => 'Muy mala',
        };
    }

    public function crearEsclerometria(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $esclerometria = EsclerometriaTc::firstOrCreate(
            ['obra_tc_id' => $obraTc->id],
            ['usuario_id' => session('usuario_id')]
        );

        return response()->json(['ok' => true, 'id' => $esclerometria->id]);
    }

    public function crearUltrasonidoIndirecto(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $ultrasonidoIndirecto = UltrasonidoIndirectoTc::firstOrCreate(
            ['obra_tc_id' => $obraTc->id],
            ['usuario_id' => session('usuario_id')]
        );

        return response()->json(['ok' => true, 'id' => $ultrasonidoIndirecto->id]);
    }

    public function crearCarbonatacion(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $carbonatacion = CarbonatacionTc::firstOrCreate(
            ['obra_tc_id' => $obraTc->id],
            ['usuario_id' => session('usuario_id')]
        );

        return response()->json(['ok' => true, 'id' => $carbonatacion->id]);
    }

    public function guardarEsclerometria(Request $request, ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $data = $request->validate([
            'fecha' => 'required|date',
            'lectura_inicial_yunque' => 'nullable|numeric',
            'lectura_final_yunque' => 'nullable|numeric',
            'puntos' => 'array',
            'puntos.*.elemento' => 'nullable|string|max:255',
            'puntos.*.direccion' => 'nullable|integer',
            'puntos.*.impactos' => 'array',
            'puntos.*.impactos.*' => 'nullable|numeric',
            'puntos.*.promedio_inicial' => 'nullable|numeric',
            'puntos.*.validos' => 'nullable|integer',
            'puntos.*.promedio_final' => 'nullable|numeric',
            'puntos.*.n_corregido' => 'nullable|numeric',
            'puntos.*.correccion_angulo' => 'nullable|numeric',
            'puntos.*.n_final' => 'nullable|numeric',
        ]);

        $usuarioId = session('usuario_id');

        DB::transaction(function () use ($data, $obraTc, $usuarioId) {
            $esclerometria = EsclerometriaTc::updateOrCreate(
                ['obra_tc_id' => $obraTc->id],
                [
                    'usuario_id' => $usuarioId,
                    'fecha' => $data['fecha'],
                    'lectura_inicial_yunque' => $data['lectura_inicial_yunque'] ?? null,
                    'lectura_final_yunque' => $data['lectura_final_yunque'] ?? null,
                ]
            );

            $esclerometria->detalles()->delete();

            foreach ($data['puntos'] ?? [] as $punto) {
                $esclerometria->detalles()->create([
                    'elemento' => $punto['elemento'] ?? null,
                    'direccion' => $punto['direccion'] ?? 0,
                    'impactos' => $punto['impactos'] ?? [],
                    'promedio_inicial' => $punto['promedio_inicial'] ?? null,
                    'validos' => $punto['validos'] ?? null,
                    'promedio_final' => $punto['promedio_final'] ?? null,
                    'n_corregido' => $punto['n_corregido'] ?? null,
                    'correccion_angulo' => $punto['correccion_angulo'] ?? null,
                    'n_final' => $punto['n_final'] ?? null,
                ]);
            }
        });

        return response()->json(['ok' => true]);
    }

    public function guardarUltrasonidoIndirecto(Request $request, ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $data = $request->validate([
            'fecha' => 'required|date',
            'puntos' => 'array',
            'puntos.*.elemento' => 'nullable|string|max:255',
            'puntos.*.velocidades' => 'array',
            'puntos.*.velocidades.*' => 'nullable|numeric',
            'puntos.*.promedio' => 'nullable|numeric',
            'puntos.*.desviacion_estandar' => 'nullable|numeric',
            'puntos.*.coeficiente_variacion' => 'nullable|numeric',
            'puntos.*.repetir_ensayo' => 'nullable|boolean',
        ]);

        $usuarioId = session('usuario_id');

        DB::transaction(function () use ($data, $obraTc, $usuarioId) {
            $ultrasonidoIndirecto = UltrasonidoIndirectoTc::updateOrCreate(
                ['obra_tc_id' => $obraTc->id],
                [
                    'usuario_id' => $usuarioId,
                    'fecha' => $data['fecha'],
                ]
            );

            $ultrasonidoIndirecto->detalles()->delete();

            foreach ($data['puntos'] ?? [] as $punto) {
                $ultrasonidoIndirecto->detalles()->create([
                    'elemento' => $punto['elemento'] ?? null,
                    'velocidades' => $punto['velocidades'] ?? [],
                    'promedio' => $punto['promedio'] ?? null,
                    'desviacion_estandar' => $punto['desviacion_estandar'] ?? null,
                    'coeficiente_variacion' => $punto['coeficiente_variacion'] ?? null,
                    'repetir_ensayo' => $punto['repetir_ensayo'] ?? false,
                ]);
            }
        });

        return response()->json(['ok' => true]);
    }

    public function guardarCarbonatacion(Request $request, ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $data = $request->validate([
            'fecha' => 'required|date',
            'puntos' => 'array',
            'puntos.*.elemento' => 'nullable|string|max:255',
            'puntos.*.recubrimiento' => 'nullable|numeric',
            'puntos.*.espesor_carbonatado' => 'nullable|numeric',
            'puntos.*.porcentaje_afectado' => 'nullable|numeric',
        ]);

        $usuarioId = session('usuario_id');

        DB::transaction(function () use ($data, $obraTc, $usuarioId) {
            $carbonatacion = CarbonatacionTc::updateOrCreate(
                ['obra_tc_id' => $obraTc->id],
                [
                    'usuario_id' => $usuarioId,
                    'fecha' => $data['fecha'],
                ]
            );

            $carbonatacion->detalles()->delete();

            foreach ($data['puntos'] ?? [] as $punto) {
                $carbonatacion->detalles()->create([
                    'elemento' => $punto['elemento'] ?? null,
                    'recubrimiento' => $punto['recubrimiento'] ?? null,
                    'espesor_carbonatado' => $punto['espesor_carbonatado'] ?? null,
                    'porcentaje_afectado' => $punto['porcentaje_afectado'] ?? null,
                ]);
            }
        });

        return response()->json(['ok' => true]);
    }

    public function eliminarEsclerometria(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        EsclerometriaTc::where('obra_tc_id', $obraTc->id)->delete();

        return response()->json(['ok' => true]);
    }

    public function eliminarUltrasonidoIndirecto(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        UltrasonidoIndirectoTc::where('obra_tc_id', $obraTc->id)->delete();

        return response()->json(['ok' => true]);
    }

    public function eliminarCarbonatacion(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        CarbonatacionTc::where('obra_tc_id', $obraTc->id)->delete();

        return response()->json(['ok' => true]);
    }

    private function tieneAccesoAObra(ObraTc $obraTc): bool
    {
        return DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', session('usuario_id'))
            ->exists();
    }
}
