<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Obra;
use App\Models\Pedido_para_obra;
use App\Models\Pedido_para_obra_detalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedobraController extends Controller
{
    public function show($id)
    {
        $pedido = Pedido_para_obra::with('detalles.insumo')->findOrFail($id);
        $estados = config('constantes.estado_de_insumo');
        $estados_label = config('constantes.estado_de_insumo_btn');
        return view('pedidobra.show', compact('pedido','estados','estados_label'));
    }
    public function index()
    {
        $pedobras = Pedido_para_obra::all();
        $estados = config('constantes.estado_de_pedido');
        $estados_label = config('constantes.estado_de_pedido_btn');
        return view('pedidobra.index', compact('pedobras', 'estados','estados_label'));
    }
    public function getInsumos()
    {
        $insumos = Insumo::all();
        return response()->json($insumos);
    }
    public function create()
    {
        $ultimoPedido = Pedido_para_obra::latest()->first();
        $nuevoIdPedido = $ultimoPedido ? $ultimoPedido->id + 1 : 1;
        $pedidobras = Pedido_para_obra::all();
        $insumos = Insumo::all();
        $obras = Obra::all();
        $unidadesMedida = config('constantes.unidad_medida');
        return view('pedidobra.create', compact('pedidobras','insumos','nuevoIdPedido','obras','unidadesMedida'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'obra' => 'required',
            'fecha_entrega' => 'required|date|after_or_equal:today',
            'cantidad' => 'required|array',
        ]);
        $pedidoObra = Pedido_para_obra::create([
            'obra_id' => $request->obra,
            'fecha_pedido' => $request->fecha_pedido,
            'fecha_entrega' => $request->fecha_entrega,
            'observacion' => $request->observacion,
            'usuario_id' => Auth::id(),
            'total_insumo' => $request->contador_insumos,
            'insumo_confirmado' => 0,
            'insumo_faltante' => $request->contador_insumos,
            'estado' => 1,
        ]);
        $insumoIds = $request->insumo;
        foreach ($request->cantidad as $index => $cantidad) {
            Pedido_para_obra_detalle::create([
                'pedido_para_obra_id' => $pedidoObra->id,
                'insumo_id' => $insumoIds[$index],
                'medida' => $request->unidad_medida[$index],
                'cantidad' => $cantidad,
                'confirmado' => 1,
                'usuario_id' => null,
            ]);
        }

        return redirect()->route('pedidobra.index')->with('success', 'Pedido creado exitosamente.');
    }
    public function edit($id)
    {
        $pedido = Pedido_para_obra::with('detalles.insumo')->findOrFail($id);
        $insumos = Insumo::all();
        $obras = Obra::all();
        $unidadesMedida = config('constantes.unidad_medida');
        return view('pedidobra.edit', compact('pedido', 'insumos', 'obras', 'unidadesMedida'));
    }
    public function getObras()
    {
        $obras = Obra::all();
        return response()->json($obras);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'obra' => 'required',
            'fecha_entrega' => 'required|date|after_or_equal:today',
            'unidad_medida' => 'required|array',
            'insumo' => 'required|array|min:1',
            'contador_insumos' => 'required|integer|min:1',
        ]);

        $pedidoObra = Pedido_para_obra::findOrFail($id);
        $pedidoObra->update([
            'obra_id' => $request->obra,
            'fecha_entrega' => $request->fecha_entrega,
            'observacion' => $request->observacion,
            'total_insumo' => $request->contador_insumos,
            'insumo_faltante' => $request->contador_insumos-$pedidoObra->insumo_confirmado,
        ]);
        $pedidoObra->detalles()->where('confirmado', '!=', 2)->delete();
        foreach ($request->cantidad as $index => $cantidad) {
            $insumoId = $request->insumo[$index];
            $detalleExistente = Pedido_para_obra_detalle::where('pedido_para_obra_id', $pedidoObra->id)
                ->where('insumo_id', $insumoId)
                ->first();
            if (!$detalleExistente) {
                Pedido_para_obra_detalle::create([
                    'pedido_para_obra_id' => $pedidoObra->id,
                    'insumo_id' => $insumoId,
                    'medida' => $request->unidad_medida[$index],
                    'confirmado' => 1,
                    'cantidad' => $cantidad,
                ]);
            }
        }
        $insumosConfirmados = $pedidoObra->detalles()->where('confirmado', 2)->count();
        if ($insumosConfirmados != $pedidoObra->total_insumo) {
            $pedidoObra->estado = 1;
        } else {
            $pedidoObra->estado = 2;
        }
        $pedidoObra->save();
        return redirect()->route('pedidobra.index')->with('success', 'Pedido actualizado exitosamente.');
    }
    public function duplicar($id)
    {
        $pedidoOriginal = Pedido_para_obra::with('detalles.insumo')->findOrFail($id);
        $nuevoPedido = $pedidoOriginal->replicate();
        $nuevoPedido->fecha_pedido = now();
        $nuevoPedido->estado = 'pendiente';
        $insumosOriginales = $pedidoOriginal->detalles;
        $insumos = Insumo::all();
        $totalInsumos = $pedidoOriginal->total_insumo;
        return view('pedidobra.duplicate', [
            'pedido' => $nuevoPedido,
            'insumos' => $insumosOriginales,
            'insumosDisponibles' => $insumos,
            'obras' => Obra::all(),
            'nuevoIdPedido' => Pedido_para_obra::max('id') + 1,
            'totalInsumos' => $totalInsumos,
        ]);
    }
}
