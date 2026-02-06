<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Contactos</title>
    @include('partials.head')
    <style>
        .budget-card { cursor: pointer; border-radius:10px; overflow:hidden; transition: transform .18s, box-shadow .18s; }
        .budget-card:hover { transform: translateY(-6px); box-shadow: 0 10px 25px rgba(16,24,40,0.08); }
        .budget-info { padding: 20px; }
        .budget-info h5 { margin-bottom: 10px; font-size:1.25rem; }
        .budget-meta { color:#6b7280; font-size:1rem; }
        .budget-card .preview { height: 440px; overflow: hidden; background:#fff; display: flex; align-items: center; justify-content: center; }
        .add-card { border: 2px dashed #2f8f4a; background: linear-gradient(180deg,#f0fff4,#e6ffed); transition: box-shadow .18s, transform .18s, border-color .18s; }
        .add-card:hover, .add-card:focus { box-shadow: 0 8px 32px rgba(47,143,74,0.13); border-color: #1e6b36; transform: scale(1.035) translateY(-4px); }
        @media (max-width: 992px) {
            .budget-card .preview { height: 360px; }
        }
        @media (max-width: 768px) {
            .budget-card .preview { height: 260px; }
            .budget-info h5 { font-size:1.05rem; }
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const cards = Array.from(document.querySelectorAll('.budget-card'));
        function filterCards() {
            const term = (searchInput.value || '').trim().toLowerCase();
            cards.forEach(card => {
                const nombre = card.querySelector('.budget-info h5')?.textContent?.toLowerCase() || '';
                const tipo = card.querySelector('.budget-info')?.querySelector('div.mb-1:nth-child(2)')?.textContent?.toLowerCase() || '';
                const tel = card.querySelector('.budget-info')?.querySelector('div.mb-1:nth-child(3)')?.textContent?.toLowerCase() || '';
                const email = card.querySelector('.budget-info')?.querySelector('div.mb-1:nth-child(4)')?.textContent?.toLowerCase() || '';
                const obs = card.querySelector('.budget-info')?.querySelector('div.mb-1:nth-child(5)')?.textContent?.toLowerCase() || '';
                const presupuesto = card.querySelector('.budget-info')?.querySelector('div.mb-1:nth-child(6)')?.textContent?.toLowerCase() || '';
                const text = nombre + ' ' + tipo + ' ' + tel + ' ' + email + ' ' + obs + ' ' + presupuesto;
                card.closest('.col-md-3').style.display = text.includes(term) ? '' : 'none';
            });
        }
        if (searchInput) {
            searchInput.addEventListener('input', filterCards);
        }
    });
    </script>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_ing')->first()->id ?? null)->where('ver', 1)->isEmpty())
        <script>
            window.location.href = "{{ url('/home') }}";
        </script>
    @endif
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
                            <h1 class="m-0">Listado de Contactos</h1>
                        </div>
                        <div class="col-md-6 col-12">
                            <form onsubmit="return false;" class="float-md-right w-100" autocomplete="off">
                                <div class="input-group">
                                    <input type="text" id="search" class="form-control" placeholder="Buscar contacto" aria-label="Buscar contacto">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <div class="titulo-box">
                                        <a href="{{ route('obras.show', $obra) }}" class="btn btn-light" title="Volver al listado"><i class="fas fa-arrow-left mr-2"></i></a>
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
                    <div class="row" id="cards-container">
                        <div class="col-md-3 mb-4">
                            <a href="{{ route('contacto.create', $obra) }}" class="card add-card" id="agregar-contacto-card" style="text-decoration:none; color:inherit;">
                                <div class="preview" style="display:flex; align-items:center; justify-content:center; background:linear-gradient(180deg,#f0fff4,#e6ffed); height:220px;">
                                    <div style="text-align:center;">
                                        <i class="fas fa-user-plus" style="font-size:48px; color:#2f8f4a;"></i>
                                        <div style="margin-top:8px; font-weight:600; color:#2f8f4a;">Agregar Contacto</div>
                                    </div>
                                </div>
                                <div class="budget-info">
                                    <h5 style="color:#2f8f4a; margin-bottom:8px;">Nuevo contacto</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="budget-meta">Crear</div>
                                        <div class="text-right">
                                            <div class="h5 mb-0">&nbsp;</div>
                                            <small class="text-muted">&nbsp;</small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @foreach ($contactos->reverse() as $contacto)
                        <div class="col-md-3 mb-4">
                            <!-- Tarjeta de contacto individual -->
                            <a href="{{ route('contacto.edit', $contacto->id) }}" style="text-decoration:none; color:inherit;">
                                <div class="card budget-card" data-contacto='@json($contacto)' data-search="{{ strtolower($contacto->nombre . ' ' . ($contacto->tipo_contacto ?? '') . ' ' . ($contacto->telefono ?? '') . ' ' . ($contacto->email ?? '')) }}">
                                    <div class="preview position-relative" style="height:120px; display:flex; align-items:center; justify-content:center; background:#f8f9fa;">
                                        <i class="fas fa-user" style="font-size:48px; color:#2f8f4a;"></i>
                                    </div>
                                    <div class="budget-info">
                                        <h5>{{ $contacto->nombre }}</h5>
                                        <div class="mb-1"><strong>Tipo:</strong> {{ $contacto->tipo_contacto ?? '-' }}</div>
                                        <div class="mb-1"><strong>Tel:</strong> {{ $contacto->telefono ?? '-' }}</div>
                                        <div class="mb-1"><strong>Email:</strong> {{ $contacto->email ?? '-' }}</div>
                                        <div class="mb-1"><strong>Obs.:</strong> {{ Str::limit($contacto->observacion, 40) }}</div>
                                        @if($contacto->presupuesto)
                                            <div class="mb-1"><strong>Presupuesto:</strong> {{ $contacto->presupuesto->clave }} <span class="text-muted">(Gs.{{ number_format($contacto->presupuesto->monto_total, 0, '', '.') }})</span></div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
        @include('partials.footer')
        </div>
        <!-- No se requiere modal para contactos -->
</body>
</html>
