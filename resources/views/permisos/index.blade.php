<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permisos</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
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
            --orange:   #d97706;
            --orange-s: #fef3c7;
            --orange-b: #fcd34d;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .content-wrapper { background: var(--bg) !important; }

        /* PAGE HEADER */
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
        }

        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }

        .ph-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-back {
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

        .btn-back:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

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

        /* CARDS GRID */
        #cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1rem;
        }

        .area-card {
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

        .area-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.09);
            border-color: var(--border2);
            color: var(--text);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        .card-icon-header {
            background: var(--orange-s);
            padding: 1.4rem 1rem 1.2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid var(--border);
        }

        .icon-circle {
            width: 52px; height: 52px;
            border-radius: 50%;
            background: var(--orange);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .card-area-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
            text-align: center;
            line-height: 1.3;
            word-break: break-word;
        }

        .id-badge {
            font-size: 0.68rem;
            font-weight: 600;
            padding: 0.18rem 0.55rem;
            border-radius: 99px;
            background: var(--surface2);
            color: var(--text2);
            border: 1px solid var(--border);
        }

        .card-footer-row {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0.6rem 1rem;
            background: var(--surface2);
            border-top: 1px solid var(--border);
        }

        .edit-btn {
            height: 32px;
            padding: 0 0.85rem;
            border-radius: 0.45rem;
            display: inline-flex;
            align-items: center;
            gap: 0.38rem;
            font-size: 0.78rem;
            font-weight: 600;
            background: var(--orange);
            color: #fff;
            text-decoration: none;
            border: none;
            transition: background 0.15s, transform 0.15s;
        }

        .edit-btn:hover { background: #b45309; color: #fff; transform: scale(1.04); }

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
                            Permisos
                        </div>
                        <h1 class="ph-title">Permisos <em>por área</em></h1>
                        <p class="ph-sub">Configuración de acceso por módulo para cada área</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar área…" autocomplete="off">
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

                <div id="cards-grid">

                    @foreach ($permisos as $permiso)
                    <div class="area-card"
                         style="animation-delay:{{ $loop->index * 0.04 }}s"
                         data-search="{{ strtolower($permiso->descripcion . ' ' . $permiso->id) }}">

                        <div class="card-icon-header">
                            <div class="icon-circle"><i class="fas fa-user-shield"></i></div>
                            <div class="card-area-name">{{ $permiso->descripcion }}</div>
                            <span class="id-badge">ID #{{ $permiso->id }}</span>
                        </div>

                        <div class="card-footer-row">
                            <a href="{{ route('permisos.edit', $permiso->id) }}" class="edit-btn">
                                <i class="fas fa-pen"></i> Editar permisos
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
    const cards = document.querySelectorAll('#cards-grid .area-card');
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
