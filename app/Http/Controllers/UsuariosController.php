<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Models\Usuarios;

class UsuariosController extends Controller
{
    public function create()
    {
        $usuarios = Usuarios::all();
        $areas = Area::where('estado', 1)->get();
        return view('usuarios.create', compact('usuarios','areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:255|unique:usuarios,nombre',
        ]);
        Usuarios::create([
            'nombre' => $request->usuario,
            'contraseña' => bcrypt($request->contraseña),
            'estado' => 1,
            'area_id' => $request->area_id,
        ]);
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function index()
    {
        $usuarios = Usuarios::all();
        $estados = config('constantes.estado_de_usuario');
        return view('usuarios.index', compact('usuarios', 'estados'));
    }
}
