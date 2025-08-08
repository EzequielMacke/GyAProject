<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Factura;
use App\Models\Moneda;
use App\Models\Obra;
use App\Models\Presupuesto;
use App\Models\Recibo;
use App\Models\Tipo_trabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PresupuestoController extends Controller
{
    public function index()
    {
        $presupuestos = Presupuesto::with([
            'obra',
            'tipoTrabajo',
            'estado',
            'moneda',
            'facturas' => function($query) {
                $query->with([
                    'moneda',
                    'recibos' => function($subQuery) {
                        $subQuery->with('moneda');
                    }
                ]);
            }
        ])->get();

        return view('presupuestos.index', compact('presupuestos'));
    }

    public function create()
    {
        $obras = Obra::all();
        $tipos_trabajo = Tipo_trabajo::all();
        $estados = Estado::all();
        $monedas = Moneda::all(); // Agregar esto

        return view('presupuestos.create', compact('obras', 'tipos_trabajo', 'estados', 'monedas'));
    }


    public function store(Request $request)
    {
        $presupuesto = new Presupuesto();
        $presupuesto->nombre = $request->nombre;
        $presupuesto->obra_id = $request->obra_id;
        $presupuesto->monto = $request->monto;
        $presupuesto->orden_trabajo = $request->orden_trabajo;
        $presupuesto->tipo_trabajo_id = $request->tipo_trabajo_id;
        $presupuesto->estado_id = $request->estado_id;
        $presupuesto->moneda_id = $request->moneda_id;
        $presupuesto->cotizacion = $request->cotizacion;
        $presupuesto->fecha = $request->fecha;
        $presupuesto->usuario_id = session('usuario_id');

        // Manejar archivo presupuesto
        if ($request->hasFile('presupuesto')) {
            $archivo = $request->file('presupuesto');
            $nombreArchivo = 'presupuesto_' . time() . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('public/presupuestos', $nombreArchivo);
            $presupuesto->presupuesto = $nombreArchivo;
        }

        // Manejar archivo conformidad
        if ($request->hasFile('conformidad')) {
            $archivo = $request->file('conformidad');
            $nombreArchivo = 'conformidad_' . time() . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('public/conformidades', $nombreArchivo);
            $presupuesto->conformidad = $nombreArchivo;
        }

        $presupuesto->save();
        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto creado exitosamente');
    }


    public function edit($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $obras = Obra::all();
        $tipos_trabajo = Tipo_trabajo::all();
        $estados = Estado::all();
        $monedas = Moneda::all(); // Agregar esto

        return view('presupuestos.edit', compact('presupuesto', 'obras', 'tipos_trabajo', 'estados', 'monedas'));
    }


    public function update(Request $request, $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);

        $presupuesto->nombre = $request->nombre;
        $presupuesto->obra_id = $request->obra_id;
        $presupuesto->monto = $request->monto;
        $presupuesto->orden_trabajo = $request->orden_trabajo;
        $presupuesto->tipo_trabajo_id = $request->tipo_trabajo_id;
        $presupuesto->estado_id = $request->estado_id;
        $presupuesto->moneda_id = $request->moneda_id;
        $presupuesto->cotizacion = $request->cotizacion;
        $presupuesto->fecha = $request->fecha;

        // Manejar archivo presupuesto
        if ($request->hasFile('presupuesto')) {
            // Eliminar archivo anterior
            if ($presupuesto->presupuesto) {
                Storage::disk('public')->delete('presupuestos/' . $presupuesto->presupuesto);
            }

            $archivo = $request->file('presupuesto');
            $nombreArchivo = 'presupuesto_' . time() . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('public/presupuestos', $nombreArchivo);
            $presupuesto->presupuesto = $nombreArchivo;
        }

        // Manejar archivo conformidad
        if ($request->hasFile('conformidad')) {
            // Eliminar archivo anterior
            if ($presupuesto->conformidad) {
                Storage::disk('public')->delete('conformidades/' . $presupuesto->conformidad);
            }

            $archivo = $request->file('conformidad');
            $nombreArchivo = 'conformidad_' . time() . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('public/conformidades', $nombreArchivo);
            $presupuesto->conformidad = $nombreArchivo;
        }

        $presupuesto->save();
        return redirect()->route('presupuestos.index')->with('success', 'Presupuesto actualizado exitosamente');
    }

    public function downloadFile($id, $type)
    {
        $presupuesto = Presupuesto::findOrFail($id);

        if ($type === 'presupuesto' && $presupuesto->presupuesto) {
            $filePath = storage_path('app/public/presupuestos/' . $presupuesto->presupuesto);
        } elseif ($type === 'conformidad' && $presupuesto->conformidad) {
            $filePath = storage_path('app/public/conformidades/' . $presupuesto->conformidad);
        } else {
            abort(404);
        }

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath);
    }

    public function show($id)
    {
        $presupuesto = Presupuesto::with(['obra', 'usuario', 'moneda', 'estado', 'tipoTrabajo'])->findOrFail($id);
        return view('presupuestos.view', compact('presupuesto'));
    }

    public function createFactura($id)
    {
        $presupuesto = Presupuesto::with(['obra', 'tipoTrabajo', 'estado', 'moneda'])->findOrFail($id);
        $monedas = Moneda::all();

        return view('facturas.create', compact('presupuesto', 'monedas'));
    }

    public function storeFactura(Request $request, $id)
    {
        $request->validate([
            'numero' => 'required|string|max:50',
            'fecha' => 'required|date',
            'concepto' => 'required|string',
            'monto' => 'required|numeric|min:0',
            'moneda_id' => 'required|exists:monedas,id',
            'cotizacion' => 'nullable|numeric|min:0'
        ]);

        $factura = new Factura();
        $factura->presupuesto_id = $id;
        $factura->numero = $request->numero;
        $factura->concepto = $request->concepto;
        $factura->fecha = $request->fecha;
        $factura->monto = $request->monto;
        $factura->moneda_id = $request->moneda_id;
        $factura->cotizacion = ($request->moneda_id == 2) ? $request->cotizacion : 1.00;
        $factura->usuario_id = session('usuario_id');

        $factura->save();

        return redirect()->route('presupuestos.index')
                        ->with('success', 'Factura creada exitosamente');
    }

    public function createRecibo($facturaId)
    {
        $factura = Factura::with(['presupuesto.obra', 'presupuesto.tipoTrabajo', 'presupuesto.estado', 'moneda', 'recibos.moneda'])->findOrFail($facturaId);
        $monedas = Moneda::all();

        return view('recibos.create', compact('factura', 'monedas'));
    }

    public function storeRecibo(Request $request, $facturaId)
    {
        $request->validate([
            'numero' => 'required|string|max:50',
            'fecha' => 'required|date',
            'concepto' => 'required|string',
            'monto' => 'required|numeric|min:0',
            'moneda_id' => 'required|exists:monedas,id',
            'cotizacion' => 'nullable|numeric|min:0'
        ]);

        $recibo = new Recibo();
        $recibo->factura_id = $facturaId;
        $recibo->numero = $request->numero;
        $recibo->concepto = $request->concepto;
        $recibo->fecha = $request->fecha;
        $recibo->monto = $request->monto;
        $recibo->moneda_id = $request->moneda_id;
        $recibo->cotizacion = ($request->moneda_id == 2) ? $request->cotizacion : 1.00;
        $recibo->usuario_id = session('usuario_id');

        $recibo->save();

        return redirect()->route('presupuestos.index')
                        ->with('success', 'Recibo creado exitosamente');
    }

}
