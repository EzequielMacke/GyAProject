<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Permisos</title>
    @include('partials.head')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const marcarTodoBtn = document.getElementById('marcar-todo');
            const desmarcarTodoBtn = document.getElementById('desmarcar-todo');
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');

            marcarTodoBtn.addEventListener('click', function () {
                checkboxes.forEach(checkbox => checkbox.checked = true);
            });

            desmarcarTodoBtn.addEventListener('click', function () {
                checkboxes.forEach(checkbox => checkbox.checked = false);
            });
            function marcarColumna(clase) {
                const checkboxes = document.querySelectorAll(`.${clase}`);
                checkboxes.forEach(checkbox => checkbox.checked = true);
            }

            function alternarColumna(clase) {
                const checkboxes = document.querySelectorAll(`.${clase}`);
                const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
            }

            document.getElementById('toggle-ver').addEventListener('click', function () {
                alternarColumna('ver');
            });

            document.getElementById('toggle-agregar').addEventListener('click', function () {
                alternarColumna('agregar');
            });

            document.getElementById('toggle-editar').addEventListener('click', function () {
                alternarColumna('editar');
            });

            document.getElementById('toggle-eliminar').addEventListener('click', function () {
                alternarColumna('eliminar');
            });

            function alternarFila(fila) {
                const checkboxes = fila.querySelectorAll('input[type="checkbox"]');
                const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
            }
            document.querySelectorAll('.toggle-fila').forEach(button => {
                button.addEventListener('click', function () {
                    const fila = button.closest('tr');
                    alternarFila(fila);
                });
            });
        });
    </script>
    <style>
        .text-center {
            text-align: center;
        }
    </style>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisex = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisex->where('modulo_id', Modulo::where('nombre', 'per')->first()->id ?? null)->where('editar', 1)->isEmpty())
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
            <form action="{{ route('permisos.update', $area->id) }}" method="POST">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Editar permisos de {{ $area->descripcion }}</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="button" id="marcar-todo" class="btn btn-success">Marcar todo</button>
                            <button type="button" id="desmarcar-todo" class="btn btn-danger">Desmarcar todo</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('permisos.index') }}" class="btn btn-warning">Volver</a>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                        @csrf
                        @method('PUT')
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Módulo</th>
                                    <th class="text-center">
                                        <button type="button" id="toggle-ver" class="btn btn-light">Ver</button>
                                    </th>
                                    <th class="text-center">
                                        <button type="button" id="toggle-agregar" class="btn btn-light">Agregar</button>
                                    </th>
                                    <th class="text-center">
                                        <button type="button" id="toggle-editar" class="btn btn-light">Editar</button>
                                    </th>
                                    <th class="text-center">
                                        <button type="button" id="toggle-eliminar" class="btn btn-light">Eliminar</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modulos as $modulo)
                                    <tr>
                                        <td>
                                        <button type="button" class="btn btn-light toggle-fila">{{ $modulo->descripcion }}</button>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="ver" name="permisos[{{ $modulo->id }}][ver]" value="1"
                                                {{ isset($permisos[$modulo->id]) && $permisos[$modulo->id]->ver == 1 ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="agregar" name="permisos[{{ $modulo->id }}][agregar]" value="1"
                                                {{ isset($permisos[$modulo->id]) && $permisos[$modulo->id]->agregar == 1 ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="editar" name="permisos[{{ $modulo->id }}][editar]" value="1"
                                                {{ isset($permisos[$modulo->id]) && $permisos[$modulo->id]->editar == 1 ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="eliminar" name="permisos[{{ $modulo->id }}][eliminar]" value="1"
                                                {{ isset($permisos[$modulo->id]) && $permisos[$modulo->id]->eliminar == 1 ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
</body>
</html>
