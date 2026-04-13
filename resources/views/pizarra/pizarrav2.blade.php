<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Pizarra</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #f5f5f5;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            font-family: sans-serif;
            touch-action: none;
            user-select: none;
            -webkit-user-select: none;
        }

        #toolbar {
            display: flex;
            gap: 6px;
            align-items: center;
            padding: 7px 12px;
            background: #fff;
            border-bottom: 1px solid #ddd;
            flex-shrink: 0;
            flex-wrap: wrap;
            min-height: 50px;
            z-index: 10;
            box-shadow: 0 1px 4px rgba(0,0,0,0.07);
        }

        .sep { width: 1px; height: 28px; background: #e0e0e0; flex-shrink: 0; }

        button {
            padding: 7px 13px;
            border: 1px solid #d0d0d0;
            border-radius: 7px;
            cursor: pointer;
            font-size: 13px;
            background: #f7f7f7;
            color: #333;
            transition: background 0.12s, border-color 0.12s;
            touch-action: manipulation;
            min-height: 36px;
            white-space: nowrap;
        }
        button:hover  { background: #eaeaea; }
        button.active { background: #4a90e2; color: #fff; border-color: #3178c6; }
        button.eraser { background: #f3e8d6; border-color: #d4a76a; color: #7a4f1e; }
        button.eraser.active { background: #e07b20; color: #fff; border-color: #b5621a; }
        button.danger { background: #fee; border-color: #f99; color: #c00; }
        button.danger:hover { background: #fdd; }

        #zoomLabel {
            font-size: 13px;
            color: #666;
            min-width: 46px;
            text-align: center;
        }

        #snapLabel {
            font-size: 13px;
            color: #555;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }
        #snapLabel input { cursor: pointer; width: 15px; height: 15px; }

        #canvas-wrap {
            flex: 1;
            overflow: hidden;
            position: relative;
            touch-action: none;
        }

        canvas {
            position: absolute;
            top: 0; left: 0;
            touch-action: none;
        }

        /* Dynamic cursor via JS class */
        canvas.mode-draw   { cursor: crosshair; }
        canvas.mode-eraser { cursor: cell; }
        canvas.mode-text   { cursor: text; }
        canvas.panning     { cursor: grab; }

        /* Info badge (bottom center) */
        #infoBadge {
            position: fixed;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(30,30,30,0.78);
            color: #fff;
            font-size: 12px;
            padding: 5px 16px;
            border-radius: 20px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 100;
            white-space: nowrap;
        }
        #infoBadge.show { opacity: 1; }

        /* Text confirm panel */
        #textPanel {
            position: fixed;
            z-index: 200;
            background: #fff;
            border: 1.5px solid #4a90e2;
            border-radius: 10px;
            padding: 7px 10px;
            display: none;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.18);
            font-size: 13px;
        }
        #textPanel input {
            font-size: 15px;
            padding: 3px 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            min-width: 140px;
            outline: none;
            font-family: sans-serif;
        }
        #textPanel .tp-hint {
            font-size: 11px;
            color: #999;
            white-space: nowrap;
        }

        /* Snap dot */
        #snapDot {
            position: fixed;
            width: 12px;
            height: 12px;
            border: 2px solid #4a90e2;
            border-radius: 50%;
            pointer-events: none;
            display: none;
            transform: translate(-50%, -50%);
            z-index: 50;
            background: rgba(74,144,226,0.15);
        }
    </style>
</head>
<body>

<div id="toolbar">
    <button id="btnDraw"   class="active">✏ Dibujar</button>
    <button id="btnEraser" class="eraser">⌫ Borrador</button>
    <button id="btnText">𝑻 Texto</button>
    <div class="sep"></div>
    <button id="btnUndo">↩ Deshacer</button>
    <div class="sep"></div>
    <button id="btnZoomOut" style="font-size:16px;padding:4px 12px;">−</button>
    <span id="zoomLabel">100%</span>
    <button id="btnZoomIn"  style="font-size:16px;padding:4px 12px;">+</button>
    <button id="btnZoomReset" title="Restablecer vista">↺</button>
    <div class="sep"></div>
    <label id="snapLabel">
        <input type="checkbox" id="chkSnap" checked>
        Snap
    </label>
    <div class="sep"></div>
    <button id="btnClear" class="danger">Limpiar</button>
</div>

<div id="canvas-wrap">
    <canvas id="canvas"></canvas>
</div>

<div id="infoBadge"></div>
<div id="snapDot"></div>

<div id="textPanel">
    <input id="textPanelInput" type="text" placeholder="Escribir texto…">
    <span class="tp-hint">Enter ✓</span>
    <button id="btnTextOk" style="background:#4a90e2;color:#fff;border-color:#3178c6;padding:4px 10px;min-height:30px;">✓</button>
    <button id="btnTextCancel" style="padding:4px 10px;min-height:30px;">✗</button>
</div>

<script>
(() => {
'use strict';

// ─────────────────────────────────────────────────────────────
//  CONFIG
// ─────────────────────────────────────────────────────────────
const GRID        = 40;    // world-units per grid cell
const SNAP_PX     = 18;    // element-snap radius in screen pixels
const DP_EPSILON  = 10;    // Douglas-Peucker epsilon (world units)
const MIN_LEN     = 8;     // minimum stroke length to process
const MIN_MOVE    = 1.5;   // minimum world-unit movement to record a point
const ERASE_PX    = 14;    // eraser hit radius in world units
const BADGE_MS    = 1600;  // how long detection badge shows

// ─────────────────────────────────────────────────────────────
//  DOM REFS
// ─────────────────────────────────────────────────────────────
const canvas    = document.getElementById('canvas');
const ctx       = canvas.getContext('2d');
const wrap      = document.getElementById('canvas-wrap');
const infoBadge = document.getElementById('infoBadge');
const snapDot   = document.getElementById('snapDot');
const zoomLabel = document.getElementById('zoomLabel');

// ─────────────────────────────────────────────────────────────
//  STATE
// ─────────────────────────────────────────────────────────────
const ZOOM_DEFAULT = 0.4;
let zoom   = ZOOM_DEFAULT;
let panX   = 0;
let panY   = 0;

const elements   = [];   // committed shapes
const undoStack  = [];   // array of element-array snapshots

let snapEnabled  = true;
let mode         = 'draw'; // 'draw' | 'eraser'

// Drawing
let drawing      = false;
let rawPoints    = [];   // {sx, sy} screen-space points

// Eraser drag
let erasing         = false;
let eraseUndoSaved  = false;

// Pan (right/middle mouse)
let panning      = false;
let panSX0 = 0, panSY0 = 0, panX0 = 0, panY0 = 0;

// Pinch
let pinching     = false;
let pinchDist0   = 0, pinchZoom0 = 1;
let pinchCX      = 0, pinchCY = 0;
let pinchPanX0   = 0, pinchPanY0 = 0;

let badgeTimer   = null;

// Text mode
let textPlaceW   = null;   // {x,y} mundo donde se colocará el nuevo texto
let selText      = null;   // elemento de texto seleccionado
let txtForm      = null;   // transformación activa: 'move'|'resize'|'rotate'
let txtFormStart = null;   // snapshot del inicio de la transformación

// ─────────────────────────────────────────────────────────────
//  RESIZE
// ─────────────────────────────────────────────────────────────
function resize() {
    canvas.width  = wrap.clientWidth;
    canvas.height = wrap.clientHeight;
    render();
}
new ResizeObserver(resize).observe(wrap);

// ─────────────────────────────────────────────────────────────
//  COORD TRANSFORMS
// ─────────────────────────────────────────────────────────────
function s2w(sx, sy) {
    return { x: (sx - panX) / zoom, y: (sy - panY) / zoom };
}
function w2s(wx, wy) {
    return { x: wx * zoom + panX, y: wy * zoom + panY };
}

// ─────────────────────────────────────────────────────────────
//  MATH UTILITIES
// ─────────────────────────────────────────────────────────────
function dist(a, b) {
    return Math.hypot(b.x - a.x, b.y - a.y);
}

function strokeLength(pts) {
    let l = 0;
    for (let i = 1; i < pts.length; i++) l += dist(pts[i-1], pts[i]);
    return l;
}

// Perpendicular distance from point p to segment (a→b)
function ptSegDist(p, a, b) {
    const dx = b.x - a.x, dy = b.y - a.y;
    const len2 = dx*dx + dy*dy;
    if (len2 === 0) return dist(p, a);
    const t = Math.max(0, Math.min(1, ((p.x-a.x)*dx + (p.y-a.y)*dy) / len2));
    return dist(p, { x: a.x + t*dx, y: a.y + t*dy });
}

// Max perpendicular deviation of all pts from line (a→b)
function maxDeviation(pts, a, b) {
    const dx = b.x - a.x, dy = b.y - a.y;
    const len = Math.hypot(dx, dy);
    if (len === 0) return 0;
    let max = 0;
    for (const p of pts) {
        const d = Math.abs(dy*p.x - dx*p.y + b.x*a.y - b.y*a.x) / len;
        if (d > max) max = d;
    }
    return max;
}

// Douglas-Peucker polyline simplification
function dpSimplify(pts, eps) {
    if (pts.length <= 2) return pts.slice();
    let maxD = 0, maxI = 0;
    const first = pts[0], last = pts[pts.length - 1];
    for (let i = 1; i < pts.length - 1; i++) {
        const d = ptSegDist(pts[i], first, last);
        if (d > maxD) { maxD = d; maxI = i; }
    }
    if (maxD > eps) {
        const L = dpSimplify(pts.slice(0, maxI + 1), eps);
        const R = dpSimplify(pts.slice(maxI), eps);
        return [...L.slice(0, -1), ...R];
    }
    return [first, last];
}

// ─────────────────────────────────────────────────────────────
//  SNAP
// ─────────────────────────────────────────────────────────────
function gridSnap(x, y) {
    return { x: Math.round(x / GRID) * GRID, y: Math.round(y / GRID) * GRID };
}

// Returns snapped world point given screen position
function bestSnap(sx, sy) {
    const w = s2w(sx, sy);
    if (!snapEnabled) return w;

    const radius = SNAP_PX / zoom;
    let best = null, bestD = radius;

    for (const el of elements) {
        for (const p of elementSnapPoints(el)) {
            const d = dist(p, w);
            if (d < bestD) { bestD = d; best = p; }
        }
    }
    return best || gridSnap(w.x, w.y);
}

function elementSnapPoints(el) {
    switch (el.type) {
        case 'rect': {
            const { x, y, w, h } = el;
            return [
                { x, y }, { x: x+w, y }, { x, y: y+h }, { x: x+w, y: y+h },
                { x: x+w/2, y }, { x: x+w/2, y: y+h },
                { x, y: y+h/2 }, { x: x+w, y: y+h/2 },
                { x: x+w/2, y: y+h/2 },
            ];
        }
        case 'circle': {
            const { cx, cy, r } = el;
            return [
                { x: cx,   y: cy   },
                { x: cx,   y: cy-r },
                { x: cx,   y: cy+r },
                { x: cx-r, y: cy   },
                { x: cx+r, y: cy   },
            ];
        }
        case 'line': {
            return [
                { x: el.x1, y: el.y1 },
                { x: el.x2, y: el.y2 },
                { x: (el.x1+el.x2)/2, y: (el.y1+el.y2)/2 },
            ];
        }
        case 'polyline': {
            const pts = el.points;
            const out = pts.slice();
            for (let i = 0; i < pts.length - 1; i++) {
                out.push({ x: (pts[i].x+pts[i+1].x)/2, y: (pts[i].y+pts[i+1].y)/2 });
            }
            if (el.closed && pts.length > 1) {
                const last = pts[pts.length-1], first = pts[0];
                out.push({ x: (last.x+first.x)/2, y: (last.y+first.y)/2 });
            }
            return out;
        }
        case 'freehand': {
            const pts = el.points;
            return [pts[0], pts[pts.length-1]];
        }
        case 'text': {
            const { w, h } = textMetrics(el);
            return [{ x: el.x + w / 2, y: el.y + h / 2 }];
        }
    }
    return [];
}

// Decompose a detected shape into individual line elements (except circle)
function decomposeToLines(shape) {
    switch (shape.type) {
        case 'rect': {
            const { x, y, w, h } = shape;
            return [
                { type: 'line', x1: x,   y1: y,   x2: x+w, y2: y   },
                { type: 'line', x1: x+w, y1: y,   x2: x+w, y2: y+h },
                { type: 'line', x1: x+w, y1: y+h, x2: x,   y2: y+h },
                { type: 'line', x1: x,   y1: y+h, x2: x,   y2: y   },
            ];
        }
        case 'polyline': {
            const pts = shape.points;
            const lines = [];
            for (let i = 0; i < pts.length - 1; i++) {
                lines.push({ type: 'line', x1: pts[i].x, y1: pts[i].y, x2: pts[i+1].x, y2: pts[i+1].y });
            }
            if (shape.closed && pts.length > 1) {
                const last = pts[pts.length - 1], first = pts[0];
                lines.push({ type: 'line', x1: last.x, y1: last.y, x2: first.x, y2: first.y });
            }
            return lines;
        }
        default:
            // circle, line, freehand → single element
            return [shape];
    }
}

// Snap a finalised shape's key points to the grid
function snapShapeToGrid(shape) {
    if (!snapEnabled) return shape;
    switch (shape.type) {
        case 'line': {
            const s1 = gridSnap(shape.x1, shape.y1);
            const s2 = gridSnap(shape.x2, shape.y2);
            return { ...shape, x1: s1.x, y1: s1.y, x2: s2.x, y2: s2.y };
        }
        case 'rect': {
            const s1 = gridSnap(shape.x, shape.y);
            const s2 = gridSnap(shape.x + shape.w, shape.y + shape.h);
            const w = s2.x - s1.x, h = s2.y - s1.y;
            if (Math.abs(w) < 4 || Math.abs(h) < 4) return null;
            return { ...shape, x: s1.x, y: s1.y, w, h };
        }
        case 'circle': {
            const sc = gridSnap(shape.cx, shape.cy);
            return { ...shape, cx: sc.x, cy: sc.y };
        }
        case 'polyline': {
            return { ...shape, points: shape.points.map(p => gridSnap(p.x, p.y)) };
        }
        default:
            return shape;
    }
}

// ─────────────────────────────────────────────────────────────
//  SHAPE DETECTION
// ─────────────────────────────────────────────────────────────
function detectShape(wPoints) {
    if (wPoints.length < 3) return null;
    const total = strokeLength(wPoints);
    if (total < MIN_LEN) return null;

    const xs = wPoints.map(p => p.x), ys = wPoints.map(p => p.y);
    const minX = Math.min(...xs), maxX = Math.max(...xs);
    const minY = Math.min(...ys), maxY = Math.max(...ys);
    const bW = maxX - minX, bH = maxY - minY;
    const diag = Math.hypot(bW, bH);

    const p0 = wPoints[0], pN = wPoints[wPoints.length - 1];
    const endDist = dist(p0, pN);
    const isClosed = endDist < Math.max(diag * 0.3, 20);

    // ── CIRCLE ─────────────────────────────────────────────────
    if (isClosed && diag > 20) {
        const cx = xs.reduce((a,b) => a+b, 0) / xs.length;
        const cy = ys.reduce((a,b) => a+b, 0) / ys.length;
        const radii  = wPoints.map(p => dist(p, {x:cx, y:cy}));
        const avgR   = radii.reduce((a,b) => a+b, 0) / radii.length;
        const maxErr = Math.max(...radii.map(r => Math.abs(r - avgR)));
        const aspect = bW > bH ? bH/bW : bW/bH;

        if (aspect > 0.5 && maxErr / avgR < 0.25) {
            return { type: 'circle', cx: (minX+maxX)/2, cy: (minY+maxY)/2, r: (bW+bH)/4 };
        }
    }

    // Simplify stroke for corner-based detection
    const simplified = dpSimplify(wPoints, DP_EPSILON);

    // ── CLOSED SHAPES (triangle, rect, polygon) ─────────────────
    if (isClosed) {
        let corners = simplified.slice();
        // Remove trailing point if it's almost the same as the first
        if (dist(corners[0], corners[corners.length-1]) < DP_EPSILON * 1.5) {
            corners = corners.slice(0, -1);
        }
        const n = corners.length;

        if (n === 3) {
            // Triangle
            return { type: 'polyline', points: corners, closed: true, label: 'Triángulo' };
        }
        if (n === 4) {
            // Check if angles are roughly 90° → rectangle
            if (hasRightAngles(corners)) {
                return { type: 'rect', x: minX, y: minY, w: bW, h: bH };
            }
            return { type: 'polyline', points: corners, closed: true, label: 'Cuadrilátero' };
        }
        if (n >= 5) {
            return { type: 'polyline', points: corners, closed: true, label: `Polígono (${n} lados)` };
        }
    }

    // ── OPEN SHAPES ─────────────────────────────────────────────

    // Line: all points very close to straight line between endpoints
    if (endDist > MIN_LEN && maxDeviation(wPoints, p0, pN) / endDist < 0.13) {
        return { type: 'line', x1: p0.x, y1: p0.y, x2: pN.x, y2: pN.y };
    }

    // Polyline: open multi-segment path (L, Z, N shapes, etc.)
    // Only apply if simplified gives a reasonable number of segments
    if (simplified.length >= 3 && simplified.length <= 9) {
        // Check that corners are actually sharp (angle change > ~18°)
        if (hasSharpCorners(simplified)) {
            const segs = simplified.length - 1;
            simplified[0] = p0;
            simplified[simplified.length - 1] = pN;
            return { type: 'polyline', points: simplified, closed: false, label: `Polilínea (${segs} seg.)` };
        }
    }

    return null; // freehand
}

// Check that a closed polygon's corners are roughly 90°
function hasRightAngles(pts) {
    const n = pts.length;
    for (let i = 0; i < n; i++) {
        const a = pts[i], b = pts[(i+1) % n], c = pts[(i+2) % n];
        const v1 = { x: b.x-a.x, y: b.y-a.y };
        const v2 = { x: c.x-b.x, y: c.y-b.y };
        const dot  = v1.x*v2.x + v1.y*v2.y;
        const mag  = Math.hypot(v1.x, v1.y) * Math.hypot(v2.x, v2.y);
        if (mag === 0) return false;
        if (Math.abs(dot/mag) > 0.45) return false; // > ~63° off perpendicular
    }
    return true;
}

// Check that an open simplified polyline has at least one sharp turn
function hasSharpCorners(pts) {
    for (let i = 1; i < pts.length - 1; i++) {
        const a = pts[i-1], b = pts[i], c = pts[i+1];
        const v1 = { x: b.x-a.x, y: b.y-a.y };
        const v2 = { x: c.x-b.x, y: c.y-b.y };
        const dot  = v1.x*v2.x + v1.y*v2.y;
        const mag  = Math.hypot(v1.x, v1.y) * Math.hypot(v2.x, v2.y);
        if (mag === 0) continue;
        const cos = dot / mag;
        // Sharp corner = vectors are not nearly parallel (cos < 0.94 ≈ >20°)
        if (cos < 0.94) return true;
    }
    return false;
}

// ─────────────────────────────────────────────────────────────
//  ERASER HIT TEST
// ─────────────────────────────────────────────────────────────
function hitTest(el, wx, wy) {
    const p = { x: wx, y: wy };
    const T = ERASE_PX;

    switch (el.type) {
        case 'line':
            return ptSegDist(p, {x:el.x1, y:el.y1}, {x:el.x2, y:el.y2}) < T;

        case 'rect':
            // Inside rect or near border
            return wx >= el.x - T && wx <= el.x + el.w + T
                && wy >= el.y - T && wy <= el.y + el.h + T;

        case 'circle': {
            const d = dist(p, {x:el.cx, y:el.cy});
            // Inside circle or near circumference
            return d < el.r + T;
        }
        case 'polyline': {
            const pts = el.points;
            for (let i = 0; i < pts.length - 1; i++) {
                if (ptSegDist(p, pts[i], pts[i+1]) < T) return true;
            }
            if (el.closed && pts.length > 1) {
                if (ptSegDist(p, pts[pts.length-1], pts[0]) < T) return true;
            }
            return false;
        }
        case 'freehand': {
            const pts = el.points;
            for (let i = 0; i < pts.length - 1; i++) {
                if (ptSegDist(p, pts[i], pts[i+1]) < T) return true;
            }
            return false;
        }
        case 'text': {
            const lp = textLocalPoint(el, wx, wy);
            const { w, h } = textMetrics(el);
            return Math.abs(lp.x) <= w / 2 + T && Math.abs(lp.y) <= h / 2 + T;
        }
    }
    return false;
}

// ─────────────────────────────────────────────────────────────
//  RENDER
// ─────────────────────────────────────────────────────────────
function render() {
    const W = canvas.width, H = canvas.height;
    ctx.clearRect(0, 0, W, H);
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, W, H);

    ctx.save();
    ctx.translate(panX, panY);
    ctx.scale(zoom, zoom);

    drawGrid();
    for (const el of elements) drawElement(el);
    if (drawing && rawPoints.length > 1) drawPreview();
    if (mode === 'text' && selText) drawTextHandles(selText);

    ctx.restore();
}

function drawGrid() {
    const W = canvas.width, H = canvas.height;
    const tl = s2w(0, 0), br = s2w(W, H);
    const sx = Math.floor(tl.x / GRID) * GRID;
    const sy = Math.floor(tl.y / GRID) * GRID;

    ctx.strokeStyle = '#ebebeb';
    ctx.lineWidth   = 1 / zoom;
    ctx.beginPath();
    for (let x = sx; x <= br.x + GRID; x += GRID) {
        ctx.moveTo(x, tl.y - 1); ctx.lineTo(x, br.y + 1);
    }
    for (let y = sy; y <= br.y + GRID; y += GRID) {
        ctx.moveTo(tl.x - 1, y); ctx.lineTo(br.x + 1, y);
    }
    ctx.stroke();

    // Intersection dots (only when cells are big enough to see them)
    if (GRID * zoom >= 8) {
        ctx.fillStyle = '#c8c8c8';
        for (let x = sx; x <= br.x + GRID; x += GRID) {
            for (let y = sy; y <= br.y + GRID; y += GRID) {
                ctx.beginPath();
                ctx.arc(x, y, 1.8 / zoom, 0, Math.PI * 2);
                ctx.fill();
            }
        }
    }
}

function drawElement(el) {
    ctx.lineCap  = 'round';
    ctx.lineJoin = 'round';
    ctx.lineWidth = 2 / zoom;

    switch (el.type) {
        case 'rect':
            ctx.fillStyle   = 'rgba(74,144,226,0.07)';
            ctx.strokeStyle = '#2c3e50';
            ctx.beginPath();
            ctx.rect(el.x, el.y, el.w, el.h);
            ctx.fill();
            ctx.stroke();
            break;

        case 'circle':
            ctx.fillStyle   = 'rgba(74,144,226,0.07)';
            ctx.strokeStyle = '#2c3e50';
            ctx.beginPath();
            ctx.arc(el.cx, el.cy, el.r, 0, Math.PI * 2);
            ctx.fill();
            ctx.stroke();
            break;

        case 'line':
            ctx.strokeStyle = '#2c3e50';
            ctx.beginPath();
            ctx.moveTo(el.x1, el.y1);
            ctx.lineTo(el.x2, el.y2);
            ctx.stroke();
            break;

        case 'polyline': {
            const pts = el.points;
            if (pts.length < 2) break;
            const isClosed = el.closed;
            ctx.fillStyle   = isClosed ? 'rgba(74,144,226,0.07)' : 'transparent';
            ctx.strokeStyle = '#2c3e50';
            ctx.beginPath();
            ctx.moveTo(pts[0].x, pts[0].y);
            for (let i = 1; i < pts.length; i++) ctx.lineTo(pts[i].x, pts[i].y);
            if (isClosed) ctx.closePath();
            if (isClosed) ctx.fill();
            ctx.stroke();
            break;
        }
        case 'freehand': {
            const pts = el.points;
            if (pts.length < 2) break;
            ctx.strokeStyle = '#2c3e50';
            ctx.beginPath();
            ctx.moveTo(pts[0].x, pts[0].y);
            for (let i = 1; i < pts.length; i++) ctx.lineTo(pts[i].x, pts[i].y);
            ctx.stroke();
            break;
        }
        case 'text': {
            ctx.save();
            ctx.font = `${el.fontSize}px sans-serif`;
            const tw = ctx.measureText(el.text).width;
            const th = el.fontSize * 1.15;
            ctx.translate(el.x + tw / 2, el.y + th / 2);
            ctx.rotate(el.rotation || 0);
            ctx.fillStyle    = '#2c3e50';
            ctx.textBaseline = 'middle';
            ctx.textAlign    = 'center';
            ctx.fillText(el.text, 0, 0);
            ctx.restore();
            break;
        }
    }
}

function drawPreview() {
    ctx.strokeStyle = '#555';
    ctx.lineWidth   = 2 / zoom;
    ctx.lineCap     = 'round';
    ctx.lineJoin    = 'round';
    ctx.beginPath();
    const first = s2w(rawPoints[0].sx, rawPoints[0].sy);
    ctx.moveTo(first.x, first.y);
    for (let i = 1; i < rawPoints.length; i++) {
        const p = s2w(rawPoints[i].sx, rawPoints[i].sy);
        ctx.lineTo(p.x, p.y);
    }
    ctx.stroke();
}

// ─────────────────────────────────────────────────────────────
//  INFO BADGE
// ─────────────────────────────────────────────────────────────
function showBadge(msg) {
    clearTimeout(badgeTimer);
    infoBadge.textContent = msg;
    infoBadge.classList.add('show');
    badgeTimer = setTimeout(() => infoBadge.classList.remove('show'), BADGE_MS);
}
function hideBadge() {
    clearTimeout(badgeTimer);
    infoBadge.classList.remove('show');
}

// ─────────────────────────────────────────────────────────────
//  SNAP DOT
// ─────────────────────────────────────────────────────────────
function updateSnapDot(sx, sy) {
    if (!snapEnabled || !drawing) { snapDot.style.display = 'none'; return; }
    const snap = bestSnap(sx, sy);
    const ss   = w2s(snap.x, snap.y);
    const rect = canvas.getBoundingClientRect();
    snapDot.style.display = 'block';
    snapDot.style.left = (rect.left + ss.x) + 'px';
    snapDot.style.top  = (rect.top  + ss.y) + 'px';
}

// ─────────────────────────────────────────────────────────────
//  DRAWING ACTIONS
// ─────────────────────────────────────────────────────────────
function startDraw(sx, sy) {
    drawing   = true;
    rawPoints = [{ sx, sy }];
    showBadge('Dibujando…');
}

function continueDraw(sx, sy) {
    if (!drawing) return;
    const last = rawPoints[rawPoints.length - 1];
    const wLast = s2w(last.sx, last.sy);
    const wCurr = s2w(sx, sy);
    if (dist(wLast, wCurr) < MIN_MOVE) return;
    rawPoints.push({ sx, sy });
    updateSnapDot(sx, sy);
    render();
}

function endDraw() {
    if (!drawing) return;
    drawing = false;
    snapDot.style.display = 'none';
    hideBadge();

    if (rawPoints.length < 2) { rawPoints = []; render(); return; }

    // Convert screen points → world, snapping first and last
    const wPoints = rawPoints.map((p, i) => {
        if (i === 0 || i === rawPoints.length - 1) return bestSnap(p.sx, p.sy);
        return s2w(p.sx, p.sy);
    });

    const detected = detectShape(wPoints);
    let el = null;

    if (detected) {
        const snapped = snapShapeToGrid(detected);
        if (snapped) {
            el = snapped;
            const label = detected.label
                || { line: 'Línea', rect: 'Rectángulo', circle: 'Círculo', polyline: 'Polilínea' }[detected.type]
                || detected.type;
            const icons = { line: '─', rect: '⬜', circle: '⭕', polyline: '' };
            const icon  = el.label
                ? (el.label.startsWith('Tri') ? '△' : el.label.startsWith('Cuad') ? '◇' : el.label.startsWith('Polí') ? '⬡' : '↗')
                : icons[detected.type] || '';
            showBadge(`${icon} ${el.label || label} detectado`);
        }
    }

    if (!el) {
        el = { type: 'freehand', points: wPoints };
    }

    commitMany(decomposeToLines(el));
    rawPoints = [];
    render();
}

// ─────────────────────────────────────────────────────────────
//  ERASER ACTIONS
// ─────────────────────────────────────────────────────────────
function tryErase(sx, sy) {
    const w = s2w(sx, sy);
    let didErase = false;
    for (let i = elements.length - 1; i >= 0; i--) {
        if (hitTest(elements[i], w.x, w.y)) {
            if (!eraseUndoSaved) {
                saveUndo();
                eraseUndoSaved = true;
            }
            elements.splice(i, 1);
            didErase = true;
        }
    }
    if (didErase) render();
}

// ─────────────────────────────────────────────────────────────
//  TEXT MODE
// ─────────────────────────────────────────────────────────────

// Medidas del texto en unidades mundo (measureText devuelve px = unidades mundo)
function textMetrics(el) {
    ctx.font = `${el.fontSize}px sans-serif`;
    return { w: ctx.measureText(el.text).width, h: el.fontSize * 1.15 };
}

// Transforma un punto mundo al espacio local (no rotado) del texto
function textLocalPoint(el, wx, wy) {
    const { w, h } = textMetrics(el);
    const cx = el.x + w / 2, cy = el.y + h / 2;
    const rot = -(el.rotation || 0);
    const dx = wx - cx, dy = wy - cy;
    return {
        x: dx * Math.cos(rot) - dy * Math.sin(rot),
        y: dx * Math.sin(rot) + dy * Math.cos(rot),
    };
}

// Devuelve qué parte del texto fue tocada: 'move' | 'resize' | 'rotate' | null
function hitTextHandle(el, wx, wy) {
    const { w, h } = textMetrics(el);
    const lp  = textLocalPoint(el, wx, wy);
    const pad = 6 / zoom;

    // Handle de rotación (círculo sobre el centro superior)
    const rotY = -h / 2 - 28 / zoom;
    if (Math.hypot(lp.x, lp.y - rotY) < 10 / zoom) return 'rotate';

    // Handle de escala (esquina inferior derecha)
    if (Math.abs(lp.x - w / 2) < 10 / zoom && Math.abs(lp.y - h / 2) < 10 / zoom) return 'resize';

    // Cuerpo del texto
    if (lp.x >= -w / 2 - pad && lp.x <= w / 2 + pad &&
        lp.y >= -h / 2 - pad && lp.y <= h / 2 + pad) return 'move';

    return null;
}

// Dibuja los handles de selección (llamado dentro del transform mundo)
function drawTextHandles(el) {
    const { w, h } = textMetrics(el);
    const cx = el.x + w / 2, cy = el.y + h / 2;

    ctx.save();
    ctx.translate(cx, cy);
    ctx.rotate(el.rotation || 0);

    // Caja punteada
    const pad = 5 / zoom;
    ctx.strokeStyle = '#4a90e2';
    ctx.lineWidth   = 1.2 / zoom;
    ctx.setLineDash([5 / zoom, 3 / zoom]);
    ctx.strokeRect(-w / 2 - pad, -h / 2 - pad, w + pad * 2, h + pad * 2);
    ctx.setLineDash([]);

    // Línea + círculo de rotación
    const rotY = -h / 2 - pad - 28 / zoom;
    ctx.lineWidth = 1.5 / zoom;
    ctx.beginPath();
    ctx.moveTo(0, -h / 2 - pad);
    ctx.lineTo(0, rotY + 6 / zoom);
    ctx.stroke();

    ctx.beginPath();
    ctx.arc(0, rotY, 7 / zoom, 0, Math.PI * 2);
    ctx.fillStyle = '#fff';
    ctx.fill();
    ctx.strokeStyle = '#4a90e2';
    ctx.stroke();

    // Cuadrado de escala (esquina inferior derecha)
    const hs = 8 / zoom;
    ctx.fillStyle = '#4a90e2';
    ctx.fillRect(w / 2 - hs / 2, h / 2 - hs / 2, hs, hs);

    ctx.restore();
}

// Muestra el panel de input flotante en la posición de pantalla (sx, sy)
function showTextInput(sx, sy) {
    const rect  = canvas.getBoundingClientRect();
    const panel = document.getElementById('textPanel');
    const input = document.getElementById('textPanelInput');
    panel.style.display = 'flex';
    panel.style.left = Math.max(8, rect.left + sx) + 'px';
    panel.style.top  = Math.max(8, rect.top  + sy - 54) + 'px';
    input.value = '';
    setTimeout(() => input.focus(), 40);
}

function commitText(raw) {
    const text = raw.trim();
    if (text && textPlaceW) {
        saveUndo();
        const el = { type: 'text', x: textPlaceW.x, y: textPlaceW.y,
                     text, fontSize: GRID * 1.2, rotation: 0 };
        elements.push(el);
        selText = el;
    }
    textPlaceW = null;
    document.getElementById('textPanel').style.display = 'none';
    render();
}

function resetTextMode() {
    textPlaceW   = null;
    selText      = null;
    txtForm      = null;
    txtFormStart = null;
    document.getElementById('textPanel').style.display = 'none';
    render();
}

// ── Interacción de texto ──────────────────────────────────────
function onTextDown(sx, sy) {
    const wp = s2w(sx, sy);

    // ¿Toca los handles del elemento seleccionado?
    if (selText) {
        const hit = hitTextHandle(selText, wp.x, wp.y);
        if (hit) { startTxtTransform(hit, wp.x, wp.y); return; }
    }

    // ¿Toca algún texto existente?
    for (let i = elements.length - 1; i >= 0; i--) {
        if (elements[i].type === 'text') {
            const hit = hitTextHandle(elements[i], wp.x, wp.y);
            if (hit) {
                selText = elements[i];
                startTxtTransform(hit, wp.x, wp.y);
                render(); return;
            }
        }
    }

    // Click en área vacía → nuevo texto
    selText    = null;
    textPlaceW = wp;
    showTextInput(sx, sy);
    render();
}

function startTxtTransform(form, wx, wy) {
    txtForm = form;
    const { w, h } = textMetrics(selText);
    saveUndo();
    txtFormStart = {
        wx, wy,
        snap: JSON.parse(JSON.stringify(selText)),
        cx: selText.x + w / 2,
        cy: selText.y + h / 2,
        angle0: Math.atan2(wy - (selText.y + h / 2), wx - (selText.x + w / 2)),
    };
}

function onTextMove(sx, sy) {
    if (!txtForm || !selText || !txtFormStart) return;
    const wp = s2w(sx, sy);
    const { wx: wx0, wy: wy0, snap, cx, cy, angle0 } = txtFormStart;

    if (txtForm === 'move') {
        selText.x = snap.x + (wp.x - wx0);
        selText.y = snap.y + (wp.y - wy0);
    } else if (txtForm === 'resize') {
        const d0 = Math.hypot(wx0 - cx, wy0 - cy);
        const d1 = Math.hypot(wp.x - cx, wp.y - cy);
        if (d0 > 1) selText.fontSize = Math.max(6, snap.fontSize * d1 / d0);
    } else if (txtForm === 'rotate') {
        const angle1 = Math.atan2(wp.y - cy, wp.x - cx);
        selText.rotation = (snap.rotation || 0) + (angle1 - angle0);
    }
    render();
}

function onTextUp() {
    txtForm      = null;
    txtFormStart = null;
}

// ─────────────────────────────────────────────────────────────
//  UNDO
// ─────────────────────────────────────────────────────────────
function saveUndo() {
    undoStack.push(JSON.parse(JSON.stringify(elements)));
    if (undoStack.length > 50) undoStack.shift();
}
function commit(el) {
    saveUndo();
    elements.push(el);
}
// Commit multiple elements as a single undo step
function commitMany(els) {
    saveUndo();
    elements.push(...els);
}
function undo() {
    if (!undoStack.length) return;
    elements.length = 0;
    elements.push(...undoStack.pop());
    render();
}

// ─────────────────────────────────────────────────────────────
//  ZOOM
// ─────────────────────────────────────────────────────────────
function applyZoom(cx, cy, factor) {
    const wx = (cx - panX) / zoom;
    const wy = (cy - panY) / zoom;
    zoom = Math.min(8, Math.max(0.1, zoom * factor));
    panX = cx - wx * zoom;
    panY = cy - wy * zoom;
    zoomLabel.textContent = Math.round(zoom * 100) + '%';
    render();
}

// ─────────────────────────────────────────────────────────────
//  MOUSE EVENTS
// ─────────────────────────────────────────────────────────────
canvas.addEventListener('mousedown', e => {
    const sx = e.offsetX, sy = e.offsetY;
    if (e.button === 1 || e.button === 2) {
        e.preventDefault();
        panning = true;
        panSX0 = sx; panSY0 = sy; panX0 = panX; panY0 = panY;
        canvas.classList.add('panning');
        return;
    }
    if (e.button !== 0) return;

    if (mode === 'draw') {
        startDraw(sx, sy);
    } else if (mode === 'eraser') {
        erasing = true;
        eraseUndoSaved = false;
        tryErase(sx, sy);
    } else if (mode === 'text') {
        onTextDown(sx, sy);
    }
});

canvas.addEventListener('mousemove', e => {
    const sx = e.offsetX, sy = e.offsetY;
    if (panning) {
        panX = panX0 + (sx - panSX0);
        panY = panY0 + (sy - panSY0);
        render();
        return;
    }
    if (mode === 'draw') continueDraw(sx, sy);
    if (mode === 'eraser' && erasing) tryErase(sx, sy);
    if (mode === 'text') onTextMove(sx, sy);
});

canvas.addEventListener('mouseup', e => {
    if (panning && (e.button === 1 || e.button === 2)) {
        panning = false;
        canvas.classList.remove('panning');
        return;
    }
    if (e.button === 0) {
        if (mode === 'draw') endDraw();
        if (mode === 'eraser') erasing = false;
        if (mode === 'text') onTextUp();
    }
});

canvas.addEventListener('mouseleave', () => {
    if (drawing) endDraw();
    if (erasing) erasing = false;
    if (mode === 'text') onTextUp();
    if (panning) { panning = false; canvas.classList.remove('panning'); }
    snapDot.style.display = 'none';
});

canvas.addEventListener('contextmenu', e => e.preventDefault());

canvas.addEventListener('wheel', e => {
    e.preventDefault();
    applyZoom(e.offsetX, e.offsetY, e.deltaY < 0 ? 1.1 : 1/1.1);
}, { passive: false });

// ─────────────────────────────────────────────────────────────
//  TOUCH EVENTS
// ─────────────────────────────────────────────────────────────
function touchOffset(touch) {
    const rect = canvas.getBoundingClientRect();
    return { sx: touch.clientX - rect.left, sy: touch.clientY - rect.top };
}

canvas.addEventListener('touchstart', e => {
    e.preventDefault();
    if (e.touches.length === 1) {
        const { sx, sy } = touchOffset(e.touches[0]);
        if (mode === 'draw') {
            startDraw(sx, sy);
        } else if (mode === 'eraser') {
            erasing = true;
            eraseUndoSaved = false;
            tryErase(sx, sy);
        } else if (mode === 'text') {
            onTextDown(sx, sy);
        }
    } else if (e.touches.length === 2) {
        // Cancel any active draw/erase
        if (drawing) { drawing = false; rawPoints = []; hideBadge(); snapDot.style.display = 'none'; }
        erasing = false;
        pinching = true;
        pinchDist0 = Math.hypot(
            e.touches[0].clientX - e.touches[1].clientX,
            e.touches[0].clientY - e.touches[1].clientY,
        );
        pinchZoom0 = zoom;
        pinchPanX0 = panX;
        pinchPanY0 = panY;
        const rect = canvas.getBoundingClientRect();
        pinchCX = (e.touches[0].clientX + e.touches[1].clientX) / 2 - rect.left;
        pinchCY = (e.touches[0].clientY + e.touches[1].clientY) / 2 - rect.top;
    }
}, { passive: false });

canvas.addEventListener('touchmove', e => {
    e.preventDefault();
    if (e.touches.length === 1) {
        const { sx, sy } = touchOffset(e.touches[0]);
        if (drawing) continueDraw(sx, sy);
        if (erasing && mode === 'eraser') tryErase(sx, sy);
        if (mode === 'text') onTextMove(sx, sy);
    } else if (e.touches.length === 2 && pinching) {
        const rect = canvas.getBoundingClientRect();
        const newDist = Math.hypot(
            e.touches[0].clientX - e.touches[1].clientX,
            e.touches[0].clientY - e.touches[1].clientY,
        );
        const cx = (e.touches[0].clientX + e.touches[1].clientX) / 2 - rect.left;
        const cy = (e.touches[0].clientY + e.touches[1].clientY) / 2 - rect.top;

        const newZoom = Math.min(8, Math.max(0.1, pinchZoom0 * newDist / pinchDist0));
        const wx = (pinchCX - pinchPanX0) / pinchZoom0;
        const wy = (pinchCY - pinchPanY0) / pinchZoom0;

        zoom = newZoom;
        panX = cx - wx * zoom;
        panY = cy - wy * zoom;

        zoomLabel.textContent = Math.round(zoom * 100) + '%';
        render();
    }
}, { passive: false });

canvas.addEventListener('touchend', e => {
    e.preventDefault();
    if (e.touches.length === 0) {
        if (drawing) endDraw();
        if (mode === 'text') onTextUp();
        erasing = false;
        pinching = false;
    } else if (e.touches.length === 1) {
        pinching = false;
        erasing = false;
        const { sx, sy } = touchOffset(e.touches[0]);
        if (drawing) continueDraw(sx, sy);
    }
}, { passive: false });

// ─────────────────────────────────────────────────────────────
//  MODE SWITCHER
// ─────────────────────────────────────────────────────────────
function setMode(m) {
    mode = m;
    document.getElementById('btnDraw').classList.toggle('active',   m === 'draw');
    document.getElementById('btnEraser').classList.toggle('active', m === 'eraser');
    document.getElementById('btnText').classList.toggle('active',   m === 'text');
    canvas.className = `mode-${m}`;
    if (m !== 'draw') { drawing = false; rawPoints = []; hideBadge(); snapDot.style.display = 'none'; }
    if (m !== 'text') resetTextMode();
}

// ─────────────────────────────────────────────────────────────
//  TOOLBAR BINDINGS
// ─────────────────────────────────────────────────────────────
document.getElementById('btnDraw').addEventListener('click',   () => setMode('draw'));
document.getElementById('btnEraser').addEventListener('click', () => setMode(mode === 'eraser' ? 'draw' : 'eraser'));
document.getElementById('btnText').addEventListener('click',   () => setMode(mode === 'text'   ? 'draw' : 'text'));

document.getElementById('btnTextOk').addEventListener('click', () =>
    commitText(document.getElementById('textPanelInput').value));
document.getElementById('btnTextCancel').addEventListener('click', resetTextMode);
document.getElementById('textPanelInput').addEventListener('keydown', e => {
    if (e.key === 'Enter')  { e.preventDefault(); commitText(e.target.value); }
    if (e.key === 'Escape') resetTextMode();
});

document.getElementById('btnUndo').addEventListener('click', undo);

document.getElementById('btnClear').addEventListener('click', () => {
    if (!elements.length) return;
    saveUndo();
    elements.length = 0;
    render();
    showBadge('Pizarra limpia');
});

document.getElementById('btnZoomIn').addEventListener('click', () =>
    applyZoom(canvas.width/2, canvas.height/2, 1.25));
document.getElementById('btnZoomOut').addEventListener('click', () =>
    applyZoom(canvas.width/2, canvas.height/2, 1/1.25));
document.getElementById('btnZoomReset').addEventListener('click', () => {
    zoom = ZOOM_DEFAULT; panX = 0; panY = 0;
    zoomLabel.textContent = Math.round(ZOOM_DEFAULT * 100) + '%';
    render();
});

document.getElementById('chkSnap').addEventListener('change', e => {
    snapEnabled = e.target.checked;
});

// ─────────────────────────────────────────────────────────────
//  INIT
// ─────────────────────────────────────────────────────────────
setMode('draw');
zoomLabel.textContent = Math.round(ZOOM_DEFAULT * 100) + '%';
resize();

})();
</script>
</body>
</html>
