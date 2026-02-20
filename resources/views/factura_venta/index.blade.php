<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Facturas de Venta</title>
    @include('partials.head')
    <style>
        .factura-card { cursor: pointer; border-radius:10px; overflow:hidden; transition: transform .18s, box-shadow .18s; }
        .factura-card:hover { transform: translateY(-6px); box-shadow: 0 10px 25px rgba(16,24,40,0.08); }
        .factura-info { padding: 20px; }
        .factura-info h5 { margin-bottom: 10px; font-size:1.25rem; }
        .factura-meta { color:#6b7280; font-size:1rem; }
        .add-card { border: 2px dashed #2f8f4a; background: linear-gradient(180deg,#f0fff4,#e6ffed); transition: box-shadow .18s, transform .18s, border-color .18s; }
        .add-card:hover, .add-card:focus { box-shadow: 0 8px 32px rgba(47,143,74,0.13); border-color: #1e6b36; transform: scale(1.035) translateY(-4px); }
        @media (max-width: 992px) {
            .budget-card .preview { height: 360px; }
        }
        @media (max-width: 768px) {
            .budget-card .preview { height: 260px; }
            .budget-info h5 { font-size:1.05rem; }
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const cards = Array.from(document.querySelectorAll('.factura-card'));
        function filterCards() {
            const term = (searchInput.value || '').trim().toLowerCase();
            cards.forEach(card => {
                // Buscar solo en los campos visibles: nro_factura, concepto, monto y fecha
                const nro = card.querySelector('.factura-info h5')?.textContent?.toLowerCase() || '';
                const concepto = card.querySelector('.factura-meta')?.textContent?.toLowerCase() || '';
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
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_ing')->first()->id ?? null)->where('ver', 1)->isEmpty())
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
                    <div class="row mb-2 align-items-end">
                        <div class="col-md-6 col-12 d-flex align-items-center">
                            <h1 class="m-0">Listado de Facturas de Venta</h1>
                        </div>
                        <div class="col-md-6 col-12">
                            <form onsubmit="return false;" class="float-md-right w-100" autocomplete="off">
                                <div class="input-group">
                                    <input type="text" id="search" class="form-control" placeholder="Buscar factura" aria-label="Buscar factura">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <div class="titulo-box">
                                        <a href="{{ route('factura_venta.show', ['obraId' => $obra->id]) }}" class="btn btn-light" title="Volver al listado"><i class="fas fa-arrow-left mr-2"></i></a>
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

                    {{-- Resumen general de facturas --}}
                    @php
                        $montoPresupuesto = $presupuesto?->monto_total ?? 0;
                        $facturadoTotal = $facturas->sum('monto');
                        $cobradoTotal = $facturas->reduce(function($carry, $factura) {
                            return $carry + $factura->recibosVenta->sum('monto');
                        }, 0);
                        $saldoPorFacturar = $montoPresupuesto - $facturadoTotal;
                        $saldoPorCobrar = $facturadoTotal - $cobradoTotal;
                    @endphp
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card card-custom p-4" style="border-left: 8px solid #0d6efd;">
                                <div class="row text-center">
                                    <div class="col-md-2 col-6 mb-2">
                                        <div style="font-size:1.1rem; color:#495057;">Presupuesto</div>
                                        <div style="font-size:1.3rem; font-weight:700; color:#1e7e34;">Gs. {{ number_format($montoPresupuesto, 0, '', '.') }}</div>
                                    </div>
                                    <div class="col-md-2 col-6 mb-2">
                                        <div style="font-size:1.1rem; color:#495057;">Facturado</div>
                                        <div style="font-size:1.3rem; font-weight:700; color:#0d6efd;">Gs. {{ number_format($facturadoTotal, 0, '', '.') }}</div>
                                    </div>
                                    <div class="col-md-2 col-6 mb-2">
                                        <div style="font-size:1.1rem; color:#495057;">Cobrado</div>
                                        <div style="font-size:1.3rem; font-weight:700; color:#28a745;">Gs. {{ number_format($cobradoTotal, 0, '', '.') }}</div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <div style="font-size:1.1rem; color:#495057;">Saldo por facturar</div>
                                        <div style="font-size:1.3rem; font-weight:700; color:#e67e22;">Gs. {{ number_format($saldoPorFacturar, 0, '', '.') }}</div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <div style="font-size:1.1rem; color:#495057;">Saldo por cobrar</div>
                                        <div style="font-size:1.3rem; font-weight:700; color:#dc3545;">Gs. {{ number_format($saldoPorCobrar, 0, '', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="cards-container">
                        <div class="col-md-3 mb-4">
                            <a href="{{ route('factura_venta.create', ['presupuesto' => $presupuesto?->id ?? null, 'obra' => $obra?->id ?? null]) }}" class="card add-card" id="agregar-factura-card" style="text-decoration:none; color:inherit;">
                                <div style="display:flex; align-items:center; justify-content:center; background:linear-gradient(180deg,#f0fff4,#e6ffed); height:440px;">
                                    <div style="text-align:center;">
                                        <i class="fas fa-plus-circle" style="font-size:48px; color:#2f8f4a;"></i>
                                        <div style="margin-top:8px; font-weight:600; color:#2f8f4a;">Agregar Factura</div>
                                    </div>
                                </div>
                                <div class="factura-info">
                                    <h5 style="color:#2f8f4a; margin-bottom:8px;">Nueva factura</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="factura-meta">Crear</div>
                                        <div class="text-right">
                                            <div class="h5 mb-0">&nbsp;</div>
                                            <small class="text-muted">&nbsp;</small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @foreach ($facturas->reverse() as $factura)
                        @php
                            $presupuesto = $factura->presupuestoAprobado;
                            $montoPresupuesto = $presupuesto?->monto_total ?? 0;
                            $porcentajeFactura = $montoPresupuesto > 0 ? round(($factura->monto / $montoPresupuesto) * 100, 2) : 0;
                            $montoCobrado = $factura->recibosVenta->sum('monto');
                            $porcentajeCobrado = $factura->monto > 0 ? round(($montoCobrado / $factura->monto) * 100, 2) : 0;
                        @endphp
                        <div class="col-md-3 mb-4">
                            <a href="{{ route('factura_venta.edit', $factura->id) }}" style="text-decoration:none; color:inherit;">
                                <div class="card factura-card" style="height:440px; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                                    <div class="factura-info w-100">
                                        <h5>Nro Factura</h5>
                                        <h4>{{ $factura->nro_factura }}</h4>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="factura-meta">{{ $factura->concepto }}</div>
                                            <div class="text-right">
                                                <div class="h5 mb-0">{{ number_format($factura->monto, 0, '', '.') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                        
                                        {{-- Resumen individual --}}
                                        <div class="mb-2 mt-2">
                                            <div class="card p-2" style="background:#f8f9fa; border-radius:10px; border-left:4px solid #0d6efd;">
                                                <div class="row text-center">
                                                    <div class="col-6 mb-1">
                                                        <div style="font-size:0.98rem; color:#495057;">% Presupuesto</div>
                                                        <div style="font-size:1.08rem; font-weight:600; color:#0d6efd;">{{ $porcentajeFactura }}% (Gs. {{ number_format($factura->monto, 0, '', '.') }})</div>
                                                    </div>
                                                    <div class="col-6 mb-1">
                                                        <div style="font-size:0.98rem; color:#495057;">% Cobrado</div>
                                                        <div style="font-size:1.08rem; font-weight:600; color:#28a745;">{{ $porcentajeCobrado }}% (Gs. {{ number_format($montoCobrado, 0, '', '.') }})</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Barras de avance --}}
                                        <div class="mb-2">
                                            <div style="font-size:0.95rem; color:#495057;">Facturado</div>
                                            <div class="progress" style="height: 16px; border-radius: 8px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $porcentajeFactura }}%; font-size:0.95rem; border-radius:8px;" aria-valuenow="{{ $porcentajeFactura }}" aria-valuemin="0" aria-valuemax="100">
                                                    {{ $porcentajeFactura }}%
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div style="font-size:0.95rem; color:#495057;">Cobrado</div>
                                            <div class="progress" style="height: 16px; border-radius: 8px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $porcentajeCobrado }}%; font-size:0.95rem; border-radius:8px;" aria-valuenow="{{ $porcentajeCobrado }}" aria-valuemin="0" aria-valuemax="100">
                                                    {{ $porcentajeCobrado }}%
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 d-flex justify-content-between align-items-center">
                                            <a href="{{ route('recibo_venta.index', ['presupuesto' => $factura->presupuesto_aprobado_id, 'obra' => $factura->obra_id, 'factura' => $factura->id]) }}" class="btn btn-sm btn-success ms-2" title="Ver recibos de esta factura">
                                               + Agregar recibo
                                            </a>
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
    </div>
</body>
</html>