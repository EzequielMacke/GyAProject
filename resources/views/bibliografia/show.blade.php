<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $bib->nombre }}</title>
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
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) { font-family: 'Plus Jakarta Sans', sans-serif; }

        .ph { padding: 1.75rem 0 1.5rem; display: flex; align-items: flex-end; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; }

        .btn { height: 38px; padding: 0 1rem; border-radius: 0.55rem; display: inline-flex; align-items: center; gap: 0.42rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.825rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); text-decoration: none; cursor: pointer; transition: all 0.14s; white-space: nowrap; }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; }

        /* Info card */
        .info-card { background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem; padding: 1.25rem 1.5rem; margin-bottom: 1.25rem; display: flex; gap: 2.5rem; flex-wrap: wrap; }
        .info-field { display: flex; flex-direction: column; gap: 0.25rem; }
        .info-label { font-size: 0.7rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .info-value { font-size: 0.92rem; font-weight: 600; color: var(--text); }
        .info-value.muted { font-weight: 500; color: var(--text2); }

        /* Contenido */
        .content-card { background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem; overflow: hidden; margin-bottom: 1.25rem; }
        .content-card-header { display: flex; align-items: center; gap: 0.5rem; padding: 0.9rem 1.35rem; background: var(--surface2); border-bottom: 1.5px solid var(--border); font-size: 0.82rem; font-weight: 700; color: var(--text2); }
        .content-card-header i { color: var(--accent); }
        .content-card-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.1rem; }

        /* Items de detalle */
        .detalle-block { display: flex; gap: 1rem; }
        .detalle-num { font-size: 0.72rem; font-weight: 700; color: var(--muted); min-width: 22px; padding-top: 0.15rem; }
        .detalle-inner { flex: 1; }
        .detalle-tipo { font-size: 0.68rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.3rem; }
        .detalle-texto { font-size: 0.88rem; color: var(--text); line-height: 1.6; white-space: pre-wrap; }
        .detalle-img-wrap { display: inline-block; }
        .detalle-img-wrap img { border-radius: 0.55rem; border: 1.5px solid var(--border); display: block; max-width: 100%; }
        .detalle-img-caption { font-size: 0.73rem; color: var(--muted); margin-top: 0.35rem; }

        .empty-content { text-align: center; padding: 2.5rem; color: var(--muted); font-size: 0.83rem; }
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
                            <a href="{{ route('bibliografia.index') }}">Bibliografías</a>
                            <i class="fas fa-chevron-right"></i>
                            <i class="fas fa-eye"></i> Ver
                        </div>
                        <h1 class="ph-title"><em>{{ $bib->nombre }}</em></h1>
                        <p class="ph-sub">{{ $bib->fuente }}</p>
                    </div>
                    <div class="ph-right">
                        @permiso('bib', 'editar')
                        <a href="{{ route('bibliografia.edit', $bib->id) }}" class="btn btn-primary">
                            <i class="fas fa-pencil-alt"></i> Editar
                        </a>
                        @endpermiso
                        <a href="{{ route('bibliografia.index') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                {{-- Datos generales --}}
                <div class="info-card">
                    <div class="info-field">
                        <span class="info-label">Nombre</span>
                        <span class="info-value">{{ $bib->nombre }}</span>
                    </div>
                    <div class="info-field">
                        <span class="info-label">Fuente</span>
                        <span class="info-value muted">{{ $bib->fuente }}</span>
                    </div>
                    @if($bib->usuario)
                    <div class="info-field">
                        <span class="info-label">Creado por</span>
                        <span class="info-value muted">{{ $bib->usuario->nombre ?? $bib->usuario->name ?? '—' }}</span>
                    </div>
                    @endif
                    <div class="info-field">
                        <span class="info-label">Elementos</span>
                        <span class="info-value">{{ $bib->detalles->count() }}</span>
                    </div>
                </div>

                {{-- Contenido --}}
                <div class="content-card">
                    <div class="content-card-header">
                        <i class="fas fa-layer-group"></i> Contenido
                    </div>
                    <div class="content-card-body">
                        @forelse($bib->detalles as $i => $detalle)
                        @php
                            $esImagen = in_array($detalle->elemento_plantilla_id, [6, 7, 8]);
                            $nombre   = $detalle->elementoPlantilla->nombre ?? '—';
                        @endphp
                        <div class="detalle-block">
                            <div class="detalle-num">{{ $i + 1 }}</div>
                            <div class="detalle-inner">
                                <div class="detalle-tipo">{{ $nombre }}</div>
                                @if($esImagen)
                                    @if($detalle->descripcion)
                                    <div class="detalle-img-wrap">
                                        <img src="{{ asset('storage/bibliografia/' . $detalle->descripcion) }}"
                                            alt="{{ $nombre }}"
                                            @if($detalle->tamanio) style="width: {{ $detalle->tamanio }}cm;" @endif>
                                        <div class="detalle-img-caption">
                                            <i class="fas fa-ruler-horizontal"></i>
                                            {{ $detalle->tamanio ? $detalle->tamanio . ' cm' : 'Sin ancho definido' }}
                                            &nbsp;·&nbsp; {{ $detalle->descripcion }}
                                        </div>
                                    </div>
                                    @else
                                    <span style="font-size:0.8rem;color:var(--muted);">Sin imagen cargada</span>
                                    @endif
                                @else
                                    <div class="detalle-texto">{{ $detalle->descripcion }}</div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="empty-content">
                            <i class="fas fa-layer-group" style="font-size:1.5rem;margin-bottom:0.5rem;display:block;"></i>
                            Esta bibliografía no tiene contenido cargado.
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>
</body>
</html>
