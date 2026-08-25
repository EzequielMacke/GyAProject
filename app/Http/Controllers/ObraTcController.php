<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTc;
use App\Models\DirectorioTcAutomatico;
use App\Models\ObraTc;
use App\Models\Plano;
use App\Models\Usuarios;
use App\Services\PermisoService;
use Illuminate\Http\Request;

class ObraTcController extends Controller
{
    public function index()
    {
        $usuarioId = session('usuario_id');

        $obrasTc = ObraTc::whereHas('directorios', function ($query) use ($usuarioId) {
                $query->where('usuario_id', $usuarioId);
            })
            ->where('estado', '!=', 2)
            ->orderBy('descripcion')
            ->get();

        $puedeGestionarAutomatico = app(PermisoService::class)->puede('tra_cam', 'eliminar');
        $usuariosDisponibles = collect();
        $usuariosAutomaticos = collect();

        if ($puedeGestionarAutomatico) {
            $usuariosDisponibles = Usuarios::where('estado', 1)
                ->where('id', '!=', 1)
                ->orderBy('nombre')
                ->get();

            $usuariosAutomaticos = DirectorioTcAutomatico::pluck('usuario_id');
        }

        return view('trabajo_campo.index', compact('obrasTc', 'usuariosDisponibles', 'usuariosAutomaticos'));
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

        if ($obraTc->estado == 2) {
            return redirect()->route('trabajo_campo.index')->with('error', 'Esta obra fue eliminada.');
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

        $automaticos = DirectorioTcAutomatico::pluck('usuario_id');
        foreach ($automaticos as $autoUsuarioId) {
            if (in_array($autoUsuarioId, [$usuarioId, 1])) continue;

            DirectorioTc::create([
                'obra_tc_id' => $obraTc->id,
                'usuario_id' => $autoUsuarioId,
            ]);
        }

        return redirect()->route('trabajo_campo.index')->with('success', 'Obra creada correctamente.');
    }

    public function update(Request $request, ObraTc $obraTc)
    {
        $request->validate([
            'nombre'  => 'required|string|max:255',
            'mensaje' => 'nullable|string',
        ]);

        $obraTc->update([
            'descripcion' => $request->nombre,
            'mensaje'     => $request->mensaje,
        ]);

        return redirect()->route('obras_tc.index', $obraTc->id)->with('success', 'Obra actualizada correctamente.');
    }

    public function destroy(ObraTc $obraTc)
    {
        $obraTc->update(['estado' => 2]);

        return redirect()->route('trabajo_campo.index')->with('success', 'Obra eliminada correctamente.');
    }
}
