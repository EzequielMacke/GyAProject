<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantillas</title>
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
        .btn-sm { height: 30px; padding: 0 0.7rem; font-size: 0.75rem; border-radius: 0.45rem; }
        .btn-danger-soft { background: #fff0f0; border-color: #f5c2c2; color: #c0392b; }
        .btn-danger-soft:hover { background: #fde0e0; border-color: #e07070; color: #a93226; }

        /* ── SEARCH ── */
        .search-wrap { position: relative; }
        .search-wrap i { position: absolute; left: 0.78rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 0.72rem; pointer-events: none; }
        .search-bar {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.83rem;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.5rem 0.9rem 0.5rem 2.1rem;
            color: var(--text); width: 220px; outline: none; height: 38px;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
        }
        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus { border-color: var(--accent); width: 270px; box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        /* ── LIST ── */
        .list-wrap { background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem; overflow: hidden; }
        .list-header {
            display: grid;
            grid-template-columns: 48px 1fr 1.5fr 120px 100px 180px;
            padding: 0.65rem 1.25rem;
            background: var(--surface2);
            border-bottom: 1.5px solid var(--border);
            font-size: 0.72rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        .pla-row {
            display: grid;
            grid-template-columns: 48px 1fr 1.5fr 120px 100px 180px;
            align-items: center;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
        }
        .pla-row:last-child { border-bottom: none; }
        .pla-row:hover { background: var(--surface2); }
        .pla-num    { font-size: 0.78rem; font-weight: 600; color: var(--muted); }
        .pla-nombre { font-size: 0.875rem; font-weight: 600; color: var(--text); }
        .pla-desc   { font-size: 0.82rem; color: var(--text2); }
        .pla-rev    { font-size: 0.82rem; color: var(--muted); }
        .pla-usuario { font-size: 0.78rem; color: var(--muted); }
        .pla-acciones { display: flex; justify-content: flex-end; gap: 0.35rem; }

        /* ── EMPTY STATE ── */
        .empty-state { padding: 3.5rem 1.5rem; text-align: center; }
        .empty-icon { width: 52px; height: 52px; border-radius: 0.75rem; background: var(--blue-s); color: var(--accent); display: inline-flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 1rem; }
        .empty-title { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 0.35rem; }
        .empty-sub { font-size: 0.82rem; color: var(--muted); }

        /* ── ALERTS ── */
        .alert { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert-success { background: #e5f6f0; color: #1e9166; border: 1px solid #b6e8d6; }
        .alert-danger  { background: #fff0f0; color: #c0392b; border: 1px solid #f5c2c2; }

        /* ── MODAL BASE ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }

        /* ── MODAL NUEVO ── */
        .modal-nuevo {
            background: #fff; border-radius: 1rem;
            width: 100%; max-width: 540px;
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
        .modal-head-title i { color: var(--accent); }
        .modal-close { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 1rem; padding: 0.25rem; border-radius: 0.35rem; transition: color 0.14s; }
        .modal-close:hover { color: var(--text); }

        .modal-body { padding: 1.5rem 1.75rem; }
        .modal-foot { padding: 1rem 1.75rem 1.4rem; display: flex; justify-content: flex-end; gap: 0.5rem; }

        /* ── DROP ZONE ── */
        .drop-zone {
            border: 2px dashed var(--border2);
            border-radius: 0.75rem;
            padding: 2.2rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.15s, background 0.15s;
            background: var(--surface2);
        }
        .drop-zone:hover, .drop-zone.drag-over { border-color: var(--accent); background: var(--accent-s); }
        .drop-zone-icon { font-size: 2rem; color: var(--accent); margin-bottom: 0.6rem; }
        .drop-zone-text { font-size: 0.875rem; font-weight: 600; color: var(--text); margin-bottom: 0.25rem; }
        .drop-zone-sub  { font-size: 0.78rem; color: var(--muted); }

        /* ── FILE LIST ── */
        .file-list { margin-top: 0.85rem; display: flex; flex-direction: column; gap: 0.4rem; }
        .file-item {
            display: flex; align-items: center; gap: 0.6rem;
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 0.5rem; padding: 0.5rem 0.75rem;
        }
        .file-item i { color: var(--accent); font-size: 0.9rem; flex-shrink: 0; }
        .file-item-name { font-size: 0.82rem; font-weight: 500; color: var(--text); flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .file-item-size { font-size: 0.75rem; color: var(--muted); flex-shrink: 0; }
        .file-item-remove { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 0.75rem; padding: 0 0.2rem; transition: color 0.14s; flex-shrink: 0; }
        .file-item-remove:hover { color: #c0392b; }

        /* ── FORM FIELDS ── */
        .form-section { margin-top: 1.25rem; border-top: 1.5px solid var(--border); padding-top: 1.25rem; display: none; }
        .form-section.visible { display: block; }
        .form-group { margin-bottom: 1rem; }
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
        .form-control:disabled { background: var(--surface2); color: var(--muted); cursor: not-allowed; }
        .form-control.error { border-color: #e74c3c; }
        textarea.form-control { resize: vertical; min-height: 80px; }
        .rev-badge {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: var(--blue-s); color: var(--accent);
            border: 1.5px solid #c5d9f7; border-radius: 0.45rem;
            padding: 0.45rem 0.85rem; font-size: 0.875rem; font-weight: 700;
        }

        /* ── MODAL VER ── */
        .modal-ver-box {
            background: #fff; border-radius: 1rem;
            width: 100%; max-width: 640px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.18);
            overflow: hidden;
            animation: modalIn 0.2s ease both;
            display: flex; flex-direction: column;
            max-height: 90vh;
        }
        .modal-ver-body {
            padding: 1.5rem 1.75rem;
            overflow-y: auto;
            flex: 1;
        }
        .ver-descripcion {
            font-size: 0.85rem; color: var(--text2);
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 0.55rem; padding: 0.7rem 1rem;
            margin-bottom: 1.4rem; line-height: 1.55;
            white-space: pre-wrap;
        }
        .ver-descripcion-vacia { color: var(--muted); font-style: italic; }
        .ver-rev-titulo {
            font-size: 0.72rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.05em;
            margin-bottom: 0.65rem;
        }
        .rev-list { display: flex; flex-direction: column; gap: 0.65rem; }
        .rev-item {
            border: 1.5px solid var(--border);
            border-radius: 0.7rem; overflow: hidden;
        }
        .rev-item-head {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.65rem 1rem;
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
        }
        .rev-actual-tag {
            font-size: 0.68rem; font-weight: 700;
            background: #e5f6f0; color: #1e9166;
            border: 1px solid #b6e8d6;
            border-radius: 0.35rem; padding: 0.15rem 0.5rem;
        }
        .rev-item-head .btn-dl {
            margin-left: auto;
            height: 28px; padding: 0 0.65rem; border-radius: 0.4rem;
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.75rem; font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface); color: var(--text2);
            text-decoration: none; cursor: pointer;
            transition: all 0.14s;
        }
        .rev-item-head .btn-dl:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .rev-archivos { padding: 0.6rem 1rem; display: flex; flex-direction: column; gap: 0.3rem; }
        .rev-archivo {
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.82rem; color: var(--text2);
        }
        .rev-archivo i { color: var(--accent); font-size: 0.82rem; flex-shrink: 0; }
        .rev-sin-archivos { font-size: 0.8rem; color: var(--muted); font-style: italic; padding: 0.5rem 1rem; }

        /* ── DELETE MODAL ── */
        .modal-del-box { background: #fff; border-radius: 0.95rem; padding: 1.75rem 1.75rem 1.5rem; max-width: 380px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.18); }
        .modal-del-icon { width: 44px; height: 44px; border-radius: 0.65rem; background: #fff0f0; color: #c0392b; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 1rem; }
        .modal-del-title { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.35rem; }
        .modal-del-sub { font-size: 0.82rem; color: var(--muted); margin-bottom: 1.35rem; }
        .modal-del-actions { display: flex; gap: 0.5rem; justify-content: flex-end; }
        .btn-cancel { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); cursor: pointer; transition: all 0.14s; }
        .btn-cancel:hover { background: var(--surface2); }
        .btn-confirm-delete { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: none; background: #c0392b; color: #fff; cursor: pointer; transition: background 0.14s; }
        .btn-confirm-delete:hover { background: #a93226; }
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
                            <i class="fas fa-file-alt"></i>
                            Plantillas
                        </div>
                        <h1 class="ph-title"><em>Plantillas</em></h1>
                        <p class="ph-sub">{{ $plantillas->count() }} {{ $plantillas->count() === 1 ? 'registro' : 'registros' }} encontrados</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search-bar" id="buscador" placeholder="Buscar plantilla..." autocomplete="off">
                        </div>
                        @permiso('pla', 'agregar')
                        <button type="button" class="btn btn-primary" onclick="abrirModalNuevo()">
                            <i class="fas fa-plus"></i> Nueva
                        </button>
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

                <div class="list-wrap">
                    @if($plantillas->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-file-alt"></i></div>
                            <div class="empty-title">Sin plantillas registradas</div>
                            <div class="empty-sub">Todavía no hay ninguna plantilla cargada en el sistema.</div>
                        </div>
                    @else
                        <div class="list-header">
                            <div>#</div>
                            <div>Nombre</div>
                            <div>Descripción</div>
                            <div>Revisión</div>
                            <div>Archivos</div>
                            <div></div>
                        </div>
                        <div id="lista-plantillas">
                            @foreach($plantillas as $i => $pla)
                            <div class="pla-row" data-search="{{ strtolower($pla->nombre . ' ' . $pla->revision . ' ' . $pla->descripcion) }}">
                                <div class="pla-num">{{ $i + 1 }}</div>
                                <div class="pla-nombre">{{ $pla->nombre }}</div>
                                <div class="pla-desc">{{ Str::limit($pla->descripcion, 55) }}</div>
                                <div class="pla-rev">{{ $pla->revision }}</div>
                                <div class="pla-usuario" style="font-size:0.78rem; color:var(--muted);">
                                    {{ $pla->detalles->count() }} {{ $pla->detalles->count() === 1 ? 'archivo' : 'archivos' }}
                                </div>
                                <div class="pla-acciones">
                                    @permiso('pla', 'ver')
                                    <button type="button" class="btn btn-sm" title="Ver" onclick="abrirModalVer({{ $pla->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('plantilla.download', $pla->id) }}" class="btn btn-sm" title="Descargar">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @endpermiso
                                    @permiso('pla', 'editar')
                                    <button type="button" class="btn btn-sm" title="Editar"
                                        onclick="abrirModalEditar({{ $pla->id }})">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    @php
                                        preg_match('/(\d+)$/', $pla->revision, $m);
                                        $nextRev = 'Rev - ' . str_pad((isset($m[1]) ? (int)$m[1] + 1 : 2), 3, '0', STR_PAD_LEFT);
                                    @endphp
                                    <button type="button" class="btn btn-sm" title="Nueva revisión"
                                        onclick="abrirModalActualizar({{ $pla->id }}, '{{ $nextRev }}')">
                                        <i class="fas fa-history"></i>
                                    </button>
                                    @endpermiso
                                    @permiso('pla', 'eliminar')
                                    <form id="form-delete-{{ $pla->id }}" method="POST" action="{{ route('plantilla.destroy', $pla->id) }}">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger-soft" title="Eliminar"
                                        onclick="abrirModalEliminar({{ $pla->id }}, '{{ addslashes($pla->nombre) }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endpermiso
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL VER PLANTILLA
══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-ver">
    <div class="modal-ver-box">
        <div class="modal-head">
            <div class="modal-head-title"><i class="fas fa-file-alt"></i> <span id="ver-titulo"></span></div>
            <button class="modal-close" onclick="cerrarModalVer()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-ver-body">
            <div class="form-group">
                <div class="form-label" style="margin-bottom:0.4rem;">Descripción</div>
                <div class="ver-descripcion" id="ver-descripcion"></div>
            </div>
            <div class="ver-rev-titulo">Revisiones</div>
            <div class="rev-list" id="ver-rev-list"></div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="cerrarModalVer()">Cerrar</button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL NUEVA PLANTILLA
══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-nuevo">
    <div class="modal-nuevo">
        <div class="modal-head">
            <div class="modal-head-title"><i class="fas fa-file-upload"></i> Nueva Plantilla</div>
            <button class="modal-close" onclick="cerrarModalNuevo()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>

        <form id="form-nuevo" method="POST" action="{{ route('plantilla.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="modal-body">

                {{-- DROP ZONE --}}
                <div class="drop-zone" id="drop-zone" onclick="document.getElementById('input-archivos').click()">
                    <div class="drop-zone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="drop-zone-text">Hacé clic o arrastrá los archivos aquí</div>
                    <div class="drop-zone-sub">Se aceptan todos los tipos de archivo</div>
                </div>
                <input type="file" id="input-archivos" name="archivos[]" multiple style="display:none">

                {{-- LISTA DE ARCHIVOS SELECCIONADOS --}}
                <div class="file-list" id="file-list"></div>

                {{-- CAMPOS (se muestran cuando hay al menos 1 archivo) --}}
                <div class="form-section" id="form-section">

                    <div class="form-group">
                        <label class="form-label">Revisión</label>
                        <div class="rev-badge"><i class="fas fa-code-branch"></i> Rev - 001</div>
                        <input type="hidden" name="revision" value="Rev - 001">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="input-nombre">Nombre <span>*</span></label>
                        <input type="text" id="input-nombre" name="nombre" class="form-control" placeholder="Nombre de la plantilla" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="input-descripcion">Descripción <span style="color:var(--muted); font-weight:400; text-transform:none; letter-spacing:0;">(opcional)</span></label>
                        <textarea id="input-descripcion" name="descripcion" class="form-control" placeholder="Descripción de la plantilla..."></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label" for="input-observacion">Observación <span style="color:var(--muted); font-weight:400; text-transform:none; letter-spacing:0;">(opcional)</span></label>
                        <textarea id="input-observacion" name="observacion" class="form-control" placeholder="Observación sobre esta revisión..."></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn-cancel" onclick="cerrarModalNuevo()">Cancelar</button>
                <button type="submit" id="btn-guardar" class="btn btn-primary" style="display:none">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL ACTUALIZAR (NUEVA REVISIÓN)
══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-actualizar">
    <div class="modal-nuevo">
        <div class="modal-head">
            <div class="modal-head-title"><i class="fas fa-history"></i> Nueva Revisión</div>
            <button class="modal-close" onclick="cerrarModalActualizar()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>

        <form id="form-actualizar" method="POST" action="" enctype="multipart/form-data">
            @csrf

            <div class="modal-body">

                {{-- DROP ZONE --}}
                <div class="drop-zone" id="drop-zone-act" onclick="document.getElementById('input-archivos-act').click()">
                    <div class="drop-zone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="drop-zone-text">Hacé clic o arrastrá los archivos aquí</div>
                    <div class="drop-zone-sub">Se aceptan todos los tipos de archivo</div>
                </div>
                <input type="file" id="input-archivos-act" name="archivos[]" multiple style="display:none">

                <div class="file-list" id="file-list-act"></div>

                <div class="form-section visible" id="form-section-act" style="margin-top:1.25rem; border-top:1.5px solid var(--border); padding-top:1.25rem;">

                    <div class="form-group">
                        <label class="form-label">Revisión</label>
                        <div class="rev-badge"><i class="fas fa-code-branch"></i> <span id="act-rev-label"></span></div>
                        <input type="hidden" id="act-revision" name="revision">
                    </div>

                    <input type="hidden" id="act-nombre-hidden" name="nombre">
                    <input type="hidden" id="act-descripcion-hidden" name="descripcion">

                    <div class="form-group">
                        <label class="form-label">Nombre</label>
                        <input type="text" id="act-nombre" class="form-control" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea id="act-descripcion" class="form-control" disabled></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label" for="act-observacion">Observación <span style="color:var(--muted); font-weight:400; text-transform:none; letter-spacing:0;">(opcional)</span></label>
                        <textarea id="act-observacion" name="observacion" class="form-control" placeholder="Observación sobre esta revisión..."></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn-cancel" onclick="cerrarModalActualizar()">Cancelar</button>
                <button type="submit" id="btn-act-guardar" class="btn btn-primary" disabled style="opacity:0.55; cursor:not-allowed;">
                    <i class="fas fa-save"></i> Guardar revisión
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL EDITAR PLANTILLA
══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-editar">
    <div class="modal-nuevo">
        <div class="modal-head">
            <div class="modal-head-title"><i class="fas fa-pencil-alt"></i> Editar Plantilla</div>
            <button class="modal-close" onclick="cerrarModalEditar()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>

        <form id="form-editar" method="POST" action="" >
            @csrf @method('PUT')

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="edit-nombre">Nombre <span>*</span></label>
                    <input type="text" id="edit-nombre" name="nombre" class="form-control" placeholder="Nombre de la plantilla" autocomplete="off">
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-descripcion">Descripción <span style="color:var(--muted); font-weight:400; text-transform:none; letter-spacing:0;">(opcional)</span></label>
                    <textarea id="edit-descripcion" name="descripcion" class="form-control" placeholder="Descripción de la plantilla..."></textarea>
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" for="edit-observacion">Observación <span style="color:var(--muted); font-weight:400; text-transform:none; letter-spacing:0;">(opcional)</span></label>
                    <textarea id="edit-observacion" name="observacion" class="form-control" placeholder="Observación sobre esta revisión..."></textarea>
                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn-cancel" onclick="cerrarModalEditar()">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL ELIMINAR
══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-eliminar">
    <div class="modal-del-box">
        <div class="modal-del-icon"><i class="fas fa-trash-alt"></i></div>
        <div class="modal-del-title">Eliminar plantilla</div>
        <div class="modal-del-sub" id="modal-eliminar-sub">¿Estás seguro que querés eliminar esta plantilla?</div>
        <div class="modal-del-actions">
            <button class="btn-cancel" onclick="cerrarModalEliminar()">Cancelar</button>
            <button class="btn-confirm-delete" onclick="confirmarEliminar()"><i class="fas fa-trash-alt"></i> Eliminar</button>
        </div>
    </div>
</div>

<script>
    /* ─── Datos de plantillas (cadenas de revisión) ───────── */
    const plantillasData = {
        @foreach($plantillas as $pla)
        {{ $pla->id }}: {
            nombre: @json($pla->nombre),
            descripcion: @json($pla->descripcion ?? ''),
            observacion: @json($pla->observacion ?? ''),
            revisiones: [
                @foreach(array_reverse($cadenas[$pla->id]) as $rev)
                {
                    id: {{ $rev->id }},
                    revision: @json($rev->revision),
                    esActual: {{ $loop->first ? 'true' : 'false' }},
                    observacion: @json($rev->observacion ?? ''),
                    archivos: @json($rev->detalles->pluck('ruta'))
                }{{ !$loop->last ? ',' : '' }}
                @endforeach
            ]
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    };

    function nombreLimpio(ruta) {
        return ruta.replace(/_\d{14}(\.[^.]*)?$/, '$1');
    }

    function iconoPorRuta(ruta) {
        return iconoPorExtension(ruta);
    }

    /* ─── Modal Ver ────────────────────────────────────────── */
    function abrirModalVer(id) {
        const data = plantillasData[id];
        if (!data) return;

        document.getElementById('ver-titulo').textContent = data.nombre;

        const desc = document.getElementById('ver-descripcion');
        if (data.descripcion && data.descripcion.trim()) {
            desc.textContent = data.descripcion;
            desc.classList.remove('ver-descripcion-vacia');
        } else {
            desc.textContent = 'Sin descripción';
            desc.classList.add('ver-descripcion-vacia');
        }

        const lista = document.getElementById('ver-rev-list');
        lista.innerHTML = '';

        data.revisiones.forEach(rev => {
            const item = document.createElement('div');
            item.className = 'rev-item';

            const actualTag = rev.esActual
                ? `<span class="rev-actual-tag">Actual</span>`
                : '';

            const archivosHtml = rev.archivos.length > 0
                ? rev.archivos.map(a => `
                    <div class="rev-archivo">
                        <i class="fas ${iconoPorRuta(a)}"></i>
                        <span>${nombreLimpio(a)}</span>
                    </div>`).join('')
                : `<div class="rev-sin-archivos">Sin archivos</div>`;

            const obsHtml = rev.observacion && rev.observacion.trim()
                ? `<div class="rev-archivo" style="padding: 0 1rem 0.55rem; color:var(--muted); font-style:italic; font-size:0.8rem; gap:0.4rem;">
                       <i class="fas fa-comment-alt" style="color:var(--muted);"></i>
                       <span style="white-space:pre-wrap;">${rev.observacion}</span>
                   </div>`
                : '';

            item.innerHTML = `
                <div class="rev-item-head">
                    <div class="rev-badge"><i class="fas fa-code-branch"></i> ${rev.revision}</div>
                    ${actualTag}
                    <a href="/plantilla/${rev.id}/descargar" class="btn-dl">
                        <i class="fas fa-download"></i> Descargar
                    </a>
                </div>
                <div class="rev-archivos">${archivosHtml}</div>
                ${obsHtml}
            `;
            lista.appendChild(item);
        });

        document.getElementById('modal-ver').classList.add('active');
    }

    function cerrarModalVer() {
        document.getElementById('modal-ver').classList.remove('active');
    }

    document.getElementById('modal-ver').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalVer();
    });

    /* ─── Búsqueda ─────────────────────────────────────────── */
    document.getElementById('buscador')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#lista-plantillas .pla-row').forEach(row => {
            row.style.display = row.dataset.search.includes(q) ? '' : 'none';
        });
    });

    /* ─── Modal Nuevo ──────────────────────────────────────── */
    let archivosSeleccionados = [];

    function abrirModalNuevo() {
        document.getElementById('modal-nuevo').classList.add('active');
    }

    function cerrarModalNuevo() {
        document.getElementById('modal-nuevo').classList.remove('active');
        resetModalNuevo();
    }

    function resetModalNuevo() {
        archivosSeleccionados = [];
        document.getElementById('file-list').innerHTML = '';
        document.getElementById('form-section').classList.remove('visible');
        document.getElementById('btn-guardar').style.display = 'none';
        document.getElementById('input-nombre').value = '';
        document.getElementById('input-descripcion').value = '';
        document.getElementById('input-observacion').value = '';
        document.getElementById('input-archivos').value = '';
        document.getElementById('input-nombre').classList.remove('error');
    }

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function iconoPorExtension(nombre) {
        const ext = nombre.split('.').pop().toLowerCase();
        const map = {
            pdf: 'fa-file-pdf', doc: 'fa-file-word', docx: 'fa-file-word',
            xls: 'fa-file-excel', xlsx: 'fa-file-excel',
            ppt: 'fa-file-powerpoint', pptx: 'fa-file-powerpoint',
            jpg: 'fa-file-image', jpeg: 'fa-file-image', png: 'fa-file-image', gif: 'fa-file-image',
            zip: 'fa-file-archive', rar: 'fa-file-archive',
            txt: 'fa-file-alt', csv: 'fa-file-csv',
        };
        return map[ext] || 'fa-file';
    }

    function actualizarListaArchivos() {
        const lista = document.getElementById('file-list');
        lista.innerHTML = '';

        archivosSeleccionados.forEach((f, idx) => {
            const item = document.createElement('div');
            item.className = 'file-item';
            item.innerHTML = `
                <i class="fas ${iconoPorExtension(f.name)}"></i>
                <span class="file-item-name" title="${f.name}">${f.name}</span>
                <span class="file-item-size">${formatSize(f.size)}</span>
                <button type="button" class="file-item-remove" onclick="quitarArchivo(${idx})" title="Quitar"><i class="fas fa-times"></i></button>
            `;
            lista.appendChild(item);
        });

        const hayArchivos = archivosSeleccionados.length > 0;
        document.getElementById('form-section').classList.toggle('visible', hayArchivos);
        document.getElementById('btn-guardar').style.display = hayArchivos ? '' : 'none';

        // Sincronizar el input file con los archivos seleccionados
        sincronizarInput();
    }

    function sincronizarInput() {
        const dt = new DataTransfer();
        archivosSeleccionados.forEach(f => dt.items.add(f));
        document.getElementById('input-archivos').files = dt.files;
    }

    function quitarArchivo(idx) {
        archivosSeleccionados.splice(idx, 1);
        actualizarListaArchivos();
    }

    document.getElementById('input-archivos').addEventListener('change', function () {
        Array.from(this.files).forEach(f => archivosSeleccionados.push(f));
        actualizarListaArchivos();
    });

    /* Drag & Drop */
    const dropZone = document.getElementById('drop-zone');

    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        Array.from(e.dataTransfer.files).forEach(f => archivosSeleccionados.push(f));
        actualizarListaArchivos();
    });

    /* Validación antes de submit */
    document.getElementById('form-nuevo').addEventListener('submit', function (e) {
        const nombre = document.getElementById('input-nombre');
        if (!nombre.value.trim()) {
            e.preventDefault();
            nombre.classList.add('error');
            nombre.focus();
            return;
        }
        nombre.classList.remove('error');
    });

    document.getElementById('input-nombre').addEventListener('input', function () {
        this.classList.remove('error');
    });

    /* Cerrar al hacer clic fuera */
    document.getElementById('modal-nuevo').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalNuevo();
    });

    /* ─── Modal Actualizar (nueva revisión) ───────────────── */
    let archivosAct = [];

    function abrirModalActualizar(id, nextRev) {
        const data = plantillasData[id];
        if (!data) return;
        archivosAct = [];
        document.getElementById('file-list-act').innerHTML = '';
        document.getElementById('input-archivos-act').value = '';
        document.getElementById('act-nombre').value = data.nombre;
        document.getElementById('act-descripcion').value = data.descripcion;
        document.getElementById('act-nombre-hidden').value = data.nombre;
        document.getElementById('act-descripcion-hidden').value = data.descripcion;
        document.getElementById('act-observacion').value = data.revisiones.length > 0 ? data.revisiones[0].observacion : '';
        document.getElementById('act-rev-label').textContent = nextRev;
        document.getElementById('act-revision').value = nextRev;
        document.getElementById('act-nombre').classList.remove('error');
        document.getElementById('form-actualizar').action = '/plantilla/' + id + '/revision';
        actualizarBtnAct();
        document.getElementById('modal-actualizar').classList.add('active');
    }

    function cerrarModalActualizar() {
        document.getElementById('modal-actualizar').classList.remove('active');
    }

    function actualizarBtnAct() {
        const btn = document.getElementById('btn-act-guardar');
        const hay = archivosAct.length > 0;
        btn.disabled = !hay;
        btn.style.opacity = hay ? '1' : '0.55';
        btn.style.cursor  = hay ? 'pointer' : 'not-allowed';
    }

    document.getElementById('input-archivos-act').addEventListener('change', function () {
        Array.from(this.files).forEach(f => archivosAct.push(f));
        renderFilesAct();
    });

    const dropZoneAct = document.getElementById('drop-zone-act');
    dropZoneAct.addEventListener('dragover', e => { e.preventDefault(); dropZoneAct.classList.add('drag-over'); });
    dropZoneAct.addEventListener('dragleave', () => dropZoneAct.classList.remove('drag-over'));
    dropZoneAct.addEventListener('drop', e => {
        e.preventDefault();
        dropZoneAct.classList.remove('drag-over');
        Array.from(e.dataTransfer.files).forEach(f => archivosAct.push(f));
        renderFilesAct();
    });

    function renderFilesAct() {
        const lista = document.getElementById('file-list-act');
        lista.innerHTML = '';
        archivosAct.forEach((f, idx) => {
            const item = document.createElement('div');
            item.className = 'file-item';
            item.innerHTML = `
                <i class="fas ${iconoPorExtension(f.name)}"></i>
                <span class="file-item-name" title="${f.name}">${f.name}</span>
                <span class="file-item-size">${formatSize(f.size)}</span>
                <button type="button" class="file-item-remove" onclick="quitarArchivoAct(${idx})" title="Quitar"><i class="fas fa-times"></i></button>
            `;
            lista.appendChild(item);
        });
        const dt = new DataTransfer();
        archivosAct.forEach(f => dt.items.add(f));
        document.getElementById('input-archivos-act').files = dt.files;
        actualizarBtnAct();
    }

    function quitarArchivoAct(idx) {
        archivosAct.splice(idx, 1);
        renderFilesAct();
    }

    document.getElementById('form-actualizar').addEventListener('submit', function (e) {
        if (archivosAct.length === 0) {
            e.preventDefault();
        }
    });

    document.getElementById('act-nombre').addEventListener('input', function () {
        this.classList.remove('error');
    });

    document.getElementById('modal-actualizar').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalActualizar();
    });

    /* ─── Modal Editar ────────────────────────────────────── */
    function abrirModalEditar(id) {
        const data = plantillasData[id];
        if (!data) return;
        document.getElementById('edit-nombre').value = data.nombre;
        document.getElementById('edit-descripcion').value = data.descripcion;
        document.getElementById('edit-observacion').value = data.observacion;
        document.getElementById('edit-nombre').classList.remove('error');
        document.getElementById('form-editar').action = '/plantilla/' + id;
        document.getElementById('modal-editar').classList.add('active');
        document.getElementById('edit-nombre').focus();
    }

    function cerrarModalEditar() {
        document.getElementById('modal-editar').classList.remove('active');
    }

    document.getElementById('form-editar').addEventListener('submit', function (e) {
        const nombre = document.getElementById('edit-nombre');
        if (!nombre.value.trim()) {
            e.preventDefault();
            nombre.classList.add('error');
            nombre.focus();
            return;
        }
        nombre.classList.remove('error');
    });

    document.getElementById('edit-nombre').addEventListener('input', function () {
        this.classList.remove('error');
    });

    document.getElementById('modal-editar').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalEditar();
    });

    /* ─── Modal Eliminar ───────────────────────────────────── */
    let formIdPendiente = null;

    function abrirModalEliminar(id, nombre) {
        formIdPendiente = id;
        document.getElementById('modal-eliminar-sub').textContent = `¿Estás seguro que querés eliminar "${nombre}"?`;
        document.getElementById('modal-eliminar').classList.add('active');
    }

    function cerrarModalEliminar() {
        formIdPendiente = null;
        document.getElementById('modal-eliminar').classList.remove('active');
    }

    function confirmarEliminar() {
        if (formIdPendiente) document.getElementById('form-delete-' + formIdPendiente).submit();
    }

    document.getElementById('modal-eliminar').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalEliminar();
    });
</script>
</body>
</html>
