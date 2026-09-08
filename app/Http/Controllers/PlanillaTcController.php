<?php

namespace App\Http\Controllers;

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

    private function tieneAccesoAObra(ObraTc $obraTc): bool
    {
        return DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', session('usuario_id'))
            ->exists();
    }
}
