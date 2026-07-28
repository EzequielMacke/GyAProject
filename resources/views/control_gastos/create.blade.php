<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Gastos</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
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
            --green:    #1e9166;
            --green-s:  #e5f6f0;
            --green-b:  #a8dcc9;
            --red:      #c0392b;
            --red-s:    #fceceb;
            --red-b:    #eeb7b0;
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

        /* ── Resumen comparativo ── */
        .resumen-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        .resumen-tile {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            padding: 1.1rem 1.25rem;
            display: flex; flex-direction: column; gap: 0.35rem;
        }
        .resumen-label {
            font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.4px; color: var(--muted);
        }
        .resumen-value {
            font-family: 'DM Mono', monospace;
            font-size: 1.4rem; font-weight: 600; color: var(--text);
        }
        .resumen-tile.tile-presupuesto .resumen-value { color: var(--accent); }
        .resumen-tile.tile-diff { transition: background 0.15s, border-color 0.15s; }
        .resumen-tile.tile-diff.diff-pos { background: var(--green-s); border-color: var(--green-b); }
        .resumen-tile.tile-diff.diff-pos .resumen-value { color: var(--green); }
        .resumen-tile.tile-diff.diff-neg { background: var(--red-s); border-color: var(--red-b); }
        .resumen-tile.tile-diff.diff-neg .resumen-value { color: var(--red); }

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
        .field-input:disabled { background: var(--surface2); color: var(--muted); cursor: not-allowed; }
        textarea.field-input { resize: vertical; min-height: 100px; }

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
        @php
            $tieneGasto  = isset($gasto) && $gasto;
            $puedeAgregar = app(\App\Services\PermisoService::class)->puede('con_gas', 'agregar');
            $puedeEditar  = app(\App\Services\PermisoService::class)->puede('con_gas', 'editar');
            $soloLectura  = $tieneGasto ? !$puedeEditar : !$puedeAgregar;
            $fmt = fn ($v) => $v !== null ? number_format($v, 0, '', '.') : '';
            $totalInicial = $tieneGasto
                ? (($gasto->ingenieros ?? 0) + ($gasto->tecnicos ?? 0) + ($gasto->mano_obra ?? 0) + ($gasto->otros ?? 0))
                : 0;
            $diferenciaInicial = $presupuesto->monto_total - $totalInicial;
        @endphp
        <div class="content-header">
            <div class="container-fluid">
                <div class="ph">
                    <div>
                        <div class="ph-crumb">
                            <i class="fas fa-hard-hat"></i>
                            <a href="{{ route('obras.index') }}">Obras</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('obras.show', $obra) }}">{{ $obra->nombre ?? '-' }}</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('control_gastos.index', $obra) }}">Control de Gastos</a>
                            <i class="fas fa-chevron-right"></i> {{ $tieneGasto ? ($soloLectura ? 'Ver' : 'Editar') : 'Registrar' }}
                        </div>
                        <h1 class="ph-title">{{ $tieneGasto ? ($soloLectura ? 'Gastos' : 'Editar') : 'Registrar' }} <em>{{ $tieneGasto ? ($soloLectura ? 'cargados' : 'gastos') : 'gastos' }}</em></h1>
                        <p class="ph-sub">{{ $presupuesto->clave }} — {{ $obra->nombre ?? '-' }}</p>
                    </div>
                    <div class="ph-right">
                        @if(!$soloLectura)
                        <button type="submit" form="form-gastos" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        @endif
                        <a href="{{ route('control_gastos.index', $obra) }}" class="btn">
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

                {{-- Resumen comparativo --}}
                <div class="resumen-grid">
                    <div class="resumen-tile tile-presupuesto">
                        <span class="resumen-label">Monto del presupuesto</span>
                        <span class="resumen-value">{{ number_format($presupuesto->monto_total, 0, '', '.') }}</span>
                    </div>
                    <div class="resumen-tile">
                        <span class="resumen-label">Total de gastos</span>
                        <span class="resumen-value" id="total-gastos">{{ number_format($totalInicial, 0, '', '.') }}</span>
                    </div>
                    <div class="resumen-tile tile-diff {{ $diferenciaInicial >= 0 ? 'diff-pos' : 'diff-neg' }}" id="tile-diferencia">
                        <span class="resumen-label">Diferencia</span>
                        <span class="resumen-value" id="diferencia">{{ number_format($diferenciaInicial, 0, '', '.') }}</span>
                    </div>
                </div>

                <form id="form-gastos"
                      action="{{ $tieneGasto ? route('control_gastos.update', $gasto->id) : route('control_gastos.store') }}"
                      method="POST">
                    @csrf
                    @if($tieneGasto) @method('PUT') @endif
                    <input type="hidden" name="obra_id" value="{{ $obra->id }}">
                    <input type="hidden" name="presupuesto_aprobado_id" value="{{ $presupuesto->id }}">

                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="fas fa-coins"></i> Gastos
                        </div>
                        <div class="form-card-body">
                            <div class="fields-grid">
                                <div>
                                    <label class="field-label" for="ingenieros">Ingenieros</label>
                                    <input type="text" id="ingenieros" name="ingenieros" class="field-input monto-input"
                                           placeholder="Ej: 500.000" value="{{ old('ingenieros', $tieneGasto ? $fmt($gasto->ingenieros) : '') }}"
                                           {{ $soloLectura ? 'disabled' : '' }}>
                                </div>
                                <div>
                                    <label class="field-label" for="tecnicos">Técnicos</label>
                                    <input type="text" id="tecnicos" name="tecnicos" class="field-input monto-input"
                                           placeholder="Ej: 300.000" value="{{ old('tecnicos', $tieneGasto ? $fmt($gasto->tecnicos) : '') }}"
                                           {{ $soloLectura ? 'disabled' : '' }}>
                                </div>
                                <div>
                                    <label class="field-label" for="mano_obra">Mano de obra</label>
                                    <input type="text" id="mano_obra" name="mano_obra" class="field-input monto-input"
                                           placeholder="Ej: 200.000" value="{{ old('mano_obra', $tieneGasto ? $fmt($gasto->mano_obra) : '') }}"
                                           {{ $soloLectura ? 'disabled' : '' }}>
                                </div>
                                <div>
                                    <label class="field-label" for="otros">Otros</label>
                                    <input type="text" id="otros" name="otros" class="field-input monto-input"
                                           placeholder="Ej: 50.000" value="{{ old('otros', $tieneGasto ? $fmt($gasto->otros) : '') }}"
                                           {{ $soloLectura ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="fas fa-sticky-note"></i> Observaciones
                        </div>
                        <div class="form-card-body">
                            <label class="field-label" for="observacion">Observación</label>
                            <textarea id="observacion" name="observacion" class="field-input"
                                      placeholder="Notas adicionales…" {{ $soloLectura ? 'disabled' : '' }}>{{ old('observacion', $tieneGasto ? $gasto->observacion : '') }}</textarea>
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
    const montoPresupuesto = {{ (float) $presupuesto->monto_total }};
    const montoInputs = document.querySelectorAll('.monto-input');
    const totalEl = document.getElementById('total-gastos');
    const diffEl = document.getElementById('diferencia');
    const diffTile = document.getElementById('tile-diferencia');

    function parseMonto(v) {
        v = v.replace(/\./g, '');
        return v && !isNaN(v) ? Number(v) : 0;
    }

    function recalcular() {
        let total = 0;
        montoInputs.forEach(input => total += parseMonto(input.value));
        const diferencia = montoPresupuesto - total;

        totalEl.textContent = total.toLocaleString('de-DE');
        diffEl.textContent = diferencia.toLocaleString('de-DE');

        diffTile.classList.toggle('diff-pos', diferencia >= 0);
        diffTile.classList.toggle('diff-neg', diferencia < 0);
    }

    montoInputs.forEach(input => {
        input.addEventListener('input', function () {
            let v = this.value.replace(/\./g, '');
            if (!isNaN(v) && v !== '') this.value = Number(v).toLocaleString('de-DE');
            recalcular();
        });
    });

    recalcular();
});
</script>
</body>
</html>
