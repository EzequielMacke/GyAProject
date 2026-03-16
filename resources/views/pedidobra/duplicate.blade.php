<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duplicar Pedido para Obra</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_ing')->first()->id ?? null)->where('agregar', 1)->isEmpty())
        <script>window.location.href = "{{ url('/home') }}";</script>
    @endif

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
            --accent-b: #1f5bbf;
            --accent-s: #e8f0fc;
            --green:    #1e9166;
            --green-s:  #e5f6f0;
            --green-b:  #a8dcc9;
            --red:      #d94040;
            --red-s:    #fdeaea;
            --orange:   #c2700a;
            --orange-s: #fff4e5;
            --orange-b: #fcd49a;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }

        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex; align-items: flex-end; justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .ph-crumb {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem;
        }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; word-break: break-word; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        .btn {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border); background: var(--surface);
            color: var(--text2); text-decoration: none; cursor: pointer;
            transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; box-shadow: 0 4px 14px rgba(42,111,219,0.3); }
        .btn-green { background: var(--green-s); border-color: var(--green-b); color: var(--green); }
        .btn-green:hover { background: var(--green); border-color: var(--green); color: #fff; }
        .btn-sm { height: 32px; padding: 0 0.75rem; font-size: 0.78rem; }

        .form-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 1rem;
        }
        .form-card-header {
            padding: 0.85rem 1.25rem;
            border-bottom: 1.5px solid var(--border);
            background: var(--surface2);
            display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
            font-size: 0.82rem; font-weight: 600; color: var(--text2);
            flex-wrap: wrap;
        }
        .form-card-header-left { display: flex; align-items: center; gap: 0.5rem; }
        .form-card-header i { color: var(--accent); font-size: 0.78rem; }
        .form-card-body { padding: 1.25rem; }

        .fields-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .field-label {
            display: block; font-size: 0.78rem; font-weight: 600;
            color: var(--text2); margin-bottom: 0.4rem;
        }
        .field-input {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.5rem 0.9rem;
            color: var(--text); width: 100%; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .field-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .field-input::placeholder { color: var(--muted); }
        .field-input:disabled, .field-input[readonly] { background: var(--surface2); color: var(--muted); cursor: default; }
        textarea.field-input { resize: vertical; min-height: 80px; }

        .orden-badge {
            display: none; align-items: center; gap: 0.5rem;
            background: var(--green-s); border: 1.5px solid var(--green-b);
            border-radius: 0.55rem; padding: 0.5rem 0.9rem;
            font-size: 0.82rem; font-weight: 600; color: var(--green);
        }
        .orden-badge.visible { display: flex; }

        .dup-banner {
            background: var(--orange-s); border: 1.5px solid var(--orange-b);
            border-radius: 0.55rem; padding: 0.65rem 1rem;
            margin-bottom: 1rem; font-size: 0.82rem; color: var(--orange);
            display: flex; align-items: center; gap: 0.5rem;
        }

        .error-list {
            background: #fef2f2; border: 1.5px solid #fca5a5;
            border-radius: 0.55rem; padding: 0.75rem 1rem;
            margin-bottom: 1rem; font-size: 0.82rem; color: #b91c1c;
        }
        .error-list ul { margin: 0; padding-left: 1.2rem; }

        .insumos-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        .insumos-table th {
            background: var(--surface2); padding: 0.6rem 0.85rem;
            text-align: left; font-size: 0.72rem; font-weight: 700;
            color: var(--text2); border-bottom: 1.5px solid var(--border); white-space: nowrap;
        }
        .insumos-table td {
            padding: 0.55rem 0.85rem; border-bottom: 1px solid var(--border);
            vertical-align: middle; color: var(--text2);
        }
        .insumos-table tbody tr:last-child td { border-bottom: none; }
        .insumos-table .field-input { padding: 0.32rem 0.65rem; font-size: 0.8rem; }
        .empty-table { text-align: center; color: var(--muted); padding: 2rem; font-size: 0.82rem; }
        .badge-num {
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--accent-s); color: var(--accent); border-radius: 99px;
            width: 22px; height: 22px; font-size: 0.72rem; font-weight: 700;
        }
        .btn-icon-danger {
            width: 28px; height: 28px; border-radius: 0.4rem; border: 1.5px solid #fca5a5;
            background: var(--red-s); color: var(--red); display: inline-flex;
            align-items: center; justify-content: center; cursor: pointer;
            font-size: 0.7rem; transition: all 0.13s;
        }
        .btn-icon-danger:hover { background: var(--red); color: #fff; border-color: var(--red); }

        .form-card-insumos {
            border-color: var(--green-b);
            border-left: 3px solid var(--green);
            box-shadow: 0 2px 10px rgba(30,145,102,0.1);
        }
        .form-card-insumos .form-card-header {
            background: var(--green-s);
            border-bottom-color: var(--green-b);
            color: var(--green);
        }
        .form-card-insumos .form-card-header i { color: var(--green); }
        .form-card-insumos .form-card-header #contador-badge { color: var(--green); opacity: 0.7; }

        .select2-container { vertical-align: middle; }
        .select2-container--default .select2-selection--single {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.81rem;
            height: 32px; border: 1.5px solid var(--border); border-radius: 0.55rem;
            background: var(--surface); display: flex; align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--text); line-height: 30px; padding-left: 0.7rem; padding-right: 2rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder { color: var(--muted); }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 30px; }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); outline: none;
        }
        .select2-dropdown {
            border: 1.5px solid var(--border); border-radius: 0.55rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12); overflow: hidden;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1.5px solid var(--border); border-radius: 0.4rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem;
            padding: 0.3rem 0.6rem; outline: none;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--accent); box-shadow: 0 0 0 2px rgba(42,111,219,0.1);
        }
        .select2-container--default .select2-results__option { padding: 0.38rem 0.75rem; }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: var(--accent); color: #fff;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background: var(--accent-s); color: var(--accent);
        }
        .select2-container--default .select2-results__message { color: var(--muted); font-size: 0.8rem; }

        /* Select2 full-width variant for obra/presupuesto fields */
        .select2-full .select2-container { width: 100% !important; }
        .select2-full .select2-container--default .select2-selection--single {
            height: 38px;
        }
        .select2-full .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
        .select2-full .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .field-label .req { color: var(--red); margin-left: 2px; }

        .drag-handle {
            color: var(--muted); cursor: grab; padding: 0 0.3rem;
            font-size: 0.78rem; user-select: none;
        }
        .drag-handle:active { cursor: grabbing; }
        .insumo-row.dragging { opacity: 0.35; background: var(--surface2); }
        .insumo-row.drag-over { background: var(--accent-s); outline: 2px dashed var(--accent); outline-offset: -2px; }

        .header-sep {
            width: 1px; height: 24px; background: var(--border2); margin: 0 0.25rem; flex-shrink: 0;
        }
        .header-controls-label {
            font-size: 0.7rem; font-weight: 600; color: var(--muted); white-space: nowrap;
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
                            <i class="fas fa-hard-hat"></i>
                            <a href="{{ route('obras.index') }}">Obras</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('pedidobra.index') }}">Pedidos</a>
                            <i class="fas fa-chevron-right"></i>
                            Duplicar
                        </div>
                        <h1 class="ph-title">Duplicar <em>pedido</em></h1>
                        <p class="ph-sub">Nuevo pedido basado en uno existente</p>
                    </div>
                    <div class="ph-right">
                        <button type="submit" form="form-duplicate" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ $pedido->obra_id ? route('pedidobra.index', $pedido->obra_id) : route('pedidobra.index') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="dup-banner">
                    <i class="fas fa-copy"></i>
                    Estás creando una copia de un pedido existente. Revisá los datos antes de guardar.
                </div>

                @if ($errors->any())
                <div class="error-list">
                    <ul>
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <form id="form-duplicate" action="{{ route('pedidobra.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="contador_insumos" id="contador_insumos_input" value="0">

                    {{-- Datos del pedido --}}
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="form-card-header-left">
                                <i class="fas fa-clipboard-list"></i> Datos del pedido
                            </div>
                        </div>
                        <div class="form-card-body">
                            <div class="fields-grid">
                                <div>
                                    <label class="field-label">Nro. de pedido</label>
                                    <input type="text" class="field-input" value="{{ $nuevoIdPedido }}" readonly>
                                </div>
                                <div>
                                    <label class="field-label">Creado por</label>
                                    <input type="text" class="field-input" value="{{ session('usuario_nombre') }}" readonly>
                                </div>
                                <div>
                                    <label class="field-label">Fecha de pedido</label>
                                    <input type="date" name="fecha_pedido" id="fecha_pedido" class="field-input" readonly>
                                </div>
                                <div>
                                    <label class="field-label" for="fecha_entrega">Fecha de entrega <span class="req">*</span></label>
                                    <input type="date" name="fecha_entrega" id="fecha_entrega" class="field-input" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Obra y Presupuesto --}}
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="form-card-header-left">
                                <i class="fas fa-hard-hat"></i> Obra y presupuesto
                            </div>
                        </div>
                        <div class="form-card-body">
                            <div class="fields-grid">
                                <div class="select2-full">
                                    <label class="field-label" for="obra-select">Obra <span class="req">*</span></label>
                                    <select name="obra" id="obra-select" required>
                                        <option value="">— Seleccionar obra —</option>
                                        @foreach ($obras as $obra)
                                            <option value="{{ $obra->id }}"
                                                {{ $pedido->obra_id == $obra->id ? 'selected' : '' }}>
                                                {{ $obra->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="field-label" for="presupuesto_aprobado_id">Presupuesto <span class="req">*</span></label>
                                    <select name="presupuesto_aprobado_id" id="presupuesto_aprobado_id" class="field-input" required>
                                        <option value="">— Seleccionar presupuesto —</option>
                                        @foreach($todasPresupuestos->where('obra_id', $pedido->obra_id) as $p)
                                            <option value="{{ $p->id }}" data-orden="{{ $p->orden_trabajo ?? '' }}">
                                                {{ $p->clave }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="field-label">Orden de trabajo</label>
                                    <div class="orden-badge" id="orden-badge">
                                        <i class="fas fa-hashtag"></i>
                                        <span id="orden-text"></span>
                                    </div>
                                    <input type="text" id="orden-empty" class="field-input" value="—" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Observación --}}
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="form-card-header-left">
                                <i class="fas fa-sticky-note"></i> Observaciones
                            </div>
                        </div>
                        <div class="form-card-body">
                            <label class="field-label" for="observacion">Observación</label>
                            <textarea name="observacion" id="observacion" class="field-input"
                                      placeholder="Notas adicionales…">{{ $pedido->observacion }}</textarea>
                        </div>
                    </div>

                    {{-- Insumos --}}
                    <div class="form-card form-card-insumos">
                        <div class="form-card-header">
                            <div class="form-card-header-left">
                                <i class="fas fa-boxes"></i> Insumos
                                <span id="contador-badge" style="font-size:0.72rem; color:var(--muted); font-weight:500; margin-left:0.25rem;">0 ítems</span>
                            </div>
                            <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                                {{-- Kit --}}
                                <span class="header-controls-label">Kit:</span>
                                <select id="kit-select" class="field-input" style="width:200px; height:32px; padding:0.22rem 0.7rem; font-size:0.81rem;">
                                    <option value="">Buscar kit…</option>
                                    @foreach ($kits as $kit)
                                        <option value="{{ $kit->id }}">{{ $kit->descripcion }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-sm" id="add-kit-btn" style="background:var(--accent-s); border-color:#c3d7f7; color:var(--accent);">
                                    <i class="fas fa-layer-group"></i> Añadir kit
                                </button>
                                <div class="header-sep"></div>
                                {{-- Insumo --}}
                                <span class="header-controls-label">Insumo:</span>
                                <select id="insumo-select" class="field-input" style="width:220px; height:32px; padding:0.22rem 0.7rem; font-size:0.81rem;">
                                    <option value="">Buscar insumo…</option>
                                    @foreach ($insumosDisponibles as $ins)
                                        <option value="{{ $ins->id }}">{{ $ins->nombre }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-green btn-sm" id="add-insumo-btn">
                                    <i class="fas fa-plus"></i> Añadir
                                </button>
                                <button type="button" class="btn btn-sm" id="recargar" title="Recargar insumos">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div style="overflow-x:auto;">
                            <table class="insumos-table">
                                <thead>
                                    <tr>
                                        <th style="width:28px;"></th>
                                        <th style="width:44px;">#</th>
                                        <th>Insumo</th>
                                        <th>Unidad</th>
                                        <th style="width:110px;">Cantidad</th>
                                        <th style="width:44px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="insumos-body">
                                    <tr id="empty-row">
                                        <td colspan="6" class="empty-table">
                                            <i class="fas fa-box-open" style="font-size:1.3rem; opacity:0.25; display:block; margin-bottom:0.4rem;"></i>
                                            Agregá insumos desde el selector
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </form>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Fechas
    const pad = n => String(n).padStart(2, '0');
    const fmtDate = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
    const hoy = new Date();
    document.getElementById('fecha_pedido').value = fmtDate(hoy);
    const manana = new Date(hoy); manana.setDate(hoy.getDate() + 1);
    const fechaEntrega = document.getElementById('fecha_entrega');
    fechaEntrega.value = fmtDate(manana);
    fechaEntrega.addEventListener('change', function () {
        const sel = new Date(this.value + 'T00:00:00');
        const today = new Date(); today.setHours(0,0,0,0);
        if (sel < today) {
            alert('La fecha de entrega no puede ser menor a hoy.');
            this.value = fmtDate(manana);
        }
    });

    // Datos para presupuestos dinámicos
    const todasPresupuestos = @json($todasPresupuestos);
    const unidades = @json($unidadesMedida);
    const kitsData = @json($kits);

    // Select2 — obra (ancho completo)
    if (typeof $.fn.select2 !== 'undefined') {
        $('#obra-select').select2({
            placeholder: '— Seleccionar obra —', allowClear: true,
            width: '100%', language: { noResults: () => 'Sin resultados' }
        });
        $('#insumo-select').select2({
            placeholder: 'Buscar insumo…', allowClear: true,
            width: '220px', language: { noResults: () => 'Sin resultados' }
        });
        $('#kit-select').select2({
            placeholder: 'Buscar kit…', allowClear: true,
            width: '200px', language: { noResults: () => 'Sin resultados' }
        });
    }

    // Presupuesto → orden de trabajo
    const presupuestoSel = document.getElementById('presupuesto_aprobado_id');
    const ordenBadge     = document.getElementById('orden-badge');
    const ordenText      = document.getElementById('orden-text');
    const ordenEmpty     = document.getElementById('orden-empty');

    function updateOrden() {
        const opt   = presupuestoSel.options[presupuestoSel.selectedIndex];
        const orden = opt ? opt.dataset.orden : '';
        if (presupuestoSel.value && orden) {
            ordenText.textContent = orden;
            ordenBadge.classList.add('visible');
            ordenEmpty.style.display = 'none';
        } else {
            ordenBadge.classList.remove('visible');
            ordenEmpty.style.display = '';
        }
    }
    presupuestoSel.addEventListener('change', updateOrden);

    // Obra cambia → recargar presupuestos
    $('#obra-select').on('change', function () {
        const obraId = $(this).val();
        presupuestoSel.innerHTML = '<option value="">— Seleccionar presupuesto —</option>';
        if (obraId) {
            const filtrados = todasPresupuestos.filter(p => String(p.obra_id) === String(obraId));
            filtrados.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.dataset.orden = p.orden_trabajo ?? '';
                opt.textContent = p.clave;
                presupuestoSel.appendChild(opt);
            });
        }
        updateOrden();
    });

    // Insumos
    const tbody    = document.getElementById('insumos-body');
    const emptyRow = document.getElementById('empty-row');
    const counter  = document.getElementById('contador_insumos_input');
    const badge    = document.getElementById('contador-badge');

    function getInsumoValue() { return $('#insumo-select').val() || ''; }
    function getInsumoText()  { return $('#insumo-select option:selected').text() || ''; }
    function clearInsumoSel() { $('#insumo-select').val('').trigger('change'); }
    function clearKitSel()    { $('#kit-select').val('').trigger('change'); }

    function refreshCounter() {
        const n = tbody.querySelectorAll('tr.insumo-row').length;
        counter.value = n;
        badge.textContent = n + ' ítem' + (n !== 1 ? 's' : '');
        emptyRow.style.display = n ? 'none' : '';
    }

    function buildUnidades(selected) {
        return Object.entries(unidades).map(([k,v]) =>
            `<option value="${k}"${String(k) === String(selected) ? ' selected' : ''}>${v}</option>`
        ).join('');
    }

    function renumber() {
        tbody.querySelectorAll('tr.insumo-row').forEach((tr, i) => {
            tr.querySelector('.badge-num').textContent = i + 1;
        });
    }

    function buildRow(id, name, cantidad, unidadMedida) {
        const n  = tbody.querySelectorAll('tr.insumo-row').length + 1;
        const tr = document.createElement('tr');
        tr.className = 'insumo-row';
        tr.draggable = true;
        tr.innerHTML = `
            <td><i class="fas fa-grip-vertical drag-handle" draggable="false"></i></td>
            <td><span class="badge-num">${n}</span></td>
            <td><input type="hidden" name="insumo[]" value="${id}" draggable="false">${name}</td>
            <td><select name="unidad_medida[]" class="field-input" draggable="false">${buildUnidades(unidadMedida)}</select></td>
            <td><input type="number" name="cantidad[]" class="field-input" value="${cantidad}" min="0.01" step="0.01" draggable="false"></td>
            <td><button type="button" class="btn-icon-danger remove-btn" draggable="false"><i class="fas fa-times" draggable="false"></i></button></td>`;
        addDragEvents(tr);
        return tr;
    }

    // Pre-cargar insumos del pedido original
    const insumosOriginales = @json($insumos->load('insumo'));
    insumosOriginales.forEach(function (det) {
        if (!det.insumo) return;
        tbody.appendChild(buildRow(det.insumo_id, det.insumo.nombre, det.cantidad, det.medida));
    });
    refreshCounter();

    // ── Añadir insumo individual ──
    document.getElementById('add-insumo-btn').addEventListener('click', function () {
        const id   = getInsumoValue();
        const name = getInsumoText();
        if (!id) return;
        const existing = tbody.querySelector(`input[name="insumo[]"][value="${id}"]`);
        if (existing) {
            const cantInput = existing.closest('tr').querySelector('input[name="cantidad[]"]');
            cantInput.value = (parseFloat(cantInput.value) || 0) + 1;
            clearInsumoSel(); return;
        }
        tbody.appendChild(buildRow(id, name, 1, ''));
        clearInsumoSel();
        refreshCounter();
    });

    // ── Añadir kit ──
    document.getElementById('add-kit-btn').addEventListener('click', function () {
        const kitId = $('#kit-select').val();
        if (!kitId) return;
        const kit = kitsData.find(k => String(k.id) === String(kitId));
        if (!kit || !kit.detalles || !kit.detalles.length) {
            alert('Este kit no tiene insumos.'); return;
        }
        kit.detalles.forEach(det => {
            if (!det.insumo) return;
            const id       = det.insumo.id;
            const name     = det.insumo.nombre;
            const cantidad = parseFloat(det.cantidad) || 1;
            const unidad   = det.unidad_medida_id ?? '';
            const existing = tbody.querySelector(`input[name="insumo[]"][value="${id}"]`);
            if (existing) {
                const cantInput = existing.closest('tr').querySelector('input[name="cantidad[]"]');
                cantInput.value = (parseFloat(cantInput.value) || 0) + cantidad;
            } else {
                tbody.appendChild(buildRow(id, name, cantidad, unidad));
            }
        });
        clearKitSel();
        refreshCounter();
    });

    // ── Eliminar fila ──
    tbody.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-btn');
        if (!btn) return;
        btn.closest('tr').remove();
        renumber();
        refreshCounter();
    });

    // ── Validar cantidad ──
    tbody.addEventListener('input', function (e) {
        if (e.target.name === 'cantidad[]' && parseFloat(e.target.value) <= 0) {
            alert('La cantidad no puede ser 0 o negativa.');
            e.target.value = 1;
        }
    });

    // ── Recargar insumos ──
    document.getElementById('recargar').addEventListener('click', function () {
        fetch('{{ route('insumos.recargar') }}')
            .then(r => r.json())
            .then(data => {
                const sel = document.getElementById('insumo-select');
                sel.innerHTML = '<option value="">Buscar insumo…</option>';
                data.forEach(i => {
                    const o = document.createElement('option');
                    o.value = i.id; o.text = i.nombre;
                    sel.appendChild(o);
                });
                if (typeof $.fn.select2 !== 'undefined') $('#insumo-select').trigger('change');
            });
    });

    // ── Drag & drop reordering ──
    let dragSrc = null;
    let fromHandle = false;

    function addDragEvents(tr) {
        tr.querySelector('.drag-handle').addEventListener('mousedown', () => { fromHandle = true; });
        tr.querySelector('.drag-handle').addEventListener('mouseup',   () => { fromHandle = false; });

        tr.addEventListener('dragstart', function (e) {
            if (!fromHandle) { e.preventDefault(); return; }
            dragSrc = tr;
            e.dataTransfer.effectAllowed = 'move';
            setTimeout(() => tr.classList.add('dragging'), 0);
        });
        tr.addEventListener('dragend', function () {
            fromHandle = false;
            tr.classList.remove('dragging');
            tbody.querySelectorAll('tr.insumo-row').forEach(r => r.classList.remove('drag-over'));
            renumber();
        });
        tr.addEventListener('dragover', function (e) {
            if (!dragSrc || dragSrc === tr) return;
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            tbody.querySelectorAll('tr.insumo-row').forEach(r => r.classList.remove('drag-over'));
            tr.classList.add('drag-over');
        });
        tr.addEventListener('dragleave', function () {
            tr.classList.remove('drag-over');
        });
        tr.addEventListener('drop', function (e) {
            e.preventDefault();
            if (!dragSrc || dragSrc === tr) return;
            const rows   = [...tbody.querySelectorAll('tr.insumo-row')];
            const srcIdx = rows.indexOf(dragSrc);
            const tgtIdx = rows.indexOf(tr);
            tbody.insertBefore(dragSrc, srcIdx < tgtIdx ? tr.nextSibling : tr);
            tr.classList.remove('drag-over');
        });
    }
});
</script>
</body>
</html>
