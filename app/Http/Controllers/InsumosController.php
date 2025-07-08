<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumo;
use Illuminate\Support\Facades\Auth;

class InsumosController extends Controller
{
    public function create()
    {
        $insumos = Insumo::all();
        return view('insumos.create', compact('insumos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:insumos,nombre',
        ]);

        Insumo::create([
            'nombre' => $request->nombre,
            'estado' => 1, // Establecer estado por defecto en 1
            'usuario_id' => Auth::id(),
        ]);

        return redirect()->route('insumos.index')->with('success', 'Insumo creado exitosamente.');
    }

    public function index()
    {
        $insumos = Insumo::all();
        $estados = config('constantes.estado_insumos');
        return view('insumos.index', compact('insumos', 'estados'));
    }
}
