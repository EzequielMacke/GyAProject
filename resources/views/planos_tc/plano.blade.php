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
        }
        .submenu-lateral.abierto { display: flex; }
        .submenu-lateral .tool-btn {
            flex-direction: row;
            white-space: nowrap;
            gap: 0.5rem;
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

        .capas-wrap { position: relative; }

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
        .capa-item .tool-swatch, .capa-item .tool-icon-img {
            width: 18px; height: 18px; flex-shrink: 0;
        }
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
        <a href="{{ route('planos_tc.index', $obraTc->id) }}" class="btn-superior">&larr; Volver</a>
    </div>

    <div class="app">
        <nav class="toolbar-vertical">
            <div class="tool-submenu-wrap activo" id="danos-wrap">
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
            <div class="tool-submenu-wrap" id="ensayos-wrap">
                <button type="button" class="tool-btn" id="tool-ensayos" title="Ensayos">
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
                </div>
            </div>
            <button type="button" class="tool-btn" data-tool="foto" title="Fotografía">
                <img class="tool-icon-img" src="{{ asset('img/iconos/foto.svg') }}" alt="">
                Foto
            </button>
        </nav>

        <div class="lienzo-wrap" id="lienzo-wrap">
            <div class="lienzo" id="lienzo">
                <canvas id="pdf-canvas"></canvas>
            </div>
            <canvas id="draw-canvas"></canvas>
        </div>
    </div>

    <input type="file" accept="image/*" capture="environment" id="input-foto" style="display:none">

    <div class="overlay-foto" id="overlay-foto">
        <div class="overlay-foto-contenido">
            <button type="button" class="overlay-foto-cerrar" id="overlay-foto-cerrar">&times;</button>
            <img id="overlay-foto-img" src="" alt="Fotografía">
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const urlPdf = @json(Storage::url('planos/' . $plano->archivo));
        const rotacionPlano = {{ (int) ($plano->rotacion ?? 0) }};
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

        /* La fotografía comparte el mecanismo de ícono con los ensayos
           (imagen + tamaño), pero no se numera y no es un ensayo: se
           agrupa aparte y su ícono se ve más chico en el plano. */
        const FOTO = { tool: 'foto', url: @json(asset('img/iconos/foto.svg')), color: '#e6a400', nombre: 'Fotografía', tamano: 6.5 };
        const ENSAYOS_Y_FOTO = [...ENSAYOS, FOTO];

        const PREFIJOS_ENSAYO = {};
        const COLORES_ENSAYO = {};
        const contadoresEnsayo = {};
        ENSAYOS_Y_FOTO.forEach(({ tool, prefijo, color }) => {
            PREFIJOS_ENSAYO[tool] = prefijo;
            COLORES_ENSAYO[tool] = color;
            contadoresEnsayo[tool] = 0;
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

        function crearItemCapa({ tool, nombre, color, url }) {
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
        panelCapas.appendChild(grupoCapasDanos);
        panelCapas.appendChild(grupoCapasEnsayos);
        panelCapas.appendChild(grupoCapasFoto);

        DANOS.forEach(item => { metaCapas[item.tool] = { ...item, grupo: grupoCapasDanos }; });
        DANOS_ICONO.forEach(item => { metaCapas[item.tool] = { ...item, grupo: grupoCapasDanos }; });
        ENSAYOS.forEach(item => { metaCapas[item.tool] = { ...item, grupo: grupoCapasEnsayos }; });
        metaCapas[FOTO.tool] = { ...FOTO, grupo: grupoCapasFoto };

        function registrarUsoCapa(tool) {
            if (itemsCapaDom[tool]) return;
            capasVisibles[tool] = true;
            const meta = metaCapas[tool];
            meta.grupo.appendChild(crearItemCapa(meta));
            meta.grupo.style.display = 'flex';
            panelCapasVacio.style.display = 'none';
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

        let herramientaActual = 'fisura';

        const wrapsSubmenu = document.querySelectorAll('.tool-submenu-wrap');

        document.querySelectorAll('.tool-btn[data-tool]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tool-btn').forEach(b => b.classList.remove('activo'));
                btn.classList.add('activo');
                herramientaActual = btn.dataset.tool;

                const wrapPadre = btn.closest('.tool-submenu-wrap');
                wrapsSubmenu.forEach(w => w.classList.toggle('activo', w === wrapPadre));

                if (wrapPadre) {
                    const btnPrincipal = wrapPadre.querySelector(':scope > .tool-btn');
                    const imgOrigen = btn.querySelector('img');
                    const swatchOrigen = btn.querySelector('.tool-swatch');
                    const imgPrincipal = btnPrincipal.querySelector('img');
                    const swatchPrincipal = btnPrincipal.querySelector('.tool-swatch');

                    if (imgOrigen) {
                        if (imgPrincipal) {
                            imgPrincipal.src = imgOrigen.src;
                            imgPrincipal.style.display = '';
                        }
                        if (swatchPrincipal) swatchPrincipal.style.display = 'none';
                    } else if (swatchOrigen) {
                        if (swatchPrincipal) {
                            swatchPrincipal.style.background = swatchOrigen.style.background;
                            swatchPrincipal.style.display = '';
                        }
                        if (imgPrincipal) imgPrincipal.style.display = 'none';
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

        let pdfDoc = null;
        let trazos = [];
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

            trazos.forEach(item => {
                if (capasVisibles[item.tool] === false) return;

                if (item.tipo === 'icono') {
                    if (!item.imagen.complete || !item.imagen.naturalWidth) return;
                    const ratio = item.imagen.naturalWidth / item.imagen.naturalHeight;
                    const base = item.tamano * vista.scale;
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

                if (item.puntos.length < 2) return;
                const puntosPantalla = item.puntos.map(p => mundoAPantalla(p.x, p.y));
                const path = new Path2D();
                path.moveTo(puntosPantalla[0].x, puntosPantalla[0].y);
                for (let i = 1; i < puntosPantalla.length; i++) {
                    path.lineTo(puntosPantalla[i].x, puntosPantalla[i].y);
                }

                if (item.cerrado) {
                    path.closePath();
                    dibujarTramaDiagonal(item, puntosPantalla, path);
                }

                drawCtx.strokeStyle = item.color;
                drawCtx.lineWidth = item.grosor * vista.scale;
                drawCtx.stroke(path);
            });
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

        window.addEventListener('resize', () => {
            ajustarTamanoHud();
            redibujarTrazos();
        });

        function zoomEn(factor, screenX, screenY) {
            const mundo = pantallaAMundo(screenX, screenY);
            fijarPuntoEnPantalla(mundo.x, mundo.y, screenX, screenY, vista.scale * factor);
        }

        function centrarVista() {
            vista.scale = 1;
            const anchoWrap = lienzoWrap.clientWidth;
            const altoWrap = lienzoWrap.clientHeight;
            vista.x = Math.max((anchoWrap - anchoBase) / 2, 0);
            vista.y = altoBase < altoWrap ? (altoWrap - altoBase) / 2 : 24;
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
            const anchoDisponible = Math.min(lienzoWrap.clientWidth - 48, 1400);
            const viewportBase = pagina.getViewport({ scale: 1, rotation: rotacionPlano });
            const escalaVisible = anchoDisponible / viewportBase.width;
            const viewportVisible = pagina.getViewport({ scale: escalaVisible, rotation: rotacionPlano });
            const viewportRender = pagina.getViewport({ scale: escalaVisible * SOBREMUESTREO, rotation: rotacionPlano });

            anchoBase = viewportVisible.width;
            altoBase = viewportVisible.height;

            pdfCanvas.width = viewportRender.width;
            pdfCanvas.height = viewportRender.height;
            pdfCanvas.style.width = anchoBase + 'px';
            pdfCanvas.style.height = altoBase + 'px';

            await pagina.render({ canvasContext: pdfCtx, viewport: viewportRender }).promise;

            factorActual = SOBREMUESTREO;
            trazos = [];
            ENSAYOS_Y_FOTO.forEach(({ tool }) => { contadoresEnsayo[tool] = 0; });
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

        /* ─── Fotografía: pin + cámara + vista previa ─────── */
        const inputFoto = document.getElementById('input-foto');
        const overlayFoto = document.getElementById('overlay-foto');
        const overlayFotoImg = document.getElementById('overlay-foto-img');
        const overlayFotoCerrar = document.getElementById('overlay-foto-cerrar');
        let pinFotoPendiente = null;

        function buscarFotoEnPunto(mundo) {
            const margenPantalla = 12;
            for (let i = trazos.length - 1; i >= 0; i--) {
                const item = trazos[i];
                if (item.tool !== 'foto' || capasVisibles[item.tool] === false) continue;
                const radioMundo = item.tamano / 2 + margenPantalla / vista.scale;
                if (Math.hypot(item.x - mundo.x, item.y - mundo.y) <= radioMundo) return item;
            }
            return null;
        }

        function solicitarFoto(mundo) {
            pinFotoPendiente = mundo;
            inputFoto.value = '';
            inputFoto.click();
        }

        inputFoto.addEventListener('change', () => {
            const archivo = inputFoto.files && inputFoto.files[0];
            const mundo = pinFotoPendiente;
            pinFotoPendiente = null;
            if (!archivo || !mundo) return;

            const lector = new FileReader();
            lector.onload = () => {
                const dataUrl = lector.result;
                registrarUsoCapa('foto');
                trazos.push({
                    tipo: 'icono',
                    tool: 'foto',
                    imagen: HERRAMIENTAS.foto.imagen,
                    x: mundo.x,
                    y: mundo.y,
                    tamano: HERRAMIENTAS.foto.tamano,
                    etiqueta: null,
                    dataUrl,
                });
                redibujarTrazos();
            };
            lector.readAsDataURL(archivo);
        });
        inputFoto.addEventListener('cancel', () => { pinFotoPendiente = null; });

        function mostrarFotoEnGrande(item) {
            overlayFotoImg.src = item.dataUrl;
            overlayFoto.classList.add('abierto');
        }

        function cerrarFotoGrande() {
            overlayFoto.classList.remove('abierto');
            overlayFotoImg.src = '';
        }

        overlayFotoCerrar.addEventListener('click', cerrarFotoGrande);
        overlayFoto.addEventListener('click', e => {
            if (e.target === overlayFoto) cerrarFotoGrande();
        });

        function iniciarAccionPuntero(puntosMundo) {
            const mundoPunto = puntosMundo[puntosMundo.length - 1];

            const fotoExistente = buscarFotoEnPunto(mundoPunto);
            if (fotoExistente) {
                mostrarFotoEnGrande(fotoExistente);
                return;
            }

            if (herramientaActual === 'foto') {
                solicitarFoto(mundoPunto);
                return;
            }

            const herramienta = HERRAMIENTAS[herramientaActual];
            registrarUsoCapa(herramientaActual);

            if (herramienta.tipo === 'icono') {
                const prefijo = PREFIJOS_ENSAYO[herramientaActual];
                let etiqueta = null;
                if (prefijo) {
                    contadoresEnsayo[herramientaActual]++;
                    etiqueta = prefijo + contadoresEnsayo[herramientaActual];
                }
                trazos.push({ tipo: 'icono', tool: herramientaActual, imagen: herramienta.imagen, x: mundoPunto.x, y: mundoPunto.y, tamano: herramienta.tamano, etiqueta, colorEtiqueta: COLORES_ENSAYO[herramientaActual] });
                redibujarTrazos();
            } else if (herramienta.tipo === 'linea') {
                dibujando = true;
                trazoActual = { tipo: 'trazo', tool: herramientaActual, color: herramienta.color, grosor: herramienta.grosor, puntos: [puntosMundo[0], mundoPunto] };
                trazos.push(trazoActual);
                redibujarTrazos();
            } else {
                dibujando = true;
                trazoActual = { tipo: 'trazo', tool: herramientaActual, color: herramienta.color, grosor: herramienta.grosor, puntos: [...puntosMundo] };
                trazos.push(trazoActual);
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
            lienzoWrap.setPointerCapture(e.pointerId);
            punterosActivos.set(e.pointerId, { x: e.clientX, y: e.clientY });

            if (punterosActivos.size === 2) {
                cancelarPunteroPendiente();
                dibujando = false;
                trazoActual = null;
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

            if (punteroPendiente && punteroPendiente.pointerId === e.pointerId) {
                const pantalla = posicionPantalla(e);
                const mundo = pantallaAMundo(pantalla.x, pantalla.y);
                punteroPendiente.puntos.push(mundo);
                return;
            }

            if (dibujando) {
                const pantalla = posicionPantalla(e);
                const mundo = pantallaAMundo(pantalla.x, pantalla.y);
                if (HERRAMIENTAS[herramientaActual].tipo === 'linea') {
                    trazoActual.puntos[1] = mundo;
                    redibujarTrazos();
                } else {
                    trazoActual.puntos.push(mundo);
                    drawCtx.lineTo(pantalla.x, pantalla.y);
                    drawCtx.stroke();
                }
            }
        });

        function finalizarPuntero(e) {
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

            if (dibujando && trazoActual && trazoActual.puntos.length > 3 && HERRAMIENTAS[herramientaActual].cierreAutomatico) {
                const inicio = trazoActual.puntos[0];
                trazoActual.puntos.push({ x: inicio.x, y: inicio.y });
                trazoActual.cerrado = true;
                redibujarTrazos();
            }

            dibujando = false;
            trazoActual = null;
            pinchInfo = null;
        }
        ['pointerup', 'pointercancel', 'pointerleave'].forEach(evt =>
            lienzoWrap.addEventListener(evt, finalizarPuntero)
        );

        cargarPdf();
    </script>
</body>
</html>
