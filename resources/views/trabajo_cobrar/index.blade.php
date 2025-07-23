<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabajos aprobados</title>
    @include('partials.head')
      <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tableRows = document.querySelectorAll('#presupuestos-table tbody tr');
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

            const tipoTrabajo = @json($tipo_trabajo);
            const modal = document.getElementById('verModal');
            const modalTitle = document.getElementById('verModalTitle');
            const modalBody = document.getElementById('verModalBody');

            document.querySelectorAll('.btn-ver').forEach(button => {
                button.addEventListener('click', function() {
                    const presupuesto = JSON.parse(this.getAttribute('data-presupuesto'));
                    modalTitle.textContent = `Presupuesto ID: ${presupuesto.id}`;
                    let pdfUrl = presupuesto.presupuesto.replace('public/', '');
                    modalBody.innerHTML = `
                        <p><strong>Gestionado por:</strong> ${presupuesto.usuario_gestion ? presupuesto.usuario_gestion.nombre : 'Pendiente'}</p>
                        <p><strong>Fecha de gestion:</strong> ${presupuesto.fecha_gestion || 'Pendiente'}</p>
                        <p><strong>Obra:</strong> ${presupuesto.obra ? presupuesto.obra.nombre : 'Pendiente'}</p>
                        <p><strong>Nombre de presupuesto:</strong> ${presupuesto.clave}</p>
                        <p><strong>Tipo de trabajo:</strong> ${presupuesto.tipo_trabajo ? tipoTrabajo[presupuesto.tipo_trabajo] : 'Desconocido'}</p>
                        <p><strong>Observación:</strong> ${presupuesto.observacion ? presupuesto.observacion : 'No contiene observaciones'}</p>
                        <p><strong>Monto total:</strong> ${Number(presupuesto.monto_total).toLocaleString('de-DE')}</p>
                        <p><strong>Contacto:</strong> ${presupuesto.obra.contacto || 'Pendiente'}</p>
                        <p><strong>Numero:</strong> ${presupuesto.obra.numero || 'Pendiente'}</p>
                        <p><strong>Orden de trabajo:</strong> ${presupuesto.orden_trabajo || 'Pendiente'}</p>
                        <p><strong>PDF:</strong></p>
                        <iframe src="/storage/${pdfUrl}" width="100%" height="500px"></iframe>
                        <iframe src="/storage/app/${presupuesto.presupuesto}" width="100%" height="500px"></iframe>
                    `;
                    $('#verModal').modal('show');
                });
            });
            document.querySelectorAll('.btn-validar').forEach(button => {
                button.addEventListener('click', function() {
                    const presupuesto = JSON.parse(this.getAttribute('data-presupuesto'));
                    document.getElementById('obraPresupuestoId').value = presupuesto.id;
                    $('#crearObraModal').modal('show');
                });
            });

            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var year = today.getFullYear();
            var todayDate = year + '-' + month + '-' + day;
            document.getElementById('fecha_gestion').value = todayDate;
            document.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === '2') {
                    event.preventDefault();
                    document.getElementById('volver-btn').click();
                }
            });

            document.querySelectorAll('.btn-anular').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    if (confirm('¿Estás seguro de que deseas anular este presupuesto?')) {
                        this.closest('form').submit();
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
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_adm')->first()->id ?? null)->where('ver', 1)->isEmpty())
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
                            <h1 class="m-0">Listado de Trabajos Aprobados</h1>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <input type="text" id="search" name="search" class="form-control mr-2" placeholder="Buscar trabajo...">
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
                    <table class="table table-bordered" id="presupuestos-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Obra</th>
                                <th>Nombre de presupuesto</th>
                                <th>Tipo de trabajo</th>
                                <th>Observacion</th>
                                <th>Monto total</th>
                                <th>Anticipo cobrado</th>
                                <th>Orden de Trabajo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($presupuestos->reverse() as $presupuesto)
                                <tr>
                                    <td>{{ $presupuesto->id }}</td>
                                    <td>{{ $presupuesto->obra->nombre ?? 'Pendiente' }}</td>
                                    <td>{{ $presupuesto->clave ?? 'Pendiente' }}</td>
                                    <td>{{ $tipo_trabajo[$presupuesto->tipo_trabajo] ?? 'Desconocido' }}</td>
                                    <td>{{ $presupuesto->observacion }}</td>
                                    <td>{{ number_format($presupuesto->monto_total, 0, '', '.') }}</td>
                                    <td>{{ $presupuesto->anticipo == 1 ? 'Sí' : ($presupuesto->anticipo == 2 ? 'No' : 'Pendiente') }}</td>
                                    <td>{{ $presupuesto->orden_trabajo ?? 'Pendiente' }}</td>
                                    <td>
                                        <button class="btn btn-{{ $estados_label[$presupuesto->estado] }}">
                                            {{ $estados[$presupuesto->estado] ?? 'Desconocido' }}
                                        </button>
                                    </td>
                                    <td>
                                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_adm')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                                        <button class="btn btn-secondary btn-sm btn-ver" data-toggle="tooltip" title="Ver" data-presupuesto="{{ json_encode($presupuesto) }}">
                                         <i class="nav-icon fas fa-eye"></i>
                                        </button>
                                        @endif
                                        @if ($presupuesto->estado == 2 && $permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_adm')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                                        <button class="btn btn-success btn-sm btn-validar" data-toggle="tooltip" title="Validar" data-presupuesto="{{ json_encode($presupuesto) }}">
                                            <i class="nav-icon fas fa-check"></i>
                                        </button>
                                        @endif
                                        @if ($presupuesto->estado == 3 && $permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_adm')->first()->id ?? null)->where('eliminar', 1)->isNotEmpty())
                                        <form action="{{ route('trabajo_cobrar.anular', $presupuesto->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('POST')
                                            <button class="btn btn-danger btn-sm btn-anular" data-toggle="tooltip" title="Anular">
                                                <i class="nav-icon fas fa-trash"></i>
                                            </button>
                                        </form>
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
    <div class="modal fade" id="verModal" tabindex="-1" role="dialog" aria-labelledby="verModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verModalTitle">Ver Presupuesto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="verModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="crearObraModal" tabindex="-1" role="dialog" aria-labelledby="crearObraModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearObraModalTitle">Gestionar Trabajo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('trabajo_cobrar.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="presupuesto_id" id="obraPresupuestoId">
                        <input type="hidden" name="user_id" id="user_id" value="{{ session('usuario_id') }}">
                        <div class="form-group">
                            <label for="orden_trabajo">Orden de Trabajo</label>
                            <input type="text" name="orden_trabajo" id="orden_trabajo" class="form-control" required>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="anticipo_cobrado" name="anticipo_cobrado">
                                <label class="form-check-label" for="anticipo_cobrado">Anticipo Cobrado</label>
                            </div>
                            <label for="nombre_obra">Gestionado por</label>
                                <input type="text" name="user" class="form-control" id="user" value="{{ session('usuario_nombre') }}" readonly>
                                <label for="nombre_obra">Fecha de gestion</label>
                                <input type="date" name="fecha_gestion" class="form-control" id="fecha_gestion" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
