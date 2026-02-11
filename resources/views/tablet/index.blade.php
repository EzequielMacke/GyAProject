<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Tabletas</title>
    @include('partials.head')
    <style>
        .tablet-card { cursor: pointer; border-radius:10px; overflow:hidden; transition: transform .18s, box-shadow .18s; }
        .tablet-card:hover { transform: translateY(-6px); box-shadow: 0 10px 25px rgba(16,24,40,0.08); }
        .tablet-info { padding: 20px; }
        .tablet-info h5 { margin-bottom: 10px; font-size:1.25rem; }
        .tablet-meta { color:#6b7280; font-size:1rem; }
        .add-card { border: 2px dashed #2f8f4a; background: linear-gradient(180deg,#f0fff4,#e6ffed); transition: box-shadow .18s, transform .18s, border-color .18s; }
        .add-card:hover, .add-card:focus { box-shadow: 0 8px 32px rgba(47,143,74,0.13); border-color: #1e6b36; transform: scale(1.035) translateY(-4px); }
        @media (max-width: 992px) {
            .tablet-card .preview { height: 360px; }
        }
        @media (max-width: 768px) {
            .tablet-card .preview { height: 260px; }
            .tablet-info h5 { font-size:1.05rem; }
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const cards = Array.from(document.querySelectorAll('.tablet-card'));
        function filterCards() {
            const term = (searchInput.value || '').trim().toLowerCase();
            cards.forEach(card => {
                const nombre = card.querySelector('.tablet-info h5')?.textContent?.toLowerCase() || '';
                const modelo = card.querySelector('.tablet-meta.modelo')?.textContent?.toLowerCase() || '';
                const serie = card.querySelector('.tablet-meta.serie')?.textContent?.toLowerCase() || '';
                const sim = card.querySelector('.tablet-meta.sim')?.textContent?.toLowerCase() || '';
                const estado = card.querySelector('.tablet-meta.estado')?.textContent?.toLowerCase() || '';
                const obs = card.querySelector('.tablet-meta.observacion')?.textContent?.toLowerCase() || '';
                const text = nombre + ' ' + modelo + ' ' + serie + ' ' + sim + ' ' + estado + ' ' + obs;
                card.closest('.col-md-3').style.display = text.includes(term) ? '' : 'none';
            });
        }
        if (searchInput) {
            searchInput.addEventListener('input', filterCards);
        }
    });
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-end">
                        <div class="col-md-6 col-12 d-flex align-items-center">
                            <h1 class="m-0">Gestión de Tablets</h1>
                        </div>
                        <div class="col-md-6 col-12">
                            <form onsubmit="return false;" class="float-md-right w-100" autocomplete="off">
                                <div class="input-group">
                                    <input type="text" id="search" class="form-control" placeholder="Buscar tableta" aria-label="Buscar tableta">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <div class="titulo-box">
                                        <a href="{{ route("home") }}" class="btn btn-light" title="Volver al listado"><i class="fas fa-arrow-left mr-2"></i></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="mb-3 d-flex gap-2">
                        <a href="{{ route('tabletas.create') }}" class="btn btn-success me-2" id="agregar-tablet-btn"><i class="fas fa-plus"></i> Agregar Tableta</a>
                        <a href="{{ route('tabletas.generarQrs') }}" class="btn btn-secondary" id="generar-qr-btn"><i class="fas fa-qrcode"></i> Generar código QR</a>
                    </div>
                        @if ($tabletas->isEmpty())
                            <div class="alert alert-info text-center mt-4">No hay tablets registradas.</div>
                        @else
                            @foreach ($tabletas->reverse() as $tableta)
                            <div class="col-md-3 mb-4">
                                <a href="#" style="text-decoration:none; color:inherit;">
                                    <div class="card tablet-card" data-tableta='@json($tableta)'>
                                        <div class="preview position-relative" style="height:120px; display:flex; align-items:center; justify-content:center; background:#f8f9fa;">
                                            <i class="fas fa-tablet-alt" style="font-size:48px; color:#2f8f4a;"></i>
                                        </div>
                                        <div class="tablet-info">
                                            <h5>{{ $tableta->nombre }}</h5>
                                            <div class="tablet-meta modelo"><strong>Modelo:</strong> {{ $tableta->modelo ?? '-' }}</div>
                                            <div class="tablet-meta serie"><strong>Serie:</strong> {{ $tableta->serie ?? '-' }}</div>
                                            <div class="tablet-meta sim"><strong>SIM:</strong> {{ $tableta->sim ?? '-' }}</div>
                                            <div class="tablet-meta estado"><strong>Estado:</strong> {{ $tableta->estado ?? '-' }}</div>
                                            <div class="tablet-meta observacion"><strong>Obs.:</strong> {{ Str::limit($tableta->observacion, 40) }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </section>
        </div>
        @include('partials.footer')
        </div>
</body>
</html>
