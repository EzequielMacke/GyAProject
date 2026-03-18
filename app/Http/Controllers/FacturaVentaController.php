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
    
    public function index($presupuesto = null, $obra = null)
    {

        $facturas = FacturaVenta::with(['presupuestoAprobado', 'obra'])
            ->when($obra, function($query) use ($obra) {
                $query->where('obra_id', $obra);
            })
            ->when($presupuesto, function($query) use ($presupuesto) {
                $query->where('presupuesto_aprobado_id', $presupuesto);
            })
            ->get();
        $obra = $obra ? Obra::find($obra) : null;
        $presupuesto = $presupuesto ? PresupuestoAprobado::find($presupuesto) : null;
        return view('factura_venta.index', compact('facturas', 'obra', 'presupuesto'));
    }

    public function create($presupuesto = null, $obra = null)
    {
        $obra = $obra ? Obra::findOrFail($obra) : null;
        $presupuestos = $obra ? PresupuestoAprobado::where('obra_id', $obra->id)->get() : collect();
        $usuarios = Usuarios::all();
        $presupuesto = $presupuesto ? PresupuestoAprobado::find($presupuesto) : null;

        return view('factura_venta.create', compact('obra', 'presupuestos', 'usuarios', 'presupuesto'));
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'nro_factura',
            'concepto',
            'razon_social',
            'monto',
            'presupuesto_aprobado_id',
            'obra_id',
        ]);
        $data['fecha_emision'] = now();
        $data['usuario_id'] = auth()->id();

        // Limpiar monto (eliminar puntos de miles)
        $data['monto'] = str_replace('.', '', $data['monto']);

        // Calcular saldo
        $saldo = $data['monto'];
        if (!empty($data['presupuesto_aprobado_id'])) {
            $presupuesto = PresupuestoAprobado::find($data['presupuesto_aprobado_id']);
            $facturas = FacturaVenta::where('presupuesto_aprobado_id', $presupuesto->id)->sum('monto');
            $montoTotal = is_numeric($presupuesto->monto_total) ? $presupuesto->monto_total : 0;
            $facturasSum = is_numeric($facturas) ? $facturas : 0;
            $montoFactura = is_numeric($data['monto']) ? $data['monto'] : 0;
            $saldo = $montoTotal - $facturasSum - $montoFactura;
        }
        $data['saldo'] = $saldo;

        FacturaVenta::create($data);

        return redirect()->route('factura_venta.index', ['presupuesto' => $data['presupuesto_aprobado_id'], 'obra' => $data['obra_id']])
            ->with('success', 'Factura de venta cargada correctamente.');
    }

    public function edit($id)
    {
        $factura = FacturaVenta::findOrFail($id);
        $obra = $factura->obra;
        $presupuesto = $factura->presupuestoAprobado;
        return view('factura_venta.edit', compact('factura', 'obra', 'presupuesto'));
    }

    public function update(Request $request, $id)
    {
        $factura = FacturaVenta::findOrFail($id);
        $data = $request->only([
            'nro_factura',
            'concepto',
            'razon_social',
            'monto',
        ]);
        // Limpiar monto (eliminar puntos de miles)
        $data['monto'] = str_replace('.', '', $data['monto']);

        // Recalcular saldo
        $saldo = $data['monto'];
        if ($factura->presupuesto_aprobado_id) {
            $presupuesto = $factura->presupuestoAprobado;
            // Sumar todas las facturas menos la actual
            $facturas = FacturaVenta::where('presupuesto_aprobado_id', $presupuesto->id)
                ->where('id', '!=', $factura->id)
                ->sum('monto');
            $montoTotal = is_numeric($presupuesto->monto_total) ? $presupuesto->monto_total : 0;
            $facturasSum = is_numeric($facturas) ? $facturas : 0;
            $montoFactura = is_numeric($data['monto']) ? $data['monto'] : 0;
            $saldo = $montoTotal - $facturasSum - $montoFactura;
        }
        $data['saldo'] = $saldo;

        $factura->update($data);

        return redirect()->route('factura_venta.index', ['presupuesto' => $factura->presupuesto_aprobado_id, 'obra' => $factura->obra_id])
            ->with('success', 'Factura de venta actualizada correctamente.');
    }

    public function search()
    {
        $presupuestos = PresupuestoAprobado::with('obra')
            ->whereNotNull('orden_trabajo')
            ->where('orden_trabajo', '!=', '')
            ->get();
        $estados     = config('constantes.estado_de_presupuestos');
        $estados_btn = config('constantes.estado_de_presupuestos_btn');
        $tipos       = config('constantes.tipo_trabajo');
        return view('factura_venta.search', compact('presupuestos', 'estados', 'estados_btn', 'tipos'));
    }

}
