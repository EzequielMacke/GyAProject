<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Problema — {{ Str::limit($problema->descripcion, 50) }}</title>
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
            --accent-b: #1f5bbf;
            --orange:   #d9622a;
            --orange-s: #fff0eb;
            --green:    #1e9166;
            --green-s:  #e5f6f0;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* PAGE HEADER */
        .ph { padding: 1.75rem 0 1.5rem; display: flex; align-items: flex-end; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
        .ph-title em { font-style: normal; color: var(--orange); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        /* BUTTONS */
        .btn { height: 38px; padding: 0 1rem; border-radius: 0.55rem; display: inline-flex; align-items: center; gap: 0.42rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.825rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); text-decoration: none; cursor: pointer; transition: all 0.14s; white-space: nowrap; }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-sm { height: 30px; padding: 0 0.7rem; font-size: 0.75rem; border-radius: 0.45rem; }

        /* CARDS */
        .card { background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem; overflow: hidden; margin-bottom: 1.5rem; }
        .card-header { display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1.35rem; background: var(--surface2); border-bottom: 1.5px solid var(--border); }
        .card-title { font-size: 0.82rem; font-weight: 700; color: var(--text2); display: flex; align-items: center; gap: 0.5rem; text-transform: uppercase; letter-spacing: 0.04em; }
        .card-title i { font-size: 0.78rem; }
        .card-body { padding: 1.25rem 1.35rem; }

        /* META CHIP */
        .meta-row { display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap; margin-top: 0.6rem; }
        .meta-chip { display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; color: var(--muted); }
        .avatar { width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.58rem; font-weight: 700; flex-shrink: 0; }
        .avatar-orange { background: var(--orange-s); color: var(--orange); }
        .avatar-green  { background: var(--green-s);  color: var(--green); }

        /* DESCRIPTION */
        .desc-text { font-size: 0.975rem; font-weight: 600; color: var(--text); line-height: 1.55; }

        /* OBSERVACION */
        .obs-box { margin-top: 1rem; padding: 0.9rem 1rem; background: var(--surface2); border-radius: 0.55rem; border-left: 3px solid var(--border2); }
        .obs-label { font-size: 0.68rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.4rem; }
        .obs-text { font-size: 0.85rem; color: var(--text2); line-height: 1.6; white-space: pre-wrap; }

        /* FOTOS */
        .fotos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.85rem; }
        .foto-card { border: 1.5px solid var(--border); border-radius: 0.65rem; overflow: hidden; background: var(--surface2); }
        .foto-card img { width: 100%; aspect-ratio: 4/3; object-fit: cover; display: block; cursor: pointer; transition: opacity 0.14s; }
        .foto-card img:hover { opacity: 0.88; }
        .foto-nombre { padding: 0.35rem 0.55rem; font-size: 0.68rem; color: var(--muted); text-align: center; border-top: 1px solid var(--border); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* SOLUCIONES */
        .soluciones-stack { display: flex; flex-direction: column; gap: 1rem; }
        .solucion-card { border: 1.5px solid var(--border); border-radius: 0.75rem; overflow: hidden; }
        .solucion-card.inactiva { opacity: 0.55; }
        .solucion-header { display: flex; align-items: center; justify-content: space-between; padding: 0.7rem 1.1rem; background: var(--green-s); border-bottom: 1px solid #c3e8d9; }
        .solucion-num { font-size: 0.7rem; font-weight: 700; color: var(--green); text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.4rem; }
        .solucion-body { padding: 1rem 1.1rem; background: var(--surface); }
        .solucion-desc { font-size: 0.875rem; font-weight: 600; color: var(--text); line-height: 1.5; }
        .badge-inactiva { font-size: 0.65rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 0.3rem; background: #f5c2c2; color: #c0392b; text-transform: uppercase; letter-spacing: 0.04em; }

        /* EMPTY */
        .empty-inline { font-size: 0.8rem; color: var(--muted); font-style: italic; }

        /* SECTION DIVIDER */
        .section-label { font-size: 0.7rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.07em; margin: 1rem 0 0.6rem; display: flex; align-items: center; gap: 0.5rem; }
        .section-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }

        /* LIGHTBOX */
        .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.88); z-index: 2000; align-items: center; justify-content: center; cursor: zoom-out; }
        .lightbox.open { display: flex; }
        .lightbox img { max-width: 92vw; max-height: 88vh; border-radius: 0.5rem; box-shadow: 0 8px 48px rgba(0,0,0,0.5); }
        .lightbox-close { position: absolute; top: 1rem; right: 1.25rem; color: #fff; font-size: 1.5rem; cursor: pointer; opacity: 0.7; }
        .lightbox-close:hover { opacity: 1; }
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
                            <a href="{{ route('problemas.index') }}">Problemas</a>
                            <i class="fas fa-chevron-right"></i>
                            <i class="fas fa-exclamation-triangle"></i>
                            Vista general
                        </div>
                        <h1 class="ph-title">Vista <em>General</em></h1>
                        <p class="ph-sub">
                            {{ $problema->soluciones->count() }} solución/es &nbsp;·&nbsp;
                            {{ $problema->detalles->count() }} foto(s)
                        </p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('problemas.detalle', $problema->id) }}" class="btn btn-sm">
                            <i class="fas fa-folder-open"></i> Detalle
                        </a>
                        <a href="{{ route('problemas.index') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                {{-- PROBLEMA --}}
                <div class="card">
                    <div class="card-header">
                        <span class="card-title" style="color:var(--orange)"><i class="fas fa-exclamation-triangle" style="color:var(--orange)"></i> Problema</span>
                        <span style="font-size:0.75rem;color:var(--muted);">
                            {{ $problema->stamp ? \Carbon\Carbon::parse($problema->stamp)->format('d/m/Y H:i') : '' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <p class="desc-text">{{ $problema->descripcion }}</p>
                        <div class="meta-row">
                            <span class="meta-chip">
                                <span class="avatar avatar-orange">{{ strtoupper(substr($problema->usuario?->nombre ?? '?', 0, 2)) }}</span>
                                {{ $problema->usuario?->nombre ?? '—' }}
                            </span>
                        </div>

                        @if($problema->observacion)
                        <div class="obs-box">
                            <div class="obs-label"><i class="fas fa-sticky-note"></i> Observación</div>
                            <p class="obs-text">{{ $problema->observacion }}</p>
                        </div>
                        @endif

                        @if($problema->detalles->isNotEmpty())
                        <div class="section-label"><i class="fas fa-images"></i> Fotos del problema</div>
                        <div class="fotos-grid">
                            @foreach($problema->detalles as $detalle)
                            <div class="foto-card">
                                <img src="{{ asset('storage/problemas/' . $detalle->foto) }}"
                                     alt="{{ $detalle->foto }}"
                                     onclick="abrirLightbox(this.src)">
                                <div class="foto-nombre" title="{{ preg_replace('/^\d+_/', '', $detalle->foto) }}">
                                    {{ preg_replace('/^\d+_/', '', $detalle->foto) }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- SOLUCIONES --}}
                @if($problema->soluciones->isNotEmpty())
                <div class="card">
                    <div class="card-header">
                        <span class="card-title" style="color:var(--green)"><i class="fas fa-check-circle" style="color:var(--green)"></i> Soluciones</span>
                        <span style="font-size:0.75rem;color:var(--muted);">{{ $problema->soluciones->count() }} registradas</span>
                    </div>
                    <div class="card-body">
                        <div class="soluciones-stack">
                            @foreach($problema->soluciones as $i => $solucion)
                            <div class="solucion-card {{ $solucion->estado == 2 ? 'inactiva' : '' }}">
                                <div class="solucion-header">
                                    <span class="solucion-num">
                                        <i class="fas fa-check-circle"></i> Solución {{ $i + 1 }}
                                    </span>
                                    <div style="display:flex;align-items:center;gap:0.75rem;">
                                        @if($solucion->estado == 2)
                                        <span class="badge-inactiva">Inactiva</span>
                                        @endif
                                        <span class="meta-chip">
                                            <span class="avatar avatar-green">{{ strtoupper(substr($solucion->usuario?->nombre ?? '?', 0, 2)) }}</span>
                                            {{ $solucion->usuario?->nombre ?? '—' }}
                                        </span>
                                        <span class="meta-chip">
                                            <i class="fas fa-clock"></i>
                                            {{ $solucion->stamp ? \Carbon\Carbon::parse($solucion->stamp)->format('d/m/Y') : '—' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="solucion-body">
                                    <p class="solucion-desc">{{ $solucion->descripcion }}</p>

                                    @if($solucion->observacion)
                                    <div class="obs-box">
                                        <div class="obs-label"><i class="fas fa-sticky-note"></i> Observación</div>
                                        <p class="obs-text">{{ $solucion->observacion }}</p>
                                    </div>
                                    @endif

                                    @if($solucion->detalles->isNotEmpty())
                                    <div class="section-label"><i class="fas fa-images"></i> Fotos</div>
                                    <div class="fotos-grid">
                                        @foreach($solucion->detalles as $detalle)
                                        <div class="foto-card">
                                            <img src="{{ asset('storage/soluciones/' . $detalle->foto) }}"
                                                 alt="{{ $detalle->foto }}"
                                                 onclick="abrirLightbox(this.src)">
                                            <div class="foto-nombre" title="{{ preg_replace('/^\d+_/', '', $detalle->foto) }}">
                                                {{ preg_replace('/^\d+_/', '', $detalle->foto) }}
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- Lightbox --}}
<div class="lightbox" id="lightbox" onclick="cerrarLightbox()">
    <span class="lightbox-close"><i class="fas fa-times"></i></span>
    <img src="" id="lightbox-img" alt="">
</div>

<script>
    function abrirLightbox(src) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox').classList.add('open');
    }
    function cerrarLightbox() {
        document.getElementById('lightbox').classList.remove('open');
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarLightbox(); });
</script>
</body>
</html>
