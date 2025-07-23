<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Pedidos para obra</title>
    @include('partials.head')
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                            <h1 class="m-0">Listado de Pedidos para Obras</h1>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <input type="text" id="search" name="search" class="form-control mr-2" placeholder="Buscar pedidos...">
                        </div>
                    </div>
                </div>
            <section class="content">
                <div class="container-fluid">
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
                                         @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_dep')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                                        <a href="{{ route('preparobra.show', $pedobra->id) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Editar">
                                            Preparar Pedido
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
    </div>
    @include('partials.footer')
</body>
</html>
