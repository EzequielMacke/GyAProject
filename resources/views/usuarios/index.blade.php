<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
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
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        /* Search */
        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: 0.78rem; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: 0.72rem; pointer-events: none;
        }
        .search-bar {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.83rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            padding: 0.5rem 0.9rem 0.5rem 2.1rem;
            color: var(--text); width: 210px; outline: none;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
            height: 38px;
        }
        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus { border-color: var(--accent); width: 250px; box-shadow: 0 0 0 3px rgba(42,111,219,0.12); }

        /* Buttons */
        .btn {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface); color: var(--text2);
            text-decoration: none; cursor: pointer;
            transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }

        /* STATS */
        .stats-row { display: flex; gap: 0.7rem; margin-bottom: 1.75rem; flex-wrap: wrap; }
        .stat-card {
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.6rem; padding: 0.85rem 1.1rem;
            display: flex; align-items: center; gap: 0.7rem; min-width: 130px;
        }
        .stat-icon {
            width: 32px; height: 32px; border-radius: 0.4rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; flex-shrink: 0;
        }
        .stat-icon.blue  { background: var(--accent-s); color: var(--accent); }
        .stat-icon.green { background: var(--green-s);  color: var(--green); }
        .stat-icon.plain { background: var(--slate-s);  color: var(--slate); }
        .stat-val { font-size: 1.3rem; font-weight: 700; color: var(--text); letter-spacing: -0.5px; line-height: 1; }
        .stat-lbl { font-size: 0.7rem; color: var(--muted); font-weight: 500; margin-top: 0.1rem; }

        /* TABLE */
        .table-wrap {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .usu-table {
            width: 100%;
            border-collapse: collapse;
        }

        .usu-table thead tr {
            background: var(--bg2);
            border-bottom: 1.5px solid var(--border);
        }

        .usu-table th {
            padding: 0.65rem 1.1rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.9px;
            text-transform: uppercase;
            color: var(--muted);
            text-align: left;
        }

        .usu-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
            animation: rowIn 0.22s ease both;
        }
        .usu-table tbody tr:last-child { border-bottom: none; }
        .usu-table tbody tr:hover { background: var(--surface2); }

        @keyframes rowIn {
            from { opacity: 0; transform: translateX(-4px); }
            to   { opacity: 1; transform: none; }
        }

        .usu-table td {
            padding: 0.85rem 1.1rem;
            font-size: 0.855rem;
            color: var(--text2);
            vertical-align: middle;
        }

        .row-num {
            font-family: 'DM Mono', monospace;
            font-size: 0.72rem;
            color: var(--muted);
            width: 48px;
        }

        .name-cell { display: flex; align-items: center; gap: 0.65rem; }
        .name-avatar {
            width: 32px; height: 32px;
            border-radius: 0.4rem;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem; font-weight: 700;
            flex-shrink: 0; letter-spacing: -0.3px;
        }
        .name-avatar.off { background: var(--slate-s); color: var(--slate); }
        .name-text { font-size: 0.875rem; font-weight: 600; color: var(--text); }

        .area-cell { display: flex; align-items: center; gap: 0.38rem; font-size: 0.8rem; color: var(--muted); }
        .area-cell i { font-size: 0.62rem; }

        .action-btn {
            height: 30px; padding: 0 0.7rem; border-radius: 0.4rem;
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.75rem; font-weight: 600;
            border: 1.5px solid var(--border); background: var(--surface); color: var(--text2);
            text-decoration: none; cursor: pointer; transition: all 0.14s;
        }
        .action-btn:hover { background: var(--accent-s); border-color: var(--accent); color: var(--accent); }

        .status-badge {
            display: inline-flex; align-items: center; gap: 0.32rem;
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 0.3px; text-transform: uppercase;
            padding: 0.24rem 0.6rem; border-radius: 99px;
        }
        .status-badge i  { font-size: 0.45rem; }
        .status-badge.on  { background: var(--green-s); color: var(--green); }
        .status-badge.off { background: var(--surface2); color: var(--muted); }

        .empty-row td {
            text-align: center; padding: 4rem 2rem;
            color: var(--muted); font-size: 0.88rem;
        }
        .empty-row i { display: block; font-size: 1.8rem; margin-bottom: 0.6rem; opacity: 0.3; }

        .no-results-row { display: none; }
        .no-results-row td {
            text-align: center; padding: 3rem 2rem;
            color: var(--muted); font-size: 0.85rem;
        }
        .no-results-row i { display: block; font-size: 1.3rem; margin-bottom: 0.4rem; opacity: 0.3; }
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
                            <i class="fas fa-home"></i> Inicio
                            <i class="fas fa-chevron-right"></i> Usuarios
                        </div>
                        <h1 class="ph-title">Listado de <em>Usuarios</em></h1>
                        <p class="ph-sub">Visualizá y gestioná todos los usuarios del sistema</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar usuarios…" autocomplete="off">
                        </div>
                        @permiso('usu', 'agregar')
                        <a href="{{ route('usuarios.create') }}" class="btn btn-primary" id="agregar-usuario-btn">
                            <i class="fas fa-plus"></i> Nuevo usuario
                        </a>
                        @endpermiso
                        <a href="{{ route('home') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @php
                    $total     = $usuarios->count();
                    $activos   = $usuarios->where('estado', 1)->count();
                    $inactivos = $total - $activos;
                @endphp

                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                        <div>
                            <div class="stat-val">{{ $total }}</div>
                            <div class="stat-lbl">Total</div>
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

                @if (session('success'))
                    <div class="alert alert-success mb-3" style="border-radius:0.55rem; font-size:0.85rem;">{{ session('success') }}</div>
                @endif

                <div class="table-wrap">
                    <table class="usu-table" id="usu-table">
                        <thead>
                            <tr>
                                <th class="row-num">#</th>
                                <th>Usuario</th>
                                <th>Área</th>
                                <th>Estado</th>
                                @permiso('usu', 'editar')<th></th>@endpermiso
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuarios->reverse()->values() as $usuario)
                            @php
                                $on       = $usuario->estado == 1;
                                $initials = mb_strtoupper(mb_substr($usuario->nombre, 0, 2));
                            @endphp
                            <tr data-search="{{ strtolower($usuario->nombre . ' ' . ($usuario->area->descripcion ?? '')) }}"
                                style="animation-delay:{{ $loop->index * 0.03 }}s">
                                <td class="row-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="name-cell">
                                        <div class="name-avatar {{ $on ? '' : 'off' }}">{{ $initials }}</div>
                                        <span class="name-text">{{ $usuario->nombre }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="area-cell">
                                        <i class="fas fa-building"></i>
                                        {{ $usuario->area->descripcion ?? '—' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge {{ $on ? 'on' : 'off' }}">
                                        <i class="fas fa-circle"></i>
                                        {{ $estados[$usuario->estado] ?? 'Desconocido' }}
                                    </span>
                                </td>
                                @permiso('usu', 'editar')
                                <td>
                                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="action-btn">
                                        <i class="fas fa-pencil"></i> Editar
                                    </a>
                                </td>
                                @endpermiso
                            </tr>
                            @empty
                            <tr class="empty-row">
                                <td colspan="5">
                                    <i class="fas fa-users"></i>
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                            @endforelse

                            <tr class="no-results-row" id="no-results">
                                <td colspan="5">
                                    <i class="fas fa-search"></i>
                                    Sin resultados para tu búsqueda.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search');
    const rows  = document.querySelectorAll('#usu-table tbody tr[data-search]');
    const noRes = document.getElementById('no-results');

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let vis = 0;
        rows.forEach(r => {
            const show = r.dataset.search.includes(q);
            r.style.display = show ? '' : 'none';
            if (show) vis++;
        });
        noRes.style.display = (!vis && rows.length) ? 'table-row' : 'none';
    });

    document.addEventListener('keydown', function (e) {
        if (e.ctrlKey && e.key === '1') {
            e.preventDefault();
            const btn = document.getElementById('agregar-usuario-btn');
            if (btn) btn.click();
        }
    });
});
</script>
</body>
</html>
