<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Facturas</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
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
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

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

        /* SEARCH BAR */
        .search-wrap {
            position: relative; width: 260px; transition: width 0.22s;
        }
        .search-wrap i {
            position: absolute; left: 0.8rem; top: 50%; transform: translateY(-50%);
            color: var(--muted); font-size: 0.78rem; pointer-events: none;
        }
        .search-input {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem;
            width: 100%; padding: 0.48rem 0.9rem 0.48rem 2.1rem;
            border: 1.5px solid var(--border); border-radius: 0.55rem;
            background: var(--surface); color: var(--text); outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            height: 36px;
        }
        .search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .search-wrap:focus-within { width: 320px; }
        .search-input::placeholder { color: var(--muted); }

        /* BACK BUTTON */
        .btn-back {
            height: 36px;
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
        .btn-back:hover {
            border-color: var(--border2);
            background: var(--surface2);
            color: var(--text);
        }

        /* ESTADO BADGE */
        .estado-badge {
            font-size: 0.68rem; font-weight: 700; padding: 0.22rem 0.65rem; border-radius: 99px;
            white-space: nowrap;
        }
        .badge-danger    { background: #fee2e2; color: #dc2626; }
        .badge-warning   { background: var(--orange-s); color: var(--orange); }
        .badge-primary   { background: var(--accent-s); color: var(--accent); }
        .badge-success   { background: var(--green-s); color: var(--green); }
        .badge-secondary { background: var(--surface2); color: var(--muted); }

        /* CARDS GRID */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 0.85rem;
        }

        .pres-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            text-decoration: none;
            display: block;
            transition: border-color 0.15s, box-shadow 0.15s, transform 0.15s;
            animation: cardIn 0.2s ease both;
        }
        .pres-card:hover {
            border-color: var(--accent);
            box-shadow: 0 4px 18px rgba(42,111,219,0.12);
            transform: translateY(-2px);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: none; }
        }

        .pres-card-top {
            padding: 0.9rem 1.1rem 0.75rem;
            display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem;
        }

        .pres-clave {
            font-family: 'DM Mono', monospace; font-size: 0.88rem; font-weight: 600;
            color: var(--accent); line-height: 1.2;
        }
        .pres-obra {
            font-size: 0.78rem; font-weight: 500; color: var(--muted); margin-top: 0.2rem;
        }

        .pres-card-body {
            padding: 0 1.1rem 0.85rem;
            display: flex; flex-direction: column; gap: 0.4rem;
        }

        .pres-row {
            display: flex; align-items: center; gap: 0.55rem;
            font-size: 0.8rem;
        }
        .pres-row i {
            width: 16px; text-align: center;
            font-size: 0.7rem; color: var(--muted); flex-shrink: 0;
        }
        .pres-row-label { color: var(--muted); font-weight: 500; flex-shrink: 0; }
        .pres-row-value { color: var(--text); font-weight: 600; }
        .pres-row-value.mono {
            font-family: 'DM Mono', monospace; font-size: 0.78rem; color: var(--accent);
        }
        .pres-row-value.money {
            font-family: 'DM Mono', monospace; font-size: 0.78rem; color: var(--green); font-weight: 600;
        }
        .pres-row-value.ot {
            font-family: 'DM Mono', monospace; font-size: 0.78rem;
            color: var(--text); font-weight: 700;
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 0.3rem; padding: 0.1rem 0.45rem;
        }

        .pres-card-footer {
            border-top: 1px solid var(--border);
            padding: 0.55rem 1.1rem;
            display: flex; align-items: center; justify-content: space-between;
            font-size: 0.75rem; color: var(--muted);
        }
        .pres-card-footer span { display: flex; align-items: center; gap: 0.35rem; }

        /* EMPTY */
        .empty-state {
            text-align: center; padding: 3.5rem 1rem; color: var(--muted);
        }
        .empty-state i { font-size: 2.2rem; margin-bottom: 0.75rem; opacity: 0.35; }
        .empty-state p { font-size: 0.88rem; }
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
                            Cargar Facturas
                        </div>
                        <h1 class="ph-title">Cargar <em>facturas de venta</em></h1>
                        <p class="ph-sub">{{ $presupuestos->count() }} presupuesto{{ $presupuestos->count() != 1 ? 's' : '' }} con orden de trabajo asignada</p>
                    </div>
                    <div style="display:flex; align-items:center; gap:0.6rem;">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search-input" id="searchInput" placeholder="Buscar por clave, obra u OT…">
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

                @if($presupuestos->isEmpty())
                <div class="empty-state">
                    <div><i class="fas fa-file-invoice-dollar"></i></div>
                    <p>No hay presupuestos con orden de trabajo asignada.</p>
                </div>
                @else
                <div class="cards-grid" id="cardsGrid">
                    @foreach($presupuestos as $i => $pres)
                    @php
                        $badgeClass = match($estados_btn[$pres->estado] ?? '') {
                            'danger'  => 'badge-danger',
                            'warning' => 'badge-warning',
                            'primary' => 'badge-primary',
                            'success' => 'badge-success',
                            default   => 'badge-secondary',
                        };
                    @endphp
                    <a href="{{ route('factura_venta.create', ['presupuesto' => $pres->id, 'obra' => $pres->obra_id]) }}"
                       class="pres-card"
                       data-search="{{ strtolower($pres->clave . ' ' . ($pres->obra->nombre ?? '') . ' ' . $pres->orden_trabajo) }}"
                       style="animation-delay: {{ $i * 0.04 }}s">
                        <div class="pres-card-top">
                            <div>
                                <div class="pres-clave">{{ $pres->clave ?? '#'.$pres->id }}</div>
                                <div class="pres-obra">{{ $pres->obra->nombre ?? '—' }}</div>
                            </div>
                            <span class="estado-badge {{ $badgeClass }}">
                                {{ $estados[$pres->estado] ?? '—' }}
                            </span>
                        </div>
                        <div class="pres-card-body">
                            <div class="pres-row">
                                <i class="fas fa-clipboard-list"></i>
                                <span class="pres-row-label">Orden:</span>
                                <span class="pres-row-value ot">{{ $pres->orden_trabajo }}</span>
                            </div>
                            <div class="pres-row">
                                <i class="fas fa-hard-hat"></i>
                                <span class="pres-row-label">Tipo:</span>
                                <span class="pres-row-value">{{ $tipos[$pres->tipo_trabajo] ?? '—' }}</span>
                            </div>
                            <div class="pres-row">
                                <i class="fas fa-dollar-sign"></i>
                                <span class="pres-row-label">Monto:</span>
                                <span class="pres-row-value money">Gs. {{ number_format($pres->monto_total ?? 0, 0, '', '.') }}</span>
                            </div>
                        </div>
                        <div class="pres-card-footer">
                            <span><i class="fas fa-map-marker-alt"></i> {{ $pres->ubicacion ?? '—' }}</span>
                            <span><i class="fas fa-plus-circle" style="color: var(--accent);"></i> Cargar factura</span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
document.getElementById('searchInput')?.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('#cardsGrid .pres-card').forEach(card => {
        const match = !q || card.dataset.search.includes(q);
        card.style.display = match ? '' : 'none';
        if (match) card.style.animationDelay = '0s';
    });
});
</script>
</body>
</html>
