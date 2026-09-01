<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Fotos</title>
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
            --blue-s:   #e8f0fc;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── PAGE HEADER ── */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex; align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

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
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn:disabled:hover { background: var(--surface); border-color: var(--border); color: var(--text2); }

        /* ── DESCARGAR (menú + selección) ── */
        .btn-descargar-wrap { position: relative; }
        .btn-descargar-wrap.open .btn-descargar { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-descargar-menu {
            display: none;
            position: absolute; top: calc(100% + 6px); right: 0;
            background: #fff; border: 1.5px solid var(--border);
            border-radius: 0.65rem; overflow: hidden;
            box-shadow: 0 10px 26px rgba(0,0,0,0.12);
            z-index: 30; min-width: 220px;
        }
        .btn-descargar-wrap.open .btn-descargar-menu { display: block; }
        .btn-descargar-opcion {
            width: 100%; padding: 0.7rem 0.9rem;
            display: flex; align-items: center; gap: 0.55rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.84rem; font-weight: 600;
            color: var(--text2); background: none; border: none; cursor: pointer;
            text-align: left; transition: background 0.12s;
        }
        .btn-descargar-opcion:not(:last-child) { border-bottom: 1px solid var(--border); }
        .btn-descargar-opcion i { color: var(--accent); width: 16px; text-align: center; }
        .btn-descargar-opcion:hover:not(:disabled) { background: var(--surface2); }
        .btn-descargar-opcion:disabled { color: var(--muted); cursor: not-allowed; }
        .btn-descargar-opcion:disabled i { color: var(--muted); }

        /* ── FILTROS ── */
        .filtros-wrap {
            display: flex; gap: 0.75rem; flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }
        .search-wrap { position: relative; flex: 1; min-width: 220px; }
        .search-wrap i.fa-search {
            position: absolute; left: 0.95rem; top: 50%; transform: translateY(-50%);
            color: var(--muted); font-size: 0.85rem; pointer-events: none;
        }
        .search-input {
            width: 100%; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.86rem;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.65rem; padding: 0.65rem 2.25rem 0.65rem 2.5rem; color: var(--text);
            outline: none; transition: border-color 0.15s, box-shadow 0.15s;
        }
        .search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .search-clear {
            display: none;
            position: absolute; right: 0.6rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; color: var(--muted);
            font-size: 0.8rem; padding: 0.3rem; border-radius: 0.3rem; transition: color 0.14s;
        }
        .search-clear:hover { color: var(--text); }
        .search-clear.show { display: block; }

        .filtro-select {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.84rem; font-weight: 500;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.65rem; padding: 0 0.85rem; color: var(--text2);
            outline: none; height: 42px; min-width: 170px; cursor: pointer;
            transition: border-color 0.15s;
        }
        .filtro-select:focus { border-color: var(--accent); }

        /* ── AUTOCOMPLETE (filtros plano / usuario) ── */
        .autocomplete-wrap { position: relative; }
        .autocomplete-input { cursor: text; }
        .autocomplete-sugerencias {
            display: none;
            position: absolute; left: 0; right: 0; top: calc(100% + 4px);
            background: #fff; border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            max-height: 220px; overflow-y: auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            z-index: 20; min-width: 220px;
        }
        .autocomplete-wrap.open .autocomplete-sugerencias { display: block; }
        .autocomplete-sugerencia {
            padding: 0.55rem 0.85rem;
            font-size: 0.84rem; color: var(--text2);
            cursor: pointer;
            transition: background 0.1s;
        }
        .autocomplete-sugerencia:hover { background: var(--surface2); color: var(--text); }
        .autocomplete-sugerencia.hidden { display: none; }
        .autocomplete-sugerencia-todos { color: var(--muted); font-style: italic; }

        .btn-filtros-clear {
            height: 42px; padding: 0 0.9rem; border-radius: 0.65rem;
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600;
            border: 1.5px solid var(--border); background: var(--surface); color: var(--text2);
            cursor: pointer; transition: all 0.14s; white-space: nowrap;
        }
        .btn-filtros-clear:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        /* ── BARRA DE SELECCIÓN ── */
        .barra-seleccion {
            display: flex; align-items: center;
            background: var(--accent-s); border: 1.5px solid var(--border);
            border-radius: 0.65rem; padding: 0.55rem 0.9rem;
            margin-bottom: 1rem;
        }
        .barra-seleccion-check {
            display: inline-flex; align-items: center; gap: 0.5rem;
            font-size: 0.84rem; font-weight: 600; color: var(--text2);
            cursor: pointer; user-select: none;
        }
        .barra-seleccion-check input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; }

        /* ── GRID DE FOTOS ── */
        .foto-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 1rem;
        }
        .foto-card {
            position: relative;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.75rem;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.16s, box-shadow 0.16s, border-color 0.16s;
            animation: cardIn 0.2s ease both;
        }
        .foto-card:hover { transform: translateY(-3px); box-shadow: 0 8px 22px rgba(0,0,0,0.1); border-color: var(--border2); }
        @keyframes cardIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
        .foto-thumb-wrap { aspect-ratio: 1 / 1; background: var(--surface2); overflow: hidden; }
        .foto-thumb-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .foto-info { padding: 0.6rem 0.75rem 0.7rem; }
        .foto-plano { font-size: 0.78rem; font-weight: 700; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .foto-meta { display: flex; align-items: center; justify-content: space-between; gap: 0.4rem; margin-top: 0.3rem; }
        .foto-fecha { font-size: 0.7rem; color: var(--muted); }
        .foto-clasificacion {
            font-size: 0.65rem; font-weight: 700; color: var(--accent);
            background: var(--accent-s); border-radius: 999px;
            padding: 0.12rem 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;
        }

        /* ── SELECCIÓN PARA DESCARGA ── */
        .foto-check {
            display: none;
            position: absolute; top: 0.5rem; left: 0.5rem; z-index: 2;
        }
        .foto-grid.seleccionando .foto-check { display: flex; }
        .foto-check-circle {
            width: 22px; height: 22px; border-radius: 50%;
            background: rgba(255,255,255,0.9); border: 2px solid var(--border2);
            display: flex; align-items: center; justify-content: center;
            color: transparent; font-size: 0.68rem;
            transition: background 0.14s, border-color 0.14s, color 0.14s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }
        .foto-card.seleccionada .foto-check-circle { background: var(--accent); border-color: var(--accent); color: #fff; }
        .foto-card.seleccionada { outline: 2.5px solid var(--accent); outline-offset: -2.5px; }
        .foto-grid.seleccionando .foto-thumb-wrap { opacity: 0.96; }

        /* ── EMPTY STATE ── */
        .empty-state { padding: 3.5rem 1.5rem; text-align: center; }
        .empty-icon { width: 52px; height: 52px; border-radius: 0.75rem; background: var(--blue-s); color: var(--accent); display: inline-flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 1rem; }
        .empty-title { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 0.35rem; }
        .empty-sub { font-size: 0.82rem; color: var(--muted); }

        /* ── LIGHTBOX ── */
        .overlay-foto {
            display: none; position: fixed; inset: 0; z-index: 9999;
            background: rgba(15,17,20,0.92);
            align-items: center; justify-content: center;
        }
        .overlay-foto.abierto { display: flex; }
        .overlay-foto-contenido {
            position: relative; width: 100%; height: 100%;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 3.5rem 4.5rem 4rem;
        }
        .overlay-foto-panels {
            width: 100%; height: 100%;
            display: flex; align-items: stretch; justify-content: center;
            gap: 1.5rem; min-height: 0;
        }
        .overlay-foto-principal {
            flex: 1 1 auto; min-width: 0; height: 100%;
            display: flex; align-items: center; justify-content: center;
        }
        .overlay-foto-principal img { max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 0.4rem; }
        .overlay-foto-panels.sin-plano .overlay-foto-principal { flex: 1 1 100%; }

        /* ── PANEL DE PLANO (contexto) ── */
        .overlay-plano-panel {
            flex: 0 0 380px; max-width: 40%; height: 100%;
            display: flex; flex-direction: column; min-height: 0;
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.14);
            border-radius: 0.6rem; padding: 0.75rem;
        }
        .overlay-plano-panel.oculto { display: none; }
        .overlay-plano-titulo {
            display: flex; align-items: center; gap: 0.4rem;
            color: #dfe3e8; font-size: 0.78rem; font-weight: 700;
            margin-bottom: 0.6rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .overlay-plano-titulo i { color: var(--accent); }
        .overlay-plano-viewport {
            flex: 1; min-height: 0; overflow: hidden; border-radius: 0.45rem;
            background: #14171b; display: flex; align-items: center; justify-content: center;
            position: relative;
        }
        .overlay-plano-inner { position: relative; display: inline-block; transition: transform 0.15s ease-out; }
        .overlay-plano-inner canvas { display: block; }
        .overlay-plano-pin {
            display: none;
            position: absolute; width: 16px; height: 16px; margin: -8px 0 0 -8px;
            border-radius: 50%; background: #ff3b30; border: 2.5px solid #fff;
            box-shadow: 0 0 0 5px rgba(255,59,48,0.35), 0 1px 4px rgba(0,0,0,0.5);
            animation: pinPulso 1.6s ease-in-out infinite;
        }
        .overlay-plano-pin.visible { display: block; }
        @keyframes pinPulso {
            0%, 100% { box-shadow: 0 0 0 5px rgba(255,59,48,0.35), 0 1px 4px rgba(0,0,0,0.5); }
            50% { box-shadow: 0 0 0 9px rgba(255,59,48,0.18), 0 1px 4px rgba(0,0,0,0.5); }
        }
        .overlay-plano-cargando {
            position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
            color: #9aa4b2; font-size: 1.3rem;
        }
        .overlay-plano-cargando.oculto { display: none; }
        .overlay-plano-zoom { display: flex; align-items: center; gap: 0.6rem; margin-top: 0.7rem; }
        .overlay-plano-zoom button {
            width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
            background: #333; color: #fff; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center; font-size: 0.72rem;
            transition: background 0.14s;
        }
        .overlay-plano-zoom button:hover { background: #555; }
        .overlay-plano-zoom input[type="range"] { flex: 1; accent-color: var(--accent); }
        .overlay-foto-cerrar {
            position: absolute; top: 1rem; right: 1.25rem;
            width: 38px; height: 38px; border-radius: 50%;
            background: #333; color: #fff; border: none; cursor: pointer;
            font-size: 1.4rem; line-height: 1; display: flex; align-items: center; justify-content: center;
            transition: background 0.14s;
        }
        .overlay-foto-cerrar:hover { background: #555; }
        .overlay-foto-descargar {
            position: absolute; top: 1rem; right: 4.25rem;
            width: 38px; height: 38px; border-radius: 50%;
            background: #333; color: #fff; border: none; cursor: pointer;
            font-size: 1rem; line-height: 1; display: flex; align-items: center; justify-content: center;
            transition: background 0.14s;
        }
        .overlay-foto-descargar:hover { background: #555; }
        .overlay-foto-descargar:disabled { opacity: 0.5; cursor: not-allowed; }
        .overlay-foto-nav {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 44px; height: 44px; border-radius: 50%;
            background: rgba(0,0,0,0.55); color: #fff; border: none; cursor: pointer;
            font-size: 1.8rem; line-height: 1; display: flex; align-items: center; justify-content: center;
            transition: background 0.14s;
        }
        .overlay-foto-nav:hover { background: rgba(0,0,0,0.75); }
        .overlay-foto-prev { left: 1rem; }
        .overlay-foto-next { right: 1rem; }
        .overlay-foto-pie {
            position: absolute; bottom: 1rem; left: 0; right: 0;
            display: flex; align-items: center; justify-content: center; gap: 0.75rem;
            color: #ddd; font-size: 0.8rem; text-align: center; padding: 0 1rem;
        }
        .overlay-foto-info strong { color: #fff; }

        /* ── MOBILE ── */
        @media (max-width: 640px) {
            .ph { padding: 1rem 0 0.75rem; gap: 0.75rem; margin-bottom: 1rem; }
            .ph-title { font-size: 1.3rem; }
            .ph-right { width: 100%; }
            #btn-toggle-plano { width: 100%; justify-content: center; }
            .btn-descargar-wrap { width: 100%; }
            .btn-descargar { width: 100%; justify-content: center; }
            .btn-descargar-menu { left: 0; right: 0; min-width: 0; }
            .filtros-wrap { flex-direction: column; }
            .filtro-select { width: 100%; }
            .autocomplete-wrap { width: 100%; }
            .overlay-foto-contenido { padding: 3rem 1.25rem 3.5rem; }
            .overlay-foto-descargar { top: 4.5rem; right: 1.25rem; }
            .overlay-foto-nav { width: 38px; height: 38px; font-size: 1.5rem; }
            .overlay-foto-panels { flex-direction: column; }
            .overlay-plano-panel { flex: 0 0 42%; max-width: 100%; width: 100%; }
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
                            <i class="fas fa-home"></i>
                            <a href="{{ route('home') }}">Inicio</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('trabajo_campo.index') }}">Trabajo de Campo</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('obras_tc.index', $obraTc->id) }}">{{ $obraTc->descripcion ?? '-' }}</a>
                            <i class="fas fa-chevron-right"></i>
                            Galería de Fotos
                        </div>
                        <h1 class="ph-title"><em>Galería de Fotos</em></h1>
                        <p class="ph-sub">{{ $fotos->count() }} {{ $fotos->count() === 1 ? 'foto cargada' : 'fotos cargadas' }}</p>
                    </div>
                    <div class="ph-right">
                        @if(!$fotos->isEmpty())
                        <button type="button" class="btn" id="btn-toggle-plano" title="Mostrar u ocultar el plano al ver una foto">
                            <i class="fas fa-map-location-dot"></i> <span id="texto-toggle-plano">Ver sin plano</span>
                        </button>
                        <div class="btn-descargar-wrap" id="wrap-descargar">
                            <button type="button" class="btn btn-descargar" id="btn-descargar">
                                <i class="fas fa-download"></i> Descargar <i class="fas fa-chevron-down" style="font-size:0.6rem;"></i>
                            </button>
                            <div class="btn-descargar-menu" id="menu-descargar">
                                <button type="button" class="btn-descargar-opcion" id="btn-descargar-seleccion" disabled>
                                    <i class="fas fa-check-square"></i>
                                    <span id="texto-descargar-seleccion">Descargar selección</span>
                                </button>
                                <button type="button" class="btn-descargar-opcion" id="btn-descargar-todo">
                                    <i class="fas fa-images"></i> Descargar todo
                                </button>
                            </div>
                        </div>
                        @endif
                        <a href="{{ route('obras_tc.index', $obraTc->id) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @if(!$fotos->isEmpty())
                <div class="filtros-wrap">
                    <div class="search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" id="input-buscar-foto" class="search-input" placeholder="Buscar por plano...">
                        <button type="button" class="search-clear" id="btn-buscar-clear" title="Limpiar"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="autocomplete-wrap" id="wrap-filtro-plano">
                        <input type="text" id="filtro-plano" class="filtro-select autocomplete-input" placeholder="Todos los planos" autocomplete="off">
                        <div class="autocomplete-sugerencias" id="sugerencias-plano">
                            <div class="autocomplete-sugerencia autocomplete-sugerencia-todos" data-valor="">Todos los planos</div>
                            @foreach($planosConFotos as $planoOpcion)
                            <div class="autocomplete-sugerencia" data-valor="{{ $planoOpcion->descripcion }}">{{ $planoOpcion->descripcion }}</div>
                            @endforeach
                        </div>
                    </div>
                    @if($clasificaciones->isNotEmpty())
                    <select id="filtro-clasificacion" class="filtro-select">
                        <option value="">Toda clasificación</option>
                        @foreach($clasificaciones as $clasificacionOpcion)
                        <option value="{{ $clasificacionOpcion }}">{{ ucfirst(str_replace('_', ' ', $clasificacionOpcion)) }}</option>
                        @endforeach
                    </select>
                    @endif
                    @if($usuariosConFotos->isNotEmpty())
                    <div class="autocomplete-wrap" id="wrap-filtro-usuario">
                        <input type="text" id="filtro-usuario" class="filtro-select autocomplete-input" placeholder="Todos los usuarios" autocomplete="off">
                        <div class="autocomplete-sugerencias" id="sugerencias-usuario">
                            <div class="autocomplete-sugerencia autocomplete-sugerencia-todos" data-valor="">Todos los usuarios</div>
                            @foreach($usuariosConFotos as $usuarioOpcion)
                            <div class="autocomplete-sugerencia" data-valor="{{ $usuarioOpcion->nombre_completo ?: $usuarioOpcion->nombre }}">{{ $usuarioOpcion->nombre_completo ?: $usuarioOpcion->nombre }}</div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <select id="filtro-dia" class="filtro-select">
                        <option value="">Día</option>
                        @foreach($diasConFotos as $diaOpcion)
                        <option value="{{ $diaOpcion }}">{{ (int) $diaOpcion }}</option>
                        @endforeach
                    </select>
                    <select id="filtro-mes" class="filtro-select">
                        <option value="">Mes</option>
                        @foreach($mesesConFotos as $numMes => $nombreMes)
                        <option value="{{ $numMes }}">{{ $nombreMes }}</option>
                        @endforeach
                    </select>
                    <select id="filtro-anio" class="filtro-select">
                        <option value="">Año</option>
                        @foreach($aniosConFotos as $anioOpcion)
                        <option value="{{ $anioOpcion }}">{{ $anioOpcion }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn-filtros-clear" id="btn-filtros-clear" title="Limpiar filtros">
                        <i class="fas fa-filter-circle-xmark"></i> Limpiar
                    </button>
                </div>
                @endif

                @if($fotos->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-images"></i></div>
                    <div class="empty-title">Sin fotos</div>
                    <div class="empty-sub">Todavía no se subió ninguna foto en los planos de esta obra.</div>
                </div>
                @else
                <div class="barra-seleccion" id="barra-seleccion" style="display:none">
                    <label class="barra-seleccion-check">
                        <input type="checkbox" id="check-seleccionar-todo">
                        <span id="texto-seleccionar-todo">Seleccionar todo lo visible</span>
                    </label>
                </div>
                <div class="foto-grid" id="foto-grid">
                    @foreach($fotos as $index => $foto)
                    <div class="foto-card"
                         data-id="{{ $foto->id }}"
                         data-index="{{ $index }}"
                         data-plano-id="{{ $foto->plano_tc_id }}"
                         data-plano-nombre="{{ Str::lower($foto->plano->descripcion ?? '') }}"
                         data-clasificacion="{{ $foto->clasificacion }}"
                         data-usuario-nombre="{{ Str::lower($foto->usuario ? ($foto->usuario->nombre_completo ?: $foto->usuario->nombre) : '') }}"
                         data-dia="{{ $foto->created_at?->format('d') }}"
                         data-mes="{{ $foto->created_at?->format('m') }}"
                         data-anio="{{ $foto->created_at?->format('Y') }}">
                        <div class="foto-check"><span class="foto-check-circle"><i class="fas fa-check"></i></span></div>
                        <div class="foto-thumb-wrap">
                            <img src="{{ $foto->archivo }}" alt="Foto" loading="lazy">
                        </div>
                        <div class="foto-info">
                            <div class="foto-plano">{{ $foto->plano->descripcion ?? 'Sin plano' }}</div>
                            <div class="foto-meta">
                                <span class="foto-fecha">{{ $foto->created_at?->format('d/m/Y') }}</span>
                                @if($foto->clasificacion)
                                <span class="foto-clasificacion" title="{{ $foto->clasificacion }}">{{ ucfirst(str_replace('_', ' ', $foto->clasificacion)) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="empty-state" id="empty-busqueda" style="display:none">
                    <div class="empty-icon"><i class="fas fa-search"></i></div>
                    <div class="empty-title">Sin resultados</div>
                    <div class="empty-sub">No se encontraron fotos que coincidan con el filtro.</div>
                </div>
                @endif

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- ══════════════════════════════════════════════════════
     LIGHTBOX
══════════════════════════════════════════════════════ --}}
<div class="overlay-foto" id="overlay-foto">
    <div class="overlay-foto-contenido">
        <button type="button" class="overlay-foto-cerrar" id="overlay-foto-cerrar">&times;</button>
        <button type="button" class="overlay-foto-descargar" id="overlay-foto-descargar" title="Descargar imagen"><i class="fas fa-download"></i></button>
        <button type="button" class="overlay-foto-nav overlay-foto-prev" id="overlay-foto-prev">&lsaquo;</button>
        <div class="overlay-foto-panels" id="overlay-foto-panels">
            <div class="overlay-foto-principal">
                <img id="overlay-foto-img" src="" alt="Fotografía">
            </div>
            <div class="overlay-plano-panel" id="overlay-plano-panel">
                <div class="overlay-plano-titulo">
                    <i class="fas fa-map-location-dot"></i> <span id="overlay-plano-nombre">Plano</span>
                </div>
                <div class="overlay-plano-viewport">
                    <div class="overlay-plano-inner" id="overlay-plano-inner">
                        <canvas id="overlay-plano-canvas"></canvas>
                        <div class="overlay-plano-pin" id="overlay-plano-pin"></div>
                    </div>
                    <div class="overlay-plano-cargando" id="overlay-plano-cargando"><i class="fas fa-spinner fa-spin"></i></div>
                </div>
                <div class="overlay-plano-zoom">
                    <button type="button" id="overlay-plano-zoom-out" title="Alejar"><i class="fas fa-minus"></i></button>
                    <input type="range" id="overlay-plano-zoom-range" min="0.5" max="10" step="0.1" value="1">
                    <button type="button" id="overlay-plano-zoom-in" title="Acercar"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </div>
        <button type="button" class="overlay-foto-nav overlay-foto-next" id="overlay-foto-next">&rsaquo;</button>
        <div class="overlay-foto-pie">
            <span class="overlay-foto-info" id="overlay-foto-info"></span>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const CSRF_TOKEN = @json(csrf_token());
    const urlDescargarFotos = @json(route('galeria_tc.descargar', $obraTc->id));

    const fotos = [
        @foreach($fotos as $foto)
        {
            src: @js($foto->archivo),
            plano: @js($foto->plano->descripcion ?? 'Sin plano'),
            planoArchivo: @js($foto->plano ? Storage::url('planos/' . $foto->plano->archivo) : null),
            planoRotacion: @js((int) ($foto->plano->rotacion ?? 0)),
            posX: @js($foto->pos_x !== null ? (float) $foto->pos_x : null),
            posY: @js($foto->pos_y !== null ? (float) $foto->pos_y : null),
            clasificacion: @js($foto->clasificacion ? ucfirst(str_replace('_', ' ', $foto->clasificacion)) : null),
            fecha: @js($foto->created_at?->format('d/m/Y H:i')),
            usuario: @js($foto->usuario ? ($foto->usuario->nombre_completo ?: $foto->usuario->nombre) : null),
        },
        @endforeach
    ];

    let indicesVisibles = fotos.map((_, i) => i);
    let indiceActual = 0;

    /* ─── Preferencia: ver foto con/sin plano ──────────────── */
    const LS_KEY_MOSTRAR_PLANO = 'galeria_tc_mostrar_plano';
    let mostrarPlanoPreferencia = localStorage.getItem(LS_KEY_MOSTRAR_PLANO) !== '0';

    const btnTogglePlano = document.getElementById('btn-toggle-plano');
    const textoTogglePlano = document.getElementById('texto-toggle-plano');
    const iconoTogglePlano = btnTogglePlano?.querySelector('i');

    function actualizarBotonTogglePlano() {
        if (!textoTogglePlano) return;
        textoTogglePlano.textContent = mostrarPlanoPreferencia ? 'Ver sin plano' : 'Ver con plano';
        if (iconoTogglePlano) iconoTogglePlano.className = mostrarPlanoPreferencia ? 'fas fa-map-location-dot' : 'fas fa-image';
    }
    actualizarBotonTogglePlano();

    btnTogglePlano?.addEventListener('click', function () {
        mostrarPlanoPreferencia = !mostrarPlanoPreferencia;
        localStorage.setItem(LS_KEY_MOSTRAR_PLANO, mostrarPlanoPreferencia ? '1' : '0');
        actualizarBotonTogglePlano();
        if (document.getElementById('overlay-foto').classList.contains('abierto')) mostrarFotoActual();
    });

    function abrirLightbox(index) {
        indiceActual = indicesVisibles.indexOf(index);
        if (indiceActual === -1) indiceActual = 0;
        mostrarFotoActual();
        document.getElementById('overlay-foto').classList.add('abierto');
    }

    function mostrarFotoActual() {
        const idx = indicesVisibles[indiceActual];
        const foto = fotos[idx];
        if (!foto) return;

        document.getElementById('overlay-foto-img').src = foto.src;

        const partes = [];
        partes.push(`<strong>${foto.plano}</strong>`);
        if (foto.clasificacion) partes.push(foto.clasificacion);
        if (foto.usuario) partes.push('Subida por ' + foto.usuario);
        if (foto.fecha) partes.push(foto.fecha);
        partes.push(`(${indiceActual + 1}/${indicesVisibles.length})`);

        document.getElementById('overlay-foto-info').innerHTML = partes.join(' &middot; ');

        mostrarPlanoContexto(foto);
    }

    /* ─── Panel de plano (contexto + zoom) ─────────────────── */
    const pdfCachePlanos = new Map();
    let tokenRenderPlano = 0;
    let tareaRenderPlano = null;
    let temporizadorZoomPlano = null;

    /* Tope de resolución interna del canvas (px). Con re-render dinámico
       según el zoom real, este tope solo entra en juego en el extremo
       de la barrita (10x) o en pantallas de dpr muy alto. */
    const DIMENSION_MAXIMA_PLANO = 4096;

    const planoActual = {
        pagina: null,
        viewportBase: null,
        rotacion: 0,
        escalaAjuste: 0,
        escalaRenderizada: 0,
    };

    function cargarPdfPlano(url) {
        if (!pdfCachePlanos.has(url)) {
            pdfCachePlanos.set(url, pdfjsLib.getDocument(url).promise);
        }
        return pdfCachePlanos.get(url);
    }

    function clamp01(valor) {
        return Math.min(1, Math.max(0, valor));
    }

    function escalaRenderNecesaria(factorZoom) {
        const dpr = window.devicePixelRatio || 1;
        let escalaRender = planoActual.escalaAjuste * factorZoom * dpr;
        const anchoEstimado = planoActual.viewportBase.width * escalaRender;
        const altoEstimado = planoActual.viewportBase.height * escalaRender;
        const excesoDimension = Math.max(
            anchoEstimado / DIMENSION_MAXIMA_PLANO,
            altoEstimado / DIMENSION_MAXIMA_PLANO,
            1
        );
        return escalaRender / excesoDimension;
    }

    /* Renderiza el PDF a la escala pedida. Cancela cualquier render en
       curso (de un zoom anterior o de la foto anterior) antes de pintar
       el canvas, para que dos renders no terminen pisándose. */
    async function renderizarPlanoAEscala(escalaRender) {
        const canvas = document.getElementById('overlay-plano-canvas');
        const viewportRender = planoActual.pagina.getViewport({ scale: escalaRender, rotation: planoActual.rotacion });

        tareaRenderPlano?.cancel();
        canvas.width = viewportRender.width;
        canvas.height = viewportRender.height;

        const ctx = canvas.getContext('2d');
        const tarea = planoActual.pagina.render({ canvasContext: ctx, viewport: viewportRender });
        tareaRenderPlano = tarea;

        try {
            await tarea.promise;
            planoActual.escalaRenderizada = escalaRender;
        } catch (err) {
            if (err?.name !== 'RenderingCancelledException') throw err;
        }
    }

    /* Vuelve a renderizar el plano a mayor resolución si el zoom actual
       lo requiere, así se ve nítido sin importar cuánto se acerque. Si
       ya está renderizado a una resolución igual o mayor (por ej. se
       alejó el zoom), no hace nada. */
    function solicitarRenderZoom(factorZoom) {
        if (!planoActual.pagina) return;
        const escalaRender = escalaRenderNecesaria(factorZoom);
        if (escalaRender <= planoActual.escalaRenderizada) return;
        renderizarPlanoAEscala(escalaRender);
    }

    function fijarZoomPlano(valor) {
        const range = document.getElementById('overlay-plano-zoom-range');
        const inner = document.getElementById('overlay-plano-inner');
        const pin = document.getElementById('overlay-plano-pin');
        range.value = valor;
        inner.style.transform = `scale(${valor})`;
        pin.style.transform = `scale(${1 / valor})`;

        /* El zoom visual es instantáneo (transform de CSS); la
           re-renderización a mayor resolución se posterga un toque para
           no recalcular en cada tick mientras se arrastra la barrita. */
        clearTimeout(temporizadorZoomPlano);
        temporizadorZoomPlano = setTimeout(() => solicitarRenderZoom(parseFloat(valor)), 180);
    }

    async function mostrarPlanoContexto(foto) {
        const panel = document.getElementById('overlay-plano-panel');
        const panels = document.getElementById('overlay-foto-panels');
        const cargando = document.getElementById('overlay-plano-cargando');
        const nombreEl = document.getElementById('overlay-plano-nombre');
        const canvas = document.getElementById('overlay-plano-canvas');
        const inner = document.getElementById('overlay-plano-inner');
        const pin = document.getElementById('overlay-plano-pin');

        clearTimeout(temporizadorZoomPlano);
        tareaRenderPlano?.cancel();
        fijarZoomPlano(1);
        inner.style.transformOrigin = '50% 50%';
        pin.classList.remove('visible');
        planoActual.pagina = null;
        planoActual.escalaRenderizada = 0;

        if (!foto.planoArchivo || !mostrarPlanoPreferencia) {
            panel.classList.add('oculto');
            panels.classList.add('sin-plano');
            cargando.classList.add('oculto');
            document.getElementById('overlay-foto-descargar').disabled = false;
            return;
        }

        panel.classList.remove('oculto');
        panels.classList.remove('sin-plano');
        nombreEl.textContent = foto.plano || 'Plano';
        cargando.classList.remove('oculto');
        /* Mientras el plano está renderizando, el canvas puede estar en
           blanco o a medio dibujar: se bloquea la descarga para no
           generar un compuesto incompleto. */
        document.getElementById('overlay-foto-descargar').disabled = true;

        const miToken = ++tokenRenderPlano;

        try {
            const pdf = await cargarPdfPlano(foto.planoArchivo);
            if (miToken !== tokenRenderPlano) return;

            const pagina = await pdf.getPage(1);
            if (miToken !== tokenRenderPlano) return;

            const viewportEl = document.querySelector('.overlay-plano-viewport');
            const anchoDisponible = viewportEl.clientWidth || 320;
            const altoDisponible = viewportEl.clientHeight || 320;

            const viewportBase = pagina.getViewport({ scale: 1, rotation: foto.planoRotacion || 0 });
            const dpr = window.devicePixelRatio || 1;
            const escalaAjuste = Math.min(
                (anchoDisponible * dpr) / viewportBase.width,
                (altoDisponible * dpr) / viewportBase.height
            );

            /* El tamaño en pantalla (CSS) queda fijo al "ajustado"; el
               zoom con la barrita escala ese elemento vía transform.
               La nitidez a cada nivel de zoom la da la resolución
               interna del canvas, que se recalcula en solicitarRenderZoom(). */
            canvas.style.width = (viewportBase.width * escalaAjuste / dpr) + 'px';
            canvas.style.height = (viewportBase.height * escalaAjuste / dpr) + 'px';

            planoActual.pagina = pagina;
            planoActual.viewportBase = viewportBase;
            planoActual.rotacion = foto.planoRotacion || 0;
            planoActual.escalaAjuste = escalaAjuste;
            planoActual.escalaRenderizada = 0;

            /* Primer renderizado con margen de nitidez (1.5x el ajuste)
               para que se vea bien apenas se abre, sin pagar de entrada
               el costo de renderizar como si ya estuviera al máximo
               zoom (10x) — eso se hace bajo demanda al mover la
               barrita, así en mobile no se gasta de más si el usuario
               nunca hace zoom. */
            await renderizarPlanoAEscala(escalaRenderNecesaria(1.5));
            if (miToken !== tokenRenderPlano) return;

            if (foto.posX !== null && foto.posY !== null) {
                const fracX = clamp01(foto.posX / viewportBase.width);
                const fracY = clamp01(foto.posY / viewportBase.height);
                pin.style.left = (fracX * 100) + '%';
                pin.style.top = (fracY * 100) + '%';
                pin.classList.add('visible');
                inner.style.transformOrigin = (fracX * 100) + '% ' + (fracY * 100) + '%';
            }

            cargando.classList.add('oculto');
            document.getElementById('overlay-foto-descargar').disabled = false;
        } catch (err) {
            if (miToken === tokenRenderPlano) {
                cargando.classList.add('oculto');
                document.getElementById('overlay-foto-descargar').disabled = false;
            }
        }
    }

    document.getElementById('overlay-plano-zoom-range').addEventListener('input', function () {
        fijarZoomPlano(parseFloat(this.value));
    });
    document.getElementById('overlay-plano-zoom-in').addEventListener('click', function () {
        const range = document.getElementById('overlay-plano-zoom-range');
        fijarZoomPlano(Math.min(parseFloat(range.max), parseFloat(range.value) + 0.5));
    });
    document.getElementById('overlay-plano-zoom-out').addEventListener('click', function () {
        const range = document.getElementById('overlay-plano-zoom-range');
        fijarZoomPlano(Math.max(parseFloat(range.min), parseFloat(range.value) - 0.5));
    });

    /* ─── Descarga de la foto (con o sin plano) ────────────── */
    function sanitizarNombreArchivo(texto) {
        return (texto || 'foto').toString().normalize('NFD').replace(/[̀-ͯ]/g, '')
            .replace(/[^a-zA-Z0-9]+/g, '_').replace(/^_+|_+$/g, '') || 'foto';
    }

    function cargarImagen(url) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = () => reject(new Error('No se pudo cargar la imagen'));
            img.src = url;
        });
    }

    /* Dibuja `src` (imagen o canvas) ajustándolo sin deformar dentro
       del rectángulo destino (equivalente a "object-fit: contain") y
       devuelve dónde quedó dibujado, para poder ubicar el pin o el
       rectángulo indicador sobre esa misma zona. */
    function dibujarAjustado(ctx, src, sx, sy, sw, sh, dx, dy, dw, dh) {
        const escala = Math.min(dw / sw, dh / sh);
        const drawW = sw * escala;
        const drawH = sh * escala;
        const offsetX = dx + (dw - drawW) / 2;
        const offsetY = dy + (dh - drawH) / 2;
        ctx.drawImage(src, sx, sy, sw, sh, offsetX, offsetY, drawW, drawH);
        return { x: offsetX, y: offsetY, w: drawW, h: drawH };
    }

    /* Contorno negro pegado al borde real de la imagen dibujada (no al
       cuadro completo), ya que con "contain" puede quedar espacio
       transparente alrededor si la proporción no coincide. */
    function dibujarContorno(ctx, info, grosor = 4) {
        ctx.strokeStyle = '#000';
        ctx.lineWidth = grosor;
        ctx.strokeRect(info.x + grosor / 2, info.y + grosor / 2, info.w - grosor, info.h - grosor);
    }

    function dibujarPin(ctx, x, y, radio) {
        ctx.beginPath();
        ctx.arc(x, y, radio, 0, Math.PI * 2);
        ctx.fillStyle = '#ff3b30';
        ctx.fill();
        ctx.lineWidth = 2;
        ctx.strokeStyle = '#fff';
        ctx.stroke();
    }

    /* Calcula, en píxeles del canvas del plano (resolución interna,
       no CSS), qué recorte es el que se ve actualmente dentro del
       viewport según el zoom aplicado con la barrita — es la misma
       transformación que hace el CSS (scale con transform-origin en
       el punto de referencia), resuelta de forma inversa. */
    function calcularRectanguloVisiblePlano() {
        const viewportEl = document.querySelector('.overlay-plano-viewport');
        const inner = document.getElementById('overlay-plano-inner');
        const canvas = document.getElementById('overlay-plano-canvas');
        const zoomValor = parseFloat(document.getElementById('overlay-plano-zoom-range').value) || 1;

        const viewportWidth = viewportEl.clientWidth;
        const viewportHeight = viewportEl.clientHeight;
        const boxWidth = inner.clientWidth;
        const boxHeight = inner.clientHeight;

        const origen = (inner.style.transformOrigin || '50% 50%').split(' ').map(v => parseFloat(v));
        const ox = (isNaN(origen[0]) ? 50 : origen[0]) / 100 * boxWidth;
        const oy = (isNaN(origen[1]) ? 50 : origen[1]) / 100 * boxHeight;

        const boxLeft = (viewportWidth - boxWidth) / 2;
        const boxTop = (viewportHeight - boxHeight) / 2;

        const aBoxX = px => ox + (px - boxLeft - ox) / zoomValor;
        const aBoxY = py => oy + (py - boxTop - oy) / zoomValor;

        const pxMin = Math.max(0, Math.min(boxWidth, aBoxX(0)));
        const pxMax = Math.max(0, Math.min(boxWidth, aBoxX(viewportWidth)));
        const pyMin = Math.max(0, Math.min(boxHeight, aBoxY(0)));
        const pyMax = Math.max(0, Math.min(boxHeight, aBoxY(viewportHeight)));

        const ratioX = canvas.width / boxWidth;
        const ratioY = canvas.height / boxHeight;

        return {
            sx: pxMin * ratioX,
            sy: pyMin * ratioY,
            sw: Math.max(1, (pxMax - pxMin) * ratioX),
            sh: Math.max(1, (pyMax - pyMin) * ratioY),
        };
    }

    async function descargarFotoActual() {
        const btnDescargarFoto = document.getElementById('overlay-foto-descargar');
        if (btnDescargarFoto.disabled) return;

        const foto = fotos[indicesVisibles[indiceActual]];
        if (!foto) return;

        const hayPlano = !!foto.planoArchivo && mostrarPlanoPreferencia && !!planoActual.pagina;
        const iconoOriginal = btnDescargarFoto.innerHTML;
        btnDescargarFoto.disabled = true;
        btnDescargarFoto.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            let blob;
            let nombreArchivo;

            if (!hayPlano) {
                const respuesta = await fetch(foto.src);
                blob = await respuesta.blob();
                nombreArchivo = `${sanitizarNombreArchivo(foto.plano)}_${sanitizarNombreArchivo(foto.fecha)}.jpg`;
            } else {
                const imgFoto = await cargarImagen(foto.src);
                const canvasPlano = document.getElementById('overlay-plano-canvas');
                const rectVisible = calcularRectanguloVisiblePlano();

                /* Fila principal: la foto y el plano con el zoom actual,
                   pegados uno al lado del otro y a la misma altura (cada
                   uno ocupa el ancho que le corresponda según su propia
                   proporción, sin espacio desperdiciado). Debajo, en un
                   tamaño chico, el plano general completo con el punto
                   de referencia y el rectángulo de la zona ampliada. */
                const ALTO_PRINCIPAL = 1200;
                const ALTO_PLANO_GENERAL = 380;
                const SEPARACION_FILAS = 20;

                const ratioFoto = imgFoto.naturalWidth / imgFoto.naturalHeight;
                const ratioZoom = rectVisible.sw / rectVisible.sh;
                const anchoFoto = ALTO_PRINCIPAL * ratioFoto;
                const anchoZoom = ALTO_PRINCIPAL * ratioZoom;
                const anchoTotal = anchoFoto + anchoZoom;

                const canvasFinal = document.createElement('canvas');
                canvasFinal.width = Math.round(anchoTotal);
                canvasFinal.height = Math.round(ALTO_PRINCIPAL + SEPARACION_FILAS + ALTO_PLANO_GENERAL);
                const ctx = canvasFinal.getContext('2d');
                /* Sin relleno de fondo: el canvas queda transparente y el
                   PNG se exporta sin fondo. Cada imagen lleva su propio
                   contorno negro en vez de líneas separadoras. */

                const infoFoto = dibujarAjustado(ctx, imgFoto, 0, 0, imgFoto.naturalWidth, imgFoto.naturalHeight, 0, 0, anchoFoto, ALTO_PRINCIPAL);
                dibujarContorno(ctx, infoFoto);

                const infoZoom = dibujarAjustado(ctx, canvasPlano, rectVisible.sx, rectVisible.sy, rectVisible.sw, rectVisible.sh, anchoFoto, 0, anchoZoom, ALTO_PRINCIPAL);
                dibujarContorno(ctx, infoZoom);

                const infoCompleto = dibujarAjustado(ctx, canvasPlano, 0, 0, canvasPlano.width, canvasPlano.height, 0, ALTO_PRINCIPAL + SEPARACION_FILAS, anchoTotal, ALTO_PLANO_GENERAL);
                dibujarContorno(ctx, infoCompleto);

                if (foto.posX !== null && foto.posY !== null && planoActual.viewportBase) {
                    const fracX = clamp01(foto.posX / planoActual.viewportBase.width);
                    const fracY = clamp01(foto.posY / planoActual.viewportBase.height);
                    const pinCanvasX = fracX * canvasPlano.width;
                    const pinCanvasY = fracY * canvasPlano.height;

                    if (pinCanvasX >= rectVisible.sx && pinCanvasX <= rectVisible.sx + rectVisible.sw &&
                        pinCanvasY >= rectVisible.sy && pinCanvasY <= rectVisible.sy + rectVisible.sh) {
                        const px = infoZoom.x + ((pinCanvasX - rectVisible.sx) / rectVisible.sw) * infoZoom.w;
                        const py = infoZoom.y + ((pinCanvasY - rectVisible.sy) / rectVisible.sh) * infoZoom.h;
                        dibujarPin(ctx, px, py, 7);
                    }

                    dibujarPin(ctx, infoCompleto.x + fracX * infoCompleto.w, infoCompleto.y + fracY * infoCompleto.h, 5);
                }

                const rx = infoCompleto.x + (rectVisible.sx / canvasPlano.width) * infoCompleto.w;
                const ry = infoCompleto.y + (rectVisible.sy / canvasPlano.height) * infoCompleto.h;
                const rw = (rectVisible.sw / canvasPlano.width) * infoCompleto.w;
                const rh = (rectVisible.sh / canvasPlano.height) * infoCompleto.h;
                ctx.strokeStyle = '#ff3b30';
                ctx.lineWidth = 3;
                ctx.strokeRect(rx, ry, Math.max(rw, 3), Math.max(rh, 3));

                blob = await new Promise(resolve => canvasFinal.toBlob(resolve, 'image/png'));
                nombreArchivo = `${sanitizarNombreArchivo(foto.plano)}_con_plano_${sanitizarNombreArchivo(foto.fecha)}.png`;
            }

            const url = URL.createObjectURL(blob);
            const enlace = document.createElement('a');
            enlace.href = url;
            enlace.download = nombreArchivo;
            document.body.appendChild(enlace);
            enlace.click();
            enlace.remove();
            URL.revokeObjectURL(url);
        } catch (err) {
            alert('No se pudo descargar la imagen. Probá de nuevo.');
        } finally {
            btnDescargarFoto.disabled = false;
            btnDescargarFoto.innerHTML = iconoOriginal;
        }
    }

    document.getElementById('overlay-foto-descargar').addEventListener('click', function (e) {
        e.stopPropagation();
        descargarFotoActual();
    });

    function cerrarLightbox() {
        document.getElementById('overlay-foto').classList.remove('abierto');
    }

    document.getElementById('overlay-foto-cerrar').addEventListener('click', cerrarLightbox);
    document.getElementById('overlay-foto').addEventListener('click', function (e) {
        if (e.target === this) cerrarLightbox();
    });
    document.getElementById('overlay-foto-prev').addEventListener('click', function () {
        indiceActual = (indiceActual - 1 + indicesVisibles.length) % indicesVisibles.length;
        mostrarFotoActual();
    });
    document.getElementById('overlay-foto-next').addEventListener('click', function () {
        indiceActual = (indiceActual + 1) % indicesVisibles.length;
        mostrarFotoActual();
    });
    document.addEventListener('keydown', function (e) {
        if (!document.getElementById('overlay-foto').classList.contains('abierto')) return;
        if (e.key === 'Escape') cerrarLightbox();
        if (e.key === 'ArrowLeft') document.getElementById('overlay-foto-prev').click();
        if (e.key === 'ArrowRight') document.getElementById('overlay-foto-next').click();
    });

    /* ─── Filtros ──────────────────────────────────────────── */
    const inputBuscarFoto = document.getElementById('input-buscar-foto');
    const filtroPlano = document.getElementById('filtro-plano');
    const filtroClasificacion = document.getElementById('filtro-clasificacion');
    const filtroUsuario = document.getElementById('filtro-usuario');
    const filtroDia = document.getElementById('filtro-dia');
    const filtroMes = document.getElementById('filtro-mes');
    const filtroAnio = document.getElementById('filtro-anio');
    const tarjetas = Array.from(document.querySelectorAll('.foto-card'));
    const emptyBusqueda = document.getElementById('empty-busqueda');

    function aplicarFiltros() {
        const q = (inputBuscarFoto?.value || '').trim().toLowerCase();
        const planoQ = (filtroPlano?.value || '').trim().toLowerCase();
        const clasificacion = filtroClasificacion?.value || '';
        const usuarioQ = (filtroUsuario?.value || '').trim().toLowerCase();
        const dia = filtroDia?.value || '';
        const mes = filtroMes?.value || '';
        const anio = filtroAnio?.value || '';

        document.getElementById('btn-buscar-clear')?.classList.toggle('show', q.length > 0);

        indicesVisibles = [];

        tarjetas.forEach(card => {
            const coincideTexto = q === '' || card.dataset.planoNombre.includes(q);
            const coincidePlano = planoQ === '' || card.dataset.planoNombre.includes(planoQ);
            const coincideClasificacion = clasificacion === '' || card.dataset.clasificacion === clasificacion;
            const coincideUsuario = usuarioQ === '' || (card.dataset.usuarioNombre || '').includes(usuarioQ);
            const coincideDia = dia === '' || card.dataset.dia === dia;
            const coincideMes = mes === '' || card.dataset.mes === mes;
            const coincideAnio = anio === '' || card.dataset.anio === anio;
            const visible = coincideTexto && coincidePlano && coincideClasificacion &&
                coincideUsuario && coincideDia && coincideMes && coincideAnio;

            card.style.display = visible ? '' : 'none';
            if (visible) indicesVisibles.push(parseInt(card.dataset.index, 10));
        });

        if (emptyBusqueda) emptyBusqueda.style.display = indicesVisibles.length === 0 ? '' : 'none';
        actualizarBotonesDescarga();
    }

    /* ─── Combobox con autocompletado (plano / usuario) ───── */
    function crearAutocomplete(wrapId, inputId, sugerenciasId, onCambio) {
        const wrap = document.getElementById(wrapId);
        const input = document.getElementById(inputId);
        if (!wrap || !input) return;

        const sugerenciasWrap = document.getElementById(sugerenciasId);
        const opciones = Array.from(sugerenciasWrap.querySelectorAll('.autocomplete-sugerencia'));

        function filtrarSugerencias(valor) {
            const q = valor.trim().toLowerCase();
            let hayCoincidencias = false;

            opciones.forEach(op => {
                const esTodos = op.classList.contains('autocomplete-sugerencia-todos');
                const match = esTodos || q === '' || op.dataset.valor.toLowerCase().includes(q);
                op.classList.toggle('hidden', !match);
                if (match) hayCoincidencias = true;
            });

            wrap.classList.toggle('open', hayCoincidencias);
        }

        input.addEventListener('input', function () {
            filtrarSugerencias(this.value);
            onCambio();
        });

        input.addEventListener('focus', function () {
            filtrarSugerencias(this.value);
        });

        opciones.forEach(op => {
            op.addEventListener('click', () => {
                input.value = op.dataset.valor;
                wrap.classList.remove('open');
                onCambio();
            });
        });

        document.addEventListener('click', function (e) {
            if (!wrap.contains(e.target)) wrap.classList.remove('open');
        });
    }

    crearAutocomplete('wrap-filtro-plano', 'filtro-plano', 'sugerencias-plano', aplicarFiltros);
    crearAutocomplete('wrap-filtro-usuario', 'filtro-usuario', 'sugerencias-usuario', aplicarFiltros);

    inputBuscarFoto?.addEventListener('input', aplicarFiltros);
    filtroClasificacion?.addEventListener('change', aplicarFiltros);
    filtroDia?.addEventListener('change', aplicarFiltros);
    filtroMes?.addEventListener('change', aplicarFiltros);
    filtroAnio?.addEventListener('change', aplicarFiltros);
    document.getElementById('btn-buscar-clear')?.addEventListener('click', function () {
        inputBuscarFoto.value = '';
        aplicarFiltros();
        inputBuscarFoto.focus();
    });
    document.getElementById('btn-filtros-clear')?.addEventListener('click', function () {
        inputBuscarFoto.value = '';
        if (filtroPlano) filtroPlano.value = '';
        if (filtroClasificacion) filtroClasificacion.value = '';
        if (filtroUsuario) filtroUsuario.value = '';
        if (filtroDia) filtroDia.value = '';
        if (filtroMes) filtroMes.value = '';
        if (filtroAnio) filtroAnio.value = '';
        aplicarFiltros();
    });

    /* ─── Selección y descarga ─────────────────────────────── */
    const fotoGrid = document.getElementById('foto-grid');
    const wrapDescargar = document.getElementById('wrap-descargar');
    const btnDescargar = document.getElementById('btn-descargar');
    const btnDescargarTodo = document.getElementById('btn-descargar-todo');
    const btnDescargarSeleccion = document.getElementById('btn-descargar-seleccion');
    const textoDescargarSeleccion = document.getElementById('texto-descargar-seleccion');
    const barraSeleccion = document.getElementById('barra-seleccion');
    const checkSeleccionarTodo = document.getElementById('check-seleccionar-todo');
    const textoSeleccionarTodo = document.getElementById('texto-seleccionar-todo');

    let seleccionando = false;
    const seleccionadas = new Set();

    function tarjetasVisibles() {
        return tarjetas.filter(card => card.style.display !== 'none');
    }

    function actualizarBotonesDescarga() {
        if (!btnDescargarSeleccion || !btnDescargarTodo) return;

        const cantidad = seleccionadas.size;
        btnDescargarSeleccion.disabled = cantidad === 0;
        textoDescargarSeleccion.textContent = cantidad > 0 ? `Descargar selección (${cantidad})` : 'Descargar selección';

        const visibles = tarjetasVisibles();
        btnDescargarTodo.disabled = visibles.length === 0;

        if (checkSeleccionarTodo) {
            const idsVisibles = visibles.map(card => card.dataset.id);
            const todasMarcadas = idsVisibles.length > 0 && idsVisibles.every(id => seleccionadas.has(id));
            const algunaMarcada = idsVisibles.some(id => seleccionadas.has(id));
            checkSeleccionarTodo.checked = todasMarcadas;
            checkSeleccionarTodo.indeterminate = !todasMarcadas && algunaMarcada;
            checkSeleccionarTodo.disabled = idsVisibles.length === 0;
            textoSeleccionarTodo.textContent = `Seleccionar todo lo visible (${idsVisibles.length})`;
        }
    }

    checkSeleccionarTodo?.addEventListener('change', function () {
        const visibles = tarjetasVisibles();

        if (this.checked) {
            visibles.forEach(card => {
                seleccionadas.add(card.dataset.id);
                card.classList.add('seleccionada');
            });
        } else {
            visibles.forEach(card => {
                seleccionadas.delete(card.dataset.id);
                card.classList.remove('seleccionada');
            });
        }

        actualizarBotonesDescarga();
    });

    function activarSeleccion() {
        seleccionando = true;
        wrapDescargar.classList.add('open');
        fotoGrid?.classList.add('seleccionando');
        if (barraSeleccion) barraSeleccion.style.display = 'flex';
        actualizarBotonesDescarga();
    }

    function desactivarSeleccion() {
        seleccionando = false;
        wrapDescargar.classList.remove('open');
        fotoGrid?.classList.remove('seleccionando');
        if (barraSeleccion) barraSeleccion.style.display = 'none';
        seleccionadas.clear();
        tarjetas.forEach(card => card.classList.remove('seleccionada'));
        actualizarBotonesDescarga();
    }

    btnDescargar?.addEventListener('click', function (e) {
        e.stopPropagation();
        if (seleccionando) {
            desactivarSeleccion();
        } else {
            activarSeleccion();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && seleccionando) desactivarSeleccion();
    });

    tarjetas.forEach(card => {
        card.addEventListener('click', function () {
            if (seleccionando) {
                const id = card.dataset.id;
                if (seleccionadas.has(id)) {
                    seleccionadas.delete(id);
                    card.classList.remove('seleccionada');
                } else {
                    seleccionadas.add(id);
                    card.classList.add('seleccionada');
                }
                actualizarBotonesDescarga();
            } else {
                abrirLightbox(parseInt(card.dataset.index, 10));
            }
        });
    });

    async function descargarFotos(ids, boton) {
        if (!ids.length) return;

        /* Se guardan los nodos originales (no un string de innerHTML):
           reemplazar el contenido con un string y después reasignarlo
           destruye y recrea el <span id="texto-descargar-seleccion">
           que actualizarBotonesDescarga() actualiza por referencia — esa
           referencia se cachea una sola vez al cargar la página, así que
           quedaba apuntando a un nodo fantasma y el contador ya no se
           actualizaba en pantalla aunque la selección se hubiera limpiado. */
        const nodosOriginales = Array.from(boton.childNodes);
        boton.disabled = true;
        boton.replaceChildren();
        const spinner = document.createElement('i');
        spinner.className = 'fas fa-spinner fa-spin';
        boton.append(spinner, ' Descargando…');

        try {
            const respuesta = await fetch(urlDescargarFotos, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ ids }),
            });

            if (!respuesta.ok) throw new Error('No se pudo generar la descarga');

            const blob = await respuesta.blob();
            const disposicion = respuesta.headers.get('Content-Disposition') || '';
            const coincidencia = disposicion.match(/filename="([^"]+)"/) || disposicion.match(/filename=([^;]+)/);
            const nombreArchivo = coincidencia ? coincidencia[1].trim() : 'fotos.zip';

            const url = window.URL.createObjectURL(blob);
            const enlace = document.createElement('a');
            enlace.href = url;
            enlace.download = nombreArchivo;
            document.body.appendChild(enlace);
            enlace.click();
            enlace.remove();
            window.URL.revokeObjectURL(url);

            boton.replaceChildren(...nodosOriginales);
            desactivarSeleccion();
        } catch (err) {
            boton.disabled = false;
            boton.replaceChildren(...nodosOriginales);
            alert('No se pudo descargar las fotos. Probá de nuevo.');
        }
    }

    btnDescargarTodo?.addEventListener('click', function () {
        const ids = tarjetasVisibles().map(card => card.dataset.id);
        descargarFotos(ids, btnDescargarTodo);
    });

    btnDescargarSeleccion?.addEventListener('click', function () {
        descargarFotos(Array.from(seleccionadas), btnDescargarSeleccion);
    });
</script>
</body>
</html>
