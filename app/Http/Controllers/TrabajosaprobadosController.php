<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrabajosaprobadosController extends Controller
{
    public function index()
    {
        $presupuestos = PresupuestoAprobado::where('estado', '>=', 2)->with('usuarioGestion')->get();
        $obras = Obra::all();
        $estados = config('constantes.estado_de_presupuestos');
        $estados_label = config('constantes.estado_de_presupuestos_btn');
        $tipo_trabajo = config('constantes.tipo_trabajo');
        return view('trabajo_cobrar.index', compact('presupuestos', 'estados', 'estados_label','tipo_trabajo','obras'));
    }
    public function store(Request $request)
    {
        $presupuesto = PresupuestoAprobado::findOrFail($request->presupuesto_id);
        $presupuesto->gestionado_por = Auth::id();
        $presupuesto->fecha_gestion = $request->fecha_gestion;
        $presupuesto->anticipo = $request->has('anticipo_cobrado') ? 1 : 2;
        $presupuesto->orden_trabajo = $request->orden_trabajo;
        $presupuesto->estado = 3;
        $presupuesto->save();
        return redirect()->route('trabajo_cobrar.index')->with('success', 'Trabajo gestionado correctamente');
    }
    public function anular($id)
    {
        $presupuesto = PresupuestoAprobado::findOrFail($id);
        $presupuesto->gestionado_por = null;
        $presupuesto->fecha_gestion = null;
        $presupuesto->anticipo = null;
        $presupuesto->orden_trabajo = null;
        $presupuesto->estado = 2;
        $presupuesto->save();
        return redirect()->route('trabajo_cobrar.index')->with('success', 'Trabajo anulado correctamente');
    }

}
