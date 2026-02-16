<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reporte de Uso de Tabletas</title>
	@include('partials.head')
	<style>
		.tablet-card {
			cursor: pointer;
			border-radius:16px;
			overflow:hidden;
			transition: transform .18s, box-shadow .18s;
			background: #f8f9fa;
			box-shadow: 0 4px 24px 0 #2f8f4a33;
			min-height: 220px;
		}
		.tablet-card:hover {
			transform: translateY(-8px) scale(1.03);
			box-shadow: 0 16px 32px 0 #2f8f4a44;
		}
		.tablet-info {
			padding: 18px 14px 14px 14px;
			display: flex;
			flex-direction: column;
			gap: 8px;
		}
		.tablet-info h5 {
			margin-bottom: 8px;
			font-size:1.15rem;
			color: #222;
			font-weight: 700;
			text-align: left;
		}
		.tablet-meta {
			font-size:1.05rem;
			border-radius:6px;
			padding: 6px 10px;
			margin-bottom: 2px;
			display: flex;
			align-items: center;
			gap: 6px;
			color: #222;
			background: #fff;
		}
		.icon-meta {
			margin-right: 8px;
			font-size: 1.2em;
			opacity: 0.85;
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
					<div class="row mb-2 align-items-end">
						<div class="col-12">
							<div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3" style="background:#f8f9fa; border-radius:12px; padding:18px 12px;">
								<div class="d-flex align-items-center gap-2">
									<h1 class="m-0 me-3">Reporte de Uso de Tabletas</h1>
									<form method="GET" class="d-inline-block" autocomplete="off">
										<div class="input-group">
											<select name="tableta_id" class="form-control">
												<option value="">Todas las tabletas</option>
												@foreach($tabletas as $tableta)
													<option value="{{ $tableta->id }}" {{ request('tableta_id') == $tableta->id ? 'selected' : '' }}>
														{{ $tableta->clave }} - {{ $tableta->nombre }}
													</option>
												@endforeach
											</select>
											<div class="input-group-append">
												<button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Filtrar</button>
											</div>
										</div>
									</form>
									<a href="{{ route('tabletas.index') }}" class="btn btn-light ms-2" title="Volver al listado"><i class="fas fa-arrow-left mr-2"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<section class="content">
				<div class="container-fluid">
					<div class="row mt-2">
						@if ($usos->isEmpty())
							<div class="alert alert-info text-center mt-4">No hay registros de uso de tabletas.</div>
						@else
							<div class="table-responsive">
								<table id="tabla-reporte" style="width:100%; border-collapse:collapse; font-size:0.98rem; background:#fff;">
									<thead>
										<tr style="border-bottom:1px solid #e0e0e0; background:#fafbfc;">
											<th style="padding:8px 6px; font-weight:600; text-align:center; cursor:pointer;" onclick="sortTable(0)">Clave <span class="sort-arrow">&#8597;</span></th>
											<th style="padding:8px 6px; font-weight:600; text-align:center; cursor:pointer;" onclick="sortTable(1)">Tableta <span class="sort-arrow">&#8597;</span></th>
											<th style="padding:8px 6px; font-weight:600; text-align:center; cursor:pointer;" onclick="sortTable(2)">Usuario <span class="sort-arrow">&#8597;</span></th>
											<th style="padding:8px 6px; font-weight:600; text-align:center; cursor:pointer;" onclick="sortTable(3)">Retiro <span class="sort-arrow">&#8597;</span></th>
											<th style="padding:8px 6px; font-weight:600; text-align:center; cursor:pointer;" onclick="sortTable(4)">Devolución <span class="sort-arrow">&#8597;</span></th>
										</tr>
									</thead>
									<tbody>
										@foreach ($usos as $uso)
											<tr style="border-bottom:1px solid #f0f0f0; background:{{ $loop->even ? '#f7fafc' : '#fff' }};">
												<td style="padding:7px 6px; text-align:center;">{{ $uso->tableta->clave ?? '-' }}</td>
												<td style="padding:7px 6px; text-align:center;">{{ $uso->tableta->nombre ?? '-' }}</td>
												<td style="padding:7px 6px; text-align:center;">{{ $uso->usuario->nombre ?? '-' }}</td>
												<td style="padding:7px 6px; text-align:center;">{{ $uso->fecha_retiro ? \Carbon\Carbon::parse($uso->fecha_retiro)->format('d/m/Y') : '-' }}</td>
												<td style="padding:7px 6px; text-align:center;">{{ $uso->fecha_devolucion ? \Carbon\Carbon::parse($uso->fecha_devolucion)->format('d/m/Y') : '-' }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<script>
							// Ordenar tabla por columna
							let sortDirection = {};
							function sortTable(n) {
								const table = document.getElementById('tabla-reporte');
								let switching = true, dir = sortDirection[n] === 'asc' ? 'desc' : 'asc', rows, i, x, y, shouldSwitch, switchcount = 0;
								sortDirection[n] = dir;
								while (switching) {
									switching = false;
									rows = table.rows;
									for (i = 1; i < (rows.length - 1); i++) {
										shouldSwitch = false;
										x = rows[i].getElementsByTagName('TD')[n];
										y = rows[i + 1].getElementsByTagName('TD')[n];
										let xContent = x.textContent.trim().toLowerCase();
										let yContent = y.textContent.trim().toLowerCase();
										if (!isNaN(Date.parse(xContent)) && !isNaN(Date.parse(yContent))) {
											xContent = Date.parse(xContent);
											yContent = Date.parse(yContent);
										}
										if (dir === 'asc') {
											if (xContent > yContent) {
												shouldSwitch = true;
												break;
											}
										} else if (dir === 'desc') {
											if (xContent < yContent) {
												shouldSwitch = true;
												break;
											}
										}
									}
									if (shouldSwitch) {
										rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
										switching = true;
										switchcount++;
									}
								}
							}
							</script>
						@endif
					</div>
				</div>
			</section>
		</div>
		@include('partials.footer')
	</div>
</body>
</html>
