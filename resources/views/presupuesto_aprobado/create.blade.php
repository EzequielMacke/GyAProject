<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Presupuesto Aprobado</title>
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
            --accent-b: #1f5bbf;
            --green:    #1e9166;
            --green-s:  #e5f6f0;
            --green-b:  #a8dcc9;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }

        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex; align-items: flex-end; justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .ph-crumb {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem;
        }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; word-break: break-word; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        .btn {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border); background: var(--surface);
            color: var(--text2); text-decoration: none; cursor: pointer;
            transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }

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
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.82rem; font-weight: 600; color: var(--text2);
        }
        .form-card-header i { color: var(--accent); font-size: 0.78rem; }
        .form-card-body { padding: 1.25rem; }

        .fields-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .field-label {
            display: block; font-size: 0.78rem; font-weight: 600;
            color: var(--text2); margin-bottom: 0.4rem;
        }
        .field-input {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.5rem 0.9rem;
            color: var(--text); width: 100%; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .field-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }
        .field-input::placeholder { color: var(--muted); }
        textarea.field-input { resize: vertical; min-height: 100px; }

        .error-list {
            background: #fef2f2; border: 1.5px solid #fca5a5;
            border-radius: 0.55rem; padding: 0.75rem 1rem;
            margin-bottom: 1rem; font-size: 0.82rem; color: #b91c1c;
        }
        .error-list ul { margin: 0; padding-left: 1.2rem; }

        /* PDF squares */
        .pdf-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }
        .pdf-drop-wrap { display: flex; flex-direction: column; gap: 0.4rem; }
        .pdf-square {
            border: 1.5px dashed var(--green-b);
            border-radius: 0.75rem;
            background: var(--green-s);
            height: 140px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 0.4rem; text-align: center; padding: 1rem;
            cursor: pointer;
            transition: border-color 0.15s, box-shadow 0.15s, transform 0.15s;
        }
        .pdf-square:hover { border-color: var(--green); box-shadow: 0 4px 14px rgba(30,145,102,0.15); transform: translateY(-2px); }
        .pdf-square.has-file { border-color: var(--green); border-style: solid; }
        .pdf-square.dragover { border-color: var(--green); background: #c9ede3; }
        .pdf-square.bounce { animation: pdfBounce 0.4s ease; }
        @keyframes pdfBounce { 0%,100% { transform: translateY(0); } 40% { transform: translateY(-5px); } }
        .pdf-square-icon { font-size: 1.5rem; color: var(--green); }
        .pdf-square-label { font-size: 0.78rem; font-weight: 600; color: var(--green); }
        .pdf-square-sub { font-size: 0.72rem; color: var(--muted); }
        .pdf-filename { font-size: 0.75rem; color: var(--text2); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 100%; }
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
                            @if($selectedObra)
                                <a href="{{ route('obras.show', $obra) }}">{{ $selectedObra->nombre }}</a>
                                <i class="fas fa-chevron-right"></i>
                                <a href="{{ route('presupuesto_aprobado.index', $obra) }}">Presupuestos</a>
                                <i class="fas fa-chevron-right"></i>
                            @endif
                            Nuevo
                        </div>
                        <h1 class="ph-title">Nuevo <em>presupuesto</em></h1>
                        <p class="ph-sub">
                            @if($selectedObra)
                                {{ $selectedObra->nombre }}
                            @else
                                Completá los datos para registrar un presupuesto aprobado
                            @endif
                        </p>
                    </div>
                    <div class="ph-right">
                        <button type="submit" form="form-create" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('presupuesto_aprobado.index', $obra) }}" class="btn">
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

                <form id="form-create" action="{{ route('presupuesto_aprobado.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="obra_id" value="{{ $obra ?? '' }}">

                    {{-- Datos del presupuesto --}}
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="fas fa-file-invoice-dollar"></i> Datos del presupuesto
                        </div>
                        <div class="form-card-body">
                            <div class="fields-grid">
                                <div>
                                    <label class="field-label" for="clave">Nombre</label>
                                    <input type="text" id="clave" name="clave" class="field-input"
                                           placeholder="Nombre del presupuesto" required
                                           value="{{ old('clave') }}">
                                </div>
                                <div>
                                    <label class="field-label" for="ubicacion">Ubicación</label>
                                    <input type="text" id="ubicacion" name="ubicacion" class="field-input"
                                           placeholder="Ubicación del presupuesto" required
                                           value="{{ old('ubicacion') }}">
                                </div>
                                <div>
                                    <label class="field-label" for="monto_total">Monto</label>
                                    <input type="text" id="monto_total" name="monto_total" class="field-input"
                                           placeholder="Ej: 1.500.000" required
                                           value="{{ old('monto_total') }}">
                                </div>
                                <div>
                                    <label class="field-label" for="tipo_trabajo">Tipo de trabajo</label>
                                    <select id="tipo_trabajo" name="tipo_trabajo" class="field-input" required>
                                        <option value="" disabled selected>Seleccionar…</option>
                                        @foreach (config('constantes.tipo_trabajo') as $codigo => $tipo)
                                            <option value="{{ $codigo }}" {{ old('tipo_trabajo') == $codigo ? 'selected' : '' }}>
                                                {{ $tipo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Archivos --}}
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="fas fa-paperclip"></i> Archivos adjuntos
                        </div>
                        <div class="form-card-body">
                            <div class="pdf-grid">
                                <div class="pdf-drop-wrap">
                                    <label class="field-label">Presupuesto (PDF)</label>
                                    <div class="pdf-square" id="sq-presupuesto" tabindex="0" role="button">
                                        <input type="file" name="presupuesto" id="presupuesto" accept="application/pdf" class="d-none" required>
                                        <div class="pdf-square-icon"><i class="fas fa-file-pdf"></i></div>
                                        <div class="pdf-square-label">Seleccionar PDF</div>
                                        <div class="pdf-square-sub">o arrastrá aquí</div>
                                    </div>
                                    <div class="pdf-filename" id="fn-presupuesto"></div>
                                </div>

                                <div class="pdf-drop-wrap">
                                    <label class="field-label">Nota de conformidad (PDF)</label>
                                    <div class="pdf-square" id="sq-conformidad" tabindex="0" role="button">
                                        <input type="file" name="conformidad" id="conformidad" accept="application/pdf" class="d-none">
                                        <div class="pdf-square-icon"><i class="fas fa-file-pdf"></i></div>
                                        <div class="pdf-square-label">Seleccionar PDF</div>
                                        <div class="pdf-square-sub">o arrastrá aquí</div>
                                    </div>
                                    <div class="pdf-filename" id="fn-conformidad"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Observaciones --}}
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="fas fa-sticky-note"></i> Observaciones
                        </div>
                        <div class="form-card-body">
                            <label class="field-label" for="observacion">Observación</label>
                            <textarea id="observacion" name="observacion" class="field-input"
                                      placeholder="Notas adicionales…">{{ old('observacion') }}</textarea>
                        </div>
                    </div>

                </form>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Formato de monto
    const montoInput = document.getElementById('monto_total');
    if (montoInput) {
        montoInput.addEventListener('input', function () {
            let v = this.value.replace(/\./g, '');
            if (!isNaN(v) && v !== '') this.value = Number(v).toLocaleString('de-DE');
        });
    }

    // PDF squares
    function setupPdfSquare(sqId, inputId, fnId) {
        const sq    = document.getElementById(sqId);
        const input = document.getElementById(inputId);
        const fn    = document.getElementById(fnId);
        if (!sq || !input) return;

        sq.addEventListener('click',   () => input.click());
        sq.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); } });
        sq.addEventListener('dragover', e => { e.preventDefault(); sq.classList.add('dragover'); });
        sq.addEventListener('dragleave', () => sq.classList.remove('dragover'));
        sq.addEventListener('drop', e => {
            e.preventDefault(); sq.classList.remove('dragover');
            if (e.dataTransfer.files?.length) { input.files = e.dataTransfer.files; update(); }
        });
        input.addEventListener('change', update);

        function update() {
            if (input.files?.[0]) {
                fn.textContent = input.files[0].name;
                sq.classList.add('has-file');
                sq.classList.remove('bounce'); void sq.offsetWidth; sq.classList.add('bounce');
                sq.querySelector('.pdf-square-label').textContent = 'Archivo seleccionado';
            }
        }
    }

    setupPdfSquare('sq-presupuesto', 'presupuesto', 'fn-presupuesto');
    setupPdfSquare('sq-conformidad', 'conformidad', 'fn-conformidad');
});
</script>
</body>
</html>
