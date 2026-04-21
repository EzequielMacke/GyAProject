<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Documento</title>
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
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) { font-family: 'Plus Jakarta Sans', sans-serif; }

        .ph { padding: 1.75rem 0 1.5rem; display: flex; align-items: flex-end; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; }

        .btn { height: 38px; padding: 0 1rem; border-radius: 0.55rem; display: inline-flex; align-items: center; gap: 0.42rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.825rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); text-decoration: none; cursor: pointer; transition: all 0.14s; white-space: nowrap; }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; }
        .btn:disabled, .btn[disabled] { opacity: 0.45; pointer-events: none; }

        /* Layout dos columnas */
        .gen-layout { display: grid; grid-template-columns: 340px 1fr; gap: 1.25rem; align-items: start; }
        @media (max-width: 900px) { .gen-layout { grid-template-columns: 1fr; } }

        /* Panel */
        .panel { background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem; overflow: hidden; }
        .panel-header { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; padding: 0.9rem 1.2rem; background: var(--surface2); border-bottom: 1.5px solid var(--border); font-size: 0.82rem; font-weight: 700; color: var(--text2); }
        .panel-header i { color: var(--accent); }
        .panel-header-left { display: flex; align-items: center; gap: 0.5rem; }

        /* Buscador */
        .bib-search-wrap { display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; border-bottom: 1px solid var(--border); background: var(--surface); }
        .bib-search-icon { color: var(--muted); font-size: 0.78rem; flex-shrink: 0; }
        .bib-search-input { flex: 1; border: none; outline: none; background: transparent; font-size: 0.82rem; color: var(--text); }
        .bib-search-input::placeholder { color: var(--muted); }

        /* Lista de selección */
        .bib-list { max-height: calc(100vh - 310px); overflow-y: auto; }
        .bib-item { display: flex; align-items: center; gap: 0.85rem; padding: 0.85rem 1.2rem; border-bottom: 1px solid var(--border); cursor: pointer; transition: background 0.12s; user-select: none; }
        .bib-item:last-child { border-bottom: none; }
        .bib-item:hover { background: var(--surface2); }
        .bib-item.selected { background: var(--accent-s); }

        .bib-checkbox { width: 17px; height: 17px; border: 2px solid var(--border2); border-radius: 0.3rem; flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: all 0.14s; }
        .bib-item.selected .bib-checkbox { background: var(--accent); border-color: var(--accent); }
        .bib-checkbox i { display: none; font-size: 0.65rem; color: #fff; }
        .bib-item.selected .bib-checkbox i { display: block; }

        .bib-info { flex: 1; min-width: 0; }
        .bib-info-nombre { font-size: 0.84rem; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .bib-info-fuente { font-size: 0.75rem; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .bib-badge { font-size: 0.68rem; font-weight: 700; background: var(--surface2); border: 1px solid var(--border); color: var(--muted); border-radius: 0.35rem; padding: 0.15rem 0.45rem; white-space: nowrap; flex-shrink: 0; }
        .bib-item.selected .bib-badge { background: var(--accent-s); border-color: var(--accent); color: var(--accent); }

        .panel-footer { padding: 0.85rem 1.2rem; border-top: 1.5px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; }
        .sel-count { font-size: 0.78rem; font-weight: 600; color: var(--muted); }
        .sel-count span { color: var(--accent); }

        /* Preview */
        .preview-empty { padding: 4rem 2rem; text-align: center; }
        .preview-empty-icon { width: 48px; height: 48px; border-radius: 0.7rem; background: var(--accent-s); color: var(--accent); display: inline-flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 1rem; }
        .preview-empty-title { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 0.3rem; }
        .preview-empty-sub { font-size: 0.82rem; color: var(--muted); }

        .preview-list { display: flex; flex-direction: column; }
        .preview-bib { border-bottom: 1.5px solid var(--border); }
        .preview-bib:last-child { border-bottom: none; }
        .preview-bib-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.95rem 1.35rem; cursor: pointer; transition: background 0.12s; }
        .preview-bib-header:hover { background: var(--surface2); }
        .preview-bib-header-left { display: flex; align-items: center; gap: 0.65rem; min-width: 0; }
        .preview-bib-num { font-size: 0.72rem; font-weight: 700; color: var(--muted); min-width: 22px; flex-shrink: 0; }
        .preview-bib-nombre { font-size: 0.88rem; font-weight: 700; color: var(--text); }
        .preview-bib-fuente { font-size: 0.78rem; color: var(--muted); }
        .preview-bib-toggle { color: var(--muted); font-size: 0.72rem; transition: transform 0.18s; flex-shrink: 0; }
        .preview-bib.open .preview-bib-toggle { transform: rotate(180deg); }

        .preview-bib-body { padding: 0 1.35rem 1.1rem 3.5rem; display: none; }
        .preview-bib.open .preview-bib-body { display: block; }

        .preview-detalle { display: flex; gap: 0.65rem; padding: 0.45rem 0; border-bottom: 1px dashed var(--border); }
        .preview-detalle:last-child { border-bottom: none; }
        .preview-detalle-num { font-size: 0.7rem; color: var(--muted); min-width: 18px; padding-top: 0.05rem; flex-shrink: 0; }
        .preview-detalle-inner { flex: 1; }
        .preview-detalle-tipo { font-size: 0.67rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.15rem; }
        .preview-detalle-texto { font-size: 0.82rem; color: var(--text2); line-height: 1.5; white-space: pre-wrap; }
        .preview-detalle-img { display: flex; align-items: center; gap: 0.45rem; font-size: 0.8rem; color: var(--muted); }
        .preview-detalle-img i { color: var(--accent); }
        .preview-no-content { font-size: 0.8rem; color: var(--muted); padding: 0.35rem 0; }

        .drag-order { color: var(--border2); cursor: grab; font-size: 0.78rem; padding: 0.1rem 0.35rem 0.1rem 0; flex-shrink: 0; transition: color 0.14s; }
        .drag-order:hover { color: var(--muted); }
        .drag-order:active { cursor: grabbing; }
        .sortable-ghost { opacity: 0.35; background: var(--accent-s) !important; }

        .empty-list { padding: 2.5rem 1.5rem; text-align: center; color: var(--muted); font-size: 0.83rem; }
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
                            <a href="{{ route('bibliografia.index') }}">Bibliografías</a>
                            <i class="fas fa-chevron-right"></i>
                            <i class="fas fa-file-word"></i> Generar documento
                        </div>
                        <h1 class="ph-title">Generar <em>Documento</em></h1>
                        <p class="ph-sub">Seleccioná las bibliografías y ordená su posición en el documento</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('bibliografia.index') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="gen-layout">

                    {{-- Panel izquierdo: selección --}}
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-header-left">
                                <i class="fas fa-book"></i> Bibliografías
                            </div>
                            <button type="button" class="btn" style="height:28px;padding:0 0.65rem;font-size:0.73rem;" id="btn-toggle-all">
                                Seleccionar todo
                            </button>
                        </div>

                        <div class="bib-search-wrap">
                            <i class="fas fa-search bib-search-icon"></i>
                            <input type="text" id="bib-search" class="bib-search-input" placeholder="Buscar bibliografía...">
                        </div>

                        @if($bibliografias->isEmpty())
                        <div class="empty-list">
                            <i class="fas fa-book" style="font-size:1.4rem;display:block;margin-bottom:0.5rem;"></i>
                            No hay bibliografías disponibles
                        </div>
                        @else
                        <div class="bib-list" id="bib-list">
                            @foreach($bibliografias as $bib)
                            <div class="bib-item"
                                data-id="{{ $bib->id }}"
                                onclick="toggleBib(this)">
                                <div class="bib-checkbox"><i class="fas fa-check"></i></div>
                                <div class="bib-info">
                                    <div class="bib-info-nombre">{{ $bib->nombre }}</div>
                                    <div class="bib-info-fuente">{{ $bib->fuente }}</div>
                                </div>
                                <div class="bib-badge">{{ $bib->detalles->count() }} elem.</div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="panel-footer">
                            <div class="sel-count"><span id="count-sel">0</span> seleccionadas</div>
                            <button type="button" class="btn btn-primary" id="btn-generar" disabled onclick="generarDocumento()">
                                <i class="fas fa-file-word"></i> Generar
                            </button>
                        </div>
                    </div>

                    {{-- Panel derecho: preview --}}
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-header-left">
                                <i class="fas fa-eye"></i> Vista previa y orden
                            </div>
                        </div>

                        <div id="preview-empty" class="preview-empty">
                            <div class="preview-empty-icon"><i class="fas fa-mouse-pointer"></i></div>
                            <div class="preview-empty-title">Nada seleccionado</div>
                            <div class="preview-empty-sub">Hacé clic en una bibliografía para agregarla al documento</div>
                        </div>

                        <div class="preview-list" id="preview-list"></div>
                    </div>

                </div>

                {{-- Form oculto --}}
                <form id="form-generar" method="POST" action="{{ route('bibliografia.generateWord') }}" style="display:none;">
                    @csrf
                    <div id="form-ids"></div>
                </form>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    const bibData = @json($bibliografias->keyBy('id'));
    const IDS_IMAGEN = [6, 7, 8];
    let seleccionados = [];

    Sortable.create(document.getElementById('preview-list'), {
        handle: '.drag-order',
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: sincronizarOrden
    });

    document.getElementById('bib-search')?.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('#bib-list .bib-item').forEach(function (item) {
            const nombre = item.querySelector('.bib-info-nombre')?.textContent.toLowerCase() ?? '';
            const fuente = item.querySelector('.bib-info-fuente')?.textContent.toLowerCase() ?? '';
            item.style.display = (nombre.includes(q) || fuente.includes(q)) ? '' : 'none';
        });
    });

    function toggleBib(el) {
        const id = el.dataset.id;
        if (el.classList.contains('selected')) {
            el.classList.remove('selected');
            seleccionados = seleccionados.filter(x => x !== id);
            document.getElementById('pbib-' + id)?.remove();
        } else {
            el.classList.add('selected');
            seleccionados.push(id);
            renderPreview(id);
        }
        sincronizarOrden();
        actualizarUI();
    }

    function renderPreview(id) {
        const bib = bibData[id];
        if (!bib) return;

        const detallesHtml = bib.detalles.length === 0
            ? `<div class="preview-no-content">Sin contenido cargado</div>`
            : bib.detalles.map((d, i) => {
                const esImg = IDS_IMAGEN.includes(d.elemento_plantilla_id);
                const tipo  = d.elemento_plantilla?.nombre ?? '—';
                const body  = esImg
                    ? `<div class="preview-detalle-img"><i class="fas fa-image"></i>${esc(d.descripcion ?? 'Sin imagen')}${d.tamanio ? ' · ' + d.tamanio + ' cm' : ''}</div>`
                    : `<div class="preview-detalle-texto">${esc(d.descripcion ?? '')}</div>`;
                return `<div class="preview-detalle">
                    <div class="preview-detalle-num">${i + 1}</div>
                    <div class="preview-detalle-inner">
                        <div class="preview-detalle-tipo">${esc(tipo)}</div>${body}
                    </div>
                </div>`;
            }).join('');

        const div = document.createElement('div');
        div.className = 'preview-bib open';
        div.id = 'pbib-' + id;
        div.innerHTML = `
            <div class="preview-bib-header" onclick="toggleAccordion(this)">
                <div class="preview-bib-header-left">
                    <span class="drag-order" onclick="event.stopPropagation()" title="Reordenar"><i class="fas fa-grip-vertical"></i></span>
                    <span class="preview-bib-num preview-orden">#1</span>
                    <div>
                        <div class="preview-bib-nombre">${esc(bib.nombre)}</div>
                        <div class="preview-bib-fuente">${esc(bib.fuente)}</div>
                    </div>
                </div>
                <i class="fas fa-chevron-up preview-bib-toggle"></i>
            </div>
            <div class="preview-bib-body">${detallesHtml}</div>`;

        document.getElementById('preview-list').appendChild(div);
    }

    function toggleAccordion(header) {
        header.closest('.preview-bib').classList.toggle('open');
    }

    function sincronizarOrden() {
        seleccionados = [];
        document.querySelectorAll('#preview-list .preview-bib').forEach((el, i) => {
            seleccionados.push(el.id.replace('pbib-', ''));
            el.querySelector('.preview-orden').textContent = '#' + (i + 1);
        });
    }

    function actualizarUI() {
        const n = seleccionados.length;
        document.getElementById('count-sel').textContent = n;
        document.getElementById('btn-generar').disabled = n === 0;
        document.getElementById('preview-empty').style.display = n === 0 ? '' : 'none';

        const total = document.querySelectorAll('#bib-list .bib-item').length;
        document.getElementById('btn-toggle-all').textContent = n === total ? 'Deseleccionar todo' : 'Seleccionar todo';
    }

    document.getElementById('btn-toggle-all')?.addEventListener('click', function () {
        const items = document.querySelectorAll('#bib-list .bib-item');
        const allSel = seleccionados.length === items.length;
        if (allSel) {
            items.forEach(el => {
                el.classList.remove('selected');
                document.getElementById('pbib-' + el.dataset.id)?.remove();
            });
            seleccionados = [];
        } else {
            items.forEach(el => {
                if (!el.classList.contains('selected')) {
                    el.classList.add('selected');
                    seleccionados.push(el.dataset.id);
                    renderPreview(el.dataset.id);
                }
            });
        }
        sincronizarOrden();
        actualizarUI();
    });

    function generarDocumento() {
        const form = document.getElementById('form-generar');
        const container = document.getElementById('form-ids');
        container.innerHTML = '';
        seleccionados.forEach((id, i) => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'bibliografias[' + i + ']';
            inp.value = id;
            container.appendChild(inp);
        });
        form.submit();
    }

    function esc(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
</script>
</body>
</html>
