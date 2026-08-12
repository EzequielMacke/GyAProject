<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTc;
use App\Models\ObraTc;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class DirectorioTcController extends Controller
{
    public function index(ObraTc $obraTc)
    {
        $usuarioId = session('usuario_id');

        $enDirectorio = DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', $usuarioId)
            ->exists();

        if (! $enDirectorio) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $directorios = DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->with(['usuario', 'agregadoPor'])
            ->orderByDesc('created_at')
            ->get();

        $usuariosEnDirectorio = $directorios->pluck('usuario_id')->toArray();
        $usuariosDisponibles = Usuarios::whereNotIn('id', $usuariosEnDirectorio)
            ->where('estado', 1)
            ->orderBy('nombre')
            ->get();

        return view('directorio_tc.index', compact('obraTc', 'directorios', 'usuariosDisponibles'));
    }

    public function store(Request $request, ObraTc $obraTc)
    {
        $request->validate([
            'usuarios' => 'required|array|min:1',
            'usuarios.*' => 'exists:usuarios,id,estado,1',
        ]);

        foreach ($request->usuarios as $usuarioId) {
            DirectorioTc::create([
                'obra_tc_id' => $obraTc->id,
                'usuario_id' => $usuarioId,
                'agregado_por' => session('usuario_id'),
            ]);
        }

        return redirect()->route('directorio_tc.index', $obraTc->id)->with('success', 'Usuarios agregados al directorio exitosamente.');
    }
}
