<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabajo de Campo</title>
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
            --blue-s:   #e8f0fc;
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
        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; }
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

        /* ── SEARCH ── */
        .search-wrap { position: relative; }
        .search-wrap i { position: absolute; left: 0.78rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 0.72rem; pointer-events: none; }
        .search-bar {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.83rem;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.5rem 0.9rem 0.5rem 2.1rem;
            color: var(--text); width: 220px; outline: none; height: 38px;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
        }
        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus { border-color: var(--accent); width: 270px; box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        /* ── ALERTS ── */
        .alert { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert-success { background: #e5f6f0; color: #1e9166; border: 1px solid #b6e8d6; }
        .alert-danger  { background: #fff0f0; color: #c0392b; border: 1px solid #f5c2c2; }

        /* ── MODAL ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-nuevo {
            background: #fff; border-radius: 1rem;
            width: 100%; max-width: 440px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.18);
            overflow: hidden;
            animation: modalIn 0.2s ease both;
        }
        @keyframes modalIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .modal-head {
            padding: 1.4rem 1.75rem 1.2rem;
            border-bottom: 1.5px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-head-title { font-size: 1rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 0.5rem; }
        .modal-head-title i { color: var(--accent); }
        .modal-close { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 1rem; padding: 0.25rem; border-radius: 0.35rem; transition: color 0.14s; }
        .modal-close:hover { color: var(--text); }
        .modal-body { padding: 1.5rem 1.75rem; }
        .modal-foot { padding: 1rem 1.75rem 1.4rem; display: flex; justify-content: flex-end; gap: 0.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group:last-child { margin-bottom: 0; }
        .form-label { font-size: 0.78rem; font-weight: 700; color: var(--text2); margin-bottom: 0.4rem; display: block; text-transform: uppercase; letter-spacing: 0.04em; }
        .form-label span { color: #c0392b; margin-left: 2px; }
        .form-control {
            width: 100%; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.55rem 0.85rem; color: var(--text);
            outline: none; transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .form-control.error { border-color: #e74c3c; }
        .btn-cancel { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); cursor: pointer; transition: all 0.14s; }
        .btn-cancel:hover { background: var(--surface2); }

        /* ── LIST ── */
        .list-wrap { background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem; overflow: hidden; }
        .list-header {
            display: grid;
            grid-template-columns: 48px 1fr 140px;
            padding: 0.65rem 1.25rem;
            background: var(--surface2);
            border-bottom: 1.5px solid var(--border);
            font-size: 0.72rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        .tc-row {
            display: grid;
            grid-template-columns: 48px 1fr 140px;
            align-items: center;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }
        .tc-row:last-child { border-bottom: none; }
        .tc-row:hover { background: var(--surface2); color: inherit; }
        .tc-num  { font-size: 0.78rem; font-weight: 600; color: var(--muted); }
        .tc-desc { font-size: 0.875rem; font-weight: 600; color: var(--text); }

        .badge {
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.72rem; font-weight: 700;
            border-radius: 0.4rem; padding: 0.25rem 0.6rem;
            width: fit-content;
        }
        .badge-activo   { background: #e5f6f0; color: #1e9166; border: 1px solid #b6e8d6; }
        .badge-inactivo { background: #fff0f0; color: #c0392b; border: 1px solid #f5c2c2; }

        /* ── EMPTY STATE ── */
        .empty-state { padding: 3.5rem 1.5rem; text-align: center; }
        .empty-icon { width: 52px; height: 52px; border-radius: 0.75rem; background: var(--blue-s); color: var(--accent); display: inline-flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 1rem; }
        .empty-title { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 0.35rem; }
        .empty-sub { font-size: 0.82rem; color: var(--muted); }

        /* ── MOBILE ── */
        @media (max-width: 640px) {
            .ph { padding: 1rem 0 0.75rem; gap: 0.75rem; margin-bottom: 1rem; }
            .ph-title { font-size: 1.3rem; }
            .ph-right { width: 100%; }
            .search-wrap { flex: 1; min-width: 0; }
            .search-bar, .search-bar:focus { width: 100% !important; }
            .list-header { display: none; }
            .tc-row { grid-template-columns: 1fr auto; }
            .tc-num { display: none; }
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
                            <i class="fas fa-hard-hat"></i>
                            Trabajo de Campo
                        </div>
                        <h1 class="ph-title"><em>Trabajo de Campo</em></h1>
                        <p class="ph-sub">{{ $obrasTc->count() }} {{ $obrasTc->count() === 1 ? 'registro' : 'registros' }} encontrados</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search-bar" id="buscador" placeholder="Buscar obra..." autocomplete="off">
                        </div>
                        @permiso('obr_tc', 'agregar')
                        <button type="button" class="btn btn-primary" onclick="abrirModalNuevaObra()">
                            <i class="fas fa-plus"></i> Nueva Obra
                        </button>
                        @endpermiso
                        <a href="{{ route('home') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
                @endif

                <div class="list-wrap">
                    @if($obrasTc->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-hard-hat"></i></div>
                            <div class="empty-title">Sin registros</div>
                            <div class="empty-sub">Todavía no hay ninguna obra de trabajo de campo cargada.</div>
                        </div>
                    @else
                        <div class="list-header">
                            <div>#</div>
                            <div>Descripción</div>
                            <div>Estado</div>
                        </div>
                        <div id="lista-obras-tc">
                            @foreach($obrasTc as $i => $obraTc)
                            <a href="{{ route('obras_tc.index', $obraTc->id) }}" class="tc-row" data-search="{{ strtolower($obraTc->descripcion) }}">
                                <div class="tc-num">{{ $i + 1 }}</div>
                                <div class="tc-desc">{{ $obraTc->descripcion }}</div>
                                <div>
                                    @if($obraTc->estado)
                                        <span class="badge badge-activo"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Activo</span>
                                    @else
                                        <span class="badge badge-inactivo"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Inactivo</span>
                                    @endif
                                </div>
                            </a>
                            @endforeach
                        </div>
                        <div class="empty-state" id="sin-resultados" style="display:none;">
                            <div class="empty-icon"><i class="fas fa-search"></i></div>
                            <div class="empty-title">Sin coincidencias</div>
                            <div class="empty-sub">No se encontraron obras para tu búsqueda.</div>
                        </div>
                    @endif
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL NUEVA OBRA
══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-nuevo">
    <div class="modal-nuevo">
        <div class="modal-head">
            <div class="modal-head-title"><i class="fas fa-hard-hat"></i> Nueva Obra</div>
            <button class="modal-close" onclick="cerrarModalNuevaObra()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>

        <form id="form-nuevo" method="POST" action="{{ route('trabajo_campo.store') }}">
            @csrf

            <div class="modal-body">
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" for="input-nombre">Nombre <span>*</span></label>
                    <input type="text" id="input-nombre" name="nombre" class="form-control" placeholder="Nombre de la obra" autocomplete="off">
                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn-cancel" onclick="cerrarModalNuevaObra()">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalNuevaObra() {
        document.getElementById('modal-nuevo').classList.add('active');
        document.getElementById('input-nombre').focus();
    }

    function cerrarModalNuevaObra() {
        document.getElementById('modal-nuevo').classList.remove('active');
    }

    document.getElementById('form-nuevo').addEventListener('submit', function (e) {
        const nombre = document.getElementById('input-nombre');
        if (!nombre.value.trim()) {
            e.preventDefault();
            nombre.classList.add('error');
            nombre.focus();
            return;
        }
        nombre.classList.remove('error');
    });

    document.getElementById('input-nombre').addEventListener('input', function () {
        this.classList.remove('error');
    });

    document.getElementById('modal-nuevo').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalNuevaObra();
    });

    @if($errors->any())
    abrirModalNuevaObra();
    @endif

    document.getElementById('buscador')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        const filas = document.querySelectorAll('#lista-obras-tc .tc-row');
        let visibles = 0;
        filas.forEach(row => {
            const show = row.dataset.search.includes(q);
            row.style.display = show ? '' : 'none';
            if (show) visibles++;
        });
        const sinResultados = document.getElementById('sin-resultados');
        if (sinResultados) sinResultados.style.display = visibles === 0 ? '' : 'none';
    });
</script>
</body>
</html>
