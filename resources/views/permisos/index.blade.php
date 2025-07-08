<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permisos</title>
    @include('partials.head')
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisex = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisex->where('modulo_id', Modulo::where('nombre', 'per')->first()->id ?? null)->where('ver', 1)->isEmpty())
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
                            <h1 class="m-0">Listado de Permisos por area</h1>
                        </div>
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
                                <th>Id de area</th>
                                <th>Nombre de Area</th>
                                <th>Editar permisos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permisos as $permiso)
                                <tr>
                                    <td>{{ $permiso->id }}</td>
                                    <td>{{ $permiso->descripcion }}</td>
                                    <td>
                                         @if ($permisex->where('modulo_id', Modulo::where('nombre', 'per')->first()->id ?? null)->where('editar', 1)->isNotEmpty())
                                        <a href="{{ route('permisos.edit', $permiso->id) }}" class="btn btn-warning btn-sm editar-btn" data-toggle="tooltip" title="Editar">
                                             <i class="nav-icon fas fa-pen"></i>
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
