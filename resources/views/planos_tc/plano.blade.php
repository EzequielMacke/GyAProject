<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $plano->descripcion ?? 'Plano' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #333; height: 100vh; display: flex; flex-direction: column; overflow: hidden; }

        .toolbar {
            flex-shrink: 0;
            display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;
            padding: 0.6rem 1rem;
            background: #222;
        }
        .toolbar a { color: #fff; text-decoration: none; font-size: 0.85rem; margin-right: 0.5rem; }
        .toolbar button {
            cursor: pointer; border: none; border-radius: 0.35rem;
            padding: 0.4rem 0.75rem; font-size: 0.82rem; font-weight: 600;
            background: #444; color: #fff;
        }
        .toolbar button:hover { background: #555; }
        .toolbar button.activo { background: #2a6fdb; }
        .swatch {
            width: 24px; height: 24px; border-radius: 50%;
            border: 2px solid #666; cursor: pointer;
        }
        .swatch.activo { border-color: #fff; }
        .toolbar label { color: #ccc; font-size: 0.78rem; display: flex; align-items: center; gap: 0.35rem; }
        .paginas { color: #ccc; font-size: 0.82rem; }

        .lienzo-wrap {
            flex: 1;
            overflow: auto;
            display: flex;
            justify-content: center;
            padding: 1.5rem;
        }
        .lienzo {
            position: relative;
            width: fit-content;
            height: fit-content;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        .lienzo canvas { display: block; }
        #draw-canvas {
            position: absolute; left: 0; top: 0;
            touch-action: none;
            cursor: crosshair;
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <a href="{{ route('planos_tc.index', $obraTc->id) }}">&larr; Volver</a>

        <div class="swatch activo" style="background:#e53e3e" data-color="#e53e3e"></div>
        <div class="swatch" style="background:#2a6fdb" data-color="#2a6fdb"></div>
        <div class="swatch" style="background:#1e9166" data-color="#1e9166"></div>
        <div class="swatch" style="background:#f5a623" data-color="#f5a623"></div>
        <div class="swatch" style="background:#111111" data-color="#111111"></div>

        <label>Grosor
            <input type="range" id="input-grosor" min="1" max="15" value="3">
        </label>

        <button type="button" id="btn-deshacer">Deshacer</button>
        <button type="button" id="btn-limpiar">Limpiar</button>

        <span class="paginas" id="paginas" style="display:none">
            <button type="button" id="btn-pagina-prev">&laquo;</button>
            <span id="texto-pagina"></span>
            <button type="button" id="btn-pagina-next">&raquo;</button>
        </span>
    </div>

    <div class="lienzo-wrap">
        <div class="lienzo" id="lienzo">
            <canvas id="pdf-canvas"></canvas>
            <canvas id="draw-canvas"></canvas>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const urlPdf = @json(Storage::url('planos/' . $plano->archivo));
        const dpr = window.devicePixelRatio || 1;

        const pdfCanvas = document.getElementById('pdf-canvas');
        const drawCanvas = document.getElementById('draw-canvas');
        const pdfCtx = pdfCanvas.getContext('2d');
        const drawCtx = drawCanvas.getContext('2d');

        let pdfDoc = null;
        let paginaActual = 1;
        let colorActual = '#e53e3e';
        let grosorActual = 3;
        let dibujando = false;
        let pilaDeshacer = [];

        async function renderPagina(numPagina) {
            const pagina = await pdfDoc.getPage(numPagina);
            const anchoDisponible = Math.min(document.querySelector('.lienzo-wrap').clientWidth - 48, 1400);
            const viewportBase = pagina.getViewport({ scale: 1 });
            const escala = anchoDisponible / viewportBase.width;
            const viewport = pagina.getViewport({ scale: escala });

            [pdfCanvas, drawCanvas].forEach(c => {
                c.width = viewport.width * dpr;
                c.height = viewport.height * dpr;
                c.style.width = viewport.width + 'px';
                c.style.height = viewport.height + 'px';
            });

            pdfCtx.setTransform(dpr, 0, 0, dpr, 0, 0);
            await pagina.render({ canvasContext: pdfCtx, viewport }).promise;

            drawCtx.setTransform(dpr, 0, 0, dpr, 0, 0);
            drawCtx.lineCap = 'round';
            drawCtx.lineJoin = 'round';

            pilaDeshacer = [];
        }

        async function cargarPdf() {
            pdfDoc = await pdfjsLib.getDocument(urlPdf).promise;

            if (pdfDoc.numPages > 1) {
                document.getElementById('paginas').style.display = 'inline-flex';
                actualizarTextoPagina();
            }

            await renderPagina(paginaActual);
        }

        function actualizarTextoPagina() {
            document.getElementById('texto-pagina').textContent = paginaActual + ' / ' + pdfDoc.numPages;
        }

        document.getElementById('btn-pagina-prev').addEventListener('click', async () => {
            if (paginaActual <= 1) return;
            paginaActual--;
            actualizarTextoPagina();
            await renderPagina(paginaActual);
        });

        document.getElementById('btn-pagina-next').addEventListener('click', async () => {
            if (paginaActual >= pdfDoc.numPages) return;
            paginaActual++;
            actualizarTextoPagina();
            await renderPagina(paginaActual);
        });

        /* ─── Dibujo a mano alzada ─────────────────────────── */
        function posicionRelativa(e) {
            const rect = drawCanvas.getBoundingClientRect();
            return { x: e.clientX - rect.left, y: e.clientY - rect.top };
        }

        function guardarEstado() {
            pilaDeshacer.push(drawCanvas.toDataURL());
            if (pilaDeshacer.length > 30) pilaDeshacer.shift();
        }

        drawCanvas.addEventListener('pointerdown', e => {
            guardarEstado();
            dibujando = true;
            const { x, y } = posicionRelativa(e);
            drawCtx.beginPath();
            drawCtx.moveTo(x, y);
            drawCanvas.setPointerCapture(e.pointerId);
        });

        drawCanvas.addEventListener('pointermove', e => {
            if (!dibujando) return;
            const { x, y } = posicionRelativa(e);
            drawCtx.strokeStyle = colorActual;
            drawCtx.lineWidth = grosorActual;
            drawCtx.lineTo(x, y);
            drawCtx.stroke();
        });

        ['pointerup', 'pointerleave', 'pointercancel'].forEach(evt =>
            drawCanvas.addEventListener(evt, () => { dibujando = false; })
        );

        function deshacer() {
            if (pilaDeshacer.length === 0) return;
            const anterior = pilaDeshacer.pop();
            const img = new Image();
            img.onload = () => {
                drawCtx.setTransform(1, 0, 0, 1, 0, 0);
                drawCtx.clearRect(0, 0, drawCanvas.width, drawCanvas.height);
                drawCtx.drawImage(img, 0, 0);
                drawCtx.setTransform(dpr, 0, 0, dpr, 0, 0);
            };
            img.src = anterior;
        }

        function limpiar() {
            guardarEstado();
            drawCtx.setTransform(1, 0, 0, 1, 0, 0);
            drawCtx.clearRect(0, 0, drawCanvas.width, drawCanvas.height);
            drawCtx.setTransform(dpr, 0, 0, dpr, 0, 0);
        }

        document.getElementById('btn-deshacer').addEventListener('click', deshacer);
        document.getElementById('btn-limpiar').addEventListener('click', limpiar);

        document.querySelectorAll('.swatch').forEach(sw => {
            sw.addEventListener('click', () => {
                document.querySelectorAll('.swatch').forEach(s => s.classList.remove('activo'));
                sw.classList.add('activo');
                colorActual = sw.dataset.color;
            });
        });

        document.getElementById('input-grosor').addEventListener('input', function () {
            grosorActual = Number(this.value);
        });

        cargarPdf();
    </script>
</body>
</html>
