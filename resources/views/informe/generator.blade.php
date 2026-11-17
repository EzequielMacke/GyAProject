<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Informe</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=EB+Garamond:wght@400;700&display=swap" rel="stylesheet">
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
        }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

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
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; margin: 0; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        .btn {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface); color: var(--text2);
            text-decoration: none; cursor: pointer;
            transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover { background: #1f5bbf; border-color: #1f5bbf; color: #fff; }

        /* Editor: contenido de la parte a la izquierda, partes del informe a la derecha */
        .editor { display: flex; gap: 1.25rem; align-items: flex-start; }
        .editor-main { flex: 1; min-width: 0; }
        .editor-side { width: 260px; flex-shrink: 0; position: sticky; top: 1rem; }

        .card-box {
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.75rem; overflow: hidden;
        }
        .card-box-head {
            padding: 0.85rem 1.1rem; border-bottom: 1.5px solid var(--border);
            font-size: 0.72rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.6px;
        }

        .partes { list-style: none; margin: 0; padding: 0.5rem; }
        .parte {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.6rem 0.75rem; border-radius: 0.5rem;
            font-size: 0.85rem; font-weight: 600; color: var(--text2);
            cursor: pointer; transition: all 0.14s;
        }
        .parte:hover { background: var(--surface2); color: var(--text); }
        .parte.activa { background: var(--accent-s); color: var(--accent); }
        .parte[hidden] { display: none; }
        .subparte { padding-left: 1.9rem; font-size: 0.8rem; font-weight: 500; }
        .subparte-2 { padding-left: 3.1rem; }

        /* Botones de Trabajos desarrollados: apagados por defecto */
        .trabajos-toggles { display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .trabajo-toggle { margin: 0; cursor: pointer; }
        .trabajo-toggle input { position: absolute; opacity: 0; pointer-events: none; }
        .trabajo-toggle span {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.45rem 0.8rem; border: 1.5px solid var(--border); border-radius: 999px;
            font-size: 0.8rem; font-weight: 600; color: var(--text2); background: var(--surface);
            transition: all 0.14s; user-select: none;
        }
        .trabajo-toggle span i { display: none; }
        .trabajo-toggle:hover span { border-color: var(--accent); }
        .trabajo-toggle input:checked + span { background: var(--accent); border-color: var(--accent); color: #fff; }
        .trabajo-toggle input:checked + span i { display: inline; }
        .trabajo-toggle input:focus-visible + span { box-shadow: 0 0 0 3px var(--accent-s); }
        .grupo + .grupo { margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border); }
        .grupo-titulo { font-size: 0.85rem; font-weight: 700; color: var(--text); margin-bottom: 0.75rem; }
        .documento {
            border: 1px solid var(--border); border-radius: 0.5rem;
            padding: 0.85rem; margin-bottom: 0.75rem;
        }
        .documento .btn-quitar { margin-top: 0.6rem; color: var(--muted); }

        .panel { display: none; }
        .panel.activo { display: block; }
        .panel-body { padding: 1.25rem 1.1rem; }

        .vacio { padding: 3rem 1rem; text-align: center; color: var(--muted); font-size: 0.85rem; }
        .vacio i { font-size: 1.8rem; display: block; margin-bottom: 0.75rem; opacity: 0.6; }

        .campo label { font-size: 0.78rem; font-weight: 600; color: var(--text2); margin-bottom: 0.35rem; display: block; }
        .campo .form-control, .campo .form-select {
            font-size: 0.85rem; border: 1.5px solid var(--border); border-radius: 0.5rem;
        }
        .campo .opcional { font-weight: 400; color: var(--muted); }
        .campo .ayuda { display: block; margin-top: 0.35rem; font-size: 0.74rem; color: var(--muted); }
        .campo .form-control::placeholder { color: #b0bccb; }
        .campo .form-control:focus, .campo .form-select:focus {
            border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-s);
        }

        .vista-previa {
            margin-top: 1.25rem; padding: 1rem 1.1rem;
            background: #fff; border: 1.5px dashed var(--border2); border-radius: 0.6rem;
        }
        .vista-previa-head {
            font-size: 0.72rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 0.6rem;
        }
        .vista-previa p { margin: 0; font-size: 0.9rem; line-height: 1.6; color: var(--text); text-align: justify; }
        /* Lo que falta cargar se ve resaltado, para saber qué completar */
        .vista-previa .falta { background: #fff4d6; color: #a07400; border-radius: 0.25rem; padding: 0 0.25rem; }

        /* Vista previa del informe completo (panel inicial) */
        .vp-ayuda { padding: 0.75rem 1.1rem 0; font-size: 0.78rem; color: var(--muted); }
        .vp-documento { padding: 1rem 1.1rem 1.5rem; background: var(--surface2); display: flex; flex-direction: column; align-items: center; gap: 1.25rem; }
        .vp-hoja {
            width: 100%; max-width: 820px; min-height: 420px; background: #fff; color: #000;
            box-shadow: 0 2px 10px rgba(15, 23, 35, 0.12); border-radius: 2px;
            padding: 3.5rem 3.75rem;
            font-family: 'EB Garamond', Garamond, serif !important; font-size: 16px; line-height: 1.5;
        }
        .vp-hoja * { font-family: 'EB Garamond', Garamond, serif !important; }
        .vp-hoja p { margin: 0 0 0.6rem; text-align: justify; }
        .vp-parte { border-radius: 0.3rem; margin: 0 -0.5rem; padding: 0 0.5rem; cursor: pointer; transition: background 0.12s, box-shadow 0.12s; }
        .vp-parte:hover { background: #f5f8ff; box-shadow: 0 0 0 1.5px var(--accent-s); }
        .vp-t1 { font-size: 20px; font-weight: 700; text-transform: uppercase; margin: 1.4rem 0 0.7rem; }
        .vp-t2 { font-size: 17px; font-weight: 700; margin: 1.1rem 0 0.5rem; }
        .vp-t3 { font-size: 16px; font-weight: 700; font-style: italic; margin: 1rem 0 0.5rem; }
        .vp-parte:first-child > .vp-t1:first-child { margin-top: 0; }
        .vp-hoja ul { margin: 0 0 0.6rem; padding-left: 1.6rem; }
        .vp-hoja li { margin-bottom: 0.25rem; text-align: justify; }
        .vp-negrita { font-weight: 700; }
        .vp-caratula { display: flex; flex-direction: column; justify-content: space-between; min-height: 640px; text-align: center; }
        .vp-caratula .vp-car-centro { margin-top: 7rem; display: flex; flex-direction: column; gap: 0.6rem; font-size: 20px; font-weight: 700; }
        .vp-caratula .vp-car-pie { text-align: left; font-size: 17px; font-weight: 700; }
        .vp-caratula .vp-car-pie p { text-align: left; margin: 0 0 0.3rem; }
        .vp-indice-titulo { text-align: center; font-size: 20px; font-weight: 700; margin-bottom: 1rem; }
        .vp-indice div { text-transform: uppercase; font-weight: 700; padding: 0.15rem 0; }
        .vp-indice .nivel-2 { padding-left: 1.5rem; }
        .vp-figura { display: flex; flex-wrap: wrap; justify-content: center; align-items: flex-end; gap: 0.4rem; margin: 0.5rem 0 0.3rem; }
        .vp-figura img { max-width: 30%; max-height: 210px; object-fit: contain; }
        .vp-figura.sola img { max-width: 75%; max-height: 300px; }
        .vp-figura canvas { max-width: 100%; height: auto; border: 1px solid #ddd; }
        .vp-figura .letra { font-size: 14px; margin-right: 0.4rem; }
        .vp-pie { text-align: center !important; font-size: 14px; font-style: italic; margin-bottom: 0.9rem !important; }
        .vp-tabla { overflow-x: auto; display: flex; justify-content: center; margin-bottom: 0.9rem; }
        .vp-tabla table { border-collapse: collapse; font-size: 14px; }
        .vp-tabla th, .vp-tabla td { border: 1px solid #000; padding: 0.15rem 0.5rem; text-align: center; white-space: nowrap; }
        .vp-cargando { text-align: center !important; color: var(--muted); font-style: italic; font-size: 14px; }
        .vp-hoja .falta, .vp-vacio { background: #fff4d6; color: #a07400; border-radius: 0.25rem; padding: 0 0.25rem; }
        .vp-vacio { display: inline-block; font-size: 14px; font-style: italic; margin-bottom: 0.6rem; }

        /* Fotos de Características de la obra */
        .bloque-foto {
            border: 1px solid var(--border); border-radius: 0.5rem;
            padding: 0.85rem; margin-top: 0.75rem; background: #fff;
        }
        .bloque-foto-imagenes { display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; margin-bottom: 0.75rem; }
        .bloque-foto-imagenes img { height: 110px; max-width: 100%; border-radius: 0.35rem; object-fit: cover; }
        .bloque-foto-letra { align-self: flex-end; margin: 0 0.5rem 0 -0.25rem; font-size: 0.8rem; color: var(--text2); }
        .bloque-foto-pie { display: flex; gap: 0.5rem; align-items: center; }
        .bloque-foto-pie .form-control { flex: 1; }
        .bloque-foto .btn-quitar-bloque { color: var(--muted); }
        .bloque-foto-aviso { font-size: 0.78rem; color: #a07400; background: #fff4d6; border-radius: 0.35rem; padding: 0.35rem 0.6rem; margin-bottom: 0.6rem; }

        .alert { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert-success { background: #e5f6f0; color: #1e9166; border: 1px solid #b6e8d6; }
        .alert-danger  { background: #fff0f0; color: #c0392b; border: 1px solid #f5c2c2; }

        .editor-side .card-box + .card-box { margin-top: 1rem; }
        .generar-box { padding: 0.85rem 1.1rem; display: flex; flex-direction: column; gap: 0.5rem; }
        .btn-generar { width: 100%; justify-content: center; background: #1e9166; border-color: #1e9166; color: #fff; }
        .btn-generar:hover { background: #187a56; border-color: #187a56; color: #fff; }
        .generar-box .ayuda { font-size: 0.74rem; color: var(--muted); line-height: 1.35; }
        #btn-agregar-imagen { margin-top: 0.75rem; }

        .modal-fotos {
            position: fixed; inset: 0; z-index: 1060; display: none;
            background: rgba(15, 23, 35, 0.55); padding: 1.5rem;
            align-items: center; justify-content: center;
        }
        .modal-fotos.abierto { display: flex; }
        .modal-fotos-caja {
            /* Tamaño fijo según la pantalla: no cambia al filtrar o elegir fotos. */
            background: var(--surface); border-radius: 0.75rem;
            width: min(1100px, 100%); height: 100%; display: flex; flex-direction: column; overflow: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .modal-fotos-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.85rem 1.1rem; border-bottom: 1.5px solid var(--border);
            font-weight: 700; color: var(--text);
        }
        .modal-fotos-cerrar { border: 0; background: none; font-size: 1.1rem; color: var(--muted); cursor: pointer; }
        .modal-fotos-filtros {
            display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;
            padding: 0.75rem 1.1rem; border-bottom: 1px solid var(--border);
        }
        .filtro-multi { position: relative; }
        .filtro-multi > summary {
            list-style: none; cursor: pointer; height: 36px; padding: 0 0.85rem;
            display: inline-flex; align-items: center; gap: 0.5rem;
            border: 1.5px solid var(--border); border-radius: 0.5rem; background: #fff;
            font-size: 0.8rem; font-weight: 600; color: var(--text2); min-width: 190px;
        }
        .filtro-multi > summary::-webkit-details-marker { display: none; }
        .filtro-multi > summary span { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 220px; }
        .filtro-multi-panel {
            position: absolute; top: calc(100% + 4px); left: 0; z-index: 5;
            background: #fff; border: 1.5px solid var(--border); border-radius: 0.5rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12); padding: 0.4rem; width: 280px;
            max-height: 300px; overflow-y: auto;
        }
        .filtro-multi-panel input[type="text"] {
            width: 100%; height: 32px; margin-bottom: 0.3rem; padding: 0 0.6rem;
            border: 1.5px solid var(--border); border-radius: 0.4rem; font-size: 0.8rem;
        }
        .filtro-multi-panel label {
            display: flex; align-items: center; gap: 0.5rem; margin: 0;
            padding: 0.35rem 0.5rem; border-radius: 0.35rem; font-size: 0.8rem; font-weight: 500; cursor: pointer;
        }
        .filtro-multi-panel label:hover { background: var(--surface2); }
        .modal-fotos-grilla {
            flex: 1; overflow-y: auto; padding: 1rem 1.1rem;
            display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.75rem;
            /* Sin esto, con muchas fotos las filas se achican para entrar en el
               alto del modal (las tarjetas tienen overflow hidden) en vez de scrollear. */
            grid-auto-rows: max-content;
            align-content: start; min-height: 200px;
        }
        .modal-foto {
            position: relative; border: 2px solid var(--border); border-radius: 0.5rem;
            overflow: hidden; cursor: pointer; background: #fff; transition: border-color 0.12s;
        }
        .modal-foto img { width: 100%; height: 120px; object-fit: cover; display: block; }
        .modal-foto-info { padding: 0.35rem 0.5rem; font-size: 0.72rem; color: var(--text2); }
        .modal-foto-info div { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .modal-foto-info .fecha { color: var(--muted); }
        .modal-foto.seleccionada { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-s); }
        .modal-foto-orden {
            position: absolute; top: 6px; right: 6px; width: 24px; height: 24px; border-radius: 50%;
            background: var(--accent); color: #fff; font-size: 0.75rem; font-weight: 700;
            display: none; align-items: center; justify-content: center;
        }
        .modal-foto.seleccionada .modal-foto-orden { display: flex; }
        .modal-fotos-vacio { grid-column: 1 / -1; }
        .modal-fotos-pie {
            display: flex; flex-wrap: wrap; gap: 0.6rem; align-items: center;
            padding: 0.85rem 1.1rem; border-top: 1.5px solid var(--border);
        }
        .modal-fotos-pie .form-control { flex: 1; min-width: 220px; font-size: 0.85rem; border: 1.5px solid var(--border); border-radius: 0.5rem; }
        .modal-fotos-contador { font-size: 0.8rem; color: var(--muted); white-space: nowrap; }
        .modal-fotos-pie .btn[hidden] { display: none; }

        /* Relevamiento geométrico: planos, fotos y textos en orden */
        .rg-botones { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.75rem; }
        .rg-elemento {
            border: 1px solid var(--border); border-radius: 0.5rem;
            padding: 0.85rem; margin-top: 0.75rem; background: #fff;
        }
        .rg-elemento-head { display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.6rem; font-size: 0.78rem; font-weight: 700; color: var(--text2); }
        .rg-elemento-head .titulo { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .rg-elemento-head .titulo i { color: var(--muted); margin-right: 0.3rem; }
        .rg-elemento-head button { border: 0; background: none; color: var(--muted); padding: 0.2rem 0.45rem; border-radius: 0.35rem; font-size: 0.78rem; }
        .rg-elemento-head button:hover { background: var(--surface2); color: var(--text); }
        .rg-elemento-head button:disabled { opacity: 0.35; pointer-events: none; }
        .rg-elemento .bloque-foto { border: 0; padding: 0; margin: 0; }
        .rg-plano-vista {
            border: 1px solid var(--border); border-radius: 0.4rem; background: #fff;
            min-height: 120px; display: flex; align-items: center; justify-content: center; overflow: hidden;
        }
        .rg-plano-vista canvas { display: block; max-width: 100%; max-height: 520px; width: auto; height: auto; }
        /* Planilla en el informe: datos de sólo lectura, controles de filas (izquierda) y columnas (arriba). */
        .rg-tabla-wrap { border: 1px solid var(--border); border-radius: 0.4rem; background: #fff; overflow-x: auto; padding: 0.5rem; }
        .rg-tabla { border-collapse: collapse; margin: 0 auto; font-family: 'EB Garamond', Garamond, serif; font-size: 15px; color: #000; }
        .rg-tabla th, .rg-tabla td { border: 1px solid #000; padding: 0; vertical-align: middle; }
        .rg-tabla th.ctl, .rg-tabla td.ctl { border: 0; background: none; padding: 0 0.15rem; }
        .rg-tabla .celda { padding: 0.25rem 0.6rem; min-width: 2.5rem; text-align: center; white-space: nowrap; }
        .rg-tabla thead .celda { font-weight: 700; }
        .rg-tabla tbody tr:hover td:not(.ctl) { background: #f5f8ff; }
        .rg-ctl { display: flex; justify-content: center; gap: 0.05rem; white-space: nowrap; }
        .rg-ctl button { border: 0; background: none; color: var(--muted); padding: 0.1rem 0.3rem; border-radius: 0.3rem; font-size: 0.68rem; line-height: 1.4; }
        .rg-ctl button:hover { background: var(--surface2); color: var(--text); }
        .rg-ctl button.peligro:hover { color: #c0392b; }
        .rg-ctl button:disabled { opacity: 0.3; pointer-events: none; }
        .rg-tabla-pie { display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem; font-size: 0.74rem; color: var(--muted); margin: 0.35rem 0 0.75rem; }
        .rg-tabla-pie .resumen { flex: 1; min-width: 0; }
        .rg-tabla-pie .resumen.error { color: #c0392b; }
        .rg-tabla-pie button { border: 0; background: none; padding: 0; color: var(--accent); font-size: 0.74rem; font-weight: 600; }
        .rg-tabla-pie button:disabled { opacity: 0.5; }
        .rg-plano-estado { font-size: 0.8rem; color: var(--muted); padding: 1rem; text-align: center; }
        .rg-plano-estado.error { color: #c0392b; }
        .rg-capas { margin: 0.6rem 0 0.75rem; }
        .rg-capas-head { display: flex; align-items: center; gap: 0.75rem; font-size: 0.74rem; color: var(--muted); margin-bottom: 0.35rem; }
        .rg-capas-head button { border: 0; background: none; padding: 0; color: var(--accent); font-size: 0.74rem; font-weight: 600; }
        .rg-capas-lista { display: flex; flex-wrap: wrap; gap: 0.35rem; }
        .rg-capa {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.25rem 0.65rem; border: 1.5px solid var(--border); border-radius: 999px;
            background: #fff; font-size: 0.75rem; font-weight: 600; color: var(--text2); cursor: pointer;
        }
        .rg-capa img { width: 16px; height: 16px; object-fit: contain; }
        .rg-capa .letra { font-size: 0.65rem; font-weight: 800; color: #ff0000; }
        .rg-capa .ojo { color: var(--accent); font-size: 0.7rem; }
        .rg-capa.apagada { color: var(--muted); background: var(--surface2); }
        .rg-capa.apagada img { opacity: 0.4; }
        .rg-capa.apagada .ojo { color: var(--muted); }
        .rg-campo-pie { display: flex; gap: 0.5rem; align-items: center; }
        .rg-campo-pie .form-control, .rg-elemento textarea.form-control { font-size: 0.85rem; border: 1.5px solid var(--border); border-radius: 0.5rem; }
        .rg-aviso { font-size: 0.78rem; color: #a07400; background: #fff4d6; border-radius: 0.35rem; padding: 0.35rem 0.6rem; }

        .modal-planos-caja { width: min(640px, 100%); height: min(80vh, 100%); }
        .modal-confirmar-caja { width: min(460px, 100%); height: auto; }
        .modal-confirmar-cuerpo { display: flex; gap: 0.85rem; align-items: flex-start; padding: 1.1rem; font-size: 0.88rem; color: var(--text2); line-height: 1.45; }
        .modal-confirmar-cuerpo i { color: #d39e00; font-size: 1.3rem; margin-top: 0.1rem; }
        .modal-confirmar-cuerpo p { margin: 0; }
        .modal-confirmar-pie { justify-content: flex-end; }
        .modal-fotos-filtros .form-control { font-size: 0.85rem; border: 1.5px solid var(--border); border-radius: 0.5rem; }
        .modal-planos-lista { flex: 1; overflow-y: auto; padding: 0.5rem 0.75rem 1rem; }
        .modal-planos-carpeta { font-size: 0.7rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; padding: 0.75rem 0.5rem 0.3rem; }
        .modal-plano {
            display: flex; align-items: center; gap: 0.6rem; width: 100%;
            padding: 0.55rem 0.6rem; border: 0; border-radius: 0.4rem; background: none;
            font-size: 0.85rem; font-weight: 500; color: var(--text); text-align: left; cursor: pointer;
        }
        .modal-plano:hover { background: var(--accent-s); color: var(--accent); }
        .modal-plano i { color: var(--muted); }
        .modal-plano .marca { margin-left: auto; font-size: 0.7rem; color: var(--muted); white-space: nowrap; }

        @media (max-width: 991px) {
            .editor { flex-direction: column-reverse; }
            .editor-side { width: 100%; position: static; }
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
                            <a href="{{ route('obras_tc.index', $obraTc->id) }}">{{ $obraTc->descripcion }}</a>
                            <i class="fas fa-chevron-right"></i>
                            <i class="fas fa-file-word"></i>
                            Generador de Informe
                        </div>
                        <h1 class="ph-title"><em>Informe</em>{{ $informe ? ': ' . ($informe->titulo ?: 'Sin título') : ' nuevo' }}</h1>
                    </div>
                    <div class="ph-right">
                        {{-- Guardar envía el mismo formulario a otra ruta (formaction). --}}
                        <button type="submit" form="form-informe" class="btn btn-primary" id="btn-guardar"
                                formaction="{{ route('informe.generador.guardar', $obraTc->id) }}">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <button type="button" class="btn" id="btn-vista-previa">
                            <i class="fas fa-eye"></i> Vista previa
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
                @if (session('success'))
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
                @endif
                <div class="editor">
                    <div class="editor-main">
                        {{-- El botón "Generar Word" del panel lateral envía este formulario (atributo form). --}}
                        <form id="form-informe" method="POST" action="{{ route('informe.generador.generar', $obraTc->id) }}" class="card-box">
                            @csrf
                            {{-- Valor guardado (o recién enviado, si volvió con errores) de cada campo. --}}
                            @php $v = fn (string $campo, $porDefecto = '') => $datos[$campo] ?? $porDefecto; @endphp
                            {{-- Vista previa del informe con lo cargado en pantalla (la arma el JS). --}}
                            <div class="panel activo" id="panel-vista-previa">
                                <div class="card-box-head">Vista previa del informe</div>
                                <div class="vp-ayuda">
                                    <i class="fas fa-info-circle"></i>
                                    Así se va armando el informe con lo cargado. Hacé clic en una parte para editarla. Es aproximada: los saltos de página y el índice definitivos los arma Word.
                                </div>
                                <div class="vp-documento" id="vp-documento"></div>
                            </div>

                            <div class="panel" id="panel-caratula">
                                <div class="card-box-head">Carátula</div>
                                <div class="panel-body">
                                    <div class="row g-3">
                                        <div class="col-12 campo">
                                            <label for="car-titulo">Título del informe</label>
                                            <input type="text" class="form-control" id="car-titulo" name="titulo" value="{{ $v('titulo') }}" placeholder="Ej.: Evaluación estructural de losas del Sector B">
                                            <small class="ayuda">Busca que la persona que lea el título sepa de qué trata el informe sin abrirlo: qué se hizo, sobre qué elemento y en qué sector.</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="car-obra">Obra</label>
                                            <input type="text" class="form-control" id="car-obra" name="obra" value="{{ $v('obra', $obraTc->descripcion) }}" placeholder="Ej.: Edificio Torre Central">
                                            <small class="ayuda">Nombre del lugar físico en el que se realizó el trabajo.</small>
                                        </div>
                                        <div class="col-md-6 campo">
                                            <label for="car-mes">Mes</label>
                                            <select class="form-select" id="car-mes" name="mes">
                                                <option value="">Seleccionar...</option>
                                                @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $mes)
                                                    <option value="{{ $mes }}" @selected($v('mes') === $mes)>{{ $mes }}</option>
                                                @endforeach
                                            </select>
                                            <small class="ayuda">Mes en el que se desarrollaron los trabajos.</small>
                                        </div>
                                        <div class="col-md-6 campo">
                                            <label for="car-anio">Año</label>
                                            <input type="number" class="form-control" id="car-anio" name="anio" value="{{ $v('anio', date('Y')) }}">
                                            <small class="ayuda">Año en el que se desarrollaron los trabajos.</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="car-peticionario">Peticionario</label>
                                            <input type="text" class="form-control" id="car-peticionario" name="peticionario" value="{{ $v('peticionario') }}" placeholder="Ej.: Constructora del Sur S.A.">
                                            <small class="ayuda">Nombre de la persona o entidad que financia el trabajo.</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="car-referencia">Referencia</label>
                                            <input type="text" class="form-control" id="car-referencia" name="referencia" value="{{ $v('referencia') }}" placeholder="Ej.: INF.: 55-25">
                                            <small class="ayuda">Se usa para encontrar el informe rápidamente en el gestor de archivos. En el ejemplo, 55 es el número de informe y 25 el año.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel" id="panel-info-general">
                                <div class="card-box-head">Información general</div>
                                <div class="panel-body">
                                    <div class="row g-3">
                                        <div class="col-12 campo">
                                            <label for="ig-fp-dia">Fecha de envío del presupuesto</label>
                                            <div class="row g-2">
                                                <div class="col-3">
                                                    <select class="form-select" id="ig-fp-dia" name="presupuesto_dia" aria-label="Día">
                                                        <option value="">Día</option>
                                                        @for ($d = 1; $d <= 31; $d++)
                                                            <option value="{{ $d }}" @selected((int) $v('presupuesto_dia') === $d)>{{ $d }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-5">
                                                    <select class="form-select" id="ig-fp-mes" name="presupuesto_mes" aria-label="Mes">
                                                        <option value="">Mes</option>
                                                        @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $mes)
                                                            <option value="{{ $mes }}" @selected($v('presupuesto_mes') === $mes)>{{ $mes }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <select class="form-select" id="ig-fp-anio" name="presupuesto_anio" aria-label="Año">
                                                        {{-- Si el informe guardado tiene un año fuera del rango, se agrega. --}}
                                                        @php
                                                            $anioPresupuesto = (int) $v('presupuesto_anio', date('Y'));
                                                            $aniosPresupuesto = collect(range(date('Y') + 1, date('Y') - 5))->push($anioPresupuesto)->unique()->sortDesc();
                                                        @endphp
                                                        @foreach ($aniosPresupuesto as $a)
                                                            <option value="{{ $a }}" @selected($a === $anioPresupuesto)>{{ $a }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="ig-motivo-presupuesto">Motivo del presupuesto</label>
                                            <textarea class="form-control" id="ig-motivo-presupuesto" name="motivo_presupuesto" rows="3" placeholder="Ej.: la evaluación de la estructura de hormigón armado perteneciente al Edificio Torre Central">{{ $v('motivo_presupuesto') }}</textarea>
                                            <small class="ayuda">Para qué se envió el presupuesto. Completa la frase "hemos presentado una propuesta técnica para…".</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="ig-ubicacion-obra">Ubicación de la obra</label>
                                            <input type="text" class="form-control" id="ig-ubicacion-obra" name="ubicacion_obra" value="{{ $v('ubicacion_obra') }}" placeholder="Ej.: la Avda. Principal N° 1234 e/ Calle Uno y Calle Dos de la ciudad de San Lorenzo">
                                            <small class="ayuda">Dónde se encuentra la obra. Completa la frase "ubicado sobre…".</small>
                                        </div>
                                    </div>

                                    <div class="vista-previa">
                                        <div class="vista-previa-head"><i class="fas fa-eye"></i> Así queda el texto</div>
                                        <p id="ig-vista-previa"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="panel" id="panel-objetivo">
                                <div class="card-box-head">Objetivo y alcance</div>
                                <div class="panel-body">
                                    <div class="row g-3">
                                        <div class="col-12 campo">
                                            <label for="obj-texto">Objetivo y alcance del informe</label>
                                            <textarea class="form-control" id="obj-texto" name="objetivo_alcance" rows="5" placeholder="Ej. 1: El presente informe tiene por finalidad evaluar el estado estructural de las columnas de hormigón armado del galpón de depósito, a fin de determinar su condición estructural y su aptitud para continuar en servicio.&#10;&#10;Ej. 2: El presente informe tiene por objeto presentar los resultados de la evaluación de patologías en la estructura de hormigón armado en diversos sectores, a fin de determinar la trascendencia estructural de los daños visualizados.">{{ $v('objetivo_alcance') }}</textarea>
                                            <small class="ayuda">Qué se busca evaluar con el informe, sobre qué elementos y para qué. Cada renglón se convierte en un párrafo del Word.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel" id="panel-datos-previos">
                                <div class="card-box-head">Datos previos para la realización del estudio</div>
                                <div class="panel-body">
                                    <div class="grupo">
                                        <div class="grupo-titulo">Documentación recibida</div>
                                        <small class="ayuda d-block mb-2">Detalle de los documentos que envió el cliente: planos, memorias, planillas, fotografías, estudios, etc.</small>
                                        <div id="documentos"></div>
                                        <button type="button" class="btn btn-sm" id="btn-agregar-documento">
                                            <i class="fas fa-plus"></i> Agregar documento
                                        </button>
                                        <datalist id="tipos-documento">
                                            @foreach (['Plano', 'Memoria de cálculo', 'Memoria descriptiva', 'Planilla', 'Fotografías', 'Estudio de suelos', 'Informe'] as $tipo)
                                                <option value="{{ $tipo }}">
                                            @endforeach
                                        </datalist>
                                        {{-- Molde de un documento; __i__ se reemplaza por un índice único al agregarlo. --}}
                                        <template id="molde-documento">
                                            <div class="documento">
                                                <div class="row g-2">
                                                    <div class="col-md-4 campo">
                                                        <label for="doc-tipo-__i__">Tipo de documento</label>
                                                        <input type="text" class="form-control" id="doc-tipo-__i__" name="documentos[__i__][tipo]" list="tipos-documento" placeholder="Ej.: Plano">
                                                    </div>
                                                    <div class="col-md-8 campo">
                                                        <label for="doc-nombre-__i__">Nombre del archivo</label>
                                                        <input type="text" class="form-control" id="doc-nombre-__i__" name="documentos[__i__][nombre]" placeholder="Ej.: Encofrado Planta Baja">
                                                    </div>
                                                    <div class="col-md-4 campo">
                                                        <label for="doc-fecha-__i__">Fecha de recepción</label>
                                                        <input type="date" class="form-control" id="doc-fecha-__i__" name="documentos[__i__][fecha]">
                                                    </div>
                                                    <div class="col-md-8 campo">
                                                        <label for="doc-remitente-__i__">Enviado por</label>
                                                        <input type="text" class="form-control" id="doc-remitente-__i__" name="documentos[__i__][remitente]" placeholder="Ej.: Juan Pérez">
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-quitar" title="Quitar documento">
                                                    <i class="fas fa-trash-alt"></i> Quitar
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="grupo">
                                        <div class="grupo-titulo">Información recibida</div>
                                        <div class="row g-3">
                                            <div class="col-12 campo">
                                                <textarea class="form-control" id="dp-informacion" name="informacion_recibida" rows="5" aria-label="Información recibida">{{ $v('informacion_recibida') }}</textarea>
                                                <small class="ayuda">Breve descripción de la información que dio el cliente: por qué decide hacer el estudio, cuándo empezó a notar las patologías y, en síntesis, el contexto de lo que relató. Cada renglón se convierte en un párrafo del Word.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grupo">
                                        <div class="grupo-titulo">Características de la obra</div>
                                        <div class="row g-3">
                                            <div class="col-12 campo">
                                                <textarea class="form-control" id="dp-caracteristicas" name="caracteristicas_obra" rows="5" aria-label="Características de la obra">{{ $v('caracteristicas_obra') }}</textarea>
                                                <small class="ayuda">Descripción de la estructura: cómo está constituida, si es nueva o existente, si está terminada o en construcción, etc. Cada renglón se convierte en un párrafo del Word.</small>
                                                <div id="bloques-fotos"></div>
                                                <button type="button" class="btn btn-sm" id="btn-agregar-imagen">
                                                    <i class="fas fa-image"></i> Agregar imagen
                                                </button>
                                                <small class="ayuda">Cada vez que agregás imágenes se crea un bloque: si elegís varias fotos podés agregarlas juntas con un solo pie, o por separado (una por bloque, cada una con su pie). Van al Word después del texto, en el orden en que se agregan.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Equipo técnico. --}}
                            <div class="panel" id="panel-equipo-tecnico">
                                <div class="card-box-head">Equipo Técnico</div>
                                <div class="panel-body">
                                    <div class="row g-3">
                                        <div class="col-12 campo">
                                            <label for="et-coordinacion">Coordinación general de los trabajos</label>
                                            <input type="text" class="form-control" id="et-coordinacion" name="equipo_coordinacion" value="{{ $v('equipo_coordinacion') }}" placeholder="Ej.: Prof. Dr. Ing. Sergio Gavilán">
                                            <small class="ayuda">Nombre de la persona que va a firmar el documento.</small>
                                        </div>
                                        <div class="col-12">
                                            <div class="grupo-titulo mb-2">Trabajos de campo</div>
                                            <div class="row g-3">
                                                <div class="col-12 campo">
                                                    <label for="et-campo-descripcion">Descripción</label>
                                                    <input type="text" class="form-control" id="et-campo-descripcion" name="equipo_campo_descripcion" value="{{ $v('equipo_campo_descripcion') }}" placeholder="Ej.: Relevamiento y Ensayos no destructivos">
                                                    <small class="ayuda">Qué trabajos se hicieron en campo.</small>
                                                </div>
                                                <div class="col-12 campo">
                                                    <label for="et-campo-personas">Personas</label>
                                                    <textarea class="form-control" id="et-campo-personas" name="equipo_campo_personas" rows="3" placeholder="Ej.: Ing. Juan Pérez&#10;Ing. María González">{{ $v('equipo_campo_personas') }}</textarea>
                                                    <small class="ayuda">Una persona por renglón (separadas con Enter).</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="grupo-titulo mb-2">Trabajos de gabinete</div>
                                            <div class="row g-3">
                                                <div class="col-12 campo">
                                                    <label for="et-gabinete-descripcion">Descripción</label>
                                                    <input type="text" class="form-control" id="et-gabinete-descripcion" name="equipo_gabinete_descripcion" value="{{ $v('equipo_gabinete_descripcion') }}" placeholder="Ej.: Procesamiento de datos y elaboración del informe">
                                                    <small class="ayuda">Qué trabajos se hicieron en gabinete.</small>
                                                </div>
                                                <div class="col-12 campo">
                                                    <label for="et-gabinete-personas">Personas</label>
                                                    <textarea class="form-control" id="et-gabinete-personas" name="equipo_gabinete_personas" rows="3" placeholder="Ej.: Ing. Juan Pérez&#10;Ing. María González">{{ $v('equipo_gabinete_personas') }}</textarea>
                                                    <small class="ayuda">Una persona por renglón (separadas con Enter).</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Trabajos desarrollados (Titulo Uno, página nueva en el Word).
                                 Cada trabajo activado aparece como subparte del informe. --}}
                            @php
                                $trabajosActivos = (array) $v('trabajos_activos', []);
                                // Menú de trabajos: los sueltos en su orden y cada grupo de
                                // ensayos donde aparece su primer ensayo, con los suyos adentro.
                                $grupoDe = [];
                                foreach (\App\Models\InformeTc::GRUPOS_ENSAYOS as $claveGrupo => $grupo) {
                                    foreach ($grupo['trabajos'] as $claveTrabajo) {
                                        $grupoDe[$claveTrabajo] = $claveGrupo;
                                    }
                                }
                                $menuTrabajos = [];
                                foreach (array_keys(\App\Models\InformeTc::TRABAJOS) as $claveTrabajo) {
                                    if (! isset($grupoDe[$claveTrabajo])) {
                                        $menuTrabajos[] = ['trabajo' => $claveTrabajo];
                                    } elseif (! in_array(['grupo' => $grupoDe[$claveTrabajo]], $menuTrabajos, true)) {
                                        $menuTrabajos[] = ['grupo' => $grupoDe[$claveTrabajo]];
                                    }
                                }
                            @endphp
                            <div class="panel" id="panel-trabajos-desarrollados">
                                <div class="card-box-head">Trabajos desarrollados</div>
                                <div class="panel-body">
                                    <div class="campo">
                                        <label for="td-descripcion">Descripción</label>
                                        <textarea class="form-control" id="td-descripcion" name="trabajos_descripcion" rows="4" placeholder="Ej.: Los trabajos y ensayos ejecutados a fin de evaluar el estado del hormigón fueron los siguientes:">{{ $v('trabajos_descripcion') }}</textarea>
                                        <small class="ayuda">Frase que introduce la lista de los trabajos realizados para un determinado objetivo.</small>
                                    </div>
                                    <div class="grupo-titulo mt-4 mb-2">Trabajos realizados</div>
                                    <div class="trabajos-toggles">
                                        @foreach (\App\Models\InformeTc::TRABAJOS as $clave => $nombre)
                                            <label class="trabajo-toggle">
                                                <input type="checkbox" name="trabajos_activos[]" value="{{ $clave }}" data-trabajo="{{ $clave }}" @checked(in_array($clave, $trabajosActivos, true))>
                                                <span><i class="fas fa-check"></i> {{ $nombre }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <small class="ayuda d-block mt-2">Los trabajos activados se agregan a las partes del informe.</small>
                                </div>
                            </div>

                            @foreach (\App\Models\InformeTc::TRABAJOS as $clave => $nombre)
                                <div class="panel" id="panel-trabajo-{{ $clave }}">
                                    <div class="card-box-head">{{ $nombre }}</div>
                                    <div class="panel-body">
                                        @if ($clave === 'relevamiento_encofrado')
                                            @include('informe.partials.contenido-seccion', [
                                                'seccion' => 'relevamiento_geometrico',
                                                'trabajosWord' => ['relevamiento_encofrado'],
                                                'placeholder' => 'Ej.: Se realizó el relevamiento geométrico de la estructura a fin de obtener la planta de encofrado, determinando las dimensiones de vigas, columnas y losas, y la ubicación de los elementos estructurales.',
                                                'ayuda' => 'Qué se relevó, cómo y con qué fin. Cada renglón se convierte en un párrafo del Word.',
                                            ])
                                        @elseif (in_array($clave, ['esclerometria', 'ultrasonido'], true))
                                            @php
                                                $textosEnsayo = [
                                                    'esclerometria' => [
                                                        'placeholder' => 'Ej.: Se realizaron ensayos de esclerometría sobre los elementos estructurales a fin de estimar la resistencia superficial del hormigón.',
                                                        'ayuda' => 'Cómo y dónde se hizo el ensayo de esclerometría. Cada renglón se convierte en un párrafo del Word.',
                                                    ],
                                                    'ultrasonido' => [
                                                        'placeholder' => 'Ej.: Se realizaron ensayos de ultrasonido por el método indirecto sobre los elementos estructurales a fin de evaluar la calidad y homogeneidad del hormigón.',
                                                        'ayuda' => 'Cómo y dónde se hizo el ensayo de ultrasonido. Cada renglón se convierte en un párrafo del Word.',
                                                    ],
                                                ][$clave];
                                            @endphp
                                            @include('informe.partials.descripcion-fotos', [
                                                'seccion' => $clave,
                                                'placeholder' => $textosEnsayo['placeholder'],
                                                'ayuda' => $textosEnsayo['ayuda'],
                                            ])
                                            {{-- Sin descripción: la de arriba ya se guarda como {clave}_descripcion. --}}
                                            @include('informe.partials.contenido-seccion', [
                                                'seccion' => $clave,
                                                'trabajosWord' => [$clave],
                                                'botones' => ['planilla', 'texto'],
                                                'planilla' => $clave,
                                                'tituloContenido' => 'Planillas y textos',
                                                'ayudaContenido' => 'Van después de las fotografías, en este orden (se puede cambiar con las flechas). La planilla muestra siempre los datos de Planillas (ahí se modifican): acá se pueden mover o quitar filas y columnas sólo para el informe, y separarla en varias tablas con la tijera (cada una con su propio pie).',
                                            ])
                                        @else
                                            {{-- Por ahora vacío. --}}
                                            <small class="ayuda">Todavía no hay contenido para esta sección.</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @foreach (\App\Models\InformeTc::GRUPOS_ENSAYOS as $claveGrupo => $grupo)
                                <div class="panel" id="panel-grupo-{{ $claveGrupo }}">
                                    <div class="card-box-head">{{ $grupo['titulo'] }}</div>
                                    <div class="panel-body">
                                        @if ($claveGrupo === 'resistencia')
                                            @include('informe.partials.contenido-seccion', [
                                                'seccion' => 'ensayos_resistencia',
                                                'trabajosWord' => $grupo['trabajos'],
                                                'placeholder' => 'Ej.: Con el objeto de estimar la resistencia del hormigón de los elementos estructurales se realizaron los siguientes ensayos.',
                                                'ayuda' => 'Qué ensayos se hicieron para determinar la resistencia del hormigón y con qué fin. Cada renglón se convierte en un párrafo del Word.',
                                            ])
                                        @else
                                            {{-- Por ahora vacío. --}}
                                            <small class="ayuda">Todavía no hay contenido para esta sección.</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </form>
                    </div>

                    <aside class="editor-side">
                        {{-- Separado de "Guardar" para no confundirlos: Generar solo descarga el Word. --}}
                        <div class="card-box">
                            <div class="card-box-head">Documento Word</div>
                            <div class="generar-box">
                                <button type="submit" form="form-informe" class="btn btn-generar" id="btn-generar">
                                    <i class="fas fa-file-word"></i> Generar Word
                                </button>
                                <small class="ayuda">Descarga el informe con lo cargado en pantalla. No guarda los cambios.</small>
                            </div>
                        </div>
                        <div class="card-box">
                            <div class="card-box-head">Partes del informe</div>
                            <ul class="partes">
                                <li class="parte activa" data-panel="panel-vista-previa">
                                    <i class="fas fa-eye"></i> Vista previa
                                </li>
                                <li class="parte" data-panel="panel-caratula">
                                    <i class="fas fa-file-alt"></i> Carátula
                                </li>
                                <li class="parte" data-panel="panel-info-general">
                                    <i class="fas fa-info-circle"></i> Información general
                                </li>
                                <li class="parte" data-panel="panel-objetivo">
                                    <i class="fas fa-bullseye"></i> Objetivo y alcance
                                </li>
                                <li class="parte" data-panel="panel-datos-previos">
                                    <i class="fas fa-folder-open"></i> Datos previos para la realización del estudio
                                </li>
                                <li class="parte" data-panel="panel-equipo-tecnico">
                                    <i class="fas fa-users"></i> Equipo Técnico
                                </li>
                                <li class="parte" data-panel="panel-trabajos-desarrollados">
                                    <i class="fas fa-tools"></i> Trabajos desarrollados
                                </li>
                                {{-- Un grupo se ve si tiene algún ensayo activado; sus ensayos van debajo. --}}
                                @foreach ($menuTrabajos as $item)
                                    @if (isset($item['grupo']))
                                        @php $grupo = \App\Models\InformeTc::GRUPOS_ENSAYOS[$item['grupo']]; @endphp
                                        <li class="parte subparte" data-panel="panel-grupo-{{ $item['grupo'] }}" data-grupo="{{ $item['grupo'] }}" data-trabajos="{{ implode(',', $grupo['trabajos']) }}" @unless (array_intersect($grupo['trabajos'], $trabajosActivos)) hidden @endunless>
                                            <i class="fas fa-angle-right"></i> {{ $grupo['titulo'] }}
                                        </li>
                                        @foreach ($grupo['trabajos'] as $clave)
                                            <li class="parte subparte subparte-2" data-panel="panel-trabajo-{{ $clave }}" data-subparte="{{ $clave }}" @unless (in_array($clave, $trabajosActivos, true)) hidden @endunless>
                                                <i class="fas fa-angle-right"></i> {{ \App\Models\InformeTc::TRABAJOS[$clave] }}
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="parte subparte" data-panel="panel-trabajo-{{ $item['trabajo'] }}" data-subparte="{{ $item['trabajo'] }}" @unless (in_array($item['trabajo'], $trabajosActivos, true)) hidden @endunless>
                                            <i class="fas fa-angle-right"></i> {{ \App\Models\InformeTc::TRABAJOS[$item['trabajo']] }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
    @include('partials.footer')
</div>

{{-- Modal para elegir fotos de la galería de la obra --}}
<div class="modal-fotos" id="modal-fotos" role="dialog" aria-modal="true" aria-labelledby="modal-fotos-titulo">
    <div class="modal-fotos-caja">
        <div class="modal-fotos-head">
            <span id="modal-fotos-titulo"><i class="fas fa-images"></i> Agregar imagen</span>
            <button type="button" class="modal-fotos-cerrar" data-cerrar-modal title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-fotos-filtros">
            <details class="filtro-multi" id="filtro-planos">
                <summary><span>Todos los planos</span><i class="fas fa-chevron-down"></i></summary>
                <div class="filtro-multi-panel">
                    <input type="text" placeholder="Buscar plano..." data-buscar>
                    @foreach ($planos as $plano)
                        <label data-texto="{{ Str::lower($plano['descripcion']) }}">
                            <input type="checkbox" value="{{ $plano['id'] }}"> <span>{{ $plano['descripcion'] }}</span>
                        </label>
                    @endforeach
                </div>
            </details>
            <details class="filtro-multi" id="filtro-etiquetas">
                <summary><span>Toda etiqueta</span><i class="fas fa-chevron-down"></i></summary>
                <div class="filtro-multi-panel">
                    <input type="text" placeholder="Buscar etiqueta..." data-buscar>
                    <label data-texto="sin etiquetas">
                        <input type="checkbox" value="__sin__"> <span>Sin etiquetas</span>
                    </label>
                    @foreach ($etiquetas as $etiqueta)
                        <label data-texto="{{ Str::lower($etiqueta->descripcion) }}">
                            <input type="checkbox" value="{{ $etiqueta->id }}"> <span>{{ $etiqueta->descripcion }}</span>
                        </label>
                    @endforeach
                </div>
            </details>
            <button type="button" class="btn btn-sm" id="btn-limpiar-filtros-fotos">
                <i class="fas fa-filter-circle-xmark"></i> Limpiar
            </button>
        </div>
        <div class="modal-fotos-grilla" id="modal-fotos-grilla"></div>
        <div class="modal-fotos-pie">
            <span class="modal-fotos-contador" id="modal-fotos-contador">Ninguna foto seleccionada</span>
            <input type="text" class="form-control" id="modal-fotos-pie" maxlength="500" placeholder="Pie de foto (ej.: Vista general de la fachada principal)">
            <button type="button" class="btn" data-cerrar-modal>Cancelar</button>
            <button type="button" class="btn" id="btn-confirmar-separadas" hidden
                    title="Cada foto va en su propio bloque; el pie de cada una se carga después, en el formulario">
                <i class="fas fa-clone"></i> Agregar por separado
            </button>
            <button type="button" class="btn btn-primary" id="btn-confirmar-fotos" disabled>
                <i class="fas fa-plus"></i> <span id="btn-confirmar-fotos-texto">Agregar</span>
            </button>
        </div>
    </div>
</div>
{{-- Modal para elegir un plano de la obra --}}
<div class="modal-fotos" id="modal-planos" role="dialog" aria-modal="true" aria-labelledby="modal-planos-titulo">
    <div class="modal-fotos-caja modal-planos-caja">
        <div class="modal-fotos-head">
            <span id="modal-planos-titulo"><i class="fas fa-map"></i> Agregar plano</span>
            <button type="button" class="modal-fotos-cerrar" data-cerrar-modal title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-fotos-filtros">
            <input type="text" class="form-control" id="modal-planos-buscar" placeholder="Buscar plano..." aria-label="Buscar plano">
        </div>
        <div class="modal-planos-lista" id="modal-planos-lista"></div>
    </div>
</div>
{{-- Modal de confirmación (ver confirmar() en el script) --}}
<div class="modal-fotos" id="modal-confirmar" role="alertdialog" aria-modal="true" aria-labelledby="modal-confirmar-titulo" aria-describedby="modal-confirmar-texto">
    <div class="modal-fotos-caja modal-confirmar-caja">
        <div class="modal-fotos-head">
            <span id="modal-confirmar-titulo"></span>
            <button type="button" class="modal-fotos-cerrar" data-confirmar="no" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-confirmar-cuerpo">
            <i class="fas fa-exclamation-triangle"></i>
            <p id="modal-confirmar-texto"></p>
        </div>
        <div class="modal-fotos-pie modal-confirmar-pie">
            <button type="button" class="btn" data-confirmar="no">Cancelar</button>
            <button type="button" class="btn btn-primary" data-confirmar="si" id="modal-confirmar-aceptar"></button>
        </div>
    </div>
</div>
<script src="{{ \App\Services\DescargaOfflineService::PDFJS }}"></script>
<script src="{{ asset('js/plano-render.js') }}"></script>
<script>
    // Al tocar una parte del informe se muestra su panel y se marca como activa.
    document.querySelectorAll('.parte').forEach(function (parte) {
        parte.addEventListener('click', function () {
            document.querySelectorAll('.parte').forEach(p => p.classList.remove('activa'));
            document.querySelectorAll('.panel').forEach(p => p.classList.remove('activo'));
            parte.classList.add('activa');
            document.getElementById(parte.dataset.panel).classList.add('activo');
        });
    });

    // Trabajos desarrollados: cada botón activado muestra su subparte en el
    // menú, y un grupo de ensayos se muestra si tiene alguno activado. Si se
    // oculta lo que se está viendo, se vuelve a Trabajos desarrollados.
    document.querySelectorAll('[data-trabajo]').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            const activos = [...document.querySelectorAll('[data-trabajo]:checked')].map(t => t.dataset.trabajo);
            const subparte = document.querySelector('[data-subparte="' + toggle.dataset.trabajo + '"]');
            subparte.hidden = ! toggle.checked;
            document.querySelectorAll('[data-grupo]').forEach(function (grupo) {
                grupo.hidden = ! grupo.dataset.trabajos.split(',').some(t => activos.includes(t));
            });
            const activa = document.querySelector('.parte.activa');
            if (activa && activa.hidden) {
                document.querySelector('[data-panel="panel-trabajos-desarrollados"]').click();
            }
        });
    });

    // Documentación recibida: cada documento sale del molde con un índice
    // propio, así los campos llegan agrupados como documentos[i][...].
    (function () {
        const lista = document.getElementById('documentos');
        const molde = document.getElementById('molde-documento').innerHTML;
        let indice = 0;

        // doc: documento guardado para precargar sus campos (opcional).
        function agregar(doc) {
            const i = indice++;
            lista.insertAdjacentHTML('beforeend', molde.replaceAll('__i__', i));
            if (doc && typeof doc === 'object' && !(doc instanceof Event)) {
                ['tipo', 'nombre', 'fecha', 'remitente'].forEach(function (campo) {
                    document.getElementById('doc-' + campo + '-' + i).value = doc[campo] || '';
                });
            }
        }

        lista.addEventListener('click', function (e) {
            const quitar = e.target.closest('.btn-quitar');
            if (quitar) {
                quitar.closest('.documento').remove();
            }
        });

        document.getElementById('btn-agregar-documento').addEventListener('click', () => agregar());

        const guardados = Object.values(@json($v('documentos', [])) || {});
        if (guardados.length) {
            guardados.forEach(agregar);
        } else {
            agregar();
        }
    })();

    // Vista previa del párrafo de Información general, se arma mientras se carga.
    (function () {
        const dia = document.getElementById('ig-fp-dia');
        const mes = document.getElementById('ig-fp-mes');
        const anio = document.getElementById('ig-fp-anio');
        const motivo = document.getElementById('ig-motivo-presupuesto');
        const ubicacion = document.getElementById('ig-ubicacion-obra');
        const vista = document.getElementById('ig-vista-previa');

        // Arma el párrafo en `destino` con nodos de texto (no innerHTML) para
        // que lo que escribe el usuario nunca se interprete como HTML. También
        // lo usa la vista previa del informe completo.
        function armar(destino) {
            function agregar(texto) {
                destino.appendChild(document.createTextNode(texto));
            }
            function agregarDato(valor, faltante) {
                if (valor) {
                    agregar(valor);
                    return;
                }
                const span = document.createElement('span');
                span.className = 'falta';
                span.textContent = faltante;
                destino.appendChild(span);
            }

            const fecha = dia.value && mes.value && anio.value
                ? dia.value + ' de ' + mes.value.toLowerCase() + ' de ' + anio.value
                : '';
            // Sin punto doble si la ubicación ya termina en punto.
            const lugar = ubicacion.value.trim().replace(/\.+$/, '');

            destino.textContent = '';
            agregar('En fecha ');
            agregarDato(fecha, '[fecha]');
            agregar(' hemos presentado una propuesta técnica para ');
            agregarDato(motivo.value.trim(), '[motivo del presupuesto]');
            agregar(' ubicado sobre ');
            agregarDato(lugar, '[ubicación de la obra]');
            agregar('.');
        }
        window.armarInfoGeneral = armar;

        function actualizar() {
            armar(vista);
        }

        [dia, mes, anio, motivo, ubicacion].forEach(function (campo) {
            campo.addEventListener('input', actualizar);
            campo.addEventListener('change', actualizar);
        });
        actualizar();
    })();

    // Fotos de Características de la obra: el modal muestra la galería de
    // la obra (filtrable por plano y etiqueta); cada confirmación crea un
    // bloque con las fotos elegidas y un único pie.
    (function () {
        const FOTOS = @json($fotos);
        const modal = document.getElementById('modal-fotos');
        const grilla = document.getElementById('modal-fotos-grilla');
        const contador = document.getElementById('modal-fotos-contador');
        const inputPie = document.getElementById('modal-fotos-pie');
        const btnConfirmar = document.getElementById('btn-confirmar-fotos');
        const textoConfirmar = document.getElementById('btn-confirmar-fotos-texto');
        const btnSeparadas = document.getElementById('btn-confirmar-separadas');
        const filtroPlanos = document.getElementById('filtro-planos');
        const filtroEtiquetas = document.getElementById('filtro-etiquetas');
        const bloques = document.getElementById('bloques-fotos');
        let seleccion = [];
        let indiceBloque = 0;
        // Quién recibe los bloques al confirmar: Características de la obra
        // (por defecto) o el Relevamiento geométrico. Devuelve el input del pie.
        let destino = null;

        // Tarjetas armadas una sola vez; los filtros sólo las ocultan.
        const tarjetas = FOTOS.map(function (foto) {
            const tarjeta = document.createElement('div');
            tarjeta.className = 'modal-foto';
            tarjeta.dataset.id = foto.id;

            const img = document.createElement('img');
            img.src = foto.miniatura;
            img.alt = 'Foto';
            img.loading = 'lazy';

            const orden = document.createElement('span');
            orden.className = 'modal-foto-orden';

            const info = document.createElement('div');
            info.className = 'modal-foto-info';
            const plano = document.createElement('div');
            plano.textContent = foto.plano;
            const fecha = document.createElement('div');
            fecha.className = 'fecha';
            fecha.textContent = foto.fecha || '';
            info.append(plano, fecha);

            tarjeta.append(img, orden, info);
            tarjeta.addEventListener('click', function () { alternar(foto); });
            grilla.appendChild(tarjeta);
            return { foto: foto, el: tarjeta };
        });

        const vacio = document.createElement('div');
        vacio.className = 'vacio modal-fotos-vacio';
        vacio.innerHTML = '<i class="fas fa-images"></i>';
        vacio.appendChild(document.createTextNode(FOTOS.length ? 'No hay fotos que coincidan con el filtro.' : 'Esta obra todavía no tiene fotos en la galería.'));
        grilla.appendChild(vacio);

        function marcados(filtro) {
            return Array.from(filtro.querySelectorAll('input[type="checkbox"]:checked'));
        }

        function actualizarTextoFiltro(filtro, textoVacio, separador = ', ') {
            const elegidos = marcados(filtro).map(c => c.nextElementSibling.textContent.trim());
            filtro.querySelector('summary span').textContent = elegidos.length ? elegidos.join(separador) : textoVacio;
        }

        function filtrar() {
            const planos = marcados(filtroPlanos).map(c => c.value);
            const etiquetas = marcados(filtroEtiquetas).map(c => c.value);
            let visibles = 0;

            tarjetas.forEach(function ({ foto, el }) {
                // Planos: la foto está en alguno de los marcados (cada foto tiene un solo plano).
                const pasaPlano = !planos.length || planos.includes(String(foto.plano_id));
                // Etiquetas: la foto tiene TODAS las marcadas (ej. Fisura + Planta alta).
                const pasaEtiqueta = etiquetas.every(function (e) {
                    return e === '__sin__' ? foto.etiquetas.length === 0 : foto.etiquetas.includes(Number(e));
                });
                const visible = pasaPlano && pasaEtiqueta;
                el.hidden = !visible;
                if (visible) visibles++;
            });

            vacio.hidden = visibles > 0;
            actualizarTextoFiltro(filtroPlanos, 'Todos los planos');
            actualizarTextoFiltro(filtroEtiquetas, 'Toda etiqueta', ' + ');
        }

        // "Sin etiquetas" no se puede combinar con etiquetas (no daría ninguna foto):
        // marcar uno desmarca el otro.
        filtroEtiquetas.addEventListener('change', function (e) {
            if (!e.target.checked) return;
            const esSin = e.target.value === '__sin__';
            marcados(filtroEtiquetas).forEach(function (c) {
                if (c !== e.target && (c.value === '__sin__') !== esSin) c.checked = false;
            });
        });

        [filtroPlanos, filtroEtiquetas].forEach(function (filtro) {
            filtro.addEventListener('change', filtrar);
            filtro.querySelector('[data-buscar]').addEventListener('input', function () {
                const texto = this.value.trim().toLowerCase();
                filtro.querySelectorAll('label[data-texto]').forEach(function (label) {
                    label.hidden = texto !== '' && !label.dataset.texto.includes(texto);
                });
            });
        });

        // Un solo desplegable abierto a la vez, y se cierra al tocar afuera.
        document.addEventListener('click', function (e) {
            [filtroPlanos, filtroEtiquetas].forEach(function (filtro) {
                if (!filtro.contains(e.target)) filtro.open = false;
            });
        });

        document.getElementById('btn-limpiar-filtros-fotos').addEventListener('click', function () {
            [filtroPlanos, filtroEtiquetas].forEach(function (filtro) {
                marcados(filtro).forEach(c => { c.checked = false; });
            });
            filtrar();
        });

        function alternar(foto) {
            const posicion = seleccion.indexOf(foto);
            if (posicion === -1) {
                seleccion.push(foto);
            } else {
                seleccion.splice(posicion, 1);
            }
            pintarSeleccion();
        }

        function pintarSeleccion() {
            tarjetas.forEach(function ({ foto, el }) {
                const posicion = seleccion.indexOf(foto);
                el.classList.toggle('seleccionada', posicion !== -1);
                el.querySelector('.modal-foto-orden').textContent = posicion !== -1 ? posicion + 1 : '';
            });
            contador.textContent = seleccion.length === 0
                ? 'Ninguna foto seleccionada'
                : seleccion.length === 1 ? '1 foto seleccionada' : seleccion.length + ' fotos seleccionadas';
            btnConfirmar.disabled = seleccion.length === 0;
            // Con varias fotos se puede elegir: juntas con un solo pie, o cada una en su bloque.
            btnSeparadas.hidden = seleccion.length < 2;
            textoConfirmar.textContent = seleccion.length < 2 ? 'Agregar' : 'Agregar juntas';
        }

        function abrir(nuevoDestino) {
            destino = typeof nuevoDestino === 'function' ? nuevoDestino : agregarBloque;
            seleccion = [];
            inputPie.value = '';
            pintarSeleccion();
            filtrar();
            modal.classList.add('abierto');
        }

        function cerrar() {
            modal.classList.remove('abierto');
        }

        document.getElementById('btn-agregar-imagen').addEventListener('click', () => abrir());
        modal.querySelectorAll('[data-cerrar-modal]').forEach(b => b.addEventListener('click', cerrar));
        modal.addEventListener('click', function (e) {
            if (e.target === modal) cerrar();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('abierto')) cerrar();
        });
        // Enter en el pie confirma, sin enviar el formulario del informe.
        inputPie.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (!btnConfirmar.disabled) btnConfirmar.click();
            }
        });

        btnConfirmar.addEventListener('click', function () {
            if (!seleccion.length) return;
            destino(seleccion.slice(), inputPie.value.trim());
            cerrar();
        });

        // Una foto por bloque, sin pie: se completa cada uno fuera del modal.
        btnSeparadas.addEventListener('click', function () {
            if (!seleccion.length) return;
            const inputs = seleccion.map(foto => destino([foto], ''));
            cerrar();
            inputs[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            inputs[0].focus({ preventScroll: true });
        });

        // Bloque de Características de la obra.
        function agregarBloque(fotos, pie, faltantes) {
            const { bloque, input } = crearBloqueFotos(fotos, pie, faltantes, 'fotos_caracteristicas[' + indiceBloque++ + ']', true);
            bloques.appendChild(bloque);
            return input;
        }

        // Bloque de fotos con sus campos (nombreBase[ids], nombreBase[pie]).
        // faltantes: fotos del bloque guardado que ya no están en la galería.
        function crearBloqueFotos(fotos, pie, faltantes, nombreBase, conQuitar) {
            const bloque = document.createElement('div');
            bloque.className = 'bloque-foto';

            if (faltantes) {
                const aviso = document.createElement('div');
                aviso.className = 'bloque-foto-aviso';
                aviso.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ';
                aviso.appendChild(document.createTextNode(faltantes === 1
                    ? 'Una foto de este bloque se borró de la galería y ya no va al Word.'
                    : faltantes + ' fotos de este bloque se borraron de la galería y ya no van al Word.'));
                bloque.appendChild(aviso);
            }

            const imagenes = document.createElement('div');
            imagenes.className = 'bloque-foto-imagenes';
            // De a varias, cada foto lleva su letra (a), (b)… igual que en el Word.
            fotos.forEach(function (foto, n) {
                const img = document.createElement('img');
                img.src = foto.miniatura;
                img.alt = 'Foto';
                imagenes.appendChild(img);
                if (fotos.length > 1) {
                    const letra = document.createElement('span');
                    letra.className = 'bloque-foto-letra';
                    letra.textContent = '(' + String.fromCharCode(97 + n) + ')';
                    imagenes.appendChild(letra);
                }
            });

            const ids = document.createElement('input');
            ids.type = 'hidden';
            ids.name = nombreBase + '[ids]';
            ids.value = fotos.map(f => f.id).join(',');

            const fila = document.createElement('div');
            fila.className = 'bloque-foto-pie';
            const inputBloque = document.createElement('input');
            inputBloque.type = 'text';
            inputBloque.className = 'form-control';
            inputBloque.name = nombreBase + '[pie]';
            inputBloque.maxLength = 500;
            inputBloque.placeholder = 'Pie de foto';
            inputBloque.value = pie;
            inputBloque.setAttribute('aria-label', 'Pie de foto');

            const quitar = document.createElement('button');
            quitar.type = 'button';
            quitar.className = 'btn btn-sm btn-quitar-bloque';
            quitar.title = 'Quitar imagen';
            quitar.innerHTML = '<i class="fas fa-trash-alt"></i> Quitar';
            quitar.addEventListener('click', () => bloque.remove());

            fila.appendChild(inputBloque);
            if (conQuitar) fila.appendChild(quitar);
            bloque.append(imagenes, ids, fila);
            return { bloque, input: inputBloque };
        }

        // Para el Relevamiento geométrico (y otras secciones con fotos).
        window.InformeFotos = {
            abrir,
            crearBloque: crearBloqueFotos,
            // Fotos de la galería a partir de "1,2,3"; faltantes = las que ya no están.
            buscar(idsTexto) {
                const ids = String(idsTexto || '').split(',').map(Number).filter(Boolean);
                const fotos = ids.map(id => FOTOS.find(f => f.id === id)).filter(Boolean);
                return { fotos, faltantes: ids.length - fotos.length };
            },
        };

        // Secciones con descripción + fotografías (ver
        // informe/partials/descripcion-fotos): bloques {seccion}_fotos[k].
        document.querySelectorAll('[data-seccion-fotos]').forEach(function (seccion) {
            const prefijo = seccion.dataset.seccionFotos + '_fotos';
            const contenedor = seccion.querySelector('[data-bloques-fotos]');
            let indice = 0;

            function agregar(fotos, pie, faltantes) {
                const { bloque, input } = crearBloqueFotos(fotos, pie, faltantes, prefijo + '[' + indice++ + ']', true);
                contenedor.appendChild(bloque);
                return input;
            }

            seccion.querySelector('[data-agregar-fotos]').addEventListener('click', () => abrir(agregar));

            JSON.parse(seccion.dataset.guardados || '[]').forEach(function (guardado) {
                const { fotos, faltantes } = window.InformeFotos.buscar(guardado.ids);
                if (fotos.length || faltantes) agregar(fotos, guardado.pie || '', faltantes);
            });
        });

        // Bloques del informe guardado, con las fotos que siguen existiendo.
        Object.values(@json($v('fotos_caracteristicas', [])) || {}).forEach(function (guardado) {
            const ids = String(guardado.ids || '').split(',').map(Number).filter(Boolean);
            const fotos = ids.map(id => FOTOS.find(f => f.id === id)).filter(Boolean);
            const faltantes = ids.length - fotos.length;
            if (fotos.length || faltantes) {
                agregarBloque(fotos, guardado.pie || '', faltantes);
            }
        });
    })();

    // Secciones con contenido (Relevamiento geométrico, grupos de ensayos):
    // planos (con sus capas), bloques de fotos y textos, en el orden en que
    // se agregan. Cada sección es un [data-seccion] (ver
    // informe/partials/contenido-seccion) y sus campos llegan como
    // {seccion}_elementos[k][...], en el orden en que se ven en pantalla.
    (function () {
        pdfjsLib.GlobalWorkerOptions.workerSrc = @json(\App\Services\DescargaOfflineService::PDFJS_WORKER);

        const PLANOS = @json($planosObra);
        const URL_PLANO = @json(route('informe.generador.plano', [$obraTc->id, '__id__']));
        const icono = archivo => @json(asset('img/iconos')) + '/' + archivo;

        /* Capas que puede tener un plano: las mismas herramientas del visor
           (planos_tc/plano.blade.php), con su nombre, grupo e ícono. `escala`
           es el grupo de escala del visor (íconos de daños, ensayos y fotos). */
        const CAPAS = {
            fisura: { nombre: 'Fisura', icono: icono('Fisura.svg') },
            corrosion: { nombre: 'Corrosión', icono: icono('Corrosion.svg') },
            humedad: { nombre: 'Humedad', icono: icono('Humedad.svg') },
            coqueras: { nombre: 'Coqueras', icono: icono('Coqueras.svg') },
            fisura_ducto: { nombre: 'Fisura por ducto', icono: icono('Fisuras por ductos.svg') },
            junta_fria: { nombre: 'Junta fría', icono: icono('Junta fria.svg') },
            armadura_expuesta: { nombre: 'Armadura expuesta', icono: icono('Armadura expuesta.svg') },
            eflorescencia: { nombre: 'Eflorescencia', icono: icono('Eflorescencia.svg') },
            socavacion: { nombre: 'Socavación', icono: icono('socavacion.svg') },
            desprendimiento: { nombre: 'Desprendimiento', icono: icono('desprendimiento.svg') },
            exfoliacion: { nombre: 'Exfoliación', icono: icono('exfoliacion.svg') },
            desaplome: { nombre: 'Desaplome', icono: icono('desaplome.svg') },
            fisura_vertical: { nombre: 'Fisura vertical', icono: icono('Fisura vertical.svg'), escala: 'danos' },
            fisura_inclinada: { nombre: 'Fisura inclinada', icono: icono('Fisura inclinada.svg'), escala: 'danos' },
            fisura_semiinclinada: { nombre: 'Fisura semi-inclinada', icono: icono('Fisura seminclinada.svg'), escala: 'danos' },
            esclerometria: { nombre: 'Esclerometría', icono: icono('esclerometria.svg'), escala: 'ensayos' },
            carbonatacion: { nombre: 'Carbonatación', icono: icono('carbonatacion.svg'), escala: 'ensayos' },
            pachometria: { nombre: 'Pachometría', icono: icono('pachometria.svg'), escala: 'ensayos' },
            testigos: { nombre: 'Testigos', icono: icono('testigos.svg'), escala: 'ensayos' },
            ultrasonido: { nombre: 'Ultrasonido', icono: icono('ultrasonido.svg'), escala: 'ensayos' },
            resistividad: { nombre: 'Resistividad', icono: icono('Resistividad.svg'), escala: 'ensayos' },
            potencial: { nombre: 'Potencial', icono: icono('Potencial.svg'), escala: 'ensayos' },
            cloruros: { nombre: 'Cloruros', icono: icono('Cloruros.svg'), escala: 'ensayos' },
            georradar: { nombre: 'Georradar', icono: icono('georradar.svg'), escala: 'ensayos' },
            computo_fisuras: { nombre: 'Cómputo de fisuras', letra: 'F', escala: 'ensayos' },
            foto: { nombre: 'Fotografía', icono: icono('foto.svg'), escala: 'fotos' },
            texto: { nombre: 'Texto', icono: icono('texto.svg') },
            dibujo_libre: { nombre: 'Dibujo a mano alzada', icono: icono('mano_alzada.svg') },
            dibujo_libre_relleno: { nombre: 'Mano alzada con relleno', icono: icono('mano_alzada_relleno.svg') },
            circulo: { nombre: 'Círculo', icono: icono('circulo.svg') },
            circulo_relleno: { nombre: 'Círculo con relleno', icono: icono('circulo_relleno.svg') },
            rectangulo: { nombre: 'Rectángulo', icono: icono('rectangulo.svg') },
            rectangulo_relleno: { nombre: 'Rectángulo con relleno', icono: icono('rectangulo_relleno.svg') },
            linea_recta: { nombre: 'Línea recta', icono: icono('linea_recta.svg') },
        };
        const ORDEN_CAPAS = Object.keys(CAPAS);
        const GRUPO_ESCALA = {};
        ORDEN_CAPAS.forEach(tool => { if (CAPAS[tool].escala) GRUPO_ESCALA[tool] = CAPAS[tool].escala; });

        // Tamaño de la imagen que va al Word (≈ 300 ppp en el ancho útil de la hoja).
        const ANCHO_WORD_PX = 2400;
        const ALTO_WORD_PX = 3100;
        // Tamaño de la vista previa en pantalla.
        const LADO_VISTA_PX = 1400;

        /* ── Carga y dibujo de planos ── */
        const cacheIconos = {};
        function cargarIcono(url) {
            if (!url) return Promise.resolve(null);
            if (!cacheIconos[url]) {
                cacheIconos[url] = new Promise(resolve => {
                    const img = new Image();
                    img.onload = () => resolve(img);
                    img.onerror = () => resolve(null);
                    img.src = url;
                });
            }
            return cacheIconos[url];
        }

        // Una sola carga por plano aunque se agregue varias veces.
        const cachePlanos = {};
        function cargarPlano(id) {
            if (!cachePlanos[id]) {
                cachePlanos[id] = (async function () {
                    const respuesta = await fetch(URL_PLANO.replace('__id__', id), { headers: { Accept: 'application/json' } });
                    if (!respuesta.ok) throw new Error('No se pudo cargar el plano.');
                    const datos = await respuesta.json();
                    const pdf = await pdfjsLib.getDocument(datos.url).promise;
                    const pagina = await pdf.getPage(1);
                    const base = pagina.getViewport({ scale: 1, rotation: datos.rotacion });
                    const estado = datos.estado || {};
                    const trazos = (estado.trazos || []).map(t => Object.assign({}, t));
                    await Promise.all(trazos.filter(t => t.tipo === 'icono').map(async function (t) {
                        t.imagen = await cargarIcono(CAPAS[t.tool] && CAPAS[t.tool].icono);
                    }));
                    const usadas = new Set(trazos.map(t => t.tool));
                    return {
                        pagina,
                        rotacion: datos.rotacion,
                        ancho: base.width,
                        alto: base.height,
                        trazos,
                        escalas: Object.assign({ danos: 1, ensayos: 1, fotos: 1, texto: 1 }, estado.escalas || {}),
                        // Las del catálogo en su orden y, al final, cualquier otra.
                        capas: ORDEN_CAPAS.filter(t => usadas.has(t)).concat([...usadas].filter(t => !CAPAS[t])),
                    };
                })();
                cachePlanos[id].catch(() => { delete cachePlanos[id]; });
            }
            return cachePlanos[id];
        }

        async function renderizarPdf(info, factor) {
            const viewport = info.pagina.getViewport({ scale: factor, rotation: info.rotacion });
            const canvas = document.createElement('canvas');
            canvas.width = Math.round(viewport.width);
            canvas.height = Math.round(viewport.height);
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#fff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            await info.pagina.render({ canvasContext: ctx, viewport }).promise;
            return canvas;
        }

        function dibujarAnotaciones(ctx, info, factor, ocultas) {
            const capasVisibles = {};
            ocultas.forEach(tool => { capasVisibles[tool] = false; });
            PlanoRender.dibujarItems(ctx, info.trazos, {
                proyectar: (x, y) => ({ x: x * factor, y: y * factor }),
                escala: factor,
                escalas: info.escalas,
                grupoEscala: GRUPO_ESCALA,
                capasVisibles,
            });
        }

        /* ── Elementos ── */
        function crearHidden(nombre, valor) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = nombre;
            input.value = valor;
            return input;
        }

        function botonCabecera(iconoClase, titulo, accion) {
            const boton = document.createElement('button');
            boton.type = 'button';
            boton.title = titulo;
            boton.setAttribute('aria-label', titulo);
            boton.innerHTML = '<i class="fas ' + iconoClase + '"></i>';
            boton.addEventListener('click', accion);
            return boton;
        }

        /* ── Planillas de ensayo como tabla (PNG) ──
           Se dibujan con los datos que tenga la planilla de la obra en ese
           momento. Garamond 16 px = 12 pt, como el Normal de la plantilla;
           el PNG va al doble (ESCALA_PLANILLA en InformeGeneradorController). */
        const FUENTE_PLANILLA = "'EB Garamond', Garamond, serif";
        const ESCALA_PLANILLA = 2;
        const PLANILLAS = {
            esclerometria: {
                nombre: 'Planilla de esclerometría',
                url: @json(route('informe.generador.planilla.esclerometria', $obraTc->id)),
                // Clave del dato de cada punto y su título, en el orden de la planilla.
                columnas: [
                    { clave: 'nivel', titulo: 'Nivel' },
                    { clave: 'elemento', titulo: 'Elemento' },
                    { clave: 'identificacion', titulo: 'Identificación' },
                    { clave: 'direccion', titulo: 'Dirección' },
                    { clave: 'n_final', titulo: 'Índice esclerométrico corregido' },
                ],
                pie: 'Resultados del ensayo de esclerometría',
            },
            ultrasonido: {
                nombre: 'Planilla de ultrasonido',
                url: @json(route('informe.generador.planilla.ultrasonido', $obraTc->id)),
                columnas: [
                    { clave: 'nivel', titulo: 'Nivel' },
                    { clave: 'elemento', titulo: 'Elemento' },
                    { clave: 'identificacion', titulo: 'Identificación' },
                    { clave: 'velocidad', titulo: 'Velocidad (m/s)' },
                    { clave: 'compactacion', titulo: 'Calidad del hormigón' },
                ],
                pie: 'Resultados del ensayo de ultrasonido',
            },
        };

        /* Ensayos para la tabla "Referencias de ensayos" de los planos (ver
           InformeTc::ICONOS_ENSAYOS): letra, color, ícono y capa del plano. */
        const ICONOS_ENSAYOS = {!! json_encode(collect(\App\Models\InformeTc::ICONOS_ENSAYOS)->map(fn ($e) => ['nombre' => $e['nombre'], 'letra' => $e['letra'], 'color' => '#' . $e['color'], 'capa' => $e['capa'], 'url' => asset('img/iconos/' . $e['icono'])])) !!};
        // Ensayos (claves de ICONOS_ENSAYOS) cuyas capas están prendidas, en ese orden.
        function ensayosVisibles(capas, ocultas) {
            return Object.keys(ICONOS_ENSAYOS).filter(t => capas.includes(ICONOS_ENSAYOS[t].capa) && !ocultas.has(ICONOS_ENSAYOS[t].capa));
        }

        function cargarFuentes() {
            return Promise.all([
                document.fonts.load('400 16px ' + FUENTE_PLANILLA),
                document.fonts.load('700 16px ' + FUENTE_PLANILLA),
            ]);
        }

        // Puntos de la planilla de la obra (sólo lectura: la planilla se
        // modifica desde Planillas). Una carga compartida por página; `fresca`
        // la vuelve a pedir (al generar el Word o al restaurar).
        const cacheDatosPlanilla = {};
        function cargarPlanilla(clave, fresca) {
            if (fresca || !cacheDatosPlanilla[clave]) {
                cacheDatosPlanilla[clave] = (async function () {
                    const respuesta = await fetch(PLANILLAS[clave].url, { headers: { 'Accept': 'application/json' } });
                    if (!respuesta.ok) throw new Error('No se pudo cargar la planilla');
                    const datos = await respuesta.json();
                    const puntos = new Map();
                    (datos.puntos || []).forEach(p => puntos.set(Number(p.id), p));
                    return puntos;
                })();
                cacheDatosPlanilla[clave].catch(() => { delete cacheDatosPlanilla[clave]; });
            }
            return cacheDatosPlanilla[clave];
        }

        /* Disposición de una planilla en el informe: qué columnas (claves) y
           qué filas (id del punto) se muestran y en qué orden. Es lo único que
           guarda el informe; los valores siempre salen de la planilla. null =
           sin cambios (toda la planilla, incluidos los puntos que se agreguen). */
        function leerDisposicion(valor, config) {
            try {
                const d = typeof valor === 'string' ? JSON.parse(valor) : valor;
                if (!d || !Array.isArray(d.columnas) || !Array.isArray(d.filas)) return null;
                const validas = config.columnas.map(c => c.clave);
                const columnas = [...new Set(d.columnas)].filter(c => validas.includes(c));
                const filas = [...new Set(d.filas.map(Number))].filter(Number.isInteger);
                return columnas.length ? { columnas, filas } : null;
            } catch (error) {
                return null;
            }
        }

        // Tabla con encabezado en negrita, bordes negros y fondo blanco.
        function dibujarPlanilla(columnas, filas) {
            const tamano = 16;
            const margenCelda = 12;
            const altoFila = 30;
            const medidor = document.createElement('canvas').getContext('2d');
            const ancho = (texto, negrita) => {
                medidor.font = (negrita ? '700 ' : '400 ') + tamano + 'px ' + FUENTE_PLANILLA;
                return medidor.measureText(String(texto)).width;
            };
            const anchos = columnas.map((titulo, c) => Math.ceil(Math.max(ancho(titulo, true), ...filas.map(f => ancho(f[c], false))) + margenCelda * 2));
            const anchoTotal = anchos.reduce((a, b) => a + b, 0) + 1;
            const altoTotal = altoFila * (filas.length + 1) + 1;

            const canvas = document.createElement('canvas');
            canvas.width = anchoTotal * ESCALA_PLANILLA;
            canvas.height = altoTotal * ESCALA_PLANILLA;
            const ctx = canvas.getContext('2d');
            ctx.scale(ESCALA_PLANILLA, ESCALA_PLANILLA);
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, anchoTotal, altoTotal);
            ctx.fillStyle = '#000000';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            [columnas, ...filas].forEach(function (fila, n) {
                ctx.font = (n === 0 ? '700 ' : '400 ') + tamano + 'px ' + FUENTE_PLANILLA;
                let x = 0.5;
                fila.forEach(function (valor, c) {
                    ctx.fillText(String(valor), x + anchos[c] / 2, 0.5 + altoFila * n + altoFila / 2);
                    x += anchos[c];
                });
            });

            ctx.strokeStyle = '#000000';
            ctx.lineWidth = 1;
            ctx.beginPath();
            for (let n = 0; n <= filas.length + 1; n++) {
                ctx.moveTo(0.5, 0.5 + altoFila * n);
                ctx.lineTo(anchoTotal - 0.5, 0.5 + altoFila * n);
            }
            let x = 0.5;
            [0, ...anchos].forEach(function (a) {
                x += a;
                ctx.moveTo(x, 0.5);
                ctx.lineTo(x, altoTotal - 0.5);
            });
            ctx.stroke();
            return canvas;
        }

        // Arma una sección: sus elementos, los botones de agregar y lo guardado.
        function iniciarSeccion(contenedor) {
            const lista = contenedor.querySelector('[data-elementos]');
            const prefijo = contenedor.dataset.seccion + '_elementos';
            let indice = 0;

            // Las flechas de subir/bajar se deshabilitan en los extremos.
            function actualizarFlechas() {
                const elementos = [...lista.children];
                elementos.forEach(function (el, n) {
                    el.querySelector('[data-subir]').disabled = n === 0;
                    el.querySelector('[data-bajar]').disabled = n === elementos.length - 1;
                });
            }

            // Contenedor común: cabecera (tipo, flechas, quitar) + campo tipo.
            function crearElemento(tipo, iconoClase, titulo) {
                const k = indice++;
                const nombreBase = prefijo + '[' + k + ']';
                const el = document.createElement('div');
                el.className = 'rg-elemento';
                el.dataset.tipo = tipo;

                const cabecera = document.createElement('div');
                cabecera.className = 'rg-elemento-head';
                const tituloEl = document.createElement('span');
                tituloEl.className = 'titulo';
                tituloEl.innerHTML = '<i class="fas ' + iconoClase + '"></i>';
                tituloEl.appendChild(document.createTextNode(titulo));
                tituloEl.title = titulo;

                const subir = botonCabecera('fa-arrow-up', 'Subir', function () {
                    if (el.previousElementSibling) lista.insertBefore(el, el.previousElementSibling);
                    actualizarFlechas();
                });
                subir.dataset.subir = '';
                const bajar = botonCabecera('fa-arrow-down', 'Bajar', function () {
                    if (el.nextElementSibling) lista.insertBefore(el.nextElementSibling, el);
                    actualizarFlechas();
                });
                bajar.dataset.bajar = '';
                const quitar = botonCabecera('fa-trash-alt', 'Quitar', function () {
                    el.remove();
                    actualizarFlechas();
                });

                cabecera.append(tituloEl, subir, bajar, quitar);
                el.append(cabecera, crearHidden(nombreBase + '[tipo]', tipo));
                lista.appendChild(el);
                actualizarFlechas();
                return { el, nombreBase };
            }

            function crearCampoPie(nombre, valor, placeholder) {
                const fila = document.createElement('div');
                fila.className = 'rg-campo-pie';
                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'form-control';
                input.name = nombre;
                input.maxLength = 500;
                input.placeholder = placeholder;
                input.value = valor || '';
                input.setAttribute('aria-label', 'Pie de figura');
                fila.appendChild(input);
                return { fila, input };
            }

            // Plano con vista previa, capas para prender/apagar y su pie.
            function agregarPlano(planoId, guardado) {
                guardado = guardado || {};
                const plano = PLANOS.find(p => p.id === Number(planoId));
                const { el, nombreBase } = crearElemento('plano', 'fa-map', plano ? 'Plano: ' + plano.descripcion : 'Plano');

                if (!plano) {
                    const aviso = document.createElement('div');
                    aviso.className = 'rg-aviso';
                    aviso.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Este plano se borró de la obra y ya no va al Word.';
                    el.appendChild(aviso);
                    el.querySelector('input[name$="[tipo]"]').remove();
                    return null;
                }

                const ocultas = new Set(String(guardado.capas_ocultas || '').split(',').filter(Boolean));
                const inputOcultas = crearHidden(nombreBase + '[capas_ocultas]', [...ocultas].join(','));
                const inputImagen = crearHidden(nombreBase + '[imagen]', '');
                inputImagen.dataset.planoImagen = '';
                // En los grupos de ensayos el plano lleva su tabla "Referencias
                // de ensayos" con los ensayos de las capas prendidas.
                const conReferencias = (contenedor.dataset.trabajosWord || '').split(',').some(t => ICONOS_ENSAYOS[t]);
                const inputReferencias = crearHidden(nombreBase + '[referencias]', '');
                inputReferencias.dataset.planoImagen = '';

                const vista = document.createElement('div');
                vista.className = 'rg-plano-vista';
                const estadoEl = document.createElement('div');
                estadoEl.className = 'rg-plano-estado';
                estadoEl.textContent = 'Cargando plano…';
                vista.appendChild(estadoEl);

                const capasEl = document.createElement('div');
                capasEl.className = 'rg-capas';

                const { fila, input } = crearCampoPie(nombreBase + '[pie]', guardado.pie, 'Pie de figura (ej.: Planta de encofrado obtenida en el relevamiento geométrico)');

                el.append(vista, capasEl, fila, crearHidden(nombreBase + '[plano_id]', plano.id), inputOcultas, inputImagen, inputReferencias);

                let info = null;
                let fondo = null;
                let lienzo = null;
                let factorVista = 1;

                function redibujarVista() {
                    if (!info || !fondo) return;
                    const ctx = lienzo.getContext('2d');
                    ctx.drawImage(fondo, 0, 0);
                    dibujarAnotaciones(ctx, info, factorVista, ocultas);
                }

                function pintarCapas() {
                    capasEl.textContent = '';
                    if (!info.capas.length) {
                        const nada = document.createElement('small');
                        nada.className = 'ayuda';
                        nada.textContent = 'Este plano no tiene anotaciones.';
                        capasEl.appendChild(nada);
                        return;
                    }
                    const cabecera = document.createElement('div');
                    cabecera.className = 'rg-capas-head';
                    cabecera.appendChild(document.createTextNode('Capas'));
                    [['Prender todas', false], ['Apagar todas', true]].forEach(function ([texto, apagar]) {
                        const boton = document.createElement('button');
                        boton.type = 'button';
                        boton.textContent = texto;
                        boton.addEventListener('click', function () {
                            info.capas.forEach(tool => apagar ? ocultas.add(tool) : ocultas.delete(tool));
                            cambiarCapas();
                        });
                        cabecera.appendChild(boton);
                    });

                    const chips = document.createElement('div');
                    chips.className = 'rg-capas-lista';
                    info.capas.forEach(function (tool) {
                        const meta = CAPAS[tool] || { nombre: tool };
                        const apagada = ocultas.has(tool);
                        const chip = document.createElement('button');
                        chip.type = 'button';
                        chip.className = 'rg-capa' + (apagada ? ' apagada' : '');
                        chip.setAttribute('aria-pressed', apagada ? 'false' : 'true');
                        chip.title = (apagada ? 'Prender ' : 'Apagar ') + meta.nombre;
                        const ojo = document.createElement('i');
                        ojo.className = 'ojo fas ' + (apagada ? 'fa-eye-slash' : 'fa-eye');
                        chip.appendChild(ojo);
                        if (meta.icono) {
                            const img = document.createElement('img');
                            img.src = meta.icono;
                            img.alt = '';
                            chip.appendChild(img);
                        } else if (meta.letra) {
                            const letra = document.createElement('span');
                            letra.className = 'letra';
                            letra.textContent = meta.letra;
                            chip.appendChild(letra);
                        }
                        chip.appendChild(document.createTextNode(meta.nombre));
                        chip.addEventListener('click', function () {
                            if (ocultas.has(tool)) ocultas.delete(tool); else ocultas.add(tool);
                            cambiarCapas();
                        });
                        chips.appendChild(chip);
                    });
                    capasEl.append(cabecera, chips);

                    if (conReferencias) {
                        const ensayos = ensayosVisibles(info.capas, ocultas);
                        const nota = document.createElement('small');
                        nota.className = 'ayuda d-block mt-2';
                        nota.textContent = ensayos.length
                            ? 'Referencias de ensayos (entre el plano y su pie): ' + ensayos.map(t => ICONOS_ENSAYOS[t].letra + ' ' + ICONOS_ENSAYOS[t].nombre).join(' · ') + '.'
                            : 'Ningún ensayo tiene la capa prendida: el plano va sin tabla de referencias.';
                        capasEl.appendChild(nota);
                    }
                }

                function cambiarCapas() {
                    inputOcultas.value = [...ocultas].join(',');
                    pintarCapas();
                    redibujarVista();
                }

                const listo = (async function () {
                    try {
                        info = await cargarPlano(plano.id);
                        factorVista = Math.min(LADO_VISTA_PX / info.ancho, LADO_VISTA_PX / info.alto);
                        fondo = await renderizarPdf(info, factorVista);
                        lienzo = document.createElement('canvas');
                        lienzo.width = fondo.width;
                        lienzo.height = fondo.height;
                        vista.textContent = '';
                        vista.appendChild(lienzo);
                        // Las capas guardadas que el plano ya no tiene no cuentan.
                        [...ocultas].forEach(tool => { if (!info.capas.includes(tool)) ocultas.delete(tool); });
                        cambiarCapas();
                        document.dispatchEvent(new Event('informe:contenido-cargado'));
                    } catch (error) {
                        estadoEl.classList.add('error');
                        estadoEl.textContent = 'No se pudo cargar el plano. Recargá la página para reintentar.';
                        throw error;
                    }
                })();
                listo.catch(() => {});

                // JPG del plano con las capas prendidas, para el Word; de paso
                // arma su tabla de referencias con los ensayos de esas capas.
                el.exportar = async function () {
                    await listo;
                    const ensayos = conReferencias ? ensayosVisibles(info.capas, ocultas) : [];
                    inputReferencias.value = ensayos.length ? await dibujarReferencias(ensayos) : '';
                    const factor = Math.min(ANCHO_WORD_PX / info.ancho, ALTO_WORD_PX / info.alto);
                    const canvas = await renderizarPdf(info, factor);
                    dibujarAnotaciones(canvas.getContext('2d'), info, factor, ocultas);
                    return canvas.toDataURL('image/jpeg', 0.9);
                };
                el.inputImagen = inputImagen;
                el.descripcion = plano.descripcion;
                return input;
            }

            // Bloque de fotos, igual que en Características de la obra.
            function agregarFotos(fotos, pie, faltantes) {
                const { el, nombreBase } = crearElemento('fotos', 'fa-image', fotos.length > 1 ? fotos.length + ' fotos' : 'Foto');
                const { bloque, input } = InformeFotos.crearBloque(fotos, pie, faltantes, nombreBase, false);
                el.appendChild(bloque);
                return input;
            }

            // Planilla de ensayo de la obra como tabla, con su pie de tabla. Los
            // datos no se editan (la planilla se modifica sólo desde Planillas):
            // acá se elige qué filas y columnas van y en qué orden, y se puede
            // separar en varias tablas. Quitar algo sólo lo saca del informe.
            // [tabla] guarda esa disposición (JSON); vacío = toda la planilla.
            // `despuesDe`: elemento tras el cual va (al separar una tabla).
            function agregarPlanilla(clave, pie, disposicionGuardada, despuesDe) {
                const config = PLANILLAS[clave];
                if (!config) return null;
                const { el, nombreBase } = crearElemento('planilla', 'fa-table', config.nombre);
                if (despuesDe) {
                    lista.insertBefore(el, despuesDe.nextElementSibling);
                    actualizarFlechas();
                }

                let disposicion = leerDisposicion(disposicionGuardada, config);
                let puntos = null; // Map id → punto, cuando cargó.
                const titulo = clave => config.columnas.find(c => c.clave === clave).titulo;

                const vista = document.createElement('div');
                vista.className = 'rg-tabla-wrap';

                const pieTabla = document.createElement('div');
                pieTabla.className = 'rg-tabla-pie';
                const resumen = document.createElement('span');
                resumen.className = 'resumen';
                const restaurar = document.createElement('button');
                restaurar.type = 'button';
                restaurar.innerHTML = '<i class="fas fa-undo"></i> ';
                restaurar.appendChild(document.createTextNode('Mostrar toda la planilla'));
                restaurar.title = 'Vuelve a mostrar todas las filas y columnas de la planilla, en su orden original';
                pieTabla.append(resumen, restaurar);

                const { fila, input } = crearCampoPie(nombreBase + '[pie]', pie, 'Pie de tabla (ej.: ' + config.pie + ')');
                input.setAttribute('aria-label', 'Pie de tabla');
                const inputDisposicion = crearHidden(nombreBase + '[tabla]', '');
                const inputImagen = crearHidden(nombreBase + '[imagen]', '');
                inputImagen.dataset.planoImagen = '';
                el.append(vista, pieTabla, fila, crearHidden(nombreBase + '[planilla]', clave), inputDisposicion, inputImagen);

                // Columnas y filas que se ven: las de la disposición (sin los
                // puntos que ya no están en la planilla) o toda la planilla.
                function actual() {
                    if (!disposicion) {
                        return { columnas: config.columnas.map(c => c.clave), filas: [...puntos.keys()] };
                    }
                    disposicion.filas = disposicion.filas.filter(id => puntos.has(id));
                    return disposicion;
                }

                // Al primer cambio se fija la disposición (deja de ser "toda la planilla").
                function modificar(cambio) {
                    const d = actual();
                    disposicion = { columnas: d.columnas.slice(), filas: d.filas.slice() };
                    cambio(disposicion);
                    renderizar();
                }

                function guardarDisposicion() {
                    inputDisposicion.value = disposicion ? JSON.stringify(disposicion) : '';
                    restaurar.hidden = !disposicion;
                }

                function mostrarEstado(texto, error) {
                    const estadoEl = document.createElement('div');
                    estadoEl.className = 'rg-plano-estado' + (error ? ' error' : '');
                    estadoEl.textContent = texto;
                    vista.replaceChildren(estadoEl);
                    resumen.textContent = '';
                }

                function botonCtl(iconoClase, tituloBoton, deshabilitado, accion, peligro) {
                    const boton = botonCabecera(iconoClase, tituloBoton, accion);
                    boton.disabled = deshabilitado;
                    if (peligro) boton.className = 'peligro';
                    return boton;
                }

                function controles(...botones) {
                    const grupo = document.createElement('div');
                    grupo.className = 'rg-ctl';
                    grupo.append(...botones);
                    return grupo;
                }

                function celda(etiqueta, texto) {
                    const c = document.createElement(etiqueta);
                    const div = document.createElement('div');
                    div.className = 'celda';
                    div.textContent = texto;
                    c.appendChild(div);
                    return c;
                }

                const mover = (arr, de, a) => arr.splice(a, 0, arr.splice(de, 1)[0]);

                // Esta fila y las de abajo pasan a una tabla nueva, justo debajo
                // de ésta, con las mismas columnas y su propio pie.
                function separar(r) {
                    let resto;
                    modificar(d => { resto = d.filas.splice(r); });
                    enfocar(agregarPlanilla(clave, '', { columnas: disposicion.columnas.slice(), filas: resto }, el));
                }

                function renderizar() {
                    guardarDisposicion();
                    if (!puntos) return;
                    const { columnas, filas } = actual();
                    const nCol = columnas.length;
                    const nFil = filas.length;

                    resumen.classList.toggle('error', !nFil);
                    resumen.textContent = !nFil
                        ? (puntos.size ? 'La tabla no tiene filas: no va al Word.' : 'La planilla todavía no tiene puntos ensayados: no va al Word hasta que los tenga.')
                        : nFil + (nFil === 1 ? ' fila' : ' filas') + ' · ' + nCol + (nCol === 1 ? ' columna' : ' columnas')
                            + (disposicion ? '' : ' (toda la planilla)') + '. Los datos se modifican desde Planillas.';

                    const t = document.createElement('table');
                    t.className = 'rg-tabla';
                    const thead = t.createTHead();
                    const esquina = () => {
                        const th = document.createElement('th');
                        th.className = 'ctl';
                        return th;
                    };

                    // Controles de columnas.
                    const trCtl = thead.insertRow();
                    trCtl.appendChild(esquina());
                    columnas.forEach(function (_, c) {
                        const th = esquina();
                        th.appendChild(controles(
                            botonCtl('fa-arrow-left', 'Mover columna a la izquierda', c === 0, () => modificar(d => mover(d.columnas, c, c - 1))),
                            botonCtl('fa-arrow-right', 'Mover columna a la derecha', c === nCol - 1, () => modificar(d => mover(d.columnas, c, c + 1))),
                            botonCtl('fa-times', 'Quitar columna del informe', nCol === 1, () => modificar(d => d.columnas.splice(c, 1)), true),
                        ));
                        trCtl.appendChild(th);
                    });

                    // Títulos.
                    const trTitulos = thead.insertRow();
                    trTitulos.appendChild(esquina());
                    columnas.forEach(c => trTitulos.appendChild(celda('th', titulo(c))));

                    const tbody = t.createTBody();
                    filas.forEach(function (id, r) {
                        const tr = tbody.insertRow();
                        const ctl = tr.insertCell();
                        ctl.className = 'ctl';
                        ctl.appendChild(controles(
                            botonCtl('fa-arrow-up', 'Subir fila', r === 0, () => modificar(d => mover(d.filas, r, r - 1))),
                            botonCtl('fa-arrow-down', 'Bajar fila', r === nFil - 1, () => modificar(d => mover(d.filas, r, r + 1))),
                            botonCtl('fa-cut', 'Separar la tabla desde esta fila (esta fila y las de abajo pasan a una tabla nueva)', r === 0, () => separar(r)),
                            botonCtl('fa-times', 'Quitar fila del informe', false, () => modificar(d => d.filas.splice(r, 1)), true),
                        ));
                        const punto = puntos.get(id);
                        columnas.forEach(c => tr.appendChild(celda('td', String(punto[c] ?? ''))));
                    });

                    vista.replaceChildren(t);
                }

                restaurar.addEventListener('click', async function () {
                    const ok = await confirmar({
                        titulo: 'Mostrar toda la planilla',
                        texto: 'Se vuelven a mostrar todas las filas y columnas de la planilla en su orden original y se pierden los cambios hechos en esta tabla del informe (filas o columnas quitadas, orden y separación). La planilla original no se modifica.',
                        aceptar: 'Mostrar toda la planilla',
                    });
                    if (!ok) return;
                    disposicion = null;
                    renderizar();
                });

                guardarDisposicion();
                mostrarEstado('Cargando planilla…');
                cargarPlanilla(clave).then(function (datos) {
                    puntos = datos;
                    renderizar();
                    document.dispatchEvent(new Event('informe:contenido-cargado'));
                }).catch(function () {
                    mostrarEstado('No se pudo cargar la planilla. Recargá la página para reintentar.', true);
                });

                // PNG de la tabla para el Word, con los datos actuales de la
                // planilla y la disposición elegida; sin filas no va nada.
                el.exportar = async function () {
                    puntos = await cargarPlanilla(clave, true);
                    renderizar();
                    const { columnas, filas } = actual();
                    if (!filas.length) return '';
                    await cargarFuentes();
                    return dibujarPlanilla(
                        columnas.map(titulo),
                        filas.map(id => columnas.map(c => String(puntos.get(id)[c] ?? '')))
                    ).toDataURL('image/png');
                };
                el.inputImagen = inputImagen;
                el.descripcion = config.nombre;
                return input;
            }

            function agregarTexto(texto) {
                const { el, nombreBase } = crearElemento('texto', 'fa-paragraph', 'Texto');
                const area = document.createElement('textarea');
                area.className = 'form-control';
                area.name = nombreBase + '[texto]';
                area.rows = 4;
                area.placeholder = 'Cada renglón se convierte en un párrafo del Word.';
                area.setAttribute('aria-label', 'Texto');
                area.value = texto || '';
                el.appendChild(area);
                return area;
            }

            // Sólo los botones que tiene la sección (ver contenido-seccion).
            const boton = tipo => contenedor.querySelector('[data-agregar="' + tipo + '"]');
            boton('plano')?.addEventListener('click', () => abrirModal(agregarPlano));
            boton('fotos')?.addEventListener('click', () => InformeFotos.abrir(agregarFotos));
            boton('planilla')?.addEventListener('click', () => enfocar(agregarPlanilla(contenedor.dataset.planilla, '')));
            boton('texto')?.addEventListener('click', () => enfocar(agregarTexto('')));

            // Elementos del informe guardado.
            JSON.parse(contenedor.dataset.guardados || '[]').forEach(function (guardado) {
                if (guardado.tipo === 'plano') {
                    agregarPlano(guardado.plano_id, guardado);
                } else if (guardado.tipo === 'fotos') {
                    const { fotos, faltantes } = InformeFotos.buscar(guardado.ids);
                    if (fotos.length || faltantes) agregarFotos(fotos, guardado.pie || '', faltantes);
                } else if (guardado.tipo === 'planilla') {
                    agregarPlanilla(guardado.planilla, guardado.pie, guardado.tabla);
                } else if (guardado.tipo === 'texto') {
                    agregarTexto(guardado.texto);
                }
            });
        }

        /* ── Modal de confirmación: confirmar({ titulo, texto, aceptar }) → Promise<boolean> ── */
        const modalConfirmar = document.getElementById('modal-confirmar');
        let resolverConfirmar = null;
        function confirmar({ titulo, texto, aceptar }) {
            if (resolverConfirmar) resolverConfirmar(false);
            document.getElementById('modal-confirmar-titulo').textContent = titulo;
            document.getElementById('modal-confirmar-texto').textContent = texto;
            const botonAceptar = document.getElementById('modal-confirmar-aceptar');
            botonAceptar.textContent = aceptar || 'Aceptar';
            const foco = document.activeElement;
            modalConfirmar.classList.add('abierto');
            botonAceptar.focus();
            return new Promise(function (resolve) {
                resolverConfirmar = function (respuesta) {
                    resolverConfirmar = null;
                    modalConfirmar.classList.remove('abierto');
                    if (foco && foco.isConnected) foco.focus();
                    resolve(respuesta);
                };
            });
        }
        modalConfirmar.querySelectorAll('[data-confirmar]').forEach(function (boton) {
            boton.addEventListener('click', () => resolverConfirmar && resolverConfirmar(boton.dataset.confirmar === 'si'));
        });
        modalConfirmar.addEventListener('click', function (e) {
            if (e.target === modalConfirmar && resolverConfirmar) resolverConfirmar(false);
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && resolverConfirmar) resolverConfirmar(false);
        });

        function enfocar(campo) {
            if (!campo) return;
            campo.scrollIntoView({ behavior: 'smooth', block: 'center' });
            campo.focus({ preventScroll: true });
        }

        /* ── Modal de planos ── */
        const modal = document.getElementById('modal-planos');
        const buscador = document.getElementById('modal-planos-buscar');
        const listaPlanos = document.getElementById('modal-planos-lista');
        // agregarPlano de la sección desde la que se abrió el modal.
        let destinoPlano = null;

        function pintarPlanos() {
            const filtro = buscador.value.trim().toLowerCase();
            listaPlanos.textContent = '';
            let carpetaActual = null;
            const visibles = PLANOS.filter(p => !filtro || (p.descripcion + ' ' + p.carpeta).toLowerCase().includes(filtro));
            visibles.forEach(function (plano) {
                if (plano.carpeta !== carpetaActual) {
                    carpetaActual = plano.carpeta;
                    const titulo = document.createElement('div');
                    titulo.className = 'modal-planos-carpeta';
                    titulo.textContent = carpetaActual;
                    listaPlanos.appendChild(titulo);
                }
                const boton = document.createElement('button');
                boton.type = 'button';
                boton.className = 'modal-plano';
                boton.innerHTML = '<i class="fas fa-map"></i>';
                boton.appendChild(document.createTextNode(plano.descripcion));
                if (plano.anotaciones) {
                    const marca = document.createElement('span');
                    marca.className = 'marca';
                    marca.textContent = 'Con anotaciones';
                    boton.appendChild(marca);
                }
                boton.addEventListener('click', function () {
                    cerrarModal();
                    enfocar(destinoPlano(plano.id));
                });
                listaPlanos.appendChild(boton);
            });
            if (!visibles.length) {
                const vacio = document.createElement('div');
                vacio.className = 'vacio';
                vacio.innerHTML = '<i class="fas fa-map"></i>';
                vacio.appendChild(document.createTextNode(PLANOS.length ? 'No hay planos que coincidan con la búsqueda.' : 'Esta obra todavía no tiene planos.'));
                listaPlanos.appendChild(vacio);
            }
        }

        function abrirModal(destino) {
            destinoPlano = destino;
            buscador.value = '';
            pintarPlanos();
            modal.classList.add('abierto');
            buscador.focus();
        }
        function cerrarModal() {
            modal.classList.remove('abierto');
        }

        buscador.addEventListener('input', pintarPlanos);
        buscador.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') e.preventDefault();
        });
        modal.querySelectorAll('[data-cerrar-modal]').forEach(b => b.addEventListener('click', cerrarModal));
        modal.addEventListener('click', function (e) {
            if (e.target === modal) cerrarModal();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('abierto')) cerrarModal();
        });

        document.querySelectorAll('[data-seccion]').forEach(iniciarSeccion);

        /* ── Generar Word: antes de enviar se arma el JPG de cada plano con
           las capas prendidas en ese momento y el PNG de cada planilla con
           sus datos actuales. Guardar no manda imágenes. ── */
        const form = document.getElementById('form-informe');
        const btnGenerar = document.getElementById('btn-generar');
        let imagenesListas = false;

        function limpiarImagenes() {
            form.querySelectorAll('[data-plano-imagen]').forEach(input => { input.value = ''; });
        }

        // Planos y planillas de las secciones que van al Word (las que tienen
        // data-trabajos-word y alguno de esos trabajos activado).
        function planosParaWord() {
            return [...form.querySelectorAll('[data-trabajos-word]')]
                .filter(seccion => seccion.dataset.trabajosWord.split(',')
                    .some(trabajo => document.querySelector('[data-trabajo="' + trabajo + '"]').checked))
                .flatMap(seccion => [...seccion.querySelector('[data-elementos]').children].filter(el => el.exportar));
        }

        /* ── Tabla "Referencias de ensayos" (imagen): en los grupos de
           ensayos va entre cada plano y su pie. Una fila por ensayo con la
           capa prendida en el plano: letra con su color, nombre e ícono. ── */

        // Ícono del ensayo recortado a lo dibujado (los SVG traen mucho margen).
        const cacheIconosRecortados = {};
        function iconoRecortado(url) {
            if (!cacheIconosRecortados[url]) {
                cacheIconosRecortados[url] = cargarIcono(url).then(function (img) {
                    if (!img) throw new Error('No se pudo cargar el ícono.');
                    const ancho = 800;
                    const alto = Math.round(ancho * (img.naturalHeight || 1) / (img.naturalWidth || 1)) || ancho;
                    const canvas = document.createElement('canvas');
                    canvas.width = ancho;
                    canvas.height = alto;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, ancho, alto);
                    const pixeles = ctx.getImageData(0, 0, ancho, alto).data;
                    let x0 = ancho, y0 = alto, x1 = -1, y1 = -1;
                    for (let y = 0; y < alto; y++) {
                        for (let x = 0; x < ancho; x++) {
                            if (pixeles[(y * ancho + x) * 4 + 3] > 8) {
                                if (x < x0) x0 = x;
                                if (x > x1) x1 = x;
                                if (y < y0) y0 = y;
                                if (y > y1) y1 = y;
                            }
                        }
                    }
                    if (x1 < 0) return canvas;
                    const recorte = document.createElement('canvas');
                    recorte.width = x1 - x0 + 1;
                    recorte.height = y1 - y0 + 1;
                    recorte.getContext('2d').drawImage(canvas, x0, y0, recorte.width, recorte.height, 0, 0, recorte.width, recorte.height);
                    return recorte;
                });
                cacheIconosRecortados[url].catch(() => { delete cacheIconosRecortados[url]; });
            }
            return cacheIconosRecortados[url];
        }

        // Misma letra y bordes que las planillas: título en una fila
        // combinada y centrada, y las columnas letra, ensayo e ícono.
        async function dibujarReferencias(trabajos) {
            await cargarFuentes();
            const ensayos = trabajos.map(t => ICONOS_ENSAYOS[t]);
            const iconos = await Promise.all(ensayos.map(e => iconoRecortado(e.url).catch(() => null)));

            const tamano = 16;
            const margenCelda = 12;
            const altoFila = 36;
            const altoIcono = 24;
            const titulo = 'Referencias de ensayos';
            const medidor = document.createElement('canvas').getContext('2d');
            const ancho = (texto, negrita) => {
                medidor.font = (negrita ? '700 ' : '400 ') + tamano + 'px ' + FUENTE_PLANILLA;
                return medidor.measureText(texto).width;
            };
            const anchoIcono = icono => icono ? altoIcono * icono.width / icono.height : 0;
            const anchos = [
                Math.ceil(Math.max(50, ...ensayos.map(e => ancho(e.letra, true) + margenCelda * 2))),
                Math.ceil(Math.max(...ensayos.map(e => ancho(e.nombre, false) + margenCelda * 2))),
                Math.ceil(Math.max(50, ...iconos.map(i => anchoIcono(i) + margenCelda * 2))),
            ];
            // Si el título no entra, se ensancha la columna del nombre.
            const falta = Math.ceil(ancho(titulo, true) + margenCelda * 2) - anchos.reduce((a, b) => a + b, 0);
            if (falta > 0) anchos[1] += falta;
            const anchoTotal = anchos.reduce((a, b) => a + b, 0) + 1;
            const altoTotal = altoFila * (ensayos.length + 1) + 1;

            const canvas = document.createElement('canvas');
            canvas.width = anchoTotal * ESCALA_PLANILLA;
            canvas.height = altoTotal * ESCALA_PLANILLA;
            const ctx = canvas.getContext('2d');
            ctx.scale(ESCALA_PLANILLA, ESCALA_PLANILLA);
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, anchoTotal, altoTotal);
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            ctx.fillStyle = '#000000';
            ctx.font = '700 ' + tamano + 'px ' + FUENTE_PLANILLA;
            ctx.fillText(titulo, anchoTotal / 2, 0.5 + altoFila / 2);

            ensayos.forEach(function (ensayo, n) {
                const centroY = 0.5 + altoFila * (n + 1) + altoFila / 2;
                let x = 0.5;
                ctx.font = '700 ' + tamano + 'px ' + FUENTE_PLANILLA;
                ctx.fillStyle = ensayo.color;
                ctx.fillText(ensayo.letra, x + anchos[0] / 2, centroY);
                x += anchos[0];
                ctx.font = '400 ' + tamano + 'px ' + FUENTE_PLANILLA;
                ctx.fillStyle = '#000000';
                ctx.fillText(ensayo.nombre, x + anchos[1] / 2, centroY);
                x += anchos[1];
                const icono = iconos[n];
                if (icono) {
                    const w = anchoIcono(icono);
                    ctx.drawImage(icono, x + (anchos[2] - w) / 2, centroY - altoIcono / 2, w, altoIcono);
                }
            });

            ctx.strokeStyle = '#000000';
            ctx.lineWidth = 1;
            ctx.beginPath();
            for (let n = 0; n <= ensayos.length + 1; n++) {
                ctx.moveTo(0.5, 0.5 + altoFila * n);
                ctx.lineTo(anchoTotal - 0.5, 0.5 + altoFila * n);
            }
            // Bordes de los costados de punta a punta; los internos, desde
            // debajo del título (la fila del título es una sola celda).
            let x = 0.5;
            [0, ...anchos].forEach(function (a, c) {
                x += a;
                const exterior = c === 0 || c === anchos.length;
                ctx.moveTo(x, exterior ? 0.5 : 0.5 + altoFila);
                ctx.lineTo(x, altoTotal - 0.5);
            });
            ctx.stroke();
            return canvas.toDataURL('image/png');
        }

        form.addEventListener('submit', async function (e) {
            if (e.submitter !== btnGenerar) {
                limpiarImagenes();
                return;
            }
            if (imagenesListas) {
                imagenesListas = false;
                return;
            }
            const planos = planosParaWord();
            if (!planos.length) return;

            e.preventDefault();
            const contenidoBoton = btnGenerar.innerHTML;
            btnGenerar.disabled = true;
            btnGenerar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparando imágenes…';
            try {
                for (const el of planos) {
                    try {
                        el.inputImagen.value = await el.exportar();
                    } catch (error) {
                        throw new Error('No se pudo preparar "' + el.descripcion + '".');
                    }
                }
                imagenesListas = true;
                // requestSubmit no funciona con el botón deshabilitado.
                btnGenerar.disabled = false;
                form.requestSubmit(btnGenerar);
            } catch (error) {
                alert(error.message);
            } finally {
                // Los datos del envío ya se armaron: las imágenes no hacen falta más.
                imagenesListas = false;
                limpiarImagenes();
                btnGenerar.disabled = false;
                btnGenerar.innerHTML = contenidoBoton;
            }
        });
    })();

    /* ── Vista previa del informe completo ──
       Se arma con lo que hay en pantalla, en el mismo orden y con los mismos
       textos fijos que InformeGeneradorController::generar. Se rehace cada
       vez que se muestra (y cuando termina de cargar un plano o planilla).
       Cada parte se puede tocar para ir a editarla. Todo lo que escribe el
       usuario entra como texto (textContent), nunca como HTML. */
    (function () {
        const TRABAJOS = @json(\App\Models\InformeTc::TRABAJOS);
        const GRUPO_RESISTENCIA = @json(\App\Models\InformeTc::GRUPOS_ENSAYOS['resistencia']);
        const panel = document.getElementById('panel-vista-previa');
        const documento = document.getElementById('vp-documento');

        const valor = id => (document.getElementById(id)?.value || '').trim();
        const activo = trabajo => !!document.querySelector('[data-trabajo="' + trabajo + '"]:checked');
        const renglones = texto => String(texto || '').split(/\r?\n/).map(r => r.trim()).filter(Boolean);
        const conPunto = texto => /[.!?…]$/.test(texto.trimEnd()) ? texto.trimEnd() : texto.trimEnd() + '.';

        function el(etiqueta, clase, texto) {
            const nodo = document.createElement(etiqueta);
            if (clase) nodo.className = clase;
            if (texto !== undefined) nodo.textContent = texto;
            return nodo;
        }

        // Texto o, si falta, la marca amarilla de lo que hay que completar.
        function dato(destino, texto, faltante) {
            if (texto) {
                destino.appendChild(document.createTextNode(texto));
            } else {
                destino.appendChild(el('span', 'falta', faltante));
            }
        }

        let hoja = null;
        const titulos = []; // Para el índice: { nivel, texto }.

        function nuevaHoja(clase) {
            hoja = el('div', 'vp-hoja' + (clase ? ' ' + clase : ''));
            documento.appendChild(hoja);
            return hoja;
        }

        // Bloque que al tocarlo abre su panel de edición.
        function parte(idPanel) {
            const bloque = el('div', 'vp-parte');
            bloque.title = 'Editar esta parte';
            bloque.addEventListener('click', function () {
                document.querySelector('.parte[data-panel="' + idPanel + '"]')?.click();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            hoja.appendChild(bloque);
            return bloque;
        }

        function titulo(destino, texto, nivel) {
            destino.appendChild(el(nivel === 1 ? 'h2' : nivel === 2 ? 'h3' : 'h4', 'vp-t' + nivel, texto));
            if (nivel <= 2) titulos.push({ nivel, texto });
        }

        // Un párrafo por renglón; sin texto, un aviso (que no va al Word).
        function parrafos(destino, texto, puntoFinal, avisoVacio) {
            const lineas = renglones(texto);
            lineas.forEach(r => destino.appendChild(el('p', null, puntoFinal ? conPunto(r) : r)));
            if (!lineas.length && avisoVacio) destino.appendChild(el('div', 'vp-vacio', avisoVacio));
        }

        function lista(destino, items) {
            if (!items.length) return;
            const ul = el('ul');
            items.forEach(texto => ul.appendChild(el('li', null, texto)));
            destino.appendChild(ul);
        }

        // Igual que descripcionTrabajo(): el último renglón termina en dos puntos.
        function descripcionTrabajo(destino, texto) {
            const lineas = renglones(texto);
            if (!lineas.length) return;
            lineas[lineas.length - 1] = lineas[lineas.length - 1].replace(/[\s.:;,]+$/, '') + ':';
            lineas.forEach(r => destino.appendChild(el('p', null, r)));
        }

        // Bloques de fotos (.bloque-foto) como en fotos(): las imágenes juntas
        // con su letra y un único pie "(a, b y c) Texto.".
        function fotos(destino, bloques) {
            bloques.forEach(function (bloque) {
                const imagenes = [...bloque.querySelectorAll('.bloque-foto-imagenes img')];
                if (!imagenes.length) return;
                const varias = imagenes.length > 1;
                const fila = el('div', 'vp-figura' + (varias ? '' : ' sola'));
                const letras = [];
                imagenes.forEach(function (img, n) {
                    const copia = el('img');
                    copia.src = img.src;
                    copia.alt = '';
                    fila.appendChild(copia);
                    if (varias) {
                        letras.push(String.fromCharCode(97 + n));
                        fila.appendChild(el('span', 'letra', '(' + letras[n] + ')'));
                    }
                });
                const textoPie = (bloque.querySelector('.bloque-foto-pie input')?.value || '').trim();
                let pie = textoPie ? conPunto(textoPie) : '';
                if (varias) {
                    const ultima = letras.pop();
                    pie = ('(' + (letras.length ? letras.join(', ') + ' y ' : '') + ultima + ') ' + pie).trim();
                }
                destino.append(fila, el('p', 'vp-pie', pie));
            });
        }

        // Plano tal como se ve en su panel (con las capas prendidas) y su pie.
        function plano(destino, elemento) {
            if (!elemento.querySelector('input[name$="[tipo]"]')) return; // Plano borrado de la obra.
            const lienzo = elemento.querySelector('.rg-plano-vista canvas');
            const pie = (elemento.querySelector('input[name$="[pie]"]')?.value || '').trim();
            if (!lienzo || !lienzo.width) {
                destino.appendChild(el('p', 'vp-cargando', 'Cargando plano…'));
                return;
            }
            const copia = document.createElement('canvas');
            copia.width = lienzo.width;
            copia.height = lienzo.height;
            copia.getContext('2d').drawImage(lienzo, 0, 0);
            const fila = el('div', 'vp-figura');
            fila.appendChild(copia);
            destino.append(fila, el('p', 'vp-pie', pie ? conPunto(pie) : ''));
        }

        // Planilla: pie arriba y la tabla sin los controles de filas y columnas.
        function planilla(destino, elemento) {
            const tabla = elemento.querySelector('.rg-tabla');
            if (!tabla) {
                destino.appendChild(el('p', 'vp-cargando', 'Cargando planilla…'));
                return;
            }
            if (!tabla.tBodies[0] || !tabla.tBodies[0].rows.length) return; // Sin filas no va al Word.
            const copia = tabla.cloneNode(true);
            copia.className = '';
            copia.tHead.rows[0].remove();
            copia.querySelectorAll('.ctl').forEach(c => c.remove());
            const pie = (elemento.querySelector('input[name$="[pie]"]')?.value || '').trim();
            const contenedor = el('div', 'vp-tabla');
            contenedor.appendChild(copia);
            destino.append(el('p', 'vp-pie', pie ? conPunto(pie) : ''), contenedor);
        }

        // Planos, fotos, planillas y textos de una sección, en su orden.
        function elementos(destino, seccion) {
            const lista = document.querySelector('[data-seccion="' + seccion + '"] [data-elementos]');
            if (!lista) return;
            [...lista.children].forEach(function (elemento) {
                const tipo = elemento.dataset.tipo;
                if (tipo === 'plano') plano(destino, elemento);
                else if (tipo === 'fotos') fotos(destino, [...elemento.querySelectorAll('.bloque-foto')]);
                else if (tipo === 'planilla') planilla(destino, elemento);
                else if (tipo === 'texto') parrafos(destino, elemento.querySelector('textarea')?.value, true);
            });
        }

        function fechaCorta(iso) {
            const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(iso || '');
            return m ? m[3] + '/' + m[2] + '/' + m[1] : '';
        }

        function caratula() {
            nuevaHoja();
            const p = parte('panel-caratula');
            p.classList.add('vp-caratula');
            const centro = el('div', 'vp-car-centro');
            const t = el('div');
            dato(t, valor('car-titulo'), '[Título del informe]');
            const obra = el('div', null, 'Obra: ');
            dato(obra, valor('car-obra'), '[obra]');
            const mesAnio = el('div');
            dato(mesAnio, [valor('car-mes'), valor('car-anio')].filter(Boolean).join(' - '), '[mes - año]');
            centro.append(t, obra, mesAnio);

            const pie = el('div', 'vp-car-pie');
            const peticionario = el('p', null, 'Peticionario: ');
            dato(peticionario, valor('car-peticionario'), '[peticionario]');
            const referencia = el('p', null, 'Ref.: ');
            dato(referencia, valor('car-referencia'), '[referencia]');
            pie.append(peticionario, referencia);
            p.append(centro, pie);
        }

        function cuerpo() {
            // Información general, Objetivo y Datos previos van seguidos.
            nuevaHoja();
            let p = parte('panel-info-general');
            titulo(p, 'Información general', 1);
            const parrafoIg = el('p');
            window.armarInfoGeneral(parrafoIg);
            p.appendChild(parrafoIg);

            p = parte('panel-objetivo');
            titulo(p, 'Objetivo y Alcance', 1);
            parrafos(p, valor('obj-texto'), false, 'Falta completar el objetivo y alcance.');

            p = parte('panel-datos-previos');
            titulo(p, 'Datos previos para la realización del estudio', 1);
            titulo(p, 'Documentación recibida', 2);
            const documentos = [...document.querySelectorAll('#documentos .documento')].map(function (doc) {
                const campo = nombre => (doc.querySelector('[name$="[' + nombre + ']"]')?.value || '').trim();
                const remitente = campo('remitente');
                const fecha = fechaCorta(campo('fecha'));
                const entrega = remitente || fecha
                    ? 'Entregado' + (remitente ? ' por ' + remitente : '') + (fecha ? ' en fecha ' + fecha : '')
                    : '';
                const texto = [campo('tipo'), campo('nombre'), entrega].filter(Boolean).join(' - ');
                return texto ? conPunto(texto) : '';
            }).filter(Boolean);
            lista(p, documentos);
            if (!documentos.length) p.appendChild(el('div', 'vp-vacio', 'No hay documentos cargados.'));
            titulo(p, 'Información recibida', 2);
            parrafos(p, valor('dp-informacion'), true, 'Falta completar la información recibida.');
            titulo(p, 'Características de la obra', 2);
            parrafos(p, valor('dp-caracteristicas'), true, 'Falta completar las características de la obra.');
            fotos(p, [...document.querySelectorAll('#bloques-fotos .bloque-foto')]);

            // Equipo técnico, en página nueva.
            nuevaHoja();
            p = parte('panel-equipo-tecnico');
            titulo(p, 'Equipo Técnico', 1);
            p.appendChild(el('p', null, 'Para el desarrollo de los distintos trabajos necesarios para dar cumplimiento a los objetivos estipulados en el presente informe se contó con la participación de los siguientes profesionales en cada uno de los aspectos estudiados.'));
            p.appendChild(el('p', null, 'Los trabajos desarrollados fueron los siguientes:'));
            p.appendChild(el('p', 'vp-negrita', 'Coordinación general de los trabajos'));
            lista(p, renglones(valor('et-coordinacion')).map(conPunto));
            p.appendChild(el('p', 'vp-negrita', 'Trabajos de Campo'));
            descripcionTrabajo(p, valor('et-campo-descripcion'));
            lista(p, renglones(valor('et-campo-personas')).map(conPunto));
            p.appendChild(el('p', 'vp-negrita', 'Trabajos de Gabinete'));
            descripcionTrabajo(p, valor('et-gabinete-descripcion'));
            lista(p, renglones(valor('et-gabinete-personas')).map(conPunto));

            // Trabajos desarrollados, en página nueva, y sus secciones.
            nuevaHoja();
            p = parte('panel-trabajos-desarrollados');
            titulo(p, 'Trabajos desarrollados', 1);
            descripcionTrabajo(p, valor('td-descripcion'));
            const activos = Object.keys(TRABAJOS).filter(activo);
            lista(p, activos.map(t => conPunto(TRABAJOS[t])));
            if (!activos.length) p.appendChild(el('div', 'vp-vacio', 'No hay trabajos activados.'));

            if (activo('relevamiento_encofrado')) {
                p = parte('panel-trabajo-relevamiento_encofrado');
                titulo(p, TRABAJOS.relevamiento_encofrado, 2);
                parrafos(p, valor('relevamiento_geometrico-descripcion'), true);
                elementos(p, 'relevamiento_geometrico');
            }

            if (GRUPO_RESISTENCIA.trabajos.some(activo)) {
                p = parte('panel-grupo-resistencia');
                titulo(p, GRUPO_RESISTENCIA.titulo, 2);
                parrafos(p, valor('ensayos_resistencia-descripcion'), true);
                elementos(p, 'ensayos_resistencia');
            }

            // Cada ensayo con planilla empieza en una página nueva.
            ['esclerometria', 'ultrasonido'].filter(activo).forEach(function (trabajo) {
                nuevaHoja();
                p = parte('panel-trabajo-' + trabajo);
                titulo(p, TRABAJOS[trabajo], 3);
                parrafos(p, valor(trabajo + '-descripcion'), true);
                fotos(p, [...document.querySelectorAll('[data-seccion-fotos="' + trabajo + '"] .bloque-foto')]);
                elementos(p, trabajo);
            });
        }

        // Índice con los títulos de nivel 1 y 2, después de la carátula.
        function indice(despuesDe) {
            const hojaIndice = el('div', 'vp-hoja');
            hojaIndice.appendChild(el('div', 'vp-indice-titulo', 'Contenido'));
            const contenido = el('div', 'vp-indice');
            titulos.forEach(t => contenido.appendChild(el('div', 'nivel-' + t.nivel, t.texto)));
            hojaIndice.appendChild(contenido);
            despuesDe.after(hojaIndice);
        }

        function actualizar() {
            documento.textContent = '';
            titulos.length = 0;
            caratula();
            const hojaCaratula = hoja;
            cuerpo();
            indice(hojaCaratula);
        }

        // Se rehace al mostrarla y cuando carga algo mientras está a la vista.
        const parteVistaPrevia = document.querySelector('.parte[data-panel="panel-vista-previa"]');
        parteVistaPrevia.addEventListener('click', actualizar);
        // El botón de la cabecera (al lado de Guardar) hace lo mismo que la entrada del menú.
        document.getElementById('btn-vista-previa').addEventListener('click', function () {
            parteVistaPrevia.click();
            panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
        let pendiente = null;
        document.addEventListener('informe:contenido-cargado', function () {
            if (!panel.classList.contains('activo')) return;
            clearTimeout(pendiente);
            pendiente = setTimeout(actualizar, 150);
        });
        actualizar();
    })();
</script>
</body>
</html>
