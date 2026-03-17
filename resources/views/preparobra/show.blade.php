<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preparar Pedido</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

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
            --green-s:  #e5f6f0;
            --green-b:  #a8dcc9;
            --orange:   #d97706;
            --orange-s: #fef3c7;
            --teal:     #0e7490;
            --teal-s:   #e0f2f7;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* PAGE HEADER */
        .ph {
            padding: 1.5rem 0 1rem;
        }

        .ph-crumb {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 0.5rem;
        }

        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }

        .ph-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.4px;
            line-height: 1.2;
        }

        .ph-title em { font-style: normal; color: var(--teal); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }

        .ph-actions {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        .btn-save {
            height: 38px; padding: 0 1.1rem;
            border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.825rem; font-weight: 600;
            background: var(--accent); color: #fff;
            border: 1.5px solid var(--accent-b);
            cursor: pointer; text-decoration: none;
            transition: background 0.14s;
        }
        .btn-save:hover { background: var(--accent-b); color: #fff; }

        .btn-back {
            height: 38px; padding: 0 1rem;
            border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.825rem; font-weight: 600;
            background: var(--surface); color: var(--text2);
            border: 1.5px solid var(--border);
            cursor: pointer; text-decoration: none;
            transition: all 0.14s;
        }
        .btn-back:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        /* INFO CARD */
        .info-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 1.25rem;
        }

        .info-card-header {
            background: var(--surface2);
            border-bottom: 1.5px solid var(--border);
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .info-card-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text2);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card-title i { color: var(--muted); font-size: 0.75rem; }

        .estado-badge {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.22rem 0.65rem;
            border-radius: 99px;
        }

        .estado-pendiente { background: var(--orange-s); color: var(--orange); }
        .estado-preparado { background: var(--green-s);  color: var(--green); }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0;
        }

        .info-field {
            padding: 0.85rem 1.25rem;
            border-right: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .info-field:last-child { border-right: none; }

        .info-field-label {
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.3rem;
        }

        .info-field-value {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text);
        }

        .info-field-value.mono {
            font-family: 'DM Mono', monospace;
            font-size: 0.82rem;
            color: var(--accent);
        }

        .info-obs {
            padding: 0.85rem 1.25rem;
            border-top: 1px solid var(--border);
            font-size: 0.83rem;
            color: var(--text2);
            font-style: italic;
        }

        .info-obs-label {
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.3rem;
            font-style: normal;
        }

        /* DETALLES TABLE CARD */
        .table-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .table-card-header {
            background: var(--surface2);
            border-bottom: 2px solid var(--border);
            padding: 0.75rem 1.25rem;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text2);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-card-header i { color: var(--muted); font-size: 0.75rem; }

        .det-table {
            width: 100%;
            border-collapse: collapse;
        }

        .det-table thead tr {
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
        }

        .det-table thead th {
            padding: 0.7rem 1rem;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: left;
            white-space: nowrap;
        }

        .det-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
        }

        .det-table tbody tr:last-child { border-bottom: none; }
        .det-table tbody tr:hover { background: var(--accent-s); }

        .det-table td {
            padding: 0.7rem 1rem;
            font-size: 0.83rem;
            color: var(--text2);
            vertical-align: middle;
        }

        .det-table td.num {
            font-family: 'DM Mono', monospace;
            font-size: 0.78rem;
            color: var(--muted);
            width: 40px;
            text-align: center;
        }

        .insumo-name {
            font-weight: 600;
            color: var(--text);
        }

        .insumo-code {
            font-family: 'DM Mono', monospace;
            font-size: 0.75rem;
            color: var(--muted);
        }

        /* Toggle switch */
        .toggle-wrap {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .toggle-input { display: none; }

        .toggle-label {
            position: relative;
            display: inline-block;
            width: 42px;
            height: 24px;
            cursor: pointer;
        }

        .toggle-label::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--border2);
            border-radius: 99px;
            transition: background 0.2s;
        }

        .toggle-label::after {
            content: '';
            position: absolute;
            left: 3px; top: 3px;
            width: 18px; height: 18px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
            transition: transform 0.2s;
        }

        .toggle-input:checked + .toggle-label::before { background: var(--green); }
        .toggle-input:checked + .toggle-label::after  { transform: translateX(18px); }

        .toggle-text {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--muted);
        }

        .toggle-input:checked ~ .toggle-text { color: var(--green); }

        /* Comentario textarea */
        .comentario-input {
            width: 100%;
            min-width: 180px;
            padding: 0.4rem 0.6rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.78rem;
            color: var(--text);
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 0.45rem;
            resize: vertical;
            min-height: 56px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .comentario-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(42,111,219,0.08);
            background: #fff;
        }

        .preparado-por {
            font-size: 0.75rem;
            color: var(--muted);
            white-space: nowrap;
        }

        .preparado-por strong { color: var(--green); font-weight: 600; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('partials.navbar')
    @include('partials.sidebar')

    <div class="content-wrapper">
        <form action="{{ route('preparobra.updateConfirmado', $pedido->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="content-header">
            <div class="container-fluid">
                <div class="ph">
                    <div class="row align-items-end">
                        <div class="col">
                            <div class="ph-crumb">
                                <i class="fas fa-home"></i>
                                <a href="{{ url('/home') }}">Inicio</a>
                                <i class="fas fa-chevron-right"></i>
                                <a href="{{ route('preparobra.index') }}">Preparar Pedidos</a>
                                <i class="fas fa-chevron-right"></i>
                                Pedido #{{ $pedido->id }}
                            </div>
                            <h1 class="ph-title">Preparar Pedido <em>#{{ $pedido->id }}</em></h1>
                            <p class="ph-sub">{{ $pedido->obra->nombre ?? '—' }}</p>
                        </div>
                        <div class="col-auto">
                            <div class="ph-actions">
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save"></i> Guardar
                                </button>
                                <a href="{{ route('preparobra.index') }}" class="btn-back" id="volver-btn">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @if(session('success'))
                <div class="alert alert-success mb-3" style="border-radius:0.55rem; font-size:0.85rem;">
                    {{ session('success') }}
                </div>
                @endif

                <!-- INFO CARD -->
                <div class="info-card">
                    <div class="info-card-header">
                        <span class="info-card-title">
                            <i class="fas fa-info-circle"></i> Información del pedido
                        </span>
                        @if($pedido->estado == 2)
                            <span class="estado-badge estado-preparado"><i class="fas fa-check-circle"></i> Preparado</span>
                        @else
                            <span class="estado-badge estado-pendiente"><i class="fas fa-clock"></i> Pendiente</span>
                        @endif
                    </div>
                    <div class="info-grid">
                        <div class="info-field">
                            <div class="info-field-label">Nro. de Pedido</div>
                            <div class="info-field-value mono">#{{ $pedido->id }}</div>
                        </div>
                        <div class="info-field">
                            <div class="info-field-label">Obra</div>
                            <div class="info-field-value">{{ $pedido->obra->nombre ?? '—' }}</div>
                        </div>
                        <div class="info-field">
                            <div class="info-field-label">Presupuesto</div>
                            <div class="info-field-value mono">{{ $pedido->presupuesto->clave ?? '—' }}</div>
                        </div>
                        <div class="info-field">
                            <div class="info-field-label">Orden de trabajo</div>
                            <div class="info-field-value mono">{{ $pedido->presupuesto->orden_trabajo ?? '—' }}</div>
                        </div>
                        <div class="info-field">
                            <div class="info-field-label">Creado por</div>
                            <div class="info-field-value">{{ $pedido->usuario->nombre ?? '—' }}</div>
                        </div>
                        <div class="info-field">
                            <div class="info-field-label">Fecha de pedido</div>
                            <div class="info-field-value">{{ $pedido->fecha_pedido ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') : '—' }}</div>
                        </div>
                        <div class="info-field" style="border-right:none;">
                            <div class="info-field-label">Fecha de entrega</div>
                            <div class="info-field-value">{{ $pedido->fecha_entrega ? \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y') : '—' }}</div>
                        </div>
                    </div>
                    @if($pedido->observacion)
                    <div class="info-obs">
                        <div class="info-obs-label">Observación</div>
                        {{ $pedido->observacion }}
                    </div>
                    @endif
                </div>

                <!-- DETALLES TABLE -->
                <div class="table-card">
                    <div class="table-card-header">
                        <i class="fas fa-boxes"></i>
                        Insumos del pedido
                        <span style="margin-left:auto; font-size:0.75rem; color:var(--muted); text-transform:none; font-weight:500;">
                            {{ $pedido->insumo_confirmado }} / {{ $pedido->total_insumo }} preparados
                        </span>
                    </div>
                    <table class="det-table">
                        <thead>
                            <tr>
                                <th style="width:40px; text-align:center;">#</th>
                                <th>Insumo</th>
                                <th>Unidad</th>
                                <th>Cantidad</th>
                                <th>Comentario</th>
                                <th>Preparado</th>
                                <th>Preparado por</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedido->detalles as $detalle)
                            <tr>
                                <td class="num">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="insumo-name">{{ $detalle->insumo->nombre }}</div>
                                    <div class="insumo-code">#{{ $detalle->insumo_id }}</div>
                                </td>
                                <td>{{ config('constantes.unidad_medida')[$detalle->medida] ?? $detalle->medida }}</td>
                                <td style="font-family:'DM Mono',monospace; font-size:0.82rem;">{{ $detalle->cantidad }}</td>
                                <td>
                                    <textarea
                                        name="comentario[{{ $detalle->id }}]"
                                        class="comentario-input"
                                        placeholder="Agregar comentario…">{{ old('comentario.'.$detalle->id, $detalle->comentario) }}</textarea>
                                </td>
                                <td>
                                    <div class="toggle-wrap">
                                        <input type="checkbox"
                                            class="toggle-input"
                                            id="tog{{ $detalle->id }}"
                                            name="confirmado[]"
                                            value="{{ $detalle->id }}"
                                            {{ $detalle->confirmado == 2 ? 'checked' : '' }}>
                                        <label class="toggle-label" for="tog{{ $detalle->id }}"></label>
                                        <span class="toggle-text">{{ $detalle->confirmado == 2 ? 'Listo' : 'Pendiente' }}</span>
                                    </div>
                                </td>
                                <td class="preparado-por">
                                    @if($detalle->usuario_id && isset($usuarios[$detalle->usuario_id]))
                                        <strong>{{ $usuarios[$detalle->usuario_id] }}</strong>
                                    @else
                                        <span>—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </section>

        </form>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('keydown', function (e) {
    if (e.ctrlKey && e.key === '2') {
        e.preventDefault();
        document.getElementById('volver-btn').click();
    }
});

// Update toggle text live
document.querySelectorAll('.toggle-input').forEach(function (cb) {
    cb.addEventListener('change', function () {
        const text = this.closest('.toggle-wrap').querySelector('.toggle-text');
        text.textContent = this.checked ? 'Listo' : 'Pendiente';
    });
});
</script>
</body>
</html>
