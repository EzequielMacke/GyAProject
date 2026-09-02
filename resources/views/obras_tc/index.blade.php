<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obra - Trabajo de Campo</title>
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
            display: flex; align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }

        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        .ph-meta {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.78rem; color: var(--muted);
            margin-top: 0.5rem;
        }
        .ph-meta i { font-size: 0.72rem; color: var(--accent); }

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
        .btn-primary:hover { background: #1f5bbf; border-color: #1f5bbf; color: #fff; }
        .btn-danger { color: #c0392b; }
        .btn-danger:hover { background: #fff0f0; border-color: #f5c2c2; color: #c0392b; }

        /* ── ALERTS ── */
        .alert { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert-success { background: #e5f6f0; color: #1e9166; border: 1px solid #b6e8d6; }
        .alert-danger  { background: #fff0f0; color: #c0392b; border: 1px solid #f5c2c2; }

        /* ── MODAL ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-caja {
            background: #fff; border-radius: 1rem;
            width: 100%; max-width: 440px;
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
        .modal-head-title.danger i { color: #c0392b; }
        .modal-close { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 1rem; padding: 0.25rem; border-radius: 0.35rem; transition: color 0.14s; }
        .modal-close:hover { color: var(--text); }
        .modal-body { padding: 1.5rem 1.75rem; }
        .modal-body p { font-size: 0.87rem; color: var(--text2); line-height: 1.5; }
        .modal-body p strong { color: var(--text); }
        .modal-foot { padding: 1rem 1.75rem 1.4rem; display: flex; justify-content: flex-end; gap: 0.5rem; }
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
        .form-control.error { border-color: #e74c3c; }
        textarea.form-control { resize: vertical; min-height: 90px; line-height: 1.5; }

        /* ── MENSAJE OBRA ── */
        .obra-mensaje {
            display: flex; gap: 0.7rem;
            background: var(--accent-s);
            border: 1.5px solid #cfe0f8;
            border-radius: 0.75rem;
            padding: 0.9rem 1.1rem;
            margin-bottom: 1.5rem;
        }
        .obra-mensaje i { color: var(--accent); font-size: 0.95rem; margin-top: 0.15rem; }
        .obra-mensaje-texto { font-size: 0.85rem; color: var(--text2); line-height: 1.55; white-space: pre-line; word-break: break-word; }
        .obra-mensaje-texto a { color: var(--accent); font-weight: 600; text-decoration: underline; text-underline-offset: 2px; }
        .obra-mensaje-texto a:hover { color: #1f5bbf; }
        .btn-cancel { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); cursor: pointer; transition: all 0.14s; }
        .btn-cancel:hover { background: var(--surface2); }
        .btn-confirmar-eliminar {
            height: 36px; padding: 0 1.1rem; border-radius: 0.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600;
            border: 1.5px solid #c0392b; background: #c0392b; color: #fff; cursor: pointer;
            display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.14s;
        }
        .btn-confirmar-eliminar:hover { background: #a93226; border-color: #a93226; }

        /* ══════════════════════════════
           OPTIONS GRID
        ══════════════════════════════ */
        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }

        .opcion-card {
            position: relative;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            padding: 1.6rem 1rem 1.35rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.7rem;
            text-decoration: none;
            color: var(--text);
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
            animation: cardIn 0.22s ease both;
        }

        .opcion-badge {
            position: absolute;
            top: 0.6rem; right: 0.6rem;
            min-width: 20px; height: 20px;
            padding: 0 0.35rem;
            border-radius: 999px;
            background: #d4920a;
            color: #fff;
            font-size: 0.68rem;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }

        .opcion-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0,0,0,0.09);
            border-color: var(--border2);
            color: var(--text);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        .opcion-icon {
            width: 48px; height: 48px;
            border-radius: 0.65rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
            transition: transform 0.18s;
        }

        .opcion-card:hover .opcion-icon { transform: scale(1.08); }

        .opcion-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1.3;
        }

        /* colour variants */
        .ic-purple { background: #eeecf9; color: #7c6fcd; }
        .ic-violet { background: #f3ecf9; color: #8e44ad; }
        .ic-green  { background: #e5f6f0; color: #1e9166; }
        .ic-yellow { background: #fef9ec; color: #d4920a; }
        .ic-orange { background: #fff0eb; color: #d9622a; }
        .ic-slate  { background: #edf1f4; color: #4e6070; }
        .ic-teal   { background: #e5f7fa; color: #0891a8; }
        .ic-pink   { background: #fdeef5; color: #c0507a; }
        .ic-blue   { background: #e8f0fc; color: #2a6fdb; }
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
                            {{ $obraTc->descripcion ?? 'Obra' }}
                        </div>
                        <h1 class="ph-title"><em>{{ $obraTc->descripcion ?? 'Obra' }}</em></h1>
                        <p class="ph-sub">Panel de gestión de la obra</p>
                        @if($obraTc->usuario)
                        <p class="ph-meta">
                            <i class="fas fa-user-circle"></i>
                            Obra creada por {{ $obraTc->usuario->nombre_completo ?: $obraTc->usuario->nombre }} el {{ $obraTc->created_at->format('d/m/Y H:i') }}
                        </p>
                        @endif
                    </div>
                    <div class="ph-right">
                        @permiso('obr_tc', 'editar')
                        <button type="button" class="btn" onclick="abrirModalEditarObra()">
                            <i class="fas fa-pen"></i> Editar
                        </button>
                        @endpermiso
                        @permiso('obr_tc', 'eliminar')
                        <button type="button" class="btn btn-danger" onclick="abrirModalEliminarObra()">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                        @endpermiso
                        <a href="{{ route('trabajo_campo.index') }}" class="btn">
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

                @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
                @endif

                @if($obraTc->mensaje)
                @php
                    $mensajeHtml = preg_replace(
                        '/(https?:\/\/[^\s<]+[^\s<.,;:!?\)\]])/i',
                        '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
                        e($obraTc->mensaje)
                    );
                @endphp
                <div class="obra-mensaje">
                    <i class="fas fa-message"></i>
                    <div class="obra-mensaje-texto">{!! $mensajeHtml !!}</div>
                </div>
                @endif

                <div class="options-grid">

                    @permiso('dir_tc', 'ver')
                    <a href="{{ route('directorio_tc.index', $obraTc->id) }}" class="opcion-card" style="animation-delay:0.04s">
                        <div class="opcion-icon ic-purple"><i class="fas fa-folder-open"></i></div>
                        <span class="opcion-label">Directorio</span>
                    </a>
                    @endpermiso

                    @permiso('pla_tc', 'ver')
                    <a href="{{ route('planos_tc.index', $obraTc->id) }}" class="opcion-card" style="animation-delay:0.08s">
                        @if($pendientesPlanos > 0)
                        <span class="opcion-badge">{{ $pendientesPlanos }}</span>
                        @endif
                        <div class="opcion-icon ic-blue"><i class="fas fa-drafting-compass"></i></div>
                        <span class="opcion-label">Planos</span>
                    </a>
                    @endpermiso

                    @permiso('gal_tc', 'ver')
                    <a href="{{ route('galeria_tc.index', $obraTc->id) }}" class="opcion-card" style="animation-delay:0.12s">
                        <div class="opcion-icon ic-teal"><i class="fas fa-images"></i></div>
                        <span class="opcion-label">Galería de Fotos</span>
                    </a>
                    @endpermiso

                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL EDITAR OBRA
══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-editar-obra">
    <div class="modal-caja">
        <div class="modal-head">
            <div class="modal-head-title"><i class="fas fa-pen"></i> Editar Obra</div>
            <button class="modal-close" onclick="cerrarModalEditarObra()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>

        <form id="form-editar-obra" method="POST" action="{{ route('obras_tc.update', $obraTc->id) }}">
            @csrf
            @method('PATCH')

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="input-editar-nombre-obra">Nombre <span>*</span></label>
                    <input type="text" id="input-editar-nombre-obra" name="nombre" class="form-control" value="{{ $obraTc->descripcion }}" placeholder="Nombre de la obra" autocomplete="off" required>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" for="input-editar-mensaje-obra">Mensaje</label>
                    <textarea id="input-editar-mensaje-obra" name="mensaje" class="form-control" placeholder="Mensaje o aclaración para la obra" rows="4">{{ $obraTc->mensaje }}</textarea>
                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn-cancel" onclick="cerrarModalEditarObra()">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL ELIMINAR OBRA
══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-eliminar-obra">
    <div class="modal-caja">
        <div class="modal-head">
            <div class="modal-head-title danger"><i class="fas fa-triangle-exclamation"></i> Eliminar Obra</div>
            <button class="modal-close" onclick="cerrarModalEliminarObra()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>

        <div class="modal-body">
            <p>¿Seguro que querés eliminar la obra <strong>{{ $obraTc->descripcion }}</strong>? Vas a dejar de verla en el listado de Trabajo de Campo.</p>
        </div>

        <form id="form-eliminar-obra" method="POST" action="{{ route('obras_tc.destroy', $obraTc->id) }}">
            @csrf
            @method('DELETE')

            <div class="modal-foot">
                <button type="button" class="btn-cancel" onclick="cerrarModalEliminarObra()">Cancelar</button>
                <button type="submit" class="btn-confirmar-eliminar">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalEditarObra() {
        document.getElementById('modal-editar-obra').classList.add('active');
        setTimeout(() => document.getElementById('input-editar-nombre-obra').focus(), 0);
    }

    function cerrarModalEditarObra() {
        document.getElementById('modal-editar-obra').classList.remove('active');
    }

    document.getElementById('modal-editar-obra').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalEditarObra();
    });

    function abrirModalEliminarObra() {
        document.getElementById('modal-eliminar-obra').classList.add('active');
    }

    function cerrarModalEliminarObra() {
        document.getElementById('modal-eliminar-obra').classList.remove('active');
    }

    document.getElementById('modal-eliminar-obra').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalEliminarObra();
    });

    @if($errors->any())
    abrirModalEditarObra();
    @endif
</script>
</body>
</html>
