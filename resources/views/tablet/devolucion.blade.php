<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Devolución</title>
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
            --red:      #d94040;
            --red-s:    #fdeaea;
            --red-b:    #f5bcbc;
            --slate:    #4e6070;
            --slate-s:  #edf1f4;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .content-wrapper { background: var(--bg) !important; }

        /* PAGE HEADER */
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
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem;
        }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: 0.78rem; top: 50%;
            transform: translateY(-50%); color: var(--muted); font-size: 0.72rem; pointer-events: none;
        }
        .search-bar {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.83rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            padding: 0.5rem 0.9rem 0.5rem 2.1rem;
            color: var(--text); width: 230px; outline: none;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
            height: 38px;
        }
        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus { border-color: var(--accent); width: 280px; box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        .btn {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border); background: var(--surface); color: var(--text2);
            text-decoration: none; cursor: pointer; transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        /* STATS */
        .stats-row { display: flex; gap: 0.7rem; margin-bottom: 1.75rem; flex-wrap: wrap; }
        .stat-card {
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.6rem; padding: 0.85rem 1.1rem;
            display: flex; align-items: center; gap: 0.7rem; min-width: 130px;
        }
        .stat-icon {
            width: 32px; height: 32px; border-radius: 0.4rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; flex-shrink: 0;
        }
        .stat-icon.red { background: var(--red-s); color: var(--red); }
        .stat-val { font-size: 1.3rem; font-weight: 700; color: var(--text); letter-spacing: -0.5px; line-height: 1; }
        .stat-lbl { font-size: 0.7rem; color: var(--muted); font-weight: 500; margin-top: 0.1rem; }

        /* CARDS GRID */
        #devol-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1rem;
        }

        .tablet-card {
            background: var(--surface);
            border: 1.5px solid var(--red-b);
            border-radius: 0.85rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            animation: cardIn 0.25s ease both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        .card-icon-header {
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid var(--border);
            background: var(--red-s);
            flex-shrink: 0;
        }
        .card-icon-header i { font-size: 2.3rem; color: var(--red); }

        .card-body {
            padding: 0.9rem 1.1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }

        .card-name {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
            word-break: break-word;
        }

        .detail-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
        }
        .detail-row i {
            color: var(--muted);
            font-size: 0.7rem;
            width: 12px;
            text-align: center;
            flex-shrink: 0;
        }
        .detail-label {
            color: var(--muted);
            font-weight: 600;
            font-size: 0.72rem;
            flex-shrink: 0;
            min-width: 62px;
        }
        .detail-value { color: var(--text2); word-break: break-word; }

        .btn-devolver {
            margin-top: 0.3rem;
            width: 100%;
            justify-content: center;
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }
        .btn-devolver:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }

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

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(20,26,36,0.45);
            align-items: center;
            justify-content: center;
            z-index: 1050;
            padding: 1rem;
        }
        .modal-overlay.open { display: flex; }

        .modal-box {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.9rem;
            padding: 1.75rem;
            max-width: 380px;
            width: 100%;
            text-align: center;
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
            animation: modalIn 0.16s ease both;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: translateY(8px) scale(0.98); }
            to   { opacity: 1; transform: none; }
        }

        .modal-icon {
            width: 48px; height: 48px; border-radius: 50%;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; margin: 0 auto 0.9rem;
        }
        .modal-title { font-size: 1.05rem; font-weight: 700; color: var(--text); margin-bottom: 0.5rem; }
        .modal-text { font-size: 0.83rem; color: var(--muted); line-height: 1.5; margin-bottom: 1.4rem; }
        .modal-actions { display: flex; align-items: center; justify-content: center; gap: 0.6rem; }
        .modal-actions .btn { flex: 1; justify-content: center; }
        .modal-actions .btn-devolver { margin-top: 0; height: 38px; }
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
                            <a href="{{ route('tabletas.index') }}">Tablets</a>
                            <i class="fas fa-chevron-right"></i>
                            Devolución
                        </div>
                        <h1 class="ph-title">Registrar <em>Devolución</em></h1>
                        <p class="ph-sub">Tablets actualmente en uso, pendientes de devolver</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar tableta…" autocomplete="off">
                        </div>
                        <a href="{{ route('tabletas.index') }}" class="btn">
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

                @php
                    $enUso = $tabletas->filter(function($t) use ($tabletausos) {
                        $u = $tabletausos->where('tableta_id', $t->id)->sortByDesc('id')->first();
                        return $u && $u->aprobado == 1 && !$u->fecha_devolucion;
                    });
                @endphp

                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon red"><i class="fas fa-user-clock"></i></div>
                        <div>
                            <div class="stat-val">{{ $enUso->count() }}</div>
                            <div class="stat-lbl">En uso</div>
                        </div>
                    </div>
                </div>

                <div id="devol-grid">

                    @if($enUso->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <p>No hay tablets pendientes de devolución.</p>
                    </div>
                    @else

                    @foreach($enUso->values() as $tableta)
                    @php
                        $ultimoUso  = $tabletausos->where('tableta_id', $tableta->id)->sortByDesc('id')->first();
                        $usuario    = $ultimoUso->usuario_id ? App\Models\Usuarios::find($ultimoUso->usuario_id) : null;
                        $nombreUsu  = $usuario ? ($usuario->nombre_completo ?: $usuario->nombre) : 'Usuario desconocido';
                        $searchData = strtolower($tableta->clave . ' ' . $tableta->nombre . ' ' . $nombreUsu);
                    @endphp
                    <div class="tablet-card" data-search="{{ $searchData }}" style="animation-delay:{{ $loop->index * 0.04 }}s">
                        <div class="card-icon-header">
                            <i class="fas fa-tablet-alt"></i>
                        </div>
                        <div class="card-body">
                            <div class="card-name">{{ $tableta->clave }} — {{ $tableta->nombre }}</div>

                            <div class="detail-row">
                                <i class="fas fa-user"></i>
                                <span class="detail-label">Retirada por</span>
                                <span class="detail-value">{{ $nombreUsu }}</span>
                            </div>

                            @if($ultimoUso->fecha_retiro)
                            <div class="detail-row">
                                <i class="fas fa-calendar"></i>
                                <span class="detail-label">Fecha retiro</span>
                                <span class="detail-value">{{ \Carbon\Carbon::parse($ultimoUso->fecha_retiro)->format('d/m/Y') }}</span>
                            </div>
                            @endif

                            <form action="{{ route('tabletas.devolucion.registrar', $ultimoUso->id) }}" method="POST" class="form-devolver">
                                @csrf
                                <button type="submit" class="btn btn-devolver">
                                    <i class="fas fa-right-to-bracket"></i> Devolver
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach

                    @endif

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

{{-- Modal confirmación devolver --}}
<div class="modal-overlay" id="modal-devolver">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-right-to-bracket"></i></div>
        <h2 class="modal-title">Registrar devolución</h2>
        <p class="modal-text">¿Confirmás que esta tableta fue devuelta? Quedará pendiente de aprobación y seguirá sin estar disponible hasta ser confirmada.</p>
        <div class="modal-actions">
            <button type="button" class="btn" id="modal-cancelar">Cancelar</button>
            <button type="button" class="btn btn-devolver" id="modal-confirmar">
                <i class="fas fa-check"></i> Sí, devolver
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search');
    const cards = document.querySelectorAll('#devol-grid .tablet-card');
    const noRes = document.getElementById('no-results');

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let vis = 0;
        cards.forEach(card => {
            const show = (card.dataset.search || '').includes(q);
            card.style.display = show ? '' : 'none';
            if (show) vis++;
        });
        noRes.style.display = (!vis && cards.length && q) ? 'block' : 'none';
    });

    const modal        = document.getElementById('modal-devolver');
    const btnCancelar  = document.getElementById('modal-cancelar');
    const btnConfirmar = document.getElementById('modal-confirmar');
    let formPendiente  = null;

    document.querySelectorAll('.form-devolver').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            formPendiente = form;
            modal.classList.add('open');
        });
    });

    function cerrarModal() {
        modal.classList.remove('open');
        formPendiente = null;
    }

    btnCancelar.addEventListener('click', cerrarModal);
    modal.addEventListener('click', function (e) {
        if (e.target === modal) cerrarModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) cerrarModal();
    });

    btnConfirmar.addEventListener('click', function () {
        if (formPendiente) formPendiente.submit();
    });
});
</script>
</body>
</html>
