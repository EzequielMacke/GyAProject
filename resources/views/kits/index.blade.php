<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kits</title>
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
            --slate:    #4e6070;
            --slate-s:  #edf1f4;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

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

        .ph-title em {
            font-style: normal;
            color: var(--accent);
        }

        .ph-sub {
            font-size: 0.8rem;
            color: var(--muted);
            margin-top: 0.3rem;
        }

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
            width: 210px;
            outline: none;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
            height: 38px;
        }

        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus {
            border-color: var(--accent);
            width: 250px;
            box-shadow: 0 0 0 3px rgba(42,111,219,0.12);
        }

        /* Buttons */
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
            background: var(--accent-b);
            border-color: var(--accent-b);
            color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
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
            font-size: 0.78rem;
            flex-shrink: 0;
        }

        .stat-icon.blue   { background: var(--accent-s); color: var(--accent); }
        .stat-icon.green  { background: var(--green-s);  color: var(--green); }
        .stat-icon.plain  { background: var(--slate-s);  color: var(--slate); }

        .stat-val { font-size: 1.3rem; font-weight: 700; color: var(--text); letter-spacing: -0.5px; line-height: 1; }
        .stat-lbl { font-size: 0.7rem; color: var(--muted); font-weight: 500; margin-top: 0.1rem; }

        /* ══════════════════════════════
           CARDS GRID
        ══════════════════════════════ */
        #kits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }

        /* ── Kit card ── */
        .kit-card {
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

        .kit-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            border-color: var(--border2);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        /* Card top stripe */
        .card-stripe {
            height: 3px;
            background: linear-gradient(90deg, var(--accent), #6aaaf5);
        }
        .card-stripe.off { background: var(--border); }

        /* Card header */
        .card-header {
            padding: 1.15rem 1.25rem 0.85rem;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .card-avatar {
            width: 40px; height: 40px;
            border-radius: 0.55rem;
            background: var(--accent-s);
            color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
            font-weight: 700;
            flex-shrink: 0;
            letter-spacing: -0.5px;
        }

        .card-avatar.off { background: var(--slate-s); color: var(--slate); }

        .card-title-wrap { flex: 1; min-width: 0; }

        .card-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
            word-break: break-word;   
        }

        .card-id {
            font-family: 'DM Mono', monospace;
            font-size: 0.63rem;
            color: var(--muted);
            margin-top: 0.15rem;
        }

        .status-badge {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 0.32rem;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            padding: 0.24rem 0.6rem;
            border-radius: 99px;
            margin-top: 2px;
        }

        .status-badge i { font-size: 0.45rem; }
        .status-badge.on  { background: var(--green-s);  color: var(--green); }
        .status-badge.off { background: var(--surface2); color: var(--muted); }

        /* Dates */
        .card-dates {
            padding: 0 1.25rem 0.9rem;
            display: flex;
            flex-direction: column;
            gap: 0.22rem;
        }

        .date-row {
            display: flex;
            align-items: center;
            gap: 0.38rem;
            font-size: 0.75rem;
            color: var(--text2);
        }

        .date-row i { font-size: 0.6rem; color: var(--muted); width: 10px; text-align: center; }

        /* Divider */
        .card-divider {
            height: 1px;
            background: var(--border);
            margin: 0 1.25rem;
        }

        /* Insumos section */
        .card-insumos {
            padding: 0.85rem 1.25rem 1.1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .insumos-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.65rem;
        }

        .insumos-title {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.9px;
            text-transform: uppercase;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .insumos-count-tag {
            font-family: 'DM Mono', monospace;
            font-size: 0.66rem;
            font-weight: 500;
            color: var(--accent);
            background: var(--accent-s);
            padding: 0.1rem 0.42rem;
            border-radius: 0.28rem;
        }

        /* Insumo rows */
        .insumo-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .insumo-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.42rem 0.55rem;
            border-radius: 0.4rem;
            transition: background 0.1s;
            gap: 0.5rem;
        }

        .insumo-row:hover { background: var(--surface2); }

        .insumo-name {
            display: flex;
            align-items: center;
            gap: 0.38rem;
            font-size: 0.83rem;
            color: var(--text);
            font-weight: 500;
            min-width: 0;
        }

        .insumo-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: var(--accent);
            flex-shrink: 0;
            opacity: 0.5;
        }

        .insumo-name span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .insumo-qty {
            font-family: 'DM Mono', monospace;
            font-size: 0.71rem;
            color: var(--text2);
            background: var(--surface2);
            border: 1px solid var(--border);
            padding: 0.1rem 0.42rem;
            border-radius: 0.3rem;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .no-insumos {
            font-size: 0.8rem;
            color: var(--muted);
            font-style: italic;
            padding: 0.3rem 0.2rem;
        }

        /* ── Empty / no results ── */
        .empty-state {
            grid-column: 1 / -1;
            padding: 5rem 2rem;
            text-align: center;
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
                            <i class="fas fa-wrench"></i> Mantenimiento
                            <i class="fas fa-chevron-right"></i> Kits
                        </div>
                        <h1 class="ph-title">Kits de <em>insumos</em></h1>
                        <p class="ph-sub">Visualizá y gestioná todos los kits disponibles</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar kits…" autocomplete="off">
                        </div>
                        @permiso('kit', 'agregar')
                        <a href="{{ route('kits.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nuevo kit
                        </a>
                        @endpermiso
                        <a href="{{ route('mantenimiento.show') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                {{-- Stats --}}
                @php
                    $total     = $kits->count();
                    $activos   = $kits->where('estado', 1)->count();
                    $inactivos = $total - $activos;
                @endphp
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-layer-group"></i></div>
                        <div>
                            <div class="stat-val">{{ $total }}</div>
                            <div class="stat-lbl">Total kits</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
                        <div>
                            <div class="stat-val">{{ $activos }}</div>
                            <div class="stat-lbl">Activos</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon plain"><i class="fas fa-circle-pause"></i></div>
                        <div>
                            <div class="stat-val">{{ $inactivos }}</div>
                            <div class="stat-lbl">Inactivos</div>
                        </div>
                    </div>
                </div>

                {{-- Cards --}}
                <div id="kits-grid">
                    @forelse($kits->reverse() as $kit)
                    @php
                        $on  = $kit->estado == 1;
                        $cnt = $kit->detalles->count();
                        $initials = mb_strtoupper(mb_substr($kit->descripcion, 0, 2));
                    @endphp

                    <div class="kit-card" style="animation-delay:{{ $loop->index * 0.05 }}s">

                        <div class="card-stripe {{ $on ? '' : 'off' }}"></div>

                        <div class="card-header">
                            <div class="card-avatar {{ $on ? '' : 'off' }}">{{ $initials }}</div>
                            <div class="card-title-wrap">
                                <div class="card-name" title="{{ $kit->descripcion }}">{{ $kit->descripcion }}</div>
                                <div class="card-id">#{{ str_pad($kit->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </div>
                            <span class="status-badge {{ $on ? 'on' : 'off' }}">
                                <i class="fas fa-circle"></i>
                                {{ $on ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <div class="card-dates">
                            <div class="date-row">
                                <i class="far fa-clock"></i>
                                Creado {{ \Carbon\Carbon::parse($kit->created_at)->diffForHumans() }}
                            </div>
                            @if(!empty($kit->updated_at))
                            <div class="date-row">
                                <i class="fas fa-rotate-right"></i>
                                Actualizado {{ \Carbon\Carbon::parse($kit->updated_at)->diffForHumans() }}
                            </div>
                            @endif
                        </div>

                        <div class="card-divider"></div>

                        <div class="card-insumos">
                            <div class="insumos-header">
                                <span class="insumos-title">
                                    <i class="fas fa-cubes"></i> Insumos
                                </span>
                                @if($cnt > 0)
                                <span class="insumos-count-tag">{{ $cnt }}</span>
                                @endif
                            </div>

                            @if($cnt)
                            <ul class="insumo-list">
                                @foreach($kit->detalles as $d)
                                <li class="insumo-row">
                                    <span class="insumo-name">
                                        <span class="insumo-dot"></span>
                                        <span>{{ $d->insumo->nombre ?? '—' }}</span>
                                    </span>
                                    <span class="insumo-qty">
                                        {{ $d->cantidad }} {{ config('constantes.unidad_medida')[$d->unidad_medida_id] ?? '' }}
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="no-insumos">Sin insumos registrados</p>
                            @endif
                        </div>

                    </div>

                    @empty
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <p>No hay kits registrados.</p>
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
    const cards = document.querySelectorAll('#kits-grid .kit-card');
    const noRes = document.getElementById('no-results');

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let vis = 0;
        cards.forEach(c => {
            const show = c.textContent.toLowerCase().includes(q);
            c.style.display = show ? '' : 'none';
            if (show) vis++;
        });
        noRes.style.display = (!vis && cards.length) ? 'block' : 'none';
    });
});
</script>
</body>
</html>