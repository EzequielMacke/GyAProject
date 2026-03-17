<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Pedido #{{ $pedido->id }}</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

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
            --red-b:    #f9b8b8;
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
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
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
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.82rem; font-weight: 600; color: var(--text2);
        }
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
        .field-value {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem;
            background: var(--surface2); border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.5rem 0.9rem;
            color: var(--muted); width: 100%; min-height: 38px;
            display: flex; align-items: center;
        }
        .field-value-text {
            font-size: 0.85rem; color: var(--text);
            background: var(--surface2); border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.6rem 0.9rem;
            width: 100%; min-height: 80px; line-height: 1.5;
            white-space: pre-wrap;
        }

        .obra-chip {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: var(--accent-s); border: 1.5px solid #c3d7f7;
            border-radius: 0.55rem; padding: 0.5rem 0.9rem;
            font-size: 0.85rem; font-weight: 600; color: var(--accent); width: 100%;
        }

        .orden-chip {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: var(--green-s); border: 1.5px solid var(--green-b);
            border-radius: 0.55rem; padding: 0.5rem 0.9rem;
            font-size: 0.82rem; font-weight: 600; color: var(--green); width: 100%;
        }

        /* Insumos table */
        .insumos-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        .insumos-table th {
            background: var(--surface2); padding: 0.6rem 0.85rem;
            text-align: left; font-size: 0.72rem; font-weight: 700;
            color: var(--text2); border-bottom: 1.5px solid var(--border); white-space: nowrap;
        }
        .insumos-table td {
            padding: 0.6rem 0.85rem; border-bottom: 1px solid var(--border);
            vertical-align: middle; color: var(--text2);
        }
        .insumos-table tbody tr:last-child td { border-bottom: none; }
        .insumos-table tbody tr:hover { background: var(--surface2); }

        .badge-num {
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--accent-s); color: var(--accent); border-radius: 99px;
            width: 22px; height: 22px; font-size: 0.72rem; font-weight: 700;
        }

        /* Estado badges */
        .estado-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            font-size: 0.72rem; font-weight: 600; border-radius: 99px;
            padding: 0.2rem 0.65rem; white-space: nowrap;
        }
        .estado-warning  { background: var(--orange-s); border: 1px solid var(--orange-b); color: var(--orange); }
        .estado-success  { background: var(--green-s);  border: 1px solid var(--green-b);  color: var(--green); }
        .estado-danger   { background: var(--red-s);    border: 1px solid var(--red-b);    color: var(--red); }
        .estado-secondary{ background: var(--surface2); border: 1px solid var(--border2);  color: var(--muted); }
        .estado-primary  { background: var(--accent-s); border: 1px solid #c3d7f7;         color: var(--accent); }

        /* Form-card con borde verde para insumos */
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
                            @if($pedido->obra)
                                <a href="{{ route('obras.show', $pedido->obra_id) }}">{{ $pedido->obra->nombre }}</a>
                                <i class="fas fa-chevron-right"></i>
                                <a href="{{ route('pedidobra.index', $pedido->obra_id) }}">Pedidos</a>
                                <i class="fas fa-chevron-right"></i>
                            @endif
                            #{{ $pedido->id }}
                        </div>
                        <h1 class="ph-title">Pedido <em>#{{ $pedido->id }}</em></h1>
                        <p class="ph-sub">{{ $pedido->obra->nombre ?? 'Pedido de insumos para obra' }}</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('pedidobra.index', $pedido->obra_id) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                {{-- Datos del pedido --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-clipboard-list"></i> Datos del pedido
                    </div>
                    <div class="form-card-body">
                        <div class="fields-grid">
                            <div>
                                <label class="field-label">Nro. de pedido</label>
                                <div class="field-value">{{ $pedido->id }}</div>
                            </div>
                            <div>
                                <label class="field-label">Creado por</label>
                                <div class="field-value">{{ $pedido->usuario->nombre ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="field-label">Fecha de pedido</label>
                                <div class="field-value">{{ $pedido->fecha_pedido ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') : '—' }}</div>
                            </div>
                            <div>
                                <label class="field-label">Fecha de entrega</label>
                                <div class="field-value">{{ $pedido->fecha_entrega ? \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y') : '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Obra y Presupuesto --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-hard-hat"></i> Obra y presupuesto
                    </div>
                    <div class="form-card-body">
                        <div class="fields-grid">
                            <div>
                                <label class="field-label">Obra</label>
                                @if($pedido->obra)
                                    <div class="obra-chip">
                                        <i class="fas fa-building"></i> {{ $pedido->obra->nombre }}
                                    </div>
                                @else
                                    <div class="field-value">Sin obra asignada</div>
                                @endif
                            </div>
                            <div>
                                <label class="field-label">Presupuesto</label>
                                <div class="field-value">{{ $pedido->presupuesto->clave ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="field-label">Orden de trabajo</label>
                                @php $orden = $pedido->presupuesto->orden_trabajo ?? null; @endphp
                                @if($orden)
                                    <div class="orden-chip">
                                        <i class="fas fa-hashtag"></i> {{ $orden }}
                                    </div>
                                @else
                                    <div class="field-value">—</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Observaciones --}}
                @if($pedido->observacion)
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-sticky-note"></i> Observaciones
                    </div>
                    <div class="form-card-body">
                        <div class="field-value-text">{{ $pedido->observacion }}</div>
                    </div>
                </div>
                @endif

                {{-- Insumos --}}
                <div class="form-card form-card-insumos">
                    <div class="form-card-header">
                        <i class="fas fa-boxes"></i> Insumos
                        <span style="font-size:0.72rem; font-weight:500; opacity:0.7; margin-left:0.25rem;">
                            {{ $pedido->detalles->count() }} {{ $pedido->detalles->count() == 1 ? 'ítem' : 'ítems' }}
                        </span>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="insumos-table">
                            <thead>
                                <tr>
                                    <th style="width:44px;">#</th>
                                    <th>Insumo</th>
                                    <th>Unidad</th>
                                    <th style="width:110px;">Cantidad</th>
                                    <th style="width:120px;">Estado</th>
                                    <th>Comentario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pedido->detalles as $detalle)
                                    @php
                                        $labelClass = match($estados_label[$detalle->confirmado] ?? '') {
                                            'warning'   => 'estado-warning',
                                            'success'   => 'estado-success',
                                            'danger'    => 'estado-danger',
                                            'primary'   => 'estado-primary',
                                            default     => 'estado-secondary',
                                        };
                                    @endphp
                                    <tr>
                                        <td><span class="badge-num">{{ $loop->iteration }}</span></td>
                                        <td>{{ $detalle->insumo->nombre ?? '—' }}</td>
                                        <td>{{ config('constantes.unidad_medida')[$detalle->medida] ?? $detalle->medida }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>
                                            <span class="estado-badge {{ $labelClass }}">
                                                {{ $estados[$detalle->confirmado] ?? 'Desconocido' }}
                                            </span>
                                        </td>
                                        <td style="font-size:0.78rem; color:var(--muted); font-style:italic;">
                                            {{ $detalle->comentario ?? '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align:center; color:var(--muted); padding:2rem; font-size:0.82rem;">
                                            <i class="fas fa-box-open" style="font-size:1.3rem; opacity:0.25; display:block; margin-bottom:0.4rem;"></i>
                                            Sin insumos registrados
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>
</body>
</html>
