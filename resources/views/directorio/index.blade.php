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

        /* ── Delete btn ── */
        .btn-danger-sm {
            height: 30px;
            padding: 0 0.65rem;
            border-radius: 0.4rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.75rem;
            font-weight: 600;
            border: 1.5px solid #fca5a5;
            background: #fef2f2;
            color: #dc2626;
            cursor: pointer;
            transition: all 0.13s;
        }
        .btn-danger-sm:hover { background: #dc2626; border-color: #dc2626; color: #fff; }

        /* ── Modal ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.35);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-box {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            padding: 1.75rem;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.15);
            animation: modalIn 0.18s ease;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.96) translateY(8px); }
            to   { opacity: 1; transform: none; }
        }
        .modal-icon {
            width: 44px; height: 44px;
            border-radius: 0.6rem;
            background: #fef2f2;
            color: #dc2626;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        .modal-title { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.35rem; }
        .modal-sub { font-size: 0.82rem; color: var(--muted); margin-bottom: 1.25rem; line-height: 1.5; }
        .modal-actions { display: flex; gap: 0.5rem; justify-content: flex-end; }
        .btn-cancel {
            height: 36px; padding: 0 1rem; border-radius: 0.5rem;
            border: 1.5px solid var(--border); background: var(--surface);
            color: var(--text2); font-size: 0.82rem; font-weight: 600;
            cursor: pointer; transition: all 0.13s;
        }
        .btn-cancel:hover { background: var(--surface2); }
        .btn-confirm-del {
            height: 36px; padding: 0 1rem; border-radius: 0.5rem;
            border: 1.5px solid #dc2626; background: #dc2626;
            color: #fff; font-size: 0.82rem; font-weight: 600;
            cursor: pointer; transition: all 0.13s;
        }
        .btn-confirm-del:hover { background: #b91c1c; border-color: #b91c1c; }
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
                            <a href="{{ route('obras.show', $obra->id) }}">{{ $obra->nombre ?? '-' }}</a>
                            <i class="fas fa-chevron-right"></i>
                            Directorio
                        </div>
                        <h1 class="ph-title">Directorio — <em>{{ $obra->nombre ?? '-' }}</em></h1>
                        <p class="ph-sub">Usuarios con acceso asignado a esta obra</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar usuario…" autocomplete="off">
                        </div>
                        @permiso('dir', 'agregar')
                        <a href="{{ route('directorio.create', $obra->id) }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Agregar usuario
                        </a>
                        @endpermiso
                        <a href="{{ route('obras.show', ['id' => $obra->id]) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="dir-table-wrap">
                    <table class="dir-table" id="directorio-table">
                        <thead>
                            <tr>
                                <th class="row-num">#</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($directorios as $directorio)
                            @php $initials = mb_strtoupper(mb_substr($directorio->usuario->nombre ?? '-', 0, 2)); @endphp
                            <tr>
                                <td class="row-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">{{ $initials }}</div>
                                        <span class="user-name">{{ $directorio->usuario->nombre ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-cell">
                                        <i class="far fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($directorio->fecha)->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td style="text-align:right;">
                                    @permiso('dir', 'eliminar')
                                    <button type="button" class="btn-danger-sm"
                                        onclick="confirmarEliminar({{ $directorio->id }}, '{{ addslashes($directorio->usuario->nombre ?? '-') }}')">
                                        <i class="fas fa-user-minus"></i> Quitar
                                    </button>
                                    @endpermiso
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row">
                                <td colspan="4">
                                    <i class="fas fa-users"></i>
                                    No hay usuarios en el directorio.
                                </td>
                            </tr>
                            @endforelse

                            <tr class="no-results-row" id="no-results">
                                <td colspan="4">
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

{{-- Modal confirmar eliminación --}}
<div class="modal-overlay" id="modal-eliminar">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-user-minus"></i></div>
        <div class="modal-title">Quitar usuario</div>
        <div class="modal-sub" id="modal-msg">¿Seguro que querés quitar a este usuario del directorio?</div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
            <button class="btn-confirm-del" onclick="ejecutarEliminar()">Sí, quitar</button>
        </div>
    </div>
</div>

<form id="form-eliminar" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

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

let _directorioId = null;

function confirmarEliminar(id, nombre) {
    _directorioId = id;
    document.getElementById('modal-msg').textContent = `¿Seguro que querés quitar a "${nombre}" del directorio?`;
    document.getElementById('modal-eliminar').classList.add('active');
}

function cerrarModal() {
    document.getElementById('modal-eliminar').classList.remove('active');
    _directorioId = null;
}

function ejecutarEliminar() {
    if (!_directorioId) return;
    const form = document.getElementById('form-eliminar');
    form.action = `/directorio/{{ $obra->id }}/${_directorioId}`;
    form.submit();
}

document.getElementById('modal-eliminar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
</body>
</html>