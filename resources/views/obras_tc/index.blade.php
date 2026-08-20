<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obra - Trabajo de Campo</title>
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
            display: flex; align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }

        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        .ph-meta {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.78rem; color: var(--muted);
            margin-top: 0.5rem;
        }
        .ph-meta i { font-size: 0.72rem; color: var(--accent); }

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

        /* ══════════════════════════════
           OPTIONS GRID
        ══════════════════════════════ */
        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }

        .opcion-card {
            position: relative;
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

        .opcion-badge {
            position: absolute;
            top: 0.6rem; right: 0.6rem;
            min-width: 20px; height: 20px;
            padding: 0 0.35rem;
            border-radius: 999px;
            background: #d4920a;
            color: #fff;
            font-size: 0.68rem;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
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

        /* colour variants */
        .ic-purple { background: #eeecf9; color: #7c6fcd; }
        .ic-violet { background: #f3ecf9; color: #8e44ad; }
        .ic-green  { background: #e5f6f0; color: #1e9166; }
        .ic-yellow { background: #fef9ec; color: #d4920a; }
        .ic-orange { background: #fff0eb; color: #d9622a; }
        .ic-slate  { background: #edf1f4; color: #4e6070; }
        .ic-teal   { background: #e5f7fa; color: #0891a8; }
        .ic-pink   { background: #fdeef5; color: #c0507a; }
        .ic-blue   { background: #e8f0fc; color: #2a6fdb; }
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
                            <a href="{{ route('trabajo_campo.index') }}">Trabajo de Campo</a>
                            <i class="fas fa-chevron-right"></i>
                            {{ $obraTc->descripcion ?? 'Obra' }}
                        </div>
                        <h1 class="ph-title"><em>{{ $obraTc->descripcion ?? 'Obra' }}</em></h1>
                        <p class="ph-sub">Panel de gestión de la obra</p>
                        @if($obraTc->usuario)
                        <p class="ph-meta">
                            <i class="fas fa-user-circle"></i>
                            Obra creada por {{ $obraTc->usuario->nombre_completo ?: $obraTc->usuario->nombre }} el {{ $obraTc->created_at->format('d/m/Y H:i') }}
                        </p>
                        @endif
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('trabajo_campo.index') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="options-grid">

                    @permiso('dir_tc', 'ver')
                    <a href="{{ route('directorio_tc.index', $obraTc->id) }}" class="opcion-card" style="animation-delay:0.04s">
                        <div class="opcion-icon ic-purple"><i class="fas fa-folder-open"></i></div>
                        <span class="opcion-label">Directorio</span>
                    </a>
                    @endpermiso

                    @permiso('pla_tc', 'ver')
                    <a href="{{ route('planos_tc.index', $obraTc->id) }}" class="opcion-card" style="animation-delay:0.08s">
                        @if($pendientesPlanos > 0)
                        <span class="opcion-badge">{{ $pendientesPlanos }}</span>
                        @endif
                        <div class="opcion-icon ic-blue"><i class="fas fa-drafting-compass"></i></div>
                        <span class="opcion-label">Planos</span>
                    </a>
                    @endpermiso

                    @permiso('pla_tc', 'ver')
                    <a href="{{ route('galeria_tc.index', $obraTc->id) }}" class="opcion-card" style="animation-delay:0.12s">
                        <div class="opcion-icon ic-teal"><i class="fas fa-images"></i></div>
                        <span class="opcion-label">Galería de Fotos</span>
                    </a>
                    @endpermiso

                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>
</body>
</html>
