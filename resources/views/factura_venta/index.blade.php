<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturas de Venta</title>
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

        .btn-green {
            background: var(--green-s);
            border-color: var(--green-b);
            color: var(--green);
        }
        .btn-green:hover {
            background: var(--green);
            border-color: var(--green);
            color: #fff;
        }

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
            width: 220px;
            outline: none;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
            height: 38px;
        }

        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus {
            border-color: var(--accent);
            width: 270px;
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
            flex: 1 1 140px;
            padding: 0.6rem 1.1rem;
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
            font-size: 0.95rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .summary-val.green  { color: var(--green); }
        .summary-val.blue   { color: var(--accent); }
        .summary-val.teal   { color: var(--green); }
        .summary-val.orange { color: var(--orange); }
        .summary-val.red    { color: var(--red); }

        /* ══════════════════════════════
           CARDS GRID
        ══════════════════════════════ */
        #cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        /* ── Add card ── */
        .add-card {
            background: var(--green-s);
            border: 1.5px dashed var(--green-b);
            border-radius: 0.85rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 2.5rem 1rem;
            text-decoration: none;
            color: var(--green);
            text-align: center;
            transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s, background 0.18s;
            min-height: 160px;
        }

        .add-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(30,145,102,0.12);
            border-color: var(--green);
            background: #d4f0e6;
            color: var(--green);
        }

        .add-card-icon {
            width: 52px; height: 52px;
            border-radius: 0.65rem;
            background: rgba(30,145,102,0.12);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            color: var(--green);
            transition: transform 0.18s;
        }

        .add-card:hover .add-card-icon { transform: scale(1.08); }
        .add-card-label { font-size: 0.88rem; font-weight: 700; color: var(--green); }
        .add-card-sub   { font-size: 0.75rem; color: var(--green); opacity: 0.7; }

        /* ── Factura card ── */
        .factura-card {
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

        .factura-card:hover {
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
            padding: 0.9rem 1.1rem 0.75rem;
            background: var(--bg2);
            border-bottom: 1px solid var(--border);
        }

        .card-head-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.5rem;
            margin-bottom: 0.3rem;
        }

        .card-nro {
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--muted);
            margin-bottom: 0.15rem;
        }

        .card-num {
            font-family: 'DM Mono', monospace;
            font-size: 1rem;
            font-weight: 500;
            color: var(--text);
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

        .card-concepto {
            font-size: 0.8rem;
            color: var(--text2);
            font-weight: 500;
            line-height: 1.4;
        }

        /* Card body */
        .card-body {
            padding: 0.9rem 1.1rem;
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
            flex: 1;
        }

        /* Monto highlight */
        .card-monto-row {
            display: flex;
            align-items: baseline;
            gap: 0.4rem;
        }

        .card-monto-label {
            font-size: 0.7rem;
            color: var(--muted);
            font-weight: 600;
        }

        .card-monto {
            font-family: 'DM Mono', monospace;
            font-size: 1.2rem;
            font-weight: 500;
            color: var(--accent);
        }

        /* Mini stats */
        .mini-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.4rem;
        }

        .mini-stat {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 0.45rem;
            padding: 0.4rem 0.6rem;
            text-align: center;
        }

        .mini-stat-label {
            font-size: 0.62rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--muted);
        }

        .mini-stat-val {
            font-family: 'DM Mono', monospace;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .mini-stat-val.blue  { color: var(--accent); }
        .mini-stat-val.teal  { color: var(--green); }

        /* Progress bars */
        .prog-row {
            display: flex;
            flex-direction: column;
            gap: 0.22rem;
        }

        .prog-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.7rem;
        }

        .prog-label { color: var(--muted); font-weight: 600; }
        .prog-val   { font-family: 'DM Mono', monospace; color: var(--text2); }

        .prog-track {
            height: 5px;
            background: var(--bg2);
            border-radius: 99px;
            overflow: hidden;
        }

        .prog-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.6s ease;
        }

        .prog-fill.blue { background: var(--accent); }
        .prog-fill.teal { background: var(--green); }

        /* Card footer action */
        .card-action {
            padding: 0.65rem 1.1rem;
            border-top: 1px solid var(--border);
            background: var(--surface2);
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        /* empty / no results */
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
                            <a href="{{ route('factura_venta.show', ['obraId' => $obra->id]) }}">Facturación</a>
                            <i class="fas fa-chevron-right"></i>
                            Facturas
                        </div>
                        <h1 class="ph-title">Facturas — <em>{{ $presupuesto?->clave ?? 'Presupuesto' }}</em></h1>
                        <p class="ph-sub">Facturas de venta asociadas al presupuesto</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar factura…" autocomplete="off">
                        </div>
                        <a href="{{ route('factura_venta.show', ['obraId' => $obra->id]) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @if(session('success'))
                <div class="alert alert-success mb-3" style="border-radius:0.55rem; font-size:0.85rem;">
                    {{ session('success') }}
                </div>
                @endif

                {{-- Summary strip --}}
                @php
                    $montoPresupuesto = $presupuesto?->monto_total ?? 0;
                    $facturadoTotal   = $facturas->sum('monto');
                    $cobradoTotal     = $facturas->reduce(fn($c, $f) => $c + $f->recibosVenta->sum('monto'), 0);
                    $saldoPorFacturar = $montoPresupuesto - $facturadoTotal;
                    $saldoPorCobrar   = $facturadoTotal - $cobradoTotal;
                @endphp

                <div class="summary-strip">
                    <div class="summary-item">
                        <span class="summary-label">Presupuesto</span>
                        <span class="summary-val green">Gs. {{ number_format($montoPresupuesto, 0, '', '.') }}</span>
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
                        <span class="summary-label">× Facturar</span>
                        <span class="summary-val orange">Gs. {{ number_format($saldoPorFacturar, 0, '', '.') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">× Cobrar</span>
                        <span class="summary-val red">Gs. {{ number_format($saldoPorCobrar, 0, '', '.') }}</span>
                    </div>
                </div>

                {{-- Cards grid --}}
                <div id="cards-grid">

                    {{-- Add card --}}
                    <a href="{{ route('factura_venta.create', ['presupuesto' => $presupuesto?->id ?? null, 'obra' => $obra?->id ?? null]) }}" class="add-card">
                        <div class="add-card-icon"><i class="fas fa-plus"></i></div>
                        <span class="add-card-label">Agregar factura</span>
                        <span class="add-card-sub">Nuevo registro</span>
                    </a>

                    {{-- Factura cards --}}
                    @foreach($facturas->reverse() as $factura)
                    @php
                        $presup              = $factura->presupuestoAprobado;
                        $montoPresup         = $presup?->monto_total ?? 0;
                        $porcentajeFactura   = $montoPresup > 0 ? round(($factura->monto / $montoPresup) * 100, 1) : 0;
                        $montoCobrado        = $factura->recibosVenta->sum('monto');
                        $porcentajeCobrado   = $factura->monto > 0 ? round(($montoCobrado / $factura->monto) * 100, 1) : 0;
                        $searchData          = strtolower(
                            ($factura->nro_factura ?? '') . ' ' .
                            ($factura->concepto ?? '') . ' ' .
                            number_format($factura->monto, 0, '', '.') . ' ' .
                            \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y')
                        );
                    @endphp

                    <div class="factura-card"
                         style="animation-delay:{{ $loop->index * 0.04 }}s"
                         data-search="{{ $searchData }}">

                        {{-- Card header --}}
                        <div class="card-head">
                            <div class="card-head-top">
                                <div>
                                    <div class="card-nro">Nro. Factura</div>
                                    <div class="card-num">{{ $factura->nro_factura }}</div>
                                </div>
                                <span class="card-fecha">
                                    <i class="far fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}
                                </span>
                            </div>
                            @if($factura->concepto)
                            <div class="card-concepto">{{ $factura->concepto }}</div>
                            @endif
                        </div>

                        {{-- Card body --}}
                        <div class="card-body">

                            <div class="card-monto-row">
                                <span class="card-monto-label">Monto</span>
                                <span class="card-monto">Gs. {{ number_format($factura->monto, 0, '', '.') }}</span>
                            </div>

                            <div class="mini-stats">
                                <div class="mini-stat">
                                    <div class="mini-stat-label">% Presupuesto</div>
                                    <div class="mini-stat-val blue">{{ $porcentajeFactura }}%</div>
                                </div>
                                <div class="mini-stat">
                                    <div class="mini-stat-label">% Cobrado</div>
                                    <div class="mini-stat-val teal">{{ $porcentajeCobrado }}%</div>
                                </div>
                            </div>

                            <div style="display:flex; flex-direction:column; gap:0.45rem;">
                                <div class="prog-row">
                                    <div class="prog-label-row">
                                        <span class="prog-label">Facturado del presupuesto</span>
                                        <span class="prog-val">{{ $porcentajeFactura }}%</span>
                                    </div>
                                    <div class="prog-track">
                                        <div class="prog-fill blue" style="width:{{ min($porcentajeFactura, 100) }}%"></div>
                                    </div>
                                </div>
                                <div class="prog-row">
                                    <div class="prog-label-row">
                                        <span class="prog-label">Cobrado de la factura</span>
                                        <span class="prog-val">{{ $porcentajeCobrado }}%</span>
                                    </div>
                                    <div class="prog-track">
                                        <div class="prog-fill teal" style="width:{{ min($porcentajeCobrado, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Footer: edit link + recibos button --}}
                        <div class="card-action">
                            <a href="{{ route('recibo_venta.index', ['presupuesto' => $factura->presupuesto_aprobado_id, 'obra' => $factura->obra_id, 'factura' => $factura->id]) }}"
                               class="btn btn-green"
                               onclick="event.stopPropagation()">
                                <i class="fas fa-receipt"></i> Agregar recibo
                            </a>
                            <a href="{{ route('factura_venta.edit', $factura->id) }}"
                               class="btn"
                               style="margin-left:0.4rem;"
                               onclick="event.stopPropagation()">
                                <i class="fas fa-pen"></i> Editar
                            </a>
                        </div>

                    </div>
                    @endforeach

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
    const cards = document.querySelectorAll('#cards-grid .factura-card');
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