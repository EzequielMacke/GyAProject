<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directorio de la Obra</title>
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
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }

        /* ── Header ── */
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
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

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

        /* ── Panels ── */
        .panel {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 1rem;
        }
        .panel-header {
            padding: 0.85rem 1.25rem;
            border-bottom: 1.5px solid var(--border);
            background: var(--surface2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text2);
        }
        .panel-header i { color: var(--accent); font-size: 0.78rem; }
        .panel-body { padding: 1.25rem; }

        /* ── Multi-select custom ── */
        .ms-wrap {
            border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            background: var(--surface);
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .ms-wrap:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }
        .ms-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            padding: 0.5rem 0.75rem 0;
            min-height: 0;
        }
        .ms-tags:empty { padding: 0; }
        .ms-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: var(--accent-s);
            border: 1px solid var(--accent);
            color: var(--accent);
            border-radius: 0.35rem;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.22rem 0.55rem;
            white-space: nowrap;
        }
        .ms-tag button {
            background: none;
            border: none;
            color: var(--accent);
            cursor: pointer;
            padding: 0;
            font-size: 0.85rem;
            line-height: 1;
            opacity: 0.7;
            display: flex;
            align-items: center;
        }
        .ms-tag button:hover { opacity: 1; }
        .ms-search {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem;
            border: none;
            outline: none;
            background: transparent;
            padding: 0.5rem 0.75rem;
            width: 100%;
            color: var(--text);
        }
        .ms-search::placeholder { color: var(--muted); }
        .ms-dropdown {
            display: none;
            border: 1.5px solid var(--border);
            border-top: none;
            border-radius: 0 0 0.55rem 0.55rem;
            background: var(--surface);
            max-height: 220px;
            overflow-y: auto;
            box-shadow: 0 6px 16px rgba(0,0,0,0.07);
        }
        .ms-wrap.open { border-radius: 0.55rem 0.55rem 0 0; }
        .ms-wrap.open .ms-dropdown { display: block; }
        .ms-option {
            padding: 0.6rem 0.85rem;
            font-size: 0.84rem;
            color: var(--text2);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.1s;
        }
        .ms-option:hover { background: var(--surface2); }
        .ms-option.selected { color: var(--accent); font-weight: 600; }
        .ms-option.selected::after {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            font-size: 0.65rem;
            margin-left: auto;
            color: var(--accent);
        }
        .ms-option.hidden { display: none; }
        .ms-empty { padding: 1rem; text-align: center; color: var(--muted); font-size: 0.82rem; }

        .field-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text2);
            margin-bottom: 0.4rem;
        }

        /* ── Table ── */
        .dir-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
        .dir-table th {
            padding: 0.6rem 1rem;
            background: var(--surface2);
            color: var(--muted);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid var(--border);
            text-align: left;
        }
        .dir-table td {
            padding: 0.7rem 1rem;
            color: var(--text2);
            border-bottom: 1px solid var(--border);
        }
        .dir-table tr:last-child td { border-bottom: none; }
        .dir-table tr:hover td { background: var(--surface2); }
        .dir-num, .dir-date { font-family: 'DM Mono', monospace; font-size: 0.78rem; color: var(--muted); }
        .dir-empty { text-align: center; padding: 2.5rem; color: var(--muted); font-size: 0.83rem; }
        .dir-empty i { display: block; font-size: 1.4rem; opacity: 0.3; margin-bottom: 0.5rem; }
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
                            <i class="fas fa-home"></i> Inicio
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('obras.index') }}" style="color:inherit;text-decoration:none;">Obras</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('directorio.index', $obra->id) }}" style="color:inherit;text-decoration:none;">{{ $obra->nombre ?? '-' }}</a>
                            <i class="fas fa-chevron-right"></i> Directorio
                        </div>
                        <h1 class="ph-title">Directorio de <em>{{ $obra->nombre ?? '-' }}</em></h1>
                        <p class="ph-sub">Gestioná los usuarios con acceso a esta obra</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('directorio.index', $obra->id) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                {{-- Agregar usuarios --}}
                <div class="panel">
                    <div class="panel-header">
                        <i class="fas fa-user-plus"></i> Agregar usuarios
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('directorio.store', $obra->id) }}" method="POST" id="form-directorio">
                            @csrf
                            <div style="margin-bottom:1rem;">
                                <label class="field-label">Seleccionar usuarios</label>
                                <div class="ms-wrap" id="ms-wrap">
                                    <div class="ms-tags" id="ms-tags"></div>
                                    <input type="text" class="ms-search" id="ms-search" placeholder="Buscar usuario…" autocomplete="off">
                                    <div class="ms-dropdown" id="ms-dropdown">
                                        @forelse($usuariosDisponibles as $usuario)
                                        <div class="ms-option" data-value="{{ $usuario->id }}" data-label="{{ $usuario->nombre }}">
                                            {{ $usuario->nombre }}
                                        </div>
                                        @empty
                                        <div class="ms-empty">No hay usuarios disponibles</div>
                                        @endforelse
                                    </div>
                                </div>
                                <div id="ms-hidden-inputs"></div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="ms-submit" disabled>
                                <i class="fas fa-plus"></i> Agregar usuarios
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Listado actual --}}
                <div class="panel">
                    <div class="panel-header">
                        <i class="fas fa-users"></i> Usuarios en el directorio
                    </div>
                    <table class="dir-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($directorios as $directorio)
                            <tr>
                                <td class="dir-num">{{ $loop->iteration }}</td>
                                <td>{{ $directorio->usuario->nombre ?? '-' }}</td>
                                <td class="dir-date">{{ \Carbon\Carbon::parse($directorio->fecha)->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">
                                    <div class="dir-empty">
                                        <i class="fas fa-users"></i>
                                        No hay usuarios en el directorio.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
(function() {
    const wrap     = document.getElementById('ms-wrap');
    const tags     = document.getElementById('ms-tags');
    const search   = document.getElementById('ms-search');
    const dropdown = document.getElementById('ms-dropdown');
    const hiddenInputs = document.getElementById('ms-hidden-inputs');
    const submit   = document.getElementById('ms-submit');
    const options  = Array.from(dropdown.querySelectorAll('.ms-option'));
    const selected = new Set();

    function open()  { wrap.classList.add('open'); }
    function close() { wrap.classList.remove('open'); }

    function updateSubmit() {
        submit.disabled = selected.size === 0;
    }

    function addTag(value, label) {
        if (selected.has(value)) return;
        selected.add(value);

        const tag = document.createElement('span');
        tag.className = 'ms-tag';
        tag.dataset.value = value;
        tag.innerHTML = `${label}<button type="button" title="Quitar"><i class="fas fa-times"></i></button>`;
        tag.querySelector('button').addEventListener('click', () => removeTag(value));
        tags.appendChild(tag);

        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'usuarios[]';
        input.value = value;
        input.id    = 'hi-' + value;
        hiddenInputs.appendChild(input);

        const opt = dropdown.querySelector(`[data-value="${value}"]`);
        if (opt) opt.classList.add('selected');

        search.value = '';
        filterOptions('');
        updateSubmit();
    }

    function removeTag(value) {
        selected.delete(value);
        tags.querySelector(`[data-value="${value}"]`)?.remove();
        document.getElementById('hi-' + value)?.remove();
        const opt = dropdown.querySelector(`[data-value="${value}"]`);
        if (opt) opt.classList.remove('selected');
        updateSubmit();
    }

    function filterOptions(q) {
        let any = false;
        options.forEach(opt => {
            const match = opt.dataset.label.toLowerCase().includes(q.toLowerCase());
            opt.classList.toggle('hidden', !match);
            if (match) any = true;
        });
        let empty = dropdown.querySelector('.ms-empty-msg');
        if (!any) {
            if (!empty) {
                empty = document.createElement('div');
                empty.className = 'ms-empty ms-empty-msg';
                empty.textContent = 'Sin resultados';
                dropdown.appendChild(empty);
            }
        } else {
            empty?.remove();
        }
    }

    options.forEach(opt => {
        opt.addEventListener('click', () => {
            if (opt.classList.contains('selected')) {
                removeTag(opt.dataset.value);
            } else {
                addTag(opt.dataset.value, opt.dataset.label);
            }
        });
    });

    search.addEventListener('focus', open);
    search.addEventListener('input', () => { open(); filterOptions(search.value); });

    document.addEventListener('click', e => {
        if (!wrap.contains(e.target)) close();
    });
})();
</script>
</body>
</html>
