<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Insumo</title>
    @includeIf('partials.head')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('nombre').focus();
        document.getElementById('nombre').addEventListener('input', function() {
            var filter = this.value.toLowerCase();
            var insumos = document.querySelectorAll('.insumo-item');
            insumos.forEach(function(insumo) {
                var text = insumo.textContent.toLowerCase();
                if (text.includes(filter)) {
                    insumo.style.display = '';
                } else {
                    insumo.style.display = 'none';
                }
            });
        });
        document.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === '2') {
                    event.preventDefault();
                    document.getElementById('volver-btn').click();
                }
            });
        });
    </script>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ins')->first()->id ?? null)->where('agregar', 1)->isEmpty())
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
                            <h1 class="m-0">Cargar Insumo</h1>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('insumos.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="nombre" class="col-sm-2 col-form-label text-center">Nombre del Insumo</label>
                            <div class="col-sm-2">
                                <input type="text" name="nombre" class="form-control" id="nombre" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('insumos.index') }}" class="btn btn-warning" id="volver-btn">Volver</a>
                    </form>
                    <h3 class="mt-4">Insumos creados</h3>
                    <ul class="list-group">
                        @foreach ($insumos as $insumo)
                            <li class="list-group-item insumo-item">{{ $insumo->nombre }}</li>
                        @endforeach
                    </ul>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
</body>
</html>
