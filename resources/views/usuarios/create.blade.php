<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Usuario</title>
    @includeIf('partials.head')
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === '2') {
                event.preventDefault();
                document.getElementById('volver-btn').click();
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            const usuario = document.getElementById('usuario');
            const contraseña = document.getElementById('contraseña');
            const repContraseña = document.getElementById('rep_contraseña');
            const areaId = document.getElementById('area_id');
            const guardarBtn = document.getElementById('guardarBtn');
            const alertaContraseña = document.getElementById('alertaContraseña');

            // Poner el foco en el campo de usuario
            usuario.focus();

            function validarCampos() {
                if (usuario.value !== '' && contraseña.value !== '' && repContraseña.value !== '' && areaId.value !== '' && contraseña.value === repContraseña.value) {
                    guardarBtn.disabled = false;
                    alertaContraseña.style.display = 'none';
                } else {
                    guardarBtn.disabled = true;
                    if (contraseña.value !== repContraseña.value) {
                        alertaContraseña.style.display = 'block';
                    } else {
                        alertaContraseña.style.display = 'none';
                    }
                }
            }

            usuario.addEventListener('input', validarCampos);
            contraseña.addEventListener('input', validarCampos);
            repContraseña.addEventListener('input', validarCampos);
            areaId.addEventListener('change', validarCampos);
        });
    </script>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'usu')->first()->id ?? null)->where('agregar', 1)->isEmpty())
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
                            <h1 class="m-0">Cargar Usuario</h1>
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
                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="usuario" class="col-sm-2 col-form-label text-center">Nombre de Usuario</label>
                            <div class="col-sm-2">
                                <input type="text" name="usuario" class="form-control" id="usuario" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="contraseña" class="col-sm-2 col-form-label text-center">Contraseña</label>
                            <div class="col-sm-2">
                                <input type="password" name="contraseña" class="form-control" id="contraseña" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="rep_contraseña" class="col-sm-2 col-form-label text-center">Repetir contraseña</label>
                            <div class="col-sm-2">
                                <input type="password" name="rep_contraseña" class="form-control" id="rep_contraseña" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="rep_contraseña" class="col-sm-2 col-form-label text-center">Seleccionar Area</label>
                            <div class="col-sm-2">
                                <select name="area_id" class="form-control" id="area_id" required>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ $area->id == 2 ? 'selected' : '' }}>{{ $area->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <button type="submit" class="btn btn-primary" id="guardarBtn" disabled>Guardar</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-warning" id="volver-btn">Volver</a>
                         </div>
                        <div class="alert alert-danger" id="alertaContraseña" style="display: none;">
                            Las contraseñas no coinciden.
                        </div>
                    </form>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
</body>
</html>
