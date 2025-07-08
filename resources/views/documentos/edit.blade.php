<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Documento</title>
    @includeIf('partials.head')
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'gen_doc')->first()->id ?? null)->where('editar', 1)->isEmpty())
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
                            <h1 class="m-0">Editar Documento</h1>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <form method="POST" action="{{ route('documentos.update', $documento->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="usuario_id" value="{{ session('usuario_id') }}">
                    <fieldset class="border p-3 mb-4">
                        <legend class="w-auto">Datos del Documento</legend>
                        <div class="form-group mb-3">
                            <label for="nombre">Nombre del Documento:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $documento->nombre) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tipo_trabajo">Tipo de Trabajo:</label>
                            <select class="form-control" id="tipo_trabajo" name="tipo_trabajo" required>
                                <option value="">Seleccione un tipo de trabajo</option>
                                @foreach($tiposTrabajo as $trabajo)
                                    <option value="{{ $trabajo->id }}" {{ old('tipo_trabajo', $documento->tipo_trabajo_id) == $trabajo->id ? 'selected' : '' }}>{{ $trabajo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tipo_documento">Tipo de Documento:</label>
                            <select class="form-control" id="tipo_documento" name="tipo_documento" required>
                                <option value="">Seleccione un tipo de documento</option>
                                @foreach($tiposDocumento as $doc)
                                    <option value="{{ $doc->id }}" {{ old('tipo_documento', $documento->tipo_documento_id) == $doc->id ? 'selected' : '' }}>{{ $doc->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="border p-3 mb-4">
                        <legend class="w-auto">Trabajos realizados</legend>
                        <div id="trabajos-realizados-content">
                            {{-- Los checkboxes se cargarán por JS --}}
                        </div>
                    </fieldset>

                    <fieldset class="border p-3 mb-4">
                        <legend class="w-auto">Asignación de encargados a trabajos</legend>
                        <div id="trabajos-asignados-content">
                            {{-- Los selects se cargarán por JS --}}
                        </div>
                    </fieldset>

                    <fieldset class="border p-3 mb-4">
                        <legend class="w-auto">Datos generales</legend>
                        <div class="form-group mb-3">
                            <label for="nombre_obra">Nombre de la obra:</label>
                            <input type="text" class="form-control" id="nombre_obra" name="nombre_obra" value="{{ old('nombre_obra', $documento->obra) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="mes">Mes:</label>
                            <input type="text" class="form-control" id="mes" name="mes" value="{{ old('mes', $documento->mes) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="año">Año:</label>
                            <input type="text" class="form-control" id="año" name="año" value="{{ old('año', $documento->año) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="peticionario">Peticionario:</label>
                            <input type="text" class="form-control" id="peticionario" name="peticionario" value="{{ old('peticionario', $documento->peticionario) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="referencia">Referencia:</label>
                            <input type="text" class="form-control" id="referencia" name="referencia" value="{{ old('referencia', $documento->referencia) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="fecha_presupuesto">Fecha del presupuesto:</label>
                            <input type="date" class="form-control" id="fecha_presupuesto" name="fecha_presupuesto" value="{{ old('fecha_presupuesto', $documento->fecha_presupuesto ? \Carbon\Carbon::parse($documento->fecha_presupuesto)->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="ubicacion">Ubicación:</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="{{ old('ubicacion', $documento->ubicacion) }}" required>
                        </div>
                    </fieldset>

                    <fieldset class="border p-3 mb-4">
                        <legend class="w-auto">Objetivo y alcance</legend>
                        <div class="form-group mb-3">
                            <textarea class="form-control" id="objetivo_alcance" name="objetivo_alcance" rows="3" required>{{ old('objetivo_alcance', $documento->objeto_alcance) }}</textarea>
                        </div>
                    </fieldset>

                    <button type="submit" class="btn btn-primary">
                        Guardar Cambios
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
        const ensayosSeleccionados = @json($documento->trabajosDetalles->pluck('tipo_ensayo_id')->toArray());
        const encargadosSeleccionados = @json($documento->trabajosDetalles->pluck('encargado_id', 'tipo_ensayo_id')->toArray());

        // Cargar los ensayos del tipo de trabajo seleccionado y marcar los seleccionados
        function cargarEnsayos(tipoTrabajoId) {
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
                                if (ensayosSeleccionados.includes(ensayo.id)) {
                                    input.checked = true;
                                }
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
                        actualizarTrabajosAsignados();
                    })
                    .catch(() => {
                        trabajosRealizadosContent.innerHTML = '<p>Error al cargar los ensayos.</p>';
                    });
            } else {
                trabajosRealizadosContent.innerHTML = '<p class="text-muted">Seleccione un tipo de trabajo para ver los ensayos disponibles.</p>';
                actualizarTrabajosAsignados();
            }
        }

        // Actualiza la lista de trabajos asignados y selecciona el encargado correspondiente
        function actualizarTrabajosAsignados() {
            const checks = trabajosRealizadosContent.querySelectorAll('input[type="checkbox"]:checked');
            if (checks.length === 0) {
                trabajosAsignadosContent.innerHTML = '<p class="text-muted">Seleccione trabajos para asignar encargados.</p>';
                return;
            }

            let html = '';
            checks.forEach(check => {
                const ensayoLabel = trabajosRealizadosContent.querySelector('label[for="' + check.id + '"]');
                const encargadoSeleccionado = encargadosSeleccionados[check.value] ?? '';
                html += `
                    <div class="mb-3 border-bottom pb-2">
                        <strong>${ensayoLabel ? ensayoLabel.textContent : ''}</strong>
                        <div class="form-group mt-2">
                            <label>Encargado:</label>
                            <select class="form-control" name="encargados_trabajo[${check.value}]" required>
                                <option value="">Seleccione un encargado</option>
                                ${encargados.map(enc => `<option value="${enc.id}" ${encargadoSeleccionado == enc.id ? 'selected' : ''}>${enc.nombre}</option>`).join('')}
                            </select>
                        </div>
                    </div>
                `;
            });
            trabajosAsignadosContent.innerHTML = html;
        }

        // Inicializar campos al cargar la página
        cargarEnsayos(tipoTrabajoSelect.value);

        tipoTrabajoSelect.addEventListener('change', function () {
            cargarEnsayos(this.value);
        });

        trabajosRealizadosContent.addEventListener('change', function (e) {
            if (e.target.type === 'checkbox') {
                actualizarTrabajosAsignados();
            }
        });
    });
    </script>
</body>
</html>
