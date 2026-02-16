<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cargar Recibo de Venta</title>
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
		});
	</script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		@include('partials.navbar')
		@include('partials.sidebar')
		<div class="content-wrapper" style="min-height: 100vh; background: transparent;">
			<div class="container-fluid">
				<div class="card card-custom p-4 w-100" style="min-width:0;">
					<form action="{{ route('recibo_venta.update', $recibo->id) }}" method="POST" class="w-100">
						@csrf
						@method('PUT')
						<div class="row mb-3">
							<div class="col-8">
								<h2 class="mb-0">Cargar Recibo de Venta</h2>
							</div>
							<div class="col-4 text-end">
								<button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Guardar</button>
								<a href="{{ route('recibo_venta.index', ['presupuesto' => $presupuesto?->id ?? null, 'obra' => $obra?->id ?? null, 'factura' => $factura?->id ?? null]) }}" class="btn btn-warning px-4 ms-2" id="volver-btn"><i class="fas fa-arrow-left me-2"></i>Volver</a>
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
						<input type="hidden" name="factura_id" value="{{ $factura?->id ?? '' }}">
						<div class="form-section-title">Información de la Factura</div>
						<div class="row mb-3">
							<div class="col-md-4 mb-2">
								<label class="form-label">Nro. Factura</label>
								<input type="text" class="form-control" value="{{ $factura?->nro_factura ?? '-' }}" disabled>
							</div>
							<div class="col-md-4 mb-2">
								<label class="form-label">Concepto</label>
								<input type="text" class="form-control" value="{{ $factura?->concepto ?? '-' }}" disabled>
							</div>
							<div class="col-md-4 mb-2">
								<label class="form-label">Monto factura</label>
								<input type="text" class="form-control" value="{{ isset($factura) ? number_format($factura->monto, 0, '', '.') : '-' }}" disabled>
							</div>
						</div>
						<div class="form-section-title">Datos del recibo</div>
						<div class="row mb-3">
							<div class="col-md-4 mb-2">
								<label for="nro_recibo" class="form-label">Número de recibo</label>
								<input type="text" name="nro_recibo" class="form-control" id="nro_recibo" value="{{ old('nro_recibo', $recibo->nro_recibo) }}" required>
							</div>
							<div class="col-md-4 mb-2">
								<label for="concepto" class="form-label">Concepto</label>
								<input type="text" name="concepto" class="form-control" id="concepto" value="{{ old('concepto', $recibo->concepto) }}" required>
							</div>
							<div class="col-md-4 mb-2">
								<label for="monto" class="form-label">Monto</label>
								<input type="text" name="monto" class="form-control" id="monto" value="{{ old('monto', number_format($recibo->monto, 0, '', '.')) }}" required>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-4 mb-2">
								<label for="fecha_emision" class="form-label">Fecha de emisión</label>
								<input type="date" name="fecha_emision" class="form-control" id="fecha_emision" value="{{ old('fecha_emision', $recibo->fecha_emision ? \Carbon\Carbon::parse($recibo->fecha_emision)->format('Y-m-d') : '') }}" required>
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
