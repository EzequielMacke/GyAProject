<?php

namespace App\Http\Controllers;

use App\Models\CarbonatacionTc;
use App\Models\ClorurosTc;
use App\Models\DirectorioTc;
use App\Models\EsclerometriaTc;
use App\Models\MedicionFisuraTc;
use App\Models\NivelPlaTc;
use App\Models\ObraTc;
use App\Models\ResistividadTc;
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

        $cloruros = ClorurosTc::withCount('detalles')
            ->where('obra_tc_id', $obraTc->id)
            ->first();

        $medicionFisura = MedicionFisuraTc::withCount('detalles')
            ->where('obra_tc_id', $obraTc->id)
            ->first();

        $resistividad = ResistividadTc::withCount('detalles')
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
            [
                'codigo' => 'cloruros',
                'nombre' => 'Cloruros',
                'icono' => 'fa-vial',
                'ruta' => route('planilla_tc.cloruros', $obraTc->id),
                'ruta_crear' => route('planilla_tc.cloruros.crear', $obraTc->id),
                'ruta_eliminar' => route('planilla_tc.cloruros.eliminar', $obraTc->id),
                'ruta_reporte' => route('planilla_tc.cloruros.reporte', $obraTc->id),
                'cargada' => (bool) $cloruros,
                'resumen' => $cloruros
                    ? ($cloruros->fecha
                        ? $cloruros->detalles_count.' '.($cloruros->detalles_count === 1 ? 'punto ensayado' : 'puntos ensayados').' · '.$cloruros->fecha->format('d/m/Y')
                        : 'Todavía sin datos cargados')
                    : null,
            ],
            [
                'codigo' => 'medicion_fisura',
                'nombre' => 'Medición de Fisuras',
                'icono' => 'fa-bolt',
                'ruta' => route('planilla_tc.medicion_fisura', $obraTc->id),
                'ruta_crear' => route('planilla_tc.medicion_fisura.crear', $obraTc->id),
                'ruta_eliminar' => route('planilla_tc.medicion_fisura.eliminar', $obraTc->id),
                'ruta_reporte' => route('planilla_tc.medicion_fisura.reporte', $obraTc->id),
                'cargada' => (bool) $medicionFisura,
                'resumen' => $medicionFisura
                    ? ($medicionFisura->fecha
                        ? $medicionFisura->detalles_count.' '.($medicionFisura->detalles_count === 1 ? 'fisura registrada' : 'fisuras registradas').' · '.$medicionFisura->fecha->format('d/m/Y')
                        : 'Todavía sin datos cargados')
                    : null,
            ],
            [
                'codigo' => 'resistividad',
                'nombre' => 'Resistividad',
                'icono' => 'fa-plug',
                'ruta' => route('planilla_tc.resistividad', $obraTc->id),
                'ruta_crear' => route('planilla_tc.resistividad.crear', $obraTc->id),
                'ruta_eliminar' => route('planilla_tc.resistividad.eliminar', $obraTc->id),
                'ruta_reporte' => route('planilla_tc.resistividad.reporte', $obraTc->id),
                'cargada' => (bool) $resistividad,
                'resumen' => $resistividad
                    ? ($resistividad->fecha
                        ? $resistividad->detalles_count.' '.($resistividad->detalles_count === 1 ? 'punto ensayado' : 'puntos ensayados').' · '.$resistividad->fecha->format('d/m/Y')
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
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $datosEsclerometria = $esclerometria ? [
            'puntos' => $esclerometria->detalles->map(function ($detalle) {
                return [
                    'elemento' => $detalle->elemento,
                    'nivel' => $detalle->nivel?->descripcion,
                    'direccion' => $detalle->direccion,
                    'impactos' => $detalle->impactos,
                ];
            })->values()->all(),
        ] : null;

        $niveles = NivelPlaTc::where('obra_tc_id', $obraTc->id)
            ->orderBy('descripcion')
            ->pluck('descripcion');

        $puedeEditar = app(PermisoService::class)->puede('ens_tc', 'editar');

        return view('planilla_tc.esclerometria_pla', compact('obraTc', 'esclerometria', 'datosEsclerometria', 'niveles', 'puedeEditar'));
    }

    public function resistividad(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $resistividad = ResistividadTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $datosResistividad = $resistividad ? [
            'puntos' => $resistividad->detalles->map(function ($detalle) {
                return [
                    'elemento' => $detalle->elemento,
                    'nivel' => $detalle->nivel?->descripcion,
                    'lecturas' => $detalle->lecturas,
                    'temperatura' => $detalle->temperatura,
                ];
            })->values()->all(),
        ] : null;

        $niveles = NivelPlaTc::where('obra_tc_id', $obraTc->id)
            ->orderBy('descripcion')
            ->pluck('descripcion');

        $puedeEditar = app(PermisoService::class)->puede('ens_tc', 'editar');

        return view('planilla_tc.resistividad_pla', compact('obraTc', 'resistividad', 'datosResistividad', 'niveles', 'puedeEditar'));
    }

    public function ultrasonidoIndirecto(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $ultrasonidoIndirecto = UltrasonidoIndirectoTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $datosUltrasonidoIndirecto = $ultrasonidoIndirecto ? [
            'puntos' => $ultrasonidoIndirecto->detalles->map(function ($detalle) {
                return [
                    'elemento' => $detalle->elemento,
                    'nivel' => $detalle->nivel?->descripcion,
                    'velocidades' => $detalle->velocidades,
                ];
            })->values()->all(),
        ] : null;

        $niveles = NivelPlaTc::where('obra_tc_id', $obraTc->id)
            ->orderBy('descripcion')
            ->pluck('descripcion');

        $puedeEditar = app(PermisoService::class)->puede('ens_tc', 'editar');

        return view('planilla_tc.ultrasonido_indirecto_pla', compact('obraTc', 'ultrasonidoIndirecto', 'datosUltrasonidoIndirecto', 'niveles', 'puedeEditar'));
    }

    public function carbonatacion(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $carbonatacion = CarbonatacionTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $datosCarbonatacion = $carbonatacion ? [
            'puntos' => $carbonatacion->detalles->map(function ($detalle) {
                return [
                    'elemento' => $detalle->elemento,
                    'nivel' => $detalle->nivel?->descripcion,
                    'recubrimiento' => $detalle->recubrimiento,
                    'espesores' => $detalle->espesores,
                ];
            })->values()->all(),
        ] : null;

        $niveles = NivelPlaTc::where('obra_tc_id', $obraTc->id)
            ->orderBy('descripcion')
            ->pluck('descripcion');

        $puedeEditar = app(PermisoService::class)->puede('ens_tc', 'editar');

        return view('planilla_tc.carbonatacion_pla', compact('obraTc', 'carbonatacion', 'datosCarbonatacion', 'niveles', 'puedeEditar'));
    }

    public function cloruros(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $cloruros = ClorurosTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $datosCloruros = $cloruros ? [
            'puntos' => $cloruros->detalles->map(function ($detalle) {
                return [
                    'elemento' => $detalle->elemento,
                    'nivel' => $detalle->nivel?->descripcion,
                    'recubrimiento' => $detalle->recubrimiento,
                    'espesores' => $detalle->espesores,
                ];
            })->values()->all(),
        ] : null;

        $niveles = NivelPlaTc::where('obra_tc_id', $obraTc->id)
            ->orderBy('descripcion')
            ->pluck('descripcion');

        $puedeEditar = app(PermisoService::class)->puede('ens_tc', 'editar');

        return view('planilla_tc.cloruros_pla', compact('obraTc', 'cloruros', 'datosCloruros', 'niveles', 'puedeEditar'));
    }

    public function medicionFisura(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $medicionFisura = MedicionFisuraTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $datosMedicionFisura = $medicionFisura ? [
            'fecha' => optional($medicionFisura->fecha)->format('Y-m-d'),
            'puntos' => $medicionFisura->detalles->map(function ($detalle) {
                return [
                    'elemento' => $detalle->elemento,
                    'nivel' => $detalle->nivel?->descripcion,
                    'ancho' => $detalle->ancho,
                    'espesores' => $detalle->espesores,
                    'profundidades' => $detalle->profundidades,
                    'pasante' => $detalle->pasante,
                ];
            })->values()->all(),
        ] : null;

        $niveles = NivelPlaTc::where('obra_tc_id', $obraTc->id)
            ->orderBy('descripcion')
            ->pluck('descripcion');

        $puedeEditar = app(PermisoService::class)->puede('ens_tc', 'editar');

        return view('planilla_tc.medicion_fisura_pla', compact('obraTc', 'medicionFisura', 'datosMedicionFisura', 'niveles', 'puedeEditar'));
    }

    public function reporteEsclerometria(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $esclerometria = EsclerometriaTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $puntos = $esclerometria
            ? $esclerometria->detalles->values()->map(function ($detalle, $i) {
                return [
                    'nivel' => $detalle->nivel?->descripcion ?: '-',
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
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $puntos = $ultrasonidoIndirecto
            ? $ultrasonidoIndirecto->detalles->values()->map(function ($detalle, $i) {
                $velocidad = $detalle->promedio !== null ? round((float) $detalle->promedio) : null;

                return [
                    'nivel' => $detalle->nivel?->descripcion ?: '-',
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
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $puntos = $carbonatacion
            ? $carbonatacion->detalles->values()->map(function ($detalle, $i) {
                $porcentaje = $detalle->porcentaje_afectado !== null ? round((float) $detalle->porcentaje_afectado, 1) : null;

                return [
                    'nivel' => $detalle->nivel?->descripcion ?: '-',
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

    public function reporteCloruros(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $cloruros = ClorurosTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $puntos = $cloruros
            ? $cloruros->detalles->values()->map(function ($detalle, $i) {
                $porcentaje = $detalle->porcentaje_afectado !== null ? round((float) $detalle->porcentaje_afectado, 1) : null;

                return [
                    'nivel' => $detalle->nivel?->descripcion ?: '-',
                    'identificacion' => 'CL'.($i + 1),
                    'elemento' => $detalle->elemento ?: '-',
                    'recubrimiento' => $detalle->recubrimiento !== null ? number_format((float) $detalle->recubrimiento, 2, ',', '.') : '-',
                    'espesor_cloruros' => $detalle->espesor_cloruros !== null ? number_format((float) $detalle->espesor_cloruros, 2, ',', '.') : '-',
                    'porcentaje_afectado' => $porcentaje !== null ? number_format($porcentaje, 1, ',', '.').'%' : '-',
                ];
            })
            : collect();

        return response()->json([
            'obra' => $obraTc->descripcion,
            'fecha' => $cloruros?->fecha?->format('d/m/Y'),
            'puntos' => $puntos,
        ]);
    }

    public function reporteMedicionFisura(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $medicionFisura = MedicionFisuraTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $puntos = $medicionFisura
            ? $medicionFisura->detalles->values()->map(function ($detalle, $i) {
                return [
                    'nivel' => $detalle->nivel?->descripcion ?: '-',
                    'identificacion' => 'F'.($i + 1),
                    'elemento' => $detalle->elemento ?: '-',
                    'ancho' => $detalle->ancho !== null ? number_format((float) $detalle->ancho, 2, ',', '.') : '-',
                    'promedio_espesor' => $detalle->promedio_espesor !== null ? number_format((float) $detalle->promedio_espesor, 2, ',', '.') : '-',
                    'promedio_profundidad' => $detalle->promedio_profundidad !== null ? number_format((float) $detalle->promedio_profundidad, 2, ',', '.') : '-',
                    'pasante' => $detalle->pasante ? 'Sí' : 'No',
                    'porcentaje_afectado' => $detalle->porcentaje_afectado !== null ? number_format(round((float) $detalle->porcentaje_afectado), 0, '', '.').'%' : '-',
                ];
            })
            : collect();

        return response()->json([
            'obra' => $obraTc->descripcion,
            'fecha' => $medicionFisura?->fecha?->format('d/m/Y'),
            'puntos' => $puntos,
        ]);
    }

    public function reporteResistividad(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $resistividad = ResistividadTc::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $puntos = $resistividad
            ? $resistividad->detalles->values()->map(function ($detalle, $i) {
                return [
                    'nivel' => $detalle->nivel?->descripcion ?: '-',
                    'identificacion' => 'R'.($i + 1),
                    'elemento' => $detalle->elemento ?: '-',
                    'resistividad' => $detalle->resistividad_final !== null ? number_format((float) $detalle->resistividad_final, 2, ',', '.') : '-',
                ];
            })
            : collect();

        return response()->json([
            'obra' => $obraTc->descripcion,
            'fecha' => $resistividad?->fecha?->format('d/m/Y'),
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
            $velocidad >= 3500 => 'Buena',
            $velocidad >= 3000 => 'Dudosa',
            $velocidad >= 2000 => 'Pobre',
            default => 'Muy pobre',
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

    public function crearCloruros(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $cloruros = ClorurosTc::firstOrCreate(
            ['obra_tc_id' => $obraTc->id],
            ['usuario_id' => session('usuario_id')]
        );

        return response()->json(['ok' => true, 'id' => $cloruros->id]);
    }

    public function crearMedicionFisura(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $medicionFisura = MedicionFisuraTc::firstOrCreate(
            ['obra_tc_id' => $obraTc->id],
            ['usuario_id' => session('usuario_id')]
        );

        return response()->json(['ok' => true, 'id' => $medicionFisura->id]);
    }

    public function crearResistividad(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $resistividad = ResistividadTc::firstOrCreate(
            ['obra_tc_id' => $obraTc->id],
            ['usuario_id' => session('usuario_id')]
        );

        return response()->json(['ok' => true, 'id' => $resistividad->id]);
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
            'puntos.*.nivel' => 'nullable|string|max:255',
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
                    'nivel_pla_tc_id' => $this->resolverNivelId($obraTc, $punto['nivel'] ?? null),
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
            'puntos.*.nivel' => 'nullable|string|max:255',
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
                    'nivel_pla_tc_id' => $this->resolverNivelId($obraTc, $punto['nivel'] ?? null),
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
            'puntos.*.nivel' => 'nullable|string|max:255',
            'puntos.*.recubrimiento' => 'nullable|numeric',
            'puntos.*.espesores' => 'array',
            'puntos.*.espesores.*' => 'nullable|numeric',
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
                    'nivel_pla_tc_id' => $this->resolverNivelId($obraTc, $punto['nivel'] ?? null),
                    'recubrimiento' => $punto['recubrimiento'] ?? null,
                    'espesores' => $punto['espesores'] ?? [],
                    'espesor_carbonatado' => $punto['espesor_carbonatado'] ?? null,
                    'porcentaje_afectado' => $punto['porcentaje_afectado'] ?? null,
                ]);
            }
        });

        return response()->json(['ok' => true]);
    }

    public function guardarCloruros(Request $request, ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $data = $request->validate([
            'fecha' => 'required|date',
            'puntos' => 'array',
            'puntos.*.elemento' => 'nullable|string|max:255',
            'puntos.*.nivel' => 'nullable|string|max:255',
            'puntos.*.recubrimiento' => 'nullable|numeric',
            'puntos.*.espesores' => 'array',
            'puntos.*.espesores.*' => 'nullable|numeric',
            'puntos.*.espesor_cloruros' => 'nullable|numeric',
            'puntos.*.porcentaje_afectado' => 'nullable|numeric',
        ]);

        $usuarioId = session('usuario_id');

        DB::transaction(function () use ($data, $obraTc, $usuarioId) {
            $cloruros = ClorurosTc::updateOrCreate(
                ['obra_tc_id' => $obraTc->id],
                [
                    'usuario_id' => $usuarioId,
                    'fecha' => $data['fecha'],
                ]
            );

            $cloruros->detalles()->delete();

            foreach ($data['puntos'] ?? [] as $punto) {
                $cloruros->detalles()->create([
                    'elemento' => $punto['elemento'] ?? null,
                    'nivel_pla_tc_id' => $this->resolverNivelId($obraTc, $punto['nivel'] ?? null),
                    'recubrimiento' => $punto['recubrimiento'] ?? null,
                    'espesores' => $punto['espesores'] ?? [],
                    'espesor_cloruros' => $punto['espesor_cloruros'] ?? null,
                    'porcentaje_afectado' => $punto['porcentaje_afectado'] ?? null,
                ]);
            }
        });

        return response()->json(['ok' => true]);
    }

    public function guardarMedicionFisura(Request $request, ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $data = $request->validate([
            'fecha' => 'nullable|date',
            'puntos' => 'array',
            'puntos.*.elemento' => 'nullable|string|max:255',
            'puntos.*.nivel' => 'nullable|string|max:255',
            'puntos.*.ancho' => 'nullable|numeric',
            'puntos.*.espesores' => 'array',
            'puntos.*.espesores.*' => 'nullable|numeric',
            'puntos.*.profundidades' => 'array',
            'puntos.*.profundidades.*' => 'nullable|numeric',
            'puntos.*.pasante' => 'nullable|boolean',
            'puntos.*.promedio_espesor' => 'nullable|numeric',
            'puntos.*.promedio_profundidad' => 'nullable|numeric',
            'puntos.*.porcentaje_afectado' => 'nullable|numeric',
        ]);

        $usuarioId = session('usuario_id');

        DB::transaction(function () use ($data, $obraTc, $usuarioId) {
            $medicionFisura = MedicionFisuraTc::updateOrCreate(
                ['obra_tc_id' => $obraTc->id],
                [
                    'usuario_id' => $usuarioId,
                    'fecha' => $data['fecha'] ?? null,
                ]
            );

            $medicionFisura->detalles()->delete();

            foreach ($data['puntos'] ?? [] as $punto) {
                $medicionFisura->detalles()->create([
                    'elemento' => $punto['elemento'] ?? null,
                    'nivel_pla_tc_id' => $this->resolverNivelId($obraTc, $punto['nivel'] ?? null),
                    'ancho' => $punto['ancho'] ?? null,
                    'espesores' => $punto['espesores'] ?? [],
                    'profundidades' => $punto['profundidades'] ?? [],
                    'pasante' => $punto['pasante'] ?? false,
                    'promedio_espesor' => $punto['promedio_espesor'] ?? null,
                    'promedio_profundidad' => $punto['promedio_profundidad'] ?? null,
                    'porcentaje_afectado' => $punto['porcentaje_afectado'] ?? null,
                ]);
            }
        });

        return response()->json(['ok' => true]);
    }

    public function guardarResistividad(Request $request, ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $data = $request->validate([
            'fecha' => 'required|date',
            'puntos' => 'array',
            'puntos.*.elemento' => 'nullable|string|max:255',
            'puntos.*.nivel' => 'nullable|string|max:255',
            'puntos.*.lecturas' => 'array',
            'puntos.*.lecturas.*' => 'nullable|numeric',
            'puntos.*.temperatura' => 'nullable|numeric',
            'puntos.*.promedio' => 'nullable|numeric',
            'puntos.*.correccion' => 'nullable|numeric',
            'puntos.*.resistividad_final' => 'nullable|numeric',
            'puntos.*.velocidad_corrosion' => 'nullable|string|max:255',
        ]);

        $usuarioId = session('usuario_id');

        DB::transaction(function () use ($data, $obraTc, $usuarioId) {
            $resistividad = ResistividadTc::updateOrCreate(
                ['obra_tc_id' => $obraTc->id],
                [
                    'usuario_id' => $usuarioId,
                    'fecha' => $data['fecha'],
                ]
            );

            $resistividad->detalles()->delete();

            foreach ($data['puntos'] ?? [] as $punto) {
                $resistividad->detalles()->create([
                    'elemento' => $punto['elemento'] ?? null,
                    'nivel_pla_tc_id' => $this->resolverNivelId($obraTc, $punto['nivel'] ?? null),
                    'lecturas' => $punto['lecturas'] ?? [],
                    'temperatura' => $punto['temperatura'] ?? null,
                    'promedio' => $punto['promedio'] ?? null,
                    'correccion' => $punto['correccion'] ?? null,
                    'resistividad_final' => $punto['resistividad_final'] ?? null,
                    'velocidad_corrosion' => $punto['velocidad_corrosion'] ?? null,
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

    public function eliminarCloruros(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        ClorurosTc::where('obra_tc_id', $obraTc->id)->delete();

        return response()->json(['ok' => true]);
    }

    public function eliminarMedicionFisura(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        MedicionFisuraTc::where('obra_tc_id', $obraTc->id)->delete();

        return response()->json(['ok' => true]);
    }

    public function eliminarResistividad(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        ResistividadTc::where('obra_tc_id', $obraTc->id)->delete();

        return response()->json(['ok' => true]);
    }

    private function tieneAccesoAObra(ObraTc $obraTc): bool
    {
        return DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', session('usuario_id'))
            ->exists();
    }

    private function resolverNivelId(ObraTc $obraTc, ?string $nivel): ?int
    {
        $descripcion = trim((string) $nivel);

        if ($descripcion === '') {
            return null;
        }

        return NivelPlaTc::firstOrCreate(
            ['obra_tc_id' => $obraTc->id, 'descripcion' => $descripcion]
        )->id;
    }
}
