<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Obras</title>
    @include('partials.head')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0
            var year = today.getFullYear();
            var todayDate = year + '-' + month + '-' + day;
            document.getElementById('fecha_carga').value = todayDate;

            document.getElementById('nombre').focus();
            document.getElementById('nombre').addEventListener('input', function() {
                var filter = this.value.toLowerCase();
                var insumos = document.querySelectorAll('.obra-item');
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
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('agregar', 1)->isEmpty())
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
                            <h1 class="m-0">Cargar Obras</h1>
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
                    <form action="{{ route('obras.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="user" class="col-sm-2 col-form-label text-center" >Creado por</label>
                            <div class="col-sm-4">
                                <input type="hidden" name="user_id" value="">
                                <input type="text" name="user" class="form-control" id="user" value="{{ session('usuario_nombre') }}" readonly>
                            </div>
                            <label for="fecha_carga" class="col-sm-2 col-form-label text-center">Fecha de creación</label>
                            <div class="col-sm-4">
                                <input type="date" name="fecha_carga" class="form-control" id="fecha_carga" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nombre" class="col-sm-2 col-form-label text-center" >Nombre de la obra</label>
                            <div class="col-sm-4">
                                <input type="text" name="nombre" class="form-control" id="nombre" required>
                            </div>
                            <label for="direccion" class="col-sm-2 col-form-label text-center">Dirección</label>
                            <div class="col-sm-4">
                                <input type="text" name="direccion" class="form-control" id="direccion">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="observacion" class="col-sm-2 col-form-label text-center">Observación</label>
                            <div class="col-sm-10">
                                <textarea name="observacion" class="form-control" id="observacion" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <h3 class="m-0">Datos de facturación</h3>
                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="ruc" class="col-sm-2 col-form-label text-center" >RUC</label>
                            <div class="col-sm-4">
                                <input type="text" name="ruc" class="form-control" id="ruc">
                            </div>

                            <label for="peticionario" class="col-sm-2 col-form-label text-center" >Razon Social</label>
                            <div class="col-sm-4">
                                <input type="text" name="peticionario" class="form-control" id="peticionario">
                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="direccion_fac" class="col-sm-2 col-form-label text-center" >Direccion de facturacion</label>
                            <div class="col-sm-4">
                                <input type="text" name="direccion_fac" class="form-control" id="direccion_fac">
                            </div>

                            <label for="correo_fac" class="col-sm-2 col-form-label text-center" >Correo de facturacion</label>
                            <div class="col-sm-4">
                                <input type="text" name="correo_fac" class="form-control" id="correo_fac">
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <h3 class="m-0">Datos de peticionario</h3>
                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="contacto" class="col-sm-2 col-form-label text-center" >Nombre</label>
                            <div class="col-sm-4">
                                <input type="text" name="contacto" class="form-control" id="contacto">
                            </div>

                            <label for="numero" class="col-sm-2 col-form-label text-center" >Numero</label>
                            <div class="col-sm-4">
                                <input type="text" name="numero" class="form-control" id="numero">
                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="correo_pet" class="col-sm-2 col-form-label text-center" >Correo</label>
                            <div class="col-sm-4">
                                <input type="text" name="correo_pet" class="form-control" id="correo_pet">
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <h3 class="m-0">Contacto administrativo</h3>
                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="nombre_adm" class="col-sm-2 col-form-label text-center" >Nombre</label>
                            <div class="col-sm-4">
                                <input type="text" name="nombre_adm" class="form-control" id="nombre_adm">
                            </div>

                            <label for="telefono_adm" class="col-sm-2 col-form-label text-center" >Numero</label>
                            <div class="col-sm-4">
                                <input type="text" name="telefono_adm" class="form-control" id="telefono_adm">
                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="correo_adm" class="col-sm-2 col-form-label text-center" >Correo</label>
                            <div class="col-sm-4">
                                <input type="text" name="correo_adm" class="form-control" id="correo_adm">
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <h3 class="m-0">Contacto de obra</h3>
                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="nombre_obr" class="col-sm-2 col-form-label text-center" >Nombre</label>
                            <div class="col-sm-4">
                                <input type="text" name="nombre_obr" class="form-control" id="nombre_obr">
                            </div>

                            <label for="telefono_obr" class="col-sm-2 col-form-label text-center" >Numero</label>
                            <div class="col-sm-4">
                                <input type="text" name="telefono_obr" class="form-control" id="telefono_obr">
                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="correo_obr" class="col-sm-2 col-form-label text-center" >Correo</label>
                            <div class="col-sm-4">
                                <input type="text" name="correo_obr" class="form-control" id="correo_obr">
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('obras.index') }}" class="btn btn-warning" id="volver-btn">Volver</a>
                    </form>

                    <h3 class="mt-4">Obras creadas</h3>
                    <ul class="list-group">
                        @foreach ($obras as $obra)
                            <li class="list-group-item obra-item">{{ $obra->nombre }}</li>
                        @endforeach
                    </ul>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
</body>
</html>
