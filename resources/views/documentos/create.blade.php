<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Documento</title>
    @includeIf('partials.head')
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'gen_doc')->first()->id ?? null)->where('agregar', 1)->isEmpty())
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
                            <h1 class="m-0">Cargar Documento</h1>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <form method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data">
                 @csrf
                    <input type="hidden" name="usuario_id" value="{{ session('usuario_id') }}">
                    <fieldset class="border p-3 mb-4">
                        <legend class="w-auto">Datos del Documento</legend>
                        <div class="form-group mb-3">
                            <label for="nombre">Nombre del Documento:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del documento" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tipo_trabajo">Tipo de Trabajo:</label>
                            <select class="form-control" id="tipo_trabajo" name="tipo_trabajo" required>
                                <option value="">Seleccione un tipo de trabajo</option>
                                @foreach($tiposTrabajo as $trabajo)
                                    <option value="{{ $trabajo->id }}">{{ $trabajo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tipo_documento">Tipo de Documento:</label>
                            <select class="form-control" id="tipo_documento" name="tipo_documento" required>
                                <option value="">Seleccione un tipo de documento</option>
                                @foreach($tiposDocumento as $documento)
                                    <option value="{{ $documento->id }}">{{ $documento->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="border p-3 mb-4">
                        <legend class="w-auto">Trabajos realizados</legend>
                        <div id="trabajos-realizados-content">
                            <p class="text-muted">Seleccione un tipo de trabajo para ver los ensayos disponibles.</p>
                        </div>
                    </fieldset>

                    <fieldset class="border p-3 mb-4">
                        <legend class="w-auto">Asignación de encargados a trabajos</legend>
                        <div id="trabajos-asignados-content">
                            <p class="text-muted">Seleccione trabajos para asignar encargados.</p>
                        </div>
                    </fieldset>

                    <fieldset class="border p-3 mb-4">
                        <legend class="w-auto">Datos generales</legend>
                        <div class="form-group mb-3">
                            <label for="nombre_obra">Nombre de la obra:</label>
                            <input type="text" class="form-control" id="nombre_obra" name="nombre_obra" placeholder="Ingrese el nombre de la obra" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="mes">Mes:</label>
                            <input type="text" class="form-control" id="mes" name="mes" placeholder="Ingrese el mes" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="año">Año:</label>
                            <input type="text" class="form-control" id="año" name="año" placeholder="Ingrese el año" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="peticionario">Peticionario:</label>
                            <input type="text" class="form-control" id="peticionario" name="peticionario" placeholder="Ingrese el peticionario" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="referencia">Referencia:</label>
                            <input type="text" class="form-control" id="referencia" name="referencia" placeholder="Ingrese la referencia" value="Inf XX/XX." required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="fecha_presupuesto">Fecha del presupuesto:</label>
                            <input type="date" class="form-control" id="fecha_presupuesto" name="fecha_presupuesto" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="ubicacion">Ubicación:</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="Ingrese la ubicación" value="sobre las calles XXXXX casi XXXXX en la ciudad de XXXXX." required>
                        </div>
                    </fieldset>

                    <fieldset class="border p-3 mb-4">
                        <legend class="w-auto">Objetivo y alcance</legend>
                        <div class="form-group mb-3">
                            <textarea class="form-control" id="objetivo_alcance" name="objetivo_alcance" rows="3" required>El presente informe tiene por objeto la evaluación del estado general de la estructura de hormigón armado y su verificación para el uso previsto.</textarea>
                        </div>
                    </fieldset>

                    <button type="submit" class="btn btn-primary">
                        Guardar
                    </button>
                    <a href="{{ route('documentos.index') }}" class="btn btn-warning">
                        Cancelar
                    </a>
                </form>
            </section>
        </div>
        @include('partials.footer')
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {


        const tipoTrabajoSelect = document.getElementById('tipo_trabajo');
        const trabajosRealizadosContent = document.getElementById('trabajos-realizados-content');
        const trabajosAsignadosContent = document.getElementById('trabajos-asignados-content');
        const encargados = @json($encargados);


        tipoTrabajoSelect.addEventListener('change', function () {
            const tipoTrabajoId = this.value;
            trabajosRealizadosContent.innerHTML = '';

            if (tipoTrabajoId) {
                fetch(`/ensayos-por-tipo/${tipoTrabajoId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(ensayo => {
                                const div = document.createElement('div');
                                div.classList.add('form-check', 'mb-2');
                                const input = document.createElement('input');
                                input.type = 'checkbox';
                                input.className = 'form-check-input';
                                input.name = 'ensayos[]';
                                input.value = ensayo.id;
                                input.id = 'ensayo_' + ensayo.id;
                                const label = document.createElement('label');
                                label.className = 'form-check-label';
                                label.htmlFor = input.id;
                                label.textContent = ensayo.nombre;
                                div.appendChild(input);
                                div.appendChild(label);
                                trabajosRealizadosContent.appendChild(div);
                            });
                        } else {
                            trabajosRealizadosContent.innerHTML = '<p>No hay ensayos para este tipo de trabajo.</p>';
                        }
                    })
                    .catch(() => {
                        trabajosRealizadosContent.innerHTML = '<p>Error al cargar los ensayos.</p>';
                    });
            } else {
                trabajosRealizadosContent.innerHTML = '<p class="text-muted">Seleccione un tipo de trabajo para ver los ensayos disponibles.</p>';
            }
        });

        // Función para actualizar la lista de trabajos asignados
        function actualizarTrabajosAsignados() {
            // Obtener todos los checks seleccionados
            const checks = trabajosRealizadosContent.querySelectorAll('input[type="checkbox"]:checked');
            if (checks.length === 0) {
                trabajosAsignadosContent.innerHTML = '<p class="text-muted">Seleccione trabajos para asignar encargados.</p>';
                return;
            }

            let html = '';
            checks.forEach(check => {
                const ensayoLabel = trabajosRealizadosContent.querySelector('label[for="' + check.id + '"]');
                html += `
                    <div class="mb-3 border-bottom pb-2">
                        <strong>${ensayoLabel ? ensayoLabel.textContent : ''}</strong>
                        <div class="form-group mt-2">
                            <label>Encargado:</label>
                            <select class="form-control" name="encargados_trabajo[${check.value}]" required>
                                <option value="">Seleccione un encargado</option>
                                ${encargados.map(enc => `<option value="${enc.id}">${enc.nombre}</option>`).join('')}
                            </select>
                        </div>
                    </div>
                `;
            });
            trabajosAsignadosContent.innerHTML = html;
        }
        // Delegar el evento de cambio en los checks de trabajos realizados
        trabajosRealizadosContent.addEventListener('change', function (e) {
            if (e.target.type === 'checkbox') {
                actualizarTrabajosAsignados();
            }
        });

        // Si cambian los ensayos (por cambio de tipo de trabajo), limpiar asignaciones
        document.getElementById('tipo_trabajo').addEventListener('change', function () {
            trabajosAsignadosContent.innerHTML = '<p class="text-muted">Seleccione trabajos para asignar encargados.</p>';
        });
    });
    </script>

</body>
</html>
