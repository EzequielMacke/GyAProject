<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Obras</title>
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
        .content-header .breadcrumb {
            background: transparent;
            margin: 0;
            padding: 0;
        }
        .search-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: none;
            margin-bottom: 25px;
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
        .btn-add {
            background: #28a745;
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 25px;
            padding: 12px 30px;
            transition: all 0.3s ease;
        }
        .btn-add:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
            color: white;
        }
        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .search-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .badge-estado {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85em;
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
        .loading-row {
            display: none;
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #212529;
        }
        .text-primary {
            color: #007bff !important;
        }
        .text-success {
            color: #28a745 !important;
        }
        .text-muted {
            color: #6c757d !important;
        }
        @media (max-width: 768px) {
            .table-responsive {
                border-radius: 15px;
            }
            .content-header {
                text-align: center;
            }
        }
    </style>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('ver', 1)->isEmpty())
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
                                <i class="fas fa-building mr-3"></i>
                                Gestión de Obras
                            </h1>
                            <p class="mb-0 opacity-75">Administre y controle todas sus obras de construcción</p>
                        </div>
                        <div class="col-md-4 text-md-right mt-3 mt-md-0">
                            @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                            <a href="{{ route('obras.create') }}" class="btn btn-add" id="agregar-obra-btn">
                                <i class="fas fa-plus mr-2"></i>
                                Nueva Obra
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

                    <!-- Buscador -->
                    <div class="card search-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent border-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="search" class="form-control search-input border-0"
                                               placeholder="Buscar por nombre, dirección, contacto, peticionario..."
                                               autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Mostrando <span id="results-count">{{ $obras->count() }}</span> obras
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de obras -->
                    <div class="card table-card">
                        <div class="card-body p-0">
                            @if($obras->count() > 0)
                            <div class="table-responsive">
                                <table class="table" id="obras-table">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-hashtag mr-1"></i> ID</th>
                                            <th><i class="fas fa-building mr-1"></i> Nombre</th>
                                            <th><i class="fas fa-map-marker-alt mr-1"></i> Dirección</th>
                                            <th><i class="fas fa-user mr-1"></i> Contacto</th>
                                            <th><i class="fas fa-phone mr-1"></i> Teléfono</th>
                                            <th><i class="fas fa-user-tie mr-1"></i> Peticionario</th>
                                            <th><i class="fas fa-calendar mr-1"></i> Fecha</th>
                                            <th><i class="fas fa-flag mr-1"></i> Estado</th>
                                            <th><i class="fas fa-cogs mr-1"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($obras->reverse() as $obra)
                                            <tr data-obra-id="{{ $obra->id }}">
                                                <td><strong>#{{ $obra->id }}</strong></td>
                                                <td>
                                                    <div class="font-weight-bold text-primary">{{ $obra->nombre }}</div>
                                                    @if($obra->observacion)
                                                        <small class="text-muted">{{ Str::limit($obra->observacion, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                                                    {{ Str::limit($obra->direccion, 30) }}
                                                </td>
                                                <td>
                                                    <div>{{ $obra->contacto }}</div>
                                                    <small class="text-muted">por {{ $obra->usuario->nombre }}</small>
                                                </td>
                                                <td>
                                                    <a href="tel:{{ $obra->numero }}" class="text-decoration-none">
                                                        <i class="fas fa-phone text-success mr-1"></i>
                                                        {{ $obra->numero }}
                                                    </a>
                                                </td>
                                                <td>{{ $obra->peticionario }}</td>
                                                <td>
                                                    <div>{{ \Carbon\Carbon::parse($obra->fecha_carga)->format('d/m/Y') }}</div>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($obra->fecha_carga)->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    @php
                                                        $estadoColors = [
                                                            1 => 'success',
                                                            2 => 'primary',
                                                            3 => 'warning',
                                                            4 => 'danger'
                                                        ];
                                                        $estadoColor = $estadoColors[$obra->estado] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge badge-{{ $estadoColor }} badge-estado">
                                                        {{ $estados[$obra->estado] ?? 'Desconocido' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                                                        <a href="{{ route('obras.show', $obra->id) }}"
                                                           class="btn btn-info btn-sm btn-action"
                                                           data-toggle="tooltip" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @endif
                                                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('editar', 1)->isNotEmpty())
                                                        <a href="{{ route('obras.edit', $obra->id) }}"
                                                           class="btn btn-warning btn-sm btn-action"
                                                           data-toggle="tooltip" title="Editar obra">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="empty-state">
                                <i class="fas fa-building-slash"></i>
                                <h4>No hay obras registradas</h4>
                                <p class="text-muted">Comience agregando su primera obra para gestionar sus proyectos de construcción.</p>
                                @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                                <a href="{{ route('obras.create') }}" class="btn btn-add">
                                    <i class="fas fa-plus mr-2"></i>
                                    Crear Primera Obra
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

            // Funcionalidad de búsqueda mejorada
            const searchInput = document.getElementById('search');
            const tableRows = document.querySelectorAll('#obras-table tbody tr');
            const resultsCount = document.getElementById('results-count');

            if (searchInput && tableRows.length > 0) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    let visibleCount = 0;

                    tableRows.forEach(row => {
                        const cells = row.querySelectorAll('td');
                        const rowText = Array.from(cells).map(cell =>
                            cell.textContent.toLowerCase()
                        ).join(' ');

                        if (searchTerm === '' || rowText.includes(searchTerm)) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Actualizar contador de resultados
                    if (resultsCount) {
                        resultsCount.textContent = visibleCount;
                    }

                    // Mostrar mensaje si no hay resultados
                    const tableBody = document.querySelector('#obras-table tbody');
                    let noResultsRow = document.getElementById('no-results-row');

                    if (visibleCount === 0 && searchTerm !== '') {
                        if (!noResultsRow) {
                            noResultsRow = document.createElement('tr');
                            noResultsRow.id = 'no-results-row';
                            noResultsRow.innerHTML = `
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-search text-muted mb-2 d-block" style="font-size: 2rem;"></i>
                                    <h5 class="text-muted">No se encontraron resultados</h5>
                                    <p class="text-muted mb-0">Intente con otros términos de búsqueda</p>
                                </td>
                            `;
                            tableBody.appendChild(noResultsRow);
                        }
                    } else if (noResultsRow) {
                        noResultsRow.remove();
                    }
                });
            }

            // Atajo de teclado para agregar obra (Ctrl + 1)
            document.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === '1') {
                    event.preventDefault();
                    const addButton = document.getElementById('agregar-obra-btn');
                    if (addButton) {
                        addButton.click();
                    }
                }
            });

            // Atajo de teclado para foco en búsqueda (Ctrl + F)
            document.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === 'f') {
                    event.preventDefault();
                    if (searchInput) {
                        searchInput.focus();
                    }
                }
            });

            // Auto-dismiss alerts
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        });
    </script>
</body>
</html>
