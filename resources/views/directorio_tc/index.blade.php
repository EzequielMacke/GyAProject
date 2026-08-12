<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directorio de la Obra</title>
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
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }
        .btn-primary:disabled { opacity: 0.55; cursor: not-allowed; box-shadow: none; }

        /* ── Alerts ── */
        .alert { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert-success { background: #e5f6f0; color: #1e9166; border: 1px solid #b6e8d6; }
        .alert-danger  { background: #fff0f0; color: #c0392b; border: 1px solid #f5c2c2; }

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
            width: 220px;
            outline: none;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
            height: 38px;
        }

        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus {
            border-color: var(--accent);
            width: 260px;
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }

        /* ══════════════════════════════
           TABLE
        ══════════════════════════════ */
        .dir-table-wrap {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .dir-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dir-table thead tr {
            background: var(--bg2);
            border-bottom: 1.5px solid var(--border);
        }

        .dir-table th {
            padding: 0.65rem 1.1rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.9px;
            text-transform: uppercase;
            color: var(--muted);
            text-align: left;
        }

        .dir-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
            animation: rowIn 0.22s ease both;
        }

        .dir-table tbody tr:last-child { border-bottom: none; }
        .dir-table tbody tr:hover { background: var(--surface2); }

        @keyframes rowIn {
            from { opacity: 0; transform: translateX(-4px); }
            to   { opacity: 1; transform: none; }
        }

        .dir-table td {
            padding: 0.85rem 1.1rem;
            font-size: 0.855rem;
            color: var(--text2);
            vertical-align: middle;
        }

        /* index number */
        .row-num {
            font-family: 'DM Mono', monospace;
            font-size: 0.72rem;
            color: var(--muted);
            width: 48px;
        }

        /* user cell */
        .user-cell {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 0.4rem;
            background: var(--accent-s);
            color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem;
            font-weight: 700;
            flex-shrink: 0;
            letter-spacing: -0.3px;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text);
        }

        /* date cell */
        .date-cell {
            display: flex;
            align-items: center;
            gap: 0.38rem;
            font-size: 0.8rem;
            color: var(--muted);
        }

        .date-cell i { font-size: 0.62rem; }

        /* empty row */
        .empty-row td {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
            font-size: 0.88rem;
        }

        .empty-row i { display: block; font-size: 1.8rem; margin-bottom: 0.6rem; opacity: 0.3; }

        /* no results */
        .no-results-row { display: none; }
        .no-results-row td {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--muted);
            font-size: 0.85rem;
        }

        .no-results-row i { display: block; font-size: 1.3rem; margin-bottom: 0.4rem; opacity: 0.3; }

        /* ══════════════════════════════
           LAYOUT + ACTIVITY PANEL
        ══════════════════════════════ */
        .dir-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.25rem;
            align-items: start;
        }

        .activity-panel {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .activity-header {
            padding: 0.85rem 1.1rem;
            border-bottom: 1.5px solid var(--border);
            background: var(--bg2);
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.78rem; font-weight: 700; color: var(--text2);
        }
        .activity-header i { color: var(--accent); font-size: 0.75rem; }

        .activity-list {
            max-height: 520px;
            overflow-y: auto;
        }

        .activity-item {
            padding: 0.85rem 1.1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.8rem;
            color: var(--text2);
            line-height: 1.5;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-item strong { color: var(--text); font-weight: 700; }
        .activity-item .activity-date {
            display: flex; align-items: center; gap: 0.35rem;
            font-size: 0.72rem; color: var(--muted); margin-top: 0.3rem;
        }
        .activity-item .activity-date i { font-size: 0.62rem; }
        .activity-item.sistema strong { color: var(--muted); }

        .activity-empty { text-align: center; padding: 2.5rem 1.25rem; color: var(--muted); font-size: 0.83rem; }
        .activity-empty i { display: block; font-size: 1.4rem; opacity: 0.3; margin-bottom: 0.5rem; }

        @media (max-width: 900px) {
            .dir-layout { grid-template-columns: 1fr; }
        }

        /* ── Modal ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-nuevo {
            background: #fff; border-radius: 1rem;
            width: 100%; max-width: 460px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.18);
            overflow: hidden;
            animation: modalIn 0.2s ease both;
            display: flex; flex-direction: column;
            height: 560px;
            max-height: 85vh;
        }
        @keyframes modalIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .modal-head {
            padding: 1.4rem 1.75rem 1.2rem;
            border-bottom: 1.5px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            flex-shrink: 0;
        }
        .modal-head-title { font-size: 1rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 0.5rem; }
        .modal-head-title i { color: var(--accent); }
        .modal-close { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 1rem; padding: 0.25rem; border-radius: 0.35rem; transition: color 0.14s; }
        .modal-close:hover { color: var(--text); }
        #form-agregar { display: flex; flex-direction: column; flex: 1; min-height: 0; }
        .modal-body { padding: 1.25rem 1.75rem; overflow-y: auto; flex: 1; min-height: 0; }
        .modal-foot { padding: 1rem 1.75rem 1.4rem; display: flex; justify-content: flex-end; gap: 0.5rem; flex-shrink: 0; border-top: 1.5px solid var(--border); }
        .btn-cancel { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); cursor: pointer; transition: all 0.14s; }
        .btn-cancel:hover { background: var(--surface2); }

        .modal-search { position: relative; margin-bottom: 1rem; }
        .modal-search i { position: absolute; left: 0.78rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 0.72rem; pointer-events: none; }
        .modal-search input {
            width: 100%; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.55rem 0.85rem 0.55rem 2.1rem; color: var(--text);
            outline: none; transition: border-color 0.15s, box-shadow 0.15s;
        }
        .modal-search input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        .user-check-list { display: flex; flex-direction: column; gap: 0.35rem; }
        .user-check {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.55rem 0.7rem;
            border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            cursor: pointer;
            transition: border-color 0.14s, background 0.14s;
        }
        .user-check:hover { background: var(--surface2); }
        .user-check.checked { border-color: var(--accent); background: var(--accent-s); }
        .user-check input { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; flex-shrink: 0; }
        .user-check .user-avatar { width: 28px; height: 28px; font-size: 0.65rem; }
        .user-check .user-name { font-size: 0.84rem; }
        .user-check.hidden { display: none; }
        .modal-empty { text-align: center; padding: 2rem 1rem; color: var(--muted); font-size: 0.83rem; }
        .modal-empty i { display: block; font-size: 1.4rem; margin-bottom: 0.5rem; opacity: 0.3; }
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
                            <a href="{{ route('trabajo_campo.index') }}">Trabajo de Campo</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('obras_tc.index', $obraTc->id) }}">{{ $obraTc->descripcion ?? '-' }}</a>
                            <i class="fas fa-chevron-right"></i>
                            Directorio
                        </div>
                        <h1 class="ph-title">Directorio — <em>{{ $obraTc->descripcion ?? '-' }}</em></h1>
                        <p class="ph-sub">Usuarios con acceso asignado a esta obra</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar usuario…" autocomplete="off">
                        </div>
                        @permiso('dir_tc', 'agregar')
                        <button type="button" class="btn btn-primary" onclick="abrirModalAgregar()">
                            <i class="fas fa-user-plus"></i> Agregar usuario
                        </button>
                        @endpermiso
                        <a href="{{ route('obras_tc.index', $obraTc->id) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
                @endif

                <div class="dir-layout">

                    <div class="dir-table-wrap">
                        <table class="dir-table" id="directorio-table">
                            <thead>
                                <tr>
                                    <th class="row-num">#</th>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($directorios as $directorio)
                                @php
                                    $nombreUsuario = $directorio->usuario->nombre_completo ?: ($directorio->usuario->nombre ?? '-');
                                    $initials = mb_strtoupper(mb_substr($nombreUsuario, 0, 2));
                                @endphp
                                <tr>
                                    <td class="row-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <div class="user-cell">
                                            <div class="user-avatar">{{ $initials }}</div>
                                            <span class="user-name">{{ $nombreUsuario }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            <i class="far fa-calendar"></i>
                                            {{ $directorio->created_at->format('d/m/Y') }}
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="empty-row">
                                    <td colspan="3">
                                        <i class="fas fa-users"></i>
                                        No hay usuarios en el directorio.
                                    </td>
                                </tr>
                                @endforelse

                                <tr class="no-results-row" id="no-results">
                                    <td colspan="3">
                                        <i class="fas fa-search"></i>
                                        Sin resultados para tu búsqueda.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="activity-panel">
                        <div class="activity-header">
                            <i class="fas fa-history"></i> Registro de incorporaciones
                        </div>
                        <div class="activity-list">
                            @forelse($directorios as $directorio)
                            @php
                                $nombreAgregado = $directorio->usuario->nombre_completo ?: ($directorio->usuario->nombre ?? '-');
                                $nombreAgrego = $directorio->agregadoPor
                                    ? ($directorio->agregadoPor->nombre_completo ?: $directorio->agregadoPor->nombre)
                                    : null;
                            @endphp
                            <div class="activity-item {{ $nombreAgrego ? '' : 'sistema' }}">
                                @if($nombreAgrego)
                                    <strong>{{ $nombreAgrego }}</strong> agregó a <strong>{{ $nombreAgregado }}</strong>
                                @else
                                    Usuario <strong>{{ $nombreAgregado }}</strong> agregado automáticamente
                                @endif
                                <div class="activity-date">
                                    <i class="far fa-clock"></i>
                                    {{ $directorio->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            @empty
                            <div class="activity-empty">
                                <i class="fas fa-history"></i>
                                Sin actividad registrada.
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL AGREGAR USUARIO
══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-agregar">
    <div class="modal-nuevo">
        <div class="modal-head">
            <div class="modal-head-title"><i class="fas fa-user-plus"></i> Agregar usuario</div>
            <button class="modal-close" onclick="cerrarModalAgregar()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>

        <form id="form-agregar" method="POST" action="{{ route('directorio_tc.store', $obraTc->id) }}">
            @csrf

            <div class="modal-body">
                <div class="modal-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="modal-search-input" placeholder="Buscar usuario…" autocomplete="off">
                </div>

                <div class="user-check-list" id="user-check-list">
                    @forelse($usuariosDisponibles as $usuario)
                    @php $nombreDisponible = $usuario->nombre_completo ?: $usuario->nombre; @endphp
                    <label class="user-check" data-search="{{ strtolower($nombreDisponible) }}">
                        <input type="checkbox" name="usuarios[]" value="{{ $usuario->id }}" onchange="this.closest('.user-check').classList.toggle('checked', this.checked); actualizarBotonGuardar();">
                        <div class="user-avatar">{{ mb_strtoupper(mb_substr($nombreDisponible, 0, 2)) }}</div>
                        <span class="user-name">{{ $nombreDisponible }}</span>
                    </label>
                    @empty
                    <div class="modal-empty">
                        <i class="fas fa-users"></i>
                        No hay usuarios disponibles para agregar.
                    </div>
                    @endforelse
                    <div class="modal-empty" id="modal-sin-resultados" style="display:none;">
                        <i class="fas fa-search"></i>
                        Sin resultados para tu búsqueda.
                    </div>
                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn-cancel" onclick="cerrarModalAgregar()">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btn-guardar-usuarios" disabled>
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search');
    const rows  = document.querySelectorAll('#directorio-table tbody tr:not(.empty-row):not(.no-results-row)');
    const noRes = document.getElementById('no-results');

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let vis = 0;
        rows.forEach(row => {
            const show = row.textContent.toLowerCase().includes(q);
            row.style.display = show ? '' : 'none';
            if (show) vis++;
        });
        if (noRes) noRes.style.display = (!vis && rows.length && q) ? 'table-row' : 'none';
    });
});

function abrirModalAgregar() {
    document.getElementById('modal-agregar').classList.add('active');
    document.getElementById('modal-search-input').focus();
}

function cerrarModalAgregar() {
    document.getElementById('modal-agregar').classList.remove('active');
}

function actualizarBotonGuardar() {
    const marcados = document.querySelectorAll('#user-check-list input[type="checkbox"]:checked').length;
    document.getElementById('btn-guardar-usuarios').disabled = marcados === 0;
}

document.getElementById('modal-search-input')?.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    const opciones = document.querySelectorAll('#user-check-list .user-check');
    let vis = 0;
    opciones.forEach(op => {
        const show = op.dataset.search.includes(q);
        op.classList.toggle('hidden', !show);
        if (show) vis++;
    });
    const sinResultados = document.getElementById('modal-sin-resultados');
    if (sinResultados) sinResultados.style.display = (!vis && opciones.length) ? '' : 'none';
});

document.getElementById('modal-agregar').addEventListener('click', function (e) {
    if (e.target === this) cerrarModalAgregar();
});

@if($errors->any())
abrirModalAgregar();
@endif
</script>
</body>
</html>
