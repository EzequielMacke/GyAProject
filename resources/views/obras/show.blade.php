<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Obra - {{ $obra->nombre }}</title>
    @include('partials.head')
    <style>
        .content-header {
            background: #007bff;
            color: white;
            border-radius: 0 0 20px 20px;
            margin-bottom: 30px;
            padding: 30px 0;
        }
        .content-header h1 {
            color: white;
            font-weight: 600;
            margin: 0;
        }
        .info-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: none;
            margin-bottom: 25px;
        }
        .info-card .card-header {
            background: #f8f9fa;
            border-radius: 15px 15px 0 0;
            border-bottom: 1px solid #e9ecef;
            padding: 20px;
        }
        .info-card .card-body {
            padding: 25px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-section h5 {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        .info-row {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 5px;
        }
        .info-value {
            color: #495057;
            font-size: 16px;
        }
        .table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: none;
            overflow: hidden;
        }
        .table {
            margin: 0;
        }
        .table thead th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
            padding: 15px;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-top: 1px solid #f1f3f4;
        }
        .table tbody tr:hover {
            background-color: #f8f9ff;
            transition: all 0.3s ease;
        }
        .btn-action {
            margin: 2px;
            border-radius: 8px;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .btn-back {
            background: #6c757d;
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 25px;
            padding: 12px 30px;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
            color: white;
        }
        .btn-edit {
            background: #ffc107;
            border: none;
            color: #212529;
            font-weight: 600;
            border-radius: 25px;
            padding: 12px 30px;
            transition: all 0.3s ease;
        }
        .btn-edit:hover {
            background: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
            color: #212529;
        }
        .badge-estado {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.9em;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-primary {
            background-color: #007bff;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        .contact-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .contact-info a {
            color: #007bff;
            text-decoration: none;
        }
        .contact-info a:hover {
            text-decoration: underline;
        }
        .status-badge-large {
            font-size: 1.1em;
            padding: 10px 20px;
        }
        @media (max-width: 768px) {
            .content-header {
                text-align: center;
            }
            .info-card .card-body {
                padding: 15px;
            }
        }
    </style>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')

        <div class="content-wrapper">
            <!-- Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2">
                                <i class="fas fa-building mr-3"></i>
                                {{ $obra->nombre }}
                            </h1>
                            <p class="mb-0 opacity-75">ID: #{{ $obra->id }} | Información detallada de la obra</p>
                        </div>
                        <div class="col-md-4 text-md-right mt-3 mt-md-0">
                            <a href="{{ route('obras.index') }}" class="btn btn-back mr-2">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver
                            </a>
                            @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('editar', 1)->isNotEmpty())
                            <a href="{{ route('obras.edit', $obra->id) }}" class="btn btn-edit">
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Mensajes de éxito -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Información General de la Obra -->
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-info-circle mr-2"></i>
                                Información General
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-section">
                                        <h5><i class="fas fa-building text-primary mr-2"></i>Datos de la Obra</h5>

                                        <div class="info-row">
                                            <div class="info-label">Nombre de la Obra:</div>
                                            <div class="info-value font-weight-bold">{{ $obra->nombre }}</div>
                                        </div>

                                        <div class="info-row">
                                            <div class="info-label">Dirección:</div>
                                            <div class="info-value">
                                                <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                                                {{ $obra->direccion }}
                                            </div>
                                        </div>

                                        <div class="info-row">
                                            <div class="info-label">Estado:</div>
                                            <div class="info-value">
                                                @php
                                                    $estadoColors = [
                                                        1 => 'success',
                                                        2 => 'primary',
                                                        3 => 'warning',
                                                        4 => 'danger'
                                                    ];
                                                    $estadoColor = $estadoColors[$obra->estado] ?? 'secondary';
                                                @endphp
                                                <span class="badge badge-{{ $estadoColor }} badge-estado status-badge-large">
                                                    {{ $estados[$obra->estado] ?? 'Desconocido' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="info-row">
                                            <div class="info-label">Fecha de Carga:</div>
                                            <div class="info-value">
                                                <i class="fas fa-calendar text-muted mr-1"></i>
                                                {{ \Carbon\Carbon::parse($obra->fecha_carga)->format('d/m/Y') }}
                                                <small class="text-muted">({{ \Carbon\Carbon::parse($obra->fecha_carga)->diffForHumans() }})</small>
                                            </div>
                                        </div>

                                        <div class="info-row">
                                            <div class="info-label">Creado por:</div>
                                            <div class="info-value">
                                                <i class="fas fa-user text-muted mr-1"></i>
                                                {{ $obra->usuario->nombre }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-section">
                                        <h5><i class="fas fa-user-tie text-warning mr-2"></i>Contacto Principal</h5>

                                        <div class="contact-info">
                                            <div class="info-label">Nombre del Contacto:</div>
                                            <div class="info-value font-weight-bold">{{ $obra->contacto }}</div>

                                            <div class="info-label mt-2">Teléfono:</div>
                                            <div class="mt-1">
                                                <a href="tel:{{ $obra->numero }}" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-phone mr-1"></i>
                                                    {{ $obra->numero }}
                                                </a>
                                            </div>
                                        </div>

                                        <div class="info-row">
                                            <div class="info-label">Peticionario:</div>
                                            <div class="info-value">{{ $obra->peticionario }}</div>
                                        </div>

                                        @if($obra->correo_pet)
                                        <div class="info-row">
                                            <div class="info-label">Email Peticionario:</div>
                                            <div class="info-value">
                                                <a href="mailto:{{ $obra->correo_pet }}">
                                                    <i class="fas fa-envelope mr-1"></i>
                                                    {{ $obra->correo_pet }}
                                                </a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($obra->observacion)
                            <div class="info-section">
                                <h5><i class="fas fa-sticky-note text-warning mr-2"></i>Observaciones</h5>
                                <div class="info-value">
                                    <div class="alert alert-light">
                                        {{ $obra->observacion }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Información de Facturación -->
                    @if($obra->ruc || $obra->razon_social || $obra->direccion_fac || $obra->correo_fac)
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-file-invoice text-success mr-2"></i>
                                Información de Facturación
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @if($obra->ruc)
                                    <div class="info-row">
                                        <div class="info-label">RUC:</div>
                                        <div class="info-value font-weight-bold">{{ $obra->ruc }}</div>
                                    </div>
                                    @endif

                                    @if($obra->razon_social)
                                    <div class="info-row">
                                        <div class="info-label">Razón Social:</div>
                                        <div class="info-value">{{ $obra->razon_social }}</div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($obra->direccion_fac)
                                    <div class="info-row">
                                        <div class="info-label">Dirección de Facturación:</div>
                                        <div class="info-value">{{ $obra->direccion_fac }}</div>
                                    </div>
                                    @endif

                                    @if($obra->correo_fac)
                                    <div class="info-row">
                                        <div class="info-label">Email de Facturación:</div>
                                        <div class="info-value">
                                            <a href="mailto:{{ $obra->correo_fac }}">
                                                <i class="fas fa-envelope mr-1"></i>
                                                {{ $obra->correo_fac }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Responsable de la Obra -->
                    @if($obra->nombre_obr || $obra->telefono_obr || $obra->correo_obr)
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-hard-hat text-primary mr-2"></i>
                                Responsable de la Obra
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @if($obra->nombre_obr)
                                    <div class="info-row">
                                        <div class="info-label">Nombre del Responsable:</div>
                                        <div class="info-value font-weight-bold">{{ $obra->nombre_obr }}</div>
                                    </div>
                                    @endif

                                    @if($obra->telefono_obr)
                                    <div class="info-row">
                                        <div class="info-label">Teléfono:</div>
                                        <div class="info-value">
                                            <a href="tel:{{ $obra->telefono_obr }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-phone mr-1"></i>
                                                {{ $obra->telefono_obr }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($obra->correo_obr)
                                    <div class="info-row">
                                        <div class="info-label">Email:</div>
                                        <div class="info-value">
                                            <a href="mailto:{{ $obra->correo_obr }}">
                                                <i class="fas fa-envelope mr-1"></i>
                                                {{ $obra->correo_obr }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Administrador de la Obra -->
                    @if($obra->nombre_adm || $obra->telefono_adm || $obra->correo_adm)
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-user-cog text-warning mr-2"></i>
                                Administrador de la Obra
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @if($obra->nombre_adm)
                                    <div class="info-row">
                                        <div class="info-label">Nombre del Administrador:</div>
                                        <div class="info-value font-weight-bold">{{ $obra->nombre_adm }}</div>
                                    </div>
                                    @endif

                                    @if($obra->telefono_adm)
                                    <div class="info-row">
                                        <div class="info-label">Teléfono:</div>
                                        <div class="info-value">
                                            <a href="tel:{{ $obra->telefono_adm }}" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-phone mr-1"></i>
                                                {{ $obra->telefono_adm }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($obra->correo_adm)
                                    <div class="info-row">
                                        <div class="info-label">Email:</div>
                                        <div class="info-value">
                                            <a href="mailto:{{ $obra->correo_adm }}">
                                                <i class="fas fa-envelope mr-1"></i>
                                                {{ $obra->correo_adm }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Presupuestos Asociados -->
                    <div class="card table-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="mb-0">
                                        <i class="fas fa-calculator text-primary mr-2"></i>
                                        Presupuestos Asociados
                                    </h4>
                                </div>
                                <div class="col-md-4 text-md-right">
                                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                                    <a href="{{ route('presupuestos.create') }}?obra_id={{ $obra->id }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus mr-1"></i>
                                        Nuevo Presupuesto
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($obra->presupuestos->count() > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-hashtag mr-1"></i> ID</th>
                                            <th><i class="fas fa-calendar mr-1"></i> Fecha</th>
                                            <th><i class="fas fa-tools mr-1"></i> Tipo de Trabajo</th>
                                            <th><i class="fas fa-dollar-sign mr-1"></i> Monto</th>
                                            <th><i class="fas fa-money-bill mr-1"></i> Moneda</th>
                                            <th><i class="fas fa-flag mr-1"></i> Estado</th>
                                            <th><i class="fas fa-user mr-1"></i> Usuario</th>
                                            <th><i class="fas fa-cogs mr-1"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($obra->presupuestos->sortByDesc('fecha') as $presupuesto)
                                            <tr>
                                                <td><strong>#{{ $presupuesto->id }}</strong></td>
                                                <td>
                                                    <div>{{ \Carbon\Carbon::parse($presupuesto->fecha)->format('d/m/Y') }}</div>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($presupuesto->fecha)->diffForHumans() }}</small>
                                                </td>
                                                <td>{{ $presupuesto->tipoTrabajo->nombre ?? 'N/A' }}</td>
                                                <td class="text-right font-weight-bold">
                                                    {{ number_format($presupuesto->monto, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ $presupuesto->moneda->simbolo ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $estadoPresColors = [
                                                            1 => 'warning',   // Pendiente
                                                            2 => 'success',   // Aprobado
                                                            3 => 'danger',    // Rechazado
                                                            4 => 'secondary'  // Cancelado
                                                        ];
                                                        $estadoPresColor = $estadoPresColors[$presupuesto->estado_id] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge badge-{{ $estadoPresColor }} badge-estado">
                                                        {{ $presupuesto->estado->descripcion ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>{{ $presupuesto->usuario->nombre ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="action-buttons">
                                                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                                                        <a href="{{ route('presupuestos.show', $presupuesto->id) }}"
                                                           class="btn btn-info btn-sm btn-action"
                                                           data-toggle="tooltip" title="Ver presupuesto">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @endif
                                                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('editar', 1)->isNotEmpty())
                                                        <a href="{{ route('presupuestos.edit', $presupuesto->id) }}"
                                                           class="btn btn-warning btn-sm btn-action"
                                                           data-toggle="tooltip" title="Editar presupuesto">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="7" class="text-right">Total de presupuestos:</th>
                                            <th class="text-center">{{ $obra->presupuestos->count() }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            @else
                            <div class="empty-state">
                                <i class="fas fa-calculator"></i>
                                <h4>No hay presupuestos asociados</h4>
                                <p class="text-muted">Esta obra aún no tiene presupuestos registrados.</p>
                                @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                                <a href="{{ route('presupuestos.create') }}?obra_id={{ $obra->id }}" class="btn btn-success">
                                    <i class="fas fa-plus mr-2"></i>
                                    Crear Primer Presupuesto
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Auto-dismiss alerts
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        });
    </script>
</body>
</html>
