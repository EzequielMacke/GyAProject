<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Contactos</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_ing')->first()->id ?? null)->where('ver', 1)->isEmpty())
        <script>window.location.href = "{{ url('/home') }}";</script>
    @endif

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
            word-break: break-word;
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
            width: 220px;
            outline: none;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
            height: 38px;
        }

        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus {
            border-color: var(--accent);
            width: 270px;
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }

        /* ══════════════════════════════
           CARDS GRID
        ══════════════════════════════ */
        #cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1rem;
        }

        /* ── Add card ── */
        .add-card {
            background: var(--green-s);
            border: 1.5px dashed var(--green-b);
            border-radius: 0.85rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 2.5rem 1rem;
            text-decoration: none;
            color: var(--green);
            text-align: center;
            transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s, background 0.18s;
            min-height: 160px;
        }

        .add-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(30,145,102,0.12);
            border-color: var(--green);
            background: #d4f0e6;
            color: var(--green);
        }

        .add-card-icon {
            width: 52px; height: 52px;
            border-radius: 0.65rem;
            background: rgba(30,145,102,0.12);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            color: var(--green);
            transition: transform 0.18s;
        }

        .add-card:hover .add-card-icon { transform: scale(1.08); }

        .add-card-label { font-size: 0.88rem; font-weight: 700; color: var(--green); }
        .add-card-sub   { font-size: 0.75rem; color: var(--green); opacity: 0.7; }

        /* ── Contact card ── */
        .contact-card {
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

        .contact-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.09);
            border-color: var(--border2);
            color: var(--text);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        /* avatar header */
        .card-avatar-header {
            background: var(--accent-s);
            padding: 1.5rem 1rem 1.2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid var(--border);
        }

        .avatar-circle {
            width: 52px; height: 52px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            flex-shrink: 0;
        }

        .card-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
            text-align: center;
            line-height: 1.3;
            word-break: break-word;
        }

        .tipo-badge {
            font-size: 0.68rem;
            font-weight: 600;
            padding: 0.18rem 0.55rem;
            border-radius: 99px;
            background: var(--surface2);
            color: var(--text2);
            border: 1px solid var(--border);
        }

        /* card details */
        .card-details {
            padding: 0.9rem 1.1rem;
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            flex: 1;
        }

        .detail-row {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            font-size: 0.8rem;
        }

        .detail-row i {
            color: var(--muted);
            font-size: 0.7rem;
            margin-top: 0.15rem;
            flex-shrink: 0;
            width: 12px;
            text-align: center;
        }

        .detail-label {
            color: var(--muted);
            font-weight: 600;
            font-size: 0.72rem;
            flex-shrink: 0;
        }

        .detail-value {
            color: var(--text2);
            word-break: break-word;
        }

        /* presupuesto footer */
        .card-footer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 1.1rem;
            background: var(--surface2);
            border-top: 1px solid var(--border);
        }

        .presup-label {
            font-size: 0.72rem;
            color: var(--muted);
            font-weight: 600;
        }

        .presup-val {
            font-family: 'DM Mono', monospace;
            font-size: 0.78rem;
            color: var(--accent);
            font-weight: 500;
        }

        /* empty / no results */
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
                            <i class="fas fa-hard-hat"></i>
                            <a href="{{ route('obras.index') }}">Obras</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('obras.show', $obra) }}">{{ $obra->nombre ?? '-' }}</a>
                            <i class="fas fa-chevron-right"></i>
                            Contactos
                        </div>
                        <h1 class="ph-title">Contactos — <em>{{ $obra->nombre ?? '-' }}</em></h1>
                        <p class="ph-sub">Personas y empresas vinculadas a esta obra</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="search-bar" placeholder="Buscar contacto…" autocomplete="off">
                        </div>
                        <a href="{{ route('obras.show', $obra) }}" class="btn">
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

                <div id="cards-grid">

                    {{-- Add card --}}
                    <a href="{{ route('contacto.create', $obra) }}" class="add-card">
                        <div class="add-card-icon"><i class="fas fa-user-plus"></i></div>
                        <span class="add-card-label">Agregar contacto</span>
                        <span class="add-card-sub">Nuevo registro</span>
                    </a>

                    {{-- Contact cards --}}
                    @foreach($contactos->reverse() as $contacto)
                    @php $initials = mb_strtoupper(mb_substr($contacto->nombre ?? '-', 0, 2)); @endphp
                    <a href="{{ route('contacto.edit', $contacto->id) }}"
                       class="contact-card"
                       style="animation-delay:{{ $loop->index * 0.04 }}s"
                       data-search="{{ strtolower($contacto->nombre . ' ' . ($contacto->tipo_contacto ?? '') . ' ' . ($contacto->telefono ?? '') . ' ' . ($contacto->email ?? '') . ' ' . ($contacto->observacion ?? '')) }}">

                        {{-- Avatar header --}}
                        <div class="card-avatar-header">
                            <div class="avatar-circle">{{ $initials }}</div>
                            <div class="card-name">{{ $contacto->nombre }}</div>
                            @if($contacto->tipo_contacto)
                            <span class="tipo-badge">{{ $contacto->tipo_contacto }}</span>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="card-details">
                            @if($contacto->telefono)
                            <div class="detail-row">
                                <i class="fas fa-phone"></i>
                                <span class="detail-label">Tel.</span>
                                <span class="detail-value">{{ $contacto->telefono }}</span>
                            </div>
                            @endif

                            @if($contacto->email)
                            <div class="detail-row">
                                <i class="fas fa-envelope"></i>
                                <span class="detail-label">Email</span>
                                <span class="detail-value">{{ $contacto->email }}</span>
                            </div>
                            @endif

                            @if($contacto->observacion)
                            <div class="detail-row">
                                <i class="fas fa-comment-alt"></i>
                                <span class="detail-label">Obs.</span>
                                <span class="detail-value">{{ Str::limit($contacto->observacion, 60) }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Presupuesto footer --}}
                        @if($contacto->presupuesto)
                        <div class="card-footer-row">
                            <span class="presup-label">{{ $contacto->presupuesto->clave }}</span>
                            <span class="presup-val">Gs. {{ number_format($contacto->presupuesto->monto_total, 0, '', '.') }}</span>
                        </div>
                        @endif

                    </a>
                    @endforeach

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
    const cards = document.querySelectorAll('#cards-grid .contact-card');
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