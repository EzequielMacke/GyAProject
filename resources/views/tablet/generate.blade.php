<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Códigos QR de Tabletas</title>
	<style>
		.qr-list { display: flex; flex-wrap: wrap; gap: 2rem; justify-content: flex-start; }
		.qr-item { border: 1px solid #e3e3e3; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; background: #fff; text-align: center; width: 220px; }
		.qr-label { font-weight: bold; margin-top: 1rem; font-size: 1.1rem; }
		@media print {
			body { background: #fff; }
			.no-print { display: none; }
			.qr-list { gap: 0.5rem; }
			.qr-item { page-break-inside: avoid; }
		}
	</style>
</head>
<body>
	<div class="no-print" style="margin-bottom: 1.5rem;">
		<button onclick="window.print()" style="padding: 0.5rem 1.5rem; font-size: 1.1rem; border-radius: 8px; background: #2f8f4a; color: #fff; border: none; cursor: pointer;">Imprimir</button>
	</div>
	<div class="qr-list">
		@foreach($qrs as $tableta)
			<div class="qr-item">
				<canvas id="qr-{{ $tableta->id }}"></canvas>
				<div class="qr-label">{{ $tableta->clave ?? $tableta->id }}</div>
			</div>
		@endforeach
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			@foreach($qrs as $tableta)
				var qr{{ $tableta->id }} = new QRious({
					element: document.getElementById('qr-{{ $tableta->id }}'),
					value: "{{ $tableta->codigo_qr }}",
					size: 160,
					background: 'white',
					foreground: '#222'
				});
			@endforeach
		});
	</script>
</body>
</html>
