<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situación de Avance</title>
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
            --green:    #1e9166;
            --green-s:  #e5f6f0;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* PAGE HEADER */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
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
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }

        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        /* BUTTONS */
        .btn {
            height: 38px;
            padding: 0 1rem;
            border-radius: 0.55rem;
            display: inline-flex;
            align-items: center;
            gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem;
            font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface);
            color: var(--text2);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.14s;
            white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-sm {
            height: 30px;
            padding: 0 0.7rem;
            font-size: 0.75rem;
            border-radius: 0.45rem;
        }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; }

        /* SEARCH */
        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: 0.78rem; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: 0.72rem; pointer-events: none;
        }
        .search-bar {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.83rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            padding: 0.5rem 0.9rem 0.5rem 2.1rem;
            color: var(--text);
            width: 220px; outline: none;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
            height: 38px;
        }
        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus { border-color: var(--accent); width: 270px; box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        /* TABS */
        .tabs-wrapper {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
        }
        .tabs-nav {
            display: flex; overflow-x: auto;
            border-bottom: 1.5px solid var(--border);
            background: var(--surface2);
            scrollbar-width: none;
        }
        .tabs-nav::-webkit-scrollbar { display: none; }
        .tab-btn {
            flex-shrink: 0;
            padding: 0.85rem 1.25rem;
            font-size: 0.83rem; font-weight: 600;
            color: var(--muted); background: transparent;
            border: none; border-bottom: 2.5px solid transparent;
            cursor: pointer;
            transition: color 0.14s, border-color 0.14s;
            white-space: nowrap;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .tab-btn:hover { color: var(--text2); }
        .tab-btn.active { color: var(--accent); border-bottom-color: var(--accent); background: var(--surface); }
        .tab-badge {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 20px; height: 20px; padding: 0 5px;
            border-radius: 99px; font-size: 0.7rem; font-weight: 700;
            background: var(--border); color: var(--text2);
        }
        .tab-btn.active .tab-badge { background: var(--accent-s); color: var(--accent); }
        .tab-pane { display: none; padding: 1.25rem; }
        .tab-pane.active { display: block; }

        /* TABLE */
        .tbl-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
        thead tr { background: var(--surface2); }
        th {
            padding: 0.65rem 0.9rem;
            text-align: left; font-size: 0.72rem; font-weight: 700;
            color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em;
            border-bottom: 1.5px solid var(--border); white-space: nowrap;
        }
        td { padding: 0.7rem 0.9rem; border-bottom: 1px solid var(--border); color: var(--text2); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr.has-obs td { border-bottom: none !important; }
        tbody tr:hover { background: var(--surface2); }
        .td-clave { font-weight: 700; color: var(--text); font-size: 0.85rem; }

        /* PROGRESS BARS */
        .prog-wrap { display: flex; flex-direction: column; gap: 4px; min-width: 110px; }
        .prog-label { font-size: 0.68rem; color: var(--muted); display: flex; justify-content: space-between; }
        .prog-track {
            height: 6px; border-radius: 99px;
            background: var(--border2); overflow: hidden;
        }
        .prog-bar { height: 100%; border-radius: 99px; transition: width 0.4s; }
        .prog-bar-fac { background: var(--accent); }
        .prog-bar-cob { background: var(--green); }

        /* OBSERVACION ROW */
        .obs-row { background: transparent !important; }
        .obs-cell {
            padding: 0.2rem 0.9rem 0.6rem 1.1rem !important;
            font-size: 0.75rem;
            color: var(--muted);
            font-style: italic;
            border-top: none !important;
            border-bottom: 1px solid var(--border) !important;
        }
        .obs-cell i { margin-right: 0.3rem; font-size: 0.65rem; }

        /* EMPTY */
        .empty-state { padding: 3rem 1rem; text-align: center; color: var(--muted); }
        .empty-state i { font-size: 2rem; margin-bottom: 0.75rem; display: block; }
        .empty-state p { font-size: 0.85rem; }

        /* DROPDOWN */
        .dropdown { position: relative; display: inline-block; }
        .dropdown-menu {
            display: none; position: fixed;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.6rem; min-width: 160px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1); z-index: 1000;
            overflow: hidden;
        }
        .dropdown-menu.open { display: block; }
        .dropdown-item {
            display: block; width: 100%;
            padding: 0.55rem 0.9rem;
            font-size: 0.82rem; font-weight: 500;
            color: var(--text2); background: none; border: none;
            text-align: left; cursor: pointer;
            transition: background 0.12s;
        }
        .dropdown-item:hover { background: var(--surface2); color: var(--text); }
        .dropdown-item.current { color: var(--accent); font-weight: 700; }

        /* MODAL */
        .modal-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(15,24,40,0.45); z-index: 500;
            align-items: center; justify-content: center;
        }
        .modal-backdrop.open { display: flex; }
        .modal-box {
            background: var(--surface); border-radius: 0.9rem;
            padding: 1.75rem; width: 100%; max-width: 480px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            animation: modalIn 0.2s ease;
        }
        @keyframes modalIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .modal-title { font-size: 1.05rem; font-weight: 700; color: var(--text); margin-bottom: 1.25rem; }
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: 0.78rem; font-weight: 600; color: var(--text2); margin-bottom: 0.35rem; }
        .form-control {
            width: 100%; padding: 0.5rem 0.75rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem; color: var(--text);
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.55rem; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .modal-actions { display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem; }

        /* FILTER PANEL */
        .filter-wrap { position: relative; }
        .filter-panel {
            display: none;
            position: absolute;
            right: 0; top: calc(100% + 6px);
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.75rem;
            box-shadow: 0 10px 32px rgba(0,0,0,0.11);
            padding: 1.1rem 1.25rem 1.25rem;
            width: 310px;
            z-index: 200;
        }
        .filter-panel.open { display: block; }
        .filter-panel-title {
            font-size: 0.72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--muted); margin-bottom: 0.85rem;
        }
        .filter-group { margin-bottom: 0.75rem; }
        .filter-label {
            display: block; font-size: 0.75rem; font-weight: 600;
            color: var(--text2); margin-bottom: 0.3rem;
        }
        /* dual range slider */
        .range-slider { position: relative; height: 20px; margin: 0.5rem 0.2rem 0.15rem; }
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
        /* searchable select */
        .ss-wrap { position: relative; }
        .ss-input {
            width: 100%; padding: 0.45rem 2rem 0.45rem 0.7rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.82rem; color: var(--text);
            background: var(--surface2); border: 1.5px solid var(--border);
            border-radius: 0.5rem; outline: none;
            transition: border-color 0.14s;
        }
        .ss-input:focus { border-color: var(--accent); }
        .ss-chevron {
            position: absolute; right: 0.6rem; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: 0.6rem; pointer-events: none;
        }
        .ss-list {
            display: none; position: absolute; top: calc(100% + 3px); left: 0; right: 0;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.5rem; box-shadow: 0 6px 20px rgba(0,0,0,0.09);
            z-index: 300; max-height: 180px; overflow-y: auto;
        }
        .ss-list.open { display: block; }
        .ss-option {
            padding: 0.45rem 0.75rem; font-size: 0.82rem; color: var(--text2);
            cursor: pointer; transition: background 0.1s;
        }
        .ss-option:hover, .ss-option.highlighted { background: var(--surface2); color: var(--text); }
        .ss-option.selected { color: var(--accent); font-weight: 700; }
        .ss-empty { padding: 0.5rem 0.75rem; font-size: 0.8rem; color: var(--muted); }
        .filter-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
        .filter-actions { display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1rem; }
        .btn-filter-active { background: var(--accent-s); border-color: var(--accent); color: var(--accent); }

        /* COUNTDOWN BADGES */
        .badge-countdown {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.2rem 0.55rem; border-radius: 99px;
            font-size: 0.72rem; font-weight: 700; white-space: nowrap;
        }
        .badge-ok   { background: #e5f6f0; color: #1e9166; }
        .badge-soon { background: #fef9ec; color: #d4920a; }
        .badge-late { background: #fdeef5; color: #c0507a; }

        /* PDF MODAL */
        .pdf-modal-box {
            background: var(--surface); border-radius: 0.9rem;
            padding: 1.25rem; width: 94vw; max-width: 900px;
            height: 82vh;
            display: flex; flex-direction: column;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: modalIn 0.2s ease;
        }
        .pdf-modal-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 0.85rem;
        }
        .pdf-modal-title { font-size: 1rem; font-weight: 700; color: var(--text); }
        .pdf-modal-close {
            width: 30px; height: 30px; border-radius: 0.45rem;
            border: 1.5px solid var(--border); background: var(--surface2);
            color: var(--text2); cursor: pointer; font-size: 0.85rem;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.14s;
        }
        .pdf-modal-close:hover { background: var(--border); color: var(--text); }
        .pdf-iframe {
            width: 100%; flex: 1; min-height: 0;
            border: 1.5px solid var(--border); border-radius: 0.55rem;
            flex: 1; min-height: 0;
        }

        /* ALERT */
        .alert-success {
            background: var(--green-s); border: 1px solid #a8dcc9;
            color: var(--green); border-radius: 0.55rem;
            padding: 0.7rem 1rem; font-size: 0.85rem; margin-bottom: 1rem;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    @include('partials.navbar')
    @include('partials.sidebar')

    <div class="content-wrapper">
        <div class="container-fluid">

            <!-- Page Header -->
            <div class="ph">
                <div>
                    <div class="ph-crumb">
                        <i class="fas fa-home"></i>
                        <a href="{{ route('home') }}">Inicio</a>
                        <i class="fas fa-chevron-right"></i>
                        <i class="fas fa-tasks"></i>
                        Situación de Avance
                    </div>
                    <h1 class="ph-title">Situación de <em>Avance</em></h1>
                    <p class="ph-sub">Presupuestos aprobados agrupados por estado de avance</p>
                </div>
                <div class="ph-right">
                    <div class="search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" id="global-search" class="search-bar" placeholder="Buscar presupuesto…" autocomplete="off">
                    </div>

                    <!-- Filtros -->
                    <div class="filter-wrap">
                        <button class="btn" id="filter-toggle">
                            <i class="fas fa-filter"></i> Filtrar <span id="filter-count" style="display:none; background:var(--accent); color:#fff; border-radius:99px; padding:0 6px; font-size:0.7rem; margin-left:2px"></span>
                        </button>
                        <div class="filter-panel" id="filter-panel">
                            <div class="filter-panel-title"><i class="fas fa-sliders-h"></i> Filtros</div>

                            <div class="filter-group">
                                <label class="filter-label">Obra</label>
                                <div class="ss-wrap" data-ss="f-obra">
                                    <input type="text" class="ss-input" placeholder="Todas…" autocomplete="off">
                                    <i class="fas fa-chevron-down ss-chevron"></i>
                                    <div class="ss-list">
                                        <div class="ss-option selected" data-value="">Todas</div>
                                        @foreach($obras as $obra)
                                            <div class="ss-option" data-value="{{ $obra->id }}">{{ $obra->nombre }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="filter-panel-title" style="margin-top:0.5rem"><i class="fas fa-calendar-alt"></i> Fecha inicio</div>
                            <div class="filter-row">
                                <div class="filter-group">
                                    <label class="filter-label">Mes</label>
                                    <div class="ss-wrap" data-ss="f-mes">
                                        <input type="text" class="ss-input" placeholder="Todos…" autocomplete="off">
                                        <i class="fas fa-chevron-down ss-chevron"></i>
                                        <div class="ss-list">
                                            <div class="ss-option selected" data-value="">Todos</div>
                                            @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $mes)
                                                <div class="ss-option" data-value="{{ $i + 1 }}">{{ $mes }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="filter-group">
                                    <label class="filter-label">Año</label>
                                    <div class="ss-wrap" data-ss="f-anio">
                                        <input type="text" class="ss-input" placeholder="Todos…" autocomplete="off">
                                        <i class="fas fa-chevron-down ss-chevron"></i>
                                        <div class="ss-list">
                                            <div class="ss-option selected" data-value="">Todos</div>
                                            @foreach($anios as $anio)
                                                <div class="ss-option" data-value="{{ $anio }}">{{ $anio }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="filter-panel-title" style="margin-top:0.25rem"><i class="fas fa-calendar-check"></i> Fecha fin</div>
                            <div class="filter-row">
                                <div class="filter-group">
                                    <label class="filter-label">Mes</label>
                                    <div class="ss-wrap" data-ss="f-mes-fin">
                                        <input type="text" class="ss-input" placeholder="Todos…" autocomplete="off">
                                        <i class="fas fa-chevron-down ss-chevron"></i>
                                        <div class="ss-list">
                                            <div class="ss-option selected" data-value="">Todos</div>
                                            @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $mes)
                                                <div class="ss-option" data-value="{{ $i + 1 }}">{{ $mes }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="filter-group">
                                    <label class="filter-label">Año</label>
                                    <div class="ss-wrap" data-ss="f-anio-fin">
                                        <input type="text" class="ss-input" placeholder="Todos…" autocomplete="off">
                                        <i class="fas fa-chevron-down ss-chevron"></i>
                                        <div class="ss-list">
                                            <div class="ss-option selected" data-value="">Todos</div>
                                            @foreach($anios as $anio)
                                                <div class="ss-option" data-value="{{ $anio }}">{{ $anio }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">Tipo de trabajo</label>
                                <div class="ss-wrap" data-ss="f-tipo">
                                    <input type="text" class="ss-input" placeholder="Todos…" autocomplete="off">
                                    <i class="fas fa-chevron-down ss-chevron"></i>
                                    <div class="ss-list">
                                        <div class="ss-option selected" data-value="">Todos</div>
                                        @foreach($tipoTrabajo as $key => $label)
                                            <div class="ss-option" data-value="{{ $key }}">{{ $label }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">% Facturado</label>
                                <div class="range-slider" data-range="f-facturado">
                                    <div class="range-track"></div>
                                    <div class="range-fill"></div>
                                    <input type="range" min="0" max="100" value="0"   class="range-input range-min">
                                    <input type="range" min="0" max="100" value="100" class="range-input range-max">
                                </div>
                                <div class="range-values">
                                    <span class="range-value-min">0%</span>
                                    <span class="range-value-max">100%</span>
                                </div>
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">% Cobrado</label>
                                <div class="range-slider" data-range="f-cobrado">
                                    <div class="range-track"></div>
                                    <div class="range-fill"></div>
                                    <input type="range" min="0" max="100" value="0"   class="range-input range-min">
                                    <input type="range" min="0" max="100" value="100" class="range-input range-max">
                                </div>
                                <div class="range-values">
                                    <span class="range-value-min">0%</span>
                                    <span class="range-value-max">100%</span>
                                </div>
                            </div>

                            <div class="filter-actions">
                                <button class="btn btn-sm" id="filter-clear">Limpiar</button>
                                <button class="btn btn-sm btn-primary" id="filter-apply">Aplicar</button>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('situacion_avance.report') }}" class="btn">
                        <i class="fas fa-file-alt"></i> Reporte
                    </a>

                    <a href="{{ route('home') }}" class="btn">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif

            <!-- Tabs -->
            <div class="tabs-wrapper">
                <nav class="tabs-nav">
                    @foreach($estados as $estado)
                        <button class="tab-btn {{ $loop->first ? 'active' : '' }}"
                                data-tab="tab-{{ $estado->id }}">
                            {{ $estado->descripcion }}
                            <span class="tab-badge">{{ $presupuestosPorEstado[$estado->id]->count() }}</span>
                        </button>
                    @endforeach
                </nav>

                @foreach($estados as $estado)
                    <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="tab-{{ $estado->id }}">
                        @if($presupuestosPorEstado[$estado->id]->isEmpty())
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>No hay presupuestos en este estado.</p>
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
                                            @if($estado->descripcion === 'Finalizado')
                                                <th>Fecha fin</th>
                                            @endif
                                            <th>Facturado</th>
                                            <th>Cobrado</th>
                                            <th></th>
                                            @if($puedeVerFac)
                                                <th></th>
                                            @endif
                                            @if($puedeEditar)
                                                <th>Estado</th>
                                                <th></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($presupuestosPorEstado[$estado->id] as $presupuesto)
                                            @php
                                                $avance       = $presupuesto->situacionAvances->sortByDesc('id')->first();
                                                $monto        = (float) $presupuesto->monto_total;
                                                $facturado    = $presupuesto->facturasVenta->sum('monto');
                                                $cobrado      = $presupuesto->recibosVenta->sum('monto');
                                                $pctFac       = $monto > 0 ? min(100, round($facturado / $monto * 100)) : 0;
                                                $pctCob       = $facturado > 0 ? min(100, round($cobrado / $facturado * 100)) : 0;
                                                $diasRestantes = null;
                                                if ($avance?->fecha_inicio) {
                                                    $diasRestantes = (int) \Carbon\Carbon::today()->diffInDays(
                                                        \Carbon\Carbon::parse($avance->fecha_inicio), false
                                                    );
                                                }
                                                $diasPlazoRestantes = null;
                                                if ($avance?->fecha_inicio && $avance?->plazo) {
                                                    $fechaVencimiento   = \Carbon\Carbon::parse($avance->fecha_inicio)->addDays($avance->plazo);
                                                    $diasPlazoRestantes = (int) \Carbon\Carbon::today()->diffInDays($fechaVencimiento, false);
                                                }
                                                $diasFinalizado = null;
                                                if ($avance?->fecha_fin && $estado->descripcion === 'Finalizado') {
                                                    $diasFinalizado = (int) \Carbon\Carbon::parse($avance->fecha_fin)->diffInDays(\Carbon\Carbon::today());
                                                }
                                            @endphp
                                            <tr {{ $avance?->observacion ? 'class=has-obs' : '' }}
                                                data-obra="{{ $presupuesto->obra_id }}"
                                                data-tipo="{{ $presupuesto->tipo_trabajo }}"
                                                data-mes="{{ $avance?->fecha_inicio ? \Carbon\Carbon::parse($avance->fecha_inicio)->month : '' }}"
                                                data-anio="{{ $avance?->fecha_inicio ? \Carbon\Carbon::parse($avance->fecha_inicio)->year : '' }}"
                                                data-mes-fin="{{ $avance?->fecha_fin ? \Carbon\Carbon::parse($avance->fecha_fin)->month : '' }}"
                                                data-anio-fin="{{ $avance?->fecha_fin ? \Carbon\Carbon::parse($avance->fecha_fin)->year : '' }}"
                                                data-pct-fac="{{ $pctFac }}"
                                                data-pct-cob="{{ $pctCob }}">
                                                <td class="td-clave">
                                                    {{ $presupuesto->clave }}
                                                    <div style="font-size:0.72rem; font-weight:500; margin-top:2px; color:var(--muted);">
                                                        @if($presupuesto->orden_trabajo)
                                                            <i class="fas fa-file-alt" style="font-size:0.65rem"></i> {{ $presupuesto->orden_trabajo }}
                                                        @else
                                                            <i class="fas fa-clock" style="font-size:0.65rem; color:#d4920a"></i> <span style="color:#d4920a">OT pendiente</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $presupuesto->obra?->nombre ?? '—' }}</td>
                                                <td>{{ config('constantes.tipo_trabajo')[$presupuesto->tipo_trabajo] ?? '—' }}</td>
                                                <td>{{ $monto > 0 ? 'Gs. ' . number_format($monto, 0, ',', '.') : '—' }}</td>

                                                <!-- Fecha inicio -->
                                                <td>
                                                    @if($avance?->fecha_inicio)
                                                        {{ \Carbon\Carbon::parse($avance->fecha_inicio)->format('d/m/Y') }}
                                                        @if($estado->descripcion === 'Agendado' && $diasRestantes !== null)
                                                            @if($diasRestantes < 0)
                                                                <br><span class="badge-countdown badge-late">
                                                                    <i class="fas fa-exclamation-circle"></i>
                                                                    Debía iniciar hace {{ abs($diasRestantes) }} día{{ abs($diasRestantes) != 1 ? 's' : '' }}
                                                                </span>
                                                            @elseif($diasRestantes <= 7)
                                                                <br><span class="badge-countdown badge-soon">
                                                                    <i class="fas fa-clock"></i>
                                                                    {{ $diasRestantes == 0 ? 'Inicia hoy' : 'Faltan ' . $diasRestantes . ' día' . ($diasRestantes != 1 ? 's' : '') }}
                                                                </span>
                                                            @else
                                                                <br><span class="badge-countdown badge-ok">
                                                                    <i class="fas fa-calendar-check"></i>
                                                                    Faltan {{ $diasRestantes }} días
                                                                </span>
                                                            @endif
                                                        @endif
                                                    @else
                                                        —
                                                    @endif
                                                </td>

                                                <!-- Plazo -->
                                                <td>
                                                    {{ $avance?->plazo ? $avance->plazo . ' días' : '—' }}
                                                    @if($estado->descripcion === 'En curso' && $diasPlazoRestantes !== null)
                                                        @if($diasPlazoRestantes < 0)
                                                            <br><span class="badge-countdown badge-late">
                                                                <i class="fas fa-exclamation-circle"></i>
                                                                Plazo vencido hace {{ abs($diasPlazoRestantes) }} día{{ abs($diasPlazoRestantes) != 1 ? 's' : '' }}
                                                            </span>
                                                        @elseif($diasPlazoRestantes <= 7)
                                                            <br><span class="badge-countdown badge-soon">
                                                                <i class="fas fa-clock"></i>
                                                                {{ $diasPlazoRestantes == 0 ? 'Vence hoy' : 'Vence en ' . $diasPlazoRestantes . ' día' . ($diasPlazoRestantes != 1 ? 's' : '') }}
                                                            </span>
                                                        @else
                                                            <br><span class="badge-countdown badge-ok">
                                                                <i class="fas fa-calendar-check"></i>
                                                                Quedan {{ $diasPlazoRestantes }} días
                                                            </span>
                                                        @endif
                                                    @endif
                                                </td>

                                                <!-- Fecha fin (solo Finalizado) -->
                                                @if($estado->descripcion === 'Finalizado')
                                                <td>
                                                    {{ $avance?->fecha_fin ? \Carbon\Carbon::parse($avance->fecha_fin)->format('d/m/Y') : '—' }}
                                                    @if($diasFinalizado !== null)
                                                        <br><span class="badge-countdown badge-ok">
                                                            <i class="fas fa-flag-checkered"></i>
                                                            Hace {{ $diasFinalizado == 0 ? 'hoy' : $diasFinalizado . ' día' . ($diasFinalizado != 1 ? 's' : '') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                @endif

                                                <!-- % Facturado -->
                                                <td>
                                                    <div class="prog-wrap">
                                                        <div class="prog-label">
                                                            <span>Facturado</span><span>{{ $pctFac }}%</span>
                                                        </div>
                                                        <div class="prog-track">
                                                            <div class="prog-bar prog-bar-fac" style="width:{{ $pctFac }}%"></div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- % Cobrado -->
                                                <td>
                                                    <div class="prog-wrap">
                                                        <div class="prog-label">
                                                            <span>Cobrado</span><span>{{ $pctCob }}%</span>
                                                        </div>
                                                        <div class="prog-track">
                                                            <div class="prog-bar prog-bar-cob" style="width:{{ $pctCob }}%"></div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Ver PDF presupuesto -->
                                                <td>
                                                    @if($presupuesto->presupuesto)
                                                    <button class="btn btn-sm btn-view-presupuesto"
                                                            data-pdf-url="{{ Storage::url('presupuestos/' . $presupuesto->presupuesto) }}"
                                                            data-clave="{{ $presupuesto->clave }}">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </button>
                                                    @else
                                                    <button class="btn btn-sm" disabled title="Sin PDF" style="opacity:.4">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </button>
                                                    @endif
                                                </td>

                                                @if($puedeVerFac)
                                                <td>
                                                    <a href="{{ route('factura_venta.index', [$presupuesto->id, $presupuesto->obra_id]) }}"
                                                       class="btn btn-sm">
                                                        <i class="fas fa-file-invoice-dollar"></i> Facturación
                                                    </a>
                                                </td>
                                                @endif

                                                @if($puedeEditar)
                                                <!-- Cambiar estado -->
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm dropdown-toggle-btn"
                                                                data-avance-id="{{ $avance?->id }}"
                                                                data-current-estado="{{ $avance?->estado_situacion_id }}">
                                                            {{ $estado->descripcion }} <i class="fas fa-chevron-down" style="font-size:0.6rem"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            @foreach($estados as $est)
                                                                <button class="dropdown-item {{ $est->id == $avance?->estado_situacion_id ? 'current' : '' }}"
                                                                        data-avance-id="{{ $avance?->id }}"
                                                                        data-estado-id="{{ $est->id }}">
                                                                    {{ $est->descripcion }}
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Editar -->
                                                <td>
                                                    <button class="btn btn-sm btn-edit"
                                                            data-avance-id="{{ $avance?->id }}"
                                                            data-clave="{{ $presupuesto->clave }}"
                                                            data-fecha="{{ $avance?->fecha_inicio }}"
                                                            data-fecha-fin="{{ $avance?->fecha_fin }}"
                                                            data-plazo="{{ $avance?->plazo }}"
                                                            data-obs="{{ $avance?->observacion }}">
                                                        <i class="fas fa-pen"></i>
                                                    </button>
                                                </td>
                                                @endif
                                            </tr>
                                            @if($avance?->observacion)
                                            <tr class="obs-row" data-obs-row>
                                                <td colspan="{{ ($puedeEditar ? 11 : 9) + ($puedeVerFac ? 1 : 0) }}" class="obs-cell">
                                                    <i class="fas fa-comment-alt"></i> {{ $avance->observacion }}
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    @include('partials.footer')
</div>

{{-- Modal ver PDF presupuesto --}}
<div class="modal-backdrop" id="pdf-presupuesto-modal">
    <div class="pdf-modal-box">
        <div class="pdf-modal-header">
            <div class="pdf-modal-title">
                <i class="fas fa-file-pdf" style="color:#e03e3e;margin-right:0.4rem"></i>
                <span id="pdf-modal-clave"></span>
            </div>
            <button class="pdf-modal-close" id="pdf-modal-close"><i class="fas fa-times"></i></button>
        </div>
        <iframe id="pdf-iframe" class="pdf-iframe" src=""></iframe>
    </div>
</div>

{{-- Modal edición --}}
@if($puedeEditar)
<div class="modal-backdrop" id="edit-modal">
    <div class="modal-box">
        <div class="modal-title"><i class="fas fa-pen" style="color:var(--accent);margin-right:0.5rem"></i> Editar — <span id="modal-clave"></span></div>
        <form id="edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Fecha de inicio</label>
                <input type="date" name="fecha_inicio" id="modal-fecha" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Fecha de fin</label>
                <input type="date" name="fecha_fin" id="modal-fecha-fin" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Plazo (días)</label>
                <input type="number" name="plazo" id="modal-plazo" class="form-control" min="0">
            </div>
            <div class="form-group">
                <label class="form-label">Observación</label>
                <textarea name="observacion" id="modal-obs" class="form-control" rows="3" style="resize:vertical"></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn" id="modal-cancel">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Forms ocultos para cambio de estado --}}
<div id="estado-forms" style="display:none"></div>
@endif

<script>
    // Modal PDF presupuesto
    const pdfModal  = document.getElementById('pdf-presupuesto-modal');
    const pdfIframe = document.getElementById('pdf-iframe');

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-view-presupuesto');
        if (!btn) return;
        document.getElementById('pdf-modal-clave').textContent = btn.dataset.clave || '';
        pdfIframe.src = btn.dataset.pdfUrl;
        pdfModal.classList.add('open');
    });

    document.getElementById('pdf-modal-close').addEventListener('click', () => {
        pdfModal.classList.remove('open');
        pdfIframe.src = '';
    });
    pdfModal.addEventListener('click', e => {
        if (e.target === pdfModal) { pdfModal.classList.remove('open'); pdfIframe.src = ''; }
    });

    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(this.dataset.tab).classList.add('active');
            applyAllFilters();
        });
    });

    // ── Searchable Select ─────────────────────────────────────────────────────
    const ssValues = {};

    document.querySelectorAll('.ss-wrap').forEach(wrap => {
        const id      = wrap.dataset.ss;
        const input   = wrap.querySelector('.ss-input');
        const list    = wrap.querySelector('.ss-list');
        const options = Array.from(list.querySelectorAll('.ss-option'));
        ssValues[id]  = '';

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
            ssValues[id] = opt.dataset.value;
            input.value  = opt.dataset.value === '' ? '' : opt.textContent;
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
                // Si no hay selección válida, limpiar
                const sel = options.find(o => o.classList.contains('selected'));
                input.value = sel && sel.dataset.value !== '' ? sel.textContent : '';
                options.forEach(o => o.style.display = '');
                list.querySelector('.ss-empty')?.remove();
                hideList();
            }, 150);
        });
    });

    // ── Filters ──────────────────────────────────────────────────────────────
    const filterPanel  = document.getElementById('filter-panel');
    const filterToggle = document.getElementById('filter-toggle');
    const filterCount  = document.getElementById('filter-count');

    filterToggle.addEventListener('click', e => {
        e.stopPropagation();
        filterPanel.classList.toggle('open');
    });
    document.addEventListener('click', e => {
        if (!filterPanel.contains(e.target) && e.target !== filterToggle) {
            filterPanel.classList.remove('open');
        }
    });

    // ── Dual range sliders ──────────────────────────────────────────────────
    const rangeValues = {};

    document.querySelectorAll('.range-slider').forEach(slider => {
        const id       = slider.dataset.range;
        const minInput = slider.querySelector('.range-min');
        const maxInput = slider.querySelector('.range-max');
        const fill     = slider.querySelector('.range-fill');
        const valMin   = slider.parentElement.querySelector('.range-value-min');
        const valMax   = slider.parentElement.querySelector('.range-value-max');

        rangeValues[id] = { min: 0, max: 100 };

        function update() {
            let min = parseInt(minInput.value);
            let max = parseInt(maxInput.value);
            if (min > max) { [min, max] = [max, min]; }

            minInput.value = min;
            maxInput.value = max;
            rangeValues[id] = { min, max };

            fill.style.left  = min + '%';
            fill.style.right = (100 - max) + '%';
            valMin.textContent = min + '%';
            valMax.textContent = max + '%';

            applyAllFilters();
        }

        minInput.addEventListener('input', update);
        maxInput.addEventListener('input', update);
        update();
    });

    function getFilters() {
        return {
            obra:      ssValues['f-obra']     || '',
            mes:       ssValues['f-mes']      || '',
            anio:      ssValues['f-anio']     || '',
            mesFin:    ssValues['f-mes-fin']  || '',
            anioFin:   ssValues['f-anio-fin'] || '',
            tipo:      ssValues['f-tipo']     || '',
            facMin:    rangeValues['f-facturado']?.min ?? 0,
            facMax:    rangeValues['f-facturado']?.max ?? 100,
            cobMin:    rangeValues['f-cobrado']?.min ?? 0,
            cobMax:    rangeValues['f-cobrado']?.max ?? 100,
            search:    document.getElementById('global-search').value.toLowerCase(),
        };
    }

    function rowMatches(row, f) {
        const pctFac = Number(row.dataset.pctFac || 0);
        const pctCob = Number(row.dataset.pctCob || 0);
        return (!f.obra    || row.dataset.obra   == f.obra)
            && (!f.mes     || row.dataset.mes    == f.mes)
            && (!f.anio    || row.dataset.anio   == f.anio)
            && (!f.mesFin  || row.dataset.mesFin == f.mesFin)
            && (!f.anioFin || row.dataset.anioFin== f.anioFin)
            && (!f.tipo    || row.dataset.tipo   == f.tipo)
            && pctFac >= f.facMin && pctFac <= f.facMax
            && pctCob >= f.cobMin && pctCob <= f.cobMax
            && (!f.search  || row.textContent.toLowerCase().includes(f.search));
    }

    function applyAllFilters() {
        const f = getFilters();
        const rangoActivo = f.facMin > 0 || f.facMax < 100 || f.cobMin > 0 || f.cobMax < 100;
        const activeCount = [f.obra, f.mes, f.anio, f.mesFin, f.anioFin, f.tipo].filter(v => v !== '').length + (rangoActivo ? 1 : 0);
        filterCount.textContent = activeCount;
        filterCount.style.display = activeCount > 0 ? 'inline' : 'none';
        filterToggle.classList.toggle('btn-filter-active', activeCount > 0);

        // Actualizar badges de todas las pestañas
        document.querySelectorAll('.tab-btn').forEach(btn => {
            const tabPane = document.getElementById(btn.dataset.tab);
            if (!tabPane) return;
            const rows  = tabPane.querySelectorAll('tbody tr:not([data-obs-row])');
            const count = Array.from(rows).filter(r => rowMatches(r, f)).length;
            const badge = btn.querySelector('.tab-badge');
            if (badge) badge.textContent = count;
        });

        // Mostrar/ocultar filas solo en la pestaña activa
        const pane = document.querySelector('.tab-pane.active');
        if (!pane) return;

        pane.querySelectorAll('tbody tr:not([data-obs-row])').forEach(row => {
            const visible = rowMatches(row, f);
            row.style.display = visible ? '' : 'none';
            const next = row.nextElementSibling;
            if (next && next.hasAttribute('data-obs-row')) {
                next.style.display = visible ? '' : 'none';
            }
        });
    }

    document.getElementById('filter-apply').addEventListener('click', () => {
        filterPanel.classList.remove('open');
        applyAllFilters();
    });

    document.getElementById('filter-clear').addEventListener('click', () => {
        document.querySelectorAll('.ss-wrap').forEach(wrap => {
            const id = wrap.dataset.ss;
            ssValues[id] = '';
            wrap.querySelector('.ss-input').value = '';
            wrap.querySelectorAll('.ss-option').forEach((o, i) => {
                o.classList.toggle('selected', i === 0);
                o.style.display = '';
            });
            wrap.querySelector('.ss-empty')?.remove();
        });
        document.querySelectorAll('.range-slider').forEach(slider => {
            const id = slider.dataset.range;
            const minInput = slider.querySelector('.range-min');
            const maxInput = slider.querySelector('.range-max');
            minInput.value = 0;
            maxInput.value = 100;
            minInput.dispatchEvent(new Event('input'));
        });
        applyAllFilters();
    });

    document.getElementById('global-search').addEventListener('input', applyAllFilters);

    @if($puedeEditar)
    // Modal edición
    const modal     = document.getElementById('edit-modal');
    const editForm  = document.getElementById('edit-form');
    const baseUrl   = '{{ url("/situacion_avance") }}';

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            const id    = this.dataset.avanceId;
            document.getElementById('modal-clave').textContent    = this.dataset.clave;
            document.getElementById('modal-fecha').value          = this.dataset.fecha    || '';
            document.getElementById('modal-fecha-fin').value      = this.dataset.fechaFin || '';
            document.getElementById('modal-plazo').value          = this.dataset.plazo    || '';
            document.getElementById('modal-obs').value            = this.dataset.obs      || '';
            editForm.action = baseUrl + '/' + id;
            modal.classList.add('open');
        });
    });

    document.getElementById('modal-cancel').addEventListener('click', () => modal.classList.remove('open'));
    modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('open'); });

    // Dropdown estado
    document.querySelectorAll('.dropdown-toggle-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const menu = this.nextElementSibling;
            const isOpen = menu.classList.contains('open');
            document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
            if (!isOpen) {
                const rect = this.getBoundingClientRect();
                menu.style.top  = (rect.bottom + 4) + 'px';
                menu.style.left = (rect.right - menu.offsetWidth || rect.left) + 'px';
                menu.classList.add('open');
                // Ajustar left después de que sea visible
                requestAnimationFrame(() => {
                    menu.style.left = (rect.right - menu.offsetWidth) + 'px';
                });
            }
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu.open').forEach(m => m.classList.remove('open'));
    });

    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function () {
            const avanceId  = this.dataset.avanceId;
            const estadoId  = this.dataset.estadoId;
            const container = document.getElementById('estado-forms');

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = baseUrl + '/' + avanceId;
            form.innerHTML = `
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="estado_situacion_id" value="${estadoId}">
            `;
            container.appendChild(form);
            form.submit();
        });
    });
    @endif
</script>

</body>
</html>
