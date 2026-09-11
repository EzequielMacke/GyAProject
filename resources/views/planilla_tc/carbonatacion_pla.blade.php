<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planilla de Carbonatación</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
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
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── PAGE HEADER ── */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex; align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; flex-wrap: wrap; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        /* ── BUTTONS ── */
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
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; }

        /* ── ALERTS ── */
        .alert { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert-info { background: var(--accent-s); color: var(--accent-b); border: 1px solid #b9d3f5; }

        /* ── PANEL GENERAL ── */
        .panel {
            background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem;
            padding: 1.5rem 1.5rem 1.6rem; margin-bottom: 1.25rem;
        }
        .panel-title {
            font-size: 0.95rem; font-weight: 700; color: var(--text);
            display: flex; align-items: center; gap: 0.5rem;
            margin-bottom: 1.1rem;
        }
        .panel-title i { color: var(--accent); }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        .form-group { display: flex; flex-direction: column; }
        .form-label { font-size: 0.72rem; font-weight: 700; color: var(--text2); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.04em; }
        .form-control {
            width: 100%; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem;
            background: #fff; border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.55rem 0.85rem; color: var(--text);
            outline: none; transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .form-control[readonly],
        .form-control:disabled { background: var(--surface2); color: var(--text2); opacity: 1; cursor: default; }

        /* ── CAMPOS INCOMPLETOS ── */
        .form-control.incompleto {
            border-color: #e08e0b; background: #fff8ec;
        }
        .form-control.incompleto:focus {
            border-color: #e08e0b; box-shadow: 0 0 0 3px rgba(224,142,11,0.15);
        }

        /* ── NAVEGACIÓN RÁPIDA DE PUNTOS ── */
        .puntos-nav {
            display: flex; flex-wrap: wrap; gap: 0.5rem;
            margin-bottom: 1.25rem;
        }
        .puntos-nav-btn {
            min-width: 42px; height: 34px; padding: 0 0.6rem;
            border-radius: 0.5rem; border: 1.5px solid var(--border);
            background: var(--surface); color: var(--text2);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.78rem; font-weight: 700;
            cursor: pointer; transition: background 0.14s, border-color 0.14s, color 0.14s, opacity 0.14s;
        }
        .puntos-nav-btn:hover { background: var(--accent-s); border-color: var(--accent); color: var(--accent-b); }
        .puntos-nav-btn[draggable="true"] { cursor: grab; }
        .puntos-nav-btn.dragging { opacity: 0.35; border-style: dashed; cursor: grabbing; }
        .punto-card.punto-resaltado { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.15); }

        /* ── PUNTOS DE ENSAYO ── */
        #puntos-list { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1rem; }

        .punto-card {
            background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem;
            overflow: hidden;
            animation: cardIn 0.18s ease both;
            transition: border-color 0.14s;
        }
        @keyframes cardIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

        .punto-head {
            display: flex; align-items: center; gap: 0.7rem;
            padding: 0.85rem 1.1rem;
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
        }
        .punto-badge {
            width: 34px; height: 34px; border-radius: 0.55rem;
            background: var(--accent-s); color: var(--accent-b);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem; font-weight: 700; flex-shrink: 0;
        }
        .punto-head-title { font-size: 0.85rem; font-weight: 700; color: var(--text); }
        .punto-head-sub { font-size: 0.72rem; color: var(--muted); }
        .punto-delete-btn {
            margin-left: auto; background: none; border: none; cursor: pointer;
            color: var(--muted); font-size: 0.82rem; padding: 0.45rem; border-radius: 0.45rem;
            transition: color 0.14s, background 0.14s; flex-shrink: 0;
        }
        .punto-delete-btn:hover { color: #c0392b; background: #fff0f0; }

        /* ── MODAL ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-caja {
            background: #fff; border-radius: 1rem;
            width: 100%; max-width: 420px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.18);
            overflow: hidden;
            animation: modalIn 0.2s ease both;
        }
        @keyframes modalIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .modal-head {
            padding: 1.4rem 1.75rem 1.2rem;
            border-bottom: 1.5px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-head-title { font-size: 1rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 0.5rem; }
        .modal-head-title.danger i { color: #c0392b; }
        .modal-close { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 1rem; padding: 0.25rem; border-radius: 0.35rem; transition: color 0.14s; }
        .modal-close:hover { color: var(--text); }
        .modal-body { padding: 1.25rem 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 0.6rem; }
        .modal-body p { font-size: 0.83rem; color: var(--muted); padding: 0 0.5rem; }
        .modal-body p strong { color: var(--text); }
        .modal-foot { padding: 1rem 1.75rem 1.4rem; display: flex; justify-content: flex-end; gap: 0.5rem; }
        .btn-cancel { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); cursor: pointer; transition: all 0.14s; }
        .btn-cancel:hover { background: var(--surface2); }
        .btn-confirmar-eliminar {
            height: 36px; padding: 0 1.1rem; border-radius: 0.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600;
            border: 1.5px solid #c0392b; background: #c0392b; color: #fff; cursor: pointer;
            display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.14s;
        }
        .btn-confirmar-eliminar:hover { background: #a93226; border-color: #a93226; }

        .punto-body { padding: 1.1rem; }

        .punto-datos-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.1rem;
        }

        /* ── RESULTADOS DEL PUNTO ── */
        .punto-resultados {
            display: flex; flex-wrap: wrap; gap: 1.5rem;
            margin-top: 1.1rem; padding-top: 1.1rem;
            border-top: 1px solid var(--border);
        }
        .resultado-item { display: flex; flex-direction: column; gap: 0.2rem; }
        .resultado-label { font-size: 0.66rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; }
        .resultado-valor { font-size: 1rem; font-weight: 700; color: var(--text); }
        .resultado-final .resultado-valor { color: var(--green); font-size: 1.1rem; }

        .btn-agregar-punto {
            width: 100%;
            border: 1.5px dashed var(--border2);
            border-radius: 0.85rem;
            background: var(--surface);
            color: var(--accent);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem; font-weight: 700;
            padding: 0.9rem;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            transition: all 0.14s;
        }
        .btn-agregar-punto:hover { background: var(--accent-s); border-color: var(--accent); }

        .btn-volver-inicio {
            width: 100%;
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            background: var(--surface);
            color: var(--text2);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem; font-weight: 700;
            padding: 0.75rem;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            transition: all 0.14s;
            margin-bottom: 0.85rem;
        }
        .btn-volver-inicio:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        /* ── EMPTY STATE ── */
        .empty-puntos {
            text-align: center; padding: 2.5rem 1.5rem;
            color: var(--muted); font-size: 0.85rem;
            border: 1.5px dashed var(--border2); border-radius: 0.85rem;
            margin-bottom: 1rem;
        }

        /* ── ESTADO DE GUARDADO ── */
        .estado-guardado {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.78rem; font-weight: 600; color: var(--muted);
        }
        .estado-guardado i { font-size: 0.72rem; }
        .estado-guardado.pendiente { color: var(--muted); }
        .estado-guardado.guardando { color: var(--accent-b); }
        .estado-guardado.guardado { color: var(--green); }
        .estado-guardado.error { color: #c0392b; }

        /* ── MOBILE ── */
        @media (max-width: 900px) {
            .form-grid { grid-template-columns: repeat(2, 1fr); }
            .punto-datos-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .ph { padding: 1rem 0 0.75rem; gap: 0.75rem; margin-bottom: 1rem; }
            .ph-title { font-size: 1.3rem; }
            .ph-right { width: 100%; }
            .form-grid { grid-template-columns: 1fr; }
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
                            <a href="{{ route('trabajo_campo.index') }}">Trabajo de Campo</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('obras_tc.index', $obraTc->id) }}">{{ $obraTc->descripcion ?? '-' }}</a>
                            <i class="fas fa-chevron-right"></i>
                            Carbonatación
                        </div>
                        <h1 class="ph-title"><em>Planilla de Carbonatación</em></h1>
                        <p class="ph-sub">{{ $obraTc->descripcion ?? '-' }}</p>
                    </div>
                    <div class="ph-right">
                        @if($puedeEditar)
                        <span class="estado-guardado" id="estado-guardado">
                            <i class="fas fa-check"></i> <span id="estado-guardado-texto">Guardado</span>
                        </span>
                        @endif
                        <a href="{{ route('planilla_tc.index', $obraTc->id) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="alert alert-info">
                    <i class="fas fa-circle-info"></i>
                    @if($puedeEditar)
                        Los cambios se guardan automáticamente.
                    @else
                        No tenés permiso para editar esta planilla: solo podés ver los valores cargados.
                    @endif
                </div>

                <form id="form-carbonatacion">

                    {{-- ═══ DATOS GENERALES ═══ --}}
                    <div class="panel">
                        <div class="panel-title"><i class="fas fa-clipboard-list"></i> Datos generales</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="input-obra">Obra</label>
                                <input type="text" id="input-obra" class="form-control" value="{{ $obraTc->descripcion }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="input-fecha">Fecha</label>
                                <input type="date" id="input-fecha" name="fecha" class="form-control" value="{{ $carbonatacion?->fecha?->format('Y-m-d') ?? now()->format('Y-m-d') }}" @if(! $puedeEditar) readonly @endif>
                            </div>
                        </div>
                    </div>

                    <div class="puntos-nav" id="puntos-nav"></div>

                    {{-- ═══ PUNTOS ENSAYADOS ═══ --}}
                    <div class="panel">
                        <div class="panel-title"><i class="fas fa-bullseye"></i> Puntos ensayados</div>

                        <div id="puntos-list"></div>

                        <div class="empty-puntos" id="empty-puntos" style="display:none">
                            Todavía no agregaste ningún punto de ensayo.
                        </div>

                        <button type="button" class="btn-volver-inicio" id="btn-volver-inicio">
                            <i class="fas fa-arrow-up"></i> Volver al inicio
                        </button>

                        @if($puedeEditar)
                        <button type="button" class="btn-agregar-punto" id="btn-agregar-punto">
                            <i class="fas fa-plus"></i> Agregar punto de ensayo
                        </button>
                        @endif
                    </div>

                </form>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL ELIMINAR PUNTO
══════════════════════════════════════════════════════ --}}
@if($puedeEditar)
<div class="modal-overlay" id="modal-eliminar-punto">
    <div class="modal-caja">
        <div class="modal-head">
            <div class="modal-head-title danger"><i class="fas fa-triangle-exclamation"></i> Eliminar punto</div>
            <button class="modal-close" onclick="cerrarModalEliminarPunto()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p>¿Seguro que querés eliminar el punto <strong id="eliminar-punto-nombre"></strong>? Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="cerrarModalEliminarPunto()">Cancelar</button>
            <button type="button" class="btn-confirmar-eliminar" onclick="eliminarPuntoConfirmado()">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
    </div>
</div>
@endif

<script>
    const PUEDE_EDITAR = @json($puedeEditar);
    const listaPuntos = document.getElementById('puntos-list');
    const emptyPuntos = document.getElementById('empty-puntos');
    const puntosNav = document.getElementById('puntos-nav');
    let chipArrastrado = null;

    function irAPunto(card) {
        card.scrollIntoView({ behavior: 'smooth', block: 'start' });
        card.classList.add('punto-resaltado');
        setTimeout(() => card.classList.remove('punto-resaltado'), 1200);
    }

    /* ─── Eliminar punto (con confirmación) ─────────────────────── */
    const modalEliminarPunto = document.getElementById('modal-eliminar-punto');
    let cardAEliminar = null;

    function abrirModalEliminarPunto(card) {
        cardAEliminar = card;
        document.getElementById('eliminar-punto-nombre').textContent = `C${card.dataset.idx}`;
        modalEliminarPunto?.classList.add('active');
    }

    function cerrarModalEliminarPunto() {
        modalEliminarPunto?.classList.remove('active');
        cardAEliminar = null;
    }
    modalEliminarPunto?.addEventListener('click', function (e) {
        if (e.target === this) cerrarModalEliminarPunto();
    });

    function eliminarPuntoConfirmado() {
        if (! cardAEliminar) return;
        cardAEliminar.remove();
        cardAEliminar = null;
        renumerarPuntos();
        programarGuardado();
        cerrarModalEliminarPunto();
    }

    /* ─── Reordenar puntos arrastrando los "cuadritos" ──────────
       Cada chip de la navegación rápida representa un punto y se
       puede arrastrar sobre otro para reordenarlo. Mientras se
       arrastra, los chips se van corriendo en vivo (vista previa);
       al soltar se aplica ese mismo orden a las tarjetas y se
       renumeran todos los puntos de forma secuencial. */
    function actualizarPuntosNav() {
        const cards = Array.from(listaPuntos.querySelectorAll('.punto-card'));
        puntosNav.innerHTML = '';
        cards.forEach((card) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'puntos-nav-btn';
            btn.textContent = `C${card.dataset.idx}`;
            btn._card = card;

            if (PUEDE_EDITAR && cards.length > 1) {
                btn.draggable = true;
                btn.addEventListener('dragstart', function (e) {
                    chipArrastrado = btn;
                    btn.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', '');
                });
                btn.addEventListener('dragend', function () {
                    btn.classList.remove('dragging');
                    chipArrastrado = null;
                });
                btn.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    if (! chipArrastrado || chipArrastrado === btn) return;
                    const chips = Array.from(puntosNav.children);
                    if (chips.indexOf(chipArrastrado) < chips.indexOf(btn)) {
                        btn.after(chipArrastrado);
                    } else {
                        btn.before(chipArrastrado);
                    }
                });
                btn.addEventListener('drop', function (e) {
                    e.preventDefault();
                    aplicarOrdenDeNav();
                });
            }

            btn.addEventListener('click', function () {
                if (! chipArrastrado) irAPunto(card);
            });
            puntosNav.appendChild(btn);
        });

        if (PUEDE_EDITAR) {
            puntosNav.ondragover = function (e) { e.preventDefault(); };
            puntosNav.ondrop = function (e) {
                e.preventDefault();
                if (chipArrastrado) aplicarOrdenDeNav();
            };
        }
    }

    function aplicarOrdenDeNav() {
        const nuevoOrden = Array.from(puntosNav.children).map(btn => btn._card);
        nuevoOrden.forEach(function (card, i) {
            card.dataset.idx = i + 1;
            listaPuntos.appendChild(card);
        });
        renumerarPuntos();
        programarGuardado();
    }

    /* ─── Numeración de puntos ────────────────────────────────
       La identificación de cada punto (C1, C2...) se asigna una
       sola vez al crearlo y no cambia si se eliminan otros puntos.
       Al agregar uno nuevo, se le asigna el primer número libre
       (llenando huecos) y se inserta en la posición que le
       corresponde según ese número, no al final de la lista. */
    function obtenerSiguienteIdx() {
        const usados = Array.from(listaPuntos.querySelectorAll('.punto-card'))
            .map(card => parseInt(card.dataset.idx, 10));
        let idx = 1;
        while (usados.includes(idx)) idx++;
        return idx;
    }

    function insertarPuntoOrdenado(card) {
        const idx = parseInt(card.dataset.idx, 10);
        const siguiente = Array.from(listaPuntos.querySelectorAll('.punto-card'))
            .find(c => parseInt(c.dataset.idx, 10) > idx);
        if (siguiente) {
            listaPuntos.insertBefore(card, siguiente);
        } else {
            listaPuntos.appendChild(card);
        }
    }

    function crearPuntoHTML(idx) {
        const card = document.createElement('div');
        card.className = 'punto-card';
        card.dataset.idx = idx;
        const soloLectura = PUEDE_EDITAR ? '' : 'readonly';
        const botonEliminar = PUEDE_EDITAR
            ? `<button type="button" class="punto-delete-btn" title="Eliminar punto"><i class="fas fa-trash"></i></button>`
            : '';
        card.innerHTML = `
            <div class="punto-head">
                <div class="punto-badge punto-identificacion">C?</div>
                <div>
                    <div class="punto-head-title">Punto de ensayo</div>
                    <div class="punto-head-sub">Identificación automática</div>
                </div>
                ${botonEliminar}
            </div>
            <div class="punto-body">
                <div class="punto-datos-grid">
                    <div class="form-group">
                        <label class="form-label">Elemento</label>
                        <input type="text" class="form-control punto-elemento-input" name="puntos[${idx}][elemento]" placeholder="Ej: Columna, Viga, Losa..." ${soloLectura}>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Recubrimiento (mm)</label>
                        <input type="number" step="any" min="0" class="form-control punto-recubrimiento-input" name="puntos[${idx}][recubrimiento]" inputmode="decimal" ${soloLectura}>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Espesor carbonatado (mm)</label>
                        <input type="number" step="any" min="0" class="form-control punto-espesor-input" name="puntos[${idx}][espesor_carbonatado]" inputmode="decimal" ${soloLectura}>
                    </div>
                </div>
                <div class="punto-resultados">
                    <div class="resultado-item resultado-final">
                        <span class="resultado-label">% afectado por carbonatación</span>
                        <span class="resultado-valor resultado-porcentaje-afectado">—</span>
                    </div>
                </div>
            </div>
        `;

        card.querySelector('.punto-delete-btn')?.addEventListener('click', function () {
            abrirModalEliminarPunto(card);
        });

        card.querySelector('.punto-elemento-input').addEventListener('input', function () {
            actualizarElementoIncompleto(card);
            programarGuardado();
        });

        card.querySelector('.punto-recubrimiento-input').addEventListener('input', function () {
            recalcularPunto(card);
            programarGuardado();
        });

        card.querySelector('.punto-espesor-input').addEventListener('input', function () {
            recalcularPunto(card);
            programarGuardado();
        });

        return card;
    }

    function actualizarElementoIncompleto(card) {
        const input = card.querySelector('.punto-elemento-input');
        input.classList.toggle('incompleto', input.value.trim() === '');
    }

    /* ─── Cálculo del porcentaje afectado por carbonatación ─────
       % afectado = (espesor carbonatado / recubrimiento) × 100. */
    function recalcularPunto(card) {
        const inputRecubrimiento = card.querySelector('.punto-recubrimiento-input');
        const inputEspesor = card.querySelector('.punto-espesor-input');
        const elPorcentaje = card.querySelector('.resultado-porcentaje-afectado');

        inputRecubrimiento.classList.toggle('incompleto', inputRecubrimiento.value.trim() === '');
        inputEspesor.classList.toggle('incompleto', inputEspesor.value.trim() === '');

        const recubrimiento = parseFloat(inputRecubrimiento.value);
        const espesor = parseFloat(inputEspesor.value);

        if (isNaN(recubrimiento) || recubrimiento <= 0 || isNaN(espesor)) {
            elPorcentaje.textContent = '—';
            Object.assign(card.dataset, { porcentajeAfectado: '' });
            return;
        }

        const porcentaje = (espesor / recubrimiento) * 100;
        elPorcentaje.textContent = porcentaje.toFixed(1) + '%';

        Object.assign(card.dataset, { porcentajeAfectado: porcentaje });
    }

    document.getElementById('input-fecha').addEventListener('input', programarGuardado);

    function renumerarPuntos() {
        const cards = listaPuntos.querySelectorAll('.punto-card');
        cards.forEach((card) => {
            card.querySelector('.punto-identificacion').textContent = `C${card.dataset.idx}`;
        });
        emptyPuntos.style.display = cards.length === 0 ? '' : 'none';
        actualizarPuntosNav();
    }

    function agregarPunto(datos) {
        const idx = obtenerSiguienteIdx();
        const card = crearPuntoHTML(idx);
        if (datos) {
            card.querySelector('.punto-elemento-input').value = datos.elemento || '';
            card.querySelector('.punto-recubrimiento-input').value = datos.recubrimiento ?? '';
            card.querySelector('.punto-espesor-input').value = datos.espesor_carbonatado ?? '';
        }
        insertarPuntoOrdenado(card);
        recalcularPunto(card);
        actualizarElementoIncompleto(card);
        renumerarPuntos();
        return card;
    }

    document.getElementById('btn-agregar-punto')?.addEventListener('click', function () {
        agregarPunto();
        programarGuardado();
    });

    document.getElementById('btn-volver-inicio')?.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    /* ─── Autoguardado ────────────────────────────────────────── */
    const CSRF_TOKEN = @json(csrf_token());
    const URL_GUARDAR = @json(route('planilla_tc.carbonatacion.guardar', $obraTc->id));
    const DEMORA_GUARDADO_MS = 900;

    const elEstadoGuardado = document.getElementById('estado-guardado');
    const elEstadoGuardadoTexto = document.getElementById('estado-guardado-texto');
    const ICONOS_ESTADO = {
        pendiente: 'fa-circle-notch',
        guardando: 'fa-circle-notch fa-spin',
        guardado: 'fa-check',
        error: 'fa-triangle-exclamation',
    };
    const TEXTOS_ESTADO = {
        pendiente: 'Cambios sin guardar',
        guardando: 'Guardando...',
        guardado: 'Guardado',
        error: 'Error al guardar',
    };

    function fijarEstadoGuardado(estado) {
        if (! elEstadoGuardado) return;
        elEstadoGuardado.className = 'estado-guardado ' + estado;
        elEstadoGuardado.querySelector('i').className = 'fas ' + ICONOS_ESTADO[estado];
        elEstadoGuardadoTexto.textContent = TEXTOS_ESTADO[estado];
    }

    function recolectarPuntos() {
        return Array.from(listaPuntos.querySelectorAll('.punto-card')).map(function (card) {
            return {
                elemento: card.querySelector('.punto-elemento-input').value || null,
                recubrimiento: card.querySelector('.punto-recubrimiento-input').value || null,
                espesor_carbonatado: card.querySelector('.punto-espesor-input').value || null,
                porcentaje_afectado: card.dataset.porcentajeAfectado || null,
            };
        });
    }

    let temporizadorGuardado = null;
    let guardadoEnCurso = false;
    let guardadoPendiente = false;

    async function guardar() {
        if (guardadoEnCurso) {
            guardadoPendiente = true;
            return;
        }
        guardadoEnCurso = true;
        fijarEstadoGuardado('guardando');

        const payload = {
            fecha: document.getElementById('input-fecha').value,
            puntos: recolectarPuntos(),
        };

        try {
            const respuesta = await fetch(URL_GUARDAR, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });
            if (!respuesta.ok) throw new Error('No se pudo guardar');
            fijarEstadoGuardado('guardado');
        } catch (e) {
            fijarEstadoGuardado('error');
        } finally {
            guardadoEnCurso = false;
            if (guardadoPendiente) {
                guardadoPendiente = false;
                guardar();
            }
        }
    }

    function programarGuardado() {
        if (! PUEDE_EDITAR) return;
        fijarEstadoGuardado('pendiente');
        clearTimeout(temporizadorGuardado);
        temporizadorGuardado = setTimeout(guardar, DEMORA_GUARDADO_MS);
    }

    // Precarga la planilla existente, o arranca con un primer punto vacío.
    const datosIniciales = @json($datosCarbonatacion ?? null);

    if (datosIniciales && datosIniciales.puntos && datosIniciales.puntos.length > 0) {
        datosIniciales.puntos.forEach(function (punto) {
            agregarPunto(punto);
        });
    } else {
        agregarPunto();
    }
    fijarEstadoGuardado('guardado');

    document.getElementById('form-carbonatacion').addEventListener('submit', function (e) {
        e.preventDefault();
    });
</script>
</body>
</html>
