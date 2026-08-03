<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Tabletas</title>
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
            --red:      #d94040;
            --red-s:    #fdeaea;
            --slate:    #4e6070;
            --slate-s:  #edf1f4;
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
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
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

        /* ── Filter bar ── */
        .filter-wrap {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.75rem;
            padding: 0.85rem 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .filter-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text2);
            white-space: nowrap;
        }
        .filter-select {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.83rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.5rem;
            padding: 0.42rem 0.85rem;
            color: var(--text);
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            height: 36px;
            min-width: 220px;
        }
        .filter-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }

        /* ── Table ── */
        .table-wrap {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .rep-table { width: 100%; border-collapse: collapse; }
        .rep-table thead tr { background: var(--bg2); border-bottom: 1.5px solid var(--border); }
        .rep-table th {
            padding: 0.65rem 1.1rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.9px;
            text-transform: uppercase;
            color: var(--muted);
            text-align: left;
            cursor: pointer;
            user-select: none;
            white-space: nowrap;
        }
        .rep-table th:hover { color: var(--accent); }
        .sort-icon { margin-left: 0.3rem; opacity: 0.45; font-size: 0.6rem; }
        .rep-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.1s;
        }
        .rep-table tbody tr:last-child { border-bottom: none; }
        .rep-table tbody tr:hover { background: var(--surface2); }
        .rep-table td {
            padding: 0.8rem 1.1rem;
            font-size: 0.845rem;
            color: var(--text2);
            vertical-align: middle;
        }

        /* cell types */
        .cell-mono { font-family: 'DM Mono', monospace; font-size: 0.78rem; color: var(--muted); }
        .cell-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: var(--accent-s);
            color: var(--accent);
            border: 1px solid var(--accent);
            border-radius: 0.35rem;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.18rem 0.55rem;
            font-family: 'DM Mono', monospace;
        }
        .cell-date {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.8rem;
            color: var(--muted);
            font-family: 'DM Mono', monospace;
        }
        .cell-date i { font-size: 0.6rem; }
        .cell-date.pending { color: var(--green); font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.78rem; font-weight: 600; }

        .estado-badge {
            display: inline-flex; align-items: center; gap: 0.32rem;
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 0.3px; text-transform: uppercase;
            padding: 0.24rem 0.6rem; border-radius: 99px;
            white-space: nowrap;
        }
        .estado-badge i { font-size: 0.55rem; }
        .estado-badge.pendiente-retiro     { background: var(--red-s);    color: var(--red); }
        .estado-badge.en-uso               { background: var(--red-s);    color: var(--red); }
        .estado-badge.pendiente-devolucion { background: var(--accent-s); color: var(--accent); }
        .estado-badge.finalizado           { background: var(--green-s);  color: var(--green); }

        /* empty */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
            font-size: 0.88rem;
        }
        .empty-state i { display: block; font-size: 2rem; opacity: 0.3; margin-bottom: 0.75rem; }
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
                            <a href="{{ route('tabletas.index') }}">Tabletas</a>
                            <i class="fas fa-chevron-right"></i> Reporte
                        </div>
                        <h1 class="ph-title">Reporte de <em>tabletas</em></h1>
                        <p class="ph-sub">Historial de retiros y devoluciones</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('tabletas.index') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                {{-- Filtro --}}
                <form method="GET" autocomplete="off">
                    <div class="filter-wrap">
                        <span class="filter-label"><i class="fas fa-tablet-alt" style="color:var(--accent);margin-right:0.3rem;"></i> Tableta</span>
                        <select name="tableta_id" class="filter-select">
                            <option value="">Todas las tabletas</option>
                            @foreach($tabletas as $tableta)
                                <option value="{{ $tableta->id }}" {{ request('tableta_id') == $tableta->id ? 'selected' : '' }}>
                                    {{ $tableta->clave }} — {{ $tableta->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <span class="filter-label"><i class="fas fa-hourglass-half" style="color:var(--accent);margin-right:0.3rem;"></i> Estado</span>
                        <select name="estado" class="filter-select" style="min-width:200px;">
                            <option value="">Todos los estados</option>
                            <option value="pendiente_retiro" {{ request('estado') == 'pendiente_retiro' ? 'selected' : '' }}>Pendiente de aprobación</option>
                            <option value="en_uso" {{ request('estado') == 'en_uso' ? 'selected' : '' }}>En uso</option>
                            <option value="pendiente_devolucion" {{ request('estado') == 'pendiente_devolucion' ? 'selected' : '' }}>Devolución pendiente</option>
                            <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        @if(request('tableta_id') || request('estado'))
                        <a href="{{ request()->url() }}" class="btn">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                        @endif
                    </div>
                </form>

                {{-- Tabla --}}
                <div class="table-wrap">
                    @if($usos->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-tablet-alt"></i>
                        No hay registros de uso de tabletas.
                    </div>
                    @else
                    <table class="rep-table" id="rep-table">
                        <thead>
                            <tr>
                                <th onclick="sortTable(0)">#<i class="fas fa-sort sort-icon"></i></th>
                                <th onclick="sortTable(1)">Clave<i class="fas fa-sort sort-icon"></i></th>
                                <th onclick="sortTable(2)">Tableta<i class="fas fa-sort sort-icon"></i></th>
                                <th onclick="sortTable(3)">Usuario<i class="fas fa-sort sort-icon"></i></th>
                                <th onclick="sortTable(4)">Retiro<i class="fas fa-sort sort-icon"></i></th>
                                <th onclick="sortTable(5)">Devolución<i class="fas fa-sort sort-icon"></i></th>
                                <th onclick="sortTable(6)">Estado<i class="fas fa-sort sort-icon"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usos as $uso)
                            @php
                                if ($uso->aprobado == 0) {
                                    $estadoTxt = 'Pendiente aprobación';
                                    $estadoCls = 'pendiente-retiro';
                                    $estadoIcon = 'fa-hourglass-half';
                                } elseif (!$uso->fecha_devolucion) {
                                    $estadoTxt = 'En uso';
                                    $estadoCls = 'en-uso';
                                    $estadoIcon = 'fa-user-clock';
                                } elseif (!$uso->aprobacion_devolucion) {
                                    $estadoTxt = 'Devolución pendiente';
                                    $estadoCls = 'pendiente-devolucion';
                                    $estadoIcon = 'fa-hourglass-half';
                                } else {
                                    $estadoTxt = 'Finalizado';
                                    $estadoCls = 'finalizado';
                                    $estadoIcon = 'fa-circle-check';
                                }
                            @endphp
                            <tr>
                                <td class="cell-mono">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                <td><span class="cell-badge">{{ $uso->tableta->clave ?? '-' }}</span></td>
                                <td style="font-weight:600; color:var(--text);">{{ $uso->tableta->nombre ?? '-' }}</td>
                                <td>{{ $uso->usuario->nombre_completo ?: ($uso->usuario->nombre ?? '-') }}</td>
                                <td data-sort="{{ $uso->fecha_retiro ? \Carbon\Carbon::parse($uso->fecha_retiro)->format('Y-m-d') : '' }}">
                                    @if($uso->fecha_retiro)
                                    <div class="cell-date">
                                        <i class="fas fa-sign-out-alt"></i>
                                        {{ \Carbon\Carbon::parse($uso->fecha_retiro)->format('d/m/Y') }}
                                    </div>
                                    @else
                                    <span class="cell-mono">—</span>
                                    @endif
                                </td>
                                <td data-sort="{{ $uso->fecha_devolucion ? \Carbon\Carbon::parse($uso->fecha_devolucion)->format('Y-m-d') : '' }}">
                                    @if($uso->fecha_devolucion)
                                    <div class="cell-date">
                                        <i class="fas fa-sign-in-alt"></i>
                                        {{ \Carbon\Carbon::parse($uso->fecha_devolucion)->format('d/m/Y') }}
                                    </div>
                                    @else
                                    <div class="cell-date pending">
                                        <i class="fas fa-clock"></i> Pendiente
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="estado-badge {{ $estadoCls }}">
                                        <i class="fas {{ $estadoIcon }}"></i> {{ $estadoTxt }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
const _sortDir = {};
function sortTable(col) {
    const table = document.getElementById('rep-table');
    if (!table) return;
    const dir = _sortDir[col] === 'asc' ? 'desc' : 'asc';
    _sortDir[col] = dir;
    const tbody = table.tBodies[0];
    const rows  = Array.from(tbody.rows);
    rows.sort((a, b) => {
        const cellA = a.cells[col], cellB = b.cells[col];
        let x = cellA?.dataset.sort ?? cellA?.textContent.trim().toLowerCase() ?? '';
        let y = cellB?.dataset.sort ?? cellB?.textContent.trim().toLowerCase() ?? '';
        const dx = Date.parse(x), dy = Date.parse(y);
        if (!isNaN(dx) && !isNaN(dy)) { x = dx; y = dy; }
        if (x < y) return dir === 'asc' ? -1 : 1;
        if (x > y) return dir === 'asc' ?  1 : -1;
        return 0;
    });
    rows.forEach(r => tbody.appendChild(r));
}
</script>
</body>
</html>
