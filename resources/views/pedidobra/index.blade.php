<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos para Obra</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
        $puedeVer     = $permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_ing')->first()->id ?? null)->where('ver', 1)->isNotEmpty();
        $puedeAgregar = $permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_ing')->first()->id ?? null)->where('agregar', 1)->isNotEmpty();
        $puedeEditar  = $permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_ing')->first()->id ?? null)->where('editar', 1)->isNotEmpty();
    @endphp

    @if (!$puedeVer)
        <script>window.location.href = "{{ url('/home') }}";</script>
    @endif

    <style>
        :root {
            --bg:        #f0f3f7;
            --bg2:       #e4e9f0;
            --surface:   #f8fafc;
            --surface2:  #edf1f6;
            --border:    #d8e0ea;
            --border2:   #c4cfdc;
            --text:      #1e2835;
            --text2:     #445060;
            --muted:     #8496aa;
            --accent:    #2a6fdb;
            --accent-s:  #e8f0fc;
            --accent-b:  #1f5bbf;
            --green:     #1e9166;
            --green-s:   #e5f6f0;
            --green-b:   #a8dcc9;
            --orange:    #c47c10;
            --orange-s:  #fef3e2;
            --orange-b:  #f5d49a;
            --red:       #d94040;
            --red-s:     #fdeaea;
            --red-b:     #f5bcbc;
            --slate:     #4e6070;
            --slate-s:   #edf1f4;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ══ PAGE HEADER ══ */
        .ph {
            padding: 1.75rem 0 1.25rem;
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
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        /* ══ BUTTONS ══ */
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
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); text-decoration: none; }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; box-shadow: 0 4px 14px rgba(42,111,219,0.3); }
        .btn-icon { width: 34px; height: 34px; padding: 0; justify-content: center; flex-shrink: 0; }
        .btn-edit  { background: var(--accent-s); border-color: #c6d9f7; color: var(--accent); }
        .btn-edit:hover { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-view  { background: var(--slate-s);  border-color: #d0d9e3; color: var(--slate); }
        .btn-view:hover { background: var(--slate); border-color: var(--slate); color: #fff; }
        .btn-dup   { background: var(--green-s);  border-color: var(--green-b); color: var(--green); }
        .btn-dup:hover { background: var(--green); border-color: var(--green); color: #fff; }

        /* ══ SEARCH ══ */
        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: 0.78rem; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: 0.72rem; pointer-events: none;
        }
        .search-bar {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.83rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            padding: 0.5rem 0.9rem 0.5rem 2.1rem;
            color: var(--text);
            width: 260px;
            outline: none;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
            height: 38px;
        }
        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus { border-color: var(--accent); width: 320px; box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        /* ══ STATS ══ */
        .stats-row { display: flex; gap: 0.7rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
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
        .stat-icon.blue   { background: var(--accent-s); color: var(--accent); }
        .stat-icon.orange { background: var(--orange-s); color: var(--orange); }
        .stat-icon.green  { background: var(--green-s);  color: var(--green);  }
        .stat-val { font-size: 1.3rem; font-weight: 700; color: var(--text); letter-spacing: -0.5px; line-height: 1; font-family: 'DM Mono', monospace; }
        .stat-lbl { font-size: 0.7rem; color: var(--muted); font-weight: 500; margin-top: 0.1rem; }

        /* ══ TABLE CARD ══ */
        .table-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: var(--surface2);
            border-bottom: 1.5px solid var(--border);
        }

        thead th {
            padding: 0.7rem 0.9rem;
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.1s;
            animation: rowIn 0.2s ease both;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--surface2); }

        @keyframes rowIn {
            from { opacity: 0; transform: translateX(-4px); }
            to   { opacity: 1; transform: none; }
        }

        td {
            padding: 0.75rem 0.9rem;
            font-size: 0.82rem;
            color: var(--text2);
            vertical-align: middle;
        }

        /* ID column */
        .td-id {
            font-family: 'DM Mono', monospace;
            font-size: 0.78rem;
            color: var(--muted);
            font-weight: 500;
        }

        /* Obra cell */
        .td-obra {
            font-weight: 600;
            color: var(--text);
            font-size: 0.84rem;
        }

        /* Date cell */
        .td-date {
            font-family: 'DM Mono', monospace;
            font-size: 0.75rem;
            color: var(--text2);
            white-space: nowrap;
        }

        /* Numbers */
        .td-num {
            font-family: 'DM Mono', monospace;
            font-size: 0.82rem;
            font-weight: 600;
            text-align: center;
        }
        .td-num.ok    { color: var(--green); }
        .td-num.warn  { color: var(--orange); }
        .td-num.plain { color: var(--text2); }

        /* Observación truncada */
        .td-obs {
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 0.78rem;
            color: var(--muted);
        }

        /* Estado badge */
        .estado-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.22rem 0.65rem;
            border-radius: 99px;
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
        }
        .estado-badge i { font-size: 0.55rem; }

        .estado-badge.pendiente { background: var(--orange-s); color: var(--orange); border: 1px solid var(--orange-b); }
        .estado-badge.preparado { background: var(--accent-s);  color: var(--accent);  border: 1px solid #c6d9f7; }
        .estado-badge.entregado { background: var(--green-s);   color: var(--green);   border: 1px solid var(--green-b); }
        .estado-badge.cancelado { background: var(--red-s);     color: var(--red);     border: 1px solid var(--red-b); }

        /* Acciones */
        .td-actions { display: flex; gap: 0.35rem; align-items: center; white-space: nowrap; }

        /* Empty */
        .empty-row td {
            text-align: center;
            padding: 4rem 1rem;
            color: var(--muted);
            font-size: 0.85rem;
        }
        .empty-row td i { display: block; font-size: 1.5rem; margin-bottom: 0.5rem; opacity: 0.3; }

        /* Alert container */
        #alert-container .alert {
            border-radius: 0.55rem;
            font-size: 0.83rem;
            margin-bottom: 1rem;
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
                            <a href="{{ url('/home') }}">Inicio</a>
                            <i class="fas fa-chevron-right"></i>
                            Pedidos para Obra
                        </div>
                        <h1 class="ph-title">Pedidos para <em>Obra</em></h1>
                        <p class="ph-sub">Listado de todos los pedidos de insumos registrados</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar pedido…" autocomplete="off">
                        </div>
                        @if($puedeAgregar)
                        <a href="{{ route('pedidobra.create') }}" class="btn btn-primary" id="agregar-pedido-btn">
                            <i class="fas fa-plus"></i> Nuevo pedido
                        </a>
                        @endif
                        <a href="{{ route('obras.show', $obra) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div id="alert-container"></div>

                @if(session('success'))
                <div class="alert alert-success mb-3" style="border-radius:0.55rem; font-size:0.85rem;">
                    {{ session('success') }}
                </div>
                @endif

                {{-- Stats --}}
                @php
                    $total     = $pedobras->count();
                    $pendientes = $pedobras->where('estado', '1')->count();
                    $entregados = $pedobras->where('estado', '3')->count();
                @endphp
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-box"></i></div>
                        <div>
                            <div class="stat-val">{{ $total }}</div>
                            <div class="stat-lbl">Total</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="stat-val">{{ $pendientes }}</div>
                            <div class="stat-lbl">Pendientes</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <div class="stat-val">{{ $entregados }}</div>
                            <div class="stat-lbl">Entregados</div>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-card">
                    <div class="table-wrap">
                        <table id="pedidos-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Obra</th>
                                    <th>Creado por</th>
                                    <th>F. Pedido</th>
                                    <th>F. Entrega</th>
                                    <th title="Insumos pedidos">Pedidos</th>
                                    <th title="Insumos preparados">Prep.</th>
                                    <th title="Insumos faltantes">Falt.</th>
                                    <th>Observación</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pedobras->reverse() as $pedobra)
                                @php
                                    $estadoKey = $pedobra->estado;
                                    $estadoNombre = $estados[$estadoKey] ?? 'Desconocido';
                                    $estadoClass = match((string)$estadoKey) {
                                        '1' => 'pendiente',
                                        '2' => 'preparado',
                                        '3' => 'entregado',
                                        '4' => 'cancelado',
                                        default => 'pendiente',
                                    };
                                    $estadoIcon = match((string)$estadoKey) {
                                        '1' => 'fa-clock',
                                        '2' => 'fa-box',
                                        '3' => 'fa-check-circle',
                                        '4' => 'fa-times-circle',
                                        default => 'fa-circle',
                                    };
                                    $faltante = $pedobra->insumo_faltante ?? 0;
                                @endphp
                                <tr style="animation-delay:{{ $loop->index * 0.03 }}s">
                                    <td class="td-id">#{{ str_pad($pedobra->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="td-obra">{{ $pedobra->obra->nombre }}</td>
                                    <td>{{ $pedobra->usuario->nombre }}</td>
                                    <td class="td-date">{{ $pedobra->fecha_pedido ? \Carbon\Carbon::parse($pedobra->fecha_pedido)->format('d/m/Y') : '—' }}</td>
                                    <td class="td-date">{{ $pedobra->fecha_entrega ? \Carbon\Carbon::parse($pedobra->fecha_entrega)->format('d/m/Y') : '—' }}</td>
                                    <td class="td-num plain">{{ $pedobra->total_insumo ?? 0 }}</td>
                                    <td class="td-num ok">{{ $pedobra->insumo_confirmado ?? 0 }}</td>
                                    <td class="td-num {{ $faltante > 0 ? 'warn' : 'ok' }}">{{ $faltante }}</td>
                                    <td>
                                        <div class="td-obs" title="{{ $pedobra->observacion }}">
                                            {{ $pedobra->observacion ?: '—' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="estado-badge {{ $estadoClass }}">
                                            <i class="fas {{ $estadoIcon }}"></i>
                                            {{ $estadoNombre }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="td-actions">
                                            @if($puedeEditar)
                                            <a href="#"
                                               class="btn btn-icon btn-edit editar-btn"
                                               data-usuario-id="{{ $pedobra->usuario_id }}"
                                               data-area="{{ session('usuario_area') }}"
                                               title="Editar">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            @endif
                                            <a href="{{ route('pedidobra.show', $pedobra->id) }}"
                                               class="btn btn-icon btn-view"
                                               title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($puedeAgregar)
                                            <a href="{{ route('pedidobra.duplicar', $pedobra->id) }}"
                                               class="btn btn-icon btn-dup"
                                               title="Duplicar">
                                                <i class="fas fa-copy"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="empty-row">
                                    <td colspan="11">
                                        <i class="fas fa-box-open"></i>
                                        No hay pedidos registrados.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Búsqueda instantánea ──
    const input = document.getElementById('search');
    const rows  = document.querySelectorAll('#pedidos-table tbody tr:not(.empty-row)');

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        rows.forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    // ── Ctrl+1 shortcut ──
    document.addEventListener('keydown', function (e) {
        if (e.ctrlKey && e.key === '1') {
            e.preventDefault();
            document.getElementById('agregar-pedido-btn')?.click();
        }
    });

    // ── Validación editar ──
    const alertContainer = document.getElementById('alert-container');
    const usuarioActualId = {{ Auth::id() }};
    const areaActual = "{{ session('usuario_area_id') }}";

    document.querySelectorAll('.editar-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (usuarioActualId != btn.dataset.usuarioId && areaActual != 1) {
                e.preventDefault();
                alertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:0.55rem; font-size:0.83rem;">
                        Solo el creador o el administrador pueden editar este pedido.
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>`;
                setTimeout(() => alertContainer.innerHTML = '', 3000);
            }
        });
    });

});
</script>
</body>
</html>