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
        .pach-lienzo {
            flex: 1; min-width: 0; min-height: 220px;
            border: 1.5px dashed var(--border2); border-radius: 0.7rem;
            background:
                linear-gradient(var(--surface2) 1px, transparent 1px) 0 0 / 20px 20px,
                linear-gradient(90deg, var(--surface2) 1px, transparent 1px) 0 0 / 20px 20px,
                #fff;
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 1rem; text-align: center;
        }
        .lienzo-rotulo { line-height: 1.25; }
        .rotulo-nombre { font-size: 0.95rem; font-weight: 700; color: var(--text); }
        .rotulo-tipo { font-size: 0.78rem; font-weight: 600; color: var(--muted); }
        .lienzo-leyenda { font-size: 0.78rem; font-weight: 600; color: var(--muted); line-height: 1.35; }
        .pach-lienzo svg { width: 100%; max-width: 320px; height: auto; }
        .pach-card.expandida .pach-lienzo svg { max-width: 300px; }
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
                    <button type="button" class="pach-agregar" id="btn-agregar-pachometria">
                        <span class="pach-agregar-icono"><i class="fas fa-plus"></i></span>
                        <span class="pach-agregar-texto">Agregar pachometría</span>
                    </button>
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

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
       Losa: todavía sin definir. */
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
            // Gancho de 135°: extensión de 6Ø (mínimo 3 cm para que se vea).
            largoGancho: Math.max(6 * diametroCm, 3) * escala,
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
            inferior: leerBarras(card.barrasInferior), // viga: a lo largo de la cara de abajo
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

    // Distancia del borde al centro de una barra: recubrimiento +
    // Ø estribo + radio de la barra (todo en cm).
    function insetBarra(armadura, diametroMm) {
        return (armadura.recubrimiento ?? 0) + (armadura.estribo ?? 0) / 10 + diametroMm / 20;
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
        const barra = (diametro, cx, cy) => ({ x: cx, y: cy, r: (diametro / 20) * escala });
        const inset = diametro => insetBarra(armadura, diametro) * escala;

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
            const radio = r - insetBarra(armadura, grupos[g].diametro) * escala;
            const p = puntoEn(o, radio, 225 + (360 * k) / orden.length);
            return { x: p.x, y: p.y, r: (grupos[g].diametro / 20) * escala };
        }).filter(b => b.r > 0);
    }

    /* Viga: todas las barras en la cara inferior, apoyadas sobre el
       estribo. Las de los extremos van en las esquinas y el resto
       queda equiespaciado entre ellas (intercalando diámetros). Con
       una sola barra, va al centro. */
    function ordenBarrasInferior(armadura) {
        const grupos = armadura.inferior;
        return intercalar(grupos.map(g => g.cantidad)).map(i => grupos[i].diametro);
    }

    function posicionesBarrasViga(armadura, x, y, w, h, escala) {
        const orden = ordenBarrasInferior(armadura);
        if (! orden.length) return [];
        const inset = diametro => insetBarra(armadura, diametro) * escala;
        const desde = x + inset(orden[0]);
        const hasta = x + w - inset(orden[orden.length - 1]);
        return orden.map((diametro, j) => ({
            x: orden.length === 1 ? x + w / 2 : desde + j * (hasta - desde) / (orden.length - 1),
            y: y + h - inset(diametro),
            r: (diametro / 20) * escala,
        }));
    }

    // Piel: una barra en cada cara lateral, apoyada en el estribo, con
    // el centro a la altura cargada. Se descartan las que caen fuera
    // de la viga.
    function posicionesBarrasPiel(armadura, x, y, w, h, escala) {
        return armadura.piel
            .filter(b => b.altura * escala < h)
            .flatMap(b => {
                const inset = insetBarra(armadura, b.diametro) * escala;
                const cy = y + h - b.altura * escala;
                const r = (b.diametro / 20) * escala;
                return [{ x: x + inset, y: cy, r }, { x: x + w - inset, y: cy, r }];
            });
    }

    /* Cotas de altura de la piel, a la izquierda de la viga: una
       línea vertical desde la cara inferior, con una marca y el valor
       en cada nivel (todas medidas desde abajo). */
    function cotasPiel(armadura, { x, y, w, h, escala }) {
        const niveles = armadura.piel.filter(b => b.altura * escala < h);
        if (! niveles.length) return '';
        const xc = x - 12;
        const yNivel = b => y + h - b.altura * escala;
        return `
            <line x1="${xc}" y1="${y + h}" x2="${xc}" y2="${yNivel(niveles[niveles.length - 1])}" ${ESTILO_COTA}></line>
            <line x1="${xc - 5}" y1="${y + h}" x2="${x - 2}" y2="${y + h}" ${ESTILO_COTA}></line>
            ${niveles.map(b => `
                <line x1="${xc - 5}" y1="${yNivel(b)}" x2="${x - 2}" y2="${yNivel(b)}" ${ESTILO_COTA}></line>
                <text x="${xc - 8}" y="${yNivel(b) + 3.5}" text-anchor="end" ${ESTILO_TEXTO_LOSA}>${formatearMedida(b.altura)}</text>
            `).join('')}
        `;
    }

    function dibujarBarras(barras) {
        return barras.map(b =>
            `<circle cx="${b.x}" cy="${b.y}" r="${Math.max(1.8, b.r)}" fill="${COLOR_ESTRIBO}"></circle>`
        ).join('');
    }

    // Radio del doblado del estribo alrededor de la barra de esquina:
    // Ø barra / 2 + Ø estribo / 2 (en unidades del dibujo).
    function radioDobladoBarra(diametroBarra, armadura, escala) {
        if (! diametroBarra) return 0;
        return ((diametroBarra + (armadura.estribo ?? 0)) / 20) * escala;
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

        // Las esquinas del estribo envuelven las barras inferiores de los extremos.
        const esquinaInferior = ordenBarrasInferior(armadura)[0] ?? null;
        const interior = sinMedidas ? '' : `
            ${estriboRectangular(r, armadura, esquinaInferior)}
            ${dibujarBarras(posicionesBarrasViga(armadura, r.x, r.y, r.w, r.h, r.escala))}
            ${dibujarBarras(posicionesBarrasPiel(armadura, r.x, r.y, r.w, r.h, r.escala))}
            ${cotasPiel(armadura, r)}
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
       de esquina, el doblado la envuelve. */
    function estriboRectangular({ escala, x, y, w, h }, armadura, diametroEsquina) {
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
                const rDoblado = radioDobladoBarra(diametroEsquina, armadura, escala);
                const rc = rDoblado
                    ? Math.min(Math.max(rDoblado, g * 0.9), Math.min(we, he) / 3)
                    : Math.min(g * 2, Math.min(we, he) / 4);
                const rb = rDoblado
                    ? rc
                    : Math.min(g * 0.9, Math.min(we, he) / 5);
                const largoPata = Math.min(datosEstribo.largoGancho, Math.min(we, he) * 0.35);
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
                        `L ${xe + we} ${ye + he - rc}`,
                        `A ${rc} ${rc} 0 0 1 ${xe + we - rc} ${ye + he}`,
                        `L ${xe + rc} ${ye + he}`,
                        `A ${rc} ${rc} 0 0 1 ${xe} ${ye + he - rc}`,
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
                const largoPata = Math.min(datosEstribo.largoGancho, rEstribo * 0.7);
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
            return `<div class="lienzo-mensaje"><i class="fas fa-layer-group"></i>Dibujo de losa a definir.</div>`;
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
            if (armadura.inferior.length) partes.push(`Inferior: ${grupos(armadura.inferior)}`);
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

    function redibujar(card) {
        card.querySelector('.pach-lienzo').innerHTML = contenidoLienzo(card);
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

    function filaVacia(clave) {
        return LISTAS_PIEL.includes(clave) ? { diametro: '', altura: '' } : { cantidad: '', diametro: '' };
    }

    function camposFilaBarra(clave, b) {
        const diametro = `
            <input type="number" min="0" step="any" inputmode="decimal" class="medida-input"
                   data-campo="diametro" value="${b.diametro}" placeholder="mm">
            <span class="barra-fila-texto">mm</span>
        `;
        if (LISTAS_PIEL.includes(clave)) {
            return `
                <span class="barra-fila-texto">Ø</span>
                ${diametro}
                <span class="barra-fila-texto">a</span>
                <input type="number" min="0" step="any" inputmode="decimal" class="medida-input"
                       data-campo="altura" value="${b.altura}" placeholder="cm">
                <span class="barra-fila-texto">cm</span>
            `;
        }
        return `
            <input type="number" min="1" step="1" inputmode="numeric" class="medida-input"
                   data-campo="cantidad" value="${b.cantidad}" placeholder="Cant.">
            <span class="barra-fila-texto">de Ø</span>
            ${diametro}
        `;
    }

    function listaBarrasHTML(card, clave) {
        return `
            <div class="barras-lista" data-lista="${clave}">
                ${card[clave].map((b, i) => `
                    <div class="barra-fila" data-indice="${i}">
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
       "barrasX" y "barrasY"; viga "barrasInferior" y "barrasPiel". */
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

    function crearTarjeta() {
        const idx = obtenerSiguienteIdx();
        const card = document.createElement('div');
        card.className = 'pach-card';
        card.dataset.idx = idx;
        card.dataset.numero = idx;
        card.barras = [{ cantidad: '', diametro: '' }];
        card.barrasX = [{ cantidad: '', diametro: '' }];
        card.barrasY = [{ cantidad: '', diametro: '' }];
        card.barrasInferior = [filaVacia('barrasInferior')];
        card.barrasPiel = [filaVacia('barrasPiel')];

        const botonesTipo = Object.entries(TIPOS).map(([clave, tipo]) => `
            <button type="button" class="tipo-btn" data-tipo="${clave}">
                <i class="fas ${tipo.icono}"></i> ${tipo.nombre}
            </button>
        `).join('');

        card.innerHTML = `
            <div class="pach-head">
                <div class="pach-badge">${PREFIJO_NOMBRE}${idx}</div>
                <div>
                    <div class="pach-head-title">Pachometría</div>
                    <div class="pach-head-sub pach-tipo-texto">Sin tipo seleccionado</div>
                </div>
                <button type="button" class="pach-cerrar-btn" title="Contraer"><i class="fas fa-compress"></i></button>
            </div>
            <div class="pach-body">
                <div class="pach-lienzo">${contenidoLienzo(card)}</div>
                <aside class="pach-panel">
                    <div>
                        <span class="tipo-label">Nombre</span>
                        <label class="nombre-campo">
                            <span class="nombre-prefijo">${PREFIJO_NOMBRE}</span>
                            <input type="number" min="1" step="1" inputmode="numeric" class="nombre-input" value="${idx}" placeholder="N°">
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
                               placeholder="Ej: Pilar 1" style="max-width:320px; margin-top:0.4rem;">
                    </div>
                    <div class="pach-parametros"></div>
                    <div class="pach-panel-acciones">
                        <button type="button" class="pach-delete-btn"><i class="fas fa-trash"></i> Eliminar pachometría</button>
                    </div>
                </aside>
            </div>
        `;

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
            card.remove();
            validarNombres();
        });

        insertarOrdenada(card);
        validarNombres();
        return card;
    }

    /* ─── Expandir / contraer ─────────────────────────────────
       Solo una tarjeta expandida a la vez. Se contrae con el botón
       de la cabecera, haciendo clic fuera de las tarjetas o con Esc. */
    function contraerTodas() {
        grilla.querySelectorAll('.pach-card.expandida').forEach(c => c.classList.remove('expandida'));
    }

    function expandir(card) {
        contraerTodas();
        card.classList.add('expandida');
        card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Se usa composedPath() y no e.target.closest(): algunos botones del
    // panel (p. ej. la forma del pilar) se regeneran al hacer clic y, para
    // cuando llega acá, ya no están dentro de la tarjeta.
    document.addEventListener('click', function (e) {
        const dentroDeTarjeta = e.composedPath().some(el =>
            el.classList && (el.classList.contains('pach-card') || el.id === 'btn-agregar-pachometria')
        );
        if (! dentroDeTarjeta) contraerTodas();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') contraerTodas();
    });

    // Una tarjeta nueva se abre expandida para elegir el tipo enseguida.
    btnAgregar.addEventListener('click', function () {
        expandir(crearTarjeta());
    });
</script>
</body>
</html>
