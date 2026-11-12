/* Motor de dibujo de las anotaciones de un plano sobre un canvas 2D.
   Lo comparten el visor de planos (planos_tc/plano.blade.php: pantalla y
   descarga PNG) y el generador de informes (informe/generator.blade.php:
   vista previa e imagen para el Word), así un plano se ve igual en ambos.

   Coordenadas "mundo": puntos del PDF ya rotado (ver renderPagina en el
   visor). `proyectar(x, y)` las pasa al canvas destino y `escala` es
   cuántos px de ese canvas mide una unidad de mundo. */
(function () {
    const ESPACIO_TRAMA = 4;

    function dibujarTramaDiagonal(ctx, item, puntosProyectados, path, escala) {
        const xs = puntosProyectados.map(p => p.x);
        const ys = puntosProyectados.map(p => p.y);
        const minX = Math.min(...xs), maxX = Math.max(...xs);
        const minY = Math.min(...ys), maxY = Math.max(...ys);
        const centroX = (minX + maxX) / 2;
        const centroY = (minY + maxY) / 2;
        const diagonal = Math.hypot(maxX - minX, maxY - minY) || 1;
        const espaciado = Math.max(1.5, ESPACIO_TRAMA * escala);

        ctx.save();
        ctx.clip(path);
        ctx.translate(centroX, centroY);
        ctx.rotate(Math.PI / 4);
        ctx.strokeStyle = item.color;
        ctx.lineWidth = Math.max(0.5, item.grosor * escala * 0.5);
        ctx.globalAlpha = 0.75;
        ctx.beginPath();
        for (let x = -diagonal; x <= diagonal; x += espaciado) {
            ctx.moveTo(x, -diagonal);
            ctx.lineTo(x, diagonal);
        }
        ctx.stroke();
        ctx.restore();
    }

    /* opciones:
       - proyectar(x, y) → {x, y} en el canvas.
       - escala: px del canvas por unidad de mundo.
       - escalas: {danos, ensayos, fotos, texto} elegidas en el plano.
       - grupoEscala: tool → grupo de `escalas` (los textos sin grupo usan 'texto').
       - capasVisibles: tool → false para no dibujar esa capa.
       - omitir(item): true para saltear un elemento puntual (opcional).
       Los íconos se dibujan con item.imagen (un <img> ya cargado). */
    function dibujarItems(ctx, trazos, opciones) {
        const { proyectar, escala } = opciones;
        const escalas = opciones.escalas || {};
        const grupoEscala = opciones.grupoEscala || {};
        const capasVisibles = opciones.capasVisibles || {};
        const omitir = opciones.omitir || null;

        trazos.forEach(item => {
            if (capasVisibles[item.tool] === false) return;
            if (omitir && omitir(item)) return;

            if (item.tipo === 'icono') {
                if (!item.imagen || !item.imagen.complete || !item.imagen.naturalWidth) return;
                const ratio = item.imagen.naturalWidth / item.imagen.naturalHeight;
                const factorEscala = escalas[grupoEscala[item.tool]] ?? 1;
                const base = item.tamano * escala * factorEscala;
                const anchoItem = ratio >= 1 ? base : base * ratio;
                const altoItem = ratio >= 1 ? base / ratio : base;
                const centro = proyectar(item.x, item.y);
                ctx.drawImage(
                    item.imagen,
                    centro.x - anchoItem / 2,
                    centro.y - altoItem / 2,
                    anchoItem,
                    altoItem
                );

                if (item.etiqueta) {
                    const tamanoFuente = base * 0.32;
                    ctx.font = `bold ${tamanoFuente}px sans-serif`;
                    ctx.textBaseline = 'middle';
                    ctx.textAlign = 'left';
                    const textoX = centro.x + anchoItem / 2 - base * 0.14;
                    ctx.fillStyle = item.colorEtiqueta || '#000';
                    ctx.fillText(item.etiqueta, textoX, centro.y);
                }
                return;
            }

            if (item.tipo === 'texto') {
                const centro = proyectar(item.x, item.y);
                const grupo = grupoEscala[item.tool] ?? 'texto';
                const tamanoFuente = item.tamano * escala * (escalas[grupo] ?? 1);
                ctx.font = `600 ${tamanoFuente}px sans-serif`;
                ctx.textBaseline = 'middle';
                ctx.textAlign = 'left';
                ctx.fillStyle = item.color;
                if (item.rotacion) {
                    ctx.save();
                    ctx.translate(centro.x, centro.y);
                    ctx.rotate(item.rotacion * Math.PI / 180);
                    ctx.fillText(item.texto, 0, 0);
                    ctx.restore();
                } else {
                    ctx.fillText(item.texto, centro.x, centro.y);
                }
                return;
            }

            if (!item.puntos || item.puntos.length < 2) return;
            const puntosProyectados = item.puntos.map(p => proyectar(p.x, p.y));
            const path = new Path2D();
            path.moveTo(puntosProyectados[0].x, puntosProyectados[0].y);
            for (let i = 1; i < puntosProyectados.length; i++) {
                path.lineTo(puntosProyectados[i].x, puntosProyectados[i].y);
            }

            if (item.cerrado) {
                path.closePath();
                if (item.relleno) dibujarTramaDiagonal(ctx, item, puntosProyectados, path, escala);
            }

            ctx.strokeStyle = item.color;
            ctx.lineWidth = item.grosor * escala;
            ctx.stroke(path);
        });
    }

    window.PlanoRender = { dibujarItems };
})();
