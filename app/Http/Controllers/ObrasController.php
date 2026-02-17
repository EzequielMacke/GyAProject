<?php

namespace App\Http\Controllers;

use App\Models\Directorio;
use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObrasController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        // Obtener solo las obras donde el usuario está en el directorio
        $obras = Obra::whereHas('directorios', function($query) use ($userId) {
            $query->where('usuario_id', $userId);
        })->with('presupuestos')->get();
        $estados = config('constantes.estado_obras');
        $estados_pre = config('constantes.estado_de_presupuestos');
        $presupuestos = PresupuestoAprobado::all();
        $tipo_trabajo = config('constantes.tipo_trabajo');
        return view('obras.index', compact('obras', 'estados','presupuestos','tipo_trabajo','estados_pre'));
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
        ]);

        $obra = Obra::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'fecha_carga' => now(),
            'usuario_id' => Auth::id(),
            'observacion' => $request->observacion,
            'estado' => 1,
        ]);

        Directorio::create([
            'obra_id' => $obra->id,
            'usuario_id' => 1,
            'fecha' => now(),
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
        $obra = Obra::findOrFail($id);
        return view('obras.show', compact('obra'));
    }


}
