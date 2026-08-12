<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTc;
use App\Models\ObraTc;
use App\Models\Plano;
use Illuminate\Http\Request;

class ObraTcController extends Controller
{
    public function index()
    {
        $usuarioId = session('usuario_id');

        $obrasTc = ObraTc::whereHas('directorios', function ($query) use ($usuarioId) {
                $query->where('usuario_id', $usuarioId);
            })
            ->orderBy('descripcion')
            ->get();

        return view('trabajo_campo.index', compact('obrasTc'));
    }

    public function show($id)
    {
        $obraTc = ObraTc::with('usuario')->findOrFail($id);

        $usuarioId = session('usuario_id');

        $enDirectorio = DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', $usuarioId)
            ->exists();

        if (! $enDirectorio) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $pendientesPlanos = Plano::where('obra_id', $obraTc->id)
            ->where(function ($query) {
                $query->whereNull('descripcion')->orWhereNull('subgrupo_id');
            })
            ->count();

        return view('obras_tc.index', compact('obraTc', 'pendientesPlanos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $usuarioId = session('usuario_id');

        $obraTc = ObraTc::create([
            'descripcion' => $request->nombre,
            'estado'      => 1,
            'usuario_id'  => $usuarioId,
        ]);

        DirectorioTc::create([
            'obra_tc_id' => $obraTc->id,
            'usuario_id' => $usuarioId,
        ]);

        if ($usuarioId != 1) {
            DirectorioTc::create([
                'obra_tc_id' => $obraTc->id,
                'usuario_id' => 1,
            ]);
        }

        return redirect()->route('trabajo_campo.index')->with('success', 'Obra creada correctamente.');
    }
}
