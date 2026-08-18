<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $plano->descripcion ?? 'Plano' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; overscroll-behavior: none; }
        body { font-family: sans-serif; background: #333; overflow: hidden; }

        .app { height: 100vh; display: flex; }

        /* ── BARRA DE HERRAMIENTAS VERTICAL ── */
        .toolbar-vertical {
            flex-shrink: 0;
            width: 78px;
            background: #222;
            display: flex; flex-direction: column; align-items: stretch;
            gap: 0.4rem;
            padding: 0.75rem 0.5rem;
        }
        .tool-btn {
            display: flex; flex-direction: column; align-items: center; gap: 0.35rem;
            background: none; border: none; cursor: pointer;
            padding: 0.55rem 0.25rem; border-radius: 0.55rem;
            color: #ccc; font-size: 0.68rem; font-weight: 600;
        }
        .tool-submenu-wrap > .tool-btn {
            width: 100%; min-height: 56px; justify-content: center;
        }
        .tool-btn:hover { background: #333; }
        .tool-btn.activo { background: #2a6fdb; color: #fff; }
        .tool-swatch {
            width: 22px; height: 22px; border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.55);
        }
        .tool-icon-img {
            width: 22px; height: 22px;
            background: #fff;
            border-radius: 50%;
            padding: 3px;
            box-sizing: border-box;
        }
        .tool-icon-letra {
            width: 22px; height: 22px;
            background: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #ff0000; font-weight: 800; font-size: 0.85rem;
            box-sizing: border-box; flex-shrink: 0;
        }

        /* ── BOTONES CON SUBMENÚ (expandible hacia la derecha) ── */
        .tool-submenu-wrap {
            position: relative;
        }
        .tool-submenu-wrap.activo > .tool-btn {
            background: #2a6fdb; color: #fff;
        }
        .submenu-lateral {
            position: absolute;
            top: 0;
            left: calc(100% + 0.4rem);
            background: #222;
            border-radius: 0.55rem;
            padding: 0.5rem;
            display: none;
            flex-direction: column;
            gap: 0.3rem;
            z-index: 30;
            box-shadow: 0 4px 16px rgba(0,0,0,0.4);
            max-height: 80vh;
            overflow-y: auto;
        }
        .submenu-lateral.abierto { display: flex; }
        .submenu-lateral .tool-btn {
            flex-direction: row;
            white-space: nowrap;
            gap: 0.5rem;
        }
        .submenu-color {
            display: flex; align-items: center; justify-content: space-between;
            gap: 0.6rem;
            padding: 0.3rem 0.4rem 0.55rem;
            margin-bottom: 0.2rem;
            border-bottom: 1px solid #333;
            color: #ccc; font-size: 0.72rem; font-weight: 600;
        }
        .submenu-color input[type="color"] {
            width: 34px; height: 26px; border: none; border-radius: 0.35rem;
            background: none; cursor: pointer; padding: 0;
        }

        /* ── BARRA SUPERIOR DERECHA (Capas + Volver, flotante) ── */
        .barra-superior-derecha {
            position: fixed; top: 12px; right: 12px; z-index: 20;
            display: flex; align-items: flex-start; gap: 0.5rem;
        }
        .btn-superior {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: #444; color: #fff; text-decoration: none; border: none; cursor: pointer;
            padding: 0.5rem 0.9rem; border-radius: 0.5rem;
            font-size: 0.82rem; font-weight: 600; font-family: inherit;
        }
        .btn-superior:hover { background: #555; }
        .btn-superior.activo { background: #2a6fdb; }

        .capas-wrap, .escala-wrap, .preferencias-wrap, .actividad-wrap { position: relative; }

        .panel-actividad {
            position: absolute; top: calc(100% + 0.4rem); right: 0;
            background: #222; border-radius: 0.55rem; padding: 0.6rem;
            display: none; flex-direction: column; gap: 0.3rem;
            min-width: 280px; max-height: 70vh; overflow-y: auto;
            box-shadow: 0 4px 16px rgba(0,0,0,0.4);
        }
        .panel-actividad.abierto { display: flex; }
        .actividad-item {
            padding: 0.5rem 0.4rem;
            border-bottom: 1px solid #333;
            font-size: 0.78rem; color: #ddd; line-height: 1.4;
        }
        .actividad-item:last-child { border-bottom: none; }
        .actividad-item strong { color: #fff; }
        .actividad-item-fecha { color: #888; font-size: 0.68rem; margin-top: 0.15rem; }
        .actividad-vacio { color: #888; font-size: 0.78rem; padding: 0.4rem; }

        .panel-preferencias {
            position: absolute; top: calc(100% + 0.4rem); right: 0;
            background: #222; border-radius: 0.55rem; padding: 0.7rem 0.8rem;
            display: none; flex-direction: column; gap: 0.6rem;
            min-width: 260px; max-height: 70vh; overflow-y: auto;
            box-shadow: 0 4px 16px rgba(0,0,0,0.4);
        }
        .panel-preferencias.abierto { display: flex; }
        .panel-preferencias-titulo {
            color: #888; font-size: 0.66rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.04em; padding-bottom: 0.2rem;
            border-bottom: 1px solid #333;
        }
        .preferencia-item {
            display: flex; align-items: center; justify-content: space-between;
            gap: 0.6rem;
        }
        .preferencia-item-nombre {
            display: flex; align-items: center; gap: 0.5rem;
            color: #ddd; font-size: 0.78rem; font-weight: 500;
        }
        .preferencia-item-nombre .tool-icon-img, .preferencia-item-nombre .tool-icon-letra {
            width: 18px; height: 18px; flex-shrink: 0;
        }
        .preferencia-item-nombre .tool-icon-letra { font-size: 0.62rem; }
        .preferencia-item input[type="number"] {
            width: 60px; padding: 0.3rem 0.4rem;
            background: #2f2f2f; border: 1px solid #444; border-radius: 0.35rem;
            color: #fff; font-size: 0.82rem; font-family: inherit;
        }

        .panel-escala {
            position: absolute; top: calc(100% + 0.4rem); right: 0;
            background: #222; border-radius: 0.55rem; padding: 0.7rem 0.8rem;
            display: none; flex-direction: column; gap: 0.9rem;
            min-width: 220px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.4);
        }
        .panel-escala.abierto { display: flex; }
        .escala-item { display: flex; flex-direction: column; gap: 0.35rem; }
        .escala-item-cabecera {
            display: flex; justify-content: space-between; align-items: center;
            color: #ddd; font-size: 0.78rem; font-weight: 600;
        }
        .escala-item-valor { color: #999; font-weight: 500; }
        .escala-item input[type="range"] {
            width: 100%; accent-color: #2a6fdb;
        }

        .panel-capas {
            position: absolute; top: calc(100% + 0.4rem); right: 0;
            background: #222; border-radius: 0.55rem; padding: 0.6rem;
            display: none; flex-direction: column; gap: 0.7rem;
            min-width: 200px; max-height: 70vh; overflow-y: auto;
            box-shadow: 0 4px 16px rgba(0,0,0,0.4);
        }
        .panel-capas.abierto { display: flex; }
        .panel-capas-acciones {
            display: flex; gap: 0.4rem;
            padding-bottom: 0.5rem; margin-bottom: 0.1rem;
            border-bottom: 1px solid #333;
        }
        .capa-accion {
            flex: 1; background: #2f2f2f; border: none; cursor: pointer;
            color: #ccc; font-size: 0.72rem; font-weight: 600; font-family: inherit;
            padding: 0.4rem 0.4rem; border-radius: 0.4rem;
        }
        .capa-accion:hover { background: #3a3a3a; color: #fff; }
        .panel-capas-vacio { color: #888; font-size: 0.78rem; padding: 0.2rem 0.3rem; }
        .panel-capas-grupo { display: flex; flex-direction: column; gap: 0.15rem; }
        .panel-capas-titulo {
            color: #888; font-size: 0.66rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.04em; padding: 0 0.3rem 0.2rem;
        }
        .capa-item {
            display: flex; align-items: center; gap: 0.55rem;
            background: none; border: none; cursor: pointer;
            color: #eee; font-size: 0.8rem; font-weight: 500;
            padding: 0.4rem 0.35rem; border-radius: 0.4rem;
            width: 100%; text-align: left; font-family: inherit;
        }
        .capa-item:hover { background: #2f2f2f; }
        .capa-item .tool-swatch, .capa-item .tool-icon-img, .capa-item .tool-icon-letra {
            width: 18px; height: 18px; flex-shrink: 0;
        }
        .capa-item .tool-icon-letra { font-size: 0.62rem; }
        .capa-nombre { flex: 1; }
        .capa-ojo { display: inline-flex; color: #ccc; flex-shrink: 0; }
        .capa-item.oculta { color: #888; }
        .capa-item.oculta .capa-ojo { color: #666; }

        .lienzo-wrap {
            flex: 1;
            position: relative;
            overflow: hidden;
            background: #333;
            touch-action: none;
        }

        /* ── VISTA PREVIA DE FOTOGRAFÍA (modal pequeño) ── */
        .overlay-foto {
            position: fixed; inset: 0; z-index: 50;
            background: rgba(0,0,0,0.6);
            display: none; align-items: center; justify-content: center;
            padding: 2rem;
        }
        .overlay-foto.abierto { display: flex; }
        .overlay-foto-contenido {
            position: relative;
            max-width: min(420px, 90vw); max-height: 80vh;
            background: #222; border-radius: 0.6rem; padding: 0.6rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.5);
        }
        .overlay-foto-contenido img {
            display: block; max-width: 100%; max-height: 70vh;
            border-radius: 0.4rem;
        }
        .overlay-foto-cerrar {
            position: absolute; top: -14px; right: -14px;
            width: 32px; height: 32px; border-radius: 50%;
            background: #444; color: #fff; border: none; cursor: pointer;
            font-size: 1.1rem; font-weight: 700; line-height: 1;
            display: flex; align-items: center; justify-content: center;
        }
        .overlay-foto-cerrar:hover { background: #555; }
        .overlay-foto-nav {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 34px; height: 34px; border-radius: 50%;
            background: rgba(0,0,0,0.55); color: #fff; border: none; cursor: pointer;
            font-size: 1.4rem; font-weight: 700; line-height: 1;
            display: flex; align-items: center; justify-content: center;
        }
        .overlay-foto-nav:hover { background: rgba(0,0,0,0.75); }
        .overlay-foto-prev { left: 8px; }
        .overlay-foto-next { right: 8px; }
        .overlay-foto-pie {
            display: flex; align-items: center; justify-content: space-between;
            gap: 0.6rem; padding-top: 0.5rem;
        }
        .overlay-foto-contador { color: #ccc; font-size: 0.78rem; font-weight: 600; }
        .overlay-foto-acciones { display: flex; gap: 0.4rem; margin-left: auto; }
        .overlay-foto-accion {
            background: #2f2f2f; border: none; cursor: pointer;
            color: #eee; font-size: 0.74rem; font-weight: 600; font-family: inherit;
            padding: 0.4rem 0.6rem; border-radius: 0.4rem;
        }
        .overlay-foto-accion:hover { background: #3a3a3a; }
        .overlay-foto-accion-borrar:hover { background: #7f1d1d; }

        .lienzo {
            position: absolute;
            left: 0; top: 0;
            transform-origin: 0 0;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            will-change: transform;
        }
        .lienzo canvas { display: block; }
        #draw-canvas {
            position: absolute; left: 0; top: 0;
            width: 100%; height: 100%;
            display: block;
            cursor: crosshair;
        }
        .input-texto-flotante {
            position: absolute;
            display: none;
            transform: translateY(-50%);
            font: 600 16px sans-serif;
            line-height: 1;
            background: rgba(255,255,255,0.9);
            border: 1px dashed #2a6fdb;
            border-radius: 0.2rem;
            padding: 0.05em 0.25em;
            min-width: 3ch;
            outline: none;
            z-index: 10;
        }

        .panel-seleccion {
            position: absolute;
            display: none;
            transform: translate(-50%, -100%);
            gap: 0.4rem;
            background: #222;
            padding: 0.35rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.4);
            z-index: 15;
        }
        .panel-seleccion.abierto { display: flex; }
        .panel-seleccion-btn {
            background: #444; color: #fff; border: none; cursor: pointer;
            padding: 0.4rem 0.7rem; border-radius: 0.4rem;
            font-size: 0.78rem; font-weight: 600; font-family: inherit;
            white-space: nowrap;
        }
        .panel-seleccion-btn:hover { background: #555; }
        .panel-seleccion-btn.activo { background: #2a6fdb; }
        .panel-seleccion-btn.borrar:hover { background: #7f1d1d; }
    </style>
</head>
<body>

    <div class="barra-superior-derecha">
        <div class="capas-wrap" id="capas-wrap">
            <button type="button" class="btn-superior" id="btn-capas">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                    <polyline points="2 17 12 22 22 17"></polyline>
                    <polyline points="2 12 12 17 22 12"></polyline>
                </svg>
                Capas
            </button>
            <div class="panel-capas" id="panel-capas"></div>
        </div>
        <div class="preferencias-wrap" id="preferencias-wrap" @if(!$puedeEditar) style="display:none" @endif>
            <button type="button" class="btn-superior" id="btn-preferencias">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                Preferencias
            </button>
            <div class="panel-preferencias" id="panel-preferencias">
                <span class="panel-preferencias-titulo">Numeración inicial</span>
            </div>
        </div>
        <div class="escala-wrap" id="escala-wrap">
            <button type="button" class="btn-superior" id="btn-escala">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="21" x2="4" y2="14"></line>
                    <line x1="4" y1="10" x2="4" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12" y2="3"></line>
                    <line x1="20" y1="21" x2="20" y2="16"></line>
                    <line x1="20" y1="12" x2="20" y2="3"></line>
                    <line x1="1" y1="14" x2="7" y2="14"></line>
                    <line x1="9" y1="8" x2="15" y2="8"></line>
                    <line x1="17" y1="16" x2="23" y2="16"></line>
                </svg>
                Escala
            </button>
            <div class="panel-escala" id="panel-escala">
                <div class="escala-item">
                    <div class="escala-item-cabecera">
                        <span>Daños</span>
                        <span class="escala-item-valor" id="escala-danos-valor">100%</span>
                    </div> 
                    <input type="range" id="escala-danos" min="50" max="200" step="5" value="100">
                </div>
                <div class="escala-item">
                    <div class="escala-item-cabecera">
                        <span>Ensayos</span>
                        <span class="escala-item-valor" id="escala-ensayos-valor">100%</span>
                    </div>
                    <input type="range" id="escala-ensayos" min="50" max="200" step="5" value="100">
                </div>
                <div class="escala-item">
                    <div class="escala-item-cabecera">
                        <span>Fotos</span>
                        <span class="escala-item-valor" id="escala-fotos-valor">100%</span>
                    </div>
                    <input type="range" id="escala-fotos" min="50" max="200" step="5" value="100">
                </div>
                <div class="escala-item">
                    <div class="escala-item-cabecera">
                        <span>Texto</span>
                        <span class="escala-item-valor" id="escala-texto-valor">100%</span>
                    </div>
                    <input type="range" id="escala-texto" min="50" max="200" step="5" value="100">
                </div>
            </div>
        </div>
        <div class="actividad-wrap" id="actividad-wrap">
            <button type="button" class="btn-superior" id="btn-actividad">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                Actividad
            </button>
            <div class="panel-actividad" id="panel-actividad"></div>
        </div>
        <a href="{{ route('planos_tc.index', $obraTc->id) }}" class="btn-superior">&larr; Volver</a>
    </div>

    <div class="app">
        <nav class="toolbar-vertical" @if(!$puedeEditar && !$puedeEliminar) style="display:none" @endif>
            <div class="tool-submenu-wrap activo" id="danos-wrap" @if(!$puedeEditar) style="display:none" @endif>
                <button type="button" class="tool-btn" id="tool-danos" title="Daños">
                    <span class="tool-swatch" style="background:#e53e3e; display:none"></span>
                    <img class="tool-icon-img" src="{{ asset('img/iconos/Fisura.svg') }}" alt="">
                    Daños
                </button>
                <div class="submenu-lateral" id="submenu-danos">
                    <button type="button" class="tool-btn tool-submenu-item activo" data-tool="fisura" title="Fisura">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Fisura.svg') }}" alt="">
                        Fisura
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="corrosion" title="Corrosión">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Corrosion.svg') }}" alt="">
                        Corrosión
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="humedad" title="Humedad">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Humedad.svg') }}" alt="">
                        Humedad
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="coqueras" title="Coqueras">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Coqueras.svg') }}" alt="">
                        Coqueras
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="fisura_ducto" title="Fisura por ducto">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Fisuras por ductos.svg') }}" alt="">
                        Fisura por ducto
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="junta_fria" title="Junta fría">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Junta fria.svg') }}" alt="">
                        Junta fría
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="armadura_expuesta" title="Armadura expuesta">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Armadura expuesta.svg') }}" alt="">
                        Armadura expuesta
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="eflorescencia" title="Eflorescencia">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Eflorescencia.svg') }}" alt="">
                        Eflorescencia
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="socavacion" title="Socavación">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/socavacion.svg') }}" alt="">
                        Socavación
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="desprendimiento" title="Desprendimiento">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/desprendimiento.svg') }}" alt="">
                        Desprendimiento
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="exfoliacion" title="Exfoliación">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/exfoliacion.svg') }}" alt="">
                        Exfoliación
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="desaplome" title="Desaplome">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/desaplome.svg') }}" alt="">
                        Desaplome
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="fisura_vertical" title="Fisura vertical">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Fisura vertical.svg') }}" alt="">
                        Fisura vertical
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="fisura_inclinada" title="Fisura inclinada">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Fisura inclinada.svg') }}" alt="">
                        Fisura inclinada
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="fisura_semiinclinada" title="Fisura semi-inclinada">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Fisura seminclinada.svg') }}" alt="">
                        Fisura semi-inclinada
                    </button>
                </div>
            </div>
            <div class="tool-submenu-wrap" id="ensayos-wrap" @if(!$puedeEditar) style="display:none" @endif>
                <button type="button" class="tool-btn" id="tool-ensayos" title="Ensayos">
                    <span class="tool-icon-letra" style="display:none">F</span>
                    <img class="tool-icon-img" src="{{ asset('img/iconos/esclerometria.svg') }}" alt="">
                    Ensayos
                </button>
                <div class="submenu-lateral" id="submenu-ensayos">
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="esclerometria" title="Esclerometría">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/esclerometria.svg') }}" alt="">
                        Esclerometría
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="carbonatacion" title="Carbonatación">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/carbonatacion.svg') }}" alt="">
                        Carbonatación
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="pachometria" title="Pachometría">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/pachometria.svg') }}" alt="">
                        Pachometría
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="testigos" title="Testigos">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/testigos.svg') }}" alt="">
                        Testigos
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="ultrasonido" title="Ultrasonido">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/ultrasonido.svg') }}" alt="">
                        Ultrasonido
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="resistividad" title="Resistividad">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Resistividad.svg') }}" alt="">
                        Resistividad
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="potencial" title="Potencial">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Potencial.svg') }}" alt="">
                        Potencial
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="cloruros" title="Cloruros">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/Cloruros.svg') }}" alt="">
                        Cloruros
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="georradar" title="Georradar">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/georradar.svg') }}" alt="">
                        Georradar
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="computo_fisuras" title="Cómputo de fisuras">
                        <span class="tool-icon-letra">F</span>
                        Cómputo de fisuras
                    </button>
                </div>
            </div>
            <div class="tool-submenu-wrap" id="anotaciones-wrap" @if(!$puedeEditar) style="display:none" @endif>
                <button type="button" class="tool-btn" id="tool-anotaciones" title="Anotaciones">
                    <img class="tool-icon-img" src="{{ asset('img/iconos/anotacion.svg') }}" alt="">
                    Anotaciones
                </button>
                <div class="submenu-lateral" id="submenu-anotaciones">
                    <div class="submenu-color">
                        <label for="color-anotacion">Color</label>
                        <input type="color" id="color-anotacion" value="#000000">
                    </div>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="texto" title="Texto">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/texto.svg') }}" alt="">
                        Texto
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="dibujo_libre" title="Dibujo a mano alzada">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/mano_alzada.svg') }}" alt="">
                        Mano alzada
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="dibujo_libre_relleno" title="Mano alzada con relleno">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/mano_alzada_relleno.svg') }}" alt="">
                        Mano alzada con relleno
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="circulo" title="Círculo">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/circulo.svg') }}" alt="">
                        Círculo
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="circulo_relleno" title="Círculo con relleno">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/circulo_relleno.svg') }}" alt="">
                        Círculo con relleno
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="rectangulo" title="Rectángulo">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/rectangulo.svg') }}" alt="">
                        Rectángulo
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="rectangulo_relleno" title="Rectángulo con relleno">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/rectangulo_relleno.svg') }}" alt="">
                        Rectángulo con relleno
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="linea_recta" title="Línea recta">
                        <img class="tool-icon-img" src="{{ asset('img/iconos/linea_recta.svg') }}" alt="">
                        Línea recta
                    </button>
                </div>
            </div>
            <button type="button" class="tool-btn" data-tool="foto" title="Fotografía" @if(!$puedeEditar) style="display:none" @endif>
                <img class="tool-icon-img" src="{{ asset('img/iconos/foto.svg') }}" alt="">
                Foto
            </button>
            <button type="button" class="tool-btn" data-tool="seleccion" title="Selección">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
                </svg>
                Selección
            </button>
        </nav>

        <div class="lienzo-wrap" id="lienzo-wrap">
            <div class="lienzo" id="lienzo">
                <canvas id="pdf-canvas"></canvas>
            </div>
            <canvas id="draw-canvas"></canvas>
            <input type="text" id="input-texto-flotante" class="input-texto-flotante" autocomplete="off">
            <div class="panel-seleccion" id="panel-seleccion">
                <button type="button" class="panel-seleccion-btn" id="btn-seleccion-mover" @if(!$puedeEditar) style="display:none" @endif>Mover</button>
                <button type="button" class="panel-seleccion-btn borrar" id="btn-seleccion-eliminar" @if(!$puedeEliminar) style="display:none" @endif>Eliminar</button>
            </div>
        </div>
    </div>

    <input type="file" accept="image/*" capture="environment" multiple id="input-foto" style="display:none">

    <div class="overlay-foto" id="overlay-foto">
        <div class="overlay-foto-contenido">
            <button type="button" class="overlay-foto-cerrar" id="overlay-foto-cerrar">&times;</button>
            <button type="button" class="overlay-foto-nav overlay-foto-prev" id="overlay-foto-prev">&lsaquo;</button>
            <img id="overlay-foto-img" src="" alt="Fotografía">
            <button type="button" class="overlay-foto-nav overlay-foto-next" id="overlay-foto-next">&rsaquo;</button>
            <div class="overlay-foto-pie">
                <span class="overlay-foto-contador" id="overlay-foto-contador"></span>
                <div class="overlay-foto-acciones">
                    <button type="button" class="overlay-foto-accion" id="overlay-foto-agregar" @if(!$puedeEditar) style="display:none" @endif>Agregar foto</button>
                    <button type="button" class="overlay-foto-accion overlay-foto-accion-borrar" id="overlay-foto-eliminar" @if(!$puedeEliminar) style="display:none" @endif>Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const urlPdf = @json(Storage::url('planos/' . $plano->archivo));
        const rotacionPlano = {{ (int) ($plano->rotacion ?? 0) }};

        /* Permisos del módulo "ano_pla" (Anotaciones - Planos), calculados
           en el backend (PlanoController::show). "ver" ya está garantizado
           por el middleware de la ruta con solo llegar a esta vista. */
        const PUEDE_EDITAR = @json($puedeEditar);
        const PUEDE_ELIMINAR = @json($puedeEliminar);

        /* Guardado en la base de datos: el estado ya guardado (si lo hay),
           las URLs de los endpoints y el token CSRF para poder mandar los
           fetch() de guardado/subida de fotos. */
        const estadoGuardado = @json($estadoGuardado);
        const urlGuardarEstado = @json(route('planos_tc.guardarEstado', [$obraTc->id, $plano->id]));
        const urlSubirFoto = @json(route('planos_tc.subirFoto', [$obraTc->id, $plano->id]));
        const urlActividad = @json(route('planos_tc.actividad', [$obraTc->id, $plano->id]));
        const CSRF_TOKEN = @json(csrf_token());

        const dpr = window.devicePixelRatio || 1;
        const SOBREMUESTREO = Math.min(Math.max(dpr, 1) * 1.5, 3);
        const ZOOM_MIN = 0.05;
        const ZOOM_MAX = 40;
        const ESPACIO_TRAMA = 4;

        /* ─── Herramientas de dibujo ───────────────────────── */
        const ENSAYOS = [
            { tool: 'esclerometria', url: @json(asset('img/iconos/esclerometria.svg')), prefijo: 'E', color: '#d800c9', nombre: 'Esclerometría' },
            { tool: 'carbonatacion', url: @json(asset('img/iconos/carbonatacion.svg')), prefijo: 'C', color: '#1f4fd8', nombre: 'Carbonatación' },
            { tool: 'pachometria', url: @json(asset('img/iconos/pachometria.svg')), prefijo: 'Pch', color: '#1f4fd8', nombre: 'Pachometría' },
            { tool: 'testigos', url: @json(asset('img/iconos/testigos.svg')), prefijo: 'T', color: '#0a5c26', nombre: 'Testigos' },
            { tool: 'ultrasonido', url: @json(asset('img/iconos/ultrasonido.svg')), prefijo: 'U', color: '#e00000', nombre: 'Ultrasonido' },
            { tool: 'resistividad', url: @json(asset('img/iconos/Resistividad.svg')), prefijo: 'R', color: '#cf9c26', nombre: 'Resistividad' },
            { tool: 'potencial', url: @json(asset('img/iconos/Potencial.svg')), prefijo: 'P', color: '#ea0d0d', nombre: 'Potencial' },
            { tool: 'cloruros', url: @json(asset('img/iconos/Cloruros.svg')), prefijo: 'CL', color: '#ea0d0d', nombre: 'Cloruros' },
            { tool: 'georradar', url: @json(asset('img/iconos/georradar.svg')), prefijo: 'Geo', color: '#006400', nombre: 'Georradar' },
        ];

        const DANOS = [
            { tool: 'fisura', nombre: 'Fisura', color: '#e53e3e', url: @json(asset('img/iconos/Fisura.svg')) },
            { tool: 'corrosion', nombre: 'Corrosión', color: '#d800c9', url: @json(asset('img/iconos/Corrosion.svg')) },
            { tool: 'humedad', nombre: 'Humedad', color: '#1565c0', url: @json(asset('img/iconos/Humedad.svg')) },
            { tool: 'coqueras', nombre: 'Coqueras', color: '#0a8a3a', url: @json(asset('img/iconos/Coqueras.svg')) },
            { tool: 'fisura_ducto', nombre: 'Fisura por ducto', color: '#16a34a', url: @json(asset('img/iconos/Fisuras por ductos.svg')) },
            { tool: 'junta_fria', nombre: 'Junta fría', color: '#f97316', url: @json(asset('img/iconos/Junta fria.svg')) },
            { tool: 'armadura_expuesta', nombre: 'Armadura expuesta', color: '#2563eb', url: @json(asset('img/iconos/Armadura expuesta.svg')) },
            { tool: 'eflorescencia', nombre: 'Eflorescencia', color: '#06b6d4', url: @json(asset('img/iconos/Eflorescencia.svg')) },
            { tool: 'socavacion', nombre: 'Socavación', color: '#78350f', url: @json(asset('img/iconos/socavacion.svg')) },
            { tool: 'desprendimiento', nombre: 'Desprendimiento', color: '#b91c1c', url: @json(asset('img/iconos/desprendimiento.svg')) },
            { tool: 'exfoliacion', nombre: 'Exfoliación', color: '#c2410c', url: @json(asset('img/iconos/exfoliacion.svg')) },
            { tool: 'desaplome', nombre: 'Desaplome', color: '#eab308', url: @json(asset('img/iconos/desaplome.svg')) },
        ];

        /* Estos daños se insertan como ícono (igual que un ensayo) pero
           sin numerar: no llevan prefijo ni contador. */
        const DANOS_ICONO = [
            { tool: 'fisura_vertical', url: @json(asset('img/iconos/Fisura vertical.svg')), nombre: 'Fisura vertical' },
            { tool: 'fisura_inclinada', url: @json(asset('img/iconos/Fisura inclinada.svg')), nombre: 'Fisura inclinada' },
            { tool: 'fisura_semiinclinada', url: @json(asset('img/iconos/Fisura seminclinada.svg')), nombre: 'Fisura semi-inclinada' },
        ];

        /* Anotaciones libres: texto, dibujo a mano alzada y línea recta.
           Comparten un mismo color, elegido en vivo con el selector del
           submenú (ver HERRAMIENTAS.texto/dibujo_libre/linea_recta más abajo). */
        const ANOTACIONES = [
            { tool: 'texto', nombre: 'Texto', url: @json(asset('img/iconos/texto.svg')) },
            { tool: 'dibujo_libre', nombre: 'Dibujo a mano alzada', url: @json(asset('img/iconos/mano_alzada.svg')) },
            { tool: 'dibujo_libre_relleno', nombre: 'Mano alzada con relleno', url: @json(asset('img/iconos/mano_alzada_relleno.svg')) },
            { tool: 'circulo', nombre: 'Círculo', url: @json(asset('img/iconos/circulo.svg')) },
            { tool: 'circulo_relleno', nombre: 'Círculo con relleno', url: @json(asset('img/iconos/circulo_relleno.svg')) },
            { tool: 'rectangulo', nombre: 'Rectángulo', url: @json(asset('img/iconos/rectangulo.svg')) },
            { tool: 'rectangulo_relleno', nombre: 'Rectángulo con relleno', url: @json(asset('img/iconos/rectangulo_relleno.svg')) },
            { tool: 'linea_recta', nombre: 'Línea recta', url: @json(asset('img/iconos/linea_recta.svg')) },
        ];

        /* La fotografía comparte el mecanismo de ícono con los ensayos
           (imagen + tamaño), pero no se numera y no es un ensayo: se
           agrupa aparte. Usa el mismo tamaño que los íconos de ensayos. */
        const FOTO = { tool: 'foto', url: @json(asset('img/iconos/foto.svg')), color: '#e6a400', nombre: 'Fotografía' };
        const ENSAYOS_Y_FOTO = [...ENSAYOS, FOTO];

        /* Grupo de escala al que pertenece cada ícono, para el panel
           de "Escala" (daños-ícono, ensayos o fotos). */
        const GRUPO_ESCALA = {};
        DANOS_ICONO.forEach(({ tool }) => { GRUPO_ESCALA[tool] = 'danos'; });
        ENSAYOS.forEach(({ tool }) => { GRUPO_ESCALA[tool] = 'ensayos'; });
        GRUPO_ESCALA[FOTO.tool] = 'fotos';
        GRUPO_ESCALA.computo_fisuras = 'ensayos';

        const PREFIJOS_ENSAYO = {};
        const COLORES_ENSAYO = {};
        ENSAYOS_Y_FOTO.forEach(({ tool, prefijo, color }) => {
            PREFIJOS_ENSAYO[tool] = prefijo;
            COLORES_ENSAYO[tool] = color;
        });

        /* Herramientas con numeración automática (E1, E2, ..., F1, F2, ...),
           listadas en el panel de "Preferencias" para elegir desde qué
           número arranca cada una (ver numeroInicial en siguienteNumeroLibre). */
        const HERRAMIENTAS_NUMERADAS = [
            ...ENSAYOS.map(({ tool, nombre, prefijo, url }) => ({ tool, nombre, prefijo, url })),
            { tool: 'computo_fisuras', nombre: 'Cómputo de fisuras', prefijo: 'F', letra: 'F' },
        ];

        const numeroInicial = {};
        HERRAMIENTAS_NUMERADAS.forEach(({ tool }) => { numeroInicial[tool] = 1; });

        const panelPreferencias = document.getElementById('panel-preferencias');
        HERRAMIENTAS_NUMERADAS.forEach(({ tool, nombre, url, letra }) => {
            const fila = document.createElement('div');
            fila.className = 'preferencia-item';

            const nombreWrap = document.createElement('span');
            nombreWrap.className = 'preferencia-item-nombre';

            if (url) {
                const marca = document.createElement('img');
                marca.className = 'tool-icon-img';
                marca.src = url;
                marca.alt = '';
                nombreWrap.appendChild(marca);
            } else if (letra) {
                const marca = document.createElement('span');
                marca.className = 'tool-icon-letra';
                marca.textContent = letra;
                nombreWrap.appendChild(marca);
            }

            const textoNombre = document.createElement('span');
            textoNombre.textContent = nombre;
            nombreWrap.appendChild(textoNombre);

            const input = document.createElement('input');
            input.type = 'number';
            input.min = '1';
            input.step = '1';
            input.value = '1';
            input.addEventListener('input', () => {
                const valor = parseInt(input.value, 10);
                numeroInicial[tool] = Number.isInteger(valor) && valor > 0 ? valor : 1;
            });

            fila.append(nombreWrap, input);
            panelPreferencias.appendChild(fila);
        });

        const HERRAMIENTAS = {
            fisura: { tipo: 'trazo', color: '#e53e3e', grosor: 0.25 },
            corrosion: { tipo: 'trazo', color: '#d800c9', grosor: 0.25, cierreAutomatico: true },
            humedad: { tipo: 'trazo', color: '#1565c0', grosor: 0.25, cierreAutomatico: true },
            coqueras: { tipo: 'trazo', color: '#0a8a3a', grosor: 0.25, cierreAutomatico: true },
            fisura_ducto: { tipo: 'trazo', color: '#16a34a', grosor: 0.25 },
            junta_fria: { tipo: 'trazo', color: '#f97316', grosor: 0.25 },
            armadura_expuesta: { tipo: 'linea', color: '#2563eb', grosor: 0.25 },
            eflorescencia: { tipo: 'trazo', color: '#06b6d4', grosor: 0.25, cierreAutomatico: true },
            socavacion: { tipo: 'trazo', color: '#78350f', grosor: 0.25, cierreAutomatico: true },
            desprendimiento: { tipo: 'trazo', color: '#b91c1c', grosor: 0.25, cierreAutomatico: true },
            exfoliacion: { tipo: 'trazo', color: '#c2410c', grosor: 0.25, cierreAutomatico: true },
            desaplome: { tipo: 'trazo', color: '#eab308', grosor: 0.25, cierreAutomatico: true },
            texto: { tipo: 'texto', color: '#000000', tamano: 8.32 }, // = tamano ensayo (26) * 0.32, la misma fuente de sus etiquetas
            computo_fisuras: { tipo: 'texto_contador', color: '#ff0000', tamano: 8.32, prefijo: 'F' },
            dibujo_libre: { tipo: 'trazo', color: '#000000', grosor: 0.2 },
            dibujo_libre_relleno: { tipo: 'trazo', color: '#000000', grosor: 0.2, cierreAutomatico: true },
            circulo: { tipo: 'circulo', color: '#000000', grosor: 0.2, relleno: false },
            circulo_relleno: { tipo: 'circulo', color: '#000000', grosor: 0.2, relleno: true },
            rectangulo: { tipo: 'rectangulo', color: '#000000', grosor: 0.2, relleno: false },
            rectangulo_relleno: { tipo: 'rectangulo', color: '#000000', grosor: 0.2, relleno: true },
            linea_recta: { tipo: 'linea', color: '#000000', grosor: 0.2 },
        };

        ENSAYOS_Y_FOTO.forEach(({ tool, url, tamano }) => {
            const img = new Image();
            img.onload = () => redibujarTrazos();
            img.src = url;
            HERRAMIENTAS[tool] = { tipo: 'icono', imagen: img, tamano: tamano || 26 };
        });

        DANOS_ICONO.forEach(({ tool, url }) => {
            const img = new Image();
            img.onload = () => redibujarTrazos();
            img.src = url;
            HERRAMIENTAS[tool] = { tipo: 'icono', imagen: img, tamano: 10 };
        });

        /* ─── Panel de capas: solo lista lo que ya se dibujó ──
             Cada tipo aparece recién cuando se usa por primera vez. ─ */
        const capasVisibles = {};
        const metaCapas = {};
        const itemsCapaDom = {};
        const ICONO_OJO_ABIERTO = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        const ICONO_OJO_CERRADO = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.5 18.5 0 0 1 5.06-5.94M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a18.32 18.32 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';

        function establecerVisibilidadCapa(tool, visible) {
            capasVisibles[tool] = visible;
            const refs = itemsCapaDom[tool];
            if (refs) {
                refs.btn.classList.toggle('oculta', !visible);
                refs.ojo.innerHTML = visible ? ICONO_OJO_ABIERTO : ICONO_OJO_CERRADO;
            }
        }

        function crearItemCapa({ tool, nombre, color, url, letra }) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'capa-item';
            btn.dataset.capa = tool;

            if (url) {
                const marca = document.createElement('img');
                marca.className = 'tool-icon-img';
                marca.src = url;
                marca.alt = '';
                btn.appendChild(marca);
            } else if (letra) {
                const marca = document.createElement('span');
                marca.className = 'tool-icon-letra';
                marca.textContent = letra;
                btn.appendChild(marca);
            } else {
                const marca = document.createElement('span');
                marca.className = 'tool-swatch';
                marca.style.background = color;
                btn.appendChild(marca);
            }

            const nombreSpan = document.createElement('span');
            nombreSpan.className = 'capa-nombre';
            nombreSpan.textContent = nombre;
            btn.appendChild(nombreSpan);

            const ojo = document.createElement('span');
            ojo.className = 'capa-ojo';
            ojo.innerHTML = ICONO_OJO_ABIERTO;
            btn.appendChild(ojo);

            btn.addEventListener('click', e => {
                e.stopPropagation();
                establecerVisibilidadCapa(tool, !capasVisibles[tool]);
                redibujarTrazos();
            });

            itemsCapaDom[tool] = { btn, ojo };
            return btn;
        }

        function crearGrupoCapas(titulo) {
            const grupo = document.createElement('div');
            grupo.className = 'panel-capas-grupo';
            grupo.style.display = 'none';
            const tituloEl = document.createElement('span');
            tituloEl.className = 'panel-capas-titulo';
            tituloEl.textContent = titulo;
            grupo.appendChild(tituloEl);
            return grupo;
        }

        const panelCapas = document.getElementById('panel-capas');

        const accionesCapas = document.createElement('div');
        accionesCapas.className = 'panel-capas-acciones';

        const btnMostrarTodo = document.createElement('button');
        btnMostrarTodo.type = 'button';
        btnMostrarTodo.className = 'capa-accion';
        btnMostrarTodo.textContent = 'Mostrar todo';
        btnMostrarTodo.addEventListener('click', e => {
            e.stopPropagation();
            Object.keys(itemsCapaDom).forEach(tool => establecerVisibilidadCapa(tool, true));
            redibujarTrazos();
        });

        const btnOcultarTodo = document.createElement('button');
        btnOcultarTodo.type = 'button';
        btnOcultarTodo.className = 'capa-accion';
        btnOcultarTodo.textContent = 'Ocultar todo';
        btnOcultarTodo.addEventListener('click', e => {
            e.stopPropagation();
            Object.keys(itemsCapaDom).forEach(tool => establecerVisibilidadCapa(tool, false));
            redibujarTrazos();
        });

        accionesCapas.append(btnMostrarTodo, btnOcultarTodo);
        panelCapas.appendChild(accionesCapas);

        const panelCapasVacio = document.createElement('span');
        panelCapasVacio.className = 'panel-capas-vacio';
        panelCapasVacio.textContent = 'Todavía no se dibujó nada';
        panelCapas.appendChild(panelCapasVacio);

        const grupoCapasDanos = crearGrupoCapas('Daños');
        const grupoCapasEnsayos = crearGrupoCapas('Ensayos');
        const grupoCapasFoto = crearGrupoCapas('Fotografía');
        const grupoCapasAnotaciones = crearGrupoCapas('Anotaciones');
        panelCapas.appendChild(grupoCapasDanos);
        panelCapas.appendChild(grupoCapasEnsayos);
        panelCapas.appendChild(grupoCapasFoto);
        panelCapas.appendChild(grupoCapasAnotaciones);

        DANOS.forEach(item => { metaCapas[item.tool] = { ...item, grupo: grupoCapasDanos }; });
        DANOS_ICONO.forEach(item => { metaCapas[item.tool] = { ...item, grupo: grupoCapasDanos }; });
        ENSAYOS.forEach(item => { metaCapas[item.tool] = { ...item, grupo: grupoCapasEnsayos }; });
        metaCapas.computo_fisuras = { tool: 'computo_fisuras', nombre: 'Cómputo de fisuras', color: '#ff0000', letra: 'F', grupo: grupoCapasEnsayos };
        metaCapas[FOTO.tool] = { ...FOTO, grupo: grupoCapasFoto };
        ANOTACIONES.forEach(item => { metaCapas[item.tool] = { ...item, grupo: grupoCapasAnotaciones }; });

        function registrarUsoCapa(tool) {
            if (itemsCapaDom[tool]) return;
            capasVisibles[tool] = true;
            const meta = metaCapas[tool];
            meta.grupo.appendChild(crearItemCapa(meta));
            meta.grupo.style.display = 'flex';
            panelCapasVacio.style.display = 'none';
        }

        /* Inversa de registrarUsoCapa: si tras un borrado ya no queda
           ningún elemento de ese tipo en el plano, saca la capa del
           panel (y oculta el grupo/vuelve al estado vacío si corresponde). */
        function quitarCapaSiVacia(tool) {
            const quedanItems = estadoPlano.trazos.some(item => item.tool === tool);
            if (quedanItems) return;

            const refs = itemsCapaDom[tool];
            if (!refs) return;

            refs.btn.remove();
            delete itemsCapaDom[tool];
            delete capasVisibles[tool];

            const meta = metaCapas[tool];
            if (meta && meta.grupo.querySelectorAll('.capa-item').length === 0) {
                meta.grupo.style.display = 'none';
            }

            if (Object.keys(itemsCapaDom).length === 0) {
                panelCapasVacio.style.display = '';
            }
        }

        const capasWrap = document.getElementById('capas-wrap');
        const btnCapas = document.getElementById('btn-capas');
        btnCapas.addEventListener('click', e => {
            e.stopPropagation();
            panelCapas.classList.toggle('abierto');
            btnCapas.classList.toggle('activo', panelCapas.classList.contains('abierto'));
        });
        document.addEventListener('click', e => {
            if (!capasWrap.contains(e.target)) {
                panelCapas.classList.remove('abierto');
                btnCapas.classList.remove('activo');
            }
        });

        const preferenciasWrap = document.getElementById('preferencias-wrap');
        const btnPreferencias = document.getElementById('btn-preferencias');
        btnPreferencias.addEventListener('click', e => {
            e.stopPropagation();
            panelPreferencias.classList.toggle('abierto');
            btnPreferencias.classList.toggle('activo', panelPreferencias.classList.contains('abierto'));
        });
        document.addEventListener('click', e => {
            if (!preferenciasWrap.contains(e.target)) {
                panelPreferencias.classList.remove('abierto');
                btnPreferencias.classList.remove('activo');
            }
        });

        const escalaWrap = document.getElementById('escala-wrap');
        const btnEscala = document.getElementById('btn-escala');
        const panelEscala = document.getElementById('panel-escala');
        btnEscala.addEventListener('click', e => {
            e.stopPropagation();
            panelEscala.classList.toggle('abierto');
            btnEscala.classList.toggle('activo', panelEscala.classList.contains('abierto'));
        });
        document.addEventListener('click', e => {
            if (!escalaWrap.contains(e.target)) {
                panelEscala.classList.remove('abierto');
                btnEscala.classList.remove('activo');
            }
        });

        /* ─── Actividad: quién hizo qué y cuándo, según el registro que
             arma el backend al comparar cada guardado con el anterior. ─ */
        const actividadWrap = document.getElementById('actividad-wrap');
        const btnActividad = document.getElementById('btn-actividad');
        const panelActividad = document.getElementById('panel-actividad');

        const ACCION_TEXTO = {
            agregar: 'agregó',
            eliminar: 'eliminó',
            mover: 'movió',
            agregar_foto: 'agregó una foto a',
            eliminar_foto: 'eliminó una foto de',
        };

        function mostrarMensajeActividad(texto) {
            panelActividad.innerHTML = '';
            const span = document.createElement('span');
            span.className = 'actividad-vacio';
            span.textContent = texto;
            panelActividad.appendChild(span);
        }

        function cargarActividad() {
            mostrarMensajeActividad('Cargando…');
            fetch(urlActividad, { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(items => {
                    if (!items.length) {
                        mostrarMensajeActividad('Todavía no hay actividad registrada.');
                        return;
                    }
                    panelActividad.innerHTML = '';
                    items.forEach(item => {
                        const fila = document.createElement('div');
                        fila.className = 'actividad-item';

                        const linea = document.createElement('div');
                        const usuarioEl = document.createElement('strong');
                        usuarioEl.textContent = item.usuario;
                        linea.appendChild(usuarioEl);
                        linea.appendChild(document.createTextNode(
                            ' ' + (ACCION_TEXTO[item.accion] || item.accion) + ' ' + (item.detalle || '')
                        ));
                        fila.appendChild(linea);

                        const fecha = document.createElement('div');
                        fecha.className = 'actividad-item-fecha';
                        fecha.textContent = item.fecha || '';
                        fila.appendChild(fecha);

                        panelActividad.appendChild(fila);
                    });
                })
                .catch(() => mostrarMensajeActividad('No se pudo cargar la actividad.'));
        }

        btnActividad.addEventListener('click', e => {
            e.stopPropagation();
            const abrira = !panelActividad.classList.contains('abierto');
            panelActividad.classList.toggle('abierto', abrira);
            btnActividad.classList.toggle('activo', abrira);
            if (abrira) cargarActividad();
        });
        document.addEventListener('click', e => {
            if (!actividadWrap.contains(e.target)) {
                panelActividad.classList.remove('abierto');
                btnActividad.classList.remove('activo');
            }
        });

        [
            ['danos', 'escala-danos', 'escala-danos-valor'],
            ['ensayos', 'escala-ensayos', 'escala-ensayos-valor'],
            ['fotos', 'escala-fotos', 'escala-fotos-valor'],
            ['texto', 'escala-texto', 'escala-texto-valor'],
        ].forEach(([grupo, idSlider, idValor]) => {
            const slider = document.getElementById(idSlider);
            const valor = document.getElementById(idValor);
            slider.addEventListener('input', () => {
                estadoPlano.escalas[grupo] = Number(slider.value) / 100;
                valor.textContent = slider.value + '%';
                redibujarTrazos();
                programarGuardado();
            });
        });

        const inputColorAnotacion = document.getElementById('color-anotacion');
        inputColorAnotacion.addEventListener('input', () => {
            const color = inputColorAnotacion.value;
            HERRAMIENTAS.texto.color = color;
            HERRAMIENTAS.dibujo_libre.color = color;
            HERRAMIENTAS.dibujo_libre_relleno.color = color;
            HERRAMIENTAS.circulo.color = color;
            HERRAMIENTAS.circulo_relleno.color = color;
            HERRAMIENTAS.rectangulo.color = color;
            HERRAMIENTAS.rectangulo_relleno.color = color;
            HERRAMIENTAS.linea_recta.color = color;
        });

        let herramientaActual = 'fisura';

        const wrapsSubmenu = document.querySelectorAll('.tool-submenu-wrap');

        document.querySelectorAll('.tool-btn[data-tool]').forEach(btn => {
            btn.addEventListener('click', () => {
                deseleccionarElemento();
                document.querySelectorAll('.tool-btn').forEach(b => b.classList.remove('activo'));
                btn.classList.add('activo');
                herramientaActual = btn.dataset.tool;

                const wrapPadre = btn.closest('.tool-submenu-wrap');
                wrapsSubmenu.forEach(w => w.classList.toggle('activo', w === wrapPadre));

                if (wrapPadre) {
                    const btnPrincipal = wrapPadre.querySelector(':scope > .tool-btn');
                    const imgOrigen = btn.querySelector('img');
                    const swatchOrigen = btn.querySelector('.tool-swatch');
                    const letraOrigen = btn.querySelector('.tool-icon-letra');
                    const imgPrincipal = btnPrincipal.querySelector('img');
                    const swatchPrincipal = btnPrincipal.querySelector('.tool-swatch');
                    const letraPrincipal = btnPrincipal.querySelector('.tool-icon-letra');

                    if (imgPrincipal) imgPrincipal.style.display = 'none';
                    if (swatchPrincipal) swatchPrincipal.style.display = 'none';
                    if (letraPrincipal) letraPrincipal.style.display = 'none';

                    if (imgOrigen && imgPrincipal) {
                        imgPrincipal.src = imgOrigen.src;
                        imgPrincipal.style.display = '';
                    } else if (letraOrigen && letraPrincipal) {
                        letraPrincipal.textContent = letraOrigen.textContent;
                        letraPrincipal.style.display = '';
                    } else if (swatchOrigen && swatchPrincipal) {
                        swatchPrincipal.style.background = swatchOrigen.style.background;
                        swatchPrincipal.style.display = '';
                    }

                    wrapPadre.querySelector('.submenu-lateral').classList.remove('abierto');
                }
            });
        });

        document.querySelectorAll('.tool-submenu-wrap > .tool-btn').forEach(btnToggle => {
            btnToggle.addEventListener('click', e => {
                e.stopPropagation();
                const submenu = btnToggle.parentElement.querySelector('.submenu-lateral');
                document.querySelectorAll('.submenu-lateral').forEach(s => {
                    if (s !== submenu) s.classList.remove('abierto');
                });
                submenu.classList.toggle('abierto');
            });
        });

        document.addEventListener('click', e => {
            wrapsSubmenu.forEach(wrap => {
                if (!wrap.contains(e.target)) {
                    wrap.querySelector('.submenu-lateral').classList.remove('abierto');
                }
            });
        });

        const lienzoWrap = document.getElementById('lienzo-wrap');
        const lienzo = document.getElementById('lienzo');
        const pdfCanvas = document.getElementById('pdf-canvas');
        const drawCanvas = document.getElementById('draw-canvas');
        const pdfCtx = pdfCanvas.getContext('2d');
        const drawCtx = drawCanvas.getContext('2d');

        /* Estado completo del plano: lo que eventualmente se persiste
           en la base de datos (trazos/íconos dibujados + escalas elegidas). */
        const estadoPlano = {
            trazos: [],
            escalas: { danos: 1, ensayos: 1, fotos: 1, texto: 1 },
        };

        /* Cada elemento dibujado tiene un id estable (asignado al crearlo)
           para poder reconocerlo entre un guardado y el siguiente: así el
           backend puede distinguir "se agregó uno nuevo" de "se movió uno
           existente" al comparar contra lo guardado antes, y armar el
           registro de actividad (quién hizo qué y cuándo). */
        function generarIdElemento() {
            return Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
        }

        /* Devuelve una copia serializable de estadoPlano (sin referencias a
           objetos Image, que no se pueden guardar como JSON) lista para
           enviarse al backend. */
        function serializarEstadoPlano() {
            return {
                escalas: { ...estadoPlano.escalas },
                trazos: estadoPlano.trazos.map(item => {
                    if (item.tipo === 'icono') {
                        return {
                            id: item.id,
                            tipo: 'icono',
                            tool: item.tool,
                            x: item.x,
                            y: item.y,
                            tamano: item.tamano,
                            etiqueta: item.etiqueta ?? null,
                            colorEtiqueta: item.colorEtiqueta ?? null,
                            fotos: item.fotos ?? null,
                        };
                    }
                    if (item.tipo === 'texto') {
                        return {
                            id: item.id,
                            tipo: 'texto',
                            tool: item.tool,
                            x: item.x,
                            y: item.y,
                            texto: item.texto,
                            color: item.color,
                            tamano: item.tamano,
                        };
                    }
                    return {
                        id: item.id,
                        tipo: 'trazo',
                        tool: item.tool,
                        color: item.color,
                        grosor: item.grosor,
                        puntos: item.puntos,
                        cerrado: item.cerrado ?? false,
                        relleno: item.relleno ?? false,
                    };
                }),
            };
        }

        function actualizarSlidersEscala() {
            [
                ['danos', 'escala-danos', 'escala-danos-valor'],
                ['ensayos', 'escala-ensayos', 'escala-ensayos-valor'],
                ['fotos', 'escala-fotos', 'escala-fotos-valor'],
                ['texto', 'escala-texto', 'escala-texto-valor'],
            ].forEach(([grupo, idSlider, idValor]) => {
                const valorPorcentaje = Math.round((estadoPlano.escalas[grupo] ?? 1) * 100);
                document.getElementById(idSlider).value = valorPorcentaje;
                document.getElementById(idValor).textContent = valorPorcentaje + '%';
            });
        }

        /* estadoBase: la última versión de estadoPlano que sabemos que
           coincide con lo guardado en el servidor (arranca con lo que
           había al abrir el plano; se actualiza después de cada guardado
           exitoso). Sirve para calcular qué cambió localmente sin tener
           que mandar el plano entero: solo mandamos operaciones puntuales
           (agregar/mover/borrar), y el servidor las aplica sobre lo que
           haya en ese momento — así, si otra persona guardó algo mientras
           tanto, no se pisan entre sí. */
        let estadoBase = { escalas: {}, trazos: [] };

        /* Si llega una respuesta de guardado justo mientras el usuario
           está dibujando o moviendo algo, aplicarla ahora reemplazaría
           estadoPlano.trazos por debajo del trazo/movimiento en curso
           (que todavía no se guardó) y lo cortaría a mitad de camino. En
           vez de eso, se guarda acá y se aplica apenas termine esa
           acción (ver aplicarEstadoPendienteSiHay). */
        let estadoRecibidoPendiente = null;

        function aplicarEstadoPendienteSiHay() {
            if (estadoRecibidoPendiente && !dibujando && !arrastrandoMover) {
                const pendiente = estadoRecibidoPendiente;
                estadoRecibidoPendiente = null;
                aplicarEstadoRecibido(pendiente);
            }
        }

        /* Reemplaza estadoPlano (y el panel de Capas + sliders de Escala)
           por un estado recibido del servidor: al cargar el plano por
           primera vez, y también después de cada guardado (la respuesta
           trae el estado ya fusionado con lo que hayan guardado otros
           usuarios mientras tanto). */
        function aplicarEstadoRecibido(estadoJson) {
            if (dibujando || arrastrandoMover) {
                estadoRecibidoPendiente = estadoJson;
                return;
            }

            const idSeleccionado = elementoSeleccionado?.id ?? null;

            Object.keys(itemsCapaDom).forEach(tool => {
                itemsCapaDom[tool].btn.remove();
                delete itemsCapaDom[tool];
                delete capasVisibles[tool];
            });
            [grupoCapasDanos, grupoCapasEnsayos, grupoCapasFoto, grupoCapasAnotaciones].forEach(g => { g.style.display = 'none'; });
            panelCapasVacio.style.display = '';

            estadoPlano.trazos = [];

            if (estadoJson?.escalas) {
                Object.assign(estadoPlano.escalas, estadoJson.escalas);
                actualizarSlidersEscala();
            }

            (estadoJson?.trazos || []).forEach(item => {
                if (item.tipo === 'icono') {
                    const nuevoItem = {
                        id: item.id ?? generarIdElemento(),
                        tipo: 'icono',
                        tool: item.tool,
                        imagen: HERRAMIENTAS[item.tool]?.imagen,
                        x: item.x,
                        y: item.y,
                        tamano: item.tamano,
                        etiqueta: item.etiqueta ?? null,
                        colorEtiqueta: item.colorEtiqueta ?? null,
                    };
                    if (item.fotos) nuevoItem.fotos = item.fotos;
                    estadoPlano.trazos.push(nuevoItem);
                } else {
                    estadoPlano.trazos.push({ ...item, id: item.id ?? generarIdElemento() });
                }
                registrarUsoCapa(item.tool);
            });

            estadoBase = serializarEstadoPlano();

            if (idSeleccionado) {
                const encontrado = estadoPlano.trazos.find(t => t.id === idSeleccionado);
                if (encontrado) {
                    elementoSeleccionado = encontrado;
                    if (panelSeleccion.classList.contains('abierto')) mostrarPanelSeleccion();
                } else {
                    deseleccionarElemento();
                }
            }

            redibujarTrazos();
        }

        /* Se llama una sola vez, al cargar el plano. */
        function cargarEstadoGuardado() {
            aplicarEstadoRecibido(estadoGuardado || { escalas: {}, trazos: [] });
        }

        /* Compara estadoPlano contra estadoBase y arma la lista de
           operaciones puntuales a mandar al servidor. */
        function calcularOperacionesPendientes() {
            const actual = serializarEstadoPlano();
            const actualesPorId = new Map(actual.trazos.map(t => [t.id, t]));
            const basePorId = new Map((estadoBase.trazos || []).map(t => [t.id, t]));

            const agregados = [];
            const movidos = [];
            const fotosCambiadas = [];

            actualesPorId.forEach((item, id) => {
                const base = basePorId.get(id);
                if (!base) {
                    agregados.push(item);
                    return;
                }

                const seMovio = item.tipo === 'trazo'
                    ? JSON.stringify(item.puntos) !== JSON.stringify(base.puntos)
                    : (item.x !== base.x || item.y !== base.y);
                if (seMovio) movidos.push(item);

                const fotosBase = base.fotos || [];
                const fotosActuales = item.fotos || [];
                if (fotosActuales.length !== fotosBase.length) {
                    fotosCambiadas.push({
                        id,
                        fotos: fotosActuales,
                        accion: fotosActuales.length > fotosBase.length ? 'agregar_foto' : 'eliminar_foto',
                    });
                }
            });

            const eliminados = [];
            basePorId.forEach((item, id) => {
                if (!actualesPorId.has(id)) eliminados.push(id);
            });

            const escalasCambiaron = JSON.stringify(actual.escalas) !== JSON.stringify(estadoBase.escalas || {});

            return {
                agregados,
                eliminados,
                movidos,
                fotosCambiadas,
                escalas: escalasCambiaron ? actual.escalas : null,
            };
        }

        /* ─── Autoguardado: cada acción (agregar, mover, borrar, cambiar
             una escala) programa un guardado 3s después de la última,
             para no mandar un request por cada movimiento del mouse. Se
             mandan solo las operaciones puntuales (no el plano entero),
             para que el servidor las pueda fusionar con lo que haya
             guardado otra persona mientras tanto en vez de pisarlo. ─ */
        const DEMORA_GUARDADO_MS = 3000;
        let temporizadorGuardado = null;
        let guardadoEnCurso = false;

        function programarGuardado() {
            if (!PUEDE_EDITAR && !PUEDE_ELIMINAR) return;
            clearTimeout(temporizadorGuardado);
            temporizadorGuardado = setTimeout(guardarEstadoPlano, DEMORA_GUARDADO_MS);
        }

        async function guardarEstadoPlano() {
            if (guardadoEnCurso) {
                programarGuardado();
                return;
            }

            /* Si justo en este instante hay un trazo o un movimiento en
               curso, no lo guardamos: mandaríamos una foto a medio hacer
               de estadoPlano, y si la respuesta vuelve después de que esa
               acción terminó, pisaría la versión final con esa parcial.
               Mejor esperar a que termine y reintentar. */
            if (dibujando || arrastrandoMover) {
                programarGuardado();
                return;
            }

            const operaciones = calcularOperacionesPendientes();
            const hayCambios = operaciones.agregados.length || operaciones.eliminados.length ||
                operaciones.movidos.length || operaciones.fotosCambiadas.length || operaciones.escalas;
            if (!hayCambios) return;

            guardadoEnCurso = true;
            try {
                const respuesta = await fetch(urlGuardarEstado, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(operaciones),
                });
                if (!respuesta.ok) {
                    console.warn('No se pudo guardar el plano (HTTP ' + respuesta.status + ')');
                    return;
                }
                const datos = await respuesta.json();
                aplicarEstadoRecibido(datos.estado);
            } catch (e) {
                /* Si falla, el próximo cambio vuelve a programar un
                   guardado; no hay reintento explícito todavía. */
                console.warn('No se pudo guardar el plano', e);
            } finally {
                guardadoEnCurso = false;
            }
        }

        let pdfDoc = null;
        let trazoActual = null;
        let anchoBase = 0, altoBase = 0;
        let factorActual = 0;
        let renderandoNitidez = false;
        let temporizadorNitidez = null;

        /* ─── Vista (pan + zoom infinito) ──────────────────── */
        const vista = { scale: 1, x: 0, y: 0 };

        function clamp(v, min, max) { return Math.min(Math.max(v, min), max); }

        function aplicarTransform() {
            lienzo.style.transform = `translate(${vista.x}px, ${vista.y}px) scale(${vista.scale})`;
            redibujarTrazos();
            posicionarInputTexto();
        }

        function pantallaAMundo(px, py) {
            return { x: (px - vista.x) / vista.scale, y: (py - vista.y) / vista.scale };
        }

        function mundoAPantalla(px, py) {
            return { x: px * vista.scale + vista.x, y: py * vista.scale + vista.y };
        }

        function fijarPuntoEnPantalla(worldX, worldY, screenX, screenY, nuevaScale) {
            vista.scale = clamp(nuevaScale, ZOOM_MIN, ZOOM_MAX);
            vista.x = screenX - worldX * vista.scale;
            vista.y = screenY - worldY * vista.scale;
            aplicarTransform();
            programarRenderNitidez();
        }

        /* ─── Render progresivo: ajusta la resolución del PDF según el zoom ─ */
        function programarRenderNitidez() {
            clearTimeout(temporizadorNitidez);
            temporizadorNitidez = setTimeout(evaluarRenderNitidez, 250);
        }

        function necesitaReajusteNitidez() {
            const factorMaxSeguro = Math.min(6000 / anchoBase, 6000 / altoBase, ZOOM_MAX * SOBREMUESTREO);
            const faltaNitidez = vista.scale > factorActual * 0.9 && factorActual < factorMaxSeguro - 0.01;
            const sobraNitidez = factorActual > SOBREMUESTREO * 1.05 && vista.scale < factorActual / 3;
            return faltaNitidez || sobraNitidez;
        }

        async function evaluarRenderNitidez() {
            if (renderandoNitidez || !pdfDoc) return;

            if (punterosActivos.size > 0) {
                programarRenderNitidez();
                return;
            }

            if (!necesitaReajusteNitidez()) return;

            const factorMaxSeguro = Math.min(6000 / anchoBase, 6000 / altoBase, ZOOM_MAX * SOBREMUESTREO);
            const nuevoFactor = clamp(vista.scale * 1.8, SOBREMUESTREO, factorMaxSeguro);
            if (Math.abs(nuevoFactor - factorActual) > 0.05) {
                await reRenderNitidez(nuevoFactor);
            }

            if (necesitaReajusteNitidez()) programarRenderNitidez();
        }

        async function reRenderNitidez(nuevoFactor) {
            renderandoNitidez = true;
            try {
                const pagina = await pdfDoc.getPage(1);
                const escalaVisible = anchoBase / pagina.getViewport({ scale: 1, rotation: rotacionPlano }).width;
                const viewportRender = pagina.getViewport({ scale: escalaVisible * nuevoFactor, rotation: rotacionPlano });

                pdfCanvas.width = viewportRender.width;
                pdfCanvas.height = viewportRender.height;
                await pagina.render({ canvasContext: pdfCtx, viewport: viewportRender }).promise;

                factorActual = nuevoFactor;
            } finally {
                renderandoNitidez = false;
            }
        }

        /* ─── Capa de dibujo: vive en espacio de pantalla (no se zoomea con el PDF) ─ */
        function ajustarTamanoHud() {
            const anchoCss = lienzoWrap.clientWidth;
            const altoCss = lienzoWrap.clientHeight;
            drawCanvas.width = anchoCss * dpr;
            drawCanvas.height = altoCss * dpr;
            drawCanvas.style.width = anchoCss + 'px';
            drawCanvas.style.height = altoCss + 'px';
            drawCtx.setTransform(dpr, 0, 0, dpr, 0, 0);
            drawCtx.lineCap = 'round';
            drawCtx.lineJoin = 'round';
        }

        function redibujarTrazos() {
            drawCtx.clearRect(0, 0, lienzoWrap.clientWidth, lienzoWrap.clientHeight);

            estadoPlano.trazos.forEach(item => {
                if (capasVisibles[item.tool] === false) return;

                if (item.tipo === 'icono') {
                    if (!item.imagen.complete || !item.imagen.naturalWidth) return;
                    const ratio = item.imagen.naturalWidth / item.imagen.naturalHeight;
                    const factorEscala = estadoPlano.escalas[GRUPO_ESCALA[item.tool]] ?? 1;
                    const base = item.tamano * vista.scale * factorEscala;
                    const anchoPantalla = ratio >= 1 ? base : base * ratio;
                    const altoPantalla = ratio >= 1 ? base / ratio : base;
                    const centro = mundoAPantalla(item.x, item.y);
                    drawCtx.drawImage(
                        item.imagen,
                        centro.x - anchoPantalla / 2,
                        centro.y - altoPantalla / 2,
                        anchoPantalla,
                        altoPantalla
                    );

                    if (item.etiqueta) {
                        const tamanoFuente = base * 0.32;
                        drawCtx.font = `bold ${tamanoFuente}px sans-serif`;
                        drawCtx.textBaseline = 'middle';
                        drawCtx.textAlign = 'left';
                        const textoX = centro.x + anchoPantalla / 2 - base * 0.14;
                        drawCtx.fillStyle = item.colorEtiqueta || '#000';
                        drawCtx.fillText(item.etiqueta, textoX, centro.y);
                    }
                    return;
                }

                if (item.tipo === 'texto') {
                    const centro = mundoAPantalla(item.x, item.y);
                    const grupoEscala = GRUPO_ESCALA[item.tool] ?? 'texto';
                    const tamanoFuente = item.tamano * vista.scale * (estadoPlano.escalas[grupoEscala] ?? 1);
                    drawCtx.font = `600 ${tamanoFuente}px sans-serif`;
                    drawCtx.textBaseline = 'middle';
                    drawCtx.textAlign = 'left';
                    drawCtx.fillStyle = item.color;
                    drawCtx.fillText(item.texto, centro.x, centro.y);
                    return;
                }

                if (item.puntos.length < 2) return;
                const puntosPantalla = item.puntos.map(p => mundoAPantalla(p.x, p.y));
                const path = new Path2D();
                path.moveTo(puntosPantalla[0].x, puntosPantalla[0].y);
                for (let i = 1; i < puntosPantalla.length; i++) {
                    path.lineTo(puntosPantalla[i].x, puntosPantalla[i].y);
                }

                if (item.cerrado) {
                    path.closePath();
                    if (item.relleno) dibujarTramaDiagonal(item, puntosPantalla, path);
                }

                drawCtx.strokeStyle = item.color;
                drawCtx.lineWidth = item.grosor * vista.scale;
                drawCtx.stroke(path);
            });

            dibujarResaltadoSeleccion();
            if (elementoSeleccionado && panelSeleccion.classList.contains('abierto')) {
                posicionarPanelSeleccion();
            }
        }

        function dibujarTramaDiagonal(item, puntosPantalla, path) {
            const xs = puntosPantalla.map(p => p.x);
            const ys = puntosPantalla.map(p => p.y);
            const minX = Math.min(...xs), maxX = Math.max(...xs);
            const minY = Math.min(...ys), maxY = Math.max(...ys);
            const centroX = (minX + maxX) / 2;
            const centroY = (minY + maxY) / 2;
            const diagonal = Math.hypot(maxX - minX, maxY - minY) || 1;
            const espaciado = Math.max(1.5, ESPACIO_TRAMA * vista.scale);

            drawCtx.save();
            drawCtx.clip(path);
            drawCtx.translate(centroX, centroY);
            drawCtx.rotate(Math.PI / 4);
            drawCtx.strokeStyle = item.color;
            drawCtx.lineWidth = Math.max(0.5, item.grosor * vista.scale * 0.5);
            drawCtx.globalAlpha = 0.75;
            drawCtx.beginPath();
            for (let x = -diagonal; x <= diagonal; x += espaciado) {
                drawCtx.moveTo(x, -diagonal);
                drawCtx.lineTo(x, diagonal);
            }
            drawCtx.stroke();
            drawCtx.restore();
        }

        /* ─── Selección: bounding box en pantalla de cualquier elemento
             (ícono, texto o trazo), usado para el resaltado y para
             ubicar el panel de Mover/Eliminar. ─ */
        function calcularBBoxPantalla(item) {
            if (item.tipo === 'icono') {
                const ratio = item.imagen && item.imagen.naturalWidth
                    ? item.imagen.naturalWidth / item.imagen.naturalHeight : 1;
                const factorEscala = estadoPlano.escalas[GRUPO_ESCALA[item.tool]] ?? 1;
                const base = item.tamano * vista.scale * factorEscala;
                const ancho = ratio >= 1 ? base : base * ratio;
                const alto = ratio >= 1 ? base / ratio : base;
                const centro = mundoAPantalla(item.x, item.y);
                return { minX: centro.x - ancho / 2, maxX: centro.x + ancho / 2, minY: centro.y - alto / 2, maxY: centro.y + alto / 2 };
            }

            if (item.tipo === 'texto') {
                const grupoEscala = GRUPO_ESCALA[item.tool] ?? 'texto';
                const tamanoFuente = item.tamano * vista.scale * (estadoPlano.escalas[grupoEscala] ?? 1);
                drawCtx.font = `600 ${tamanoFuente}px sans-serif`;
                const ancho = drawCtx.measureText(item.texto).width;
                const centro = mundoAPantalla(item.x, item.y);
                return { minX: centro.x, maxX: centro.x + ancho, minY: centro.y - tamanoFuente / 2, maxY: centro.y + tamanoFuente / 2 };
            }

            if (!item.puntos || !item.puntos.length) return null;
            const puntosPantalla = item.puntos.map(p => mundoAPantalla(p.x, p.y));
            const xs = puntosPantalla.map(p => p.x);
            const ys = puntosPantalla.map(p => p.y);
            return { minX: Math.min(...xs), maxX: Math.max(...xs), minY: Math.min(...ys), maxY: Math.max(...ys) };
        }

        function dibujarResaltadoSeleccion() {
            if (!elementoSeleccionado) return;
            const bbox = calcularBBoxPantalla(elementoSeleccionado);
            if (!bbox) return;
            const pad = 8;
            drawCtx.save();
            drawCtx.strokeStyle = '#2a6fdb';
            drawCtx.lineWidth = 2;
            drawCtx.setLineDash([6, 4]);
            drawCtx.strokeRect(bbox.minX - pad, bbox.minY - pad, (bbox.maxX - bbox.minX) + pad * 2, (bbox.maxY - bbox.minY) + pad * 2);
            drawCtx.restore();
        }

        window.addEventListener('resize', () => {
            ajustarTamanoHud();
            redibujarTrazos();
        });

        function zoomEn(factor, screenX, screenY) {
            const mundo = pantallaAMundo(screenX, screenY);
            fijarPuntoEnPantalla(mundo.x, mundo.y, screenX, screenY, vista.scale * factor);
        }

        function centrarVista() {
            const anchoWrap = lienzoWrap.clientWidth;
            const altoWrap = lienzoWrap.clientHeight;
            const anchoDisponible = Math.min(anchoWrap - 48, 1400);
            vista.scale = clamp(anchoDisponible / anchoBase, ZOOM_MIN, ZOOM_MAX);
            const anchoEscalado = anchoBase * vista.scale;
            const altoEscalado = altoBase * vista.scale;
            vista.x = Math.max((anchoWrap - anchoEscalado) / 2, 0);
            vista.y = altoEscalado < altoWrap ? (altoWrap - altoEscalado) / 2 : 24;
            aplicarTransform();
            programarRenderNitidez();
        }

        lienzoWrap.addEventListener('wheel', e => {
            e.preventDefault();
            const rect = lienzoWrap.getBoundingClientRect();
            const factor = Math.pow(1.0015, -e.deltaY);
            zoomEn(factor, e.clientX - rect.left, e.clientY - rect.top);
        }, { passive: false });

        /* ─── Render de la página del PDF (documento de una sola página) ─ */
        async function renderPagina() {
            clearTimeout(temporizadorNitidez);
            const pagina = await pdfDoc.getPage(1);
            const viewportBase = pagina.getViewport({ scale: 1, rotation: rotacionPlano });
            const viewportRender = pagina.getViewport({ scale: SOBREMUESTREO, rotation: rotacionPlano });

            /* anchoBase/altoBase (y por lo tanto la posición "mundo" de
               cada elemento dibujado) se miden en puntos del PDF, un
               tamaño fijo del documento — no en píxeles de pantalla, que
               varían según el dispositivo. Así, algo dibujado en una
               tablet cae en el mismo lugar al abrir el plano en una
               computadora. El ajuste a cada tamaño de pantalla lo hace
               vista.scale (ver centrarVista), no anchoBase/altoBase. */
            anchoBase = viewportBase.width;
            altoBase = viewportBase.height;

            pdfCanvas.width = viewportRender.width;
            pdfCanvas.height = viewportRender.height;
            pdfCanvas.style.width = anchoBase + 'px';
            pdfCanvas.style.height = altoBase + 'px';

            await pagina.render({ canvasContext: pdfCtx, viewport: viewportRender }).promise;

            factorActual = SOBREMUESTREO;
            estadoPlano.trazos = [];
            deseleccionarElemento();
            cargarEstadoGuardado();
            centrarVista();
        }

        async function cargarPdf() {
            ajustarTamanoHud();
            pdfDoc = await pdfjsLib.getDocument(urlPdf).promise;
            await renderPagina();
        }

        /* ─── Dibujo y pellizco (mouse, lápiz y táctil) ───────
             Un puntero dibuja; dos puntero hacen zoom + paneo.
             En táctil, el primer dedo espera un instante antes de
             confirmar el trazo/ícono, por si llega un segundo dedo
             (pellizco) unos milisegundos más tarde. ─ */
        const punterosActivos = new Map();
        let dibujando = false;
        let pinchInfo = null;
        let punteroPendiente = null;
        const RETRASO_CONFIRMACION_TACTIL = 150;

        function distancia(p1, p2) { return Math.hypot(p1.x - p2.x, p1.y - p2.y); }

        function posicionPantalla(e) {
            const rect = lienzoWrap.getBoundingClientRect();
            return { x: e.clientX - rect.left, y: e.clientY - rect.top };
        }

        function cancelarPunteroPendiente() {
            if (punteroPendiente) {
                clearTimeout(punteroPendiente.temporizador);
                punteroPendiente = null;
            }
        }

        /* ─── Anotación de texto: input flotante posicionado sobre el
             punto clickeado, en vez de un prompt() desconectado del plano. ─ */
        const inputTextoFlotante = document.getElementById('input-texto-flotante');
        let edicionTextoPendiente = null;

        function posicionarInputTexto() {
            if (!edicionTextoPendiente) return;
            const herramienta = HERRAMIENTAS.texto;
            const centro = mundoAPantalla(edicionTextoPendiente.x, edicionTextoPendiente.y);
            const tamanoFuente = herramienta.tamano * vista.scale * (estadoPlano.escalas.texto ?? 1);
            inputTextoFlotante.style.left = centro.x + 'px';
            inputTextoFlotante.style.top = centro.y + 'px';
            inputTextoFlotante.style.fontSize = tamanoFuente + 'px';
            inputTextoFlotante.style.color = herramienta.color;
        }

        function abrirInputTexto(mundo) {
            edicionTextoPendiente = mundo;
            inputTextoFlotante.value = '';
            inputTextoFlotante.style.display = 'block';
            posicionarInputTexto();
            /* El foco se difiere: si se llama en el mismo pointerdown/mousedown
               con el que se abrió, el navegador se lo roba de nuevo apenas
               termina el evento (el lienzo no es un elemento enfocable), y el
               input se cierra solo por el blur antes de poder escribir. */
            setTimeout(() => inputTextoFlotante.focus(), 0);
        }

        function cerrarInputTexto(confirmar) {
            if (!edicionTextoPendiente) return;
            const texto = inputTextoFlotante.value.trim();
            const mundo = edicionTextoPendiente;
            edicionTextoPendiente = null;
            inputTextoFlotante.style.display = 'none';
            inputTextoFlotante.blur();

            if (confirmar && texto) {
                const herramienta = HERRAMIENTAS.texto;
                registrarUsoCapa('texto');
                estadoPlano.trazos.push({ id: generarIdElemento(), tipo: 'texto', tool: 'texto', x: mundo.x, y: mundo.y, texto, color: herramienta.color, tamano: herramienta.tamano });
                redibujarTrazos();
                programarGuardado();
            }
        }

        inputTextoFlotante.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); cerrarInputTexto(true); }
            else if (e.key === 'Escape') { e.preventDefault(); cerrarInputTexto(false); }
        });
        inputTextoFlotante.addEventListener('blur', () => cerrarInputTexto(true));

        /* ─── Fotografía: pin + cámara + vista previa ─────── */
        const inputFoto = document.getElementById('input-foto');
        const overlayFoto = document.getElementById('overlay-foto');
        const overlayFotoImg = document.getElementById('overlay-foto-img');
        const overlayFotoCerrar = document.getElementById('overlay-foto-cerrar');
        const overlayFotoPrev = document.getElementById('overlay-foto-prev');
        const overlayFotoNext = document.getElementById('overlay-foto-next');
        const overlayFotoContador = document.getElementById('overlay-foto-contador');
        const overlayFotoAgregar = document.getElementById('overlay-foto-agregar');
        const overlayFotoEliminar = document.getElementById('overlay-foto-eliminar');

        /* contextoFotoPendiente distingue si el selector de archivos se
           abrió para crear un pin nuevo (modo 'nuevo') o para sumar más
           fotos a un pin ya existente (modo 'agregar'). */
        let contextoFotoPendiente = null;
        let fotoAbiertaItem = null;
        let fotoAbiertaIndice = 0;

        /* ─── Formas de arrastre (círculo/rectángulo): se recalculan
             en cada movimiento a partir del punto inicial y el actual. ─ */
        function puntosRectangulo(a, b) {
            return [
                { x: a.x, y: a.y },
                { x: b.x, y: a.y },
                { x: b.x, y: b.y },
                { x: a.x, y: b.y },
            ];
        }

        function puntosCirculo(centro, borde, segmentos = 48) {
            const radio = Math.hypot(borde.x - centro.x, borde.y - centro.y);
            const puntos = [];
            for (let i = 0; i < segmentos; i++) {
                const angulo = (i / segmentos) * Math.PI * 2;
                puntos.push({ x: centro.x + radio * Math.cos(angulo), y: centro.y + radio * Math.sin(angulo) });
            }
            return puntos;
        }

        function buscarFotoEnPunto(mundo) {
            const margenPantalla = 12;
            for (let i = estadoPlano.trazos.length - 1; i >= 0; i--) {
                const item = estadoPlano.trazos[i];
                if (item.tool !== 'foto' || capasVisibles[item.tool] === false) continue;
                const radioMundo = item.tamano / 2 + margenPantalla / vista.scale;
                if (Math.hypot(item.x - mundo.x, item.y - mundo.y) <= radioMundo) return item;
            }
            return null;
        }

        function solicitarFoto(mundo) {
            contextoFotoPendiente = { modo: 'nuevo', mundo };
            inputFoto.value = '';
            inputFoto.click();
        }

        function solicitarAgregarFotos(item) {
            contextoFotoPendiente = { modo: 'agregar', item };
            inputFoto.value = '';
            inputFoto.click();
        }

        /* Las fotos se suben al servidor como archivo (igual que los PDF
           de los planos) y en estadoPlano solo se guarda la URL resultante,
           en vez de la imagen codificada en base64. */
        function subirFotoAlServidor(archivo) {
            const formData = new FormData();
            formData.append('foto', archivo);
            return fetch(urlSubirFoto, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: formData,
            }).then(r => {
                if (!r.ok) throw new Error('No se pudo subir la foto');
                return r.json();
            }).then(data => data.url);
        }

        inputFoto.addEventListener('change', () => {
            const archivos = Array.from(inputFoto.files || []);
            const contexto = contextoFotoPendiente;
            contextoFotoPendiente = null;
            if (!archivos.length || !contexto) return;

            Promise.all(archivos.map(subirFotoAlServidor)).then(urls => {
                if (contexto.modo === 'nuevo') {
                    registrarUsoCapa('foto');
                    estadoPlano.trazos.push({
                        id: generarIdElemento(),
                        tipo: 'icono',
                        tool: 'foto',
                        imagen: HERRAMIENTAS.foto.imagen,
                        x: contexto.mundo.x,
                        y: contexto.mundo.y,
                        tamano: HERRAMIENTAS.foto.tamano,
                        etiqueta: null,
                        fotos: urls,
                    });
                    redibujarTrazos();
                } else {
                    contexto.item.fotos.push(...urls);
                    fotoAbiertaIndice = contexto.item.fotos.length - urls.length;
                    actualizarOverlayFoto();
                }
                programarGuardado();
            }).catch(() => {
                alert('No se pudo subir la foto. Probá de nuevo.');
            });
        });
        inputFoto.addEventListener('cancel', () => { contextoFotoPendiente = null; });

        function actualizarOverlayFoto() {
            if (!fotoAbiertaItem) return;
            const fotos = fotoAbiertaItem.fotos;
            if (!fotos.length) { cerrarFotoGrande(); return; }
            fotoAbiertaIndice = clamp(fotoAbiertaIndice, 0, fotos.length - 1);
            overlayFotoImg.src = fotos[fotoAbiertaIndice];
            const multiples = fotos.length > 1;
            overlayFotoContador.textContent = multiples ? `${fotoAbiertaIndice + 1} / ${fotos.length}` : '';
            overlayFotoPrev.style.display = multiples ? 'flex' : 'none';
            overlayFotoNext.style.display = multiples ? 'flex' : 'none';
        }

        function mostrarFotoEnGrande(item) {
            fotoAbiertaItem = item;
            fotoAbiertaIndice = 0;
            actualizarOverlayFoto();
            overlayFoto.classList.add('abierto');
        }

        function cerrarFotoGrande() {
            overlayFoto.classList.remove('abierto');
            overlayFotoImg.src = '';
            fotoAbiertaItem = null;
        }

        overlayFotoCerrar.addEventListener('click', cerrarFotoGrande);
        overlayFoto.addEventListener('click', e => {
            if (e.target === overlayFoto) cerrarFotoGrande();
        });

        overlayFotoPrev.addEventListener('click', () => {
            if (!fotoAbiertaItem) return;
            const total = fotoAbiertaItem.fotos.length;
            fotoAbiertaIndice = (fotoAbiertaIndice - 1 + total) % total;
            actualizarOverlayFoto();
        });

        overlayFotoNext.addEventListener('click', () => {
            if (!fotoAbiertaItem) return;
            fotoAbiertaIndice = (fotoAbiertaIndice + 1) % fotoAbiertaItem.fotos.length;
            actualizarOverlayFoto();
        });

        overlayFotoAgregar.addEventListener('click', () => {
            if (!PUEDE_EDITAR) return;
            if (fotoAbiertaItem) solicitarAgregarFotos(fotoAbiertaItem);
        });

        overlayFotoEliminar.addEventListener('click', () => {
            if (!PUEDE_ELIMINAR) return;
            if (!fotoAbiertaItem) return;
            fotoAbiertaItem.fotos.splice(fotoAbiertaIndice, 1);
            if (!fotoAbiertaItem.fotos.length) {
                const idx = estadoPlano.trazos.indexOf(fotoAbiertaItem);
                if (idx !== -1) estadoPlano.trazos.splice(idx, 1);
                cerrarFotoGrande();
                quitarCapaSiVacia('foto');
                redibujarTrazos();
                programarGuardado();
                return;
            }
            actualizarOverlayFoto();
            programarGuardado();
        });

        /* ─── Selección: elegir un elemento ya dibujado para moverlo o
             eliminarlo. Mientras esta herramienta está activa, toma el
             control completo del puntero sobre el lienzo (no dibuja). ─ */
        const panelSeleccion = document.getElementById('panel-seleccion');
        const btnSeleccionMover = document.getElementById('btn-seleccion-mover');
        const btnSeleccionEliminar = document.getElementById('btn-seleccion-eliminar');

        let elementoSeleccionado = null;
        let modoMover = false;
        let arrastrandoMover = false;
        let arrastreMoverInicio = null;
        let arrastreMoverOrigen = null;

        function distanciaPuntoSegmento(p, a, b) {
            const dx = b.x - a.x, dy = b.y - a.y;
            const largoSq = dx * dx + dy * dy;
            if (largoSq === 0) return Math.hypot(p.x - a.x, p.y - a.y);
            const t = clamp(((p.x - a.x) * dx + (p.y - a.y) * dy) / largoSq, 0, 1);
            return Math.hypot(p.x - (a.x + t * dx), p.y - (a.y + t * dy));
        }

        function puntoEnPoligono(p, puntos) {
            let dentro = false;
            for (let i = 0, j = puntos.length - 1; i < puntos.length; j = i++) {
                const xi = puntos[i].x, yi = puntos[i].y;
                const xj = puntos[j].x, yj = puntos[j].y;
                const interseca = ((yi > p.y) !== (yj > p.y)) &&
                    (p.x < (xj - xi) * (p.y - yi) / (yj - yi) + xi);
                if (interseca) dentro = !dentro;
            }
            return dentro;
        }

        function buscarElementoEnPunto(mundo) {
            const margenPantalla = 12;
            for (let i = estadoPlano.trazos.length - 1; i >= 0; i--) {
                const item = estadoPlano.trazos[i];
                if (capasVisibles[item.tool] === false) continue;

                if (item.tipo === 'icono') {
                    const factorEscala = estadoPlano.escalas[GRUPO_ESCALA[item.tool]] ?? 1;
                    const radioMundo = (item.tamano * factorEscala) / 2 + margenPantalla / vista.scale;
                    if (Math.hypot(item.x - mundo.x, item.y - mundo.y) <= radioMundo) return item;
                    continue;
                }

                if (item.tipo === 'texto') {
                    const bbox = calcularBBoxPantalla(item);
                    if (!bbox) continue;
                    const punto = mundoAPantalla(mundo.x, mundo.y);
                    if (punto.x >= bbox.minX - margenPantalla && punto.x <= bbox.maxX + margenPantalla &&
                        punto.y >= bbox.minY - margenPantalla && punto.y <= bbox.maxY + margenPantalla) return item;
                    continue;
                }

                if (!item.puntos || item.puntos.length < 2) continue;
                const umbralMundo = Math.max(8, (item.grosor || 0.2) * vista.scale * 4) / vista.scale;
                let cerca = false;
                for (let j = 0; j < item.puntos.length - 1 && !cerca; j++) {
                    if (distanciaPuntoSegmento(mundo, item.puntos[j], item.puntos[j + 1]) <= umbralMundo) cerca = true;
                }
                if (!cerca && item.cerrado && item.puntos.length > 2 && puntoEnPoligono(mundo, item.puntos)) cerca = true;
                if (cerca) return item;
            }
            return null;
        }

        function mostrarPanelSeleccion() {
            panelSeleccion.classList.add('abierto');
            posicionarPanelSeleccion();
        }

        function ocultarPanelSeleccion() {
            panelSeleccion.classList.remove('abierto');
        }

        function posicionarPanelSeleccion() {
            if (!elementoSeleccionado) return;
            const bbox = calcularBBoxPantalla(elementoSeleccionado);
            if (!bbox) { ocultarPanelSeleccion(); return; }
            const cx = (bbox.minX + bbox.maxX) / 2;
            panelSeleccion.style.left = cx + 'px';
            panelSeleccion.style.top = Math.max(bbox.minY - 14, 12) + 'px';
        }

        function seleccionarElemento(item) {
            elementoSeleccionado = item;
            modoMover = false;
            btnSeleccionMover.classList.remove('activo');
            if (item) mostrarPanelSeleccion(); else ocultarPanelSeleccion();
            redibujarTrazos();
        }

        function deseleccionarElemento() {
            elementoSeleccionado = null;
            modoMover = false;
            arrastrandoMover = false;
            btnSeleccionMover.classList.remove('activo');
            ocultarPanelSeleccion();
        }

        function eliminarElementoSeleccionado() {
            if (!PUEDE_ELIMINAR) return;
            if (!elementoSeleccionado) return;
            const tool = elementoSeleccionado.tool;
            const idx = estadoPlano.trazos.indexOf(elementoSeleccionado);
            if (idx !== -1) estadoPlano.trazos.splice(idx, 1);
            deseleccionarElemento();
            quitarCapaSiVacia(tool);
            redibujarTrazos();
            programarGuardado();
        }

        function iniciarArrastreMover(mundo) {
            arrastrandoMover = true;
            arrastreMoverInicio = mundo;
            arrastreMoverOrigen = elementoSeleccionado.puntos
                ? elementoSeleccionado.puntos.map(p => ({ x: p.x, y: p.y }))
                : { x: elementoSeleccionado.x, y: elementoSeleccionado.y };
            ocultarPanelSeleccion();
        }

        function moverElementoSeleccionado(mundo) {
            if (!elementoSeleccionado || !arrastreMoverInicio) return;
            const dx = mundo.x - arrastreMoverInicio.x;
            const dy = mundo.y - arrastreMoverInicio.y;
            if (elementoSeleccionado.puntos) {
                elementoSeleccionado.puntos = arrastreMoverOrigen.map(p => ({ x: p.x + dx, y: p.y + dy }));
            } else {
                elementoSeleccionado.x = arrastreMoverOrigen.x + dx;
                elementoSeleccionado.y = arrastreMoverOrigen.y + dy;
            }
            redibujarTrazos();
        }

        function finalizarArrastreMover() {
            arrastrandoMover = false;
            modoMover = false;
            btnSeleccionMover.classList.remove('activo');
            if (elementoSeleccionado) mostrarPanelSeleccion();
            programarGuardado();
            aplicarEstadoPendienteSiHay();
        }

        function manejarClickSeleccion(mundo) {
            if (modoMover && elementoSeleccionado) {
                iniciarArrastreMover(mundo);
                return;
            }
            seleccionarElemento(buscarElementoEnPunto(mundo));
        }

        btnSeleccionMover.addEventListener('click', () => {
            if (!PUEDE_EDITAR) return;
            if (!elementoSeleccionado) return;
            modoMover = !modoMover;
            btnSeleccionMover.classList.toggle('activo', modoMover);
        });

        btnSeleccionEliminar.addEventListener('click', eliminarElementoSeleccionado);

        /* Busca el menor número consecutivo libre para un ícono/texto
           numerado (E1, E2, F1, F2, ...), a partir de lo que sigue
           dibujado en el plano y del número inicial elegido en el panel
           de Preferencias. Así, al borrar un elemento del medio, el
           próximo que se inserte rellena ese hueco en vez de saltar
           siempre al último número usado. */
        function siguienteNumeroLibre(tool, prefijo) {
            const usados = new Set();
            estadoPlano.trazos.forEach(item => {
                if (item.tool !== tool) return;
                const etiquetaTexto = item.etiqueta ?? item.texto;
                if (typeof etiquetaTexto !== 'string' || !etiquetaTexto.startsWith(prefijo)) return;
                const numero = parseInt(etiquetaTexto.slice(prefijo.length), 10);
                if (Number.isInteger(numero)) usados.add(numero);
            });
            let numero = numeroInicial[tool] ?? 1;
            while (usados.has(numero)) numero++;
            return numero;
        }

        function iniciarAccionPuntero(puntosMundo) {
            const mundoPunto = puntosMundo[puntosMundo.length - 1];

            if (herramientaActual === 'seleccion') {
                manejarClickSeleccion(mundoPunto);
                return;
            }

            const fotoExistente = buscarFotoEnPunto(mundoPunto);
            if (fotoExistente) {
                mostrarFotoEnGrande(fotoExistente);
                return;
            }

            /* Ver los daños/ensayos/fotos ya cargados siempre está permitido
               (líneas arriba); a partir de acá todo crea o modifica algo,
               así que requiere permiso de edición. */
            if (!PUEDE_EDITAR) return;

            if (herramientaActual === 'foto') {
                solicitarFoto(mundoPunto);
                return;
            }

            const herramienta = HERRAMIENTAS[herramientaActual];

            if (herramienta.tipo === 'texto') {
                abrirInputTexto(mundoPunto);
                return;
            }

            registrarUsoCapa(herramientaActual);

            if (herramienta.tipo === 'icono') {
                const prefijo = PREFIJOS_ENSAYO[herramientaActual];
                let etiqueta = null;
                if (prefijo) {
                    etiqueta = prefijo + siguienteNumeroLibre(herramientaActual, prefijo);
                }
                estadoPlano.trazos.push({ id: generarIdElemento(), tipo: 'icono', tool: herramientaActual, imagen: herramienta.imagen, x: mundoPunto.x, y: mundoPunto.y, tamano: herramienta.tamano, etiqueta, colorEtiqueta: COLORES_ENSAYO[herramientaActual] });
                redibujarTrazos();
                programarGuardado();
            } else if (herramienta.tipo === 'texto_contador') {
                const texto = herramienta.prefijo + siguienteNumeroLibre(herramientaActual, herramienta.prefijo);
                estadoPlano.trazos.push({ id: generarIdElemento(), tipo: 'texto', tool: herramientaActual, x: mundoPunto.x, y: mundoPunto.y, texto, color: herramienta.color, tamano: herramienta.tamano });
                redibujarTrazos();
                programarGuardado();
            } else if (herramienta.tipo === 'linea') {
                dibujando = true;
                trazoActual = { id: generarIdElemento(), tipo: 'trazo', tool: herramientaActual, color: herramienta.color, grosor: herramienta.grosor, puntos: [puntosMundo[0], mundoPunto] };
                estadoPlano.trazos.push(trazoActual);
                redibujarTrazos();
            } else if (herramienta.tipo === 'circulo' || herramienta.tipo === 'rectangulo') {
                dibujando = true;
                const generarPuntos = herramienta.tipo === 'circulo' ? puntosCirculo : puntosRectangulo;
                trazoActual = {
                    id: generarIdElemento(),
                    tipo: 'trazo',
                    tool: herramientaActual,
                    color: herramienta.color,
                    grosor: herramienta.grosor,
                    relleno: herramienta.relleno,
                    puntoInicio: mundoPunto,
                    puntos: generarPuntos(mundoPunto, mundoPunto),
                    cerrado: true,
                };
                estadoPlano.trazos.push(trazoActual);
                redibujarTrazos();
            } else {
                dibujando = true;
                trazoActual = { id: generarIdElemento(), tipo: 'trazo', tool: herramientaActual, color: herramienta.color, grosor: herramienta.grosor, puntos: [...puntosMundo] };
                estadoPlano.trazos.push(trazoActual);
                drawCtx.strokeStyle = herramienta.color;
                drawCtx.lineWidth = herramienta.grosor * vista.scale;
                drawCtx.beginPath();
                puntosMundo.forEach((p, i) => {
                    const ps = mundoAPantalla(p.x, p.y);
                    if (i === 0) drawCtx.moveTo(ps.x, ps.y); else drawCtx.lineTo(ps.x, ps.y);
                });
                if (puntosMundo.length > 1) drawCtx.stroke();
            }
        }

        lienzoWrap.addEventListener('pointerdown', e => {
            if (e.target === inputTextoFlotante) return;
            if (panelSeleccion.contains(e.target)) return;
            if (edicionTextoPendiente) cerrarInputTexto(true);
            lienzoWrap.setPointerCapture(e.pointerId);
            punterosActivos.set(e.pointerId, { x: e.clientX, y: e.clientY });

            if (punterosActivos.size === 2) {
                cancelarPunteroPendiente();
                dibujando = false;
                trazoActual = null;
                if (arrastrandoMover) finalizarArrastreMover();
                aplicarEstadoPendienteSiHay();
                const pts = Array.from(punterosActivos.values());
                const rect = lienzoWrap.getBoundingClientRect();
                const cx = (pts[0].x + pts[1].x) / 2 - rect.left;
                const cy = (pts[0].y + pts[1].y) / 2 - rect.top;
                pinchInfo = {
                    distInicial: distancia(pts[0], pts[1]),
                    scaleInicial: vista.scale,
                    mundo: pantallaAMundo(cx, cy),
                };
            } else if (punterosActivos.size === 1) {
                const pantalla = posicionPantalla(e);
                const mundo = pantallaAMundo(pantalla.x, pantalla.y);

                if (e.pointerType === 'touch') {
                    punteroPendiente = {
                        pointerId: e.pointerId,
                        puntos: [mundo],
                        temporizador: setTimeout(() => {
                            if (!punteroPendiente || punterosActivos.size !== 1) return;
                            const pendiente = punteroPendiente;
                            punteroPendiente = null;
                            iniciarAccionPuntero(pendiente.puntos);
                        }, RETRASO_CONFIRMACION_TACTIL),
                    };
                } else {
                    iniciarAccionPuntero([mundo]);
                }
            }
        });

        lienzoWrap.addEventListener('pointermove', e => {
            if (!punterosActivos.has(e.pointerId)) return;
            punterosActivos.set(e.pointerId, { x: e.clientX, y: e.clientY });

            if (punterosActivos.size >= 2 && pinchInfo) {
                const pts = Array.from(punterosActivos.values()).slice(0, 2);
                const distActual = distancia(pts[0], pts[1]);
                const rect = lienzoWrap.getBoundingClientRect();
                const cx = (pts[0].x + pts[1].x) / 2 - rect.left;
                const cy = (pts[0].y + pts[1].y) / 2 - rect.top;
                const nuevaScale = pinchInfo.scaleInicial * (distActual / pinchInfo.distInicial);
                fijarPuntoEnPantalla(pinchInfo.mundo.x, pinchInfo.mundo.y, cx, cy, nuevaScale);
                return;
            }

            if (arrastrandoMover) {
                const pantalla = posicionPantalla(e);
                moverElementoSeleccionado(pantallaAMundo(pantalla.x, pantalla.y));
                return;
            }

            if (punteroPendiente && punteroPendiente.pointerId === e.pointerId) {
                const pantalla = posicionPantalla(e);
                const mundo = pantallaAMundo(pantalla.x, pantalla.y);
                punteroPendiente.puntos.push(mundo);
                return;
            }

            if (dibujando) {
                const pantalla = posicionPantalla(e);
                const mundo = pantallaAMundo(pantalla.x, pantalla.y);
                const tipoHerramienta = HERRAMIENTAS[herramientaActual].tipo;
                if (tipoHerramienta === 'linea') {
                    trazoActual.puntos[1] = mundo;
                    redibujarTrazos();
                } else if (tipoHerramienta === 'circulo' || tipoHerramienta === 'rectangulo') {
                    const generarPuntos = tipoHerramienta === 'circulo' ? puntosCirculo : puntosRectangulo;
                    trazoActual.puntos = generarPuntos(trazoActual.puntoInicio, mundo);
                    redibujarTrazos();
                } else {
                    trazoActual.puntos.push(mundo);
                    drawCtx.lineTo(pantalla.x, pantalla.y);
                    drawCtx.stroke();
                }
            }
        });

        function finalizarPuntero(e) {
            if (arrastrandoMover) {
                punterosActivos.delete(e.pointerId);
                finalizarArrastreMover();
                return;
            }

            if (punteroPendiente && punteroPendiente.pointerId === e.pointerId) {
                clearTimeout(punteroPendiente.temporizador);
                const pendiente = punteroPendiente;
                punteroPendiente = null;
                punterosActivos.delete(e.pointerId);
                if (e.type === 'pointerup' && punterosActivos.size === 0) {
                    iniciarAccionPuntero(pendiente.puntos);
                }
            } else {
                punterosActivos.delete(e.pointerId);
            }

            if (dibujando && trazoActual && trazoActual.puntos.length > 3 && HERRAMIENTAS[herramientaActual]?.cierreAutomatico) {
                const inicio = trazoActual.puntos[0];
                trazoActual.puntos.push({ x: inicio.x, y: inicio.y });
                trazoActual.cerrado = true;
                trazoActual.relleno = true;
                redibujarTrazos();
            }

            if (dibujando && trazoActual) programarGuardado();

            dibujando = false;
            trazoActual = null;
            pinchInfo = null;
            aplicarEstadoPendienteSiHay();
        }
        ['pointerup', 'pointercancel', 'pointerleave'].forEach(evt =>
            lienzoWrap.addEventListener(evt, finalizarPuntero)
        );

        cargarPdf();
    </script>
</body>
</html>
