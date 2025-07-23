<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Pedidos para obra</title>
    @include('partials.head')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
            const editarBtns = document.querySelectorAll('.editar-btn');
            const usuarioActualId = {{ Auth::id() }};
            const areaActual = "{{ session('usuario_area_id') }}";
            const alertContainer = document.getElementById('alert-container');

            editarBtns.forEach(btn => {
                btn.addEventListener('click', function (event) {
                    const usuarioId = btn.getAttribute('data-usuario-id');
                    const area = btn.getAttribute('data-area');

                    if (usuarioActualId != usuarioId && areaActual != 1) {
                        event.preventDefault();
                        alertContainer.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Solo el creador o el administrador pueden editar este pedido.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        setTimeout(() => {
                            alertContainer.innerHTML = '';
                        }, 3000);
                    }
                });
            });
        });
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === '1') {
                event.preventDefault();
                document.getElementById('agregar-pedido-btn').click();
            }
        });
    </script>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_ing')->first()->id ?? null)->where('ver', 1)->isEmpty())
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
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Listado de Pedidos para Obras</h1>
                        </div>
                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_ing')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                        <div class="col-sm-6">
                            <a href="{{ route('pedidobra.create') }}" class="btn btn-primary float-right" id="agregar-pedido-btn">Agregar Pedido</a>
                        </div>
                        @endif
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <input type="text" id="search" name="search" class="form-control mr-2" placeholder="Buscar pedidos...">
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div id="alert-container"></div>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="table table-bordered" id="pedidos-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Obra</th>
                                <th>Dirección de la obra</th>
                                <th>Creado por</th>
                                <th>Fecha de creación</th>
                                <th>Fecha de entrega</th>
                                <th>Insumos pedidos</th>
                                <th>Insumos preparados</th>
                                <th>Insumos faltantes</th>
                                <th>Observacion</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedobras->reverse() as $pedobra)
                                <tr>
                                    <td>{{ $pedobra->id }}</td>
                                    <td>{{ $pedobra->obra->nombre }}</td>
                                    <td>{{ $pedobra->obra->direccion }}</td>
                                    <td>{{ $pedobra->usuario->nombre }}</td>
                                    <td>{{ $pedobra->fecha_pedido }}</td>
                                    <td>{{ $pedobra->fecha_entrega }}</td>
                                    <td>{{ $pedobra->total_insumo }}</td>
                                    <td>{{ $pedobra->insumo_confirmado }}</td>
                                    <td>{{ $pedobra->insumo_faltante }}</td>
                                    <td>{{ $pedobra->observacion }}</td>
                                    <td>
                                        <button class="btn btn-{{ $estados_label[$pedobra->estado] }}">
                                            {{ $estados[$pedobra->estado] ?? 'Desconocido' }}
                                        </button>
                                    </td>
                                    <td>
                                         @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_ing')->first()->id ?? null)->where('editar', 1)->isNotEmpty())
                                            <a href="{{ route('pedidobra.edit', $pedobra->id) }}"
                                                class="btn btn-warning btn-sm editar-btn"
                                                data-toggle="tooltip"
                                                title="Editar"
                                                data-usuario-id="{{ $pedobra->usuario_id }}"
                                                data-area="{{ session('usuario_area') }}">
                                                <i class="nav-icon fas fa-pen"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('pedidobra.show', $pedobra->id) }}" class="btn btn-secondary btn-sm" data-toggle="tooltip" title="Resumen">
                                            <i class="nav-icon fas fa-eye"></i>
                                        </a>
                                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_ing')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                                            <a href="{{ route('pedidobra.duplicar', $pedobra->id) }}" class="btn btn-success btn-sm" data-toggle="tooltip" title="Duplicar">
                                                <i class="nav-icon fas fa-copy"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
</body>
</html>
