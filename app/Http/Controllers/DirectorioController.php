<?php

namespace App\Http\Controllers;

use App\Models\Directorio;
use App\Models\Obra;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class DirectorioController extends Controller
{
    public function index(Obra $obra)
    {
        if (!$obra) {
            return redirect()->route('obras.index')->with('error', 'Obra no encontrada');
        }
        $directorios = Directorio::where('obra_id', $obra->id)->with('usuario')->get();
        $usuariosEnDirectorio = $directorios->pluck('usuario_id')->toArray();
        $usuariosDisponibles = Usuarios::whereNotIn('id', $usuariosEnDirectorio)->where('estado', 1)->get();
        return view('directorio.index', compact('obra', 'directorios', 'usuariosDisponibles'));
    }

    public function create(Obra $obra)
    {
        if (!$obra) {
            return redirect()->route('obras.index')->with('error', 'Obra no encontrada');
        }
        $directorios = Directorio::where('obra_id', $obra->id)->with('usuario')->get();
        $usuariosEnDirectorio = $directorios->pluck('usuario_id')->toArray();
        $usuariosDisponibles = Usuarios::whereNotIn('id', $usuariosEnDirectorio)->where('estado', 1)->get();
        return view('directorio.create', compact('obra', 'directorios', 'usuariosDisponibles'));
    }

    public function store(Request $request, Obra $obra)
    {
        $request->validate([
            'usuarios' => 'required|array|min:1',
            'usuarios.*' => 'exists:usuarios,id',
        ]);

        foreach ($request->usuarios as $usuarioId) {
            Directorio::create([
                'obra_id' => $obra->id,
                'usuario_id' => $usuarioId,
                'fecha' => now()->toDateString(),
            ]);
        }

        return redirect()->route('directorio.index', $obra->id)->with('success', 'Usuarios agregados al directorio exitosamente.');
    }

    public function destroy(Obra $obra, Directorio $directorio)
    {
        $directorio->delete();
        return redirect()->route('directorio.index', $obra->id)->with('success', 'Usuario eliminado del directorio.');
    }
}
