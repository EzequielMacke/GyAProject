<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObrasController extends Controller
{
    public function index()
    {
        $obras = Obra::with('usuario')->get();
        $estados = config('constantes.estado_obras');

        return view('obras.index', compact('obras', 'estados'));
    }

    public function create()
    {

        $obras = Obra::all();
        return view('obras.create', compact('obras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:obras,nombre',
            'fecha_carga' => 'required|date',
        ]);

        Obra::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'fecha_carga' => $request->fecha_carga,
            'usuario_id' => Auth::id(),
            'observacion'=> $request->observacion,

            'ruc' => $request->ruc,
            'peticionario' => $request->peticionario,
            'direccion_fac' => $request->direccion_fac,
            'correo_fac' => $request->correo_fac,

            'contacto' => $request->contacto,
            'numero' => $request->numero,
            'correo_pet' => $request->correo_pet,

            'nombre_obr' => $request->nombre_obr,
            'telefono_obr' => $request->telefono_obr,
            'correo_obr' => $request->correo_obr,

            'nombre_adm' => $request->nombre_adm,
            'telefono_adm' => $request->telefono_adm,
            'correo_adm' => $request->correo_adm,

            'estado' => 1,
        ]);

        return redirect()->route('obras.index')->with('success', 'Obra creada exitosamente.');
    }

    public function edit($id)
    {
        $obra = Obra::findOrFail($id);
        return view('obras.edit', compact('obra'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:obras,nombre,' . $id,
        ]);

        $obra = Obra::findOrFail($id);
        $obra->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'observacion'=> $request->observacion,

            'ruc' => $request->ruc,
            'peticionario' => $request->peticionario,
            'direccion_fac' => $request->direccion_fac,
            'correo_fac' => $request->correo_fac,

            'contacto' => $request->contacto,
            'numero' => $request->numero,
            'correo_pet' => $request->correo_pet,

            'nombre_obr' => $request->nombre_obr,
            'telefono_obr' => $request->telefono_obr,
            'correo_obr' => $request->correo_obr,

            'nombre_adm' => $request->nombre_adm,
            'telefono_adm' => $request->telefono_adm,
            'correo_adm' => $request->correo_adm,
        ]);

        return redirect()->route('obras.index')->with('success', 'Obra actualizada exitosamente.');
    }

    public function show($id)
    {
        $obra = Obra::with(['usuario', 'presupuestos.tipoTrabajo', 'presupuestos.moneda', 'presupuestos.estado', 'presupuestos.usuario'])->findOrFail($id);
        $estados = config('constantes.estado_obras');

        return view('obras.show', compact('obra', 'estados'));
    }

}
