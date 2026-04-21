<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliografía</title>
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

        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex; align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .ph-crumb {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.72rem; font-weight: 500; color: var(--muted);
            margin-bottom: 0.5rem;
        }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

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
        .btn-sm { height: 30px; padding: 0 0.7rem; font-size: 0.75rem; border-radius: 0.45rem; }
        .btn-danger-soft { background: #fff0f0; border-color: #f5c2c2; color: #c0392b; }
        .btn-danger-soft:hover { background: #fde0e0; border-color: #e07070; color: #a93226; }

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

        .list-wrap {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
        }
        .list-header {
            display: grid;
            grid-template-columns: 48px 1fr 1fr 140px;
            padding: 0.65rem 1.25rem;
            background: var(--surface2);
            border-bottom: 1.5px solid var(--border);
            font-size: 0.72rem; font-weight: 700;
            color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        .bib-num { font-size: 0.78rem; font-weight: 600; color: var(--muted); }

        .bib-row {
            display: grid;
            grid-template-columns: 48px 1fr 1fr 140px;
            align-items: center;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
        }
        .bib-row:last-child { border-bottom: none; }
        .bib-row:hover { background: var(--surface2); }

        .bib-nombre { font-size: 0.875rem; font-weight: 600; color: var(--text); }
        .bib-fuente { font-size: 0.82rem; color: var(--text2); }
        .bib-acciones { display: flex; justify-content: flex-end; gap: 0.35rem; }

        .empty-state { padding: 3.5rem 1.5rem; text-align: center; }
        .empty-icon {
            width: 52px; height: 52px; border-radius: 0.75rem;
            background: var(--blue-s); color: var(--accent);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin-bottom: 1rem;
        }
        .empty-title { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 0.35rem; }
        .empty-sub { font-size: 0.82rem; color: var(--muted); }

        .alert {
            padding: 0.75rem 1rem; border-radius: 0.55rem;
            font-size: 0.83rem; font-weight: 500;
            margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .alert-success { background: #e5f6f0; color: #1e9166; border: 1px solid #b6e8d6; }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.45); z-index: 9999; align-items: center; justify-content: center; }
        .modal-overlay.active { display: flex; }
        .modal-box { background: #fff; border-radius: 0.95rem; padding: 1.75rem 1.75rem 1.5rem; max-width: 380px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.18); }
        .modal-icon { width: 44px; height: 44px; border-radius: 0.65rem; background: #fff0f0; color: #c0392b; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 1rem; }
        .modal-title { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.35rem; }
        .modal-sub { font-size: 0.82rem; color: var(--muted); margin-bottom: 1.35rem; }
        .modal-actions { display: flex; gap: 0.5rem; justify-content: flex-end; }
        .btn-cancel { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); cursor: pointer; transition: all 0.14s; }
        .btn-cancel:hover { background: var(--surface2); }
        .btn-confirm-delete { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: none; background: #c0392b; color: #fff; cursor: pointer; transition: background 0.14s; }
        .btn-confirm-delete:hover { background: #a93226; }
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
                            <i class="fas fa-book"></i>
                            Bibliografías
                        </div>
                        <h1 class="ph-title"><em>Bibliografías</em></h1>
                        <p class="ph-sub">{{ $bibliografias->count() }} {{ $bibliografias->count() === 1 ? 'registro' : 'registros' }} encontrados</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search-bar" id="buscador" placeholder="Buscar bibliografía..." autocomplete="off">
                        </div>
                        @permiso('bib', 'ver')
                        <a href="{{ route('bibliografia.generate') }}" class="btn">
                            <i class="fas fa-file-word"></i> Generar
                        </a>
                        @endpermiso
                        @permiso('bib', 'agregar')
                        <a href="{{ route('bibliografia.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nueva
                        </a>
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

                <div class="list-wrap">
                    @if($bibliografias->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-book"></i></div>
                            <div class="empty-title">Sin bibliografías registradas</div>
                            <div class="empty-sub">Todavía no hay ninguna bibliografía cargada en el sistema.</div>
                        </div>
                    @else
                        <div class="list-header">
                            <div>#</div>
                            <div>Nombre</div>
                            <div>Fuente</div>
                            <div></div>
                        </div>
                        <div id="lista-bibliografias">
                            @foreach($bibliografias as $i => $bib)
                            <div class="bib-row" data-search="{{ strtolower($bib->nombre . ' ' . $bib->fuente) }}">
                                <div class="bib-num">{{ $i + 1 }}</div>
                                <div class="bib-nombre">{{ $bib->nombre }}</div>
                                <div class="bib-fuente">{{ $bib->fuente }}</div>
                                <div class="bib-acciones">
                                    @permiso('bib', 'ver')
                                    <a href="{{ route('bibliografia.show', $bib->id) }}" class="btn btn-sm" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endpermiso
                                    @permiso('bib', 'editar')
                                    <a href="{{ route('bibliografia.edit', $bib->id) }}" class="btn btn-sm" title="Editar">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    @endpermiso
                                    @permiso('bib', 'eliminar')
                                    <form id="form-delete-{{ $bib->id }}" method="POST" action="{{ route('bibliografia.destroy', $bib->id) }}">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger-soft" title="Eliminar"
                                        onclick="abrirModalEliminar({{ $bib->id }}, '{{ addslashes($bib->nombre) }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endpermiso
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<div class="modal-overlay" id="modal-eliminar">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-trash-alt"></i></div>
        <div class="modal-title">Eliminar bibliografía</div>
        <div class="modal-sub" id="modal-eliminar-sub">¿Estás seguro que querés eliminar esta bibliografía?</div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="cerrarModalEliminar()">Cancelar</button>
            <button class="btn-confirm-delete" onclick="confirmarEliminar()"><i class="fas fa-trash-alt"></i> Eliminar</button>
        </div>
    </div>
</div>

<script>
    document.getElementById('buscador')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#lista-bibliografias .bib-row').forEach(row => {
            row.style.display = row.dataset.search.includes(q) ? '' : 'none';
        });
    });

    let formIdPendiente = null;

    function abrirModalEliminar(id, nombre) {
        formIdPendiente = id;
        document.getElementById('modal-eliminar-sub').textContent = `¿Estás seguro que querés eliminar "${nombre}"?`;
        document.getElementById('modal-eliminar').classList.add('active');
    }

    function cerrarModalEliminar() {
        formIdPendiente = null;
        document.getElementById('modal-eliminar').classList.remove('active');
    }

    function confirmarEliminar() {
        if (formIdPendiente) {
            document.getElementById('form-delete-' + formIdPendiente).submit();
        }
    }

    document.getElementById('modal-eliminar').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalEliminar();
    });
</script>
</body>
</html>
