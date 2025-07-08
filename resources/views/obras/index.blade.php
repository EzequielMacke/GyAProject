<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Obras</title>
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
            const tableRows = document.querySelectorAll('#obras-table tbody tr');
            const tipoTrabajo = @json($tipo_trabajo);
            const estados = @json($estados);
            const estados_pre = @json($estados_pre);

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

            document.querySelectorAll('.btn-ver').forEach(button => {
                button.addEventListener('click', function() {
                    const obra = JSON.parse(this.getAttribute('data-obra'));
                    const presupuestos = obra.presupuestos || [];
                    const modalTitle = document.getElementById('verObraModalTitle');
                    const modalBody = document.getElementById('verObraModalBody');

                    modalTitle.textContent = `Obra ID: ${obra.id}`;
                let presupuestosHtml = '';
                if (presupuestos.length > 0) {
                    presupuestosHtml = `
                        <h5>Presupuestos Asociados</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del presupuesto</th>
                                    <th>Tipo de Trabajo</th>
                                    <th>Orden de Trabajo</th>
                                    <th>PDF</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    presupuestos.forEach(presupuesto => {
                        const pdfUrl = presupuesto.presupuesto.replace('public/', '');
                        presupuestosHtml += `
                            <tr>
                                <td>${presupuesto.id}</td>
                                <td>${presupuesto.clave}</td>
                                <td>${tipoTrabajo[presupuesto.tipo_trabajo] || 'Desconocido'}</td>
                                <td>${presupuesto.orden_trabajo || 'Pendiente'}</td>
                                <td><a href="/storage/${pdfUrl}" target="_blank">Ver PDF</a></td>
                                <td>${estados_pre[presupuesto.estado] || 'Pendiente'}</td>
                            </tr>
                        `;
                    });
                    presupuestosHtml += `
                            </tbody>
                        </table>
                    `;
                } else {
                    presupuestosHtml = '<p>No hay presupuestos asociados a esta obra.</p>';
                }

                modalBody.innerHTML = `
                    <p><strong>Nombre:</strong> ${obra.nombre || 'Pendiente'}</p>
                    <p><strong>Dirección:</strong> ${obra.direccion || 'Pendiente'}</p>
                    <p><strong>Contacto:</strong> ${obra.contacto || 'Pendiente'}</p>
                    <p><strong>Número de contacto:</strong> ${obra.numero || 'Pendiente'}</p>
                    <p><strong>Peticionario:</strong> ${obra.peticionario || 'Pendiente'}</p>
                    <p><strong>Observación:</strong> ${obra.observacion || 'Pendiente'}</p>
                    <p><strong>Cargado por:</strong> ${obra.usuario.nombre || 'Pendiente'}</p>
                    <p><strong>Cargado en fecha:</strong> ${obra.fecha_carga || 'Pendiente'}</p>
                    <p><strong>RUC:</strong> ${obra.ruc || 'Pendiente'}</p>
                    <p><strong>Razón Social:</strong> ${obra.razon_social || 'Pendiente'}</p>
                    <p><strong>Dirección Facturación:</strong> ${obra.direccion_fac || 'Pendiente'}</p>
                    <p><strong>Correo Facturación:</strong> ${obra.correo_fac || 'Pendiente'}</p>
                    <p><strong>Correo Peticionario:</strong> ${obra.correo_pet || 'Pendiente'}</p>
                    <p><strong>Nombre Obra:</strong> ${obra.nombre_obr || 'Pendiente'}</p>
                    <p><strong>Teléfono Obra:</strong> ${obra.telefono_obr || 'Pendiente'}</p>
                    <p><strong>Correo Obra:</strong> ${obra.correo_obr || 'Pendiente'}</p>
                    <p><strong>Nombre Administrador:</strong> ${obra.nombre_adm || 'Pendiente'}</p>
                    <p><strong>Teléfono Administrador:</strong> ${obra.telefono_adm || 'Pendiente'}</p>
                    <p><strong>Correo Administrador:</strong> ${obra.correo_adm || 'Pendiente'}</p>
                    ${presupuestosHtml}
                `;
                    $('#verObraModal').modal('show');
                });
            });
        });
    </script>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('ver', 1)->isEmpty())
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
                            <h1 class="m-0">Listado de Obras</h1>
                        </div>
                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('agregar', 1)->isNotEmpty())
                        <div class="col-sm-6">
                            <a href="{{ route('obras.create') }}" class="btn btn-primary float-right" id="agregar-obra-btn">Agregar Obra</a>
                        </div>
                        @endif
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <input type="text" id="search" name="search" class="form-control mr-2" placeholder="Buscar obras...">
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
                    <table class="table table-bordered" id="obras-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Dirección</th>
                                <th>Contacto</th>
                                <th>Numero de contacto</th>
                                <th>Peticionario</th>
                                <th>Cargado por</th>
                                <th>Cargado en fecha</th>
                                <th>Observacion</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($obras->reverse() as $obra)
                                <tr>
                                    <td>{{ $obra->id }}</td>
                                    <td>{{ $obra->nombre }}</td>
                                    <td>{{ $obra->direccion }}</td>
                                    <td>{{ $obra->contacto }}</td>
                                    <td>{{ $obra->numero }}</td>
                                    <td>{{ $obra->peticionario }}</td>
                                    <td>{{ $obra->usuario->nombre }}</td>
                                    <td>{{ $obra->fecha_carga }}</td>
                                    <td>{{ $obra->observacion }}</td>
                                    <td>{{ $estados[$obra->estado] ?? 'Desconocido' }}</td>
                                    <td>
                                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('editar', 1)->isNotEmpty())
                                        <a href="{{ route('obras.edit', $obra->id) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Editar">
                                         <i class="nav-icon fas fa-pen"></i>
                                        </a>
                                        @endif
                                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                                        <button class="btn btn-secondary btn-sm btn-ver" data-toggle="tooltip" title="Ver" data-obra="{{ json_encode($obra) }}" data-presupuestos="{{ json_encode($presupuestos->where('obra_id', $obra->id)) }}">
                                            <i class="nav-icon fas fa-eye"></i>
                                        </button>
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
    <div class="modal fade" id="verObraModal" tabindex="-1" role="dialog" aria-labelledby="verObraModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verObraModalTitle">Ver Obra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="verObraModalBody">
                    <!-- Aquí se llenará la información de la obra -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
