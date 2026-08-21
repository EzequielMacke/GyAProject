<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTcAutomatico;
use Illuminate\Http\Request;

class DirectorioTcAutomaticoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'usuarios' => 'array',
            'usuarios.*' => 'exists:usuarios,id,estado,1',
        ]);

        $usuarioIds = $request->input('usuarios', []);

        DirectorioTcAutomatico::whereNotIn('usuario_id', $usuarioIds)->delete();

        $existentes = DirectorioTcAutomatico::pluck('usuario_id')->toArray();

        foreach ($usuarioIds as $usuarioId) {
            if (in_array($usuarioId, $existentes)) continue;

            DirectorioTcAutomatico::create([
                'usuario_id' => $usuarioId,
                'agregado_por' => session('usuario_id'),
            ]);
        }

        return redirect()->route('trabajo_campo.index')->with('success', 'Directorio automático actualizado correctamente.');
    }
}
