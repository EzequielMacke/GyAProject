<?php

namespace App\Http\Controllers;

use App\Models\ControlGasto;
use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use Illuminate\Http\Request;

class ControlGastoController extends Controller
{
    public function index($obra)
    {
        $obraModel = Obra::findOrFail($obra);
        $presupuestos = PresupuestoAprobado::with(['usuario', 'situacionAvances.estadoSituacion'])
            ->where('obra_id', $obraModel->id)
            ->get();

        return view('control_gastos.index', ['obra' => $obraModel, 'presupuestos' => $presupuestos]);
    }

    public function create($obra, $presupuesto)
    {
        $obraModel = Obra::findOrFail($obra);
        $presupuestoModel = PresupuestoAprobado::where('obra_id', $obraModel->id)->findOrFail($presupuesto);
        $gasto = ControlGasto::where('presupuesto_aprobado_id', $presupuestoModel->id)->first();

        return view('control_gastos.create', ['obra' => $obraModel, 'presupuesto' => $presupuestoModel, 'gasto' => $gasto]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'obra_id' => 'required|exists:obras,id',
            'presupuesto_aprobado_id' => 'required|exists:presupuesto_aprobados,id|unique:control_gastos,presupuesto_aprobado_id',
            'ingenieros' => 'nullable|string',
            'tecnicos' => 'nullable|string',
            'mano_obra' => 'nullable|string',
            'otros' => 'nullable|string',
            'observacion' => 'nullable|string',
        ]);

        $monto = fn ($valor) => filled($valor) ? (float) str_replace('.', '', $valor) : null;

        ControlGasto::create([
            'obra_id' => $request->obra_id,
            'presupuesto_aprobado_id' => $request->presupuesto_aprobado_id,
            'ingenieros' => $monto($request->ingenieros),
            'tecnicos' => $monto($request->tecnicos),
            'mano_obra' => $monto($request->mano_obra),
            'otros' => $monto($request->otros),
            'observacion' => $request->observacion,
        ]);

        return redirect()->route('control_gastos.index', $request->obra_id)->with('success', 'Gastos registrados correctamente.');
    }

    public function update(Request $request, $id)
    {
        $gasto = ControlGasto::findOrFail($id);

        $request->validate([
            'ingenieros' => 'nullable|string',
            'tecnicos' => 'nullable|string',
            'mano_obra' => 'nullable|string',
            'otros' => 'nullable|string',
            'observacion' => 'nullable|string',
        ]);

        $monto = fn ($valor) => filled($valor) ? (float) str_replace('.', '', $valor) : null;

        $gasto->update([
            'ingenieros' => $monto($request->ingenieros),
            'tecnicos' => $monto($request->tecnicos),
            'mano_obra' => $monto($request->mano_obra),
            'otros' => $monto($request->otros),
            'observacion' => $request->observacion,
        ]);

        return redirect()->route('control_gastos.index', $gasto->obra_id)->with('success', 'Gastos actualizados correctamente.');
    }
}
