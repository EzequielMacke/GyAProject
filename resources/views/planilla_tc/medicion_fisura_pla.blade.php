<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planilla de Medición de Fisuras</title>
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

        /* ── ALERTS ── */
        .alert { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert-info { background: var(--accent-s); color: var(--accent-b); border: 1px solid #b9d3f5; }

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
        .form-control.incompleto,
        .medida-input.incompleto {
            border-color: #e08e0b; background: #fff8ec;
        }
        .form-control.incompleto:focus,
        .medida-input.incompleto:focus {
            border-color: #e08e0b; box-shadow: 0 0 0 3px rgba(224,142,11,0.15);
        }

        /* ── NAVEGACIÓN RÁPIDA DE FISURAS ── */
        .puntos-nav { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.25rem; }
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
        .puntos-nav-btn.drag-over { border-color: var(--accent); background: var(--accent-s); color: var(--accent-b); }
        .punto-card.punto-resaltado { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.15); }

        /* ── FISURAS ── */
        #puntos-list { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1rem; }

        .punto-card {
            background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem;
            overflow: hidden;
            animation: cardIn 0.18s ease both;
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

        /* ── AUTOCOMPLETADO DE NIVEL ── */
        .autocomplete-dropdown {
            display: none;
            position: fixed;
            background: #fff; border: 1.5px solid var(--border); border-radius: 0.55rem;
            box-shadow: 0 10px 28px rgba(0,0,0,0.14);
            max-height: 190px; overflow-y: auto; z-index: 5000;
        }
        .autocomplete-dropdown.active { display: block; }
        .autocomplete-opcion {
            padding: 0.5rem 0.85rem; font-size: 0.83rem; color: var(--text);
            cursor: pointer; transition: background 0.1s;
        }
        .autocomplete-opcion:hover,
        .autocomplete-opcion.resaltada { background: var(--accent-s); color: var(--accent-b); }
        .autocomplete-opcion-nueva { color: var(--muted); font-size: 0.76rem; padding: 0.45rem 0.85rem 0.6rem; border-top: 1px solid var(--border); }

        /* ── MEDIDAS (espesor / profundidad) ── */
        .medidas-bloque { margin-bottom: 1.1rem; }
        .medidas-label {
            font-size: 0.72rem; font-weight: 700; color: var(--text2);
            text-transform: uppercase; letter-spacing: 0.04em;
            margin-bottom: 0.5rem; display: block;
        }
        .medidas-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }
        .medida-item { display: flex; flex-direction: column; gap: 0.3rem; }
        .medida-num { font-size: 0.66rem; font-weight: 700; color: var(--muted); }
        .medida-input {
            width: 100%; text-align: center;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem;
            background: #fff; border: 1.5px solid var(--border);
            border-radius: 0.5rem; padding: 0.5rem 0.4rem; color: var(--text);
            outline: none; transition: border-color 0.15s, box-shadow 0.15s;
            -moz-appearance: textfield;
        }
        .medida-input::-webkit-outer-spin-button,
        .medida-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .medida-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        /* ── FISURA PASANTE ── */
        .pasante-row {
            display: flex; align-items: center; gap: 0.55rem;
            padding: 0.7rem 0.9rem;
            background: var(--surface2); border: 1.5px solid var(--border); border-radius: 0.6rem;
            margin-bottom: 1.1rem;
        }
        .pasante-row input[type="checkbox"] { width: 17px; height: 17px; accent-color: var(--accent); cursor: pointer; }
        .pasante-row label { font-size: 0.82rem; font-weight: 600; color: var(--text2); cursor: pointer; }
        .pasante-row .pasante-hint { font-size: 0.72rem; color: var(--muted); margin-left: auto; }

        /* ── RESULTADOS DEL PUNTO ── */
        .punto-resultados {
            display: flex; flex-wrap: wrap; gap: 1.5rem;
            padding-top: 1.1rem;
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
            .medidas-grid { grid-template-columns: repeat(3, 1fr); }
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
                            Medición de Fisuras
                        </div>
                        <h1 class="ph-title"><em>Planilla de Medición de Fisuras</em></h1>
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

                <form id="form-medicion-fisura">

                    {{-- ═══ DATOS GENERALES ═══ --}}
                    <div class="panel">
                        <div class="panel-title"><i class="fas fa-clipboard-list"></i> Datos generales</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="input-norma">Norma</label>
                                <input type="text" id="input-norma" class="form-control" value="ASTM C597/C597-22" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="input-obra">Obra</label>
                                <input type="text" id="input-obra" class="form-control" value="{{ $obraTc->descripcion }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="input-fecha">Fecha</label>
                                <input type="date" id="input-fecha" name="fecha" class="form-control" value="{{ $datosMedicionFisura['fecha'] ?? now()->format('Y-m-d') }}" @if(! $puedeEditar) readonly @endif>
                            </div>
                        </div>
                    </div>

                    <div class="puntos-nav" id="puntos-nav"></div>

                    {{-- ═══ FISURAS REGISTRADAS ═══ --}}
                    <div class="panel">
                        <div class="panel-title"><i class="fas fa-bolt"></i> Fisuras registradas</div>

                        <div id="puntos-list"></div>

                        <div class="empty-puntos" id="empty-puntos" style="display:none">
                            Todavía no agregaste ninguna fisura.
                        </div>

                        <button type="button" class="btn-volver-inicio" id="btn-volver-inicio">
                            <i class="fas fa-arrow-up"></i> Volver al inicio
                        </button>

                        @if($puedeEditar)
                        <button type="button" class="btn-agregar-punto" id="btn-agregar-punto">
                            <i class="fas fa-plus"></i> Agregar fisura
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
     MODAL ELIMINAR FISURA
══════════════════════════════════════════════════════ --}}
@if($puedeEditar)
<div class="modal-overlay" id="modal-eliminar-punto">
    <div class="modal-caja">
        <div class="modal-head">
            <div class="modal-head-title danger"><i class="fas fa-triangle-exclamation"></i> Eliminar fisura</div>
            <button class="modal-close" onclick="cerrarModalEliminarPunto()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p>¿Seguro que querés eliminar la fisura <strong id="eliminar-punto-nombre"></strong>? Esta acción no se puede deshacer.</p>
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
    let nivelesDisponibles = @json($niveles ?? []);

    /* ─── Autocompletado de nivel ─── (igual que en las demás planillas) */
    const dropdownNivel = document.createElement('div');
    dropdownNivel.className = 'autocomplete-dropdown';
    document.body.appendChild(dropdownNivel);
    let inputNivelActivo = null;

    function posicionarDropdownNivel(input) {
        const rect = input.getBoundingClientRect();
        dropdownNivel.style.left = `${rect.left}px`;
        dropdownNivel.style.top = `${rect.bottom + 4}px`;
        dropdownNivel.style.width = `${rect.width}px`;
    }

    window.addEventListener('scroll', function () {
        if (inputNivelActivo && dropdownNivel.classList.contains('active')) {
            posicionarDropdownNivel(inputNivelActivo);
        }
    }, true);

    function configurarAutocompletadoNivel(card) {
        const input = card.querySelector('.punto-nivel-input');
        if (! input || ! PUEDE_EDITAR) return;

        function mostrarOpciones() {
            inputNivelActivo = input;
            const texto = input.value.trim().toLowerCase();
            const coincidencias = texto === ''
                ? nivelesDisponibles
                : nivelesDisponibles.filter(n => n.toLowerCase().includes(texto));

            dropdownNivel.innerHTML = '';

            coincidencias.slice(0, 8).forEach(function (nivel) {
                const opcion = document.createElement('div');
                opcion.className = 'autocomplete-opcion';
                opcion.textContent = nivel;
                opcion.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    input.value = nivel;
                    dropdownNivel.classList.remove('active');
                    actualizarIncompletos(card);
                    programarGuardado();
                });
                dropdownNivel.appendChild(opcion);
            });

            if (texto !== '' && ! nivelesDisponibles.some(n => n.toLowerCase() === texto)) {
                const aviso = document.createElement('div');
                aviso.className = 'autocomplete-opcion-nueva';
                aviso.textContent = `Se va a crear el nivel "${input.value.trim()}"`;
                dropdownNivel.appendChild(aviso);
            }

            if (dropdownNivel.children.length > 0) {
                posicionarDropdownNivel(input);
                dropdownNivel.classList.add('active');
            } else {
                dropdownNivel.classList.remove('active');
            }
        }

        input.addEventListener('input', function () {
            mostrarOpciones();
            actualizarIncompletos(card);
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                input.blur();
            }
        });
        input.addEventListener('focus', mostrarOpciones);
        input.addEventListener('blur', function () {
            setTimeout(() => {
                if (inputNivelActivo === input) {
                    dropdownNivel.classList.remove('active');
                    inputNivelActivo = null;
                }
            }, 150);
            const valor = input.value.trim();
            if (valor && ! nivelesDisponibles.some(n => n.toLowerCase() === valor.toLowerCase())) {
                nivelesDisponibles.push(valor);
            }
        });
        input.addEventListener('change', function () {
            actualizarIncompletos(card);
            programarGuardado();
        });
    }

    function irAPunto(card) {
        card.scrollIntoView({ behavior: 'smooth', block: 'start' });
        card.classList.add('punto-resaltado');
        setTimeout(() => card.classList.remove('punto-resaltado'), 1200);
    }

    /* ─── Eliminar fisura (con confirmación) ─── */
    const modalEliminarPunto = document.getElementById('modal-eliminar-punto');
    let cardAEliminar = null;

    function abrirModalEliminarPunto(card) {
        cardAEliminar = card;
        document.getElementById('eliminar-punto-nombre').textContent = `F${card.dataset.idx}`;
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

    /* ─── Intercambiar fisuras arrastrando los chips ─── */
    function actualizarPuntosNav() {
        const cards = Array.from(listaPuntos.querySelectorAll('.punto-card'));
        puntosNav.innerHTML = '';
        cards.forEach((card) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'puntos-nav-btn';
            btn.textContent = `F${card.dataset.idx}`;
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
                    puntosNav.querySelectorAll('.drag-over').forEach(el => el.classList.remove('drag-over'));
                    chipArrastrado = null;
                });
                btn.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    if (! chipArrastrado || chipArrastrado === btn) return;
                    btn.classList.add('drag-over');
                });
                btn.addEventListener('dragleave', function () {
                    btn.classList.remove('drag-over');
                });
                btn.addEventListener('drop', function (e) {
                    e.preventDefault();
                    btn.classList.remove('drag-over');
                    if (chipArrastrado && chipArrastrado !== btn) {
                        intercambiarPuntos(chipArrastrado._card, btn._card);
                    }
                });
            }

            btn.addEventListener('click', function () {
                if (! chipArrastrado) irAPunto(card);
            });
            puntosNav.appendChild(btn);
        });
    }

    function intercambiarPuntos(cardA, cardB) {
        if (! cardA || ! cardB || cardA === cardB) return;

        const idxA = cardA.dataset.idx;
        const idxB = cardB.dataset.idx;
        cardA.dataset.idx = idxB;
        cardB.dataset.idx = idxA;

        Array.from(listaPuntos.querySelectorAll('.punto-card'))
            .sort((a, b) => parseInt(a.dataset.idx, 10) - parseInt(b.dataset.idx, 10))
            .forEach(card => listaPuntos.appendChild(card));

        renumerarPuntos();
        programarGuardado();
    }

    /* ─── Numeración de fisuras ─── */
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

    function crearMedidasHTML(idx, grupo, cantidad) {
        let html = '';
        const soloLectura = PUEDE_EDITAR ? '' : 'readonly';
        for (let i = 1; i <= cantidad; i++) {
            html += `
                <div class="medida-item">
                    <span class="medida-num">${i}</span>
                    <input type="number" step="any" class="medida-input ${grupo}-input" name="puntos[${idx}][${grupo}][]" inputmode="decimal" ${soloLectura}>
                </div>
            `;
        }
        return html;
    }

    function crearPuntoHTML(idx) {
        const card = document.createElement('div');
        card.className = 'punto-card';
        card.dataset.idx = idx;
        const soloLectura = PUEDE_EDITAR ? '' : 'readonly';
        const deshabilitado = PUEDE_EDITAR ? '' : 'disabled';
        const botonEliminar = PUEDE_EDITAR
            ? `<button type="button" class="punto-delete-btn" title="Eliminar fisura"><i class="fas fa-trash"></i></button>`
            : '';
        card.innerHTML = `
            <div class="punto-head">
                <div class="punto-badge punto-identificacion">F?</div>
                <div>
                    <div class="punto-head-title">Fisura</div>
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
                        <label class="form-label">Nivel</label>
                        <input type="text" class="form-control punto-nivel-input" name="puntos[${idx}][nivel]" placeholder="Ej: PB, 1° piso..." autocomplete="off" ${soloLectura}>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ancho del elemento</label>
                        <input type="number" step="any" class="form-control punto-ancho-input" name="puntos[${idx}][ancho]" placeholder="0" ${soloLectura}>
                    </div>
                </div>

                <div class="medidas-bloque">
                    <span class="medidas-label">Espesor (3 medidas)</span>
                    <div class="medidas-grid">
                        ${crearMedidasHTML(idx, 'espesor', 3)}
                    </div>
                </div>

                <div class="medidas-bloque">
                    <span class="medidas-label">Profundidad (3 medidas)</span>
                    <div class="medidas-grid">
                        ${crearMedidasHTML(idx, 'profundidad', 3)}
                    </div>
                </div>

                <div class="pasante-row">
                    <input type="checkbox" class="punto-pasante-input" id="pasante-${idx}" name="puntos[${idx}][pasante]" ${deshabilitado}>
                    <label for="pasante-${idx}">Fisura pasante</label>
                    <span class="pasante-hint">Cambia la fórmula del % de sección afectada</span>
                </div>

                <div class="punto-resultados">
                    <div class="resultado-item">
                        <span class="resultado-label">Promedio espesor</span>
                        <span class="resultado-valor resultado-promedio-espesor">—</span>
                    </div>
                    <div class="resultado-item">
                        <span class="resultado-label">Promedio profundidad</span>
                        <span class="resultado-valor resultado-promedio-profundidad">—</span>
                    </div>
                    <div class="resultado-item resultado-final">
                        <span class="resultado-label">% sección afectada</span>
                        <span class="resultado-valor resultado-porcentaje-afectada">—</span>
                    </div>
                </div>
            </div>
        `;

        card.querySelector('.punto-delete-btn')?.addEventListener('click', function () {
            abrirModalEliminarPunto(card);
        });

        card.querySelectorAll('.espesor-input, .profundidad-input, .punto-ancho-input').forEach(function (input) {
            input.addEventListener('input', function () {
                actualizarIncompletos(card);
                recalcularPunto(card);
                programarGuardado();
            });
        });

        card.querySelector('.punto-pasante-input').addEventListener('change', function () {
            recalcularPunto(card);
            programarGuardado();
        });

        card.querySelector('.punto-elemento-input').addEventListener('input', function () {
            actualizarIncompletos(card);
            programarGuardado();
        });

        configurarAutocompletadoNivel(card);

        return card;
    }

    /* ─── Campos incompletos ───────────────────────────────────
       Se marcan en naranja los campos vacíos: elemento, nivel,
       ancho del elemento y cada medida de espesor/profundidad. */
    function actualizarIncompletos(card) {
        card.querySelectorAll(
            '.punto-elemento-input, .punto-nivel-input, .punto-ancho-input, .espesor-input, .profundidad-input'
        ).forEach(function (input) {
            input.classList.toggle('incompleto', input.value.trim() === '');
        });
    }

    /* ─── Cálculo de la fisura ────────────────────────────────
       1) Promedio de espesor: media de las 3 medidas cargadas.
       2) Promedio de profundidad: media de las 3 medidas cargadas.
       3) % sección afectada (siempre contra el ancho del elemento):
          - Si NO es pasante: promedio profundidad / ancho del elemento.
          - Si es pasante: (promedio profundidad × 2) / ancho del elemento. */
    function promediar(valores) {
        return valores.reduce((s, v) => s + v, 0) / valores.length;
    }

    function leerValores(card, selector) {
        return Array.from(card.querySelectorAll(selector))
            .map(input => parseFloat(input.value))
            .filter(v => !isNaN(v));
    }

    function recalcularPunto(card) {
        const espesores = leerValores(card, '.espesor-input');
        const profundidades = leerValores(card, '.profundidad-input');
        const ancho = parseFloat(card.querySelector('.punto-ancho-input').value);
        const pasante = card.querySelector('.punto-pasante-input').checked;

        const elEspesor = card.querySelector('.resultado-promedio-espesor');
        const elProfundidad = card.querySelector('.resultado-promedio-profundidad');
        const elPorcentaje = card.querySelector('.resultado-porcentaje-afectada');

        const promedioEspesor = espesores.length > 0 ? promediar(espesores) : null;
        const promedioProfundidad = profundidades.length > 0 ? promediar(profundidades) : null;

        elEspesor.textContent = promedioEspesor !== null ? promedioEspesor.toFixed(2) : '—';
        elProfundidad.textContent = promedioProfundidad !== null ? promedioProfundidad.toFixed(2) : '—';

        let porcentaje = null;
        if (promedioProfundidad !== null && ! isNaN(ancho) && ancho > 0) {
            porcentaje = pasante
                ? (promedioProfundidad * 2 / ancho) * 100
                : (promedioProfundidad / ancho) * 100;
        }
        elPorcentaje.textContent = porcentaje !== null ? porcentaje.toFixed(2) + ' %' : '—';

        Object.assign(card.dataset, {
            promedioEspesor: promedioEspesor !== null ? promedioEspesor : '',
            promedioProfundidad: promedioProfundidad !== null ? promedioProfundidad : '',
            porcentajeAfectada: porcentaje !== null ? porcentaje : '',
        });
    }

    function renumerarPuntos() {
        const cards = listaPuntos.querySelectorAll('.punto-card');
        cards.forEach((card) => {
            card.querySelector('.punto-identificacion').textContent = `F${card.dataset.idx}`;
        });
        emptyPuntos.style.display = cards.length === 0 ? '' : 'none';
        actualizarPuntosNav();
    }

    function agregarPunto(datos) {
        const idx = obtenerSiguienteIdx();
        const card = crearPuntoHTML(idx);
        if (datos) {
            card.querySelector('.punto-elemento-input').value = datos.elemento || '';
            card.querySelector('.punto-nivel-input').value = datos.nivel || '';
            card.querySelector('.punto-ancho-input').value = datos.ancho ?? '';
            card.querySelector('.punto-pasante-input').checked = !! datos.pasante;
            const espesorInputs = card.querySelectorAll('.espesor-input');
            (datos.espesores || []).forEach((valor, i) => {
                if (espesorInputs[i] && valor !== null && valor !== undefined) {
                    espesorInputs[i].value = valor;
                }
            });
            const profundidadInputs = card.querySelectorAll('.profundidad-input');
            (datos.profundidades || []).forEach((valor, i) => {
                if (profundidadInputs[i] && valor !== null && valor !== undefined) {
                    profundidadInputs[i].value = valor;
                }
            });
        }
        insertarPuntoOrdenado(card);
        recalcularPunto(card);
        actualizarIncompletos(card);
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

    document.getElementById('input-fecha').addEventListener('input', programarGuardado);

    /* ─── Autoguardado ────────────────────────────────────────── */
    const CSRF_TOKEN = @json(csrf_token());
    const URL_GUARDAR = @json(route('planilla_tc.medicion_fisura.guardar', $obraTc->id));
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
            const espesores = Array.from(card.querySelectorAll('.espesor-input')).map(function (input) {
                const valor = parseFloat(input.value);
                return input.value.trim() === '' || isNaN(valor) ? null : valor;
            });
            const profundidades = Array.from(card.querySelectorAll('.profundidad-input')).map(function (input) {
                const valor = parseFloat(input.value);
                return input.value.trim() === '' || isNaN(valor) ? null : valor;
            });
            return {
                elemento: card.querySelector('.punto-elemento-input').value || null,
                nivel: card.querySelector('.punto-nivel-input').value || null,
                ancho: card.querySelector('.punto-ancho-input').value || null,
                espesores: espesores,
                profundidades: profundidades,
                pasante: card.querySelector('.punto-pasante-input').checked,
                promedio_espesor: card.dataset.promedioEspesor || null,
                promedio_profundidad: card.dataset.promedioProfundidad || null,
                porcentaje_afectado: card.dataset.porcentajeAfectada || null,
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
            fecha: document.getElementById('input-fecha').value || null,
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

    // Precarga la planilla existente, o arranca con una primera fisura vacía.
    const datosIniciales = @json($datosMedicionFisura);

    if (datosIniciales && datosIniciales.puntos.length > 0) {
        datosIniciales.puntos.forEach(function (punto) {
            agregarPunto(punto);
        });
    } else {
        agregarPunto();
    }
    fijarEstadoGuardado('guardado');

    document.getElementById('form-medicion-fisura').addEventListener('submit', function (e) {
        e.preventDefault();
    });
</script>
</body>
</html>
