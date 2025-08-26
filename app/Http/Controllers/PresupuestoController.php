<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Factura;
use App\Models\Moneda;
use App\Models\Obra;
use App\Models\Presupuesto;
use App\Models\Recibo;
use App\Models\Tipo_trabajo;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $presupuesto->observacion = $request->observacion;
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
        $presupuesto->observacion = $request->observacion;
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

    public function destroy($id)
    {
        try {
            $presupuesto = Presupuesto::findOrFail($id);

            // Verificar que no tenga facturas
            if ($presupuesto->facturas->count() > 0) {
                return redirect()->route('presupuestos.index')
                            ->with('error', 'No se puede eliminar el presupuesto porque tiene facturas asociadas.');
            }

            // Eliminar archivos adjuntos si existen
            if ($presupuesto->presupuesto) {
                Storage::delete('public/presupuestos/' . $presupuesto->presupuesto);
            }

            if ($presupuesto->conformidad) {
                Storage::delete('public/conformidades/' . $presupuesto->conformidad);
            }

            $presupuesto->delete();

            return redirect()->route('presupuestos.index')
                            ->with('success', 'Presupuesto eliminado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('presupuestos.index')
                            ->with('error', 'Error al eliminar el presupuesto: ' . $e->getMessage());
        }
    }

    //CONTROLADOR PARA FACTURAS
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
            'cotizacion' => 'nullable|numeric|min:0',
            'documento' => 'nullable|file|mimes:pdf|max:102400' // 100MB en KB
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

        // Manejar archivo adjunto
        if ($request->hasFile('documento')) {
            $archivo = $request->file('documento');
            $nombreArchivo = 'factura_' . time() . '_' . $archivo->getClientOriginalName();
            $archivo->storeAs('public/facturas_adjuntos', $nombreArchivo);
            $factura->adjunto = $nombreArchivo;
        }

        $factura->save();

        return redirect()->route('presupuestos.index')
                        ->with('success', 'Factura creada exitosamente');
    }

    public function downloadAdjuntoFactura($id)
    {
        $factura = Factura::findOrFail($id);

        if (!$factura->adjunto) {
            abort(404, 'No hay documento adjunto para esta factura');
        }

        $path = storage_path('app/public/facturas_adjuntos/' . $factura->adjunto);

        if (!file_exists($path)) {
            abort(404, 'El archivo no existe');
        }

        return response()->file($path);
    }

    public function editFactura($id)
    {
        $factura = Factura::with(['presupuesto.obra', 'presupuesto.tipoTrabajo', 'presupuesto.estado', 'usuario', 'moneda'])->findOrFail($id);
        $monedas = Moneda::all();

        return view('facturas.edit', compact('factura', 'monedas'));
    }

    public function updateFactura(Request $request, $id)
    {
        $request->validate([
            'numero' => 'required|string|max:50',
            'fecha' => 'required|date',
            'concepto' => 'required|string',
            'monto' => 'required|numeric|min:0',
            'moneda_id' => 'required|exists:monedas,id',
            'cotizacion' => 'nullable|numeric|min:0',
            'documento' => 'nullable|file|mimes:pdf|max:102400' // 100MB en KB
        ]);

        $factura = Factura::findOrFail($id);

        $factura->numero = $request->numero;
        $factura->concepto = $request->concepto;
        $factura->fecha = $request->fecha;
        $factura->monto = $request->monto;
        $factura->moneda_id = $request->moneda_id;
        $factura->cotizacion = ($request->moneda_id == 2) ? $request->cotizacion : 1.00;

        // Manejar archivo adjunto
        if ($request->hasFile('documento')) {
            // Eliminar archivo anterior si existe
            if ($factura->adjunto) {
                Storage::delete('public/facturas_adjuntos/' . $factura->adjunto);
            }

            $archivo = $request->file('documento');
            $nombreArchivo = 'factura_' . time() . '_' . preg_replace('/[^A-Za-z0-9\-_\.]/', '', $archivo->getClientOriginalName());
            $archivo->storeAs('public/facturas_adjuntos', $nombreArchivo);
            $factura->adjunto = $nombreArchivo;
        }

        $factura->save();

        return redirect()->route('presupuestos.index')
                        ->with('success', 'Factura actualizada exitosamente');
    }

    public function destroyFactura($id)
    {
        try {
            $factura = Factura::findOrFail($id);

            // Verificar que no tenga recibos
            if ($factura->recibos->count() > 0) {
                return redirect()->route('presupuestos.index')
                            ->with('error', 'No se puede eliminar la factura porque tiene recibos asociados.');
            }

            // Eliminar archivo adjunto si existe
            if ($factura->adjunto) {
                Storage::delete('public/facturas_adjuntos/' . $factura->adjunto);
            }

            $factura->delete();

            return redirect()->route('presupuestos.index')
                            ->with('success', 'Factura eliminada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('presupuestos.index')
                            ->with('error', 'Error al eliminar la factura: ' . $e->getMessage());
        }
    }

    //CONTROLADOR PARA RECIBOS

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


    public function editRecibo($id)
    {
        $recibo = Recibo::with([
            'factura.presupuesto.obra',
            'factura.presupuesto.tipoTrabajo',
            'factura.presupuesto.estado',
            'factura.moneda',
            'factura.recibos.moneda',
            'usuario',
            'moneda'
        ])->findOrFail($id);

        $monedas = Moneda::all();

        return view('recibos.edit', compact('recibo', 'monedas'));
    }

    public function updateRecibo(Request $request, $id)
    {
        $request->validate([
            'numero' => 'required|string|max:50',
            'fecha' => 'required|date',
            'concepto' => 'required|string',
            'monto' => 'required|numeric|min:0',
            'moneda_id' => 'required|exists:monedas,id',
            'cotizacion' => 'nullable|numeric|min:0'
        ]);

        $recibo = Recibo::findOrFail($id);

        $recibo->numero = $request->numero;
        $recibo->concepto = $request->concepto;
        $recibo->fecha = $request->fecha;
        $recibo->monto = $request->monto;
        $recibo->moneda_id = $request->moneda_id;
        $recibo->cotizacion = ($request->moneda_id == 2) ? $request->cotizacion : 1.00;

        $recibo->save();

        return redirect()->route('presupuestos.index')
                        ->with('success', 'Recibo actualizado exitosamente');
    }

    public function destroyRecibo($id)
    {
        try {
            $recibo = Recibo::findOrFail($id);

            $recibo->delete();

            return redirect()->route('presupuestos.index')
                            ->with('success', 'Recibo eliminado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->route('presupuestos.index')
                            ->with('error', 'Error al eliminar el recibo: ' . $e->getMessage());
        }
    }

    //Reporte de presupuestos
    public function reportes()
    {
        $obras = Obra::all();
        $estados = Estado::all();
        $tipos_trabajo = Tipo_trabajo::all();

        return view('presupuestos.reportes', compact('obras', 'estados', 'tipos_trabajo'));
    }

    public function generarReporte(Request $request, $tipo)
    {
        try {
            // Obtener filtros
            $filtros = [
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'obra_id' => $request->obra_id,
                'estado_id' => $request->estado_id,
                'tipo_trabajo_id' => $request->tipo_trabajo_id,
                'monto_min' => $request->monto_min,
                'monto_max' => $request->monto_max,
                'incluir_facturas' => $request->has('incluir_facturas'),
                'incluir_recibos' => $request->has('incluir_recibos'),
                'incluir_saldos' => $request->has('incluir_saldos'),
                'incluir_totales' => $request->has('incluir_totales')
            ];

            // Construir query base
            $query = Presupuesto::with([
                'obra',
                'tipoTrabajo',
                'estado',
                'moneda',
                'usuario'
            ]);

            // Si incluye facturas, cargar la relación
            if ($filtros['incluir_facturas']) {
                $query->with(['facturas.moneda']);
            }

            // Si incluye recibos, cargar la relación
            if ($filtros['incluir_recibos']) {
                $query->with(['facturas.recibos.moneda']);
            }

            // Aplicar filtros de fecha
            if ($filtros['fecha_inicio']) {
                $query->where('fecha', '>=', $filtros['fecha_inicio']);
            }

            if ($filtros['fecha_fin']) {
                $query->where('fecha', '<=', $filtros['fecha_fin']);
            }

            // Aplicar filtros opcionales
            if ($filtros['obra_id']) {
                $query->where('obra_id', $filtros['obra_id']);
            }

            if ($filtros['estado_id']) {
                $query->where('estado_id', $filtros['estado_id']);
            }

            if ($filtros['tipo_trabajo_id']) {
                $query->where('tipo_trabajo_id', $filtros['tipo_trabajo_id']);
            }

            if ($filtros['monto_min']) {
                $query->where('monto', '>=', $filtros['monto_min']);
            }

            if ($filtros['monto_max']) {
                $query->where('monto', '<=', $filtros['monto_max']);
            }

            // Obtener presupuestos ordenados por fecha
            $presupuestos = $query->orderBy('fecha', 'desc')->get();

            // Calcular totales y estadísticas
            $estadisticas = $this->calcularEstadisticas($presupuestos, $filtros);

            // Preparar datos para el PDF
            $data = [
                'titulo' => 'Reporte Completo de Presupuestos',
                'presupuestos' => $presupuestos,
                'filtros' => $filtros,
                'estadisticas' => $estadisticas,
                'fecha_generacion' => now()->format('d/m/Y H:i'),
                'usuario' => session('usuario_nombre', 'Usuario'),
                'empresa' => 'G&A CONSTRUCCIONES'
            ];

            // Generar PDF
            $pdf = Pdf::loadView('reportes.presupuestos_completo', $data);
            $pdf->setPaper('A4', 'portrait');

            // Descargar el PDF
            $nombreArchivo = 'reporte_presupuestos_' . date('Y-m-d_H-i-s') . '.pdf';

            return $pdf->download($nombreArchivo);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    private function calcularEstadisticas($presupuestos, $filtros)
    {
        $estadisticas = [
            'total_presupuestos' => $presupuestos->count(),
            'monto_total_guaranies' => 0,
            'monto_total_dolares' => 0,
            'total_facturas' => 0,
            'monto_facturado_guaranies' => 0,
            'monto_facturado_dolares' => 0,
            'total_cobros' => 0,
            'monto_cobrado_guaranies' => 0,
            'monto_cobrado_dolares' => 0,
            'saldo_pendiente_guaranies' => 0,
            'saldo_pendiente_dolares' => 0,
            'por_estado' => [],
            'por_obra' => [],
            'por_tipo_trabajo' => []
        ];

        // Calcular totales de presupuestos
        foreach ($presupuestos as $presupuesto) {
            if ($presupuesto->moneda_id == 1) { // Guaraníes
                $estadisticas['monto_total_guaranies'] += $presupuesto->monto;
            } else { // Dólares
                $estadisticas['monto_total_dolares'] += $presupuesto->monto;
            }

            // Agrupar por estado
            $estado = $presupuesto->estado->descripcion ?? 'Sin estado';
            if (!isset($estadisticas['por_estado'][$estado])) {
                $estadisticas['por_estado'][$estado] = [
                    'cantidad' => 0,
                    'monto_guaranies' => 0,
                    'monto_dolares' => 0
                ];
            }
            $estadisticas['por_estado'][$estado]['cantidad']++;
            if ($presupuesto->moneda_id == 1) {
                $estadisticas['por_estado'][$estado]['monto_guaranies'] += $presupuesto->monto;
            } else {
                $estadisticas['por_estado'][$estado]['monto_dolares'] += $presupuesto->monto;
            }

            // Agrupar por obra
            $obra = $presupuesto->obra->nombre ?? 'Sin obra';
            if (!isset($estadisticas['por_obra'][$obra])) {
                $estadisticas['por_obra'][$obra] = [
                    'cantidad' => 0,
                    'monto_guaranies' => 0,
                    'monto_dolares' => 0
                ];
            }
            $estadisticas['por_obra'][$obra]['cantidad']++;
            if ($presupuesto->moneda_id == 1) {
                $estadisticas['por_obra'][$obra]['monto_guaranies'] += $presupuesto->monto;
            } else {
                $estadisticas['por_obra'][$obra]['monto_dolares'] += $presupuesto->monto;
            }

            // Agrupar por tipo de trabajo
            $tipo = $presupuesto->tipoTrabajo->nombre ?? 'Sin tipo';
            if (!isset($estadisticas['por_tipo_trabajo'][$tipo])) {
                $estadisticas['por_tipo_trabajo'][$tipo] = [
                    'cantidad' => 0,
                    'monto_guaranies' => 0,
                    'monto_dolares' => 0
                ];
            }
            $estadisticas['por_tipo_trabajo'][$tipo]['cantidad']++;
            if ($presupuesto->moneda_id == 1) {
                $estadisticas['por_tipo_trabajo'][$tipo]['monto_guaranies'] += $presupuesto->monto;
            } else {
                $estadisticas['por_tipo_trabajo'][$tipo]['monto_dolares'] += $presupuesto->monto;
            }

            // Calcular facturas si está habilitado
            if ($filtros['incluir_facturas'] && $presupuesto->facturas) {
                foreach ($presupuesto->facturas as $factura) {
                    $estadisticas['total_facturas']++;
                    if ($factura->moneda_id == 1) {
                        $estadisticas['monto_facturado_guaranies'] += $factura->monto;
                    } else {
                        $estadisticas['monto_facturado_dolares'] += $factura->monto;
                    }

                    // Calcular cobros si está habilitado
                    if ($filtros['incluir_recibos'] && $factura->recibos) {
                        foreach ($factura->recibos as $recibo) {
                            $estadisticas['total_cobros']++;
                            if ($recibo->moneda_id == 1) {
                                $estadisticas['monto_cobrado_guaranies'] += $recibo->monto;
                            } else {
                                $estadisticas['monto_cobrado_dolares'] += $recibo->monto;
                            }
                        }
                    }
                }
            }
        }

        // Calcular saldos pendientes
        if ($filtros['incluir_saldos']) {
            $estadisticas['saldo_pendiente_guaranies'] = $estadisticas['monto_facturado_guaranies'] - $estadisticas['monto_cobrado_guaranies'];
            $estadisticas['saldo_pendiente_dolares'] = $estadisticas['monto_facturado_dolares'] - $estadisticas['monto_cobrado_dolares'];
        }

        return $estadisticas;
    }


}
