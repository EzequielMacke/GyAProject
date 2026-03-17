<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Orden de Trabajo</title>
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
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* PAGE HEADER */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .ph-crumb {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem;
        }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }

        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }

        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        /* Search */
        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: 0.78rem; top: 50%;
            transform: translateY(-50%); color: var(--muted); font-size: 0.72rem; pointer-events: none;
        }
        .search-bar {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.83rem;
            background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.55rem;
            padding: 0.5rem 0.9rem 0.5rem 2.1rem; color: var(--text);
            width: 220px; outline: none; height: 38px;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
        }
        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus { border-color: var(--accent); width: 270px; box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        .btn-back {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border); background: var(--surface); color: var(--text2);
            text-decoration: none; transition: all 0.14s;
        }
        .btn-back:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        /* TABS */
        .tabs-row {
            display: flex; align-items: center; gap: 0.35rem;
            margin: 0 0 1.5rem; border-bottom: 2px solid var(--border);
        }
        .tab-btn {
            display: inline-flex; align-items: center; gap: 0.45rem;
            padding: 0.6rem 1.1rem; font-size: 0.85rem; font-weight: 600;
            color: var(--muted); background: none; border: none;
            border-bottom: 2.5px solid transparent; margin-bottom: -2px;
            cursor: pointer; transition: color 0.15s, border-color 0.15s;
        }
        .tab-btn:hover { color: var(--text2); }
        .tab-btn.active.tab-sin { color: var(--orange); border-bottom-color: var(--orange); }
        .tab-btn.active.tab-con { color: var(--green);  border-bottom-color: var(--green); }

        .tab-count {
            font-size: 0.7rem; font-weight: 700; padding: 0.12rem 0.5rem;
            border-radius: 99px; background: var(--surface2); color: var(--muted);
        }
        .tab-btn.active.tab-sin .tab-count { background: var(--orange-s); color: var(--orange); }
        .tab-btn.active.tab-con .tab-count { background: var(--green-s);  color: var(--green); }

        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* CARDS GRID */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1rem;
        }

        /* PRESUPUESTO CARD */
        .pres-card {
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
        .pres-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.09); border-color: var(--border2); }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        .card-head {
            padding: 0.9rem 1.1rem 0.8rem;
            border-bottom: 1px solid var(--border);
            background: var(--surface2);
            display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem;
        }
        .card-clave { font-family: 'DM Mono', monospace; font-size: 1rem; font-weight: 700; color: var(--accent); }
        .card-obra  { font-size: 0.78rem; color: var(--text2); margin-top: 0.15rem; font-weight: 500; }

        .estado-badge { font-size: 0.66rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 99px; white-space: nowrap; flex-shrink: 0; }
        .badge-danger    { background: #fee2e2; color: #dc2626; }
        .badge-warning   { background: var(--orange-s); color: var(--orange); }
        .badge-primary   { background: var(--accent-s); color: var(--accent); }
        .badge-success   { background: var(--green-s); color: var(--green); }
        .badge-secondary { background: var(--surface2); color: var(--muted); }

        .card-body {
            padding: 0.8rem 1.1rem;
            display: flex; flex-direction: column; gap: 0.45rem;
        }

        .detail-row { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.79rem; }
        .detail-row i { color: var(--muted); font-size: 0.68rem; margin-top: 0.18rem; flex-shrink: 0; width: 13px; text-align: center; }
        .detail-label { color: var(--muted); font-weight: 600; font-size: 0.7rem; flex-shrink: 0; min-width: 80px; }
        .detail-value { color: var(--text2); word-break: break-word; }
        .detail-value.money { font-family: 'DM Mono', monospace; font-size: 0.78rem; font-weight: 600; color: var(--green); }

        .orden-chip {
            display: inline-flex; align-items: center; gap: 0.35rem;
            background: var(--green-s); border: 1px solid var(--green-b);
            border-radius: 0.4rem; padding: 0.22rem 0.6rem;
            font-family: 'DM Mono', monospace; font-size: 0.78rem; font-weight: 600; color: var(--green);
        }

        .divider { border: none; border-top: 1px solid var(--border); margin: 0.15rem 0; }

        /* PDF preview */
        .pdf-preview-wrap {
            border-top: 1px solid var(--border);
        }

        .pdf-toggle {
            width: 100%; padding: 0.6rem 1.1rem;
            background: var(--surface2); border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: space-between;
            font-size: 0.78rem; font-weight: 600; color: var(--text2);
            transition: background 0.14s;
        }
        .pdf-toggle:hover { background: var(--accent-s); color: var(--accent); }
        .pdf-toggle i { font-size: 0.72rem; transition: transform 0.2s; }
        .pdf-toggle.open i.chevron { transform: rotate(180deg); }

        .pdf-frame-wrap {
            display: none;
            height: 320px;
            overflow-y: auto;
            background: #555;
        }
        .pdf-frame-wrap.open { display: block; }

        .pdf-frame-wrap iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }

        /* Card footer */
        .card-footer {
            padding: 0.6rem 1.1rem;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
        }
        .timestamps { font-size: 0.68rem; color: var(--muted); }

        .btn-asignar {
            height: 32px; padding: 0 0.9rem; border-radius: 0.45rem;
            display: inline-flex; align-items: center; gap: 0.38rem;
            font-size: 0.78rem; font-weight: 600;
            background: var(--accent); color: #fff;
            text-decoration: none; border: none; cursor: pointer;
            transition: background 0.15s, transform 0.15s;
        }
        .btn-asignar:hover { background: var(--accent-b); color: #fff; transform: scale(1.03); }
        .btn-asignar.green { background: var(--green); }
        .btn-asignar.green:hover { background: #177a56; }

        /* Empty / no results */
        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--muted); }
        .empty-state i { font-size: 2rem; opacity: 0.25; display: block; margin-bottom: 0.75rem; }
        .empty-state p { font-size: 0.85rem; }

        .no-results { display: none; grid-column: 1/-1; text-align: center; color: var(--muted); padding: 4rem 2rem; font-size: 0.85rem; }
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
                            Asignar Orden de Trabajo
                        </div>
                        <h1 class="ph-title">Orden de <em>Trabajo</em></h1>
                        <p class="ph-sub">Asignación de órdenes de trabajo a presupuestos aprobados</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar presupuesto…" autocomplete="off">
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
                    $sinOrden = $presupuestos->filter(fn($p) => empty($p->orden_trabajo))->values();
                    $conOrden = $presupuestos->filter(fn($p) => !empty($p->orden_trabajo))->values();
                @endphp

                <!-- TABS -->
                <div class="tabs-row">
                    <button class="tab-btn tab-sin active" data-tab="sin-orden">
                        <i class="fas fa-clock"></i> Sin orden de trabajo
                        <span class="tab-count">{{ $sinOrden->count() }}</span>
                    </button>
                    <button class="tab-btn tab-con" data-tab="con-orden">
                        <i class="fas fa-check-circle"></i> Con orden de trabajo
                        <span class="tab-count">{{ $conOrden->count() }}</span>
                    </button>
                </div>

                <!-- PANEL: SIN ORDEN -->
                <div class="tab-panel active" id="panel-sin-orden">
                    @if($sinOrden->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-clipboard-check"></i>
                        <p>Todos los presupuestos tienen orden de trabajo asignada.</p>
                    </div>
                    @else
                    <div class="cards-grid">
                        @foreach($sinOrden as $pres)
                        @php
                            $badgeClass = match($estados_btn[$pres->estado] ?? '') {
                                'danger'  => 'badge-danger',
                                'warning' => 'badge-warning',
                                'primary' => 'badge-primary',
                                'success' => 'badge-success',
                                default   => 'badge-secondary',
                            };
                            $pdfUrl = $pres->presupuesto ? asset('storage/presupuestos/' . $pres->presupuesto) : null;
                        @endphp
                        <div class="pres-card"
                             style="animation-delay:{{ $loop->index * 0.04 }}s"
                             data-search="{{ strtolower(($pres->clave ?? '') . ' ' . ($pres->obra->nombre ?? '') . ' ' . ($pres->ubicacion ?? '')) }}">

                            <div class="card-head">
                                <div>
                                    <div class="card-clave">{{ $pres->clave ?? 'Sin clave' }}</div>
                                    <div class="card-obra">
                                        <i class="fas fa-hard-hat" style="font-size:0.65rem;"></i>
                                        {{ $pres->obra->nombre ?? '—' }}
                                    </div>
                                </div>
                                <span class="estado-badge {{ $badgeClass }}">{{ $estados[$pres->estado] ?? 'Desconocido' }}</span>
                            </div>

                            <div class="card-body">
                                <div class="detail-row">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span class="detail-label">Ubicación</span>
                                    <span class="detail-value">{{ $pres->ubicacion ?? '—' }}</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-tools"></i>
                                    <span class="detail-label">Tipo trabajo</span>
                                    <span class="detail-value">{{ $tipos[$pres->tipo_trabajo] ?? '—' }}</span>
                                </div>
                                <hr class="divider">
                                <div class="detail-row">
                                    <i class="fas fa-dollar-sign"></i>
                                    <span class="detail-label">Monto total</span>
                                    <span class="detail-value money">Gs. {{ number_format($pres->monto_total ?? 0, 0, '', '.') }}</span>
                                </div>
                                @if($pres->observacion)
                                <div class="detail-row">
                                    <i class="fas fa-comment-alt"></i>
                                    <span class="detail-label">Observación</span>
                                    <span class="detail-value" style="font-style:italic; color:var(--muted);">{{ Str::limit($pres->observacion, 70) }}</span>
                                </div>
                                @endif
                            </div>

                            @if($pdfUrl)
                            <div class="pdf-preview-wrap">
                                <button type="button" class="pdf-toggle" onclick="togglePdf(this)">
                                    <span><i class="fas fa-file-pdf" style="color:#dc2626; margin-right:0.35rem;"></i> Ver presupuesto PDF</span>
                                    <i class="fas fa-chevron-down chevron"></i>
                                </button>
                                <div class="pdf-frame-wrap">
                                    <iframe data-src="{{ $pdfUrl }}" loading="lazy"></iframe>
                                </div>
                            </div>
                            @endif

                            <div class="card-footer">
                                <span class="timestamps">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $pres->fecha_aprobacion ? \Carbon\Carbon::parse($pres->fecha_aprobacion)->format('d/m/Y') : '—' }}
                                </span>
                                <a href="{{ route('asignar_orden.edit', $pres->id) }}" class="btn-asignar">
                                    <i class="fas fa-hashtag"></i> Asignar orden
                                </a>
                            </div>

                        </div>
                        @endforeach

                        <div class="no-results">
                            <i class="fas fa-search"></i> Sin resultados para tu búsqueda.
                        </div>
                    </div>
                    @endif
                </div>

                <!-- PANEL: CON ORDEN -->
                <div class="tab-panel" id="panel-con-orden">
                    @if($conOrden->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No hay presupuestos con orden de trabajo asignada.</p>
                    </div>
                    @else
                    <div class="cards-grid">
                        @foreach($conOrden as $pres)
                        @php
                            $badgeClass = match($estados_btn[$pres->estado] ?? '') {
                                'danger'  => 'badge-danger',
                                'warning' => 'badge-warning',
                                'primary' => 'badge-primary',
                                'success' => 'badge-success',
                                default   => 'badge-secondary',
                            };
                            $pdfUrl = $pres->presupuesto ? asset('storage/presupuestos/' . $pres->presupuesto) : null;    
                        @endphp
                        <div class="pres-card"
                             style="animation-delay:{{ $loop->index * 0.04 }}s"
                             data-search="{{ strtolower(($pres->clave ?? '') . ' ' . ($pres->obra->nombre ?? '') . ' ' . ($pres->orden_trabajo ?? '') . ' ' . ($pres->ubicacion ?? '')) }}">

                            <div class="card-head">
                                <div>
                                    <div class="card-clave">{{ $pres->clave ?? 'Sin clave' }}</div>
                                    <div class="card-obra">
                                        <i class="fas fa-hard-hat" style="font-size:0.65rem;"></i>
                                        {{ $pres->obra->nombre ?? '—' }}
                                    </div>
                                </div>
                                <span class="estado-badge {{ $badgeClass }}">{{ $estados[$pres->estado] ?? 'Desconocido' }}</span>
                            </div>

                            <div class="card-body">
                                <div class="detail-row">
                                    <i class="fas fa-clipboard-list"></i>
                                    <span class="detail-label">Orden trabajo</span>
                                    <span class="detail-value">
                                        <span class="orden-chip">
                                            <i class="fas fa-hashtag" style="font-size:0.65rem;"></i>
                                            {{ $pres->orden_trabajo }}
                                        </span>
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span class="detail-label">Ubicación</span>
                                    <span class="detail-value">{{ $pres->ubicacion ?? '—' }}</span>
                                </div>
                                <div class="detail-row">
                                    <i class="fas fa-tools"></i>
                                    <span class="detail-label">Tipo trabajo</span>
                                    <span class="detail-value">{{ $tipos[$pres->tipo_trabajo] ?? '—' }}</span>
                                </div>
                                <hr class="divider">
                                <div class="detail-row">
                                    <i class="fas fa-dollar-sign"></i>
                                    <span class="detail-label">Monto total</span>
                                    <span class="detail-value money">Gs. {{ number_format($pres->monto_total ?? 0, 0, '', '.') }}</span>
                                </div>
                                @if($pres->observacion)
                                <div class="detail-row">
                                    <i class="fas fa-comment-alt"></i>
                                    <span class="detail-label">Observación</span>
                                    <span class="detail-value" style="font-style:italic; color:var(--muted);">{{ Str::limit($pres->observacion, 70) }}</span>
                                </div>
                                @endif
                            </div>

                            @if($pdfUrl)
                            <div class="pdf-preview-wrap">
                                <button type="button" class="pdf-toggle" onclick="togglePdf(this)">
                                    <span><i class="fas fa-file-pdf" style="color:#dc2626; margin-right:0.35rem;"></i> Ver presupuesto PDF</span>
                                    <i class="fas fa-chevron-down chevron"></i>
                                </button>
                                <div class="pdf-frame-wrap">
                                    <iframe data-src="{{ $pdfUrl }}" loading="lazy"></iframe>
                                </div>
                            </div>
                            @endif

                            <div class="card-footer">
                                <span class="timestamps">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $pres->fecha_aprobacion ? \Carbon\Carbon::parse($pres->fecha_aprobacion)->format('d/m/Y') : '—' }}
                                </span>
                                <a href="{{ route('asignar_orden.edit', $pres->id) }}" class="btn-asignar green">
                                    <i class="fas fa-pen"></i> Editar orden
                                </a>
                            </div>

                        </div>
                        @endforeach

                        <div class="no-results">
                            <i class="fas fa-search"></i> Sin resultados para tu búsqueda.
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
function togglePdf(btn) {
    const wrap = btn.nextElementSibling;
    const isOpen = wrap.classList.contains('open');

    wrap.classList.toggle('open', !isOpen);
    btn.classList.toggle('open', !isOpen);

    // Lazy load: set src only when opening for the first time
    if (!isOpen) {
        const iframe = wrap.querySelector('iframe');
        if (iframe && iframe.dataset.src && !iframe.src.includes('storage')) {
            iframe.src = iframe.dataset.src;
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('panel-' + btn.dataset.tab).classList.add('active');
            document.getElementById('search').value = '';
            filterCards('');
        });
    });

    // Search
    document.getElementById('search').addEventListener('input', function () {
        filterCards(this.value.toLowerCase().trim());
    });

    function filterCards(q) {
        const panel = document.querySelector('.tab-panel.active');
        if (!panel) return;
        const cards = panel.querySelectorAll('.pres-card');
        const noRes = panel.querySelector('.no-results');
        let vis = 0;

        cards.forEach(card => {
            const show = (card.dataset.search || '').includes(q);
            card.style.display = show ? '' : 'none';
            if (show) { card.style.animationDelay = '0s'; vis++; }
        });

        if (noRes) noRes.style.display = (!vis && cards.length && q) ? 'block' : 'none';
    }
});
</script>
</body>
</html>
