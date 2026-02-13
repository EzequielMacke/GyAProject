<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Tabletas</title>
    @include('partials.head')
    <style>
        .tablet-card {
            cursor: pointer;
            border-radius:16px;
            overflow:hidden;
            transition: transform .18s, box-shadow .18s;
            background: #f8f9fa;
            box-shadow: 0 4px 24px 0 #2f8f4a33;
            min-height: 320px;
        }
        .tablet-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 16px 32px 0 #2f8f4a44;
        }
        .tablet-card.sin-devolucion {
            border: 2.5px solid #d32f2f;
            box-shadow: 0 4px 32px 0 #d32f2f44;
            background: #fff0f0;
        }
        .tablet-info {
            padding: 24px 18px 18px 18px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .tablet-info h5 {
            margin-bottom: 8px;
            font-size:1.35rem;
            color: #222;
            font-weight: 700;
            text-align: left;
        }
        .tablet-meta {
            font-size:1.05rem;
            border-radius:6px;
            padding: 6px 10px;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #222;
            background: #fff;
        }
        .tablet-meta strong { min-width: 70px; text-align:right; color:#666; }
        .preview {
            height: 140px;
            display:flex;
            align-items:center;
            justify-content:center;
            background: #e6ffe6;
            box-shadow: 0 0 32px 0 #2f8f4a33;
        }
        .preview i {
            font-size:56px;
            color:#2f8f4a;
            /* filter: drop-shadow(0 0 8px #2f8f4a); */
        }
        .tablet-card.sin-devolucion .preview i {
            color: #d32f2f;
        }
        .icon-meta {
            margin-right: 8px;
            font-size: 1.2em;
            opacity: 0.85;
        }
        @media (max-width: 992px) {
            .tablet-card .preview { height: 360px; }
        }
        @media (max-width: 768px) {
            .tablet-card .preview { height: 260px; }
            .tablet-info h5 { font-size:1.05rem; }
        }
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
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3" style="background:#f8f9fa; border-radius:12px; padding:18px 12px;">
                                <div class="d-flex align-items-center gap-2">
                                    <h1 class="m-0 me-3">Gestión de Tablets</h1>
                                    <form onsubmit="return false;" class="d-inline-block" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" id="search" class="form-control" placeholder="Buscar tableta" aria-label="Buscar tableta">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('home') }}" class="btn btn-light" title="Volver al listado"><i class="fas fa-arrow-left mr-2"></i></a>
                                    <a href="{{ route('tabletas.create') }}" class="btn btn-success" id="agregar-tablet-btn"><i class="fas fa-plus"></i> Agregar Tableta</a>
                                    <a href="{{ route('tabletas.generarQrs') }}" class="btn btn-secondary" id="generar-qr-btn"><i class="fas fa-qrcode"></i> Generar código QR</a>
                                </div>
                            </div>
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
                    <div class="tablet-list-group row mt-2">
                        @if ($tabletas->isEmpty())
                            <div class="alert alert-info text-center mt-4">No hay tablets registradas.</div>
                        @else
                            @foreach ($tabletas->reverse() as $tableta)
                                @php
                                    $ultimoUso = $tabletausos->where('tableta_id', $tableta->id)->sortByDesc('id')->first();
                                    $sinDevolucion = $ultimoUso && !$ultimoUso->fecha_devolucion;
                                @endphp
                                <div class="col-md-3 mb-4">
                                    <a href="#" style="text-decoration:none; color:inherit;">
                                        <div class="card tablet-card{{ $sinDevolucion ? ' sin-devolucion' : '' }}" data-tableta='@json($tableta)'>
                                            <div class="preview position-relative" style="height:120px; display:flex; align-items:center; justify-content:center; background:#f8f9fa;">
                                                <i class="fas fa-tablet-alt" style="font-size:48px; color:{{ $sinDevolucion ? '#d32f2f' : '#2f8f4a' }};"></i>
                                            </div>
                                            <div class="tablet-info">
                                                <h5>{{ $tableta->clave }} - {{ $tableta->nombre }}</h5>
                                                @if($sinDevolucion && $ultimoUso && $ultimoUso->usuario_id)
                                                    @php
                                                        $usuario = $ultimoUso->usuario_id ? App\Models\Usuarios::find($ultimoUso->usuario_id) : null;
                                                    @endphp
                                                    <div class="tablet-meta" style="background:#fff7f7; color:#b71c1c; font-size:0.98em;">
                                                        <span class="icon-meta"><i class="fas fa-user"></i></span>
                                                        <strong>Retirado por:</strong>
                                                        {{ $usuario ? $usuario->nombre : 'Usuario desconocido' }}
                                                        <span style="margin-left:8px;"><i class="fas fa-calendar-alt"></i> {{ $ultimoUso->fecha_retiro ? \Carbon\Carbon::parse($ultimoUso->fecha_retiro)->format('d/m/Y') : '' }}</span>
                                                    </div>
                                                @endif
                                                @if (!empty($tableta->modelo))
                                                    <div class="tablet-meta modelo"><span class="icon-meta"><i class="fas fa-microchip"></i></span><strong>Modelo:</strong> {{ $tableta->modelo }}</div>
                                                @endif
                                                @if (!empty($tableta->serie))
                                                    <div class="tablet-meta serie"><span class="icon-meta"><i class="fas fa-barcode"></i></span><strong>Serie:</strong> {{ $tableta->serie }}</div>
                                                @endif
                                                @if (!empty($tableta->sim))
                                                    <div class="tablet-meta sim"><span class="icon-meta"><i class="fas fa-sim-card"></i></span><strong>SIM:</strong> {{ $tableta->sim }}</div>
                                                @endif
                                                @if (!empty($tableta->observacion))
                                                    <div class="tablet-meta observacion"><span class="icon-meta"><i class="fas fa-comment-dots"></i></span><strong>Obs.:</strong> {{ Str::limit($tableta->observacion, 40) }}</div>
                                                @endif
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
