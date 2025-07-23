<?php

namespace App\Http\Controllers;

use App\Models\Agendamiento;
use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendamientoController extends Controller
{
    public function index()
    {
        $presupuestosPendientes = PresupuestoAprobado::where('estado', 3)->get();
        $obras = Obra::all();
        $estados = config('constantes.estado_de_presupuestos');
        $estados_label = config('constantes.estado_de_presupuestos_btn');
        $agendamientos = Agendamiento::with('presupuesto')->get();
        $presupuestoAgendados = PresupuestoAprobado::where('estado', 4)->get();
        $meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        return view('agendamiento.index', compact('presupuestosPendientes','meses', 'obras', 'estados', 'estados_label','agendamientos', 'presupuestoAgendados'));
    }
    public function store(Request $request)
    {
        $agendamiento = new Agendamiento();
        $agendamiento->presupuesto_id = $request->input('presupuesto_id');
        $agendamiento->usuario_id = Auth::id();
        $agendamiento->obra_id = $request->input('obra_id');
        $agendamiento->fecha = now();
        $agendamiento->mes = $request->input('mes');
        $agendamiento->inicio = $request->input('semana_inicio');
        $agendamiento->fin = $request->input('semana_fin');
        $agendamiento->observacion = $request->input('observaciones');
        $agendamiento->estado = 4;
        $agendamiento->save();

        $presupuesto = PresupuestoAprobado::find($request->input('presupuesto_id'));
        $presupuesto->estado = 4;
        $presupuesto->save();
        return redirect()->route('agendamiento.index')->with('success', 'Agendamiento guardado exitosamente.');
    }
    public function destroy($id)
    {
        $agendamiento = Agendamiento::find($id);
        if ($agendamiento) {
            $presupuesto = PresupuestoAprobado::find($agendamiento->presupuesto_id);
            if ($presupuesto) {
                $presupuesto->estado = 3;
                $presupuesto->save();
            }
            $agendamiento->delete();
        }

        return redirect()->route('agendamiento.index')->with('success', 'Agendamiento eliminado exitosamente.');
    }
}
