<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preparar Pedidos</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

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
            --green-b:  #a8dcc9;
            --orange:   #d97706;
            --orange-s: #fef3c7;
            --orange-b: #fcd34d;
            --teal:     #0e7490;
            --teal-s:   #e0f2f7;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* PAGE HEADER */
        .ph {
            padding: 1.75rem 0 0.75rem;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
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
        }

        .ph-title em { font-style: normal; color: var(--teal); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }

        .ph-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Search */
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

        .btn-back {
            height: 38px;
            padding: 0 1rem;
            border-radius: 0.55rem;
            display: inline-flex;
            align-items: center;
            gap: 0.42rem;
            font-size: 0.825rem;
            font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface);
            color: var(--text2);
            text-decoration: none;
            transition: all 0.14s;
        }

        .btn-back:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        /* TABS */
        .tabs-row {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            margin: 1.25rem 0 1.5rem;
            border-bottom: 2px solid var(--border);
            padding-bottom: 0;
        }

        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 1.1rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--muted);
            background: none;
            border: none;
            border-bottom: 2.5px solid transparent;
            margin-bottom: -2px;
            cursor: pointer;
            transition: color 0.15s, border-color 0.15s;
        }

        .tab-btn:hover { color: var(--text2); }

        .tab-btn.active { color: var(--text); border-bottom-color: var(--accent); }
        .tab-btn.active.tab-preparado { border-bottom-color: var(--green); color: var(--green); }
        .tab-btn.active.tab-pendiente { border-bottom-color: var(--orange); color: var(--orange); }

        .tab-count {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.12rem 0.5rem;
            border-radius: 99px;
            background: var(--surface2);
            color: var(--muted);
        }

        .tab-btn.active.tab-pendiente .tab-count { background: var(--orange-s); color: var(--orange); }
        .tab-btn.active.tab-preparado .tab-count { background: var(--green-s);  color: var(--green); }

        /* TAB PANELS */
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* CARDS GRID */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }

        /* PEDIDO CARD */
        .pedido-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
            animation: cardIn 0.25s ease both;
        }

        .pedido-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.09);
            border-color: var(--border2);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        /* Card header */
        .card-head {
            padding: 1rem 1.1rem 0.85rem;
            border-bottom: 1px solid var(--border);
            background: var(--surface2);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .card-obra-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
            word-break: break-word;
        }

        .estado-badge {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.22rem 0.6rem;
            border-radius: 99px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .estado-pendiente { background: var(--orange-s); color: var(--orange); border: 1px solid var(--orange-b); }
        .estado-preparado { background: var(--green-s);  color: var(--green);  border: 1px solid var(--green-b); }

        /* Card body */
        .card-body {
            padding: 0.85rem 1.1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }

        .detail-row {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            font-size: 0.8rem;
        }

        .detail-row i {
            color: var(--muted);
            font-size: 0.7rem;
            margin-top: 0.18rem;
            flex-shrink: 0;
            width: 13px;
            text-align: center;
        }

        .detail-label {
            color: var(--muted);
            font-weight: 600;
            font-size: 0.72rem;
            flex-shrink: 0;
            min-width: 90px;
        }

        .detail-value {
            color: var(--text2);
            word-break: break-word;
        }

        .detail-value.mono {
            font-family: 'DM Mono', monospace;
            font-size: 0.78rem;
            color: var(--accent);
        }

        .detail-value.obs {
            font-style: italic;
            color: var(--muted);
        }

        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 0.2rem 0;
        }

        /* Timestamps row */
        .timestamps {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            padding: 0.5rem 1.1rem 0.65rem;
            background: var(--surface2);
            border-top: 1px solid var(--border);
            font-size: 0.72rem;
            color: var(--muted);
        }

        .timestamps i { margin-right: 0.25rem; font-size: 0.65rem; }

        /* Card footer */
        .card-footer {
            padding: 0.65rem 1.1rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
        }

        .btn-preparar {
            height: 34px;
            padding: 0 1rem;
            border-radius: 0.45rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            font-weight: 600;
            background: var(--teal);
            color: #fff;
            text-decoration: none;
            transition: background 0.15s, transform 0.15s;
        }

        .btn-preparar:hover { background: #0c5f75; color: #fff; transform: scale(1.03); }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
        }

        .empty-state i { font-size: 2rem; opacity: 0.25; display: block; margin-bottom: 0.75rem; }
        .empty-state p { font-size: 0.85rem; }

        /* No results */
        .no-results {
            display: none;
            grid-column: 1 / -1;
            text-align: center;
            color: var(--muted);
            padding: 4rem 2rem;
            font-size: 0.85rem;
        }

        .no-results i { display: block; font-size: 1.5rem; margin-bottom: 0.5rem; opacity: 0.3; }
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
                            <i class="fas fa-home"></i>
                            <a href="{{ url('/home') }}">Inicio</a>
                            <i class="fas fa-chevron-right"></i>
                            Preparar Pedidos
                        </div>
                        <h1 class="ph-title">Preparar <em>Pedidos</em></h1>
                        <p class="ph-sub">Gestión de pedidos de insumos para obras</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar pedido…" autocomplete="off">
                        </div>
                        <a href="{{ url('/home') }}" class="btn-back">
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

                @php
                    $pendientes = $pedobras->reverse()->where('estado', 1);
                    $preparados = $pedobras->reverse()->where('estado', 2);
                @endphp

                <!-- TABS -->
                <div class="tabs-row">
                    <button class="tab-btn tab-pendiente active" data-tab="pendientes">
                        <i class="fas fa-clock"></i>
                        Pendientes
                        <span class="tab-count">{{ $pendientes->count() }}</span>
                    </button>
                    <button class="tab-btn tab-preparado" data-tab="preparados">
                        <i class="fas fa-check-circle"></i>
                        Preparados
                        <span class="tab-count">{{ $preparados->count() }}</span>
                    </button>
                </div>

                <!-- PANEL: PENDIENTES -->
                <div class="tab-panel active" id="panel-pendientes">
                    @if($pendientes->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No hay pedidos pendientes.</p>
                    </div>
                    @else
                    <div class="cards-grid" id="grid-pendientes">
                        @foreach($pendientes as $pedobra)
                        <div class="pedido-card"
                             style="animation-delay:{{ $loop->index * 0.04 }}s"
                             data-search="{{ strtolower(($pedobra->obra->nombre ?? '') . ' ' . ($pedobra->usuario->nombre ?? '') . ' ' . ($pedobra->presupuesto->clave ?? '') . ' ' . ($pedobra->presupuesto->orden_trabajo ?? '') . ' ' . ($pedobra->observacion ?? '')) }}">

                            <div class="card-head">
                                <div>
                                    <div class="card-obra-name">{{ $pedobra->obra->nombre ?? '—' }}</div>
                                    <div style="font-size:0.75rem; color:var(--muted); margin-top:0.2rem;">
                                        <i class="fas fa-map-marker-alt" style="font-size:0.65rem;"></i>
                                        {{ $pedobra->obra->direccion ?? '—' }}
                                    </div>
                                </div>
                                <span class="estado-badge estado-pendiente">
                                    <i class="fas fa-clock"></i> Pendiente
                                </span>
                            </div>

                            <div class="card-body">
                                <div class="detail-row">
                                    <i class="fas fa-file-alt"></i>
                                    <span class="detail-label">Presupuesto</span>
                                    <span class="detail-value mono">{{ $pedobra->presupuesto->clave ?? '—' }}</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-clipboard-list"></i>
                                    <span class="detail-label">Orden de trabajo</span>
                                    <span class="detail-value mono">{{ $pedobra->presupuesto->orden_trabajo ?? '—' }}</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-user"></i>
                                    <span class="detail-label">Creado por</span>
                                    <span class="detail-value">{{ $pedobra->usuario->nombre ?? '—' }}</span>
                                </div>
                                <hr class="divider">
                                <div class="detail-row">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span class="detail-label">Fecha entrega</span>
                                    <span class="detail-value">{{ $pedobra->fecha_entrega ? \Carbon\Carbon::parse($pedobra->fecha_entrega)->format('d/m/Y') : '—' }}</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-boxes"></i>
                                    <span class="detail-label">Insumos</span>
                                    <span class="detail-value">
                                        {{ $pedobra->total_insumo }} pedidos &nbsp;·&nbsp;
                                        {{ $pedobra->insumo_confirmado }} preparados &nbsp;·&nbsp;
                                        <span style="color:var(--orange);">{{ $pedobra->insumo_faltante }} faltantes</span>
                                    </span>
                                </div>
                                @if($pedobra->observacion)
                                <div class="detail-row">
                                    <i class="fas fa-comment-alt"></i>
                                    <span class="detail-label">Observación</span>
                                    <span class="detail-value obs">{{ Str::limit($pedobra->observacion, 80) }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="timestamps">
                                <span><i class="fas fa-plus-circle"></i> Creado {{ $pedobra->created_at->diffForHumans() }}</span>
                                <span><i class="fas fa-sync-alt"></i> Actualizado {{ $pedobra->updated_at->diffForHumans() }}</span>
                            </div>

                            <div class="card-footer">
                                <a href="{{ route('preparobra.show', $pedobra->id) }}" class="btn-preparar">
                                    <i class="fas fa-tools"></i> Preparar Pedido
                                </a>
                            </div>

                        </div>
                        @endforeach

                        <div class="no-results" id="no-results-pendientes">
                            <i class="fas fa-search"></i>
                            Sin resultados para tu búsqueda.
                        </div>
                    </div>
                    @endif
                </div>

                <!-- PANEL: PREPARADOS -->
                <div class="tab-panel" id="panel-preparados">
                    @if($preparados->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-check-double"></i>
                        <p>No hay pedidos preparados aún.</p>
                    </div>
                    @else
                    <div class="cards-grid" id="grid-preparados">
                        @foreach($preparados as $pedobra)
                        <div class="pedido-card"
                             style="animation-delay:{{ $loop->index * 0.04 }}s"
                             data-search="{{ strtolower(($pedobra->obra->nombre ?? '') . ' ' . ($pedobra->usuario->nombre ?? '') . ' ' . ($pedobra->presupuesto->clave ?? '') . ' ' . ($pedobra->presupuesto->orden_trabajo ?? '') . ' ' . ($pedobra->observacion ?? '')) }}">

                            <div class="card-head">
                                <div>
                                    <div class="card-obra-name">{{ $pedobra->obra->nombre ?? '—' }}</div>
                                    <div style="font-size:0.75rem; color:var(--muted); margin-top:0.2rem;">
                                        <i class="fas fa-map-marker-alt" style="font-size:0.65rem;"></i>
                                        {{ $pedobra->obra->direccion ?? '—' }}
                                    </div>
                                </div>
                                <span class="estado-badge estado-preparado">
                                    <i class="fas fa-check-circle"></i> Preparado
                                </span>
                            </div>

                            <div class="card-body">
                                <div class="detail-row">
                                    <i class="fas fa-file-alt"></i>
                                    <span class="detail-label">Presupuesto</span>
                                    <span class="detail-value mono">{{ $pedobra->presupuesto->clave ?? '—' }}</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-clipboard-list"></i>
                                    <span class="detail-label">Orden de trabajo</span>
                                    <span class="detail-value mono">{{ $pedobra->presupuesto->orden_trabajo ?? '—' }}</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-user"></i>
                                    <span class="detail-label">Creado por</span>
                                    <span class="detail-value">{{ $pedobra->usuario->nombre ?? '—' }}</span>
                                </div>
                                <hr class="divider">
                                <div class="detail-row">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span class="detail-label">Fecha entrega</span>
                                    <span class="detail-value">{{ $pedobra->fecha_entrega ? \Carbon\Carbon::parse($pedobra->fecha_entrega)->format('d/m/Y') : '—' }}</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-boxes"></i>
                                    <span class="detail-label">Insumos</span>
                                    <span class="detail-value">
                                        {{ $pedobra->total_insumo }} pedidos &nbsp;·&nbsp;
                                        <span style="color:var(--green);">{{ $pedobra->insumo_confirmado }} preparados</span>
                                    </span>
                                </div>
                                @if($pedobra->observacion)
                                <div class="detail-row">
                                    <i class="fas fa-comment-alt"></i>
                                    <span class="detail-label">Observación</span>
                                    <span class="detail-value obs">{{ Str::limit($pedobra->observacion, 80) }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="timestamps">
                                <span><i class="fas fa-plus-circle"></i> Creado {{ $pedobra->created_at->diffForHumans() }}</span>
                                <span><i class="fas fa-sync-alt"></i> Actualizado {{ $pedobra->updated_at->diffForHumans() }}</span>
                            </div>

                            <div class="card-footer">
                                <a href="{{ route('preparobra.show', $pedobra->id) }}" class="btn-preparar">
                                    <i class="fas fa-eye"></i> Ver detalle
                                </a>
                            </div>

                        </div>
                        @endforeach

                        <div class="no-results" id="no-results-preparados">
                            <i class="fas fa-search"></i>
                            Sin resultados para tu búsqueda.
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Tabs ──────────────────────────────────────
    const tabBtns  = document.querySelectorAll('.tab-btn');
    const panels   = document.querySelectorAll('.tab-panel');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            tabBtns.forEach(b => b.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('panel-' + btn.dataset.tab).classList.add('active');
            // reset search
            document.getElementById('search').value = '';
            filterCards('');
        });
    });

    // ── Search ────────────────────────────────────
    document.getElementById('search').addEventListener('input', function () {
        filterCards(this.value.toLowerCase().trim());
    });

    function filterCards(q) {
        const activePanel = document.querySelector('.tab-panel.active');
        if (!activePanel) return;

        const cards  = activePanel.querySelectorAll('.pedido-card');
        const noRes  = activePanel.querySelector('.no-results');
        let vis = 0;

        cards.forEach(card => {
            const show = (card.dataset.search || '').includes(q);
            if (show) {
                card.style.display = '';
                card.style.animationDelay = '0s';
            } else {
                card.style.display = 'none';
            }
            if (show) vis++;
        });

        if (noRes) noRes.style.display = (!vis && cards.length && q) ? 'block' : 'none';
    }
});
</script>
</body>
</html>
