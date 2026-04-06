<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizarra</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #1a1a2e; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }

        /* ── Toolbar ── */
        #toolbar {
            display: flex; gap: 8px; align-items: center; flex-wrap: wrap;
            padding: 7px 14px; background: #16213e;
            width: 100%; border-bottom: 1px solid #0f3460; flex-shrink: 0;
        }
        .sep { width: 1px; height: 26px; background: #0f3460; margin: 0 2px; }
        #toolbar label { color: #e0e0e0; font-family: sans-serif; font-size: 12px; }
        #colorPicker { width: 32px; height: 32px; border: none; cursor: pointer; border-radius: 50%; padding: 2px; background: none; }
        #brushSize { width: 75px; cursor: pointer; }
        button {
            padding: 4px 10px; border: none; border-radius: 5px;
            cursor: pointer; font-size: 12px; font-family: sans-serif;
            background: #0f3460; color: white; transition: filter 0.1s;
        }
        button:hover { filter: brightness(1.3); }
        button.active { outline: 2px solid #60b4ff; outline-offset: 1px; }
        #btnEraser { background: #c0392b; }
        #btnClear  { background: #6d1f1f; }
        #btnUndo   { background: #533483; }
        #btnSelect { background: #1a6e4a; }
        #btnGrid     { background: #2a5a3a; }
        #btnSnap     { background: #2a3a6a; }
        #btnMeasures { background: #3a2a6a; }

        /* ── Dimension input overlay ── */
        #dimOverlay {
            position: fixed; display: none; align-items: center; gap: 5px;
            background: rgba(13,17,23,0.95); border: 1px solid #1E88E5;
            border-radius: 6px; padding: 5px 9px;
            font-family: 'Consolas', monospace; font-size: 13px;
            z-index: 500; pointer-events: all;
            box-shadow: 0 2px 12px rgba(30,136,229,0.3);
        }
        #dimOverlay.show { display: flex; }
        #dimVal {
            background: none; border: none; outline: none;
            color: #79c0ff; font-family: inherit; font-size: 13px;
            width: 100px; caret-color: #58a6ff;
        }
        #dimVal::placeholder { color: #484f58; }
        .dim-live { color: #3fb950; font-size: 11px; min-width: 60px; }
        .dim-tip  { color: #484f58; font-size: 10px; }

        /* ── Canvas ── */
        #canvas { cursor: crosshair; background: white; flex: 1; width: 100%; display: block; min-height: 0; }

        /* ── Edit panel ── */
        #editPanel {
            position: fixed; display: none; align-items: center; gap: 8px;
            background: #16213e; border: 1px solid #0f3460; border-radius: 8px;
            padding: 6px 11px; font-family: sans-serif; font-size: 12px; color: #e0e0e0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.5); z-index: 200;
        }
        #editPanel.show { display: flex; }
        #editPanel input[type=color] { width:26px; height:26px; border:none; cursor:pointer; border-radius:4px; padding:1px; background:none; }
        #editPanel input[type=range] { width:65px; cursor:pointer; }
        #btnDelSel { background:#c0392b; color:white; padding:3px 8px; }

        /* ── Command bar ── */
        #cmdBar {
            flex-shrink: 0; background: #0d1117;
            border-top: 1px solid #0f3460;
            display: flex; align-items: center;
            padding: 0 12px; height: 30px; gap: 6px;
            font-family: 'Consolas', 'Courier New', monospace; font-size: 13px;
        }
        #cmdPrompt { color: #3fb950; user-select: none; font-weight: bold; }
        #cmdInput {
            background: none; border: none; outline: none;
            color: #c9d1d9; font-family: inherit; font-size: 13px;
            flex: 1; caret-color: #58a6ff;
        }
        #cmdInput::placeholder { color: #484f58; }
        #cmdStatus { color: #484f58; font-size: 11px; white-space: nowrap; }
        #cmdStatus span { margin-left: 10px; }
        #cmdStatus .on  { color: #3fb950; }
        #cmdStatus .off { color: #6e7681; }
        #cmdStatus .tool { color: #79c0ff; }

        /* ── Suggestions ── */
        #cmdSuggestions {
            position: fixed; bottom: 32px; left: 0;
            background: #161b22; border: 1px solid #30363d;
            border-radius: 6px 6px 0 0; font-family: monospace; font-size: 12px;
            display: none; z-index: 300; max-width: 320px; overflow: hidden;
        }
        #cmdSuggestions.show { display: block; }
        .sug-item {
            padding: 4px 12px; cursor: pointer; color: #c9d1d9;
            display: flex; gap: 12px; align-items: center;
        }
        .sug-item:hover, .sug-item.sel { background: #1f2937; }
        .sug-cmd  { color: #79c0ff; min-width: 70px; }
        .sug-desc { color: #6e7681; font-size: 11px; }
    </style>
</head>
<body>

<div id="toolbar">
    <label>Color:</label>
    <input type="color" id="colorPicker" value="#000000">
    <label>Tamaño:</label>
    <input type="range" id="brushSize" min="1" max="50" value="1">
    <span id="sizeLabel" style="color:#aaa;font-family:sans-serif;font-size:12px;">1px</span>

    <div class="sep"></div>
    <button id="btnSelect"   title="Seleccionar (S)">⬚ Selec.</button>
    <button id="btnPen"      class="active" title="Lápiz (P)">✏️ Lápiz</button>
    <button id="btnEraser"   title="Borrador (E)">🧹 Borrador</button>

    <div class="sep"></div>
    <button id="btnLine"     title="Línea">╱ Línea</button>
    <button id="btnRect"     title="Rectángulo">▭ Rect.</button>
    <button id="btnCircle"   title="Círculo">○ Círculo</button>
    <button id="btnTriangle" title="Triángulo">△ Triáng.</button>
    <button id="btnFill"     title="Relleno">⬛ Relleno</button>

    <div class="sep"></div>
    <button id="btnGrid" class="active" title="Cuadrícula (G)">⊞ Grid</button>
    <button id="btnSnap" class="active" title="Snap (X)">⌖ Snap</button>
    <button id="btnMeasures" title="Mostrar/ocultar medidas (M)">📐 Medidas</button>

    <div class="sep"></div>
    <button id="btnUndo">↩ Deshacer</button>
    <button id="btnClear">🗑 Limpiar</button>
</div>

<!-- Dimension input while drawing -->
<div id="dimOverlay">
    <input id="dimVal" type="text" placeholder="ej: 10 ó 10x5" autocomplete="off" spellcheck="false">
    <span class="dim-live" id="dimLive">0m</span>
    <span class="dim-tip">↵ aplicar · Esc cancelar</span>
</div>

<div id="editPanel">
    <span>Color:</span><input type="color" id="epColor">
    <span>Grosor:</span><input type="range" id="epSize" min="1" max="50">
    <button id="btnDelSel">🗑 Eliminar</button>
</div>

<canvas id="canvas"></canvas>

<div id="cmdSuggestions"></div>

<div id="cmdBar">
    <span id="cmdPrompt">&gt;</span>
    <input id="cmdInput" type="text" placeholder="Escribe un comando... (ayuda: 'help')" autocomplete="off" spellcheck="false">
    <div id="cmdStatus">
        <span class="tool" id="stTool">Lápiz</span>
        <span id="stGrid" class="on">Grid: ON</span>
        <span id="stSnap" class="on">Snap: ON</span>
    </div>
</div>

<script>
// ── DOM ───────────────────────────────────────────────────────────────────────
const canvas      = document.getElementById('canvas');
const ctx         = canvas.getContext('2d');
const colorPicker = document.getElementById('colorPicker');
const brushSize   = document.getElementById('brushSize');
const sizeLabel   = document.getElementById('sizeLabel');
const editPanel   = document.getElementById('editPanel');
const epColor     = document.getElementById('epColor');
const epSize      = document.getElementById('epSize');
const cmdInput    = document.getElementById('cmdInput');
const cmdSugs     = document.getElementById('cmdSuggestions');
const stTool      = document.getElementById('stTool');
const stGrid      = document.getElementById('stGrid');
const stSnap      = document.getElementById('stSnap');

// ── State ─────────────────────────────────────────────────────────────────────
let tool        = 'pen';
let filled      = false;
let drawing     = false;
let objects     = [];
let currentObj  = null;
let selectedObj = null;
let history     = [];

let dragMode    = null;
let dragStart   = null;
let origCopy    = null;

let gridOn       = true;
let snapOn       = true;
let GRID         = 20;
let snapLines    = [];   // [{axis:'x'|'y', value}]
let showMeasures = false;
let PIXELS_PER_M = 20;   // 1 grid cell = 1 metre by default
let drawStartPos = null; // first click position for active shape

const HANDLE_SZ = 8;
const SNAP_TOL  = 10;

// ── Dim overlay refs ──────────────────────────────────────────────────────────
const dimOverlay = document.getElementById('dimOverlay');
const dimVal     = document.getElementById('dimVal');
const dimLive    = document.getElementById('dimLive');

// ── Resize canvas ─────────────────────────────────────────────────────────────
function resizeCanvas() {
    const tb  = document.getElementById('toolbar').offsetHeight;
    const cb  = document.getElementById('cmdBar').offsetHeight;
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight - tb - cb;
    render();
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

// ── History ───────────────────────────────────────────────────────────────────
function saveHistory() {
    history.push(JSON.parse(JSON.stringify(objects)));
    if (history.length > 40) history.shift();
}

// ── Grid ──────────────────────────────────────────────────────────────────────
function drawGrid() {
    if (!gridOn) return;
    ctx.save();
    ctx.strokeStyle = 'rgba(140,140,200,0.18)';
    ctx.lineWidth = 0.5;
    ctx.beginPath();
    for (let x = 0; x <= canvas.width;  x += GRID) { ctx.moveTo(x,0); ctx.lineTo(x, canvas.height); }
    for (let y = 0; y <= canvas.height; y += GRID) { ctx.moveTo(0,y); ctx.lineTo(canvas.width, y); }
    ctx.stroke();
    ctx.strokeStyle = 'rgba(140,140,200,0.35)';
    ctx.lineWidth = 0.8;
    ctx.beginPath();
    for (let x = 0; x <= canvas.width;  x += GRID*5) { ctx.moveTo(x,0); ctx.lineTo(x, canvas.height); }
    for (let y = 0; y <= canvas.height; y += GRID*5) { ctx.moveTo(0,y); ctx.lineTo(canvas.width, y); }
    ctx.stroke();
    ctx.restore();
}

// ── Snap guides ───────────────────────────────────────────────────────────────
function drawSnapGuides() {
    if (!snapLines.length) return;
    ctx.save();
    ctx.strokeStyle = 'rgba(224,64,251,0.75)';
    ctx.lineWidth = 1.2;
    ctx.setLineDash([5, 4]);
    for (const l of snapLines) {
        ctx.beginPath();
        if (l.axis === 'x') { ctx.moveTo(l.value, 0); ctx.lineTo(l.value, canvas.height); }
        else                 { ctx.moveTo(0, l.value); ctx.lineTo(canvas.width, l.value); }
        ctx.stroke();
    }
    ctx.setLineDash([]);
    ctx.restore();
}

// ── Snap logic ────────────────────────────────────────────────────────────────
function getRawBBox(obj) {
    if (obj.type === 'pen') {
        const xs=obj.pts.map(p=>p.x), ys=obj.pts.map(p=>p.y);
        return {x:Math.min(...xs),y:Math.min(...ys),w:Math.max(...xs)-Math.min(...xs),h:Math.max(...ys)-Math.min(...ys)};
    }
    return {x:Math.min(obj.x1,obj.x2),y:Math.min(obj.y1,obj.y2),w:Math.abs(obj.x2-obj.x1),h:Math.abs(obj.y2-obj.y1)};
}

function objSnapVals(obj) {
    const b = getRawBBox(obj);
    return { xs:[b.x, b.x+b.w/2, b.x+b.w], ys:[b.y, b.y+b.h/2, b.y+b.h] };
}

// Snap a single (x,y) point. excludeObj won't be used as element target.
function snapPt(x, y, excludeObj=null) {
    if (!snapOn) return {x, y, lines:[]};
    const lines = [];
    let bx=x, by=y, dxBest=Infinity, dyBest=Infinity;

    for (const obj of objects) {
        if (obj === excludeObj || obj === currentObj) continue;
        const sv = objSnapVals(obj);
        for (const vx of sv.xs) { const d=Math.abs(x-vx); if(d<dxBest){dxBest=d;bx=vx;} }
        for (const vy of sv.ys) { const d=Math.abs(y-vy); if(d<dyBest){dyBest=d;by=vy;} }
    }

    // Grid snap (fallback or if element snap too far)
    if (dxBest > SNAP_TOL) { bx = Math.round(x/GRID)*GRID; dxBest = Math.abs(x-bx); }
    if (dyBest > SNAP_TOL) { by = Math.round(y/GRID)*GRID; dyBest = Math.abs(y-by); }

    if (dxBest <= SNAP_TOL) lines.push({axis:'x', value:bx});
    if (dyBest <= SNAP_TOL) lines.push({axis:'y', value:by});

    return {x:bx, y:by, lines};
}

// Snap when moving an object: snaps bbox edges/center
function snapForMove(origObj, dx, dy) {
    if (!snapOn) return {dx, dy, lines:[]};
    const b = getRawBBox(origObj);
    const candXs = [b.x+dx, b.x+b.w/2+dx, b.x+b.w+dx];
    const candYs = [b.y+dy, b.y+b.h/2+dy, b.y+b.h+dy];

    let bestDX=0, bestDY=0, dxBest=Infinity, dyBest=Infinity;
    const lines = [];

    for (const obj of objects) {
        if (obj === selectedObj) continue;
        const sv = objSnapVals(obj);
        for (const cx of candXs) { for (const vx of sv.xs) { const d=Math.abs(cx-vx); if(d<dxBest){dxBest=d;bestDX=vx-cx;} } }
        for (const cy of candYs) { for (const vy of sv.ys) { const d=Math.abs(cy-vy); if(d<dyBest){dyBest=d;bestDY=vy-cy;} } }
    }

    // Grid fallback
    if (dxBest > SNAP_TOL) {
        for (const cx of candXs) { const snp=Math.round(cx/GRID)*GRID; const d=Math.abs(cx-snp); if(d<dxBest){dxBest=d;bestDX=snp-cx;} }
    }
    if (dyBest > SNAP_TOL) {
        for (const cy of candYs) { const snp=Math.round(cy/GRID)*GRID; const d=Math.abs(cy-snp); if(d<dyBest){dyBest=d;bestDY=snp-cy;} }
    }

    if (dxBest <= SNAP_TOL) lines.push({axis:'x', value: candXs[0]+bestDX});
    if (dyBest <= SNAP_TOL) lines.push({axis:'y', value: candYs[0]+bestDY});

    return {dx: dx+bestDX, dy: dy+bestDY, lines};
}

// ── Render ────────────────────────────────────────────────────────────────────
function render() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawGrid();
    for (const obj of objects) drawObject(obj);
    if (currentObj) drawObject(currentObj);
    drawSnapGuides();
    if (showMeasures) { for (const obj of objects) drawObjectMeasures(obj); if (currentObj) drawObjectMeasures(currentObj); }
    if (selectedObj) drawSelectionOverlay(selectedObj);
}

// ── Measurements ──────────────────────────────────────────────────────────────
function pxToM(px) {
    const m = Math.abs(px) / PIXELS_PER_M;
    const s = +m.toFixed(2);
    return (Number.isInteger(s) ? s : s) + 'm';
}

function drawMeasureLabel(x, y, text, angle=0) {
    ctx.save();
    ctx.translate(x, y);
    if (angle) ctx.rotate(angle);
    ctx.font = 'bold 10px sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    const tw = ctx.measureText(text).width + 8, th = 15;
    ctx.fillStyle = 'rgba(10,20,50,0.82)';
    ctx.strokeStyle = 'rgba(30,136,229,0.6)';
    ctx.lineWidth = 0.8;
    ctx.fillRect(-tw/2, -th/2, tw, th);
    ctx.strokeRect(-tw/2, -th/2, tw, th);
    ctx.fillStyle = '#90CAF9';
    ctx.fillText(text, 0, 0);
    ctx.restore();
}

function drawObjectMeasures(obj) {
    if (obj.type === 'pen') return;

    if (obj.type === 'line') {
        const len = Math.hypot(obj.x2-obj.x1, obj.y2-obj.y1);
        const angle = Math.atan2(obj.y2-obj.y1, obj.x2-obj.x1);
        const mx=(obj.x1+obj.x2)/2, my=(obj.y1+obj.y2)/2;
        const nx=-Math.sin(angle)*14, ny=Math.cos(angle)*14;
        drawMeasureLabel(mx+nx, my+ny, pxToM(len), angle);
        return;
    }

    const [x,y,w,h] = normRect(obj);

    if (obj.type === 'rect') {
        drawMeasureLabel(x+w/2, y-14,   pxToM(w));               // top
        drawMeasureLabel(x+w/2, y+h+14, pxToM(w));               // bottom
        drawMeasureLabel(x-16,  y+h/2,  pxToM(h), -Math.PI/2);   // left
        drawMeasureLabel(x+w+16,y+h/2,  pxToM(h),  Math.PI/2);   // right
        return;
    }

    if (obj.type === 'circle') {
        drawMeasureLabel(x+w/2, y-14,    'Ø'+pxToM(w));           // horizontal diameter
        drawMeasureLabel(x+w+16,y+h/2,   'Ø'+pxToM(h), Math.PI/2); // vertical diameter
        return;
    }

    if (obj.type === 'triangle') {
        const p1x=x+w/2, p1y=y, p2x=x, p2y=y+h, p3x=x+w, p3y=y+h;
        const s1=Math.hypot(p2x-p1x,p2y-p1y);
        const s2=Math.hypot(p3x-p2x,p3y-p2y);
        const s3=Math.hypot(p1x-p3x,p1y-p3y);
        const a1=Math.atan2(p2y-p1y,p2x-p1x);
        const a2=Math.atan2(p3y-p2y,p3x-p2x);
        const a3=Math.atan2(p1y-p3y,p1x-p3x);
        // Offset perpendicular-outward from each side
        drawMeasureLabel((p1x+p2x)/2 - Math.sin(a1)*16, (p1y+p2y)/2 + Math.cos(a1)*16, pxToM(s1), a1);
        drawMeasureLabel((p2x+p3x)/2, p2y+14, pxToM(s2), a2);
        drawMeasureLabel((p3x+p1x)/2 + Math.sin(a3)*16, (p3y+p1y)/2 - Math.cos(a3)*16, pxToM(s3), a3);
    }
}

// ── Dimension input overlay ───────────────────────────────────────────────────
function parseDim(str) {
    const parts = str.toLowerCase().split(/[x×*,\s]+/);
    const w = parseFloat(parts[0]);
    const h = parts.length > 1 ? parseFloat(parts[1]) : NaN;
    return {w: isNaN(w)?null:w, h: isNaN(h)?null:h};
}

function getLiveDim(obj) {
    if (!obj || obj.type==='pen') return '';
    if (obj.type==='line') return pxToM(Math.hypot(obj.x2-obj.x1, obj.y2-obj.y1));
    const w=Math.abs(obj.x2-obj.x1), h=Math.abs(obj.y2-obj.y1);
    return pxToM(w) + ' × ' + pxToM(h);
}

function positionDimOverlay(canvasX, canvasY) {
    const cr = canvas.getBoundingClientRect();
    let ox = cr.left + canvasX + 18;
    let oy = cr.top  + canvasY - 38;
    ox = Math.min(ox, window.innerWidth  - dimOverlay.offsetWidth  - 8);
    oy = Math.max(oy, cr.top + 4);
    dimOverlay.style.left = ox + 'px';
    dimOverlay.style.top  = oy + 'px';
}

function showDimOverlay(canvasX, canvasY) {
    dimOverlay.classList.add('show');
    positionDimOverlay(canvasX, canvasY);
    dimLive.textContent = getLiveDim(currentObj);
}

function hideDimOverlay() {
    dimOverlay.classList.remove('show');
    dimVal.value = '';
}

// Apply typed dimensions to currentObj (preview while typing, finalize on Enter)
function applyTypedDim(obj, start, wM, hM) {
    if (!obj || !start || obj.type==='pen') return;
    if (obj.type==='line') {
        if (wM===null) return;
        const dx=obj.x2-start.x, dy=obj.y2-start.y;
        const angle = Math.atan2(dy, dx) || 0;
        obj.x2 = start.x + Math.cos(angle) * wM * PIXELS_PER_M;
        obj.y2 = start.y + Math.sin(angle) * wM * PIXELS_PER_M;
        return;
    }
    const wSign = obj.x2 >= start.x ? 1 : -1;
    const hSign = obj.y2 >= start.y ? 1 : -1;
    if (wM !== null) obj.x2 = start.x + wM * PIXELS_PER_M * wSign;
    const effH = (hM !== null) ? hM : wM; // square default
    if (effH !== null) obj.y2 = start.y + effH * PIXELS_PER_M * hSign;
}

dimVal.addEventListener('input', () => {
    if (!currentObj || !drawStartPos) return;
    const {w, h} = parseDim(dimVal.value);
    if (w === null) return;
    // Clone original start coords for preview
    const preview = JSON.parse(JSON.stringify(currentObj));
    applyTypedDim(currentObj, drawStartPos, w, h);
    dimLive.textContent = getLiveDim(currentObj);
    render();
});

dimVal.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
        e.preventDefault();
        if (currentObj && drawStartPos) {
            const {w, h} = parseDim(dimVal.value.trim());
            if (w !== null) applyTypedDim(currentObj, drawStartPos, w, h);
            // Snap final point
            const s = snapPt(currentObj.x2, currentObj.y2);
            currentObj.x2 = s.x; currentObj.y2 = s.y;
            objects.push(currentObj); currentObj = null;
            drawing = false; drawStartPos = null;
            hideDimOverlay(); snapLines = []; render();
        }
    }
    if (e.key === 'Escape') {
        e.preventDefault();
        // Cancel dim input but keep drawing
        dimVal.value = ''; dimVal.blur();
    }
});

// Press any digit while drawing a shape → auto-focus dim input
document.addEventListener('keydown', e => {
    if (drawing && currentObj && currentObj.type!=='pen' &&
        document.activeElement !== dimVal &&
        document.activeElement !== cmdInput &&
        /^[0-9.]$/.test(e.key)) {
        dimVal.focus();
        // allow key to land in dimVal
    }
});

function normRect(obj) {
    const x=Math.min(obj.x1,obj.x2), y=Math.min(obj.y1,obj.y2);
    return [x, y, Math.abs(obj.x2-obj.x1), Math.abs(obj.y2-obj.y1)];
}

function drawObject(obj) {
    ctx.save();
    ctx.lineWidth   = obj.lineWidth;
    ctx.strokeStyle = obj.color;
    ctx.fillStyle   = obj.color;
    ctx.lineCap = 'round'; ctx.lineJoin = 'round';

    if (obj.type === 'pen') {
        if (obj.pts.length < 2) { ctx.restore(); return; }
        ctx.beginPath();
        ctx.moveTo(obj.pts[0].x, obj.pts[0].y);
        for (let i=1; i<obj.pts.length; i++) ctx.lineTo(obj.pts[i].x, obj.pts[i].y);
        ctx.stroke();
    } else if (obj.type === 'line') {
        ctx.beginPath(); ctx.moveTo(obj.x1,obj.y1); ctx.lineTo(obj.x2,obj.y2); ctx.stroke();
    } else if (obj.type === 'rect') {
        const [x,y,w,h]=normRect(obj);
        obj.filled ? ctx.fillRect(x,y,w,h) : ctx.strokeRect(x,y,w,h);
    } else if (obj.type === 'circle') {
        const [x,y,w,h]=normRect(obj);
        ctx.beginPath();
        ctx.ellipse(x+w/2, y+h/2, Math.max(1,w/2), Math.max(1,h/2), 0, 0, Math.PI*2);
        obj.filled ? ctx.fill() : ctx.stroke();
    } else if (obj.type === 'triangle') {
        const [x,y,w,h]=normRect(obj);
        ctx.beginPath();
        ctx.moveTo(x+w/2,y); ctx.lineTo(x,y+h); ctx.lineTo(x+w,y+h);
        ctx.closePath();
        obj.filled ? ctx.fill() : ctx.stroke();
    }
    ctx.restore();
}

// ── Bounding box (with padding) ───────────────────────────────────────────────
function getBBox(obj) {
    const pad = Math.max(4, obj.lineWidth/2);
    const b = getRawBBox(obj);
    return {x:b.x-pad, y:b.y-pad, w:b.w+pad*2, h:b.h+pad*2};
}

// ── Control points per shape ──────────────────────────────────────────────────
// kind: 'vertex' (diamond, blue) | 'midpoint' (circle, hollow) | 'center' (cross, green)
function getControlPoints(obj) {
    if (obj.type === 'pen') {
        const b = getRawBBox(obj);
        return [{id:'c', x:b.x+b.w/2, y:b.y+b.h/2, kind:'center'}];
    }
    if (obj.type === 'line') {
        return [
            {id:'p1',  x:obj.x1,               y:obj.y1,               kind:'vertex'},
            {id:'p2',  x:obj.x2,               y:obj.y2,               kind:'vertex'},
            {id:'c',   x:(obj.x1+obj.x2)/2,    y:(obj.y1+obj.y2)/2,    kind:'center'},
        ];
    }
    const lx=Math.min(obj.x1,obj.x2), rx=Math.max(obj.x1,obj.x2);
    const ty=Math.min(obj.y1,obj.y2), by=Math.max(obj.y1,obj.y2);
    const cx=(lx+rx)/2, cy=(ty+by)/2;

    if (obj.type === 'rect') {
        return [
            // 4 vertices (corners)
            {id:'tl', x:lx, y:ty, kind:'vertex'},
            {id:'tr', x:rx, y:ty, kind:'vertex'},
            {id:'br', x:rx, y:by, kind:'vertex'},
            {id:'bl', x:lx, y:by, kind:'vertex'},
            // 4 perimeter midpoints (one per side)
            {id:'mt', x:cx, y:ty, kind:'midpoint'},
            {id:'mr', x:rx, y:cy, kind:'midpoint'},
            {id:'mb', x:cx, y:by, kind:'midpoint'},
            {id:'ml', x:lx, y:cy, kind:'midpoint'},
            // center
            {id:'c',  x:cx, y:cy, kind:'center'},
        ];
    }
    if (obj.type === 'circle') {
        return [
            // 4 cardinal points on the ellipse perimeter
            {id:'et', x:cx, y:ty, kind:'vertex'},
            {id:'er', x:rx, y:cy, kind:'vertex'},
            {id:'eb', x:cx, y:by, kind:'vertex'},
            {id:'el', x:lx, y:cy, kind:'vertex'},
            // diagonal corners of bbox (for proportional resize)
            {id:'tl', x:lx, y:ty, kind:'midpoint'},
            {id:'tr', x:rx, y:ty, kind:'midpoint'},
            {id:'br', x:rx, y:by, kind:'midpoint'},
            {id:'bl', x:lx, y:by, kind:'midpoint'},
            // center
            {id:'c',  x:cx, y:cy, kind:'center'},
        ];
    }
    if (obj.type === 'triangle') {
        // apex=(cx,ty), bl=(lx,by), br=(rx,by)
        return [
            // 3 vertices
            {id:'apex', x:cx,           y:ty,           kind:'vertex'},
            {id:'vtl',  x:lx,           y:by,           kind:'vertex'},
            {id:'vtr',  x:rx,           y:by,           kind:'vertex'},
            // 3 edge midpoints
            {id:'ml',   x:(cx+lx)/2,    y:(ty+by)/2,    kind:'midpoint'},
            {id:'mb',   x:cx,           y:by,           kind:'midpoint'},
            {id:'mr',   x:(cx+rx)/2,    y:(ty+by)/2,    kind:'midpoint'},
            // centroid
            {id:'c',    x:cx,           y:(ty+by*2)/3,  kind:'center'},
        ];
    }
    return [];
}

function drawControlPoint(cp) {
    ctx.save();
    if (cp.kind === 'vertex') {
        // Filled diamond (rotated square)
        ctx.fillStyle   = '#1E88E5';
        ctx.strokeStyle = 'white';
        ctx.lineWidth   = 1.5;
        ctx.save();
        ctx.translate(cp.x, cp.y);
        ctx.rotate(Math.PI / 4);
        ctx.fillRect(-4.5, -4.5, 9, 9);
        ctx.strokeRect(-4.5, -4.5, 9, 9);
        ctx.restore();
    } else if (cp.kind === 'midpoint') {
        // Hollow circle
        ctx.fillStyle   = 'white';
        ctx.strokeStyle = '#1E88E5';
        ctx.lineWidth   = 1.5;
        ctx.beginPath();
        ctx.arc(cp.x, cp.y, 4.5, 0, Math.PI * 2);
        ctx.fill(); ctx.stroke();
    } else { // center
        // Green circle with crosshair
        ctx.fillStyle   = '#43A047';
        ctx.strokeStyle = 'white';
        ctx.lineWidth   = 1.5;
        ctx.beginPath();
        ctx.arc(cp.x, cp.y, 5.5, 0, Math.PI * 2);
        ctx.fill(); ctx.stroke();
        ctx.strokeStyle = 'rgba(255,255,255,0.85)';
        ctx.lineWidth   = 1;
        ctx.beginPath();
        ctx.moveTo(cp.x - 8, cp.y); ctx.lineTo(cp.x + 8, cp.y);
        ctx.moveTo(cp.x, cp.y - 8); ctx.lineTo(cp.x, cp.y + 8);
        ctx.stroke();
    }
    ctx.restore();
}

// ── Selection overlay ─────────────────────────────────────────────────────────
function drawSelectionOverlay(obj) {
    ctx.save();
    // Light dashed bounding box (secondary visual)
    const b = getBBox(obj);
    ctx.strokeStyle = 'rgba(30,136,229,0.3)';
    ctx.lineWidth   = 1;
    ctx.setLineDash([4, 3]);
    ctx.strokeRect(b.x - 4, b.y - 4, b.w + 8, b.h + 8);
    ctx.setLineDash([]);
    ctx.restore();

    // Control points on the actual shape geometry
    for (const cp of getControlPoints(obj)) drawControlPoint(cp);
}

// ── Hit testing ───────────────────────────────────────────────────────────────
function distSeg(px,py,ax,ay,bx,by) {
    const dx=bx-ax,dy=by-ay,l2=dx*dx+dy*dy;
    if(!l2) return Math.hypot(px-ax,py-ay);
    const t=Math.max(0,Math.min(1,((px-ax)*dx+(py-ay)*dy)/l2));
    return Math.hypot(px-(ax+t*dx),py-(ay+t*dy));
}

function hitTest(obj,px,py) {
    const T=Math.max(8,obj.lineWidth/2+5);
    if (obj.type==='pen') {
        for(let i=1;i<obj.pts.length;i++)
            if(distSeg(px,py,obj.pts[i-1].x,obj.pts[i-1].y,obj.pts[i].x,obj.pts[i].y)<T) return true;
        return false;
    }
    if (obj.type==='line') return distSeg(px,py,obj.x1,obj.y1,obj.x2,obj.y2)<T;
    const [x,y,w,h]=normRect(obj);
    if (obj.type==='rect') {
        if(obj.filled) return px>=x&&px<=x+w&&py>=y&&py<=y+h;
        return px>=x-T&&px<=x+w+T&&py>=y-T&&py<=y+h+T&&(px<=x+T||px>=x+w-T||py<=y+T||py>=y+h-T);
    }
    if (obj.type==='circle') {
        const cx=x+w/2,cy=y+h/2,rx=Math.max(1,w/2),ry=Math.max(1,h/2);
        const d=((px-cx)/rx)**2+((py-cy)/ry)**2;
        if(obj.filled) return d<=1;
        return Math.abs(Math.sqrt(d)-1)*Math.min(rx,ry)<T;
    }
    if (obj.type==='triangle') {
        const p1x=x+w/2,p1y=y,p2x=x,p2y=y+h,p3x=x+w,p3y=y+h;
        const sg=(ax,ay,bx,by)=>(px-bx)*(ay-by)-(ax-bx)*(py-by);
        const d1=sg(p1x,p1y,p2x,p2y),d2=sg(p2x,p2y,p3x,p3y),d3=sg(p3x,p3y,p1x,p1y);
        const ins=!((d1<0||d2<0||d3<0)&&(d1>0||d2>0||d3>0));
        if(obj.filled) return ins;
        return ins||distSeg(px,py,p1x,p1y,p2x,p2y)<T||distSeg(px,py,p2x,p2y,p3x,p3y)<T||distSeg(px,py,p3x,p3y,p1x,p1y)<T;
    }
    return false;
}

function hitControlPoint(obj, px, py) {
    for (const cp of getControlPoints(obj))
        if (Math.hypot(px - cp.x, py - cp.y) <= 10) return cp.id;
    return null;
}

function cpCursor(cpId) {
    const map = {
        c:'move', p1:'crosshair', p2:'crosshair',
        tl:'nw-resize', tr:'ne-resize', br:'se-resize', bl:'sw-resize',
        mt:'n-resize',  mr:'e-resize',  mb:'s-resize',  ml:'w-resize',
        et:'n-resize',  er:'e-resize',  eb:'s-resize',  el:'w-resize',
        apex:'n-resize', vtl:'sw-resize', vtr:'se-resize',
        ml:'nw-resize',  mr:'ne-resize',
    };
    return map[cpId] || 'crosshair';
}

// ── Move / Edit via control points ────────────────────────────────────────────
function moveObj(obj, orig, dx, dy) {
    if (orig.type==='pen') { obj.pts=orig.pts.map(p=>({x:p.x+dx,y:p.y+dy})); return; }
    obj.x1=orig.x1+dx; obj.y1=orig.y1+dy; obj.x2=orig.x2+dx; obj.y2=orig.y2+dy;
}

// Apply a control-point drag. sx,sy = snapped cursor position.
function applyControlPointDrag(obj, orig, cpId, sx, sy) {
    if (cpId === 'c') return; // center is handled via moveObj

    if (orig.type === 'line') {
        if (cpId==='p1') { obj.x1=sx; obj.y1=sy; }
        if (cpId==='p2') { obj.x2=sx; obj.y2=sy; }
        return;
    }

    let lx=Math.min(orig.x1,orig.x2), rx=Math.max(orig.x1,orig.x2);
    let ty=Math.min(orig.y1,orig.y2), by=Math.max(orig.y1,orig.y2);

    if (orig.type === 'rect') {
        if(cpId==='tl'){lx=sx;ty=sy;} else if(cpId==='tr'){rx=sx;ty=sy;}
        else if(cpId==='br'){rx=sx;by=sy;} else if(cpId==='bl'){lx=sx;by=sy;}
        else if(cpId==='mt'){ty=sy;} else if(cpId==='mr'){rx=sx;}
        else if(cpId==='mb'){by=sy;} else if(cpId==='ml'){lx=sx;}
    } else if (orig.type === 'circle') {
        if(cpId==='et'){ty=sy;} else if(cpId==='er'){rx=sx;}
        else if(cpId==='eb'){by=sy;} else if(cpId==='el'){lx=sx;}
        else if(cpId==='tl'){lx=sx;ty=sy;} else if(cpId==='tr'){rx=sx;ty=sy;}
        else if(cpId==='br'){rx=sx;by=sy;} else if(cpId==='bl'){lx=sx;by=sy;}
    } else if (orig.type === 'triangle') {
        // apex: only moves the top edge (apex stays centered)
        if(cpId==='apex'){ty=sy;}
        // bottom vertices move their respective corner
        else if(cpId==='vtl'){lx=sx;by=sy;} else if(cpId==='vtr'){rx=sx;by=sy;}
        // edge midpoints: each moves one edge
        else if(cpId==='mb'){by=sy;}
        else if(cpId==='ml'){lx=sx;}
        else if(cpId==='mr'){rx=sx;}
    }

    // Enforce minimum size of 1px
    obj.x1=lx; obj.y1=ty;
    obj.x2=Math.max(rx, lx+1); obj.y2=Math.max(by, ty+1);
}

// ── Pointer helpers ───────────────────────────────────────────────────────────
function getPos(e) {
    const r=canvas.getBoundingClientRect(), src=e.touches?e.touches[0]:e;
    return {x:src.clientX-r.left, y:src.clientY-r.top};
}

// ── Canvas events ─────────────────────────────────────────────────────────────
canvas.addEventListener('mousedown',  onStart);
canvas.addEventListener('mousemove',  onMove);
canvas.addEventListener('mouseup',    onStop);
canvas.addEventListener('mouseleave', onLeave);   // only cancels pen drag
canvas.addEventListener('touchstart', onStart, {passive:false});
canvas.addEventListener('touchmove',  onMove,  {passive:false});
canvas.addEventListener('touchend',   onStop);

function onStart(e) {
    e.preventDefault();
    const raw = getPos(e);

    if (tool === 'select') {
        if (selectedObj) {
            const cpId = hitControlPoint(selectedObj, raw.x, raw.y);
            if (cpId) {
                saveHistory();
                dragMode = cpId; dragStart = {...raw};
                origCopy = JSON.parse(JSON.stringify(selectedObj));
                return;
            }
        }
        let found = null;
        for (let i=objects.length-1; i>=0; i--)
            if (hitTest(objects[i], raw.x, raw.y)) { found=objects[i]; break; }
        selectedObj = found;
        if (found) {
            saveHistory();
            dragMode='c'; dragStart={...raw};
            origCopy=JSON.parse(JSON.stringify(found));
            showEditPanel();
        } else { hideEditPanel(); }
        render(); return;
    }

    const isShape = tool !== 'pen' && tool !== 'eraser';

    // ── Shape: two-click mode ─────────────────────────────────────────────────
    if (isShape) {
        if (drawing) {
            // Second click → finalize (unless dim input active, Enter handles that)
            if (dimVal.value.trim()) return;
            const snapped = snapPt(raw.x, raw.y);
            currentObj.x2 = snapped.x; currentObj.y2 = snapped.y;
            snapLines = snapped.lines || [];
            objects.push(currentObj); currentObj = null;
            drawing = false; drawStartPos = null;
            hideDimOverlay(); snapLines = [];
            render();
        } else {
            // First click → set origin
            saveHistory();
            const snapped = snapPt(raw.x, raw.y);
            const color = colorPicker.value;
            currentObj = {type:tool, x1:snapped.x, y1:snapped.y, x2:snapped.x, y2:snapped.y, color, lineWidth:+brushSize.value, filled};
            drawStartPos = {x: snapped.x, y: snapped.y};
            drawing = true;
            snapLines = snapped.lines || [];
            showDimOverlay(raw.x, raw.y);
        }
        return;
    }

    // ── Pen / Eraser: drag mode ───────────────────────────────────────────────
    saveHistory();
    drawing = true;
    const color = tool==='eraser' ? '#ffffff' : colorPicker.value;
    currentObj = {type:'pen', pts:[{x:raw.x,y:raw.y}], color, lineWidth:+brushSize.value};
    snapLines = [];
}

function onMove(e) {
    const raw = getPos(e);

    if (tool === 'select') {
        if (dragMode && selectedObj) {
            e.preventDefault();
            const dx = raw.x - dragStart.x, dy = raw.y - dragStart.y;
            if (dragMode === 'c') {
                // Center handle → move entire object with snap
                const s = snapForMove(origCopy, dx, dy);
                snapLines = s.lines;
                moveObj(selectedObj, origCopy, s.dx, s.dy);
            } else {
                // Shape control point → snap cursor and reshape
                const s = snapPt(raw.x, raw.y, selectedObj);
                snapLines = s.lines;
                applyControlPointDrag(selectedObj, origCopy, dragMode, s.x, s.y);
            }
            render(); updateEditPanelPos();
        } else {
            snapLines = [];
            if (selectedObj) {
                const cpId = hitControlPoint(selectedObj, raw.x, raw.y);
                if (cpId) { canvas.style.cursor = cpCursor(cpId); return; }
            }
            for (let i=objects.length-1; i>=0; i--)
                if (hitTest(objects[i], raw.x, raw.y)) { canvas.style.cursor='move'; return; }
            canvas.style.cursor = 'default';
        }
        return;
    }

    if (!drawing) return;
    if (tool==='pen'||tool==='eraser') {
        e.preventDefault();
        currentObj.pts.push({x:raw.x, y:raw.y});
        snapLines = [];
        render();
        return;
    }
    // Shape preview: update x2/y2 from mouse unless user typed a dim
    if (!dimVal.value.trim()) {
        const s = snapPt(raw.x, raw.y);
        currentObj.x2 = s.x; currentObj.y2 = s.y;
        snapLines = s.lines;
    }
    showDimOverlay(raw.x, raw.y);
    render();
}

function onStop() {
    if (tool === 'select') {
        snapLines=[]; dragMode=null; dragStart=null; origCopy=null;
        render(); return;
    }
    // Shapes finalize on second click (onStart), not on mouseup
    if (tool !== 'pen' && tool !== 'eraser') return;
    // Pen / eraser: finalize on mouseup
    if (!drawing) return;
    drawing=false; snapLines=[];
    if (currentObj) { objects.push(currentObj); currentObj=null; }
    render();
}

// Leave canvas: only interrupt pen/eraser drag, never shapes
function onLeave() {
    if ((tool==='pen' || tool==='eraser') && drawing) onStop();
}

function cursorForHandle(hid) { // legacy alias
    return cpCursor(hid) || ({nw:'nw-resize',n:'n-resize',ne:'ne-resize',e:'e-resize',
            se:'se-resize',s:'s-resize',sw:'sw-resize',w:'w-resize'}[hid]||'move');
}

// ── Edit panel ────────────────────────────────────────────────────────────────
function showEditPanel() {
    if (!selectedObj) return;
    epColor.value=/^#[0-9a-f]{6}$/i.test(selectedObj.color)?selectedObj.color:'#000000';
    epSize.value=selectedObj.lineWidth;
    editPanel.classList.add('show');
    updateEditPanelPos();
}
function hideEditPanel() { editPanel.classList.remove('show'); }
function updateEditPanelPos() {
    if (!selectedObj||!editPanel.classList.contains('show')) return;
    const b=getBBox(selectedObj), cr=canvas.getBoundingClientRect();
    let px=cr.left+b.x+b.w/2-editPanel.offsetWidth/2;
    let py=cr.top+b.y-editPanel.offsetHeight-12;
    if(py<cr.top+4) py=cr.top+b.y+b.h+12;
    px=Math.max(8,Math.min(window.innerWidth-editPanel.offsetWidth-8,px));
    editPanel.style.left=px+'px'; editPanel.style.top=Math.max(8,py)+'px';
}
epColor.addEventListener('input',()=>{ if(selectedObj){selectedObj.color=epColor.value;render();} });
epSize.addEventListener('input',()=>{ if(selectedObj){selectedObj.lineWidth=+epSize.value;render();} });
document.getElementById('btnDelSel').addEventListener('click',()=>{
    if(!selectedObj) return;
    saveHistory(); objects=objects.filter(o=>o!==selectedObj);
    selectedObj=null; hideEditPanel(); render();
});

// ── Toolbar ───────────────────────────────────────────────────────────────────
brushSize.addEventListener('input',()=>sizeLabel.textContent=brushSize.value+'px');

const toolBtns = {
    select:document.getElementById('btnSelect'), pen:document.getElementById('btnPen'),
    eraser:document.getElementById('btnEraser'), line:document.getElementById('btnLine'),
    rect:document.getElementById('btnRect'),     circle:document.getElementById('btnCircle'),
    triangle:document.getElementById('btnTriangle'),
};
const toolNames = {select:'Seleccionar',pen:'Lápiz',eraser:'Borrador',line:'Línea',rect:'Rectángulo',circle:'Círculo',triangle:'Triángulo'};

function setTool(name) {
    tool=name;
    if(name!=='select'){selectedObj=null;hideEditPanel();}
    canvas.style.cursor={select:'default',eraser:'cell'}[name]||'crosshair';
    Object.values(toolBtns).forEach(b=>b.classList.remove('active'));
    if(toolBtns[name]) toolBtns[name].classList.add('active');
    stTool.textContent = toolNames[name]||name;
    render();
}
Object.entries(toolBtns).forEach(([n,b])=>b.addEventListener('click',()=>setTool(n)));

document.getElementById('btnFill').addEventListener('click',function(){
    filled=!filled; this.classList.toggle('active',filled);
});

function toggleGrid() {
    gridOn=!gridOn;
    document.getElementById('btnGrid').classList.toggle('active',gridOn);
    stGrid.className=gridOn?'on':'off';
    stGrid.textContent='Grid: '+(gridOn?'ON':'OFF');
    render();
}
function toggleSnap() {
    snapOn=!snapOn;
    document.getElementById('btnSnap').classList.toggle('active',snapOn);
    stSnap.className=snapOn?'on':'off';
    stSnap.textContent='Snap: '+(snapOn?'ON':'OFF');
}

function toggleMeasures() {
    showMeasures = !showMeasures;
    document.getElementById('btnMeasures').classList.toggle('active', showMeasures);
    render();
}

document.getElementById('btnGrid').addEventListener('click', toggleGrid);
document.getElementById('btnSnap').addEventListener('click', toggleSnap);
document.getElementById('btnMeasures').addEventListener('click', toggleMeasures);

document.getElementById('btnClear').addEventListener('click',()=>{
    saveHistory(); objects=[]; selectedObj=null; hideEditPanel(); render();
});
document.getElementById('btnUndo').addEventListener('click',()=>doUndo());

function doUndo() {
    if(!history.length) return;
    objects=history.pop();
    if(selectedObj&&!objects.includes(selectedObj)){selectedObj=null;hideEditPanel();}
    render();
}

// ── Keyboard shortcuts ────────────────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if (document.activeElement===cmdInput) return;
    if (e.key==='Delete'&&selectedObj) {
        saveHistory(); objects=objects.filter(o=>o!==selectedObj);
        selectedObj=null; hideEditPanel(); render();
    }
    if (e.key==='Escape') {
        if (drawing && currentObj) {
            // Cancel shape in progress
            drawing=false; currentObj=null; drawStartPos=null;
            hideDimOverlay(); snapLines=[]; render();
        } else {
            selectedObj=null; hideEditPanel(); render();
        }
    }
    if (!e.ctrlKey&&!e.metaKey) {
        if(e.key==='s') setTool('select');
        if(e.key==='p') setTool('pen');
        if(e.key==='e') setTool('eraser');
        if(e.key==='r') setTool('rect');
        if(e.key==='c') setTool('circle');
        if(e.key==='l') setTool('line');
        if(e.key==='t') setTool('triangle');
        if(e.key==='g') toggleGrid();
        if(e.key==='x') toggleSnap();
        if(e.key==='m') toggleMeasures();
    }
    if((e.ctrlKey||e.metaKey)&&e.key==='z'){e.preventDefault();doUndo();}
});

// ── Command bar ───────────────────────────────────────────────────────────────
const COMMANDS = [
    {keys:['rect','rec','rectangulo','r'],  action:()=>setTool('rect'),     desc:'Herramienta rectángulo'},
    {keys:['circle','cir','circulo','c'],   action:()=>setTool('circle'),   desc:'Herramienta círculo'},
    {keys:['triangle','tri','triangulo','t'],action:()=>setTool('triangle'),desc:'Herramienta triángulo'},
    {keys:['line','lin','linea','l'],        action:()=>setTool('line'),     desc:'Herramienta línea'},
    {keys:['pen','lapiz','lap','p'],         action:()=>setTool('pen'),      desc:'Herramienta lápiz'},
    {keys:['eraser','era','borrador','bor','e'],action:()=>setTool('eraser'),desc:'Herramienta borrador'},
    {keys:['select','sel','selec','s'],      action:()=>setTool('select'),   desc:'Herramienta selección'},
    {keys:['fill','relleno','rell','f'],     action:()=>{ filled=!filled; document.getElementById('btnFill').classList.toggle('active',filled); }, desc:'Activar/desactivar relleno'},
    {keys:['grid','cuadricula','g'],         action:(args)=>{ if(args&&!isNaN(+args)){GRID=Math.max(5,Math.min(100,+args));PIXELS_PER_M=GRID;render();} else toggleGrid(); }, desc:'Toggle cuadrícula / grid <tamaño>'},
    {keys:['measures','medidas','med','m'],  action:()=>toggleMeasures(), desc:'Mostrar/ocultar medidas'},
    {keys:['scale','escala'],               action:(args)=>{ if(args&&!isNaN(+args)){PIXELS_PER_M=Math.max(1,+args);render();} }, desc:'Definir escala en px/m: scale <n>'},
    {keys:['snap','x'],                      action:()=>toggleSnap(),        desc:'Activar/desactivar snap'},
    {keys:['clear','limpiar','limp'],        action:()=>{ saveHistory();objects=[];selectedObj=null;hideEditPanel();render(); }, desc:'Limpiar pizarra'},
    {keys:['undo','deshacer','z'],           action:()=>doUndo(),            desc:'Deshacer'},
    {keys:['help','ayuda','?'],              action:()=>showHelp(),          desc:'Mostrar comandos disponibles'},
];

function showHelp() {
    const list = COMMANDS.map(c=>`${c.keys[0]} — ${c.desc}`).join('\n');
    alert('Comandos disponibles:\n\n'+list);
}

function execCommand(raw) {
    const parts = raw.trim().toLowerCase().split(/\s+/);
    const cmd   = parts[0];
    const args  = parts[1] || '';
    for (const c of COMMANDS) {
        if (c.keys.includes(cmd)) { c.action(args); return true; }
    }
    return false;
}

// Suggestions
let sugIdx = -1;

function updateSuggestions(val) {
    const q = val.trim().toLowerCase();
    if (!q) { cmdSugs.classList.remove('show'); return; }

    const matches = [];
    for (const c of COMMANDS) {
        for (const k of c.keys) {
            if (k.startsWith(q) && k!==q) { matches.push({key:k, desc:c.desc}); break; }
        }
        if (matches.length >= 6) break;
    }

    if (!matches.length) { cmdSugs.classList.remove('show'); return; }

    cmdSugs.innerHTML = matches.map((m,i)=>
        `<div class="sug-item" data-cmd="${m.key}" data-idx="${i}"><span class="sug-cmd">${m.key}</span><span class="sug-desc">${m.desc}</span></div>`
    ).join('');

    // Position
    const cr = cmdInput.getBoundingClientRect();
    cmdSugs.style.left = cr.left+'px';
    cmdSugs.style.bottom = (window.innerHeight - cr.top)+'px';
    cmdSugs.classList.add('show');
    sugIdx = -1;
}

cmdSugs.addEventListener('click', e => {
    const item = e.target.closest('.sug-item');
    if (!item) return;
    cmdInput.value = item.dataset.cmd;
    cmdSugs.classList.remove('show');
    cmdInput.focus();
});

cmdInput.addEventListener('input', () => updateSuggestions(cmdInput.value));

cmdInput.addEventListener('keydown', e => {
    const items = cmdSugs.querySelectorAll('.sug-item');

    if (e.key==='ArrowDown'&&items.length) {
        e.preventDefault();
        sugIdx = Math.min(sugIdx+1, items.length-1);
        items.forEach((el,i)=>el.classList.toggle('sel',i===sugIdx));
        return;
    }
    if (e.key==='ArrowUp'&&items.length) {
        e.preventDefault();
        sugIdx = Math.max(sugIdx-1, -1);
        items.forEach((el,i)=>el.classList.toggle('sel',i===sugIdx));
        return;
    }
    if (e.key==='Tab'&&cmdSugs.classList.contains('show')) {
        e.preventDefault();
        const target = sugIdx>=0 ? items[sugIdx] : items[0];
        if (target) { cmdInput.value=target.dataset.cmd; cmdSugs.classList.remove('show'); }
        return;
    }
    if (e.key==='Escape') {
        cmdSugs.classList.remove('show'); cmdInput.blur(); return;
    }
    if (e.key==='Enter') {
        e.preventDefault();
        // If a suggestion is selected, use it
        if (sugIdx>=0 && items[sugIdx]) cmdInput.value=items[sugIdx].dataset.cmd;
        cmdSugs.classList.remove('show');
        const ok = execCommand(cmdInput.value);
        if (!ok && cmdInput.value.trim()) {
            cmdInput.style.color='#f85149';
            setTimeout(()=>cmdInput.style.color='',600);
        }
        cmdInput.value='';
        sugIdx=-1;
    }
});

// Focus command bar with ":"
document.addEventListener('keydown', e => {
    if (e.key===':' && document.activeElement!==cmdInput) {
        e.preventDefault();
        cmdInput.focus();
        cmdInput.value=':'.replace(':','');
    }
});
</script>
</body>
</html>
