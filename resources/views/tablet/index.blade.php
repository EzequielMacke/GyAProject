<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tablets</title>
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
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; box-shadow: 0 4px 14px rgba(42,111,219,0.3); }

        .btn-green { background: var(--green-s); border-color: var(--green-b); color: var(--green); }
        .btn-green:hover { background: var(--green); border-color: var(--green); color: #fff; }

        .btn-aprobaciones { position: relative; }
        .btn-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 99px;
            background: var(--red);
            color: #fff;
            font-size: 0.68rem;
            font-weight: 700;
            line-height: 1;
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
            width: 230px;
            outline: none;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
            height: 38px;
        }

        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus {
            border-color: var(--accent);
            width: 280px;
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }

        /* ══════════════════════════════
           STATS ROW
        ══════════════════════════════ */
        .stats-row {
            display: flex;
            gap: 0.7rem;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
        }

        .stat-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.6rem;
            padding: 0.85rem 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            min-width: 130px;
        }

        .stat-icon {
            width: 32px; height: 32px;
            border-radius: 0.4rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; flex-shrink: 0;
        }

        .stat-icon.blue  { background: var(--accent-s); color: var(--accent); }
        .stat-icon.green { background: var(--green-s);  color: var(--green); }
        .stat-icon.red   { background: var(--red-s);    color: var(--red); }

        .stat-val { font-size: 1.3rem; font-weight: 700; color: var(--text); letter-spacing: -0.5px; line-height: 1; }
        .stat-lbl { font-size: 0.7rem; color: var(--muted); font-weight: 500; margin-top: 0.1rem; }

        /* ══════════════════════════════
           CARDS GRID
        ══════════════════════════════ */
        #tablets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1rem;
        }

        /* ── Tablet card ── */
        .tablet-card {
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

        .tablet-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.09);
            border-color: var(--border2);
            color: var(--text);
        }

        .tablet-card.sin-devolucion {
            border-color: var(--red-b);
            background: #fdfafa;
        }

        .tablet-card.sin-devolucion:hover {
            border-color: var(--red);
            box-shadow: 0 8px 24px rgba(217,64,64,0.12);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        /* Icon header */
        .card-icon-header {
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }

        .card-icon-header.available { background: var(--green-s); }
        .card-icon-header.unavailable { background: var(--red-s); }

        .card-icon-header i { font-size: 2.5rem; }
        .card-icon-header.available i   { color: var(--green); }
        .card-icon-header.unavailable i { color: var(--red); }

        /* Status badge */
        .status-badge {
            position: absolute;
            top: 10px; right: 10px;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.2rem 0.55rem;
            border-radius: 99px;
            letter-spacing: 0.3px;
        }

        .status-badge.ok  { background: var(--green-s); color: var(--green); border: 1px solid var(--green-b); }
        .status-badge.out { background: var(--red-s);   color: var(--red);   border: 1px solid var(--red-b); }

        .icon-wrap { position: relative; width: 100%; display: flex; align-items: center; justify-content: center; height: 100%; }

        /* Card body */
        .card-body {
            padding: 0.9rem 1.1rem;
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            flex: 1;
        }

        .card-name {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
            word-break: break-word;
            margin-bottom: 0.2rem;
        }

        /* Alert row: retirado por */
        .alert-row {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            background: var(--red-s);
            border: 1px solid var(--red-b);
            border-radius: 0.45rem;
            padding: 0.5rem 0.7rem;
            margin-bottom: 0.15rem;
        }

        .alert-row i { color: var(--red); font-size: 0.7rem; margin-top: 0.2rem; flex-shrink: 0; }

        .alert-row-text {
            font-size: 0.78rem;
            color: var(--red);
            font-weight: 600;
            line-height: 1.4;
        }

        /* Detail rows */
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
            min-width: 48px;
        }

        .detail-value {
            color: var(--text2);
            word-break: break-word;
        }

        .detail-value.mono {
            font-family: 'DM Mono', monospace;
            font-size: 0.78rem;
        }

        /* empty / no results */
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
                            Tablets
                        </div>
                        <h1 class="ph-title">Gestión de <em>Tablets</em></h1>
                        <p class="ph-sub">Inventario y estado de uso de tabletas</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar tableta…" autocomplete="off">
                        </div>
                        @permiso('tab', 'agregar')
                        <a href="{{ route('tabletas.create') }}" class="btn btn-green">
                            <i class="fas fa-plus"></i> Nueva tablet
                        </a>
                        @endpermiso
                        @permiso('ret_tab', 'agregar')
                        <a href="{{ route('tabletas.retiro') }}" class="btn">
                            <i class="fas fa-right-from-bracket"></i> Retiro
                        </a>
                        <a href="{{ route('tabletas.devolucion.index') }}" class="btn">
                            <i class="fas fa-right-to-bracket"></i> Devolución
                        </a>
                        @endpermiso
                        @permiso('ret_tab', 'eliminar')
                        @php
                            $pendAprobaciones = $tabletausos->filter(function($u) {
                                return $u->aprobado == 0
                                    || ($u->aprobado == 1 && $u->fecha_devolucion && !$u->aprobacion_devolucion);
                            })->count();
                        @endphp
                        <a href="{{ route('tabletas.aprobacion') }}" class="btn btn-aprobaciones">
                            <i class="fas fa-check-double"></i> Aprobaciones
                            @if($pendAprobaciones > 0)
                            <span class="btn-count">{{ $pendAprobaciones }}</span>
                            @endif
                        </a>
                        @endpermiso
                        @permiso('tab', 'eliminar')
                        <a href="{{ route('tabletas.report') }}" class="btn">
                            <i class="fas fa-file-alt"></i> Reportes
                        </a>
                        <a href="{{ route('tabletas.generarQrs') }}" class="btn">
                            <i class="fas fa-qrcode"></i> Generar QR
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
                <div class="alert alert-success mb-3" style="border-radius:0.55rem; font-size:0.85rem;">
                    {{ session('success') }}
                </div>
                @endif

                {{-- Stats --}}
                @php
                    $total       = $tabletas->count();
                    $enUso       = $tabletas->filter(function($t) use ($tabletausos) {
                        $u = $tabletausos->where('tableta_id', $t->id)->sortByDesc('id')->first();
                        return $u && $u->aprobado == 1 && (!$u->fecha_devolucion || !$u->aprobacion_devolucion);
                    })->count();
                    $disponibles = $total - $enUso;
                @endphp

                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="fas fa-tablet-alt"></i></div>
                        <div>
                            <div class="stat-val">{{ $total }}</div>
                            <div class="stat-lbl">Total</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <div class="stat-val">{{ $disponibles }}</div>
                            <div class="stat-lbl">Disponibles</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red"><i class="fas fa-user-clock"></i></div>
                        <div>
                            <div class="stat-val">{{ $enUso }}</div>
                            <div class="stat-lbl">En uso</div>
                        </div>
                    </div>
                </div>

                {{-- Grid --}}
                <div id="tablets-grid">

                    @if($tabletas->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-tablet-alt"></i>
                        <p>No hay tablets registradas.</p>
                    </div>
                    @else

                    @foreach($tabletas->reverse() as $tableta)
                    @php
                        $ultimoUso      = $tabletausos->where('tableta_id', $tableta->id)->sortByDesc('id')->first();
                        $sinDevolucion  = $ultimoUso && $ultimoUso->aprobado == 1 && (!$ultimoUso->fecha_devolucion || !$ultimoUso->aprobacion_devolucion);
                        $usuario        = ($sinDevolucion && $ultimoUso->usuario_id)
                            ? App\Models\Usuarios::find($ultimoUso->usuario_id)
                            : null;
                        $searchData     = strtolower(
                            $tableta->clave . ' ' . $tableta->nombre . ' ' .
                            ($tableta->modelo ?? '') . ' ' .
                            ($tableta->serie  ?? '') . ' ' .
                            ($tableta->sim    ?? '') . ' ' .
                            ($tableta->observacion ?? '')
                        );
                    @endphp

                    @if(app(\App\Services\PermisoService::class)->puede('tab', 'editar'))
                    <a href="{{ route('tabletas.edit', $tableta->id) }}"
                       class="tablet-card{{ $sinDevolucion ? ' sin-devolucion' : '' }}"
                       style="animation-delay:{{ $loop->index * 0.04 }}s"
                       data-search="{{ $searchData }}">
                    @else
                    <div class="tablet-card{{ $sinDevolucion ? ' sin-devolucion' : '' }}"
                         style="animation-delay:{{ $loop->index * 0.04 }}s; cursor:default;"
                         data-search="{{ $searchData }}">
                    @endif

                        {{-- Icon header --}}
                        <div class="card-icon-header {{ $sinDevolucion ? 'unavailable' : 'available' }}">
                            <div class="icon-wrap">
                                <i class="fas fa-tablet-alt"></i>
                                <span class="status-badge {{ $sinDevolucion ? 'out' : 'ok' }}">
                                    {{ $sinDevolucion ? 'En uso' : 'Disponible' }}
                                </span>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="card-body">

                            <div class="card-name">{{ $tableta->clave }} — {{ $tableta->nombre }}</div>

                            {{-- Alert if sin devolución --}}
                            @if($sinDevolucion)
                            <div class="alert-row">
                                <i class="fas fa-exclamation-circle"></i>
                                <div class="alert-row-text">
                                    Retirado por: {{ $usuario ? ($usuario->nombre_completo ?: $usuario->nombre) : 'Usuario desconocido' }}
                                    @if($ultimoUso->fecha_retiro)
                                    · {{ \Carbon\Carbon::parse($ultimoUso->fecha_retiro)->format('d/m/Y') }}
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if(!empty($tableta->modelo))
                            <div class="detail-row">
                                <i class="fas fa-microchip"></i>
                                <span class="detail-label">Modelo</span>
                                <span class="detail-value">{{ $tableta->modelo }}</span>
                            </div>
                            @endif

                            @if(!empty($tableta->serie))
                            <div class="detail-row">
                                <i class="fas fa-barcode"></i>
                                <span class="detail-label">Serie</span>
                                <span class="detail-value mono">{{ $tableta->serie }}</span>
                            </div>
                            @endif

                            @if(!empty($tableta->sim))
                            <div class="detail-row">
                                <i class="fas fa-sim-card"></i>
                                <span class="detail-label">SIM</span>
                                <span class="detail-value mono">{{ $tableta->sim }}</span>
                            </div>
                            @endif

                            @if(!empty($tableta->observacion))
                            <div class="detail-row">
                                <i class="fas fa-comment-alt"></i>
                                <span class="detail-label">Obs.</span>
                                <span class="detail-value">{{ Str::limit($tableta->observacion, 55) }}</span>
                            </div>
                            @endif

                        </div>

                    @if(app(\App\Services\PermisoService::class)->puede('tab', 'editar'))
                    </a>
                    @else
                    </div>
                    @endif
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search');
    const cards = document.querySelectorAll('#tablets-grid .tablet-card');
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
});
</script>
</body>
</html>