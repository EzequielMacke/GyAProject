<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestion de Trabajos</title>
        @include('partials.head')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search');
                const tableRows = document.querySelectorAll('#agendamientos-table tbody tr');

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
        @php
            use App\Models\Modulo;
            use App\Models\Permiso;
            $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
        @endphp
        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ges_tra')->first()->id ?? null)->where('ver', 1)->isEmpty())
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
                                <h1 class="m-0">Buscador</h1>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <input type="text" id="search" name="search" class="form-control mr-2" placeholder="Buscar...">
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
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ges_tra_asig')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                                <li class="nav-item">
                                    <a class="nav-link active" id="asignacion-tab" data-toggle="tab" href="#asignacion" role="tab" aria-controls="asignacion" aria-selected="true">Asignacionaciones</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" id="agendado-tab" data-toggle="tab" href="#agendado" role="tab" aria-controls="agendado" aria-selected="false">Agendado</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="resumen-tab" data-toggle="tab" href="#resumen" role="tab" aria-controls="resumen" aria-selected="false">Resumen</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="asignacion" role="tabpanel" aria-labelledby="asignacion-tab">
                                <table class="table table-bordered" id="agendamientos-table">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Nombre del Presupuesto</th>
                                            <th>Obra</th>
                                            <th>PDF del Presupuesto</th>
                                            <th>Tipo de Trabajo</th>
                                            <th>Mes de Inicio</th>
                                            <th>Semana de Inicio</th>
                                            <th>Plazo (días)</th>
                                            <th>Asignar a</th>
                                            <th>Estado</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($agendamientos as $agendamiento)
                                            <tr>
                                                <td>{{ $agendamiento->presupuesto->id }}</td>
                                                <td>{{ $agendamiento->presupuesto->clave }}</td>
                                                <td>{{ $agendamiento->obra->nombre }}</td>
                                                <td><a href="{{ Storage::url($agendamiento->presupuesto->presupuesto) }}" target="_blank">Ver PDF</a></td>
                                                <td>{{ config('constantes.tipo_trabajo')[$agendamiento->presupuesto->tipo_trabajo] ?? 'Desconocido' }}</td>
                                                <td>{{ $agendamiento->mes }}</td>
                                                <td>Semana {{ $agendamiento->inicio }}</td>
                                                <td>
                                                    <input type="number" class="form-control" name="plazo_{{ $agendamiento->id }}" value="{{ $agendamiento->plazo ?? '' }}">
                                                </td>
                                                <td>
                                                    <select class="form-control" name="usuario_{{ $agendamiento->id }}">
                                                        @foreach ($usuarios as $usuario)
                                                            <option value="{{ $usuario->id }}" {{ $agendamiento->usuario_id == $usuario->id ? 'selected' : '' }}>{{ $usuario->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>{{ config('constantes.estado_de_presupuestos')[$agendamiento->estado] ?? 'Desconocido' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="agendado" role="tabpanel" aria-labelledby="agendado-tab">

                            </div>
                            <div class="tab-pane fade" id="resumen" role="tabpanel" aria-labelledby="resumen-tab">

                            </div>
                        </div>
                    </div>
                </section>
            </div>
            @include('partials.footer')
        </div>
    </body>
</html>
