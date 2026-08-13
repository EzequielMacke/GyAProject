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
        .tool-btn:hover { background: #333; }
        .tool-btn.activo { background: #2a6fdb; color: #fff; }
        .tool-swatch {
            width: 20px; height: 20px; border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.55);
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
            <button type="button" class="tool-btn activo" id="tool-fisura" data-tool="fisura" title="Fisura">
                <span class="tool-swatch" style="background:#e53e3e"></span>
                Fisura
            </button>
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
        const dpr = window.devicePixelRatio || 1;
        const SOBREMUESTREO = Math.min(Math.max(dpr, 1) * 1.5, 3);
        const ZOOM_MIN = 0.05;
        const ZOOM_MAX = 40;

        /* ─── Herramientas de dibujo ───────────────────────── */
        const HERRAMIENTAS = {
            fisura: { color: '#e53e3e', grosor: 0.25 },
        };
        let herramientaActual = 'fisura';

        document.querySelectorAll('.tool-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tool-btn').forEach(b => b.classList.remove('activo'));
                btn.classList.add('activo');
                herramientaActual = btn.dataset.tool;
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
            const factorMaxSeguro = Math.min(12000 / anchoBase, 12000 / altoBase, ZOOM_MAX * SOBREMUESTREO);
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

            const factorMaxSeguro = Math.min(12000 / anchoBase, 12000 / altoBase, ZOOM_MAX * SOBREMUESTREO);
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
                const escalaVisible = anchoBase / pagina.getViewport({ scale: 1 }).width;
                const viewportRender = pagina.getViewport({ scale: escalaVisible * nuevoFactor });

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

            trazos.forEach(trazo => {
                if (trazo.puntos.length < 2) return;
                drawCtx.strokeStyle = trazo.color;
                drawCtx.lineWidth = trazo.grosor * vista.scale;
                drawCtx.beginPath();
                const p0 = mundoAPantalla(trazo.puntos[0].x, trazo.puntos[0].y);
                drawCtx.moveTo(p0.x, p0.y);
                for (let i = 1; i < trazo.puntos.length; i++) {
                    const p = mundoAPantalla(trazo.puntos[i].x, trazo.puntos[i].y);
                    drawCtx.lineTo(p.x, p.y);
                }
                drawCtx.stroke();
            });
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
            const viewportBase = pagina.getViewport({ scale: 1 });
            const escalaVisible = anchoDisponible / viewportBase.width;
            const viewportVisible = pagina.getViewport({ scale: escalaVisible });
            const viewportRender = pagina.getViewport({ scale: escalaVisible * SOBREMUESTREO });

            anchoBase = viewportVisible.width;
            altoBase = viewportVisible.height;

            pdfCanvas.width = viewportRender.width;
            pdfCanvas.height = viewportRender.height;
            pdfCanvas.style.width = anchoBase + 'px';
            pdfCanvas.style.height = altoBase + 'px';

            await pagina.render({ canvasContext: pdfCtx, viewport: viewportRender }).promise;

            factorActual = SOBREMUESTREO;
            trazos = [];
            centrarVista();
        }

        async function cargarPdf() {
            ajustarTamanoHud();
            pdfDoc = await pdfjsLib.getDocument(urlPdf).promise;
            await renderPagina();
        }

        /* ─── Dibujo y pellizco (mouse, lápiz y táctil) ───────
             Un puntero dibuja; dos puntero hacen zoom + paneo. ─ */
        const punterosActivos = new Map();
        let dibujando = false;
        let pinchInfo = null;

        function distancia(p1, p2) { return Math.hypot(p1.x - p2.x, p1.y - p2.y); }

        function posicionPantalla(e) {
            const rect = lienzoWrap.getBoundingClientRect();
            return { x: e.clientX - rect.left, y: e.clientY - rect.top };
        }

        lienzoWrap.addEventListener('pointerdown', e => {
            lienzoWrap.setPointerCapture(e.pointerId);
            punterosActivos.set(e.pointerId, { x: e.clientX, y: e.clientY });

            if (punterosActivos.size === 2) {
                dibujando = false;
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
                const herramienta = HERRAMIENTAS[herramientaActual];
                dibujando = true;
                const pantalla = posicionPantalla(e);
                const mundo = pantallaAMundo(pantalla.x, pantalla.y);
                trazoActual = { color: herramienta.color, grosor: herramienta.grosor, puntos: [mundo] };
                trazos.push(trazoActual);
                drawCtx.strokeStyle = herramienta.color;
                drawCtx.lineWidth = herramienta.grosor * vista.scale;
                drawCtx.beginPath();
                drawCtx.moveTo(pantalla.x, pantalla.y);
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

            if (dibujando) {
                const pantalla = posicionPantalla(e);
                const mundo = pantallaAMundo(pantalla.x, pantalla.y);
                trazoActual.puntos.push(mundo);
                drawCtx.lineTo(pantalla.x, pantalla.y);
                drawCtx.stroke();
            }
        });

        function finalizarPuntero(e) {
            punterosActivos.delete(e.pointerId);
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
