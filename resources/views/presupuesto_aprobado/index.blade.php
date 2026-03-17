<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuestos Aprobados</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #f0f3f7;
            --bg2:      #e4e9f0;
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
            --green-s:  #e5f6f0;
            --green-b:  #a8dcc9;
            --slate:    #4e6070;
            --slate-s:  #edf1f4;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .content-wrapper { background: var(--bg) !important; }

        /* ══════════════════════════════
           PAGE HEADER
        ══════════════════════════════ */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .ph-crumb {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 0.5rem;
        }

        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }

        .ph-title {
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.4px;
            line-height: 1.1;
            word-break: break-word;
        }

        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }

        .ph-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* ── Buttons ── */
        .btn {
            height: 38px;
            padding: 0 1rem;
            border-radius: 0.55rem;
            display: inline-flex;
            align-items: center;
            gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem;
            font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface);
            color: var(--text2);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.14s;
            white-space: nowrap;
        }

        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }

        /* ── Search ── */
        .search-wrap { position: relative; }

        .search-wrap i {
            position: absolute;
            left: 0.78rem; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 0.72rem;
            pointer-events: none;
        }

        .search-bar {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.83rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            padding: 0.5rem 0.9rem 0.5rem 2.1rem;
            color: var(--text);
            width: 220px;
            outline: none;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
            height: 38px;
        }

        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus {
            border-color: var(--accent);
            width: 270px;
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }

        /* ══════════════════════════════
           CARDS GRID
        ══════════════════════════════ */
        #cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1rem;
        }

        /* ── Add card ── */
        .add-card {
            background: var(--green-s);
            border: 1.5px dashed var(--green-b);
            border-radius: 0.85rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 2.5rem 1rem;
            text-decoration: none;
            color: var(--green);
            text-align: center;
            transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s, background 0.18s;
            min-height: 200px;
        }

        .add-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(30,145,102,0.12);
            border-color: var(--green);
            background: #d4f0e6;
            color: var(--green);
        }

        .add-card-icon {
            width: 52px; height: 52px;
            border-radius: 0.65rem;
            background: rgba(30,145,102,0.12);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            color: var(--green);
            transition: transform 0.18s;
        }

        .add-card:hover .add-card-icon { transform: scale(1.08); }

        .add-card-label {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--green);
        }

        .add-card-sub {
            font-size: 0.75rem;
            color: var(--green);
            opacity: 0.7;
        }

        /* ── Budget card ── */
        .budget-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: var(--text);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
            animation: cardIn 0.25s ease both;
        }

        .budget-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.09);
            border-color: var(--border2);
            color: var(--text);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        /* PDF preview */
        .card-preview {
            height: 220px;
            background: var(--bg2);
            overflow: hidden;
            position: relative;
            flex-shrink: 0;
            cursor: pointer;
        }

        .card-preview iframe {
            width: 100%; height: 100%;
            border: none;
            pointer-events: none;
        }

        .expand-btn {
            position: absolute;
            top: 8px; right: 8px;
            width: 30px; height: 30px;
            border-radius: 0.4rem;
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text2);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem;
            cursor: pointer;
            z-index: 2;
            transition: background 0.12s, color 0.12s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        .expand-btn:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

        /* Card body */
        .card-body {
            padding: 0.95rem 1.1rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            flex: 1;
        }

        .card-name {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
            word-break: break-word;
        }

        .card-tipo {
            font-size: 0.75rem;
            color: var(--muted);
            font-weight: 500;
        }

        /* Card footer */
        .card-footer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 1.1rem;
            background: var(--surface2);
            border-top: 1px solid var(--border);
        }

        .card-monto {
            font-family: 'DM Mono', monospace;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text);
        }

        .card-fecha {
            font-size: 0.72rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .card-fecha i { font-size: 0.6rem; }

        /* ── Empty / no results ── */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 5rem 2rem;
            color: var(--muted);
        }

        .empty-state i { font-size: 2rem; display: block; margin-bottom: 0.75rem; opacity: 0.35; }
        .empty-state p { font-size: 0.88rem; }

        .no-results {
            display: none;
            grid-column: 1 / -1;
            text-align: center;
            color: var(--muted);
            padding: 4rem 2rem;
            font-size: 0.85rem;
        }

        .no-results i { display: block; font-size: 1.5rem; margin-bottom: 0.5rem; opacity: 0.3; }

        /* ══════════════════════════════
           PDF MODAL
        ══════════════════════════════ */
        .pdf-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,24,40,0.55);
            z-index: 1050;
            align-items: center;
            justify-content: center;
        }

        .pdf-modal-backdrop.open { display: flex; }

        .pdf-modal {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            width: 90vw;
            max-width: 960px;
            height: 85vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }

        .pdf-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.85rem 1.2rem;
            border-bottom: 1px solid var(--border);
            background: var(--bg2);
        }

        .pdf-modal-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pdf-modal-close {
            width: 30px; height: 30px;
            border-radius: 0.4rem;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text2);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            cursor: pointer;
            transition: background 0.12s;
        }

        .pdf-modal-close:hover { background: var(--surface2); color: var(--text); }

        .pdf-modal-body {
            flex: 1;
            overflow: hidden;
        }

        .pdf-modal-body iframe {
            width: 100%; height: 100%;
            border: none;
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
                            <i class="fas fa-hard-hat"></i>
                            <a href="{{ route('obras.index') }}">Obras</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('obras.show', $obra) }}">{{ $obra->nombre ?? '-' }}</a>
                            <i class="fas fa-chevron-right"></i>
                            Presupuestos
                        </div>
                        <h1 class="ph-title">Presupuestos — <em>{{ $obra->nombre ?? '-' }}</em></h1>
                        <p class="ph-sub">Presupuestos aprobados asociados a la obra</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar presupuesto…" autocomplete="off">
                        </div>
                        <a href="{{ route('obras.show', $obra) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @if(session('success'))
                <div class="alert alert-success mb-3" style="border-radius:0.55rem; font-size:0.85rem;">
                    {{ session('success') }}
                </div>
                @endif

                <div id="cards-grid">

                    {{-- Add card --}}
                    <a href="{{ route('presupuesto_aprobado.create', $obra) }}" class="add-card">
                        <div class="add-card-icon"><i class="fas fa-plus"></i></div>
                        <span class="add-card-label">Agregar presupuesto</span>
                        <span class="add-card-sub">Nuevo registro</span>
                    </a>

                    {{-- Budget cards --}}
                    @foreach($presupuestos->reverse() as $presupuesto)
                    <a href="{{ route('presupuesto_aprobado.edit', $presupuesto->id) }}"
                       class="budget-card"
                       style="animation-delay:{{ $loop->index * 0.04 }}s"
                       data-search="{{ strtolower($presupuesto->clave . ' ' . ($presupuesto->obra->nombre ?? '') . ' ' . (\Illuminate\Support\Arr::get(config('constantes.tipo_trabajo'), $presupuesto->tipo_trabajo, ''))) }}">

                        <div class="card-preview"
                             onclick="event.preventDefault(); event.stopPropagation(); abrirModal('{{ Storage::url('presupuestos/'.$presupuesto->presupuesto) }}', '{{ $presupuesto->clave }}')">
                            <iframe
                                src="{{ Storage::url('presupuestos/'.$presupuesto->presupuesto) }}"
                                loading="lazy">
                            </iframe>
                            <div class="expand-btn" title="Ver en pantalla completa">
                                <i class="fas fa-expand"></i>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="card-name">{{ $presupuesto->clave }}</div>
                            <div class="card-tipo">{{ config('constantes.tipo_trabajo')[$presupuesto->tipo_trabajo] ?? 'Desconocido' }}</div>
                        </div>

                        <div class="card-footer-row">
                            <span class="card-monto">{{ number_format($presupuesto->monto_total, 0, '', '.') }}</span>
                            <span class="card-fecha">
                                <i class="far fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($presupuesto->fecha_carga)->format('d/m/Y') }}
                            </span>
                        </div>

                    </a>
                    @endforeach

                    <div class="no-results" id="no-results">
                        <i class="fas fa-search"></i>
                        Sin resultados para tu búsqueda.
                    </div>

                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- PDF Modal --}}
<div class="pdf-modal-backdrop" id="pdf-modal-backdrop">
    <div class="pdf-modal">
        <div class="pdf-modal-header">
            <span class="pdf-modal-title">
                <i class="fas fa-file-pdf"></i>
                <span id="pdf-modal-title-text">Presupuesto</span>
            </span>
            <button class="pdf-modal-close" onclick="cerrarModal()" title="Cerrar">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="pdf-modal-body">
            <iframe id="pdf-modal-iframe" src=""></iframe>
        </div>
    </div>
</div>

<script>
// PDF modal
function abrirModal(src, titulo) {
    document.getElementById('pdf-modal-iframe').src = src;
    document.getElementById('pdf-modal-title-text').textContent = titulo || 'Presupuesto';
    document.getElementById('pdf-modal-backdrop').classList.add('open');
}

function cerrarModal() {
    document.getElementById('pdf-modal-backdrop').classList.remove('open');
    document.getElementById('pdf-modal-iframe').src = '';
}

document.getElementById('pdf-modal-backdrop').addEventListener('click', function (e) {
    if (e.target === this) cerrarModal();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') cerrarModal();
});

// Search
document.addEventListener('DOMContentLoaded', function () {
    const input  = document.getElementById('search');
    const cards  = document.querySelectorAll('#cards-grid .budget-card');
    const noRes  = document.getElementById('no-results');

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let vis = 0;

        cards.forEach(card => {
            const text = card.dataset.search || '';
            const show = text.includes(q);
            card.style.display = show ? '' : 'none';
            if (show) vis++;
        });

        noRes.style.display = (!vis && cards.length && q) ? 'block' : 'none';
    });
});
</script>
</body>
</html>