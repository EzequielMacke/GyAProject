<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Presupuesto Aprobado</title>
    @include('partials.head')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const montoInput = document.getElementById('monto_total');
            montoInput.addEventListener('input', function() {
                let value = montoInput.value.replace(/\./g, '');
                if (!isNaN(value) && value !== '') {
                    montoInput.value = Number(value).toLocaleString('de-DE');
                }
            });
            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var year = today.getFullYear();
            var todayDate = year + '-' + month + '-' + day;
            document.getElementById('fecha_carga').value = todayDate;
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
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_ing')->first()->id ?? null)->where('agregar', 1)->isEmpty())
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
            <form action="{{ route('presupuesto_aprobado.store') }}" method="POST" enctype="multipart/form-data">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Cargar Presupuesto Aprobado</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('presupuesto_aprobado.index') }}" class="btn btn-warning" id="volver-btn">Volver</a>
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
                        @csrf
                        <div class="form-group row">
                            <label for="user" class="col-sm-2 col-form-label text-center" >Creado por</label>
                            <div class="col-sm-4">
                                <input type="hidden" name="user_id" value="">
                                <input type="text" name="user" class="form-control" id="user" value="{{ session('usuario_nombre') }}" readonly>
                            </div>
                            <label for="fecha_carga" class="col-sm-2 col-form-label text-center">Fecha de creación</label>
                            <div class="col-sm-2">
                                <input type="date" name="fecha_carga" class="form-control" id="fecha_carga" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="clave" class="col-sm-2 col-form-label text-center">Nombre del presupuesto</label>
                            <div class="col-sm-4">
                                <input type="text" name="clave" class="form-control" id="clave" required>
                            </div>
                            <label for="ubicacion" class="col-sm-2 col-form-label text-center" >Ubicacion del presupuesto</label>
                            <div class="col-sm-2">
                                <input type="text" name="ubicacion" class="form-control" id="ubicacion" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tipo_trabajo" class="col-sm-2 col-form-label text-center">Tipo de trabajo</label>
                            <div class="col-sm-4">
                                <select name="tipo_trabajo" class="form-control" id="tipo_trabajo" required>
                                    <option value="" disabled selected>Seleccionar tipo de trabajo</option>
                                    @foreach (config('constantes.tipo_trabajo') as $codigo => $tipo_trabajo)
                                        <option value="{{ $codigo }}">{{ $tipo_trabajo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="monto_total" class="col-sm-2 col-form-label text-center">Monto del trabajo</label>
                            <div class="col-sm-2">
                                <input type="text" name="monto_total" class="form-control" id="monto_total" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="presupuesto" class="col-sm-2 col-form-label text-center">Presupuesto (PDF):</label>
                            <div class="col-sm-6">
                                <input type="file" name="presupuesto" id="presupuesto" accept="application/pdf" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="conformidad" class="col-sm-2 col-form-label text-center">Nota de conformidad (PDF):</label>
                            <div class="col-sm-6">
                                <input type="file" name="conformidad" id="conformidad" accept="application/pdf">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="observacion" class="col-sm-2 col-form-label text-center">Observación</label>
                            <div class="col-sm-8">
                                <textarea name="observacion" class="form-control" id="observacion" rows="5"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
</body>
</html>
