<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    @includeIf('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #f0f3f7;
            --bg2:      #e4e9f0;
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
            --red:      #c0392b;
            --slate:    #4e6070;
            --slate-s:  #edf1f4;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .content-wrapper { background: var(--bg) !important; }

        /* PAGE HEADER */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex; align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .ph-crumb {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem;
        }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; }

        /* BUTTONS */
        .btn {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border); background: var(--surface); color: var(--text2);
            text-decoration: none; cursor: pointer; transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }
        .btn-secondary { background: var(--slate-s); border-color: var(--border2); color: var(--slate); }
        .btn-secondary:hover { background: var(--border); color: var(--text); }

        /* LAYOUT */
        .page-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 1.25rem;
            align-items: start;
        }
        @media (max-width: 820px) {
            .page-grid { grid-template-columns: 1fr; }
        }

        /* FORM CARD */
        .form-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .form-card-stripe { height: 3px; background: linear-gradient(90deg, var(--accent), #6aaaf5); }
        .form-card-body { padding: 1.75rem 2rem; }

        .section-heading {
            display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.4rem;
        }
        .section-heading-icon {
            width: 30px; height: 30px; border-radius: 0.4rem;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; flex-shrink: 0;
        }
        .section-heading-text { font-size: 0.82rem; font-weight: 700; color: var(--text); }

        .fields-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .field-full { grid-column: 1 / -1; }

        .field-label {
            display: block; font-size: 0.75rem; font-weight: 600;
            color: var(--text2); margin-bottom: 0.4rem; letter-spacing: 0.1px;
        }
        .field-input {
            width: 100%; height: 40px; padding: 0 0.85rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.855rem; color: var(--text);
            background: var(--bg); border: 1.5px solid var(--border);
            border-radius: 0.5rem; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }
        .field-input:focus {
            border-color: var(--accent); background: #fff;
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }
        .field-input::placeholder { color: var(--muted); }
        .field-input.error { border-color: var(--red); box-shadow: 0 0 0 3px rgba(192,57,43,0.1); }

        .field-note { font-size: 0.71rem; color: var(--muted); margin-top: 0.3rem; }

        .field-hint {
            display: none; align-items: center; gap: 0.3rem;
            font-size: 0.72rem; margin-top: 0.35rem; color: var(--red);
        }
        .field-hint i { font-size: 0.65rem; }
        .field-hint.visible { display: flex; }

        .form-actions {
            display: flex; align-items: center; gap: 0.6rem;
            padding-top: 1.5rem; border-top: 1px solid var(--border); margin-top: 1.5rem;
        }

        /* INFO PANEL */
        .panel {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .panel-header {
            padding: 0.85rem 1.1rem;
            background: var(--bg2);
            border-bottom: 1.5px solid var(--border);
            display: flex; align-items: center; gap: 0.5rem;
        }
        .panel-header-icon {
            width: 26px; height: 26px; border-radius: 0.35rem;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.68rem; flex-shrink: 0;
        }
        .panel-header-text { font-size: 0.78rem; font-weight: 700; color: var(--text); }

        .panel-body { padding: 1.1rem; display: flex; flex-direction: column; gap: 0.85rem; }

        .avatar-big {
            width: 56px; height: 56px; border-radius: 0.65rem;
            background: var(--accent-s); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; font-weight: 700; letter-spacing: -0.5px;
            margin-bottom: 0.1rem;
        }

        .info-row { display: flex; flex-direction: column; gap: 0.2rem; }
        .info-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: var(--muted); }
        .info-value { font-size: 0.855rem; color: var(--text2); font-weight: 500; }

        .status-badge {
            display: inline-flex; align-items: center; gap: 0.32rem;
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 0.3px; text-transform: uppercase;
            padding: 0.24rem 0.6rem; border-radius: 99px;
        }
        .status-badge i { font-size: 0.45rem; }
        .status-badge.on  { background: var(--green-s); color: var(--green); }
        .status-badge.off { background: var(--surface2); color: var(--muted); }
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
                            <i class="fas fa-home"></i> Inicio
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('usuarios.index') }}">Usuarios</a>
                            <i class="fas fa-chevron-right"></i> Editar
                        </div>
                        <h1 class="ph-title">Editar <em>{{ $usuario->nombre }}</em></h1>
                        <p class="ph-sub">Modificá los datos del usuario</p>
                    </div>
                    <div class="ph-right">
                        <a href="{{ route('usuarios.index') }}" class="btn" id="volver-btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="page-grid">

                    {{-- Form --}}
                    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-card">
                            <div class="form-card-stripe"></div>
                            <div class="form-card-body">

                                <div class="section-heading">
                                    <div class="section-heading-icon"><i class="fas fa-user-pen"></i></div>
                                    <span class="section-heading-text">Datos del usuario</span>
                                </div>

                                @if ($errors->any())
                                    <div class="alert alert-danger mb-3" style="border-radius:0.55rem; font-size:0.85rem;">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="fields-grid">

                                    <div class="field-full">
                                        <label class="field-label" for="usuario">Nombre de usuario</label>
                                        <input type="text" class="field-input" id="usuario" name="usuario"
                                               required autocomplete="off"
                                               value="{{ old('usuario', $usuario->nombre) }}">
                                    </div>

                                    <div>
                                        <label class="field-label" for="contraseña">Nueva contraseña</label>
                                        <input type="password" class="field-input" id="contraseña" name="contraseña"
                                               placeholder="Dejar en blanco para no cambiar">
                                        <span class="field-note">Opcional — solo completar para cambiar</span>
                                    </div>

                                    <div>
                                        <label class="field-label" for="rep_contraseña">Repetir contraseña</label>
                                        <input type="password" class="field-input" id="rep_contraseña" name="rep_contraseña"
                                               placeholder="••••••••">
                                        <span class="field-hint" id="hint-pass">
                                            <i class="fas fa-triangle-exclamation"></i>
                                            Las contraseñas no coinciden.
                                        </span>
                                    </div>

                                    <div>
                                        <label class="field-label" for="area_id">Área</label>
                                        <select class="field-input" id="area_id" name="area_id" required>
                                            @foreach($areas as $area)
                                                <option value="{{ $area->id }}"
                                                    {{ old('area_id', $usuario->area_id) == $area->id ? 'selected' : '' }}>
                                                    {{ $area->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="field-label" for="estado">Estado</label>
                                        <select class="field-input" id="estado" name="estado" required>
                                            @foreach($estados as $valor => $etiqueta)
                                                <option value="{{ $valor }}"
                                                    {{ old('estado', $usuario->estado) == $valor ? 'selected' : '' }}>
                                                    {{ $etiqueta }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" id="guardarBtn">
                                        <i class="fas fa-floppy-disk"></i> Guardar cambios
                                    </button>
                                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                                        Cancelar
                                    </a>
                                </div>

                            </div>
                        </div>
                    </form>

                    {{-- Info panel --}}
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-header-icon"><i class="fas fa-circle-info"></i></div>
                            <span class="panel-header-text">Información actual</span>
                        </div>
                        <div class="panel-body">
                            @php $initials = mb_strtoupper(mb_substr($usuario->nombre, 0, 2)); @endphp
                            <div class="avatar-big">{{ $initials }}</div>

                            <div class="info-row">
                                <span class="info-label">ID</span>
                                <span class="info-value" style="font-family:'DM Mono',monospace">#{{ str_pad($usuario->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Usuario</span>
                                <span class="info-value">{{ $usuario->nombre }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Área</span>
                                <span class="info-value">{{ $usuario->area->descripcion ?? '—' }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Estado actual</span>
                                <span class="status-badge {{ $usuario->estado == 1 ? 'on' : 'off' }}">
                                    <i class="fas fa-circle"></i>
                                    {{ $estados[$usuario->estado] ?? '—' }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const passInput    = document.getElementById('contraseña');
    const repPassInput = document.getElementById('rep_contraseña');
    const guardarBtn   = document.getElementById('guardarBtn');
    const hintPass     = document.getElementById('hint-pass');

    function validar() {
        const pass    = passInput.value;
        const repPass = repPassInput.value;

        if (pass === '' && repPass === '') {
            hintPass.classList.remove('visible');
            repPassInput.classList.remove('error');
            guardarBtn.disabled = false;
            return;
        }

        const passOk = pass === repPass;
        hintPass.classList.toggle('visible', repPass !== '' && !passOk);
        repPassInput.classList.toggle('error', repPass !== '' && !passOk);
        guardarBtn.disabled = !passOk;
    }

    passInput.addEventListener('input', validar);
    repPassInput.addEventListener('input', validar);

    document.addEventListener('keydown', function (e) {
        if (e.ctrlKey && e.key === '2') {
            e.preventDefault();
            document.getElementById('volver-btn').click();
        }
    });
});
</script>
</body>
</html>
