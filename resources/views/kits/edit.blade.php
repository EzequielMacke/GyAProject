<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Kit</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css">
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
            --green:    #1e9166;
            --green-s:  #e5f6f0;
            --red:      #d94040;
            --red-s:    #fdeaea;
            --slate:    #4e6070;
            --slate-s:  #edf1f4;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .content-wrapper { background: var(--bg) !important; }

        /* PAGE HEADER */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex; align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .ph-crumb {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem;
        }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; }

        /* BUTTONS */
        .btn {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border); background: var(--surface); color: var(--text2);
            text-decoration: none; cursor: pointer; transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }
        .btn-success { background: var(--green); border-color: var(--green); color: #fff; }
        .btn-success:hover { background: #178057; border-color: #178057; color: #fff; }
        .btn-danger { background: var(--red-s); border-color: #f5bcbc; color: var(--red); }
        .btn-danger:hover { background: var(--red); border-color: var(--red); color: #fff; }
        .btn-sm { height: 30px; padding: 0 0.7rem; font-size: 0.75rem; border-radius: 0.4rem; }
        .btn-secondary { background: var(--slate-s); border-color: var(--border2); color: var(--slate); }
        .btn-secondary:hover { background: var(--border); color: var(--text); }

        /* FORM CARD */
        .form-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .form-card-stripe { height: 3px; background: linear-gradient(90deg, var(--accent), #6aaaf5); }
        .form-card-body { padding: 1.75rem 2rem; }

        .section-heading {
            display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.25rem;
        }
        .section-heading-icon {
            width: 30px; height: 30px; border-radius: 0.4rem;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; flex-shrink: 0;
        }
        .section-heading-text { font-size: 0.82rem; font-weight: 700; color: var(--text); }
        .section-divider { height: 1px; background: var(--border); margin: 1.5rem 0; }

        .field-label {
            display: block; font-size: 0.75rem; font-weight: 600;
            color: var(--text2); margin-bottom: 0.4rem; letter-spacing: 0.1px;
        }
        .field-input {
            width: 100%; height: 40px; padding: 0 0.85rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.855rem; color: var(--text);
            background: var(--bg); border: 1.5px solid var(--border);
            border-radius: 0.5rem; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }
        .field-input:focus {
            border-color: var(--accent); background: #fff;
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }
        select.field-input { cursor: pointer; }
        .field-input::placeholder { color: var(--muted); }

        /* TOM SELECT */
        .ts-wrapper { border: none !important; box-shadow: none !important; padding: 0 !important; }
        .ts-wrapper.single .ts-control {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 0.855rem !important; color: var(--text) !important;
            background: var(--bg) !important; border: 1.5px solid var(--border) !important;
            border-radius: 0.5rem !important; min-height: 40px !important; height: 40px !important;
            padding: 0 2rem 0 0.85rem !important; box-shadow: none !important;
            display: flex !important; align-items: center !important;
            cursor: text !important; transition: border-color 0.15s, box-shadow 0.15s, background 0.15s !important;
        }
        .ts-wrapper.single.focus .ts-control {
            border-color: var(--accent) !important; background: #fff !important;
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1) !important;
        }
        .ts-wrapper .ts-control input {
            font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 0.855rem !important;
            color: var(--text) !important; background: transparent !important;
            border: none !important; outline: none !important; box-shadow: none !important;
            padding: 0 !important; margin: 0 !important;
        }
        .ts-wrapper .ts-control input::placeholder { color: var(--muted) !important; }
        .ts-dropdown {
            font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 0.845rem !important;
            background: var(--surface) !important; border: 1.5px solid var(--border2) !important;
            border-radius: 0.55rem !important; box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important; margin-top: 4px !important;
        }
        .ts-dropdown .option { padding: 0.5rem 0.85rem !important; color: var(--text) !important; border-radius: 0 !important; cursor: pointer !important; }
        .ts-dropdown .option:hover, .ts-dropdown .option.active { background: var(--accent-s) !important; color: var(--accent) !important; }
        .ts-dropdown .no-results { padding: 0.6rem 0.85rem !important; color: var(--muted) !important; font-style: italic !important; font-size: 0.82rem !important; }
        .ts-dropdown .option[data-value=""] { display: none !important; }
        .ts-wrapper.single .ts-control:after { display: none !important; }
        .ts-search-icon {
            position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%);
            color: var(--muted); font-size: 0.7rem; pointer-events: none; z-index: 2;
        }

        .add-row {
            display: grid; grid-template-columns: 2fr 1fr 1.4fr auto;
            gap: 0.75rem; align-items: flex-end;
        }
        .field-group { display: flex; flex-direction: column; }

        /* INSUMO LIST */
        .insumos-list-wrap { margin-top: 1rem; }
        .insumos-list-label {
            font-size: 0.65rem; font-weight: 700; letter-spacing: 0.9px; text-transform: uppercase;
            color: var(--muted); display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.65rem;
        }
        .insumos-list-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }
        #detalle-lista { list-style: none; display: flex; flex-direction: column; gap: 0.4rem; }
        .insumo-item {
            display: grid; grid-template-columns: auto 1fr 80px 130px auto;
            align-items: center; padding: 0.6rem 0.85rem;
            background: var(--bg); border: 1.5px solid var(--border);
            border-radius: 0.5rem; gap: 0.75rem;
            animation: itemIn 0.18s ease both; transition: box-shadow 0.15s, background 0.15s;
        }
        .insumo-item.sortable-ghost { opacity: 0.4; background: var(--accent-s); border-color: var(--accent); }
        .insumo-item.sortable-drag { box-shadow: 0 8px 24px rgba(0,0,0,0.12); background: var(--surface); border-color: var(--accent); opacity: 1 !important; }
        @keyframes itemIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: none; } }
        .insumo-item-info { display: flex; align-items: center; gap: 0.6rem; min-width: 0; }
        .insumo-item-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--accent); flex-shrink: 0; opacity: 0.6; }
        .insumo-item-name { font-size: 0.855rem; font-weight: 500; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .insumo-item-qty {
            font-family: 'DM Mono', monospace; font-size: 0.72rem; color: var(--text2);
            background: var(--surface2); border: 1px solid var(--border);
            padding: 0.18rem 0.55rem; border-radius: 0.3rem; white-space: nowrap; text-align: right; min-width: 80px;
        }
        .item-input {
            width: 100%; height: 32px; padding: 0 0.5rem;
            font-family: 'DM Mono', monospace; font-size: 0.8rem; color: var(--text);
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.4rem; outline: none; text-align: right;
            transition: border-color 0.15s;
        }
        .item-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .item-select {
            width: 100%; height: 32px; padding: 0 0.4rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.78rem; color: var(--text);
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.4rem; outline: none; cursor: pointer;
            transition: border-color 0.15s;
        }
        .item-select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .insumo-item-actions { display: flex; justify-content: flex-end; width: 60px; }
        .drag-handle {
            display: flex; flex-direction: column; gap: 3px; cursor: grab;
            padding: 0.2rem 0.1rem; opacity: 0.35; transition: opacity 0.12s; flex-shrink: 0;
        }
        .drag-handle:hover { opacity: 0.7; }
        .drag-handle:active { cursor: grabbing; }
        .drag-handle span { display: block; width: 14px; height: 2px; background: var(--text2); border-radius: 2px; }
        .insumos-list-header {
            display: grid; grid-template-columns: auto 1fr 80px 130px auto;
            gap: 0.75rem; padding: 0 0.85rem 0.4rem;
        }
        .insumos-list-header span { font-size: 0.63rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: var(--muted); }
        .insumos-list-header .col-handle { width: 14px; }
        .insumos-list-header .col-qty { min-width: 80px; text-align: right; }
        .insumos-list-header .col-unit { min-width: 130px; }
        .insumos-list-header .col-act { width: 60px; }
        .empty-list {
            text-align: center; padding: 1.5rem; color: var(--muted); font-size: 0.82rem;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            border: 1.5px dashed var(--border); border-radius: 0.6rem; background: var(--bg);
        }
        .empty-list i { display: block; font-size: 1.2rem; opacity: 0.3; margin-bottom: 0.4rem; }

        .form-actions {
            display: flex; align-items: center; gap: 0.6rem;
            padding-top: 1.5rem; border-top: 1px solid var(--border); margin-top: 1.5rem;
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
                            <i class="fas fa-wrench"></i> Mantenimiento
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('kits.index') }}">Kits</a>
                            <i class="fas fa-chevron-right"></i> Editar
                        </div>
                        <h1 class="ph-title">Editar <em>{{ $kit->descripcion }}</em></h1>
                        <p class="ph-sub">Modificá la descripción y los insumos del kit</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('kits.index') }}" class="btn" id="volver-btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <form id="kit-form" action="{{ route('kits.update', $kit->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-card">
                        <div class="form-card-stripe"></div>
                        <div class="form-card-body">

                            {{-- Section: General --}}
                            <div class="section-heading">
                                <div class="section-heading-icon"><i class="fas fa-tag"></i></div>
                                <span class="section-heading-text">Información general</span>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="descripcion">Nombre / Descripción del kit</label>
                                <input type="text" class="field-input" id="descripcion" name="descripcion"
                                       placeholder="Ej: Kit de ensayos" required
                                       value="{{ old('descripcion', $kit->descripcion) }}">
                            </div>

                            <div class="section-divider"></div>

                            {{-- Section: Insumos --}}
                            <div class="section-heading">
                                <div class="section-heading-icon"><i class="fas fa-cubes"></i></div>
                                <span class="section-heading-text">Insumos del kit</span>
                            </div>

                            @if ($errors->has('detalle'))
                                <div class="alert alert-danger mb-3" style="border-radius:0.55rem; font-size:0.85rem;">
                                    {{ $errors->first('detalle') }}
                                </div>
                            @endif

                            <div class="add-row">
                                <div class="field-group">
                                    <label class="field-label" for="insumo_id">Insumo</label>
                                    <div style="position:relative;">
                                        <select class="field-input" id="insumo_id">
                                            <option value="">Buscar insumo…</option>
                                            @foreach($insumos as $insumo)
                                                <option value="{{ $insumo->id }}">{{ $insumo->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-search ts-search-icon"></i>
                                    </div>
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="cantidad">Cantidad</label>
                                    <input type="number" min="0" step="0.01" class="field-input"
                                           id="cantidad" placeholder="0">
                                </div>

                                <div class="field-group">
                                    <label class="field-label" for="unidad_medida_id">Unidad de medida</label>
                                    <select class="field-input" id="unidad_medida_id">
                                        <option value="">Seleccione</option>
                                        @foreach(config('constantes.unidad_medida') as $id => $nombre)
                                            <option value="{{ $id }}">{{ $nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field-group" style="justify-content:flex-end;">
                                    <button type="button" class="btn btn-success" id="agregar-insumo" title="Agregar insumo">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="insumos-list-wrap">
                                <div class="insumos-list-label"><i class="fas fa-list-ul"></i> Insumos en el kit</div>
                                <div id="list-header" class="insumos-list-header" style="display:none;">
                                    <span class="col-handle"></span>
                                    <span>Insumo</span>
                                    <span class="col-qty">Cantidad</span>
                                    <span class="col-unit">Unidad</span>
                                    <span class="col-act"></span>
                                </div>
                                <ul id="detalle-lista"></ul>
                            </div>

                            <input type="hidden" name="detalle" id="detalle-json">

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-floppy-disk"></i> Guardar cambios
                                </button>
                                <a href="{{ route('kits.index') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
const UNIDADES = @json(config('constantes.unidad_medida'));

const tomInsumo = new TomSelect('#insumo_id', {
    placeholder: 'Buscar insumo…',
    allowEmptyOption: false,
    maxOptions: null,
    onFocus: function() { if (!this.getValue()) this.clear(true); }
});

// Pre-cargar insumos existentes del kit
let detalle = {!! json_encode($kit->detalles->map(function($d) {
    return [
        'insumo_id'        => (string) $d->insumo_id,
        'insumo'           => $d->insumo->nombre ?? '',
        'cantidad'         => $d->cantidad,
        'unidad_medida_id' => (string) $d->unidad_medida_id,
        'unidad'           => config('constantes.unidad_medida')[$d->unidad_medida_id] ?? '',
    ];
})) !!};

let sortable = null;

renderDetalle();
if (detalle.length) initSortable();

document.getElementById('agregar-insumo').addEventListener('click', function () {
    const cantInput  = document.getElementById('cantidad');
    const unidadSel  = document.getElementById('unidad_medida_id');

    const insumoId   = tomInsumo.getValue();
    const insumoText = insumoId ? tomInsumo.getOption(insumoId)?.textContent?.trim() : '';
    const cantidad   = cantInput.value;
    const unidadId   = unidadSel.value;
    const unidadText = unidadId ? unidadSel.options[unidadSel.selectedIndex].text : '';

    const fields = [
        { el: tomInsumo.control, valid: !!insumoId },
        { el: cantInput,         valid: !!cantidad  },
        { el: unidadSel,         valid: !!unidadId  },
    ];

    let hasError = false;
    fields.forEach(({ el, valid }) => {
        if (!valid) {
            hasError = true;
            el.style.borderColor = 'var(--red)';
            el.style.boxShadow   = '0 0 0 3px rgba(217,64,64,0.1)';
            setTimeout(() => { el.style.borderColor = ''; el.style.boxShadow = ''; }, 1500);
        }
    });
    if (hasError) return;

    detalle.push({ insumo_id: insumoId, insumo: insumoText, cantidad, unidad_medida_id: unidadId, unidad: unidadText });
    renderDetalle();
    initSortable();

    tomInsumo.clear();
    cantInput.value = '';
    unidadSel.value = '';
});

function renderDetalle() {
    const lista  = document.getElementById('detalle-lista');
    const header = document.getElementById('list-header');
    lista.innerHTML = '';

    if (detalle.length === 0) {
        header.style.display = 'none';
        const li = document.createElement('li');
        li.className = 'empty-list';
        li.innerHTML = '<i class="fas fa-inbox"></i> Todavía no hay insumos en este kit';
        lista.appendChild(li);
    } else {
        header.style.display = 'grid';
        detalle.forEach((item, idx) => {
            const li = document.createElement('li');
            li.className   = 'insumo-item';
            li.dataset.idx = idx;
            li.innerHTML = `
                <div class="drag-handle" title="Arrastrar para reordenar">
                    <span></span><span></span><span></span>
                </div>
                <div class="insumo-item-info">
                    <span class="insumo-item-dot"></span>
                    <span class="insumo-item-name">${item.insumo}</span>
                </div>
                <input type="number" min="0" step="0.01" class="item-input" value="${item.cantidad}"
                       oninput="actualizarItem(${idx}, 'cantidad', this.value)" title="Cantidad">
                <select class="item-select" onchange="actualizarItem(${idx}, 'unidad_medida_id', this.value)" title="Unidad de medida">
                    <option value="">—</option>
                    ${unidadesOptions(item.unidad_medida_id)}
                </select>
                <div class="insumo-item-actions">
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(${idx})" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>`;
            lista.appendChild(li);
        });
    }

    document.getElementById('detalle-json').value = JSON.stringify(detalle);
}

function unidadesOptions(selectedId) {
    return Object.entries(UNIDADES).map(([id, nombre]) =>
        `<option value="${id}" ${id === String(selectedId) ? 'selected' : ''}>${nombre}</option>`
    ).join('');
}

function actualizarItem(idx, campo, valor) {
    detalle[idx][campo] = valor;
    if (campo === 'unidad_medida_id') {
        detalle[idx]['unidad'] = UNIDADES[valor] || '';
    }
    document.getElementById('detalle-json').value = JSON.stringify(detalle);
}

function initSortable() {
    if (sortable) return;
    sortable = Sortable.create(document.getElementById('detalle-lista'), {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        onEnd: function () {
            const items = document.querySelectorAll('#detalle-lista .insumo-item');
            const reordered = [];
            items.forEach(li => reordered.push(detalle[parseInt(li.dataset.idx)]));
            detalle = reordered;
            renderDetalle();
        }
    });
}

window.eliminarDetalle = function (idx) {
    detalle.splice(idx, 1);
    renderDetalle();
};

document.addEventListener('keydown', function (e) {
    if (e.ctrlKey && e.key === '2') {
        e.preventDefault();
        document.getElementById('volver-btn').click();
    }
});
</script>
</body>
</html>
