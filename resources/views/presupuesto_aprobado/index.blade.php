<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Presupuestos Aprobados</title>
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
                // Buscar solo en los campos visibles: nombre, tipo, precio y fecha
                const nombre = card.querySelector('.budget-info h5')?.textContent?.toLowerCase() || '';
                const tipo = card.querySelector('.budget-meta')?.textContent?.toLowerCase() || '';
                const precio = card.querySelector('.text-right .h5')?.textContent?.toLowerCase() || '';
                const fecha = card.querySelector('.text-right small')?.textContent?.toLowerCase() || '';
                const text = nombre + ' ' + tipo + ' ' + precio + ' ' + fecha;
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
                            <h1 class="m-0">Listado de Presupuesto</h1>
                        </div>
                        <div class="col-md-6 col-12">
                            <form onsubmit="return false;" class="float-md-right w-100" autocomplete="off">
                                <div class="input-group">
                                    <input type="text" id="search" class="form-control" placeholder="Buscar presupuesto" aria-label="Buscar presupuesto">
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
                            <a href="{{ route('presupuesto_aprobado.create', $obra) }}" class="card add-card" id="agregar-presupuesto-card" style="text-decoration:none; color:inherit;">
                                <div class="preview" style="display:flex; align-items:center; justify-content:center; background:linear-gradient(180deg,#f0fff4,#e6ffed); height:440px;">
                                    <div style="text-align:center;">
                                        <i class="fas fa-plus-circle" style="font-size:48px; color:#2f8f4a;"></i>
                                        <div style="margin-top:8px; font-weight:600; color:#2f8f4a;">Agregar Presupuesto</div>
                                    </div>
                                </div>
                                <div class="budget-info">
                                    <h5 style="color:#2f8f4a; margin-bottom:8px;">Nuevo presupuesto</h5>
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
                        @foreach ($presupuestos->reverse() as $presupuesto)
                        <div class="col-md-3 mb-4">
                            <!-- Tarjeta de presupuesto individual -->
                            <a href="{{ route('presupuesto_aprobado.edit', $presupuesto->id) }}" style="text-decoration:none; color:inherit;">
                                <div class="card budget-card" data-presupuesto='@json($presupuesto)' data-search="{{ strtolower($presupuesto->clave . ' ' . ($presupuesto->obra->nombre ?? 'Pendiente') . ' ' . (\Illuminate\Support\Arr::get(config('constantes.tipo_trabajo'), $presupuesto->tipo_trabajo, ''))) }}">
                                    <div class="preview position-relative">
                                        <!-- Vista previa PDF usando iframe -->
                                        <iframe src="{{ asset('storage/' . str_replace('public/', '', $presupuesto->presupuesto)) }}" width="100%" height="100%" style="border:none; min-height:180px; background:#fff;" class="pdf-preview-iframe" data-pdf-full="{{ asset('storage/' . str_replace('public/', '', $presupuesto->presupuesto)) }}"></iframe>
                                        <button type="button" class="btn btn-light btn-sm position-absolute" style="top:10px; right:10px; z-index:2; border-radius:50%; box-shadow:0 2px 8px rgba(0,0,0,0.08);" title="Pantalla completa" onclick="event.preventDefault(); event.stopPropagation(); abrirPdfModal('{{ asset('storage/' . str_replace('public/', '', $presupuesto->presupuesto)) }}')">
                                            <i class="fas fa-expand"></i>
                                        </button>
                                    </div>
                                    <div class="budget-info">
                                        <h5>{{ $presupuesto->clave }}</h5>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="budget-meta">{{ config('constantes.tipo_trabajo')[$presupuesto->tipo_trabajo] ?? 'Desconocido' }}</div>
                                            <div class="text-right">
                                                <div class="h5 mb-0">{{ number_format($presupuesto->monto_total, 0, '', '.') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($presupuesto->fecha_carga)->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
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
        <!-- Modal para PDF grande -->
        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfModalLabel">Vista de Presupuesto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="height:80vh;">
                        <iframe id="pdfModalIframe" src="" width="100%" height="100%" style="border:none; min-height:500px;"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <script>
        function abrirPdfModal(src) {
            var modal = document.getElementById('pdfModal');
            var modalIframe = document.getElementById('pdfModalIframe');
            if (modal && modalIframe) {
            modalIframe.src = src;
            if (window.jQuery && $(modal).modal) {
                $(modal).modal('show');
            } else {
                modal.style.display = 'block';
                modal.classList.add('show');
                modal.setAttribute('aria-modal', 'true');
                modal.removeAttribute('aria-hidden');
            }
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Cerrar modal manual si no hay Bootstrap
            document.getElementById('pdfModal')?.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                this.style.display = 'none';
                this.classList.remove('show');
                this.setAttribute('aria-hidden', 'true');
            }
            });
        });
        </script>
</body>
</html>
