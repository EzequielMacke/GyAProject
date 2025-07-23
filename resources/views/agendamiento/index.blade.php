<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Agendamiento</title>
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
            const meses = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];

            function getWeeksInMonth(year, month) {
                const firstDayOfMonth = new Date(year, month, 1);
                const lastDayOfMonth = new Date(year, month + 1, 0);
                const used = firstDayOfMonth.getDay() + lastDayOfMonth.getDate();
                return Math.ceil(used / 7);
            }

            document.querySelectorAll('.btn-agendar').forEach(button => {
                button.addEventListener('click', function() {
                    const presupuesto = JSON.parse(this.getAttribute('data-presupuesto'));
                    const modalTitle = document.getElementById('agendarModalTitle');
                    const modalBody = document.getElementById('agendarModalBody');

                    const currentYear = new Date().getFullYear();
                    const currentMonth = new Date().getMonth();

                    modalTitle.textContent = `Agendar Presupuesto: ${presupuesto.clave}`;
                    modalBody.innerHTML = `
                        <form id="agendarForm" method="POST" action="{{ route('agendamiento.store') }}">
                            @csrf
                            <input type="hidden" name="presupuesto_id" value="${presupuesto.id}">
                            <input type="hidden" name="obra_id" value="${presupuesto.obra.id}">
                            <p><strong>Nombre del presupuesto:</strong> ${presupuesto.clave}</p>
                            <p><strong>Orden de trabajo:</strong> ${presupuesto.orden_trabajo}</p>
                            <p><strong>Fecha actual:</strong> ${new Date().toLocaleDateString()}</p>
                            <div class="form-group">
                                <label for="mes">Mes</label>
                                <select id="mes" name="mes" class="form-control">
                                    ${meses.map((mes, index) => `<option value="${index}" ${index === currentMonth ? 'selected' : ''}>${mes}</option>`).join('')}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="semana_inicio">Semana de inicio</label>
                                <select id="semana_inicio" name="semana_inicio" class="form-control">
                                    ${Array.from({ length: getWeeksInMonth(currentYear, currentMonth) }, (_, i) => `<option value="${i + 1}">Semana ${i + 1}</option>`).join('')}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="semana_fin">Semana de fin</label>
                                <select id="semana_fin" name="semana_fin" class="form-control">
                                    ${Array.from({ length: getWeeksInMonth(currentYear, currentMonth) }, (_, i) => `<option value="${i + 1}">Semana ${i + 1}</option>`).join('')}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea id="observaciones" name="observaciones" class="form-control" rows="3"></textarea>
                            </div>
                        </form>
                    `;

                    document.getElementById('mes').addEventListener('change', function() {
                        const selectedMonth = parseInt(this.value);
                        const weeksInMonth = getWeeksInMonth(currentYear, selectedMonth);
                        const semanaInicioSelect = document.getElementById('semana_inicio');
                        const semanaFinSelect = document.getElementById('semana_fin');

                        semanaInicioSelect.innerHTML = Array.from({ length: weeksInMonth }, (_, i) => `<option value="${i + 1}">Semana ${i + 1}</option>`).join('');
                        semanaFinSelect.innerHTML = Array.from({ length: weeksInMonth }, (_, i) => `<option value="${i + 1}">Semana ${i + 1}</option>`).join('');
                    });

                    $('#agendarModal').modal('show');
                });
            });

            document.getElementById('guardarAgendamiento').addEventListener('click', function() {
                document.getElementById('agendarForm').submit();
            });
                document.querySelectorAll('.form-eliminar').forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const confirmed = confirm('¿Estás seguro de que deseas eliminar este agendamiento?');
                    if (confirmed) {
                        form.submit();
                    }
                });
            });
            function generateCalendar(agendamientos, month) {
                const currentYear = new Date().getFullYear();
                const weeksInMonth = getWeeksInMonth(currentYear, month);

                let calendarHtml = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Presupuesto</th>
                `;

                for (let i = 1; i <= weeksInMonth; i++) {
                    calendarHtml += `<th>Semana ${i}</th>`;
                }

                calendarHtml += `
                            </tr>
                        </thead>
                        <tbody>
                `;

                agendamientos.forEach(agendamiento => {
                    if (agendamiento.mes === month) {
                        calendarHtml += `<tr><td>${agendamiento.presupuesto.clave}</td>`;
                        for (let i = 1; i <= weeksInMonth; i++) {
                            if (i >= agendamiento.inicio && i <= agendamiento.fin) {
                                calendarHtml += `<td>${agendamiento.presupuesto.clave}</td>`;
                            } else {
                                calendarHtml += `<td></td>`;
                            }
                        }
                        calendarHtml += `</tr>`;
                    }
                });

                calendarHtml += `
                        </tbody>
                    </table>
                `;

                document.getElementById('calendar').innerHTML = calendarHtml;
            }

            // Inicializar el calendario con el mes actual
            const currentMonth = new Date().getMonth();
            generateCalendar(@json($agendamientos), currentMonth);

            // Actualizar el calendario cuando se seleccione un nuevo mes
            document.getElementById('mes-selector').addEventListener('change', function() {
                const selectedMonth = parseInt(this.value);
                generateCalendar(@json($agendamientos), selectedMonth);
            });
        });
    </script>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'age_tra')->first()->id ?? null)->where('ver', 1)->isEmpty())
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
                        <li class="nav-item">
                            <a class="nav-link active" id="pendientes-tab" data-toggle="tab" href="#pendientes" role="tab" aria-controls="pendientes" aria-selected="true">Agendamientos Pendientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="agendado-tab" data-toggle="tab" href="#agendado" role="tab" aria-controls="agendado" aria-selected="false">Agendado</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="resumen-tab" data-toggle="tab" href="#resumen" role="tab" aria-controls="resumen" aria-selected="false">Resumen</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="pendientes" role="tabpanel" aria-labelledby="pendientes-tab">
                            @if ($presupuestosPendientes->isEmpty())
                                <p>No hay agendamientos pendientes.</p>
                            @else
                                <table class="table table-bordered" id="agendamientos-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre del presupuesto</th>
                                            <th>Obra</th>
                                            <th>Tipo de Trabajo</th>
                                            <th>Orden de Trabajo</th>
                                            <th>Presupuesto</th>
                                            <th>Fecha de aprobación</th>
                                            <th>Estado</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($presupuestosPendientes as $presupuesto)
                                            <tr>
                                                <td>{{ $presupuesto->id }}</td>
                                                <td>{{ $presupuesto->clave }}</td>
                                                <td>{{ $presupuesto->obra->nombre}}</td>
                                                <td>{{ config('constantes.tipo_trabajo')[$presupuesto->tipo_trabajo] ?? 'Desconocido' }}</td>
                                                <td>{{ $presupuesto->orden_trabajo ?? 'Pendiente' }}</td>
                                                <td><a href="{{ Storage::url($presupuesto->presupuesto) }}" target="_blank">Ver PDF</a></td>
                                                <td>{{ $presupuesto->fecha_gestion}}</td>
                                                <td>
                                                    <button class="btn btn-{{ $estados_label[$presupuesto->estado] }}">
                                                    {{ $estados[$presupuesto->estado] ?? 'Desconocido' }}
                                                    </button>
                                                </td>
                                                <td>
                                                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'age_tra')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                                                        <button class="btn btn-secondary btn-sm btn-ver" data-toggle="tooltip" title="Ver">
                                                            <i class="nav-icon fas fa-eye"></i>
                                                        </button>
                                                    @endif
                                                    @if ($presupuesto->estado == 3 && $permisos->where('modulo_id', Modulo::where('nombre', 'age_tra')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                                                        <button class="btn btn-success btn-sm btn-agendar" data-toggle="tooltip" title="Agendar" data-presupuesto="{{ json_encode($presupuesto) }}">
                                                            <i class="nav-icon fas fa-calendar"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="agendado" role="tabpanel" aria-labelledby="agendado-tab">
                            @if ($presupuestoAgendados->isEmpty())
                                <p>No hay agendamientos.</p>
                            @else
                                <table class="table table-bordered" id="agendamientos-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre del presupuesto</th>
                                            <th>Obra</th>
                                            <th>Tipo de Trabajo</th>
                                            <th>Orden de Trabajo</th>
                                            <th>Presupuesto</th>
                                            <th>Fecha de aprobación</th>
                                            <th>Mes</th>
                                            <th>Semana de Inicio</th>
                                            <th>Semana de Fin</th>
                                            <th>Estado</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($presupuestoAgendados as $presupuestoAgendado)
                                            @foreach ($agendamientos as $agendamiento)
                                                @if ($agendamiento->presupuesto_id == $presupuestoAgendado->id)
                                                    <tr>
                                                        <td>{{ $presupuestoAgendado->id }}</td>
                                                        <td>{{ $presupuestoAgendado->clave }}</td>
                                                        <td>{{ $presupuestoAgendado->obra->nombre}}</td>
                                                        <td>{{ config('constantes.tipo_trabajo')[$presupuestoAgendado->tipo_trabajo] ?? 'Desconocido' }}</td>
                                                        <td>{{ $presupuestoAgendado->orden_trabajo ?? 'Pendiente' }}</td>
                                                        <td><a href="{{ Storage::url($presupuestoAgendado->presupuesto) }}" target="_blank">Ver PDF</a></td>
                                                        <td>{{ $presupuestoAgendado->fecha_gestion}}</td>
                                                        <td>{{ $meses[$agendamiento->mes] }}</td>
                                                        <td>Semana {{ $agendamiento->inicio }}</td>
                                                        <td>Semana {{ $agendamiento->fin }}</td>
                                                        <td>
                                                            <button class="btn btn-{{ $estados_label[$presupuestoAgendado->estado] }}">
                                                            {{ $estados[$presupuestoAgendado->estado] ?? 'Desconocido' }}
                                                            </button>
                                                        </td>
                                                        <td>
                                                            @if ($permisos->where('modulo_id', Modulo::where('nombre', 'age_tra')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                                                                <button class="btn btn-secondary btn-sm btn-ver" data-toggle="tooltip" title="Ver">
                                                                    <i class="nav-icon fas fa-eye"></i>
                                                                </button>
                                                            @endif
                                                            @if ($presupuestoAgendado->estado == 4 && $permisos->where('modulo_id', Modulo::where('nombre', 'age_tra')->first()->id ?? null)->where('eliminar', 1)->isNotEmpty())
                                                                <form action="{{ route('agendamiento.destroy', $agendamiento->id) }}" method="POST" style="display:inline;" class="form-eliminar">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar">
                                                                        <i class="nav-icon fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="resumen" role="tabpanel" aria-labelledby="resumen-tab">
                            <div class="form-group">
                                <label for="mes-selector">Seleccionar Mes:</label>
                                <select id="mes-selector" class="form-control">
                                    @foreach ($meses as $index => $mes)
                                        <option value="{{ $index }}">{{ $mes }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
    <div class="modal fade" id="agendarModal" tabindex="-1" role="dialog" aria-labelledby="agendarModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agendarModalTitle">Agendar Presupuesto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="agendarModalBody">
                    <!-- Aquí se llenará la información del presupuesto -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="guardarAgendamiento">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
