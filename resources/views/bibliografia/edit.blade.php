<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Bibliografía</title>
    @include('partials.head')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
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
        .btn-sm { height: 30px; padding: 0 0.7rem; font-size: 0.75rem; border-radius: 0.45rem; }

        .section-card { background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem; margin-bottom: 1.25rem; overflow: hidden; }
        .section-header { display: flex; align-items: center; gap: 0.5rem; padding: 0.9rem 1.35rem; background: var(--surface2); border-bottom: 1.5px solid var(--border); font-size: 0.82rem; font-weight: 700; color: var(--text2); }
        .section-header i { color: var(--accent); }
        .section-body { padding: 1.35rem; }

        .form-group { margin-bottom: 1.1rem; }
        .form-group:last-child { margin-bottom: 0; }
        .form-label { display: block; font-size: 0.78rem; font-weight: 600; color: var(--text2); margin-bottom: 0.4rem; }
        .form-control { width: 100%; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem; background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.55rem; padding: 0.6rem 0.85rem; color: var(--text); outline: none; transition: border-color 0.15s, box-shadow 0.15s; }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        .elementos-btns { display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .btn-elemento { height: 34px; padding: 0 0.85rem; border-radius: 0.5rem; display: inline-flex; align-items: center; gap: 0.35rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.78rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface2); color: var(--text2); cursor: pointer; transition: all 0.14s; }
        .btn-elemento:hover { background: var(--accent-s); border-color: var(--accent); color: var(--accent); }
        .btn-elemento i { font-size: 0.72rem; }

        .detalle-lista { display: flex; flex-direction: column; gap: 0.6rem; }
        .detalle-item { display: flex; align-items: flex-start; gap: 0.75rem; background: var(--surface2); border: 1.5px solid var(--border); border-radius: 0.65rem; padding: 0.85rem 1rem; }
        .detalle-item-label { font-size: 0.72rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap; padding-top: 0.15rem; min-width: 80px; }
        .detalle-item-input { flex: 1; }
        .detalle-item-input .form-control { background: var(--surface); }
        .detalle-item-remove { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 0.8rem; padding: 0.2rem; border-radius: 0.3rem; transition: color 0.14s; flex-shrink: 0; margin-top: 0.1rem; }
        .detalle-item-remove:hover { color: #c0392b; }
        .drag-handle { color: var(--border2); cursor: grab; font-size: 0.78rem; padding: 0.1rem 0.3rem 0.1rem 0; flex-shrink: 0; margin-top: 0.15rem; transition: color 0.14s; }
        .drag-handle:hover { color: var(--muted); }
        .drag-handle:active { cursor: grabbing; }
        .sortable-ghost { opacity: 0.4; background: var(--accent-s) !important; }
        .sortable-chosen { background: var(--surface2); }

        .file-upload-area { border: 2px dashed var(--border2); border-radius: 0.65rem; padding: 1.25rem 1rem; text-align: center; cursor: pointer; transition: border-color 0.15s, background 0.15s; background: var(--surface); }
        .file-upload-area:hover { border-color: var(--accent); background: var(--accent-s); }
        .file-upload-area.has-file { border-color: var(--accent); border-style: solid; background: var(--accent-s); }
        .file-upload-area i { font-size: 1.4rem; color: var(--muted); display: block; margin-bottom: 0.4rem; }
        .file-upload-area.has-file i { color: var(--accent); }
        .file-upload-label { font-size: 0.78rem; font-weight: 600; color: var(--muted); }
        .file-upload-area.has-file .file-upload-label { color: var(--accent); }
        .file-upload-input { display: none; }

        .img-actual { font-size: 0.75rem; color: var(--muted); margin-top: 0.4rem; display: flex; align-items: center; gap: 0.3rem; }
        .img-actual i { color: var(--accent); }

        .empty-elementos { text-align: center; padding: 2rem; color: var(--muted); font-size: 0.82rem; border: 1.5px dashed var(--border); border-radius: 0.65rem; }
        .form-actions { display: flex; gap: 0.5rem; margin-top: 1.25rem; }
        .alert-error { background: #fff0f0; color: #c0392b; border: 1px solid #f5c2c2; padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; margin-bottom: 1.25rem; }
        .alert-error ul { margin: 0.4rem 0 0 1rem; }

        /* NOTA AL PIE SWITCH */
        .nota-pie-toggle-row { display: flex; align-items: center; gap: 0.55rem; margin-top: 0.7rem; padding-top: 0.7rem; border-top: 1.5px dashed var(--border); }
        .toggle-switch { position: relative; display: inline-block; width: 36px; height: 20px; flex-shrink: 0; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; position: absolute; }
        .toggle-slider { position: absolute; cursor: pointer; inset: 0; background: var(--border2); border-radius: 20px; transition: background 0.2s; }
        .toggle-slider::before { content: ''; position: absolute; width: 14px; height: 14px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: transform 0.2s; }
        .toggle-switch input:checked + .toggle-slider { background: var(--accent); }
        .toggle-switch input:checked + .toggle-slider::before { transform: translateX(16px); }
        .nota-pie-label { font-size: 0.75rem; font-weight: 700; color: var(--text2); }
        .nota-pie-panel { margin-top: 0.55rem; display: none; }
        .nota-pie-panel.visible { display: block; }
        .nota-pie-aviso { display: flex; align-items: flex-start; gap: 0.45rem; background: #fffbea; border: 1px solid #e8cc50; border-radius: 0.45rem; padding: 0.5rem 0.7rem; font-size: 0.74rem; color: #7a5800; margin-bottom: 0.5rem; line-height: 1.45; }
        .nota-pie-aviso i { font-size: 0.7rem; margin-top: 0.15rem; flex-shrink: 0; color: #b38a00; }
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
                            <i class="fas fa-pencil-alt"></i> Editar
                        </div>
                        <h1 class="ph-title">Editar <em>Bibliografía</em></h1>
                        <p class="ph-sub">{{ $bib->nombre }}</p>
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

                @if($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('bibliografia.update', $bib->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Sección 1: Datos generales --}}
                    <div class="section-card">
                        <div class="section-header">
                            <i class="fas fa-info-circle"></i> Datos generales
                        </div>
                        <div class="section-body">
                            <div class="form-group">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre"
                                    value="{{ old('nombre', $bib->nombre) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Fuente</label>
                                <input type="text" class="form-control" name="fuente"
                                    value="{{ old('fuente', $bib->fuente) }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- Sección 2: Contenido --}}
                    <div class="section-card">
                        <div class="section-header">
                            <i class="fas fa-layer-group"></i> Contenido
                        </div>
                        <div class="section-body">

                            @php
                                // Construir pares [detalle_texto, detalle_nota_pie_o_null]
                                $detallesCol = $bib->detalles->values();
                                $pairs = [];
                                $pi = 0;
                                while ($pi < $detallesCol->count()) {
                                    $d = $detallesCol[$pi];
                                    if ($d->elemento_plantilla_id == 9) { $pi++; continue; }
                                    $np = null;
                                    if ($pi + 1 < $detallesCol->count() && $detallesCol[$pi + 1]->elemento_plantilla_id == 9) {
                                        $np = $detallesCol[$pi + 1];
                                        $pi += 2;
                                    } else {
                                        $pi++;
                                    }
                                    $pairs[] = [$d, $np];
                                }
                            @endphp

                            <div class="detalle-lista" id="detalle-lista">
                                @forelse($pairs as $pairIdx => $pair)
                                @php
                                    [$detalle, $npDetalle] = $pair;
                                    $esImagen = in_array($detalle->elemento_plantilla_id, [6,7,8]);
                                    $uid = 'file-ex-'.$detalle->id;
                                    $npUid = 'np-ex-'.$detalle->id;
                                @endphp
                                <div class="detalle-item">
                                    <span class="drag-handle" title="Arrastrar"><i class="fas fa-grip-vertical"></i></span>
                                    <input type="hidden" name="detalles[{{ $pairIdx }}][id]" value="{{ $detalle->id }}">
                                    <input type="hidden" name="detalles[{{ $pairIdx }}][elemento_id]" value="{{ $detalle->elemento_plantilla_id }}">
                                    <input type="hidden" class="orden-input" name="detalles[{{ $pairIdx }}][orden]" value="{{ $detalle->orden }}">
                                    <input type="hidden" class="estado-input" name="detalles[{{ $pairIdx }}][estado]" value="1">
                                    @if($npDetalle)
                                    <input type="hidden" name="detalles[{{ $pairIdx }}][nota_pie_id]" value="{{ $npDetalle->id }}">
                                    @endif
                                    <div class="detalle-item-label">{{ $detalle->elementoPlantilla->nombre ?? '—' }}</div>
                                    <div class="detalle-item-input">
                                        @if($esImagen)
                                            <div class="file-upload-area" onclick="document.getElementById('{{ $uid }}').click()" id="area-{{ $uid }}">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <span class="file-upload-label" id="label-{{ $uid }}">Hacé clic para cambiar la imagen</span>
                                                <input type="file" class="file-upload-input" id="{{ $uid }}"
                                                    name="detalles[{{ $pairIdx }}][descripcion]" accept="image/*"
                                                    onchange="actualizarLabel('{{ $uid }}', this)">
                                            </div>
                                            @if($detalle->descripcion)
                                            <div class="img-actual"><i class="fas fa-image"></i> Actual: {{ $detalle->descripcion }}</div>
                                            @endif
                                            <input type="hidden" name="detalles[{{ $pairIdx }}][descripcion_actual]" value="{{ $detalle->descripcion }}">
                                            <div style="display:flex;align-items:center;gap:0.5rem;margin-top:0.6rem;">
                                                <label style="font-size:0.75rem;font-weight:600;color:var(--text2);white-space:nowrap;">Ancho (cm)</label>
                                                <input type="number" class="form-control" name="detalles[{{ $pairIdx }}][tamanio]"
                                                    value="{{ $detalle->tamanio }}" min="1" max="16" step="0.5"
                                                    placeholder="Máx. 16 cm" style="max-width:130px;" required
                                                    oninput="if(this.value>16)this.value=16;">
                                                <span style="font-size:0.75rem;color:var(--muted);">máx. 16 cm</span>
                                            </div>
                                        @else
                                            <textarea class="form-control" name="detalles[{{ $pairIdx }}][descripcion]" rows="2" required>{{ $detalle->descripcion }}</textarea>
                                            <div class="nota-pie-toggle-row">
                                                <label class="toggle-switch">
                                                    <input type="checkbox" onchange="toggleNotaPie(this, '{{ $npUid }}')"
                                                        {{ $npDetalle ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                                <span class="nota-pie-label"><i class="fas fa-quote-right" style="font-size:0.68rem;margin-right:0.25rem;color:var(--muted);"></i>Nota al Pie</span>
                                            </div>
                                            <div class="nota-pie-panel {{ $npDetalle ? 'visible' : '' }}" id="np-panel-{{ $npUid }}">
                                                <div class="nota-pie-aviso">
                                                    <i class="fas fa-info-circle"></i>
                                                    Si no completás el texto, la nota al pie se completará automáticamente con el autor.
                                                </div>
                                                <textarea class="form-control" name="detalles[{{ $pairIdx }}][nota_pie]"
                                                    rows="2" placeholder="Texto de la nota al pie (opcional)..."
                                                    id="np-textarea-{{ $npUid }}"
                                                    {{ $npDetalle ? '' : 'disabled' }}>{{ $npDetalle?->descripcion ?? '' }}</textarea>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="detalle-item-remove" onclick="eliminarItem(this)" title="Quitar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @empty
                                <div class="empty-elementos" id="empty-msg">
                                    <i class="fas fa-layer-group" style="font-size:1.5rem;margin-bottom:0.5rem;display:block;"></i>
                                    Usá los botones de abajo para agregar contenido
                                </div>
                                @endforelse
                            </div>

                            <div class="elementos-btns" style="margin-top:1.25rem;margin-bottom:0;">
                                @foreach($elementos as $el)
                                @if($el->id == 9) @continue @endif
                                <button type="button" class="btn-elemento"
                                    data-id="{{ $el->id }}"
                                    data-nombre="{{ $el->nombre }}"
                                    onclick="agregarElemento(this)">
                                    <i class="fas fa-plus"></i> {{ $el->nombre }}
                                </button>
                                @endforeach
                            </div>

                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar cambios
                        </button>
                    </div>

                </form>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
    let contador = 1000;

    const sortable = Sortable.create(document.getElementById('detalle-lista'), {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: actualizarOrdenes
    });

    function actualizarOrdenes() {
        document.querySelectorAll('#detalle-lista .detalle-item').forEach((item, index) => {
            const ordenInput = item.querySelector('.orden-input');
            if (ordenInput) ordenInput.value = index + 1;
        });
    }

    const idsImagen   = [6, 7, 8];
    const ID_NOTA_PIE = 9;

    function agregarElemento(btn) {
        const id       = btn.dataset.id;
        const nombre   = btn.dataset.nombre;
        const esImagen = idsImagen.includes(parseInt(id));
        const idx      = contador;

        document.getElementById('empty-msg')?.remove();

        const uid = `file-${idx}`;
        let inputHtml;

        if (esImagen) {
            inputHtml = `<div class="file-upload-area" onclick="document.getElementById('${uid}').click()" id="area-${uid}">
                <i class="fas fa-cloud-upload-alt"></i>
                <span class="file-upload-label" id="label-${uid}">Hacé clic para subir una imagen</span>
                <input type="file" class="file-upload-input" id="${uid}" name="detalles[${idx}][descripcion]" accept="image/*" required
                    onchange="actualizarLabel('${uid}', this)">
               </div>
               <div style="display:flex;align-items:center;gap:0.5rem;margin-top:0.6rem;">
                   <label style="font-size:0.75rem;font-weight:600;color:var(--text2);white-space:nowrap;">Ancho (cm)</label>
                   <input type="number" class="form-control" name="detalles[${idx}][tamanio]"
                       min="1" max="16" step="0.5" placeholder="Máx. 16 cm"
                       style="max-width:130px;" required
                       oninput="if(this.value>16)this.value=16;">
                   <span style="font-size:0.75rem;color:var(--muted);">máx. 16 cm</span>
               </div>`;
        } else {
            inputHtml = `<textarea class="form-control" name="detalles[${idx}][descripcion]" rows="2" placeholder="Ingresá el contenido..." required></textarea>
            <div class="nota-pie-toggle-row">
                <label class="toggle-switch">
                    <input type="checkbox" onchange="toggleNotaPie(this, ${idx})">
                    <span class="toggle-slider"></span>
                </label>
                <span class="nota-pie-label"><i class="fas fa-quote-right" style="font-size:0.68rem;margin-right:0.25rem;color:var(--muted);"></i>Nota al Pie</span>
            </div>
            <div class="nota-pie-panel" id="np-panel-${idx}">
                <div class="nota-pie-aviso">
                    <i class="fas fa-info-circle"></i>
                    Si no agregas texto, la nota al pie se completará automáticamente con la fuente.
                </div>
                <textarea class="form-control" name="detalles[${idx}][nota_pie]" rows="2"
                    placeholder="Texto de la nota al pie (opcional)..."
                    id="np-textarea-${idx}" disabled></textarea>
            </div>`;
        }

        const item = document.createElement('div');
        item.className = 'detalle-item';
        item.innerHTML = `
            <span class="drag-handle" title="Arrastrar"><i class="fas fa-grip-vertical"></i></span>
            <input type="hidden" name="detalles[${idx}][elemento_id]" value="${id}">
            <input type="hidden" class="orden-input" name="detalles[${idx}][orden]" value="0">
            <div class="detalle-item-label">${nombre}</div>
            <div class="detalle-item-input">${inputHtml}</div>
            <button type="button" class="detalle-item-remove" onclick="eliminarItem(this)" title="Quitar">
                <i class="fas fa-times"></i>
            </button>
        `;

        document.getElementById('detalle-lista').appendChild(item);
        actualizarOrdenes();
        contador++;
    }

    function toggleNotaPie(checkbox, uid) {
        const panel    = document.getElementById('np-panel-' + uid);
        const textarea = document.getElementById('np-textarea-' + uid);
        panel.classList.toggle('visible', checkbox.checked);
        textarea.disabled = !checkbox.checked;
        if (checkbox.checked) textarea.focus();
    }

    function actualizarLabel(uid, input) {
        const area  = document.getElementById('area-' + uid);
        const label = document.getElementById('label-' + uid);
        if (input.files && input.files[0]) {
            label.textContent = input.files[0].name;
            area.classList.add('has-file');
        }
    }

    function eliminarItem(btn) {
        const lista = document.getElementById('detalle-lista');
        const item  = btn.closest('.detalle-item');
        const estadoInput = item.querySelector('.estado-input');

        if (estadoInput) {
            // Item existente: ocultar y marcar estado=2 (soft delete)
            estadoInput.value = 2;
            item.querySelectorAll('[required]').forEach(el => el.removeAttribute('required'));
            item.style.display = 'none';
        } else {
            // Item nuevo: eliminar del DOM directamente
            item.remove();
        }

        actualizarOrdenes();
        const visibles = lista.querySelectorAll('.detalle-item:not([style*="display: none"])');
        if (visibles.length === 0) {
            if (!document.getElementById('empty-msg')) {
                const empty = document.createElement('div');
                empty.className = 'empty-elementos';
                empty.id = 'empty-msg';
                empty.innerHTML = '<i class="fas fa-layer-group" style="font-size:1.5rem;margin-bottom:0.5rem;display:block;"></i>Usá los botones de abajo para agregar contenido';
                lista.appendChild(empty);
            }
        }
    }
</script>
</body>
</html>
