<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Permisos</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            --orange:   #d97706;
            --orange-s: #fef3c7;
            --green:    #1e9166;
            --green-s:  #e5f6f0;
            --red:      #dc2626;
            --red-s:    #fee2e2;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* PAGE HEADER */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
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
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.4px;
            line-height: 1.2;
        }

        .ph-title em { font-style: normal; color: var(--orange); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }

        .ph-actions {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        /* BUTTONS */
        .btn-action {
            height: 38px;
            padding: 0 1rem;
            border-radius: 0.55rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 600;
            border: 1.5px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.14s;
            white-space: nowrap;
        }

        .btn-save    { background: var(--accent);  color: #fff; border-color: var(--accent-b); }
        .btn-save:hover    { background: var(--accent-b); color: #fff; }

        .btn-check   { background: var(--green-s); color: var(--green); border-color: #a8dcc9; }
        .btn-check:hover   { background: #d4f0e6; color: var(--green); border-color: var(--green); }

        .btn-uncheck { background: var(--red-s);   color: var(--red);   border-color: #fca5a5; }
        .btn-uncheck:hover { background: #fecaca; color: var(--red); border-color: var(--red); }

        .btn-back    { background: var(--surface);  color: var(--text2); border-color: var(--border); }
        .btn-back:hover    { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        /* PERMISSIONS TABLE */
        .perm-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .perm-table {
            width: 100%;
            border-collapse: collapse;
        }

        .perm-table thead tr {
            background: var(--surface2);
            border-bottom: 2px solid var(--border);
        }

        .perm-table thead th {
            padding: 0.85rem 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: center;
        }

        .perm-table thead th:first-child { text-align: left; padding-left: 1.25rem; }

        .perm-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
        }

        .perm-table tbody tr:last-child { border-bottom: none; }
        .perm-table tbody tr:hover { background: var(--accent-s); }

        .perm-table td {
            padding: 0.7rem 1rem;
            text-align: center;
            vertical-align: middle;
        }

        .perm-table td:first-child { text-align: left; padding-left: 1.25rem; }

        /* Module toggle button */
        .mod-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text);
            padding: 0.25rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.14s;
        }

        .mod-btn:hover { color: var(--accent); }
        .mod-btn i { font-size: 0.65rem; color: var(--muted); }

        /* Column toggle button */
        .col-toggle {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.4rem;
            padding: 0.3rem 0.7rem;
            font-size: 0.72rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.14s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .col-toggle.col-ver      { color: var(--accent); }
        .col-toggle.col-agregar  { color: var(--green); }
        .col-toggle.col-editar   { color: var(--orange); }
        .col-toggle.col-eliminar { color: var(--red); }

        .col-toggle:hover { background: var(--surface2); border-color: var(--border2); }

        /* Custom checkbox */
        .perm-check {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .perm-check.ver      { accent-color: var(--accent); }
        .perm-check.agregar  { accent-color: var(--green); }
        .perm-check.editar   { accent-color: var(--orange); }
        .perm-check.eliminar { accent-color: var(--red); }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('partials.navbar')
    @include('partials.sidebar')

    <div class="content-wrapper">
        <form action="{{ route('permisos.update', $area->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="content-header">
            <div class="container-fluid">
                <div class="ph">
                    <div>
                        <div class="ph-crumb">
                            <i class="fas fa-home"></i>
                            <a href="{{ url('/home') }}">Inicio</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('permisos.index') }}">Permisos</a>
                            <i class="fas fa-chevron-right"></i>
                            {{ $area->descripcion }}
                        </div>
                        <h1 class="ph-title">Permisos — <em>{{ $area->descripcion }}</em></h1>
                        <p class="ph-sub">Configurá los accesos por módulo para esta área</p>
                    </div>
                    <div class="ph-actions">
                        <button type="button" id="marcar-todo" class="btn-action btn-check">
                            <i class="fas fa-check-double"></i> Marcar todo
                        </button>
                        <button type="button" id="desmarcar-todo" class="btn-action btn-uncheck">
                            <i class="fas fa-times"></i> Desmarcar todo
                        </button>
                        <button type="submit" class="btn-action btn-save">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('permisos.index') }}" class="btn-action btn-back">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="perm-card">
                    <table class="perm-table">
                        <thead>
                            <tr>
                                <th>Módulo</th>
                                <th>
                                    <button type="button" id="toggle-ver" class="col-toggle col-ver">
                                        <i class="fas fa-eye"></i> Ver
                                    </button>
                                </th>
                                <th>
                                    <button type="button" id="toggle-agregar" class="col-toggle col-agregar">
                                        <i class="fas fa-plus"></i> Agregar
                                    </button>
                                </th>
                                <th>
                                    <button type="button" id="toggle-editar" class="col-toggle col-editar">
                                        <i class="fas fa-pen"></i> Editar
                                    </button>
                                </th>
                                <th>
                                    <button type="button" id="toggle-eliminar" class="col-toggle col-eliminar">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modulos as $modulo)
                            <tr>
                                <td>
                                    <button type="button" class="mod-btn toggle-fila">
                                        <i class="fas fa-grip-vertical"></i>
                                        {{ $modulo->descripcion }}
                                    </button>
                                </td>
                                <td>
                                    <input type="checkbox" class="perm-check ver"
                                        name="permisos[{{ $modulo->id }}][ver]" value="1"
                                        {{ isset($permisos[$modulo->id]) && $permisos[$modulo->id]->ver == 1 ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="checkbox" class="perm-check agregar"
                                        name="permisos[{{ $modulo->id }}][agregar]" value="1"
                                        {{ isset($permisos[$modulo->id]) && $permisos[$modulo->id]->agregar == 1 ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="checkbox" class="perm-check editar"
                                        name="permisos[{{ $modulo->id }}][editar]" value="1"
                                        {{ isset($permisos[$modulo->id]) && $permisos[$modulo->id]->editar == 1 ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="checkbox" class="perm-check eliminar"
                                        name="permisos[{{ $modulo->id }}][eliminar]" value="1"
                                        {{ isset($permisos[$modulo->id]) && $permisos[$modulo->id]->eliminar == 1 ? 'checked' : '' }}>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        </form>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('marcar-todo').addEventListener('click', function () {
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
    });

    document.getElementById('desmarcar-todo').addEventListener('click', function () {
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    });

    function alternarColumna(clase) {
        const cbs = document.querySelectorAll(`.${clase}`);
        const allChecked = Array.from(cbs).every(cb => cb.checked);
        cbs.forEach(cb => cb.checked = !allChecked);
    }

    document.getElementById('toggle-ver').addEventListener('click',      () => alternarColumna('ver'));
    document.getElementById('toggle-agregar').addEventListener('click',  () => alternarColumna('agregar'));
    document.getElementById('toggle-editar').addEventListener('click',   () => alternarColumna('editar'));
    document.getElementById('toggle-eliminar').addEventListener('click', () => alternarColumna('eliminar'));

    document.querySelectorAll('.toggle-fila').forEach(btn => {
        btn.addEventListener('click', function () {
            const cbs = btn.closest('tr').querySelectorAll('input[type="checkbox"]');
            const allChecked = Array.from(cbs).every(cb => cb.checked);
            cbs.forEach(cb => cb.checked = !allChecked);
        });
    });
});
</script>
</body>
</html>
