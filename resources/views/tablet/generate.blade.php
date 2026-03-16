<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Códigos QR de Tabletas</title>
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

        /* ── QR Grid ── */
        .qr-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 1rem;
        }

        .qr-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            padding: 1.25rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            text-align: center;
        }

        .qr-canvas-wrap {
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            padding: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-label {
            font-family: 'DM Mono', monospace;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: 0.5px;
        }

        .qr-sublabel {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: -0.4rem;
        }

        .qr-admin .qr-canvas-wrap {
            border-color: var(--accent);
            background: var(--accent-s);
        }

        .qr-admin .qr-label { color: var(--accent); }

        /* ── Print ── */
        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .content-wrapper { background: #fff !important; }
            .qr-grid { gap: 0.5rem; }
            .qr-card {
                border: 1px solid #ccc;
                box-shadow: none;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('partials.navbar')
    @include('partials.sidebar')

    <div class="content-wrapper">
        <div class="content-header no-print">
            <div class="container-fluid">
                <div class="ph">
                    <div>
                        <div class="ph-crumb">
                            <i class="fas fa-home"></i> Inicio
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('tabletas.index') }}">Tabletas</a>
                            <i class="fas fa-chevron-right"></i> Códigos QR
                        </div>
                        <h1 class="ph-title">Códigos <em>QR</em></h1>
                        <p class="ph-sub">Imprimí los códigos QR de cada tableta</p>
                    </div>
                    <div class="ph-right">
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                        <a href="{{ route('tabletas.index') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="qr-grid">

                    {{-- QR Administrador --}}
                    <div class="qr-card qr-admin">
                        <div class="qr-canvas-wrap">
                            <canvas id="qr-admin"></canvas>
                        </div>
                        <div class="qr-label">ADMINISTRADOR</div>
                        <div class="qr-sublabel">Acceso de administrador</div>
                    </div>

                    @foreach($qrs as $tableta)
                    <div class="qr-card">
                        <div class="qr-canvas-wrap">
                            <canvas id="qr-{{ $tableta->id }}"></canvas>
                        </div>
                        <div class="qr-label">{{ $tableta->clave ?? $tableta->id }}</div>
                        <div class="qr-sublabel">{{ $tableta->nombre ?? '' }}</div>
                    </div>
                    @endforeach

                </div>
            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new QRious({
        element: document.getElementById('qr-admin'),
        value: "9XQ2Z7LJ4B1V6KTP",
        size: 150,
        background: 'transparent',
        foreground: '#2a6fdb'
    });

    @foreach($qrs as $tableta)
    new QRious({
        element: document.getElementById('qr-{{ $tableta->id }}'),
        value: "{{ $tableta->codigo_qr }}",
        size: 150,
        background: 'white',
        foreground: '#1e2835'
    });
    @endforeach
});
</script>
</body>
</html>
