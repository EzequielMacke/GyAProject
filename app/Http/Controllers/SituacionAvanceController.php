<?php

namespace App\Http\Controllers;

use App\Models\EstadoSituacion;
use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use App\Models\SituacionAvance;
use Illuminate\Http\Request;

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

        return view('situacion_avance.index', compact('estados', 'presupuestosPorEstado', 'puedeEditar', 'puedeVerFac', 'obras', 'tipoTrabajo', 'anios'));
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
}
