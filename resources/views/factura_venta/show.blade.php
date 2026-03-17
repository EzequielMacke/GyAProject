<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturación de la Obra</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #f0f3f7;
            --bg2:      #e4e9f0;
            --surface:  #f8fafc;
            --surface2: #edf1f6;
            --border:   #d8e0ea;
            --border2:  #c4cfdc;
            --text:     #1e2835;
            --text2:    #445060;
            --muted:    #8496aa;
            --accent:   #2a6fdb;
            --accent-s: #e8f0fc;
            --accent-b: #1f5bbf;
            --green:    #1e9166;
            --green-s:  #e5f6f0;
            --green-b:  #a8dcc9;
            --orange:   #c47c10;
            --orange-s: #fef3e2;
            --red:      #d94040;
            --red-s:    #fdeaea;
            --slate:    #4e6070;
            --slate-s:  #edf1f4;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .content-wrapper { background: var(--bg) !important; }

        /* ══════════════════════════════
           PAGE HEADER
        ══════════════════════════════ */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .ph-crumb {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 0.5rem;
        }

        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }

        .ph-title {
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.4px;
            line-height: 1.1;
            word-break: break-word;
        }

        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }

        .ph-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* ── Buttons ── */
        .btn {
            height: 38px;
            padding: 0 1rem;
            border-radius: 0.55rem;
            display: inline-flex;
            align-items: center;
            gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem;
            font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface);
            color: var(--text2);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.14s;
            white-space: nowrap;
        }

        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        /* ── Search ── */
        .search-wrap { position: relative; }

        .search-wrap i {
            position: absolute;
            left: 0.78rem; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 0.72rem;
            pointer-events: none;
        }

        .search-bar {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.83rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            padding: 0.5rem 0.9rem 0.5rem 2.1rem;
            color: var(--text);
            width: 240px;
            outline: none;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
            height: 38px;
        }

        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus {
            border-color: var(--accent);
            width: 290px;
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }

        /* ══════════════════════════════
           SUMMARY STRIP
        ══════════════════════════════ */
        .summary-strip {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.75rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0;
            overflow: hidden;
        }

        .summary-item {
            flex: 1 1 160px;
            padding: 0.6rem 1.2rem;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .summary-item:last-child { border-right: none; }

        .summary-label {
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--muted);
        }

        .summary-val {
            font-family: 'DM Mono', monospace;
            font-size: 1rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .summary-val.green  { color: var(--green); }
        .summary-val.blue   { color: var(--accent); }
        .summary-val.teal   { color: #1e9166; }
        .summary-val.orange { color: var(--orange); }
        .summary-val.red    { color: var(--red); }

        /* ══════════════════════════════
           CARDS GRID
        ══════════════════════════════ */
        #presupuestos-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1rem;
        }

        /* ── Presupuesto card ── */
        .presup-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: var(--text);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
            animation: cardIn 0.25s ease both;
        }

        .presup-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.09);
            border-color: var(--border2);
            color: var(--text);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        /* Card header */
        .card-head {
            padding: 1rem 1.15rem 0.8rem;
            border-bottom: 1px solid var(--border);
            background: var(--bg2);
        }

        .card-head-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.5rem;
            margin-bottom: 0.4rem;
        }

        .card-clave {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
            word-break: break-word;
        }

        .card-fecha {
            font-size: 0.72rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .card-fecha i { font-size: 0.6rem; }

        .card-tipo {
            font-size: 0.75rem;
            color: var(--text2);
            font-weight: 500;
        }

        .card-ot {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.72rem;
            font-weight: 600;
            margin-top: 0.35rem;
            padding: 0.18rem 0.55rem;
            border-radius: 99px;
            background: var(--orange-s);
            color: var(--orange);
            border: 1px solid #f5dba8;
        }

        .card-ot.pending {
            background: var(--slate-s);
            color: var(--muted);
            border-color: var(--border);
        }

        /* Card body */
        .card-body {
            padding: 0.9rem 1.15rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            flex: 1;
        }

        /* Mini summary grid */
        .mini-summary {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0.4rem;
        }

        .mini-item {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 0.45rem;
            padding: 0.45rem 0.5rem;
            text-align: center;
        }

        .mini-label {
            font-size: 0.62rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--muted);
            margin-bottom: 0.15rem;
        }

        .mini-val {
            font-family: 'DM Mono', monospace;
            font-size: 0.78rem;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mini-val.green  { color: var(--green); }
        .mini-val.blue   { color: var(--accent); }
        .mini-val.teal   { color: #1e9166; }
        .mini-val.orange { color: var(--orange); }
        .mini-val.red    { color: var(--red); }

        /* Counts */
        .card-counts {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .count-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.2rem 0.55rem;
            border-radius: 99px;
            border: 1px solid var(--border);
            background: var(--surface2);
            color: var(--text2);
        }

        .count-badge i { font-size: 0.62rem; color: var(--muted); }

        /* Progress bars */
        .progress-section { display: flex; flex-direction: column; gap: 0.5rem; }

        .prog-row { display: flex; flex-direction: column; gap: 0.25rem; }

        .prog-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.72rem;
        }

        .prog-label { color: var(--muted); font-weight: 600; }
        .prog-val   { font-family: 'DM Mono', monospace; color: var(--text2); font-size: 0.72rem; }

        .prog-track {
            height: 6px;
            background: var(--bg2);
            border-radius: 99px;
            overflow: hidden;
        }

        .prog-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.6s ease;
        }

        .prog-fill.blue  { background: var(--accent); }
        .prog-fill.teal  { background: var(--green); }

        /* PDF preview */
        .card-pdf {
            height: 140px;
            background: var(--bg2);
            overflow: hidden;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }

        .card-pdf iframe {
            width: 100%; height: 100%;
            border: none;
            pointer-events: none;
        }

        /* Observacion footer */
        .card-obs {
            padding: 0.55rem 1.1rem;
            background: var(--surface2);
            border-top: 1px solid var(--border);
            font-size: 0.75rem;
            color: var(--muted);
            font-style: italic;
            display: flex;
            align-items: flex-start;
            gap: 0.4rem;
        }

        .card-obs i { margin-top: 0.1rem; font-size: 0.65rem; flex-shrink: 0; }

        /* empty / no results */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 5rem 2rem;
            color: var(--muted);
        }

        .empty-state i { font-size: 2rem; display: block; margin-bottom: 0.75rem; opacity: 0.35; }
        .empty-state p { font-size: 0.88rem; }

        .no-results {
            display: none;
            grid-column: 1 / -1;
            text-align: center;
            color: var(--muted);
            padding: 4rem 2rem;
            font-size: 0.85rem;
        }

        .no-results i { display: block; font-size: 1.5rem; margin-bottom: 0.5rem; opacity: 0.3; }

        @media (max-width: 576px) {
            .summary-item { border-right: none; border-bottom: 1px solid var(--border); }
            .summary-item:last-child { border-bottom: none; }
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

                <div class="ph">
                    <div>
                        <div class="ph-crumb">
                            <i class="fas fa-hard-hat"></i>
                            <a href="{{ route('obras.index') }}">Obras</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('obras.show', $obra) }}">{{ $obra->nombre ?? '-' }}</a>
                            <i class="fas fa-chevron-right"></i>
                            Facturación
                        </div>
                        <h1 class="ph-title">Facturación — <em>{{ $obra->nombre ?? '-' }}</em></h1>
                        <p class="ph-sub">Seguimiento de presupuestos, facturas y cobros</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar presupuesto…" autocomplete="off">
                        </div>
                        <a href="{{ route('obras.show', $obra) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                {{-- Summary strip --}}
                @php
                    $presupuestadoTotal = $presupuestos->sum('monto_total');
                    $facturadoTotal = $presupuestos->reduce(fn($c, $p) => $c + $p->facturasVenta->sum('monto'), 0);
                    $cobradoTotal   = $presupuestos->reduce(fn($c, $p) =>
                        $c + $p->facturasVenta->reduce(fn($c2, $f) => $c2 + $f->recibosVenta->sum('monto'), 0), 0);
                    $saldoPorFacturar = $presupuestadoTotal - $facturadoTotal;
                    $saldoPorCobrar   = $facturadoTotal - $cobradoTotal;
                @endphp

                <div class="summary-strip">
                    <div class="summary-item">
                        <span class="summary-label">Presupuestado</span>
                        <span class="summary-val green">Gs. {{ number_format($presupuestadoTotal, 0, '', '.') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Facturado</span>
                        <span class="summary-val blue">Gs. {{ number_format($facturadoTotal, 0, '', '.') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Cobrado</span>
                        <span class="summary-val teal">Gs. {{ number_format($cobradoTotal, 0, '', '.') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Saldo por facturar</span>
                        <span class="summary-val orange">Gs. {{ number_format($saldoPorFacturar, 0, '', '.') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Saldo por cobrar</span>
                        <span class="summary-val red">Gs. {{ number_format($saldoPorCobrar, 0, '', '.') }}</span>
                    </div>
                </div>

                {{-- Cards grid --}}
                <div id="presupuestos-list">

                    @forelse($presupuestos->reverse() as $presupuesto)
                    @php
                        $totalFacturado  = $presupuesto->facturasVenta->sum('monto') ?? 0;
                        $montoTotal      = $presupuesto->monto_total ?? 0;
                        $porcentaje      = $montoTotal > 0 ? round(($totalFacturado / $montoTotal) * 100, 1) : 0;
                        $cantidadRecibos = $presupuesto->facturasVenta->reduce(fn($c, $f) => $c + $f->recibosVenta->count(), 0);
                        $montoRecibido   = $presupuesto->facturasVenta->reduce(fn($c, $f) => $c + $f->recibosVenta->sum('monto'), 0);
                        $porcRecibido    = $totalFacturado > 0 ? round(($montoRecibido / $totalFacturado) * 100, 1) : 0;
                        $searchData      = strtolower(
                            $presupuesto->clave . ' ' .
                            ($presupuesto->fecha_carga ? \Carbon\Carbon::parse($presupuesto->fecha_carga)->format('d/m/Y') : '') . ' ' .
                            (config('constantes.tipo_trabajo')[$presupuesto->tipo_trabajo] ?? '') . ' ' .
                            number_format($montoTotal, 0, '', '.') . ' ' .
                            ($presupuesto->observacion ?? '') . ' ' .
                            ($presupuesto->orden_trabajo ?? '')
                        );
                    @endphp

                    <a href="{{ route('factura_venta.index', ['presupuesto' => $presupuesto->id, 'obra' => $obra->id]) }}"
                       class="presup-card"
                       style="animation-delay:{{ $loop->index * 0.04 }}s"
                       data-search="{{ $searchData }}">

                        {{-- Card header --}}
                        <div class="card-head">
                            <div class="card-head-top">
                                <span class="card-clave">{{ $presupuesto->clave }}</span>
                                <span class="card-fecha">
                                    <i class="far fa-calendar"></i>
                                    {{ $presupuesto->fecha_carga ? \Carbon\Carbon::parse($presupuesto->fecha_carga)->format('d/m/Y') : '—' }}
                                </span>
                            </div>
                            <div class="card-tipo">{{ config('constantes.tipo_trabajo')[$presupuesto->tipo_trabajo] ?? '—' }}</div>
                            <div style="margin-top:0.4rem;">
                                @if(!empty($presupuesto->orden_trabajo))
                                    <span class="card-ot"><i class="fas fa-file-alt"></i> OT: {{ $presupuesto->orden_trabajo }}</span>
                                @else
                                    <span class="card-ot pending"><i class="fas fa-clock"></i> Orden pendiente</span>
                                @endif
                            </div>
                        </div>

                        {{-- Card body --}}
                        <div class="card-body">

                            {{-- Mini summary --}}
                            <div class="mini-summary">
                                <div class="mini-item">
                                    <div class="mini-label">Presup.</div>
                                    <div class="mini-val green">{{ number_format($montoTotal, 0, '', '.') }}</div>
                                </div>
                                <div class="mini-item">
                                    <div class="mini-label">Facturado</div>
                                    <div class="mini-val blue">{{ number_format($totalFacturado, 0, '', '.') }}</div>
                                </div>
                                <div class="mini-item">
                                    <div class="mini-label">Cobrado</div>
                                    <div class="mini-val teal">{{ number_format($montoRecibido, 0, '', '.') }}</div>
                                </div>
                                <div class="mini-item">
                                    <div class="mini-label">× Facturar</div>
                                    <div class="mini-val orange">{{ number_format($montoTotal - $totalFacturado, 0, '', '.') }}</div>
                                </div>
                                <div class="mini-item">
                                    <div class="mini-label">× Cobrar</div>
                                    <div class="mini-val red">{{ number_format($totalFacturado - $montoRecibido, 0, '', '.') }}</div>
                                </div>
                                <div class="mini-item">
                                    <div class="mini-label">Fact. %</div>
                                    <div class="mini-val blue">{{ $porcentaje }}%</div>
                                </div>
                            </div>

                            {{-- Counts --}}
                            <div class="card-counts">
                                <span class="count-badge">
                                    <i class="fas fa-file-invoice"></i>
                                    {{ $presupuesto->facturasVenta->count() }} factura{{ $presupuesto->facturasVenta->count() == 1 ? '' : 's' }}
                                </span>
                                <span class="count-badge">
                                    <i class="fas fa-receipt"></i>
                                    {{ $cantidadRecibos }} recibo{{ $cantidadRecibos == 1 ? '' : 's' }}
                                </span>
                            </div>

                            {{-- Progress bars --}}
                            <div class="progress-section">
                                <div class="prog-row">
                                    <div class="prog-label-row">
                                        <span class="prog-label">Facturado</span>
                                        <span class="prog-val">{{ $porcentaje }}%</span>
                                    </div>
                                    <div class="prog-track">
                                        <div class="prog-fill blue" style="width:{{ min($porcentaje, 100) }}%"></div>
                                    </div>
                                </div>
                                <div class="prog-row">
                                    <div class="prog-label-row">
                                        <span class="prog-label">Cobrado</span>
                                        <span class="prog-val">{{ $porcRecibido }}%</span>
                                    </div>
                                    <div class="prog-track">
                                        <div class="prog-fill teal" style="width:{{ min($porcRecibido, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- PDF preview --}}
                        @if($presupuesto->presupuesto)
                        <div class="card-pdf">
                            <iframe src="{{ Storage::url('presupuestos/' . $presupuesto->presupuesto) }}" loading="lazy"></iframe>
                        </div>
                        @endif

                        {{-- Observacion --}}
                        @if($presupuesto->observacion)
                        <div class="card-obs">
                            <i class="fas fa-comment-alt"></i>
                            {{ $presupuesto->observacion }}
                        </div>
                        @endif

                    </a>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <p>No hay presupuestos disponibles para esta obra.</p>
                    </div>
                    @endforelse

                    <div class="no-results" id="no-results">
                        <i class="fas fa-search"></i>
                        Sin resultados para tu búsqueda.
                    </div>

                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search');
    const cards = document.querySelectorAll('#presupuestos-list .presup-card');
    const noRes = document.getElementById('no-results');

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let vis = 0;

        cards.forEach(card => {
            const show = (card.dataset.search || '').includes(q);
            card.style.display = show ? '' : 'none';
            if (show) vis++;
        });

        noRes.style.display = (!vis && cards.length && q) ? 'block' : 'none';
    });
});
</script>
</body>
</html>