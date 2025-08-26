<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos para Obra</title>
    @include('partials.head')
    <style>
        .content-header {
            background: #17a2b8;
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
        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: none;
            margin-bottom: 25px;
            padding: 20px;
            text-align: center;
        }
        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
        }
        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
        }
        .table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: none;
            overflow: hidden;
            margin-bottom: 25px;
        }
        .table-card .card-header {
            background: #f8f9fa;
            border-radius: 15px 15px 0 0;
            border-bottom: 1px solid #e9ecef;
            padding: 20px;
        }
        .search-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 20px;
            margin-bottom: 25px;
        }
        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .search-input:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
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
            border-bottom: 2px solid #e9ecef;
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
            padding: 8px 15px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .badge-estado {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85em;
        }
        .progress-bar-custom {
            background: #e9ecef;
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
            margin-top: 5px;
        }
        .progress-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        .obra-info {
            margin-bottom: 8px;
        }
        .obra-name {
            font-weight: bold;
            color: #495057;
        }
        .obra-address {
            font-size: 0.85em;
            color: #6c757d;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #17a2b8;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 8px;
        }
        .datetime-info {
            margin-bottom: 5px;
        }
        .date-text {
            font-weight: 500;
            color: #495057;
        }
        .time-text {
            font-size: 0.85em;
            color: #6c757d;
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
        .alert-custom {
            border-radius: 15px;
            border: none;
            padding: 20px;
            margin-bottom: 25px;
        }
        @media (max-width: 768px) {
            .content-header {
                text-align: center;
            }
            .table-responsive {
                border-radius: 15px;
            }
            .search-container {
                padding: 15px;
            }
        }
    </style>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_dep')->first()->id ?? null)->where('ver', 1)->isEmpty())
        <script>
            window.location.href = "{{ url('/home') }}";
        </script>
    @endif
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
                                <i class="fas fa-clipboard-list mr-3"></i>
                                Pedidos para Obra
                            </h1>
                            <p class="mb-0 opacity-75">Gestión y preparación de insumos para obras</p>
                        </div>
                        <div class="col-md-4 text-md-right mt-3 mt-md-0">
                            <span class="badge badge-light p-2">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ now()->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Mensajes de éxito -->
                    @if (session('success'))
                        <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Estadísticas -->
                    <div class="row mb-4">
                        @php
                            $totalPedidos = $pedobras->count();
                            $pendientes = $pedobras->where('estado', 1)->count();
                            $enPreparacion = $pedobras->where('estado', 2)->count();
                            $completados = $pedobras->where('estado', 3)->count();
                        @endphp

                        <div class="col-lg-3 col-md-6">
                            <div class="stats-card">
                                <div class="stats-icon text-info">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <h3 class="stats-number text-info">{{ $totalPedidos }}</h3>
                                <p class="stats-label">Total de Pedidos</p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="stats-card">
                                <div class="stats-icon text-warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h3 class="stats-number text-warning">{{ $pendientes }}</h3>
                                <p class="stats-label">Pendientes</p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="stats-card">
                                <div class="stats-icon text-primary">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <h3 class="stats-number text-primary">{{ $enPreparacion }}</h3>
                                <p class="stats-label">En Preparación</p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="stats-card">
                                <div class="stats-icon text-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h3 class="stats-number text-success">{{ $completados }}</h3>
                                <p class="stats-label">Completados</p>
                            </div>
                        </div>
                    </div>

                    <!-- Búsqueda -->
                    <div class="search-container">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-transparent border-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="search" class="form-control search-input border-0"
                                           placeholder="🔍 Buscar por obra, dirección, usuario o estado...">
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ $totalPedidos }} pedido{{ $totalPedidos != 1 ? 's' : '' }} encontrado{{ $totalPedidos != 1 ? 's' : '' }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Pedidos -->
                    <div class="table-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-list mr-2"></i>
                                Lista de Pedidos
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            @if($pedobras->count() > 0)
                            <div class="table-responsive">
                                <table class="table" id="pedidos-table">
                                    <thead>
                                        <tr>
                                            <th width="60"><i class="fas fa-hashtag mr-1"></i> ID</th>
                                            <th><i class="fas fa-building mr-1"></i> Obra</th>
                                            <th><i class="fas fa-user mr-1"></i> Creado por</th>
                                            <th><i class="fas fa-calendar-plus mr-1"></i> Fecha Pedido</th>
                                            <th><i class="fas fa-calendar-check mr-1"></i> Fecha Entrega</th>
                                            <th><i class="fas fa-boxes mr-1"></i> Progreso</th>
                                            <th><i class="fas fa-sticky-note mr-1"></i> Observación</th>
                                            <th><i class="fas fa-flag mr-1"></i> Estado</th>
                                            <th width="120"><i class="fas fa-cogs mr-1"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pedobras->sortByDesc('created_at') as $pedobra)
                                            <tr>
                                                <td><strong>#{{ $pedobra->id }}</strong></td>
                                                <td>
                                                    <div class="obra-info">
                                                        <div class="obra-name">{{ $pedobra->obra->nombre }}</div>
                                                        <div class="obra-address">
                                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                                            {{ Str::limit($pedobra->obra->direccion, 50) }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="user-info">
                                                        <div class="user-avatar">
                                                            {{ strtoupper(substr($pedobra->usuario->nombre, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div>{{ $pedobra->usuario->nombre }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="datetime-info">
                                                        <div class="date-text">{{ \Carbon\Carbon::parse($pedobra->fecha_pedido)->format('d/m/Y') }}</div>
                                                        <div class="time-text">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            {{ \Carbon\Carbon::parse($pedobra->created_at)->format('H:i:s') }}
                                                        </div>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($pedobra->created_at)->diffForHumans() }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="date-text">{{ \Carbon\Carbon::parse($pedobra->fecha_entrega)->format('d/m/Y') }}</div>
                                                    @php
                                                        $diasRestantes = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($pedobra->fecha_entrega), false);
                                                    @endphp
                                                    @if($diasRestantes >= 0)
                                                        <small class="text-success">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            {{ $diasRestantes }} día{{ $diasRestantes != 1 ? 's' : '' }}
                                                        </small>
                                                    @else
                                                        <small class="text-danger">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                                            Vencido
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $porcentaje = $pedobra->total_insumo > 0 ? ($pedobra->insumo_confirmado / $pedobra->total_insumo) * 100 : 0;
                                                    @endphp
                                                    <div>
                                                        <small class="text-muted">{{ $pedobra->insumo_confirmado }}/{{ $pedobra->total_insumo }} insumos</small>
                                                        <div class="progress-bar-custom">
                                                            <div class="progress-fill bg-{{ $porcentaje == 100 ? 'success' : ($porcentaje > 50 ? 'warning' : 'danger') }}"
                                                                 style="width: {{ $porcentaje }}%"></div>
                                                        </div>
                                                        <small class="text-muted">{{ number_format($porcentaje, 1) }}% completado</small>
                                                    </div>
                                                    @if($pedobra->insumo_faltante > 0)
                                                        <small class="text-danger">
                                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                                            {{ $pedobra->insumo_faltante }} faltante{{ $pedobra->insumo_faltante != 1 ? 's' : '' }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($pedobra->observacion)
                                                        <span data-toggle="tooltip" title="{{ $pedobra->observacion }}">
                                                            {{ Str::limit($pedobra->observacion, 30) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">Sin observaciones</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $estados_label[$pedobra->estado] ?? 'secondary' }} badge-estado">
                                                        {{ $estados[$pedobra->estado] ?? 'Desconocido' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_dep')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                                                        <a href="{{ route('preparobra.show', $pedobra->id) }}"
                                                           class="btn btn-primary btn-sm btn-action"
                                                           data-toggle="tooltip" title="Preparar Pedido">
                                                            <i class="fas fa-cogs mr-1"></i>
                                                            Preparar
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <h4>No hay pedidos registrados</h4>
                                <p class="text-muted">Aún no se han creado pedidos para obras.</p>
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
            // Funcionalidad de búsqueda mejorada
            const searchInput = document.getElementById('search');
            const tableRows = document.querySelectorAll('#pedidos-table tbody tr');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');

                    if (rowText.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Auto-dismiss alerts
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);

            // Enfocar búsqueda con Ctrl + F
            document.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === 'f') {
                    event.preventDefault();
                    searchInput.focus();
                }
            });
        });
    </script>
</body>
</html>
