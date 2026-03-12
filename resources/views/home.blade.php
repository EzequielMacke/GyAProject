<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gavilan y Asociados - Home</title>
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
            margin-bottom: 1.5rem;
        }

        .ph-greeting {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .ph-title {
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.4px;
            line-height: 1.1;
        }

        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }

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
        <section class="content">
            <div class="container-fluid">

                <div class="ph">
                    <div class="ph-greeting"><i class="fas fa-grip-horizontal"></i> Panel principal</div>
                    <h1 class="ph-title">Gavilan <em>y Asociados</em></h1>
                    <p class="ph-sub">Seleccioná un módulo para continuar</p>
                </div>

                <div class="options-grid">

                    <a href="{{ route('obras.index') }}" class="opcion-card" style="animation-delay:0.04s">
                        <div class="opcion-icon ic-purple"><i class="fas fa-building"></i></div>
                        <span class="opcion-label">Obras</span>
                    </a>

                    <a href="{{ route('tabletas.index') }}" class="opcion-card" style="animation-delay:0.07s">
                        <div class="opcion-icon ic-violet"><i class="fas fa-tablet-alt"></i></div>
                        <span class="opcion-label">Tablets</span>
                    </a>

                    <a href="#" class="opcion-card" style="animation-delay:0.10s">
                        <div class="opcion-icon ic-green"><i class="fas fa-tools"></i></div>
                        <span class="opcion-label">Equipos</span>
                    </a>

                    <a href="#" class="opcion-card" style="animation-delay:0.13s">
                        <div class="opcion-icon ic-yellow"><i class="fas fa-truck"></i></div>
                        <span class="opcion-label">Vehículos</span>
                    </a>

                    <a href="#" class="opcion-card" style="animation-delay:0.16s">
                        <div class="opcion-icon ic-orange"><i class="fas fa-user-shield"></i></div>
                        <span class="opcion-label">Permisos</span>
                    </a>

                    <a href="#" class="opcion-card" style="animation-delay:0.19s">
                        <div class="opcion-icon ic-slate"><i class="fas fa-users"></i></div>
                        <span class="opcion-label">Usuarios</span>
                    </a>

                    <a href="#" class="opcion-card" style="animation-delay:0.22s">
                        <div class="opcion-icon ic-teal"><i class="fas fa-hammer"></i></div>
                        <span class="opcion-label">Herramientas</span>
                    </a>

                    <a href="{{ route('mantenimiento.show') }}" class="opcion-card" style="animation-delay:0.25s">
                        <div class="opcion-icon ic-pink"><i class="fas fa-wrench"></i></div>
                        <span class="opcion-label">Mantenimiento</span>
                    </a>

                    <a href="#" class="opcion-card" style="animation-delay:0.28s">
                        <div class="opcion-icon ic-blue"><i class="fas fa-chart-bar"></i></div>
                        <span class="opcion-label">Reportes</span>
                    </a>

                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>
</body>
</html>