<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Obra</title>
    @include('partials.head')
    <style>
        body { background: #f5f6fa; }
        .card-custom { border-radius: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); background: #fff; margin-top: 30px; margin-bottom: 30px; }
        .form-control, .form-select { border-radius: 10px; }
        .input-group-text { border-radius: 10px 0 0 10px; }
        .btn-primary, .btn-warning { border-radius: 10px; }
        .form-section-title { font-size: 1.1rem; font-weight: 600; color: #495057; margin-bottom: 10px; margin-top: 20px; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')
        <div class="content-wrapper" style="min-height: 100vh; background: transparent;">
            <div class="container-fluid">
                <div class="card card-custom p-4 w-100" style="min-width:0;">
                    <form action="{{ route('obras.update', $obra->id) }}" method="POST" class="w-100">
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-8">
                                <h2 class="mb-0">Agregar Obra</h2>
                            </div>
                            <div class="col-4 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Guardar</button>
                                <a href="{{ route('obras.show', $obra->id) }}" class="btn btn-warning px-4 ms-2" id="volver-btn"><i class="fas fa-arrow-left me-2"></i>Volver</a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @csrf
                        <div class="form-section-title">Datos principales</div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label for="nombre" class="form-label">Nombre de la obra</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    <input type="text" name="nombre" class="form-control" id="nombre" required placeholder="Ej: Obra Central" value="{{ old('nombre', $obra->nombre) }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="direccion" class="form-label">Ubicación en el mapa</label>
                                <div id="map" style="height: 250px; border-radius: 10px; margin-bottom: 10px;"></div>
                                <input type="hidden" name="direccion" id="direccion" value="{{ old('direccion', $obra->direccion) }}">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                                    <input type="text" class="form-control" id="direccion_link" placeholder="El link se generará automáticamente" readonly value="{{ old('direccion', $obra->direccion) }}">
                                </div>
                                <small class="text-muted">Haz clic en el mapa para seleccionar la ubicación. El enlace se generará automáticamente.</small>
                            </div>
                        </div>
                        <div class="form-section-title">Observaciones</div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="observacion" class="form-label">Observación</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                    <textarea name="observacion" class="form-control" id="observacion" rows="4" placeholder="Ej: Detalles adicionales de la obra">{{ old('observacion', $obra->observacion) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials.footer')
    </div>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').setView([-25.3167, -57.5667], 11); // Departamento Central, Paraguay por defecto
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            var marker;
            // Si hay dirección guardada, extraer lat/lng y mostrar marker
            var direccion = document.getElementById('direccion').value;
            if (direccion && direccion.includes('maps?q=')) {
                var match = direccion.match(/maps\\?q=([-0-9.]+),([-0-9.]+)/);
                if (match) {
                    var lat = parseFloat(match[1]);
                    var lng = parseFloat(match[2]);
                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 14);
                }
            }
            map.on('click', function(e) {
                if (marker) { map.removeLayer(marker); }
                marker = L.marker(e.latlng).addTo(map);
                var lat = e.latlng.lat.toFixed(6);
                var lng = e.latlng.lng.toFixed(6);
                var gmapsLink = `https://www.google.com/maps?q=${lat},${lng}`;
                document.getElementById('direccion').value = gmapsLink;
                document.getElementById('direccion_link').value = gmapsLink;
            });
        });
    </script>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
</body>
</html>
