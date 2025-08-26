<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .report-title {
            font-size: 16px;
            margin: 10px 0;
            color: #666;
        }
        .filters {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #e9ecef !important;
            font-weight: bold;
        }
        .summary-box {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
        }
        .summary-grid {
            display: table;
            width: 100%;
        }
        .summary-col {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            vertical-align: top;
        }
        .stat-item {
            margin-bottom: 8px;
        }
        .stat-label {
            font-weight: bold;
            color: #333;
        }
        .stat-value {
            color: #666;
            margin-left: 5px;
        }
        .page-break {
            page-break-before: always;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ $empresa }}</div>
        <div class="report-title">{{ $titulo }}</div>
        <div style="font-size: 10px; color: #666;">
            Generado el {{ $fecha_generacion }} por {{ $usuario }}
        </div>
    </div>

    <!-- Filtros aplicados -->
    <div class="filters">
        <strong>Filtros aplicados:</strong><br>
        <strong>Período:</strong> {{ $filtros['fecha_inicio'] ?? 'No especificado' }} al {{ $filtros['fecha_fin'] ?? 'No especificado' }}<br>
        @if($filtros['obra_id'])
            <strong>Obra:</strong> {{ $presupuestos->first()->obra->nombre ?? 'N/A' }}<br>
        @endif
        @if($filtros['estado_id'])
            <strong>Estado:</strong> {{ $presupuestos->first()->estado->descripcion ?? 'N/A' }}<br>
        @endif
        @if($filtros['tipo_trabajo_id'])
            <strong>Tipo de trabajo:</strong> {{ $presupuestos->first()->tipoTrabajo->nombre ?? 'N/A' }}<br>
        @endif
        @if($filtros['monto_min'] || $filtros['monto_max'])
            <strong>Rango de montos:</strong>
            {{ number_format($filtros['monto_min'] ?? 0, 0, ',', '.') }} -
            {{ $filtros['monto_max'] ? number_format($filtros['monto_max'], 0, ',', '.') : 'Sin límite' }}<br>
        @endif
    </div>

    @if($filtros['incluir_totales'])
    <!-- Resumen ejecutivo -->
    <div class="section-title">RESUMEN EJECUTIVO</div>
    <div class="summary-box">
        <div class="summary-grid">
            <div class="summary-col">
                <div class="stat-item">
                    <span class="stat-label">Total Presupuestos:</span>
                    <span class="stat-value">{{ $estadisticas['total_presupuestos'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Monto Total (Gs.):</span>
                    <span class="stat-value">{{ number_format($estadisticas['monto_total_guaranies'], 0, ',', '.') }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Monto Total (USD):</span>
                    <span class="stat-value">{{ number_format($estadisticas['monto_total_dolares'], 2, ',', '.') }}</span>
                </div>
            </div>
            @if($filtros['incluir_facturas'])
            <div class="summary-col">
                <div class="stat-item">
                    <span class="stat-label">Total Facturas:</span>
                    <span class="stat-value">{{ $estadisticas['total_facturas'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Facturado (Gs.):</span>
                    <span class="stat-value">{{ number_format($estadisticas['monto_facturado_guaranies'], 0, ',', '.') }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Facturado (USD):</span>
                    <span class="stat-value">{{ number_format($estadisticas['monto_facturado_dolares'], 2, ',', '.') }}</span>
                </div>
            </div>
            @endif
            @if($filtros['incluir_recibos'])
            <div class="summary-col">
                <div class="stat-item">
                    <span class="stat-label">Total Cobros:</span>
                    <span class="stat-value">{{ $estadisticas['total_cobros'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Cobrado (Gs.):</span>
                    <span class="stat-value">{{ number_format($estadisticas['monto_cobrado_guaranies'], 0, ',', '.') }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Cobrado (USD):</span>
                    <span class="stat-value">{{ number_format($estadisticas['monto_cobrado_dolares'], 2, ',', '.') }}</span>
                </div>
            </div>
            @endif
        </div>
        @if($filtros['incluir_saldos'])
        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ccc;">
            <div class="stat-item">
                <span class="stat-label">Saldo Pendiente (Gs.):</span>
                <span class="stat-value">{{ number_format($estadisticas['saldo_pendiente_guaranies'], 0, ',', '.') }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Saldo Pendiente (USD):</span>
                <span class="stat-value">{{ number_format($estadisticas['saldo_pendiente_dolares'], 2, ',', '.') }}</span>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Detalle de presupuestos -->
    <div class="section-title">DETALLE DE PRESUPUESTOS</div>
    @if($presupuestos->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Obra</th>
                <th>Tipo Trabajo</th>
                <th>Estado</th>
                <th>Monto</th>
                <th>Moneda</th>
                @if($filtros['incluir_facturas'])
                <th>Facturas</th>
                @endif
                @if($filtros['incluir_recibos'])
                <th>Cobros</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($presupuestos as $index => $presupuesto)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($presupuesto->fecha)->format('d/m/Y') }}</td>
                <td>{{ $presupuesto->obra->nombre ?? 'N/A' }}</td>
                <td>{{ $presupuesto->tipoTrabajo->nombre ?? 'N/A' }}</td>
                <td>{{ $presupuesto->estado->descripcion ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($presupuesto->monto, 0, ',', '.') }}</td>
                <td>{{ $presupuesto->moneda->simbolo ?? 'N/A' }}</td>
                @if($filtros['incluir_facturas'])
                <td class="text-center">{{ $presupuesto->facturas->count() }}</td>
                @endif
                @if($filtros['incluir_recibos'])
                <td class="text-center">
                    {{ $presupuesto->facturas->sum(function($factura) { return $factura->recibos->count(); }) }}
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>No se encontraron presupuestos con los filtros aplicados.</p>
    @endif

    <!-- Agrupación por estado -->
    @if($filtros['incluir_totales'] && count($estadisticas['por_estado']) > 0)
    <div class="page-break"></div>
    <div class="section-title">PRESUPUESTOS POR ESTADO</div>
    <table class="table">
        <thead>
            <tr>
                <th>Estado</th>
                <th>Cantidad</th>
                <th>Monto (Gs.)</th>
                <th>Monto (USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estadisticas['por_estado'] as $estado => $datos)
            <tr>
                <td>{{ $estado }}</td>
                <td class="text-center">{{ $datos['cantidad'] }}</td>
                <td class="text-right">{{ number_format($datos['monto_guaranies'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($datos['monto_dolares'], 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Agrupación por obra -->
    @if($filtros['incluir_totales'] && count($estadisticas['por_obra']) > 0)
    <div class="section-title">PRESUPUESTOS POR OBRA</div>
    <table class="table">
        <thead>
            <tr>
                <th>Obra</th>
                <th>Cantidad</th>
                <th>Monto (Gs.)</th>
                <th>Monto (USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estadisticas['por_obra'] as $obra => $datos)
            <tr>
                <td>{{ $obra }}</td>
                <td class="text-center">{{ $datos['cantidad'] }}</td>
                <td class="text-right">{{ number_format($datos['monto_guaranies'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($datos['monto_dolares'], 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($filtros['incluir_facturas'] && $estadisticas['total_facturas'] > 0)
    <!-- Detalle de facturas -->
    <div class="page-break"></div>
    <div class="section-title">DETALLE DE FACTURAS</div>
    <table class="table">
        <thead>
            <tr>
                <th>Presupuesto</th>
                <th>Número</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Moneda</th>
                @if($filtros['incluir_recibos'])
                <th>Cobros</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($presupuestos as $presupuesto)
                @foreach($presupuesto->facturas as $factura)
                <tr>
                    <td>{{ $presupuesto->id }}</td>
                    <td>{{ $factura->numero }}</td>
                    <td>{{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') }}</td>
                    <td class="text-right">{{ number_format($factura->monto, 0, ',', '.') }}</td>
                    <td>{{ $factura->moneda->simbolo ?? 'N/A' }}</td>
                    @if($filtros['incluir_recibos'])
                    <td class="text-center">{{ $factura->recibos->count() }}</td>
                    @endif
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    @endif

    @if($filtros['incluir_recibos'] && $estadisticas['total_cobros'] > 0)
    <!-- Detalle de cobros -->
    <div class="page-break"></div>
    <div class="section-title">DETALLE DE COBROS</div>
    <table class="table">
        <thead>
            <tr>
                <th>Factura</th>
                <th>Número</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Moneda</th>
                <th>Forma de Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presupuestos as $presupuesto)
                @foreach($presupuesto->facturas as $factura)
                    @foreach($factura->recibos as $recibo)
                    <tr>
                        <td>{{ $factura->numero }}</td>
                        <td>{{ $recibo->numero }}</td>
                        <td>{{ \Carbon\Carbon::parse($recibo->fecha)->format('d/m/Y') }}</td>
                        <td class="text-right">{{ number_format($recibo->monto, 0, ',', '.') }}</td>
                        <td>{{ $recibo->moneda->simbolo ?? 'N/A' }}</td>
                        <td>{{ $recibo->forma_pago ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>{{ $empresa }} - Reporte generado el {{ $fecha_generacion }}</div>
        <div>Página <span class="pagenum"></span></div>
    </div>
</body>
</html>
