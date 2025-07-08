<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validacion de Presupuestos</title>
    @include('partials.head')
    <style>
        .select2-container .select2-selection--single {
            height: 45px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 45px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#obra_id').select2({
                placeholder: 'Seleccione una obra',
                allowClear: true,
                width: '100%'
            });
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
                        <p><strong>Validado por:</strong> ${presupuesto.usuario_validado ? presupuesto.usuario_validado.nombre : 'Pendiente'}</p>
                        <p><strong>Fecha de validacion:</strong> ${presupuesto.fecha_aprobacion ? presupuesto.fecha_aprobacion: 'Pendiente'}</p>
                        <p><strong>Obra:</strong> ${presupuesto.obra ? presupuesto.obra.nombre : 'Pendiente'}</p>
                        <p><strong>Nombre del presupuesto:</strong> ${presupuesto.clave}</p>
                        <p><strong>Tipo de trabajo:</strong> ${presupuesto.tipo_trabajo ? tipoTrabajo[presupuesto.tipo_trabajo] : 'Desconocido'}</p>
                        <p><strong>Ubicación del presupuesto:</strong> ${presupuesto.ubicacion}</p>
                        <p><strong>Observación:</strong> ${presupuesto.observacion ? presupuesto.observacion : 'No contiene observaciones'}</p>
                        <p><strong>Monto total:</strong> ${Number(presupuesto.monto_total).toLocaleString('de-DE')}</p>
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
            document.getElementById('fecha_aprobacion').value = todayDate;
            document.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === '2') {
                    event.preventDefault();
                    document.getElementById('volver-btn').click();
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
            const nombreObraInput = document.getElementById('nombre_obra');
            const form = document.querySelector('#crearObraModal form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const nombreObra = nombreObraInput.value;
                fetch('{{ route('validar_presupuesto.check') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ nombre_obra: nombreObra })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        alert('La obra ya existe.');
                    } else {
                        form.submit();
                    }
                })
                .catch(error => console.error('Error:', error));
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
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'val_pre_apr')->first()->id ?? null)->where('ver', 1)->isEmpty())
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
                            <h1 class="m-0">Listado de Presupuesto</h1>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <input type="text" id="search" name="search" class="form-control mr-2" placeholder="Buscar presupuesto...">
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
                                <th>Nombre de presupuesto</th>
                                <th>Obra</th>
                                <th>Tipo de trabajo</th>
                                <th>Ubicacion del presupuesto</th>
                                <th>Observacion</th>
                                <th>Monto total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($presupuestos->reverse() as $presupuesto)
                                <tr>
                                    <td>{{ $presupuesto->id }}</td>
                                    <td>{{ $presupuesto->clave }}</td>
                                    <td>{{ $presupuesto->obra->nombre ?? 'Pendiente' }}</td>
                                    <td>{{ $tipo_trabajo[$presupuesto->tipo_trabajo] ?? 'Desconocido' }}</td>
                                    <td>{{ $presupuesto->ubicacion }}</td>
                                    <td>{{ $presupuesto->observacion }}</td>
                                    <td>{{ number_format($presupuesto->monto_total, 0, '', '.') }}</td>
                                    <td>
                                        <button class="btn btn-{{ $estados_label[$presupuesto->estado] }}">
                                            {{ $estados[$presupuesto->estado] ?? 'Desconocido' }}
                                        </button>
                                    </td>
                                    <td>
                                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'val_pre_apr')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                                        <button class="btn btn-secondary btn-sm btn-ver" data-toggle="tooltip" title="Ver" data-presupuesto="{{ json_encode($presupuesto) }}">
                                         <i class="nav-icon fas fa-eye"></i>
                                        </button>
                                        @endif
                                        @if ($presupuesto->estado == 1 && $permisos->where('modulo_id', Modulo::where('nombre', 'val_pre_apr')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                                        <button class="btn btn-success btn-sm btn-validar" data-toggle="tooltip" title="Validar" data-presupuesto="{{ json_encode($presupuesto) }}">
                                            <i class="nav-icon fas fa-check"></i>
                                        </button>
                                        @endif
                                        @if ($presupuesto->estado == 2 && $permisos->where('modulo_id', Modulo::where('nombre', 'val_pre_apr')->first()->id ?? null)->where('eliminar', 1)->isNotEmpty())
                                        <form action="{{ route('validar_presupuesto.anular', $presupuesto->id) }}" method="POST" style="display:inline;">
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
                    <h5 class="modal-title" id="crearObraModalTitle">Gestion de obra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('validar_presupuesto.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="presupuesto_id" id="obraPresupuestoId">
                        <input type="hidden" name="user_id" id="user_id" value="{{ session('usuario_id') }}">
                        <div class="form-group">
                            <label for="obra_id">Seleccionar Obra Existente</label>
                            <select name="obra_id" id="obra_id" class="form-control select2">
                                <option value="">-- Seleccionar Obra --</option>
                                @foreach ($obras as $obra)
                                    <option value="{{ $obra->id }}">{{ $obra->nombre }}</option>
                                @endforeach
                            </select>
                            <label for="nombre_obra">Crear Nueva Obra</label>
                            <input type="text" name="nombre_obra" id="nombre_obra" class="form-control">
                            <label for="nombre_obra">Creado por</label>
                            <input type="text" name="user" class="form-control" id="user" value="{{ session('usuario_nombre') }}" readonly>
                            <label for="nombre_obra">Fecha de aprobacion</label>
                            <input type="date" name="fecha_aprobacion" class="form-control" id="fecha_aprobacion" readonly>
                            <input type="date" name="fecha_carga" class="form-control" id="fecha_carga" hidden>
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
