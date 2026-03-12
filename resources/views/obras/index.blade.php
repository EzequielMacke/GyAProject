<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Obras</title>
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

        .ph-crumb i { font-size: 0.58rem; }

        .ph-title {
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.4px;
            line-height: 1.1;
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

        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }

        /* ── Search ── */
        .search-wrap { position: relative; }

        .search-wrap i {
            position: absolute;
            left: 0.78rem;
            top: 50%;
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
            width: 280px;
            outline: none;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
            height: 38px;
        }

        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus {
            border-color: var(--accent);
            width: 340px;
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }

        /* ══════════════════════════════
           STATS
        ══════════════════════════════ */
        .stats-row {
            display: flex;
            gap: 0.7rem;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
        }

        .stat-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.6rem;
            padding: 0.85rem 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            min-width: 130px;
        }

        .stat-icon {
            width: 32px; height: 32px;
            border-radius: 0.4rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; flex-shrink: 0;
        }

        .stat-icon.blue  { background: var(--accent-s); color: var(--accent); }
        .stat-icon.green { background: var(--green-s);  color: var(--green); }
        .stat-icon.plain { background: var(--slate-s);  color: var(--slate); }

        .stat-val { font-size: 1.3rem; font-weight: 700; color: var(--text); letter-spacing: -0.5px; line-height: 1; }
        .stat-lbl { font-size: 0.7rem; color: var(--muted); font-weight: 500; margin-top: 0.1rem; }

        /* ══════════════════════════════
           CARDS GRID
        ══════════════════════════════ */
        #obras-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }

        /* ── Obra card ── */
        .obra-card {
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

        .obra-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.09);
            border-color: var(--border2);
            color: var(--text);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        /* map / placeholder */
        .card-map {
            height: 175px;
            width: 100%;
            display: block;
            object-fit: cover;
            background: var(--bg2);
            border: none;
            flex-shrink: 0;
        }

        .card-map-placeholder {
            height: 175px;
            background: var(--bg2);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 0.4rem;
            color: var(--muted);
            font-size: 0.78rem;
        }

        .card-map-placeholder i { font-size: 1.4rem; opacity: 0.35; }

        /* body */
        .card-body {
            padding: 1rem 1.15rem 0.85rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            flex: 1;
        }

        .card-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
            word-break: break-word;
        }

        .card-date {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .card-date i { font-size: 0.6rem; }

        .coincidencia-tag {
            display: none;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--green);
            background: var(--green-s);
            padding: 0.18rem 0.5rem;
            border-radius: 99px;
            margin-top: 0.2rem;
            width: fit-content;
        }

        .coincidencia-tag i { font-size: 0.55rem; }

        /* stats footer */
        .card-stats {
            display: flex;
            border-top: 1px solid var(--border);
            background: var(--surface2);
        }

        .card-stat {
            flex: 1;
            padding: 0.6rem 0.5rem;
            text-align: center;
        }

        .card-stat + .card-stat { border-left: 1px solid var(--border); }

        .card-stat-val {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1;
            font-family: 'DM Mono', monospace;
        }

        .card-stat-lbl {
            font-size: 0.67rem;
            color: var(--muted);
            font-weight: 500;
            margin-top: 0.15rem;
        }

        /* ── Empty / no results ── */
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
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('partials.navbar')
    @include('partials.sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">

                {{-- Header --}}
                <div class="ph">
                    <div>
                        <div class="ph-crumb">
                            <i class="fas fa-home"></i> Inicio
                            <i class="fas fa-chevron-right"></i> Obras
                        </div>
                        <h1 class="ph-title">Listado de <em>obras</em></h1>
                        <p class="ph-sub">Buscá, filtrá y accedé a todas las obras registradas</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar"
                                   placeholder="Buscar obra, presupuesto, contacto…" autocomplete="off">
                        </div>
                        <a href="{{ route('obras.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nueva obra
                        </a>
                        <a href="{{ route('home') }}" class="btn">
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

                {{-- Stats --}}
                @php $totalObras = $obras->count(); @endphp
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-hard-hat"></i></div>
                        <div>
                            <div class="stat-val">{{ $totalObras }}</div>
                            <div class="stat-lbl">Total obras</div>
                        </div>
                    </div>
                </div>

                {{-- Grid --}}
                <div id="obras-grid">

                    @forelse($obras->reverse() as $obra)
                    @php
                        $contactos         = \App\Models\Contacto::where('obra_id', $obra->id)->pluck('nombre')->implode(' ');
                        $ordenesTrabajo    = $obra->presupuestos->pluck('orden_trabajo')->implode(' ');
                        $presupuestosNames = $obra->presupuestos->pluck('clave')->implode(' ');
                        $lat = null; $lng = null;
                        if (!empty($obra->direccion) && preg_match('/maps\?q=([-0-9.]+),([-0-9.]+)/', $obra->direccion, $m)) {
                            $lat = $m[1]; $lng = $m[2];
                        }
                    @endphp

                    <a href="{{ route('obras.show', $obra->id) }}"
                       class="obra-card"
                       style="animation-delay:{{ $loop->index * 0.04 }}s"
                       data-nombre="{{ strtolower($obra->nombre) }}"
                       data-presupuestos="{{ strtolower($presupuestosNames) }}"
                       data-ordenes="{{ strtolower($ordenesTrabajo) }}"
                       data-contactos="{{ strtolower($contactos) }}">

                        {{-- Map / placeholder --}}
                        @if($lat && $lng)
                        <iframe class="card-map"
                            src="https://www.google.com/maps?q={{ $lat }},{{ $lng }}&hl=es&z=14&output=embed"
                            frameborder="0" allowfullscreen loading="lazy"></iframe>
                        @else
                        <div class="card-map-placeholder">
                            <i class="fas fa-map-marker-alt"></i>
                            Sin ubicación
                        </div>
                        @endif

                        {{-- Body --}}
                        <div class="card-body">
                            <div class="card-name">{{ $obra->nombre }}</div>
                            <div class="card-date">
                                <i class="far fa-clock"></i>
                                {{ \Carbon\Carbon::parse($obra->created_at)->diffForHumans() }}
                            </div>
                            <div class="coincidencia-tag" id="coin-{{ $obra->id }}">
                                <i class="fas fa-check-circle"></i>
                                <span></span>
                            </div>
                        </div>

                        {{-- Stats footer --}}
                        <div class="card-stats">
                            <div class="card-stat">
                                <div class="card-stat-val">{{ $obra->presupuestos->count() }}</div>
                                <div class="card-stat-lbl">Presupuestos</div>
                            </div>
                            <div class="card-stat">
                                <div class="card-stat-val">{{ \App\Models\Pedido_para_obra::where('obra_id', $obra->id)->count() }}</div>
                                <div class="card-stat-lbl">Pedidos</div>
                            </div>
                            <div class="card-stat">
                                <div class="card-stat-val">{{ $obra->directorios->count() }}</div>
                                <div class="card-stat-lbl">Usuarios</div>
                            </div>
                        </div>

                    </a>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-hard-hat"></i>
                        <p>No hay obras registradas.</p>
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
    const cards = document.querySelectorAll('#obras-grid .obra-card');
    const noRes = document.getElementById('no-results');

    input.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        let vis = 0;

        cards.forEach(card => {
            const nombre       = card.dataset.nombre       || '';
            const presupuestos = card.dataset.presupuestos || '';
            const ordenes      = card.dataset.ordenes      || '';
            const contactos    = card.dataset.contactos    || '';
            const todo         = nombre + ' ' + presupuestos + ' ' + ordenes + ' ' + contactos;

            const show = todo.includes(q);
            card.style.display = show ? '' : 'none';
            if (show) vis++;

            const tag  = card.querySelector('.coincidencia-tag');
            const span = tag?.querySelector('span');
            if (tag && span) {
                let label = '';
                if (q && show) {
                    if      (nombre.includes(q))       label = 'Nombre';
                    else if (contactos.includes(q))    label = 'Contacto';
                    else if (ordenes.includes(q))      label = 'Orden de trabajo';
                    else if (presupuestos.includes(q)) label = 'Presupuesto';
                }
                span.textContent = label;
                tag.style.display = label ? 'flex' : 'none';
            }
        });

        noRes.style.display = (!vis && cards.length && q) ? 'block' : 'none';
    });
});
</script>
</body>
</html>