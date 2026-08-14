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

        /* ── BOTÓN VOLVER (flotante, arriba a la derecha) ── */
        .btn-volver {
            position: fixed; top: 12px; right: 12px; z-index: 20;
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: #444; color: #fff; text-decoration: none;
            padding: 0.5rem 0.9rem; border-radius: 0.5rem;
            font-size: 0.82rem; font-weight: 600;
        }
        .btn-volver:hover { background: #555; }

        .lienzo-wrap {
            flex: 1;
            position: relative;
            overflow: hidden;
            background: #333;
            touch-action: none;
        }

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

    <a href="{{ route('planos_tc.index', $obraTc->id) }}" class="btn-volver">&larr; Volver</a>

    <div class="app">
        <nav class="toolbar-vertical">
            <div class="tool-submenu-wrap activo" id="danos-wrap">
                <button type="button" class="tool-btn" id="tool-danos" title="Daños">
                    <span class="tool-swatch" style="background:#e53e3e"></span>
                    Daños
                </button>
                <div class="submenu-lateral" id="submenu-danos">
                    <button type="button" class="tool-btn tool-submenu-item activo" data-tool="fisura" title="Fisura">
                        <span class="tool-swatch" style="background:#e53e3e"></span>
                        Fisura
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="corrosion" title="Corrosión">
                        <span class="tool-swatch" style="background:#d800c9"></span>
                        Corrosión
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="humedad" title="Humedad">
                        <span class="tool-swatch" style="background:#1565c0"></span>
                        Humedad
                    </button>
                    <button type="button" class="tool-btn tool-submenu-item" data-tool="coqueras" title="Coqueras">
                        <span class="tool-swatch" style="background:#0a8a3a"></span>
                        Coqueras
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
        </nav>

        <div class="lienzo-wrap" id="lienzo-wrap">
            <div class="lienzo" id="lienzo">
                <canvas id="pdf-canvas"></canvas>
            </div>
            <canvas id="draw-canvas"></canvas>
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
            { tool: 'esclerometria', url: @json(asset('img/iconos/esclerometria.svg')), prefijo: 'E', color: '#d800c9' },
            { tool: 'carbonatacion', url: @json(asset('img/iconos/carbonatacion.svg')), prefijo: 'C', color: '#1f4fd8' },
            { tool: 'pachometria', url: @json(asset('img/iconos/pachometria.svg')), prefijo: 'Pch', color: '#1f4fd8' },
            { tool: 'testigos', url: @json(asset('img/iconos/testigos.svg')), prefijo: 'T', color: '#0a5c26' },
            { tool: 'ultrasonido', url: @json(asset('img/iconos/ultrasonido.svg')), prefijo: 'U', color: '#e00000' },
        ];

        const PREFIJOS_ENSAYO = {};
        const COLORES_ENSAYO = {};
        const contadoresEnsayo = {};
        ENSAYOS.forEach(({ tool, prefijo, color }) => {
            PREFIJOS_ENSAYO[tool] = prefijo;
            COLORES_ENSAYO[tool] = color;
            contadoresEnsayo[tool] = 0;
        });

        const HERRAMIENTAS = {
            fisura: { tipo: 'trazo', color: '#e53e3e', grosor: 0.25 },
            corrosion: { tipo: 'trazo', color: '#d800c9', grosor: 0.25, cierreAutomatico: true },
            humedad: { tipo: 'trazo', color: '#1565c0', grosor: 0.25, cierreAutomatico: true },
            coqueras: { tipo: 'trazo', color: '#0a8a3a', grosor: 0.25, cierreAutomatico: true },
        };

        ENSAYOS.forEach(({ tool, url }) => {
            const img = new Image();
            img.onload = () => redibujarTrazos();
            img.src = url;
            HERRAMIENTAS[tool] = { tipo: 'icono', imagen: img, tamano: 26 };
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

                const btnPrincipal = wrapPadre.querySelector(':scope > .tool-btn');
                const imgOrigen = btn.querySelector('img');
                const swatchOrigen = btn.querySelector('.tool-swatch');
                if (imgOrigen) btnPrincipal.querySelector('img').src = imgOrigen.src;
                if (swatchOrigen) btnPrincipal.querySelector('.tool-swatch').style.background = swatchOrigen.style.background;

                wrapPadre.querySelector('.submenu-lateral').classList.remove('abierto');
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
            ENSAYOS.forEach(({ tool }) => { contadoresEnsayo[tool] = 0; });
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

        function iniciarAccionPuntero(puntosMundo) {
            const herramienta = HERRAMIENTAS[herramientaActual];

            if (herramienta.tipo === 'icono') {
                const mundo = puntosMundo[puntosMundo.length - 1];
                const prefijo = PREFIJOS_ENSAYO[herramientaActual];
                let etiqueta = null;
                if (prefijo) {
                    contadoresEnsayo[herramientaActual]++;
                    etiqueta = prefijo + contadoresEnsayo[herramientaActual];
                }
                trazos.push({ tipo: 'icono', imagen: herramienta.imagen, x: mundo.x, y: mundo.y, tamano: herramienta.tamano, etiqueta, colorEtiqueta: COLORES_ENSAYO[herramientaActual] });
                redibujarTrazos();
            } else {
                dibujando = true;
                trazoActual = { tipo: 'trazo', color: herramienta.color, grosor: herramienta.grosor, puntos: [...puntosMundo] };
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
                trazoActual.puntos.push(mundo);
                drawCtx.lineTo(pantalla.x, pantalla.y);
                drawCtx.stroke();
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
