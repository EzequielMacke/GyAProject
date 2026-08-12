<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planos</title>
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
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap; }
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
        .btn-primary:disabled { opacity: 0.55; cursor: not-allowed; }

        /* ── BOTÓN PENDIENTES ── */
        .btn-pendientes {
            background: #fef9ec; color: #d4920a; border-color: #f5e0a8;
        }
        .btn-pendientes:hover {
            background: #fbeecb; border-color: #eccd7e; color: #d4920a;
        }

        /* ── ALERTS ── */
        .alert { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert-success { background: #e5f6f0; color: #1e9166; border: 1px solid #b6e8d6; }
        .alert-danger  { background: #fff0f0; color: #c0392b; border: 1px solid #f5c2c2; }

        /* ── ÁRBOL GRUPO › SUBGRUPO › PLANOS ── */
        .tree-wrap { background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem; overflow: hidden; }

        .tree-grupo { border-bottom: 1px solid var(--border); }
        .tree-grupo:last-child { border-bottom: none; }
        .tree-grupo > summary {
            list-style: none;
            cursor: pointer;
            padding: 0.9rem 1.25rem;
            display: flex; align-items: center; gap: 0.65rem;
            background: var(--surface2);
            font-size: 0.9rem; font-weight: 700; color: var(--text);
            transition: background 0.12s;
        }
        .tree-grupo > summary::-webkit-details-marker { display: none; }
        .tree-grupo > summary:hover { background: var(--border); }
        .tree-grupo > summary i.chevron { font-size: 0.7rem; color: var(--muted); transition: transform 0.15s; flex-shrink: 0; }
        .tree-grupo[open] > summary i.chevron { transform: rotate(90deg); }
        .tree-grupo > summary i.fa-folder { color: var(--accent); }

        .tree-count {
            margin-left: auto;
            font-size: 0.7rem; font-weight: 700; color: var(--muted);
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 999px; padding: 0.1rem 0.55rem;
        }

        .tree-subgrupo { border-top: 1px solid var(--border); }
        .tree-subgrupo > summary {
            list-style: none;
            cursor: pointer;
            padding: 0.75rem 1.25rem 0.75rem 2.5rem;
            display: flex; align-items: center; gap: 0.6rem;
            font-size: 0.83rem; font-weight: 600; color: var(--text2);
            transition: background 0.12s;
        }
        .tree-subgrupo > summary::-webkit-details-marker { display: none; }
        .tree-subgrupo > summary:hover { background: var(--surface2); }
        .tree-subgrupo > summary i.chevron { font-size: 0.62rem; color: var(--muted); transition: transform 0.15s; flex-shrink: 0; }
        .tree-subgrupo[open] > summary i.chevron { transform: rotate(90deg); }
        .tree-subgrupo > summary i.fa-layer-group { color: var(--muted); }

        .pl-row {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.7rem 1.25rem 0.7rem 3.75rem;
            border-top: 1px solid var(--border);
            transition: background 0.12s;
        }
        .pl-row:hover { background: var(--surface2); }
        .pl-row i.fa-file-pdf { color: #c0392b; flex-shrink: 0; }
        .pl-desc { font-size: 0.85rem; font-weight: 600; color: var(--text); }

        /* ── EMPTY STATE ── */
        .empty-state { padding: 3.5rem 1.5rem; text-align: center; }
        .empty-icon { width: 52px; height: 52px; border-radius: 0.75rem; background: var(--blue-s); color: var(--accent); display: inline-flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 1rem; }
        .empty-title { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 0.35rem; }
        .empty-sub { font-size: 0.82rem; color: var(--muted); }

        /* ── MODAL ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-nuevo {
            background: #fff; border-radius: 1rem;
            width: 100%; max-width: 520px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.18);
            overflow: hidden;
            animation: modalIn 0.2s ease both;
            display: flex; flex-direction: column;
            height: 620px;
            max-height: 88vh;
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
        #form-nuevo-plano { display: flex; flex-direction: column; flex: 1; min-height: 0; }
        .modal-body { padding: 1.5rem 1.75rem; overflow-y: auto; flex: 1; min-height: 0; }
        .modal-foot { padding: 1rem 1.75rem 1.4rem; display: flex; justify-content: flex-end; gap: 0.5rem; flex-shrink: 0; border-top: 1.5px solid var(--border); }

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
        .btn-cancel { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); cursor: pointer; transition: all 0.14s; }
        .btn-cancel:hover { background: var(--surface2); }

        /* ── AUTOCOMPLETE GRUPO ── */
        .grupo-autocomplete { position: relative; }
        .grupo-sugerencias {
            display: none;
            position: absolute; left: 0; right: 0; top: calc(100% + 4px);
            background: #fff; border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            max-height: 180px; overflow-y: auto;
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

        /* ── DROP ZONE ── */
        .drop-zone {
            border: 2px dashed var(--border2);
            border-radius: 0.75rem;
            padding: 2rem 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.15s, background 0.15s;
            background: var(--surface2);
        }
        .drop-zone:hover, .drop-zone.drag-over { border-color: var(--accent); background: var(--accent-s); }
        .drop-zone-icon { font-size: 1.8rem; color: var(--accent); margin-bottom: 0.5rem; }
        .drop-zone-text { font-size: 0.85rem; font-weight: 600; color: var(--text); margin-bottom: 0.2rem; }
        .drop-zone-sub  { font-size: 0.76rem; color: var(--muted); }

        .file-list { margin-top: 0.85rem; display: flex; flex-direction: column; gap: 0.4rem; }
        .file-item {
            display: flex; align-items: center; gap: 0.6rem;
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 0.5rem; padding: 0.5rem 0.75rem;
        }
        .file-item i { color: #c0392b; font-size: 0.9rem; flex-shrink: 0; }
        .file-item-name { font-size: 0.82rem; font-weight: 500; color: var(--text); flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .file-item-size { font-size: 0.75rem; color: var(--muted); flex-shrink: 0; }
        .file-item-remove { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 0.75rem; padding: 0 0.2rem; transition: color 0.14s; flex-shrink: 0; }
        .file-item-remove:hover { color: #c0392b; }

        /* ── MOBILE ── */
        @media (max-width: 640px) {
            .ph { padding: 1rem 0 0.75rem; gap: 0.75rem; margin-bottom: 1rem; }
            .ph-title { font-size: 1.3rem; }
            .ph-right { width: 100%; }
            .pl-row { padding-left: 2.25rem; }
            .tree-subgrupo > summary { padding-left: 1.75rem; }
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
                            Planos
                        </div>
                        <h1 class="ph-title"><em>Planos</em></h1>
                        <p class="ph-sub">{{ $planos->count() }} {{ $planos->count() === 1 ? 'plano cargado' : 'planos cargados' }}</p>
                    </div>
                    <div class="ph-right">
                        @permiso('pla_tc', 'editar')
                        @permiso('pla_tc', 'agregar')
                        @if($pendientesCount > 0)
                        <a href="{{ route('planos_tc.aprobar', $obraTc->id) }}" class="btn btn-pendientes">
                            <i class="fas fa-clock"></i> {{ $pendientesCount }} {{ $pendientesCount === 1 ? 'pendiente' : 'pendientes' }}
                        </a>
                        @endif
                        @endpermiso
                        @endpermiso
                        @permiso('pla_tc', 'agregar')
                        <button type="button" class="btn btn-primary" onclick="abrirModalNuevoPlano()">
                            <i class="fas fa-plus"></i> Agregar planos
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

                <div class="tree-wrap">
                    @if($planos->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-drafting-compass"></i></div>
                            <div class="empty-title">Sin planos</div>
                            <div class="empty-sub">Todavía no hay ningún plano cargado.</div>
                        </div>
                    @else
                        @foreach($arbol as $grupoId => $ramaGrupo)
                        <details class="tree-grupo" open>
                            <summary>
                                <i class="fas fa-chevron-right chevron"></i>
                                <i class="fas fa-folder"></i>
                                {{ $ramaGrupo['grupo']->descripcion ?? '-' }}
                                <span class="tree-count">{{ $ramaGrupo['subgrupos']->sum(fn($r) => $r['planos']->count()) }}</span>
                            </summary>

                            @foreach($ramaGrupo['subgrupos'] as $subgrupoId => $ramaSubgrupo)
                            <details class="tree-subgrupo">
                                <summary>
                                    <i class="fas fa-chevron-right chevron"></i>
                                    <i class="fas fa-layer-group"></i>
                                    {{ $ramaSubgrupo['subgrupo']->descripcion ?? '-' }}
                                    <span class="tree-count">{{ $ramaSubgrupo['planos']->count() }}</span>
                                </summary>

                                @foreach($ramaSubgrupo['planos'] as $plano)
                                <div class="pl-row">
                                    <i class="fas fa-file-pdf"></i>
                                    <div class="pl-desc">{{ $plano->descripcion }}</div>
                                </div>
                                @endforeach
                            </details>
                            @endforeach
                        </details>
                        @endforeach
                    @endif
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL AGREGAR PLANOS
══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-nuevo-plano">
    <div class="modal-nuevo">
        <div class="modal-head">
            <div class="modal-head-title"><i class="fas fa-drafting-compass"></i> Agregar planos</div>
            <button class="modal-close" onclick="cerrarModalNuevoPlano()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>

        <form id="form-nuevo-plano" method="POST" action="{{ route('planos_tc.store', $obraTc->id) }}" enctype="multipart/form-data">
            @csrf

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="input-nombre-grupo">Nombre del grupo <span>*</span></label>
                    <div class="grupo-autocomplete" id="grupo-autocomplete">
                        <input type="text" id="input-nombre-grupo" name="nombre_grupo" class="form-control" placeholder="Ej: Planta baja" autocomplete="off">
                        <div class="grupo-sugerencias" id="grupo-sugerencias">
                            @foreach($gruposExistentes as $grupoExistente)
                            <div class="grupo-sugerencia" data-valor="{{ $grupoExistente }}">{{ $grupoExistente }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Planos (PDF) <span>*</span></label>
                    <div class="drop-zone" id="drop-zone" onclick="document.getElementById('input-archivos-plano').click()">
                        <div class="drop-zone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <div class="drop-zone-text">Hacé clic o arrastrá los archivos aquí</div>
                        <div class="drop-zone-sub">Solo archivos PDF</div>
                    </div>
                    <input type="file" id="input-archivos-plano" name="archivos[]" accept="application/pdf" multiple style="display:none">

                    <div class="file-list" id="file-list-plano"></div>
                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn-cancel" onclick="cerrarModalNuevoPlano()">Cancelar</button>
                <button type="submit" id="btn-guardar-plano" class="btn btn-primary" disabled>
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let archivosPlanoSeleccionados = [];

    function abrirModalNuevoPlano() {
        document.getElementById('modal-nuevo-plano').classList.add('active');
        const inputGrupo = document.getElementById('input-nombre-grupo');
        setTimeout(() => {
            inputGrupo.focus();
            filtrarSugerenciasGrupo(inputGrupo.value);
        }, 0);
    }

    function cerrarModalNuevoPlano() {
        document.getElementById('modal-nuevo-plano').classList.remove('active');
        resetModalNuevoPlano();
    }

    function resetModalNuevoPlano() {
        archivosPlanoSeleccionados = [];
        document.getElementById('file-list-plano').innerHTML = '';
        document.getElementById('input-nombre-grupo').value = '';
        document.getElementById('input-nombre-grupo').classList.remove('error');
        document.getElementById('input-archivos-plano').value = '';
        document.getElementById('grupo-autocomplete').classList.remove('open');
        actualizarBtnGuardarPlano();
    }

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function actualizarListaArchivosPlano() {
        const lista = document.getElementById('file-list-plano');
        lista.innerHTML = '';

        archivosPlanoSeleccionados.forEach((f, idx) => {
            const item = document.createElement('div');
            item.className = 'file-item';
            item.innerHTML = `
                <i class="fas fa-file-pdf"></i>
                <span class="file-item-name" title="${f.name}">${f.name}</span>
                <span class="file-item-size">${formatSize(f.size)}</span>
                <button type="button" class="file-item-remove" onclick="quitarArchivoPlano(${idx})" title="Quitar"><i class="fas fa-times"></i></button>
            `;
            lista.appendChild(item);
        });

        sincronizarInputPlano();
        actualizarBtnGuardarPlano();
    }

    function sincronizarInputPlano() {
        const dt = new DataTransfer();
        archivosPlanoSeleccionados.forEach(f => dt.items.add(f));
        document.getElementById('input-archivos-plano').files = dt.files;
    }

    function quitarArchivoPlano(idx) {
        archivosPlanoSeleccionados.splice(idx, 1);
        actualizarListaArchivosPlano();
    }

    function agregarArchivosPlano(files) {
        Array.from(files).forEach(f => {
            if (f.type === 'application/pdf') archivosPlanoSeleccionados.push(f);
        });
        actualizarListaArchivosPlano();
    }

    function actualizarBtnGuardarPlano() {
        const nombre = document.getElementById('input-nombre-grupo').value.trim();
        document.getElementById('btn-guardar-plano').disabled = !(nombre.length > 0 && archivosPlanoSeleccionados.length > 0);
    }

    document.getElementById('input-nombre-grupo').addEventListener('input', function () {
        this.classList.remove('error');
        actualizarBtnGuardarPlano();
        filtrarSugerenciasGrupo(this.value);
    });

    /* ─── Autocompletado de grupo existente ───────────────── */
    const grupoWrap = document.getElementById('grupo-autocomplete');
    const grupoSugerencias = document.getElementById('grupo-sugerencias');
    const grupoOpciones = Array.from(grupoSugerencias.querySelectorAll('.grupo-sugerencia'));

    function filtrarSugerenciasGrupo(valor) {
        const q = valor.trim().toLowerCase();
        let hayCoincidencias = false;

        grupoOpciones.forEach(op => {
            const match = q.length === 0 || op.dataset.valor.toLowerCase().includes(q);
            op.classList.toggle('hidden', !match);
            if (match) hayCoincidencias = true;
        });

        grupoWrap.classList.toggle('open', hayCoincidencias);
    }

    grupoOpciones.forEach(op => {
        op.addEventListener('click', () => {
            const input = document.getElementById('input-nombre-grupo');
            input.value = op.dataset.valor;
            input.classList.remove('error');
            grupoWrap.classList.remove('open');
            actualizarBtnGuardarPlano();
        });
    });

    document.getElementById('input-nombre-grupo').addEventListener('focus', function () {
        filtrarSugerenciasGrupo(this.value);
    });

    document.addEventListener('click', function (e) {
        if (!grupoWrap.contains(e.target)) grupoWrap.classList.remove('open');
    });

    document.getElementById('input-archivos-plano').addEventListener('change', function () {
        agregarArchivosPlano(this.files);
    });

    const dropZonePlano = document.getElementById('drop-zone');
    dropZonePlano.addEventListener('dragover', e => { e.preventDefault(); dropZonePlano.classList.add('drag-over'); });
    dropZonePlano.addEventListener('dragleave', () => dropZonePlano.classList.remove('drag-over'));
    dropZonePlano.addEventListener('drop', e => {
        e.preventDefault();
        dropZonePlano.classList.remove('drag-over');
        agregarArchivosPlano(e.dataTransfer.files);
    });

    document.getElementById('form-nuevo-plano').addEventListener('submit', function (e) {
        const nombre = document.getElementById('input-nombre-grupo');
        if (!nombre.value.trim() || archivosPlanoSeleccionados.length === 0) {
            e.preventDefault();
            if (!nombre.value.trim()) nombre.classList.add('error');
            return;
        }

        const btn = document.getElementById('btn-guardar-plano');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subiendo…';
        document.getElementById('input-nombre-grupo').readOnly = true;
    });

    document.getElementById('modal-nuevo-plano').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalNuevoPlano();
    });

    @if($errors->any())
    abrirModalNuevoPlano();
    @endif
</script>
</body>
</html>
