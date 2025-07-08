<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Insumos</title>
    @include('partials.head')
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === '1') {
                event.preventDefault();
                document.getElementById('agregar-insumo-btn').click();
            }
        });
    </script>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ins')->first()->id ?? null)->where('ver', 1)->isEmpty())
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
                            <h1 class="m-0">Listado de Insumos</h1>
                        </div>
                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ins')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                        <div class="col-sm-6">
                            <a href="{{ route('insumos.create') }}" class="btn btn-primary float-right" id="agregar-insumo-btn">Agregar Insumo</a>
                        </div>
                        @endif
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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Cargado por</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($insumos as $insumo)
                                <tr>
                                    <td>{{ $insumo->id }}</td>
                                    <td>{{ $insumo->nombre }}</td>
                                    <td>{{ $insumo->usuario->nombre }}</td>
                                    <td>{{ $estados[$insumo->estado] ?? 'Desconocido' }}</td>
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
