<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento</title>
    @include('partials.head')
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
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
        }

        /* ── Button ── */
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

        /* ══════════════════════════════
           OPTIONS GRID
        ══════════════════════════════ */
        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }

        .opcion-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            padding: 1.6rem 1rem 1.35rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.7rem;
            text-decoration: none;
            color: var(--text);
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
            animation: cardIn 0.22s ease both;
        }

        .opcion-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0,0,0,0.09);
            border-color: var(--border2);
            color: var(--text);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        .opcion-icon {
            width: 48px; height: 48px;
            border-radius: 0.65rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
            transition: transform 0.18s;
        }

        .opcion-card:hover .opcion-icon { transform: scale(1.08); }

        .opcion-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1.3;
        }

        .ic-green  { background: #e5f6f0; color: #1e9166; }
        .ic-brown  { background: #f5ede9; color: #853724; }
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
                            <i class="fas fa-chevron-right"></i> Mantenimiento
                        </div>
                        <h1 class="ph-title"><em>Mantenimiento</em></h1>
                        <p class="ph-sub">Administrá insumos, kits y configuraciones</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('home') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="options-grid">

                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ins')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                    <a href="{{ route('insumos.index') }}" class="opcion-card" style="animation-delay:0.04s">
                        <div class="opcion-icon ic-green"><i class="fas fa-cubes"></i></div>
                        <span class="opcion-label">Insumos</span>
                    </a>
                    @endif

                    <a href="{{ route('kits.index') }}" class="opcion-card" style="animation-delay:0.08s">
                        <div class="opcion-icon ic-brown"><i class="fas fa-box"></i></div>
                        <span class="opcion-label">Kits</span>
                    </a>

                </div>
            </div>
        </section>
    </div>

    @include('partials.footer')
</div>
</body>
</html>