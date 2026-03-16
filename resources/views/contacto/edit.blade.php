<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contacto</title>
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
            --accent-s: #e8f0fc;
            --red:      #d94040;
            --red-s:    #fdeaea;
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
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; box-shadow: 0 4px 14px rgba(42,111,219,0.3); }

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
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
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
        textarea.field-input { resize: vertical; min-height: 80px; }

        .error-list {
            background: #fef2f2; border: 1.5px solid #fca5a5;
            border-radius: 0.55rem; padding: 0.75rem 1rem;
            margin-bottom: 1rem; font-size: 0.82rem; color: #b91c1c;
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
                            <i class="fas fa-hard-hat"></i>
                            <a href="{{ route('obras.index') }}">Obras</a>
                            @if($obra)
                                <i class="fas fa-chevron-right"></i>
                                <a href="{{ route('obras.show', $obra) }}">{{ $obraModel->nombre ?? 'Obra' }}</a>
                                <i class="fas fa-chevron-right"></i>
                                <a href="{{ route('contacto.index', $obra) }}">Contactos</a>
                            @endif
                            <i class="fas fa-chevron-right"></i>
                            Editar
                        </div>
                        <h1 class="ph-title">Editar <em>contacto</em></h1>
                        <p class="ph-sub">{{ $contacto->nombre }}</p>
                    </div>
                    <div class="ph-right">
                        <button type="submit" form="form-contacto" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('contacto.index', $obra) }}" class="btn">
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
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <form id="form-contacto" action="{{ route('contacto.update', $contacto->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="obra_id" value="{{ $obra ?? '' }}">

                    {{-- Datos del contacto --}}
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="fas fa-user"></i> Datos del contacto
                        </div>
                        <div class="form-card-body">
                            <div class="fields-grid">
                                <div>
                                    <label class="field-label" for="nombre">Nombre</label>
                                    <input type="text" name="nombre" id="nombre" class="field-input"
                                           placeholder="Ej: Juan Pérez" required
                                           value="{{ old('nombre', $contacto->nombre) }}">
                                </div>
                                <div>
                                    <label class="field-label" for="tipo_contacto">Tipo de contacto</label>
                                    <input type="text" name="tipo_contacto" id="tipo_contacto" class="field-input"
                                           placeholder="Ej: Administrativo, Peticionario…"
                                           value="{{ old('tipo_contacto', $contacto->tipo_contacto) }}">
                                </div>
                                <div>
                                    <label class="field-label" for="telefono">Teléfono</label>
                                    <input type="text" name="telefono" id="telefono" class="field-input"
                                           placeholder="Ej: +595-123456"
                                           value="{{ old('telefono', $contacto->telefono) }}">
                                </div>
                                <div>
                                    <label class="field-label" for="email">Email</label>
                                    <input type="email" name="email" id="email" class="field-input"
                                           placeholder="Ej: correo@ejemplo.com"
                                           value="{{ old('email', $contacto->email) }}">
                                </div>
                                <div>
                                    <label class="field-label" for="presupuesto_id">Presupuesto vinculado</label>
                                    <select name="presupuesto_id" id="presupuesto_id" class="field-input">
                                        <option value="">Sin vincular</option>
                                        @foreach($presupuestos as $presupuesto)
                                            <option value="{{ $presupuesto->id }}"
                                                {{ old('presupuesto_id', $contacto->presupuesto_id) == $presupuesto->id ? 'selected' : '' }}>
                                                {{ $presupuesto->clave }}
                                            </option>
                                        @endforeach
                                    </select>
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
                            <textarea name="observacion" id="observacion" class="field-input"
                                      placeholder="Ej: Llamar solo por la mañana">{{ old('observacion', $contacto->observacion) }}</textarea>
                        </div>
                    </div>

                </form>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>
</body>
</html>
