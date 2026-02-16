<?php

namespace App\Http\Controllers;

use App\Models\FacturaVenta;
use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use App\Models\RecibidoVenta;
use Illuminate\Http\Request;

class ReciboController extends Controller
{
    public function index(Request $request)
    {
        $obraId = $request->obra;
        $presupuestoId = $request->presupuesto;
        $facturaId = $request->factura;

        $obra = $obraId ? Obra::find($obraId) : null;
        $presupuesto = $presupuestoId ? PresupuestoAprobado::find($presupuestoId) : null;
        $factura = $facturaId ? FacturaVenta::find($facturaId) : null;

        $query = RecibidoVenta::query();
        if ($obraId) {
            $query->where('obra_id', $obraId);
        }
        if ($presupuestoId) {
            $query->where('presupuesto_aprobado_id', $presupuestoId);
        }
        if ($facturaId) {
            $query->where('factura_id', $facturaId);
        }

        $recibos = $query->with(['obra', 'facturaVenta'])->get();

        return view('recibo_venta.show', compact('recibos', 'obra', 'presupuesto', 'factura'));
    }

    public function create(Request $request)
    {
        $obraId = $request->obra;
        $presupuestoId = $request->presupuesto;
        $facturaId = $request->factura;

        $obra = $obraId ? Obra::find($obraId) : null;
        $presupuesto = $presupuestoId ? PresupuestoAprobado::find($presupuestoId) : null;
        $factura = $facturaId ? FacturaVenta::find($facturaId) : null;

        return view('recibo_venta.create', compact('obra', 'presupuesto', 'factura'));
    }

    public function store(Request $request)
    {
        $recibo = new RecibidoVenta();
        $recibo->nro_recibo = $request->input('nro_recibo');
        $recibo->fecha_emision = now();
        $recibo->concepto = $request->input('concepto');
        $recibo->monto = str_replace('.', '', $request->input('monto'));
        $recibo->factura_id = $request->input('factura_id');
        $recibo->presupuesto_aprobado_id = $request->input('presupuesto_aprobado_id');
        $recibo->obra_id = $request->input('obra_id');
        $recibo->usuario_id = auth()->id();
        $recibo->save();

        return redirect()->route('recibo_venta.index', [
            'presupuesto' => $recibo->presupuesto_aprobado_id,
            'obra' => $recibo->obra_id,
            'factura' => $recibo->factura_id
        ])->with('success', 'Recibo creado correctamente.');
    }

    public function edit($id)
    {
        $recibo = RecibidoVenta::with(['obra', 'facturaVenta', 'presupuestoAprobado'])->findOrFail($id);
        $obra = $recibo->obra;
        $presupuesto = $recibo->presupuestoAprobado;
        $factura = $recibo->facturaVenta;
        return view('recibo_venta.edit', compact('recibo', 'obra', 'presupuesto', 'factura'));
    }

    public function update(Request $request, $id)
    {
        $recibo = RecibidoVenta::findOrFail($id);
        $recibo->nro_recibo = $request->input('nro_recibo');
        $recibo->fecha_emision = now();
        $recibo->concepto = $request->input('concepto');
        $recibo->monto = str_replace('.', '', $request->input('monto'));
        $recibo->save();

        return redirect()->route('recibo_venta.index', [
            'presupuesto' => $recibo->presupuesto_aprobado_id,
            'obra' => $recibo->obra_id,
            'factura' => $recibo->factura_id
        ])->with('success', 'Recibo actualizado correctamente.');
    }


    
}
