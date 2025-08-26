{{-- filepath: c:\laragon\www\GyAProject\resources\views\presupuestos\index.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Presupuestos</title>
    @include('partials.head')
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('ver', 1)->isEmpty())
        <script>
            window.location.href = "{{ url('/home') }}";
        </script>
    @endif
    <style>
        .expandable-row {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .expandable-row:hover {
            background-color: #f8f9fa;
        }
        .expanded-content {
            display: none;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
        }
        .facturas-container {
            padding: 15px;
            margin: 5px 0;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .factura-item {
            border-left: 3px solid #28a745;
            background-color: #f8fff8;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .recibo-item {
            border-left: 3px solid #17a2b8;
            background-color: #f0fdff;
            padding: 8px;
            margin: 3px 0;
            border-radius: 3px;
            margin-left: 20px;
        }
        .currency-display {
            font-weight: bold;
        }
        .currency-secondary {
            font-size: 0.85em;
            color: #6c757d;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .btn-xs {
            padding: 2px 6px;
            font-size: 0.75rem;
        }
        .expand-icon {
            transition: transform 0.3s;
        }
        .expand-icon.rotated {
            transform: rotate(90deg);
        }
        .totals-summary {
            background-color: #e3f2fd;
            border-radius: 5px;
            padding: 8px;
            margin-top: 10px;
            font-size: 0.85em;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tableRows = document.querySelectorAll('#presupuestos-table tbody tr.main-row');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                    if (rowText.includes(searchTerm)) {
                        row.style.display = '';
                        // Ocultar fila expandida si existe
                        const expandedRow = row.nextElementSibling;
                        if (expandedRow && expandedRow.classList.contains('expanded-row')) {
                            expandedRow.style.display = 'none';
                        }
                    } else {
                        row.style.display = 'none';
                        // Ocultar fila expandida si existe
                        const expandedRow = row.nextElementSibling;
                        if (expandedRow && expandedRow.classList.contains('expanded-row')) {
                            expandedRow.style.display = 'none';
                        }
                    }
                });
            });

            // Función para expandir/contraer filas
            window.toggleFacturas = function(presupuestoId) {
                const expandedRow = document.getElementById('expanded-' + presupuestoId);
                const expandIcon = document.getElementById('expand-icon-' + presupuestoId);

                if (expandedRow.style.display === 'none' || expandedRow.style.display === '') {
                    expandedRow.style.display = 'table-row';
                    expandIcon.classList.add('rotated');
                } else {
                    expandedRow.style.display = 'none';
                    expandIcon.classList.remove('rotated');
                }
            };

            // Función para expandir/contraer recibos
            window.toggleRecibos = function(facturaId) {
                const recibosContainer = document.getElementById('recibos-' + facturaId);
                const expandIcon = document.getElementById('recibos-icon-' + facturaId);

                if (recibosContainer.style.display === 'none' || recibosContainer.style.display === '') {
                    recibosContainer.style.display = 'block';
                    expandIcon.classList.add('rotated');
                } else {
                    recibosContainer.style.display = 'none';
                    expandIcon.classList.remove('rotated');
                }
            };

        });
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-file-invoice-dollar text-primary"></i>
                                Listado de Presupuestos
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-sm-right">
                                @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                                <a href="{{ route('presupuestos.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus mr-1"></i>
                                    Agregar Presupuesto
                                </a>
                                @endif
                                <a href="{{ route('presupuestos.reportes') }}" class="btn btn-danger mt-2">
                                    <i class="fas fa-file-pdf mr-1"></i>
                                    Generar Reportes
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input type="text" id="search" name="search" class="form-control"
                                       placeholder="Buscar presupuestos por obra, nombre, tipo de trabajo...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-list mr-2"></i>
                                Presupuestos Registrados
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-info">{{ $presupuestos->count() }} presupuestos</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-0" id="presupuestos-table" style="font-size: 0.85em;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" style="width: 3%;"></th>
                                            <th class="text-center" style="width: 10%;">Obra</th>
                                            <th class="text-center" style="width: 10%;">Nombre</th>
                                            <th class="text-center" style="width: 8%;">Tipo</th>
                                            <th class="text-center" style="width: 7%;">Fecha</th>
                                            <th class="text-center" style="width: 7%;">Estado</th>
                                            <th class="text-center" style="width: 10%;">Monto</th>
                                            <th class="text-center" style="width: 9%;">Facturado</th>
                                            <th class="text-center" style="width: 6%;">% Fact.</th>
                                            <th class="text-center" style="width: 9%;">Cobrado</th>
                                            <th class="text-center" style="width: 6%;">% Cobr.</th>
                                            <th class="text-center" style="width: 9%;">Saldo</th>
                                            <th class="text-center" style="width: 6%;">% Saldo</th>
                                            <th class="text-center" style="width: 6%;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($presupuestos->reverse() as $presupuesto)
                                            @php
                                                $totalFacturado = $presupuesto->facturas->sum(function($factura) {
                                                    return $factura->moneda_id == 2 ? $factura->monto * $factura->cotizacion : $factura->monto;
                                                });
                                                $totalCobrado = $presupuesto->facturas->sum(function($factura) {
                                                    return $factura->recibos->sum(function($recibo) {
                                                        return $recibo->moneda_id == 2 ? $recibo->monto * $recibo->cotizacion : $recibo->monto;
                                                    });
                                                });
                                                $saldoPendiente = $totalFacturado - $totalCobrado;
                                                $montoPresupuesto = $presupuesto->moneda_id == 2 ? $presupuesto->monto * $presupuesto->cotizacion : $presupuesto->monto;
                                                $porcentajeFacturado = $montoPresupuesto > 0 ? ($totalFacturado / $montoPresupuesto) * 100 : 0;
                                                $porcentajeCobrado = $totalFacturado > 0 ? ($totalCobrado / $totalFacturado) * 100 : 0;
                                                $porcentajeSaldo = $totalFacturado > 0 ? ($saldoPendiente / $totalFacturado) * 100 : 0;
                                            @endphp
                                            <!-- Fila principal del presupuesto -->
                                            <tr class="main-row expandable-row" onclick="toggleFacturas({{ $presupuesto->id }})">
                                                <td class="text-center">
                                                    <i class="fas fa-chevron-right expand-icon" id="expand-icon-{{ $presupuesto->id }}"></i>
                                                </td>
                                                <td class="text-center">
                                                    <div class="font-weight-bold">{{ $presupuesto->obra->nombre ?? '-' }}</div>
                                                    @if($presupuesto->orden_trabajo)
                                                        <span class="badge badge-info badge-sm">{{ $presupuesto->orden_trabajo }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $presupuesto->nombre }}</td>
                                                <td class="text-center">{{ $presupuesto->tipoTrabajo->nombre ?? '-' }}</td>
                                                <td class="text-center">
                                                    {{ $presupuesto->fecha ? \Carbon\Carbon::parse($presupuesto->fecha)->format('d/m/Y') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill" style="
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
                                                        {{ $presupuesto->estado->descripcion ?? 'Sin estado' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($presupuesto->monto)
                                                        @if($presupuesto->moneda_id == 2 && $presupuesto->cotizacion)
                                                            <div class="currency-display">Gs. {{ number_format($presupuesto->monto * $presupuesto->cotizacion, 0, ',', '.') }}</div>
                                                            <div class="currency-secondary">{{ $presupuesto->moneda->simbolo }} {{ number_format($presupuesto->monto, 2, ',', '.') }}</div>
                                                        @else
                                                            <div class="currency-display">{{ $presupuesto->moneda->simbolo ?? 'Gs.' }} {{ number_format($presupuesto->monto, 2, ',', '.') }}</div>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($totalFacturado > 0)
                                                        <div class="currency-display text-success">Gs. {{ number_format($totalFacturado, 0, ',', '.') }}</div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($porcentajeFacturado > 0)
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar bg-success" style="width: {{ min($porcentajeFacturado, 100) }}%">
                                                                {{ number_format($porcentajeFacturado, 1) }}%
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">0%</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($totalCobrado > 0)
                                                        <div class="currency-display text-info">Gs. {{ number_format($totalCobrado, 0, ',', '.') }}</div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($porcentajeCobrado > 0)
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar bg-info" style="width: {{ min($porcentajeCobrado, 100) }}%">
                                                                {{ number_format($porcentajeCobrado, 1) }}%
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">0%</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($saldoPendiente > 0)
                                                        <div class="currency-display text-warning">Gs. {{ number_format($saldoPendiente, 0, ',', '.') }}</div>
                                                    @elseif($saldoPendiente < 0)
                                                        <div class="currency-display text-danger">Gs. {{ number_format($saldoPendiente, 0, ',', '.') }}</div>
                                                    @else
                                                        <span class="text-success">Gs. 0</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($porcentajeSaldo > 0)
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar bg-warning" style="width: {{ min($porcentajeSaldo, 100) }}%">
                                                                {{ number_format($porcentajeSaldo, 1) }}%
                                                            </div>
                                                        </div>
                                                    @elseif($porcentajeSaldo < 0)
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar bg-danger" style="width: {{ min(abs($porcentajeSaldo), 100) }}%">
                                                                {{ number_format($porcentajeSaldo, 1) }}%
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="badge badge-success">100%</span>
                                                    @endif
                                                </td>
                                                <td class="text-center action-buttons" onclick="event.stopPropagation();">
                                                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                                                        <a href="{{ route('presupuestos.show', $presupuesto->id) }}" class="btn btn-secondary btn-xs" data-toggle="tooltip" title="Ver">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('editar', 1)->isNotEmpty())
                                                        <a href="{{ route('presupuestos.edit', $presupuesto->id) }}" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Editar">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                    @endif
                                                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('eliminar', 1)->isNotEmpty() && $presupuesto->facturas->count() == 0)
                                                        <form action="{{ route('presupuestos.destroy', $presupuesto->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Eliminar"
                                                                    onclick="return confirm('¿Está seguro de eliminar este presupuesto?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>

                                            <!-- Fila expandible con facturas -->
                                            <tr class="expanded-row" id="expanded-{{ $presupuesto->id }}" style="display: none;">
                                                <td colspan="14">
                                                    <div class="facturas-container">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h6 class="mb-0">
                                                                <i class="fas fa-file-invoice text-success mr-2"></i>
                                                                Facturas del Presupuesto
                                                            </h6>
                                                            <a href="{{ route('presupuestos.facturas.create', $presupuesto->id) }}" class="btn btn-success btn-sm">
                                                                <i class="fas fa-plus mr-1"></i>
                                                                Agregar Factura
                                                            </a>
                                                        </div>

                                                        @if($presupuesto->facturas->count() > 0)
                                                            @foreach($presupuesto->facturas as $factura)
                                                                @php
                                                                    $totalCobradoFactura = $factura->recibos->sum(function($recibo) {
                                                                        return $recibo->moneda_id == 2 ? $recibo->monto * $recibo->cotizacion : $recibo->monto;
                                                                    });
                                                                    $montoFactura = $factura->moneda_id == 2 ? $factura->monto * $factura->cotizacion : $factura->monto;
                                                                    $porcentajeCobradoFactura = $montoFactura > 0 ? ($totalCobradoFactura / $montoFactura) * 100 : 0;
                                                                @endphp
                                                                <div class="factura-item">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-md-2">
                                                                            <strong>Factura #{{ $factura->numero ?? 'S/N' }}</strong>
                                                                            @if($factura->adjunto)
                                                                                <br><small class="text-success">
                                                                                    <i class="fas fa-paperclip mr-1"></i>Con adjunto
                                                                                </small>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <small class="text-muted">Fecha:</small><br>
                                                                            {{ $factura->fecha ? \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') : '-' }}
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <small class="text-muted">Concepto:</small><br>
                                                                            {{ Str::limit($factura->concepto ?? '-', 30) }}
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <small class="text-muted">Monto:</small><br>
                                                                            @if($factura->monto)
                                                                                @if($factura->moneda_id == 2 && $factura->cotizacion)
                                                                                    <div class="currency-display">Gs. {{ number_format($factura->monto * $factura->cotizacion, 0, ',', '.') }}</div>
                                                                                    <div class="currency-secondary">{{ $factura->moneda->simbolo }} {{ number_format($factura->monto, 2, ',', '.') }}</div>
                                                                                @else
                                                                                    <div class="currency-display">{{ $factura->moneda->simbolo ?? 'Gs.' }} {{ number_format($factura->monto, 2, ',', '.') }}</div>
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <small class="text-muted">Cobrado:</small><br>
                                                                            @if($totalCobradoFactura > 0)
                                                                                <div class="currency-display text-info">Gs. {{ number_format($totalCobradoFactura, 0, ',', '.') }}</div>
                                                                                <div class="progress mt-1" style="height: 15px;">
                                                                                    <div class="progress-bar bg-info" style="width: {{ min($porcentajeCobradoFactura, 100) }}%">
                                                                                        {{ number_format($porcentajeCobradoFactura, 1) }}%
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                <span class="text-muted">Sin cobros</span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <button class="btn btn-info btn-xs mr-1" onclick="toggleRecibos({{ $factura->id }})">
                                                                                <i class="fas fa-chevron-right expand-icon" id="recibos-icon-{{ $factura->id }}"></i>
                                                                                Recibos
                                                                            </button>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-secondary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <i class="fas fa-cog"></i>
                                                                                </button>
                                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                                    <a class="dropdown-item" href="{{ route('facturas.edit', $factura->id) }}">
                                                                                        <i class="fas fa-edit mr-1"></i> Editar
                                                                                    </a>
                                                                                    @if($factura->adjunto)
                                                                                        <a class="dropdown-item" href="{{ route('facturas.download-adjunto', $factura->id) }}" target="_blank">
                                                                                            <i class="fas fa-file-pdf text-danger mr-1"></i> Ver Documento
                                                                                        </a>
                                                                                    @endif
                                                                                    @if($factura->recibos->count() == 0)
                                                                                        <form action="{{ route('facturas.destroy', $factura->id) }}" method="POST" style="display: inline;">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                            <button type="submit" class="dropdown-item text-danger" style="border: none; background: none; width: 100%; text-align: left;"
                                                                                                    onclick="return confirm('¿Está seguro de eliminar esta factura? Esta acción no se puede deshacer.')">
                                                                                                <i class="fas fa-trash mr-1"></i> Eliminar
                                                                                            </button>
                                                                                        </form>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Recibos de la factura -->
                                                                    <div id="recibos-{{ $factura->id }}" style="display: none;">
                                                                        <div class="mt-3">
                                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                <h6 class="mb-0">
                                                                                    <i class="fas fa-receipt text-info mr-2"></i>
                                                                                    Recibos de la Factura
                                                                                    @if($totalCobradoFactura > 0)
                                                                                        <span class="badge badge-info ml-2">Total: Gs. {{ number_format($totalCobradoFactura, 0, ',', '.') }}</span>
                                                                                    @endif
                                                                                </h6>
                                                                                <a href="{{ route('facturas.recibos.create', $factura->id) }}" class="btn btn-info btn-xs">
                                                                                    <i class="fas fa-plus mr-1"></i>
                                                                                    Agregar Recibo
                                                                                </a>
                                                                            </div>

                                                                            @if($factura->recibos->count() > 0)
                                                                                @foreach($factura->recibos as $recibo)
                                                                                    <div class="recibo-item">
                                                                                        <div class="row align-items-center">
                                                                                            <div class="col-md-2">
                                                                                                <strong>Recibo #{{ $recibo->numero ?? 'S/N' }}</strong>
                                                                                            </div>
                                                                                            <div class="col-md-2">
                                                                                                <small class="text-muted">Fecha:</small><br>
                                                                                                {{ $recibo->fecha ? \Carbon\Carbon::parse($recibo->fecha)->format('d/m/Y') : '-' }}
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <small class="text-muted">Concepto:</small><br>
                                                                                                {{ $recibo->concepto ?? '-' }}
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <small class="text-muted">Monto:</small><br>
                                                                                                @if($recibo->monto)
                                                                                                    @if($recibo->moneda_id == 2 && $recibo->cotizacion)
                                                                                                        <div class="currency-display">Gs. {{ number_format($recibo->monto * $recibo->cotizacion, 0, ',', '.') }}</div>
                                                                                                        <div class="currency-secondary">{{ $recibo->moneda->simbolo }} {{ number_format($recibo->monto, 2, ',', '.') }}</div>
                                                                                                    @else
                                                                                                        <div class="currency-display">{{ $recibo->moneda->simbolo ?? 'Gs.' }} {{ number_format($recibo->monto, 2, ',', '.') }}</div>
                                                                                                    @endif
                                                                                                @endif
                                                                                            </div>
                                                                                            <div class="col-md-2">
                                                                                                <div class="dropdown">
                                                                                                    <button class="btn btn-secondary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                        <i class="fas fa-cog"></i>
                                                                                                    </button>
                                                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                                                        <a class="dropdown-item" href="{{ route('recibos.edit', $recibo->id) }}">
                                                                                                            <i class="fas fa-edit mr-1"></i> Editar
                                                                                                        </a>
                                                                                                        <form action="{{ route('recibos.destroy', $recibo->id) }}" method="POST" style="display: inline;">
                                                                                                            @csrf
                                                                                                            @method('DELETE')
                                                                                                            <button type="submit" class="dropdown-item text-danger" style="border: none; background: none; width: 100%; text-align: left;"
                                                                                                                    onclick="return confirm('¿Está seguro de eliminar este recibo?')">
                                                                                                                <i class="fas fa-trash mr-1"></i> Eliminar
                                                                                                            </button>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach

                                                                                <!-- Resumen de la factura -->
                                                                                <div class="mt-3 p-2 bg-light rounded">
                                                                                    <div class="row">
                                                                                        <div class="col-md-4">
                                                                                            <small><strong>Total Factura:</strong></small><br>
                                                                                            <span class="text-primary">Gs. {{ number_format($montoFactura, 0, ',', '.') }}</span>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <small><strong>Total Cobrado:</strong></small><br>
                                                                                            <span class="text-success">Gs. {{ number_format($totalCobradoFactura, 0, ',', '.') }}</span>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <small><strong>Saldo Pendiente:</strong></small><br>
                                                                                            <span class="text-warning">Gs. {{ number_format($montoFactura - $totalCobradoFactura, 0, ',', '.') }}</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                <div class="text-center text-muted py-3">
                                                                                    <i class="fas fa-receipt fa-2x mb-2 opacity-50"></i>
                                                                                    <p>No hay recibos registrados para esta factura</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                            <!-- Resumen de totales -->
                                                            <div class="totals-summary">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <strong>Total Facturado:</strong><br>
                                                                        <span class="text-success">Gs. {{ number_format($totalFacturado, 0, ',', '.') }}</span>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <strong>Total Cobrado:</strong><br>
                                                                        <span class="text-info">Gs. {{ number_format($totalCobrado, 0, ',', '.') }}</span>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <strong>% Cobrado:</strong><br>
                                                                        <div class="progress" style="height: 20px;">
                                                                            <div class="progress-bar bg-info" style="width: {{ min($porcentajeCobrado, 100) }}%">
                                                                                {{ number_format($porcentajeCobrado, 1) }}%
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <strong>Saldo Pendiente:</strong><br>
                                                                        <span class="text-warning">Gs. {{ number_format($totalFacturado - $totalCobrado, 0, ',', '.') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-center text-muted py-4">
                                                                <i class="fas fa-file-invoice fa-3x mb-3 opacity-50"></i>
                                                                <h5>No hay facturas registradas</h5>
                                                                <p>Haga clic en "Agregar Factura" para crear la primera factura de este presupuesto</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
