<?php

namespace App\Http\Controllers;

use App\Models\PresupuestoAprobado;
use Illuminate\Http\Request;

class AsignarOrdenController extends Controller
{
    public function index()
    {
        $presupuestos = PresupuestoAprobado::with('obra', 'usuario')->get();
        $estados      = config('constantes.estado_de_presupuestos');
        $estados_btn  = config('constantes.estado_de_presupuestos_btn');
        $tipos        = config('constantes.tipo_trabajo');
        return view('asignar_orden.index', compact('presupuestos', 'estados', 'estados_btn', 'tipos'));
    }

    public function edit($id)
    {
        $presupuesto = PresupuestoAprobado::with('obra', 'usuario', 'usuarioGestion')->findOrFail($id);
        $estados     = config('constantes.estado_de_presupuestos');
        $estados_btn = config('constantes.estado_de_presupuestos_btn');
        $tipos       = config('constantes.tipo_trabajo');
        return view('asignar_orden.edit', compact('presupuesto', 'estados', 'estados_btn', 'tipos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'orden_trabajo' => 'required|string|max:255',
        ]);

        $presupuesto = PresupuestoAprobado::findOrFail($id);
        $presupuesto->orden_trabajo = $request->orden_trabajo;
        $presupuesto->save();

        return redirect()->route('asignar_orden.index')
            ->with('success', 'Orden de trabajo asignada correctamente.');
    }
}
