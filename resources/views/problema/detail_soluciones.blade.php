<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de la Solución</title>
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
            --orange:   #d9622a;
            --orange-s: #fff0eb;
            --green:    #1e9166;
            --green-s:  #e5f6f0;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .ph { padding: 1.75rem 0 1.5rem; display: flex; align-items: flex-end; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
        .ph-title em { font-style: normal; color: var(--green); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        .btn { height: 38px; padding: 0 1rem; border-radius: 0.55rem; display: inline-flex; align-items: center; gap: 0.42rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.825rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); text-decoration: none; cursor: pointer; transition: all 0.14s; white-space: nowrap; }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; }
        .btn-sm { height: 30px; padding: 0 0.7rem; font-size: 0.75rem; border-radius: 0.45rem; }
        .btn-danger-soft { background: #fff0f0; border-color: #f5c2c2; color: #c0392b; }
        .btn-danger-soft:hover { background: #fde0e0; border-color: #e07070; color: #a93226; }
        .btn-green { background: var(--green); border-color: var(--green); color: #fff; }
        .btn-green:hover { background: #187a58; border-color: #187a58; color: #fff; }

        .alert { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert-success { background: var(--green-s); color: var(--green); border: 1px solid #b6e8d6; }

        .card { background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem; overflow: hidden; margin-bottom: 1.5rem; }
        .card-header { display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1.35rem; background: var(--surface2); border-bottom: 1.5px solid var(--border); }
        .card-title { font-size: 0.85rem; font-weight: 700; color: var(--text2); display: flex; align-items: center; gap: 0.5rem; text-transform: uppercase; letter-spacing: 0.04em; }
        .card-title i { color: var(--green); font-size: 0.8rem; }
        .card-body { padding: 1.25rem 1.35rem; }

        .sol-desc-text { font-size: 1rem; font-weight: 600; color: var(--text); line-height: 1.55; }
        .sol-context { margin-top: 0.6rem; padding: 0.65rem 0.85rem; background: var(--surface2); border-radius: 0.5rem; border-left: 3px solid var(--border2); font-size: 0.78rem; color: var(--muted); line-height: 1.45; }
        .sol-context strong { color: var(--text2); }
        .prob-meta { display: flex; align-items: center; gap: 1.5rem; margin-top: 0.75rem; flex-wrap: wrap; }
        .prob-meta-item { display: flex; align-items: center; gap: 0.4rem; font-size: 0.78rem; color: var(--muted); }
        .prob-autor-avatar { width: 24px; height: 24px; border-radius: 50%; background: var(--green-s); color: var(--green); display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 700; }

        .obs-text { font-size: 0.875rem; color: var(--text2); line-height: 1.65; white-space: pre-wrap; }
        .obs-empty { font-size: 0.83rem; color: var(--muted); font-style: italic; }

        .form-control { width: 100%; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.55rem; padding: 0.7rem 0.9rem; color: var(--text); outline: none; transition: border-color 0.15s, box-shadow 0.15s; resize: vertical; }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .form-actions { display: flex; gap: 0.5rem; margin-top: 0.75rem; justify-content: flex-end; }

        .fotos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 0.85rem; }
        .foto-card { border: 1.5px solid var(--border); border-radius: 0.65rem; overflow: hidden; background: var(--surface2); }
        .foto-card img { width: 100%; aspect-ratio: 4/3; object-fit: cover; display: block; cursor: pointer; transition: opacity 0.14s; }
        .foto-card img:hover { opacity: 0.88; }
        .foto-card-footer { padding: 0.4rem 0.5rem; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border); }
        .foto-autor { font-size: 0.68rem; color: var(--muted); }
        .btn-foto-del { background: none; border: none; cursor: pointer; color: #c0392b; font-size: 0.72rem; padding: 0.2rem 0.3rem; border-radius: 0.3rem; transition: background 0.14s; }
        .btn-foto-del:hover { background: #fde0e0; }

        .upload-zone { border: 2px dashed var(--border2); border-radius: 0.65rem; padding: 1.5rem; text-align: center; cursor: pointer; transition: border-color 0.15s, background 0.15s; background: var(--surface2); }
        .upload-zone:hover, .upload-zone.drag { border-color: var(--accent); background: var(--accent-s); }
        .upload-zone i { font-size: 1.5rem; color: var(--muted); margin-bottom: 0.4rem; display: block; }
        .upload-zone p { font-size: 0.8rem; color: var(--muted); }
        .upload-zone p span { color: var(--accent); font-weight: 600; }
        #input-fotos { display: none; }
        #preview-fotos { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.75rem; }
        .preview-thumb { width: 72px; height: 72px; border-radius: 0.4rem; object-fit: cover; border: 1.5px solid var(--border); }

        .modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(10,18,30,0.45); z-index: 1050; align-items: center; justify-content: center; }
        .modal-backdrop.open { display: flex; }
        .modal-box { background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.95rem; width: 100%; max-width: 480px; box-shadow: 0 16px 48px rgba(0,0,0,0.15); animation: modalIn 0.18s ease both; }
        @keyframes modalIn { from { opacity: 0; transform: translateY(12px) scale(0.98); } to { opacity: 1; transform: none; } }
        .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 1.1rem 1.35rem 0.9rem; border-bottom: 1.5px solid var(--border); }
        .modal-title { font-size: 0.98rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 0.5rem; }
        .modal-title i { color: #c0392b; }
        .modal-close { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 1rem; padding: 0.2rem; border-radius: 0.35rem; transition: color 0.14s; }
        .modal-close:hover { color: var(--text); }
        .modal-body { padding: 1.25rem 1.35rem; }
        .modal-footer { display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; padding: 0.9rem 1.35rem 1.1rem; border-top: 1.5px solid var(--border); }

        .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.88); z-index: 2000; align-items: center; justify-content: center; cursor: zoom-out; }
        .lightbox.open { display: flex; }
        .lightbox img { max-width: 92vw; max-height: 88vh; border-radius: 0.5rem; box-shadow: 0 8px 48px rgba(0,0,0,0.5); }
        .lightbox-close { position: absolute; top: 1rem; right: 1.25rem; color: #fff; font-size: 1.5rem; cursor: pointer; opacity: 0.7; }
        .lightbox-close:hover { opacity: 1; }
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
                            <a href="{{ route('problemas.index') }}">Problema</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('problemas.detalle', $solucion->problema_id) }}">
                                {{ \Illuminate\Support\Str::limit($solucion->problema->descripcion ?? '—', 40) }}
                            </a>
                            <i class="fas fa-chevron-right"></i>
                            <i class="fas fa-check-circle"></i>
                            Solución
                        </div>
                        <h1 class="ph-title">Detalle de la <em>Solución</em></h1>
                        <p class="ph-sub">{{ $solucion->detalles->count() }} foto(s) cargada(s)</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('problemas.index', $solucion->problema_id) }}" class="btn">
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
                <div class="alert" style="background:#fff0f0;color:#c0392b;border:1px solid #f5c2c2;">
                    <i class="fas fa-ban"></i> {{ session('error') }}
                </div>
                @endif

                @if($solucion->estado == 2)
                <div style="display:flex;align-items:center;gap:0.55rem;background:#fff0f0;border:1.5px solid #f5c2c2;border-radius:0.55rem;padding:0.7rem 1rem;margin-bottom:1.25rem;font-size:0.83rem;color:#c0392b;font-weight:600;">
                    <i class="fas fa-ban"></i> Esta solución está <strong>inactiva</strong>. No se pueden agregar ni editar detalles.
                </div>
                @endif

                {{-- Descripción --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="fas fa-check-circle"></i> Solución</span>
                        <div style="display:flex;align-items:center;gap:0.6rem;">
                            @if($solucion->estado == 2)
                            <span style="font-size:0.68rem;font-weight:700;padding:0.15rem 0.55rem;border-radius:0.3rem;background:#f5c2c2;color:#c0392b;text-transform:uppercase;letter-spacing:0.04em;">Inactiva</span>
                            @endif
                            <span style="font-size:0.75rem;color:var(--muted);">
                                {{ $solucion->stamp ? \Carbon\Carbon::parse($solucion->stamp)->format('d/m/Y H:i') : '' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="sol-desc-text">{{ $solucion->descripcion }}</p>
                        @if($solucion->problema)
                        <div class="sol-context">
                            <strong>Problema:</strong> {{ $solucion->problema->descripcion }}
                        </div>
                        @endif
                        <div class="prob-meta">
                            <span class="prob-meta-item">
                                <span class="prob-autor-avatar">{{ strtoupper(substr($solucion->usuario?->nombre ?? '?', 0, 2)) }}</span>
                                {{ $solucion->usuario?->nombre ?? '—' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Observación --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="fas fa-sticky-note" style="color:var(--accent)"></i> Observación</span>
                        @if($puedeEditarSolucion && $solucion->estado == 1)
                        <button type="button" class="btn btn-sm" id="btn-editar-obs" onclick="toggleObs(true)">
                            <i class="fas fa-pencil-alt"></i> Editar
                        </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div id="obs-vista">
                            @if($solucion->observacion)
                                <p class="obs-text">{{ $solucion->observacion }}</p>
                            @else
                                <p class="obs-empty">Sin observaciones cargadas.</p>
                            @endif
                        </div>
                        @if($puedeEditarSolucion && $solucion->estado == 1)
                        <div id="obs-form" style="display:none;">
                            <form method="POST" action="{{ route('soluciones.observacion', $solucion->id) }}">
                                @csrf @method('PUT')
                                <textarea class="form-control" name="observacion" rows="6"
                                    placeholder="Escribí la observación...">{{ $solucion->observacion }}</textarea>
                                <div class="form-actions">
                                    <button type="button" class="btn" onclick="toggleObs(false)">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Fotos --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="fas fa-images"></i> Fotos</span>
                        @if($puedeAgregarSolucion && $solucion->estado == 1)
                        <button type="button" class="btn btn-sm btn-green" onclick="document.getElementById('upload-section').scrollIntoView({behavior:'smooth'})">
                            <i class="fas fa-plus"></i> Agregar fotos
                        </button>
                        @endif
                    </div>
                    <div class="card-body">

                        @if($solucion->detalles->isEmpty())
                            <p style="font-size:0.83rem;color:var(--muted);font-style:italic;">Sin fotos cargadas.</p>
                        @else
                        <div class="fotos-grid">
                            @foreach($solucion->detalles as $detalle)
                            <div class="foto-card">
                                <img src="{{ asset('storage/soluciones/' . $detalle->foto) }}"
                                     alt="Foto"
                                     onclick="abrirLightbox(this.src)">
                                <div class="foto-card-footer">
                                    <span class="foto-autor">{{ $detalle->usuario?->nombre ?? '—' }}</span>
                                    @if($puedeEliminarSolucion)
                                    <button type="button" class="btn-foto-del"
                                        onclick="confirmarEliminarFoto({{ $detalle->id }})"
                                        title="Eliminar foto">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @if($puedeAgregarSolucion && $solucion->estado == 1)
                        <div id="upload-section" style="margin-top:1.25rem;">
                            <form method="POST" action="{{ route('soluciones.fotos.store', $solucion->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="upload-zone" id="upload-zone" onclick="document.getElementById('input-fotos').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Arrastrá o <span>hacé click</span> para seleccionar fotos</p>
                                    <p style="font-size:0.72rem;margin-top:0.25rem;">JPG, PNG, WEBP — máx. 5 MB por imagen</p>
                                </div>
                                <input type="file" id="input-fotos" name="fotos[]" multiple accept="image/*">
                                <div id="preview-fotos"></div>
                                <div id="upload-actions" style="display:none; justify-content:flex-end; margin-top:0.75rem; gap:0.5rem;">
                                    <button type="button" class="btn" onclick="limpiarUpload()">Cancelar</button>
                                    <button type="submit" class="btn btn-green">
                                        <i class="fas fa-upload"></i> Subir fotos
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif

                    </div>
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- Modal confirmar eliminar foto --}}
<div class="modal-backdrop" id="modal-del-foto">
    <div class="modal-box" style="max-width:400px;">
        <div class="modal-header">
            <span class="modal-title"><i class="fas fa-trash-alt"></i> Eliminar foto</span>
            <button type="button" class="modal-close" onclick="cerrarModal('modal-del-foto')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p style="font-size:0.875rem;color:var(--text2);line-height:1.5;">
                ¿Estás seguro que querés eliminar esta foto? Esta acción no se puede deshacer.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" onclick="cerrarModal('modal-del-foto')">Cancelar</button>
            <button type="button" class="btn btn-danger-soft" onclick="ejecutarEliminarFoto()">
                <i class="fas fa-trash-alt"></i> Eliminar
            </button>
        </div>
    </div>
</div>
<form id="form-del-foto" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

{{-- Lightbox --}}
<div class="lightbox" id="lightbox" onclick="cerrarLightbox()">
    <span class="lightbox-close"><i class="fas fa-times"></i></span>
    <img src="" id="lightbox-img" alt="">
</div>

<script>
    function toggleObs(editar) {
        document.getElementById('obs-vista').style.display = editar ? 'none' : '';
        document.getElementById('obs-form').style.display  = editar ? '' : 'none';
        document.getElementById('btn-editar-obs').style.display = editar ? 'none' : '';
    }

    const inputFotos    = document.getElementById('input-fotos');
    const previewFotos  = document.getElementById('preview-fotos');
    const uploadZone    = document.getElementById('upload-zone');
    const uploadActions = document.getElementById('upload-actions');

    if (inputFotos) {
        inputFotos.addEventListener('change', mostrarPreviews);
        uploadZone?.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('drag'); });
        uploadZone?.addEventListener('dragleave', () => uploadZone.classList.remove('drag'));
        uploadZone?.addEventListener('drop', e => {
            e.preventDefault();
            uploadZone.classList.remove('drag');
            inputFotos.files = e.dataTransfer.files;
            mostrarPreviews();
        });
    }

    function mostrarPreviews() {
        previewFotos.innerHTML = '';
        [...inputFotos.files].forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-thumb';
                previewFotos.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
        if (uploadActions) uploadActions.style.display = inputFotos.files.length ? 'flex' : 'none';
    }

    function limpiarUpload() {
        inputFotos.value = '';
        previewFotos.innerHTML = '';
        if (uploadActions) uploadActions.style.display = 'none';
    }

    function confirmarEliminarFoto(id) {
        const base = "{{ url('/solucion-detalles') }}";
        document.getElementById('form-del-foto').action = base + '/' + id;
        document.getElementById('modal-del-foto').classList.add('open');
    }
    function ejecutarEliminarFoto() {
        document.getElementById('form-del-foto').submit();
    }
    function cerrarModal(id) {
        document.getElementById(id).classList.remove('open');
    }
    document.querySelectorAll('.modal-backdrop').forEach(b => {
        b.addEventListener('click', e => { if (e.target === b) cerrarModal(b.id); });
    });

    function abrirLightbox(src) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox').classList.add('open');
    }
    function cerrarLightbox() {
        document.getElementById('lightbox').classList.remove('open');
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarLightbox(); });
</script>
</body>
</html>
