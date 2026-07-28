<?php

namespace App\Http\Controllers;

use App\Models\EstadoSituacion;
use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use App\Models\SituacionAvance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SituacionAvanceController extends Controller
{
    public function index()
    {
        $estados = EstadoSituacion::all();

        // Sync: crear registro en situacion_avances para cada presupuesto que no tenga uno
        $existingIds = SituacionAvance::pluck('presupuesto_aprobado_id');
        $missingPresupuestos = PresupuestoAprobado::whereNotIn('id', $existingIds)->pluck('id');

        foreach ($missingPresupuestos as $id) {
            SituacionAvance::create([
                'presupuesto_aprobado_id' => $id,
                'estado_situacion_id'     => 1,
            ]);
        }

        $presupuestos = PresupuestoAprobado::with([
            'obra',
            'situacionAvances',
            'facturasVenta',
            'recibosVenta',
        ])->latest()->get();

        $presupuestosPorEstado = $estados->mapWithKeys(function ($estado) use ($presupuestos) {
            $filtered = $presupuestos->filter(function ($p) use ($estado) {
                $ultimo = $p->situacionAvances->sortByDesc('id')->first();
                return $ultimo && $ultimo->estado_situacion_id == $estado->id;
            })->values();
            return [$estado->id => $filtered];
        });

        $puedeEditar  = app(\App\Services\PermisoService::class)->puede('sit_ava', 'agregar');
        $puedeVerFac  = app(\App\Services\PermisoService::class)->puede('fac', 'ver');

        $obras      = Obra::orderBy('nombre')->get();
        $tipoTrabajo = config('constantes.tipo_trabajo');
        $anios      = $presupuestos->filter(fn($p) => $p->situacionAvances->first()?->fecha_inicio)
                        ->map(fn($p) => \Carbon\Carbon::parse($p->situacionAvances->sortByDesc('id')->first()->fecha_inicio)->year)
                        ->unique()->sort()->values();

        $montoMin = (int) $presupuestos->min('monto_total');
        $montoMax = (int) $presupuestos->max('monto_total');

        return view('situacion_avance.index', compact('estados', 'presupuestosPorEstado', 'puedeEditar', 'puedeVerFac', 'obras', 'tipoTrabajo', 'anios', 'montoMin', 'montoMax'));
    }

    public function update(Request $request, $id)
    {
        $avance = SituacionAvance::findOrFail($id);

        $data = $request->only([
            'fecha_inicio',
            'fecha_fin',
            'plazo',
            'observacion',
            'estado_situacion_id',
        ]);

        // Si se carga fecha_inicio y el estado es "Aprobado" (1), pasar a "Agendado" automáticamente
        if (!empty($data['fecha_inicio']) && !isset($data['estado_situacion_id'])) {
            $agendado = EstadoSituacion::where('descripcion', 'Agendado')->first();
            if ($agendado && $avance->estado_situacion_id == 1) {
                $data['estado_situacion_id'] = $agendado->id;
            }
        }

        // Si cambia a "Finalizado" y no trae fecha_fin, asignar hoy
        if (isset($data['estado_situacion_id'])) {
            $finalizado = EstadoSituacion::where('descripcion', 'Finalizado')->first();
            if ($finalizado && $data['estado_situacion_id'] == $finalizado->id && empty($data['fecha_fin'])) {
                $data['fecha_fin'] = now()->toDateString();
            }
        }

        $avance->update($data);

        return back()->with('success', 'Situación de avance actualizada correctamente.');
    }

    public function report(Request $request)
    {
        $estados     = EstadoSituacion::all();
        $obras       = Obra::orderBy('nombre')->get();
        $tipoTrabajo = config('constantes.tipo_trabajo');

        $presupuestos = PresupuestoAprobado::with([
            'obra',
            'situacionAvances.estadoSituacion',
            'facturasVenta',
            'recibosVenta',
            'controlGasto',
        ])->latest()->get();

        $anios = $presupuestos->filter(fn ($p) => $p->situacionAvances->first()?->fecha_inicio)
            ->map(fn ($p) => \Carbon\Carbon::parse($p->situacionAvances->sortByDesc('id')->first()->fecha_inicio)->year)
            ->unique()->sort()->values();

        $montoMin = (int) $presupuestos->min('monto_total');
        $montoMax = (int) $presupuestos->max('monto_total');

        $filas = $this->filasReporte($presupuestos, $request);

        return view('situacion_avance.report', compact('estados', 'obras', 'tipoTrabajo', 'anios', 'montoMin', 'montoMax', 'filas'));
    }

    public function reportePdf(Request $request)
    {
        $presupuestos = PresupuestoAprobado::with([
            'obra',
            'situacionAvances.estadoSituacion',
            'facturasVenta',
            'recibosVenta',
            'controlGasto',
        ])->latest()->get();

        $filas = $this->filasReporte($presupuestos, $request);

        $totales = [
            'facturado'   => $filas->sum('facturado'),
            'cobrado'     => $filas->sum('cobrado'),
            'totalGastos' => $filas->sum('totalGastos'),
        ];

        $pdf = Pdf::loadView('situacion_avance.report_pdf', compact('filas', 'totales'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('reporte_situacion_avance_' . now()->format('Ymd_His') . '.pdf');
    }

    private function filasReporte(Collection $presupuestos, Request $request): Collection
    {
        $obraId   = $request->input('obra');
        $mes      = $request->input('mes');
        $anio     = $request->input('anio');
        $mesFin   = $request->input('mes_fin');
        $anioFin  = $request->input('anio_fin');
        $tipo     = $request->input('tipo');
        $estadoId = $request->input('estado');
        $facMin   = (int) $request->input('fac_min', 0);
        $facMax   = (int) $request->input('fac_max', 100);
        $cobMin   = (int) $request->input('cob_min', 0);
        $cobMax   = (int) $request->input('cob_max', 100);
        $montoMin = $request->filled('monto_min') ? (float) $request->input('monto_min') : null;
        $montoMax = $request->filled('monto_max') ? (float) $request->input('monto_max') : null;

        return $presupuestos->map(function ($presupuesto) {
            $avance      = $presupuesto->situacionAvances->sortByDesc('id')->first();
            $monto       = (float) $presupuesto->monto_total;
            $facturado   = $presupuesto->facturasVenta->sum('monto');
            $cobrado     = $presupuesto->recibosVenta->sum('monto');
            $gasto       = $presupuesto->controlGasto;
            $totalGastos = $gasto
                ? (float) (($gasto->ingenieros ?? 0) + ($gasto->tecnicos ?? 0) + ($gasto->mano_obra ?? 0) + ($gasto->otros ?? 0))
                : 0;

            return (object) [
                'presupuesto' => $presupuesto,
                'avance'      => $avance,
                'monto'       => $monto,
                'facturado'   => $facturado,
                'cobrado'     => $cobrado,
                'pctFac'      => $monto > 0 ? min(100, round($facturado / $monto * 100)) : 0,
                'pctCob'      => $facturado > 0 ? min(100, round($cobrado / $facturado * 100)) : 0,
                'totalGastos' => $totalGastos,
                'estado'      => $avance?->estadoSituacion?->descripcion ?? '—',
            ];
        })->filter(function ($fila) use ($obraId, $mes, $anio, $mesFin, $anioFin, $tipo, $estadoId, $montoMin, $montoMax, $facMin, $facMax, $cobMin, $cobMax) {
            $p      = $fila->presupuesto;
            $inicio = $fila->avance?->fecha_inicio ? \Carbon\Carbon::parse($fila->avance->fecha_inicio) : null;
            $fin    = $fila->avance?->fecha_fin ? \Carbon\Carbon::parse($fila->avance->fecha_fin) : null;

            return (!$obraId   || $p->obra_id == $obraId)
                && (!$tipo     || $p->tipo_trabajo == $tipo)
                && (!$estadoId || $fila->avance?->estado_situacion_id == $estadoId)
                && (!$mes      || $inicio?->month == $mes)
                && (!$anio     || $inicio?->year == $anio)
                && (!$mesFin   || $fin?->month == $mesFin)
                && (!$anioFin  || $fin?->year == $anioFin)
                && (is_null($montoMin) || $fila->monto >= $montoMin)
                && (is_null($montoMax) || $fila->monto <= $montoMax)
                && $fila->pctFac >= $facMin && $fila->pctFac <= $facMax
                && $fila->pctCob >= $cobMin && $fila->pctCob <= $cobMax;
        })->values();
    }
}
