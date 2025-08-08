{{-- filepath: c:\laragon\www\GyAProject\resources\views\presupuestos\edit.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Presupuesto</title>
    @include('partials.head')
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('editar', 1)->isEmpty())
        <script>
            window.location.href = "{{ url('/home') }}";
        </script>
    @endif
    <style>
        .card-header {
            background-color: #f8f9fa;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
        }
        .form-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.1em;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px 30px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        .required-field::after {
            content: " *";
            color: #e74c3c;
            font-weight: bold;
        }
        .info-section {
            background-color: #e3f2fd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #2196f3;
        }
        .file-info {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            padding: 8px 12px;
            margin-top: 5px;
        }
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }
        .file-input-label {
            cursor: pointer;
            display: block;
            padding: 8px 12px;
            border: 2px dashed #dee2e6;
            border-radius: 4px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .file-input-label:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
        }
        .cotizacion-container {
            display: none;
        }
    </style>
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
                            <h1 class="m-0">
                                <i class="fas fa-edit text-primary"></i>
                                Editar Presupuesto
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-sm-right">
                                <a href="{{ route('presupuestos.index') }}" class="btn btn-outline-secondary btn-sm mt-2">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Volver a Presupuestos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card shadow-lg">
                                <div class="card-header">
                                    <h3 class="card-title mb-0">
                                        <i class="fas fa-edit mr-2"></i>
                                        Modificar Información del Presupuesto
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('presupuestos.update', $presupuesto->id) }}" method="POST" enctype="multipart/form-data" id="presupuestoForm">
                                        @csrf
                                        @method('PUT')

                                        <!-- Información del sistema -->
                                        <div class="info-section">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="font-weight-bold">
                                                        <i class="fas fa-user text-info mr-1"></i>
                                                        Usuario que registró
                                                    </label>
                                                    <input type="text" class="form-control-plaintext font-weight-bold"
                                                           value="{{ $presupuesto->usuario->nombre ?? session('usuario_nombre') }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="font-weight-bold">
                                                        <i class="fas fa-calendar text-info mr-1"></i>
                                                        Fecha de registro
                                                    </label>
                                                    <input type="text" class="form-control-plaintext font-weight-bold"
                                                           value="{{ $presupuesto->created_at ? \Carbon\Carbon::parse($presupuesto->created_at)->format('d/m/Y H:i') : 'No registrada' }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información básica -->
                                        <div class="form-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-info-circle text-primary mr-2"></i>
                                                Información Básica
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="required-field">Nombre del Presupuesto</label>
                                                        <input type="text" name="nombre" class="form-control"
                                                               value="{{ old('nombre', $presupuesto->nombre) }}"
                                                               placeholder="Ej: Instalación eléctrica edificio A" required>
                                                        <small class="form-text text-muted">Ingrese un nombre descriptivo para el presupuesto</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="required-field">Obra Asociada</label>
                                                        <select name="obra_id" class="form-control select2" style="width: 100%;" required>
                                                            <option value="">Seleccionar obra...</option>
                                                            @foreach($obras as $obra)
                                                                <option value="{{ $obra->id }}" {{ old('obra_id', $presupuesto->obra_id) == $obra->id ? 'selected' : '' }}>
                                                                    {{ $obra->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Orden de Trabajo</label>
                                                        <input type="text" name="orden_trabajo" id="orden_trabajo"
                                                               class="form-control"
                                                               value="{{ old('orden_trabajo', $presupuesto->orden_trabajo ?: 'OT-') }}"
                                                               placeholder="OT-001">
                                                        <small class="form-text text-muted">Opcional: Número de orden de trabajo</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Tipo de Trabajo</label>
                                                        <select name="tipo_trabajo_id" class="form-control">
                                                            <option value="">Seleccionar tipo...</option>
                                                            @foreach($tipos_trabajo as $tipo)
                                                                <option value="{{ $tipo->id }}" {{ old('tipo_trabajo_id', $presupuesto->tipo_trabajo_id) == $tipo->id ? 'selected' : '' }}>
                                                                    {{ $tipo->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información financiera -->
                                        <div class="form-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-dollar-sign text-success mr-2"></i>
                                                Información Financiera
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="required-field">Moneda</label>
                                                        <select name="moneda_id" id="moneda_id" class="form-control" required>
                                                            @foreach($monedas as $moneda)
                                                                <option value="{{ $moneda->id }}"
                                                                        data-simbolo="{{ $moneda->simbolo }}"
                                                                        {{ old('moneda_id', $presupuesto->moneda_id) == $moneda->id ? 'selected' : '' }}>
                                                                    {{ $moneda->descripcion }} ({{ $moneda->simbolo }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 cotizacion-container">
                                                    <div class="form-group">
                                                        <label class="required-field">Cotización USD</label>
                                                        <input type="text" name="cotizacion" id="cotizacion"
                                                               class="form-control"
                                                               value="{{ old('cotizacion', number_format($presupuesto->cotizacion ?? 1, 2, ',', '.')) }}"
                                                               placeholder="7.300,00">
                                                        <small class="form-text text-muted">Cotización del dólar en guaraníes</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-8" id="monto-container">
                                                    <div class="form-group">
                                                        <label class="required-field">Monto del Presupuesto</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="currency-symbol">Gs.</span>
                                                            </div>
                                                            <input type="text" name="monto" id="monto"
                                                                   class="form-control"
                                                                   value="{{ old('monto', number_format($presupuesto->monto, 2, ',', '.')) }}"
                                                                   placeholder="0,00" required>
                                                        </div>
                                                        <small class="form-text text-muted">Use punto (.) para miles y coma (,) para decimales</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Estado y fechas -->
                                        <div class="form-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-tasks text-warning mr-2"></i>
                                                Estado y Fechas
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Estado del Presupuesto</label>
                                                        <select name="estado_id" class="form-control">
                                                            <option value="">Seleccionar estado...</option>
                                                            @foreach($estados as $estado)
                                                                <option value="{{ $estado->id }}" {{ old('estado_id', $presupuesto->estado_id) == $estado->id ? 'selected' : '' }}>
                                                                    {{ $estado->descripcion }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="required-field">Fecha de Aprobación</label>
                                                        <input type="date" name="fecha" class="form-control"
                                                               value="{{ old('fecha', $presupuesto->fecha) }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Documentos -->
                                        <div class="form-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                Documentos Adjuntos
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Presupuesto (PDF)</label>
                                                        <div class="file-input-wrapper">
                                                            <input type="file" name="presupuesto" id="presupuesto"
                                                                   accept="application/pdf">
                                                            <label for="presupuesto" class="file-input-label">
                                                                <i class="fas fa-cloud-upload-alt text-primary mr-2"></i>
                                                                Haga clic para seleccionar nuevo archivo PDF
                                                                <div class="small text-muted mt-1">Máximo 10MB</div>
                                                            </label>
                                                        </div>
                                                        @if($presupuesto->presupuesto)
                                                            <div class="file-info mt-2">
                                                                <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                                <strong>Archivo actual:</strong> {{ $presupuesto->presupuesto }}
                                                                <br>
                                                                <a href="{{ route('presupuestos.download-file', ['id' => $presupuesto->id, 'type' => 'presupuesto']) }}"
                                                                   target="_blank" class="btn btn-sm btn-info mt-1">
                                                                    <i class="fas fa-eye mr-1"></i>Ver archivo actual
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <div id="presupuesto-preview" class="mt-2"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Nota de Conformidad (PDF)</label>
                                                        <div class="file-input-wrapper">
                                                            <input type="file" name="conformidad" id="conformidad"
                                                                   accept="application/pdf">
                                                            <label for="conformidad" class="file-input-label">
                                                                <i class="fas fa-cloud-upload-alt text-primary mr-2"></i>
                                                                Haga clic para seleccionar nuevo archivo PDF
                                                                <div class="small text-muted mt-1">Máximo 10MB</div>
                                                            </label>
                                                        </div>
                                                        @if($presupuesto->conformidad)
                                                            <div class="file-info mt-2">
                                                                <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                                <strong>Archivo actual:</strong> {{ $presupuesto->conformidad }}
                                                                <br>
                                                                <a href="{{ route('presupuestos.download-file', ['id' => $presupuesto->id, 'type' => 'conformidad']) }}"
                                                                   target="_blank" class="btn btn-sm btn-info mt-1">
                                                                    <i class="fas fa-eye mr-1"></i>Ver archivo actual
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <div id="conformidad-preview" class="mt-2"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botones -->
                                        <div class="row mt-4">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-primary btn-lg mr-3">
                                                    <i class="fas fa-save mr-2"></i>
                                                    Actualizar Presupuesto
                                                </button>
                                                <a href="{{ route('presupuestos.index') }}" class="btn btn-secondary btn-lg">
                                                    <i class="fas fa-times mr-2"></i>
                                                    Cancelar
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: "Buscar y seleccionar obra...",
                allowClear: true,
                width: '100%'
            });

            // Manejar cambio de moneda
            $('#moneda_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const simbolo = selectedOption.data('simbolo');
                const monedaId = $(this).val();

                // Actualizar símbolo de moneda
                $('#currency-symbol').text(simbolo);

                // Mostrar/ocultar cotización para USD
                if (monedaId == 2) { // USD
                    $('.cotizacion-container').show();
                    $('#cotizacion').attr('required', true);
                    $('#monto-container').removeClass('col-md-8').addClass('col-md-4');
                } else {
                    $('.cotizacion-container').hide();
                    $('#cotizacion').attr('required', false);
                    $('#monto-container').removeClass('col-md-4').addClass('col-md-8');
                }
            });

            // Inicializar estado de moneda al cargar
            $('#moneda_id').trigger('change');

            // Manejar campo OT
            $('#orden_trabajo').on('input', function() {
                let value = this.value;
                if (!value.startsWith('OT-')) {
                    value = 'OT-' + value.replace(/^OT-?/, '');
                }
                value = value.replace(/^(OT-)(.*)/, function(match, prefix, numbers) {
                    return prefix + numbers.replace(/[^\d]/g, '');
                });
                this.value = value;
            });

            $('#orden_trabajo').on('focus', function() {
                if (this.value === 'OT-') {
                    this.setSelectionRange(3, 3);
                }
            });

            // Formatear monto
            $('#monto').on('input', function() {
                let value = this.value.replace(/[^\d,]/g, '');
                let parts = value.split(',');
                if (parts[0]) {
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }
                if (parts.length > 2) {
                    parts = [parts[0], parts.slice(1).join('')];
                }
                if (parts[1] && parts[1].length > 2) {
                    parts[1] = parts[1].substring(0, 2);
                }
                this.value = parts.join(',');
            });

            // Formatear cotización
            $('#cotizacion').on('input', function() {
                let value = this.value.replace(/[^\d,]/g, '');
                let parts = value.split(',');
                if (parts[0]) {
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }
                if (parts.length > 2) {
                    parts = [parts[0], parts.slice(1).join('')];
                }
                if (parts[1] && parts[1].length > 4) {
                    parts[1] = parts[1].substring(0, 4);
                }
                this.value = parts.join(',');
            });

            // Preview de archivos
            function handleFilePreview(input, previewId) {
                $(input).on('change', function() {
                    const file = this.files[0];
                    const preview = $(previewId);

                    if (file) {
                        preview.html(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-file-pdf mr-2"></i>
                                <strong>Nuevo archivo seleccionado:</strong> ${file.name}
                                <small class="d-block">Tamaño: ${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                            </div>
                        `);

                        // Actualizar label
                        $(this).siblings('label').html(`
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            ${file.name}
                            <div class="small text-muted mt-1">Nuevo archivo seleccionado correctamente</div>
                        `);
                    } else {
                        preview.empty();
                    }
                });
            }

            handleFilePreview('#presupuesto', '#presupuesto-preview');
            handleFilePreview('#conformidad', '#conformidad-preview');

            // Al enviar el formulario
            $('#presupuestoForm').on('submit', function(e) {
                // Convertir formatos numéricos
                let montoValue = $('#monto').val().replace(/\./g, '').replace(',', '.');
                $('#monto').val(montoValue);

                let cotizacionValue = $('#cotizacion').val().replace(/\./g, '').replace(',', '.');
                $('#cotizacion').val(cotizacionValue);

                // Mostrar loading
                $('button[type="submit"]').html('<i class="fas fa-spinner fa-spin mr-2"></i>Actualizando...').prop('disabled', true);
            });
        });
    </script>
</body>
</html>
