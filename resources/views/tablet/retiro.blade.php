<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Retiro</title>
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
            --red:      #d94040;
            --red-s:    #fdeaea;
            --red-b:    #f5bcbc;
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

        .btn {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border); background: var(--surface); color: var(--text2);
            text-decoration: none; cursor: pointer; transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        /* 3-COLUMN LAYOUT */
        .retiro-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.25rem;
            align-items: start;
        }
        @media (max-width: 1100px) {
            .retiro-grid { grid-template-columns: 1fr; }
        }

        .panel {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            display: flex;
            flex-direction: column;
        }
        .panel-header {
            padding: 0.9rem 1.1rem;
            background: var(--bg2);
            border-bottom: 1.5px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
        }
        .panel-header-left { display: flex; align-items: center; gap: 0.55rem; }
        .panel-header-icon {
            width: 28px; height: 28px; border-radius: 0.4rem;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem; flex-shrink: 0;
        }
        .panel-header-icon.green { background: var(--green-s); color: var(--green); }
        .panel-header-icon.slate { background: var(--slate-s); color: var(--slate); }
        .panel-header-text { font-size: 0.82rem; font-weight: 700; color: var(--text); }
        .panel-header-count {
            font-size: 0.68rem; font-weight: 700; color: var(--muted);
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 99px; padding: 0.15rem 0.55rem;
        }

        .panel-search { padding: 0.8rem 0.9rem 0; }
        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: 0.7rem; top: 50%;
            transform: translateY(-50%); color: var(--muted); font-size: 0.68rem; pointer-events: none;
        }
        .search-bar {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem;
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 0.5rem;
            padding: 0.5rem 0.8rem 0.5rem 1.95rem;
            color: var(--text); width: 100%; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            height: 36px;
        }
        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        .panel-body {
            padding: 0.9rem;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.6rem;
            max-height: 560px;
            overflow-y: auto;
        }

        /* Small selectable cards */
        .mini-card {
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 0.6rem;
            padding: 0.65rem 0.6rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0.4rem;
            cursor: pointer;
            transition: transform 0.14s, box-shadow 0.14s, border-color 0.14s, background 0.14s;
            animation: cardIn 0.2s ease both;
        }
        .mini-card:hover {
            transform: translateY(-2px);
            border-color: var(--border2);
            box-shadow: 0 4px 14px rgba(0,0,0,0.07);
        }
        .mini-card.selected,
        .mini-card.active-user {
            border-color: var(--accent);
            background: var(--accent-s);
            box-shadow: 0 0 0 3px rgba(42,111,219,0.12);
        }

        .mini-card.assigned {
            border-color: var(--green-b);
            background: var(--green-s);
        }
        .mini-card.assigned:hover { transform: none; }

        .mini-card.unavailable {
            cursor: not-allowed;
            opacity: 0.55;
        }
        .mini-card.unavailable:hover {
            transform: none;
            box-shadow: none;
            border-color: var(--border);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: none; }
        }

        .mini-avatar {
            width: 36px; height: 36px; border-radius: 0.5rem;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700; letter-spacing: -0.3px;
        }
        .mini-avatar.tablet { background: var(--green-s); color: var(--green); font-size: 0.95rem; }
        .mini-avatar.tablet.out { background: var(--red-s); color: var(--red); }

        .mini-name { font-size: 0.78rem; font-weight: 700; color: var(--text); line-height: 1.25; word-break: break-word; }
        .mini-sub { font-size: 0.68rem; color: var(--muted); }

        .mini-status {
            font-size: 0.6rem; font-weight: 700; letter-spacing: 0.3px; text-transform: uppercase;
            padding: 0.12rem 0.45rem; border-radius: 99px;
        }
        .mini-status.ok  { background: var(--green-s); color: var(--green); }
        .mini-status.out { background: var(--red-s); color: var(--red); }

        .mini-details {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
            width: 100%;
        }
        .mini-detail {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            font-size: 0.66rem;
            color: var(--muted);
        }
        .mini-detail i { font-size: 0.6rem; flex-shrink: 0; }
        .mini-detail span { word-break: break-word; }
        .mini-detail.mono span { font-family: 'DM Mono', monospace; }

        .panel-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--muted);
            font-size: 0.82rem;
        }
        .panel-empty i { display: block; font-size: 1.4rem; margin-bottom: 0.5rem; opacity: 0.3; }

        .no-results-mini { display: none; grid-column: 1 / -1; text-align: center; padding: 2rem 1rem; color: var(--muted); font-size: 0.8rem; }
        .no-results-mini i { display: block; font-size: 1.2rem; margin-bottom: 0.4rem; opacity: 0.3; }

        /* Resumen panel */
        .resumen-body {
            padding: 1rem 1.1rem;
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
            min-height: 300px;
        }
        .resumen-empty {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--muted);
            gap: 0.6rem;
            padding: 2rem 1rem;
        }
        .resumen-empty i { font-size: 1.8rem; opacity: 0.3; }
        .resumen-empty p { font-size: 0.85rem; max-width: 220px; }

        .resumen-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-height: 460px;
            overflow-y: auto;
        }

        .resumen-group {
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 0.6rem;
            padding: 0.7rem 0.8rem;
            animation: cardIn 0.2s ease both;
        }

        .resumen-group-header {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin-bottom: 0.55rem;
        }

        .resumen-avatar {
            width: 30px; height: 30px; border-radius: 0.45rem;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.68rem; font-weight: 700; letter-spacing: -0.3px;
            flex-shrink: 0;
        }

        .resumen-group-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text);
            flex: 1;
            word-break: break-word;
        }

        .resumen-remove-group {
            width: 26px; height: 26px; border-radius: 0.4rem;
            border: 1.5px solid var(--border); background: var(--surface);
            color: var(--muted); display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 0.7rem; transition: all 0.14s; flex-shrink: 0;
        }
        .resumen-remove-group:hover { background: var(--red-s); border-color: var(--red-b); color: var(--red); }

        .resumen-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
        }

        .resumen-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 99px;
            padding: 0.28rem 0.4rem 0.28rem 0.65rem;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--text2);
        }
        .resumen-chip i.fa-tablet-alt { color: var(--green); font-size: 0.68rem; }

        .resumen-chip-remove {
            width: 18px; height: 18px; border-radius: 50%;
            border: none; background: var(--surface2);
            color: var(--muted); display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 0.6rem; transition: all 0.14s; flex-shrink: 0;
        }
        .resumen-chip-remove:hover { background: var(--red-s); color: var(--red); }

        .resumen-actions {
            padding-top: 0.9rem;
            border-top: 1px solid var(--border);
            margin-top: 0.3rem;
        }

        .btn-guardar-retiro {
            width: 100%;
            justify-content: center;
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }
        .btn-guardar-retiro:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
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
                            <i class="fas fa-home"></i>
                            <a href="{{ route('home') }}">Inicio</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('tabletas.index') }}">Tablets</a>
                            <i class="fas fa-chevron-right"></i>
                            Retiro
                        </div>
                        <h1 class="ph-title">Registrar <em>Retiro</em></h1>
                        <p class="ph-sub">Seleccioná el usuario y la tableta a retirar</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('tabletas.index') }}" class="btn">
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

                <div class="retiro-grid">

                    {{-- Usuarios --}}
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-header-left">
                                <div class="panel-header-icon"><i class="fas fa-users"></i></div>
                                <span class="panel-header-text">Usuarios</span>
                            </div>
                            <span class="panel-header-count">{{ $usuarios->count() }}</span>
                        </div>
                        <div class="panel-search">
                            <div class="search-wrap">
                                <i class="fas fa-search"></i>
                                <input type="text" id="search-usuarios" class="search-bar" placeholder="Buscar usuario…" autocomplete="off">
                            </div>
                        </div>
                        <div class="panel-body" id="usuarios-grid">
                            @forelse($usuarios as $usuario)
                            @php
                                $nombreMostrar = $usuario->nombre_completo ?: $usuario->nombre;
                                $initials      = mb_strtoupper(mb_substr($nombreMostrar, 0, 2));
                            @endphp
                            <div class="mini-card" data-id="{{ $usuario->id }}" data-nombre="{{ $nombreMostrar }}" data-search="{{ strtolower($usuario->nombre . ' ' . ($usuario->nombre_completo ?? '')) }}">
                                <div class="mini-avatar">{{ $initials }}</div>
                                <div class="mini-name">{{ $nombreMostrar }}</div>
                            </div>
                            @empty
                            <div class="panel-empty">
                                <i class="fas fa-users"></i>
                                No hay usuarios activos.
                            </div>
                            @endforelse
                            <div class="no-results-mini" id="no-results-usuarios">
                                <i class="fas fa-search"></i>
                                Sin resultados.
                            </div>
                        </div>
                    </div>

                    {{-- Tabletas --}}
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-header-left">
                                <div class="panel-header-icon green"><i class="fas fa-tablet-alt"></i></div>
                                <span class="panel-header-text">Tablets</span>
                            </div>
                            <span class="panel-header-count">{{ $tabletas->count() }}</span>
                        </div>
                        <div class="panel-search">
                            <div class="search-wrap">
                                <i class="fas fa-search"></i>
                                <input type="text" id="search-tabletas" class="search-bar" placeholder="Buscar tableta…" autocomplete="off">
                            </div>
                        </div>
                        <div class="panel-body" id="tabletas-grid">
                            @forelse($tabletas as $tableta)
                            @php
                                $ultimoUso     = $tabletausos->where('tableta_id', $tableta->id)->sortByDesc('id')->first();
                                $sinDevolucion = $ultimoUso && $ultimoUso->aprobado == 1 && (!$ultimoUso->fecha_devolucion || !$ultimoUso->aprobacion_devolucion);
                            @endphp
                            <div class="mini-card{{ $sinDevolucion ? ' unavailable' : '' }}"
                                 data-id="{{ $tableta->id }}"
                                 data-clave="{{ $tableta->clave }}"
                                 data-nombre="{{ $tableta->nombre }}"
                                 data-disponible="{{ $sinDevolucion ? '0' : '1' }}"
                                 data-search="{{ strtolower($tableta->clave . ' ' . $tableta->nombre . ' ' . ($tableta->serie ?? '') . ' ' . ($tableta->sim ?? '') . ' ' . ($tableta->observacion ?? '')) }}">
                                <div class="mini-avatar tablet {{ $sinDevolucion ? 'out' : '' }}"><i class="fas fa-tablet-alt"></i></div>
                                <div class="mini-name">{{ $tableta->clave }}</div>
                                <div class="mini-sub">{{ $tableta->nombre }}</div>
                                <span class="mini-status {{ $sinDevolucion ? 'out' : 'ok' }}">
                                    {{ $sinDevolucion ? 'En uso' : 'Disponible' }}
                                </span>
                                @if(!empty($tableta->serie) || !empty($tableta->sim) || !empty($tableta->observacion))
                                <div class="mini-details">
                                    @if(!empty($tableta->serie))
                                    <div class="mini-detail mono">
                                        <i class="fas fa-barcode"></i>
                                        <span>{{ $tableta->serie }}</span>
                                    </div>
                                    @endif
                                    @if(!empty($tableta->sim))
                                    <div class="mini-detail mono">
                                        <i class="fas fa-sim-card"></i>
                                        <span>{{ $tableta->sim }}</span>
                                    </div>
                                    @endif
                                    @if(!empty($tableta->observacion))
                                    <div class="mini-detail">
                                        <i class="fas fa-comment-alt"></i>
                                        <span>{{ Str::limit($tableta->observacion, 40) }}</span>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="panel-empty">
                                <i class="fas fa-tablet-alt"></i>
                                No hay tablets registradas.
                            </div>
                            @endforelse
                            <div class="no-results-mini" id="no-results-tabletas">
                                <i class="fas fa-search"></i>
                                Sin resultados.
                            </div>
                        </div>
                    </div>

                    {{-- Resumen --}}
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-header-left">
                                <div class="panel-header-icon slate"><i class="fas fa-clipboard-list"></i></div>
                                <span class="panel-header-text">Resumen</span>
                            </div>
                        </div>
                        <div class="resumen-body" id="resumen-body">
                            <div class="resumen-empty">
                                <i class="fas fa-clipboard-list"></i>
                                <p>Seleccioná un usuario y una tableta para ver el resumen del retiro.</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function wireSearch(inputId, gridId, noResId) {
        const input  = document.getElementById(inputId);
        const grid   = document.getElementById(gridId);
        const noRes  = document.getElementById(noResId);
        if (!input || !grid) return;
        const cards = grid.querySelectorAll('.mini-card');

        input.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            let vis = 0;
            cards.forEach(card => {
                const show = (card.dataset.search || '').includes(q);
                card.style.display = show ? '' : 'none';
                if (show) vis++;
            });
            if (noRes) noRes.style.display = (!vis && cards.length && q) ? 'block' : 'none';
        });
    }

    wireSearch('search-usuarios', 'usuarios-grid', 'no-results-usuarios');
    wireSearch('search-tabletas', 'tabletas-grid', 'no-results-tabletas');

    const usuariosGrid = document.getElementById('usuarios-grid');
    const tabletasGrid = document.getElementById('tabletas-grid');
    const resumenBody  = document.getElementById('resumen-body');

    let grupos = [];
    let activeUserId = null;

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str == null ? '' : String(str);
        return div.innerHTML;
    }

    function getGrupo(usuarioId) {
        return grupos.find(g => g.usuarioId === usuarioId);
    }

    function actualizarEstadoTabletas() {
        tabletasGrid.querySelectorAll('.mini-card').forEach(card => {
            const id = card.dataset.id;
            const grupo = grupos.find(g => g.tabletas.some(t => t.id === id));
            card.classList.toggle('assigned', !!grupo);
            if (grupo) {
                card.title = 'Asignada a ' + grupo.usuarioNombre;
            } else {
                card.removeAttribute('title');
            }
        });
    }

    function renderResumen() {
        if (!grupos.length) {
            resumenBody.innerHTML =
                '<div class="resumen-empty">' +
                    '<i class="fas fa-clipboard-list"></i>' +
                    '<p>Seleccioná un usuario y una tableta para ver el resumen del retiro.</p>' +
                '</div>';
            return;
        }

        let html = '<div class="resumen-list">';
        grupos.forEach(g => {
            html += '<div class="resumen-group">' +
                        '<div class="resumen-group-header">' +
                            '<div class="resumen-avatar">' + escapeHtml(g.usuarioIniciales) + '</div>' +
                            '<div class="resumen-group-name">' + escapeHtml(g.usuarioNombre) + '</div>' +
                            '<button type="button" class="resumen-remove-group" data-user-id="' + g.usuarioId + '" title="Quitar usuario">' +
                                '<i class="fas fa-trash"></i>' +
                            '</button>' +
                        '</div>' +
                        '<div class="resumen-chips">';
            g.tabletas.forEach(t => {
                html += '<span class="resumen-chip">' +
                            '<i class="fas fa-tablet-alt"></i> ' + escapeHtml(t.clave) + ' — ' + escapeHtml(t.nombre) +
                            '<button type="button" class="resumen-chip-remove" data-user-id="' + g.usuarioId + '" data-tableta-id="' + t.id + '" title="Quitar tableta">' +
                                '<i class="fas fa-times"></i>' +
                            '</button>' +
                        '</span>';
            });
            html += '</div></div>';
        });
        html += '</div>' +
                '<div class="resumen-actions">' +
                    '<button type="button" class="btn btn-guardar-retiro" id="btn-guardar-retiro">' +
                        '<i class="fas fa-save"></i> Guardar retiro' +
                    '</button>' +
                '</div>';

        resumenBody.innerHTML = html;

        resumenBody.querySelectorAll('.resumen-remove-group').forEach(btn => {
            btn.addEventListener('click', function () {
                const uid = this.dataset.userId;
                grupos = grupos.filter(g => g.usuarioId !== uid);
                if (activeUserId === uid) {
                    activeUserId = null;
                    usuariosGrid.querySelectorAll('.mini-card').forEach(c => c.classList.remove('active-user'));
                }
                actualizarEstadoTabletas();
                renderResumen();
            });
        });

        resumenBody.querySelectorAll('.resumen-chip-remove').forEach(btn => {
            btn.addEventListener('click', function () {
                const uid = this.dataset.userId;
                const tid = this.dataset.tabletaId;
                const grupo = getGrupo(uid);
                if (grupo) {
                    grupo.tabletas = grupo.tabletas.filter(t => t.id !== tid);
                    if (!grupo.tabletas.length) {
                        grupos = grupos.filter(g => g.usuarioId !== uid);
                    }
                }
                actualizarEstadoTabletas();
                renderResumen();
            });
        });

        const btnGuardar = document.getElementById('btn-guardar-retiro');
        if (btnGuardar) btnGuardar.addEventListener('click', guardarRetiro);
    }

    usuariosGrid.querySelectorAll('.mini-card').forEach(card => {
        card.addEventListener('click', function () {
            const id = this.dataset.id;
            if (activeUserId === id) {
                activeUserId = null;
                this.classList.remove('active-user');
                return;
            }
            usuariosGrid.querySelectorAll('.mini-card').forEach(c => c.classList.remove('active-user'));
            activeUserId = id;
            this.classList.add('active-user');
        });
    });

    tabletasGrid.querySelectorAll('.mini-card').forEach(card => {
        if (card.dataset.disponible === '0') return;

        card.addEventListener('click', function () {
            if (!activeUserId) return;

            const tabletaId = this.dataset.id;
            const yaAsignadaA = grupos.find(g => g.tabletas.some(t => t.id === tabletaId));

            if (yaAsignadaA) {
                if (yaAsignadaA.usuarioId === activeUserId) {
                    yaAsignadaA.tabletas = yaAsignadaA.tabletas.filter(t => t.id !== tabletaId);
                    if (!yaAsignadaA.tabletas.length) {
                        grupos = grupos.filter(g => g.usuarioId !== activeUserId);
                    }
                    actualizarEstadoTabletas();
                    renderResumen();
                }
                return;
            }

            let grupo = getGrupo(activeUserId);
            if (!grupo) {
                const userCard = usuariosGrid.querySelector('.mini-card[data-id="' + activeUserId + '"]');
                grupo = {
                    usuarioId: activeUserId,
                    usuarioNombre: userCard.dataset.nombre,
                    usuarioIniciales: userCard.querySelector('.mini-avatar').textContent.trim(),
                    tabletas: []
                };
                grupos.push(grupo);
            }

            grupo.tabletas.push({
                id: tabletaId,
                clave: this.dataset.clave,
                nombre: this.dataset.nombre
            });

            actualizarEstadoTabletas();
            renderResumen();
        });
    });

    function guardarRetiro() {
        if (!grupos.length) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('tabletas.retiro.store') }}';

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        let idx = 0;
        grupos.forEach(g => {
            g.tabletas.forEach(t => {
                const inputUsuario = document.createElement('input');
                inputUsuario.type = 'hidden';
                inputUsuario.name = 'asignaciones[' + idx + '][usuario_id]';
                inputUsuario.value = g.usuarioId;
                form.appendChild(inputUsuario);

                const inputTableta = document.createElement('input');
                inputTableta.type = 'hidden';
                inputTableta.name = 'asignaciones[' + idx + '][tableta_id]';
                inputTableta.value = t.id;
                form.appendChild(inputTableta);

                idx++;
            });
        });

        document.body.appendChild(form);
        form.submit();
    }
});
</script>
</body>
</html>
