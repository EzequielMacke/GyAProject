<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Presupuesto</title>
    @include('partials.head')
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();

        // Calcular datos financieros
        $totalFacturado = $presupuesto->facturas->sum(function($factura) {
            return $factura->moneda_id == 2 ? $factura->monto * $factura->cotizacion : $factura->monto;
        });
        $totalCobrado = $presupuesto->facturas->sum(function($factura) {
            return $factura->recibos->sum(function($recibo) {
                return $recibo->moneda_id == 2 ? $recibo->monto * $recibo->cotizacion : $recibo->monto;
            });
        });
        $montoPresupuesto = $presupuesto->moneda_id == 2 ? $presupuesto->monto * $presupuesto->cotizacion : $presupuesto->monto;
        $saldoFacturar = $montoPresupuesto - $totalFacturado;
        $saldoCobrar = $totalFacturado - $totalCobrado;
        $porcentajeFacturado = $montoPresupuesto > 0 ? ($totalFacturado / $montoPresupuesto) * 100 : 0;
        $porcentajeCobrado = $totalFacturado > 0 ? ($totalCobrado / $totalFacturado) * 100 : 0;
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('ver', 1)->isEmpty())
        <script>
            window.location.href = "{{ url('/home') }}";
        </script>
    @endif
    <style>
        .info-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
        }
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .section-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
            margin: 0;
        }
        .section-header h5 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        .section-header i {
            margin-right: 10px;
            font-size: 1.2em;
        }
        .info-table {
            margin: 0;
            border: none;
        }
        .info-table th {
            background-color: #f8f9fa;
            border: none;
            padding: 15px;
            font-weight: 600;
            color: #495057;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }
        .info-table td {
            padding: 15px;
            border: none;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }
        .info-table tr:last-child th,
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .file-card {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            transition: all 0.3s ease;
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .file-card.has-file {
            border: 2px solid #28a745;
            background-color: #f8fff9;
        }
        .file-card.no-file {
            border: 2px dashed #6c757d;
            background-color: #f8f9fa;
        }
        .file-card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .currency-main {
            font-size: 1.1em;
            font-weight: 700;
            color: #2c3e50;
        }
        .currency-secondary {
            font-size: 0.85em;
            color: #6c757d;
            margin-top: 2px;
        }
        .progress-modern {
            height: 8px;
            border-radius: 10px;
            background-color: #e9ecef;
            overflow: hidden;
        }
        .progress-bar-modern {
            height: 100%;
            border-radius: 10px;
            transition: width 0.6s ease;
        }
        .metric-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            background: white;
            transition: all 0.3s ease;
        }
        .metric-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .metric-value {
            font-size: 1.5em;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .metric-label {
            color: #6c757d;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .breadcrumb-modern {
            background: none;
            padding: 0;
            margin: 0;
        }
        .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #6c757d;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-modern {
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .financial-summary {
            background-color: #17a2b8;
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
        }
        .financial-summary h5 {
            margin-bottom: 20px;
            font-weight: 700;
        }
        .border-left-primary {
            border-left: 4px solid #007bff;
        }
        .border-left-info {
            border-left: 4px solid #17a2b8;
        }
        .border-left-warning {
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-modern">
                                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Inicio</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('presupuestos.index') }}">Presupuestos</a></li>
                                    <li class="breadcrumb-item active">{{ $presupuesto->nombre }}</li>
                                </ol>
                            </nav>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h1 class="m-0 text-dark font-weight-bold">
                                        <i class="fas fa-file-invoice-dollar text-primary mr-2"></i>
                                        {{ $presupuesto->nombre }}
                                    </h1>
                                    <p class="text-muted mb-0">Detalles completos del presupuesto</p>
                                </div>
                                <div class="action-buttons">
                                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('editar', 1)->isNotEmpty())
                                        <a href="{{ route('presupuestos.edit', $presupuesto->id) }}" class="btn btn-warning btn-modern">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                    @endif
                                    <a href="{{ route('presupuestos.facturas.create', $presupuesto->id) }}" class="btn btn-success btn-modern">
                                        <i class="fas fa-plus"></i> Nueva Factura
                                    </a>
                                    <a href="{{ route('presupuestos.index') }}" class="btn btn-secondary btn-modern">
                                        <i class="fas fa-arrow-left"></i> Volver
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Resumen Financiero -->
                    <div class="financial-summary">
                        <h5><i class="fas fa-chart-line mr-2"></i>Resumen Financiero</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="metric-card bg-white">
                                    <div class="metric-value text-primary">
                                        @if($montoPresupuesto > 0)
                                            Gs. {{ number_format($montoPresupuesto, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                    <div class="metric-label">Monto Presupuesto</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="metric-card bg-white">
                                    <div class="metric-value text-success">
                                        Gs. {{ number_format($totalFacturado, 0, ',', '.') }}
                                    </div>
                                    <div class="metric-label">Facturado</div>
                                    <div class="progress-modern mt-2">
                                        <div class="progress-bar-modern bg-success" style="width: {{ min($porcentajeFacturado, 100) }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format($porcentajeFacturado, 1) }}%</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="metric-card bg-white">
                                    <div class="metric-value text-info">
                                        Gs. {{ number_format($totalCobrado, 0, ',', '.') }}
                                    </div>
                                    <div class="metric-label">Cobrado</div>
                                    <div class="progress-modern mt-2">
                                        <div class="progress-bar-modern bg-info" style="width: {{ min($porcentajeCobrado, 100) }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format($porcentajeCobrado, 1) }}%</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="metric-card bg-white">
                                    <div class="metric-value text-warning">
                                        Gs. {{ number_format($saldoCobrar, 0, ',', '.') }}
                                    </div>
                                    <div class="metric-label">Saldo por Cobrar</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Información General -->
                        <div class="col-md-6">
                            <div class="card info-card">
                                <div class="section-header">
                                    <h5><i class="fas fa-info-circle"></i>Información General</h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table info-table">
                                        <tr>
                                            <th style="width: 40%;">
                                                <i class="fas fa-tag text-primary mr-2"></i>Nombre
                                            </th>
                                            <td class="font-weight-bold">{{ $presupuesto->nombre }}</td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-building text-warning mr-2"></i>Obra
                                            </th>
                                            <td>{{ $presupuesto->obra->nombre ?? 'Sin obra' }}</td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-clipboard-list text-info mr-2"></i>Orden de Trabajo
                                            </th>
                                            <td>
                                                @if($presupuesto->orden_trabajo)
                                                    <span class="badge badge-info badge-pill">{{ $presupuesto->orden_trabajo }}</span>
                                                @else
                                                    <span class="text-muted">No asignada</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-cogs text-secondary mr-2"></i>Tipo de Trabajo
                                            </th>
                                            <td>{{ $presupuesto->tipoTrabajo->nombre ?? 'Sin tipo' }}</td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-flag text-success mr-2"></i>Estado
                                            </th>
                                            <td>
                                                <span class="status-badge" style="
                                                    background-color:
                                                        @switch($presupuesto->estado_id)
                                                            @case(1) #28a745; @break
                                                            @case(2) #ffc107; @break
                                                            @case(3) #007bff; @break
                                                            @case(4) #dc3545; @break
                                                            @default #6c757d;
                                                        @endswitch
                                                    color: #fff;
                                                ">
                                                    <i class="fas fa-circle"></i>
                                                    {{ $presupuesto->estado->descripcion ?? 'Sin estado' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-calendar text-primary mr-2"></i>Fecha de Aprobación
                                            </th>
                                            <td>
                                                @if($presupuesto->fecha)
                                                    <span class="font-weight-bold">
                                                        {{ \Carbon\Carbon::parse($presupuesto->fecha)->format('d/m/Y') }}
                                                    </span>
                                                    <small class="text-muted d-block">
                                                        {{ \Carbon\Carbon::parse($presupuesto->fecha)->diffForHumans() }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">No definida</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Información Financiera -->
                        <div class="col-md-6">
                            <div class="card info-card">
                                <div class="section-header">
                                    <h5><i class="fas fa-dollar-sign"></i>Información Financiera</h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table info-table">
                                        <tr>
                                            <th style="width: 40%;">
                                                <i class="fas fa-money-bill-wave text-success mr-2"></i>Monto
                                            </th>
                                            <td>
                                                @if($presupuesto->monto)
                                                    <div class="currency-main">
                                                        @if($presupuesto->moneda_id == 2 && $presupuesto->cotizacion)
                                                            {{ $presupuesto->moneda->simbolo }} {{ number_format($presupuesto->monto, 2, ',', '.') }}
                                                        @elseif($presupuesto->moneda)
                                                            {{ $presupuesto->moneda->simbolo }} {{ number_format($presupuesto->monto, 2, ',', '.') }}
                                                        @else
                                                            {{ number_format($presupuesto->monto, 2, ',', '.') }}
                                                        @endif
                                                    </div>
                                                    @if($presupuesto->moneda_id == 2 && $presupuesto->cotizacion)
                                                        <div class="currency-secondary">
                                                            Gs. {{ number_format($presupuesto->monto * $presupuesto->cotizacion, 0, ',', '.') }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No definido</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-coins text-warning mr-2"></i>Moneda
                                            </th>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ $presupuesto->moneda->nombre ?? 'Sin moneda' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-exchange-alt text-info mr-2"></i>Cotización
                                            </th>
                                            <td>
                                                @if($presupuesto->cotizacion)
                                                    <span class="font-weight-bold">
                                                        {{ number_format($presupuesto->cotizacion, 4, ',', '.') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">No aplica</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-user-check text-primary mr-2"></i>Usuario que Cargó
                                            </th>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                    {{ $presupuesto->usuario->nombre ?? 'Sin usuario' }}
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <i class="fas fa-clock text-secondary mr-2"></i>Fecha de Creación
                                            </th>
                                            <td>
                                                <span class="font-weight-bold">
                                                    {{ $presupuesto->created_at ? $presupuesto->created_at->format('d/m/Y H:i') : '-' }}
                                                </span>
                                                @if($presupuesto->created_at)
                                                    <small class="text-muted d-block">
                                                        {{ $presupuesto->created_at->diffForHumans() }}
                                                    </small>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Archivos -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card info-card">
                                <div class="section-header">
                                    <h5><i class="fas fa-folder-open"></i>Documentos Adjuntos</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="file-card {{ $presupuesto->presupuesto ? 'has-file' : 'no-file' }}">
                                                @if($presupuesto->presupuesto)
                                                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                                    <h6 class="font-weight-bold">Presupuesto (PDF)</h6>
                                                    <p class="text-muted mb-3">{{ $presupuesto->presupuesto }}</p>
                                                    <a href="{{ route('presupuestos.download-file', ['id' => $presupuesto->id, 'type' => 'presupuesto']) }}"
                                                       target="_blank" class="btn btn-danger btn-modern">
                                                        <i class="fas fa-eye"></i> Ver Documento
                                                    </a>
                                                @else
                                                    <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                                                    <h6 class="text-muted">Presupuesto (PDF)</h6>
                                                    <p class="text-muted">No hay archivo cargado</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="file-card {{ $presupuesto->conformidad ? 'has-file' : 'no-file' }}">
                                                @if($presupuesto->conformidad)
                                                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                                    <h6 class="font-weight-bold">Nota de Conformidad (PDF)</h6>
                                                    <p class="text-muted mb-3">{{ $presupuesto->conformidad }}</p>
                                                    <a href="{{ route('presupuestos.download-file', ['id' => $presupuesto->id, 'type' => 'conformidad']) }}"
                                                       target="_blank" class="btn btn-danger btn-modern">
                                                        <i class="fas fa-eye"></i> Ver Documento
                                                    </a>
                                                @else
                                                    <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                                                    <h6 class="text-muted">Nota de Conformidad (PDF)</h6>
                                                    <p class="text-muted">No hay archivo cargado</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de Facturación Detallado -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card info-card">
                                <div class="section-header">
                                    <h5><i class="fas fa-chart-pie"></i>Estado de Facturación Detallado</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <div class="metric-card border-left-primary">
                                                <div class="metric-value text-success">
                                                    Gs. {{ number_format($totalFacturado, 0, ',', '.') }}
                                                </div>
                                                <div class="metric-label">Monto Facturado</div>
                                                <div class="progress-modern mt-2">
                                                    <div class="progress-bar-modern bg-success" style="width: {{ min($porcentajeFacturado, 100) }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ number_format($porcentajeFacturado, 1) }}% del presupuesto</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="metric-card border-left-info">
                                                <div class="metric-value text-info">
                                                    Gs. {{ number_format($totalCobrado, 0, ',', '.') }}
                                                </div>
                                                <div class="metric-label">Monto Cobrado</div>
                                                <div class="progress-modern mt-2">
                                                    <div class="progress-bar-modern bg-info" style="width: {{ min($porcentajeCobrado, 100) }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ number_format($porcentajeCobrado, 1) }}% de lo facturado</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="metric-card border-left-warning">
                                                <div class="metric-value text-warning">
                                                    Gs. {{ number_format($saldoFacturar, 0, ',', '.') }}
                                                </div>
                                                <div class="metric-label">Saldo a Facturar</div>
                                                <div class="metric-value text-danger mt-2">
                                                    Gs. {{ number_format($saldoCobrar, 0, ',', '.') }}
                                                </div>
                                                <div class="metric-label">Saldo a Cobrar</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lista de Facturas -->
                                    @if($presupuesto->facturas->count() > 0)
                                        <h6 class="font-weight-bold mb-3">
                                            <i class="fas fa-file-invoice text-success mr-2"></i>
                                            Facturas Emitidas ({{ $presupuesto->facturas->count() }})
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Número</th>
                                                        <th>Fecha</th>
                                                        <th>Concepto</th>
                                                        <th>Monto</th>
                                                        <th>Cobrado</th>
                                                        <th>% Cobrado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($presupuesto->facturas as $factura)
                                                        @php
                                                            $totalCobradoFactura = $factura->recibos->sum(function($recibo) {
                                                                return $recibo->moneda_id == 2 ? $recibo->monto * $recibo->cotizacion : $recibo->monto;
                                                            });
                                                            $montoFactura = $factura->moneda_id == 2 ? $factura->monto * $factura->cotizacion : $factura->monto;
                                                            $porcentajeCobradoFactura = $montoFactura > 0 ? ($totalCobradoFactura / $montoFactura) * 100 : 0;
                                                        @endphp
                                                        <tr>
                                                            <td><strong>{{ $factura->numero ?? 'S/N' }}</strong></td>
                                                            <td>{{ $factura->fecha ? \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') : '-' }}</td>
                                                            <td>{{ Str::limit($factura->concepto ?? '-', 40) }}</td>
                                                            <td>
                                                                <span class="font-weight-bold">Gs. {{ number_format($montoFactura, 0, ',', '.') }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="text-info font-weight-bold">Gs. {{ number_format($totalCobradoFactura, 0, ',', '.') }}</span>
                                                            </td>
                                                            <td>
                                                                <div class="progress-modern">
                                                                    <div class="progress-bar-modern bg-info" style="width: {{ min($porcentajeCobradoFactura, 100) }}%"></div>
                                                                </div>
                                                                <small class="text-muted">{{ number_format($porcentajeCobradoFactura, 1) }}%</small>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('facturas.recibos.create', $factura->id) }}" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-plus"></i> Recibo
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                                            <h6 class="text-muted">No hay facturas emitidas</h6>
                                            <a href="{{ route('presupuestos.facturas.create', $presupuesto->id) }}" class="btn btn-success btn-modern mt-2">
                                                <i class="fas fa-plus"></i> Emitir Primera Factura
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('partials.footer')
</body>
</html>
