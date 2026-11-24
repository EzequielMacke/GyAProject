<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salidas - {{ $fechaTexto }}</title>
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
            --yellow:   #b7791f;
            --yellow-s: #fdf4e3;
            --green:    #1e9166;
            --green-s:  #e5f6f0;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── Header ── */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex; align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .ph-crumb {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.72rem; font-weight: 500; color: var(--muted);
            margin-bottom: 0.5rem;
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
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface); color: var(--text2);
            text-decoration: none; cursor: pointer;
            transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        /* ── Layout ── */
        .board {
            display: grid;
            grid-template-columns: 1fr 280px;
            grid-template-rows: auto 1fr auto;
            grid-template-areas:
                "top    side"
                "main   side"
                "bottom side";
            gap: 1rem;
            min-height: 600px;
        }
        .area-top    { grid-area: top; }
        .area-main   { grid-area: main; }
        .area-side   { grid-area: side; }
        .area-bottom { grid-area: bottom; }

        @media (max-width: 900px) {
            .board {
                grid-template-columns: 1fr;
                grid-template-areas: "top" "main" "side" "bottom";
            }
        }

        .panel {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            padding: 1rem 1.1rem;
            display: flex; flex-direction: column; gap: 0.8rem;
            min-width: 0;
        }
        .panel-head { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; }
        .panel-title {
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.78rem; font-weight: 700; color: var(--text2);
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        .panel-title .pi {
            width: 26px; height: 26px; border-radius: 0.45rem;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 0.72rem;
        }
        .pi-blue   { background: var(--blue-s);   color: var(--accent); }
        .pi-yellow { background: var(--yellow-s); color: var(--yellow); }
        .pi-green  { background: var(--green-s);  color: var(--green); }
        .panel-count { font-size: 0.72rem; font-weight: 600; color: var(--muted); }

        /* ── Input para agregar ── */
        .add-form { display: flex; gap: 0.4rem; }
        .area-top .add-form, .area-bottom .add-form { max-width: 380px; }
        .add-input {
            flex: 1; min-width: 0; height: 36px;
            font-size: 0.83rem; color: var(--text);
            background: #fff; border: 1.5px solid var(--border);
            border-radius: 0.5rem; padding: 0 0.75rem; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .add-input::placeholder { color: var(--muted); }
        .add-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .add-btn {
            width: 36px; height: 36px; flex-shrink: 0;
            border-radius: 0.5rem; border: none;
            background: var(--accent); color: #fff; cursor: pointer;
            transition: background 0.14s;
        }
        .add-btn:hover { background: var(--accent-b); }

        /* ── Chips ── */
        .chips { display: flex; flex-wrap: wrap; gap: 0.4rem; }
        .chips-col { display: flex; flex-direction: column; gap: 0.4rem; overflow-y: auto; }
        .chip {
            display: inline-flex; align-items: center; gap: 0.45rem;
            padding: 0.4rem 0.55rem 0.4rem 0.7rem;
            background: #fff; border: 1.5px solid var(--border);
            border-radius: 0.5rem;
            font-size: 0.8rem; font-weight: 600; color: var(--text);
            user-select: none;
            transition: border-color 0.12s, background 0.12s, opacity 0.12s;
        }
        .chip.clickable { cursor: pointer; }
        .chip.clickable:hover { border-color: var(--border2); }
        .chip.dragging { opacity: 0.45; }
        .chip .chip-icon { font-size: 0.7rem; color: var(--muted); }
        .chip-salida .chip-icon { color: var(--accent); }
        .chip .chip-text { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .chip .chip-badge {
            font-size: 0.65rem; font-weight: 700;
            background: var(--surface2); color: var(--muted);
            border-radius: 0.35rem; padding: 0.1rem 0.35rem;
        }
        .chip-btn {
            width: 22px; height: 22px; border-radius: 0.35rem;
            border: none; background: transparent; color: var(--muted);
            font-size: 0.68rem; cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .chip-btn:hover { background: var(--surface2); color: var(--text); }
        .chip-btn.del:hover { background: #fff0f0; color: #c0392b; }

        .empty-mini { font-size: 0.78rem; color: var(--muted); padding: 0.4rem 0; }

        /* ── Tablero central ── */
        .area-main { transition: border-color 0.12s, background 0.12s; }
        .area-side, .area-bottom { transition: border-color 0.12s, background 0.12s; }
        .area-side.drop-over, .area-bottom.drop-over,
        .area-main.drop-over { border-color: var(--accent); border-style: dashed; background: var(--accent-s); }

        .tablero {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 0.9rem;
            align-content: start;
        }

        .tablero-empty {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center; text-align: center;
            border: 1.5px dashed var(--border2); border-radius: 0.7rem;
            padding: 2.5rem 1rem;
        }
        .empty-icon {
            width: 52px; height: 52px; border-radius: 0.75rem;
            background: var(--blue-s); color: var(--accent);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin-bottom: 1rem;
        }
        .empty-title { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 0.35rem; }
        .empty-sub { font-size: 0.82rem; color: var(--muted); }

        /* Tarjeta de salida en el tablero */
        .salida-card {
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 0.75rem;
            display: flex; flex-direction: column;
            overflow: hidden;
            transition: border-color 0.12s, box-shadow 0.12s;
        }
        .salida-card.drop-over { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.15); }
        .salida-card-head {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.7rem 0.8rem;
            background: var(--accent-s);
            border-bottom: 1.5px solid var(--border);
        }
        .salida-card-head .sc-icon {
            width: 28px; height: 28px; border-radius: 0.45rem;
            background: var(--accent); color: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 0.75rem; flex-shrink: 0;
        }
        .salida-card-head .sc-name {
            flex: 1; font-size: 0.9rem; font-weight: 700; color: var(--text);
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .salida-card-body { padding: 0.75rem 0.8rem; display: flex; flex-direction: column; gap: 0.7rem; }
        .sc-section-title {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.68rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.05em;
            margin-bottom: 0.4rem;
        }
        .sc-section-title i { font-size: 0.65rem; }
        .sc-hint {
            font-size: 0.74rem; color: var(--muted);
            border: 1.5px dashed var(--border); border-radius: 0.45rem;
            padding: 0.45rem 0.6rem; text-align: center;
        }
        .chip-veh { background: var(--yellow-s); border-color: #f0dcb4; }
        .chip-veh .chip-icon { color: var(--yellow); }
        .chip-per { background: var(--green-s); border-color: #b6e8d6; }
        .chip-per .chip-icon { color: var(--green); }
        .chip.clickable { cursor: pointer; }

        /* Botón "Ocultos" junto al + */
        .ocultos-btn {
            height: 36px; padding: 0 0.7rem; flex-shrink: 0;
            border-radius: 0.5rem; border: 1.5px solid var(--border);
            background: var(--surface); color: var(--muted);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.75rem; font-weight: 600;
            display: inline-flex; align-items: center; gap: 0.35rem;
            cursor: pointer; transition: all 0.14s; white-space: nowrap;
        }
        .ocultos-btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text2); }
        .ocultos-btn .ocultos-count {
            font-size: 0.65rem; font-weight: 700;
            background: var(--surface2); color: var(--muted);
            border-radius: 0.35rem; padding: 0.05rem 0.35rem;
        }
        .ocultos-btn.has { color: var(--text2); }
        .ocultos-btn.has .ocultos-count { background: var(--yellow-s); color: var(--yellow); }
        .area-top .add-form, .area-bottom .add-form { max-width: 480px; }

        /* Modal de opciones / ocultos */
        .modal-icon.modal-icon-blue { background: var(--blue-s); color: var(--accent); }
        .op-opciones { display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1.2rem; }
        .op-opcion {
            display: flex; align-items: center; gap: 0.75rem; text-align: left;
            padding: 0.7rem 0.8rem; border-radius: 0.6rem;
            border: 1.5px solid var(--border); background: var(--surface);
            font-family: 'Plus Jakarta Sans', sans-serif; cursor: pointer;
            transition: all 0.14s;
        }
        .op-opcion:hover { border-color: var(--accent); background: var(--accent-s); }
        .op-opcion strong { display: block; font-size: 0.85rem; color: var(--text); }
        .op-opcion small { display: block; font-size: 0.74rem; color: var(--muted); }
        .op-ic {
            width: 32px; height: 32px; border-radius: 0.5rem; flex-shrink: 0;
            display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;
        }
        .op-input { width: 100%; margin-bottom: 1.2rem; }

        /* Personal agrupado por vehículo (opcional) */
        .sc-grupos { display: flex; flex-direction: column; gap: 0.55rem; }
        .sc-grupo { display: flex; flex-direction: column; gap: 0.3rem; }
        .sc-grupo-tit {
            display: flex; align-items: center; gap: 0.35rem;
            font-size: 0.72rem; font-weight: 700; color: var(--yellow);
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .sc-grupo-tit i { font-size: 0.65rem; }
        .sc-grupo-sin .sc-grupo-tit { color: var(--muted); }
        .chip .chip-badge.chip-cond { background: var(--yellow-s); color: var(--yellow); border: 1px solid #f0dcb4; }

        /* Modal de una persona del tablero: vehículo / conductor / sacar */
        .modal-icon.modal-icon-green { background: var(--green-s); color: var(--green); }
        .pe-label { font-size: 0.7rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.45rem; }
        .pe-hint { font-size: 0.78rem; color: var(--muted); border: 1.5px dashed var(--border); border-radius: 0.5rem; padding: 0.6rem 0.7rem; margin-bottom: 1.2rem; }
        .pe-lista { max-height: 50vh; overflow-y: auto; }
        .pe-fila { display: flex; gap: 0.4rem; }
        .pe-fila .op-opcion { flex: 1; min-width: 0; padding: 0.55rem 0.7rem; }
        .pe-fila .op-opcion > span:last-of-type { flex: 1; min-width: 0; }
        .pe-fila .op-opcion strong { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .op-opcion.activo { border-color: var(--accent); background: var(--accent-s); }
        .pe-check { color: var(--accent); font-size: 0.8rem; }
        .pe-cond {
            flex-shrink: 0; padding: 0 0.7rem; border-radius: 0.6rem;
            border: 1.5px solid var(--border); background: var(--surface); color: var(--text2);
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.74rem; font-weight: 600;
            display: inline-flex; align-items: center; gap: 0.35rem; cursor: pointer; transition: all 0.14s;
        }
        .pe-cond:hover { border-color: var(--yellow); color: var(--yellow); background: var(--yellow-s); }
        .pe-cond.activo { background: var(--yellow); border-color: var(--yellow); color: #fff; }
        .modal-actions .pe-sacar { margin-right: auto; }
        .btn-save { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: none; background: var(--accent); color: #fff; cursor: pointer; transition: background 0.14s; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-save:hover { background: var(--accent-b); }
        .oc-lista { margin-bottom: 1.2rem; max-height: 300px; overflow-y: auto; }
        .oc-lista .chip:hover { border-color: var(--accent); background: var(--accent-s); }

        /* Modal buscador de salidas */
        .bus-wrap { position: relative; margin-bottom: 0.75rem; }
        .bus-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 0.75rem; pointer-events: none; }
        .bus-input { width: 100%; padding-left: 2.1rem; height: 40px; }
        .bus-lista {
            display: flex; flex-direction: column; gap: 0.35rem;
            max-height: 300px; overflow-y: auto; margin-bottom: 1.2rem;
        }
        .bus-item {
            display: flex; align-items: center; gap: 0.6rem; width: 100%; text-align: left;
            padding: 0.6rem 0.75rem; border-radius: 0.55rem;
            border: 1.5px solid var(--border); background: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem; font-weight: 600; color: var(--text);
            cursor: pointer; transition: all 0.12s;
        }
        .bus-item i { color: var(--accent); font-size: 0.75rem; }
        .bus-item > span { flex: 1; }
        .bus-check {
            width: 20px; height: 20px; border-radius: 0.35rem; flex-shrink: 0;
            border: 1.5px solid var(--border2); background: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.12s;
        }
        .bus-check i { color: #fff !important; font-size: 0.65rem !important; opacity: 0; }
        .bus-item.marcado { border-color: var(--accent); background: var(--accent-s); }
        .bus-item.marcado .bus-check { background: var(--accent); border-color: var(--accent); }
        .bus-item.marcado .bus-check i { opacity: 1; }
        .btn-save:disabled { opacity: 0.5; cursor: not-allowed; }
        .bus-acciones { display: flex; justify-content: space-between; align-items: center; margin: -0.35rem 0 0.5rem; font-size: 0.75rem; }
        .bus-acciones button { border: none; background: none; color: var(--accent); font-weight: 600; cursor: pointer; padding: 0.15rem 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.75rem; }
        .bus-acciones span { color: var(--muted); }
        .bus-item:hover, .bus-item.activo { border-color: var(--accent); background: var(--accent-s); }
        .bus-item mark { background: #fff1b8; color: inherit; padding: 0 1px; border-radius: 2px; }
        .bus-vacio { font-size: 0.8rem; color: var(--muted); padding: 0.75rem 0.25rem; text-align: center; }

        /* El espacio libre del tablero es clicable */
        #zona-tablero { cursor: pointer; }
        #zona-tablero .salida-card, #zona-tablero .panel-head { cursor: default; }
        #zona-tablero .salida-card-head, #zona-tablero .chip.clickable, #zona-tablero .btn-copiar { cursor: pointer; }

        /* Secciones Vehículos / Personal de la tarjeta: clic abre el buscador */
        #zona-tablero .sc-seccion {
            cursor: pointer; border-radius: 0.5rem;
            margin: -0.3rem; padding: 0.3rem;
            transition: background 0.12s;
        }
        #zona-tablero .sc-seccion:hover { background: var(--surface2); }
        .sc-section-title .sc-add {
            margin-left: auto; width: 18px; height: 18px; border-radius: 0.3rem;
            display: inline-flex; align-items: center; justify-content: center;
            color: var(--muted); font-size: 0.6rem; transition: all 0.12s;
        }
        .sc-seccion:hover .sc-add { background: var(--accent); color: #fff; }
        .sc-seccion:hover .sc-hint { border-color: var(--accent); color: var(--accent); }
        [hidden] { display: none !important; }

        /* Selección múltiple en las barras */
        .chip.selected { background: var(--accent-s); border-color: var(--accent); color: var(--accent); }
        .chip.selected .chip-icon { color: var(--accent); }
        .drag-ghost {
            position: absolute; top: -1000px; left: -1000px;
            padding: 0.4rem 0.75rem; border-radius: 0.5rem;
            background: var(--accent); color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem; font-weight: 700;
        }
        .chip-veh.clickable:hover { border-color: var(--yellow); }
        .chip-per.clickable:hover { border-color: var(--green); }
        .salida-card-head { cursor: pointer; transition: background 0.12s; }
        .salida-card-head:hover { background: #dce8fb; }

        /* ── Modal ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.45); z-index: 9999; align-items: center; justify-content: center; }
        .modal-overlay.active { display: flex; }
        .modal-box { background: #fff; border-radius: 0.95rem; padding: 1.75rem 1.75rem 1.5rem; max-width: 400px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.18); font-family: 'Plus Jakarta Sans', sans-serif; }
        .modal-icon { width: 44px; height: 44px; border-radius: 0.65rem; background: #fff0f0; color: #c0392b; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 1rem; }
        .modal-title { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.35rem; }
        .modal-sub { font-size: 0.82rem; color: var(--muted); margin-bottom: 1.35rem; }
        .modal-actions { display: flex; gap: 0.5rem; justify-content: flex-end; }
        .btn-cancel { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); cursor: pointer; transition: all 0.14s; }
        .btn-cancel:hover { background: var(--surface2); }
        .btn-confirm { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: none; background: #c0392b; color: #fff; cursor: pointer; transition: background 0.14s; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-confirm:hover { background: #a93226; }
        .modal-icon.modal-icon-amber { background: #fff8e6; color: #d99a0b; }
        .modal-actions .btn-guardar-salir {
            height: 36px; padding: 0 1rem; border-radius: 0.5rem; border: none;
            background: var(--accent); color: #fff; cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600;
            display: inline-flex; align-items: center; gap: 0.4rem; transition: background 0.14s;
        }
        .modal-actions .btn-guardar-salir:hover { background: var(--accent-b); }
        .modal-actions .btn-guardar-salir:disabled { opacity: 0.7; cursor: wait; }
        #modal-salir .modal-box { max-width: 460px; }
        #modal-salir .modal-actions { flex-wrap: wrap; }
        #modal-salir .btn-confirm { margin-right: auto; }

        /* ══════════════════════════════════════
           APROVECHAMIENTO DEL ESPACIO
        ══════════════════════════════════════ */

        /* Encabezado más compacto */
        .ph { padding: 0.9rem 0 0.4rem; margin-bottom: 0.25rem; gap: 1rem; }
        .ph-crumb { margin-bottom: 0.3rem; }
        .ph-title { font-size: 1.4rem; }

        /* PC: como mínimo llena la pantalla; si hay muchas salidas, el tablero crece
           (las tarjetas se acomodan hacia el costado y luego hacia abajo) */
        .board {
            min-height: 520px; /* se ajusta por JS al alto disponible */
            grid-template-columns: 1fr 260px;
            grid-template-rows: auto 1fr auto;
            gap: 0.75rem;
        }
        .panel { padding: 0.8rem 0.9rem; gap: 0.6rem; }
        .tablero { overflow: visible; align-items: start; }
        /* Que un nombre largo nunca empuje la tarjeta fuera de la pantalla */
        .salida-card { min-width: 0; }
        .salida-card .chip { max-width: 100%; }
        /* La columna de vehículos no estira la página: toma el alto disponible y hace scroll por dentro */
        .area-side { contain: size; min-height: 0; }
        .area-side .chips-col { flex: 1; min-height: 0; overflow-y: auto; }

        /* Barras de Salidas y Personal en una sola fila: título | campo | etiquetas */
        .panel-row {
            display: grid;
            grid-template-columns: 150px minmax(260px, 400px) 1fr;
            align-items: center;
            gap: 0.6rem 1rem;
        }
        .panel-row .panel-head { flex-direction: column; align-items: flex-start; gap: 0.15rem; }
        .panel-row .add-form { max-width: none; }
        .panel-row .chips { max-height: 76px; overflow-y: auto; align-content: flex-start; }
        @media (max-width: 1280px) {
            .panel-row { grid-template-columns: 150px minmax(260px, 420px); }
            .panel-row .chips { grid-column: 1 / -1; }
        }

        /* Tarjetas un poco más compactas */
        .tablero { grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0.7rem; }
        .salida-card-head { padding: 0.55rem 0.7rem; }
        .salida-card-body { padding: 0.6rem 0.7rem; gap: 0.55rem; }

        /* Modo asignar: hay vehículos/personal seleccionados → las tarjetas son "tocables" */
        .tablero.modo-asignar .salida-card {
            cursor: pointer;
            border-style: dashed; border-color: var(--accent);
        }
        .tablero.modo-asignar .salida-card:hover { box-shadow: 0 0 0 3px rgba(42,111,219,0.15); }

        /* Barra flotante de selección */
        .sel-bar {
            position: fixed; left: 50%; bottom: 1rem; transform: translateX(-50%);
            z-index: 1050;
            display: flex; align-items: center; gap: 0.6rem;
            max-width: calc(100vw - 1.5rem);
            padding: 0.5rem 0.5rem 0.5rem 0.9rem;
            background: var(--text); color: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 10px 30px rgba(15,23,42,0.25);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem; font-weight: 600;
        }
        .sel-bar .sel-texto { line-height: 1.3; }
        .sel-bar .sel-texto small { display: block; font-weight: 500; color: #b7c3d3; font-size: 0.72rem; }
        .sel-bar .btn-save { height: 34px; flex-shrink: 0; }
        .sel-bar .sel-x {
            width: 34px; height: 34px; flex-shrink: 0; border-radius: 0.5rem;
            border: none; background: rgba(255,255,255,0.1); color: #fff; cursor: pointer;
        }
        .sel-bar .sel-x:hover { background: rgba(255,255,255,0.2); }

        .chip { -webkit-touch-callout: none; }

        /* Botón Guardar */
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; position: relative; }
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; }
        .btn-primary:disabled { opacity: 0.7; cursor: wait; }
        .btn-primary.cargando .fa-save::before { content: "\f110"; }
        .btn-primary.cargando .fa-save { animation: fa-spin 1s linear infinite; }
        .dot-sucio {
            display: none; position: absolute; top: -4px; right: -4px;
            width: 12px; height: 12px; border-radius: 50%;
            background: #f59e0b; border: 2px solid var(--bg);
        }
        .btn-primary.sucio .dot-sucio { display: block; }

        /* Navegador de fechas */
        .fecha-nav { display: inline-flex; align-items: center; gap: 0.3rem; }
        .fecha-nav .fn-btn {
            height: 34px; min-width: 34px; padding: 0 0.6rem; border-radius: 0.5rem;
            border: 1.5px solid var(--border); background: #fff; color: var(--text2);
            display: inline-flex; align-items: center; justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.78rem; font-weight: 600;
            text-decoration: none;
        }
        .fecha-nav .fn-btn:hover { border-color: var(--accent); color: var(--accent); }
        .fecha-nav .fn-input {
            height: 34px; padding: 0 0.5rem; border-radius: 0.5rem;
            border: 1.5px solid var(--border); background: #fff; color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8rem; font-weight: 600;
        }
        .fecha-nav .fn-input:focus { outline: none; border-color: var(--accent); }

        /* Aviso de salidas copiadas de otra fecha */
        .aviso-copia {
            display: flex; align-items: center; gap: 0.6rem;
            margin-bottom: 0.9rem; padding: 0.7rem 0.9rem;
            border: 1.5px solid #f6d58a; border-radius: 0.65rem;
            background: #fff8e6; color: #7a5a12;
            font-size: 0.8rem; font-weight: 500;
        }
        .aviso-copia i { color: #d99a0b; }

        /* Autor de la salida en la tarjeta */
        .salida-card-head .sc-titulo { flex: 1; min-width: 0; display: flex; flex-direction: column; }
        .salida-card-head .sc-autor { font-size: 0.68rem; font-weight: 500; color: var(--muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* Botón copiar imagen */
        .btn-copiar {
            height: 32px; padding: 0 0.75rem; border-radius: 0.5rem;
            border: 1.5px solid var(--border); background: #fff; color: var(--text2);
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.78rem; font-weight: 600;
            display: inline-flex; align-items: center; gap: 0.4rem;
            cursor: pointer; transition: all 0.14s; white-space: nowrap;
        }
        .btn-copiar:hover { border-color: #25d366; color: #128c4b; background: #effbf3; }
        .btn-copiar:disabled { opacity: 0.6; cursor: wait; }

        /* Aviso flotante */
        .toast-aviso {
            position: fixed; top: 1rem; left: 50%; transform: translateX(-50%);
            z-index: 10000; max-width: calc(100vw - 1.5rem);
            padding: 0.65rem 1rem; border-radius: 0.6rem;
            background: var(--text); color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600;
            box-shadow: 0 10px 30px rgba(15,23,42,0.25);
            display: flex; align-items: center; gap: 0.5rem;
        }
        .toast-aviso.ok i { color: #4ade80; }
        .toast-aviso.err i { color: #f87171; }

        /* ── Tablet / celular ── */
        @media (max-width: 900px) {
            .board {
                height: auto; min-height: 0;
                grid-template-columns: 1fr;
                grid-template-rows: none;
                grid-template-areas: "top" "side" "bottom" "main";
                padding-bottom: 5rem; /* espacio para la barra flotante */
            }
            .panel-row { grid-template-columns: 1fr; }
            .panel-row .panel-head { flex-direction: row; align-items: center; }

            /* Las listas pasan a ser una fila deslizable */
            .area-top .chips, .area-bottom .chips, .area-side .chips-col {
                flex-direction: row; flex-wrap: nowrap;
                overflow-x: auto; overflow-y: hidden;
                max-height: none; padding-bottom: 0.25rem;
                scrollbar-width: thin;
            }
            .area-top .chip, .area-bottom .chip, .area-side .chip { flex-shrink: 0; }
            .area-side .chips-col .empty-mini, .area-top .chips .empty-mini, .area-bottom .chips .empty-mini { white-space: nowrap; }

            .tablero { overflow: visible; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); }
            .area-main { min-height: 260px; }
            .area-side { contain: none; }
            .area-side .chips-col { flex: none; }
        }

        @media (max-width: 600px) {
            .ph { padding-top: 0.6rem; }
            .ph-sub, .ph-crumb { display: none; }
            .ph-title { font-size: 1.2rem; }
            .btn-label, .oc-label { display: none; }
            .btn { padding: 0 0.75rem; }
            .panel { padding: 0.7rem; border-radius: 0.7rem; }
            /* Celular: cada salida en su propio renglón, a todo el ancho */
            .tablero { grid-template-columns: minmax(0, 1fr); }
            .chip { padding: 0.5rem 0.6rem 0.5rem 0.75rem; } /* más fácil de tocar */
            .sel-bar { left: 0.75rem; right: 0.75rem; transform: none; bottom: 0.75rem; }
            .sel-bar .sel-texto { flex: 1; }
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
                            <a href="{{ route('salidas.index') }}#{{ substr($fecha, 0, 7) }}">Salidas</a>
                            <i class="fas fa-chevron-right"></i>
                            {{ date('d/m/Y', strtotime($fecha)) }}
                        </div>
                        <h1 class="ph-title">Salidas del <em>{{ $fechaTexto }}</em></h1>
                        <p class="ph-sub">Seleccioná vehículos y personal, y tocá una salida del tablero para asignarlos</p>
                    </div>
                    <div class="ph-right">
                        @php
                            $fechaCarbon = \Carbon\Carbon::parse($fecha);
                            $hoy = \Carbon\Carbon::today()->format('Y-m-d');
                        @endphp
                        <div class="fecha-nav">
                            <a href="{{ route('salidas.create', ['fecha' => $fechaCarbon->copy()->subDay()->format('Y-m-d')]) }}"
                               class="fn-btn" title="Día anterior"><i class="fas fa-chevron-left"></i></a>
                            <input type="date" id="fecha-nav-input" class="fn-input" value="{{ $fecha }}" title="Ir a una fecha">
                            <a href="{{ route('salidas.create', ['fecha' => $fechaCarbon->copy()->addDay()->format('Y-m-d')]) }}"
                               class="fn-btn" title="Día siguiente"><i class="fas fa-chevron-right"></i></a>
                            @if ($fecha !== $hoy)
                                <a href="{{ route('salidas.create', ['fecha' => $hoy]) }}" class="fn-btn fn-hoy" title="Ir a hoy">Hoy</a>
                            @endif
                        </div>
                        <button type="button" class="btn btn-primary" id="btn-guardar">
                            <i class="fas fa-save"></i> <span class="btn-label">Guardar</span>
                            <span class="dot-sucio"></span>
                        </button>
                        <a href="{{ route('salidas.index') }}#{{ substr($fecha, 0, 7) }}" class="btn" title="Volver al calendario">
                            <i class="fas fa-arrow-left"></i> <span class="btn-label">Volver</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @if ($copiadoDe)
                    <div class="aviso-copia">
                        <i class="fas fa-copy"></i>
                        <span>Se copiaron las salidas del <strong>{{ $copiadoDe }}</strong>. Revisá, modificá lo que haga falta y tocá <strong>Guardar</strong>. Hasta entonces no se guarda nada.</span>
                    </div>
                @endif

                <div class="board">

                    {{-- ── Barra superior: Salidas ── --}}
                    <div class="panel panel-row area-top">
                        <div class="panel-head">
                            <div class="panel-title">
                                <span class="pi pi-blue"><i class="fas fa-sign-out-alt"></i></span>
                                Salidas
                            </div>
                        </div>
                        <form class="add-form" data-tipo="salida">
                            <input type="text" class="add-input" placeholder="Nombre de la salida..." autocomplete="off">
                            <button type="submit" class="add-btn" title="Agregar salida"><i class="fas fa-plus"></i></button>
                            <button type="button" class="ocultos-btn" data-tipo="salida" title="Ver salidas ocultas">
                                <i class="fas fa-eye-slash"></i> <span class="oc-label">Ocultas</span> <span class="ocultos-count">0</span>
                            </button>
                        </form>
                        <div class="chips" id="lista-salidas"></div>
                    </div>

                    {{-- ── Centro: tablero ── --}}
                    <div class="panel area-main" id="zona-tablero">
                        <div class="panel-head">
                            <div class="panel-title">
                                <span class="pi pi-blue"><i class="fas fa-th-large"></i></span>
                                Tablero
                            </div>
                            <button type="button" class="btn-copiar" id="btn-copiar" title="Copiar el tablero como imagen">
                                <i class="fas fa-copy"></i> <span class="btn-label">Copiar imagen</span>
                            </button>
                        </div>
                        <div class="tablero" id="tablero"></div>
                    </div>

                    {{-- ── Barra derecha: Vehículos ── --}}
                    <div class="panel area-side">
                        <div class="panel-head">
                            <div class="panel-title">
                                <span class="pi pi-yellow"><i class="fas fa-truck"></i></span>
                                Vehículos
                            </div>
                        </div>
                        <form class="add-form" data-tipo="vehiculo">
                            <input type="text" class="add-input" placeholder="Vehículo..." autocomplete="off">
                            <button type="submit" class="add-btn" title="Agregar vehículo"><i class="fas fa-plus"></i></button>
                            <button type="button" class="ocultos-btn" data-tipo="vehiculo" title="Ver vehículos ocultos">
                                <i class="fas fa-eye-slash"></i> <span class="oc-label">Ocultos</span> <span class="ocultos-count">0</span>
                            </button>
                        </form>
                        <div class="chips-col" id="lista-vehiculos"></div>
                    </div>

                    {{-- ── Barra inferior: Personal ── --}}
                    <div class="panel panel-row area-bottom">
                        <div class="panel-head">
                            <div class="panel-title">
                                <span class="pi pi-green"><i class="fas fa-users"></i></span>
                                Personal
                            </div>
                        </div>
                        <form class="add-form" data-tipo="personal">
                            <input type="text" class="add-input" placeholder="Nombre del personal..." autocomplete="off">
                            <button type="submit" class="add-btn" title="Agregar personal"><i class="fas fa-plus"></i></button>
                            <button type="button" class="ocultos-btn" data-tipo="personal" title="Ver personal oculto">
                                <i class="fas fa-eye-slash"></i> <span class="oc-label">Ocultos</span> <span class="ocultos-count">0</span>
                            </button>
                        </form>
                        <div class="chips" id="lista-personal"></div>
                    </div>

                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- Aviso flotante --}}
<div class="toast-aviso" id="toast" hidden></div>

{{-- Barra flotante de selección --}}
<div class="sel-bar" id="sel-bar" hidden>
    <div class="sel-texto" id="sel-texto"></div>
    <button type="button" class="btn-save" id="sel-llevar"><i class="fas fa-th-large"></i> Al tablero</button>
    <button type="button" class="sel-x" id="sel-limpiar" title="Quitar selección"><i class="fas fa-times"></i></button>
</div>

{{-- Modal: opciones (mantener presionado) --}}
<div class="modal-overlay" id="modal-opciones">
    <div class="modal-box">
        <div class="modal-icon modal-icon-blue"><i class="fas fa-sliders-h"></i></div>
        <div class="modal-title" id="op-titulo">Opciones</div>
        <div class="modal-sub" id="op-sub"></div>

        <div id="op-menu">
            <div class="op-opciones">
                <button type="button" class="op-opcion" id="op-btn-editar">
                    <span class="op-ic pi-blue"><i class="fas fa-pencil-alt"></i></span>
                    <span><strong>Editar</strong><small>Cambiar el nombre</small></span>
                </button>
                <button type="button" class="op-opcion" id="op-btn-ocultar">
                    <span class="op-ic pi-yellow"><i class="fas fa-eye-slash"></i></span>
                    <span><strong>Ocultar</strong><small>Sacarlo de la lista sin eliminarlo</small></span>
                </button>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" data-cerrar="opciones">Cancelar</button>
            </div>
        </div>

        <form id="op-editar" hidden>
            <input type="text" class="add-input op-input" id="op-input" autocomplete="off">
            <div class="modal-actions">
                <button type="button" class="btn-cancel" data-cerrar="opciones">Cancelar</button>
                <button type="submit" class="btn-save"><i class="fas fa-check"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: buscar salida para llevar al tablero --}}
<div class="modal-overlay" id="modal-buscar">
    <div class="modal-box">
        <div class="modal-icon modal-icon-blue"><i class="fas fa-search"></i></div>
        <div class="modal-title" id="bus-titulo">Agregar salidas al tablero</div>
        <div class="modal-sub" id="bus-sub">Buscá y marcá las salidas que querés llevar al tablero.</div>
        <div class="bus-wrap">
            <i class="fas fa-search"></i>
            <input type="text" class="add-input bus-input" id="bus-input" placeholder="Buscar salida..." autocomplete="off">
        </div>
        <div class="bus-acciones">
            <button type="button" id="bus-todos">Marcar todas</button>
            <span id="bus-info"></span>
        </div>
        <div class="bus-lista" id="bus-lista"></div>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" data-cerrar="buscar">Cancelar</button>
            <button type="button" class="btn-save" id="bus-agregar" disabled>
                <i class="fas fa-plus"></i> <span id="bus-agregar-txt">Agregar al tablero</span> <span id="bus-cant"></span>
            </button>
        </div>
    </div>
</div>

{{-- Modal: ocultos --}}
<div class="modal-overlay" id="modal-ocultos">
    <div class="modal-box">
        <div class="modal-icon modal-icon-blue"><i class="fas fa-eye-slash"></i></div>
        <div class="modal-title" id="oc-titulo">Ocultos</div>
        <div class="modal-sub" id="oc-sub"></div>
        <div class="chips oc-lista" id="oc-lista"></div>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" data-cerrar="ocultos">Cerrar</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="modal-salir">
    <div class="modal-box">
        <div class="modal-icon modal-icon-amber"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="modal-title">Hay cambios sin guardar</div>
        <div class="modal-sub">Si salís ahora se pierden los cambios que hiciste en el tablero. ¿Qué querés hacer?</div>
        <div class="modal-actions">
            <button type="button" class="btn-confirm" id="salir-igual"><i class="fas fa-sign-out-alt"></i> Salir sin guardar</button>
            <button type="button" class="btn-cancel" id="salir-cancelar">Cancelar</button>
            <button type="button" class="btn-guardar-salir" id="salir-guardar"><i class="fas fa-save"></i> Guardar y salir</button>
        </div>
    </div>
</div>

{{-- Modal: persona del tablero (en qué vehículo va, si conduce, sacarla) --}}
<div class="modal-overlay" id="modal-persona">
    <div class="modal-box">
        <div class="modal-icon modal-icon-green"><i class="fas fa-user"></i></div>
        <div class="modal-title" id="pe-titulo"></div>
        <div class="modal-sub" id="pe-sub"></div>
        <div id="pe-cuerpo"></div>
        <div class="modal-actions">
            <button type="button" class="btn-confirm pe-sacar" id="pe-sacar"><i class="fas fa-sign-out-alt"></i> Sacar</button>
            <button type="button" class="btn-cancel" data-cerrar="persona">Cerrar</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="modal-quitar">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-sign-out-alt"></i></div>
        <div class="modal-title" id="modal-titulo">Sacar del tablero</div>
        <div class="modal-sub" id="modal-texto">¿Querés sacarlo del tablero?</div>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" id="modal-cancelar">Cancelar</button>
            <button type="button" class="btn-confirm" id="modal-confirmar"><i class="fas fa-check"></i> Sacar</button>
        </div>
    </div>
</div>

<script>
(function () {
    // ── Estado (viene del servidor; se guarda con el botón "Guardar") ──
    const state = {
        salidas:   [],   // { id, nombre, oculto, enTablero, vehiculos: [ids], personal: [ids], viaje, agregadoPor }
                         //   viaje (opcional) = { [idPersona]: { veh: idVehiculo, cond: bool } }, solo quienes tienen vehículo
        vehiculos: [],   // { id, nombre, oculto }
        personal:  [],   // { id, nombre, oculto }
    };
    let seq = 1; // para ids temporales de elementos nuevos ("n1", "n2", …)

    // Catálogo: ids nuevos o modificados que hay que enviar al guardar
    const cambiados = { salida: new Set(), vehiculo: new Set(), personal: new Set() };
    let sucio = false; // hay cambios sin guardar

    function cargarEstado(e) {
        state.salidas   = e.salidas.map(s => ({
            ...s,
            vehiculos: [...s.vehiculos],
            personal:  [...s.personal],
            viaje: Object.fromEntries((s.viaje || []).map(v => [String(v.personal), { veh: v.vehiculo, cond: !!v.conductor }])),
        }));
        state.vehiculos = e.vehiculos.map(v => ({ ...v }));
        state.personal  = e.personal.map(p => ({ ...p }));
        Object.values(cambiados).forEach(c => c.clear());
    }

    function marcarSucio() {
        sucio = true;
        actualizarBotonGuardar();
    }

    function marcarCambio(tipo, id) {
        cambiados[tipo].add(id);
        marcarSucio();
    }

    cargarEstado(@json($estado));

    // Elementos seleccionados en las barras (clic para seleccionar)
    const seleccion = { salida: new Set(), vehiculo: new Set(), personal: new Set() };

    function toggleSeleccion(tipo, id) {
        const set = seleccion[tipo];
        set.has(id) ? set.delete(id) : set.add(id);
        render();
    }

    function limpiarSeleccion() {
        Object.values(seleccion).forEach(s => s.clear());
        render();
    }

    // Quita de la selección lo que ya no está disponible en su barra
    function depurarSeleccion() {
        const libres = {
            salida:   new Set(state.salidas.filter(s => !s.enTablero && !s.oculto).map(s => s.id)),
            vehiculo: new Set(state.vehiculos.filter(v => !v.oculto && !estaAsignado('vehiculo', v.id)).map(v => v.id)),
            personal: new Set(state.personal.filter(p => !p.oculto && !estaAsignado('personal', p.id)).map(p => p.id)),
        };
        Object.keys(seleccion).forEach(t => {
            seleccion[t].forEach(id => { if (!libres[t].has(id)) seleccion[t].delete(id); });
        });
    }

    const LISTA = { vehiculo: 'vehiculos', personal: 'personal' };
    const $ = id => document.getElementById(id);

    function el(tag, cls, text) {
        const e = document.createElement(tag);
        if (cls) e.className = cls;
        if (text !== undefined) e.textContent = text;
        return e;
    }

    function chipBtn(icon, title, onClick, extra) {
        const b = el('button', 'chip-btn' + (extra ? ' ' + extra : ''));
        b.type = 'button';
        b.title = title;
        b.innerHTML = `<i class="fas ${icon}"></i>`;
        b.addEventListener('click', e => { e.stopPropagation(); onClick(); });
        return b;
    }

    function chip(icon, texto, extraCls) {
        const c = el('div', 'chip' + (extraCls ? ' ' + extraCls : ''));
        c.innerHTML = `<i class="fas ${icon} chip-icon"></i>`;
        c.appendChild(el('span', 'chip-text', texto));
        return c;
    }

    const getSalida = id => state.salidas.find(s => s.id === id);

    // ── Acciones ──
    function agregar(tipo, nombre) {
        const item = { id: 'n' + (seq++), nombre, oculto: false };
        if (tipo === 'salida') {
            Object.assign(item, { enTablero: false, vehiculos: [], personal: [], viaje: {}, agregadoPor: null });
            state.salidas.push(item);
        } else {
            state[LISTA[tipo]].push(item);
        }
        marcarCambio(tipo, item.id);
        render();
    }

    // Pone en el tablero una o varias salidas
    function moverAlTablero(ids) {
        ids.forEach(id => {
            const s = getSalida(id);
            if (s) s.enTablero = true;
        });
        marcarSucio();
        render();
    }

    // Asigna uno o varios a una salida; si ya estaban en otra, los mueve
    function asignar(salidaId, tipo, ids) {
        const s = getSalida(salidaId);
        if (!s) return;
        const key = LISTA[tipo];
        state.salidas.forEach(x => { if (x !== s) x[key] = x[key].filter(i => !ids.includes(i)); });
        ids.forEach(id => { if (!s[key].includes(id)) s[key].push(id); });
        marcarSucio();
        render();
    }

    // Los quita de cualquier salida y vuelven a su lista
    function liberar(tipo, ids) {
        const key = LISTA[tipo];
        state.salidas.forEach(s => s[key] = s[key].filter(i => !ids.includes(i)));
        marcarSucio();
        render();
    }

    function quitar(salidaId, tipo, id) {
        const s = getSalida(salidaId);
        if (!s) return;
        const key = LISTA[tipo];
        s[key] = s[key].filter(x => x !== id);
        marcarSucio();
        render();
    }

    // Saca la salida del tablero y libera todos sus vehículos y personal
    function sacarSalida(id) {
        const s = getSalida(id);
        if (!s) return;
        s.enTablero = false;
        s.vehiculos = [];
        s.personal  = [];
        s.viaje     = {};
        s.agregadoPor = null;
        marcarSucio();
        render();
    }

    // ── Personas en vehículos (opcional) ──
    // Las claves de s.viaje son texto: se compara siempre con k()
    const k = id => String(id);

    // Mantiene s.viaje coherente después de cualquier cambio: si la persona o el
    // vehículo ya no están en la salida, la persona queda "sin especificar";
    // y nunca hay más de un conductor por vehículo.
    function depurarViajes() {
        state.salidas.forEach(s => {
            const per = new Set(s.personal.map(k));
            const veh = new Set(s.vehiculos.map(k));
            const conConductor = new Set();
            Object.keys(s.viaje).forEach(p => {
                const v = s.viaje[p];
                if (!per.has(p) || !veh.has(k(v.veh))) { delete s.viaje[p]; return; }
                if (v.cond) {
                    if (conConductor.has(k(v.veh))) v.cond = false;
                    else conConductor.add(k(v.veh));
                }
            });
        });
    }

    // vehId null → sin especificar. cond true → pasa a ser el conductor (reemplaza al anterior)
    function ponerEnVehiculo(s, perId, vehId, cond) {
        if (vehId === null) {
            delete s.viaje[k(perId)];
        } else {
            if (cond) Object.values(s.viaje).forEach(v => { if (k(v.veh) === k(vehId)) v.cond = false; });
            s.viaje[k(perId)] = { veh: vehId, cond: !!cond };
        }
        marcarSucio();
        render();
    }

    // Personal de la salida agrupado por vehículo, o null si nadie tiene vehículo
    // → [{ veh: vehículo | null (sin especificar), ids: [idsPersona] }], el conductor primero
    function gruposPersonal(s) {
        if (!Object.keys(s.viaje).length) return null;
        const grupos = s.vehiculos
            .map(id => state.vehiculos.find(v => v.id === id))
            .filter(Boolean)
            .map(veh => ({ veh, ids: [] }));
        const sin = { veh: null, ids: [] };
        s.personal.forEach(id => {
            const v = s.viaje[k(id)];
            const g = v && grupos.find(g => k(g.veh.id) === k(v.veh));
            (g || sin).ids.push(id);
        });
        const esCond = id => s.viaje[k(id)]?.cond ? 1 : 0;
        grupos.forEach(g => g.ids.sort((a, b) => esCond(b) - esCond(a)));
        return [...grupos, sin].filter(g => g.ids.length);
    }

    // ── Modal de confirmación ──
    let accionPendiente = null;

    function abrirModal(titulo, texto, onOk) {
        $('modal-titulo').textContent = titulo;
        $('modal-texto').textContent  = texto;
        accionPendiente = onOk;
        $('modal-quitar').classList.add('active');
    }

    function cerrarModal() {
        accionPendiente = null;
        $('modal-quitar').classList.remove('active');
    }

    $('modal-cancelar').addEventListener('click', cerrarModal);
    $('modal-confirmar').addEventListener('click', () => {
        const fn = accionPendiente;
        cerrarModal();
        if (fn) fn();
    });
    $('modal-quitar').addEventListener('click', e => { if (e.target === e.currentTarget) cerrarModal(); });
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        if ($('modal-salir').classList.contains('active')) cerrarSalir();
        else if ($('modal-quitar').classList.contains('active')) cerrarModal();
        else if ($('modal-persona').classList.contains('active')) cerrarPersona();
        else if ($('modal-opciones').classList.contains('active')) cerrarOpciones();
        else if ($('modal-ocultos').classList.contains('active')) cerrarOcultos();
        else if ($('modal-buscar').classList.contains('active')) cerrarBuscar();
        else limpiarSeleccion();
    });

    function confirmarSacarSalida(s) {
        const n = s.vehiculos.length + s.personal.length;
        abrirModal(
            'Sacar salida del tablero',
            `¿Querés sacar "${s.nombre}" del tablero?` +
                (n ? ` Los vehículos y el personal asignados (${n}) vuelven a sus listas.` : ''),
            () => sacarSalida(s.id)
        );
    }

    function confirmarQuitarRecurso(s, tipo, item) {
        const enEl = tipo === 'vehiculo'
            ? Object.values(s.viaje).filter(v => k(v.veh) === k(item.id)).length : 0;
        abrirModal(
            tipo === 'vehiculo' ? 'Sacar vehículo del tablero' : 'Sacar personal del tablero',
            `¿Querés sacar "${item.nombre}" de la salida "${s.nombre}"? Vuelve a su lista.` +
                (enEl ? ` ${enEl === 1 ? 'La persona que iba en él queda' : `Las ${enEl} personas que iban en él quedan`} sin vehículo.` : ''),
            () => quitar(s.id, tipo, item.id)
        );
    }

    // ── Modal de una persona del tablero ──
    let personaCtx = null; // { salidaId, perId }

    function abrirPersona(s, item) {
        personaCtx = { salidaId: s.id, perId: item.id };
        renderPersona();
        $('modal-persona').classList.add('active');
    }

    function cerrarPersona() {
        personaCtx = null;
        $('modal-persona').classList.remove('active');
    }

    function renderPersona() {
        const s = personaCtx && getSalida(personaCtx.salidaId);
        const item = personaCtx && state.personal.find(p => p.id === personaCtx.perId);
        if (!s || !item) return cerrarPersona();

        const actual = s.viaje[k(item.id)] || null;
        $('pe-titulo').textContent = item.nombre;
        $('pe-sub').textContent = `En la salida "${s.nombre}"`;

        const cuerpo = $('pe-cuerpo');
        cuerpo.innerHTML = '';
        const vehiculos = s.vehiculos.map(id => state.vehiculos.find(v => v.id === id)).filter(Boolean);

        if (!vehiculos.length) {
            cuerpo.appendChild(el('div', 'pe-hint',
                'Si agregás vehículos a esta salida, acá vas a poder elegir en cuál va y quién lo conduce.'));
            return;
        }

        cuerpo.appendChild(el('div', 'pe-label', '¿En qué vehículo va?'));
        const lista = el('div', 'op-opciones pe-lista');

        const opcion = (icono, clsIcono, titulo, detalle, activo, onClick) => {
            const b = el('button', 'op-opcion' + (activo ? ' activo' : ''));
            b.type = 'button';
            b.innerHTML = `<span class="op-ic ${clsIcono}"><i class="fas ${icono}"></i></span>`;
            const txt = el('span');
            txt.appendChild(el('strong', '', titulo));
            if (detalle) txt.appendChild(el('small', '', detalle));
            b.appendChild(txt);
            if (activo) b.insertAdjacentHTML('beforeend', '<i class="fas fa-check pe-check"></i>');
            b.addEventListener('click', onClick);
            return b;
        };

        const fila = el('div', 'pe-fila');
        fila.appendChild(opcion('fa-question', 'pi-blue', 'Sin especificar', 'No importa en qué vehículo va', !actual, () => {
            ponerEnVehiculo(s, item.id, null);
            cerrarPersona();
        }));
        lista.appendChild(fila);

        vehiculos.forEach(veh => {
            const enEste = actual && k(actual.veh) === k(veh.id);
            const conduce = enEste && actual.cond;
            const otroCond = Object.entries(s.viaje).find(([p, v]) => v.cond && k(v.veh) === k(veh.id) && p !== k(item.id));
            const nomCond = otroCond && state.personal.find(p => k(p.id) === otroCond[0])?.nombre;
            const n = Object.values(s.viaje).filter(v => k(v.veh) === k(veh.id)).length;
            const detalle = [n ? `${n} ${n === 1 ? 'persona' : 'personas'}` : 'Sin personas', nomCond ? `conduce ${nomCond}` : '']
                .filter(Boolean).join(' · ');

            const f = el('div', 'pe-fila');
            f.appendChild(opcion('fa-truck', 'pi-yellow', veh.nombre, detalle, enEste, () => {
                // Elegir el vehículo donde ya está no cambia nada (conserva si es conductor)
                if (!enEste) ponerEnVehiculo(s, item.id, veh.id, false);
                cerrarPersona();
            }));

            const c = el('button', 'pe-cond' + (conduce ? ' activo' : ''));
            c.type = 'button';
            c.title = conduce ? 'Dejar de ser el conductor' : `Que ${item.nombre} conduzca ${veh.nombre}`;
            c.innerHTML = '<i class="fas fa-id-card"></i>';
            c.append('Conduce');
            c.addEventListener('click', () => {
                ponerEnVehiculo(s, item.id, veh.id, !conduce);
                cerrarPersona();
            });
            f.appendChild(c);
            lista.appendChild(f);
        });
        cuerpo.appendChild(lista);
    }

    $('pe-sacar').addEventListener('click', () => {
        if (!personaCtx) return;
        const { salidaId, perId } = personaCtx;
        cerrarPersona();
        quitar(salidaId, 'personal', perId);
    });
    document.querySelectorAll('[data-cerrar="persona"]').forEach(b => b.addEventListener('click', cerrarPersona));
    $('modal-persona').addEventListener('click', e => { if (e.target === e.currentTarget) cerrarPersona(); });

    function estaAsignado(tipo, id) {
        const key = LISTA[tipo];
        return state.salidas.some(s => s[key].includes(id));
    }

    // Chip de barra: clic selecciona/deselecciona
    function hacerSeleccionable(c, tipo, id) {
        c.dataset.tipo = tipo;
        c.classList.add('clickable');
        if (seleccion[tipo].has(id)) c.classList.add('selected');
        c.addEventListener('click', () => {
            if (c.dataset.longpress) return; // fue un "mantener presionado", no un clic
            toggleSeleccion(tipo, id);
        });
    }

    // ── Mantener presionado → modal de opciones ──
    const LONG_PRESS_MS = 550;

    function hacerMantenible(c, tipo, item) {
        let timer = null, x0 = 0, y0 = 0;
        const cancelar = () => { clearTimeout(timer); timer = null; };

        c.addEventListener('pointerdown', e => {
            if (e.button !== 0 || e.target.closest('.chip-btn')) return;
            x0 = e.clientX; y0 = e.clientY;
            delete c.dataset.longpress;
            timer = setTimeout(() => {
                timer = null;
                c.dataset.longpress = '1';
                abrirOpciones(tipo, item);
            }, LONG_PRESS_MS);
        });
        c.addEventListener('pointermove', e => {
            if (timer && Math.hypot(e.clientX - x0, e.clientY - y0) > 6) cancelar();
        });
        ['pointerup', 'pointerleave', 'pointercancel', 'dragstart'].forEach(ev => c.addEventListener(ev, cancelar));
        c.addEventListener('contextmenu', e => { if (c.dataset.longpress) e.preventDefault(); });
    }

    const NOMBRE_TIPO = { salida: 'salida', vehiculo: 'vehículo', personal: 'personal' };
    const listaDe = tipo => tipo === 'salida' ? state.salidas : state[LISTA[tipo]];
    let opcionesItem = null;

    function abrirOpciones(tipo, item) {
        opcionesItem = { tipo, item };
        $('op-titulo').textContent = item.nombre;
        $('op-sub').textContent = `¿Qué querés hacer con este ${NOMBRE_TIPO[tipo]}?`;
        $('op-menu').hidden = false;
        $('op-editar').hidden = true;
        $('modal-opciones').classList.add('active');
    }

    function cerrarOpciones() {
        opcionesItem = null;
        $('modal-opciones').classList.remove('active');
    }

    $('op-btn-editar').addEventListener('click', () => {
        if (!opcionesItem) return;
        $('op-menu').hidden = true;
        $('op-editar').hidden = false;
        $('op-sub').textContent = 'Modificá el nombre y guardá los cambios.';
        const input = $('op-input');
        input.value = opcionesItem.item.nombre;
        input.focus();
        input.select();
    });

    $('op-editar').addEventListener('submit', e => {
        e.preventDefault();
        const nombre = $('op-input').value.trim();
        if (!nombre || !opcionesItem) { $('op-input').focus(); return; }
        opcionesItem.item.nombre = nombre;
        marcarCambio(opcionesItem.tipo, opcionesItem.item.id);
        cerrarOpciones();
        render();
    });

    $('op-btn-ocultar').addEventListener('click', () => {
        if (!opcionesItem) return;
        const { tipo, item } = opcionesItem;
        item.oculto = true;
        marcarCambio(tipo, item.id);
        seleccion[tipo].delete(item.id);
        cerrarOpciones();
        render();
    });

    document.querySelectorAll('[data-cerrar="opciones"]').forEach(b => b.addEventListener('click', cerrarOpciones));
    $('modal-opciones').addEventListener('click', e => { if (e.target === e.currentTarget) cerrarOpciones(); });

    // ── Modal de ocultos ──
    let ocultosTipo = null;
    const TITULO_OCULTOS = { salida: 'Salidas ocultas', vehiculo: 'Vehículos ocultos', personal: 'Personal oculto' };
    const ICONO = { salida: 'fa-sign-out-alt', vehiculo: 'fa-truck', personal: 'fa-user' };

    function abrirOcultos(tipo) {
        ocultosTipo = tipo;
        renderOcultos();
        $('modal-ocultos').classList.add('active');
    }

    function cerrarOcultos() {
        ocultosTipo = null;
        $('modal-ocultos').classList.remove('active');
    }

    function renderOcultos() {
        if (!ocultosTipo) return;
        const tipo = ocultosTipo;
        const ocultos = listaDe(tipo).filter(x => x.oculto);
        $('oc-titulo').textContent = TITULO_OCULTOS[tipo];
        $('oc-sub').textContent = ocultos.length
            ? 'Hacé clic en uno para volver a mostrarlo.'
            : 'No hay elementos ocultos.';

        const cont = $('oc-lista');
        cont.innerHTML = '';
        ocultos.forEach(item => {
            const c = chip(ICONO[tipo], item.nombre, 'clickable');
            c.title = 'Clic para volver a mostrar';
            const ojo = el('i', 'fas fa-eye chip-icon');
            c.appendChild(ojo);
            c.addEventListener('click', () => {
                item.oculto = false;
                marcarCambio(tipo, item.id);
                render();
                renderOcultos();
            });
            cont.appendChild(c);
        });
    }

    function actualizarBotonesOcultos() {
        document.querySelectorAll('.ocultos-btn').forEach(b => {
            const n = listaDe(b.dataset.tipo).filter(x => x.oculto).length;
            b.querySelector('.ocultos-count').textContent = n;
            b.classList.toggle('has', n > 0);
        });
    }

    document.querySelectorAll('.ocultos-btn').forEach(b =>
        b.addEventListener('click', () => abrirOcultos(b.dataset.tipo)));
    document.querySelectorAll('[data-cerrar="ocultos"]').forEach(b => b.addEventListener('click', cerrarOcultos));
    $('modal-ocultos').addEventListener('click', e => { if (e.target === e.currentTarget) cerrarOcultos(); });

    // ── Asignar tocando ──
    function hayRecursosSeleccionados() {
        return seleccion.vehiculo.size > 0 || seleccion.personal.size > 0;
    }

    function asignarSeleccionA(salidaId) {
        const veh = [...seleccion.vehiculo];
        const per = [...seleccion.personal];
        seleccion.vehiculo.clear();
        seleccion.personal.clear();
        if (veh.length) asignar(salidaId, 'vehiculo', veh);
        if (per.length) asignar(salidaId, 'personal', per);
    }

    const plural = (n, uno, varios) => `${n} ${n === 1 ? uno : varios}`;

    function renderSelBar() {
        const nS = seleccion.salida.size, nV = seleccion.vehiculo.size, nP = seleccion.personal.size;
        const bar = $('sel-bar');
        const enTablero = state.salidas.some(s => s.enTablero);

        $('tablero').classList.toggle('modo-asignar', (nV + nP) > 0 && enTablero);

        if (!nS && !nV && !nP) { bar.hidden = true; return; }
        bar.hidden = false;

        const partes = [];
        if (nS) partes.push(plural(nS, 'salida', 'salidas'));
        if (nV) partes.push(plural(nV, 'vehículo', 'vehículos'));
        if (nP) partes.push(plural(nP, 'persona', 'personas'));

        let ayuda = '';
        if (nV + nP) ayuda = enTablero ? 'Tocá una salida del tablero para asignar' : 'Primero llevá una salida al tablero';
        else if (nS) ayuda = 'Llevalas al tablero con el botón';

        const t = $('sel-texto');
        t.textContent = partes.join(' · ') + (partes.length ? ' seleccionados' : '');
        if (ayuda) t.appendChild(el('small', '', ayuda));

        $('sel-llevar').hidden = nS === 0;
    }

    $('sel-llevar').addEventListener('click', () => {
        const ids = [...seleccion.salida];
        seleccion.salida.clear();
        moverAlTablero(ids);
    });
    $('sel-limpiar').addEventListener('click', limpiarSeleccion);

    // ── Render ──
    function renderSalidas() {
        const cont = $('lista-salidas');
        cont.innerHTML = '';
        const pendientes = state.salidas.filter(s => !s.enTablero && !s.oculto);

        if (!pendientes.length) {
            cont.appendChild(el('div', 'empty-mini',
                state.salidas.some(s => !s.oculto)
                    ? 'Todas las salidas están en el tablero.'
                    : 'No hay salidas visibles. Escribí un nombre y presioná +.'));
            return;
        }

        pendientes.forEach(s => {
            const c = chip('fa-sign-out-alt', s.nombre, 'chip-salida');
            c.title = 'Clic para seleccionar · Mantener presionado para opciones';
            hacerSeleccionable(c, 'salida', s.id);
            hacerMantenible(c, 'salida', s);
            cont.appendChild(c);
        });
    }

    function renderRecurso(tipo, contId) {
        const cont = $(contId);
        const key  = LISTA[tipo];
        cont.innerHTML = '';
        const disponibles = state[key].filter(item => !item.oculto && !estaAsignado(tipo, item.id));

        if (!disponibles.length) {
            const vacio = tipo === 'vehiculo' ? 'Sin vehículos visibles.' : 'Sin personal visible.';
            const todos = tipo === 'vehiculo' ? 'Todos los vehículos están asignados.' : 'Todo el personal está asignado.';
            cont.appendChild(el('div', 'empty-mini', state[key].some(x => !x.oculto) ? todos : vacio));
            return;
        }

        disponibles.forEach(item => {
            const c = chip(tipo === 'vehiculo' ? 'fa-truck' : 'fa-user', item.nombre);
            c.title = 'Clic para seleccionar · Mantener presionado para opciones';
            hacerSeleccionable(c, tipo, item.id);
            hacerMantenible(c, tipo, item);
            cont.appendChild(c);
        });
    }

    function renderSeccion(s, tipo) {
        const key = LISTA[tipo];
        const sec = el('div', 'sc-seccion');
        sec.title = tipo === 'vehiculo' ? 'Clic para buscar y agregar vehículos' : 'Clic para buscar y agregar personal';
        const t = el('div', 'sc-section-title');
        t.innerHTML = `<i class="fas ${tipo === 'vehiculo' ? 'fa-truck' : 'fa-users'}"></i>`;
        t.append(tipo === 'vehiculo' ? 'Vehículos' : 'Personal');
        const mas = el('span', 'sc-add');
        mas.innerHTML = '<i class="fas fa-plus"></i>';
        t.appendChild(mas);
        sec.appendChild(t);

        // Clic en la sección (fuera de una etiqueta) sin nada seleccionado → buscador
        sec.addEventListener('click', e => {
            if (hayRecursosSeleccionados() || e.target.closest('.chip')) return;
            abrirBuscar(tipo, s.id);
        });

        if (!s[key].length) {
            sec.appendChild(el('div', 'sc-hint',
                tipo === 'vehiculo' ? 'Tocá para agregar vehículos' : 'Tocá para agregar personal'));
            return sec;
        }

        const chipsDe = ids => {
            const lista = el('div', 'chips');
            ids.forEach(id => {
                const item = state[key].find(x => x.id === id);
                if (item) lista.appendChild(tipo === 'vehiculo' ? chipVehiculo(s, item) : chipPersona(s, item));
            });
            return lista;
        };

        // Personal: si alguien tiene vehículo asignado, se agrupa por vehículo
        const grupos = tipo === 'personal' ? gruposPersonal(s) : null;
        if (!grupos) {
            sec.appendChild(chipsDe(s[key]));
            return sec;
        }
        const cont = el('div', 'sc-grupos');
        grupos.forEach(g => {
            const box = el('div', 'sc-grupo' + (g.veh ? '' : ' sc-grupo-sin'));
            const tit = el('div', 'sc-grupo-tit');
            tit.innerHTML = `<i class="fas ${g.veh ? 'fa-truck' : 'fa-question-circle'}"></i>`;
            tit.append(g.veh ? g.veh.nombre : 'Sin especificar');
            box.appendChild(tit);
            box.appendChild(chipsDe(g.ids));
            cont.appendChild(box);
        });
        sec.appendChild(cont);
        return sec;
    }

    function chipVehiculo(s, item) {
        const c = chip('fa-truck', item.nombre, 'chip-veh clickable');
        c.title = 'Clic para sacar del tablero';
        c.addEventListener('click', () => {
            if (hayRecursosSeleccionados()) return; // la tarjeta se encarga de asignar
            confirmarQuitarRecurso(s, 'vehiculo', item);
        });
        return c;
    }

    function chipPersona(s, item) {
        const conduce = !!s.viaje[k(item.id)]?.cond;
        const c = chip(conduce ? 'fa-id-card' : 'fa-user', item.nombre, 'chip-per clickable');
        if (conduce) c.appendChild(el('span', 'chip-badge chip-cond', 'Conductor'));
        c.title = 'Clic para elegir vehículo, conductor o sacarlo de la salida';
        c.addEventListener('click', () => {
            if (hayRecursosSeleccionados()) return; // la tarjeta se encarga de asignar
            abrirPersona(s, item);
        });
        return c;
    }

    function renderTablero() {
        const cont = $('tablero');
        cont.innerHTML = '';
        const enTablero = state.salidas.filter(s => s.enTablero);

        if (!enTablero.length) {
            cont.style.display = 'flex';
            const vacio = el('div', 'tablero-empty');
            vacio.innerHTML = '<div class="empty-icon"><i class="fas fa-hand-pointer"></i></div>';
            vacio.appendChild(el('div', 'empty-title', 'El tablero está vacío'));
            vacio.appendChild(el('div', 'empty-sub', 'Hacé clic aquí para buscar una salida, o seleccioná varias en la barra de arriba y tocá "Al tablero".'));
            vacio.style.width = '100%';
            cont.appendChild(vacio);
            return;
        }
        cont.style.display = '';

        enTablero.forEach(s => {
            const card = el('div', 'salida-card');

            const head = el('div', 'salida-card-head');
            head.innerHTML = '<span class="sc-icon"><i class="fas fa-sign-out-alt"></i></span>';
            const nom = el('div', 'sc-titulo');
            nom.appendChild(el('span', 'sc-name', s.nombre));
            nom.appendChild(el('span', 'sc-autor', s.agregadoPor ? `Agregada por ${s.agregadoPor}` : 'Sin guardar'));
            head.appendChild(nom);
            head.title = 'Clic para sacar la salida del tablero';
            head.addEventListener('click', () => confirmarSacarSalida(s));
            card.appendChild(head);

            const body = el('div', 'salida-card-body');
            body.appendChild(renderSeccion(s, 'vehiculo'));
            body.appendChild(renderSeccion(s, 'personal'));
            card.appendChild(body);

            // Tocar la tarjeta con vehículos/personal seleccionados los asigna
            card.addEventListener('click', e => {
                if (!hayRecursosSeleccionados()) return;
                e.stopPropagation();
                asignarSeleccionA(s.id);
            }, true);
            cont.appendChild(card);
        });
    }

    function render() {
        depurarViajes();
        depurarSeleccion();
        actualizarBotonesOcultos();
        renderSelBar();
        renderSalidas();
        renderTablero();
        renderRecurso('vehiculo', 'lista-vehiculos');
        renderRecurso('personal', 'lista-personal');
    }

    // ── Clic en espacio libre del tablero ──
    //   · con salidas seleccionadas → las lleva al tablero
    //   · sin nada seleccionado     → abre el buscador de salidas
    $('zona-tablero').addEventListener('click', e => {
        if (e.target.closest('.salida-card, .btn-copiar')) return;
        if (seleccion.salida.size) {
            const ids = [...seleccion.salida];
            seleccion.salida.clear();
            moverAlTablero(ids);
            return;
        }
        if (hayRecursosSeleccionados()) return; // está asignando vehículos/personal
        abrirBuscar();
    });

    // ── Modal buscador de salidas (por coincidencia, sin importar mayúsculas ni acentos) ──
    const normalizar = t => t.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();
    let busActivo = 0;
    const busSel = new Set(); // elementos marcados en el buscador
    let busCtx = { tipo: 'salida', salidaId: null }; // qué se busca y, si aplica, para qué salida

    const BUS_TXT = {
        salida: {
            titulo: () => 'Agregar salidas al tablero',
            sub:    () => 'Buscá y marcá las salidas que querés llevar al tablero.',
            ph: 'Buscar salida...', icono: 'fa-sign-out-alt', fem: true, uno: 'marcada', varios: 'marcadas',
            sinRes: 'No hay salidas que coincidan con la búsqueda.',
            vacio:  'No hay salidas disponibles. Creá una en la barra de arriba.',
        },
        vehiculo: {
            titulo: s => `Agregar vehículos a "${s.nombre}"`,
            sub:    () => 'Buscá y marcá los vehículos que van en esta salida.',
            ph: 'Buscar vehículo...', icono: 'fa-truck', fem: false, uno: 'marcado', varios: 'marcados',
            sinRes: 'No hay vehículos que coincidan con la búsqueda.',
            vacio:  'No hay vehículos disponibles. Cargá uno en la barra de vehículos.',
        },
        personal: {
            titulo: s => `Agregar personal a "${s.nombre}"`,
            sub:    () => 'Buscá y marcá las personas que van en esta salida.',
            ph: 'Buscar persona...', icono: 'fa-user', fem: true, uno: 'marcada', varios: 'marcadas',
            sinRes: 'No hay personas que coincidan con la búsqueda.',
            vacio:  'No hay personal disponible. Cargá uno en la barra de personal.',
        },
    };

    // Lo que se puede elegir según el tipo (sin filtrar por texto)
    function disponiblesBuscar() {
        const { tipo } = busCtx;
        if (tipo === 'salida') return state.salidas.filter(s => !s.enTablero && !s.oculto);
        return state[LISTA[tipo]].filter(x => !x.oculto && !estaAsignado(tipo, x.id));
    }

    function abrirBuscar(tipo = 'salida', salidaId = null) {
        busCtx = { tipo, salidaId };
        const txt = BUS_TXT[tipo];
        $('bus-titulo').textContent = txt.titulo(getSalida(salidaId) || {});
        $('bus-sub').textContent = txt.sub();
        $('bus-input').placeholder = txt.ph;
        $('bus-input').value = '';
        busActivo = 0;
        busSel.clear();
        renderBuscar();
        $('modal-buscar').classList.add('active');
        setTimeout(() => $('bus-input').focus(), 30);
    }

    function cerrarBuscar() {
        $('modal-buscar').classList.remove('active');
    }

    function resultadosBuscar() {
        const q = normalizar($('bus-input').value.trim());
        return disponiblesBuscar().filter(x => !q || normalizar(x.nombre).includes(q));
    }

    // Resalta la coincidencia dentro del nombre (respetando acentos del original)
    function nombreResaltado(nombre, q) {
        const span = el('span');
        if (!q) { span.textContent = nombre; return span; }
        const i = normalizar(nombre).indexOf(q);
        if (i < 0) { span.textContent = nombre; return span; }
        span.append(nombre.slice(0, i));
        span.appendChild(el('mark', '', nombre.slice(i, i + q.length)));
        span.append(nombre.slice(i + q.length));
        return span;
    }

    function renderBuscar() {
        const cont = $('bus-lista');
        const q = normalizar($('bus-input').value.trim());
        const res = resultadosBuscar();
        cont.innerHTML = '';
        const txt = BUS_TXT[busCtx.tipo];

        // Botón "Agregar" y contador
        const n = busSel.size;
        $('bus-agregar').disabled = n === 0;
        $('bus-cant').textContent = n ? `(${n})` : '';
        $('bus-info').textContent = n ? `${n} ${n === 1 ? txt.uno : txt.varios}` : '';
        $('bus-agregar-txt').textContent = busCtx.tipo === 'salida' ? 'Agregar al tablero' : 'Agregar a la salida';

        // "Marcar todas" / "Desmarcar todas" según los resultados visibles
        const todasMarcadas = res.length > 0 && res.every(s => busSel.has(s.id));
        $('bus-todos').textContent = (todasMarcadas ? 'Desmarcar ' : 'Marcar ') + (txt.fem ? 'todas' : 'todos');
        $('bus-todos').hidden = res.length === 0;

        if (!res.length) {
            cont.appendChild(el('div', 'bus-vacio', disponiblesBuscar().length ? txt.sinRes : txt.vacio));
            return;
        }

        busActivo = Math.min(busActivo, res.length - 1);
        res.forEach((s, i) => {
            const cls = 'bus-item' + (i === busActivo ? ' activo' : '') + (busSel.has(s.id) ? ' marcado' : '');
            const b = el('button', cls);
            b.type = 'button';
            b.innerHTML = `<span class="bus-check"><i class="fas fa-check"></i></span><i class="fas ${txt.icono}"></i>`;
            b.appendChild(nombreResaltado(s.nombre, q));
            b.addEventListener('click', () => { busActivo = i; marcarBuscar(s.id); });
            cont.appendChild(b);
        });
    }

    function marcarBuscar(id) {
        busSel.has(id) ? busSel.delete(id) : busSel.add(id);
        renderBuscar();
    }

    function agregarBuscar() {
        if (!busSel.size) return;
        const ids = [...busSel];
        busSel.clear();
        cerrarBuscar();
        if (busCtx.tipo === 'salida') moverAlTablero(ids);
        else asignar(busCtx.salidaId, busCtx.tipo, ids);
    }

    $('bus-agregar').addEventListener('click', agregarBuscar);
    $('bus-todos').addEventListener('click', () => {
        const res = resultadosBuscar();
        const todas = res.every(s => busSel.has(s.id));
        res.forEach(s => todas ? busSel.delete(s.id) : busSel.add(s.id));
        renderBuscar();
    });

    $('bus-input').addEventListener('input', () => { busActivo = 0; renderBuscar(); });
    $('bus-input').addEventListener('keydown', e => {
        const res = resultadosBuscar();
        if (e.key === 'ArrowDown') { e.preventDefault(); busActivo = Math.min(busActivo + 1, res.length - 1); renderBuscar(); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); busActivo = Math.max(busActivo - 1, 0); renderBuscar(); }
        else if (e.key === 'Enter') {
            e.preventDefault();
            if (e.ctrlKey || e.metaKey) agregarBuscar();          // Ctrl+Enter → agregar
            else if (res[busActivo]) marcarBuscar(res[busActivo].id); // Enter → marcar/desmarcar
        }
        const act = $('bus-lista').querySelector('.bus-item.activo');
        if (act) act.scrollIntoView({ block: 'nearest' });
    });
    document.querySelectorAll('[data-cerrar="buscar"]').forEach(b => b.addEventListener('click', cerrarBuscar));
    $('modal-buscar').addEventListener('click', e => { if (e.target === e.currentTarget) cerrarBuscar(); });

    // ── Alto mínimo del tablero en PC: llena la pantalla sin generar scroll innecesario ──
    function ajustarAlto() {
        const board = document.querySelector('.board');
        if (window.innerWidth <= 900) { board.style.minHeight = ''; return; }
        const top    = board.getBoundingClientRect().top + window.scrollY;
        const footer = document.querySelector('.main-footer');
        const fh     = footer ? footer.offsetHeight : 0;
        const margen = 16; // respiro inferior
        board.style.minHeight = Math.max(420, window.innerHeight - top - fh - margen) + 'px';
    }
    window.addEventListener('resize', ajustarAlto);
    window.addEventListener('load', ajustarAlto);
    ajustarAlto();

    // ══════════════════════════════════════
    //  COPIAR TABLERO COMO IMAGEN
    // ══════════════════════════════════════
    let toastTimer = null;
    function aviso(texto, tipo = 'ok') {
        const t = $('toast');
        t.className = 'toast-aviso ' + tipo;
        t.innerHTML = `<i class="fas ${tipo === 'ok' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>`;
        t.appendChild(el('span', '', texto));
        t.hidden = false;
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => t.hidden = true, 3500);
    }

    // Fecha elegida en el calendario
    const FECHA       = @json($fecha);
    const FECHA_TEXTO = @json($fechaTexto);

    const COL = {
        bg: '#f0f3f7', card: '#ffffff', border: '#d8e0ea', text: '#1e2835', muted: '#8496aa',
        accent: '#2a6fdb', accentS: '#e8f0fc',
        vehBg: '#fdf4e3', vehBd: '#f0dcb4', vehTx: '#8a5a12',
        perBg: '#e5f6f0', perBd: '#b6e8d6', perTx: '#16714f',
    };
    const FONT = "'Plus Jakarta Sans', 'Segoe UI', Arial, sans-serif";

    function rr(ctx, x, y, w, h, r) {
        ctx.beginPath();
        ctx.moveTo(x + r, y);
        ctx.arcTo(x + w, y, x + w, y + h, r);
        ctx.arcTo(x + w, y + h, x, y + h, r);
        ctx.arcTo(x, y + h, x, y, r);
        ctx.arcTo(x, y, x + w, y, r);
        ctx.closePath();
    }

    function recortar(ctx, texto, maxW) {
        if (ctx.measureText(texto).width <= maxW) return texto;
        let t = texto;
        while (t.length > 1 && ctx.measureText(t + '…').width > maxW) t = t.slice(0, -1);
        return t + '…';
    }

    // Dibuja (o solo mide si draw = false) y devuelve el alto total
    function dibujarTablero(ctx, W, draw) {
        const P = 32, CARD_GAP = 16, CHIP_H = 32, CHIP_GAP = 8, CHIP_PX = 12;
        const salidas = state.salidas.filter(s => s.enTablero);
        let y = P;

        // Encabezado
        ctx.textBaseline = 'alphabetic';
        if (draw) {
            ctx.fillStyle = COL.text;
            ctx.font = `700 30px ${FONT}`;
            ctx.fillText('Salidas', P, y + 28);
            ctx.fillStyle = COL.muted;
            ctx.font = `500 16px ${FONT}`;
            ctx.fillText(FECHA_TEXTO, P, y + 54);
        }
        y += 54 + 24;

        const cw = W - P * 2;

        salidas.forEach(s => {
            const y0 = y;
            const HEAD_H = 52;
            let cy = y0 + HEAD_H + 16;

            const subtitulo = (texto, color) => {
                if (draw) {
                    ctx.fillStyle = color;
                    ctx.font = `700 13px ${FONT}`;
                    ctx.fillText(recortar(ctx, texto, cw - 36), P + 18, cy + 12);
                }
                cy += 20;
            };

            const tituloSeccion = titulo => {
                if (draw) {
                    ctx.fillStyle = COL.muted;
                    ctx.font = `700 12px ${FONT}`;
                    ctx.fillText(titulo, P + 18, cy + 10);
                }
                cy += 20;
            };

            // items: [{ nombre }]; titulo null → solo los chips
            const seccion = (titulo, items, bg, bd, tx) => {
                if (titulo !== null) tituloSeccion(titulo);
                if (!items.length) {
                    if (draw) {
                        ctx.fillStyle = COL.muted;
                        ctx.font = `italic 500 14px ${FONT}`;
                        ctx.fillText('Sin asignar', P + 18, cy + 18);
                    }
                    cy += 26;
                    return;
                }
                ctx.font = `600 15px ${FONT}`;
                const maxX = P + cw - 18;
                let cx = P + 18;
                items.forEach(it => {
                    const txt = recortar(ctx, it.nombre, cw - 36 - CHIP_PX * 2 - 14);
                    const w = ctx.measureText(txt).width + CHIP_PX * 2 + 14;
                    if (cx + w > maxX && cx > P + 18) { cx = P + 18; cy += CHIP_H + CHIP_GAP; }
                    if (draw) {
                        rr(ctx, cx, cy, w, CHIP_H, 8);
                        ctx.fillStyle = bg; ctx.fill();
                        ctx.strokeStyle = bd; ctx.lineWidth = 1.5; ctx.stroke();
                        ctx.beginPath();
                        ctx.arc(cx + CHIP_PX + 3, cy + CHIP_H / 2, 3.5, 0, Math.PI * 2);
                        ctx.fillStyle = tx; ctx.fill();
                        ctx.fillStyle = COL.text;
                        ctx.font = `600 15px ${FONT}`;
                        ctx.fillText(txt, cx + CHIP_PX + 14, cy + CHIP_H / 2 + 5.5);
                    }
                    cx += w + CHIP_GAP;
                });
                cy += CHIP_H;
            };

            const nombres = (ids, lista) => ids.map(id => lista.find(x => x.id === id)).filter(Boolean);
            const nombrePersona = p => ({ nombre: s.viaje[k(p.id)]?.cond ? `${p.nombre} (conductor)` : p.nombre });

            const contenido = () => {
                seccion('VEHÍCULOS', nombres(s.vehiculos, state.vehiculos), COL.vehBg, COL.vehBd, COL.vehTx);
                cy += 14;
                const grupos = gruposPersonal(s);
                if (!grupos) {
                    seccion('PERSONAL', nombres(s.personal, state.personal).map(nombrePersona), COL.perBg, COL.perBd, COL.perTx);
                    return;
                }
                tituloSeccion('PERSONAL');
                grupos.forEach((g, i) => {
                    if (i) cy += 10;
                    subtitulo(g.veh ? g.veh.nombre : 'Sin especificar', g.veh ? COL.vehTx : COL.muted);
                    seccion(null, nombres(g.ids, state.personal).map(nombrePersona), COL.perBg, COL.perBd, COL.perTx);
                });
            };

            // Primero medimos el contenido para saber el alto de la tarjeta
            const drawOrig = draw;
            draw = false;
            contenido();
            const cardH = cy + 18 - y0;
            draw = drawOrig;

            if (draw) {
                // Tarjeta
                rr(ctx, P, y0, cw, cardH, 14);
                ctx.fillStyle = COL.card; ctx.fill();
                ctx.strokeStyle = COL.border; ctx.lineWidth = 1.5; ctx.stroke();
                // Cabecera
                ctx.save();
                rr(ctx, P, y0, cw, cardH, 14); ctx.clip();
                ctx.fillStyle = COL.accentS;
                ctx.fillRect(P, y0, cw, HEAD_H);
                ctx.fillStyle = COL.accent;
                ctx.fillRect(P, y0, 6, HEAD_H);
                ctx.restore();
                ctx.strokeStyle = COL.border; ctx.lineWidth = 1.5;
                ctx.beginPath(); ctx.moveTo(P, y0 + HEAD_H); ctx.lineTo(P + cw, y0 + HEAD_H); ctx.stroke();
                ctx.fillStyle = COL.text;
                ctx.font = `700 19px ${FONT}`;
                ctx.fillText(recortar(ctx, s.nombre, cw - 40), P + 20, y0 + 33);

                cy = y0 + HEAD_H + 16;
                contenido();
            }

            y = y0 + cardH + CARD_GAP;
        });

        // Pie
        y += 4;
        if (draw) {
            ctx.fillStyle = COL.muted;
            ctx.font = `500 13px ${FONT}`;
            ctx.fillText('Gavilan y Asociados', P, y + 12);
        }
        return y + 12 + P;
    }

    async function generarImagen() {
        if (document.fonts && document.fonts.ready) await document.fonts.ready;
        const W = 800, ESCALA = 2;
        const medir = document.createElement('canvas').getContext('2d');
        const H = dibujarTablero(medir, W, false);

        const canvas = document.createElement('canvas');
        canvas.width = W * ESCALA;
        canvas.height = H * ESCALA;
        const ctx = canvas.getContext('2d');
        ctx.scale(ESCALA, ESCALA);
        ctx.fillStyle = COL.bg;
        ctx.fillRect(0, 0, W, H);
        dibujarTablero(ctx, W, true);

        return new Promise(res => canvas.toBlob(res, 'image/png'));
    }

    function descargar(blob) {
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = `salidas-${FECHA}.png`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        setTimeout(() => URL.revokeObjectURL(a.href), 1000);
    }

    $('btn-copiar').addEventListener('click', async () => {
        if (!state.salidas.some(s => s.enTablero)) {
            aviso('El tablero está vacío, no hay nada para copiar.', 'err');
            return;
        }
        const btn = $('btn-copiar');
        btn.disabled = true;
        const esTactil = window.matchMedia('(pointer: coarse)').matches;

        try {
            // Celular: abrir el menú de compartir
            if (esTactil && navigator.canShare) {
                const blob = await generarImagen();
                const file = new File([blob], `salidas-${FECHA}.png`, { type: 'image/png' });
                if (navigator.canShare({ files: [file] })) {
                    await navigator.share({ files: [file], title: 'Salidas - ' + FECHA_TEXTO });
                    return;
                }
            }

            // PC: copiar al portapapeles (
            if (window.isSecureContext && navigator.clipboard && window.ClipboardItem) {
                await navigator.clipboard.write([new ClipboardItem({ 'image/png': generarImagen() })]);
                aviso('Imagen copiada.');
                return;
            }

            // Sin portapapeles disponible (ej. sitio sin https): se descarga
            descargar(await generarImagen());
            aviso('Tu navegador no permite copiar imágenes aquí; se descargó la imagen.');
        } catch (err) {
            if (err && err.name === 'AbortError') return; // canceló el menú de compartir
            try {
                descargar(await generarImagen());
                aviso('No se pudo copiar; se descargó la imagen.', 'err');
            } catch {
                aviso('No se pudo generar la imagen.', 'err');
            }
        } finally {
            btn.disabled = false;
        }
    });

    // ── Formularios de alta ──
    document.querySelectorAll('.add-form').forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();
            const input = form.querySelector('.add-input');
            const nombre = input.value.trim();
            if (!nombre) { input.focus(); return; }
            agregar(form.dataset.tipo, nombre);
            input.value = '';
            input.focus();
        });
    });

    // ══════════════════════════════════════
    //  GUARDAR
    // ══════════════════════════════════════
    const URL_GUARDAR = @json(route('salidas.guardar'));
    const CSRF        = @json(csrf_token());
    let guardando = false;

    function actualizarBotonGuardar() {
        const b = $('btn-guardar');
        b.classList.toggle('sucio', sucio);
        b.title = sucio ? 'Hay cambios sin guardar (Ctrl+S)' : 'Todo guardado';
    }

    function armarPayload() {
        const catalogo = {};
        [['salida', state.salidas], ['vehiculo', state.vehiculos], ['personal', state.personal]].forEach(([tipo, lista]) => {
            catalogo[tipo] = lista
                .filter(x => cambiados[tipo].has(x.id))
                .map(x => ({ id: x.id, nombre: x.nombre, oculto: !!x.oculto }));
        });
        const tablero = state.salidas
            .filter(s => s.enTablero)
            .map(s => ({
                salida_id: s.id,
                vehiculos: s.vehiculos,
                personal:  s.personal,
                // La clave es texto: se envía el id original (número o temporal "n…")
                viaje: s.personal
                    .filter(id => s.viaje[k(id)])
                    .map(id => ({ personal: id, vehiculo: s.viaje[k(id)].veh, conductor: s.viaje[k(id)].cond })),
            }));
        return { fecha: FECHA, catalogo, tablero };
    }

    async function guardar() {
        if (guardando) return false;
        guardando = true;
        const btn = $('btn-guardar');
        btn.disabled = true;
        btn.classList.add('cargando');

        try {
            const resp = await fetch(URL_GUARDAR, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(armarPayload()),
            });
            const data = await resp.json().catch(() => ({}));

            if (!resp.ok) {
                const msg = data.errors ? Object.values(data.errors).flat()[0] : (data.message || 'No se pudo guardar.');
                aviso(resp.status === 419 ? 'La sesión expiró. Recargá la página.' : msg, 'err');
                return false;
            }

            // El servidor devuelve el estado con los ids definitivos
            Object.values(seleccion).forEach(s => s.clear());
            cargarEstado(data.estado);
            sucio = false;
            document.querySelector('.aviso-copia')?.remove();
            actualizarBotonGuardar();
            render();
            aviso('Salidas guardadas correctamente.');
            return true;
        } catch (err) {
            aviso('No se pudo conectar con el servidor.', 'err');
            return false;
        } finally {
            guardando = false;
            btn.disabled = false;
            btn.classList.remove('cargando');
        }
    }

    $('btn-guardar').addEventListener('click', guardar);
    document.addEventListener('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') { e.preventDefault(); guardar(); }
    });
    // ── Salir con cambios sin guardar ──
    let saliendo = false;     // salida confirmada: no volver a preguntar
    let destinoSalir = null;  // URL a la que se quiere ir

    function irA(url) {
        if (sucio) {
            destinoSalir = url;
            $('modal-salir').classList.add('active');
            $('salir-cancelar').focus();
            return;
        }
        window.location.href = url;
    }
    function cerrarSalir() {
        $('modal-salir').classList.remove('active');
        destinoSalir = null;
    }
    function salirAhora() {
        saliendo = true;
        window.location.href = destinoSalir;
    }

    $('salir-cancelar').addEventListener('click', cerrarSalir);
    $('modal-salir').addEventListener('click', e => { if (e.target === e.currentTarget) cerrarSalir(); });
    $('salir-igual').addEventListener('click', salirAhora);
    $('salir-guardar').addEventListener('click', async () => {
        const b = $('salir-guardar');
        b.disabled = true;
        const ok = await guardar();
        b.disabled = false;
        if (ok) salirAhora();
        else cerrarSalir(); // el error ya se mostró en el aviso
    });

    // Cualquier enlace de la página (Volver, migas, navegador de fechas, menú)
    document.addEventListener('click', e => {
        if (!sucio || e.defaultPrevented || e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey) return;
        const a = e.target.closest('a[href]');
        if (!a || a.target === '_blank' || a.hasAttribute('download')) return;
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
        e.preventDefault();
        irA(a.href);
    });

    window.addEventListener('beforeunload', e => {
        if (!sucio || saliendo) return;
        e.preventDefault();
        e.returnValue = '';
    });

    // Navegador de fechas: al elegir una fecha se va a esa página
    // (si hay cambios sin guardar, se muestra el aviso de salir)
    $('fecha-nav-input').addEventListener('change', e => {
        const v = e.target.value;
        if (!v || v === FECHA) return;
        const url = new URL(@json(route('salidas.create')), window.location.origin);
        url.searchParams.set('fecha', v);
        e.target.value = FECHA; // si cancela, queda la fecha actual
        irA(url.toString());
    });

    // Tablero copiado de otra fecha: queda pendiente de guardar
    if (@json((bool) $copiadoDe)) sucio = true;

    actualizarBotonGuardar();
    render();
})();
</script>
</body>
</html>
