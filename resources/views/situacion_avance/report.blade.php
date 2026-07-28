<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Situación de Avance</title>
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
            --accent-s: #e8f0fc;
            --accent-b: #1f5bbf;
            --green:    #1e9166;
            --green-s:  #e5f6f0;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

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
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
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
        .btn-primary:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }

        /* Parámetros */
        .form-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 1.25rem;
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
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .fields-grid:last-of-type { margin-bottom: 0; }

        .field-label {
            display: block; font-size: 0.75rem; font-weight: 600;
            color: var(--text2); margin-bottom: 0.35rem;
        }
        .field-input {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.5rem 0.9rem;
            color: var(--text); width: 100%; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .field-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        /* searchable select */
        .ss-wrap { position: relative; }
        .ss-input {
            width: 100%; padding: 0.5rem 2rem 0.5rem 0.9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem; color: var(--text);
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.55rem; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .ss-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .ss-chevron {
            position: absolute; right: 0.7rem; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: 0.6rem; pointer-events: none;
        }
        .ss-list {
            display: none; position: absolute; top: calc(100% + 3px); left: 0; right: 0;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.5rem; box-shadow: 0 6px 20px rgba(0,0,0,0.09);
            z-index: 300; max-height: 200px; overflow-y: auto;
        }
        .ss-list.open { display: block; }
        .ss-option {
            padding: 0.45rem 0.8rem; font-size: 0.82rem; color: var(--text2);
            cursor: pointer; transition: background 0.1s;
        }
        .ss-option:hover, .ss-option.highlighted { background: var(--surface2); color: var(--text); }
        .ss-option.selected { color: var(--accent); font-weight: 700; }
        .ss-empty { padding: 0.5rem 0.8rem; font-size: 0.8rem; color: var(--muted); }

        /* dual range slider */
        .range-slider { position: relative; height: 20px; margin: 0.85rem 0.2rem 0.15rem; }
        .range-track {
            position: absolute; top: 50%; left: 0; right: 0; height: 4px;
            background: var(--border2); border-radius: 99px; transform: translateY(-50%);
        }
        .range-fill {
            position: absolute; top: 50%; height: 4px;
            background: var(--accent-s); border-radius: 99px; transform: translateY(-50%);
        }
        .range-input {
            position: absolute; top: 0; left: 0; width: 100%; height: 20px;
            margin: 0; background: transparent; pointer-events: none;
            -webkit-appearance: none; appearance: none;
        }
        .range-input::-webkit-slider-runnable-track { background: transparent; height: 20px; }
        .range-input::-moz-range-track { background: transparent; height: 20px; }
        .range-input::-webkit-slider-thumb {
            -webkit-appearance: none; pointer-events: auto;
            width: 15px; height: 15px; border-radius: 50%;
            border: 2px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.3);
            cursor: pointer; margin-top: 3px;
        }
        .range-input::-moz-range-thumb {
            pointer-events: auto; width: 15px; height: 15px; border-radius: 50%;
            border: 2px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.3); cursor: pointer;
        }
        .range-input.range-min::-webkit-slider-thumb { background: var(--accent); }
        .range-input.range-min::-moz-range-thumb { background: var(--accent); }
        .range-input.range-max::-webkit-slider-thumb { background: var(--green); }
        .range-input.range-max::-moz-range-thumb { background: var(--green); }
        .range-values {
            display: flex; justify-content: space-between;
            font-size: 0.7rem; font-weight: 600; color: var(--muted);
        }

        .form-actions { display: flex; justify-content: flex-end; gap: 0.5rem; }

        /* Resultados */
        .results-count { font-size: 0.8rem; color: var(--muted); margin-bottom: 0.75rem; }
        .tbl-wrap {
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.85rem; overflow: hidden; overflow-x: auto;
        }
        table { width: 100%; border-collapse: collapse; font-size: 0.78rem; table-layout: fixed; }
        thead tr { background: var(--surface2); }
        th {
            padding: 0.5rem 0.6rem; text-align: left; font-size: 0.66rem; font-weight: 700;
            color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em;
            border-bottom: 1.5px solid var(--border);
            overflow-wrap: break-word; word-break: break-word;
        }
        td {
            padding: 0.45rem 0.6rem; border-bottom: 1px solid var(--border);
            color: var(--text2); vertical-align: middle;
            overflow-wrap: break-word; word-break: break-word;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: var(--surface2); }
        .td-clave { font-weight: 700; color: var(--text); font-size: 0.8rem; }

        /* Anchos fijos por columna */
        th:nth-child(1),  td:nth-child(1)  { width: 10%; } /* Clave */
        th:nth-child(2),  td:nth-child(2)  { width: 11%; } /* Obra */
        th:nth-child(3),  td:nth-child(3)  { width: 10%; } /* Tipo de trabajo */
        th:nth-child(4),  td:nth-child(4)  { width: 9%;  } /* Monto total */
        th:nth-child(5),  td:nth-child(5)  { width: 8%;  } /* Fecha inicio */
        th:nth-child(6),  td:nth-child(6)  { width: 7%;  } /* Plazo */
        th:nth-child(7),  td:nth-child(7)  { width: 8%;  } /* Fecha fin */
        th:nth-child(8),  td:nth-child(8)  { width: 12%; } /* Facturado */
        th:nth-child(9),  td:nth-child(9)  { width: 12%; } /* Cobrado */
        th:nth-child(10), td:nth-child(10) { width: 8%;  } /* Total gastos */
        th:nth-child(11), td:nth-child(11) { width: 5%;  } /* Estado */

        .estado-badge {
            display: inline-flex; align-items: center;
            padding: 0.18rem 0.55rem; border-radius: 99px;
            font-size: 0.64rem; font-weight: 700; white-space: nowrap;
            background: var(--accent-s); color: var(--accent);
        }

        /* Mobile: tabla → tarjetas apiladas */
        @media (max-width: 768px) {
            .tbl-wrap { overflow-x: visible; }
            table, thead, tbody, th, td, tr { display: block; }
            thead { display: none; }
            tbody tr {
                border-bottom: 6px solid var(--bg);
                padding: 0.4rem 0;
            }
            tbody tr:last-child { border-bottom: none; }
            tbody tr:hover { background: none; }
            td {
                display: flex; align-items: center; justify-content: space-between;
                gap: 0.75rem; white-space: normal; text-align: right;
                border-bottom: 1px solid var(--border);
                padding: 0.5rem 0.9rem;
                width: auto !important;
            }
            tr td:last-child { border-bottom: none; }
            td::before {
                content: attr(data-label);
                font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
                letter-spacing: 0.03em; color: var(--muted);
                text-align: left; flex-shrink: 0;
            }
            .td-clave { justify-content: space-between; background: var(--surface2); }
        }

        .empty-state { padding: 3rem 1rem; text-align: center; color: var(--muted); }
        .empty-state i { font-size: 2rem; margin-bottom: 0.75rem; display: block; }
        .empty-state p { font-size: 0.85rem; }
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
                            <i class="fas fa-chart-line"></i>
                            <a href="{{ route('situacion_avance.index') }}">Situación de Avance</a>
                            <i class="fas fa-chevron-right"></i> Reporte
                        </div>
                        <h1 class="ph-title">Reporte de <em>situación de avance</em></h1>
                        <p class="ph-sub">Filtrá por los mismos parámetros del listado y generá el PDF</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('situacion_avance.index') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <form method="GET" action="{{ route('situacion_avance.report') }}">
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="fas fa-sliders-h"></i> Parámetros
                        </div>
                        <div class="form-card-body">
                            <div class="fields-grid">
                                <div>
                                    <label class="field-label">Obra</label>
                                    <div class="ss-wrap" data-ss="obra">
                                        <input type="text" class="ss-input" placeholder="Todas…" autocomplete="off">
                                        <i class="fas fa-chevron-down ss-chevron"></i>
                                        <div class="ss-list">
                                            <div class="ss-option {{ !request('obra') ? 'selected' : '' }}" data-value="">Todas</div>
                                            @foreach($obras as $obra)
                                                <div class="ss-option {{ (string) request('obra') === (string) $obra->id ? 'selected' : '' }}" data-value="{{ $obra->id }}">{{ $obra->nombre }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" name="obra" value="{{ request('obra') }}">
                                </div>
                                <div>
                                    <label class="field-label">Tipo de trabajo</label>
                                    <select name="tipo" class="field-input">
                                        <option value="">Todos</option>
                                        @foreach($tipoTrabajo as $key => $label)
                                            <option value="{{ $key }}" {{ request('tipo') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="field-label">Estado</label>
                                    <select name="estado" class="field-input">
                                        <option value="">Todos</option>
                                        @foreach($estados as $estadoOpt)
                                            <option value="{{ $estadoOpt->id }}" {{ request('estado') == $estadoOpt->id ? 'selected' : '' }}>{{ $estadoOpt->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="fields-grid">
                                <div>
                                    <label class="field-label">Fecha inicio — Mes</label>
                                    <select name="mes" class="field-input">
                                        <option value="">Todos</option>
                                        @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $mesNombre)
                                            <option value="{{ $i + 1 }}" {{ request('mes') == $i + 1 ? 'selected' : '' }}>{{ $mesNombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="field-label">Fecha inicio — Año</label>
                                    <select name="anio" class="field-input">
                                        <option value="">Todos</option>
                                        @foreach($anios as $anioOpt)
                                            <option value="{{ $anioOpt }}" {{ request('anio') == $anioOpt ? 'selected' : '' }}>{{ $anioOpt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="field-label">Fecha fin — Mes</label>
                                    <select name="mes_fin" class="field-input">
                                        <option value="">Todos</option>
                                        @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $mesNombre)
                                            <option value="{{ $i + 1 }}" {{ request('mes_fin') == $i + 1 ? 'selected' : '' }}>{{ $mesNombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="field-label">Fecha fin — Año</label>
                                    <select name="anio_fin" class="field-input">
                                        <option value="">Todos</option>
                                        @foreach($anios as $anioOpt)
                                            <option value="{{ $anioOpt }}" {{ request('anio_fin') == $anioOpt ? 'selected' : '' }}>{{ $anioOpt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="fields-grid">
                                <div>
                                    <label class="field-label">Monto</label>
                                    <div class="range-slider" data-range="monto" data-format="currency">
                                        <div class="range-track"></div>
                                        <div class="range-fill"></div>
                                        <input type="range" min="{{ $montoMin }}" max="{{ $montoMax }}" value="{{ request('monto_min', $montoMin) }}" class="range-input range-min">
                                        <input type="range" min="{{ $montoMin }}" max="{{ $montoMax }}" value="{{ request('monto_max', $montoMax) }}" class="range-input range-max">
                                    </div>
                                    <div class="range-values">
                                        <span class="range-value-min">Gs. {{ number_format(request('monto_min', $montoMin), 0, ',', '.') }}</span>
                                        <span class="range-value-max">Gs. {{ number_format(request('monto_max', $montoMax), 0, ',', '.') }}</span>
                                    </div>
                                    <input type="hidden" name="monto_min" value="{{ request('monto_min', $montoMin) }}">
                                    <input type="hidden" name="monto_max" value="{{ request('monto_max', $montoMax) }}">
                                </div>
                                <div>
                                    <label class="field-label">% Facturado</label>
                                    <div class="range-slider" data-range="facturado">
                                        <div class="range-track"></div>
                                        <div class="range-fill"></div>
                                        <input type="range" min="0" max="100" value="{{ request('fac_min', 0) }}"   class="range-input range-min">
                                        <input type="range" min="0" max="100" value="{{ request('fac_max', 100) }}" class="range-input range-max">
                                    </div>
                                    <div class="range-values">
                                        <span class="range-value-min">{{ request('fac_min', 0) }}%</span>
                                        <span class="range-value-max">{{ request('fac_max', 100) }}%</span>
                                    </div>
                                    <input type="hidden" name="fac_min" value="{{ request('fac_min', 0) }}">
                                    <input type="hidden" name="fac_max" value="{{ request('fac_max', 100) }}">
                                </div>
                                <div>
                                    <label class="field-label">% Cobrado</label>
                                    <div class="range-slider" data-range="cobrado">
                                        <div class="range-track"></div>
                                        <div class="range-fill"></div>
                                        <input type="range" min="0" max="100" value="{{ request('cob_min', 0) }}"   class="range-input range-min">
                                        <input type="range" min="0" max="100" value="{{ request('cob_max', 100) }}" class="range-input range-max">
                                    </div>
                                    <div class="range-values">
                                        <span class="range-value-min">{{ request('cob_min', 0) }}%</span>
                                        <span class="range-value-max">{{ request('cob_max', 100) }}%</span>
                                    </div>
                                    <input type="hidden" name="cob_min" value="{{ request('cob_min', 0) }}">
                                    <input type="hidden" name="cob_max" value="{{ request('cob_max', 100) }}">
                                </div>
                            </div>

                            <div class="form-actions">
                                <a href="{{ route('situacion_avance.report') }}" class="btn">
                                    <i class="fas fa-eraser"></i> Limpiar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Generar reporte
                                </button>
                                <button type="submit" formaction="{{ route('situacion_avance.report.pdf') }}" class="btn">
                                    <i class="fas fa-file-pdf"></i> Generar PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <p class="results-count">{{ $filas->count() }} resultado{{ $filas->count() != 1 ? 's' : '' }}</p>

                @if($filas->isEmpty())
                    <div class="tbl-wrap">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No hay presupuestos que coincidan con los parámetros seleccionados.</p>
                        </div>
                    </div>
                @else
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Clave</th>
                                    <th>Obra</th>
                                    <th>Tipo de trabajo</th>
                                    <th>Monto total</th>
                                    <th>Fecha inicio</th>
                                    <th>Plazo (días)</th>
                                    <th>Fecha fin</th>
                                    <th>Facturado</th>
                                    <th>Cobrado</th>
                                    <th>Total gastos</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($filas as $fila)
                                    <tr>
                                        <td class="td-clave" data-label="Clave">{{ $fila->presupuesto->clave }}</td>
                                        <td data-label="Obra">{{ $fila->presupuesto->obra?->nombre ?? '—' }}</td>
                                        <td data-label="Tipo de trabajo">{{ config('constantes.tipo_trabajo')[$fila->presupuesto->tipo_trabajo] ?? '—' }}</td>
                                        <td data-label="Monto total">{{ $fila->monto > 0 ? 'Gs. ' . number_format($fila->monto, 0, ',', '.') : '—' }}</td>
                                        <td data-label="Fecha inicio">{{ $fila->avance?->fecha_inicio ? \Carbon\Carbon::parse($fila->avance->fecha_inicio)->format('d/m/Y') : '—' }}</td>
                                        <td data-label="Plazo (días)">{{ $fila->avance?->plazo ? $fila->avance->plazo . ' días' : '—' }}</td>
                                        <td data-label="Fecha fin">{{ $fila->avance?->fecha_fin ? \Carbon\Carbon::parse($fila->avance->fecha_fin)->format('d/m/Y') : '—' }}</td>
                                        <td data-label="Facturado">Gs. {{ number_format($fila->facturado, 0, ',', '.') }} ({{ $fila->pctFac }}%)</td>
                                        <td data-label="Cobrado">Gs. {{ number_format($fila->cobrado, 0, ',', '.') }} ({{ $fila->pctCob }}%)</td>
                                        <td data-label="Total gastos">Gs. {{ number_format($fila->totalGastos, 0, ',', '.') }}</td>
                                        <td data-label="Estado"><span class="estado-badge">{{ $fila->estado }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Searchable select
    document.querySelectorAll('.ss-wrap').forEach(wrap => {
        const input   = wrap.querySelector('.ss-input');
        const list    = wrap.querySelector('.ss-list');
        const options = Array.from(list.querySelectorAll('.ss-option'));
        const hidden  = wrap.parentElement.querySelector('input[type="hidden"]');

        const selected = options.find(o => o.classList.contains('selected'));
        input.value = selected && selected.dataset.value !== '' ? selected.textContent.trim() : '';

        function showList() { list.classList.add('open'); }
        function hideList() { list.classList.remove('open'); }

        function filterOptions(term) {
            let any = false;
            options.forEach(opt => {
                const match = opt.textContent.toLowerCase().includes(term.toLowerCase());
                opt.style.display = match ? '' : 'none';
                if (match) any = true;
            });
            list.querySelector('.ss-empty')?.remove();
            if (!any) {
                const empty = document.createElement('div');
                empty.className = 'ss-empty';
                empty.textContent = 'Sin coincidencias';
                list.appendChild(empty);
            }
        }

        function selectOption(opt) {
            options.forEach(o => o.classList.remove('selected'));
            opt.classList.add('selected');
            if (hidden) hidden.value = opt.dataset.value;
            input.value = opt.dataset.value === '' ? '' : opt.textContent.trim();
            options.forEach(o => o.style.display = '');
            list.querySelector('.ss-empty')?.remove();
            hideList();
        }

        input.addEventListener('focus', () => { filterOptions(input.value); showList(); });
        input.addEventListener('input', () => { filterOptions(input.value); showList(); });

        input.addEventListener('keydown', e => {
            const visible = options.filter(o => o.style.display !== 'none');
            const hi = list.querySelector('.highlighted');
            let idx = visible.indexOf(hi);
            if (e.key === 'ArrowDown') { e.preventDefault(); hi?.classList.remove('highlighted'); visible[Math.min(idx+1, visible.length-1)]?.classList.add('highlighted'); }
            if (e.key === 'ArrowUp')   { e.preventDefault(); hi?.classList.remove('highlighted'); visible[Math.max(idx-1, 0)]?.classList.add('highlighted'); }
            if (e.key === 'Enter' && hi) { e.preventDefault(); selectOption(hi); hi.classList.remove('highlighted'); }
            if (e.key === 'Escape') hideList();
        });

        options.forEach(opt => {
            opt.addEventListener('mousedown', e => { e.preventDefault(); selectOption(opt); });
        });

        input.addEventListener('blur', () => {
            setTimeout(() => {
                const sel = options.find(o => o.classList.contains('selected'));
                input.value = sel && sel.dataset.value !== '' ? sel.textContent.trim() : '';
                options.forEach(o => o.style.display = '');
                list.querySelector('.ss-empty')?.remove();
                hideList();
            }, 150);
        });
    });

    function formatRangeValue(format, value) {
        return format === 'currency'
            ? 'Gs. ' + Number(value).toLocaleString('de-DE')
            : value + '%';
    }

    document.querySelectorAll('.range-slider').forEach(slider => {
        const wrap      = slider.parentElement;
        const format    = slider.dataset.format || 'percent';
        const minInput  = slider.querySelector('.range-min');
        const maxInput  = slider.querySelector('.range-max');
        const fill      = slider.querySelector('.range-fill');
        const valMin    = wrap.querySelector('.range-value-min');
        const valMax    = wrap.querySelector('.range-value-max');
        const hiddenMin = wrap.querySelector('input[type="hidden"][name$="_min"]');
        const hiddenMax = wrap.querySelector('input[type="hidden"][name$="_max"]');
        const sliderMin = parseFloat(minInput.min);
        const sliderMax = parseFloat(minInput.max);
        const span      = sliderMax - sliderMin || 1;

        function update() {
            let min = parseFloat(minInput.value);
            let max = parseFloat(maxInput.value);
            if (min > max) { [min, max] = [max, min]; }

            minInput.value = min;
            maxInput.value = max;

            fill.style.left  = ((min - sliderMin) / span * 100) + '%';
            fill.style.right = (100 - (max - sliderMin) / span * 100) + '%';
            valMin.textContent = formatRangeValue(format, min);
            valMax.textContent = formatRangeValue(format, max);

            if (hiddenMin) hiddenMin.value = min;
            if (hiddenMax) hiddenMax.value = max;
        }

        minInput.addEventListener('input', update);
        maxInput.addEventListener('input', update);
        update();
    });
});
</script>
</body>
</html>
