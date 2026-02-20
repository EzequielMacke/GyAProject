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

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchPresupuesto');
            const cards = Array.from(document.querySelectorAll('.presupuesto-card-item'));
            searchInput?.addEventListener('input', function() {
                const term = (searchInput.value || '').trim().toLowerCase();
                cards.forEach(card => {
                    const searchData = card.getAttribute('data-search').toLowerCase();
                    card.style.display = searchData.includes(term) ? '' : 'none';
                });
            });
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
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2 align-items-end">
                            <div class="col-md-6 col-12 d-flex align-items-center">
                                <h1 class="m-0">Presupuestos de la Obra</h1>
                            </div>
                            <div class="col-md-6 col-12">
                                <form onsubmit="return false;" class="float-md-right w-100" autocomplete="off">
                                    <div class="input-group">
                                        <input type="text" id="searchPresupuesto" class="form-control" placeholder="Buscar presupuesto por clave, tipo o monto..." aria-label="Buscar presupuesto">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        </div>
                                        <div class="titulo-box">
                                            <a href="{{ route('obras.show', $obra) }}" class="btn btn-light" title="Volver al listado"><i class="fas fa-arrow-left mr-2"></i></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Cuadro de resumen general --}}
                @php
                    $presupuestadoTotal = $presupuestos->sum('monto_total');
                    $facturadoTotal = $presupuestos->reduce(function($carry, $presupuesto) {
                        return $carry + $presupuesto->facturasVenta->sum('monto');
                    }, 0);
                    $cobradoTotal = $presupuestos->reduce(function($carry, $presupuesto) {
                        return $carry + $presupuesto->facturasVenta->reduce(function($carry2, $factura) {
                            return $carry2 + $factura->recibosVenta->sum('monto');
                        }, 0);
                    }, 0);
                    $saldoPorFacturar = $presupuestadoTotal - $facturadoTotal;
                    $saldoPorCobrar = $facturadoTotal - $cobradoTotal;
                @endphp
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card card-custom p-4" style="border-left: 8px solid #0d6efd;">
                            <div class="row text-center">
                                <div class="col-md-2 col-6 mb-2">
                                    <div style="font-size:1.1rem; color:#495057;">Presupuestado</div>
                                    <div style="font-size:1.3rem; font-weight:700; color:#1e7e34;">Gs. {{ number_format($presupuestadoTotal, 0, '', '.') }}</div>
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
                    <div class="row" id="presupuestos-list">
                        @forelse($presupuestos->reverse() as $presupuesto)
                            @php
                                $totalFacturado = $presupuesto->facturasVenta->sum('monto') ?? 0;
                                $montoTotal = $presupuesto->monto_total ?? 0;
                                $porcentaje = $montoTotal > 0 ? round(($totalFacturado / $montoTotal) * 100, 2) : 0;
                                // Calcular cantidad total de recibos asociados a todas las facturas de este presupuesto
                                $cantidadRecibos = $presupuesto->facturasVenta->reduce(function($carry, $factura) {
                                    return $carry + $factura->recibosVenta->count();
                                }, 0);
                                // Calcular monto total recibido (sumando todos los recibos de todas las facturas)
                                $montoRecibido = $presupuesto->facturasVenta->reduce(function($carry, $factura) {
                                    return $carry + $factura->recibosVenta->sum('monto');
                                }, 0);
                                $porcentajeRecibido = $totalFacturado > 0 ? round(($montoRecibido / $totalFacturado) * 100, 2) : 0;
                            @endphp
                            <div class="col-md-4 mb-4 presupuesto-card-item" 
                                data-search="{{
                                    $presupuesto->clave . ' ' .
                                    ($presupuesto->fecha_carga ? \Carbon\Carbon::parse($presupuesto->fecha_carga)->format('d/m/Y') : '') . ' ' .
                                    (config('constantes.tipo_trabajo')[$presupuesto->tipo_trabajo] ?? '') . ' ' .
                                    number_format($montoTotal, 0, '', '.') . ' ' .
                                    ($presupuesto->observacion ?? '') . ' ' .
                                    number_format($totalFacturado, 0, '', '.') . ' ' .
                                    $porcentaje . ' ' .
                                    $presupuesto->facturasVenta->count()
                                }}">
                                <a href="{{ route('factura_venta.index', ['presupuesto' => $presupuesto->id, 'obra' => $obra->id]) }}" style="text-decoration:none; color:inherit;">
                                    <div class="card h-100 d-flex flex-column justify-content-between" style="border-radius:22px; min-height:540px; box-shadow:0 2px 16px rgba(0,0,0,0.11); transition:box-shadow .18s,transform .18s;">
                                        <div class="card-body d-flex flex-column p-4">
                                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                                <span class="fw-bold" style="font-size:1.5rem;">{{ $presupuesto->clave }}</span>
                                                <span class="text-muted" style="font-size:1.1rem;">{{ $presupuesto->fecha_carga ? \Carbon\Carbon::parse($presupuesto->fecha_carga)->format('d/m/Y') : '' }}</span>
                                            </div>
                                            <div class="mb-2 text-center" style="font-size:1.15rem; font-weight:500;">
                                                {{ config('constantes.tipo_trabajo')[$presupuesto->tipo_trabajo] ?? '' }}
                                            </div>
                                            <div class="mb-2 text-end" style="font-size:1.25rem; color:#1e7e34; font-weight:600;">Gs. {{ number_format($montoTotal, 0, '', '.') }}</div>
                                            <div class="mb-2 text-center" style="font-size:1.05rem; color:#0d6efd;">
                                                {{ $presupuesto->facturasVenta->count() }} factura{{ $presupuesto->facturasVenta->count() == 1 ? '' : 's' }}
                                                <span style="margin: 0 8px;">|</span>
                                                {{ $cantidadRecibos }} recibo{{ $cantidadRecibos == 1 ? '' : 's' }}
                                            </div>
                                            <div class="mb-2 text-center" style="font-size:1.05rem; color:#e67e22;">
                                                @if(!empty($presupuesto->orden_trabajo))
                                                    Orden de Trabajo: {{ $presupuesto->orden_trabajo }}
                                                @else
                                                    Orden Pendiente
                                                @endif
                                            </div>
                                            {{-- Cuadro resumen individual --}}
                                            <div class="mb-3">
                                                <div class="card p-2" style="background:#f8f9fa; border-radius:12px; border-left:4px solid #0d6efd;">
                                                    <div class="row text-center">
                                                        <div class="col-6 col-md-4 mb-1">
                                                            <div style="font-size:0.98rem; color:#495057;">Presupuestado</div>
                                                            <div style="font-size:1.08rem; font-weight:600; color:#1e7e34;">Gs. {{ number_format($montoTotal, 0, '', '.') }}</div>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-1">
                                                            <div style="font-size:0.98rem; color:#495057;">Facturado</div>
                                                            <div style="font-size:1.08rem; font-weight:600; color:#0d6efd;">Gs. {{ number_format($totalFacturado, 0, '', '.') }}</div>
                                                        </div>
                                                        <div class="col-6 col-md-4 mb-1">
                                                            <div style="font-size:0.98rem; color:#495057;">Cobrado</div>
                                                            <div style="font-size:1.08rem; font-weight:600; color:#28a745;">Gs. {{ number_format($montoRecibido, 0, '', '.') }}</div>
                                                        </div>
                                                        <div class="col-6 col-md-6 mb-1">
                                                            <div style="font-size:0.98rem; color:#495057;">Saldo por facturar</div>
                                                            <div style="font-size:1.08rem; font-weight:600; color:#e67e22;">Gs. {{ number_format($montoTotal - $totalFacturado, 0, '', '.') }}</div>
                                                        </div>
                                                        <div class="col-6 col-md-6 mb-1">
                                                            <div style="font-size:0.98rem; color:#495057;">Saldo por cobrar</div>
                                                            <div style="font-size:1.08rem; font-weight:600; color:#dc3545;">Gs. {{ number_format($totalFacturado - $montoRecibido, 0, '', '.') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($presupuesto->presupuesto)
                                                <div class="mb-3 text-center">
                                                    <iframe src="{{ Storage::url('presupuestos/' . $presupuesto->presupuesto) }}" style="width:100%; height:180px; border-radius:10px; border:1px solid #e0e0e0;"></iframe>
                                                </div>
                                            @endif
                                            @if($presupuesto->observacion)
                                                <div class="mb-2 text-center" style="font-size:1.05rem; color:#6c757d;">{{ $presupuesto->observacion }}</div>
                                            @endif
                                            <div class="mt-auto">
                                                <div class="mb-2">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span style="font-size:1.05rem;">Facturado</span>
                                                        <span style="font-size:1.15rem; font-weight:600; color:#0d6efd;">Gs. {{ number_format($totalFacturado, 0, '', '.') }} ({{ $porcentaje }}%)</span>
                                                    </div>
                                                    <div class="progress" style="height: 20px; border-radius: 10px;">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $porcentaje }}%; font-size:1.05rem; border-radius:10px;" aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="100">
                                                            {{ $porcentaje }}%
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-1" style="font-size:1.05rem;">
                                                        <span>Gs. {{ number_format($montoTotal, 0, '', '.') }}</span>
                                                        <span>Gs. {{ number_format($totalFacturado, 0, '', '.') }}</span>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span style="font-size:1.05rem;">Cobrado</span>
                                                        <span style="font-size:1.15rem; font-weight:600; color:#28a745;">Gs. {{ number_format($montoRecibido, 0, '', '.') }} ({{ $porcentajeRecibido }}%)</span>
                                                    </div>
                                                    <div class="progress" style="height: 20px; border-radius: 10px;">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $porcentajeRecibido }}%; font-size:1.05rem; border-radius:10px;" aria-valuenow="{{ $porcentajeRecibido }}" aria-valuemin="0" aria-valuemax="100">
                                                            {{ $porcentajeRecibido }}%
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-1" style="font-size:1.05rem;">
                                                        <span>Gs. {{ number_format($totalFacturado, 0, '', '.') }}</span>
                                                        <span>Gs. {{ number_format($montoRecibido, 0, '', '.') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">No hay presupuestos disponibles para esta obra.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
                
            </div>
        </div>
        @include('partials.footer')
    </div>
</body>
</html>