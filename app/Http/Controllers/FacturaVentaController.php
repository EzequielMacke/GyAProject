<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\FacturaVenta;
use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use App\Models\Usuarios;

class FacturaVentaController extends Controller
{
    public function show($obraId)
    {
        $obra = Obra::findOrFail($obraId);
        $presupuestos = PresupuestoAprobado::with('facturasVenta')->where('obra_id', $obraId)->get();
        return view('factura_venta.show', compact('obra', 'presupuestos'));
    }
    
	public function index($obra = null)
	{
		$facturas = FacturaVenta::with(['presupuestoAprobado', 'obra'])->when($obra, function($query) use ($obra) {
			$query->where('obra_id', $obra);
		})->get();
		$obra = $obra ? Obra::find($obra) : null;
		return view('factura_venta.index', compact('facturas', 'obra'));
	}

    public function create($obraId)
    {
        $obra = Obra::findOrFail($obraId);
        $presupuestos = PresupuestoAprobado::where('obra_id', $obraId)->get();
        $usuarios = Usuarios::all();

        return view('factura_venta.create', compact('obra', 'presupuestos', 'usuarios'));
    }

    public function store(Request $request)
    {
        $factura = new FacturaVenta();

        $factura->nro_factura = $request->input('nro_factura');
        $factura->fecha_emision = now();
        $factura->concepto = $request->input('concepto');
        $factura->monto = $request->input('monto');
        $factura->presupuesto_aprobado_id = $request->input('presupuesto_aprobado_id');
        $factura->usuario_id = auth()->id();
        $factura->obra_id = $request->input('obra_id');

        // Calcular saldo
        $saldo = $factura->monto;
        if (!empty($factura->presupuesto_aprobado_id)) {
            $presupuesto = PresupuestoAprobado::find($factura->presupuesto_aprobado_id);
            $facturas = FacturaVenta::where('presupuesto_aprobado_id', $presupuesto->id)->sum('monto');
            $saldo = $presupuesto->monto - $facturas - $factura->monto;
        }
        $factura->saldo = $saldo;

        $factura->save();

        return redirect()->route('factura_venta.index', $factura->obra_id)
            ->with('success', 'Factura de venta cargada correctamente.');
    }
}
