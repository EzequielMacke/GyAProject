<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planos Pendientes</title>
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
            --blue-s:   #e8f0fc;
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
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.25rem;
        }
        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; }
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
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; width: 100%; justify-content: center; }
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; }
        .btn-primary:disabled { opacity: 0.55; cursor: not-allowed; }

        /* ── ALERTS ── */
        .alert { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert-success { background: #e5f6f0; color: #1e9166; border: 1px solid #b6e8d6; }
        .alert-danger  { background: #fff0f0; color: #c0392b; border: 1px solid #f5c2c2; }

        /* ══════════════════════════════
           LAYOUT DE 3 COLUMNAS
        ══════════════════════════════ */
        .aprobar-layout {
            display: grid;
            grid-template-columns: 280px 1fr 320px;
            gap: 1.1rem;
            height: calc(100vh - 210px);
            min-height: 420px;
        }

        .panel {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* ── Panel izquierdo: lista ── */
        .pend-list-header {
            padding: 0.85rem 1.1rem;
            border-bottom: 1.5px solid var(--border);
            background: var(--surface2);
            font-size: 0.72rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.05em;
            flex-shrink: 0;
        }
        .pend-list { overflow-y: auto; flex: 1; }
        .pend-item {
            display: flex; align-items: center; gap: 0.55rem;
            width: 100%;
            padding: 0.75rem 1.1rem;
            border: none; border-bottom: 1px solid var(--border);
            background: none; cursor: pointer; text-align: left;
            transition: background 0.12s;
        }
        .pend-item:last-child { border-bottom: none; }
        .pend-item:hover { background: var(--surface2); }
        .pend-item.active { background: var(--accent-s); border-left: 3px solid var(--accent); padding-left: calc(1.1rem - 3px); }
        .pend-item i { color: #c0392b; font-size: 0.9rem; flex-shrink: 0; }
        .pend-item.active i { color: var(--accent); }
        .pend-item-name { font-size: 0.82rem; font-weight: 500; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        .pend-empty { padding: 2.5rem 1.25rem; text-align: center; color: var(--muted); font-size: 0.83rem; }
        .pend-empty i { display: block; font-size: 1.6rem; opacity: 0.3; margin-bottom: 0.6rem; }

        /* ── Panel central: PDF ── */
        .pdf-panel { position: relative; align-items: stretch; justify-content: stretch; }
        .pdf-canvas-wrap {
            width: 100%; height: 100%; display: none;
            align-items: center; justify-content: center;
            overflow: auto; background: #e4e9f0;
        }
        .pdf-canvas-wrap canvas { box-shadow: 0 0 16px rgba(0,0,0,0.15); }
        .pdf-toolbar { position: absolute; top: 10px; right: 10px; z-index: 5; display: none; gap: 0.4rem; }
        .pdf-rot-btn {
            width: 34px; height: 34px; border-radius: 0.5rem;
            border: 1.5px solid var(--border); background: #fff; color: var(--text2);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.12);
            transition: all 0.14s;
        }
        .pdf-rot-btn:hover { background: var(--surface2); color: var(--text); border-color: var(--border2); }
        .pdf-empty-state { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--muted); font-size: 0.85rem; text-align: center; padding: 1.5rem; }
        .pdf-empty-state i { font-size: 2.2rem; opacity: 0.25; margin-bottom: 0.75rem; }

        /* ── Panel derecho: form ── */
        .form-panel-body { padding: 1.25rem; overflow-y: auto; flex: 1; }
        .form-panel-grupo {
            font-size: 0.95rem; font-weight: 800; color: var(--accent);
            background: var(--accent-s);
            border: 1.5px solid #c5d9f7;
            border-radius: 0.65rem;
            padding: 0.7rem 0.9rem;
            margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 0.55rem;
        }
        .form-panel-grupo i { color: var(--accent); font-size: 1rem; }
        .form-panel-grupo-label { font-size: 0.65rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.1rem; }
        .form-group { margin-bottom: 1.1rem; }
        .form-group:last-child { margin-bottom: 0; }
        .form-label { font-size: 0.78rem; font-weight: 700; color: var(--text2); margin-bottom: 0.4rem; display: block; text-transform: uppercase; letter-spacing: 0.04em; }
        .form-label span { color: #c0392b; margin-left: 2px; }
        .form-control {
            width: 100%; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.55rem 0.85rem; color: var(--text);
            outline: none; transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .form-control.error { border-color: #e74c3c; }
        .form-control:disabled { background: var(--surface2); color: var(--muted); }

        /* ── Autocomplete subgrupo ── */
        .grupo-autocomplete { position: relative; }
        .grupo-sugerencias {
            display: none;
            position: absolute; left: 0; right: 0; top: calc(100% + 4px);
            background: #fff; border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            max-height: 160px; overflow-y: auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            z-index: 10;
        }
        .grupo-autocomplete.open .grupo-sugerencias { display: block; }
        .grupo-sugerencia {
            padding: 0.55rem 0.85rem;
            font-size: 0.84rem; color: var(--text2);
            cursor: pointer;
            transition: background 0.1s;
        }
        .grupo-sugerencia:hover { background: var(--surface2); color: var(--text); }
        .grupo-sugerencia.hidden { display: none; }

        /* ── Sugerencias de solo lectura (nombre de plano) ── */
        .grupo-sugerencia.solo-lectura { cursor: default; color: var(--muted); font-style: italic; }
        .grupo-sugerencia.solo-lectura:hover { background: none; color: var(--muted); }

        .field-hint { font-size: 0.74rem; color: var(--muted); margin-top: 0.4rem; }
        .field-error { font-size: 0.74rem; color: #c0392b; margin-top: 0.4rem; display: none; }
        .field-error.visible { display: block; }

        .form-panel-foot { padding: 1.1rem 1.25rem; border-top: 1.5px solid var(--border); flex-shrink: 0; }

        @media (max-width: 1100px) {
            .aprobar-layout { grid-template-columns: 240px 1fr 280px; }
        }
        @media (max-width: 860px) {
            .aprobar-layout { grid-template-columns: 1fr; height: auto; }
            .pend-list-panel, .pdf-panel, .form-panel { height: 420px; }
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
                            <a href="{{ route('planos_tc.index', $obraTc->id) }}">Planos</a>
                            <i class="fas fa-chevron-right"></i>
                            Pendientes
                        </div>
                        <h1 class="ph-title"><em>Planos Pendientes</em></h1>
                        <p class="ph-sub" id="contador-pendientes">{{ $pendientes->count() }} {{ $pendientes->count() === 1 ? 'plano por clasificar' : 'planos por clasificar' }}</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('planos_tc.index', $obraTc->id) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div id="alerta-guardado"></div>

                <div class="aprobar-layout">

                    {{-- IZQUIERDA: lista de pendientes --}}
                    <div class="panel pend-list-panel">
                        <div class="pend-list-header">Pendientes</div>
                        <div class="pend-list" id="pend-list">
                            @forelse($pendientes as $p)
                            <button type="button" class="pend-item" id="pend-item-{{ $p->id }}" onclick="seleccionarPendiente({{ $p->id }})">
                                <i class="fas fa-file-pdf"></i>
                                <span class="pend-item-name" title="{{ $p->archivo_original ?? $p->archivo }}">{{ $p->archivo_original ?? $p->archivo }}</span>
                            </button>
                            @empty
                            <div class="pend-empty" id="pend-empty">
                                <i class="fas fa-check-circle"></i>
                                No hay planos pendientes.
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- CENTRO: visor de PDF --}}
                    <div class="panel pdf-panel">
                        <div class="pdf-toolbar" id="pdf-toolbar">
                            <button type="button" class="pdf-rot-btn" id="btn-rot-izq" title="Rotar a la izquierda"><i class="fas fa-undo"></i></button>
                            <button type="button" class="pdf-rot-btn" id="btn-rot-der" title="Rotar a la derecha"><i class="fas fa-redo"></i></button>
                        </div>
                        <div class="pdf-canvas-wrap" id="pdf-canvas-wrap">
                            <canvas id="pdf-canvas"></canvas>
                        </div>
                        <div class="pdf-empty-state" id="pdf-empty-state">
                            <i class="fas fa-file-pdf"></i>
                            Seleccioná un plano de la lista para verlo acá.
                        </div>
                    </div>

                    {{-- DERECHA: formulario --}}
                    <div class="panel form-panel">
                        <div class="pend-list-header">Completar datos</div>
                        <form id="form-aprobar">
                            @csrf
                            <div class="form-panel-body">
                                <div class="form-panel-grupo" id="form-panel-grupo" style="display:none">
                                    <i class="fas fa-layer-group"></i>
                                    <div>
                                        <span class="form-panel-grupo-label">Grupo</span>
                                        <span id="form-panel-grupo-nombre"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="input-nombre-plano">Nombre del plano <span>*</span></label>
                                    <div class="grupo-autocomplete" id="nombre-autocomplete">
                                        <input type="text" id="input-nombre-plano" class="form-control" placeholder="Ej: Fachada principal" autocomplete="off" disabled>
                                        <div class="grupo-sugerencias" id="nombre-sugerencias"></div>
                                    </div>
                                    <div class="field-hint">Se muestran los nombres ya usados en esta obra, a modo de referencia.</div>
                                    <div class="field-error" id="nombre-error">Ya existe un plano con ese nombre en esta obra.</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="input-subgrupo">Subgrupo <span>*</span></label>
                                    <div class="grupo-autocomplete" id="subgrupo-autocomplete">
                                        <input type="text" id="input-subgrupo" class="form-control" placeholder="Ej: Nivel 1" autocomplete="off" disabled>
                                        <div class="grupo-sugerencias" id="subgrupo-sugerencias"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-panel-foot">
                                <button type="submit" id="btn-guardar-aprobar" class="btn btn-primary" disabled>
                                    <i class="fas fa-check"></i> Guardar y siguiente
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const obraTcId = {{ $obraTc->id }};

    const pendientesData = {
        @foreach($pendientes as $p)
        {{ $p->id }}: {
            archivo: @json($p->archivo),
            grupoId: {{ $p->grupo_id }},
            grupoNombre: @json($p->grupo->descripcion ?? '-')
        },
        @endforeach
    };

    const subgruposObra = {!! json_encode($subgruposObra) !!};
    const nombresPlanosObra = {!! json_encode($nombresPlanosObra) !!};

    let planoActualId = null;

    /* ─── Visor de PDF (canvas + pdf.js) con rotación ── */
    const pdfCanvas = document.getElementById('pdf-canvas');
    const pdfCtx = pdfCanvas.getContext('2d');
    const pdfCanvasWrap = document.getElementById('pdf-canvas-wrap');
    const pdfToolbar = document.getElementById('pdf-toolbar');
    let pdfDocActual = null;
    let rotacionActual = 0;

    async function cargarPdfEnPanel(url) {
        rotacionActual = 0;
        pdfDocActual = await pdfjsLib.getDocument(url).promise;
        await renderizarPaginaPanel();
    }

    async function renderizarPaginaPanel() {
        if (!pdfDocActual) return;
        const pagina = await pdfDocActual.getPage(1);
        const dpr = window.devicePixelRatio || 1;

        const viewportBase = pagina.getViewport({ scale: 1, rotation: rotacionActual });
        const anchoDisponible = Math.max(pdfCanvasWrap.clientWidth - 32, 50);
        const altoDisponible = Math.max(pdfCanvasWrap.clientHeight - 32, 50);
        const escala = Math.min(anchoDisponible / viewportBase.width, altoDisponible / viewportBase.height);

        const viewport = pagina.getViewport({ scale: escala, rotation: rotacionActual });
        const viewportRender = pagina.getViewport({ scale: escala * dpr, rotation: rotacionActual });

        pdfCanvas.width = viewportRender.width;
        pdfCanvas.height = viewportRender.height;
        pdfCanvas.style.width = viewport.width + 'px';
        pdfCanvas.style.height = viewport.height + 'px';

        await pagina.render({ canvasContext: pdfCtx, viewport: viewportRender }).promise;
    }

    document.getElementById('btn-rot-izq').addEventListener('click', () => {
        rotacionActual = (rotacionActual - 90 + 360) % 360;
        renderizarPaginaPanel();
    });
    document.getElementById('btn-rot-der').addEventListener('click', () => {
        rotacionActual = (rotacionActual + 90) % 360;
        renderizarPaginaPanel();
    });
    window.addEventListener('resize', () => renderizarPaginaPanel());

    function seleccionarPendiente(id) {
        planoActualId = id;
        const data = pendientesData[id];
        if (!data) return;

        document.querySelectorAll('.pend-item').forEach(el => el.classList.remove('active'));
        document.getElementById('pend-item-' + id)?.classList.add('active');

        document.getElementById('pdf-empty-state').style.display = 'none';
        pdfCanvasWrap.style.display = 'flex';
        pdfToolbar.style.display = 'flex';
        cargarPdfEnPanel('/storage/planos/' + data.archivo);

        document.getElementById('form-panel-grupo').style.display = 'flex';
        document.getElementById('form-panel-grupo-nombre').textContent = data.grupoNombre;

        const inputNombre = document.getElementById('input-nombre-plano');
        const inputSubgrupo = document.getElementById('input-subgrupo');
        inputNombre.disabled = false;
        inputSubgrupo.disabled = false;
        inputNombre.value = '';
        inputSubgrupo.value = '';
        inputNombre.classList.remove('error');
        inputSubgrupo.classList.remove('error');

        document.getElementById('nombre-error').classList.remove('visible');
        document.getElementById('nombre-autocomplete').classList.remove('open');
        document.getElementById('subgrupo-autocomplete').classList.remove('open');
        actualizarBtnGuardar();
    }

    /* ─── Sugerencias de subgrupo (toda la obra, seleccionables) ── */
    function renderSugerenciasSubgrupo() {
        const cont = document.getElementById('subgrupo-sugerencias');
        cont.innerHTML = '';
        subgruposObra.forEach(nombre => {
            const div = document.createElement('div');
            div.className = 'grupo-sugerencia';
            div.dataset.valor = nombre;
            div.textContent = nombre;
            div.addEventListener('click', () => {
                document.getElementById('input-subgrupo').value = nombre;
                document.getElementById('subgrupo-autocomplete').classList.remove('open');
                actualizarBtnGuardar();
            });
            cont.appendChild(div);
        });
    }

    function filtrarSugerenciasSubgrupo(valor) {
        const wrap = document.getElementById('subgrupo-autocomplete');
        const q = valor.trim().toLowerCase();
        const opciones = Array.from(document.querySelectorAll('#subgrupo-sugerencias .grupo-sugerencia'));
        let hay = false;
        opciones.forEach(op => {
            const match = q.length === 0 || op.dataset.valor.toLowerCase().includes(q);
            op.classList.toggle('hidden', !match);
            if (match) hay = true;
        });
        wrap.classList.toggle('open', hay && opciones.length > 0);
    }

    /* ─── Sugerencias de nombre (toda la obra, solo referencia) ── */
    function renderSugerenciasNombre() {
        const cont = document.getElementById('nombre-sugerencias');
        cont.innerHTML = '';
        nombresPlanosObra.forEach(nombre => {
            const div = document.createElement('div');
            div.className = 'grupo-sugerencia solo-lectura';
            div.dataset.valor = nombre;
            div.textContent = nombre;
            cont.appendChild(div);
        });
    }

    function filtrarSugerenciasNombre(valor) {
        const wrap = document.getElementById('nombre-autocomplete');
        const q = valor.trim().toLowerCase();
        const opciones = Array.from(document.querySelectorAll('#nombre-sugerencias .grupo-sugerencia'));
        let hay = false;
        let duplicado = false;
        opciones.forEach(op => {
            const valorOp = op.dataset.valor.toLowerCase();
            const match = q.length === 0 || valorOp.includes(q);
            op.classList.toggle('hidden', !match);
            if (match) hay = true;
            if (q.length > 0 && valorOp === q) duplicado = true;
        });
        wrap.classList.toggle('open', hay);
        document.getElementById('nombre-error').classList.toggle('visible', duplicado);
        document.getElementById('input-nombre-plano').classList.toggle('error', duplicado);
        return duplicado;
    }

    renderSugerenciasSubgrupo();
    renderSugerenciasNombre();

    document.getElementById('input-subgrupo').addEventListener('input', function () {
        this.classList.remove('error');
        filtrarSugerenciasSubgrupo(this.value);
        actualizarBtnGuardar();
    });
    document.getElementById('input-subgrupo').addEventListener('focus', function () {
        filtrarSugerenciasSubgrupo(this.value);
    });
    document.getElementById('input-nombre-plano').addEventListener('input', function () {
        filtrarSugerenciasNombre(this.value);
        actualizarBtnGuardar();
    });
    document.getElementById('input-nombre-plano').addEventListener('focus', function () {
        filtrarSugerenciasNombre(this.value);
    });
    document.addEventListener('click', function (e) {
        const wrapSubgrupo = document.getElementById('subgrupo-autocomplete');
        if (!wrapSubgrupo.contains(e.target)) wrapSubgrupo.classList.remove('open');
        const wrapNombre = document.getElementById('nombre-autocomplete');
        if (!wrapNombre.contains(e.target)) wrapNombre.classList.remove('open');
    });

    function actualizarBtnGuardar() {
        const nombre = document.getElementById('input-nombre-plano').value.trim();
        const subgrupo = document.getElementById('input-subgrupo').value.trim();
        const duplicado = nombresPlanosObra.some(n => n.toLowerCase() === nombre.toLowerCase());
        document.getElementById('btn-guardar-aprobar').disabled = !(planoActualId && nombre.length > 0 && subgrupo.length > 0 && !duplicado);
    }

    document.getElementById('form-aprobar').addEventListener('submit', function (e) {
        e.preventDefault();
        const nombreInput = document.getElementById('input-nombre-plano');
        const subgrupoInput = document.getElementById('input-subgrupo');
        const nombre = nombreInput.value.trim();
        const subgrupo = subgrupoInput.value.trim();
        const duplicado = nombresPlanosObra.some(n => n.toLowerCase() === nombre.toLowerCase());

        if (!nombre || !subgrupo || !planoActualId || duplicado) {
            if (!nombre) nombreInput.classList.add('error');
            if (duplicado) {
                nombreInput.classList.add('error');
                document.getElementById('nombre-error').classList.add('visible');
            }
            if (!subgrupo) subgrupoInput.classList.add('error');
            return;
        }

        const btn = document.getElementById('btn-guardar-aprobar');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando…';

        const idGuardado = planoActualId;

        fetch(`/trabajo-campo/${obraTcId}/planos/${idGuardado}/aprobar`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ nombre: nombre, subgrupo: subgrupo, rotacion: rotacionActual }),
        })
        .then(async r => {
            if (!r.ok) {
                const data = await r.json().catch(() => null);
                const mensaje = data?.errors?.nombre?.[0] || data?.errors?.subgrupo?.[0] || data?.message || 'No se pudo guardar. Intentá de nuevo.';
                throw new Error(mensaje);
            }
            return r.json();
        })
        .then(() => {
            window.location.reload();
        })
        .catch((err) => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Guardar y siguiente';
            nombreInput.classList.add('error');
            document.getElementById('alerta-guardado').innerHTML =
                `<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
        });
    });

    // Auto-seleccionar el primer pendiente al cargar
    (function () {
        const primerId = Object.keys(pendientesData)[0];
        if (primerId) seleccionarPendiente(Number(primerId));
    })();
</script>
</body>
</html>
