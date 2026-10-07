<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuraciones de Obras</title>
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

        .ph-crumb i { font-size: 0.58rem; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }

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

        /* ══════════════════════════════
           PANEL
        ══════════════════════════════ */
        .panel {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .panel-stripe { height: 3px; background: linear-gradient(90deg, var(--accent), #6aaaf5); }

        .panel-header {
            padding: 0.85rem 1.1rem;
            background: var(--bg2);
            border-bottom: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .panel-header-icon {
            width: 26px; height: 26px;
            border-radius: 0.35rem;
            background: var(--accent-s);
            color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.68rem;
            flex-shrink: 0;
        }

        .panel-header-text { font-size: 0.78rem; font-weight: 700; color: var(--text); }

        /* ── Tabs ── */
        .tabs {
            display: flex;
            gap: 0.25rem;
            padding: 0 1.1rem;
            background: var(--bg2);
            border-bottom: 1.5px solid var(--border);
            overflow-x: auto;
        }

        .tab {
            display: inline-flex;
            align-items: center;
            gap: 0.42rem;
            padding: 0.8rem 0.9rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--muted);
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -1.5px;
            cursor: pointer;
            white-space: nowrap;
            transition: color 0.14s, border-color 0.14s;
        }

        .tab i { font-size: 0.72rem; }
        .tab:hover { color: var(--text2); }
        .tab.active { color: var(--accent); border-bottom-color: var(--accent); }

        .tab-pane { display: none; }
        .tab-pane.active { display: block; }

        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }

        /* ── Opciones de notificación ── */
        .notif-option {
            padding: 1.25rem 1.4rem;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .notif-option + .notif-option { border-top: 1px solid var(--border); }

        .notif-info { display: flex; gap: 0.8rem; flex: 1; min-width: 260px; }

        .notif-icon {
            width: 36px; height: 36px;
            border-radius: 0.45rem;
            background: var(--accent-s);
            color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .notif-title { font-size: 0.9rem; font-weight: 700; color: var(--text); }
        .notif-desc { font-size: 0.78rem; color: var(--muted); margin-top: 0.15rem; }

        .chips { display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.75rem; }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.74rem;
            font-weight: 600;
            color: var(--text2);
            background: var(--surface2);
            border: 1px solid var(--border);
            padding: 0.22rem 0.6rem;
            border-radius: 99px;
        }

        .chip i { font-size: 0.6rem; color: var(--muted); }

        .chip-empty {
            font-size: 0.75rem;
            color: var(--muted);
            font-style: italic;
            margin-top: 0.75rem;
        }

        /* ── Modal ── */
        .cfg-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.35);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .cfg-overlay.active { display: flex; }

        .cfg-modal {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            width: 100%;
            max-width: 480px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 16px 40px rgba(0,0,0,0.15);
            animation: modalIn 0.18s ease;
            overflow: hidden;
        }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.96) translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        .cfg-modal-head { padding: 1.25rem 1.4rem 0.9rem; border-bottom: 1px solid var(--border); }
        .cfg-modal-title { font-size: 1rem; font-weight: 700; color: var(--text); }
        .cfg-modal-sub { font-size: 0.78rem; color: var(--muted); margin-top: 0.2rem; }

        .cfg-search {
            margin-top: 0.85rem;
            width: 100%;
            height: 36px;
            padding: 0 0.85rem;
            font-size: 0.83rem;
            color: var(--text);
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 0.5rem;
            outline: none;
        }

        .cfg-search:focus { border-color: var(--accent); background: #fff; box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        .cfg-list { overflow-y: auto; padding: 0.4rem 0.6rem; flex: 1; }

        .cfg-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.8rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background 0.12s;
        }

        .cfg-user:hover { background: var(--surface2); }
        .cfg-user input { width: 16px; height: 16px; accent-color: var(--accent); flex-shrink: 0; cursor: pointer; }
        .cfg-user-name { font-size: 0.85rem; font-weight: 600; color: var(--text); }
        .cfg-user-mail { font-size: 0.74rem; color: var(--muted); }

        .cfg-list-empty { text-align: center; padding: 2rem 1rem; font-size: 0.82rem; color: var(--muted); }
        .cfg-list-empty a { color: var(--accent); }

        .cfg-modal-foot {
            padding: 0.9rem 1.4rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .cfg-count { font-size: 0.76rem; color: var(--muted); font-weight: 500; }
        .cfg-actions { display: flex; gap: 0.5rem; }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            color: var(--muted);
        }

        .empty-state i { font-size: 2rem; display: block; margin-bottom: 0.75rem; opacity: 0.35; }
        .empty-state p { font-size: 0.88rem; }
        .empty-state small { display: block; font-size: 0.75rem; margin-top: 0.3rem; }
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
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('obras.index') }}">Obras</a>
                            <i class="fas fa-chevron-right"></i> Configuraciones
                        </div>
                        <h1 class="ph-title">Configuraciones de <em>obras</em></h1>
                        <p class="ph-sub">Ajustes generales del módulo de obras</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('obras.index') }}" class="btn">
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

                @if ($errors->any())
                <div class="alert alert-danger mb-3" style="border-radius:0.55rem; font-size:0.85rem;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <div class="panel">
                    <div class="panel-stripe"></div>
                    <div class="tabs">
                        <button type="button" class="tab active" data-tab="notificaciones">
                            <i class="fas fa-bell"></i> Notificaciones
                        </button>
                    </div>

                    {{-- Notificaciones --}}
                    <div class="tab-pane active" id="tab-notificaciones">

                        <div class="notif-option">
                            <div class="notif-info">
                                <div class="notif-icon"><i class="fas fa-file-circle-check"></i></div>
                                <div>
                                    <div class="notif-title">Presupuestos aprobados</div>
                                    <div class="notif-desc">Usuarios que reciben un correo cuando se aprueba un presupuesto.</div>

                                    @if($destinatariosPresupuestos->isNotEmpty())
                                        <div class="chips">
                                            @foreach($destinatariosPresupuestos as $dest)
                                                <span class="chip" title="{{ $dest->correo }}">
                                                    <i class="fas fa-envelope"></i>
                                                    {{ $dest->nombre_completo ?: $dest->nombre }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="chip-empty">Ningún usuario seleccionado.</div>
                                    @endif
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="abrirModal('modal-presupuestos')">
                                <i class="fas fa-user-check"></i> Seleccionar usuarios
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- Modal: destinatarios de presupuestos aprobados --}}
<div class="cfg-overlay" id="modal-presupuestos">
    <form class="cfg-modal" method="POST" action="{{ route('obras.config.notificaciones') }}">
        @csrf
        <div class="cfg-modal-head">
            <div class="cfg-modal-title">Presupuestos aprobados</div>
            <div class="cfg-modal-sub">Seleccioná los usuarios que van a recibir el aviso por correo. Solo se muestran usuarios activos con correo registrado.</div>
            @if($usuariosConCorreo->isNotEmpty())
                <input type="text" class="cfg-search" placeholder="Buscar usuario o correo…" autocomplete="off">
            @endif
        </div>

        <div class="cfg-list">
            @forelse($usuariosConCorreo as $u)
                <label class="cfg-user" data-search="{{ strtolower($u->nombre . ' ' . $u->nombre_completo . ' ' . $u->correo) }}">
                    <input type="checkbox" name="usuarios[]" value="{{ $u->id }}"
                           {{ in_array($u->id, $seleccionadosPresupuestos) ? 'checked' : '' }}>
                    <div>
                        <div class="cfg-user-name">{{ $u->nombre_completo ?: $u->nombre }}</div>
                        <div class="cfg-user-mail">{{ $u->correo }}</div>
                    </div>
                </label>
            @empty
                <div class="cfg-list-empty">
                    No hay usuarios con correo registrado.<br>
                    Cargá el correo desde <a href="{{ route('usuarios.index') }}">Usuarios</a>.
                </div>
            @endforelse
        </div>

        <div class="cfg-modal-foot">
            <span class="cfg-count"></span>
            <div class="cfg-actions">
                <button type="button" class="btn" onclick="cerrarModal('modal-presupuestos')">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Guardar
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function abrirModal(id) {
    document.getElementById(id).classList.add('active');
}

function cerrarModal(id) {
    const overlay = document.getElementById(id);
    const form    = overlay.querySelector('form');
    form.reset();
    overlay.querySelectorAll('.cfg-user').forEach(el => el.style.display = '');
    overlay.dispatchEvent(new Event('modal:reset'));
    overlay.classList.remove('active');
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.cfg-overlay').forEach(function (overlay) {
        const search = overlay.querySelector('.cfg-search');
        const count  = overlay.querySelector('.cfg-count');
        const checks = overlay.querySelectorAll('input[type=checkbox]');

        function actualizarContador() {
            const n = overlay.querySelectorAll('input[type=checkbox]:checked').length;
            count.textContent = n === 1 ? '1 seleccionado' : n + ' seleccionados';
        }

        checks.forEach(c => c.addEventListener('change', actualizarContador));
        overlay.addEventListener('modal:reset', () => setTimeout(actualizarContador));
        actualizarContador();

        if (search) {
            search.addEventListener('keydown', e => { if (e.key === 'Enter') e.preventDefault(); });
            search.addEventListener('input', function () {
                const q = this.value.trim().toLowerCase();
                overlay.querySelectorAll('.cfg-user').forEach(function (el) {
                    el.style.display = !q || el.dataset.search.includes(q) ? '' : 'none';
                });
            });
        }

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) cerrarModal(overlay.id);
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.cfg-overlay.active').forEach(o => cerrarModal(o.id));
        }
    });

    const tabs = document.querySelectorAll('.tab');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));

            tab.classList.add('active');
            document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
        });
    });
});
</script>
</body>
</html>
