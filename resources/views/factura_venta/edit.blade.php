<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Factura de Venta</title>
    @include('partials.head')
    <style>
        body { background: #f5f6fa; }
        .card-custom { border-radius: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); background: #fff; margin-top: 30px; margin-bottom: 30px; }
        .form-control, .form-select { border-radius: 10px; }
        .btn-primary, .btn-warning { border-radius: 10px; }
        .form-section-title { font-size: 1.1rem; font-weight: 600; color: #495057; margin-bottom: 10px; margin-top: 20px; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const montoInput = document.getElementById('monto');
            if (montoInput) {
                montoInput.addEventListener('input', function() {
                    let value = montoInput.value.replace(/\./g, '');
                    if (!isNaN(value) && value !== '') {
                        montoInput.value = Number(value).toLocaleString('de-DE');
                    }
                });
            }
            const saldoInput = document.getElementById('saldo');
            if (saldoInput) {
                saldoInput.addEventListener('input', function() {
                    let value = saldoInput.value.replace(/\./g, '');
                    if (!isNaN(value) && value !== '') {
                        saldoInput.value = Number(value).toLocaleString('de-DE');
                    }
                });
            }
        });
    </script>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_ing')->first()->id ?? null)->where('agregar', 1)->isEmpty())
        <script>
            window.location.href = "{{ url('/home') }}";
        </script>
    @endif
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')
        <div class="content-wrapper" style="min-height: 100vh; background: transparent;">
            <div class="container-fluid">
                <div class="card card-custom p-4 w-100" style="min-width:0;">
                    <form action="{{ route('factura_venta.update', $factura->id) }}" method="POST" class="w-100">
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-8">
                                <h2 class="mb-0">Cargar Factura de Venta</h2>
                            </div>
                            <div class="col-4 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Guardar</button>
                                <a href="{{ route('factura_venta.index', ['presupuesto' => $presupuesto->id, 'obra' => $obra->id]) }}" class="btn btn-warning px-4 ms-2" id="volver-btn"><i class="fas fa-arrow-left me-2"></i>Volver</a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @csrf
                        <input type="hidden" name="obra_id" value="{{ $obra?->id ?? '' }}">
                        <input type="hidden" name="presupuesto_aprobado_id" value="{{ $presupuesto?->id ?? '' }}">
                        <div class="form-section-title">Información del Presupuesto</div>
                        <div class="row mb-3">
                            <div class="col-md-3 mb-2">
                                <label class="form-label">Clave</label>
                                <input type="text" class="form-control" value="{{ $presupuesto?->clave ?? '' }}" disabled>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label">Fecha de carga</label>
                                <input type="text" class="form-control" value="{{ $presupuesto?->fecha_carga ? \Carbon\Carbon::parse($presupuesto->fecha_carga)->format('d/m/Y') : '' }}" disabled>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label">Monto presupuesto</label>
                                <input type="text" class="form-control" value="{{ number_format($presupuesto?->monto_total ?? 0, 0, '', '.') }}" disabled>
                            </div>
                        </div>
                        <div class="form-section-title">Datos de la factura</div>
                        <div class="row mb-3">
                            <div class="col-md-4 mb-2">
                                <label for="nro_factura" class="form-label">Número de factura</label>
                                <input type="text" name="nro_factura" class="form-control" id="nro_factura" value="{{ old('nro_factura', $factura->nro_factura) }}" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="concepto" class="form-label">Concepto</label>
                                <input type="text" name="concepto" class="form-control" id="concepto" value="{{ old('concepto', $factura->concepto) }}" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="monto" class="form-label">Monto</label>
                                <input type="text" name="monto" class="form-control" id="monto" value="{{ old('monto', number_format($factura->monto, 0, '', '.')) }}" required>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials.footer')
    </div>
</body>
</html>