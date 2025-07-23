<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Presupuestos Aprobados</title>
    @include('partials.head')
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === '1') {
                event.preventDefault();
                document.getElementById('agregar-obra-btn').click();
            }
        });
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
                        <p><strong>Cargado por:</strong> ${presupuesto.usuario ? presupuesto.usuario.nombre : 'Desconocido'}</p>
                        <p><strong>Cargado en fecha:</strong> ${presupuesto.fecha_carga}</p>
                        <p><strong>Nombre de presupuesto:</strong> ${presupuesto.clave}</p>
                        <p><strong>Obra:</strong> ${presupuesto.obra ? presupuesto.obra.nombre : 'Pendiente'}</p>
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
        });
    </script>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_ing')->first()->id ?? null)->where('ver', 1)->isEmpty())
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
                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_ing')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                        <div class="col-sm-6">
                            <a href="{{ route('presupuesto_aprobado.create') }}" class="btn btn-primary float-right" id="agregar-presupuesto-btn">Agregar Presupuesto</a>
                        </div>
                        @endif
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
                                <th>Nombre del presupuesto</th>
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
                                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_ing')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                                        <button class="btn btn-secondary btn-sm btn-ver" data-toggle="tooltip" title="Ver" data-presupuesto="{{ $presupuesto }}">
                                         <i class="nav-icon fas fa-eye"></i>
                                        </button>
                                        @endif
                                        @if ($presupuesto->estado == 1 && $permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_ing')->first()->id ?? null)->where('editar', 1)->isNotEmpty())
                                        <a href="{{ route('presupuesto_aprobado.edit', $presupuesto->id) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Editar">
                                         <i class="nav-icon fas fa-pen"></i>
                                        </a>
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
</body>
</html>
