<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Listado de Recibos de Venta</title>
	@include('partials.head')
	<style>
		.recibo-card { cursor: pointer; border-radius:10px; overflow:hidden; transition: transform .18s, box-shadow .18s; }
		.recibo-card:hover { transform: translateY(-6px); box-shadow: 0 10px 25px rgba(16,24,40,0.08); }
		.recibo-info { padding: 20px; }
		.recibo-info h5 { margin-bottom: 10px; font-size:1.25rem; }
		.recibo-meta { color:#6b7280; font-size:1rem; }
		.add-card { border: 2px dashed #2f8f4a; background: linear-gradient(180deg,#f0fff4,#e6ffed); transition: box-shadow .18s, transform .18s, border-color .18s; }
		.add-card:hover, .add-card:focus { box-shadow: 0 8px 32px rgba(47,143,74,0.13); border-color: #1e6b36; transform: scale(1.035) translateY(-4px); }
	</style>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const searchInput = document.getElementById('search');
		const cards = Array.from(document.querySelectorAll('.recibo-card'));
		function filterCards() {
			const term = (searchInput.value || '').trim().toLowerCase();
			cards.forEach(card => {
				const nro = card.querySelector('.recibo-info h5')?.textContent?.toLowerCase() || '';
				const concepto = card.querySelector('.recibo-meta')?.textContent?.toLowerCase() || '';
				const monto = card.querySelector('.text-right .h5')?.textContent?.toLowerCase() || '';
				const fecha = card.querySelector('.text-right small')?.textContent?.toLowerCase() || '';
				const text = nro + ' ' + concepto + ' ' + monto + ' ' + fecha;
				card.closest('.col-md-3').style.display = text.includes(term) ? '' : 'none';
			});
		}
		if (searchInput) {
			searchInput.addEventListener('input', filterCards);
		}
	});
	</script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		@include('partials.navbar')
		@include('partials.sidebar')
		<div class="content-wrapper">
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2 align-items-end">
						<div class="col-md-6 col-12 d-flex align-items-center">
							<h1 class="m-0">Listado de Recibos de Venta</h1>
						</div>
						<div class="col-md-6 col-12">
							<form onsubmit="return false;" class="float-md-right w-100" autocomplete="off">
								<div class="input-group">
									<input type="text" id="search" class="form-control" placeholder="Buscar recibo" aria-label="Buscar recibo">
									<div class="input-group-append">
										<span class="input-group-text"><i class="fas fa-search"></i></span>
									</div>
									<div class="titulo-box">
										<a href="{{ route('factura_venta.index', ['presupuesto' => $presupuesto, 'obra' => $obra]) }}" class="btn btn-light" title="Volver al listado"><i class="fas fa-arrow-left mr-2"></i></a>
									</div>
								</div>
							</form>
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
					<div class="row" id="cards-container">
						<div class="col-md-3 mb-4">
							<a href="{{ route('recibo_venta.create', [
								'presupuesto' => $presupuesto?->id ?? null,
								'obra' => $obra?->id ?? null,
								'factura' => $factura?->id ?? null
							]) }}" class="card add-card" id="agregar-recibo-card" style="text-decoration:none; color:inherit;">
								<div style="display:flex; align-items:center; justify-content:center; background:linear-gradient(180deg,#f0fff4,#e6ffed); height:440px;">
									<div style="text-align:center;">
										<i class="fas fa-plus-circle" style="font-size:48px; color:#2f8f4a;"></i>
										<div style="margin-top:8px; font-weight:600; color:#2f8f4a;">Agregar Recibo</div>
									</div>
								</div>
								<div class="recibo-info">
									<h5 style="color:#2f8f4a; margin-bottom:8px;">Nuevo recibo</h5>
									<div class="d-flex justify-content-between align-items-center">
										<div class="recibo-meta">Crear</div>
										<div class="text-right">
											<div class="h5 mb-0">&nbsp;</div>
											<small class="text-muted">&nbsp;</small>
										</div>
									</div>
								</div>
							</a>
						</div>
						@foreach ($recibos->reverse() as $recibo)
						<div class="col-md-3 mb-4">
							<a href="{{ route('recibo_venta.edit', $recibo->id) }}" style="text-decoration:none; color:inherit;">
								<div class="card recibo-card" style="height:440px; display:flex; flex-direction:column; justify-content:center; align-items:center;">
									<div class="recibo-info w-100">
										<h5>{{ $recibo->nro_recibo }}</h5>
										<div class="d-flex justify-content-between align-items-center">
											<div class="recibo-meta">{{ $recibo->concepto }}</div>
											<div class="text-right">
												<div class="h5 mb-0">{{ number_format($recibo->monto, 0, '', '.') }}</div>
												<small class="text-muted">{{ \Carbon\Carbon::parse($recibo->fecha_emision)->format('d/m/Y') }}</small>
											</div>
										</div>
										<div class="mt-2">
											<span class="badge badge-info">Factura: {{ $recibo->facturaVenta?->nro_factura ?? '-' }}</span>
										</div>
										<div class="mt-2">
											<small class="text-muted">Obra: {{ $recibo->obra?->nombre ?? '' }}</small>
										</div>
									</div>
								</div>
							</a>
						</div>
						@endforeach
					</div>
				</div>
			</section>
		</div>
		@include('partials.footer')
	</div>
</body>
</html>
