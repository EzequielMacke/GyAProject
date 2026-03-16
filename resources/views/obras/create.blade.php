<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Obra</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
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

        /* ── Form card ── */
        .form-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 1rem;
        }

        .form-card-header {
            padding: 0.85rem 1.25rem;
            border-bottom: 1.5px solid var(--border);
            background: var(--surface2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text2);
        }

        .form-card-header i { color: var(--accent); font-size: 0.78rem; }

        .form-card-body { padding: 1.25rem; }

        /* ── Fields ── */
        .field-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text2);
            margin-bottom: 0.4rem;
        }

        .field-input {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            padding: 0.5rem 0.9rem;
            color: var(--text);
            width: 100%;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .field-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }

        .field-input::placeholder { color: var(--muted); }

        textarea.field-input { resize: vertical; min-height: 100px; }

        .field-hint {
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: 0.35rem;
        }

        .field-readonly {
            background: var(--surface2);
            color: var(--muted);
            cursor: default;
        }

        /* ── Map ── */
        #map {
            height: 280px;
            border-radius: 0.55rem;
            border: 1.5px solid var(--border);
            margin-bottom: 0.6rem;
        }

        /* ── Grid ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
        }

        /* ── Errors ── */
        .error-list {
            background: #fef2f2;
            border: 1.5px solid #fca5a5;
            border-radius: 0.55rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.82rem;
            color: #b91c1c;
        }

        .error-list ul { margin: 0; padding-left: 1.2rem; }
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
                            <a href="{{ route('obras.index') }}" style="color:inherit;text-decoration:none;">Obras</a>
                            <i class="fas fa-chevron-right"></i> Nueva
                        </div>
                        <h1 class="ph-title">Nueva <em>obra</em></h1>
                        <p class="ph-sub">Completá los datos para registrar una nueva obra</p>
                    </div>
                    <div class="ph-right">
                        <button type="submit" form="form-create" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('obras.index') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @if ($errors->any())
                <div class="error-list">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form id="form-create" action="{{ route('obras.store') }}" method="POST">
                    @csrf

                    <div class="form-grid">

                        {{-- Datos principales --}}
                        <div class="form-card">
                            <div class="form-card-header">
                                <i class="fas fa-hard-hat"></i> Datos principales
                            </div>
                            <div class="form-card-body" style="display:flex;flex-direction:column;gap:1rem;">
                                <div>
                                    <label class="field-label" for="nombre">Nombre de la obra</label>
                                    <input type="text" id="nombre" name="nombre" class="field-input"
                                           placeholder="Ej: Obra Central" required
                                           value="{{ old('nombre') }}">
                                </div>
                                <div>
                                    <label class="field-label" for="observacion">Observación</label>
                                    <textarea id="observacion" name="observacion" class="field-input"
                                              placeholder="Detalles adicionales de la obra">{{ old('observacion') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Ubicación --}}
                        <div class="form-card">
                            <div class="form-card-header">
                                <i class="fas fa-map-marker-alt"></i> Ubicación
                            </div>
                            <div class="form-card-body">
                                <div id="map"></div>
                                <input type="hidden" name="direccion" id="direccion">
                                <label class="field-label">Enlace generado</label>
                                <input type="text" id="direccion_link" class="field-input field-readonly"
                                       placeholder="Hacé clic en el mapa para seleccionar la ubicación" readonly>
                                <p class="field-hint"><i class="fas fa-info-circle"></i> Hacé clic en el mapa para marcar la ubicación. El enlace se genera automáticamente.</p>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([-25.3167, -57.5667], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);
        var marker;
        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker(e.latlng).addTo(map);
            var lat = e.latlng.lat.toFixed(6);
            var lng = e.latlng.lng.toFixed(6);
            var link = 'https://www.google.com/maps?q=' + lat + ',' + lng;
            document.getElementById('direccion').value = link;
            document.getElementById('direccion_link').value = link;
        });
    });
</script>
</body>
</html>
