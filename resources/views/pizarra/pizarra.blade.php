<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relevamiento de encofrado</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body { height: 100%; }

        body {
            background: #ffffff;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* ── Insertar (arriba a la derecha) ── */
        #insertar {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 11; /* por encima de Seleccionar, para que los submenús lo tapen */
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Botón principal */
        #btnInsertar {
            min-width: 190px;
            display: flex;
            align-items: center;
            gap: 10px;
            height: 48px;
            padding: 0 20px 0 8px;
            border: none;
            border-radius: 24px;
            background: linear-gradient(135deg, #1e5aa8 0%, #0f3460 100%);
            color: #fff;
            font: 600 15px/1 inherit;
            font-family: inherit;
            letter-spacing: 0.3px;
            cursor: pointer;
            box-shadow:
                0 6px 16px rgba(15, 52, 96, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        #btnInsertar:hover {
            transform: translateY(-1px);
            box-shadow:
                0 10px 22px rgba(15, 52, 96, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }
        #btnInsertar:active { transform: translateY(0); }
        #btnInsertar:focus-visible { outline: 3px solid #8cc2ff; outline-offset: 2px; }

        #btnInsertar .icono {
            display: grid;
            place-items: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.18);
            transition: transform 0.25s ease, background 0.25s ease;
        }
        #insertar.abierto #btnInsertar .icono {
            transform: rotate(45deg);
            background: rgba(255, 255, 255, 0.28);
        }

        /* Menú desplegable hacia la izquierda */
        #menuInsertar {
            display: flex;
            flex-direction: row-reverse; /* la primera opción queda pegada al botón */
            gap: 8px;
            pointer-events: none;
        }
        #insertar.abierto #menuInsertar { pointer-events: auto; }

        .opcion {
            display: flex;
            align-items: center;
            gap: 8px;
            height: 44px;
            padding: 0 16px 0 6px;
            border: 1px solid #dbe4ef;
            border-radius: 22px;
            background: #fff;
            color: #0f3460;
            font: 600 14px/1 inherit;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(15, 52, 96, 0.12);

            /* estado cerrado: escondida y desplazada hacia el botón */
            opacity: 0;
            transform: translateX(24px) scale(0.9);
            transition:
                opacity 0.2s ease,
                transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1),
                background 0.15s ease,
                border-color 0.15s ease;
        }
        #insertar.abierto .opcion {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
        /* escalonado: cada opción aparece un poco después que la anterior */
        #insertar.abierto .grupo:nth-child(2) .opcion { transition-delay: 0.05s, 0.05s, 0s, 0s; }
        #insertar.abierto .grupo:nth-child(3) .opcion { transition-delay: 0.1s, 0.1s, 0s, 0s; }
        #insertar.abierto .grupo:nth-child(4) .opcion { transition-delay: 0.15s, 0.15s, 0s, 0s; }

        .opcion:hover { background: #f0f6fd; border-color: #a9c4dc; }
        .opcion:focus-visible { outline: 3px solid #8cc2ff; outline-offset: 2px; }

        .opcion .icono {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #e8f0fa;
            color: #1e5aa8;
        }
        .opcion.activa { background: #f0f6fd; border-color: #1e5aa8; }

        /* ── Submenú de Pilar (sale hacia abajo desde el botón) ── */
        .grupo { position: relative; }

        .submenu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 230px;
            padding: 12px;
            background: #fff;
            border: 1px solid #dbe4ef;
            border-radius: 14px;
            box-shadow: 0 10px 28px rgba(15, 52, 96, 0.18);

            opacity: 0;
            transform: translateY(-8px) scale(0.97);
            transform-origin: top right;
            pointer-events: none;
            transition: opacity 0.18s ease, transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .submenu.abierto {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }
        /* flechita apuntando al botón Pilar */
        .submenu::before {
            content: '';
            position: absolute;
            top: -6px;
            right: 28px;
            width: 10px;
            height: 10px;
            background: #fff;
            border-left: 1px solid #dbe4ef;
            border-top: 1px solid #dbe4ef;
            transform: rotate(45deg);
        }

        .paso[hidden] { display: none; }

        .submenu-titulo {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
            color: #5a6b80;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-volver {
            display: grid;
            place-items: center;
            width: 24px;
            height: 24px;
            border: none;
            border-radius: 50%;
            background: #eef3f8;
            color: #0f3460;
            cursor: pointer;
        }
        .btn-volver:hover { background: #dde8f4; }

        .subopcion {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 8px 10px;
            border: none;
            border-radius: 10px;
            background: none;
            color: #0f3460;
            font: 600 14px/1 inherit;
            font-family: inherit;
            text-align: left;
            cursor: pointer;
        }
        .subopcion + .subopcion { margin-top: 4px; }
        .subopcion:hover { background: #f0f6fd; }
        .subopcion .icono {
            display: grid;
            place-items: center;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #e8f0fa;
            color: #1e5aa8;
        }

        .campo { display: block; margin-bottom: 10px; }
        .campo span {
            display: block;
            margin-bottom: 4px;
            color: #33475e;
            font-size: 13px;
            font-weight: 600;
        }
        .campo .entrada {
            display: flex;
            align-items: center;
            border: 1px solid #c9d6e5;
            border-radius: 8px;
            overflow: hidden;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .campo .entrada:focus-within {
            border-color: #1e5aa8;
            box-shadow: 0 0 0 3px rgba(30, 90, 168, 0.15);
        }
        .campo input {
            flex: 1;
            min-width: 0;
            padding: 8px 10px;
            border: none;
            outline: none;
            font: 14px inherit;
            font-family: inherit;
        }
        .campo .unidad {
            padding: 0 10px;
            color: #7a8a9e;
            font-size: 13px;
        }

        .btn-agregar {
            width: 100%;
            margin-top: 4px;
            padding: 10px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #1e5aa8 0%, #0f3460 100%);
            color: #fff;
            font: 600 14px/1 inherit;
            font-family: inherit;
            cursor: pointer;
        }
        .btn-agregar:hover { filter: brightness(1.15); }

        /* ── Seleccionar (debajo de Insertar) ── */
        #btnSeleccionar {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 10;
            min-width: 190px;
            display: flex;
            align-items: center;
            gap: 10px;
            height: 48px;
            padding: 0 20px 0 8px;
            border: 1px solid #c9d6e5;
            border-radius: 24px;
            background: #fff;
            color: #0f3460;
            font: 600 15px/1 inherit;
            font-family: inherit;
            letter-spacing: 0.3px;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(15, 52, 96, 0.15);
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.2s ease, color 0.2s ease;
        }
        #btnSeleccionar:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(15, 52, 96, 0.22);
        }
        #btnSeleccionar:focus-visible { outline: 3px solid #8cc2ff; outline-offset: 2px; }
        #btnSeleccionar .icono {
            display: grid;
            place-items: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e8f0fa;
            transition: background 0.2s ease;
        }
        /* activo: mismo estilo que Insertar */
        #btnSeleccionar.activo,
        #btnCotas.activo,
        #btnIdent.activo,
        #btnDim.activo,
        #btnElem.activo {
            border-color: transparent;
            background: linear-gradient(135deg, #1e5aa8 0%, #0f3460 100%);
            color: #fff;
        }
        #btnSeleccionar.activo .icono,
        #btnCotas.activo .icono,
        #btnIdent.activo .icono,
        #btnDim.activo .icono,
        #btnElem.activo .icono { background: rgba(255, 255, 255, 0.18); }

        /* ── Identificación (debajo de Cotas); por debajo en z para que el panel de Cotas lo tape ── */
        #identCtl { top: 200px !important; z-index: 9 !important; }
        /* Dimensiones y Elementos, más abajo (cada uno por debajo en z del de arriba) */
        #dimCtl  { top: 260px !important; z-index: 8 !important; }
        #elemCtl { top: 320px !important; z-index: 7 !important; }

        /* Etiquetas de dimensiones: fondo blanco con borde del color del elemento */
        #dimensiones { pointer-events: none; }
        .dim.pilar { --color-etiqueta: #0f3460; }
        .dim.viga  { --color-etiqueta: #2e7d4f; }
        .dim.losa  { --color-etiqueta: #8a6408; }

        /* Elementos ocultos */
        body.oculto-pilar #elementos,
        body.oculto-viga #vigas,
        body.oculto-losa #losas,
        body.oculto-hueco #huecos,
        body.oculto-voladizo #voladizos { display: none; }

        /* Etiquetas de identificación */
        #identificacion { pointer-events: none; }
        .ident.pilar { --color-etiqueta: #0f3460; }
        .ident.viga  { --color-etiqueta: #2e7d4f; }
        .ident.losa  { --color-etiqueta: #8a6408; }

        /* ── Cotas (debajo de Seleccionar) ── */
        #cotasCtl,
        #identCtl,
        #dimCtl,
        #elemCtl {
            position: fixed;
            top: 140px;
            right: 20px;
            z-index: 10;
        }
        #btnCotas,
        #btnIdent,
        #btnDim,
        #btnElem {
            min-width: 190px;
            display: flex;
            align-items: center;
            gap: 10px;
            height: 48px;
            padding: 0 20px 0 8px;
            border: 1px solid #c9d6e5;
            border-radius: 24px;
            background: #fff;
            color: #0f3460;
            font: 600 15px/1 inherit;
            font-family: inherit;
            letter-spacing: 0.3px;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(15, 52, 96, 0.15);
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.2s ease, color 0.2s ease;
        }
        #btnCotas:hover,
        #btnIdent:hover,
        #btnDim:hover,
        #btnElem:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(15, 52, 96, 0.22);
        }
        #btnCotas:focus-visible,
        #btnIdent:focus-visible,
        #btnDim:focus-visible,
        #btnElem:focus-visible { outline: 3px solid #8cc2ff; outline-offset: 2px; }
        #btnCotas .icono,
        #btnIdent .icono,
        #btnDim .icono,
        #btnElem .icono {
            display: grid;
            place-items: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e8f0fa;
            transition: background 0.2s ease;
        }

        /* Interruptor on/off */
        .interruptor {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 4px;
            color: #0f3460;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .interruptor input { position: absolute; opacity: 0; pointer-events: none; }
        .interruptor .deslizador {
            position: relative;
            flex-shrink: 0;
            width: 38px;
            height: 22px;
            border-radius: 11px;
            background: #c9d6e5;
            transition: background 0.2s ease;
        }
        .interruptor .deslizador::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
            transition: transform 0.2s ease;
        }
        .interruptor input:checked + .deslizador { background: #1e5aa8; }
        .interruptor input:checked + .deslizador::after { transform: translateX(16px); }
        .interruptor input:focus-visible + .deslizador { outline: 3px solid #8cc2ff; outline-offset: 2px; }

        /* ── Pizarra ── */
        #pizarra {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
        }
        #pizarra { cursor: grab; }
        body.colocando #pizarra { cursor: crosshair; }
        body.arrastrando #pizarra { cursor: grabbing; }
        body.seleccionando #pizarra { cursor: default; }
        body.seleccionando .pilar:hover,
        body.seleccionando .viga:hover,
        body.seleccionando .losa:hover,
        body.seleccionando .hueco:hover { stroke-width: 2.5; cursor: pointer; }
        body.seleccionando .voladizo:hover { cursor: pointer; }
        body.seleccionando .voladizo:hover .linea { stroke-width: 3; }

        /* Voladizo: borde libre de la losa (línea sin ancho); la línea ancha transparente facilita el click */
        .voladizo .linea {
            stroke: #0e7490;
            stroke-width: 2;
            stroke-dasharray: 8 4;
            stroke-linecap: round;
            vector-effect: non-scaling-stroke;
        }
        .voladizo .toque {
            stroke: transparent;
            stroke-width: 12;
            vector-effect: non-scaling-stroke;
        }
        .voladizo.seleccionado .linea { stroke: #e67e22; stroke-width: 3; }
        #fantasma .toque { display: none; }

        .losa {
            fill: rgba(212, 160, 23, 0.14);
            stroke: #b7860b;
            stroke-width: 1;
            fill-rule: evenodd;
            vector-effect: non-scaling-stroke;
        }
        /* Hueco: contorno con una X adentro */
        .hueco {
            fill: #fff;
            stroke: #4a5568;
            stroke-width: 1.5;
            stroke-linejoin: round;
            vector-effect: non-scaling-stroke;
        }
        /* contorno del hueco mientras se dibuja */
        .hueco-previa {
            fill: rgba(74, 85, 104, 0.1);
            stroke: #4a5568;
            stroke-width: 1.5;
            stroke-dasharray: 6 4;
            stroke-linejoin: round;
            vector-effect: non-scaling-stroke;
            pointer-events: none;
        }
        .cota.hueco { --color-etiqueta: #4a5568; }

        /* vista previa del paño bajo el mouse */
        #previaLosa {
            fill: rgba(212, 160, 23, 0.22);
            stroke: #b7860b;
            stroke-width: 1.5;
            stroke-dasharray: 6 4;
            fill-rule: evenodd;
            vector-effect: non-scaling-stroke;
            pointer-events: none;
        }

        /* Aviso temporal (abajo al centro) */
        #aviso {
            position: fixed;
            left: 50%;
            bottom: 24px;
            z-index: 30;
            max-width: 90vw;
            padding: 12px 18px;
            border-radius: 10px;
            background: #7f1d1d;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.25);
            transform: translateX(-50%);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }
        #aviso.oculto { opacity: 0; transform: translate(-50%, 12px); pointer-events: none; }

        /* Elementos seleccionados */
        .pilar.seleccionado,
        .viga.seleccionado,
        .losa.seleccionado,
        .hueco.seleccionado {
            fill: rgba(230, 126, 34, 0.25);
            stroke: #e67e22;
            stroke-width: 2.5;
        }

        body.moviendo #pizarra { cursor: crosshair; }

        /* Barra de acciones sobre la selección */
        #acciones {
            position: fixed;
            z-index: 9;
            display: flex;
            gap: 4px;
            padding: 4px;
            border: 1px solid #dbe4ef;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 6px 16px rgba(15, 52, 96, 0.18);
            transform: translate(-50%, calc(-100% - 12px)); /* centrada, encima del punto */
        }
        #acciones[hidden] { display: none; }
        #acciones button {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border: none;
            border-radius: 8px;
            background: none;
            color: #0f3460;
            font: 600 13px/1 inherit;
            font-family: inherit;
            cursor: pointer;
        }
        #acciones button:hover { background: #f0f6fd; }
        #acciones button.peligro { color: #c0392b; }
        #acciones button.peligro:hover { background: #fde8e8; }

        /* Lazo de selección */
        #lazo {
            fill: rgba(30, 90, 168, 0.08);
            stroke: #1e5aa8;
            stroke-width: 1.2;
            stroke-dasharray: 5 4;
            vector-effect: non-scaling-stroke;
            pointer-events: none;
        }

        .pilar {
            fill: rgba(30, 90, 168, 0.18);
            stroke: #0f3460;
            stroke-width: 1.5;
            vector-effect: non-scaling-stroke; /* el borde no engorda con el zoom */
        }
        /* Guías: alineación y cota de distancia */
        #guias { pointer-events: none; }
        .guia-snap {
            stroke: #e67e22;
            stroke-width: 1;
            stroke-dasharray: 6 4;
            vector-effect: non-scaling-stroke;
        }
        .cota line {
            stroke: #c0392b;
            stroke-width: 1.2;
            vector-effect: non-scaling-stroke;
        }
        .cota circle { fill: #c0392b; }
        /* Etiquetas de texto (cotas, identificación, dimensiones): interior blanco, borde y texto del color */
        .etiqueta rect {
            fill: #fff;
            stroke: var(--color-etiqueta, #0f3460);
            stroke-width: 1.2;
            vector-effect: non-scaling-stroke;
        }
        .etiqueta text {
            stroke: none; /* los grupos de identificación/dimensiones llevan la clase pilar/viga/losa y heredarían su borde */
            fill: var(--color-etiqueta, #0f3460);
            font-family: 'Segoe UI', system-ui, sans-serif;
            font-weight: 600;
        }
        .cota { --color-etiqueta: #c0392b; }
        .cota.superpuesto { --color-etiqueta: #7f1d1d; }
        .cota.largo { --color-etiqueta: #2e7d4f; }
        #cotas { pointer-events: none; }
        .cota.largo line { stroke: #2e7d4f; }
        .cota.largo circle { fill: #2e7d4f; }
        .cota.sep-viga line { stroke: #7b3fa0; }
        .cota.sep-viga circle { fill: #7b3fa0; }
        .cota.sep-viga { --color-etiqueta: #7b3fa0; }
        .cota.mover line { stroke: #1e5aa8; stroke-dasharray: 6 4; }
        .cota.mover circle { fill: #1e5aa8; }
        .cota.mover { --color-etiqueta: #1e5aa8; }

        .viga {
            fill: rgba(46, 125, 79, 0.18);
            stroke: #2e7d4f;
            stroke-width: 1.5;
            vector-effect: non-scaling-stroke;
        }
        .marca-snap {
            fill: none;
            stroke: #e67e22;
            stroke-width: 1.8;
            vector-effect: non-scaling-stroke;
        }

        /* vista previa que sigue al mouse */
        #fantasma {
            pointer-events: none;
            opacity: 0.5;
            stroke-dasharray: 4 3;
        }

        /* ── Caja de distancia exacta (Tab) ── */
        #distancia {
            position: fixed;
            z-index: 20;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px 4px 4px;
            border: 1px solid #c0392b;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
        }
        #distancia[hidden] { display: none; }
        #distancia .flecha {
            display: grid;
            place-items: center;
            width: 26px;
            height: 26px;
            border-radius: 7px;
            background: #c0392b;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
        }
        #distancia input {
            width: 64px;
            padding: 4px 2px;
            border: none;
            outline: none;
            font: 600 15px inherit;
            font-family: inherit;
            text-align: right;
        }
        #distancia .unidad { color: #7a8a9e; font-size: 13px; }

        /* ── Indicador de lo que se va a insertar (abajo a la izquierda) ── */
        #estado {
            position: fixed;
            left: 20px;
            bottom: 20px;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 10px;
            height: 44px;
            padding: 0 6px 0 8px;
            border: 1px solid #dbe4ef;
            border-radius: 22px;
            background: #fff;
            color: #0f3460;
            box-shadow: 0 4px 12px rgba(15, 52, 96, 0.12);
            font-size: 14px;
        }
        #estado[hidden] { display: none; }
        #estado .icono {
            display: grid;
            place-items: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e8f0fa;
            color: #1e5aa8;
        }
        #estado .etiqueta { color: #7a8a9e; font-size: 12px; }
        #estado strong { font-weight: 600; }
        #estado .ayuda {
            padding-left: 10px;
            border-left: 1px solid #dbe4ef;
            color: #7a8a9e;
            font-size: 12px;
        }
        #btnCancelar {
            display: grid;
            place-items: center;
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 50%;
            background: #eef3f8;
            color: #5a6b80;
            cursor: pointer;
        }
        #btnCancelar:hover { background: #fde8e8; color: #c0392b; }
    </style>
</head>
<body>
    <svg id="pizarra" xmlns="http://www.w3.org/2000/svg">
        <!-- "vista" lleva el zoom y el desplazamiento; todo lo dibujado va adentro -->
        <g id="vista">
            <g id="losas"></g>     <!-- abajo de todo -->
            <g id="huecos"></g>    <!-- sobre las losas que cortan, debajo de vigas y pilares -->
            <g id="vigas"></g>     <!-- debajo, para que los pilares queden encima -->
            <g id="voladizos"></g> <!-- bordes libres de losa -->
            <g id="elementos"></g>
            <g id="cotas"></g>
            <g id="identificacion"></g>
            <g id="dimensiones"></g>
            <g id="guias"></g>
        </g>
    </svg>
    
    <div id="estado" hidden aria-live="polite">
        <span class="icono" id="estadoIcono"></span>
        <span><span class="etiqueta">Insertando</span> <strong id="estadoTexto"></strong></span>
        <span class="ayuda" id="estadoAyuda"></span>
        <button type="button" id="btnCancelar" aria-label="Dejar de insertar" title="Dejar de insertar (Esc)">
            <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 3l10 10M13 3L3 13"/></svg>
        </button>
    </div>

    <!-- Caja para tipear una distancia exacta (se abre con Tab) -->
    <div id="distancia" hidden>
        <span id="flechaDistancia" class="flecha"></span>
        <input type="text" id="inputDistancia" inputmode="decimal" autocomplete="off" aria-label="Distancia de borde a borde en cm">
        <span class="unidad">cm</span>
    </div>

    <div id="insertar">
        <div id="menuInsertar" role="menu" aria-label="Elementos para insertar">
            <div class="grupo">
                <button type="button" class="opcion" id="btnPilar" role="menuitem" data-tipo="pilar" tabindex="-1"
                        aria-haspopup="true" aria-expanded="false" aria-controls="submenuPilar">
                    <span class="icono">
                        <!-- Pilar: columna con capitel y base -->
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 3h14M5 21h14"/>
                            <rect x="8" y="5" width="8" height="14" rx="0.5" fill="currentColor" fill-opacity="0.15"/>
                            <path d="M10.5 8v8M13.5 8v8" stroke-width="1.2"/>
                        </svg>
                    </span>
                    Pilar
                </button>

                <div class="submenu" id="submenuPilar">
                    <!-- Paso 1: elegir la sección -->
                    <div class="paso" data-paso="forma">
                        <p class="submenu-titulo">Sección del pilar</p>
                        <button type="button" class="subopcion" data-forma="rectangular">
                            <span class="icono">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="5" y="4" width="14" height="16" rx="1" fill="currentColor" fill-opacity="0.15"/>
                                </svg>
                            </span>
                            Rectangular
                        </button>
                        <button type="button" class="subopcion" data-forma="circular">
                            <span class="icono">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="8" fill="currentColor" fill-opacity="0.15"/>
                                </svg>
                            </span>
                            Circular
                        </button>
                    </div>

                    <!-- Paso 2a: medidas de pilar rectangular -->
                    <form class="paso" data-paso="rectangular" hidden>
                        <p class="submenu-titulo">
                            <button type="button" class="btn-volver" aria-label="Volver">
                                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 3L5 8l5 5"/></svg>
                            </button>
                            Pilar rectangular
                        </p>
                        <label class="campo">
                            <span>X</span>
                            <div class="entrada">
                                <input type="number" name="x" min="0.1" step="any" inputmode="decimal" required>
                                <span class="unidad">cm</span>
                            </div>
                        </label>
                        <label class="campo">
                            <span>Y</span>
                            <div class="entrada">
                                <input type="number" name="y" min="0.1" step="any" inputmode="decimal" required>
                                <span class="unidad">cm</span>
                            </div>
                        </label>
                        <button type="submit" class="btn-agregar">Agregar pilar</button>
                    </form>

                    <!-- Paso 2b: medida de pilar circular -->
                    <form class="paso" data-paso="circular" hidden>
                        <p class="submenu-titulo">
                            <button type="button" class="btn-volver" aria-label="Volver">
                                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 3L5 8l5 5"/></svg>
                            </button>
                            Pilar circular
                        </p>
                        <label class="campo">
                            <span>Diámetro</span>
                            <div class="entrada">
                                <input type="number" name="diametro" min="0.1" step="any" inputmode="decimal" required>
                                <span class="unidad">cm</span>
                            </div>
                        </label>
                        <button type="submit" class="btn-agregar">Agregar pilar</button>
                    </form>
                </div>
            </div>
            <div class="grupo">
                <button type="button" class="opcion" id="btnViga" role="menuitem" data-tipo="viga" tabindex="-1"
                        aria-haspopup="true" aria-expanded="false" aria-controls="submenuViga">
                    <span class="icono">
                        <!-- Viga: pieza horizontal apoyada sobre dos pilares -->
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="6" width="20" height="5" rx="0.5" fill="currentColor" fill-opacity="0.15"/>
                            <path d="M5 11v9M19 11v9" stroke-width="1.4"/>
                            <path d="M3 20h4M17 20h4"/>
                        </svg>
                    </span>
                    Viga
                </button>
                
                <div class="submenu" id="submenuViga">
                    <form class="paso" data-paso="viga" data-tipo="viga">
                        <p class="submenu-titulo">Sección de la viga</p>
                        <label class="campo">
                            <span>Ancho</span>
                            <div class="entrada">
                                <input type="number" name="ancho" min="0.1" step="any" inputmode="decimal" required>
                                <span class="unidad">cm</span>
                            </div>
                        </label>
                        <label class="campo">
                            <span>Alto</span>
                            <div class="entrada">
                                <input type="number" name="alto" min="0.1" step="any" inputmode="decimal" required>
                                <span class="unidad">cm</span>
                            </div>
                        </label>
                        <button type="submit" class="btn-agregar">Agregar viga</button>
                    </form>
                </div>
            </div>
            <div class="grupo">
                <button type="button" class="opcion" id="btnLosa" role="menuitem" data-tipo="losa" tabindex="-1"
                        aria-haspopup="true" aria-expanded="false" aria-controls="submenuLosa">
                    <span class="icono">
                        <!-- Losa: placa apoyada en vigas -->
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-5 9 5-9 5z" fill="currentColor" fill-opacity="0.15"/>
                            <path d="M3 9v3l9 5 9-5V9"/>
                        </svg>
                    </span>
                    Losa
                </button>

                <div class="submenu" id="submenuLosa">
                    <form class="paso" data-paso="losa" data-tipo="losa">
                        <p class="submenu-titulo">Losa</p>
                        <label class="campo">
                            <span>Espesor</span>
                            <div class="entrada">
                                <input type="number" name="espesor" min="0.1" step="any" inputmode="decimal" required>
                                <span class="unidad">cm</span>
                            </div>
                        </label>
                        <button type="submit" class="btn-agregar">Agregar losa</button>
                    </form>
                </div>
            </div>
            <div class="grupo">
                <!-- Hueco: sin datos, se dibuja directo con clicks -->
                <button type="button" class="opcion" id="btnHueco" role="menuitem" data-tipo="hueco" tabindex="-1">
                    <span class="icono">
                        <!-- Hueco: contorno rayado -->
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="4" width="16" height="16" rx="1"/>
                            <path d="M4 11l7-7M4 17L17 4M10 20L20 10M16 20l4-4" stroke-width="1.2"/>
                        </svg>
                    </span>
                    Hueco
                </button>
            </div>
            <div class="grupo">
                <!-- Voladizo: sin datos, línea de dos clicks que cierra paños de losa -->
                <button type="button" class="opcion" id="btnVoladizo" role="menuitem" data-tipo="voladizo" tabindex="-1">
                    <span class="icono">
                        <!-- Voladizo: vigas en L y borde libre en línea de trazos -->
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 20V4h16" stroke-width="3"/>
                            <path d="M20 4v16H4" stroke-dasharray="3 2.5"/>
                        </svg>
                    </span>
                    Voladizo
                </button>
            </div>
        </div>

        <button type="button" id="btnInsertar" aria-haspopup="menu" aria-expanded="false" aria-controls="menuInsertar">
            <span class="icono">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M8 2v12M2 8h12"/>
                </svg>
            </span>
            Insertar
        </button>
    </div>

    <!-- Acciones sobre la selección (aparece encima de lo seleccionado) -->
    <div id="acciones" hidden>
        <button type="button" id="btnMover">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v20M2 12h20M12 2l-3 3M12 2l3 3M12 22l-3-3M12 22l3-3M2 12l3-3M2 12l3 3M22 12l-3-3M22 12l-3 3"/>
            </svg>
            Mover
        </button>
        <button type="button" id="btnEliminar" class="peligro">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 7h16M10 11v6M14 11v6M5 7l1 13h12l1-13M9 7V4h6v3"/>
            </svg>
            Eliminar
        </button>
    </div>

    <div id="aviso" class="oculto" role="alert"></div>

    <!-- Cotas (debajo de Seleccionar) -->
    <div id="cotasCtl">
        <button type="button" id="btnCotas" aria-haspopup="true" aria-expanded="false" aria-controls="panelCotas">
            <span class="icono">
                <!-- Cota: línea con extremos -->
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <path d="M2 8h12M2 5v6M14 5v6M4 6.5L2.5 8 4 9.5M12 6.5L13.5 8 12 9.5"/>
                </svg>
            </span>
            Cotas
        </button>
        <div class="submenu" id="panelCotas">
            <p class="submenu-titulo">Mostrar cotas</p>
            <label class="interruptor">
                <span>Cotas de pilares</span>
                <input type="checkbox" id="chkCotasPilares">
                <span class="deslizador"></span>
            </label>
            <label class="interruptor">
                <span>Cotas de vigas</span>
                <input type="checkbox" id="chkCotasVigas">
                <span class="deslizador"></span>
            </label>
        </div>
    </div>

    <!-- Identificación de elementos (debajo de Cotas) -->
    <div id="identCtl">
        <button type="button" id="btnIdent" aria-haspopup="true" aria-expanded="false" aria-controls="panelIdent">
            <span class="icono">
                <!-- Etiqueta -->
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round">
                    <path d="M2 3h7l5 5-5 5H2z"/>
                    <circle cx="5" cy="8" r="1.2" fill="currentColor"/>
                </svg>
            </span>
            Identificación
        </button>
        <div class="submenu" id="panelIdent">
            <p class="submenu-titulo">Identificación de elementos</p>
            <label class="interruptor">
                <span>Identificación de pilares</span>
                <input type="checkbox" id="chkIdentPilares">
                <span class="deslizador"></span>
            </label>
            <label class="interruptor">
                <span>Identificación de vigas</span>
                <input type="checkbox" id="chkIdentVigas">
                <span class="deslizador"></span>
            </label>
            <label class="interruptor">
                <span>Identificación de losas</span>
                <input type="checkbox" id="chkIdentLosas">
                <span class="deslizador"></span>
            </label>
        </div>
    </div>

    <!-- Dimensiones de los elementos (debajo de Identificación) -->
    <div id="dimCtl">
        <button type="button" id="btnDim" aria-haspopup="true" aria-expanded="false" aria-controls="panelDim">
            <span class="icono">
                <!-- Regla -->
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" stroke-linecap="round">
                    <path d="M1.5 10.5l9-9 4 4-9 9z"/>
                    <path d="M5 7l1.5 1.5M7 5l1.5 1.5M9 3l1.5 1.5"/>
                </svg>
            </span>
            Dimensiones
        </button>
        <div class="submenu" id="panelDim">
            <p class="submenu-titulo">Dimensiones de elementos</p>
            <label class="interruptor">
                <span>Dimensiones de pilares</span>
                <input type="checkbox" id="chkDimPilares">
                <span class="deslizador"></span>
            </label>
            <label class="interruptor">
                <span>Dimensiones de vigas</span>
                <input type="checkbox" id="chkDimVigas">
                <span class="deslizador"></span>
            </label>
            <label class="interruptor">
                <span>Dimensiones de losas</span>
                <input type="checkbox" id="chkDimLosas">
                <span class="deslizador"></span>
            </label>
        </div>
    </div>

    <!-- Elementos visibles (debajo de Dimensiones) -->
    <div id="elemCtl">
        <button type="button" id="btnElem" aria-haspopup="true" aria-expanded="false" aria-controls="panelElem">
            <span class="icono">
                <!-- Capas -->
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round">
                    <path d="M8 2l6 3-6 3-6-3z"/>
                    <path d="M2 8l6 3 6-3M2 11l6 3 6-3"/>
                </svg>
            </span>
            Elementos
        </button>
        <div class="submenu" id="panelElem">
            <p class="submenu-titulo">Elementos visibles</p>
            <label class="interruptor">
                <span>Pilares</span>
                <input type="checkbox" id="chkVerPilares" checked>
                <span class="deslizador"></span>
            </label>
            <label class="interruptor">
                <span>Vigas</span>
                <input type="checkbox" id="chkVerVigas" checked>
                <span class="deslizador"></span>
            </label>
            <label class="interruptor">
                <span>Losas</span>
                <input type="checkbox" id="chkVerLosas" checked>
                <span class="deslizador"></span>
            </label>
            <label class="interruptor">
                <span>Huecos</span>
                <input type="checkbox" id="chkVerHuecos" checked>
                <span class="deslizador"></span>
            </label>
            <label class="interruptor">
                <span>Voladizos</span>
                <input type="checkbox" id="chkVerVoladizos" checked>
                <span class="deslizador"></span>
            </label>
        </div>
    </div>

    <button type="button" id="btnSeleccionar" aria-pressed="false">
        <span class="icono">
            <!-- Flecha de selección -->
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linejoin="round">
                <path d="M3 2l9.5 6.2-4.2.8 2.4 4.6-1.6.8-2.4-4.6L3 12.6z"/>
            </svg>
        </span>
        Seleccionar
    </button>

    <script>
        const insertar     = document.getElementById('insertar');
        const btnInsertar  = document.getElementById('btnInsertar');
        const menuInsertar = document.getElementById('menuInsertar');
        const opciones     = menuInsertar.querySelectorAll('.opcion');
        const submenuPilar = document.getElementById('submenuPilar');

        // Cada opción del menú con su submenú y el paso con el que arranca
        const submenus = [
            { boton: document.getElementById('btnPilar'), menu: submenuPilar,                           pasoInicial: 'forma' },
            { boton: document.getElementById('btnViga'),  menu: document.getElementById('submenuViga'), pasoInicial: 'viga' },
            { boton: document.getElementById('btnLosa'),  menu: document.getElementById('submenuLosa'), pasoInicial: 'losa' },
        ];

        function abrirMenu(abrir) {
            insertar.classList.toggle('abierto', abrir);
            btnInsertar.setAttribute('aria-expanded', abrir);
            opciones.forEach(o => o.tabIndex = abrir ? 0 : -1);
            if (!abrir) abrirSubmenu(null);
        }

        // Abre un submenú (y cierra los demás); null cierra todos
        function abrirSubmenu(cual) {
            for (const s of submenus) {
                const abrir = s === cual;
                s.menu.classList.toggle('abierto', abrir);
                s.boton.classList.toggle('activa', abrir);
                s.boton.setAttribute('aria-expanded', abrir);
                if (abrir) mostrarPaso(s.menu, s.pasoInicial);
            }
        }

        // Muestra un paso del submenú (p. ej. forma / rectangular / circular)
        function mostrarPaso(menu, nombre) {
            menu.querySelectorAll('.paso').forEach(p => {
                p.hidden = p.dataset.paso !== nombre;
                if (p.tagName === 'FORM') p.reset();
            });
            const primerCampo = menu.querySelector(`.paso[data-paso="${nombre}"] input`);
            if (primerCampo) primerCampo.focus();
        }

        btnInsertar.addEventListener('click', () => {
            abrirMenu(!insertar.classList.contains('abierto'));
        });

        for (const s of submenus) {
            s.boton.addEventListener('click', () => {
                abrirSubmenu(s.menu.classList.contains('abierto') ? null : s);
            });
        }

        submenuPilar.addEventListener('click', (e) => {
            const subopcion = e.target.closest('.subopcion');
            if (subopcion) mostrarPaso(submenuPilar, subopcion.dataset.forma);

            if (e.target.closest('.btn-volver')) mostrarPaso(submenuPilar, 'forma');
        });

        menuInsertar.querySelectorAll('.submenu form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const tipo  = form.dataset.tipo || 'pilar';
                const datos = tipo === 'pilar' ? { tipo, forma: form.dataset.paso } : { tipo };
                new FormData(form).forEach((valor, campo) => datos[campo] = parseFloat(valor));
                abrirMenu(false);
                empezarAColocar(datos);
            });
        });

        // El hueco no pide datos: arranca a dibujarse directamente
        document.getElementById('btnHueco').addEventListener('click', () => {
            abrirMenu(false);
            empezarAColocar({ tipo: 'hueco' });
        });

        // El voladizo tampoco pide datos: se dibuja como una viga sin ancho
        document.getElementById('btnVoladizo').addEventListener('click', () => {
            abrirMenu(false);
            empezarAColocar({ tipo: 'voladizo' });
        });

        // ── Colocar pilares en la pizarra ──
        const SVG_NS       = 'http://www.w3.org/2000/svg';
        const PX_POR_CM    = 2; // escala de dibujo
        const pizarra      = document.getElementById('pizarra');
        const elementos    = document.getElementById('elementos');
        const estado       = document.getElementById('estado');
        const estadoTexto  = document.getElementById('estadoTexto');
        const estadoIcono  = document.getElementById('estadoIcono');

        const guias        = document.getElementById('guias');

        let aColocar = null; // datos del pilar que se inserta con cada click
        let fantasma = null; // vista previa que sigue al mouse
        const pilares = [];  // pilares colocados: { datos, cx, cy } en coordenadas del dibujo

        const SNAP_PX = 10; // distancia en pantalla a la que se engancha la alineación

        const ICONOS_FORMA = {
            rectangular: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="4" width="14" height="16" rx="1" fill="currentColor" fill-opacity="0.15"/></svg>',
            circular:    '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="8" fill="currentColor" fill-opacity="0.15"/></svg>',
            viga:        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="8" width="20" height="8" rx="1" fill="currentColor" fill-opacity="0.15"/></svg>',
            losa:        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M3 9l9-5 9 5-9 5z" fill="currentColor" fill-opacity="0.15"/><path d="M3 9v3l9 5 9-5V9"/></svg>',
            hueco:       '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="4" y="4" width="16" height="16" rx="1"/><path d="M4 12l8-8M4 20L20 4M12 20l8-8" stroke-width="1.4"/></svg>',
            voladizo:    '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V4h16" stroke-width="3"/><path d="M20 4v16H4" stroke-dasharray="3 2.5"/></svg>',
        };

        function descripcion(datos) {
            if (datos.tipo === 'viga') return `Viga ${datos.ancho}x${datos.alto}`;
            if (datos.tipo === 'losa') return `Losa e=${datos.espesor}`;
            if (datos.tipo === 'hueco') return 'Hueco';
            if (datos.tipo === 'voladizo') return 'Voladizo';
            return datos.forma === 'circular'
                ? `Pilar Ø${datos.diametro}`
                : `Pilar ${datos.x}x${datos.y}`;
        }

        // Crea la figura SVG del pilar centrada en (cx, cy)
        function crearPilar(datos, cx, cy) {
            let figura;
            if (datos.forma === 'circular') {
                figura = document.createElementNS(SVG_NS, 'circle');
                figura.setAttribute('r', datos.diametro * PX_POR_CM / 2);
            } else {
                figura = document.createElementNS(SVG_NS, 'rect');
                figura.setAttribute('width',  datos.x * PX_POR_CM);
                figura.setAttribute('height', datos.y * PX_POR_CM);
            }
            figura.classList.add('pilar');
            moverPilar(figura, datos, cx, cy);
            return figura;
        }

        function moverPilar(figura, datos, cx, cy) {
            if (datos.forma === 'circular') {
                figura.setAttribute('cx', cx);
                figura.setAttribute('cy', cy);
            } else {
                figura.setAttribute('x', cx - datos.x * PX_POR_CM / 2);
                figura.setAttribute('y', cy - datos.y * PX_POR_CM / 2);
            }
        }

        function empezarAColocar(datos) {
            terminarSeleccion();
            dejarDeColocar();
            aColocar = datos;
            mostrarTipo(datos.tipo); // si ese tipo estaba oculto, se vuelve a mostrar para ver lo que se inserta
            estadoTexto.textContent = descripcion(datos);
            estadoIcono.innerHTML = ICONOS_FORMA[datos.tipo === 'pilar' ? datos.forma : datos.tipo];
            estado.hidden = false;
            document.body.classList.add('colocando');
            actualizarAyuda();
        }

        function dejarDeColocar() {
            if (distancia) cerrarDistancia();
            if (huecoPuntos.length) cancelarHueco(); // un hueco a medio dibujar no se crea
            vigaInicio = null;
            aColocar = null;
            estado.hidden = true;
            document.body.classList.remove('colocando');
            quitarFantasma();
        }

        function quitarFantasma() {
            if (fantasma) { fantasma.remove(); fantasma = null; }
            guias.replaceChildren();
        }

        // ── Geometría: medio ancho / medio alto de cada pilar ──
        function figuraDe(datos, cx, cy) {
            if (datos.forma === 'circular') {
                const r = datos.diametro * PX_POR_CM / 2;
                return { circular: true, cx, cy, r, hw: r, hh: r };
            }
            return { circular: false, cx, cy, hw: datos.x * PX_POR_CM / 2, hh: datos.y * PX_POR_CM / 2 };
        }

        // Punto del rectángulo más cercano a (x, y)
        function puntoEnRect(f, x, y) {
            return {
                x: Math.min(Math.max(x, f.cx - f.hw), f.cx + f.hw),
                y: Math.min(Math.max(y, f.cy - f.hh), f.cy + f.hh),
            };
        }

        // Distancia de borde a borde entre dos figuras: { d, p1, p2 } (d <= 0 si se superponen)
        function distanciaBordes(a, b) {
            if (!a.circular && !b.circular) {
                const gx = Math.max((b.cx - b.hw) - (a.cx + a.hw), (a.cx - a.hw) - (b.cx + b.hw));
                const gy = Math.max((b.cy - b.hh) - (a.cy + a.hh), (a.cy - a.hh) - (b.cy + b.hh));
                // borde de cada uno que mira al otro
                const ax = b.cx > a.cx ? a.cx + a.hw : a.cx - a.hw;
                const bx = b.cx > a.cx ? b.cx - b.hw : b.cx + b.hw;
                const ay = b.cy > a.cy ? a.cy + a.hh : a.cy - a.hh;
                const by = b.cy > a.cy ? b.cy - b.hh : b.cy + b.hh;
                // centro del tramo en que se solapan (para que la cota quede recta)
                const my = (Math.max(a.cy - a.hh, b.cy - b.hh) + Math.min(a.cy + a.hh, b.cy + b.hh)) / 2;
                const mx = (Math.max(a.cx - a.hw, b.cx - b.hw) + Math.min(a.cx + a.hw, b.cx + b.hw)) / 2;

                if (gx > 0 && gy > 0) return { d: Math.hypot(gx, gy), p1: { x: ax, y: ay }, p2: { x: bx, y: by } };
                if (gx > 0)           return { d: gx, p1: { x: ax, y: my }, p2: { x: bx, y: my } };
                if (gy > 0)           return { d: gy, p1: { x: mx, y: ay }, p2: { x: mx, y: by } };
                return { d: 0, p1: { x: a.cx, y: a.cy }, p2: { x: b.cx, y: b.cy } };
            }

            // Al menos uno es circular: se mide desde el centro del círculo
            if (!a.circular) {
                const r = distanciaBordes(b, a);
                return { d: r.d, p1: r.p2, p2: r.p1 };
            }
            const q = b.circular
                ? { x: b.cx, y: b.cy }
                : puntoEnRect(b, a.cx, a.cy);
            const vx = q.x - a.cx, vy = q.y - a.cy;
            const largo = Math.hypot(vx, vy);
            if (largo === 0) return { d: 0, p1: q, p2: q };
            const ux = vx / largo, uy = vy / largo;
            const rb = b.circular ? b.r : 0;
            return {
                d:  largo - a.r - rb,
                p1: { x: a.cx + ux * a.r, y: a.cy + uy * a.r },
                p2: { x: q.x - ux * rb,   y: q.y - uy * rb },
            };
        }

        // Ajusta la posición: se engancha alineado (centro o caras) con otros pilares
        function posicionConSnap(datos, p) {
            const tol = SNAP_PX / camara.zoom;
            const yo  = figuraDe(datos, p.x, p.y);
            let mejorX = null, mejorY = null;

            for (const pilar of pilares) {
                const otro = figuraDe(pilar.datos, pilar.cx, pilar.cy);
                const izq = otro.cx - otro.hw, der = otro.cx + otro.hw;
                const sup = otro.cy - otro.hh, inf = otro.cy + otro.hh;

                // [posición del centro del nuevo pilar, línea de referencia]
                const candidatosX = [
                    [otro.cx,      otro.cx], // centro con centro
                    [izq + yo.hw,  izq],     // izquierda con izquierda
                    [der - yo.hw,  der],     // derecha con derecha
                    [der + yo.hw,  der],     // izquierda del nuevo con derecha del otro
                    [izq - yo.hw,  izq],     // derecha del nuevo con izquierda del otro
                ];
                const candidatosY = [
                    [otro.cy,      otro.cy], // centro con centro
                    [sup + yo.hh,  sup],     // superior con superior
                    [inf - yo.hh,  inf],     // inferior con inferior
                    [inf + yo.hh,  inf],     // superior del nuevo con inferior del otro
                    [sup - yo.hh,  sup],     // inferior del nuevo con superior del otro
                ];

                for (const [cx, linea] of candidatosX) {
                    const dif = Math.abs(cx - p.x);
                    if (dif <= tol && (!mejorX || dif < mejorX.dif)) mejorX = { dif, cx, linea, otro };
                }
                for (const [cy, linea] of candidatosY) {
                    const dif = Math.abs(cy - p.y);
                    if (dif <= tol && (!mejorY || dif < mejorY.dif)) mejorY = { dif, cy, linea, otro };
                }
            }

            // Si no se engancha, redondea al centímetro
            return {
                x: mejorX ? mejorX.cx : Math.round(p.x / PX_POR_CM) * PX_POR_CM,
                y: mejorY ? mejorY.cy : Math.round(p.y / PX_POR_CM) * PX_POR_CM,
                snapX: mejorX,
                snapY: mejorY,
            };
        }

        function formatoCm(px) {
            const cm = Math.round(px / PX_POR_CM * 10) / 10;
            return `${cm.toLocaleString('es-AR')} cm`;
        }

        function crearSvg(tag, atributos) {
            const el = document.createElementNS(SVG_NS, tag);
            for (const [k, v] of Object.entries(atributos)) el.setAttribute(k, v);
            return el;
        }

        // Pilar colocado más cercano a la figura: { figura, d, p1, p2 } o null
        function pilarMasCercano(yo) {
            let cercano = null;
            for (const pilar of pilares) {
                const figura = figuraDe(pilar.datos, pilar.cx, pilar.cy);
                const r = distanciaBordes(yo, figura);
                if (!cercano || r.d < cercano.d) cercano = { ...r, figura };
            }
            return cercano;
        }

        // Dibuja las líneas de alineación y la cota al pilar más cercano (o a `ref` si se indica)
        function dibujarGuias(pos, ref = null) {
            guias.replaceChildren();
            const yo = figuraDe(aColocar, pos.x, pos.y);
            const z  = camara.zoom;

            // Líneas de alineación (fila / columna)
            if (pos.snapX) {
                const o = pos.snapX.otro;
                guias.appendChild(crearSvg('line', {
                    class: 'guia-snap',
                    x1: pos.snapX.linea, y1: Math.min(o.cy - o.hh, yo.cy - yo.hh) - 20 / z,
                    x2: pos.snapX.linea, y2: Math.max(o.cy + o.hh, yo.cy + yo.hh) + 20 / z,
                }));
            }
            if (pos.snapY) {
                const o = pos.snapY.otro;
                guias.appendChild(crearSvg('line', {
                    class: 'guia-snap',
                    x1: Math.min(o.cx - o.hw, yo.cx - yo.hw) - 20 / z, y1: pos.snapY.linea,
                    x2: Math.max(o.cx + o.hw, yo.cx + yo.hw) + 20 / z, y2: pos.snapY.linea,
                }));
            }

            // Cota de borde a borde con el pilar de referencia (o el más cercano)
            const cercano = ref ? distanciaBordes(yo, ref) : pilarMasCercano(yo);
            if (!cercano) return;

            const superpuesto = cercano.d <= 0;
            const { p1, p2 } = cercano;
            const cota = crearSvg('g', { class: superpuesto ? 'cota superpuesto' : 'cota' });
            if (!superpuesto) {
                cota.appendChild(crearSvg('line', { x1: p1.x, y1: p1.y, x2: p2.x, y2: p2.y }));
                cota.appendChild(crearSvg('circle', { cx: p1.x, cy: p1.y, r: 2.5 / z }));
                cota.appendChild(crearSvg('circle', { cx: p2.x, cy: p2.y, r: 2.5 / z }));
            }

            guias.appendChild(cota);
            const mx = superpuesto ? yo.cx : (p1.x + p2.x) / 2;
            const my = superpuesto ? yo.cy - yo.hh - 14 / z : (p1.y + p2.y) / 2;
            agregarEtiqueta(cota, mx, my, superpuesto ? 'Superpuesto' : formatoCm(cercano.d));
        }

        // Etiqueta con fondo, de tamaño fijo en pantalla (el grupo ya tiene que estar en el SVG)
        // El texto se dibuja siempre a 12 px y el grupo se escala por 1/zoom: así nunca hay tamaños de letra
        // diminutos (p. ej. 1.2 con zoom 10), que el navegador renderiza mal o directamente no muestra.
        function agregarEtiqueta(grupo, x, y, contenido) {
            const z = camara.zoom;
            const etiqueta = crearSvg('g', { class: 'etiqueta', transform: `translate(${x} ${y}) scale(${1 / z})` });
            const fondo = crearSvg('rect', { rx: 4 });
            const texto = crearSvg('text', {
                x: 0, y: 0,
                'font-size': 12,
                'text-anchor': 'middle',
                'dominant-baseline': 'central',
            });
            texto.textContent = contenido;
            etiqueta.append(fondo, texto);
            grupo.appendChild(etiqueta);

            const caja = texto.getBBox();
            const pad = 4;
            fondo.setAttribute('x', caja.x - pad);
            fondo.setAttribute('y', caja.y - pad / 2);
            fondo.setAttribute('width',  caja.width + pad * 2);
            fondo.setAttribute('height', caja.height + pad);

            // el color se copia del grupo, así la etiqueta lo conserva si se la cambia de lugar (ver subirEtiquetas)
            etiqueta.style.setProperty('--color-etiqueta', getComputedStyle(grupo).getPropertyValue('--color-etiqueta'));
        }


        let ultimaPos = null; // última posición de la vista previa

        function mostrarFantasma(pos, ref = null) {
            ultimaPos = pos;
            if (!fantasma) {
                fantasma = crearPilar(aColocar, pos.x, pos.y);
                fantasma.id = 'fantasma';
                vista.insertBefore(fantasma, capaCotas);
            }
            moverPilar(fantasma, aColocar, pos.x, pos.y);
            dibujarGuias(pos, ref);
        }

        function actualizarFantasma(e) {
            if (mover) return distancia ? refrescarDistancia() : actualizarMover(e);
            if (!aColocar) return;
            if (distancia) return refrescarDistancia(); // mientras se tipea la distancia el mouse no mueve nada
            if (esTramo(aColocar.tipo)) return actualizarViga(e);
            if (aColocar.tipo === 'losa') return previsualizarLosa(e);
            if (aColocar.tipo === 'hueco') return actualizarHueco(e);
            mostrarFantasma(posicionConSnap(aColocar, aMundo(e)));
        }

        function colocarPilar(pos) {
            const el = crearPilar(aColocar, pos.x, pos.y);
            elementos.appendChild(el);
            pilares.push({ tipo: 'pilar', datos: aColocar, cx: pos.x, cy: pos.y, el });
            alCambiarModelo();
        }

        const estadoAyuda = document.getElementById('estadoAyuda');

        function actualizarAyuda() {
            if (!aColocar) return;
            estadoAyuda.textContent =
                esTramo(aColocar.tipo) ? (vigaInicio ? 'Click en el final · Tab: largo exacto · Esc cancela' : 'Click en el inicio')
              : aColocar.tipo === 'losa' ? 'Click dentro de un paño cerrado por vigas, pilares o voladizos'
              : aColocar.tipo === 'hueco' ? (
                    !huecoPuntos.length ? 'Click en el primer vértice'
                  : huecoPuntos.length < 3 ? 'Click: siguiente vértice · Tab: largo exacto · Retroceso: borrar el último · Esc cancela'
                  : 'Click en el primer vértice para cerrar · Tab: largo exacto · Retroceso: borrar el último · Esc cancela')
              : 'Tab: distancia exacta';
        }

        // ── Modelo: listas de elementos ──
        const losas = []; // { tipo: 'losa', datos: { espesor }, contornos: [[{x, y}, ...], ...], el }

        function todosLosElementos() {
            return [...losas, ...huecos, ...vigas, ...voladizos, ...pilares];
        }

        function listaDe(tipo) {
            return { pilar: pilares, viga: vigas, losa: losas, hueco: huecos, voladizo: voladizos }[tipo];
        }

        // Llamar cada vez que se agrega, mueve o borra un elemento
        function alCambiarModelo() {
            paños = null; // se recalcula en el próximo uso
            actualizarCotas();
            actualizarIdentificacion();
            actualizarDimensiones();
            guardarModelo();
        }

        // ── Guardado local (prototipo): el dibujo queda en el navegador y se recupera al recargar ──
        const CLAVE_GUARDADO = 'pizarra';

        function guardarModelo() {
            localStorage.setItem(CLAVE_GUARDADO, JSON.stringify({
                pilares: pilares.map(p => ({ datos: p.datos, cx: p.cx, cy: p.cy })),
                vigas:   vigas.map(v => ({ datos: v.datos, a: v.a, b: v.b })),
                losas:   losas.map(l => ({ datos: l.datos, contornos: l.contornos })),
                huecos:  huecos.map(h => ({ datos: h.datos, puntos: h.puntos })),
                voladizos: voladizos.map(v => ({ datos: v.datos, a: v.a, b: v.b })),
            }));
        }

        function cargarModelo() {
            let guardado;
            try { guardado = JSON.parse(localStorage.getItem(CLAVE_GUARDADO)); } catch { return; }
            if (!guardado) return;

            for (const p of guardado.pilares || []) {
                const el = crearPilar(p.datos, p.cx, p.cy);
                elementos.appendChild(el);
                pilares.push({ tipo: 'pilar', datos: p.datos, cx: p.cx, cy: p.cy, el });
            }
            for (const v of guardado.vigas || []) {
                const el = crearViga(v.datos, v.a, v.b);
                capaVigas.appendChild(el);
                vigas.push({ tipo: 'viga', datos: v.datos, a: v.a, b: v.b, el });
            }
            for (const l of guardado.losas || []) agregarLosa(l.datos, l.contornos);
            for (const h of guardado.huecos || []) {
                const el = crearHueco(h.puntos);
                capaHuecos.appendChild(el);
                huecos.push({ tipo: 'hueco', datos: h.datos, puntos: h.puntos, el });
            }
            for (const v of guardado.voladizos || []) {
                const el = crearViga(v.datos, v.a, v.b);
                capaVoladizos.appendChild(el);
                voladizos.push({ tipo: 'voladizo', datos: v.datos, a: v.a, b: v.b, el });
            }
            alCambiarModelo();
        }

        // Aviso temporal abajo al centro
        const aviso = document.getElementById('aviso');
        let avisoTimer = null;
        function avisar(texto) {
            aviso.textContent = texto;
            aviso.classList.remove('oculto');
            clearTimeout(avisoTimer);
            avisoTimer = setTimeout(() => aviso.classList.add('oculto'), 3500);
        }

        // ── Losas: click dentro de un paño cerrado por vigas, pilares o voladizos ──
        // Las caras de vigas y pilares y las líneas de voladizo se cortan entre sí en sus cruces (igual que
        // en la resta de huecos). Cada tramo con lugar libre de un solo lado es borde de un paño; un voladizo
        // con lugar libre de los dos lados es borde de los dos paños que separa. Los bordes se encadenan en
        // anillos: el paño del click es el anillo exterior más chico que lo contiene, con los interiores que
        // caen directo adentro (p. ej. un pilar suelto). Es exacto también con vigas y voladizos en diagonal.
        const capaLosas = document.getElementById('losas');
        let paños = null; // anillos ya encadenados; se recalculan cuando cambia el modelo
        const EPS = 1e-6; // coordenadas más cercanas que esto se consideran la misma

        function obstaculos() {
            return [
                ...pilares.map(p => p.datos.forma === 'circular' ? circuloPilar(p, 32) : contorno(p)),
                ...vigas.map(v => verticesViga(v.a, v.b, v.datos.ancho)),
            ];
        }

        // Pilar circular como polígono de `n` lados (para cerrar paños)
        function circuloPilar(p, n) {
            const r = p.datos.diametro * PX_POR_CM / 2;
            return Array.from({ length: n }, (_, i) => ({
                x: p.cx + Math.cos(i * 2 * Math.PI / n) * r,
                y: p.cy + Math.sin(i * 2 * Math.PI / n) * r,
            }));
        }

        function construirPaños() {
            const polis = obstaculos();
            const tramos = [];
            for (const poli of polis) poli.forEach((a, i) => tramos.push({ a, b: poli[(i + 1) % poli.length], muro: false }));
            for (const v of voladizos) tramos.push({ a: v.a, b: v.b, muro: true });
            if (!tramos.length) return null;

            const cajas = polis.map(cajaDe);
            const libre = (q) => !polis.some((poli, k) => {
                const c = cajas[k];
                return q.x >= c.x0 && q.x <= c.x1 && q.y >= c.y0 && q.y <= c.y1 && dentroDePoligono(q, poli);
            });
            const { verts, aristas } = partirTramos(tramos);

            // Bordes con lugar libre a la izquierda (-dy, dx); los voladizos libres de los dos lados, aparte
            const bordes = [], muros = [];
            for (const { i, j, muro } of aristas) {
                const [izq, der] = ladosArista(verts[i], verts[j], libre);
                if (izq !== der) bordes.push(izq ? [i, j] : [j, i]);
                else if (izq && muro) muros.push([i, j]);
            }
            // Un voladizo con una punta suelta no cierra nada: se quitan esos tramos (en cadena)
            const grado = new Map();
            const sumar = (k, d) => grado.set(k, (grado.get(k) || 0) + d);
            for (const [i, j] of [...bordes, ...muros]) { sumar(i, 1); sumar(j, 1); }
            let quedan = muros, cambio = true;
            while (cambio) {
                cambio = false;
                quedan = quedan.filter(([i, j]) => {
                    if (grado.get(i) > 1 && grado.get(j) > 1) return true;
                    sumar(i, -1); sumar(j, -1); cambio = true;
                    return false;
                });
            }

            const salientes = new Map();
            const agregar = (de, hasta) => {
                if (!salientes.has(de)) salientes.set(de, []);
                salientes.get(de).push({ de, hasta, usada: false });
            };
            for (const [i, j] of bordes) agregar(i, j);
            for (const [i, j] of quedan) { agregar(i, j); agregar(j, i); }

            return encadenarAnillos(verts, salientes).map(c => ({ puntos: c, area: areaPoligono(c), muestra: puntoAIzquierda(c) }));
        }

        // Busca el paño que contiene el punto: { contornos } | { error }
        function buscarPaño(p) {
            if (!paños) paños = construirPaños();
            if (!paños) return { error: 'No se pudo insertar la losa: no hay vigas ni voladizos que la cierren' };
            if (obstaculos().some(poli => dentroDePoligono(p, poli))) {
                return { error: 'Hacé click en un lugar vacío, no sobre una viga o pilar' };
            }

            const exteriores = paños.filter(c => c.area > 0);
            let exterior = null;
            for (const c of exteriores) {
                if (dentroDePoligono(p, c.puntos) && (!exterior || c.area < exterior.area)) exterior = c;
            }
            if (!exterior) return { error: 'No se pudo insertar la losa: el paño no está cerrado por vigas, pilares o voladizos' };

            // interiores de este paño: adentro de él y no de otro paño más chico que esté adentro
            const interiores = paños.filter(c => c.area < 0 && dentroDePoligono(c.muestra, exterior.puntos) &&
                !exteriores.some(o => o !== exterior && o.area < exterior.area && dentroDePoligono(c.muestra, o.puntos)));
            return { contornos: [exterior.puntos, ...interiores.map(c => c.puntos)] };
        }

        function pathLosa(contornos) {
            return contornos.map(c => 'M' + c.map(p => `${p.x} ${p.y}`).join('L') + 'Z').join('');
        }

        // ¿El punto está dentro de alguna losa ya colocada? (regla par-impar, por los huecos)
        function losaEn(p) {
            return losas.find(l => l.contornos.filter(c => dentroDePoligono(p, c)).length % 2 === 1) || null;
        }

        function agregarLosa(datos, contornos) {
            const el = crearSvg('path', { class: 'losa', d: pathLosa(contornos) });
            capaLosas.appendChild(el);
            const losa = { tipo: 'losa', datos, contornos, el };
            losas.push(losa);
            return losa;
        }

        function quitarLosa(losa) {
            losa.el.remove();
            losas.splice(losas.indexOf(losa), 1);
        }

        // Paño bajo el punto sin lo que ya ocupan otras losas ni los huecos: { contornos } | { error }
        function pañoLibre(p) {
            if (losaEn(p)) return { error: 'Ya hay una losa en ese paño' };
            if (huecoEn(p)) return { error: 'Ahí hay un hueco: hacé click en la parte donde va la losa' };
            const res = buscarPaño(p);
            if (res.error) return res;
            const piezas = restarPoligonos(res.contornos, [...huecos.map(h => [h.puntos]), ...losas.map(l => l.contornos)]);
            if (!piezas) return res;
            const pieza = piezas.find(c => dentroDeAnillos(p, c));
            return pieza ? { contornos: pieza } : { error: 'Ese paño ya está cubierto por losas y huecos' };
        }

        function previsualizarLosa(e) {
            guias.replaceChildren();
            const res = pañoLibre(aMundo(e));
            if (res.contornos) guias.appendChild(crearSvg('path', { id: 'previaLosa', d: pathLosa(res.contornos) }));
        }

        function clickLosa(e) {
            const res = pañoLibre(aMundo(e));
            if (res.error) return avisar(res.error);
            agregarLosa(aColocar, res.contornos);
            alCambiarModelo();
            guias.replaceChildren();
        }

        // ── Vigas: se insertan con dos clicks (inicio y final) ──
        const capaVigas = document.getElementById('vigas');
        const vigas = [];      // vigas colocadas: { datos, a: {x, y}, b: {x, y} } (eje de la viga)
        let vigaInicio = null; // primer click de la viga (o voladizo) que se está dibujando
        const capaVoladizos = document.getElementById('voladizos');
        const voladizos = [];  // voladizos: { tipo: 'voladizo', datos, a: {x, y}, b: {x, y}, el } (línea sin ancho)

        // Rectángulo de la viga alrededor de su eje a-b
        function verticesViga(a, b, ancho) {
            const largo = Math.hypot(b.x - a.x, b.y - a.y) || 1;
            const h  = ancho * PX_POR_CM / 2;
            const nx = -(b.y - a.y) / largo * h;
            const ny =  (b.x - a.x) / largo * h;
            return [
                { x: a.x + nx, y: a.y + ny }, { x: b.x + nx, y: b.y + ny },
                { x: b.x - nx, y: b.y - ny }, { x: a.x - nx, y: a.y - ny },
            ];
        }

        function puntosViga(a, b, ancho) {
            return verticesViga(a, b, ancho).map(p => `${p.x},${p.y}`).join(' ');
        }

        // Viga y voladizo se dibujan igual (dos clicks); el voladizo es una línea sin ancho
        function esTramo(tipo) {
            return tipo === 'viga' || tipo === 'voladizo';
        }

        function anchoDe(datos) {
            return datos.ancho || 0;
        }

        function crearViga(datos, a, b) {
            if (datos.tipo === 'voladizo') {
                const g = crearSvg('g', { class: 'voladizo' });
                g.appendChild(crearSvg('line', { class: 'toque' }));
                g.appendChild(crearSvg('line', { class: 'linea' }));
                moverTramo(g, datos, a, b);
                return g;
            }
            return crearSvg('polygon', { class: 'viga', points: puntosViga(a, b, datos.ancho) });
        }

        function moverTramo(el, datos, a, b) {
            if (datos.tipo !== 'voladizo') return el.setAttribute('points', puntosViga(a, b, datos.ancho));
            for (const linea of el.children) {
                linea.setAttribute('x1', a.x); linea.setAttribute('y1', a.y);
                linea.setAttribute('x2', b.x); linea.setAttribute('y2', b.y);
            }
        }

        function colocarViga(a, b) {
            const el = crearViga(aColocar, a, b);
            (aColocar.tipo === 'voladizo' ? capaVoladizos : capaVigas).appendChild(el);
            listaDe(aColocar.tipo).push({ tipo: aColocar.tipo, datos: aColocar, a: { x: a.x, y: a.y }, b: { x: b.x, y: b.y }, el });
            alCambiarModelo();
        }

        // Punto del segmento a-b más cercano a p
        function puntoEnSegmento(p, a, b) {
            const dx = b.x - a.x, dy = b.y - a.y;
            const l2 = dx * dx + dy * dy;
            const t  = l2 ? Math.max(0, Math.min(1, ((p.x - a.x) * dx + (p.y - a.y) * dy) / l2)) : 0;
            return { x: a.x + t * dx, y: a.y + t * dy };
        }

        // Snap del extremo de la viga, por prioridad:
        // 1) extremo de otra viga
        // 2) lado de un pilar (si el mouse está sobre él); a lo largo del lado se alinea con el inicio,
        //    con centros de pilares (= mitad de la cara), con caras de pilares (viga a filo) o extremos de vigas
        // 3) sobre la cara o el eje de otra viga
        // 4) alineado horizontal/vertical con el inicio, con centros de pilares o extremos de vigas
        function snapViga(p) {
            const tol = SNAP_PX / camara.zoom;
            const dist = (q) => Math.hypot(p.x - q.x, p.y - q.y);
            const tramos = [...vigas, ...voladizos];

            // Candidatos de alineación por eje: { v: valor del eje de la viga, ref: punto de referencia para la guía }
            const candidatos = { x: [], y: [] };
            const puntos = [
                ...(vigaInicio ? [vigaInicio] : []),
                ...pilares.map(pl => ({ x: pl.cx, y: pl.cy })),
                ...tramos.flatMap(v => [v.a, v.b]),
            ];
            for (const r of puntos) {
                candidatos.x.push({ v: r.x, ref: r });
                candidatos.y.push({ v: r.y, ref: r });
            }
            // Cara de la viga a filo con la misma cara del pilar (izq-izq, der-der, sup-sup, inf-inf):
            // el eje queda a medio ancho de viga hacia adentro de la cara
            const h = anchoDe(aColocar) * PX_POR_CM / 2;
            for (const pilar of pilares) {
                const f = figuraDe(pilar.datos, pilar.cx, pilar.cy);
                for (const cara of [f.cx - f.hw, f.cx + f.hw]) {
                    candidatos.x.push({ v: cara + Math.sign(f.cx - cara) * h, ref: { x: cara, y: f.cy, guiaX: cara } });
                }
                for (const cara of [f.cy - f.hh, f.cy + f.hh]) {
                    candidatos.y.push({ v: cara + Math.sign(f.cy - cara) * h, ref: { x: f.cx, y: cara, guiaY: cara } });
                }
            }
            // Lo mismo con las caras de las vigas verticales / horizontales ya colocadas
            for (const v of tramos) {
                const hv = anchoDe(v.datos) * PX_POR_CM / 2;
                const medio = { x: (v.a.x + v.b.x) / 2, y: (v.a.y + v.b.y) / 2 };
                // contra un voladizo (sin ancho) la cara de la viga puede ir de cualquiera de los dos lados
                const lados = (eje, cara) => hv ? [Math.sign(eje - cara)] : [1, -1];
                if (v.a.x === v.b.x) {
                    // caras largas (laterales): a filo con la misma cara
                    for (const cara of [v.a.x - hv, v.a.x + hv]) {
                        for (const s of lados(v.a.x, cara)) candidatos.x.push({ v: cara + s * h, ref: { x: cara, y: medio.y, guiaX: cara } });
                    }
                    // caras de los extremos (arriba / abajo): a filo de cualquiera de las dos caras de la nueva
                    for (const cara of [Math.min(v.a.y, v.b.y), Math.max(v.a.y, v.b.y)]) {
                        for (const s of [1, -1]) {
                            candidatos.y.push({ v: cara + s * h, ref: { x: v.a.x, y: cara, guiaY: cara } });
                        }
                    }
                } else if (v.a.y === v.b.y) {
                    for (const cara of [v.a.y - hv, v.a.y + hv]) {
                        for (const s of lados(v.a.y, cara)) candidatos.y.push({ v: cara + s * h, ref: { x: medio.x, y: cara, guiaY: cara } });
                    }
                    // caras de los extremos (izquierda / derecha), p. ej. la cara derecha de una viga vertical
                    // contra la cara izquierda de esta viga horizontal
                    for (const cara of [Math.min(v.a.x, v.b.x), Math.max(v.a.x, v.b.x)]) {
                        for (const s of [1, -1]) {
                            candidatos.x.push({ v: cara + s * h, ref: { x: cara, y: v.a.y, guiaX: cara } });
                        }
                    }
                }
            }

            // Valor alineado sobre un eje ('x' o 'y') con el candidato más cercano; si no hay, redondeado al cm
            const alinear = (valor, eje) => {
                let mejorC = null;
                for (const c of candidatos[eje]) {
                    const d = Math.abs(c.v - valor);
                    if (d <= tol && (!mejorC || d < Math.abs(mejorC.v - valor))) mejorC = c;
                }
                return mejorC
                    ? { v: mejorC.v, ref: mejorC.ref }
                    : { v: Math.round(valor / PX_POR_CM) * PX_POR_CM, ref: null };
            };

            let mejor = null;
            const probar = (q, tipo) => {
                const d = dist(q);
                if (d <= tol && (!mejor || d < mejor.d)) mejor = { x: q.x, y: q.y, tipo, d };
            };

            // 1) Extremos de vigas y voladizos (el voladizo también se engancha en esquinas de vigas y pilares)
            for (const v of tramos) { probar(v.a, 'extremo'); probar(v.b, 'extremo'); }
            if (aColocar.tipo === 'voladizo') {
                for (const v of vigas) verticesViga(v.a, v.b, v.datos.ancho).forEach(q => probar(q, 'extremo'));
                for (const pl of pilares) if (pl.datos.forma !== 'circular') contorno(pl).forEach(q => probar(q, 'extremo'));
            }
            if (mejor) return mejor;

            // 2) Lados de pilares: el pilar bajo el mouse (el de centro más cercano si hay varios)
            let pilarEncima = null;
            for (const pilar of pilares) {
                const f = figuraDe(pilar.datos, pilar.cx, pilar.cy);
                const encima = Math.abs(p.x - f.cx) <= f.hw + tol && Math.abs(p.y - f.cy) <= f.hh + tol;
                if (encima && (!pilarEncima || dist(f) < dist(pilarEncima))) pilarEncima = f;
            }
            if (pilarEncima) return snapLadoPilar(p, pilarEncima, alinear);

            // 3) Sobre la cara de otra viga (encuentro en T a filo de la cara) o sobre su eje
            let cara = null;
            for (const v of tramos) {
                const vert = verticesViga(v.a, v.b, anchoDe(v.datos));
                for (const [c0, c1] of [[vert[0], vert[1]], [vert[3], vert[2]]]) {
                    const q = puntoEnSegmento(p, c0, c1);
                    const d = dist(q);
                    if (d <= tol && (!cara || d < cara.d)) cara = { d, c0, c1, q };
                }
            }
            for (const v of vigas) probar(puntoEnSegmento(p, v.a, v.b), 'sobreViga');
            if (cara && (!mejor || cara.d < mejor.d)) return snapCaraViga(cara, alinear);
            if (mejor) return mejor;

            // 4) Alineación por eje
            const ax = alinear(p.x, 'x');
            const ay = alinear(p.y, 'y');
            return { x: ax.v, y: ay.v, tipo: null, refX: ax.ref, refY: ay.ref };
        }

        // Punto sobre la cara de una viga; si la cara es horizontal/vertical, a lo largo de ella
        // se alinea igual que en los pilares (con el inicio, caras, centros...)
        function snapCaraViga({ c0, c1, q }, alinear) {
            if (c0.y === c1.y) {
                const a = alinear(q.x, 'x');
                const x = Math.min(Math.max(a.v, Math.min(c0.x, c1.x)), Math.max(c0.x, c1.x));
                return { x, y: c0.y, tipo: 'caraViga', refX: x === a.v ? a.ref : null };
            }
            if (c0.x === c1.x) {
                const a = alinear(q.y, 'y');
                const y = Math.min(Math.max(a.v, Math.min(c0.y, c1.y)), Math.max(c0.y, c1.y));
                return { x: c0.x, y, tipo: 'caraViga', refY: y === a.v ? a.ref : null };
            }
            return { x: q.x, y: q.y, tipo: 'caraViga' }; // viga en diagonal: punto más cercano de la cara
        }

        // Punto sobre el borde del pilar más cercano al mouse
        function snapLadoPilar(p, f, alinear) {
            if (f.circular) {
                const lado = (v) => Math.sign(v) || 1;
                // si está alineado con algo, el punto del círculo a esa altura (o a esa x)
                const ay = alinear(p.y, 'y');
                if (ay.ref && Math.abs(ay.v - f.cy) < f.r) {
                    const x = f.cx + lado(p.x - f.cx) * Math.sqrt(f.r ** 2 - (ay.v - f.cy) ** 2);
                    return { x, y: ay.v, tipo: 'pilar', refY: ay.ref };
                }
                const ax = alinear(p.x, 'x');
                if (ax.ref && Math.abs(ax.v - f.cx) < f.r) {
                    const y = f.cy + lado(p.y - f.cy) * Math.sqrt(f.r ** 2 - (ax.v - f.cx) ** 2);
                    return { x: ax.v, y, tipo: 'pilar', refX: ax.ref };
                }
                const ang = Math.atan2(p.y - f.cy, p.x - f.cx);
                return { x: f.cx + Math.cos(ang) * f.r, y: f.cy + Math.sin(ang) * f.r, tipo: 'pilar' };
            }

            const izq = f.cx - f.hw, der = f.cx + f.hw;
            const sup = f.cy - f.hh, inf = f.cy + f.hh;
            const lados = [
                { d: Math.abs(p.x - izq), eje: 'x', valor: izq },
                { d: Math.abs(p.x - der), eje: 'x', valor: der },
                { d: Math.abs(p.y - sup), eje: 'y', valor: sup },
                { d: Math.abs(p.y - inf), eje: 'y', valor: inf },
            ];
            const lado = lados.reduce((a, b) => (b.d < a.d ? b : a));

            // Lado vertical: x fija, y libre a lo largo de la cara (y viceversa)
            if (lado.eje === 'x') {
                const a = alinear(p.y, 'y');
                const y = Math.min(Math.max(a.v, sup), inf);
                return { x: lado.valor, y, tipo: 'pilar', refY: y === a.v ? a.ref : null };
            }
            const a = alinear(p.x, 'x');
            const x = Math.min(Math.max(a.v, izq), der);
            return { x, y: lado.valor, tipo: 'pilar', refX: x === a.v ? a.ref : null };
        }

        let ultimaPosViga = null; // último punto (con snap) bajo el mouse

        function actualizarViga(e) {
            ultimaPosViga = snapViga(aMundo(e));
            dibujarVistaViga(ultimaPosViga);
        }

        // Vista previa: viga desde el inicio hasta `pos`, su largo, guías y marca de snap
        function dibujarVistaViga(pos) {
            const z = camara.zoom;
            guias.replaceChildren();

            if (vigaInicio) {
                // Vista previa de la viga y su largo (eje a eje)
                if (!fantasma) {
                    fantasma = crearViga(aColocar, vigaInicio, pos);
                    fantasma.id = 'fantasma';
                    vista.insertBefore(fantasma, capaCotas);
                } else {
                    moverTramo(fantasma, aColocar, vigaInicio, pos);
                }

                const largo = Math.hypot(pos.x - vigaInicio.x, pos.y - vigaInicio.y);
                if (largo > 0) {
                    // la etiqueta va al costado de la viga, del lado de "arriba"
                    let nx = -(pos.y - vigaInicio.y) / largo, ny = (pos.x - vigaInicio.x) / largo;
                    if (ny > 0) { nx = -nx; ny = -ny; }
                    const sep = anchoDe(aColocar) * PX_POR_CM / 2 + 14 / z;
                    const grupo = crearSvg('g', { class: 'cota largo' });
                    guias.appendChild(grupo);
                    agregarEtiqueta(grupo,
                        (vigaInicio.x + pos.x) / 2 + nx * sep,
                        (vigaInicio.y + pos.y) / 2 + ny * sep,
                        formatoCm(largo));
                }
            }

            dibujarSnap(pos);
        }

        // Guías de alineación y marca del punto de snap (vigas y huecos)
        function dibujarSnap(pos) {
            const z = camara.zoom;

            // Líneas de alineación
            // (si la alineación es de caras, la guía va sobre la cara del pilar y no sobre el eje de la viga)
            if (pos.refX) {
                const gx = pos.refX.guiaX ?? pos.x;
                guias.appendChild(crearSvg('line', { class: 'guia-snap', x1: gx, y1: pos.refX.y, x2: gx, y2: pos.y }));
            }
            if (pos.refY) {
                const gy = pos.refY.guiaY ?? pos.y;
                guias.appendChild(crearSvg('line', { class: 'guia-snap', x1: pos.refY.x, y1: gy, x2: pos.x, y2: gy }));
            }

            // Marca del punto de snap: círculo (extremo de viga), cuadrado (cara de pilar o viga), rombo (eje de viga), cruz (libre)
            const r = 5 / z;
            let marca;
            if (pos.tipo === 'extremo' || pos.tipo === 'cierre') {
                // cierre del hueco: círculo más grande
                marca = crearSvg('circle', { cx: pos.x, cy: pos.y, r: pos.tipo === 'cierre' ? r * 1.6 : r });
            } else if (pos.tipo === 'pilar' || pos.tipo === 'caraViga') {
                marca = crearSvg('rect', { x: pos.x - r, y: pos.y - r, width: r * 2, height: r * 2 });
            } else if (pos.tipo === 'sobreViga') {
                marca = crearSvg('polygon', { points: `${pos.x},${pos.y - r} ${pos.x + r},${pos.y} ${pos.x},${pos.y + r} ${pos.x - r},${pos.y}` });
            } else {
                marca = crearSvg('path', { d: `M${pos.x - r} ${pos.y}H${pos.x + r}M${pos.x} ${pos.y - r}V${pos.y + r}` });
            }
            marca.classList.add('marca-snap');
            guias.appendChild(marca);
        }

        function clickViga(e) {
            const pos = snapViga(aMundo(e));
            if (!vigaInicio) {
                vigaInicio = { x: pos.x, y: pos.y };
            } else if (pos.x !== vigaInicio.x || pos.y !== vigaInicio.y) {
                terminarViga(pos);
            }
            actualizarAyuda();
            actualizarViga(e);
        }

        // Coloca la viga en curso hasta `fin`; la siguiente arranca con un click nuevo
        function terminarViga(fin) {
            colocarViga(vigaInicio, fin);
            vigaInicio = null;
            quitarFantasma();
            actualizarAyuda();
        }

        function cortarViga() {
            vigaInicio = null;
            quitarFantasma();
            actualizarAyuda();
        }

        // ── Huecos: contorno de líneas que se cierra con un click en el primer vértice ──
        // Al cerrarse se resta de las losas que toca: pueden quedar en L, C, U, O o partirse en varias.
        const capaHuecos = document.getElementById('huecos');
        const huecos = [];      // huecos colocados: { tipo: 'hueco', datos, puntos: [{x, y}, ...], el }
        let huecoPuntos = [];   // vértices del hueco que se está dibujando
        let ultimaPosHueco = null;

        // Contorno + una X adentro (en el mismo path: las líneas sueltas no tienen relleno)
        function pathHueco(puntos) {
            const contornoD = 'M' + puntos.map(p => `${p.x} ${p.y}`).join('L') + 'Z';
            return contornoD + lineasX(puntos).map(([a, b]) => `M${a.x} ${a.y}L${b.x} ${b.y}`).join('');
        }

        // Cuadrilátero convexo: de vértice a vértice opuesto. Otra forma: las diagonales del rectángulo
        // que lo encierra, solo los tramos que caen adentro del hueco
        function lineasX(puntos) {
            if (puntos.length === 4) {
                const giros = puntos.map((p, i) => {
                    const a = puntos[(i + 3) % 4], b = puntos[(i + 1) % 4];
                    return Math.sign((p.x - a.x) * (b.y - p.y) - (p.y - a.y) * (b.x - p.x));
                });
                if (giros.every(g => g === giros[0] && g !== 0)) return [[puntos[0], puntos[2]], [puntos[1], puntos[3]]];
            }
            const c = cajaDe(puntos);
            return [
                ...tramosDentro({ x: c.x0, y: c.y0 }, { x: c.x1, y: c.y1 }, puntos),
                ...tramosDentro({ x: c.x1, y: c.y0 }, { x: c.x0, y: c.y1 }, puntos),
            ];
        }

        // Partes del segmento a-b que quedan dentro del polígono
        function tramosDentro(a, b, poligono) {
            const d = { x: b.x - a.x, y: b.y - a.y };
            const ts = [0, 1];
            for (let i = 0; i < poligono.length; i++) {
                const p = poligono[i], q = poligono[(i + 1) % poligono.length];
                const e = { x: q.x - p.x, y: q.y - p.y };
                const den = d.x * e.y - d.y * e.x;
                if (Math.abs(den) < 1e-12) continue;
                const w = { x: p.x - a.x, y: p.y - a.y };
                const t = (w.x * e.y - w.y * e.x) / den;
                const u = (w.x * d.y - w.y * d.x) / den;
                if (t > 0 && t < 1 && u >= 0 && u <= 1) ts.push(t);
            }
            ts.sort((x, y) => x - y);
            const tramos = [];
            for (let k = 0; k + 1 < ts.length; k++) {
                if (ts[k + 1] - ts[k] < 1e-9) continue;
                const m = (ts[k] + ts[k + 1]) / 2;
                if (!dentroDePoligono({ x: a.x + d.x * m, y: a.y + d.y * m }, poligono)) continue;
                const ultimo = tramos[tramos.length - 1];
                const desde = { x: a.x + d.x * ts[k], y: a.y + d.y * ts[k] };
                const hasta = { x: a.x + d.x * ts[k + 1], y: a.y + d.y * ts[k + 1] };
                // tramos seguidos (p. ej. cruzando justo por un vértice) se unen en uno
                if (ultimo && Math.abs(ultimo[1].x - desde.x) < EPS && Math.abs(ultimo[1].y - desde.y) < EPS) ultimo[1] = hasta;
                else tramos.push([desde, hasta]);
            }
            return tramos;
        }

        function crearHueco(puntos) {
            return crearSvg('path', { class: 'hueco', d: pathHueco(puntos) });
        }

        function huecoEn(p) {
            return huecos.find(h => dentroDePoligono(p, h.puntos)) || null;
        }

        // Snap del vértice, por prioridad:
        // 1) el primer vértice (cierra el hueco)
        // 2) vértices de losas, huecos, vigas, pilares y del propio hueco
        // 3) sobre un borde de esos elementos (a lo largo de un borde recto se alinea como en las vigas)
        // 4) alineado horizontal/vertical con el último vértice o con otros vértices; si no, redondeado al cm
        function snapHueco(p) {
            const tol = SNAP_PX / camara.zoom;
            const dist = (q) => Math.hypot(p.x - q.x, p.y - q.y);
            const propios = huecoPuntos;
            if (propios.length >= 3 && dist(propios[0]) <= tol) return { x: propios[0].x, y: propios[0].y, tipo: 'cierre' };

            const vertices = [...propios];
            const segmentos = propios.slice(1).map((b, i) => [propios[i], b]);
            const anillo = (pts) => {
                vertices.push(...pts);
                pts.forEach((a, i) => segmentos.push([a, pts[(i + 1) % pts.length]]));
            };
            for (const l of losas) l.contornos.forEach(anillo);
            for (const h of huecos) anillo(h.puntos);
            for (const v of vigas) anillo(verticesViga(v.a, v.b, v.datos.ancho));
            for (const v of voladizos) { vertices.push(v.a, v.b); segmentos.push([v.a, v.b]); }
            const circulares = [];
            for (const pl of pilares) {
                if (pl.datos.forma === 'circular') circulares.push(figuraDe(pl.datos, pl.cx, pl.cy));
                else anillo(contorno(pl));
            }

            let mejor = null;
            for (const q of vertices) {
                const d = dist(q);
                if (d <= tol && (!mejor || d < mejor.d)) mejor = { x: q.x, y: q.y, tipo: 'extremo', d };
            }
            if (mejor) return mejor;

            // primero el último vértice: con él salen las líneas rectas del contorno
            const refs = [...propios.slice().reverse(), ...vertices];
            const alinear = (valor, eje) => {
                let ref = null;
                for (const r of refs) {
                    const d = Math.abs(r[eje] - valor);
                    if (d <= tol && (!ref || d < Math.abs(ref[eje] - valor))) ref = r;
                }
                return ref ? { v: ref[eje], ref } : { v: Math.round(valor / PX_POR_CM) * PX_POR_CM, ref: null };
            };

            let borde = null;
            for (const [a, b] of segmentos) {
                const q = puntoEnSegmento(p, a, b);
                const d = dist(q);
                if (d <= tol && (!borde || d < borde.d)) borde = { d, c0: a, c1: b, q };
            }
            for (const f of circulares) {
                const d = Math.abs(Math.hypot(p.x - f.cx, p.y - f.cy) - f.r);
                if (d <= tol && (!borde || d < borde.d)) {
                    const ang = Math.atan2(p.y - f.cy, p.x - f.cx);
                    borde = { d, q: { x: f.cx + Math.cos(ang) * f.r, y: f.cy + Math.sin(ang) * f.r } };
                }
            }
            if (borde) return borde.c0 ? snapCaraViga(borde, alinear) : { x: borde.q.x, y: borde.q.y, tipo: 'caraViga' };

            const ax = alinear(p.x, 'x');
            const ay = alinear(p.y, 'y');
            return { x: ax.v, y: ay.v, tipo: null, refX: ax.ref, refY: ay.ref };
        }

        function actualizarHueco(e) {
            ultimaPosHueco = snapHueco(aMundo(e));
            dibujarVistaHueco(ultimaPosHueco);
        }

        // Vista previa: el contorno hasta el mouse, el largo del tramo actual, guías y marca de snap
        function dibujarVistaHueco(pos) {
            const z = camara.zoom;
            guias.replaceChildren();
            if (huecoPuntos.length) {
                const pts = [...huecoPuntos, pos];
                guias.appendChild(crearSvg('path', { class: 'hueco-previa', d: 'M' + pts.map(q => `${q.x} ${q.y}`).join('L') }));

                const a = huecoPuntos[huecoPuntos.length - 1];
                const largo = Math.hypot(pos.x - a.x, pos.y - a.y);
                if (largo > 0) {
                    let nx = -(pos.y - a.y) / largo, ny = (pos.x - a.x) / largo;
                    if (ny > 0) { nx = -nx; ny = -ny; }
                    const grupo = crearSvg('g', { class: 'cota hueco' });
                    guias.appendChild(grupo);
                    agregarEtiqueta(grupo, (a.x + pos.x) / 2 + nx * 14 / z, (a.y + pos.y) / 2 + ny * 14 / z, formatoCm(largo));
                }
            }
            dibujarSnap(pos);
        }

        function clickHueco(e) {
            agregarPuntoHueco(snapHueco(aMundo(e)));
            actualizarHueco(e);
        }

        // Suma un vértice; si es el primero (con al menos 3 puestos), cierra el hueco
        function agregarPuntoHueco(pos) {
            const igual = (q) => q && Math.abs(q.x - pos.x) <= EPS && Math.abs(q.y - pos.y) <= EPS;
            if (huecoPuntos.length >= 3 && igual(huecoPuntos[0])) return cerrarHueco();
            if (igual(huecoPuntos[0]) || igual(huecoPuntos[huecoPuntos.length - 1])) return; // mismo punto
            huecoPuntos.push({ x: pos.x, y: pos.y });
            actualizarAyuda();
        }

        function abrirLargoHueco() {
            if (huecoPuntos.length && ultimaPosHueco) {
                abrirDistanciaDireccional('hueco', huecoPuntos[huecoPuntos.length - 1], ultimaPosHueco);
            }
        }

        function cerrarHueco() {
            const puntos = huecoPuntos;
            huecoPuntos = [];
            guias.replaceChildren();
            actualizarAyuda();
            if (Math.abs(areaPoligono(puntos)) <= EPS) return avisar('No se creó el hueco: el contorno no encierra superficie');

            const el = crearHueco(puntos);
            capaHuecos.appendChild(el);
            huecos.push({ tipo: 'hueco', datos: aColocar, puntos, el });

            // Se resta de cada losa que toca; si la parte, cada pieza pasa a ser una losa
            for (const losa of [...losas]) {
                const piezas = restarPoligonos(losa.contornos, [[puntos]]);
                if (!piezas) continue;
                if (!piezas.length) { quitarLosa(losa); continue; } // el hueco la cubre entera
                losa.contornos = piezas[0];
                losa.el.setAttribute('d', pathLosa(losa.contornos));
                for (const pieza of piezas.slice(1)) agregarLosa(losa.datos, pieza);
            }
            alCambiarModelo();
        }

        // Esc (o cambiar de herramienta) con el contorno abierto: el hueco no se crea
        function cancelarHueco() {
            if (!huecoPuntos.length) return;
            huecoPuntos = [];
            guias.replaceChildren();
            actualizarAyuda();
            avisar('Hueco cancelado: el contorno no se cerró y no se creó el hueco');
        }

        function borrarUltimoPuntoHueco() {
            huecoPuntos.pop();
            actualizarAyuda();
            if (ultimaPosHueco) dibujarVistaHueco(ultimaPosHueco);
        }

        // ── Resta exacta de polígonos (losa − huecos) ──
        // Se cortan todos los bordes en sus cruces y de cada tramo se mira qué queda a cada lado (un punto
        // apenas corrido): si de un lado hay losa y del otro no, el tramo es borde del resultado. Los bordes
        // se encadenan en anillos: cada exterior es una losa aparte y los interiores son sus huecos.
        const DELTA = 1e-3; // corrimiento para mirar a cada lado de un tramo

        function dentroDeAnillos(p, anillos) {
            return anillos.filter(c => dentroDePoligono(p, c)).length % 2 === 1;
        }

        function cajaDe(puntos) {
            const xs = puntos.map(p => p.x), ys = puntos.map(p => p.y);
            return { x0: Math.min(...xs), x1: Math.max(...xs), y0: Math.min(...ys), y1: Math.max(...ys) };
        }

        function cajasSeTocan(a, b) {
            return a.x0 <= b.x1 + EPS && b.x0 <= a.x1 + EPS && a.y0 <= b.y1 + EPS && b.y0 <= a.y1 + EPS;
        }

        // Corta los tramos [{ a, b, muro }] en todos sus cruces (también tramos superpuestos sobre la misma recta).
        // Devuelve los vértices unificados y las aristas sin repetidos: { verts, aristas: [{ i, j, muro }] }
        function partirTramos(tramos) {
            // Vértices unificados (los casi iguales son el mismo)
            const verts = [];
            const idDe = (p) => {
                for (let i = 0; i < verts.length; i++) {
                    if (Math.abs(verts[i].x - p.x) <= EPS && Math.abs(verts[i].y - p.y) <= EPS) return i;
                }
                // los cruces calculados se redondean (44.99999999999999 → 45)
                verts.push({ x: Math.round(p.x * 1e9) / 1e9, y: Math.round(p.y * 1e9) / 1e9 });
                return verts.length - 1;
            };

            const segs = tramos
                .filter(({ a, b }) => Math.hypot(b.x - a.x, b.y - a.y) > EPS)
                .map(({ a, b, muro }) => ({ a, b, muro, cortes: [{ t: 0, p: a }, { t: 1, p: b }] }));

            const cruz = (u, v) => u.x * v.y - u.y * v.x;
            for (let i = 0; i < segs.length; i++) {
                const s = segs[i];
                const d1 = { x: s.b.x - s.a.x, y: s.b.y - s.a.y }, l1 = Math.hypot(d1.x, d1.y);
                for (let j = i + 1; j < segs.length; j++) {
                    const r = segs[j];
                    if (Math.max(s.a.x, s.b.x) < Math.min(r.a.x, r.b.x) - EPS || Math.max(r.a.x, r.b.x) < Math.min(s.a.x, s.b.x) - EPS ||
                        Math.max(s.a.y, s.b.y) < Math.min(r.a.y, r.b.y) - EPS || Math.max(r.a.y, r.b.y) < Math.min(s.a.y, s.b.y) - EPS) continue;
                    const d2 = { x: r.b.x - r.a.x, y: r.b.y - r.a.y }, l2 = Math.hypot(d2.x, d2.y);
                    const w = { x: r.a.x - s.a.x, y: r.a.y - s.a.y };
                    const den = cruz(d1, d2);
                    if (Math.abs(den) > 1e-12 * l1 * l2) {
                        const t = cruz(w, d2) / den, u = cruz(w, d1) / den;
                        if (t < -EPS / l1 || t > 1 + EPS / l1 || u < -EPS / l2 || u > 1 + EPS / l2) continue;
                        const p = { x: s.a.x + t * d1.x, y: s.a.y + t * d1.y };
                        s.cortes.push({ t, p });
                        r.cortes.push({ t: u, p });
                    } else if (Math.abs(cruz(w, d1)) / l1 <= EPS) {
                        // misma recta: los extremos de cada uno que caen sobre el otro
                        for (const q of [r.a, r.b]) {
                            const t = ((q.x - s.a.x) * d1.x + (q.y - s.a.y) * d1.y) / (l1 * l1);
                            if (t > 0 && t < 1) s.cortes.push({ t, p: q });
                        }
                        for (const q of [s.a, s.b]) {
                            const u = ((q.x - r.a.x) * d2.x + (q.y - r.a.y) * d2.y) / (l2 * l2);
                            if (u > 0 && u < 1) r.cortes.push({ t: u, p: q });
                        }
                    }
                }
            }

            // Tramos partidos en sus cortes, sin repetidos (si uno de los repetidos es muro, queda muro)
            const aristas = new Map();
            for (const s of segs) {
                const ids = s.cortes.sort((a, b) => a.t - b.t).map(c => idDe(c.p));
                for (let k = 0; k + 1 < ids.length; k++) {
                    const i = ids[k], j = ids[k + 1];
                    if (i === j) continue;
                    const clave = i < j ? `${i},${j}` : `${j},${i}`;
                    const previa = aristas.get(clave);
                    if (!previa) aristas.set(clave, { i, j, muro: !!s.muro });
                    else if (s.muro) previa.muro = true;
                }
            }
            return { verts, aristas: [...aristas.values()] };
        }

        // ¿Cumple `queda` un punto apenas corrido a la izquierda (-dy, dx) y a la derecha del medio de a-b?
        function ladosArista(a, b, queda) {
            const l = Math.hypot(b.x - a.x, b.y - a.y);
            const n = { x: -(b.y - a.y) / l * DELTA, y: (b.x - a.x) / l * DELTA };
            const m = { x: (a.x + b.x) / 2, y: (a.y + b.y) / 2 };
            return [queda({ x: m.x + n.x, y: m.y + n.y }), queda({ x: m.x - n.x, y: m.y - n.y })];
        }

        // Punto apenas a la izquierda del primer tramo del anillo (del lado de adentro si es exterior)
        function puntoAIzquierda(c) {
            const a = c[0], b = c[1];
            const l = Math.hypot(b.x - a.x, b.y - a.y);
            return { x: (a.x + b.x) / 2 - (b.y - a.y) / l * DELTA, y: (a.y + b.y) / 2 + (b.x - a.x) / l * DELTA };
        }

        // Encadena bordes orientados (salientes: vértice → [{ de, hasta, usada }]) en anillos.
        // En cada vértice se sigue por el primer borde girando en sentido horario desde el que vuelve;
        // así, si dos piezas se tocan en un vértice, cada anillo sigue por su pieza
        function encadenarAnillos(verts, salientes) {
            const cruz = (u, v) => u.x * v.y - u.y * v.x;
            const angulo = (de, hasta) => Math.atan2(verts[hasta].y - verts[de].y, verts[hasta].x - verts[de].x);
            const anillos = [];
            for (const lista of salientes.values()) {
                for (const inicio of lista) {
                    if (inicio.usada) continue;
                    const ids = [];
                    let h = inicio;
                    while (h && !h.usada) {
                        h.usada = true;
                        ids.push(h.de);
                        const vuelta = angulo(h.hasta, h.de);
                        let sig = null, mejorGiro = Infinity;
                        for (const c of salientes.get(h.hasta) || []) {
                            let giro = vuelta - angulo(c.de, c.hasta);
                            while (giro <= 1e-12) giro += 2 * Math.PI;
                            while (giro > 2 * Math.PI) giro -= 2 * Math.PI;
                            if (giro < mejorGiro) { mejorGiro = giro; sig = c; }
                        }
                        h = sig;
                    }
                    // sin vértices intermedios en línea recta
                    const pts = ids.map(k => verts[k]);
                    const limpio = pts.filter((p, k) => {
                        const a = pts[(k - 1 + pts.length) % pts.length], b = pts[(k + 1) % pts.length];
                        const ab = { x: b.x - a.x, y: b.y - a.y };
                        return Math.abs(cruz({ x: p.x - a.x, y: p.y - a.y }, ab)) / (Math.hypot(ab.x, ab.y) || 1) > EPS;
                    });
                    if (limpio.length >= 3) anillos.push(limpio);
                }
            }
            return anillos;
        }

        // base: anillos de la losa · restas: lista de figuras (cada una, su lista de anillos).
        // Devuelve null si ninguna resta la toca; si no, las piezas que quedan: [[exterior, ...huecos], ...]
        function restarPoligonos(base, restas) {
            const cajaBase = cajaDe(base.flat());
            const utiles = restas.filter(r => cajasSeTocan(cajaBase, cajaDe(r.flat())));
            if (!utiles.length) return null;

            const tramos = [];
            for (const anillo of [...base, ...utiles.flat()]) {
                anillo.forEach((a, i) => tramos.push({ a, b: anillo[(i + 1) % anillo.length] }));
            }
            const { verts, aristas } = partirTramos(tramos);

            // Bordes del resultado, orientados con la losa del lado (-dy, dx)
            const queda = (p) => dentroDeAnillos(p, base) && !utiles.some(r => dentroDeAnillos(p, r));
            const salientes = new Map(); // vértice → bordes que salen de él
            for (const { i, j } of aristas) {
                const [izq, der] = ladosArista(verts[i], verts[j], queda);
                if (izq === der) continue;
                const [de, hasta] = izq ? [i, j] : [j, i];
                if (!salientes.has(de)) salientes.set(de, []);
                salientes.get(de).push({ de, hasta, usada: false });
            }
            const anillos = encadenarAnillos(verts, salientes);

            // Cada exterior es una pieza; cada interior va a la pieza más chica que lo contiene
            const piezas = anillos.filter(c => areaPoligono(c) > 0).map(c => [c]);
            for (const interior of anillos.filter(c => areaPoligono(c) < 0)) {
                const p = puntoAIzquierda(interior);
                let dueña = null;
                for (const pieza of piezas) {
                    if (dentroDePoligono(p, pieza[0]) && (!dueña || Math.abs(areaPoligono(pieza[0])) < Math.abs(areaPoligono(dueña[0])))) dueña = pieza;
                }
                if (dueña) dueña.push(interior);
            }
            return piezas;
        }

        // ── Distancia exacta con Tab ──
        // Pilar: toma el pilar más cercano y la dirección hacia donde apunta el mouse (→ ← ↑ ↓),
        // y coloca el nuevo pilar a la distancia tipeada, de borde a borde.
        // Viga: con el inicio ya marcado, la termina con el largo tipeado en la dirección del mouse.
        // Mover: con la base ya marcada, mueve la selección la distancia tipeada en la dirección del mouse.
        const cajaDistancia    = document.getElementById('distancia');
        const inputDistancia   = document.getElementById('inputDistancia');
        const flechaDistancia  = document.getElementById('flechaDistancia');
        const FLECHAS = { '1,0': '→', '-1,0': '←', '0,1': '↓', '0,-1': '↑' };

        // Mientras la caja está abierta:
        //   pilar        → { ref, dir: {x, y}, fijo }
        //   viga / mover → { modo: 'viga' | 'mover', origen: {x, y}, dir: {x, y} (unitario) }
        let distancia = null;

        function abrirLargoViga() {
            if (vigaInicio && ultimaPosViga) abrirDistanciaDireccional('viga', vigaInicio, ultimaPosViga);
        }

        function abrirDistanciaMover() {
            if (mover.base && mover.ultimo) abrirDistanciaDireccional('mover', mover.base, mover.ultimo);
        }

        // Caja con flecha girada en la dirección origen → destino
        function abrirDistanciaDireccional(modo, origen, destino) {
            const dx = destino.x - origen.x;
            const dy = destino.y - origen.y;
            const largo = Math.hypot(dx, dy);
            if (!largo) return; // sin dirección todavía

            distancia = { modo, origen: { x: origen.x, y: origen.y }, dir: { x: dx / largo, y: dy / largo } };
            flechaDistancia.textContent = '→';
            flechaDistancia.style.transform = `rotate(${Math.atan2(dy, dx)}rad)`;
            inputDistancia.value = Math.round(largo / PX_POR_CM);
            cajaDistancia.hidden = false;
            inputDistancia.focus();
            inputDistancia.select();
            refrescarDistancia();
        }

        function abrirDistancia() {
            if (!ultimaPos || !pilares.length) return;
            const yo = figuraDe(aColocar, ultimaPos.x, ultimaPos.y);
            const cercano = pilarMasCercano(yo);
            const ref = cercano.figura;

            const dx = ultimaPos.x - ref.cx;
            const dy = ultimaPos.y - ref.cy;
            const dir = Math.abs(dx) >= Math.abs(dy)
                ? { x: Math.sign(dx) || 1, y: 0 }
                : { x: 0, y: Math.sign(dy) || 1 };

            // el otro eje queda como está (alineado si había snap)
            distancia = { ref, dir, fijo: dir.x ? ultimaPos.y : ultimaPos.x };
            flechaDistancia.textContent = FLECHAS[`${dir.x},${dir.y}`];
            flechaDistancia.style.transform = '';
            inputDistancia.value = Math.max(0, Math.round(cercano.d / PX_POR_CM));
            cajaDistancia.hidden = false;
            inputDistancia.focus();
            inputDistancia.select();
            refrescarDistancia();
        }

        function cerrarDistancia() {
            distancia = null;
            cajaDistancia.hidden = true;
            inputDistancia.blur();
        }

        // Centro del nuevo pilar a `cm` de borde a borde del pilar de referencia
        function posicionPorDistancia(cm) {
            const { ref, dir, fijo } = distancia;
            const yo = figuraDe(aColocar, 0, 0);
            const d  = cm * PX_POR_CM;
            return dir.x
                ? { x: ref.cx + dir.x * (ref.hw + d + yo.hw), y: fijo }
                : { x: fijo, y: ref.cy + dir.y * (ref.hh + d + yo.hh) };
        }

        function valorDistancia() {
            const cm = parseFloat(inputDistancia.value.replace(',', '.'));
            return Number.isFinite(cm) && cm >= 0 ? cm : null;
        }

        // Final de la viga a `cm` del inicio, en la dirección tomada
        function finPorLargo(cm) {
            const d = cm * PX_POR_CM;
            return { x: distancia.origen.x + distancia.dir.x * d, y: distancia.origen.y + distancia.dir.y * d };
        }

        // Mueve la vista previa según lo tipeado y ubica la caja al lado del pilar / del final de la viga
        function refrescarDistancia() {
            const cm  = valorDistancia();
            if (distancia.modo) {
                const modo = distancia.modo;
                const fin = cm ? finPorLargo(cm) : (modo === 'viga' ? ultimaPosViga : modo === 'hueco' ? ultimaPosHueco : mover.ultimo);
                if (modo === 'viga') dibujarVistaViga({ x: fin.x, y: fin.y });
                else if (modo === 'hueco') dibujarVistaHueco({ x: fin.x, y: fin.y });
                else dibujarMover({ x: fin.x, y: fin.y });
                cajaDistancia.style.left = `${fin.x * camara.zoom + camara.x + 14}px`;
                cajaDistancia.style.top  = `${fin.y * camara.zoom + camara.y + 14}px`;
                return;
            }
            const pos = cm === null ? ultimaPos : posicionPorDistancia(cm);
            mostrarFantasma(pos, distancia.ref);

            const yo = figuraDe(aColocar, pos.x, pos.y);
            cajaDistancia.style.left = `${(yo.cx + yo.hw) * camara.zoom + camara.x + 14}px`;
            cajaDistancia.style.top  = `${(yo.cy + yo.hh) * camara.zoom + camara.y + 14}px`;
        }

        inputDistancia.addEventListener('input', refrescarDistancia);

        inputDistancia.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const cm = valorDistancia();
                if (cm === null) return;
                if (distancia.modo) {
                    if (!cm) return; // largo / desplazamiento 0 no tiene sentido
                    const fin = finPorLargo(cm);
                    const modo = distancia.modo;
                    cerrarDistancia();
                    if (modo === 'viga') terminarViga(fin);
                    else if (modo === 'hueco') {
                        agregarPuntoHueco(fin);
                        if (huecoPuntos.length) { ultimaPosHueco = fin; dibujarVistaHueco(fin); }
                    }
                    else aplicarMovimiento(fin.x - mover.base.x, fin.y - mover.base.y);
                    return;
                }
                const pos = posicionPorDistancia(cm);
                colocarPilar(pos);
                // Sigue en la misma dirección desde el pilar recién puesto: Enter de nuevo repite la distancia
                distancia.ref = figuraDe(aColocar, pos.x, pos.y);
                inputDistancia.select();
                refrescarDistancia();
            } else if (e.key === 'Escape' || e.key === 'Tab') {
                e.preventDefault();
                e.stopPropagation();
                cerrarDistancia();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Tab' || distancia) return;
            if (insertar.classList.contains('abierto')) return; // en el menú, Tab navega normal
            if (mover) {
                e.preventDefault();
                abrirDistanciaMover();
                return;
            }
            if (!aColocar) return;
            e.preventDefault();
            if (aColocar.tipo === 'pilar') abrirDistancia();
            else if (esTramo(aColocar.tipo)) abrirLargoViga();
            else if (aColocar.tipo === 'hueco') abrirLargoHueco();
        });

        // ── Zoom y desplazamiento de la vista ──
        const vista     = document.getElementById('vista');
        const ZOOM_MIN  = 0.1;
        const ZOOM_MAX  = 10;
        const camara    = { x: 0, y: 0, zoom: 1 }; // pantalla = mundo * zoom + (x, y)

        function aplicarCamara() {
            vista.setAttribute('transform', `translate(${camara.x} ${camara.y}) scale(${camara.zoom})`);
            posicionarAcciones();
            // el tamaño de las etiquetas depende del zoom (al desplazarse no hace falta redibujar)
            if (camara.zoom !== zoomCotas) {
                actualizarCotas();
                actualizarIdentificacion();
                actualizarDimensiones();
            }
        }

        // Convierte la posición del mouse a coordenadas del dibujo
        function aMundo(e) {
            return {
                x: (e.clientX - camara.x) / camara.zoom,
                y: (e.clientY - camara.y) / camara.zoom,
            };
        }

        // Rueda: zoom centrado en el cursor (el punto bajo el mouse queda quieto)
        pizarra.addEventListener('wheel', (e) => {
            e.preventDefault();
            const antes = aMundo(e);
            const factor = Math.exp(-e.deltaY * 0.0015);
            camara.zoom = Math.min(ZOOM_MAX, Math.max(ZOOM_MIN, camara.zoom * factor));
            camara.x = e.clientX - antes.x * camara.zoom;
            camara.y = e.clientY - antes.y * camara.zoom;
            aplicarCamara();
            actualizarFantasma(e); // la tolerancia del snap y el tamaño de las etiquetas dependen del zoom
        }, { passive: false });

        // Arrastrar: botón izquierdo cuando no hay nada para insertar, o la rueda (botón del medio) siempre
        let arrastre = null;

        pizarra.addEventListener('mousedown', (e) => {
            // En modo selección el botón izquierdo hace click/lazo; el del medio sigue desplazando
            if (seleccionando && e.button === 0) {
                if (mover) return e.preventDefault(); // al mover, los clicks los maneja el evento click
                return empezarLazo(e);
            }

            const izquierdoLibre = e.button === 0 && !aColocar;
            if (!izquierdoLibre && e.button !== 1) return;
            e.preventDefault();
            arrastre = { x: e.clientX - camara.x, y: e.clientY - camara.y };
            document.body.classList.add('arrastrando');
        });

        window.addEventListener('mousemove', (e) => {
            if (lazo) return moverLazo(e);
            if (!arrastre) return;
            camara.x = e.clientX - arrastre.x;
            camara.y = e.clientY - arrastre.y;
            aplicarCamara();
            if (distancia) refrescarDistancia();
        });

        window.addEventListener('mouseup', (e) => {
            if (lazo) terminarLazo(e);
            arrastre = null;
            document.body.classList.remove('arrastrando');
        });

        // ── Selección ──
        // Click sobre un elemento: lo selecciona. Mantener apretado y arrastrar: lazo libre,
        // se seleccionan los elementos que quedan completamente adentro. Shift suma/quita.
        const btnSeleccionar = document.getElementById('btnSeleccionar');
        const seleccion = new Set(); // pilares / vigas seleccionados
        let seleccionando = false;
        let lazo = null; // { puntos: [{x, y}], inicio: {clientX, clientY}, objetivo, movido, sumar, path }

        const ICONO_SELECCIONAR = '<svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linejoin="round"><path d="M3 2l9.5 6.2-4.2.8 2.4 4.6-1.6.8-2.4-4.6L3 12.6z"/></svg>';

        function empezarSeleccion() {
            dejarDeColocar();
            seleccionando = true;
            btnSeleccionar.classList.add('activo');
            btnSeleccionar.setAttribute('aria-pressed', 'true');
            document.body.classList.add('seleccionando');
            estadoIcono.innerHTML = ICONO_SELECCIONAR;
            estado.hidden = false;
            actualizarEstadoSeleccion();
        }

        function terminarSeleccion() {
            if (!seleccionando) return;
            cancelarMover();
            seleccionando = false;
            limpiarSeleccion();
            btnSeleccionar.classList.remove('activo');
            btnSeleccionar.setAttribute('aria-pressed', 'false');
            document.body.classList.remove('seleccionando');
            estado.hidden = true;
        }

        function actualizarEstadoSeleccion() {
            const n = seleccion.size;
            estadoTexto.textContent = n === 0 ? 'Seleccionar' : `${n} ${n === 1 ? 'elemento' : 'elementos'}`;
            if (mover) {
                estadoTexto.textContent = `Moviendo ${estadoTexto.textContent.toLowerCase()}`;
                estadoAyuda.textContent = mover.base
                    ? 'Click en el destino · Tab: distancia exacta · Esc cancela'
                    : 'Click en el punto base · Esc cancela';
            } else {
                estadoAyuda.textContent = 'Click: un elemento · Arrastrar: lazo · Shift: sumar · Supr: eliminar';
            }
            posicionarAcciones();
        }

        function marcar(obj, si) {
            if (si) seleccion.add(obj); else seleccion.delete(obj);
            obj.el.classList.toggle('seleccionado', si);
        }

        function limpiarSeleccion() {
            for (const obj of [...seleccion]) marcar(obj, false);
            actualizarEstadoSeleccion();
        }

        // Pilar o viga dibujado en ese elemento SVG
        function objetoDe(el) {
            return todosLosElementos().find(o => o.el === el) || null;
        }

        // Puntos del contorno, para ver si el elemento entra entero en el lazo
        function contorno(obj) {
            if (obj.tipo === 'losa') return obj.contornos.flat();
            if (obj.tipo === 'hueco') return obj.puntos;
            if (obj.tipo === 'viga') return verticesViga(obj.a, obj.b, obj.datos.ancho);
            if (obj.tipo === 'voladizo') return [obj.a, obj.b];
            const f = figuraDe(obj.datos, obj.cx, obj.cy);
            if (f.circular) {
                return Array.from({ length: 12 }, (_, i) => ({
                    x: f.cx + Math.cos(i * Math.PI / 6) * f.r,
                    y: f.cy + Math.sin(i * Math.PI / 6) * f.r,
                }));
            }
            return [
                { x: f.cx - f.hw, y: f.cy - f.hh }, { x: f.cx + f.hw, y: f.cy - f.hh },
                { x: f.cx + f.hw, y: f.cy + f.hh }, { x: f.cx - f.hw, y: f.cy + f.hh },
            ];
        }

        // Punto dentro de polígono (ray casting)
        function dentroDePoligono(p, poligono) {
            let dentro = false;
            for (let i = 0, j = poligono.length - 1; i < poligono.length; j = i++) {
                const a = poligono[i], b = poligono[j];
                if ((a.y > p.y) !== (b.y > p.y) && p.x < (b.x - a.x) * (p.y - a.y) / (b.y - a.y) + a.x) {
                    dentro = !dentro;
                }
            }
            return dentro;
        }

        function empezarLazo(e) {
            e.preventDefault();
            lazo = {
                puntos: [aMundo(e)],
                inicio: { clientX: e.clientX, clientY: e.clientY },
                objetivo: e.target.closest('.pilar, .viga, .losa, .hueco, .voladizo'),
                movido: false,
                sumar: e.shiftKey,
                path: null,
            };
        }

        function moverLazo(e) {
            // recién después de moverse unos px se considera lazo (si no, es un click)
            if (!lazo.movido) {
                if (Math.hypot(e.clientX - lazo.inicio.clientX, e.clientY - lazo.inicio.clientY) < 4) return;
                lazo.movido = true;
                lazo.path = crearSvg('path', { id: 'lazo' });
                vista.appendChild(lazo.path);
            }
            lazo.puntos.push(aMundo(e));
            const d = lazo.puntos.map((p, i) => `${i ? 'L' : 'M'}${p.x} ${p.y}`).join('') + 'Z';
            lazo.path.setAttribute('d', d);
        }

        function terminarLazo(e) {
            const { movido, objetivo, sumar, puntos, path } = lazo;
            lazo = null;
            if (path) path.remove();

            if (!movido) {
                // Click: un elemento (o nada, si fue en vacío)
                const obj = objetivo && objetoDe(objetivo);
                if (sumar) {
                    if (obj) marcar(obj, !seleccion.has(obj));
                } else {
                    limpiarSeleccion();
                    if (obj) marcar(obj, true);
                }
            } else {
                if (!sumar) limpiarSeleccion();
                for (const obj of todosLosElementos()) {
                    if (!esVisible(obj.tipo)) continue; // lo oculto no se selecciona
                    if (contorno(obj).every(p => dentroDePoligono(p, puntos))) marcar(obj, true);
                }
            }
            actualizarEstadoSeleccion();
        }

        btnSeleccionar.addEventListener('click', () => {
            if (seleccionando) terminarSeleccion();
            else empezarSeleccion();
        });

        // ── Mover y eliminar la selección ──
        // Mover: click en un punto base y click en el destino (o Tab para una distancia exacta).
        const acciones = document.getElementById('acciones');
        let mover = null; // { base: {x, y} | null, ultimo: último punto con snap }

        // Ubica la barra de acciones centrada encima de lo seleccionado
        function posicionarAcciones() {
            if (!seleccion.size || mover) { acciones.hidden = true; return; }
            let minX = Infinity, maxX = -Infinity, minY = Infinity;
            for (const obj of seleccion) {
                for (const p of contorno(obj)) {
                    minX = Math.min(minX, p.x);
                    maxX = Math.max(maxX, p.x);
                    minY = Math.min(minY, p.y);
                }
            }
            acciones.style.left = `${(minX + maxX) / 2 * camara.zoom + camara.x}px`;
            acciones.style.top  = `${Math.max(70, minY * camara.zoom + camara.y)}px`;
            acciones.hidden = false;
        }

        function eliminarSeleccion() {
            for (const obj of seleccion) {
                obj.el.remove();
                const lista = listaDe(obj.tipo);
                lista.splice(lista.indexOf(obj), 1);
            }
            seleccion.clear();
            actualizarEstadoSeleccion();
            alCambiarModelo();
        }

        function empezarMover() {
            mover = { base: null, ultimo: null };
            document.body.classList.add('moviendo');
            actualizarEstadoSeleccion();
        }

        function cancelarMover() {
            if (!mover) return;
            if (distancia) cerrarDistancia();
            for (const obj of seleccion) obj.el.removeAttribute('transform');
            mover = null;
            document.body.classList.remove('moviendo');
            guias.replaceChildren();
            actualizarEstadoSeleccion();
        }

        // Puntos donde se engancha el mover: centros, esquinas, extremos de vigas
        function puntosNotables(obj) {
            if (obj.tipo === 'losa') return obj.contornos.flat();
            if (obj.tipo === 'hueco') return obj.puntos;
            if (obj.tipo === 'viga') return [obj.a, obj.b, ...verticesViga(obj.a, obj.b, obj.datos.ancho)];
            if (obj.tipo === 'voladizo') return [obj.a, obj.b];
            const f = figuraDe(obj.datos, obj.cx, obj.cy);
            const centro = { x: f.cx, y: f.cy };
            if (f.circular) {
                return [centro,
                    { x: f.cx + f.r, y: f.cy }, { x: f.cx - f.r, y: f.cy },
                    { x: f.cx, y: f.cy + f.r }, { x: f.cx, y: f.cy - f.r }];
            }
            return [centro, ...contorno(obj)];
        }

        function snapMover(p) {
            const tol = SNAP_PX / camara.zoom;
            // para el punto base vale cualquier elemento; para el destino, solo los que no se mueven
            const objs = todosLosElementos().filter(o => !mover.base || !seleccion.has(o));
            const notables = objs.flatMap(puntosNotables);

            let mejor = null;
            for (const q of notables) {
                const d = Math.hypot(p.x - q.x, p.y - q.y);
                if (d <= tol && (!mejor || d < mejor.d)) mejor = { x: q.x, y: q.y, d, tipo: 'punto' };
            }
            if (mejor) return mejor;

            // Alineación: con la base (movimiento recto) y con los puntos notables
            const refs = [...(mover.base ? [mover.base] : []), ...notables];
            let refX = null, refY = null;
            for (const r of refs) {
                const dx = Math.abs(r.x - p.x), dy = Math.abs(r.y - p.y);
                if (dx <= tol && (!refX || dx < Math.abs(refX.x - p.x))) refX = r;
                if (dy <= tol && (!refY || dy < Math.abs(refY.y - p.y))) refY = r;
            }
            return {
                x: refX ? refX.x : Math.round(p.x / PX_POR_CM) * PX_POR_CM,
                y: refY ? refY.y : Math.round(p.y / PX_POR_CM) * PX_POR_CM,
                refX, refY,
            };
        }

        function actualizarMover(e) {
            mover.ultimo = snapMover(aMundo(e));
            dibujarMover(mover.ultimo);
        }

        // Vista previa: desplaza lo seleccionado y muestra la distancia desde la base
        function dibujarMover(pos) {
            const z = camara.zoom;
            guias.replaceChildren();

            if (mover.base) {
                const b  = mover.base;
                const dx = pos.x - b.x, dy = pos.y - b.y;
                for (const obj of seleccion) obj.el.setAttribute('transform', `translate(${dx} ${dy})`);

                const d = Math.hypot(dx, dy);
                if (d > 0) {
                    const cota = crearSvg('g', { class: 'cota mover' });
                    cota.appendChild(crearSvg('line', { x1: b.x, y1: b.y, x2: pos.x, y2: pos.y }));
                    cota.appendChild(crearSvg('circle', { cx: b.x, cy: b.y, r: 2.5 / z }));
                    guias.appendChild(cota);
                    // etiqueta al costado de la línea, del lado de "arriba"
                    let nx = -dy / d, ny = dx / d;
                    if (ny > 0) { nx = -nx; ny = -ny; }
                    agregarEtiqueta(cota, (b.x + pos.x) / 2 + nx * 14 / z, (b.y + pos.y) / 2 + ny * 14 / z, formatoCm(d));
                }
            }

            if (pos.refX) guias.appendChild(crearSvg('line', { class: 'guia-snap', x1: pos.x, y1: pos.refX.y, x2: pos.x, y2: pos.y }));
            if (pos.refY) guias.appendChild(crearSvg('line', { class: 'guia-snap', x1: pos.refY.x, y1: pos.y, x2: pos.x, y2: pos.y }));

            const r = 5 / z;
            const marca = pos.tipo === 'punto'
                ? crearSvg('circle', { cx: pos.x, cy: pos.y, r })
                : crearSvg('path', { d: `M${pos.x - r} ${pos.y}H${pos.x + r}M${pos.x} ${pos.y - r}V${pos.y + r}` });
            marca.classList.add('marca-snap');
            guias.appendChild(marca);
        }

        function clickMover(e) {
            const pos = snapMover(aMundo(e));
            if (!mover.base) {
                mover.base = { x: pos.x, y: pos.y };
                actualizarEstadoSeleccion();
                dibujarMover(pos);
                return;
            }
            aplicarMovimiento(pos.x - mover.base.x, pos.y - mover.base.y);
        }

        function aplicarMovimiento(dx, dy) {
            for (const obj of seleccion) {
                obj.el.removeAttribute('transform');
                if (obj.tipo === 'losa') {
                    obj.contornos = obj.contornos.map(c => c.map(p => ({ x: p.x + dx, y: p.y + dy })));
                    obj.el.setAttribute('d', pathLosa(obj.contornos));
                } else if (obj.tipo === 'hueco') {
                    // mueve solo el contorno: lo que ya se restó de las losas queda como está
                    obj.puntos = obj.puntos.map(p => ({ x: p.x + dx, y: p.y + dy }));
                    obj.el.setAttribute('d', pathHueco(obj.puntos));
                } else if (esTramo(obj.tipo)) {
                    obj.a = { x: obj.a.x + dx, y: obj.a.y + dy };
                    obj.b = { x: obj.b.x + dx, y: obj.b.y + dy };
                    moverTramo(obj.el, obj.datos, obj.a, obj.b);
                } else {
                    obj.cx += dx;
                    obj.cy += dy;
                    moverPilar(obj.el, obj.datos, obj.cx, obj.cy);
                }
            }
            mover = null;
            document.body.classList.remove('moviendo');
            guias.replaceChildren();
            actualizarEstadoSeleccion();
            alCambiarModelo();
        }

        // ── Cotas ──
        // Pilares: separación libre (cara a cara) con el vecino más cercano a la derecha y abajo,
        //          si están enfrentados (sus rangos en Y / en X se solapan).
        // Vigas: largo de cada viga y separación (cara a cara) con la viga paralela más cercana a cada lado.
        const capaCotas       = document.getElementById('cotas');
        const btnCotas        = document.getElementById('btnCotas');
        const panelCotas      = document.getElementById('panelCotas');
        const chkCotasPilares = document.getElementById('chkCotasPilares');
        const chkCotasVigas   = document.getElementById('chkCotasVigas');
        let zoomCotas = null; // zoom con el que se dibujaron (las etiquetas tienen tamaño fijo en pantalla)

        function abrirPanelCotas(abrir) {
            panelCotas.classList.toggle('abierto', abrir);
            btnCotas.setAttribute('aria-expanded', abrir);
        }

        btnCotas.addEventListener('click', () => {
            abrirPanelCotas(!panelCotas.classList.contains('abierto'));
        });

        for (const chk of [chkCotasPilares, chkCotasVigas]) {
            chk.addEventListener('change', () => {
                btnCotas.classList.toggle('activo', chkCotasPilares.checked || chkCotasVigas.checked);
                actualizarCotas();
            });
        }

        // Línea de cota de p1 a p2 con su etiqueta en el medio
        function dibujarCota(p1, p2, texto, clase, desplazamiento = { x: 0, y: 0 }) {
            const z = camara.zoom;
            const g = crearSvg('g', { class: `cota ${clase}` });
            g.appendChild(crearSvg('line', { x1: p1.x, y1: p1.y, x2: p2.x, y2: p2.y }));
            g.appendChild(crearSvg('circle', { cx: p1.x, cy: p1.y, r: 2.5 / z }));
            g.appendChild(crearSvg('circle', { cx: p2.x, cy: p2.y, r: 2.5 / z }));
            capaCotas.appendChild(g);
            agregarEtiqueta(g, (p1.x + p2.x) / 2 + desplazamiento.x, (p1.y + p2.y) / 2 + desplazamiento.y, texto);
        }

        // Medio ancho del pilar a la altura `y` (para circulares depende de la altura)
        function medioAnchoEn(f, y) {
            return f.circular ? Math.sqrt(Math.max(0, f.r ** 2 - (y - f.cy) ** 2)) : f.hw;
        }
        function medioAltoEn(f, x) {
            return f.circular ? Math.sqrt(Math.max(0, f.r ** 2 - (x - f.cx) ** 2)) : f.hh;
        }

        function cotasDePilares() {
            const figs = pilares.map(p => figuraDe(p.datos, p.cx, p.cy));
            for (const a of figs) {
                // Vecino a la derecha (enfrentado en Y) y vecino abajo (enfrentado en X)
                let der = null, abajo = null;
                for (const b of figs) {
                    if (a === b) continue;
                    const solapeY = Math.min(a.cy + a.hh, b.cy + b.hh) - Math.max(a.cy - a.hh, b.cy - b.hh);
                    const solapeX = Math.min(a.cx + a.hw, b.cx + b.hw) - Math.max(a.cx - a.hw, b.cx - b.hw);
                    if (solapeY > 0 && b.cx > a.cx && (!der || b.cx < der.cx)) der = b;
                    if (solapeX > 0 && b.cy > a.cy && (!abajo || b.cy < abajo.cy)) abajo = b;
                }

                if (der) {
                    const y  = (Math.max(a.cy - a.hh, der.cy - der.hh) + Math.min(a.cy + a.hh, der.cy + der.hh)) / 2;
                    const x1 = a.cx + medioAnchoEn(a, y);
                    const x2 = der.cx - medioAnchoEn(der, y);
                    if (x2 > x1) dibujarCota({ x: x1, y }, { x: x2, y }, formatoCm(x2 - x1), 'sep-pilar');
                }
                if (abajo) {
                    const x  = (Math.max(a.cx - a.hw, abajo.cx - abajo.hw) + Math.min(a.cx + a.hw, abajo.cx + abajo.hw)) / 2;
                    const y1 = a.cy + medioAltoEn(a, x);
                    const y2 = abajo.cy - medioAltoEn(abajo, x);
                    if (y2 > y1) dibujarCota({ x, y: y1 }, { x, y: y2 }, formatoCm(y2 - y1), 'sep-pilar');
                }
            }
        }

        function cotasDeVigas() {
            const z = camara.zoom;
            const geo = vigas.map(v => {
                const largo = Math.hypot(v.b.x - v.a.x, v.b.y - v.a.y) || 1;
                const u = { x: (v.b.x - v.a.x) / largo, y: (v.b.y - v.a.y) / largo };
                return { v, largo, u, n: { x: -u.y, y: u.x }, h: v.datos.ancho * PX_POR_CM / 2 };
            });

            // Largo de cada viga, al costado del lado de "arriba"
            for (const g of geo) {
                let { x: nx, y: ny } = g.n;
                if (ny > 0) { nx = -nx; ny = -ny; }
                const sep = g.h + 14 / z;
                const grupo = crearSvg('g', { class: 'cota largo' });
                capaCotas.appendChild(grupo);
                agregarEtiqueta(grupo, (g.v.a.x + g.v.b.x) / 2 + nx * sep, (g.v.a.y + g.v.b.y) / 2 + ny * sep, formatoCm(g.largo));
            }

            // Separación con la viga paralela más cercana a cada lado (solo si se enfrentan)
            const hechas = new Set();
            geo.forEach((a, i) => {
                const masCercana = { '1': null, '-1': null };
                geo.forEach((b, j) => {
                    if (i === j) return;
                    if (Math.abs(a.u.x * b.u.y - a.u.y * b.u.x) > 0.01) return; // no son paralelas

                    // distancia entre ejes (con signo, según el lado) y tramo en que se enfrentan
                    const dist = (b.v.a.x - a.v.a.x) * a.n.x + (b.v.a.y - a.v.a.y) * a.n.y;
                    const t1 = (b.v.a.x - a.v.a.x) * a.u.x + (b.v.a.y - a.v.a.y) * a.u.y;
                    const t2 = (b.v.b.x - a.v.a.x) * a.u.x + (b.v.b.y - a.v.a.y) * a.u.y;
                    const desde = Math.max(0, Math.min(t1, t2));
                    const hasta = Math.min(a.largo, Math.max(t1, t2));
                    const libre = Math.abs(dist) - a.h - b.h;
                    if (hasta <= desde || libre <= 0) return;

                    const lado = String(Math.sign(dist));
                    if (!masCercana[lado] || libre < masCercana[lado].libre) {
                        masCercana[lado] = { j, libre, t: (desde + hasta) / 2, s: Math.sign(dist) };
                    }
                });

                for (const c of Object.values(masCercana)) {
                    if (!c) continue;
                    const clave = [i, c.j].sort().join('-');
                    if (hechas.has(clave)) continue;
                    hechas.add(clave);
                    // de la cara de `a` a la cara de la otra, perpendicular, a mitad del tramo enfrentado
                    const base = { x: a.v.a.x + a.u.x * c.t, y: a.v.a.y + a.u.y * c.t };
                    const p1 = { x: base.x + a.n.x * c.s * a.h, y: base.y + a.n.y * c.s * a.h };
                    const p2 = { x: p1.x + a.n.x * c.s * c.libre, y: p1.y + a.n.y * c.s * c.libre };
                    dibujarCota(p1, p2, formatoCm(c.libre), 'sep-viga');
                }
            });
        }

        function actualizarCotas() {
            zoomCotas = camara.zoom;
            capaCotas.replaceChildren();
            if (chkCotasPilares.checked && esVisible('pilar')) cotasDePilares();
            if (chkCotasVigas.checked && esVisible('viga')) cotasDeVigas();
            subirEtiquetas(capaCotas);
        }

        // Las etiquetas van al final de la capa, para que ninguna línea de cota quede por encima de un texto
        function subirEtiquetas(capa) {
            for (const etiqueta of capa.querySelectorAll('.etiqueta')) capa.appendChild(etiqueta);
        }

        // ── Identificación de elementos ──
        // Se numeran por posición, no por orden de inserción: por filas de arriba hacia abajo y,
        // dentro de cada fila, de izquierda a derecha. P1, P2... / V1, V2... / L1, L2...
        const capaIdent       = document.getElementById('identificacion');
        const btnIdent        = document.getElementById('btnIdent');
        const panelIdent      = document.getElementById('panelIdent');
        const chkIdentPilares = document.getElementById('chkIdentPilares');
        const chkIdentVigas   = document.getElementById('chkIdentVigas');
        const chkIdentLosas   = document.getElementById('chkIdentLosas');
        const TOLERANCIA_FILA = 10 * PX_POR_CM; // elementos cuyo punto de referencia difiere menos que esto en Y van en la misma fila

        function abrirPanelIdent(abrir) {
            panelIdent.classList.toggle('abierto', abrir);
            btnIdent.setAttribute('aria-expanded', abrir);
        }

        btnIdent.addEventListener('click', () => {
            abrirPanelIdent(!panelIdent.classList.contains('abierto'));
        });

        for (const chk of [chkIdentPilares, chkIdentVigas, chkIdentLosas]) {
            chk.addEventListener('change', () => {
                btnIdent.classList.toggle('activo', chkIdentPilares.checked || chkIdentVigas.checked || chkIdentLosas.checked);
                actualizarIdentificacion();
            });
        }

        // Punto donde va la etiqueta y por el que se ordena
        function puntoReferencia(obj) {
            if (obj.tipo === 'pilar') return { x: obj.cx, y: obj.cy };
            if (obj.tipo === 'viga')  return { x: (obj.a.x + obj.b.x) / 2, y: (obj.a.y + obj.b.y) / 2 };
            return puntoInteriorLosa(obj);
        }

        // Centroide del contorno exterior; si cae afuera (losa en L, o en un hueco), el centro del
        // tramo interior más ancho sobre la horizontal que pasa por el centroide
        function puntoInteriorLosa(losa) {
            const exterior = losa.contornos.reduce((a, b) => (Math.abs(areaPoligono(b)) > Math.abs(areaPoligono(a)) ? b : a));
            const c = centroidePoligono(exterior);
            if (losaEn(c) === losa) return c;

            const cortes = [];
            for (const contorno of losa.contornos) {
                for (let i = 0; i < contorno.length; i++) {
                    const a = contorno[i], b = contorno[(i + 1) % contorno.length];
                    if ((a.y > c.y) !== (b.y > c.y)) cortes.push(a.x + (c.y - a.y) * (b.x - a.x) / (b.y - a.y));
                }
            }
            cortes.sort((a, b) => a - b);
            let mejor = null;
            for (let i = 0; i + 1 < cortes.length; i += 2) {
                if (!mejor || cortes[i + 1] - cortes[i] > mejor[1] - mejor[0]) mejor = [cortes[i], cortes[i + 1]];
            }
            return mejor ? { x: (mejor[0] + mejor[1]) / 2, y: c.y } : c;
        }

        function areaPoligono(pts) {
            let a = 0;
            for (let i = 0; i < pts.length; i++) {
                const p = pts[i], q = pts[(i + 1) % pts.length];
                a += p.x * q.y - q.x * p.y;
            }
            return a / 2;
        }

        function centroidePoligono(pts) {
            const a = areaPoligono(pts);
            let cx = 0, cy = 0;
            for (let i = 0; i < pts.length; i++) {
                const p = pts[i], q = pts[(i + 1) % pts.length];
                const f = p.x * q.y - q.x * p.y;
                cx += (p.x + q.x) * f;
                cy += (p.y + q.y) * f;
            }
            return { x: cx / (6 * a), y: cy / (6 * a) };
        }

        // Ordena por filas (arriba → abajo) y dentro de la fila (izquierda → derecha)
        function ordenarPorPosicion(lista) {
            const items = lista.map(obj => ({ obj, p: puntoReferencia(obj) })).sort((a, b) => a.p.y - b.p.y);
            const filas = [];
            for (const it of items) {
                const fila = filas[filas.length - 1];
                if (fila && it.p.y - fila[0].p.y <= TOLERANCIA_FILA) fila.push(it);
                else filas.push([it]);
            }
            return filas.flatMap(f => f.sort((a, b) => a.p.x - b.p.x));
        }

        function actualizarIdentificacion() {
            capaIdent.replaceChildren();
            const capas = [
                [chkIdentLosas,   losas,   'L', 'losa'],
                [chkIdentVigas,   vigas,   'V', 'viga'],
                [chkIdentPilares, pilares, 'P', 'pilar'],
            ];
            for (const [chk, lista, prefijo, clase] of capas) {
                if (!chk.checked || !esVisible(clase)) continue;
                const items = ordenarPorPosicion(lista);
                items.forEach((it, i) => {
                    const g = crearSvg('g', { class: `ident ${clase}` });
                    capaIdent.appendChild(g);
                    agregarEtiqueta(g, it.p.x, it.p.y, `${prefijo}${i + 1}`);
                });
            }
        }

        // ── Dimensiones de los elementos ──
        // Pilar: 30x30 / Ø30 debajo del pilar · Viga: ancho x alto del lado de abajo (el largo de las cotas va arriba)
        // · Losa: espesor dentro del paño (debajo de la identificación si está prendida)
        const capaDim       = document.getElementById('dimensiones');
        const btnDim        = document.getElementById('btnDim');
        const panelDim      = document.getElementById('panelDim');
        const chkDimPilares = document.getElementById('chkDimPilares');
        const chkDimVigas   = document.getElementById('chkDimVigas');
        const chkDimLosas   = document.getElementById('chkDimLosas');

        function abrirPanelDim(abrir) {
            panelDim.classList.toggle('abierto', abrir);
            btnDim.setAttribute('aria-expanded', abrir);
        }

        btnDim.addEventListener('click', () => {
            abrirPanelDim(!panelDim.classList.contains('abierto'));
        });

        for (const chk of [chkDimPilares, chkDimVigas, chkDimLosas]) {
            chk.addEventListener('change', () => {
                btnDim.classList.toggle('activo', chkDimPilares.checked || chkDimVigas.checked || chkDimLosas.checked);
                actualizarDimensiones();
            });
        }

        function etiquetaDimension(clase, x, y, texto) {
            const g = crearSvg('g', { class: `dim ${clase}` });
            capaDim.appendChild(g);
            agregarEtiqueta(g, x, y, texto);
        }

        function actualizarDimensiones() {
            capaDim.replaceChildren();
            const z = camara.zoom;

            if (chkDimPilares.checked && esVisible('pilar')) {
                for (const p of pilares) {
                    const f = figuraDe(p.datos, p.cx, p.cy);
                    const texto = p.datos.forma === 'circular' ? `Ø${p.datos.diametro}` : `${p.datos.x}x${p.datos.y}`;
                    etiquetaDimension('pilar', p.cx, p.cy + (f.circular ? f.r : f.hh) + 12 / z, texto);
                }
            }

            if (chkDimVigas.checked && esVisible('viga')) {
                for (const v of vigas) {
                    const largo = Math.hypot(v.b.x - v.a.x, v.b.y - v.a.y) || 1;
                    // lado opuesto al que usa la cota de largo (ver cotasDeVigas), así no se pisan
                    let nx = -(v.b.y - v.a.y) / largo, ny = (v.b.x - v.a.x) / largo;
                    if (ny > 0) { nx = -nx; ny = -ny; }
                    nx = -nx; ny = -ny;
                    const sep = v.datos.ancho * PX_POR_CM / 2 + 14 / z;
                    etiquetaDimension('viga', (v.a.x + v.b.x) / 2 + nx * sep, (v.a.y + v.b.y) / 2 + ny * sep,
                        `${v.datos.ancho}x${v.datos.alto}`);
                }
            }

            if (chkDimLosas.checked && esVisible('losa')) {
                const bajar = chkIdentLosas.checked ? 22 / z : 0;
                for (const l of losas) {
                    const c = puntoInteriorLosa(l);
                    etiquetaDimension('losa', c.x, c.y + bajar, `e=${l.datos.espesor}`);
                }
            }
        }

        // ── Elementos visibles ──
        // Ocultar un tipo lo saca de la vista, de la selección y de cotas, identificación y dimensiones.
        // Sigue existiendo: cierra losas y sirve para snap.
        const btnElem   = document.getElementById('btnElem');
        const panelElem = document.getElementById('panelElem');
        const chkVer    = {
            pilar: document.getElementById('chkVerPilares'),
            viga:  document.getElementById('chkVerVigas'),
            losa:  document.getElementById('chkVerLosas'),
            hueco: document.getElementById('chkVerHuecos'),
            voladizo: document.getElementById('chkVerVoladizos'),
        };

        function esVisible(tipo) {
            return chkVer[tipo].checked;
        }

        function abrirPanelElem(abrir) {
            panelElem.classList.toggle('abierto', abrir);
            btnElem.setAttribute('aria-expanded', abrir);
        }

        btnElem.addEventListener('click', () => {
            abrirPanelElem(!panelElem.classList.contains('abierto'));
        });

        function aplicarVisibilidad() {
            for (const [tipo, chk] of Object.entries(chkVer)) {
                document.body.classList.toggle(`oculto-${tipo}`, !chk.checked);
                if (!chk.checked) {
                    for (const obj of [...seleccion]) if (obj.tipo === tipo) marcar(obj, false);
                }
            }
            // resaltado mientras haya algo oculto, para que no pase desapercibido
            btnElem.classList.toggle('activo', Object.values(chkVer).some(chk => !chk.checked));
            if (seleccionando) actualizarEstadoSeleccion();
            actualizarCotas();
            actualizarIdentificacion();
            actualizarDimensiones();
        }

        function mostrarTipo(tipo) {
            if (esVisible(tipo)) return;
            chkVer[tipo].checked = true;
            aplicarVisibilidad();
        }

        for (const chk of Object.values(chkVer)) chk.addEventListener('change', aplicarVisibilidad);

        document.getElementById('btnMover').addEventListener('click', empezarMover);
        document.getElementById('btnEliminar').addEventListener('click', eliminarSeleccion);

        // Supr / Delete elimina lo seleccionado
        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Delete' || !seleccionando || mover || !seleccion.size) return;
            if (e.target.closest('input')) return;
            eliminarSeleccion();
        });

        pizarra.addEventListener('mousemove', actualizarFantasma);
        pizarra.addEventListener('mouseleave', () => {
            if (!distancia) quitarFantasma(); // con la caja abierta, el mouse puede ir hasta ella
        });

        pizarra.addEventListener('click', (e) => {
            // Si el menú estaba abierto, el click solo lo cierra
            if (insertar.classList.contains('abierto')) return;
            if (mover) {
                if (distancia) { cerrarDistancia(); actualizarMover(e); }
                else clickMover(e);
                return;
            }
            if (!aColocar) return;
            // Con la caja de distancia abierta, el click solo la cierra
            if (distancia) { cerrarDistancia(); actualizarFantasma(e); return; }
            if (esTramo(aColocar.tipo)) return clickViga(e);
            if (aColocar.tipo === 'losa') return clickLosa(e);
            if (aColocar.tipo === 'hueco') return clickHueco(e);
            colocarPilar(posicionConSnap(aColocar, aMundo(e)));
            actualizarFantasma(e); // la cota pasa a medirse también contra el pilar recién puesto
        });

        document.getElementById('btnCancelar').addEventListener('click', () => {
            dejarDeColocar();
            terminarSeleccion();
        });

        // Cerrar al hacer click fuera o con Escape.
        // Corre después del click de la pizarra (burbujeo), así ese click ve el menú todavía abierto.
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#insertar')) abrirMenu(false);
            if (!e.target.closest('#cotasCtl')) abrirPanelCotas(false);
            if (!e.target.closest('#identCtl')) abrirPanelIdent(false);
            if (!e.target.closest('#dimCtl')) abrirPanelDim(false);
            if (!e.target.closest('#elemCtl')) abrirPanelElem(false);
        });
        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            if (panelCotas.classList.contains('abierto')) abrirPanelCotas(false);
            else if (panelIdent.classList.contains('abierto')) abrirPanelIdent(false);
            else if (panelDim.classList.contains('abierto')) abrirPanelDim(false);
            else if (panelElem.classList.contains('abierto')) abrirPanelElem(false);
            else if (insertar.classList.contains('abierto')) abrirMenu(false);
            else if (mover) cancelarMover();
            else if (vigaInicio) cortarViga(); // primer Esc cancela la viga en curso, el segundo deja de insertar
            else if (huecoPuntos.length) cancelarHueco(); // igual con el hueco
            else if (seleccionando) {
                // primer Esc limpia la selección, el segundo sale del modo
                if (seleccion.size) limpiarSeleccion();
                else terminarSeleccion();
            }
            else dejarDeColocar();
        });

        // Retroceso: borra el último vértice del hueco en curso
        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Backspace' || !huecoPuntos.length || distancia || e.target.closest('input')) return;
            e.preventDefault();
            borrarUltimoPuntoHueco();
        });

        cargarModelo();
    </script>
</body>
</html>
