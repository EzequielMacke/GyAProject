{{-- filepath: c:\laragon\www\GyAProject\resources\views\recibos\edit.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Recibo</title>
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
            border-left: 4px solid #17a2b8;
        }
        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.1em;
        }
        .form-control:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
        }
        .btn-primary {
            background-color: #17a2b8;
            border-color: #17a2b8;
            padding: 10px 30px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .required-field::after {
            content: " *";
            color: #e74c3c;
            font-weight: bold;
        }
        .info-section {
            background-color: #e0f7fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #17a2b8;
        }
        .factura-info {
            background-color: #e8f5e8;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
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
                                <i class="fas fa-edit text-info"></i>
                                Editar Recibo
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
                                        <i class="fas fa-receipt mr-2"></i>
                                        Modificar Información del Recibo
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('recibos.update', $recibo->id) }}" method="POST" id="reciboForm">
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
                                                           value="{{ $recibo->usuario->nombre ?? session('usuario_nombre') }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="font-weight-bold">
                                                        <i class="fas fa-calendar text-info mr-1"></i>
                                                        Fecha de registro
                                                    </label>
                                                    <input type="text" class="form-control-plaintext font-weight-bold"
                                                           value="{{ $recibo->created_at ? \Carbon\Carbon::parse($recibo->created_at)->format('d/m/Y H:i') : 'No registrada' }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información de la factura -->
                                        <div class="factura-info">
                                            <h6 class="font-weight-bold mb-3">
                                                <i class="fas fa-file-invoice text-success mr-2"></i>
                                                Información de la Factura Asociada
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="font-weight-bold">Factura Nº:</label>
                                                    <p class="mb-1">{{ $recibo->factura->numero ?? 'S/N' }}</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="font-weight-bold">Presupuesto:</label>
                                                    <p class="mb-1">{{ $recibo->factura->presupuesto->nombre ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="font-weight-bold">Obra:</label>
                                                    <p class="mb-1">{{ $recibo->factura->presupuesto->obra->nombre ?? '-' }}</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="font-weight-bold">Monto Factura:</label>
                                                    <p class="mb-1">
                                                        @if($recibo->factura->moneda_id == 2 && $recibo->factura->cotizacion)
                                                            <span class="text-success font-weight-bold">Gs. {{ number_format($recibo->factura->monto * $recibo->factura->cotizacion, 0, ',', '.') }}</span>
                                                            <br><small class="text-muted">{{ $recibo->factura->moneda->simbolo }} {{ number_format($recibo->factura->monto, 2, ',', '.') }}</small>
                                                        @else
                                                            <span class="text-success font-weight-bold">{{ $recibo->factura->moneda->simbolo ?? 'Gs.' }} {{ number_format($recibo->factura->monto, 2, ',', '.') }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información del recibo -->
                                        <div class="form-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-receipt text-info mr-2"></i>
                                                Información del Recibo
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="required-field">Número de Recibo</label>
                                                        <input type="text" name="numero" class="form-control"
                                                               value="{{ old('numero', $recibo->numero) }}"
                                                               placeholder="Ej: REC-001, 00123" required>
                                                        <small class="form-text text-muted">Número de identificación del recibo</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="required-field">Fecha del Recibo</label>
                                                        <input type="date" name="fecha" class="form-control"
                                                               value="{{ old('fecha', $recibo->fecha) }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="required-field">Concepto</label>
                                                        <textarea name="concepto" class="form-control" rows="3" required
                                                                  placeholder="Ej: Pago parcial por servicios de instalación eléctrica">{{ old('concepto', $recibo->concepto) }}</textarea>
                                                        <small class="form-text text-muted">Descripción del pago recibido</small>
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
                                                                        {{ old('moneda_id', $recibo->moneda_id) == $moneda->id ? 'selected' : '' }}>
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
                                                               value="{{ old('cotizacion', number_format($recibo->cotizacion ?? 1, 2, ',', '.')) }}"
                                                               placeholder="7.300,00">
                                                        <small class="form-text text-muted">Cotización del dólar en guaraníes</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-8" id="monto-container">
                                                    <div class="form-group">
                                                        <label class="required-field">Monto del Recibo</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="currency-symbol">Gs.</span>
                                                            </div>
                                                            <input type="text" name="monto" id="monto"
                                                                   class="form-control"
                                                                   value="{{ old('monto', number_format($recibo->monto, 2, ',', '.')) }}"
                                                                   placeholder="0,00" required>
                                                        </div>
                                                        <small class="form-text text-muted">Use punto (.) para miles y coma (,) para decimales</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Resumen de montos -->
                                            @php
                                                $totalCobradoFactura = $recibo->factura->recibos->sum(function($r) {
                                                    return $r->moneda_id == 2 ? $r->monto * $r->cotizacion : $r->monto;
                                                });
                                                $montoFactura = $recibo->factura->moneda_id == 2 ? $recibo->factura->monto * $recibo->factura->cotizacion : $recibo->factura->monto;
                                                $montoReciboActual = $recibo->moneda_id == 2 ? $recibo->monto * $recibo->cotizacion : $recibo->monto;
                                                $totalSinActual = $totalCobradoFactura - $montoReciboActual;
                                                $saldoPendiente = $montoFactura - $totalSinActual;
                                            @endphp
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="alert alert-info">
                                                        <h6 class="font-weight-bold mb-2">
                                                            <i class="fas fa-calculator mr-1"></i>
                                                            Resumen de Cobros de la Factura
                                                        </h6>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <small class="text-muted">Total Factura:</small><br>
                                                                <span class="font-weight-bold text-primary">Gs. {{ number_format($montoFactura, 0, ',', '.') }}</span>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <small class="text-muted">Otros Recibos:</small><br>
                                                                <span class="font-weight-bold text-success">Gs. {{ number_format($totalSinActual, 0, ',', '.') }}</span>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <small class="text-muted">Recibo Actual:</small><br>
                                                                <span class="font-weight-bold text-info">Gs. {{ number_format($montoReciboActual, 0, ',', '.') }}</span>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <small class="text-muted">Saldo Disponible:</small><br>
                                                                <span class="font-weight-bold text-warning">Gs. {{ number_format($saldoPendiente, 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botones -->
                                        <div class="row mt-4">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-primary btn-lg mr-3">
                                                    <i class="fas fa-save mr-2"></i>
                                                    Actualizar Recibo
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
                    $('#cotizacion').attr('required', false);
                    $('#monto-container').removeClass('col-md-4').addClass('col-md-8');
                }
            });

            // Inicializar estado de moneda al cargar
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

            // Al enviar el formulario
            $('#reciboForm').on('submit', function(e) {
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
