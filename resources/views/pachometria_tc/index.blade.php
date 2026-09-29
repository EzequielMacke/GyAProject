<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pachometrías</title>
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
            --orange:   #d9622a;
            --orange-s: #fff0eb;
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
        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; flex-wrap: wrap; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        /* ── ESTADO DE GUARDADO ── */
        .estado-guardado {
            display: inline-flex; align-items: center; gap: 0.4rem; margin-right: 0.35rem;
            font-size: 0.78rem; font-weight: 600; color: var(--muted);
        }
        .estado-guardado i { font-size: 0.72rem; }
        .estado-guardado.pendiente { color: var(--muted); }
        .estado-guardado.guardando { color: var(--accent-b); }
        .estado-guardado.guardado { color: #1e8e5a; }
        .estado-guardado.error { color: #c0392b; }

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

        /* ── PANEL ── */
        .panel {
            background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem;
            padding: 1.5rem 1.5rem 1.6rem; margin-bottom: 1.25rem;
        }
        .panel-title {
            font-size: 0.95rem; font-weight: 700; color: var(--text);
            display: flex; align-items: center; gap: 0.5rem;
            margin-bottom: 1.1rem;
        }
        .panel-title i { color: var(--accent); }

        /* ── GRILLA DE PACHOMETRÍAS ── */
        .pach-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 1.25rem;
        }

        .pach-card {
            background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem;
            overflow: hidden; display: flex; flex-direction: column;
            min-height: 340px;
            cursor: pointer;
            transition: border-color 0.14s, box-shadow 0.14s;
            animation: cardIn 0.18s ease both;
        }
        .pach-card:hover { border-color: var(--border2); }

        /* Tarjeta expandida: ocupa todo el ancho y muestra el panel lateral */
        .pach-card.expandida {
            grid-column: 1 / -1;
            cursor: default;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(42,111,219,0.12);
        }
        @keyframes cardIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

        .pach-head {
            display: flex; align-items: center; gap: 0.7rem;
            padding: 0.85rem 1.1rem;
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
        }
        .pach-badge {
            min-width: 34px; height: 34px; padding: 0 0.4rem; border-radius: 0.55rem;
            background: var(--orange-s); color: var(--orange);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem; font-weight: 700; flex-shrink: 0;
        }
        .pach-head-title { font-size: 0.85rem; font-weight: 700; color: var(--text); }
        .pach-head-sub { font-size: 0.72rem; color: var(--muted); }
        .pach-cerrar-btn {
            display: none;
            margin-left: auto; background: none; border: none; cursor: pointer;
            color: var(--muted); font-size: 0.9rem; padding: 0.45rem; border-radius: 0.45rem;
            transition: color 0.14s, background 0.14s; flex-shrink: 0;
        }
        .pach-cerrar-btn:hover { color: var(--text); background: var(--surface2); }
        .pach-card.expandida .pach-cerrar-btn { display: block; }

        .pach-body { padding: 1.1rem; display: flex; gap: 1.1rem; flex: 1; }
        /* Expandida: dibujo y parámetros se reparten el ancho mitad y mitad */
        .pach-card.expandida .pach-lienzo { flex: 1 1 0; min-height: 260px; }

        /* Panel lateral (solo visible con la tarjeta expandida) */
        .pach-panel {
            display: none;
            flex: 1 1 0; min-width: 0;
            flex-direction: column; gap: 1.25rem;
            padding-left: 1.25rem; border-left: 1px solid var(--border);
        }
        .pach-card.expandida .pach-panel { display: flex; }
        .pach-panel-acciones { margin-top: auto; }

        .pach-delete-btn {
            width: 100%; height: 38px; border-radius: 0.55rem;
            border: 1.5px solid #f5c2c2; background: #fff0f0; color: #c0392b;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600;
            cursor: pointer; transition: all 0.14s;
            display: inline-flex; align-items: center; justify-content: center; gap: 0.45rem;
        }
        .pach-delete-btn:hover { background: #c0392b; border-color: #c0392b; color: #fff; }

        /* Selector de tipo de elemento */
        .tipo-label { font-size: 0.72rem; font-weight: 700; color: var(--text2); text-transform: uppercase; letter-spacing: 0.04em; }
        .tipo-opciones { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-top: 0.4rem; }
        .tipo-opciones .tipo-btn { justify-content: center; }
        .tipo-btn {
            height: 38px; border-radius: 0.55rem;
            border: 1.5px solid var(--border); background: #fff; color: var(--text2);
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600;
            cursor: pointer; transition: all 0.14s;
            display: inline-flex; align-items: center; justify-content: flex-start; gap: 0.55rem;
            padding: 0 0.85rem;
        }
        .tipo-btn i { width: 16px; text-align: center; }
        .tipo-btn:hover { border-color: var(--accent); color: var(--accent-b); }
        .tipo-btn.activo { background: var(--accent); border-color: var(--accent); color: #fff; }

        /* Nombre (prefijo fijo "PCH" + número) */
        .nombre-campo {
            display: flex; align-items: stretch; max-width: 220px; margin-top: 0.4rem;
            border: 1.5px solid var(--border); border-radius: 0.55rem; background: #fff;
            overflow: hidden; transition: border-color 0.15s, box-shadow 0.15s;
        }
        .nombre-campo:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .nombre-campo.duplicado { border-color: #e74c3c; background: #fff0f0; }
        .nombre-prefijo {
            display: flex; align-items: center; padding: 0 0.75rem;
            background: var(--surface2); border-right: 1.5px solid var(--border);
            font-size: 0.85rem; font-weight: 700; color: var(--text2);
        }
        .nombre-input {
            flex: 1; min-width: 0; border: none; outline: none; background: transparent;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; font-weight: 600;
            padding: 0.5rem 0.75rem; color: var(--text);
            -moz-appearance: textfield;
        }
        .nombre-input::-webkit-outer-spin-button,
        .nombre-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .nombre-aviso { font-size: 0.72rem; font-weight: 600; color: #c0392b; margin-top: 0.35rem; }

        /* Parámetros del elemento */
        .pach-parametros { display: flex; flex-direction: column; gap: 1rem; }
        .pach-parametros:empty { display: none; }
        .forma-opciones { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; margin-top: 0.4rem; }
        .forma-opciones .tipo-btn { justify-content: center; padding: 0 0.5rem; }
        .forma-opciones.losa-opciones { grid-template-columns: repeat(4, 1fr); }
        .losa-opciones + .medidas-grid { margin-top: 0.75rem; }
        .medidas-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 220px)); gap: 0.75rem; margin-top: 0.4rem; }
        .medidas-grid.tres { grid-template-columns: repeat(3, minmax(0, 160px)); }
        .sub-label { font-size: 0.72rem; font-weight: 700; color: var(--text2); margin-top: 0.85rem; display: block; }
        .sub-label small { font-weight: 500; color: var(--muted); }
        .barras-lista { display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.4rem; }
        .barra-fila { display: flex; align-items: center; gap: 0.5rem; }
        .barra-fila .medida-input { width: 100px; }
        .barra-fila.camada .medida-input { width: 80px; }
        .barra-fila-texto { font-size: 0.8rem; font-weight: 600; color: var(--text2); }
        .barra-quitar {
            width: 34px; height: 34px; flex-shrink: 0; border-radius: 0.5rem;
            border: none; background: none; color: var(--muted); cursor: pointer;
            transition: color 0.14s, background 0.14s;
        }
        .barra-quitar:hover { color: #c0392b; background: #fff0f0; }
        .barra-agregar {
            align-self: flex-start; height: 34px; padding: 0 0.85rem; margin-top: 0.1rem;
            border-radius: 0.5rem; border: 1.5px dashed var(--border2); background: #fff;
            color: var(--accent); font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.14s;
        }
        .barra-agregar:hover { background: var(--accent-s); border-color: var(--accent); }
        .medida-campo { display: flex; flex-direction: column; gap: 0.3rem; }
        .medida-campo span { font-size: 0.7rem; font-weight: 600; color: var(--muted); }
        .medida-input {
            width: 100%; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem;
            background: #fff; border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.5rem 0.75rem; color: var(--text);
            outline: none; transition: border-color 0.15s, box-shadow 0.15s;
            -moz-appearance: textfield;
        }
        .medida-input::-webkit-outer-spin-button,
        .medida-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .medida-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        /* Área de dibujo */
        /* El marco no se agranda; adentro, la capa .lienzo-zoom lleva la
           cuadrícula y el contenido, y es la que se escala con el zoom. */
        .pach-lienzo {
            flex: 1; min-width: 0; min-height: 220px;
            border: 1.5px dashed var(--border2); border-radius: 0.7rem;
            background: #fff;
            display: flex; position: relative; overflow: hidden;
        }
        .lienzo-zoom {
            flex: 1; min-width: 0;
            background:
                linear-gradient(var(--surface2) 1px, transparent 1px) 0 0 / 20px 20px,
                linear-gradient(90deg, var(--surface2) 1px, transparent 1px) 0 0 / 20px 20px,
                #fff;
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 1rem; text-align: center;
            transform-origin: 0 0;
        }
        .lienzo-rotulo { line-height: 1.25; }
        .rotulo-nombre { font-size: 0.95rem; font-weight: 700; color: var(--text); }
        .rotulo-tipo { font-size: 0.78rem; font-weight: 600; color: var(--muted); }
        .lienzo-leyenda { font-size: 0.78rem; font-weight: 600; color: var(--muted); line-height: 1.35; }
        .pach-lienzo svg { width: 100%; max-width: 320px; height: auto; }
        .pach-card.expandida .pach-lienzo svg { max-width: 300px; }
        /* Zoom (solo con la tarjeta expandida): ruedita / dos dedos, y arrastrar para mover */
        .pach-card.expandida .lienzo-zoom { touch-action: none; cursor: grab; user-select: none; -webkit-user-select: none; }
        .pach-card.expandida .pach-lienzo.arrastrando .lienzo-zoom { cursor: grabbing; }
        .lienzo-zoom-reset {
            display: none;
            position: absolute; top: 0.5rem; right: 0.5rem;
            height: 30px; padding: 0 0.65rem; border-radius: 0.45rem;
            border: 1.5px solid var(--border); background: #fff; color: var(--text2);
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.75rem; font-weight: 600;
            cursor: pointer; align-items: center; gap: 0.35rem;
        }
        .lienzo-zoom-reset:hover { border-color: var(--accent); color: var(--accent-b); }
        .pach-card.expandida .pach-lienzo.con-zoom .lienzo-zoom-reset { display: inline-flex; }
        .lienzo-mensaje { font-size: 0.8rem; color: var(--muted); }
        .lienzo-mensaje i { display: block; font-size: 1.3rem; margin-bottom: 0.4rem; color: var(--border2); }

        /* Tarjeta "Agregar pachometría" */
        .pach-agregar {
            min-height: 340px;
            border: 1.5px dashed var(--border2); border-radius: 0.85rem;
            background: var(--surface); color: var(--accent);
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.75rem;
            cursor: pointer; transition: all 0.14s;
        }
        .pach-agregar:hover { background: var(--accent-s); border-color: var(--accent); }
        .pach-agregar[hidden], .pach-delete-btn[hidden] { display: none; }

        /* ── MODAL (igual que en las planillas) ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-caja {
            background: #fff; border-radius: 1rem;
            width: 100%; max-width: 420px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.18);
            overflow: hidden;
            animation: modalIn 0.2s ease both;
        }
        @keyframes modalIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .modal-head {
            padding: 1.4rem 1.75rem 1.2rem;
            border-bottom: 1.5px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-head-title { font-size: 1rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 0.5rem; }
        .modal-head-title.danger i { color: #c0392b; }
        .modal-close { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 1rem; padding: 0.25rem; border-radius: 0.35rem; transition: color 0.14s; }
        .modal-close:hover { color: var(--text); }
        .modal-body { padding: 1.25rem 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 0.6rem; }
        .modal-body p { font-size: 0.83rem; color: var(--muted); padding: 0 0.5rem; }
        .modal-body p strong { color: var(--text); }
        .modal-foot { padding: 1rem 1.75rem 1.4rem; display: flex; justify-content: flex-end; gap: 0.5rem; }
        .btn-cancel { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); cursor: pointer; transition: all 0.14s; }
        .btn-cancel:hover { background: var(--surface2); }
        .btn-confirmar-eliminar {
            height: 36px; padding: 0 1.1rem; border-radius: 0.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600;
            border: 1.5px solid #c0392b; background: #c0392b; color: #fff; cursor: pointer;
            display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.14s;
        }
        .btn-confirmar-eliminar:hover { background: #a93226; border-color: #a93226; }
        .pach-agregar-icono {
            width: 60px; height: 60px; border-radius: 50%;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
            transition: all 0.14s;
        }
        .pach-agregar:hover .pach-agregar-icono { background: var(--accent); color: #fff; }
        .pach-agregar-texto { font-size: 0.95rem; font-weight: 700; }

        /* ── MOBILE ── */
        @media (max-width: 640px) {
            .ph { padding: 1rem 0 0.75rem; gap: 0.75rem; margin-bottom: 1rem; }
            .ph-title { font-size: 1.3rem; }
            .ph-right { width: 100%; }
            .pach-grid { grid-template-columns: 1fr; }
            .pach-card, .pach-agregar { min-height: 300px; }
            .forma-opciones.losa-opciones { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 900px) {
            .pach-card.expandida .pach-body { flex-direction: column; }
            .pach-card.expandida .pach-lienzo { flex: none; min-height: 220px; }
            .pach-panel { width: 100%; padding-left: 0; border-left: none; padding-top: 1rem; border-top: 1px solid var(--border); }
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
                            Pachometrías
                        </div>
                        <h1 class="ph-title"><em>Detalle de Pachometrías</em></h1>
                        <p class="ph-sub">{{ $obraTc->descripcion ?? '-' }}</p>
                    </div>
                    <div class="ph-right">
                        @if($puedeEditar || $puedeAgregar)
                        <span class="estado-guardado guardado" id="estado-guardado">
                            <i class="fas fa-check"></i> <span id="estado-guardado-texto">Guardado</span>
                        </span>
                        @endif
                        <button type="button" class="btn" id="btn-exportar-pdf">
                            <i class="fas fa-file-pdf"></i> Exportar PDF
                        </button>
                        <a href="{{ route('obras_tc.index', $obraTc->id) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="pach-grid" id="pach-grid">
                    <button type="button" class="pach-agregar" id="btn-agregar-pachometria" @if(! $puedeAgregar) hidden @endif>
                        <span class="pach-agregar-icono"><i class="fas fa-plus"></i></span>
                        <span class="pach-agregar-texto">Agregar pachometría</span>
                    </button>
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- Modal de confirmación para eliminar una pachometría --}}
@if($puedeEliminar)
<div class="modal-overlay" id="modal-eliminar-pachometria">
    <div class="modal-caja">
        <div class="modal-head">
            <div class="modal-head-title danger"><i class="fas fa-triangle-exclamation"></i> Eliminar pachometría</div>
            <button type="button" class="modal-close" id="modal-eliminar-cerrar" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p>¿Seguro que querés eliminar la pachometría <strong id="eliminar-pachometria-nombre"></strong>? Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" id="modal-eliminar-cancelar">Cancelar</button>
            <button type="button" class="btn-confirmar-eliminar" id="modal-eliminar-confirmar">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
    </div>
</div>
@endif

<script>
    const grilla = document.getElementById('pach-grid');
    const btnAgregar = document.getElementById('btn-agregar-pachometria');

    const TIPOS = {
        viga:  { nombre: 'Viga',  icono: 'fa-grip-lines' },
        pilar: { nombre: 'Pilar', icono: 'fa-square' },
        losa:  { nombre: 'Losa',  icono: 'fa-layer-group' },
    };

    /* ─── Dibujo de la sección ────────────────────────────────
       Viga: rectángulo base × altura, en proporción real (sin
             medidas, una viga genérica vertical de 20×40).
       Pilar: rectangular (lado × ancho, en proporción real) o
              circular (diámetro). Sin medidas cargadas se dibuja
              un cuadrado / círculo genérico.
       Losa: planta de 1 m × 1 m y corte A-A (ver más abajo). */
    const ESTILO_SECCION = 'fill="#e9edf2" stroke="#445060" stroke-width="2"';
    const ESTILO_COTA = 'stroke="#8496aa" stroke-width="1"';
    const ESTILO_TEXTO_CARA = 'font-size="10" font-weight="700" fill="#2a6fdb" letter-spacing="0.3" font-family="Plus Jakarta Sans, sans-serif"';
    const ESTILO_TEXTO_COTA = 'font-size="12" font-weight="600" fill="#445060" font-family="Plus Jakarta Sans, sans-serif"';

    // Espacio disponible para la sección dentro del viewBox 320×200,
    // dejando margen para las cotas.
    const MAX_ANCHO_DIBUJO = 200;
    const MAX_ALTO_DIBUJO = 140;

    function leerMedida(valor) {
        const numero = parseFloat(valor);
        return isNaN(numero) || numero <= 0 ? null : numero;
    }

    function formatearMedida(valor) {
        return `${Number(valor.toFixed(2))} cm`;
    }

    /* ─── Estribo ─────────────────────────────────────────────
       El recubrimiento se mide desde el borde hasta la cara
       exterior del estribo, así que el eje del estribo queda a
       (recubrimiento + Ø/2) del borde. Ø se carga en mm.
       Devuelve la distancia al eje (en cm), el grosor del trazo y
       el largo del gancho en unidades del dibujo, o null si no hay
       recubrimiento. */
    const COLOR_ESTRIBO = '#1e2835';
    const GROSOR_MINIMO_ESTRIBO = 4;

    function calcularEstribo(armadura, escala) {
        if (armadura.recubrimiento === null) return null;
        const diametroCm = (armadura.estribo ?? 0) / 10;
        return {
            distanciaEje: armadura.recubrimiento + diametroCm / 2,
            grosor: Math.max(GROSOR_MINIMO_ESTRIBO, diametroCm * escala),
            // Gancho de 135°: extensión de 12Ø del estribo. Sin Ø cargado,
            // 3 cm para que se vea.
            largoGancho: (diametroCm ? 12 * diametroCm : 3) * escala,
        };
    }

    /* Dibuja el estribo como una sola barra continua y "hueca":
       el trazo se hace primero grueso y oscuro, y encima un poco más
       fino en blanco, así se ven los dos bordes de la barra (igual que
       en los planos).

       El gancho de 135° es parte del mismo trazo: cada extremo del
       estribo se dobla alrededor de la esquina (donde iría la barra
       longitudinal) y entra en diagonal hacia el interior. Los dos
       dobleces comparten el centro "c", con radio "rb".

       En el doblez un extremo pasa por encima del otro: por eso el
       estribo se arma en dos tramos que se dibujan completos (borde
       oscuro + relleno blanco) uno después del otro. El segundo tramo
       queda arriba y sus bordes "cortan" al primero en el cruce. Los
       tramos se solapan un poco en un lado recto para que la unión
       no se note.

       construirTramos(recorte) devuelve los atributos "d" de cada
       tramo, en orden de abajo hacia arriba; para el trazo blanco las
       patas se acortan un poco, así las puntas quedan cerradas. */
    function dibujarEstriboHueco(construirTramos, datos) {
        const g = datos.grosor;
        const borde = Math.min(1.2, g / 4);
        const trazo = (d, color, ancho) =>
            `<path d="${d}" fill="none" stroke="${color}" stroke-width="${ancho}"
                   stroke-linecap="butt" stroke-linejoin="round"></path>`;
        const oscuros = construirTramos(0);
        const claros = construirTramos(borde);
        return oscuros.map((d, i) => trazo(d, COLOR_ESTRIBO, g) + trazo(claros[i], '#fff', g - 2 * borde)).join('');
    }

    // Punto sobre una circunferencia (ángulo en grados, eje Y hacia abajo).
    function puntoEn(c, radio, grados) {
        const rad = grados * Math.PI / 180;
        return { x: c.x + radio * Math.cos(rad), y: c.y + radio * Math.sin(rad) };
    }

    // Punta de la pata: sale de "p" en diagonal hacia el interior (↘).
    function puntaPata(p, largo) {
        return { x: p.x + largo * Math.SQRT1_2, y: p.y + largo * Math.SQRT1_2 };
    }

    function leerArmadura(card) {
        const d = card.dataset;
        return {
            // Se carga en mm; los cálculos del dibujo trabajan en cm.
            recubrimientoMm: leerMedida(d.recubrimiento),
            recubrimiento: leerMedida(d.recubrimiento) !== null ? leerMedida(d.recubrimiento) / 10 : null,
            estribo: leerMedida(d.estribo),
            separacion: leerMedida(d.separacion),
            barras: leerBarras(card.barras),     // pilar circular
            esquina: leerMedida(d.esquina),     // pilar rectangular: Ø de las 4 esquinas
            caraX: leerBarras(card.barrasX),    // pilar rectangular: por cara X (arriba = abajo)
            caraY: leerBarras(card.barrasY),    // pilar rectangular: por cara Y (izquierda = derecha)
            superior: leerBarras(card.barrasSuperior), // viga: a lo largo de la cara de arriba
            inferior: leerBarras(card.barrasInferior), // viga: a lo largo de la cara de abajo
            camadas: leerCamadas(card.barrasCamadas),  // viga: a lo ancho, a cierta altura
            piel: leerPiel(card.barrasPiel),           // viga: una por cara lateral, a cierta altura
        };
    }

    /* ─── Armadura principal ──────────────────────────────────
       Cada lista es [{ cantidad, diametro (mm) }, ...]. Se toman solo
       los grupos completos, ordenados de mayor a menor diámetro. */
    function leerBarras(lista) {
        return (lista || [])
            .map(b => ({ cantidad: parseInt(b.cantidad, 10), diametro: leerMedida(b.diametro) }))
            .filter(b => b.cantidad > 0 && b.diametro !== null)
            .sort((a, b) => b.diametro - a.diametro);
    }

    // Camadas: [{ cantidad, diametro (mm), altura (cm desde abajo) }].
    // Las filas completas con la misma altura forman una camada. Devuelve
    // [{ altura, grupos: [{ cantidad, diametro }] }], de abajo hacia arriba.
    function leerCamadas(lista) {
        const porAltura = new Map();
        (lista || []).forEach(b => {
            const altura = leerMedida(b.altura);
            const [grupo] = leerBarras([b]);
            if (altura === null || ! grupo) return;
            if (! porAltura.has(altura)) porAltura.set(altura, []);
            porAltura.get(altura).push(grupo);
        });
        return [...porAltura.entries()]
            .map(([altura, grupos]) => ({ altura, grupos: grupos.sort((a, b) => b.diametro - a.diametro) }))
            .sort((a, b) => a.altura - b.altura);
    }

    // Armadura de piel: [{ diametro (mm), altura (cm desde abajo) }],
    // solo las filas completas, de abajo hacia arriba.
    function leerPiel(lista) {
        return (lista || [])
            .map(b => ({ diametro: leerMedida(b.diametro), altura: leerMedida(b.altura) }))
            .filter(b => b.diametro !== null && b.altura !== null)
            .sort((a, b) => a.altura - b.altura);
    }

    // Reparte "total" lugares entre los grupos según sus cantidades,
    // intercalándolos de forma pareja (no todas las de un tipo juntas).
    // Devuelve el índice de grupo de cada lugar.
    function intercalar(cantidades) {
        const total = cantidades.reduce((s, c) => s + c, 0);
        const usados = cantidades.map(() => 0);
        const orden = [];
        for (let k = 1; k <= total; k++) {
            let elegido = -1;
            let mayorDeficit = -Infinity;
            cantidades.forEach((c, i) => {
                if (usados[i] >= c) return;
                const deficit = (k * c) / total - usados[i];
                if (deficit > mayorDeficit) { mayorDeficit = deficit; elegido = i; }
            });
            usados[elegido]++;
            orden.push(elegido);
        }
        return orden;
    }

    // Radio con el que se dibuja una barra (en unidades del dibujo):
    // el real, con un mínimo para que se vea.
    const RADIO_MINIMO_BARRA = 1.8;

    function radioBarraDibujo(diametroMm, escala) {
        return diametroMm ? Math.max(RADIO_MINIMO_BARRA, (diametroMm / 20) * escala) : 0;
    }

    /* Distancia del borde al centro de una barra, en unidades del
       dibujo: cara interior del estribo + radio de la barra. Se mide
       sobre el estribo y la barra tal como se dibujan (con sus
       grosores mínimos), así la barra queda apoyada contra el estribo
       y nunca tapada por él aunque sean muy chicos. */
    function insetBarra(armadura, diametroMm, escala) {
        const datos = calcularEstribo(armadura, escala);
        const caraInterior = datos
            ? datos.distanciaEje * escala + datos.grosor / 2
            : ((armadura.estribo ?? 0) / 10) * escala;
        return caraInterior + radioBarraDibujo(diametroMm, escala);
    }

    /* Pilar rectangular:
       - Esquinas: una barra del Ø cargado en cada una de las 4.
       - Cara X (horizontales): las barras cargadas van en la cara
         superior y se replican igual en la inferior.
       - Cara Y (verticales): van en la izquierda y se replican en la
         derecha.
       En cada cara las barras quedan equiespaciadas entre las esquinas
       y, si hay varios diámetros, intercalados. */
    function posicionesBarrasRectangular(armadura, x, y, w, h, escala) {
        const resultado = [];
        const barra = (diametro, cx, cy) => ({ x: cx, y: cy, r: radioBarraDibujo(diametro, escala) });
        const inset = diametro => insetBarra(armadura, diametro, escala);

        const esquina = armadura.esquina;
        if (esquina !== null) {
            const e = inset(esquina);
            [[x + e, y + e], [x + w - e, y + e], [x + w - e, y + h - e], [x + e, y + h - e]]
                .forEach(([cx, cy]) => resultado.push(barra(esquina, cx, cy)));
        }

        // Tramo útil de cada cara: entre los centros de las barras de esquina.
        const e = inset(esquina ?? 0);
        const repartir = (desde, hasta, n, j) => desde + (j + 1) * (hasta - desde) / (n + 1);
        const ordenCara = grupos => intercalar(grupos.map(g => g.cantidad)).map(i => grupos[i].diametro);

        const caraX = ordenCara(armadura.caraX);
        caraX.forEach((diametro, j) => {
            const cx = repartir(x + e, x + w - e, caraX.length, j);
            resultado.push(barra(diametro, cx, y + inset(diametro)));
            resultado.push(barra(diametro, cx, y + h - inset(diametro)));
        });

        const caraY = ordenCara(armadura.caraY);
        caraY.forEach((diametro, j) => {
            const cy = repartir(y + e, y + h - e, caraY.length, j);
            resultado.push(barra(diametro, x + inset(diametro), cy));
            resultado.push(barra(diametro, x + w - inset(diametro), cy));
        });

        return resultado;
    }

    // Pilar circular: todas equiespaciadas, empezando por la esquina del gancho.
    function posicionesBarrasCircular(armadura, o, r, diametroCm, escala) {
        const grupos = armadura.barras;
        if (! grupos.length) return [];
        const orden = intercalar(grupos.map(g => g.cantidad));
        return orden.map((g, k) => {
            const radio = r - insetBarra(armadura, grupos[g].diametro, escala);
            const p = puntoEn(o, radio, 225 + (360 * k) / orden.length);
            return { x: p.x, y: p.y, r: radioBarraDibujo(grupos[g].diametro, escala) };
        }).filter(b => b.r > 0);
    }

    /* Viga: barras en la cara superior y en la inferior, apoyadas
       contra el estribo. En cada cara las de los extremos van en las
       esquinas y el resto queda equiespaciado entre ellas (intercalando
       diámetros). Con una sola barra, va al centro.
       "cara" es 'superior' o 'inferior'. */
    function ordenGrupos(grupos) {
        return intercalar(grupos.map(g => g.cantidad)).map(i => grupos[i].diametro);
    }

    function ordenBarrasViga(armadura, cara) {
        return ordenGrupos(armadura[cara]);
    }

    // Reparte una fila de barras a lo ancho de la viga: las de los
    // extremos contra el estribo y el resto equiespaciado. yDe(diametro)
    // da la altura del centro de cada barra.
    function filaBarrasViga(armadura, orden, x, w, escala, yDe) {
        if (! orden.length) return [];
        const inset = diametro => insetBarra(armadura, diametro, escala);
        const desde = x + inset(orden[0]);
        const hasta = x + w - inset(orden[orden.length - 1]);
        return orden.map((diametro, j) => ({
            x: orden.length === 1 ? x + w / 2 : desde + j * (hasta - desde) / (orden.length - 1),
            y: yDe(diametro),
            r: radioBarraDibujo(diametro, escala),
        }));
    }

    function posicionesBarrasViga(armadura, cara, x, y, w, h, escala) {
        const inset = diametro => insetBarra(armadura, diametro, escala);
        return filaBarrasViga(armadura, ordenBarrasViga(armadura, cara), x, w, escala,
            diametro => cara === 'superior' ? y + inset(diametro) : y + h - inset(diametro));
    }

    // Camadas: igual que la armadura principal, pero con el centro de
    // las barras a la altura cargada. Se descartan las que caen fuera
    // de la viga.
    function posicionesBarrasCamadas(armadura, x, y, w, h, escala) {
        return armadura.camadas
            .filter(c => c.altura * escala < h)
            .flatMap(c => filaBarrasViga(armadura, ordenGrupos(c.grupos), x, w, escala, () => y + h - c.altura * escala));
    }

    // Piel: una barra en cada cara lateral, apoyada en el estribo, con
    // el centro a la altura cargada. Se descartan las que caen fuera
    // de la viga.
    function posicionesBarrasPiel(armadura, x, y, w, h, escala) {
        return armadura.piel
            .filter(b => b.altura * escala < h)
            .flatMap(b => {
                const inset = insetBarra(armadura, b.diametro, escala);
                const cy = y + h - b.altura * escala;
                const r = radioBarraDibujo(b.diametro, escala);
                return [{ x: x + inset, y: cy, r }, { x: x + w - inset, y: cy, r }];
            });
    }

    /* Cotas de altura de las camadas y la piel, a la izquierda de la
       viga: una línea vertical desde la cara inferior, con una marca y
       el valor en cada nivel (todas medidas desde abajo). */
    function cotasAlturas(armadura, { x, y, w, h, escala }) {
        const alturas = [...new Set([...armadura.camadas, ...armadura.piel].map(b => b.altura))]
            .filter(a => a * escala < h)
            .sort((a, b) => a - b);
        if (! alturas.length) return '';
        const xc = x - 12;
        const yNivel = a => y + h - a * escala;
        return `
            <line x1="${xc}" y1="${y + h}" x2="${xc}" y2="${yNivel(alturas[alturas.length - 1])}" ${ESTILO_COTA}></line>
            <line x1="${xc - 5}" y1="${y + h}" x2="${x - 2}" y2="${y + h}" ${ESTILO_COTA}></line>
            ${alturas.map(a => `
                <line x1="${xc - 5}" y1="${yNivel(a)}" x2="${x - 2}" y2="${yNivel(a)}" ${ESTILO_COTA}></line>
                <text x="${xc - 8}" y="${yNivel(a) + 3.5}" text-anchor="end" ${ESTILO_TEXTO_LOSA}>${formatearMedida(a)}</text>
            `).join('')}
        `;
    }

    function dibujarBarras(barras) {
        return barras.map(b =>
            `<circle cx="${b.x}" cy="${b.y}" r="${b.r}" fill="${COLOR_ESTRIBO}"></circle>`
        ).join('');
    }

    // Radio del doblado del estribo alrededor de la barra de esquina
    // (en unidades del dibujo): radio dibujado de la barra + medio
    // grosor dibujado del estribo, así el eje del estribo la envuelve
    // con el mismo centro que la barra.
    function radioDobladoBarra(diametroBarra, armadura, escala) {
        if (! diametroBarra) return 0;
        const datos = calcularEstribo(armadura, escala);
        const medioGrosor = datos ? datos.grosor / 2 : ((armadura.estribo ?? 0) / 20) * escala;
        return radioBarraDibujo(diametroBarra, escala) + medioGrosor;
    }

    /* ─── Sección rectangular (pilar y viga) ──────────────────
       Ubica el rectángulo en proporción real dentro del viewBox.
       Si falta una medida se toma igual a la otra (cuadrado). */
    function encuadrarRectangulo(horizontal, vertical, maxAncho = MAX_ANCHO_DIBUJO) {
        const hor = horizontal ?? vertical ?? 1;
        const ver = vertical ?? horizontal ?? 1;
        const escala = Math.min(maxAncho / hor, MAX_ALTO_DIBUJO / ver);
        const w = hor * escala;
        const h = ver * escala;
        return { escala, w, h, x: (320 - w) / 2, y: (200 - h) / 2 - 6 };
    }

    /* Cotas: la medida horizontal debajo y la vertical a la derecha.
       "corrimiento" aleja la cota vertical del borde (p. ej. para
       pasar por fuera de una losa); en ese caso se agregan líneas de
       referencia hasta la cota. */
    function cotasRectangulo({ x, y, w, h }, horizontal, vertical, corrimiento = 0) {
        const xc = x + w + corrimiento;
        const referencias = corrimiento > 0 ? `
            <line x1="${xc + 2}" y1="${y}" x2="${xc + 17}" y2="${y}" ${ESTILO_COTA}></line>
            <line x1="${x + w + 2}" y1="${y + h}" x2="${xc + 17}" y2="${y + h}" ${ESTILO_COTA}></line>
        ` : '';
        const cotaHorizontal = horizontal !== null ? `
            <line x1="${x}" y1="${y + h + 12}" x2="${x + w}" y2="${y + h + 12}" ${ESTILO_COTA}></line>
            <line x1="${x}" y1="${y + h + 7}" x2="${x}" y2="${y + h + 17}" ${ESTILO_COTA}></line>
            <line x1="${x + w}" y1="${y + h + 7}" x2="${x + w}" y2="${y + h + 17}" ${ESTILO_COTA}></line>
            <text x="${x + w / 2}" y="${y + h + 28}" text-anchor="middle" ${ESTILO_TEXTO_COTA}>${formatearMedida(horizontal)}</text>
        ` : '';
        const cotaVertical = vertical !== null ? `
            ${referencias}
            <line x1="${xc + 12}" y1="${y}" x2="${xc + 12}" y2="${y + h}" ${ESTILO_COTA}></line>
            <line x1="${xc + 7}" y1="${y}" x2="${xc + 17}" y2="${y}" ${ESTILO_COTA}></line>
            <line x1="${xc + 7}" y1="${y + h}" x2="${xc + 17}" y2="${y + h}" ${ESTILO_COTA}></line>
            <text x="${xc + 22}" y="${y + h / 2 + 4}" text-anchor="start" ${ESTILO_TEXTO_COTA}>${formatearMedida(vertical)}</text>
        ` : '';
        return cotaHorizontal + cotaVertical;
    }

    /* ─── Viga ────────────────────────────────────────────────
       Sección base (horizontal) × altura (vertical), en proporción
       real. Opcionalmente con losa a uno o ambos lados: la losa se
       apoya al ras de la cara superior, su altura va a escala y el
       largo es esquemático, cortado con una línea de continuidad. */
    const LARGO_LOSA = 56;           // largo dibujado de cada losa
    const ANCHO_VIGA_CON_LOSAS = 205; // viga + losas, dejando lugar a la cota vertical
    const ESTILO_TEXTO_LOSA = 'font-size="10" font-weight="600" fill="#445060" font-family="Plus Jakarta Sans, sans-serif"';

    // Contorno de la sección en una sola figura (viga + losas), para
    // que no quede una línea entre la viga y la losa. "t" es la altura
    // de la losa en unidades del dibujo.
    function contornoViga({ x, y, w, h }, t, largoIzq, largoDer) {
        // Corte en Z a media altura del extremo libre de la losa.
        const corte = (xf, sentido) => {
            const a = Math.min(5, t * 0.35);
            const ym = y + t / 2;
            const puntos = [[xf, ym - a], [xf + a, ym - a * 0.2], [xf - a, ym + a * 0.2], [xf, ym + a]];
            return (sentido > 0 ? puntos : puntos.reverse()).map(([px, py]) => `L ${px} ${py}`).join(' ');
        };
        const xi = x - largoIzq;
        const xd = x + w + largoDer;
        return [
            `M ${xi} ${y}`,
            `L ${xd} ${y}`,
            largoDer ? `${corte(xd, 1)} L ${xd} ${y + t} L ${x + w} ${y + t}` : '',
            `L ${x + w} ${y + h}`,
            `L ${x} ${y + h}`,
            largoIzq ? `L ${x} ${y + t} L ${xi} ${y + t} ${corte(xi, -1)}` : '',
            'Z',
        ].join(' ');
    }

    // Sin medidas cargadas se dibuja una viga genérica vertical de 20×40,
    // sin estribo ni barras (hace falta la escala en cm).
    function dibujarViga(base, altura, armadura, losas) {
        const sinMedidas = base === null && altura === null;
        const largoIzq = losas.izquierda ? LARGO_LOSA : 0;
        const largoDer = losas.derecha ? LARGO_LOSA : 0;
        const r = encuadrarRectangulo(
            sinMedidas ? 20 : base,
            sinMedidas ? 40 : altura,
            Math.min(MAX_ANCHO_DIBUJO, ANCHO_VIGA_CON_LOSAS - largoIzq - largoDer)
        );
        // Se centra el conjunto viga + losas.
        r.x = (320 - (r.w + largoIzq + largoDer)) / 2 + largoIzq;

        // Sin altura cargada, la losa se dibuja a un tercio de la viga.
        const t = losas.altura !== null ? Math.min(losas.altura * r.escala, r.h) : r.h / 3;
        const rotuloLosa = xCentro => losas.altura !== null
            // Arriba de la losa: abajo quedan las cotas de la armadura de piel.
            ? `<text x="${xCentro}" y="${r.y - 6}" text-anchor="middle" ${ESTILO_TEXTO_LOSA}>h = ${formatearMedida(losas.altura)}</text>`
            : '';
        const rotulosLosas =
            (largoIzq ? rotuloLosa(r.x - largoIzq / 2) : '') +
            (largoDer ? rotuloLosa(r.x + r.w + largoDer / 2) : '');

        // Las esquinas del estribo envuelven las barras de los extremos:
        // arriba las superiores y abajo las inferiores. Si falta una de
        // las dos, se toma la otra para que las cuatro esquinas coincidan.
        const esquinaInferior = ordenBarrasViga(armadura, 'inferior')[0] ?? null;
        const esquinaSuperior = ordenBarrasViga(armadura, 'superior')[0] ?? null;
        const interior = sinMedidas ? '' : `
            ${estriboRectangular(r, armadura, esquinaSuperior ?? esquinaInferior, esquinaInferior ?? esquinaSuperior)}
            ${dibujarBarras(posicionesBarrasViga(armadura, 'superior', r.x, r.y, r.w, r.h, r.escala))}
            ${dibujarBarras(posicionesBarrasViga(armadura, 'inferior', r.x, r.y, r.w, r.h, r.escala))}
            ${dibujarBarras(posicionesBarrasCamadas(armadura, r.x, r.y, r.w, r.h, r.escala))}
            ${dibujarBarras(posicionesBarrasPiel(armadura, r.x, r.y, r.w, r.h, r.escala))}
            ${cotasAlturas(armadura, r)}
        `;

        return `<svg viewBox="0 0 320 200" aria-label="Sección de viga">
                    <path d="${contornoViga(r, t, largoIzq, largoDer)}" ${ESTILO_SECCION} stroke-linejoin="round"></path>
                    ${interior}
                    ${rotulosLosas}
                    ${cotasRectangulo(r, base, altura, largoDer)}
                </svg>`;
    }

    /* Estribo de una sección rectangular (pilar o viga), con el gancho
       en la esquina superior izquierda. Si se conoce el Ø de la barra
       de esquina, el doblado la envuelve. Las esquinas de abajo pueden
       envolver barras de otro Ø (viga: superior e inferior). */
    function estriboRectangular({ escala, x, y, w, h }, armadura, diametroEsquina, diametroEsquinaInferior = diametroEsquina) {
        let estribo = '';
        const datosEstribo = calcularEstribo(armadura, escala);
        if (datosEstribo) {
            const i = datosEstribo.distanciaEje * escala;
            const we = w - 2 * i;
            const he = h - 2 * i;
            if (we > 0 && he > 0) {
                const xe = x + i;
                const ye = y + i;
                // Radio de las esquinas ~2Ø y radio del doblado del gancho
                // ~Ø, sin pasar de una fracción del lado menor.
                const g = datosEstribo.grosor;
                // Con armadura principal, el doblado envuelve la barra de esquina.
                const radioEsquina = diametro => {
                    const rDoblado = radioDobladoBarra(diametro, armadura, escala);
                    return rDoblado
                        ? Math.min(Math.max(rDoblado, g * 0.9), Math.min(we, he) / 3)
                        : Math.min(g * 2, Math.min(we, he) / 4);
                };
                const rc = radioEsquina(diametroEsquina);            // esquina superior derecha
                const rci = radioEsquina(diametroEsquinaInferior);   // esquinas inferiores
                const rb = radioDobladoBarra(diametroEsquina, armadura, escala)
                    ? rc
                    : Math.min(g * 0.9, Math.min(we, he) / 5);
                // 12Ø siempre; solo se acorta si la pata (en diagonal) se
                // saldría por el otro lado del estribo.
                const largoPata = Math.min(datosEstribo.largoGancho, Math.max(0, Math.min(we, he) - 2 * rb) * Math.SQRT2);
                const c = { x: xe + rb, y: ye + rb };

                estribo = dibujarEstriboHueco(recorte => {
                    const inicioA = puntoEn(c, rb, 135);          // fin del doblado del extremo superior
                    const inicioB = puntoEn(c, rb, 315);          // fin del doblado del extremo izquierdo
                    const puntaA = puntaPata(inicioA, largoPata - recorte);
                    const puntaB = puntaPata(inicioB, largoPata - recorte);
                    const medio = ye + he / 2;  // unión de los dos tramos (lado izquierdo)
                    const tramoAbajo = [
                        `M ${puntaA.x} ${puntaA.y}`,
                        `L ${inicioA.x} ${inicioA.y}`,
                        `A ${rb} ${rb} 0 0 1 ${xe + rb} ${ye}`,      // doblado 135° → lado superior
                        `L ${xe + we - rc} ${ye}`,
                        `A ${rc} ${rc} 0 0 1 ${xe + we} ${ye + rc}`,
                        `L ${xe + we} ${ye + he - rci}`,
                        `A ${rci} ${rci} 0 0 1 ${xe + we - rci} ${ye + he}`,
                        `L ${xe + rci} ${ye + he}`,
                        `A ${rci} ${rci} 0 0 1 ${xe} ${ye + he - rci}`,
                        `L ${xe} ${medio}`,
                    ].join(' ');
                    // El relleno blanco arranca antes que el borde oscuro,
                    // así tapa el canto del trazo y la unión no se ve.
                    const tramoArriba = [
                        `M ${xe} ${medio + (recorte ? 2 : 1)}`,
                        `L ${xe} ${ye + rb}`,
                        `A ${rb} ${rb} 0 0 1 ${inicioB.x} ${inicioB.y}`, // doblado 135° → pata (pasa por encima)
                        `L ${puntaB.x} ${puntaB.y}`,
                    ].join(' ');
                    return [tramoAbajo, tramoArriba];
                }, datosEstribo);
            }
        }
        return estribo;
    }

    function dibujarPilarRectangular(lado, ancho, armadura) {
        const rect = encuadrarRectangulo(lado, ancho);
        const { escala, x, y, w, h } = rect;

        // El estribo solo se dibuja con medidas reales (hace falta la escala en cm).
        const estribo = lado !== null || ancho !== null ? estriboRectangular(rect, armadura, armadura.esquina) : '';

        // Las barras necesitan la escala real (medidas del pilar cargadas).
        const barras = lado !== null || ancho !== null
            ? dibujarBarras(posicionesBarrasRectangular(armadura, x, y, w, h, escala))
            : '';

        // Identificación de las caras: X arriba (horizontales), Y a la izquierda (verticales).
        const rotulosCaras = `
            <text x="${x + w / 2}" y="${y - 7}" text-anchor="middle" ${ESTILO_TEXTO_CARA}>Cara X</text>
            <text x="${x - 8}" y="${y + h / 2}" text-anchor="middle" ${ESTILO_TEXTO_CARA}
                  transform="rotate(-90 ${x - 8} ${y + h / 2})">Cara Y</text>
        `;

        return `<svg viewBox="0 0 320 200" aria-label="Sección de pilar rectangular">
                    <rect x="${x}" y="${y}" width="${w}" height="${h}" ${ESTILO_SECCION}></rect>
                    ${estribo}
                    ${barras}
                    ${cotasRectangulo(rect, lado, ancho)}
                    ${rotulosCaras}
                </svg>`;
    }

    function dibujarPilarCircular(diametro, armadura) {
        const r = MAX_ALTO_DIBUJO / 2;
        const cx = 160;
        const cy = 94;

        // Cota debajo del círculo, igual que el lado del pilar rectangular.
        const yCota = cy + r + 12;
        const cota = diametro !== null ? `
            <line x1="${cx - r}" y1="${yCota}" x2="${cx + r}" y2="${yCota}" ${ESTILO_COTA}></line>
            <line x1="${cx - r}" y1="${yCota - 5}" x2="${cx - r}" y2="${yCota + 5}" ${ESTILO_COTA}></line>
            <line x1="${cx + r}" y1="${yCota - 5}" x2="${cx + r}" y2="${yCota + 5}" ${ESTILO_COTA}></line>
            <text x="${cx}" y="${yCota + 16}" text-anchor="middle" ${ESTILO_TEXTO_COTA}>Ø ${formatearMedida(diametro)}</text>
        ` : '';

        let estribo = '';
        const datosEstribo = diametro !== null ? calcularEstribo(armadura, (2 * r) / diametro) : null;
        if (datosEstribo) {
            const rEstribo = r - datosEstribo.distanciaEje * ((2 * r) / diametro);
            if (rEstribo > 0) {
                // El doblado es un círculo chico tangente por dentro al
                // estribo en el punto de 225° (arriba a la izquierda).
                const o = { x: cx, y: cy };
                const g = datosEstribo.grosor;
                const rDoblado = radioDobladoBarra(armadura.barras[0]?.diametro, armadura, (2 * r) / diametro);
                const rb = rDoblado
                    ? Math.min(Math.max(rDoblado, g * 0.9), rEstribo / 3)
                    : Math.min(g * 0.9, rEstribo / 4);
                // 12Ø siempre; solo se acorta si la pata se saldría por el
                // otro lado del estribo.
                const largoPata = Math.min(datosEstribo.largoGancho, Math.max(0, 2 * (rEstribo - rb)));
                const c = puntoEn(o, rEstribo - rb, 225);
                const tangente = puntoEn(o, rEstribo, 225);
                const opuesto = puntoEn(o, rEstribo, 45);

                estribo = dibujarEstriboHueco(recorte => {
                    const inicioA = puntoEn(c, rb, 135);
                    const inicioB = puntoEn(c, rb, 315);
                    const puntaA = puntaPata(inicioA, largoPata - recorte);
                    const puntaB = puntaPata(inicioB, largoPata - recorte);
                    const union = puntoEn(o, rEstribo, 135);         // unión de los dos tramos
                    // Arranca un poco antes (solape); el relleno blanco, antes aún que el borde.
                    const inicioArriba = puntoEn(o, rEstribo, recorte ? 130 : 132);
                    const tramoAbajo = [
                        `M ${puntaA.x} ${puntaA.y}`,
                        `L ${inicioA.x} ${inicioA.y}`,
                        `A ${rb} ${rb} 0 0 1 ${tangente.x} ${tangente.y}`,             // doblado → aro
                        `A ${rEstribo} ${rEstribo} 0 0 1 ${opuesto.x} ${opuesto.y}`,   // media vuelta
                        `A ${rEstribo} ${rEstribo} 0 0 1 ${union.x} ${union.y}`,
                    ].join(' ');
                    const tramoArriba = [
                        `M ${inicioArriba.x} ${inicioArriba.y}`,
                        `A ${rEstribo} ${rEstribo} 0 0 1 ${tangente.x} ${tangente.y}`,
                        `A ${rb} ${rb} 0 0 1 ${inicioB.x} ${inicioB.y}`,               // doblado → pata (pasa por encima)
                        `L ${puntaB.x} ${puntaB.y}`,
                    ].join(' ');
                    return [tramoAbajo, tramoArriba];
                }, datosEstribo);
            }
        }

        const barras = diametro !== null
            ? dibujarBarras(posicionesBarrasCircular(armadura, { x: cx, y: cy }, r, diametro, (2 * r) / diametro))
            : '';

        return `<svg viewBox="0 0 320 200" aria-label="Sección de pilar circular">
                    <circle cx="${cx}" cy="${cy}" r="${r}" ${ESTILO_SECCION}></circle>
                    ${estribo}
                    ${barras}
                    ${cota}
                </svg>`;
    }

    /* ─── Losa ────────────────────────────────────────────────
       Se dibuja una porción de 1 m × 1 m en planta y su corte A-A
       (paralelo al eje X). Armaduras, cada una con Ø (mm) y separación
       (cm), en dos direcciones:
       - Dirección X: barras paralelas al eje X (separadas en Y).
       - Dirección Y: barras paralelas al eje Y (separadas en X).
       Inferior: capa de abajo X y encima Y. Negativa (superior, en
       rojo y a trazos): capa de arriba X y debajo Y. */
    const VENTANA_LOSA = 100; // cm que abarcan la planta y el corte
    const COLOR_NEGATIVA = '#c0392b';
    const ESPESOR_GENERICO_LOSA = 12;
    const ESTILO_TITULO_VISTA = 'font-size="11" font-weight="700" fill="#445060" font-family="Plus Jakarta Sans, sans-serif"';

    // Cada dirección admite varias armaduras (p. ej. Ø10 c/15 + Ø8 c/15):
    // [{ diametro (mm) o null, separacion (cm) }], solo las filas con
    // separación cargada.
    function leerMallas(lista) {
        return (lista || [])
            .map(b => ({ diametro: leerMedida(b.diametro), separacion: leerMedida(b.separacion) }))
            .filter(b => b.separacion !== null);
    }

    function leerLosa(card) {
        const d = card.dataset;
        return {
            espesor: leerMedida(d.espesor),
            recubrimientoMm: leerMedida(d.recubrimiento),
            recubrimiento: leerMedida(d.recubrimiento) !== null ? leerMedida(d.recubrimiento) / 10 : null,
            inferior: { x: leerMallas(card.losaInfX), y: leerMallas(card.losaInfY) },
            negativa: { x: leerMallas(card.losaNegX), y: leerMallas(card.losaNegY) },
            referenciaX: (d.referenciaX || '').trim(),
            referenciaY: (d.referenciaY || '').trim(),
        };
    }

    // Posiciones (cm, dentro de la ventana) de las barras de una malla.
    // Las inferiores arrancan a media separación del borde y las
    // negativas a una separación entera, así no quedan encimadas en
    // planta cuando tienen la misma separación.
    function posicionesMalla(malla, negativa) {
        if (! malla || malla.separacion < 1) return [];
        const posiciones = [];
        for (let p = negativa ? malla.separacion : malla.separacion / 2; p < VENTANA_LOSA; p += malla.separacion) {
            posiciones.push(p);
        }
        return posiciones;
    }

    // Grosor de una barra dibujada como línea y radio de una barra vista
    // de punta (sin Ø cargado, se toma 8 mm para que se vea).
    const grosorBarraLosa = (malla, escala, minimo) => Math.max(minimo, ((malla.diametro ?? 8) / 10) * escala);
    const radioBarraLosa = (malla, escala) => Math.max(RADIO_MINIMO_BARRA, ((malla.diametro ?? 8) / 20) * escala);

    /* Barras de una dirección con varias armaduras. Las que tienen la
       misma separación van juntas: cada una se corre, respecto de la
       anterior, lo que ocupan las dos (ancho(malla) = ancho dibujado de
       la barra), así se ven una al lado de la otra. Devuelve
       [{ malla, posiciones (cm), desplazamiento (unidades del dibujo) }]. */
    function barrasDireccion(mallas, negativa, ancho) {
        const ultimaPorSeparacion = new Map();
        return mallas.map(malla => {
            const w = ancho(malla);
            const anterior = ultimaPorSeparacion.get(malla.separacion);
            const desplazamiento = anterior ? anterior.desplazamiento + anterior.w / 2 + w / 2 + 0.8 : 0;
            ultimaPorSeparacion.set(malla.separacion, { desplazamiento, w });
            return { malla, posiciones: posicionesMalla(malla, negativa), desplazamiento };
        });
    }

    function dibujarLosaPlanta(losa) {
        const S = 150;
        const x0 = 110;
        const y0 = 24;
        const escala = S / VENTANA_LOSA;

        const grosor = malla => grosorBarraLosa(malla, escala, 1.2);
        const lineas = (mallas, direccion, negativa) => {
            const color = negativa ? COLOR_NEGATIVA : COLOR_ESTRIBO;
            const trazo = negativa ? 'stroke-dasharray="6 3"' : '';
            return barrasDireccion(mallas, negativa, grosor).map(({ malla, posiciones, desplazamiento }) => {
                const g = grosor(malla);
                return posiciones.map(p => {
                    if (direccion === 'x') {
                        const y = y0 + S - p * escala - desplazamiento;
                        return `<line x1="${x0}" y1="${y}" x2="${x0 + S}" y2="${y}" stroke="${color}" stroke-width="${g}" ${trazo}></line>`;
                    }
                    const x = x0 + p * escala + desplazamiento;
                    return `<line x1="${x}" y1="${y0}" x2="${x}" y2="${y0 + S}" stroke="${color}" stroke-width="${g}" ${trazo}></line>`;
                }).join('');
            }).join('');
        };

        /* Cota de la separación (de la primera armadura) de cada
           dirección: a la derecha para X, arriba para Y. Las inferiores
           se acotan entre las dos primeras barras (abajo / a la
           izquierda) y las negativas, en rojo, entre las dos últimas
           (arriba / a la derecha), así las cotas no se pisan. */
        const cotaSeparacion = (mallas, direccion, negativa) => {
            const malla = mallas[0];
            const todas = posicionesMalla(malla, negativa);
            if (todas.length < 2) return '';
            const ps = negativa ? todas.slice(-2) : todas.slice(0, 2);
            const texto = `c/${Number(malla.separacion.toFixed(2))}`;
            const estiloLinea = negativa ? `stroke="${COLOR_NEGATIVA}" stroke-width="1"` : ESTILO_COTA;
            const estiloTexto = negativa ? ESTILO_TEXTO_LOSA.replace('fill="#445060"', `fill="${COLOR_NEGATIVA}"`) : ESTILO_TEXTO_LOSA;
            if (direccion === 'x') {
                const xc = x0 + S + 8;
                const [ya, yb] = [y0 + S - ps[0] * escala, y0 + S - ps[1] * escala];
                return `
                    <line x1="${xc}" y1="${ya}" x2="${xc}" y2="${yb}" ${estiloLinea}></line>
                    <line x1="${xc - 3}" y1="${ya}" x2="${xc + 3}" y2="${ya}" ${estiloLinea}></line>
                    <line x1="${xc - 3}" y1="${yb}" x2="${xc + 3}" y2="${yb}" ${estiloLinea}></line>
                    <text x="${xc + 6}" y="${(ya + yb) / 2 + 3.5}" ${estiloTexto}>${texto}</text>`;
            }
            // La negativa va en una línea de cota más arriba que la
            // inferior, así los textos no se pisan.
            const yc = negativa ? y0 - 16 : y0 - 6;
            const [xa, xb] = [x0 + ps[0] * escala, x0 + ps[1] * escala];
            // El texto va a la derecha de la cota; si no entra (cota
            // cerca del borde derecho), a la izquierda.
            const textoY = xb + 40 > 320
                ? `<text x="${xa - 5}" y="${yc + 3.5}" text-anchor="end" ${estiloTexto}>${texto}</text>`
                : `<text x="${xb + 5}" y="${yc + 3.5}" ${estiloTexto}>${texto}</text>`;
            return `
                <line x1="${xa}" y1="${yc}" x2="${xb}" y2="${yc}" ${estiloLinea}></line>
                <line x1="${xa}" y1="${yc - 3}" x2="${xa}" y2="${yc + 3}" ${estiloLinea}></line>
                <line x1="${xb}" y1="${yc - 3}" x2="${xb}" y2="${yc + 3}" ${estiloLinea}></line>
                ${textoY}`;
        };

        // Ejes con sus referencias: X abajo (hacia la derecha) e Y a la
        // izquierda (hacia arriba).
        const flecha = 'fill="#2a6fdb"';
        const yEjeX = y0 + S + 14;
        const xEjeY = x0 - 32; // separado: entre el eje y la planta va la marca del corte
        const rotuloEje = (eje, referencia) => `${eje}${referencia ? ` · ${escaparHtml(referencia)}` : ''}`;
        const ejes = `
            <line x1="${x0}" y1="${yEjeX}" x2="${x0 + S}" y2="${yEjeX}" stroke="#2a6fdb" stroke-width="1.2"></line>
            <polygon points="${x0 + S + 6},${yEjeX} ${x0 + S - 1},${yEjeX - 3.5} ${x0 + S - 1},${yEjeX + 3.5}" ${flecha}></polygon>
            <text x="${x0 + S / 2}" y="${yEjeX + 15}" text-anchor="middle" ${ESTILO_TEXTO_CARA}>${rotuloEje('X', losa.referenciaX)}</text>
            <line x1="${xEjeY}" y1="${y0 + S}" x2="${xEjeY}" y2="${y0}" stroke="#2a6fdb" stroke-width="1.2"></line>
            <polygon points="${xEjeY},${y0 - 6} ${xEjeY - 3.5},${y0 + 1} ${xEjeY + 3.5},${y0 + 1}" ${flecha}></polygon>
            <text x="${xEjeY - 9}" y="${y0 + S / 2}" text-anchor="middle" ${ESTILO_TEXTO_CARA}
                  transform="rotate(-90 ${xEjeY - 9} ${y0 + S / 2})">${rotuloEje('Y', losa.referenciaY)}</text>
        `;

        // Línea del corte A-A (paralela a X, a media altura). Sale de la
        // planta a los dos lados y las marcas con la "A" quedan afuera,
        // así no se pierden entre las armaduras.
        const yA = y0 + S / 2;
        const salida = 22;
        const estiloA = 'font-size="12" font-weight="800" fill="#2a6fdb" font-family="Plus Jakarta Sans, sans-serif"';
        const corte = `
            <line x1="${x0 - salida}" y1="${yA}" x2="${x0 + S + salida}" y2="${yA}" stroke="#2a6fdb" stroke-width="1.2" stroke-dasharray="9 3 2 3"></line>
            <line x1="${x0 - salida}" y1="${yA}" x2="${x0 - 6}" y2="${yA}" stroke="#2a6fdb" stroke-width="2.5"></line>
            <line x1="${x0 + S + 6}" y1="${yA}" x2="${x0 + S + salida}" y2="${yA}" stroke="#2a6fdb" stroke-width="2.5"></line>
            <text x="${x0 - salida}" y="${yA - 5}" ${estiloA}>A</text>
            <text x="${x0 + S + salida}" y="${yA - 5}" text-anchor="end" ${estiloA}>A</text>
        `;

        return `<svg viewBox="0 0 320 214" aria-label="Planta de la losa">
                    <text x="8" y="14" ${ESTILO_TITULO_VISTA}>Planta</text>
                    <rect x="${x0}" y="${y0}" width="${S}" height="${S}" fill="#e9edf2" stroke="#445060" stroke-width="1.5" stroke-dasharray="6 3"></rect>
                    ${lineas(losa.inferior.y, 'y', false)}
                    ${lineas(losa.inferior.x, 'x', false)}
                    ${lineas(losa.negativa.y, 'y', true)}
                    ${lineas(losa.negativa.x, 'x', true)}
                    ${corte}
                    ${cotaSeparacion(losa.inferior.x, 'x', false)}
                    ${cotaSeparacion(losa.inferior.y, 'y', false)}
                    ${cotaSeparacion(losa.negativa.x, 'x', true)}
                    ${cotaSeparacion(losa.negativa.y, 'y', true)}
                    ${ejes}
                </svg>`;
    }

    // Corte A-A: paralelo a X. Las barras en X se ven a lo largo (una
    // línea) y las barras en Y se ven de punta (círculos). Sin espesor
    // cargado se dibuja uno genérico, sin cota.
    function dibujarLosaCorte(losa) {
        const espesor = losa.espesor ?? ESPESOR_GENERICO_LOSA;
        const escala = Math.min(2.4, 110 / espesor);
        const W = VENTANA_LOSA * escala;
        const h = espesor * escala;
        const x0 = (320 - W) / 2 - 20; // corrido a la izquierda: a la derecha va la cota
        const y0 = 28;
        const rec = (losa.recubrimiento ?? 0) * escala;

        // Capas desde cada cara hacia adentro, apoyadas una sobre otra.
        // Las barras en X de todas las armaduras se ven como una sola
        // línea (del grosor de la más gruesa); las barras en Y, como
        // círculos, las de igual separación una al lado de la otra.
        let capas = '';
        const capaLinea = (mallas, desdeAbajo, yCara, negativa) => {
            if (! mallas.length) return yCara;
            const g = Math.max(...mallas.map(m => grosorBarraLosa(m, escala, 1.5)));
            const y = desdeAbajo ? yCara - g / 2 : yCara + g / 2;
            capas += `<line x1="${x0}" y1="${y}" x2="${x0 + W}" y2="${y}" stroke="${negativa ? COLOR_NEGATIVA : COLOR_ESTRIBO}" stroke-width="${g}"></line>`;
            return desdeAbajo ? yCara - g : yCara + g;
        };
        const capaPuntos = (mallas, desdeAbajo, yCara, negativa) => {
            const radio = malla => radioBarraLosa(malla, escala);
            barrasDireccion(mallas, negativa, malla => 2 * radio(malla)).forEach(({ malla, posiciones, desplazamiento }) => {
                const r = radio(malla);
                const y = desdeAbajo ? yCara - r : yCara + r;
                capas += posiciones.map(p =>
                    `<circle cx="${x0 + p * escala + desplazamiento}" cy="${y}" r="${r}" fill="${negativa ? COLOR_NEGATIVA : COLOR_ESTRIBO}"></circle>`
                ).join('');
            });
        };

        const yAbajo = capaLinea(losa.inferior.x, true, y0 + h - rec, false);
        capaPuntos(losa.inferior.y, true, yAbajo, false);
        const yArriba = capaLinea(losa.negativa.x, false, y0 + rec, true);
        capaPuntos(losa.negativa.y, false, yArriba, true);

        // Extremos cortados (la losa sigue): línea quebrada en cada lado.
        const quiebre = xq => {
            const ym = y0 + h / 2;
            const a = Math.min(4, h / 5);
            return `<path d="M ${xq} ${y0} L ${xq} ${ym - a} L ${xq + a} ${ym - a / 3} L ${xq - a} ${ym + a / 3} L ${xq} ${ym + a} L ${xq} ${y0 + h}"
                          fill="none" stroke="#445060" stroke-width="1"></path>`;
        };

        const cota = losa.espesor !== null ? `
            <line x1="${x0 + W + 12}" y1="${y0}" x2="${x0 + W + 12}" y2="${y0 + h}" ${ESTILO_COTA}></line>
            <line x1="${x0 + W + 7}" y1="${y0}" x2="${x0 + W + 17}" y2="${y0}" ${ESTILO_COTA}></line>
            <line x1="${x0 + W + 7}" y1="${y0 + h}" x2="${x0 + W + 17}" y2="${y0 + h}" ${ESTILO_COTA}></line>
            <text x="${x0 + W + 20}" y="${y0 + h / 2 + 4}" ${ESTILO_TEXTO_COTA}>${formatearMedida(losa.espesor)}</text>
        ` : '';

        return `<svg viewBox="0 0 320 ${Math.ceil(y0 + h + 14)}" aria-label="Corte A-A de la losa">
                    <text x="8" y="14" ${ESTILO_TITULO_VISTA}>Corte A-A</text>
                    <rect x="${x0}" y="${y0}" width="${W}" height="${h}" fill="#e9edf2"></rect>
                    <line x1="${x0}" y1="${y0}" x2="${x0 + W}" y2="${y0}" stroke="#445060" stroke-width="2"></line>
                    <line x1="${x0}" y1="${y0 + h}" x2="${x0 + W}" y2="${y0 + h}" stroke="#445060" stroke-width="2"></line>
                    ${quiebre(x0)}
                    ${quiebre(x0 + W)}
                    ${capas}
                    ${cota}
                </svg>`;
    }

    function leyendaLosa(losa) {
        const texto = mallas => mallas
            .map(m => `${m.diametro !== null ? `Ø${Number(m.diametro.toFixed(2))}mm ` : ''}c/${Number(m.separacion.toFixed(2))}cm`)
            .join(' + ');
        const partes = [];
        if (losa.inferior.x.length) partes.push(`Inf. X: ${texto(losa.inferior.x)}`);
        if (losa.inferior.y.length) partes.push(`Inf. Y: ${texto(losa.inferior.y)}`);
        if (losa.negativa.x.length) partes.push(`<span style="color:${COLOR_NEGATIVA}">Neg. X: ${texto(losa.negativa.x)}</span>`);
        if (losa.negativa.y.length) partes.push(`<span style="color:${COLOR_NEGATIVA}">Neg. Y: ${texto(losa.negativa.y)}</span>`);
        if (losa.recubrimientoMm !== null) partes.push(`Rec. ${Number(losa.recubrimientoMm.toFixed(2))}mm`);
        return partes.length
            ? `<div class="lienzo-leyenda">${partes.map(p => `<div>${p}</div>`).join('')}</div>`
            : '';
    }

    function dibujarSeccion(card) {
        const d = card.dataset;
        if (d.tipo === 'viga') {
            const armadura = leerArmadura(card);
            const losas = {
                izquierda: d.losas === 'izquierda' || d.losas === 'ambas',
                derecha: d.losas === 'derecha' || d.losas === 'ambas',
                altura: leerMedida(d.alturaLosa),
            };
            return dibujarViga(leerMedida(d.base), leerMedida(d.altura), armadura, losas) + leyendaArmadura(armadura, 'viga');
        }
        if (d.tipo === 'pilar') {
            const armadura = leerArmadura(card);
            const dibujo = d.forma === 'circular'
                ? dibujarPilarCircular(leerMedida(d.diametro), armadura)
                : dibujarPilarRectangular(leerMedida(d.lado), leerMedida(d.ancho), armadura);
            return dibujo + leyendaArmadura(armadura, d.forma === 'circular' ? 'circular' : 'rectangular');
        }
        if (d.tipo === 'losa') {
            const losa = leerLosa(card);
            return dibujarLosaPlanta(losa) + dibujarLosaCorte(losa) + leyendaLosa(losa);
        }
        return `<div class="lienzo-mensaje"><i class="fas fa-hand-pointer"></i>Seleccioná el tipo de elemento para dibujarlo.</div>`;
    }

    // Leyenda debajo del dibujo, p. ej. "Est. Ø8mm c/10cm" y abajo "Rec. 1cm".
    // La separación no se ve en el corte, por eso va como texto.
    function leyendaArmadura(armadura, forma) {
        const cm = v => `${Number(v.toFixed(2))}cm`;
        const mm = v => `Ø${Number(v.toFixed(2))}mm`;
        const grupos = lista => lista.map(b => `${b.cantidad} ${mm(b.diametro)}`).join(' + ');
        const partes = [];
        if (forma === 'circular') {
            if (armadura.barras.length) partes.push(grupos(armadura.barras));
        } else if (forma === 'viga') {
            if (armadura.superior.length) partes.push(`Superior: ${grupos(armadura.superior)}`);
            if (armadura.inferior.length) partes.push(`Inferior: ${grupos(armadura.inferior)}`);
            armadura.camadas.forEach(c => partes.push(`Camada a ${cm(c.altura)}: ${grupos(c.grupos)}`));
            if (armadura.piel.length) {
                partes.push(`Piel: ${armadura.piel.map(b => `${mm(b.diametro)} a ${cm(b.altura)}`).join(' + ')} por cara`);
            }
        } else if (forma === 'rectangular') {
            if (armadura.esquina !== null) partes.push(`Esquinas: 4 ${mm(armadura.esquina)}`);
            if (armadura.caraX.length) partes.push(`Cara X: ${grupos(armadura.caraX)} por cara`);
            if (armadura.caraY.length) partes.push(`Cara Y: ${grupos(armadura.caraY)} por cara`);
        }
        if (armadura.estribo !== null || armadura.separacion !== null) {
            let texto = 'Est.';
            if (armadura.estribo !== null) texto += ` Ø${Number(armadura.estribo.toFixed(2))}mm`;
            if (armadura.separacion !== null) texto += ` c/${cm(armadura.separacion)}`;
            partes.push(texto);
        }
        if (armadura.recubrimientoMm !== null) partes.push(`Rec. ${Number(armadura.recubrimientoMm.toFixed(2))}mm`);
        // Armadura principal, estribo y recubrimiento en renglones separados.
        return partes.length
            ? `<div class="lienzo-leyenda">${partes.map(p => `<div>${p}</div>`).join('')}</div>`
            : '';
    }

    // Dimensiones para el rótulo: "(30cm x 40cm)" o "(Ø 40cm)".
    // Si falta una medida se muestra "?"; sin ninguna, no se agrega nada.
    function dimensionesRotulo(card) {
        const d = card.dataset;
        const cm = valor => valor !== null ? `${Number(valor.toFixed(2))}cm` : '?cm';

        if (d.tipo === 'pilar') {
            if (d.forma === 'circular') {
                const diametro = leerMedida(d.diametro);
                return diametro !== null ? ` (Ø ${cm(diametro)})` : '';
            }
            const lado = leerMedida(d.lado);
            const ancho = leerMedida(d.ancho);
            return lado !== null || ancho !== null ? ` (${cm(lado)} x ${cm(ancho)})` : '';
        }
        if (d.tipo === 'viga') {
            const base = leerMedida(d.base);
            const altura = leerMedida(d.altura);
            return base !== null || altura !== null ? ` (${cm(base)} x ${cm(altura)})` : '';
        }
        if (d.tipo === 'losa') {
            const espesor = leerMedida(d.espesor);
            return espesor !== null ? ` (e = ${cm(espesor)})` : '';
        }
        return '';
    }

    function escaparHtml(texto) {
        return String(texto).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    }

    // Nombre del elemento cargado (p. ej. "Pilar 1"); si no hay, el tipo elegido.
    function nombreElemento(card) {
        const propio = (card.dataset.elemento || '').trim();
        if (propio) return propio;
        return card.dataset.tipo ? TIPOS[card.dataset.tipo].nombre : '';
    }

    // Rótulo centrado arriba del dibujo: nombre (PCH1) y debajo el elemento
    // con sus dimensiones, p. ej. "Pilar 1 (30cm x 40cm)".
    function dibujarRotulo(card) {
        const nombre = `${PREFIJO_NOMBRE}${card.dataset.numero || '?'}`;
        const elemento = nombreElemento(card);
        const tipo = elemento
            ? `<div class="rotulo-tipo">${escaparHtml(elemento)}${dimensionesRotulo(card)}</div>`
            : '';
        return `<div class="lienzo-rotulo"><div class="rotulo-nombre">${nombre}</div>${tipo}</div>`;
    }

    // Subtítulo de la cabecera de la tarjeta.
    function actualizarSubtitulo(card) {
        card.querySelector('.pach-tipo-texto').textContent = nombreElemento(card) || 'Sin tipo seleccionado';
    }

    function contenidoLienzo(card) {
        return dibujarRotulo(card) + dibujarSeccion(card);
    }

    // El marco del lienzo: la capa que se escala con el zoom (cuadrícula
    // y contenido) y, afuera de ella, el botón para restablecer la vista.
    function marcoLienzo(card) {
        return `
            <div class="lienzo-zoom">${contenidoLienzo(card)}</div>
            <button type="button" class="lienzo-zoom-reset" title="Ver el área completa">
                <i class="fas fa-compress-arrows-alt"></i> Restablecer
            </button>
        `;
    }

    // Cada cambio de datos pasa por acá, así que también dispara el
    // autoguardado de la tarjeta.
    function redibujar(card) {
        card.querySelector('.lienzo-zoom').innerHTML = contenidoLienzo(card);
        programarGuardado(card);
    }

    /* ─── Zoom del lienzo ─────────────────────────────────────
       Se escala toda el área cuadriculada (dibujo, textos y
       cuadrícula) con un transform sobre la capa .lienzo-zoom; el marco
       queda fijo y recorta lo que sobra. La vista { k, tx, ty } (aumento
       y corrimiento en px) queda guardada en la tarjeta, así no se
       pierde al redibujar mientras se cargan datos; null es el área
       completa. Solo funciona con la tarjeta expandida. */
    const ZOOM_MAXIMO = 8;

    function aplicarVista(card) {
        const lienzo = card.querySelector('.pach-lienzo');
        const v = card.vista;
        lienzo.querySelector('.lienzo-zoom').style.transform =
            v ? `translate(${v.tx}px, ${v.ty}px) scale(${v.k})` : '';
        lienzo.classList.toggle('con-zoom', !! v);
    }

    // Limita el aumento y no deja mover la capa fuera del marco.
    function ajustarVista(v, ancho, alto) {
        const k = Math.min(ZOOM_MAXIMO, Math.max(1, v.k));
        if (k <= 1) return null;
        return {
            k,
            tx: Math.min(0, Math.max(ancho - ancho * k, v.tx)),
            ty: Math.min(0, Math.max(alto - alto * k, v.ty)),
        };
    }

    /* Mueve y/o escala la capa para que el punto que estaba bajo
       "antes" (coordenadas de pantalla) quede bajo "despues", con el
       aumento multiplicado por "factor". Sirve para la ruedita, el
       arrastre y el pellizco con dos dedos. */
    function moverVista(card, antes, despues, factor) {
        const lienzo = card.querySelector('.pach-lienzo');
        const capa = lienzo.querySelector('.lienzo-zoom');
        const marco = lienzo.getBoundingClientRect();
        // Origen de la capa sin transformar (dentro del borde del marco).
        const ox = marco.left + lienzo.clientLeft;
        const oy = marco.top + lienzo.clientTop;
        const v = card.vista ?? { k: 1, tx: 0, ty: 0 };
        // Punto de la capa (sin escalar) que está bajo "antes".
        const ux = (antes.x - ox - v.tx) / v.k;
        const uy = (antes.y - oy - v.ty) / v.k;
        const k = v.k * factor;
        card.vista = ajustarVista({
            k,
            tx: despues.x - ox - ux * k,
            ty: despues.y - oy - uy * k,
        }, capa.offsetWidth, capa.offsetHeight);
        aplicarVista(card);
    }

    function activarZoom(card) {
        const lienzo = card.querySelector('.pach-lienzo');
        const punteros = new Map();
        const expandida = () => card.classList.contains('expandida');

        lienzo.addEventListener('wheel', function (e) {
            if (! expandida()) return;
            e.preventDefault();
            const punto = { x: e.clientX, y: e.clientY };
            moverVista(card, punto, punto, Math.exp(-e.deltaY * 0.0015));
        }, { passive: false });

        lienzo.addEventListener('pointerdown', function (e) {
            if (! expandida() || e.target.closest('.lienzo-zoom-reset')) return;
            punteros.set(e.pointerId, { x: e.clientX, y: e.clientY });
            lienzo.setPointerCapture(e.pointerId);
            lienzo.classList.add('arrastrando');
        });

        // Con un dedo (o el mouse) se arrastra; con dos, el centro entre
        // ellos arrastra y la distancia entre ellos da el aumento.
        lienzo.addEventListener('pointermove', function (e) {
            if (! punteros.has(e.pointerId)) return;
            const previos = [...punteros.values()];
            punteros.set(e.pointerId, { x: e.clientX, y: e.clientY });
            const actuales = [...punteros.values()];
            const centro = ps => ({
                x: ps.reduce((s, p) => s + p.x, 0) / ps.length,
                y: ps.reduce((s, p) => s + p.y, 0) / ps.length,
            });
            const distancia = ps => ps.length > 1 ? Math.hypot(ps[0].x - ps[1].x, ps[0].y - ps[1].y) : 0;
            const factor = actuales.length > 1 && distancia(previos) > 0
                ? distancia(actuales) / distancia(previos)
                : 1;
            moverVista(card, centro(previos), centro(actuales), factor);
        });

        const soltar = function (e) {
            punteros.delete(e.pointerId);
            if (! punteros.size) lienzo.classList.remove('arrastrando');
        };
        lienzo.addEventListener('pointerup', soltar);
        lienzo.addEventListener('pointercancel', soltar);

        lienzo.addEventListener('click', function (e) {
            if (! e.target.closest('.lienzo-zoom-reset')) return;
            card.vista = null;
            aplicarVista(card);
        });

        lienzo.addEventListener('dblclick', function () {
            if (! expandida()) return;
            card.vista = null;
            aplicarVista(card);
        });
    }

    /* ─── Parámetros según el tipo de elemento ───────────────── */
    function campoMedida(clave, etiqueta, valor, unidad = 'cm') {
        return `
            <label class="medida-campo">
                <span>${etiqueta}</span>
                <input type="number" step="any" min="0" inputmode="decimal" class="medida-input"
                       data-medida="${clave}" value="${valor ?? ''}" placeholder="${unidad}">
            </label>
        `;
    }

    // Campo de texto libre (p. ej. las referencias de la losa). Usa
    // data-medida igual que las medidas, así se guarda y redibuja igual.
    function campoTexto(clave, etiqueta, valor, placeholder) {
        return `
            <label class="medida-campo">
                <span>${etiqueta}</span>
                <input type="text" maxlength="40" class="medida-input"
                       data-medida="${clave}" value="${escaparHtml(valor ?? '')}" placeholder="${placeholder}">
            </label>
        `;
    }

    // Recubrimiento y estribos: iguales para pilar y viga.
    function camposEstribo(d) {
        return `
            <div>
                <span class="tipo-label">Recubrimiento y estribos</span>
                <div class="medidas-grid tres">
                    ${campoMedida('recubrimiento', 'Recubrimiento (mm)', d.recubrimiento, 'mm')}
                    ${campoMedida('estribo', 'Estribo Ø (mm)', d.estribo, 'mm')}
                    ${campoMedida('separacion', 'Separación (cm)', d.separacion)}
                </div>
            </div>
        `;
    }

    /* Listas de barras. Cada fila edita los campos marcados con
       data-campo. La armadura de piel de la viga guarda Ø y altura;
       el resto, cantidad y Ø. */
    const LISTAS_PIEL = ['barrasPiel'];
    const LISTAS_CAMADA = ['barrasCamadas']; // cantidad, Ø y altura
    const LISTAS_MALLA = ['losaInfX', 'losaInfY', 'losaNegX', 'losaNegY']; // losa: Ø y separación

    function filaVacia(clave) {
        if (LISTAS_CAMADA.includes(clave)) return { cantidad: '', diametro: '', altura: '' };
        if (LISTAS_MALLA.includes(clave)) return { diametro: '', separacion: '' };
        return LISTAS_PIEL.includes(clave) ? { diametro: '', altura: '' } : { cantidad: '', diametro: '' };
    }

    function camposFilaBarra(clave, b) {
        const diametro = `
            <input type="number" min="0" step="any" inputmode="decimal" class="medida-input"
                   data-campo="diametro" value="${b.diametro}" placeholder="mm">
            <span class="barra-fila-texto">mm</span>
        `;
        const altura = `
            <span class="barra-fila-texto">a</span>
            <input type="number" min="0" step="any" inputmode="decimal" class="medida-input"
                   data-campo="altura" value="${b.altura}" placeholder="cm">
            <span class="barra-fila-texto">cm</span>
        `;
        if (LISTAS_PIEL.includes(clave)) {
            return `
                <span class="barra-fila-texto">Ø</span>
                ${diametro}
                ${altura}
            `;
        }
        if (LISTAS_MALLA.includes(clave)) {
            return `
                <span class="barra-fila-texto">Ø</span>
                ${diametro}
                <span class="barra-fila-texto">c/</span>
                <input type="number" min="0" step="any" inputmode="decimal" class="medida-input"
                       data-campo="separacion" value="${b.separacion}" placeholder="cm">
                <span class="barra-fila-texto">cm</span>
            `;
        }
        return `
            <input type="number" min="1" step="1" inputmode="numeric" class="medida-input"
                   data-campo="cantidad" value="${b.cantidad}" placeholder="Cant.">
            <span class="barra-fila-texto">de Ø</span>
            ${diametro}
            ${LISTAS_CAMADA.includes(clave) ? altura : ''}
        `;
    }

    function listaBarrasHTML(card, clave) {
        return `
            <div class="barras-lista" data-lista="${clave}">
                ${card[clave].map((b, i) => `
                    <div class="barra-fila ${LISTAS_CAMADA.includes(clave) ? 'camada' : ''}" data-indice="${i}">
                        ${camposFilaBarra(clave, b)}
                        <button type="button" class="barra-quitar" title="Quitar armadura"><i class="fas fa-trash"></i></button>
                    </div>
                `).join('')}
                <button type="button" class="barra-agregar"><i class="fas fa-plus"></i> Agregar armadura</button>
            </div>
        `;
    }

    // Losas a los costados de la viga.
    const OPCIONES_LOSA = {
        ninguna:   { nombre: 'Ninguna',   icono: 'fa-ban' },
        izquierda: { nombre: 'Izquierda', icono: 'fa-arrow-left' },
        derecha:   { nombre: 'Derecha',   icono: 'fa-arrow-right' },
        ambas:     { nombre: 'Ambas',     icono: 'fa-arrows-alt-h' },
    };

    function renderParametros(card) {
        const contenedor = card.querySelector('.pach-parametros');
        const d = card.dataset;

        if (d.tipo === 'viga') {
            const losas = d.losas || 'ninguna';
            const botonesLosa = Object.entries(OPCIONES_LOSA).map(([clave, op]) => `
                <button type="button" class="tipo-btn losa-btn ${losas === clave ? 'activo' : ''}" data-losas="${clave}">
                    <i class="fas ${op.icono}"></i> ${op.nombre}
                </button>
            `).join('');

            contenedor.innerHTML = `
                <div>
                    <span class="tipo-label">Medidas</span>
                    <div class="medidas-grid">
                        ${campoMedida('base', 'Base (cm)', d.base)}
                        ${campoMedida('altura', 'Altura (cm)', d.altura)}
                    </div>
                </div>
                <div>
                    <span class="tipo-label">Losas a los costados</span>
                    <div class="forma-opciones losa-opciones">${botonesLosa}</div>
                    ${losas !== 'ninguna' ? `
                        <div class="medidas-grid" style="grid-template-columns:minmax(0, 220px)">
                            ${campoMedida('alturaLosa', 'Altura de la losa (cm)', d.alturaLosa)}
                        </div>
                    ` : ''}
                </div>
                ${camposEstribo(d)}
                <div>
                    <span class="tipo-label">Armadura principal</span>
                    <span class="sub-label">Inferior <small>· se distribuye a lo largo de la cara de abajo</small></span>
                    ${listaBarrasHTML(card, 'barrasInferior')}
                    <span class="sub-label">Superior <small>· se distribuye a lo largo de la cara de arriba</small></span>
                    ${listaBarrasHTML(card, 'barrasSuperior')}
                </div>
                <div>
                    <span class="tipo-label">Camadas</span>
                    <span class="sub-label">Se distribuyen a lo ancho, como la principal <small>· altura al centro de la barra, medida desde abajo; las filas con la misma altura forman una camada</small></span>
                    ${listaBarrasHTML(card, 'barrasCamadas')}
                </div>
                <div>
                    <span class="tipo-label">Armadura de piel</span>
                    <span class="sub-label">Una barra por cara lateral <small>· altura al centro de la barra, medida desde abajo</small></span>
                    ${listaBarrasHTML(card, 'barrasPiel')}
                </div>
            `;
            contenedor.querySelectorAll('.losa-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    card.dataset.losas = btn.dataset.losas;
                    renderParametros(card);
                    redibujar(card);
                });
            });
            escucharListasBarras(card, contenedor);
            escucharMedidas(card, contenedor);
            return;
        }

        if (d.tipo === 'losa') {
            // Una lista por dirección: cada fila, Ø y separación. Las de
            // igual separación se dibujan juntas (p. ej. Ø10 + Ø8 c/15).
            const malla = (clave, direccion, detalle) => `
                <span class="sub-label">Dirección ${direccion} <small>· ${detalle}</small></span>
                ${listaBarrasHTML(card, clave)}
            `;
            contenedor.innerHTML = `
                <div>
                    <span class="tipo-label">Medidas</span>
                    <div class="medidas-grid">
                        ${campoMedida('espesor', 'Espesor (cm)', d.espesor)}
                        ${campoMedida('recubrimiento', 'Recubrimiento (mm)', d.recubrimiento, 'mm')}
                    </div>
                </div>
                <div>
                    <span class="tipo-label">Referencias</span>
                    <div class="medidas-grid">
                        ${campoTexto('referenciaX', 'Referencia X', d.referenciaX, 'Ej: Calle Mitre')}
                        ${campoTexto('referenciaY', 'Referencia Y', d.referenciaY, 'Ej: Calle Tango')}
                    </div>
                </div>
                <div>
                    <span class="tipo-label">Armadura inferior</span>
                    ${malla('losaInfX', 'X', 'barras paralelas al eje X')}
                    ${malla('losaInfY', 'Y', 'barras paralelas al eje Y')}
                </div>
                <div>
                    <span class="tipo-label">Armadura negativa</span>
                    ${malla('losaNegX', 'X', 'barras paralelas al eje X')}
                    ${malla('losaNegY', 'Y', 'barras paralelas al eje Y')}
                </div>
            `;
            escucharListasBarras(card, contenedor);
            escucharMedidas(card, contenedor);
            return;
        }

        if (d.tipo !== 'pilar') {
            contenedor.innerHTML = '';
            return;
        }

        const forma = d.forma || 'rectangular';
        const medidas = forma === 'circular'
            ? campoMedida('diametro', 'Diámetro (cm)', d.diametro)
            : campoMedida('lado', 'Lado · cara X (cm)', d.lado) + campoMedida('ancho', 'Ancho · cara Y (cm)', d.ancho);

        const armaduraPrincipal = forma === 'circular'
            ? listaBarrasHTML(card, 'barras')
            : `
                <div class="medidas-grid" style="grid-template-columns:minmax(0, 220px)">
                    ${campoMedida('esquina', 'Esquinas Ø (mm) · 4 barras', d.esquina, 'mm')}
                </div>
                <span class="sub-label">Cara X <small>· arriba, se replica abajo</small></span>
                ${listaBarrasHTML(card, 'barrasX')}
                <span class="sub-label">Cara Y <small>· izquierda, se replica a la derecha</small></span>
                ${listaBarrasHTML(card, 'barrasY')}
            `;

        contenedor.innerHTML = `
            <div>
                <span class="tipo-label">Sección del pilar</span>
                <div class="forma-opciones">
                    <button type="button" class="tipo-btn forma-btn ${forma === 'rectangular' ? 'activo' : ''}" data-forma="rectangular">
                        <i class="far fa-square"></i> Rectangular
                    </button>
                    <button type="button" class="tipo-btn forma-btn ${forma === 'circular' ? 'activo' : ''}" data-forma="circular">
                        <i class="far fa-circle"></i> Circular
                    </button>
                </div>
            </div>
            <div>
                <span class="tipo-label">Medidas</span>
                <div class="medidas-grid" ${forma === 'circular' ? 'style="grid-template-columns:minmax(0, 220px)"' : ''}>${medidas}</div>
            </div>
            ${camposEstribo(d)}
            <div>
                <span class="tipo-label">Armadura principal</span>
                ${armaduraPrincipal}
            </div>
        `;

        escucharListasBarras(card, contenedor);

        contenedor.querySelectorAll('.forma-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                card.dataset.forma = btn.dataset.forma;
                renderParametros(card);
                redibujar(card);
            });
        });

        escucharMedidas(card, contenedor);
    }

    /* Listas de barras: pilar circular "barras"; pilar rectangular
       "barrasX" y "barrasY"; viga "barrasSuperior", "barrasInferior",
       "barrasCamadas" y "barrasPiel". */
    function escucharListasBarras(card, contenedor) {
        contenedor.querySelectorAll('.barras-lista').forEach(function (lista) {
            const clave = lista.dataset.lista;

            lista.querySelectorAll('.barra-fila').forEach(function (fila) {
                const indice = parseInt(fila.dataset.indice, 10);
                fila.querySelectorAll('[data-campo]').forEach(function (input) {
                    input.addEventListener('input', function () {
                        card[clave][indice][input.dataset.campo] = input.value;
                        redibujar(card);
                    });
                });
                fila.querySelector('.barra-quitar').addEventListener('click', function () {
                    card[clave].splice(indice, 1);
                    if (! card[clave].length) card[clave].push(filaVacia(clave));
                    renderParametros(card);
                    redibujar(card);
                });
            });

            lista.querySelector('.barra-agregar').addEventListener('click', function () {
                card[clave].push(filaVacia(clave));
                renderParametros(card);
                const filas = contenedor.querySelectorAll(`.barras-lista[data-lista="${clave}"] .barra-fila`);
                filas[filas.length - 1].querySelector('[data-campo]').focus();
            });
        });
    }

    // El dibujo se actualiza en vivo mientras se escriben las medidas.
    function escucharMedidas(card, contenedor) {
        contenedor.querySelectorAll('.medida-input[data-medida]').forEach(function (input) {
            input.addEventListener('input', function () {
                card.dataset[input.dataset.medida] = input.value;
                redibujar(card);
            });
        });
    }

    /* ─── Nombre: "PCH" + número editable ────────────────────
       Marca en rojo las tarjetas cuyo nombre se repite. */
    const PREFIJO_NOMBRE = 'PCH';

    function validarNombres() {
        const cards = Array.from(grilla.querySelectorAll('.pach-card'));
        const conteo = {};
        cards.forEach(c => {
            if (c.dataset.numero) conteo[c.dataset.numero] = (conteo[c.dataset.numero] || 0) + 1;
        });
        cards.forEach(c => {
            const duplicado = !! c.dataset.numero && conteo[c.dataset.numero] > 1;
            c.querySelector('.nombre-campo').classList.toggle('duplicado', duplicado);
            c.querySelector('.nombre-aviso').hidden = ! duplicado;
        });
    }

    /* ─── Numeración ──────────────────────────────────────────
       Igual que en las planillas: cada tarjeta recibe el primer
       número libre y no se renumera al eliminar otras. */
    function obtenerSiguienteIdx() {
        // Se evitan tanto los números de tarjeta como los nombres ya
        // editados a mano, para que el nombre sugerido no quede repetido.
        const usados = Array.from(grilla.querySelectorAll('.pach-card'))
            .flatMap(card => [parseInt(card.dataset.idx, 10), parseInt(card.dataset.numero, 10)]);
        let idx = 1;
        while (usados.includes(idx)) idx++;
        return idx;
    }

    function insertarOrdenada(card) {
        const idx = parseInt(card.dataset.idx, 10);
        const siguiente = Array.from(grilla.querySelectorAll('.pach-card'))
            .find(c => parseInt(c.dataset.idx, 10) > idx);
        grilla.insertBefore(card, siguiente || btnAgregar);
    }

    /* Sin argumento crea una tarjeta nueva. Con "guardada" ({ id, datos })
       la reconstruye con lo que vino de la base: los campos van al
       dataset y las listas de barras a sus propiedades. */
    function crearTarjeta(guardada = null) {
        const card = document.createElement('div');
        card.className = 'pach-card';
        const campos = guardada?.datos?.campos ?? {};
        const listas = guardada?.datos?.listas ?? {};
        Object.entries(campos).forEach(([clave, valor]) => {
            if (clave !== 'id' && valor !== null) card.dataset[clave] = valor;
        });
        if (guardada) card.dataset.id = guardada.id;
        const idx = parseInt(card.dataset.idx, 10) || obtenerSiguienteIdx();
        card.dataset.idx = idx;
        if (card.dataset.numero === undefined) card.dataset.numero = idx;
        // Los campos vacíos llegan como null (Laravel convierte "" en null):
        // se vuelven a dejar como texto vacío para los inputs.
        const sinNulos = fila => Object.fromEntries(Object.entries(fila ?? {}).map(([k, v]) => [k, v ?? '']));
        LISTAS_GUARDADAS.forEach(clave => {
            card[clave] = Array.isArray(listas[clave]) && listas[clave].length
                ? listas[clave].map(fila => ({ ...filaVacia(clave), ...sinNulos(fila) }))
                : [filaVacia(clave)];
        });

        const botonesTipo = Object.entries(TIPOS).map(([clave, tipo]) => `
            <button type="button" class="tipo-btn" data-tipo="${clave}">
                <i class="fas ${tipo.icono}"></i> ${tipo.nombre}
            </button>
        `).join('');

        card.innerHTML = `
            <div class="pach-head">
                <div class="pach-badge">${PREFIJO_NOMBRE}${escaparHtml(card.dataset.numero || '?')}</div>
                <div>
                    <div class="pach-head-title">Pachometría</div>
                    <div class="pach-head-sub pach-tipo-texto">Sin tipo seleccionado</div>
                </div>
                <button type="button" class="pach-cerrar-btn" title="Contraer"><i class="fas fa-compress"></i></button>
            </div>
            <div class="pach-body">
                <div class="pach-lienzo">${marcoLienzo(card)}</div>
                <aside class="pach-panel">
                    <div>
                        <span class="tipo-label">Nombre</span>
                        <label class="nombre-campo">
                            <span class="nombre-prefijo">${PREFIJO_NOMBRE}</span>
                            <input type="number" min="1" step="1" inputmode="numeric" class="nombre-input" value="${escaparHtml(card.dataset.numero)}" placeholder="N°">
                        </label>
                        <div class="nombre-aviso" hidden>Ya existe otra pachometría con este nombre.</div>
                    </div>
                    <div>
                        <span class="tipo-label">Tipo de elemento</span>
                        <div class="tipo-opciones">${botonesTipo}</div>
                    </div>
                    <div>
                        <span class="tipo-label">Nombre del elemento</span>
                        <input type="text" class="medida-input elemento-input" maxlength="60"
                               value="${escaparHtml(card.dataset.elemento ?? '')}"
                               placeholder="Ej: ${card.dataset.tipo ? TIPOS[card.dataset.tipo].nombre : 'Pilar'} 1" style="max-width:320px; margin-top:0.4rem;">
                    </div>
                    <div class="pach-parametros"></div>
                    <div class="pach-panel-acciones">
                        <button type="button" class="pach-delete-btn" ${PUEDE_ELIMINAR ? '' : 'hidden'}><i class="fas fa-trash"></i> Eliminar pachometría</button>
                    </div>
                </aside>
            </div>
        `;

        activarZoom(card);

        card.addEventListener('click', function () {
            if (! card.classList.contains('expandida')) expandir(card);
        });

        card.querySelector('.pach-cerrar-btn').addEventListener('click', function (e) {
            e.stopPropagation();
            contraerTodas();
        });

        card.querySelector('.nombre-input').addEventListener('input', function () {
            card.dataset.numero = this.value.trim();
            card.querySelector('.pach-badge').textContent = `${PREFIJO_NOMBRE}${card.dataset.numero || '?'}`;
            redibujar(card);
            validarNombres();
        });

        card.querySelector('.elemento-input').addEventListener('input', function () {
            card.dataset.elemento = this.value;
            actualizarSubtitulo(card);
            redibujar(card);
        });

        const botonesTipoEl = card.querySelectorAll('.tipo-opciones .tipo-btn');
        botonesTipoEl.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const tipo = btn.dataset.tipo;
                card.dataset.tipo = tipo;
                botonesTipoEl.forEach(b => b.classList.toggle('activo', b === btn));
                card.querySelector('.elemento-input').placeholder = `Ej: ${TIPOS[tipo].nombre} 1`;
                actualizarSubtitulo(card);
                renderParametros(card);
                redibujar(card);
            });
        });

        card.querySelector('.pach-delete-btn').addEventListener('click', function () {
            abrirModalEliminar(card);
        });

        // Tarjeta reconstruida: se marca el tipo elegido y se arma su panel.
        if (card.dataset.tipo && TIPOS[card.dataset.tipo]) {
            botonesTipoEl.forEach(b => b.classList.toggle('activo', b.dataset.tipo === card.dataset.tipo));
            actualizarSubtitulo(card);
            renderParametros(card);
        }

        insertarOrdenada(card);
        validarNombres();
        return card;
    }

    /* ─── Eliminar (con confirmación en un modal) ─────────────
       Se cierra con Cancelar, la cruz, un clic en el fondo o Esc. */
    const modalEliminar = document.getElementById('modal-eliminar-pachometria');
    let tarjetaAEliminar = null;

    function abrirModalEliminar(card) {
        if (! modalEliminar) return;
        tarjetaAEliminar = card;
        document.getElementById('eliminar-pachometria-nombre').textContent = `${PREFIJO_NOMBRE}${card.dataset.numero || '?'}`;
        modalEliminar.classList.add('active');
    }

    function cerrarModalEliminar() {
        modalEliminar?.classList.remove('active');
        tarjetaAEliminar = null;
    }

    function eliminarConfirmado() {
        if (! tarjetaAEliminar) return;
        eliminarEnServidor(tarjetaAEliminar);
        tarjetaAEliminar.remove();
        validarNombres();
        cerrarModalEliminar();
    }

    if (modalEliminar) {
        document.getElementById('modal-eliminar-cerrar').addEventListener('click', cerrarModalEliminar);
        document.getElementById('modal-eliminar-cancelar').addEventListener('click', cerrarModalEliminar);
        document.getElementById('modal-eliminar-confirmar').addEventListener('click', eliminarConfirmado);
        modalEliminar.addEventListener('click', function (e) {
            if (e.target === modalEliminar) cerrarModalEliminar();
        });
    }

    /* ─── Expandir / contraer ─────────────────────────────────
       Solo una tarjeta expandida a la vez. Se contrae con el botón
       de la cabecera, haciendo clic fuera de las tarjetas o con Esc. */
    function contraerTodas() {
        // Al contraer se vuelve a ver la sección completa.
        grilla.querySelectorAll('.pach-card.expandida').forEach(c => {
            c.classList.remove('expandida');
            c.vista = null;
            aplicarVista(c);
        });
    }

    function expandir(card) {
        contraerTodas();
        card.classList.add('expandida');
        card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Se usa composedPath() y no e.target.closest(): algunos botones del
    // panel (p. ej. la forma del pilar) se regeneran al hacer clic y, para
    // cuando llega acá, ya no están dentro de la tarjeta.
    // Los clics en el modal de eliminar tampoco contraen: si se cancela,
    // la tarjeta sigue abierta.
    document.addEventListener('click', function (e) {
        const dentroDeTarjeta = e.composedPath().some(el =>
            el.classList && (el.classList.contains('pach-card') || el.id === 'btn-agregar-pachometria' || el === modalEliminar)
        );
        if (! dentroDeTarjeta) contraerTodas();
    });

    // Esc cierra primero el modal (si está abierto) y si no, contrae.
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        if (modalEliminar?.classList.contains('active')) cerrarModalEliminar();
        else contraerTodas();
    });

    /* ─── Guardado en la base ─────────────────────────────────
       Cada pachometría es una fila (PachometriaTc). Al agregar una
       tarjeta se crea en el servidor (store) y, con cada modificación,
       se guarda sola unos instantes después (update), sin botón. Se
       manda la tarjeta completa: los campos del dataset y las listas
       de barras. */
    const CSRF_TOKEN = @json(csrf_token());
    const URL_PACHOMETRIAS = @json(route('pachometria_tc.store', $obraTc->id));
    const PACHOMETRIAS_GUARDADAS = @json($pachometrias);
    const PUEDE_AGREGAR = @json($puedeAgregar);
    const PUEDE_EDITAR = @json($puedeEditar);
    const PUEDE_ELIMINAR = @json($puedeEliminar);
    const DEMORA_GUARDADO_MS = 800;
    const LISTAS_GUARDADAS = [
        'barras', 'barrasX', 'barrasY',
        'barrasSuperior', 'barrasInferior', 'barrasCamadas', 'barrasPiel',
        ...LISTAS_MALLA,
    ];

    const urlPachometria = id => `${URL_PACHOMETRIAS}/${id}`;

    function datosTarjeta(card) {
        const campos = { ...card.dataset };
        delete campos.id;
        const listas = {};
        LISTAS_GUARDADAS.forEach(clave => { listas[clave] = card[clave] ?? []; });
        return { campos, listas };
    }

    function pedir(url, metodo, cuerpo, keepalive = false) {
        return fetch(url, {
            method: metodo,
            keepalive,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: cuerpo === undefined ? undefined : JSON.stringify(cuerpo),
        }).then(respuesta => {
            if (! respuesta.ok) throw new Error(`Error ${respuesta.status}`);
            return respuesta.status === 204 ? null : respuesta.json();
        });
    }

    // Indicador de la cabecera: "Cambios sin guardar", "Guardando...",
    // "Guardado" o "Error al guardar", según el estado de todas las tarjetas.
    const elEstadoGuardado = document.getElementById('estado-guardado');
    const ESTADOS_GUARDADO = {
        pendiente: { icono: 'fa-circle-notch', texto: 'Cambios sin guardar' },
        guardando: { icono: 'fa-circle-notch fa-spin', texto: 'Guardando...' },
        guardado: { icono: 'fa-check', texto: 'Guardado' },
        error: { icono: 'fa-exclamation-triangle', texto: 'Error al guardar' },
    };
    const tarjetasPendientes = new Set();  // con cambios esperando la demora
    let pedidosEnCurso = 0;
    let huboError = false;

    function actualizarEstadoGuardado() {
        if (! elEstadoGuardado) return;
        const estado = huboError ? 'error'
            : pedidosEnCurso ? 'guardando'
            : tarjetasPendientes.size ? 'pendiente'
            : 'guardado';
        elEstadoGuardado.className = `estado-guardado ${estado}`;
        elEstadoGuardado.querySelector('i').className = `fas ${ESTADOS_GUARDADO[estado].icono}`;
        document.getElementById('estado-guardado-texto').textContent = ESTADOS_GUARDADO[estado].texto;
    }

    // Envuelve cada pedido para llevar la cuenta de los que están en curso.
    async function conEstado(promesa) {
        pedidosEnCurso++;
        actualizarEstadoGuardado();
        try {
            const resultado = await promesa;
            huboError = false;
            return resultado;
        } catch (error) {
            huboError = true;
            throw error;
        } finally {
            pedidosEnCurso--;
            actualizarEstadoGuardado();
        }
    }

    // Tarjeta nueva: se crea en el servidor para tener su id. Los cambios
    // que se hagan mientras tanto esperan a esta promesa.
    function crearEnServidor(card) {
        card.creacion = conEstado(pedir(URL_PACHOMETRIAS, 'POST', { datos: datosTarjeta(card) }))
            .then(({ id }) => { card.dataset.id = id; })
            .catch(error => console.error('No se pudo crear la pachometría', error));
        return card.creacion;
    }

    function programarGuardado(card) {
        if (! PUEDE_EDITAR) return;
        tarjetasPendientes.add(card);
        actualizarEstadoGuardado();
        clearTimeout(card.temporizadorGuardado);
        card.temporizadorGuardado = setTimeout(() => guardarTarjeta(card), DEMORA_GUARDADO_MS);
    }

    // Una tarjeta nunca manda dos guardados a la vez: si cambia mientras
    // se guarda, se vuelve a guardar al terminar (con los datos nuevos).
    async function guardarTarjeta(card) {
        clearTimeout(card.temporizadorGuardado);
        if (card.guardando) {
            card.guardarDeNuevo = true;
            return;
        }
        tarjetasPendientes.delete(card);
        card.guardando = true;
        try {
            if (card.creacion) await card.creacion;
            if (! card.dataset.id) throw new Error('La pachometría no se pudo crear en el servidor');
            await conEstado(pedir(urlPachometria(card.dataset.id), 'PATCH', { datos: datosTarjeta(card) }));
        } catch (error) {
            console.error(error);
            huboError = true;
            actualizarEstadoGuardado();
        } finally {
            card.guardando = false;
            if (card.guardarDeNuevo) {
                card.guardarDeNuevo = false;
                guardarTarjeta(card);
            }
        }
    }

    async function eliminarEnServidor(card) {
        clearTimeout(card.temporizadorGuardado);
        tarjetasPendientes.delete(card);
        actualizarEstadoGuardado();
        if (card.creacion) await card.creacion;
        if (! card.dataset.id) return;
        try {
            await conEstado(pedir(urlPachometria(card.dataset.id), 'DELETE'));
        } catch (error) {
            console.error('No se pudo eliminar la pachometría', error);
        }
    }

    // Si se sale de la página con cambios esperando la demora, se mandan
    // igual (keepalive deja terminar el pedido aunque la página se cierre).
    window.addEventListener('pagehide', function () {
        tarjetasPendientes.forEach(card => {
            if (! card.dataset.id) return;
            clearTimeout(card.temporizadorGuardado);
            pedir(urlPachometria(card.dataset.id), 'PATCH', { datos: datosTarjeta(card) }, true).catch(() => {});
        });
    });
    window.addEventListener('beforeunload', function (e) {
        if (pedidosEnCurso || [...tarjetasPendientes].some(card => ! card.dataset.id)) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Las pachometrías guardadas se reconstruyen al abrir la página.
    PACHOMETRIAS_GUARDADAS.forEach(guardada => crearTarjeta(guardada));

    // Una tarjeta nueva se abre expandida para elegir el tipo enseguida.
    btnAgregar.addEventListener('click', function () {
        const card = crearTarjeta();
        crearEnServidor(card);
        expandir(card);
    });

    /* ─── Exportar a PDF ──────────────────────────────────────
       Todas las pachometrías con tipo elegido, en orden, en un A4
       vertical en blanco (sin encabezado). Cada detalle es igual que en
       pantalla: rótulo (PCH y elemento), dibujos (vectoriales, con
       svg2pdf) y leyenda. Los detalles se ubican uno al lado del otro
       mientras entren en el ancho; si no, pasan al renglón siguiente,
       y si el renglón no entra, a la hoja siguiente. Las librerías se
       cargan recién al exportar, para no sumar peso a la página. */
    const OBRA_DESCRIPCION = @json($obraTc->descripcion ?? '');
    const LIBRERIAS_PDF = [
        'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',
        'https://cdn.jsdelivr.net/npm/svg2pdf.js@2.2.3/dist/svg2pdf.umd.min.js',
    ];

    function cargarScript(url) {
        return new Promise((resolve, reject) => {
            if (document.querySelector(`script[src="${url}"]`)) return resolve();
            const s = document.createElement('script');
            s.src = url;
            s.onload = resolve;
            s.onerror = () => reject(new Error(`No se pudo cargar ${url}`));
            document.head.appendChild(s);
        });
    }

    async function exportarPdf() {
        const cards = Array.from(grilla.querySelectorAll('.pach-card')).filter(c => c.dataset.tipo);
        if (! cards.length) {
            alert('No hay pachometrías con tipo de elemento para exportar.');
            return;
        }

        for (const url of LIBRERIAS_PDF) await cargarScript(url);
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ unit: 'mm', format: 'a4' });

        const ANCHO_PAGINA = 210;
        const ALTO_PAGINA = 297;
        const MARGEN = 12;
        const ANCHO_DETALLE = 88;   // 2 por renglón en A4
        const SEPARACION = 6;       // entre detalles, horizontal y vertical
        const RENGLON = 4;

        // Los dibujos se generan de nuevo (sin el zoom que tenga la
        // tarjeta) en un contenedor fuera de pantalla: svg2pdf necesita
        // que estén en el documento para medir los textos.
        const temporal = document.createElement('div');
        temporal.style.cssText = 'position:absolute; left:-10000px; top:0; width:320px;';
        document.body.appendChild(temporal);

        try {
            let x = MARGEN;
            let y = MARGEN;
            let altoRenglon = 0;

            for (const card of cards) {
                temporal.innerHTML = dibujarSeccion(card);
                const svgs = Array.from(temporal.querySelectorAll('svg'));
                const leyenda = Array.from(temporal.querySelectorAll('.lienzo-leyenda > div'));

                const medidas = svgs.map(svg => {
                    const [, , w, h] = svg.getAttribute('viewBox').split(/\s+/).map(Number);
                    return { svg, alto: ANCHO_DETALLE * h / w };
                });
                const altoDetalle = 9 + medidas.reduce((s, m) => s + m.alto + 1, 0) + leyenda.length * RENGLON;

                // No entra al lado: renglón siguiente. No entra el renglón: hoja siguiente.
                if (x + ANCHO_DETALLE > ANCHO_PAGINA - MARGEN + 0.01) {
                    x = MARGEN;
                    y += altoRenglon + SEPARACION;
                    altoRenglon = 0;
                }
                if (y + altoDetalle > ALTO_PAGINA - MARGEN && y > MARGEN) {
                    doc.addPage();
                    x = MARGEN;
                    y = MARGEN;
                    altoRenglon = 0;
                }

                const centro = x + ANCHO_DETALLE / 2;
                let yd = y + 4;

                // Rótulo: "PCH1" y el elemento con sus dimensiones.
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(11);
                doc.setTextColor('#1e2835');
                doc.text(`${PREFIJO_NOMBRE}${card.dataset.numero || '?'}`, centro, yd, { align: 'center' });
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(8.5);
                doc.setTextColor('#6b7a8c');
                doc.text(`${nombreElemento(card)}${dimensionesRotulo(card)}`, centro, yd + 4.5, { align: 'center' });
                yd += 5;

                for (const { svg, alto } of medidas) {
                    const opciones = { x, y: yd, width: ANCHO_DETALLE, height: alto };
                    if (typeof doc.svg === 'function') await doc.svg(svg, opciones);
                    else await window.svg2pdf.svg2pdf(svg, doc, opciones);
                    yd += alto + 1;
                }

                doc.setFontSize(8);
                leyenda.forEach(renglon => {
                    // Las negativas de la losa van en rojo, como en pantalla.
                    doc.setTextColor(renglon.querySelector('span') ? COLOR_NEGATIVA : '#445060');
                    doc.text(renglon.textContent, centro, yd + 3, { align: 'center' });
                    yd += RENGLON;
                });

                x += ANCHO_DETALLE + SEPARACION;
                altoRenglon = Math.max(altoRenglon, altoDetalle);
            }
        } finally {
            temporal.remove();
        }

        const nombreArchivo = `Pachometrias ${OBRA_DESCRIPCION}`.trim().replace(/[\\/:*?"<>|]+/g, '-');
        doc.save(`${nombreArchivo}.pdf`);
    }

    const btnExportarPdf = document.getElementById('btn-exportar-pdf');
    btnExportarPdf.addEventListener('click', async function () {
        const textoOriginal = btnExportarPdf.innerHTML;
        btnExportarPdf.disabled = true;
        btnExportarPdf.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando…';
        try {
            await exportarPdf();
        } catch (error) {
            console.error(error);
            alert('No se pudo generar el PDF. Revisá la conexión e intentá de nuevo.');
        } finally {
            btnExportarPdf.disabled = false;
            btnExportarPdf.innerHTML = textoOriginal;
        }
    });
</script>
</body>
</html>
