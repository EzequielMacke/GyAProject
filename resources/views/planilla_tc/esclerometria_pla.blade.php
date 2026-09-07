<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planilla de Esclerometría</title>
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
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; }

        /* ── ALERTS ── */
        .alert { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert-info { background: var(--accent-s); color: var(--accent-b); border: 1px solid #b9d3f5; }

        /* ── PANEL GENERAL ── */
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        .form-group { display: flex; flex-direction: column; }
        .form-label { font-size: 0.72rem; font-weight: 700; color: var(--text2); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.04em; }
        .form-control {
            width: 100%; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem;
            background: #fff; border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.55rem 0.85rem; color: var(--text);
            outline: none; transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .form-control[readonly] { background: var(--surface2); color: var(--text2); }

        /* ── PUNTOS DE ENSAYO ── */
        #puntos-list { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1rem; }

        .punto-card {
            background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem;
            overflow: hidden;
            animation: cardIn 0.18s ease both;
        }
        @keyframes cardIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

        .punto-head {
            display: flex; align-items: center; gap: 0.7rem;
            padding: 0.85rem 1.1rem;
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
        }
        .punto-badge {
            width: 34px; height: 34px; border-radius: 0.55rem;
            background: var(--accent-s); color: var(--accent-b);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem; font-weight: 700; flex-shrink: 0;
        }
        .punto-head-title { font-size: 0.85rem; font-weight: 700; color: var(--text); }
        .punto-head-sub { font-size: 0.72rem; color: var(--muted); }
        .punto-delete-btn {
            margin-left: auto; background: none; border: none; cursor: pointer;
            color: var(--muted); font-size: 0.82rem; padding: 0.45rem; border-radius: 0.45rem;
            transition: color 0.14s, background 0.14s; flex-shrink: 0;
        }
        .punto-delete-btn:hover { color: #c0392b; background: #fff0f0; }

        .punto-body { padding: 1.1rem; }

        .punto-datos-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1rem;
            margin-bottom: 1.1rem;
        }

        .impactos-label {
            font-size: 0.72rem; font-weight: 700; color: var(--text2);
            text-transform: uppercase; letter-spacing: 0.04em;
            margin-bottom: 0.5rem; display: block;
        }
        .impactos-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }
        .impacto-item { display: flex; flex-direction: column; align-items: center; gap: 0.3rem; }
        .impacto-num { font-size: 0.66rem; font-weight: 700; color: var(--muted); }
        .impacto-input {
            width: 100%; text-align: center;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem;
            background: #fff; border: 1.5px solid var(--border);
            border-radius: 0.5rem; padding: 0.45rem 0.25rem; color: var(--text);
            outline: none; transition: border-color 0.15s, box-shadow 0.15s;
            -moz-appearance: textfield;
        }
        .impacto-input::-webkit-outer-spin-button,
        .impacto-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .impacto-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .impacto-input.impacto-descartado {
            border-color: #e74c3c; background: #fff0f0; color: #c0392b; text-decoration: line-through;
        }

        /* ── RESULTADOS DEL PUNTO ── */
        .punto-resultados {
            display: flex; flex-wrap: wrap; gap: 1.5rem;
            margin-top: 1.1rem; padding-top: 1.1rem;
            border-top: 1px solid var(--border);
        }
        .resultado-item { display: flex; flex-direction: column; gap: 0.2rem; }
        .resultado-label { font-size: 0.66rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; }
        .resultado-valor { font-size: 1rem; font-weight: 700; color: var(--text); }
        .resultado-final .resultado-valor { color: var(--green); }
        .resultado-n-corregido .resultado-valor { color: var(--accent-b); }
        .resultado-n-final .resultado-valor { color: var(--green); font-size: 1.1rem; }

        .btn-agregar-punto {
            width: 100%;
            border: 1.5px dashed var(--border2);
            border-radius: 0.85rem;
            background: var(--surface);
            color: var(--accent);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem; font-weight: 700;
            padding: 0.9rem;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            transition: all 0.14s;
        }
        .btn-agregar-punto:hover { background: var(--accent-s); border-color: var(--accent); }

        /* ── EMPTY STATE ── */
        .empty-puntos {
            text-align: center; padding: 2.5rem 1.5rem;
            color: var(--muted); font-size: 0.85rem;
            border: 1.5px dashed var(--border2); border-radius: 0.85rem;
            margin-bottom: 1rem;
        }

        /* ── FOOTER ACCIONES ── */
        .footer-acciones {
            display: flex; justify-content: flex-end; gap: 0.6rem;
            margin-top: 1.5rem;
        }

        /* ── MOBILE ── */
        @media (max-width: 900px) {
            .form-grid { grid-template-columns: repeat(2, 1fr); }
            .punto-datos-grid { grid-template-columns: 1fr; }
            .impactos-grid { grid-template-columns: repeat(4, 1fr); }
        }
        @media (max-width: 640px) {
            .ph { padding: 1rem 0 0.75rem; gap: 0.75rem; margin-bottom: 1rem; }
            .ph-title { font-size: 1.3rem; }
            .ph-right { width: 100%; }
            .form-grid { grid-template-columns: 1fr; }
            .impactos-grid { grid-template-columns: repeat(3, 1fr); }
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
                            Esclerometría
                        </div>
                        <h1 class="ph-title"><em>Planilla de Esclerometría</em></h1>
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

                <div class="alert alert-info">
                    <i class="fas fa-circle-info"></i>
                    Esta planilla todavía no guarda datos: es solo la interfaz de carga.
                </div>

                <form id="form-esclerometria">

                    {{-- ═══ DATOS GENERALES ═══ --}}
                    <div class="panel">
                        <div class="panel-title"><i class="fas fa-clipboard-list"></i> Datos generales</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="input-obra">Obra</label>
                                <input type="text" id="input-obra" class="form-control" value="{{ $obraTc->descripcion }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="input-fecha">Fecha</label>
                                <input type="date" id="input-fecha" name="fecha" class="form-control" value="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="input-lectura-inicial">Lectura inicial yunque</label>
                                <input type="number" step="any" id="input-lectura-inicial" name="lectura_inicial_yunque" class="form-control" placeholder="0" value="80">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="input-lectura-final">Lectura final yunque</label>
                                <input type="number" step="any" id="input-lectura-final" name="lectura_final_yunque" class="form-control" placeholder="0" value="80">
                            </div>
                        </div>
                    </div>

                    {{-- ═══ PUNTOS ENSAYADOS ═══ --}}
                    <div class="panel">
                        <div class="panel-title"><i class="fas fa-bullseye"></i> Puntos ensayados</div>

                        <div id="puntos-list"></div>

                        <div class="empty-puntos" id="empty-puntos" style="display:none">
                            Todavía no agregaste ningún punto de ensayo.
                        </div>

                        <button type="button" class="btn-agregar-punto" id="btn-agregar-punto">
                            <i class="fas fa-plus"></i> Agregar punto de ensayo
                        </button>
                    </div>

                    <div class="footer-acciones">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>

                </form>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
    const CANTIDAD_IMPACTOS = 14;
    const listaPuntos = document.getElementById('puntos-list');
    const emptyPuntos = document.getElementById('empty-puntos');
    let contadorPuntos = 0;

    function crearImpactosHTML(idx) {
        let html = '';
        for (let i = 1; i <= CANTIDAD_IMPACTOS; i++) {
            html += `
                <div class="impacto-item">
                    <span class="impacto-num">${i}</span>
                    <input type="number" step="any" class="impacto-input" name="puntos[${idx}][impactos][]" inputmode="decimal">
                </div>
            `;
        }
        return html;
    }

    function crearPuntoHTML(idx) {
        const card = document.createElement('div');
        card.className = 'punto-card';
        card.dataset.idx = idx;
        card.innerHTML = `
            <div class="punto-head">
                <div class="punto-badge punto-identificacion">E?</div>
                <div>
                    <div class="punto-head-title">Punto de ensayo</div>
                    <div class="punto-head-sub">Identificación automática</div>
                </div>
                <button type="button" class="punto-delete-btn" title="Eliminar punto">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="punto-body">
                <div class="punto-datos-grid">
                    <div class="form-group">
                        <label class="form-label">Elemento</label>
                        <input type="text" class="form-control" name="puntos[${idx}][elemento]" placeholder="Ej: Columna, Viga, Losa...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dirección de ensayo</label>
                        <select class="form-control punto-direccion-select" name="puntos[${idx}][direccion]">
                            <option value="0">0°</option>
                            <option value="45">45°</option>
                            <option value="-45">-45°</option>
                            <option value="90">90°</option>
                            <option value="-90">-90°</option>
                        </select>
                    </div>
                </div>
                <div>
                    <span class="impactos-label">14 impactos</span>
                    <div class="impactos-grid">
                        ${crearImpactosHTML(idx)}
                    </div>
                </div>
                <div class="punto-resultados">
                    <div class="resultado-item">
                        <span class="resultado-label">Promedio inicial</span>
                        <span class="resultado-valor resultado-promedio-inicial">—</span>
                    </div>
                    <div class="resultado-item">
                        <span class="resultado-label">Válidos</span>
                        <span class="resultado-valor resultado-validos">—</span>
                    </div>
                    <div class="resultado-item resultado-final">
                        <span class="resultado-label">Promedio final</span>
                        <span class="resultado-valor resultado-promedio-final">—</span>
                    </div>
                    <div class="resultado-item resultado-n-corregido">
                        <span class="resultado-label">N corregido</span>
                        <span class="resultado-valor resultado-n-corregido-valor">—</span>
                    </div>
                    <div class="resultado-item">
                        <span class="resultado-label">Corrección ángulo</span>
                        <span class="resultado-valor resultado-correccion-angulo">—</span>
                    </div>
                    <div class="resultado-item resultado-n-final">
                        <span class="resultado-label">N final</span>
                        <span class="resultado-valor resultado-n-final-valor">—</span>
                    </div>
                </div>
            </div>
        `;

        card.querySelector('.punto-delete-btn').addEventListener('click', function () {
            card.remove();
            renumerarPuntos();
        });

        card.querySelectorAll('.impacto-input').forEach(function (input) {
            input.addEventListener('input', function () {
                recalcularPunto(card);
            });
        });

        card.querySelector('.punto-direccion-select').addEventListener('change', function () {
            recalcularPunto(card);
        });

        return card;
    }

    /* ─── Tabla de corrección por ángulo de ensayo ──────────────
       Clave = índice esclerométrico (N corregido, redondeado al
       entero más cercano), valor = corrección a sumar según el
       ángulo. A 0° no se aplica ninguna corrección. */
    const CORRECCION_ANGULO = {
        10: { '90': 0, '45': 0, '-45': 2.4, '-90': 3.2 },
        11: { '90': 0, '45': 0, '-45': 2.41, '-90': 3.22 },
        12: { '90': 0, '45': 0, '-45': 2.42, '-90': 3.24 },
        13: { '90': 0, '45': 0, '-45': 2.43, '-90': 3.26 },
        14: { '90': 0, '45': 0, '-45': 2.44, '-90': 3.28 },
        15: { '90': 0, '45': 0, '-45': 2.45, '-90': 3.3 },
        16: { '90': 0, '45': 0, '-45': 2.46, '-90': 3.32 },
        17: { '90': 0, '45': 0, '-45': 2.47, '-90': 3.34 },
        18: { '90': 0, '45': 0, '-45': 2.48, '-90': 3.36 },
        19: { '90': 0, '45': 0, '-45': 2.49, '-90': 3.38 },
        20: { '90': -5.4, '45': -3.5, '-45': 2.5, '-90': 3.4 },
        21: { '90': -5.33, '45': -3.46, '-45': 2.48, '-90': 3.37 },
        22: { '90': -5.26, '45': -3.42, '-45': 2.46, '-90': 3.34 },
        23: { '90': -5.19, '45': -3.38, '-45': 2.44, '-90': 3.31 },
        24: { '90': -5.12, '45': -3.34, '-45': 2.42, '-90': 3.28 },
        25: { '90': -5.05, '45': -3.3, '-45': 2.4, '-90': 3.25 },
        26: { '90': -4.98, '45': -3.26, '-45': 2.38, '-90': 3.22 },
        27: { '90': -4.91, '45': -3.22, '-45': 2.36, '-90': 3.19 },
        28: { '90': -4.84, '45': -3.18, '-45': 2.34, '-90': 3.16 },
        29: { '90': -4.77, '45': -3.14, '-45': 2.32, '-90': 3.13 },
        30: { '90': -4.7, '45': -3.1, '-45': 2.3, '-90': 3.1 },
        31: { '90': -4.62, '45': -3.05, '-45': 2.3, '-90': 3.06 },
        32: { '90': -4.54, '45': -3, '-45': 2.3, '-90': 3.02 },
        33: { '90': -4.46, '45': -2.95, '-45': 2.3, '-90': 2.98 },
        34: { '90': -4.38, '45': -2.9, '-45': 2.3, '-90': 2.94 },
        35: { '90': -4.3, '45': -2.85, '-45': 2.3, '-90': 2.9 },
        36: { '90': -4.22, '45': -2.8, '-45': 2.3, '-90': 2.86 },
        37: { '90': -4.14, '45': -2.75, '-45': 2.3, '-90': 2.82 },
        38: { '90': -4.06, '45': -2.7, '-45': 2.3, '-90': 2.78 },
        39: { '90': -3.98, '45': -2.65, '-45': 2.3, '-90': 2.74 },
        40: { '90': -3.9, '45': -2.6, '-45': 2.3, '-90': 2.7 },
        41: { '90': -3.82, '45': -2.55, '-45': 2.23, '-90': 2.65 },
        42: { '90': -3.74, '45': -2.5, '-45': 2.16, '-90': 2.6 },
        43: { '90': -3.66, '45': -2.45, '-45': 2.09, '-90': 2.55 },
        44: { '90': -3.58, '45': -2.4, '-45': 2.02, '-90': 2.5 },
        45: { '90': -3.5, '45': -2.35, '-45': 1.95, '-90': 2.45 },
        46: { '90': -3.42, '45': -2.3, '-45': 1.88, '-90': 2.4 },
        47: { '90': -3.34, '45': -2.25, '-45': 1.81, '-90': 2.35 },
        48: { '90': -3.26, '45': -2.2, '-45': 1.74, '-90': 2.3 },
        49: { '90': -3.18, '45': -2.15, '-45': 1.67, '-90': 2.25 },
        50: { '90': -3.1, '45': -2.1, '-45': 1.6, '-90': 2.2 },
        51: { '90': -3.02, '45': -2.05, '-45': 1.57, '-90': 2.15 },
        52: { '90': -2.94, '45': -2, '-45': 1.54, '-90': 2.1 },
        53: { '90': -2.86, '45': -1.95, '-45': 1.51, '-90': 2.05 },
        54: { '90': -2.78, '45': -1.9, '-45': 1.48, '-90': 2 },
        55: { '90': -2.7, '45': -1.85, '-45': 1.45, '-90': 1.95 },
        56: { '90': -2.62, '45': -1.8, '-45': 1.42, '-90': 1.9 },
        57: { '90': -2.54, '45': -1.75, '-45': 1.39, '-90': 1.85 },
        58: { '90': -2.46, '45': -1.7, '-45': 1.36, '-90': 1.8 },
        59: { '90': -2.38, '45': -1.65, '-45': 1.33, '-90': 1.75 },
        60: { '90': -2.3, '45': -1.6, '-45': 1.3, '-90': 1.7 },
    };

    /* ─── Cálculo del promedio esclerométrico ──────────────────
       1) Promedio de los 14 impactos cargados.
       2) Se descartan los que quedan fuera de ±6 de ese promedio.
       3) Se promedian los restantes y se vuelve a descartar ±6
          de ese nuevo promedio (sobre lo que quedó del paso 2).
       4) El promedio de lo que sobrevive a ambas rondas es el final.
       5) N corregido = (sumatoria final × yunque inicial) /
                        (cantidad de válidos × yunque final). */
    function promediar(valores) {
        return valores.reduce((s, v) => s + v, 0) / valores.length;
    }

    function obtenerYunques() {
        const inicial = parseFloat(document.getElementById('input-lectura-inicial').value);
        const final = parseFloat(document.getElementById('input-lectura-final').value);
        return {
            inicial: isNaN(inicial) ? null : inicial,
            final: isNaN(final) ? null : final,
        };
    }

    function recalcularPunto(card) {
        const inputs = Array.from(card.querySelectorAll('.impacto-input'));
        inputs.forEach(inp => inp.classList.remove('impacto-descartado'));

        const cargados = inputs
            .map(input => ({ input, valor: parseFloat(input.value) }))
            .filter(d => d.input.value.trim() !== '' && !isNaN(d.valor));

        const elInicial = card.querySelector('.resultado-promedio-inicial');
        const elValidos = card.querySelector('.resultado-validos');
        const elFinal = card.querySelector('.resultado-promedio-final');
        const elNCorregido = card.querySelector('.resultado-n-corregido-valor');
        const elCorreccion = card.querySelector('.resultado-correccion-angulo');
        const elNFinal = card.querySelector('.resultado-n-final-valor');

        if (cargados.length === 0) {
            elInicial.textContent = '—';
            elValidos.textContent = '—';
            elFinal.textContent = '—';
            elNCorregido.textContent = '—';
            elCorreccion.textContent = '—';
            elNFinal.textContent = '—';
            return;
        }

        const promedio1 = promediar(cargados.map(d => d.valor));
        const rondaUno = cargados.filter(d => Math.abs(d.valor - promedio1) <= 6);
        const descartadosUno = cargados.filter(d => Math.abs(d.valor - promedio1) > 6);

        let rondaDos = [];
        let descartadosDos = [];
        let sumaFinal = null;
        let promedioFinal = null;

        if (rondaUno.length > 0) {
            const promedio2 = promediar(rondaUno.map(d => d.valor));
            rondaDos = rondaUno.filter(d => Math.abs(d.valor - promedio2) <= 6);
            descartadosDos = rondaUno.filter(d => Math.abs(d.valor - promedio2) > 6);

            if (rondaDos.length > 0) {
                sumaFinal = rondaDos.reduce((s, d) => s + d.valor, 0);
                promedioFinal = sumaFinal / rondaDos.length;
            }
        }

        [...descartadosUno, ...descartadosDos].forEach(d => d.input.classList.add('impacto-descartado'));

        elInicial.textContent = promedio1.toFixed(2);
        elValidos.textContent = `${rondaDos.length} / ${cargados.length}`;
        elFinal.textContent = promedioFinal !== null ? promedioFinal.toFixed(2) : '—';

        const yunques = obtenerYunques();
        let nCorregido = null;
        if (sumaFinal !== null && rondaDos.length > 0 && yunques.inicial !== null && yunques.final) {
            nCorregido = (sumaFinal * yunques.inicial) / (rondaDos.length * yunques.final);
        }
        elNCorregido.textContent = nCorregido !== null ? nCorregido.toFixed(2) : '—';

        let correccion = null;
        let nFinal = null;
        if (nCorregido !== null) {
            const direccion = card.querySelector('.punto-direccion-select').value;
            if (direccion === '0') {
                correccion = 0;
                nFinal = nCorregido;
            } else {
                const indice = Math.round(nCorregido);
                const fila = CORRECCION_ANGULO[indice];
                if (fila && direccion in fila) {
                    correccion = fila[direccion];
                    nFinal = nCorregido + correccion;
                }
            }
        }
        elCorreccion.textContent = correccion !== null ? correccion.toFixed(2) : '—';
        elNFinal.textContent = nFinal !== null ? nFinal.toFixed(2) : '—';
    }

    function recalcularTodosPuntos() {
        listaPuntos.querySelectorAll('.punto-card').forEach(recalcularPunto);
    }

    document.getElementById('input-lectura-inicial').addEventListener('input', recalcularTodosPuntos);
    document.getElementById('input-lectura-final').addEventListener('input', recalcularTodosPuntos);

    function renumerarPuntos() {
        const cards = listaPuntos.querySelectorAll('.punto-card');
        cards.forEach((card, i) => {
            card.querySelector('.punto-identificacion').textContent = `E${i + 1}`;
        });
        emptyPuntos.style.display = cards.length === 0 ? '' : 'none';
    }

    function agregarPunto() {
        contadorPuntos++;
        const card = crearPuntoHTML(contadorPuntos);
        listaPuntos.appendChild(card);
        renumerarPuntos();
    }

    document.getElementById('btn-agregar-punto').addEventListener('click', agregarPunto);

    // Arranca la planilla con un primer punto de ensayo ya cargado.
    agregarPunto();

    document.getElementById('form-esclerometria').addEventListener('submit', function (e) {
        e.preventDefault();
    });
</script>
</body>
</html>
