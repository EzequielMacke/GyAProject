<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $plano->descripcion ?? 'Plano' }}</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2a6fdb">
    <link rel="apple-touch-icon" href="{{ asset('img/pwa/icon-192.png') }}">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{{ asset('sw.js') }}');
            });
        }
    </script>
    @if(request()->boolean('debug'))
    {{-- Consola de depuración en pantalla (ver partials/head.blade.php). Esta
         página no incluye ese partial (es un editor liviano sin jQuery/
         Bootstrap), así que se repite acá para poder diagnosticar en el
         propio celular la subida de fotos. Solo carga con ?debug=1. --}}
    <script src="https://cdn.jsdelivr.net/npm/eruda"></script>
    <script>eruda.init();</script>
    @endif
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

        .estado-guardado {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 0.7rem;
            font-size: 0.78rem; font-weight: 500; color: #999;
        }
        .estado-guardado-punto {
            width: 7px; height: 7px; border-radius: 50%; background: #666;
            flex-shrink: 0;
        }
        .estado-guardado.pendiente .estado-guardado-punto { background: #d9a441; }
        .estado-guardado.guardando .estado-guardado-punto { background: #2a6fdb; }
        .estado-guardado.error .estado-guardado-punto { background: #c0392b; }
        .estado-guardado.error { color: #e07a6f; }
        .estado-guardado.local .estado-guardado-punto { background: #d9a441; }
        .estado-guardado.local { color: #d9a441; }

        /* En pantallas angostas (celular en vertical) la barra no entra
           en una sola fila: se apila en columna, pegada a la esquina
           superior derecha. */
        @media (max-width: 700px) {
            .barra-superior-derecha {
                /* column-reverse para que el orden de arriba hacia abajo
                   coincida con el de derecha a izquierda en horizontal
                   (Volver arriba de todo, Guardado al final). */
                flex-direction: column-reverse;
                align-items: flex-end;
                top: 8px; right: 8px;
                gap: 0.35rem;
            }
            .btn-superior {
                padding: 0.4rem 0.7rem;
                font-size: 0.76rem;
            }
            .estado-guardado {
                padding: 0.4rem 0.6rem;
            }
        }

        .capas-wrap, .escala-wrap, .preferencias-wrap, .actividad-wrap, .pendientes-wrap { position: relative; }

        .badge-pendientes {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 16px; height: 16px; padding: 0 4px;
            border-radius: 999px; background: #c0392b; color: #fff;
            font-size: 0.66rem; font-weight: 700; line-height: 1;
        }

        .panel-pendientes {
            position: absolute; top: calc(100% + 0.4rem); right: 0;
            background: #222; border-radius: 0.55rem; padding: 0.6rem;
            display: none; flex-direction: column; gap: 0.3rem;
            min-width: 260px; max-height: 70vh; overflow-y: auto;
            box-shadow: 0 4px 16px rgba(0,0,0,0.4);
        }
        .panel-pendientes.abierto { display: flex; }
        .pendiente-item {
            display: flex; align-items: center; justify-content: space-between;
            gap: 0.6rem;
            padding: 0.4rem 0.4rem;
            border-bottom: 1px solid #333;
            font-size: 0.78rem; color: #ddd;
        }
        .pendiente-item:last-child { border-bottom: none; }
        .pendiente-item-cantidad { color: #d9a441; font-weight: 700; flex-shrink: 0; }
        .pendiente-vacio { color: #888; font-size: 0.78rem; padding: 0.4rem; }

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

        /* ── DESCARGA (modal de opciones PDF/PNG) ── */
        .overlay-descarga {
            position: fixed; inset: 0; z-index: 50;
            background: rgba(0,0,0,0.6);
            display: none; align-items: center; justify-content: center;
            padding: 2rem;
        }
        .overlay-descarga.abierto { display: flex; }
        .overlay-descarga-contenido {
            width: min(320px, 90vw);
            background: #222; border-radius: 0.6rem; padding: 1.1rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.5);
            display: flex; flex-direction: column; gap: 0.9rem;
        }
        .overlay-descarga-titulo {
            color: #fff; font-size: 0.95rem; font-weight: 700;
        }
        .overlay-descarga-opciones {
            display: flex; gap: 0.5rem;
        }
        .overlay-descarga-formato {
            flex: 1;
            display: flex; flex-direction: column; align-items: center; gap: 0.45rem;
            background: #2f2f2f; border: 2px solid transparent; cursor: pointer;
            color: #ccc; font-size: 0.78rem; font-weight: 600; font-family: inherit;
            padding: 0.9rem 0.5rem; border-radius: 0.5rem;
        }
        .overlay-descarga-formato:hover { background: #3a3a3a; }
        .overlay-descarga-formato.activo { border-color: #2a6fdb; color: #fff; background: #24344d; }
        .overlay-descarga-acciones {
            display: flex; justify-content: flex-end; gap: 0.5rem;
        }
        .overlay-descarga-confirmar { background: #2a6fdb; color: #fff; }
        .overlay-descarga-confirmar:hover { background: #3a7fe8; }
        .overlay-descarga-confirmar:disabled { background: #2f4a73; cursor: default; }
        .overlay-descarga-referencia {
            display: flex; align-items: center; gap: 0.5rem;
            color: #ccc; font-size: 0.82rem; font-weight: 500; cursor: pointer;
        }
        .overlay-descarga-referencia input { accent-color: #2a6fdb; width: 16px; height: 16px; cursor: pointer; }

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
        <div class="estado-guardado" id="estado-guardado" @if(!$puedeEditar && !$puedeEliminar) style="display:none" @endif>
            <span class="estado-guardado-punto"></span>
            <span class="estado-guardado-texto">Guardado</span>
        </div>
        <div class="pendientes-wrap" id="pendientes-wrap" @if(!$puedeEditar && !$puedeEliminar) style="display:none" @endif>
            <button type="button" class="btn-superior" id="btn-pendientes">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                Pendientes
                <span class="badge-pendientes" id="badge-pendientes" style="display:none">0</span>
            </button>
            <div class="panel-pendientes" id="panel-pendientes"></div>
        </div>
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
                    <input type="range" id="escala-danos" min="10" max="400" step="5" value="100">
                </div>
                <div class="escala-item">
                    <div class="escala-item-cabecera">
                        <span>Ensayos</span>
                        <span class="escala-item-valor" id="escala-ensayos-valor">100%</span>
                    </div>
                    <input type="range" id="escala-ensayos" min="10" max="400" step="5" value="100">
                </div>
                <div class="escala-item">
                    <div class="escala-item-cabecera">
                        <span>Fotos</span>
                        <span class="escala-item-valor" id="escala-fotos-valor">100%</span>
                    </div>
                    <input type="range" id="escala-fotos" min="10" max="400" step="5" value="100">
                </div>
                <div class="escala-item">
                    <div class="escala-item-cabecera">
                        <span>Texto</span>
                        <span class="escala-item-valor" id="escala-texto-valor">100%</span>
                    </div>
                    <input type="range" id="escala-texto" min="10" max="400" step="5" value="100">
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
        <div class="descarga-wrap" id="descarga-wrap">
            <button type="button" class="btn-superior" id="btn-descarga">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Descargar
            </button>
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
            <button type="button" class="tool-btn" data-tool="seleccion_multiple" title="Selección múltiple">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" stroke-dasharray="4 3"></rect>
                    <circle cx="8" cy="8" r="1.3" fill="currentColor" stroke="none"></circle>
                    <circle cx="16" cy="16" r="1.3" fill="currentColor" stroke="none"></circle>
                </svg>
                Selección múltiple
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
            <div class="panel-seleccion" id="panel-seleccion-multiple">
                <button type="button" class="panel-seleccion-btn" id="btn-multi-mover" @if(!$puedeEditar) style="display:none" @endif>Mover</button>
                <button type="button" class="panel-seleccion-btn borrar" id="btn-multi-eliminar" @if(!$puedeEliminar) style="display:none" @endif>Eliminar (<span id="multi-cantidad">0</span>)</button>
            </div>
        </div>
    </div>

    <input type="file" accept="image/*" capture="environment" multiple id="input-foto" style="display:none">
    <input type="file" accept="image/*" multiple id="input-foto-galeria" style="display:none">

    <div class="overlay-descarga" id="overlay-origen-foto">
        <div class="overlay-descarga-contenido">
            <span class="overlay-descarga-titulo">Agregar fotografía</span>
            <div class="overlay-descarga-opciones">
                <button type="button" class="overlay-descarga-formato" id="btn-origen-camara">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                        <circle cx="12" cy="13" r="4"></circle>
                    </svg>
                    Cámara
                </button>
                <button type="button" class="overlay-descarga-formato" id="btn-origen-galeria">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Galería
                </button>
            </div>
            <div class="overlay-descarga-acciones">
                <button type="button" class="overlay-foto-accion" id="btn-origen-cancelar">Cancelar</button>
            </div>
        </div>
    </div>

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

    <div class="overlay-descarga" id="overlay-descarga">
        <div class="overlay-descarga-contenido">
            <span class="overlay-descarga-titulo">Descargar plano</span>
            <div class="overlay-descarga-opciones">
                <button type="button" class="overlay-descarga-formato activo" data-formato="pdf">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    PDF
                </button>
                <button type="button" class="overlay-descarga-formato" data-formato="png">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    PNG
                </button>
            </div>
            <label class="overlay-descarga-referencia">
                <input type="checkbox" id="check-descarga-referencia">
                Con referencia
            </label>
            <div class="overlay-descarga-acciones">
                <button type="button" class="overlay-foto-accion" id="btn-descarga-cancelar">Cancelar</button>
                <button type="button" class="overlay-foto-accion overlay-descarga-confirmar" id="btn-descarga-confirmar">Descargar</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>
    <script src="{{ asset('js/plano-offline.js') }}"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const urlPdf = @json(Storage::url('planos/' . $plano->archivo));
        const rotacionPlano = {{ (int) ($plano->rotacion ?? 0) }};
        const nombrePlanoBase = @json($plano->descripcion ?? 'plano');

        /* Permisos del módulo "ano_pla" (Anotaciones - Planos), calculados
           en el backend (PlanoController::show). "ver" ya está garantizado
           por el middleware de la ruta con solo llegar a esta vista. */
        const PUEDE_EDITAR = @json($puedeEditar);
        const PUEDE_ELIMINAR = @json($puedeEliminar);

        /* Guardado en la base de datos: el estado ya guardado (si lo hay),
           las URLs de los endpoints y el token CSRF para poder mandar los
           fetch() de guardado/subida de fotos. */
        const PLANO_ID = {{ (int) $plano->id }};
        /* Si public/js/plano-offline.js no llegó a cargar (por ejemplo,
           un dispositivo que se queda sin señal justo antes de que ese
           script se cachee por primera vez), la app tiene que poder
           seguir funcionando en modo "solo online" en vez de romperse
           entera. Referenciar PlanoOffline directo tira ReferenceError
           si el script no cargó (el optional chaining "?." no protege
           contra un identificador que no existe, solo contra que valga
           null/undefined); por eso todo el resto del código usa esta
           referencia local, que sí es segura. */
        const OfflineAPI = window.PlanoOffline || null;
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
        /* Ancho/alto máximo (en px) del canvas del PDF al hacer zoom (ver
           calcularFactorMaxSeguro). En dispositivos con dpr > 1 este tope
           se queda corto para zooms moderados (un plano A3 a dpr 1.5 y
           zoom 5x ya necesita ~9100px de ancho para verse nítido) y se ve
           pixelado antes de llegar al zoom máximo — PERO se probó subirlo
           a 9200 y colgó una tablet real (canvas RGBA: el costo en
           memoria crece con el cuadrado del lado, ~240MB solo para este
           canvas a 9200px). 6000 es el máximo verificado como seguro en
           el hardware real que usa esta app; el pixelado a partir de
           cierto zoom en tablets de dpr alto es el costo aceptado de esa
           seguridad, no subir sin volver a probar en el dispositivo real. */
        const RESOLUCION_MAXIMA_CANVAS_PX = 6000;

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

        /* Datos para la tabla "Referencia" de la descarga (ver checkbox
           "Con referencia"): nombre + ícono de cada daño/ensayo, en el
           mismo orden en que aparecen en la barra de herramientas.
           `vectorial: true` marca los íconos ya normalizados (sin <g>/
           transform) que el exportador de PDF puede dibujar como
           trazos vectoriales reales (igual que hace con los que van
           sobre el plano); los de `vectorial: false` (los daños que se
           dibujan como trazo, no como ícono estampado) traen <g>/
           transform que ese parser liviano no soporta, así que en el
           PDF se insertan rasterizados (ver rasterizarIconoAPng). En el
           PNG de descarga no hace falta esta distinción: el canvas
           dibuja cualquier SVG correctamente sea cual sea su estructura. */
        const REFERENCIA_DANOS = [
            ...DANOS.map(({ tool, nombre, url }) => ({ tool, nombre, url, vectorial: false })),
            ...DANOS_ICONO.map(({ tool, nombre, url }) => ({ tool, nombre, url, vectorial: true })),
        ];
        const REFERENCIA_ENSAYOS = [
            ...ENSAYOS.map(({ tool, nombre, prefijo, url }) => ({ tool, nombre, letra: prefijo, url, vectorial: true })),
            { tool: 'computo_fisuras', nombre: 'Cómputo de fisuras', letra: 'F', url: null, vectorial: false },
        ];

        /* Solo entran a la referencia los tipos que: (a) tienen al menos
           un elemento dibujado en el plano y (b) su capa está prendida
           (capasVisibles) — así "referencia con lo que se ve", igual
           que el criterio que ya usa dibujarItems() para decidir qué
           pintar. */
        function categoriasReferenciaActivas() {
            const toolsUsados = new Set(estadoPlano.trazos.map(t => t.tool));
            const activo = tool => toolsUsados.has(tool) && capasVisibles[tool] !== false;

            const categorias = [];
            const filasDanos = REFERENCIA_DANOS.filter(d => activo(d.tool));
            if (filasDanos.length) categorias.push({ columnas: 2, filas: filasDanos });

            const filasEnsayos = REFERENCIA_ENSAYOS.filter(e => activo(e.tool));
            if (filasEnsayos.length) categorias.push({ columnas: 3, filas: filasEnsayos });

            return categorias;
        }

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

        /* ─── Pendientes: todo lo que todavía no se confirmó contra el
             servidor (ni se guardó localmente como "ya sincronizado"),
             para que se pueda ver de un vistazo qué falta subir sin tener
             que adivinarlo por el estado genérico "Cambios sin guardar".
             Se apoya en calcularOperacionesPendientes(true) (mismo cálculo
             que ya usa el autoguardado), incluyendo las fotos que solo
             viven como blob local ('local:<id>') porque todavía no
             terminaron de subirse. ─ */
        const pendientesWrap = document.getElementById('pendientes-wrap');
        const btnPendientes = document.getElementById('btn-pendientes');
        const panelPendientes = document.getElementById('panel-pendientes');
        const badgePendientes = document.getElementById('badge-pendientes');

        function calcularResumenPendientes() {
            const operaciones = calcularOperacionesPendientes(true);
            const basePorId = new Map((estadoBase.trazos || []).map(t => [t.id, t]));
            const conteos = new Map();
            const sumar = (etiqueta, cantidad = 1) => conteos.set(etiqueta, (conteos.get(etiqueta) || 0) + cantidad);
            const nombreDe = tool => metaCapas[tool]?.nombre || tool;

            operaciones.agregados.forEach(item => sumar(`${nombreDe(item.tool)} pendiente`));
            operaciones.movidos.forEach(item => sumar(`${nombreDe(item.tool)} modificada, pendiente`));
            operaciones.eliminados.forEach(id => {
                const base = basePorId.get(id);
                sumar(`${base ? nombreDe(base.tool) : 'Elemento'} eliminada, pendiente`);
            });

            let fotosNuevas = 0, fotosBorradas = 0;
            operaciones.fotosCambiadas.forEach(cambio => {
                fotosNuevas += cambio.fotosAgregadas.length;
                fotosBorradas += cambio.fotosEliminadas.length;
            });
            if (fotosNuevas) sumar('Foto pendiente', fotosNuevas);
            if (fotosBorradas) sumar('Eliminación de foto pendiente', fotosBorradas);

            if (operaciones.escalas) sumar('Cambio de escala pendiente');

            const items = Array.from(conteos, ([etiqueta, cantidad]) => ({ etiqueta, cantidad }));
            return { items, total: items.reduce((acc, i) => acc + i.cantidad, 0) };
        }

        function actualizarPendientes() {
            const { items, total } = calcularResumenPendientes();

            if (badgePendientes) {
                badgePendientes.style.display = total ? '' : 'none';
                badgePendientes.textContent = total > 99 ? '99+' : String(total);
            }

            panelPendientes.innerHTML = '';
            if (!items.length) {
                const vacio = document.createElement('span');
                vacio.className = 'pendiente-vacio';
                vacio.textContent = 'No hay cambios sin guardar.';
                panelPendientes.appendChild(vacio);
                return;
            }
            items.forEach(({ etiqueta, cantidad }) => {
                const fila = document.createElement('div');
                fila.className = 'pendiente-item';
                const texto = document.createElement('span');
                texto.textContent = etiqueta;
                const num = document.createElement('span');
                num.className = 'pendiente-item-cantidad';
                num.textContent = '×' + cantidad;
                fila.append(texto, num);
                panelPendientes.appendChild(fila);
            });
        }

        btnPendientes?.addEventListener('click', e => {
            e.stopPropagation();
            const abrira = !panelPendientes.classList.contains('abierto');
            if (abrira) actualizarPendientes();
            panelPendientes.classList.toggle('abierto', abrira);
            btnPendientes.classList.toggle('activo', abrira);
        });
        document.addEventListener('click', e => {
            if (pendientesWrap && !pendientesWrap.contains(e.target)) {
                panelPendientes.classList.remove('abierto');
                btnPendientes.classList.remove('activo');
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
                deseleccionarMultiple();
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
           enviarse al backend.

           `incluirPendientesLocales`: las fotos recién tomadas offline
           viven primero como blobs en IndexedDB y se referencian en
           item.fotos con el prefijo 'local:' hasta que se suben de
           verdad (ver PlanoOffline / manejarArchivosFoto). Esas
           referencias son solo para persistencia LOCAL (para sobrevivir
           un cierre de la app antes de reconectar) y nunca deben viajar
           al servidor: si se colara una 'local:<id>' en el payload de
           guardarEstado, quedaría escrita tal cual en la base de datos.
           Por eso el valor por defecto (usado por todo lo que va a la
           red: guardado y estadoBase) las filtra; solo el guardado local
           en IndexedDB pide incluirlas, para no perder el pin al recargar. */
        function serializarEstadoPlano(incluirPendientesLocales = false) {
            return {
                escalas: { ...estadoPlano.escalas },
                trazos: estadoPlano.trazos.map(item => {
                    if (item.tipo === 'icono') {
                        let fotos = item.fotos ? [...item.fotos] : null;
                        if (!incluirPendientesLocales && fotos) {
                            fotos = fotos.filter(f => !f.startsWith('local:'));
                            if (fotos.length === 0) fotos = null;
                        }
                        return {
                            id: item.id,
                            tipo: 'icono',
                            tool: item.tool,
                            x: item.x,
                            y: item.y,
                            tamano: item.tamano,
                            etiqueta: item.etiqueta ?? null,
                            colorEtiqueta: item.colorEtiqueta ?? null,
                            /* Copia del array, no la misma referencia: item.fotos
                               se edita con push/splice (in-place). Si acá se
                               guardara la misma referencia, esa mutación
                               ensuciaría también a estadoBase (la última
                               versión confirmada por el servidor), y el
                               siguiente cálculo de operaciones pendientes
                               compararía el array contra sí mismo — nunca
                               detectaría el cambio y la edición de fotos no
                               se guardaría. */
                            fotos,
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
            if (estadoRecibidoPendiente && !dibujando && !arrastrandoMover && !arrastrandoMoverMultiple && !dibujandoRectangulo) {
                const pendiente = estadoRecibidoPendiente;
                estadoRecibidoPendiente = null;
                aplicarEstadoRecibido(pendiente);
            }
        }

        function crearItemDesdeGuardado(item) {
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
                if (item.tool === 'foto') nuevoItem.fotos = item.fotos || [];
                else if (item.fotos) nuevoItem.fotos = item.fotos;
                return nuevoItem;
            }
            return { ...item, id: item.id ?? generarIdElemento() };
        }

        function aplicarCamposRecibidos(item, nuevo) {
            if (item.tipo === 'icono') {
                item.x = nuevo.x;
                item.y = nuevo.y;
                item.tamano = nuevo.tamano;
                item.etiqueta = nuevo.etiqueta ?? null;
                item.colorEtiqueta = nuevo.colorEtiqueta ?? null;
                if (nuevo.fotos) {
                    /* No pisar sin más: si hay fotos 'local:<id>' todavía
                       subiéndose en segundo plano (ver dispararSubidaFotosPendientes),
                       el estado que acaba de confirmar el servidor todavía
                       no las conoce (subirFoto y guardarEstado son dos
                       pedidos separados). Perder esa referencia acá hace
                       que, cuando la subida termine, no encuentre dónde
                       poner la URL real — el archivo queda subido pero
                       huérfano, sin vincular a ningún ícono. */
                    const pendientesLocales = (item.fotos || []).filter(f => f.startsWith('local:'));
                    item.fotos = pendientesLocales.length ? [...nuevo.fotos, ...pendientesLocales] : nuevo.fotos;
                } else if (item.tool === 'foto' && !item.fotos) {
                    item.fotos = [];
                }
            } else if (item.tipo === 'texto') {
                item.x = nuevo.x;
                item.y = nuevo.y;
                item.texto = nuevo.texto;
                item.color = nuevo.color;
                item.tamano = nuevo.tamano;
            } else {
                item.puntos = nuevo.puntos;
                item.color = nuevo.color;
                item.grosor = nuevo.grosor;
                item.cerrado = nuevo.cerrado ?? false;
                item.relleno = nuevo.relleno ?? false;
            }
        }

        /* Sincroniza el panel de capas contra estadoPlano.trazos SIN
           destruir y recrear todo: eso hacía que, tras cada guardado
           automático, todas las capas volvieran a "prenderse" (perdían
           el ocultar/mostrar que el usuario había elegido) y parpadearan.
           registrarUsoCapa/quitarCapaSiVacia ya son no-op si la capa no
           cambió, así que solo tocan el DOM de lo que realmente entró o
           quedó vacío. */
        function sincronizarPanelCapas() {
            const toolsActuales = new Set(estadoPlano.trazos.map(item => item.tool));

            Object.keys(itemsCapaDom).forEach(tool => {
                if (!toolsActuales.has(tool)) quitarCapaSiVacia(tool);
            });

            toolsActuales.forEach(tool => registrarUsoCapa(tool));
        }

        /* Fusiona un estado recibido del servidor (al cargar el plano por
           primera vez, y también después de cada guardado) con lo que hay
           en pantalla — SIN reemplazar estadoPlano.trazos entero. Los
           elementos que siguen existiendo se actualizan en el mismo
           objeto, conservando su referencia: así, cualquier cosa que la
           tenga guardada mientras tanto (una foto subiéndose a un pin, el
           elemento seleccionado, etc.) no queda huérfana ni se pierde el
           cambio que estaba por aplicar. */
        function aplicarEstadoRecibido(estadoJson) {
            if (dibujando || arrastrandoMover || arrastrandoMoverMultiple || dibujandoRectangulo) {
                estadoRecibidoPendiente = estadoJson;
                return;
            }

            const nuevosPorId = new Map((estadoJson?.trazos || []).map(item => [item.id, item]));
            const baseAnteriorPorId = new Map((estadoBase.trazos || []).map(item => [item.id, item]));

            for (let i = estadoPlano.trazos.length - 1; i >= 0; i--) {
                const item = estadoPlano.trazos[i];
                const nuevo = nuevosPorId.get(item.id);

                if (nuevo) {
                    aplicarCamposRecibidos(item, nuevo);
                    continue;
                }

                if (baseAnteriorPorId.has(item.id)) {
                    /* El servidor lo conocía y ya no lo tiene: lo borró
                       otro usuario. */
                    estadoPlano.trazos.splice(i, 1);
                    if (elementoSeleccionado === item) deseleccionarElemento();
                    if (seleccionMultiple.includes(item)) {
                        seleccionMultiple = seleccionMultiple.filter(it => it !== item);
                    }
                } /* si no, es algo local recién creado que todavía no se
                     guardó — no se toca. */
            }

            const idsActuales = new Set(estadoPlano.trazos.map(t => t.id));
            (estadoJson?.trazos || []).forEach(item => {
                if (idsActuales.has(item.id)) return;
                estadoPlano.trazos.push(crearItemDesdeGuardado(item));
            });

            if (estadoJson?.escalas) {
                Object.assign(estadoPlano.escalas, estadoJson.escalas);
                actualizarSlidersEscala();
            }

            sincronizarPanelCapas();
            estadoBase = serializarEstadoPlano();

            if (fotoAbiertaItem) actualizarOverlayFoto();
            if (elementoSeleccionado && panelSeleccion.classList.contains('abierto')) mostrarPanelSeleccion();
            if (seleccionMultiple.length && panelSeleccionMultiple.classList.contains('abierto')) {
                multiCantidadEl.textContent = seleccionMultiple.length;
                mostrarPanelSeleccionMultiple();
            } else if (!seleccionMultiple.length) {
                ocultarPanelSeleccionMultiple();
            }

            redibujarTrazos();
        }

        /* Se llama una sola vez, al cargar el plano. */
        function cargarEstadoGuardado() {
            aplicarEstadoRecibido(estadoGuardado || { escalas: {}, trazos: [] });
        }

        /* Reaplica sobre el estado recién cargado del servidor lo que
           haya quedado pendiente de sincronizar en IndexedDB (por falta
           de conexión, o porque se cerró la app antes de que terminara
           de guardar). Se llama después de cargarEstadoGuardado(), así
           que estadoPlano ya tiene la versión más reciente confirmada.

           A propósito reaplica el diff completo (agregados/movidos/
           eliminados/fotos), no solo "agregar lo que falte": lo pendiente
           acá es SOLO lo que nunca se confirmó (ver persistirLocalmenteLoPendiente
           y el comentario de estado_local en plano-offline.js), así que
           si algo ya se había sincronizado antes y después se borró desde
           otro dispositivo, no hay riesgo de "revivirlo" — simplemente no
           va a estar en este diff, porque se limpió/actualizó apenas se
           confirmó el guardado la primera vez. */
        async function fusionarEstadoLocalPendiente() {
            const operaciones = await OfflineAPI?.leerOperacionesLocal(PLANO_ID);
            if (!operaciones) return;

            let huboCambios = false;

            (operaciones.agregados || []).forEach(item => {
                if (estadoPlano.trazos.some(t => t.id === item.id)) return; // se sincronizó justo antes de cerrar
                estadoPlano.trazos.push(crearItemDesdeGuardado(item));
                huboCambios = true;
            });

            (operaciones.movidos || []).forEach(nuevo => {
                const item = estadoPlano.trazos.find(t => t.id === nuevo.id);
                if (!item) return; // ya no existe (lo borraron desde otro dispositivo)
                aplicarCamposRecibidos(item, nuevo);
                huboCambios = true;
            });

            (operaciones.eliminados || []).forEach(id => {
                const idx = estadoPlano.trazos.findIndex(t => t.id === id);
                if (idx === -1) return;
                estadoPlano.trazos.splice(idx, 1);
                huboCambios = true;
            });

            (operaciones.fotosCambiadas || []).forEach(cambio => {
                const item = estadoPlano.trazos.find(t => t.id === cambio.id);
                if (!item) return;
                const fotosEliminadas = cambio.fotosEliminadas || [];
                const base = (item.fotos || []).filter(f => !fotosEliminadas.includes(f));
                item.fotos = [...new Set([...base, ...(cambio.fotosAgregadas || [])])];
                huboCambios = true;
            });

            if (operaciones.escalas) {
                Object.assign(estadoPlano.escalas, operaciones.escalas);
                actualizarSlidersEscala();
                huboCambios = true;
            }

            if (!huboCambios) return;

            sincronizarPanelCapas();
            redibujarTrazos();
            programarGuardado();
        }

        /* Compara estadoPlano contra estadoBase y arma la lista de
           operaciones puntuales a mandar al servidor. */
        function calcularOperacionesPendientes(incluirPendientesLocales = false) {
            const actual = serializarEstadoPlano(incluirPendientesLocales);
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

                /* Se manda qué URLs se agregaron/sacaron (no el array
                   completo): si mandáramos el array completo, un
                   dispositivo con el estado desactualizado (p. ej. el PC
                   sin sincronizar mientras se sube una foto desde el
                   celular) pisaría en el servidor las fotos que agregó
                   el otro dispositivo. Con el diff, el servidor aplica
                   el cambio sobre su propio estado más reciente. */
                const fotosBase = base.fotos || [];
                const fotosActuales = item.fotos || [];
                const fotosAgregadas = fotosActuales.filter(url => !fotosBase.includes(url));
                const fotosEliminadas = fotosBase.filter(url => !fotosActuales.includes(url));
                if (fotosAgregadas.length || fotosEliminadas.length) {
                    fotosCambiadas.push({ id, fotosAgregadas, fotosEliminadas });
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
             una escala) programa un guardado 15s después de la última
             acción (se reinicia el temporizador en cada una), para
             guardar recién cuando el usuario se queda quieto y no
             interrumpir lo que está haciendo. Se mandan solo las
             operaciones puntuales (no el plano entero), para que el
             servidor las pueda fusionar con lo que haya guardado otra
             persona mientras tanto en vez de pisarlo. ─ */
        const DEMORA_GUARDADO_MS = 15000;
        let temporizadorGuardado = null;
        let guardadoEnCurso = false;

        const elEstadoGuardado = document.getElementById('estado-guardado');
        const elEstadoGuardadoTexto = elEstadoGuardado?.querySelector('.estado-guardado-texto');
        const TEXTOS_ESTADO_GUARDADO = {
            guardado: 'Guardado',
            pendiente: 'Cambios sin guardar',
            guardando: 'Guardando…',
            error: 'Error al guardar',
            local: 'Guardado en el dispositivo — se sincronizará al recuperar conexión',
        };
        function fijarEstadoGuardado(estado) {
            if (!elEstadoGuardado) return;
            elEstadoGuardado.className = 'estado-guardado ' + estado;
            elEstadoGuardadoTexto.textContent = TEXTOS_ESTADO_GUARDADO[estado];
        }

        /* Deja en IndexedDB una copia de lo que TODAVÍA no está confirmado
           por el servidor (no una copia del estado completo — ver el
           comentario de estado_local en plano-offline.js). Se llama tanto
           al programar un guardado como después de uno exitoso: en ese
           segundo caso puede parecer redundante, pero no lo es, porque
           justo después de guardar puede seguir habiendo algo pendiente
           de verdad (una foto que todavía se está subiendo en segundo
           plano) — así la copia local siempre refleja lo que realmente
           falta, ni más ni menos. */
        function persistirLocalmenteLoPendiente() {
            OfflineAPI?.guardarOperacionesLocal(PLANO_ID, calcularOperacionesPendientes(true)).catch(() => {});
        }

        function programarGuardado() {
            if (!PUEDE_EDITAR && !PUEDE_ELIMINAR) return;
            fijarEstadoGuardado('pendiente');
            persistirLocalmenteLoPendiente();
            actualizarPendientes();
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
            if (dibujando || arrastrandoMover || arrastrandoMoverMultiple || dibujandoRectangulo) {
                programarGuardado();
                return;
            }

            const operaciones = calcularOperacionesPendientes();
            const hayCambios = operaciones.agregados.length || operaciones.eliminados.length ||
                operaciones.movidos.length || operaciones.fotosCambiadas.length || operaciones.escalas;
            if (!hayCambios) {
                fijarEstadoGuardado('guardado');
                actualizarPendientes();
                return;
            }

            guardadoEnCurso = true;
            fijarEstadoGuardado('guardando');
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
                    /* Hubo respuesta del servidor (llegó y volvió), así que
                       la conexión anda: esto es un error real (sesión
                       vencida, permisos, etc.), no un tema de señal. */
                    console.warn('No se pudo guardar el plano (HTTP ' + respuesta.status + ')');
                    fijarEstadoGuardado('error');
                    return;
                }
                const datos = await respuesta.json();
                aplicarEstadoRecibido(datos.estado);
                fijarEstadoGuardado('guardado');
                persistirLocalmenteLoPendiente();
            } catch (e) {
                /* El fetch ni siquiera llegó a completarse: es un problema
                   de conexión real, sin importar lo que diga
                   navigator.onLine (ese valor no es confiable — solo
                   refleja si el dispositivo está asociado a alguna red,
                   no si esa red tiene salida a internet de verdad; una
                   tablet con wifi débil puede seguir "online" para el
                   navegador aunque ningún pedido llegue a destino). Ya
                   quedó a salvo en IndexedDB (programarGuardado lo
                   persiste localmente antes de programar este guardado);
                   el reintento periódico de OfflineAPI.iniciarReintentos()
                   y el evento 'online' se encargan de reintentar sin que
                   el usuario tenga que hacer nada. */
                console.warn('No se pudo guardar el plano', e);
                fijarEstadoGuardado('local');
            } finally {
                guardadoEnCurso = false;
                actualizarPendientes();
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
            solicitarRedibujado();
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

        /* Tope de resolución del canvas del PDF (en "px por punto de PDF"),
           para no pedirle al navegador un canvas gigante que haga crashear
           una tablet (ver RESOLUCION_MAXIMA_CANVAS_PX). Tiene que ser el MISMO tope que
           usa el render inicial (ver renderPagina): si el inicial pudiera
           superarlo sin chequearlo, factorActual arrancaría ya por encima
           de este límite y necesitaReajusteNitidez() jamás volvería a
           disparar un re-render (su condición exige factorActual por
           DEBAJO del tope) — el plano quedaría congelado en la nitidez
           inicial para siempre, sin importar cuánto zoom se haga. Esto es
           más probable cuanto más grande es la hoja del plano (anchoBase/
           altoBase grandes achican el tope) y más alto el dpr del
           dispositivo (agranda SOBREMUESTREO) — el combo típico de una
           tablet con un plano tipo A1/A0. */
        function calcularFactorMaxSeguro() {
            return Math.min(RESOLUCION_MAXIMA_CANVAS_PX / anchoBase, RESOLUCION_MAXIMA_CANVAS_PX / altoBase, ZOOM_MAX * SOBREMUESTREO);
        }

        function necesitaReajusteNitidez() {
            const factorMaxSeguro = calcularFactorMaxSeguro();
            /* factorActual es "px de canvas por punto de PDF"; para que se
               vea nítido en pantalla tiene que cubrir tanto el zoom actual
               como la densidad de píxeles del dispositivo (dpr) — si acá
               solo se compara contra vista.scale, en una tablet con dpr 2-3
               el re-render dispara con menos resolución de la que hace
               falta y el plano queda más borroso después de zoomear que
               antes (perdiendo el colchón de dpr que sí tenía el render
               inicial, ver SOBREMUESTREO). */
            const factorNecesario = vista.scale * dpr;
            const faltaNitidez = factorNecesario > factorActual * 0.9 && factorActual < factorMaxSeguro - 0.01;
            const sobraNitidez = factorActual > SOBREMUESTREO * 1.05 && factorNecesario < factorActual / 3;
            return faltaNitidez || sobraNitidez;
        }

        async function evaluarRenderNitidez() {
            if (renderandoNitidez || !pdfDoc) return;

            if (punterosActivos.size > 0) {
                programarRenderNitidez();
                return;
            }

            if (!necesitaReajusteNitidez()) return;

            const factorMaxSeguro = calcularFactorMaxSeguro();
            const nuevoFactor = clamp(vista.scale * dpr * 1.8, SOBREMUESTREO, factorMaxSeguro);
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

        /* En tablets, pointermove llega con mucha más frecuencia de la que
           la pantalla puede pintar frames nuevos. Sin esto, cada evento
           dispara un redibujado completo (recorre todas las anotaciones),
           y la mayoría nunca llega a mostrarse — solo se ve el último
           antes de cada frame real. Agrupando en requestAnimationFrame,
           como mucho se redibuja una vez por frame. Se usa en los puntos
           que se disparan durante un gesto (pellizco de zoom, arrastrar
           un elemento, previsualizar línea/círculo/rectángulo); el resto
           de los llamados a redibujarTrazos() son por acciones puntuales
           (un click, una foto subida) y no lo necesitan. */
        let redibujadoPendiente = false;
        function solicitarRedibujado() {
            if (redibujadoPendiente) return;
            redibujadoPendiente = true;
            requestAnimationFrame(() => {
                redibujadoPendiente = false;
                redibujarTrazos();
            });
        }

        function redibujarTrazos() {
            drawCtx.clearRect(0, 0, lienzoWrap.clientWidth, lienzoWrap.clientHeight);
            dibujarItems(drawCtx, mundoAPantalla, vista.scale);
            dibujarResaltadoSeleccion();
            if (elementoSeleccionado && panelSeleccion.classList.contains('abierto')) {
                posicionarPanelSeleccion();
            }
            if (seleccionMultiple.length && panelSeleccionMultiple.classList.contains('abierto')) {
                posicionarPanelSeleccionMultiple();
            }
        }

        /* Motor de dibujo compartido entre la vista en pantalla
           (drawCtx + mundoAPantalla + vista.scale, con pan/zoom) y la
           exportación a PDF/PNG (un canvas offscreen + una proyección
           simple "mundo * factor", sin pan: se exporta el plano entero). */
        function dibujarItems(ctx, proyectar, escala) {
            estadoPlano.trazos.forEach(item => {
                if (capasVisibles[item.tool] === false) return;

                if (item.tipo === 'icono') {
                    if (!item.imagen.complete || !item.imagen.naturalWidth) return;
                    const ratio = item.imagen.naturalWidth / item.imagen.naturalHeight;
                    const factorEscala = estadoPlano.escalas[GRUPO_ESCALA[item.tool]] ?? 1;
                    const base = item.tamano * escala * factorEscala;
                    const anchoItem = ratio >= 1 ? base : base * ratio;
                    const altoItem = ratio >= 1 ? base / ratio : base;
                    const centro = proyectar(item.x, item.y);
                    ctx.drawImage(
                        item.imagen,
                        centro.x - anchoItem / 2,
                        centro.y - altoItem / 2,
                        anchoItem,
                        altoItem
                    );

                    if (item.etiqueta) {
                        const tamanoFuente = base * 0.32;
                        ctx.font = `bold ${tamanoFuente}px sans-serif`;
                        ctx.textBaseline = 'middle';
                        ctx.textAlign = 'left';
                        const textoX = centro.x + anchoItem / 2 - base * 0.14;
                        ctx.fillStyle = item.colorEtiqueta || '#000';
                        ctx.fillText(item.etiqueta, textoX, centro.y);
                    }
                    return;
                }

                if (item.tipo === 'texto') {
                    const centro = proyectar(item.x, item.y);
                    const grupoEscala = GRUPO_ESCALA[item.tool] ?? 'texto';
                    const tamanoFuente = item.tamano * escala * (estadoPlano.escalas[grupoEscala] ?? 1);
                    ctx.font = `600 ${tamanoFuente}px sans-serif`;
                    ctx.textBaseline = 'middle';
                    ctx.textAlign = 'left';
                    ctx.fillStyle = item.color;
                    ctx.fillText(item.texto, centro.x, centro.y);
                    return;
                }

                if (item.puntos.length < 2) return;
                const puntosProyectados = item.puntos.map(p => proyectar(p.x, p.y));
                const path = new Path2D();
                path.moveTo(puntosProyectados[0].x, puntosProyectados[0].y);
                for (let i = 1; i < puntosProyectados.length; i++) {
                    path.lineTo(puntosProyectados[i].x, puntosProyectados[i].y);
                }

                if (item.cerrado) {
                    path.closePath();
                    if (item.relleno) dibujarTramaDiagonal(ctx, item, puntosProyectados, path, escala);
                }

                ctx.strokeStyle = item.color;
                ctx.lineWidth = item.grosor * escala;
                ctx.stroke(path);
            });
        }

        function dibujarTramaDiagonal(ctx, item, puntosProyectados, path, escala) {
            const xs = puntosProyectados.map(p => p.x);
            const ys = puntosProyectados.map(p => p.y);
            const minX = Math.min(...xs), maxX = Math.max(...xs);
            const minY = Math.min(...ys), maxY = Math.max(...ys);
            const centroX = (minX + maxX) / 2;
            const centroY = (minY + maxY) / 2;
            const diagonal = Math.hypot(maxX - minX, maxY - minY) || 1;
            const espaciado = Math.max(1.5, ESPACIO_TRAMA * escala);

            ctx.save();
            ctx.clip(path);
            ctx.translate(centroX, centroY);
            ctx.rotate(Math.PI / 4);
            ctx.strokeStyle = item.color;
            ctx.lineWidth = Math.max(0.5, item.grosor * escala * 0.5);
            ctx.globalAlpha = 0.75;
            ctx.beginPath();
            for (let x = -diagonal; x <= diagonal; x += espaciado) {
                ctx.moveTo(x, -diagonal);
                ctx.lineTo(x, diagonal);
            }
            ctx.stroke();
            ctx.restore();
        }

        /* ─── Exportación a PDF/PNG: renderiza la página completa del PDF
             a resolución fija (independiente del pan/zoom actual) y le
             superpone todas las anotaciones con el mismo motor de dibujo
             de arriba, proyectando "mundo * factor" (sin desplazamiento). ─ */
        const FACTOR_EXPORTACION_DESEADO = 3;

        function calcularFactorExportacion() {
            return Math.min(FACTOR_EXPORTACION_DESEADO, 6000 / anchoBase, 6000 / altoBase);
        }

        async function generarCanvasExportacion() {
            const factor = calcularFactorExportacion();
            const pagina = await pdfDoc.getPage(1);
            const viewport = pagina.getViewport({ scale: factor, rotation: rotacionPlano });

            const canvas = document.createElement('canvas');
            canvas.width = Math.round(viewport.width);
            canvas.height = Math.round(viewport.height);
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#fff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            await pagina.render({ canvasContext: ctx, viewport }).promise;
            dibujarItems(ctx, (x, y) => ({ x: x * factor, y: y * factor }), factor);

            return canvas;
        }

        /* Cache de <img> ya cargadas para los íconos de la tabla de
           referencia (se usa tanto para dibujarlos en el canvas del PNG
           como, rasterizados, para incrustarlos en el PDF — ver
           rasterizarIconoAPng). Se cachea por url para no repetir la
           carga si el mismo ícono aparece en ambos formatos o se
           descarga más de una vez en la misma sesión. */
        const cacheImagenReferencia = {};
        function cargarImagenIcono(url) {
            if (!url) return Promise.resolve(null);
            if (!cacheImagenReferencia[url]) {
                cacheImagenReferencia[url] = new Promise(resolve => {
                    const img = new Image();
                    img.onload = () => resolve(img);
                    img.onerror = () => resolve(null);
                    img.src = url;
                });
            }
            return cacheImagenReferencia[url];
        }

        /* Dibuja la tabla "Referencia" de una categoría (daños o
           ensayos) en el canvas de exportación PNG, empezando en (x, y).
           Devuelve el "y" donde terminó, para poder apilar la próxima
           tabla debajo. */
        async function dibujarTablaReferenciaCanvas(ctx, categoria, y, anchoCanvas) {
            const CABECERA_ALTO = 60;
            const FILA_ALTO = 80;
            const ANCHO_ICONO = 110;
            const ANCHO_LETRA = categoria.columnas === 3 ? 80 : 0;
            const PADDING_NOMBRE = 64;
            const FUENTE_NOMBRE = '600 22px sans-serif';

            /* La columna de nombre se ajusta al texto más largo de esta
               categoría (con un padding), en vez de un ancho fijo — con
               un ancho fijo se veía una columna enorme con el texto
               perdido y chico adentro cuando los nombres eran cortos. */
            ctx.font = FUENTE_NOMBRE;
            const anchoNombreDeseado = Math.max(...categoria.filas.map(f => ctx.measureText(f.nombre).width)) + PADDING_NOMBRE;
            const anchoNombreMax = anchoCanvas - 2 * 50 - ANCHO_ICONO - ANCHO_LETRA;
            const anchoNombre = Math.min(anchoNombreDeseado, anchoNombreMax);
            const ancho = ANCHO_LETRA + anchoNombre + ANCHO_ICONO;
            const x = (anchoCanvas - ancho) / 2;

            ctx.save();
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 1.5;

            ctx.strokeRect(x, y, ancho, CABECERA_ALTO);
            ctx.fillStyle = '#000';
            ctx.font = 'bold 28px sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('Referencia', x + ancho / 2, y + CABECERA_ALTO / 2);
            y += CABECERA_ALTO;

            for (const fila of categoria.filas) {
                let cx = x;
                if (categoria.columnas === 3) {
                    ctx.strokeRect(cx, y, ANCHO_LETRA, FILA_ALTO);
                    ctx.font = 'bold 24px sans-serif';
                    ctx.fillText(fila.letra || '', cx + ANCHO_LETRA / 2, y + FILA_ALTO / 2);
                    cx += ANCHO_LETRA;
                }

                ctx.strokeRect(cx, y, anchoNombre, FILA_ALTO);
                ctx.font = FUENTE_NOMBRE;
                ctx.textAlign = 'left';
                ctx.fillText(fila.nombre, cx + 16, y + FILA_ALTO / 2);
                ctx.textAlign = 'center';
                cx += anchoNombre;

                ctx.strokeRect(cx, y, ANCHO_ICONO, FILA_ALTO);
                if (fila.url) {
                    const img = await cargarImagenIcono(fila.url);
                    if (img) {
                        const ratio = img.naturalWidth / img.naturalHeight || 1;
                        const tam = FILA_ALTO * 0.7;
                        const w = ratio >= 1 ? tam : tam * ratio;
                        const h = ratio >= 1 ? tam / ratio : tam;
                        ctx.drawImage(img, cx + (ANCHO_ICONO - w) / 2, y + (FILA_ALTO - h) / 2, w, h);
                    }
                } else if (fila.letra) {
                    const cxCirculo = cx + ANCHO_ICONO / 2, cyCirculo = y + FILA_ALTO / 2, radio = FILA_ALTO * 0.3;
                    ctx.beginPath();
                    ctx.arc(cxCirculo, cyCirculo, radio, 0, Math.PI * 2);
                    ctx.fillStyle = '#fff';
                    ctx.fill();
                    ctx.strokeStyle = '#999';
                    ctx.lineWidth = 1;
                    ctx.stroke();
                    ctx.fillStyle = '#ff0000';
                    ctx.font = 'bold 26px sans-serif';
                    ctx.fillText(fila.letra, cxCirculo, cyCirculo);
                    ctx.strokeStyle = '#000';
                    ctx.lineWidth = 1.5;
                }

                y += FILA_ALTO;
            }

            ctx.restore();
            return y;
        }

        /* Arma un canvas más alto que junta el plano exportado arriba y,
           debajo, una tabla "Referencia" por cada categoría (daños/
           ensayos) que tenga al menos una capa prendida — ver
           categoriasReferenciaActivas(). Si no hay ninguna categoría
           activa, devuelve el canvas del plano sin modificar. */
        async function agregarReferenciaACanvas(canvasPlano) {
            const categorias = categoriasReferenciaActivas();
            if (!categorias.length) return canvasPlano;

            const PAD = 50;
            const FILA_ALTO = 80, CABECERA_ALTO = 60;
            let altoTablas = PAD;
            categorias.forEach(cat => { altoTablas += CABECERA_ALTO + cat.filas.length * FILA_ALTO + PAD; });

            const canvas = document.createElement('canvas');
            canvas.width = canvasPlano.width;
            canvas.height = canvasPlano.height + altoTablas;
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#fff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(canvasPlano, 0, 0);

            let y = canvasPlano.height + PAD;
            for (const cat of categorias) {
                y = await dibujarTablaReferenciaCanvas(ctx, cat, y, canvas.width);
                y += PAD;
            }

            return canvas;
        }

        function nombreArchivoDescarga(extension) {
            const base = nombrePlanoBase.replace(/[\\/:*?"<>|]+/g, '_').trim() || 'plano';
            return `${base}.${extension}`;
        }

        function descargarBlob(blob, nombreArchivo) {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = nombreArchivo;
            document.body.appendChild(a);
            a.click();
            a.remove();
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        }

        async function descargarComoPNG(conReferencia) {
            let canvas = await generarCanvasExportacion();
            if (conReferencia) canvas = await agregarReferenciaACanvas(canvas);
            await new Promise(resolve => {
                canvas.toBlob(blob => {
                    if (blob) descargarBlob(blob, nombreArchivoDescarga('png'));
                    resolve();
                }, 'image/png');
            });
        }

        function hexARgb(hex) {
            const limpio = (hex || '#000000').replace('#', '');
            const normalizado = limpio.length === 3
                ? limpio.split('').map(c => c + c).join('')
                : limpio.padStart(6, '0');
            const bigint = parseInt(normalizado, 16) || 0;
            return PDFLib.rgb(((bigint >> 16) & 255) / 255, ((bigint >> 8) & 255) / 255, (bigint & 255) / 255);
        }

        /* Versión clara (mezclada con blanco) de un color, para el
           relleno de las formas "con relleno": pdf-lib no expone un
           operador simple de opacidad a este nivel bajo (requeriría
           armar un ExtGState a mano), así que en vez de una trama/alpha
           real se usa un tono pastel del mismo color a opacidad plena. */
        function colorClaro(hex) {
            const limpio = (hex || '#000000').replace('#', '');
            const normalizado = limpio.length === 3
                ? limpio.split('').map(c => c + c).join('')
                : limpio.padStart(6, '0');
            const bigint = parseInt(normalizado, 16) || 0;
            const mezclar = canal => (canal + (255 - canal) * 0.8) / 255;
            return PDFLib.rgb(
                mezclar((bigint >> 16) & 255),
                mezclar((bigint >> 8) & 255),
                mezclar(bigint & 255)
            );
        }

        /* ─── Íconos como vectores reales en el PDF (no como imagen) ───
             Los SVG de daños-ícono/ensayos/foto se normalizaron a mano
             (ver conversación) para quedar "planos": sin <g>, sin
             transform, sin clipPath — cada <path>/<circle> ya tiene sus
             coordenadas finales dentro del propio viewBox. Gracias a eso,
           acá alcanza con una lectura por regex (no hace falta un parser
             de XML/transforms completo). El resultado se cachea a nivel
             de módulo (no depende del PDFDocument de turno), así que se
             fetchea una sola vez por sesión aunque se exporte varias veces. */
        const ICONOS_SVG_URL = {};
        [...DANOS_ICONO, ...ENSAYOS_Y_FOTO].forEach(({ tool, url }) => { ICONOS_SVG_URL[tool] = url; });

        function extraerViewBox(svgTexto) {
            const m = svgTexto.match(/viewBox="\s*([-\d.]+)\s+([-\d.]+)\s+([-\d.]+)\s+([-\d.]+)\s*"/);
            if (!m) return null;
            return { minX: parseFloat(m[1]), minY: parseFloat(m[2]), w: parseFloat(m[3]), h: parseFloat(m[4]) };
        }

        function extraerValorAtributo(tag, nombre) {
            const mAtributo = tag.match(new RegExp(nombre + '="([^"]*)"'));
            if (mAtributo) return mAtributo[1];
            const mStyle = tag.match(/style="([^"]*)"/);
            if (mStyle) {
                const mPropiedad = mStyle[1].match(new RegExp('(?:^|;)\\s*' + nombre + '\\s*:\\s*([^;]+)'));
                if (mPropiedad) return mPropiedad[1].trim();
            }
            return null;
        }

        /* Convierte un "d" de <path> (soporta M/L/H/V/C/Z — los únicos
           comandos que aparecen en estos SVG ya normalizados) en
           subtrazados de puntos rectos, aproximando cada curva Bézier
           cúbica por segmentos.
           Por qué: AutoCAD importa perfecto un relleno de PDF cuyo
           contorno son líneas rectas (HATCH normal), pero rompe el
           relleno cuando el contorno tiene curvas — por eso los íconos
           con "C" en su path (carbonatación, ultrasonido, resistividad,
           potencial, cloruros) se veían mal al importar y esclerometría/
           testigos (sin curvas) no. Aplanando acá, todos los íconos
           terminan dibujándose como el mismo tipo de polígono recto que
           ya se sabe que importa bien. */
        /* Ángulo con signo entre dos vectores (para el arco elíptico). */
        function anguloEntreVectores(ux, uy, vx, vy) {
            const punto = ux * vx + uy * vy;
            const largo = Math.sqrt((ux * ux + uy * uy) * (vx * vx + vy * vy));
            let angulo = Math.acos(Math.min(1, Math.max(-1, punto / largo)));
            if (ux * vy - uy * vx < 0) angulo = -angulo;
            return angulo;
        }

        /* Convierte un arco elíptico SVG (comando A/a, parametrización por
           extremos) en puntos, siguiendo el algoritmo estándar de la
           especificación SVG 1.1 (apéndice F.6: de extremos a centro).
           Verificado a mano contra los 4 arcos reales de foto.svg (cada
           uno termina exacto en el punto de destino esperado). */
        function arcoAPuntos(x1, y1, rxIn, ryIn, phiDeg, arcoGrande, sentidoHorario, x2, y2, segmentos = 16) {
            if (!rxIn || !ryIn || (x1 === x2 && y1 === y2)) return [{ x: x2, y: y2 }];
            let rx = Math.abs(rxIn), ry = Math.abs(ryIn);
            const phi = phiDeg * Math.PI / 180;
            const cosPhi = Math.cos(phi), sinPhi = Math.sin(phi);

            const dx2 = (x1 - x2) / 2, dy2 = (y1 - y2) / 2;
            const x1p = cosPhi * dx2 + sinPhi * dy2;
            const y1p = -sinPhi * dx2 + cosPhi * dy2;

            const lambda = (x1p * x1p) / (rx * rx) + (y1p * y1p) / (ry * ry);
            if (lambda > 1) { const raiz = Math.sqrt(lambda); rx *= raiz; ry *= raiz; }

            const signo = arcoGrande !== sentidoHorario ? 1 : -1;
            const num = rx * rx * ry * ry - rx * rx * y1p * y1p - ry * ry * x1p * x1p;
            const den = rx * rx * y1p * y1p + ry * ry * x1p * x1p;
            const co = signo * Math.sqrt(Math.max(0, num) / (den || 1e-9));
            const cxp = co * (rx * y1p / ry);
            const cyp = co * (-ry * x1p / rx);

            const cx = cosPhi * cxp - sinPhi * cyp + (x1 + x2) / 2;
            const cy = sinPhi * cxp + cosPhi * cyp + (y1 + y2) / 2;

            const theta1 = anguloEntreVectores(1, 0, (x1p - cxp) / rx, (y1p - cyp) / ry);
            let dtheta = anguloEntreVectores((x1p - cxp) / rx, (y1p - cyp) / ry, (-x1p - cxp) / rx, (-y1p - cyp) / ry);
            if (!sentidoHorario && dtheta > 0) dtheta -= 2 * Math.PI;
            if (sentidoHorario && dtheta < 0) dtheta += 2 * Math.PI;

            const puntos = [];
            for (let s = 1; s <= segmentos; s++) {
                const theta = theta1 + (s / segmentos) * dtheta;
                puntos.push({
                    x: cx + rx * cosPhi * Math.cos(theta) - ry * sinPhi * Math.sin(theta),
                    y: cy + rx * sinPhi * Math.cos(theta) + ry * cosPhi * Math.sin(theta),
                });
            }
            return puntos;
        }

        function parsearPathAPuntos(d, segmentosPorCurva = 16) {
            const tokens = d.match(/[MLHVCAZmlhvcaz]|-?\d*\.?\d+(?:e[-+]?\d+)?/g) || [];
            let i = 0;
            let cx = 0, cy = 0;
            let inicioSubpath = { x: 0, y: 0 };
            let actual = null;
            const subpaths = [];
            const esComando = () => i < tokens.length && /^[A-Za-z]$/.test(tokens[i]);
            const leerNum = () => parseFloat(tokens[i++]);

            while (i < tokens.length) {
                const cmd = tokens[i++];
                if (cmd === 'M' || cmd === 'm') {
                    const relativo = cmd === 'm';
                    const x = leerNum(), y = leerNum();
                    cx = relativo ? cx + x : x;
                    cy = relativo ? cy + y : y;
                    inicioSubpath = { x: cx, y: cy };
                    actual = [{ x: cx, y: cy }];
                    subpaths.push(actual);
                    while (i < tokens.length && !esComando()) {
                        const x2 = leerNum(), y2 = leerNum();
                        cx = relativo ? cx + x2 : x2;
                        cy = relativo ? cy + y2 : y2;
                        actual.push({ x: cx, y: cy });
                    }
                } else if (cmd === 'L' || cmd === 'l') {
                    const relativo = cmd === 'l';
                    while (i < tokens.length && !esComando()) {
                        const x = leerNum(), y = leerNum();
                        cx = relativo ? cx + x : x;
                        cy = relativo ? cy + y : y;
                        if (actual) actual.push({ x: cx, y: cy });
                    }
                } else if (cmd === 'H' || cmd === 'h') {
                    const relativo = cmd === 'h';
                    while (i < tokens.length && !esComando()) {
                        const x = leerNum();
                        cx = relativo ? cx + x : x;
                        if (actual) actual.push({ x: cx, y: cy });
                    }
                } else if (cmd === 'V' || cmd === 'v') {
                    const relativo = cmd === 'v';
                    while (i < tokens.length && !esComando()) {
                        const y = leerNum();
                        cy = relativo ? cy + y : y;
                        if (actual) actual.push({ x: cx, y: cy });
                    }
                } else if (cmd === 'C' || cmd === 'c') {
                    const relativo = cmd === 'c';
                    while (i < tokens.length && !esComando()) {
                        const x1r = leerNum(), y1r = leerNum();
                        const x2r = leerNum(), y2r = leerNum();
                        const xr = leerNum(), yr = leerNum();
                        const x1 = relativo ? cx + x1r : x1r;
                        const y1 = relativo ? cy + y1r : y1r;
                        const x2 = relativo ? cx + x2r : x2r;
                        const y2 = relativo ? cy + y2r : y2r;
                        const x = relativo ? cx + xr : xr;
                        const y = relativo ? cy + yr : yr;
                        for (let s = 1; s <= segmentosPorCurva; s++) {
                            const t = s / segmentosPorCurva, mt = 1 - t;
                            const px = mt * mt * mt * cx + 3 * mt * mt * t * x1 + 3 * mt * t * t * x2 + t * t * t * x;
                            const py = mt * mt * mt * cy + 3 * mt * mt * t * y1 + 3 * mt * t * t * y2 + t * t * t * y;
                            if (actual) actual.push({ x: px, y: py });
                        }
                        cx = x; cy = y;
                    }
                } else if (cmd === 'A' || cmd === 'a') {
                    const relativo = cmd === 'a';
                    while (i < tokens.length && !esComando()) {
                        const rx = leerNum(), ry = leerNum();
                        const rot = leerNum();
                        const arcoGrande = leerNum() !== 0;
                        const sentidoHorario = leerNum() !== 0;
                        const xr = leerNum(), yr = leerNum();
                        const x = relativo ? cx + xr : xr;
                        const y = relativo ? cy + yr : yr;
                        const puntosArco = arcoAPuntos(cx, cy, rx, ry, rot, arcoGrande, sentidoHorario, x, y, segmentosPorCurva);
                        if (actual) puntosArco.forEach(p => actual.push(p));
                        cx = x; cy = y;
                    }
                } else if (cmd === 'Z' || cmd === 'z') {
                    if (actual) actual.push({ x: inicioSubpath.x, y: inicioSubpath.y });
                    cx = inicioSubpath.x; cy = inicioSubpath.y;
                }
                /* Cualquier otro comando (S/Q/T) se ignora: no aparece
                   en estos archivos ya normalizados. */
            }
            return subpaths;
        }

        /* Bajar de 32 a 16 lados no cambió nada (mismo problema exacto),
           así que no es la CANTIDAD de vértices lo que dispara el
           "reconocimiento de círculo" de AutoCAD (que lo convierte a un
           objeto ARCO tomando parte de los puntos y deja el resto como
           geometría suelta desalineada — el efecto "se ve como una C con
           el resto al lado", sin relleno, porque un ARCO no lleva
           relleno) — sino que todos los vértices están a la MISMA
           distancia exacta del centro. Acá se alterna el radio ±1.5% en
           vértices consecutivos: sigue viéndose redondo a este tamaño,
           pero ya no matchea el patrón "equidistante" que dispara esa
           detección. */
        function circuloAPuntos(cx, cy, r, segmentos = 16) {
            const puntos = [];
            for (let i = 0; i < segmentos; i++) {
                const angulo = (i / segmentos) * Math.PI * 2;
                const radio = r * (i % 2 === 0 ? 1.015 : 0.985);
                puntos.push({ x: cx + radio * Math.cos(angulo), y: cy + radio * Math.sin(angulo) });
            }
            return [puntos];
        }

        function parsearFormasSvg(svgTexto) {
            const formas = [];
            const tagRe = /<(path|circle)\b([^>]*)>/g;
            let m;
            while ((m = tagRe.exec(svgTexto))) {
                const [, tipo, tag] = m;
                const fillRaw = extraerValorAtributo(tag, 'fill');
                const strokeRaw = extraerValorAtributo(tag, 'stroke');
                const fill = fillRaw && fillRaw !== 'none' ? fillRaw : null;
                const stroke = strokeRaw && strokeRaw !== 'none' ? strokeRaw : null;
                const strokeWidth = parseFloat(extraerValorAtributo(tag, 'stroke-width') || '1') || 1;

                if (tipo === 'path') {
                    const dMatch = tag.match(/\bd="([^"]*)"/);
                    if (dMatch) formas.push({ subpaths: parsearPathAPuntos(dMatch[1]), fill, stroke, strokeWidth });
                } else {
                    const cx = parseFloat(extraerValorAtributo(tag, 'cx') || '0');
                    const cy = parseFloat(extraerValorAtributo(tag, 'cy') || '0');
                    const r = parseFloat(extraerValorAtributo(tag, 'r') || '0');
                    formas.push({ subpaths: circuloAPuntos(cx, cy, r), fill, stroke, strokeWidth });
                }
            }
            return formas;
        }

        const cacheIconoVectorial = {};
        async function obtenerIconoVectorial(tool) {
            if (tool in cacheIconoVectorial) return cacheIconoVectorial[tool];
            const url = ICONOS_SVG_URL[tool];
            if (!url) { cacheIconoVectorial[tool] = null; return null; }
            try {
                const texto = await fetch(url).then(r => r.text());
                const viewBox = extraerViewBox(texto);
                if (!viewBox || !viewBox.w || !viewBox.h) { cacheIconoVectorial[tool] = null; return null; }
                const icono = { minX: viewBox.minX, minY: viewBox.minY, w: viewBox.w, h: viewBox.h, formas: parsearFormasSvg(texto) };
                cacheIconoVectorial[tool] = icono;
                return icono;
            } catch (e) {
                console.warn('No se pudo cargar el ícono vectorial de "' + tool + '"', e);
                cacheIconoVectorial[tool] = null;
                return null;
            }
        }

        /* Exportación a PDF VECTORIAL: carga el PDF original tal cual
           (sin re-renderizarlo ni rasterizarlo) y dibuja las anotaciones
           directamente sobre esa misma página, como objetos PDF reales
           (líneas/texto/imágenes) — el archivo original nunca se
           modifica, esto arma una copia nueva en memoria para descargar.

           El punto delicado es la rotación del plano (rotacionPlano):
           las coordenadas "mundo" (item.x/y) están pensadas para el
           viewport YA ROTADO que ve el usuario (ver anchoBase/altoBase
           en renderPagina), pero page.drawX() de pdf-lib dibuja en el
           espacio NATIVO de la página (sin rotar). Para convertir un
           punto se usa viewport.convertToPdfPoint(x, y) —lo hace PDF.js,
           no hay que reinventar la matriz de rotación—. Para que un
           ícono o un texto se vea "derecho" en pantalla (igual que en
           la vista en vivo, donde nunca giran) hay que además rotar su
           forma local en sentido contrario al que después va a aplicar
           cualquier lector de PDF al respetar /Rotate: por eso se le
           suma "rotate: degrees(rotObjetivo)" y se rota a mano el offset
           del punto de anclaje con rotarOffset (mismo ángulo, sentido
           antihorario = convención nativa de PDF/pdf-lib). */
        async function generarPdfVectorial(conReferencia) {
            const { PDFDocument, StandardFonts, degrees, PDFName, PDFString, PDFOperator, PDFContentStream } = PDFLib;

            const bytesOriginal = await pdfDoc.getData();
            const pdfOut = await PDFDocument.load(bytesOriginal);
            const page = pdfOut.getPage(0);
            const { context } = pdfOut;

            const rotObjetivo = ((rotacionPlano % 360) + 360) % 360;
            page.setRotation(degrees(rotObjetivo));

            /* ─── Capas (Optional Content Groups) ───────────────
                 Una capa por herramienta usada en el plano + una para
                 el plano de fondo original, para que AutoCAD las
                 importe como capas nativas separadas (PDFIMPORT
                 convierte los OCG de un PDF en capas de AutoCAD, y
                 viceversa al exportar). pdf-lib no tiene soporte de
                 alto nivel para esto: se arma a mano con su API de
                 bajo nivel (context.obj/register + operadores BDC/EMC
                 de "marked content"). Verificado con pdf.js —un parser
                 independiente de pdf-lib— que el resultado es válido y
                 que los nombres de capa se leen correctamente antes de
                 integrarlo acá. */
            function crearOCG(nombre) {
                const dict = context.obj({ Type: 'OCG', Name: PDFString.of(nombre) });
                return context.register(dict);
            }

            const capaFondo = crearOCG('Plano original');

            /* Solo se crea una capa por herramienta que realmente tenga
               algo dibujado y visible (mismo criterio que ya usa el
               resto de la función para decidir qué exportar), para no
               llenar AutoCAD de capas vacías. */
            const toolsUsados = [...new Set(
                estadoPlano.trazos
                    .filter(item => capasVisibles[item.tool] !== false)
                    .map(item => item.tool)
            )];
            const ocgPorTool = {};
            toolsUsados.forEach(tool => {
                ocgPorTool[tool] = crearOCG(metaCapas[tool]?.nombre || tool);
            });

            const todasLasCapas = [capaFondo, ...toolsUsados.map(t => ocgPorTool[t])];
            pdfOut.catalog.set(PDFName.of('OCProperties'), context.obj({
                OCGs: todasLasCapas,
                D: { ON: todasLasCapas, Order: todasLasCapas },
            }));

            /* page.node.Resources() puede devolver undefined si la
               página no tiene ese diccionario propio ni heredado (raro,
               pero posible en un PDF armado a mano). normalizedEntries()
               fuerza a pdf-lib a crearlo y adjuntarlo si falta —además
               de convertir /Contents a array, lo mismo que hacíamos a
               mano abajo, así que ya no hace falta ese chequeo manual. */
            const { Resources } = page.node.normalizedEntries();

            const nombreCortoPorTool = {};
            const propiedades = { OCFondo: capaFondo };
            toolsUsados.forEach((tool, i) => {
                nombreCortoPorTool[tool] = 'OCTool' + i;
                propiedades['OCTool' + i] = ocgPorTool[tool];
            });
            Resources.set(PDFName.of('Properties'), context.obj(propiedades));

            /* Envuelve el contenido YA EXISTENTE del PDF original (el
               plano de fondo, que no generamos nosotros) en la capa
               "Plano original" — tiene que hacerse ANTES de agregar
               contenido nuevo, mientras /Contents todavía solo tiene
               el stream original. */
            function crearStreamMarcador(operador) {
                return context.register(PDFContentStream.of(context.obj({}), [operador]));
            }
            page.node.wrapContentStreams(
                crearStreamMarcador(PDFOperator.of('BDC', [PDFName.of('OC'), PDFName.of('OCFondo')])),
                crearStreamMarcador(PDFOperator.of('EMC', []))
            );

            function iniciarCapa(tool) {
                page.pushOperators(PDFOperator.of('BDC', [PDFName.of('OC'), PDFName.of(nombreCortoPorTool[tool])]));
            }
            function cerrarCapa() {
                page.pushOperators(PDFOperator.of('EMC', []));
            }

            const pagina = await pdfDoc.getPage(1);
            const viewportBase = pagina.getViewport({ scale: 1, rotation: rotacionPlano });
            const proyectarNativo = (x, y) => {
                const [nx, ny] = viewportBase.convertToPdfPoint(x, y);
                return { x: nx, y: ny };
            };

            const anguloRad = rotObjetivo * Math.PI / 180;
            const cosR = Math.cos(anguloRad);
            const sinR = Math.sin(anguloRad);
            const rotarOffset = (u, v) => ({ x: u * cosR - v * sinR, y: u * sinR + v * cosR });

            const fuente = await pdfOut.embedFont(StandardFonts.HelveticaBold);

            for (const item of estadoPlano.trazos) {
                if (capasVisibles[item.tool] === false) continue;
                iniciarCapa(item.tool);

                if (item.tipo === 'icono') {
                    const factorEscala = estadoPlano.escalas[GRUPO_ESCALA[item.tool]] ?? 1;
                    const base = item.tamano * factorEscala;
                    const icono = await obtenerIconoVectorial(item.tool);
                    if (!icono) { cerrarCapa(); continue; }

                    /* s: factor uniforme que lleva el viewBox del ícono
                       (p. ej. 1588x1122.6667) al tamaño "base" deseado en
                       el plano — misma lógica que antes usaba ratio de
                       imagen, pero sin distinguir ancho/alto porque acá
                       se escala el viewBox entero por igual. */
                    const s = base / Math.max(icono.w, icono.h);
                    const ancho = s * icono.w;
                    const centro = proyectarNativo(item.x, item.y);
                    /* anclaX/Y: dónde cae el punto local (0,0) —el origen
                       del sistema de coordenadas del propio path/d, NO
                       necesariamente el borde del viewBox— en espacio PDF
                       nativo, para que el CENTRO REAL del viewBox
                       (minX+w/2, minY+h/2; varios íconos como Potencial,
                       Cloruros o Resistividad tienen viewBox con origen
                       negativo, no en 0,0) termine exactamente en "centro"
                       una vez aplicados escala+rotación (ver drawSvgPath:
                       hace translate(x,y) → rotate → scale(s,-s) → dibuja
                       el path con sus coordenadas tal cual). Los círculos
                       (foto.svg, fisura direccional) no pasan por
                       drawSvgPath, así que se posicionan a mano con la
                       misma fórmula (ancla + rotarOffset del punto local). */
                    const centroViewBoxLocal = { x: icono.minX + icono.w / 2, y: icono.minY + icono.h / 2 };
                    const anclaLocal = rotarOffset(s * centroViewBoxLocal.x, -s * centroViewBoxLocal.y);
                    const anclaX = centro.x - anclaLocal.x;
                    const anclaY = centro.y - anclaLocal.y;

                    /* Cada forma se dibuja con los mismos operadores de
                       bajo nivel que los trazos hechos a mano (moveTo/
                       lineTo/closePath/fill) en vez de drawSvgPath/
                       drawCircle: son polígonos rectos (las curvas ya se
                       aplanaron en parsearPathAPuntos), el mismo tipo de
                       contorno que AutoCAD importa bien como HATCH. Si
                       una forma tiene varios subtrazados (p. ej. un
                       "anillo": contorno exterior + interior) se agrupan
                       en un solo fill para que la regla non-zero cree el
                       hueco, en vez de rellenar cada subtrazado aparte. */
                    icono.formas.forEach(forma => {
                        if (!forma.subpaths.length) return;
                        const color = forma.fill ? hexARgb(forma.fill) : null;
                        let colorTrazo = forma.stroke ? hexARgb(forma.stroke) : null;
                        let anchoTrazo = forma.strokeWidth * s;
                        /* AutoCAD no siempre convierte bien a HATCH un
                           relleno de PDF que no tiene un trazo (stroke)
                           acompañándolo (visto con las fisuras
                           direccionales y con foto.svg, que solo tienen
                           fill, sin stroke): salían en blanco/incompletos
                           al importar aunque el PDF se viera bien. Se le
                           agrega un trazo sintético del mismo color que
                           el relleno (imperceptible, mismo tono) solo
                           para darle a AutoCAD geometría de borde.
                           El ancho de ESTE trazo sintético se fija en un
                           valor chico absoluto, sin depender de "s": los
                           íconos con viewBox chico (24x24, fisuras) tienen
                           una "s" mucho más grande que los de viewBox
                           enorme (esclerometria, ~1588), así que heredar
                           forma.strokeWidth*s les daba un trazo ~25 veces
                           más grueso en proporción a su tamaño — posible
                           causa de que AutoCAD interprete mal el borde. */
                        if (color && !colorTrazo) { colorTrazo = color; anchoTrazo = 0.05; }
                        if (!color && !colorTrazo) return;

                        const operadores = [PDFLib.pushGraphicsState()];
                        if (colorTrazo) {
                            operadores.push(PDFLib.setLineWidth(anchoTrazo), PDFLib.setStrokingColor(colorTrazo));
                        }
                        if (color) operadores.push(PDFLib.setFillingColor(color));

                        forma.subpaths.forEach(puntosLocales => {
                            if (puntosLocales.length < 2) return;
                            const puntos = puntosLocales.map(p => {
                                const o = rotarOffset(s * p.x, -s * p.y);
                                return { x: anclaX + o.x, y: anclaY + o.y };
                            });
                            operadores.push(PDFLib.moveTo(puntos[0].x, puntos[0].y));
                            for (let i = 1; i < puntos.length; i++) operadores.push(PDFLib.lineTo(puntos[i].x, puntos[i].y));
                            operadores.push(PDFLib.closePath());
                        });

                        if (color && colorTrazo) operadores.push(PDFLib.fillAndStroke());
                        else if (color) operadores.push(PDFLib.fill());
                        else operadores.push(PDFLib.stroke());
                        operadores.push(PDFLib.popGraphicsState());
                        page.pushOperators(...operadores);
                    });

                    if (item.etiqueta) {
                        const tamanoFuente = base * 0.32;
                        const offsetTexto = rotarOffset(ancho / 2 - base * 0.14, -tamanoFuente * 0.32);
                        page.drawText(item.etiqueta, {
                            x: centro.x + offsetTexto.x,
                            y: centro.y + offsetTexto.y,
                            size: tamanoFuente,
                            font: fuente,
                            color: hexARgb(item.colorEtiqueta || '#000000'),
                            rotate: degrees(rotObjetivo),
                        });
                    }
                    cerrarCapa();
                    continue;
                }

                if (item.tipo === 'texto') {
                    const grupoEscala = GRUPO_ESCALA[item.tool] ?? 'texto';
                    const tamanoFuente = item.tamano * (estadoPlano.escalas[grupoEscala] ?? 1);
                    const centro = proyectarNativo(item.x, item.y);
                    const offsetTexto = rotarOffset(0, -tamanoFuente * 0.32);
                    page.drawText(item.texto, {
                        x: centro.x + offsetTexto.x,
                        y: centro.y + offsetTexto.y,
                        size: tamanoFuente,
                        font: fuente,
                        color: hexARgb(item.color),
                        rotate: degrees(rotObjetivo),
                    });
                    cerrarCapa();
                    continue;
                }

                if (!item.puntos || item.puntos.length < 2) { cerrarCapa(); continue; }
                const puntos = item.puntos.map(p => proyectarNativo(p.x, p.y));
                const color = hexARgb(item.color);
                const relleno = item.cerrado && item.relleno;

                const operadores = [
                    PDFLib.pushGraphicsState(),
                    PDFLib.setLineWidth(item.grosor),
                    PDFLib.setStrokingColor(color),
                    PDFLib.moveTo(puntos[0].x, puntos[0].y),
                ];
                for (let i = 1; i < puntos.length; i++) {
                    operadores.push(PDFLib.lineTo(puntos[i].x, puntos[i].y));
                }
                if (item.cerrado) operadores.push(PDFLib.closePath());
                if (relleno) {
                    operadores.push(PDFLib.setFillingColor(colorClaro(item.color)));
                    operadores.push(PDFLib.fillAndStroke());
                } else {
                    operadores.push(PDFLib.stroke());
                }
                operadores.push(PDFLib.popGraphicsState());
                page.pushOperators(...operadores);
                cerrarCapa();
            }

            if (conReferencia) await agregarPaginasReferenciaPdf(pdfOut, fuente);

            return pdfOut.save();
        }

        /* Rasteriza un ícono SVG a PNG (data URL) dibujándolo en un
           canvas offscreen — se usa para los daños que se dibujan como
           trazo (Fisura, Corrosión, etc.), cuyo SVG del ícono trae <g>/
           transform que el parser vectorial liviano (obtenerIconoVectorial)
           no soporta. El navegador sí sabe renderizar ese SVG completo
           como <img>, así que en vez de reimplementar un parser de XML
           completo, se aprovecha eso y se incrusta el resultado como
           imagen — no hace falta que sea vectorial de verdad: es un
           ícono de referencia/leyenda, no un objeto real del plano. */
        async function rasterizarIconoAPng(url, tamanoPx = 200) {
            const img = await cargarImagenIcono(url);
            if (!img) return null;
            const ratio = img.naturalWidth / img.naturalHeight || 1;
            const w = ratio >= 1 ? tamanoPx : Math.round(tamanoPx * ratio);
            const h = ratio >= 1 ? Math.round(tamanoPx / ratio) : tamanoPx;
            const canvas = document.createElement('canvas');
            canvas.width = w;
            canvas.height = h;
            canvas.getContext('2d').drawImage(img, 0, 0, w, h);
            return canvas.toDataURL('image/png');
        }

        /* Versión sin rotación de la misma matemática que usa
           generarPdfVectorial() para plantar un ícono vectorial en un
           punto del PDF (ver el comentario largo ahí sobre por qué hay
           que centrar en base al viewBox real, no al origen del path).
           Las páginas de referencia no rotan, así que no hace falta la
           parte de rotarOffset. */
        function dibujarIconoVectorialEnPuntoPdf(page, icono, cx, cy, tamano) {
            const s = tamano / Math.max(icono.w, icono.h);
            const centroViewBoxLocal = { x: icono.minX + icono.w / 2, y: icono.minY + icono.h / 2 };
            const anclaX = cx - s * centroViewBoxLocal.x;
            const anclaY = cy + s * centroViewBoxLocal.y;

            icono.formas.forEach(forma => {
                if (!forma.subpaths.length) return;
                const color = forma.fill ? hexARgb(forma.fill) : null;
                let colorTrazo = forma.stroke ? hexARgb(forma.stroke) : null;
                let anchoTrazo = forma.strokeWidth * s;
                if (color && !colorTrazo) { colorTrazo = color; anchoTrazo = 0.05; }
                if (!color && !colorTrazo) return;

                const operadores = [PDFLib.pushGraphicsState()];
                if (colorTrazo) operadores.push(PDFLib.setLineWidth(anchoTrazo), PDFLib.setStrokingColor(colorTrazo));
                if (color) operadores.push(PDFLib.setFillingColor(color));

                forma.subpaths.forEach(puntosLocales => {
                    if (puntosLocales.length < 2) return;
                    const puntos = puntosLocales.map(p => ({ x: anclaX + s * p.x, y: anclaY - s * p.y }));
                    operadores.push(PDFLib.moveTo(puntos[0].x, puntos[0].y));
                    for (let i = 1; i < puntos.length; i++) operadores.push(PDFLib.lineTo(puntos[i].x, puntos[i].y));
                    operadores.push(PDFLib.closePath());
                });

                if (color && colorTrazo) operadores.push(PDFLib.fillAndStroke());
                else if (color) operadores.push(PDFLib.fill());
                else operadores.push(PDFLib.stroke());
                operadores.push(PDFLib.popGraphicsState());
                page.pushOperators(...operadores);
            });
        }

        /* Agrega, al final del PDF, una página A4 por cada categoría de
           referencia activa (ver categoriasReferenciaActivas) con la
           tabla "Referencia" pedida: cabecera fusionada + una fila por
           daño/ensayo con su nombre y su ícono (y, en ensayos, la letra
           de su prefijo de numeración). Si una tabla no entra entera en
           una página, sigue en una nueva. */
        async function agregarPaginasReferenciaPdf(pdfOut, fuente) {
            const categorias = categoriasReferenciaActivas();
            if (!categorias.length) return;

            const ANCHO = 595.28, ALTO = 841.89; // A4 en puntos
            const MARGEN = 50;
            const CABECERA_ALTO = 36;
            const FILA_ALTO = 42;
            const ANCHO_ICONO = 65;
            const ANCHO_LETRA = 40;

            let page = pdfOut.addPage([ANCHO, ALTO]);
            let y = ALTO - MARGEN;

            function nuevaPaginaSiHaceFalta(alturaNecesaria) {
                if (y - alturaNecesaria < MARGEN) {
                    page = pdfOut.addPage([ANCHO, ALTO]);
                    y = ALTO - MARGEN;
                }
            }

            const TAMANO_NOMBRE = 12;
            const PADDING_NOMBRE = 20;

            for (const cat of categorias) {
                nuevaPaginaSiHaceFalta(CABECERA_ALTO + FILA_ALTO);

                const anchoLetraCol = cat.columnas === 3 ? ANCHO_LETRA : 0;
                /* La columna de nombre se ajusta al texto más largo de
                   esta categoría (con un padding), no a un ancho fijo —
                   con un ancho fijo la columna quedaba enorme y el
                   texto se veía perdido/chico adentro cuando los
                   nombres eran cortos. */
                const anchoNombreDeseado = Math.max(...cat.filas.map(f => fuente.widthOfTextAtSize(f.nombre, TAMANO_NOMBRE))) + PADDING_NOMBRE * 2;
                const anchoNombreMax = ANCHO - MARGEN * 2 - anchoLetraCol - ANCHO_ICONO;
                const anchoNombreCol = Math.min(anchoNombreDeseado, anchoNombreMax);
                const anchoTabla = anchoLetraCol + anchoNombreCol + ANCHO_ICONO;
                const xTabla = MARGEN + (ANCHO - MARGEN * 2 - anchoTabla) / 2;

                page.drawRectangle({ x: xTabla, y: y - CABECERA_ALTO, width: anchoTabla, height: CABECERA_ALTO, borderColor: PDFLib.rgb(0, 0, 0), borderWidth: 1 });
                const tituloAncho = fuente.widthOfTextAtSize('Referencia', 16);
                page.drawText('Referencia', { x: xTabla + (anchoTabla - tituloAncho) / 2, y: y - CABECERA_ALTO / 2 - 6, size: 16, font: fuente });
                y -= CABECERA_ALTO;

                for (const fila of cat.filas) {
                    nuevaPaginaSiHaceFalta(FILA_ALTO);
                    let cx = xTabla;

                    if (cat.columnas === 3) {
                        page.drawRectangle({ x: cx, y: y - FILA_ALTO, width: anchoLetraCol, height: FILA_ALTO, borderColor: PDFLib.rgb(0, 0, 0), borderWidth: 1 });
                        const letraAncho = fuente.widthOfTextAtSize(fila.letra || '', 14);
                        page.drawText(fila.letra || '', { x: cx + (anchoLetraCol - letraAncho) / 2, y: y - FILA_ALTO / 2 - 5, size: 14, font: fuente });
                        cx += anchoLetraCol;
                    }

                    page.drawRectangle({ x: cx, y: y - FILA_ALTO, width: anchoNombreCol, height: FILA_ALTO, borderColor: PDFLib.rgb(0, 0, 0), borderWidth: 1 });
                    page.drawText(fila.nombre, { x: cx + PADDING_NOMBRE, y: y - FILA_ALTO / 2 - 5, size: TAMANO_NOMBRE, font: fuente });
                    cx += anchoNombreCol;

                    page.drawRectangle({ x: cx, y: y - FILA_ALTO, width: ANCHO_ICONO, height: FILA_ALTO, borderColor: PDFLib.rgb(0, 0, 0), borderWidth: 1 });
                    const centro = { x: cx + ANCHO_ICONO / 2, y: y - FILA_ALTO / 2 };

                    if (fila.url && fila.vectorial) {
                        const icono = await obtenerIconoVectorial(fila.tool);
                        if (icono) dibujarIconoVectorialEnPuntoPdf(page, icono, centro.x, centro.y, FILA_ALTO * 0.65);
                    } else if (fila.url) {
                        const dataUrl = await rasterizarIconoAPng(fila.url);
                        if (dataUrl) {
                            const png = await pdfOut.embedPng(dataUrl);
                            const tam = FILA_ALTO * 0.65;
                            const escalaImg = Math.min(tam / png.width, tam / png.height);
                            const w = png.width * escalaImg, h = png.height * escalaImg;
                            page.drawImage(png, { x: centro.x - w / 2, y: centro.y - h / 2, width: w, height: h });
                        }
                    } else if (fila.letra) {
                        page.drawCircle({ x: centro.x, y: centro.y, size: FILA_ALTO * 0.3, color: PDFLib.rgb(1, 1, 1), borderColor: PDFLib.rgb(0.6, 0.6, 0.6), borderWidth: 1 });
                        const glyphAncho = fuente.widthOfTextAtSize(fila.letra, 14);
                        page.drawText(fila.letra, { x: centro.x - glyphAncho / 2, y: centro.y - 5, size: 14, font: fuente, color: PDFLib.rgb(1, 0, 0) });
                    }

                    y -= FILA_ALTO;
                }

                y -= 24;
            }
        }

        async function descargarComoPDF(conReferencia) {
            const bytes = await generarPdfVectorial(conReferencia);
            descargarBlob(new Blob([bytes], { type: 'application/pdf' }), nombreArchivoDescarga('pdf'));
        }

        const descargaWrap = document.getElementById('descarga-wrap');
        const btnDescarga = document.getElementById('btn-descarga');
        const overlayDescarga = document.getElementById('overlay-descarga');
        const btnDescargaCancelar = document.getElementById('btn-descarga-cancelar');
        const btnDescargaConfirmar = document.getElementById('btn-descarga-confirmar');
        const checkDescargaReferencia = document.getElementById('check-descarga-referencia');
        let formatoDescarga = 'pdf';

        function abrirModalDescarga() {
            overlayDescarga.classList.add('abierto');
        }
        function cerrarModalDescarga() {
            overlayDescarga.classList.remove('abierto');
        }

        btnDescarga.addEventListener('click', e => {
            e.stopPropagation();
            abrirModalDescarga();
        });
        btnDescargaCancelar.addEventListener('click', cerrarModalDescarga);
        overlayDescarga.addEventListener('click', e => {
            if (e.target === overlayDescarga) cerrarModalDescarga();
        });

        document.querySelectorAll('.overlay-descarga-formato').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.overlay-descarga-formato').forEach(b => b.classList.remove('activo'));
                btn.classList.add('activo');
                formatoDescarga = btn.dataset.formato;
            });
        });

        btnDescargaConfirmar.addEventListener('click', async () => {
            if (!pdfDoc) return;
            btnDescargaConfirmar.disabled = true;
            btnDescargaConfirmar.textContent = 'Generando…';
            try {
                const conReferencia = checkDescargaReferencia?.checked || false;
                if (formatoDescarga === 'png') {
                    await descargarComoPNG(conReferencia);
                } else {
                    await descargarComoPDF(conReferencia);
                }
                cerrarModalDescarga();
            } catch (e) {
                console.warn('No se pudo generar la descarga', e);
                alert('No se pudo generar el archivo. Intentá de nuevo.');
            } finally {
                btnDescargaConfirmar.disabled = false;
                btnDescargaConfirmar.textContent = 'Descargar';
            }
        });

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

        function dibujarRectanguloResaltado(bbox) {
            const pad = 8;
            drawCtx.save();
            drawCtx.strokeStyle = '#2a6fdb';
            drawCtx.lineWidth = 2;
            drawCtx.setLineDash([6, 4]);
            drawCtx.strokeRect(bbox.minX - pad, bbox.minY - pad, (bbox.maxX - bbox.minX) + pad * 2, (bbox.maxY - bbox.minY) + pad * 2);
            drawCtx.restore();
        }

        function dibujarResaltadoSeleccion() {
            if (elementoSeleccionado) {
                const bbox = calcularBBoxPantalla(elementoSeleccionado);
                if (bbox) dibujarRectanguloResaltado(bbox);
            }

            seleccionMultiple.forEach(item => {
                const bbox = calcularBBoxPantalla(item);
                if (bbox) dibujarRectanguloResaltado(bbox);
            });

            if (dibujandoRectangulo && rectSeleccion) {
                const pA = mundoAPantalla(rectSeleccion.inicio.x, rectSeleccion.inicio.y);
                const pB = mundoAPantalla(rectSeleccion.actual.x, rectSeleccion.actual.y);
                const x = Math.min(pA.x, pB.x);
                const y = Math.min(pA.y, pB.y);
                const w = Math.abs(pB.x - pA.x);
                const h = Math.abs(pB.y - pA.y);
                drawCtx.save();
                drawCtx.fillStyle = 'rgba(42,111,219,0.1)';
                drawCtx.strokeStyle = '#2a6fdb';
                drawCtx.lineWidth = 1.5;
                drawCtx.setLineDash([5, 4]);
                drawCtx.fillRect(x, y, w, h);
                drawCtx.strokeRect(x, y, w, h);
                drawCtx.restore();
            }
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

            /* anchoBase/altoBase (y por lo tanto la posición "mundo" de
               cada elemento dibujado) se miden en puntos del PDF, un
               tamaño fijo del documento — no en píxeles de pantalla, que
               varían según el dispositivo. Así, algo dibujado en una
               tablet cae en el mismo lugar al abrir el plano en una
               computadora. El ajuste a cada tamaño de pantalla lo hace
               vista.scale (ver centrarVista), no anchoBase/altoBase. */
            anchoBase = viewportBase.width;
            altoBase = viewportBase.height;

            /* Igual que en reRenderNitidez, el factor inicial no puede
               superar calcularFactorMaxSeguro() — si lo hiciera (hojas
               grandes tipo A1/A0 con dpr alto), factorActual arrancaría ya
               por encima del tope y la nitidez quedaría congelada para
               siempre al hacer zoom (ver el comentario en
               calcularFactorMaxSeguro). */
            const factorInicial = Math.min(SOBREMUESTREO, calcularFactorMaxSeguro());
            const viewportRender = pagina.getViewport({ scale: factorInicial, rotation: rotacionPlano });

            pdfCanvas.width = viewportRender.width;
            pdfCanvas.height = viewportRender.height;
            pdfCanvas.style.width = anchoBase + 'px';
            pdfCanvas.style.height = altoBase + 'px';

            await pagina.render({ canvasContext: pdfCtx, viewport: viewportRender }).promise;

            factorActual = factorInicial;
            estadoPlano.trazos = [];
            deseleccionarElemento();
            deseleccionarMultiple();
            cargarEstadoGuardado();
            await fusionarEstadoLocalPendiente();
            dispararSubidaFotosPendientes();
            actualizarPendientes();
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
        const inputFotoGaleria = document.getElementById('input-foto-galeria');
        const overlayOrigenFoto = document.getElementById('overlay-origen-foto');
        const btnOrigenCamara = document.getElementById('btn-origen-camara');
        const btnOrigenGaleria = document.getElementById('btn-origen-galeria');
        const btnOrigenCancelar = document.getElementById('btn-origen-cancelar');
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
        /* Object URL del blob que se está mostrando en el visor grande
           cuando la foto todavía no se subió (referencia 'local:<id>').
           Se revoca al cambiar de foto o cerrar el visor para no
           acumular memoria en una sesión larga. */
        let overlayObjectUrlActual = null;

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
            overlayOrigenFoto.classList.add('abierto');
        }

        function solicitarAgregarFotos(item) {
            contextoFotoPendiente = { modo: 'agregar', item };
            overlayOrigenFoto.classList.add('abierto');
        }

        btnOrigenCamara.addEventListener('click', () => {
            overlayOrigenFoto.classList.remove('abierto');
            inputFoto.value = '';
            inputFoto.click();
        });
        btnOrigenGaleria.addEventListener('click', () => {
            overlayOrigenFoto.classList.remove('abierto');
            inputFotoGaleria.value = '';
            inputFotoGaleria.click();
        });
        btnOrigenCancelar.addEventListener('click', () => {
            overlayOrigenFoto.classList.remove('abierto');
            contextoFotoPendiente = null;
        });

        /* El servidor (regla "image" de Laravel) no acepta HEIC/HEIF, el
           formato en que el iPhone guarda las fotos por defecto — una
           foto elegida de la galería (no tomada en el momento) puede
           venir en ese formato y la subida se rechaza en silencio (queda
           "pendiente" para siempre, sin ningún aviso visible). Safari sí
           puede decodificar HEIC de forma nativa, así que se aprovecha
           eso para convertir a JPEG en el propio dispositivo antes de
           guardar/subir nada — sin depender de ninguna librería. */
        function esHeic(archivo) {
            const tipo = (archivo.type || '').toLowerCase();
            if (tipo === 'image/heic' || tipo === 'image/heif') return true;
            if (tipo) return false;
            const nombre = (archivo.name || '').toLowerCase();
            return nombre.endsWith('.heic') || nombre.endsWith('.heif');
        }

        function convertirHeicAJpeg(archivo) {
            return new Promise((resolve) => {
                const url = URL.createObjectURL(archivo);
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    canvas.width = img.naturalWidth;
                    canvas.height = img.naturalHeight;
                    canvas.getContext('2d').drawImage(img, 0, 0);
                    canvas.toBlob((blob) => {
                        URL.revokeObjectURL(url);
                        resolve(blob || archivo); // sin soporte para decodificar HEIC: se sigue con el original
                    }, 'image/jpeg', 0.9);
                };
                img.onerror = () => {
                    URL.revokeObjectURL(url);
                    resolve(archivo);
                };
                img.src = url;
            });
        }

        /* Las fotos se guardan primero en el dispositivo (IndexedDB) y el
           pin se crea al instante, sin esperar a que se suban: así,
           sacar una foto en el campo nunca se bloquea ni se pierde por
           falta de señal. En estadoPlano.fotos queda una referencia
           'local:<id>' hasta que dispararSubidaFotosPendientes() logre
           subirla y la reemplace por la URL real del servidor. */
        async function manejarArchivosFoto(input) {
            const archivos = Array.from(input.files || []);
            const contexto = contextoFotoPendiente;
            contextoFotoPendiente = null;
            if (!archivos.length || !contexto) return;

            const refs = [];
            for (const archivoOriginal of archivos) {
                const archivo = esHeic(archivoOriginal) ? await convertirHeicAJpeg(archivoOriginal) : archivoOriginal;
                const id = generarIdElemento();
                await OfflineAPI?.guardarFotoPendiente(id, PLANO_ID, archivo, archivo.type);
                refs.push('local:' + id);
            }

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
                    fotos: refs,
                });
                redibujarTrazos();
            } else {
                contexto.item.fotos.push(...refs);
                fotoAbiertaIndice = contexto.item.fotos.length - refs.length;
                actualizarOverlayFoto();
            }
            programarGuardado();
            dispararSubidaFotosPendientes();
        }

        inputFoto.addEventListener('change', () => manejarArchivosFoto(inputFoto));
        inputFotoGaleria.addEventListener('change', () => manejarArchivosFoto(inputFotoGaleria));
        inputFoto.addEventListener('cancel', () => { contextoFotoPendiente = null; });
        inputFotoGaleria.addEventListener('cancel', () => { contextoFotoPendiente = null; });

        /* Sube en segundo plano las fotos que quedaron pendientes (sin
           conexión al sacarlas, o la subida falló). Se llama apenas se
           agrega una foto, al reconectar y periódicamente como respaldo
           (ver PlanoOffline.iniciarReintentos más abajo). */
        function dispararSubidaFotosPendientes() {
            OfflineAPI?.subirFotosPendientes(PLANO_ID, {
                urlSubirFoto,
                csrfToken: CSRF_TOKEN,
                resolverItemPorFotoId: (id) => estadoPlano.trazos.find(
                    t => Array.isArray(t.fotos) && t.fotos.includes('local:' + id)
                ) || null,
            }).then(subioAlguna => {
                if (!subioAlguna) return;
                if (fotoAbiertaItem) actualizarOverlayFoto();
                programarGuardado();
            });
        }

        async function actualizarOverlayFoto() {
            if (!fotoAbiertaItem) return;
            const fotos = fotoAbiertaItem.fotos || [];
            if (!fotos.length) { cerrarFotoGrande(); return; }
            fotoAbiertaIndice = clamp(fotoAbiertaIndice, 0, fotos.length - 1);
            const item = fotoAbiertaItem; // por si cambia mientras se resuelve el blob
            const indice = fotoAbiertaIndice;
            const foto = fotos[indice];

            if (overlayObjectUrlActual) {
                URL.revokeObjectURL(overlayObjectUrlActual);
                overlayObjectUrlActual = null;
            }

            if (foto.startsWith('local:')) {
                const blob = await OfflineAPI?.obtenerBlobFotoPendiente(foto.slice('local:'.length));
                if (item !== fotoAbiertaItem || indice !== fotoAbiertaIndice) return; // el usuario ya navegó a otra
                if (blob) {
                    overlayObjectUrlActual = URL.createObjectURL(blob);
                    overlayFotoImg.src = overlayObjectUrlActual;
                } else {
                    overlayFotoImg.src = '';
                }
            } else {
                overlayFotoImg.src = foto;
            }

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
            if (overlayObjectUrlActual) {
                URL.revokeObjectURL(overlayObjectUrlActual);
                overlayObjectUrlActual = null;
            }
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
            const [fotoQuitada] = fotoAbiertaItem.fotos.splice(fotoAbiertaIndice, 1);
            if (fotoQuitada?.startsWith('local:')) {
                OfflineAPI?.eliminarFotoPendiente(fotoQuitada.slice('local:'.length));
            }
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

        /* ─── Selección múltiple: arrastrar un rectángulo y agarrar todo
             lo que quede completamente adentro (respetando capas ocultas),
             para moverlo o borrarlo en bloque. ─ */
        const panelSeleccionMultiple = document.getElementById('panel-seleccion-multiple');
        const btnMultiMover = document.getElementById('btn-multi-mover');
        const btnMultiEliminar = document.getElementById('btn-multi-eliminar');
        const multiCantidadEl = document.getElementById('multi-cantidad');

        let seleccionMultiple = [];
        let modoMoverMultiple = false;
        let arrastrandoMoverMultiple = false;
        let arrastreMoverMultipleInicio = null;
        let arrastreMoverMultipleOrigen = null;
        let dibujandoRectangulo = false;
        let rectSeleccion = null;

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
            solicitarRedibujado();
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

        function bboxUnion(bboxes) {
            return {
                minX: Math.min(...bboxes.map(b => b.minX)),
                maxX: Math.max(...bboxes.map(b => b.maxX)),
                minY: Math.min(...bboxes.map(b => b.minY)),
                maxY: Math.max(...bboxes.map(b => b.maxY)),
            };
        }

        function mostrarPanelSeleccionMultiple() {
            panelSeleccionMultiple.classList.add('abierto');
            posicionarPanelSeleccionMultiple();
        }

        function ocultarPanelSeleccionMultiple() {
            panelSeleccionMultiple.classList.remove('abierto');
        }

        function posicionarPanelSeleccionMultiple() {
            const bboxes = seleccionMultiple.map(calcularBBoxPantalla).filter(Boolean);
            if (!bboxes.length) { ocultarPanelSeleccionMultiple(); return; }
            const bbox = bboxUnion(bboxes);
            const cx = (bbox.minX + bbox.maxX) / 2;
            panelSeleccionMultiple.style.left = cx + 'px';
            panelSeleccionMultiple.style.top = Math.max(bbox.minY - 14, 12) + 'px';
        }

        function seleccionarMultiple(items) {
            seleccionMultiple = items;
            modoMoverMultiple = false;
            btnMultiMover.classList.remove('activo');
            multiCantidadEl.textContent = items.length;
            if (items.length) mostrarPanelSeleccionMultiple(); else ocultarPanelSeleccionMultiple();
            redibujarTrazos();
        }

        function deseleccionarMultiple() {
            seleccionMultiple = [];
            modoMoverMultiple = false;
            arrastrandoMoverMultiple = false;
            btnMultiMover.classList.remove('activo');
            ocultarPanelSeleccionMultiple();
            dibujandoRectangulo = false;
            rectSeleccion = null;
        }

        /* "Completamente contenido": el bbox del elemento tiene que caer
           entero dentro del rectángulo, no alcanza con que se toquen. */
        function elementoDentroDeRectangulo(item, rectPantalla) {
            const bbox = calcularBBoxPantalla(item);
            if (!bbox) return false;
            return bbox.minX >= rectPantalla.minX && bbox.maxX <= rectPantalla.maxX &&
                bbox.minY >= rectPantalla.minY && bbox.maxY <= rectPantalla.maxY;
        }

        function seleccionarElementosEnRectangulo(mundoA, mundoB) {
            const pA = mundoAPantalla(mundoA.x, mundoA.y);
            const pB = mundoAPantalla(mundoB.x, mundoB.y);
            const rectPantalla = {
                minX: Math.min(pA.x, pB.x), maxX: Math.max(pA.x, pB.x),
                minY: Math.min(pA.y, pB.y), maxY: Math.max(pA.y, pB.y),
            };
            const seleccionados = estadoPlano.trazos.filter(item => {
                if (capasVisibles[item.tool] === false) return false;
                return elementoDentroDeRectangulo(item, rectPantalla);
            });
            seleccionarMultiple(seleccionados);
        }

        function iniciarArrastreMoverMultiple(mundo) {
            arrastrandoMoverMultiple = true;
            arrastreMoverMultipleInicio = mundo;
            arrastreMoverMultipleOrigen = seleccionMultiple.map(item => ({
                item,
                puntos: item.puntos ? item.puntos.map(p => ({ x: p.x, y: p.y })) : null,
                x: item.x,
                y: item.y,
            }));
            ocultarPanelSeleccionMultiple();
        }

        function moverSeleccionMultiple(mundo) {
            if (!arrastreMoverMultipleInicio || !arrastreMoverMultipleOrigen) return;
            const dx = mundo.x - arrastreMoverMultipleInicio.x;
            const dy = mundo.y - arrastreMoverMultipleInicio.y;
            arrastreMoverMultipleOrigen.forEach(({ item, puntos, x, y }) => {
                if (puntos) {
                    item.puntos = puntos.map(p => ({ x: p.x + dx, y: p.y + dy }));
                } else {
                    item.x = x + dx;
                    item.y = y + dy;
                }
            });
            solicitarRedibujado();
        }

        function finalizarArrastreMoverMultiple() {
            arrastrandoMoverMultiple = false;
            modoMoverMultiple = false;
            btnMultiMover.classList.remove('activo');
            if (seleccionMultiple.length) mostrarPanelSeleccionMultiple();
            programarGuardado();
            aplicarEstadoPendienteSiHay();
        }

        function eliminarSeleccionMultiple() {
            if (!PUEDE_ELIMINAR) return;
            if (!seleccionMultiple.length) return;
            const capasAfectadas = new Set(seleccionMultiple.map(item => item.tool));
            seleccionMultiple.forEach(item => {
                const idx = estadoPlano.trazos.indexOf(item);
                if (idx !== -1) estadoPlano.trazos.splice(idx, 1);
            });
            deseleccionarMultiple();
            capasAfectadas.forEach(tool => quitarCapaSiVacia(tool));
            redibujarTrazos();
            programarGuardado();
        }

        btnMultiMover.addEventListener('click', () => {
            if (!PUEDE_EDITAR) return;
            if (!seleccionMultiple.length) return;
            modoMoverMultiple = !modoMoverMultiple;
            btnMultiMover.classList.toggle('activo', modoMoverMultiple);
        });

        btnMultiEliminar.addEventListener('click', eliminarSeleccionMultiple);

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

            if (herramientaActual === 'seleccion_multiple') {
                if (modoMoverMultiple && seleccionMultiple.length) {
                    iniciarArrastreMoverMultiple(mundoPunto);
                    return;
                }
                dibujandoRectangulo = true;
                rectSeleccion = { inicio: puntosMundo[0], actual: mundoPunto };
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
            if (panelSeleccionMultiple.contains(e.target)) return;
            if (edicionTextoPendiente) cerrarInputTexto(true);
            lienzoWrap.setPointerCapture(e.pointerId);
            punterosActivos.set(e.pointerId, { x: e.clientX, y: e.clientY });

            if (punterosActivos.size === 2) {
                cancelarPunteroPendiente();
                dibujando = false;
                trazoActual = null;
                if (arrastrandoMover) finalizarArrastreMover();
                if (arrastrandoMoverMultiple) finalizarArrastreMoverMultiple();
                dibujandoRectangulo = false;
                rectSeleccion = null;
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

            if (arrastrandoMoverMultiple) {
                const pantalla = posicionPantalla(e);
                moverSeleccionMultiple(pantallaAMundo(pantalla.x, pantalla.y));
                return;
            }

            if (dibujandoRectangulo && rectSeleccion) {
                const pantalla = posicionPantalla(e);
                rectSeleccion.actual = pantallaAMundo(pantalla.x, pantalla.y);
                solicitarRedibujado();
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
                    solicitarRedibujado();
                } else if (tipoHerramienta === 'circulo' || tipoHerramienta === 'rectangulo') {
                    const generarPuntos = tipoHerramienta === 'circulo' ? puntosCirculo : puntosRectangulo;
                    trazoActual.puntos = generarPuntos(trazoActual.puntoInicio, mundo);
                    solicitarRedibujado();
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

            if (arrastrandoMoverMultiple) {
                punterosActivos.delete(e.pointerId);
                finalizarArrastreMoverMultiple();
                return;
            }

            if (dibujandoRectangulo) {
                punterosActivos.delete(e.pointerId);
                const rect = rectSeleccion;
                dibujandoRectangulo = false;
                rectSeleccion = null;
                if (rect && e.type === 'pointerup') {
                    const pA = mundoAPantalla(rect.inicio.x, rect.inicio.y);
                    const pB = mundoAPantalla(rect.actual.x, rect.actual.y);
                    if (Math.hypot(pB.x - pA.x, pB.y - pA.y) < 6) {
                        deseleccionarMultiple();
                    } else {
                        seleccionarElementosEnRectangulo(rect.inicio, rect.actual);
                    }
                }
                redibujarTrazos();
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

        /* Reintento de guardado/subida: al reconectar (evento 'online') y
           cada ~25s como respaldo, para el caso de conexión "técnicamente
           online" pero inestable donde ese evento no siempre avisa. */
        OfflineAPI?.iniciarReintentos({
            onIntentar: () => { guardarEstadoPlano(); dispararSubidaFotosPendientes(); },
        });

        cargarPdf();
    </script>
</body>
</html>
