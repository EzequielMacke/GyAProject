<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen — {{ $obra->nombre }}</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #f0f3f7;
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
            --orange:   #d97706;
            --orange-s: #fef3c7;
            --red:      #d94040;
            --red-s:    #fdeaea;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* PAGE HEADER */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex; align-items: flex-end; justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap;
        }
        .ph-crumb {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem;
        }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.5rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.2; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        /* BUTTONS */
        .btn {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border); background: var(--surface);
            color: var(--text2); text-decoration: none; cursor: pointer;
            transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        /* STATS STRIP */
        .stats-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .stat-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.75rem;
            padding: 0.9rem 1.1rem;
            display: flex; flex-direction: column; gap: 0.25rem;
        }
        .stat-label { font-size: 0.7rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; }
        .stat-value { font-family: 'DM Mono', monospace; font-size: 1rem; font-weight: 600; color: var(--text); }
        .stat-value.green  { color: var(--green); }
        .stat-value.accent { color: var(--accent); }
        .stat-value.orange { color: var(--orange); }

        /* SECTION TITLE */
        .section-title {
            font-size: 0.78rem; font-weight: 700; color: var(--text2);
            text-transform: uppercase; letter-spacing: 0.06em;
            margin-bottom: 0.75rem;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .section-title i { font-size: 0.7rem; color: var(--accent); }

        /* INFO CARD */
        .info-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .info-card-body { padding: 1.1rem 1.25rem; }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 0.75rem 1.5rem;
        }
        .info-row { display: flex; flex-direction: column; gap: 0.15rem; }
        .info-row-label { font-size: 0.7rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; }
        .info-row-value { font-size: 0.85rem; font-weight: 600; color: var(--text); }
        .info-row-value.muted { color: var(--muted); font-weight: 500; }

        /* BADGES */
        .badge {
            display: inline-block;
            font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.6rem;
            border-radius: 99px; white-space: nowrap;
        }
        .badge-danger    { background: var(--red-s); color: var(--red); }
        .badge-warning   { background: var(--orange-s); color: var(--orange); }
        .badge-primary   { background: var(--accent-s); color: var(--accent); }
        .badge-success   { background: var(--green-s); color: var(--green); }
        .badge-secondary { background: var(--surface2); color: var(--muted); }

        /* PRESUPUESTO BLOCK */
        .pres-block {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            animation: cardIn 0.2s ease both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: none; }
        }
        .pres-block-header {
            padding: 0.75rem 1.25rem;
            background: var(--surface2);
            border-bottom: 1.5px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;
        }
        .pres-clave {
            font-family: 'DM Mono', monospace;
            font-size: 0.88rem; font-weight: 600; color: var(--accent);
        }
        .pres-meta { font-size: 0.75rem; color: var(--muted); margin-top: 0.15rem; }
        .pres-block-stats { display: flex; gap: 1.5rem; flex-wrap: wrap; align-items: center; }
        .pres-stat { display: flex; flex-direction: column; align-items: flex-end; gap: 0.1rem; }
        .pres-stat-label { font-size: 0.65rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; }
        .pres-stat-val { font-family: 'DM Mono', monospace; font-size: 0.8rem; font-weight: 600; color: var(--text); }
        .pres-stat-val.accent { color: var(--accent); }
        .pres-stat-val.green  { color: var(--green); }
        .pres-stat-val.orange { color: var(--orange); }

        /* PROGRESS BAR */
        .progress-mini { height: 4px; background: var(--surface2); border-radius: 99px; margin-top: 0.3rem; overflow: hidden; }
        .progress-mini-fill { height: 100%; border-radius: 99px; }

        /* OT CHIP */
        .ot-chip {
            font-family: 'DM Mono', monospace; font-size: 0.72rem; font-weight: 700;
            background: var(--surface2); border: 1px solid var(--border2);
            border-radius: 0.3rem; padding: 0.1rem 0.4rem; color: var(--text);
        }

        /* SUB-SECTIONS */
        .sub-section { padding: 0.85rem 1.25rem; }
        .sub-section + .sub-section { border-top: 1px solid var(--border); }
        .sub-section-title {
            font-size: 0.7rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.05em;
            margin-bottom: 0.65rem;
            display: flex; align-items: center; gap: 0.4rem;
        }

        /* SUB-TABLE */
        .sub-table { width: 100%; border-collapse: collapse; }
        .sub-table th {
            font-size: 0.67rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.04em;
            padding: 0.3rem 0.7rem; text-align: left;
            border-bottom: 1px solid var(--border);
        }
        .sub-table td {
            font-size: 0.8rem; color: var(--text);
            padding: 0.45rem 0.7rem;
            border-bottom: 1px solid var(--surface2);
            vertical-align: middle;
        }
        .sub-table tr:last-child td { border-bottom: none; }
        .sub-table .mono  { font-family: 'DM Mono', monospace; font-size: 0.75rem; color: var(--accent); }
        .sub-table .money { font-family: 'DM Mono', monospace; font-size: 0.75rem; color: var(--green); font-weight: 600; }
        .sub-table .muted-cell { color: var(--muted); font-size: 0.75rem; }
        .sub-table .orange-money { font-family: 'DM Mono', monospace; font-size: 0.75rem; color: var(--orange); font-weight: 600; }

        .empty-sub { text-align: center; padding: 0.85rem; font-size: 0.78rem; color: var(--muted); }
        .empty-sub i { margin-right: 0.3rem; opacity: 0.5; }
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
                            <a href="{{ route('obras.show', $obra->id) }}">{{ $obra->nombre }}</a>
                            <i class="fas fa-chevron-right"></i>
                            Resumen
                        </div>
                        <h1 class="ph-title">Resumen — <em>{{ $obra->nombre }}</em></h1>
                        <p class="ph-sub">Vista general de presupuestos, facturas y recibos</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('obras.show', $obra->id) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @php
                    $totalPresupuestado = $obra->presupuestos->sum('monto_total');
                    $allFacturas        = $obra->presupuestos->flatMap(fn($p) => $p->facturasVenta);
                    $allRecibos         = $allFacturas->flatMap(fn($f) => $f->recibosVenta);
                    $totalFacturado     = $allFacturas->sum('monto');
                    $totalCobrado       = $allRecibos->sum('monto');
                    $saldoFacturar      = $totalPresupuestado - $totalFacturado;
                    $saldoCobrar        = $totalFacturado - $totalCobrado;
                @endphp

                {{-- STATS STRIP --}}
                <div class="stats-strip">
                    <div class="stat-card">
                        <span class="stat-label">Presupuestos</span>
                        <span class="stat-value accent">{{ $obra->presupuestos->count() }}</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">Total presupuestado</span>
                        <span class="stat-value">Gs. {{ number_format($totalPresupuestado, 0, '', '.') }}</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">Total facturado</span>
                        <span class="stat-value accent">Gs. {{ number_format($totalFacturado, 0, '', '.') }}</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">Total cobrado</span>
                        <span class="stat-value green">Gs. {{ number_format($totalCobrado, 0, '', '.') }}</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">× Facturar</span>
                        <span class="stat-value orange">Gs. {{ number_format($saldoFacturar, 0, '', '.') }}</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">× Cobrar</span>
                        <span class="stat-value orange">Gs. {{ number_format($saldoCobrar, 0, '', '.') }}</span>
                    </div>
                </div>

                {{-- INFO DE LA OBRA --}}
                <div class="section-title">
                    <i class="fas fa-building"></i> Información de la obra
                </div>
                <div class="info-card">
                    <div class="info-card-body">
                        <div class="info-grid">
                            <div class="info-row">
                                <span class="info-row-label">Nombre</span>
                                <span class="info-row-value">{{ $obra->nombre }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-label">Dirección</span>
                                <span class="info-row-value {{ $obra->direccion ? '' : 'muted' }}">{{ $obra->direccion ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-label">Estado</span>
                                <span class="info-row-value">
                                    <span class="badge {{ $obra->estado == 1 ? 'badge-success' : 'badge-danger' }}">
                                        {{ $estadosObra[$obra->estado] ?? '—' }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-label">Fecha de carga</span>
                                <span class="info-row-value">
                                    {{ $obra->fecha_carga ? \Carbon\Carbon::parse($obra->fecha_carga)->format('d/m/Y') : '—' }}
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-label">Responsable</span>
                                <span class="info-row-value {{ $obra->usuario ? '' : 'muted' }}">{{ $obra->usuario?->name ?? '—' }}</span>
                            </div>
                            @if($obra->observacion)
                            <div class="info-row" style="grid-column: 1 / -1;">
                                <span class="info-row-label">Observación</span>
                                <span class="info-row-value" style="font-weight: 500; color: var(--text2);">{{ $obra->observacion }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- PRESUPUESTOS --}}
                <div class="section-title" style="margin-top: 0.5rem;">
                    <i class="fas fa-file-invoice-dollar"></i> Presupuestos ({{ $obra->presupuestos->count() }})
                </div>

                @if($obra->presupuestos->isEmpty())
                <div class="info-card">
                    <div class="info-card-body">
                        <div class="empty-sub"><i class="fas fa-folder-open"></i> No hay presupuestos registrados</div>
                    </div>
                </div>
                @else
                @foreach($obra->presupuestos as $i => $pres)
                @php
                    $presFacturado  = $pres->facturasVenta->sum('monto');
                    $presRecibos    = $pres->facturasVenta->flatMap(fn($f) => $f->recibosVenta);
                    $presCobrado    = $presRecibos->sum('monto');
                    $presSaldoFact  = $pres->monto_total - $presFacturado;
                    $presSaldoCobr  = $presFacturado - $presCobrado;
                    $pctFact        = $pres->monto_total > 0 ? min(100, round(($presFacturado / $pres->monto_total) * 100, 1)) : 0;
                    $pctCobr        = $presFacturado > 0 ? min(100, round(($presCobrado / $presFacturado) * 100, 1)) : 0;
                    $badgeClass     = match($estados_btn[$pres->estado] ?? '') {
                        'danger'  => 'badge-danger',
                        'warning' => 'badge-warning',
                        'primary' => 'badge-primary',
                        'success' => 'badge-success',
                        default   => 'badge-secondary',
                    };
                @endphp
                <div class="pres-block" style="animation-delay: {{ $i * 0.04 }}s">

                    <div class="pres-block-header">
                        <div>
                            <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
                                <span class="pres-clave">{{ $pres->clave ?? '#'.$pres->id }}</span>
                                <span class="badge {{ $badgeClass }}">{{ $estados[$pres->estado] ?? '—' }}</span>
                                @if($pres->orden_trabajo)
                                    <span class="ot-chip">OT: {{ $pres->orden_trabajo }}</span>
                                @endif
                            </div>
                            <div class="pres-meta">
                                {{ $tipos[$pres->tipo_trabajo] ?? '—' }}
                                @if($pres->ubicacion) · {{ $pres->ubicacion }}@endif
                                @if($pres->fecha_carga) · {{ \Carbon\Carbon::parse($pres->fecha_carga)->format('d/m/Y') }}@endif
                            </div>
                        </div>
                        <div class="pres-block-stats">
                            <div class="pres-stat">
                                <span class="pres-stat-label">Presupuesto</span>
                                <span class="pres-stat-val">Gs. {{ number_format($pres->monto_total, 0, '', '.') }}</span>
                            </div>
                            <div class="pres-stat">
                                <span class="pres-stat-label">Facturado</span>
                                <span class="pres-stat-val accent">Gs. {{ number_format($presFacturado, 0, '', '.') }}</span>
                                <div class="progress-mini" style="width: 80px;">
                                    <div class="progress-mini-fill" style="width:{{ $pctFact }}%; background: var(--accent);"></div>
                                </div>
                            </div>
                            <div class="pres-stat">
                                <span class="pres-stat-label">Cobrado</span>
                                <span class="pres-stat-val green">Gs. {{ number_format($presCobrado, 0, '', '.') }}</span>
                                <div class="progress-mini" style="width: 80px;">
                                    <div class="progress-mini-fill" style="width:{{ $pctCobr }}%; background: var(--green);"></div>
                                </div>
                            </div>
                            <div class="pres-stat">
                                <span class="pres-stat-label">× Facturar</span>
                                <span class="pres-stat-val orange">Gs. {{ number_format($presSaldoFact, 0, '', '.') }}</span>
                            </div>
                            <div class="pres-stat">
                                <span class="pres-stat-label">× Cobrar</span>
                                <span class="pres-stat-val orange">Gs. {{ number_format($presSaldoCobr, 0, '', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Facturas --}}
                    <div class="sub-section">
                        <div class="sub-section-title">
                            <i class="fas fa-receipt" style="color: var(--accent);"></i>
                            Facturas ({{ $pres->facturasVenta->count() }})
                        </div>
                        @if($pres->facturasVenta->isEmpty())
                        <div class="empty-sub"><i class="fas fa-inbox"></i> Sin facturas registradas</div>
                        @else
                        <table class="sub-table">
                            <thead>
                                <tr>
                                    <th>Nro. Factura</th>
                                    <th>Fecha</th>
                                    <th>Concepto</th>
                                    <th>Razón social</th>
                                    <th>Monto</th>
                                    <th style="text-align:center;">Recibos</th>
                                    <th>Cobrado</th>
                                    <th>× Cobrar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pres->facturasVenta as $factura)
                                @php
                                    $factCobrado   = $factura->recibosVenta->sum('monto');
                                    $factSaldoCobr = $factura->monto - $factCobrado;
                                @endphp
                                <tr>
                                    <td><span class="mono">{{ $factura->nro_factura ?? '—' }}</span></td>
                                    <td class="muted-cell">{{ $factura->fecha_emision ? \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') : '—' }}</td>
                                    <td>{{ $factura->concepto ?? '—' }}</td>
                                    <td class="muted-cell">{{ $factura->razon_social ?? '—' }}</td>
                                    <td><span class="money">Gs. {{ number_format($factura->monto, 0, '', '.') }}</span></td>
                                    <td class="muted-cell" style="text-align:center;">{{ $factura->recibosVenta->count() }}</td>
                                    <td><span class="money">Gs. {{ number_format($factCobrado, 0, '', '.') }}</span></td>
                                    <td>
                                        <span class="{{ $factSaldoCobr > 0 ? 'orange-money' : 'money' }}">
                                            Gs. {{ number_format($factSaldoCobr, 0, '', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>

                    {{-- Recibos --}}
                    <div class="sub-section">
                        <div class="sub-section-title">
                            <i class="fas fa-money-bill-wave" style="color: var(--green);"></i>
                            Recibos ({{ $presRecibos->count() }})
                        </div>
                        @if($presRecibos->isEmpty())
                        <div class="empty-sub"><i class="fas fa-inbox"></i> Sin recibos registrados</div>
                        @else
                        <table class="sub-table">
                            <thead>
                                <tr>
                                    <th>Nro. Recibo</th>
                                    <th>Fecha</th>
                                    <th>Concepto</th>
                                    <th>Factura</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($presRecibos as $recibo)
                                <tr>
                                    <td><span class="mono">{{ $recibo->nro_recibo ?? '—' }}</span></td>
                                    <td class="muted-cell">{{ $recibo->fecha_emision ? \Carbon\Carbon::parse($recibo->fecha_emision)->format('d/m/Y') : '—' }}</td>
                                    <td>{{ $recibo->concepto ?? '—' }}</td>
                                    <td class="muted-cell">{{ $recibo->facturaVenta?->nro_factura ?? '—' }}</td>
                                    <td><span class="money">Gs. {{ number_format($recibo->monto, 0, '', '.') }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>

                </div>
                @endforeach
                @endif

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>
</body>
</html>
