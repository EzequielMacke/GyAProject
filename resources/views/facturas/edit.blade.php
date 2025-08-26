{{-- filepath: c:\laragon\www\GyAProject\resources\views\facturas\edit.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Factura</title>
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
            border-left: 4px solid #28a745;
        }
        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.1em;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
            padding: 10px 30px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .required-field::after {
            content: " *";
            color: #e74c3c;
            font-weight: bold;
        }
        .info-section {
            background-color: #e8f5e8;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        .presupuesto-info {
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
            padding: 12px 16px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        .file-input-label:hover {
            border-color: #28a745;
            background-color: #f0fff4;
        }
        .file-preview {
            margin-top: 10px;
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            display: none;
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
                                <i class="fas fa-edit text-success"></i>
                                Editar Factura
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
                        <div class="col-md-10">
                            <div class="card shadow-lg">
                                <div class="card-header">
                                    <h3 class="card-title mb-0">
                                        <i class="fas fa-edit mr-2"></i>
                                        Modificar Información de la Factura
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <!-- Información del presupuesto -->
                                    <div class="presupuesto-info">
                                        <h6 class="font-weight-bold mb-3">
                                            <i class="fas fa-file-invoice-dollar text-primary mr-2"></i>
                                            Presupuesto Asociado
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Obra:</strong> {{ $factura->presupuesto->obra->nombre ?? '-' }}<br>
                                                <strong>Presupuesto:</strong> {{ $factura->presupuesto->nombre }}<br>
                                                @if($factura->presupuesto->orden_trabajo)
                                                    <strong>OT:</strong> <span class="badge badge-info">{{ $factura->presupuesto->orden_trabajo }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Tipo:</strong> {{ $factura->presupuesto->tipoTrabajo->nombre ?? '-' }}<br>
                                                <strong>Estado:</strong>
                                                <span class="badge badge-pill" style="background-color: #28a745; color: #fff;">
                                                    {{ $factura->presupuesto->estado->descripcion ?? 'Sin estado' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <form action="{{ route('facturas.update', $factura->id) }}" method="POST" id="facturaForm" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <!-- Información del sistema -->
                                        <div class="info-section">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="font-weight-bold">
                                                        <i class="fas fa-user text-success mr-1"></i>
                                                        Usuario que registró
                                                    </label>
                                                    <input type="text" class="form-control-plaintext font-weight-bold"
                                                           value="{{ $factura->usuario->nombre ?? 'Usuario no encontrado' }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="font-weight-bold">
                                                        <i class="fas fa-calendar text-success mr-1"></i>
                                                        Fecha de registro
                                                    </label>
                                                    <input type="text" class="form-control-plaintext font-weight-bold"
                                                           value="{{ $factura->created_at ? \Carbon\Carbon::parse($factura->created_at)->format('d/m/Y H:i') : 'No registrada' }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información básica de la factura -->
                                        <div class="form-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-info-circle text-success mr-2"></i>
                                                Información Básica de la Factura
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="required-field">Número de Factura</label>
                                                        <input type="text" name="numero" class="form-control"
                                                               value="{{ old('numero', $factura->numero) }}"
                                                               placeholder="Ej: 001-001-000001" required>
                                                        <small class="form-text text-muted">Número de factura o timbrado</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="required-field">Fecha de Factura</label>
                                                        <input type="date" name="fecha" class="form-control"
                                                               value="{{ old('fecha', $factura->fecha) }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="required-field">Concepto</label>
                                                        <textarea name="concepto" class="form-control" rows="3"
                                                                  placeholder="Descripción del trabajo facturado..." required>{{ old('concepto', $factura->concepto) }}</textarea>
                                                        <small class="form-text text-muted">Descripción detallada del concepto facturado</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>
                                                            <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                            Documento adjunto (PDF opcional)
                                                        </label>
                                                        <div class="file-input-wrapper">
                                                            <input type="file" name="documento" id="documento" accept="application/pdf">
                                                            <label for="documento" class="file-input-label">
                                                                <i class="fas fa-cloud-upload-alt text-success fa-2x mb-2"></i>
                                                                <div class="font-weight-bold">Haga clic para seleccionar nuevo archivo PDF</div>
                                                                <div class="small text-muted mt-1">Máximo 100MB - Formato PDF únicamente</div>
                                                            </label>
                                                        </div>
                                                        @if($factura->adjunto)
                                                            <div class="file-info mt-2">
                                                                <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                                <strong>Archivo actual:</strong> {{ $factura->adjunto }}
                                                                <br>
                                                                <a href="{{ route('facturas.download-adjunto', $factura->id) }}"
                                                                   target="_blank" class="btn btn-sm btn-info mt-1">
                                                                    <i class="fas fa-eye mr-1"></i>Ver archivo actual
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <div id="documento-preview" class="file-preview"></div>
                                                        <small class="form-text text-muted">
                                                            <i class="fas fa-info-circle mr-1"></i>
                                                            Adjunte un archivo PDF que respalde lo facturado (opcional). Si selecciona un nuevo archivo, reemplazará el actual.
                                                        </small>
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
                                                                        {{ old('moneda_id', $factura->moneda_id) == $moneda->id ? 'selected' : '' }}>
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
                                                               value="{{ old('cotizacion', number_format($factura->cotizacion ?? 1, 2, ',', '.')) }}"
                                                               placeholder="7.300,00">
                                                        <small class="form-text text-muted">Cotización del dólar en guaraníes</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-8" id="monto-container">
                                                    <div class="form-group">
                                                        <label class="required-field">Monto de la Factura</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="currency-symbol">Gs.</span>
                                                            </div>
                                                            <input type="text" name="monto" id="monto"
                                                                   class="form-control"
                                                                   value="{{ old('monto', number_format($factura->monto, 2, ',', '.')) }}"
                                                                   placeholder="0,00" required>
                                                        </div>
                                                        <small class="form-text text-muted">Use punto (.) para miles y coma (,) para decimales</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botones -->
                                        <div class="row mt-4">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-primary btn-lg mr-3">
                                                    <i class="fas fa-save mr-2"></i>
                                                    Actualizar Factura
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
                    $('#cotizacion').attr('required', false).val('1,00');
                    $('#monto-container').removeClass('col-md-4').addClass('col-md-8');
                }
            });

            // Inicializar valores por defecto
            $('#moneda_id').trigger('change');

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

            // Preview de archivo PDF
            $('#documento').on('change', function() {
                const file = this.files[0];
                const preview = $('#documento-preview');

                if (file) {
                    // Validar tipo de archivo
                    if (file.type !== 'application/pdf') {
                        alert('Por favor seleccione solo archivos PDF');
                        this.value = '';
                        preview.hide();
                        return;
                    }

                    // Validar tamaño (100MB = 104857600 bytes)
                    if (file.size > 104857600) {
                        alert('El archivo es demasiado grande. El tamaño máximo permitido es 100MB');
                        this.value = '';
                        preview.hide();
                        return;
                    }

                    // Mostrar preview
                    preview.html(`
                        <div class="alert alert-success">
                            <i class="fas fa-file-pdf text-danger mr-2"></i>
                            <strong>Nuevo archivo seleccionado:</strong> ${file.name}
                            <br><small>Tamaño: ${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                        </div>
                    `).show();

                    // Actualizar label
                    $(this).siblings('label').html(`
                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                        <div class="font-weight-bold text-success">Nuevo archivo seleccionado correctamente</div>
                        <div class="small text-muted mt-1">${file.name}</div>
                    `);
                } else {
                    preview.hide();
                    // Restaurar label original
                    $(this).siblings('label').html(`
                        <i class="fas fa-cloud-upload-alt text-success fa-2x mb-2"></i>
                        <div class="font-weight-bold">Haga clic para seleccionar nuevo archivo PDF</div>
                        <div class="small text-muted mt-1">Máximo 100MB - Formato PDF únicamente</div>
                    `);
                }
            });

            // Al enviar el formulario
            $('#facturaForm').on('submit', function(e) {
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
