<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Documentos</title>
    @include('partials.head')

    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'gen_doc')->first()->id ?? null)->where('ver', 1)->isEmpty())
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
                            <h1 class="m-0">Listado de Documentos</h1>
                        </div>
                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'gen_doc')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                        <div class="col-sm-6">
                            <a href="{{ route('documentos.create') }}" class="btn btn-primary float-right" id="agregar-insumo-btn">Agregar Documento</a>
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
                                <th>Tipo de documento</th>
                                <th>Tipo de trabajo</th>
                                <th>Fecha de creación</th>
                                <th>Creado por</th>
                                <th>Detalles</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documentos as $documento)
                                <tr>
                                    <td>{{ $documento->id }}</td>
                                    <td>{{ $documento->nombre }}</td>
                                    <td>{{ $documento->tipoDocumento->nombre ?? '-' }}</td>
                                    <td>{{ $documento->tipoTrabajo->nombre ?? '-' }}</td>
                                    <td>{{ $documento->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $documento->usuario->nombre ?? '-' }}</td>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach ($documento->trabajosDetalles as $detalle)
                                                <li>
                                                    {{ $detalle->ensayo->nombre ?? 'Sin ensayo' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <a href="{{ route('documentos.edit', $documento->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="{{ route('documentos.reemplazarMarcadores', $documento->id) }}" class="btn btn-success btn-sm">Generar Word</a>
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
