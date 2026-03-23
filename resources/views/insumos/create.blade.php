<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Insumo</title>
    @includeIf('partials.head')
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ins')->first()->id ?? null)->where('agregar', 1)->isEmpty())
        <script>window.location.href = "{{ url('/home') }}";</script>
    @endif
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
            display: flex; align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .ph-crumb {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem;
        }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; }

        /* BUTTONS */
        .btn {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border); background: var(--surface); color: var(--text2);
            text-decoration: none; cursor: pointer; transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }
        .btn-secondary { background: var(--slate-s); border-color: var(--border2); color: var(--slate); }
        .btn-secondary:hover { background: var(--border); color: var(--text); }

        /* LAYOUT */
        .page-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.25rem;
            align-items: start;
        }
        @media (max-width: 820px) {
            .page-grid { grid-template-columns: 1fr; }
        }

        /* FORM CARD */
        .form-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .form-card-stripe { height: 3px; background: linear-gradient(90deg, var(--accent), #6aaaf5); }
        .form-card-body { padding: 1.75rem 2rem; }

        .section-heading {
            display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.25rem;
        }
        .section-heading-icon {
            width: 30px; height: 30px; border-radius: 0.4rem;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; flex-shrink: 0;
        }
        .section-heading-text { font-size: 0.82rem; font-weight: 700; color: var(--text); }

        .field-label {
            display: block; font-size: 0.75rem; font-weight: 600;
            color: var(--text2); margin-bottom: 0.4rem; letter-spacing: 0.1px;
        }
        .field-input {
            width: 100%; height: 40px; padding: 0 0.85rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.855rem; color: var(--text);
            background: var(--bg); border: 1.5px solid var(--border);
            border-radius: 0.5rem; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }
        .field-input:focus {
            border-color: var(--accent); background: #fff;
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }
        .field-input::placeholder { color: var(--muted); }

        .form-actions {
            display: flex; align-items: center; gap: 0.6rem;
            padding-top: 1.5rem; border-top: 1px solid var(--border); margin-top: 1.5rem;
        }

        /* EXISTING INSUMOS PANEL */
        .panel {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .panel-header {
            padding: 0.85rem 1.1rem;
            background: var(--bg2);
            border-bottom: 1.5px solid var(--border);
            display: flex; align-items: center; gap: 0.5rem;
        }
        .panel-header-icon {
            width: 26px; height: 26px; border-radius: 0.35rem;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.68rem; flex-shrink: 0;
        }
        .panel-header-text { font-size: 0.78rem; font-weight: 700; color: var(--text); }
        .panel-count {
            margin-left: auto;
            font-family: 'DM Mono', monospace;
            font-size: 0.66rem; font-weight: 500;
            color: var(--accent); background: var(--accent-s);
            padding: 0.1rem 0.42rem; border-radius: 0.28rem;
        }

        .panel-search-wrap {
            padding: 0.75rem 1.1rem;
            border-bottom: 1px solid var(--border);
            position: relative;
        }
        .panel-search-wrap i {
            position: absolute; left: 1.75rem; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: 0.7rem; pointer-events: none;
        }
        .panel-search {
            width: 100%; height: 34px;
            padding: 0 0.75rem 0 2rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem; color: var(--text);
            background: var(--bg); border: 1.5px solid var(--border);
            border-radius: 0.45rem; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .panel-search::placeholder { color: var(--muted); }
        .panel-search:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        .panel-list {
            list-style: none;
            max-height: 400px;
            overflow-y: auto;
            padding: 0.4rem 0;
        }
        .panel-list li {
            display: flex; align-items: center; gap: 0.55rem;
            padding: 0.5rem 1.1rem;
            font-size: 0.83rem; color: var(--text2);
            transition: background 0.1s;
        }
        .panel-list li:hover { background: var(--surface2); }
        .panel-list li i { font-size: 0.45rem; color: var(--muted); }
        .panel-list li.hidden { display: none; }

        .panel-empty {
            padding: 2rem 1.1rem; text-align: center;
            color: var(--muted); font-size: 0.82rem; display: none;
        }
        .panel-empty i { display: block; font-size: 1.2rem; opacity: 0.3; margin-bottom: 0.4rem; }
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
                            <i class="fas fa-wrench"></i> Mantenimiento
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('insumos.index') }}">Insumos</a>
                            <i class="fas fa-chevron-right"></i> Nuevo
                        </div>
                        <h1 class="ph-title">Nuevo <em>Insumo</em></h1>
                        <p class="ph-sub">Completá el nombre para registrar el insumo</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('insumos.index') }}" class="btn" id="volver-btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="page-grid">

                    {{-- Form --}}
                    <form action="{{ route('insumos.store') }}" method="POST">
                        @csrf
                        <div class="form-card">
                            <div class="form-card-stripe"></div>
                            <div class="form-card-body">

                                <div class="section-heading">
                                    <div class="section-heading-icon"><i class="fas fa-cube"></i></div>
                                    <span class="section-heading-text">Datos del insumo</span>
                                </div>

                                @if ($errors->any())
                                    <div class="alert alert-danger mb-3">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif

                                <div>
                                    <label class="field-label" for="nombre">Nombre del insumo</label>
                                    <input type="text" class="field-input" id="nombre" name="nombre"
                                           placeholder="Ej: Cemento portland" required
                                           value="{{ old('nombre') }}" autocomplete="off">
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-floppy-disk"></i> Guardar insumo
                                    </button>
                                    <a href="{{ route('insumos.index') }}" class="btn btn-secondary">
                                        Cancelar
                                    </a>
                                </div>

                            </div>
                        </div>
                    </form>

                    {{-- Existing insumos panel --}}
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-header-icon"><i class="fas fa-cubes"></i></div>
                            <span class="panel-header-text">Insumos existentes</span>
                            <span class="panel-count" id="panel-count">{{ count($insumos) }}</span>
                        </div>
                        <div class="panel-search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" class="panel-search" id="panel-search" placeholder="Filtrar…" autocomplete="off">
                        </div>
                        <ul class="panel-list" id="panel-list">
                            @foreach($insumos->reverse() as $insumo)
                            <li data-name="{{ strtolower($insumo->nombre) }}">
                                <i class="fas fa-circle"></i>
                                {{ $insumo->nombre }}
                            </li>
                            @endforeach
                        </ul>
                        <div class="panel-empty" id="panel-empty">
                            <i class="fas fa-search"></i>
                            Sin resultados.
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('nombre').focus();

    const nombreInput = document.getElementById('nombre');
    const panelSearch = document.getElementById('panel-search');
    const panelList   = document.getElementById('panel-list');
    const panelEmpty  = document.getElementById('panel-empty');
    const panelCount  = document.getElementById('panel-count');
    const total       = panelList.querySelectorAll('li').length;

    function filterPanel(q) {
        const items = panelList.querySelectorAll('li');
        let vis = 0;
        items.forEach(li => {
            const show = li.dataset.name.includes(q.toLowerCase().trim());
            li.classList.toggle('hidden', !show);
            if (show) vis++;
        });
        panelEmpty.style.display = (!vis && total) ? 'block' : 'none';
        panelCount.textContent = vis;
    }

    nombreInput.addEventListener('input', function () { filterPanel(this.value); });
    panelSearch.addEventListener('input', function () { filterPanel(this.value); });

    document.addEventListener('keydown', function (e) {
        if (e.ctrlKey && e.key === '2') {
            e.preventDefault();
            document.getElementById('volver-btn').click();
        }
    });
});
</script>
</body>
</html>
