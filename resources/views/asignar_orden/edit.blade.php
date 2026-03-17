<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Orden — {{ $presupuesto->clave }}</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
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
            --green:    #1e9166;
            --green-s:  #e5f6f0;
            --orange:   #d97706;
            --orange-s: #fef3c7;
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
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
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
        .btn-primary { background: var(--accent); border-color: var(--accent-b); color: #fff; }
        .btn-primary:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }

        /* FORM CARD */
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
            display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
            font-size: 0.82rem; font-weight: 600; color: var(--text2);
        }
        .form-card-header i { color: var(--accent); font-size: 0.78rem; }
        .form-card-body { padding: 1.25rem; }

        /* ESTADO BADGE */
        .estado-badge {
            font-size: 0.68rem; font-weight: 700; padding: 0.22rem 0.65rem; border-radius: 99px;
        }
        .badge-danger    { background: #fee2e2; color: #dc2626; }
        .badge-warning   { background: var(--orange-s); color: var(--orange); }
        .badge-primary   { background: var(--accent-s); color: var(--accent); }
        .badge-success   { background: var(--green-s); color: var(--green); }
        .badge-secondary { background: var(--surface2); color: var(--muted); }

        /* FIELDS */
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
        .field-input.mono { font-family: 'DM Mono', monospace; font-size: 0.88rem; font-weight: 600; }

        /* READ-ONLY DISPLAY */
        .field-display {
            font-size: 0.875rem; font-weight: 600; color: var(--text);
            background: var(--surface2); border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.5rem 0.9rem;
            width: 100%;
        }
        .field-display.mono {
            font-family: 'DM Mono', monospace; font-size: 0.82rem; color: var(--accent);
        }
        .field-display.money {
            font-family: 'DM Mono', monospace; font-size: 0.82rem; color: var(--green); font-weight: 600;
        }

        /* ERROR */
        .error-list {
            background: #fef2f2; border: 1.5px solid #fca5a5;
            border-radius: 0.55rem; padding: 0.75rem 1rem;
            margin-bottom: 1rem; font-size: 0.82rem; color: #b91c1c;
        }

        /* PDF */
        .pdf-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 1rem;
        }
        .pdf-card-header {
            padding: 0.85rem 1.25rem;
            border-bottom: 1.5px solid var(--border);
            background: var(--surface2);
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.82rem; font-weight: 600; color: var(--text2);
        }
        .pdf-card-header i { color: #dc2626; font-size: 0.85rem; }
        .pdf-frame { width: 100%; height: 540px; border: none; display: block; }
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
                            <a href="{{ url('/home') }}">Inicio</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('asignar_orden.index') }}">Orden de Trabajo</a>
                            <i class="fas fa-chevron-right"></i>
                            {{ $presupuesto->clave ?? '#'.$presupuesto->id }}
                        </div>
                        <h1 class="ph-title">Asignar <em>orden de trabajo</em></h1>
                        <p class="ph-sub">{{ $presupuesto->obra->nombre ?? '—' }}</p>
                    </div>
                    <div class="ph-right">
                        <button type="submit" form="form-asignar" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('asignar_orden.index') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @if($errors->any())
                <div class="error-list">
                    {{ $errors->first() }}
                </div>
                @endif

                <form id="form-asignar" action="{{ route('asignar_orden.update', $presupuesto->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- ORDEN DE TRABAJO -->
                <div class="form-card">
                    <div class="form-card-header">
                        <span><i class="fas fa-clipboard-list"></i> Orden de trabajo</span>
                    </div>
                    <div class="form-card-body">
                        <div style="max-width: 360px;">
                            <label class="field-label" for="orden_trabajo">Número de orden <span style="color:#dc2626;">*</span></label>
                            <input type="text"
                                   id="orden_trabajo"
                                   name="orden_trabajo"
                                   class="field-input mono"
                                   value="{{ old('orden_trabajo', $presupuesto->orden_trabajo) }}"
                                   placeholder="Ej: OT-2024-001"
                                   autocomplete="off">
                        </div>
                    </div>
                </div>

                </form>

                @php
                    $badgeClass = match($estados_btn[$presupuesto->estado] ?? '') {
                        'danger'  => 'badge-danger',
                        'warning' => 'badge-warning',
                        'primary' => 'badge-primary',
                        'success' => 'badge-success',
                        default   => 'badge-secondary',
                    };
                @endphp

                <!-- INFO PRESUPUESTO (solo lectura) -->
                <div class="form-card">
                    <div class="form-card-header">
                        <span><i class="fas fa-file-alt"></i> Presupuesto</span>
                        <span class="estado-badge {{ $badgeClass }}">
                            {{ $estados[$presupuesto->estado] ?? 'Desconocido' }}
                        </span>
                    </div>
                    <div class="form-card-body">
                        <div class="fields-grid">
                            <div>
                                <label class="field-label">Clave</label>
                                <div class="field-display mono">{{ $presupuesto->clave ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="field-label">Obra</label>
                                <div class="field-display">{{ $presupuesto->obra->nombre ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="field-label">Tipo de trabajo</label>
                                <div class="field-display">{{ $tipos[$presupuesto->tipo_trabajo] ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="field-label">Ubicación</label>
                                <div class="field-display">{{ $presupuesto->ubicacion ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="field-label">Monto total</label>
                                <div class="field-display money">Gs. {{ number_format($presupuesto->monto_total ?? 0, 0, '', '.') }}</div>
                            </div>
                            <div>
                                <label class="field-label">Fecha aprobación</label>
                                <div class="field-display">{{ $presupuesto->fecha_aprobacion ? \Carbon\Carbon::parse($presupuesto->fecha_aprobacion)->format('d/m/Y') : '—' }}</div>
                            </div>
                        </div>
                        @if($presupuesto->observacion)
                        <div style="margin-top: 1rem;">
                            <label class="field-label">Observación</label>
                            <div class="field-display" style="font-style: italic; color: var(--text2);">{{ $presupuesto->observacion }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- PDF -->
                @if($presupuesto->presupuesto)
                <div class="pdf-card">
                    <div class="pdf-card-header">
                        <i class="fas fa-file-pdf"></i> Presupuesto PDF
                    </div>
                    <iframe
                        class="pdf-frame"
                        src="{{ asset('storage/presupuestos/' . $presupuesto->presupuesto) }}"
                        title="Presupuesto PDF">
                    </iframe>
                </div>
                @endif

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>
</body>
</html>
