<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tableta->clave }} — {{ $tableta->nombre }}</title>
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
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .info-card {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }

        .info-header {
            padding: 2rem 1.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0.6rem;
        }
        .info-header.disponible { background: var(--green-s); }
        .info-header.en-uso     { background: var(--red-s); }

        .info-icon {
            width: 64px; height: 64px; border-radius: 1rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.7rem;
            background: #fff;
        }
        .info-header.disponible .info-icon { color: var(--green); }
        .info-header.en-uso .info-icon     { color: var(--red); }

        .info-clave { font-size: 1.3rem; font-weight: 700; color: var(--text); letter-spacing: -0.3px; }
        .info-nombre { font-size: 0.88rem; color: var(--text2); margin-top: -0.35rem; }

        .status-badge {
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.4px; text-transform: uppercase;
            padding: 0.3rem 0.75rem; border-radius: 99px;
        }
        .status-badge.ok  { background: var(--green); color: #fff; }
        .status-badge.out { background: var(--red); color: #fff; }

        .info-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 0.9rem; }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            font-size: 0.85rem;
        }
        .info-row i {
            width: 30px; height: 30px; border-radius: 0.5rem;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; flex-shrink: 0;
        }
        .info-row-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); }
        .info-row-value { font-size: 0.88rem; color: var(--text); font-weight: 600; word-break: break-word; }
        .info-row-value.mono { font-family: 'DM Mono', monospace; font-weight: 500; }

        .alert-uso {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            background: var(--red-s);
            border: 1px solid var(--red-b);
            border-radius: 0.6rem;
            padding: 0.85rem 1rem;
            margin: 0 1.5rem 1.5rem;
        }
        .alert-uso i { color: var(--red); font-size: 0.85rem; margin-top: 0.15rem; flex-shrink: 0; }
        .alert-uso-text { font-size: 0.82rem; color: var(--red); font-weight: 600; line-height: 1.45; }

        .info-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 0.7rem;
            color: var(--muted);
        }
    </style>
</head>
<body>
    <div class="info-card">
        <div class="info-header {{ $enUso ? 'en-uso' : 'disponible' }}">
            <div class="info-icon"><i class="fas fa-tablet-alt"></i></div>
            <div class="info-clave">{{ $tableta->clave }}</div>
            <div class="info-nombre">{{ $tableta->nombre }}</div>
            <span class="status-badge {{ $enUso ? 'out' : 'ok' }}">
                <i class="fas fa-circle"></i>
                {{ $enUso ? 'En uso' : 'Disponible' }}
            </span>
        </div>

        @if($enUso)
        <div class="alert-uso">
            <i class="fas fa-triangle-exclamation"></i>
            <div class="alert-uso-text">
                Retirada por {{ $usuario ? ($usuario->nombre_completo ?: $usuario->nombre) : 'usuario desconocido' }}
                @if($ultimoUso->fecha_retiro)
                el {{ \Carbon\Carbon::parse($ultimoUso->fecha_retiro)->format('d/m/Y') }}
                @endif
            </div>
        </div>
        @endif

        <div class="info-body">
            @if(!empty($tableta->modelo))
            <div class="info-row">
                <i class="fas fa-microchip"></i>
                <div>
                    <div class="info-row-label">Modelo</div>
                    <div class="info-row-value">{{ $tableta->modelo }}</div>
                </div>
            </div>
            @endif

            @if(!empty($tableta->serie))
            <div class="info-row">
                <i class="fas fa-barcode"></i>
                <div>
                    <div class="info-row-label">Serie</div>
                    <div class="info-row-value mono">{{ $tableta->serie }}</div>
                </div>
            </div>
            @endif

            @if(!empty($tableta->sim))
            <div class="info-row">
                <i class="fas fa-sim-card"></i>
                <div>
                    <div class="info-row-label">SIM</div>
                    <div class="info-row-value mono">{{ $tableta->sim }}</div>
                </div>
            </div>
            @endif

            @if(!empty($tableta->observacion))
            <div class="info-row">
                <i class="fas fa-comment-alt"></i>
                <div>
                    <div class="info-row-label">Observación</div>
                    <div class="info-row-value">{{ $tableta->observacion }}</div>
                </div>
            </div>
            @endif
        </div>

        <div class="info-footer">
            Información de la tableta — GyA
        </div>
    </div>
</body>
</html>
