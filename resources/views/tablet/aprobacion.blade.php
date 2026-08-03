<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobación de Retiros</title>
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

        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: 0.78rem; top: 50%;
            transform: translateY(-50%); color: var(--muted); font-size: 0.72rem; pointer-events: none;
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
        .stat-icon.red { background: var(--red-s); color: var(--red); }
        .stat-icon.blue { background: var(--accent-s); color: var(--accent); }
        .stat-val { font-size: 1.3rem; font-weight: 700; color: var(--text); letter-spacing: -0.5px; line-height: 1; }
        .stat-lbl { font-size: 0.7rem; color: var(--muted); font-weight: 500; margin-top: 0.1rem; }

        .tipo-badge {
            display: inline-flex; align-items: center; gap: 0.32rem;
            font-size: 0.65rem; font-weight: 700;
            letter-spacing: 0.3px; text-transform: uppercase;
            padding: 0.22rem 0.55rem; border-radius: 99px;
        }
        .tipo-badge.retiro     { background: var(--red-s); color: var(--red); }
        .tipo-badge.devolucion { background: var(--accent-s); color: var(--accent); }

        .action-btn.approve-dev { background: var(--accent-s); border-color: var(--accent); color: var(--accent); }
        .action-btn.approve-dev:hover { background: var(--accent); border-color: var(--accent); color: #fff; }

        .dup-warning {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--red);
            margin-top: 0.15rem;
        }
        .dup-warning i { font-size: 0.62rem; }

        /* TABLE */
        .table-wrap {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .apr-table { width: 100%; border-collapse: collapse; }

        .apr-table thead tr {
            background: var(--bg2);
            border-bottom: 1.5px solid var(--border);
        }

        .apr-table th {
            padding: 0.65rem 1.1rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.9px;
            text-transform: uppercase;
            color: var(--muted);
            text-align: left;
        }

        .apr-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
            animation: rowIn 0.22s ease both;
        }
        .apr-table tbody tr:last-child { border-bottom: none; }
        .apr-table tbody tr:hover { background: var(--surface2); }

        @keyframes rowIn {
            from { opacity: 0; transform: translateX(-4px); }
            to   { opacity: 1; transform: none; }
        }

        .apr-table td {
            padding: 0.85rem 1.1rem;
            font-size: 0.855rem;
            color: var(--text2);
            vertical-align: middle;
        }

        .row-num { font-family: 'DM Mono', monospace; font-size: 0.72rem; color: var(--muted); width: 48px; }

        .name-cell { display: flex; align-items: center; gap: 0.65rem; }
        .name-avatar {
            width: 32px; height: 32px;
            border-radius: 0.4rem;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem; font-weight: 700;
            flex-shrink: 0; letter-spacing: -0.3px;
        }
        .name-text { font-size: 0.875rem; font-weight: 600; color: var(--text); }

        .tablet-cell { display: flex; align-items: center; gap: 0.5rem; }
        .tablet-icon {
            width: 32px; height: 32px; border-radius: 0.4rem;
            background: var(--green-s); color: var(--green);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; flex-shrink: 0;
        }
        .tablet-clave { font-size: 0.875rem; font-weight: 600; color: var(--text); }
        .tablet-nombre { font-size: 0.72rem; color: var(--muted); }

        .fecha-cell { display: flex; align-items: center; gap: 0.38rem; font-size: 0.8rem; color: var(--muted); }
        .fecha-cell i { font-size: 0.62rem; }

        .actions-cell { display: flex; align-items: center; gap: 0.5rem; }
        .action-btn {
            height: 32px; padding: 0 0.85rem; border-radius: 0.45rem;
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.76rem; font-weight: 700;
            border: 1.5px solid transparent;
            cursor: pointer; transition: all 0.14s;
        }
        .action-btn.approve { background: var(--green-s); border-color: var(--green-b); color: var(--green); }
        .action-btn.approve:hover { background: var(--green); border-color: var(--green); color: #fff; }
        .action-btn.deny { background: var(--red-s); border-color: var(--red-b); color: var(--red); }
        .action-btn.deny:hover { background: var(--red); border-color: var(--red); color: #fff; }

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

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(20,26,36,0.45);
            align-items: center;
            justify-content: center;
            z-index: 1050;
            padding: 1rem;
        }
        .modal-overlay.open { display: flex; }

        .modal-box {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.9rem;
            padding: 1.75rem;
            max-width: 380px;
            width: 100%;
            text-align: center;
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
            animation: modalIn 0.16s ease both;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: translateY(8px) scale(0.98); }
            to   { opacity: 1; transform: none; }
        }

        .modal-icon {
            width: 48px; height: 48px; border-radius: 50%;
            background: var(--red-s); color: var(--red);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; margin: 0 auto 0.9rem;
        }
        .modal-title { font-size: 1.05rem; font-weight: 700; color: var(--text); margin-bottom: 0.5rem; }
        .modal-text { font-size: 0.83rem; color: var(--muted); line-height: 1.5; margin-bottom: 1.4rem; }
        .modal-actions { display: flex; align-items: center; justify-content: center; gap: 0.6rem; }
        .modal-actions .btn { flex: 1; justify-content: center; }
        .modal-actions .action-btn { flex: 1; justify-content: center; height: 38px; }
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
                            Aprobación
                        </div>
                        <h1 class="ph-title">Aprobación de <em>Retiros</em></h1>
                        <p class="ph-sub">Revisá y aprobá o denegá los retiros pendientes</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar…" autocomplete="off">
                        </div>
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

                @if(session('error'))
                <div class="alert alert-danger mb-3" style="border-radius:0.55rem; font-size:0.85rem;">
                    <i class="fas fa-triangle-exclamation" style="margin-right:0.4rem;"></i>{{ session('error') }}
                </div>
                @endif

                @php
                    $pendRetiros      = $usos->where('tipo', 'retiro')->count();
                    $pendDevoluciones = $usos->where('tipo', 'devolucion')->count();
                @endphp

                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon red"><i class="fas fa-hourglass-half"></i></div>
                        <div>
                            <div class="stat-val">{{ $usos->count() }}</div>
                            <div class="stat-lbl">Pendientes</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red"><i class="fas fa-right-from-bracket"></i></div>
                        <div>
                            <div class="stat-val">{{ $pendRetiros }}</div>
                            <div class="stat-lbl">Retiros</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-right-to-bracket"></i></div>
                        <div>
                            <div class="stat-val">{{ $pendDevoluciones }}</div>
                            <div class="stat-lbl">Devoluciones</div>
                        </div>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="apr-table" id="apr-table">
                        <thead>
                            <tr>
                                <th class="row-num">#</th>
                                <th>Tipo</th>
                                <th>Usuario</th>
                                <th>Tableta</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usos as $uso)
                            @php
                                $nombreUsuario = $uso->usuario
                                    ? ($uso->usuario->nombre_completo ?: $uso->usuario->nombre)
                                    : 'Usuario desconocido';
                                $initials = mb_strtoupper(mb_substr($nombreUsuario, 0, 2));
                            @endphp
                            <tr data-search="{{ strtolower($nombreUsuario . ' ' . ($uso->tableta->clave ?? '') . ' ' . ($uso->tableta->nombre ?? '')) }}"
                                style="animation-delay:{{ $loop->index * 0.03 }}s">
                                <td class="row-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    @if($uso->tipo === 'retiro')
                                    <span class="tipo-badge retiro"><i class="fas fa-right-from-bracket"></i> Retiro</span>
                                    @else
                                    <span class="tipo-badge devolucion"><i class="fas fa-right-to-bracket"></i> Devolución</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="name-cell">
                                        <div class="name-avatar">{{ $initials }}</div>
                                        <span class="name-text">{{ $nombreUsuario }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="tablet-cell">
                                        <div class="tablet-icon"><i class="fas fa-tablet-alt"></i></div>
                                        <div>
                                            <div class="tablet-clave">{{ $uso->tableta->clave ?? '—' }}</div>
                                            <div class="tablet-nombre">{{ $uso->tableta->nombre ?? '' }}</div>
                                            @if($uso->tipo === 'retiro' && !empty($uso->duplicado))
                                            <div class="dup-warning">
                                                <i class="fas fa-triangle-exclamation"></i> Otro retiro pendiente de este equipo
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fecha-cell">
                                        <i class="fas fa-calendar"></i>
                                        @if($uso->tipo === 'retiro')
                                            {{ $uso->fecha_retiro ? \Carbon\Carbon::parse($uso->fecha_retiro)->format('d/m/Y') : '—' }}
                                        @else
                                            {{ $uso->fecha_devolucion ? \Carbon\Carbon::parse($uso->fecha_devolucion)->format('d/m/Y') : '—' }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        @if($uso->tipo === 'retiro')
                                        <form action="{{ route('tabletas.aprobacion.aprobar', $uso->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="action-btn approve">
                                                <i class="fas fa-check"></i> Aprobar
                                            </button>
                                        </form>
                                        <form action="{{ route('tabletas.aprobacion.denegar', $uso->id) }}" method="POST" class="form-denegar">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn deny">
                                                <i class="fas fa-times"></i> Denegar
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('tabletas.aprobacion.aprobarDevolucion', $uso->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="action-btn approve-dev">
                                                <i class="fas fa-check-double"></i> Aprobar devolución
                                            </button>
                                        </form>
                                        <form action="{{ route('tabletas.aprobacion.denegarDevolucion', $uso->id) }}" method="POST" class="form-denegar-devolucion">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="action-btn deny">
                                                <i class="fas fa-times"></i> Denegar devolución
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row">
                                <td colspan="6">
                                    <i class="fas fa-check-double"></i>
                                    No hay retiros pendientes de aprobación.
                                </td>
                            </tr>
                            @endforelse

                            <tr class="no-results-row" id="no-results">
                                <td colspan="6">
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

{{-- Modal confirmación denegar --}}
<div class="modal-overlay" id="modal-denegar">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-triangle-exclamation"></i></div>
        <h2 class="modal-title">Denegar retiro</h2>
        <p class="modal-text">¿Seguro que querés denegar este retiro? Esta acción elimina el registro y no se puede deshacer.</p>
        <div class="modal-actions">
            <button type="button" class="btn" id="modal-cancelar">Cancelar</button>
            <button type="button" class="action-btn deny" id="modal-confirmar">
                <i class="fas fa-times"></i> Sí, denegar
            </button>
        </div>
    </div>
</div>

{{-- Modal confirmación denegar devolución --}}
<div class="modal-overlay" id="modal-denegar-devolucion">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-triangle-exclamation"></i></div>
        <h2 class="modal-title">Denegar devolución</h2>
        <p class="modal-text">¿Seguro que querés denegar esta devolución? Se eliminará la fecha de devolución registrada, como si nunca se hubiera realizado, y la tableta seguirá en uso.</p>
        <div class="modal-actions">
            <button type="button" class="btn" id="modal-cancelar-dev">Cancelar</button>
            <button type="button" class="action-btn deny" id="modal-confirmar-dev">
                <i class="fas fa-times"></i> Sí, denegar
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search');
    const rows  = document.querySelectorAll('#apr-table tbody tr[data-search]');
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

    const modal          = document.getElementById('modal-denegar');
    const btnCancelar    = document.getElementById('modal-cancelar');
    const btnConfirmar   = document.getElementById('modal-confirmar');
    let formPendiente    = null;

    document.querySelectorAll('.form-denegar').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            formPendiente = form;
            modal.classList.add('open');
        });
    });

    function cerrarModal() {
        modal.classList.remove('open');
        formPendiente = null;
    }

    btnCancelar.addEventListener('click', cerrarModal);
    modal.addEventListener('click', function (e) {
        if (e.target === modal) cerrarModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) cerrarModal();
    });

    btnConfirmar.addEventListener('click', function () {
        if (formPendiente) formPendiente.submit();
    });

    const modalDev        = document.getElementById('modal-denegar-devolucion');
    const btnCancelarDev  = document.getElementById('modal-cancelar-dev');
    const btnConfirmarDev = document.getElementById('modal-confirmar-dev');
    let formPendienteDev  = null;

    document.querySelectorAll('.form-denegar-devolucion').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            formPendienteDev = form;
            modalDev.classList.add('open');
        });
    });

    function cerrarModalDev() {
        modalDev.classList.remove('open');
        formPendienteDev = null;
    }

    btnCancelarDev.addEventListener('click', cerrarModalDev);
    modalDev.addEventListener('click', function (e) {
        if (e.target === modalDev) cerrarModalDev();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modalDev.classList.contains('open')) cerrarModalDev();
    });

    btnConfirmarDev.addEventListener('click', function () {
        if (formPendienteDev) formPendienteDev.submit();
    });
});
</script>
</body>
</html>
