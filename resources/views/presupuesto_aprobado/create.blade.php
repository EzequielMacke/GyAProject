<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Presupuesto Aprobado</title>
    @include('partials.head')
    
    <style>
        body {
            background: #f5f6fa;
        }
        .card-custom {
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            background: #fff;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .form-control, .form-select {
            border-radius: 10px;
        }
        .input-group-text {
            border-radius: 10px 0 0 10px;
        }
        /* Eliminar estilos custom file para usar el input estándar */
        .btn-primary, .btn-warning {
            border-radius: 10px;
        }
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
            margin-top: 20px;
        }

        /* PDF square inputs (actualizado: apaisado y centrado, más grandes) */
        .pdf-square-grid {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            justify-content: center; /* centrar horizontalmente */
        }
        .pdf-square {
            width: 360px; /* más ancho que alto */
            height: 180px;
            border-radius: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #f6fff6 100%);
            border: 2px solid rgba(76,175,80,0.55); /* borde verde */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 14px;
            cursor: pointer;
            transition: transform 0.16s ease, box-shadow 0.16s ease, border-color 0.16s ease;
            box-shadow: 0 6px 18px rgba(76,175,80,0.08);
        }
        .pdf-square:focus {
            outline: none;
            box-shadow: 0 0 0 10px rgba(76,175,80,0.12);
        }
        .pdf-square:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 36px rgba(76,175,80,0.16);
        }
        .pdf-square.dragover {
            border-color: #43a047;
            background: linear-gradient(180deg, #f1fff3 0%, #e9fff0 100%);
        }
        .pdf-square .plus { font-size: 22px; color: #43a047; margin-bottom: 8px; }
        .pdf-square .icon { font-size: 48px; color: #d32f2f; margin-bottom: 8px; }
        .pdf-square .label { font-size: 1rem; color: #2e7d32; font-weight:700; }
        .pdf-square .filename { font-size: 0.95rem; color: #333; margin-top: 10px; word-break: break-word; max-width: 320px; }
        .pdf-square.has-file { border-color: rgba(56,142,60,0.9); box-shadow: 0 14px 36px rgba(76,175,80,0.14); }
        .pdf-square.bounce { animation: bounce 420ms ease; }
        @keyframes bounce { 0% { transform: translateY(-6px) scale(1); } 50% { transform: translateY(-3px) scale(1.02); } 100% { transform: translateY(0) scale(1); } }

        /* Responsive: en pantallas pequeñas apilar verticalmente y que el título pase encima */
        @media (max-width: 768px) {
            .pdf-square-grid { flex-direction: column; gap: 1rem; }
            .pdf-square { width: 90%; max-width: 520px; height: 160px; }
            .pdf-square .icon { font-size: 42px; }
            .pdf-square .label { font-size: 0.98rem; }
            .pdf-square .filename { max-width: 420px; font-size: 0.9rem; }
        }

        /* Más pequeño en móviles estrechos */
        @media (max-width: 480px) {
            .pdf-square { width: 96%; height: 150px; }
            .pdf-square .icon { font-size: 36px; }
            .pdf-square .label { font-size: 0.9rem; }
            .pdf-square .filename { font-size: 0.85rem; }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const montoInput = document.getElementById('monto_total');
            if (montoInput) {
                montoInput.addEventListener('input', function() {
                    let value = montoInput.value.replace(/\./g, '');
                    if (!isNaN(value) && value !== '') {
                        montoInput.value = Number(value).toLocaleString('de-DE');
                    }
                });
            }

            // Set up interactive PDF squares
            // NOTE: No file size limit is enforced here (user can select any file size);
            // server-side validation should handle limits if needed.
            function setupPdfSquare(sqId, inputId, fnId) {
                const sq = document.getElementById(sqId);
                const input = document.getElementById(inputId);
                const fn = document.getElementById(fnId);
                if (!sq || !input) return;

                const openFile = () => input.click();
                sq.addEventListener('click', openFile);
                sq.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        openFile();
                    }
                });
                sq.addEventListener('dragover', function(e) { e.preventDefault(); sq.classList.add('dragover'); });
                sq.addEventListener('dragleave', function() { sq.classList.remove('dragover'); });
                sq.addEventListener('drop', function(e) {
                    e.preventDefault();
                    sq.classList.remove('dragover');
                    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                        input.files = e.dataTransfer.files;
                        update();
                    }
                });
                input.addEventListener('change', update);

                function update() {
                    if (input.files && input.files[0]) {
                        fn.textContent = input.files[0].name;
                        sq.classList.add('has-file');
                        sq.classList.remove('bounce'); void sq.offsetWidth; sq.classList.add('bounce');
                        // hide helper label when there is a file
                        const lab = sq.querySelector('.label'); if (lab) lab.style.display = 'none';
                    } else {
                        fn.textContent = '';
                        sq.classList.remove('has-file');
                        const lab = sq.querySelector('.label'); if (lab) lab.style.display = 'block';
                    }
                }
            }

            setupPdfSquare('sq-presupuesto', 'presupuesto', 'fn-presupuesto');
            setupPdfSquare('sq-conformidad', 'conformidad', 'fn-conformidad');
        });
    </script>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_ing')->first()->id ?? null)->where('agregar', 1)->isEmpty())
        <script>
            window.location.href = "{{ url('/home') }}";
        </script>
    @endif
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')
        <div class="content-wrapper" style="min-height: 100vh; background: transparent;">
            <div class="container-fluid">
                <div class="card card-custom p-4 w-100" style="min-width:0;">
                    <form action="{{ route('presupuesto_aprobado.store') }}" method="POST" enctype="multipart/form-data" class="w-100">
                        <div class="row mb-3">
                            <div class="col-8">
                                <h2 class="mb-0">Cargar Presupuesto Aprobado</h2>
                            </div>
                            <div class="col-4 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Guardar</button>
                                <a href="{{ route('presupuesto_aprobado.index', $obra) }}" class="btn btn-warning px-4 ms-2" id="volver-btn"><i class="fas fa-arrow-left me-2"></i>Volver</a>
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
                        <input type="hidden" name="obra_id" value="{{ $obra ?? '' }}">
                        <!-- Campos de usuario y fecha eliminados -->
                        <div class="form-section-title">Datos del presupuesto</div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label for="clave" class="form-label">Nombre del presupuesto</label>
                                <input type="text" name="clave" class="form-control" id="clave" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="ubicacion" class="form-label">Ubicación del presupuesto</label>
                                <input type="text" name="ubicacion" class="form-control" id="ubicacion" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label for="tipo_trabajo" class="form-label">Tipo de trabajo</label>
                                <select name="tipo_trabajo" class="form-select" id="tipo_trabajo" required>
                                    <option value="" disabled selected>Seleccionar tipo de trabajo</option>
                                    @foreach (config('constantes.tipo_trabajo') as $codigo => $tipo_trabajo)
                                        <option value="{{ $codigo }}">{{ $tipo_trabajo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Campo Obra eliminado -->
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label for="monto_total" class="form-label">Monto del trabajo</label>
                                <input type="text" name="monto_total" class="form-control" id="monto_total" required>
                            </div>
                        </div>
                        <div class="form-section-title">Archivos adjuntos</div>
                        <div class="row mb-3">
                            <div class="col-12 mb-2 d-flex justify-content-center">
                                <div class="pdf-square-grid">
                                    <div class="d-flex flex-column align-items-center">
                                        <label class="form-label mb-1">Presupuesto (PDF)</label>
                                        <div class="pdf-square" id="sq-presupuesto" tabindex="0" role="button" aria-label="Seleccionar presupuesto PDF">
                                            <input type="file" name="presupuesto" id="presupuesto" accept="application/pdf" class="d-none" required>
                                            <div class="plus"><i class="fas fa-plus-circle"></i></div>
                                            <div class="icon"><i class="fas fa-file-pdf"></i></div>
                                            <div class="label">Suelta aquí<br>o selecciona tu archivo</div>
                                            <div class="filename" id="fn-presupuesto"></div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-center">
                                        <label class="form-label mb-1">Nota de conformidad (PDF)</label>
                                        <div class="pdf-square" id="sq-conformidad" tabindex="0" role="button" aria-label="Seleccionar nota de conformidad PDF">
                                            <input type="file" name="conformidad" id="conformidad" accept="application/pdf" class="d-none">
                                            <div class="plus"><i class="fas fa-plus-circle"></i></div>
                                            <div class="icon"><i class="fas fa-file-pdf"></i></div>
                                            <div class="label">Suelta aquí<br>o selecciona tu archivo</div>
                                            <div class="filename" id="fn-conformidad"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="form-section-title">Observaciones</div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="observacion" class="form-label">Observación</label>
                                <textarea name="observacion" class="form-control" id="observacion" rows="4"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials.footer')
    </div>
</body>
</html>
