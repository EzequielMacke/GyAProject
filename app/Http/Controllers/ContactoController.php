<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function index(Request $request, $obra = null)
    {
        $query = Contacto::query();
        $obraModel = null;
        if ($obra) {
            $obraModel = Obra::find($obra);
            $query->where('obra_id', $obra);
        }
        $contactos = $query->get();
        return view('contacto.index', compact('contactos', 'obra', 'obraModel'));
    }

    public function create($obra = null)
    {
        $presupuestos = collect();
        $obraModel = null;
        if ($obra) {
            $obraModel = Obra::find($obra);
            $presupuestos = PresupuestoAprobado::where('obra_id', $obra)->get();
        }
        return view('contacto.create', compact('obra', 'obraModel', 'presupuestos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'obra_id' => 'required|exists:obras,id',
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'tipo_contacto' => 'nullable|string|max:255',
            'observacion' => 'nullable|string',
            'presupuesto_id' => 'nullable|exists:presupuesto_aprobados,id',
        ]);

        $contacto = new Contacto();
        $contacto->obra_id = $request->obra_id;
        $contacto->presupuesto_id = $request->presupuesto_id;
        $contacto->nombre = $request->nombre;
        $contacto->telefono = $request->telefono;
        $contacto->email = $request->email;
        $contacto->tipo_contacto = $request->tipo_contacto;
        $contacto->observacion = $request->observacion;
        $contacto->save();

        return redirect()->route('contacto.index', $request->obra_id)->with('success', 'Contacto creado correctamente.');
    }

    public function edit($id)
    {
        $contacto = Contacto::findOrFail($id);
        $obra = $contacto->obra_id;
        $obraModel = $obra ? Obra::find($obra) : null;
        $presupuestos = PresupuestoAprobado::where('obra_id', $obra)->get();
        return view('contacto.edit', compact('contacto', 'obra', 'obraModel', 'presupuestos'));
    }

    public function update(Request $request, $id)
    {
        $contacto = Contacto::findOrFail($id);
        $request->validate([
            'obra_id' => 'required|exists:obras,id',
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'tipo_contacto' => 'nullable|string|max:255',
            'observacion' => 'nullable|string',
            'presupuesto_id' => 'nullable|exists:presupuesto_aprobados,id',
        ]);

        $contacto->obra_id = $request->obra_id;
        $contacto->presupuesto_id = $request->presupuesto_id;
        $contacto->nombre = $request->nombre;
        $contacto->telefono = $request->telefono;
        $contacto->email = $request->email;
        $contacto->tipo_contacto = $request->tipo_contacto;
        $contacto->observacion = $request->observacion;
        $contacto->save();

        return redirect()->route('contacto.index', $request->obra_id)->with('success', 'Contacto actualizado correctamente.');
    }

}
