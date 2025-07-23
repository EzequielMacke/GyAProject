<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidarpresupuestoController extends Controller
{
    public function index()
    {
        $presupuestos = PresupuestoAprobado::with('usuarioValidado')->get();
        $obras = Obra::all();
        $estados = config('constantes.estado_de_presupuestos');
        $estados_label = config('constantes.estado_de_presupuestos_btn');
        $tipo_trabajo = config('constantes.tipo_trabajo');
        return view('validar_presupuesto.index', compact('presupuestos', 'estados', 'estados_label','tipo_trabajo','obras'));
    }
    public function store(Request $request)
    {
        if ($request->filled('nombre_obra')) {
            $existingObra = Obra::where('nombre', $request->nombre_obra)->first();
            if ($existingObra) {
                return redirect()->route('validar_presupuesto.index')->with('error', 'La obra ya existe.');
            }
            $obra = new Obra();
            $obra->nombre = $request->nombre_obra;
            $obra->usuario_id = Auth::id();
            $obra->estado = 1;
            $obra->fecha_carga = $request->fecha_carga;
            $obra->save();
        } else {
            $obra = Obra::findOrFail($request->obra_id);
        }
        $presupuesto = PresupuestoAprobado::findOrFail($request->presupuesto_id);
        $presupuesto->obra_id = $obra->id;
        $presupuesto->fecha_aprobacion = $request->fecha_aprobacion;
        $presupuesto->estado = 2;
        $presupuesto->validado_por = Auth::id();
        $presupuesto->save();
        return redirect()->route('validar_presupuesto.index')->with('success', 'Obra vinculada y presupuesto actualizado correctamente');
    }
    public function checkObra(Request $request)
    {
        $exists = Obra::where('nombre', $request->nombre_obra)->exists();
        return response()->json(['exists' => $exists]);
    }
    public function anular(Request $request, $id)
    {
        $presupuesto = PresupuestoAprobado::findOrFail($id);
        $presupuesto->obra_id = null;
        $presupuesto->estado = 1;
        $presupuesto->validado_por = null;
        $presupuesto->fecha_aprobacion = null;
        $presupuesto->save();
        return redirect()->route('validar_presupuesto.index')->with('success', 'Presupuesto anulado correctamente');
    }
}
