<?php

namespace App\Http\Controllers;

use App\Models\Pedido_para_obra;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreparobraController extends Controller
{
    public function index()
    {
        $pedobras = Pedido_para_obra::all();
        $estados = config('constantes.estado_de_pedido');
        $estados_label = config('constantes.estado_de_pedido_btn');
        return view('preparobra.index', compact('pedobras', 'estados','estados_label'));
    }
    public function show($id)
    {
        $pedido = Pedido_para_obra::with('detalles.insumo')->findOrFail($id);
        $estados = config('constantes.estado_de_insumo');
        $estados_label = config('constantes.estado_de_insumo_btn');
        $usuarioIds = $pedido->detalles->pluck('usuario_id')->unique()->filter();
        $usuarios = Usuarios::whereIn('id', $usuarioIds)->pluck('nombre', 'id');
        return view('preparobra.show', compact('pedido', 'estados', 'estados_label', 'usuarios'));
    }
    public function updateConfirmado(Request $request, $id)
    {
        $pedido = Pedido_para_obra::findOrFail($id);
        $userId = Auth::id();
        foreach ($pedido->detalles as $detalle) {
            if (in_array($detalle->id, $request->confirmado ?? [])) {
                $detalle->confirmado = 2;
                $detalle->usuario_id = $userId;
            } else {
                $detalle->confirmado = 1;
                $detalle->usuario_id = null;
            }
            $detalle->save();
        }
        $insumosConfirmados = $pedido->detalles()->where('confirmado', 2)->count();
        $pedido->insumo_confirmado = $insumosConfirmados;
        $pedido->insumo_faltante = $pedido->total_insumo - $insumosConfirmados;
        if ($insumosConfirmados == $pedido->total_insumo) {
            $pedido->estado = 2;
        } else {
            $pedido->estado = 1;
        }
        $pedido->save();
        return redirect()->route('preparobra.index')
            ->with('success', 'Estado de los insumos actualizado exitosamente.');
    }
}
