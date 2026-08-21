<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gavilan y Asociados - Login</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #f0f3f7;
            --bg2:      #e4e9f0;
            --surface:  #f8fafc;
            --border:   #d8e0ea;
            --border2:  #c4cfdc;
            --text:     #1e2835;
            --text2:    #445060;
            --muted:    #8496aa;
            --accent:   #2a6fdb;
            --accent-s: #e8f0fc;
            --accent-b: #1f5bbf;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* ══════════════════════════════
           LOGIN CARD
        ══════════════════════════════ */
        .login-wrap {
            width: 100%;
            max-width: 400px;
        }

        /* Logo / brand */
        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-icon {
            width: 56px; height: 56px;
            background: var(--accent);
            border-radius: 0.85rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #fff;
            margin-bottom: 0.85rem;
            box-shadow: 0 6px 20px rgba(42,111,219,0.3);
        }

        .brand-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.3px;
            display: block;
        }

        .brand-sub {
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 0.2rem;
            display: block;
        }

        /* Card */
        .login-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        }

        .login-card h2 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.25rem;
        }

        .login-card p {
            font-size: 0.8rem;
            color: var(--muted);
            margin-bottom: 1.5rem;
        }

        /* Alerts */
        .alert {
            border-radius: 0.55rem;
            padding: 0.65rem 0.9rem;
            font-size: 0.82rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #e5f6f0;
            border: 1px solid #a8dcc9;
            color: #1e6b4e;
        }

        .alert-danger {
            background: #fdeaea;
            border: 1px solid #f5bcbc;
            color: #b83232;
        }

        .alert ul { margin: 0; padding-left: 1.1rem; }
        .alert li { margin-bottom: 0.15rem; }

        /* Form fields */
        .field { margin-bottom: 1rem; }

        .field label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text2);
            margin-bottom: 0.4rem;
        }

        .field-wrap {
            position: relative;
        }

        .field-wrap i {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 0.75rem;
            pointer-events: none;
        }

        .field-input {
            width: 100%;
            height: 42px;
            padding: 0 0.9rem 0 2.2rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            color: var(--text);
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .field-input::placeholder { color: var(--muted); }

        .field-input:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(42,111,219,0.1);
        }

        .field-input.is-invalid {
            border-color: #d94040;
        }

        .field-error {
            font-size: 0.72rem;
            color: #d94040;
            margin-top: 0.3rem;
            display: block;
        }

        /* Submit */
        .btn-login {
            width: 100%;
            height: 42px;
            background: var(--accent);
            border: none;
            border-radius: 0.55rem;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.14s, box-shadow 0.14s;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .btn-login:hover {
            background: var(--accent-b);
            box-shadow: 0 4px 16px rgba(42,111,219,0.35);
        }

        .btn-login:active { transform: scale(0.99); }

        /* Footer note */
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.72rem;
            color: var(--muted);
        }

        /* Install PWA button */
        .btn-install {
            display: none;
            width: 100%;
            height: 40px;
            background: var(--surface);
            border: 1.5px solid var(--border2);
            border-radius: 0.55rem;
            color: var(--text2);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            transition: border-color 0.14s, color 0.14s;
        }

        .btn-install.is-visible { display: flex; }

        .btn-install:hover {
            border-color: var(--accent);
            color: var(--accent);
        }
    </style>
</head>
<body>

<div class="login-wrap">

    {{-- Brand --}}
    <div class="login-brand">
        <div class="brand-icon"><i class="fas fa-hard-hat"></i></div>
        <span class="brand-name">Gavilan y Asociados</span>
        <span class="brand-sub">Sistema de gestión de obras</span>
    </div>

    {{-- Card --}}
    <div class="login-card">
        <h2>Iniciá sesión</h2>
        <p>Ingresá tus credenciales para continuar</p>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <div class="field">
                <label for="nombre">Usuario</label>
                <div class="field-wrap">
                    <i class="fas fa-user"></i>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        class="field-input {{ $errors->has('nombre') ? 'is-invalid' : '' }}"
                        placeholder="Nombre de usuario"
                        value="{{ old('nombre') }}"
                        required
                        autocomplete="username">
                </div>
                @if($errors->has('nombre'))
                <span class="field-error">{{ $errors->first('nombre') }}</span>
                @endif
            </div>

            <div class="field">
                <label for="password">Contraseña</label>
                <div class="field-wrap">
                    <i class="fas fa-lock"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password">
                </div>
                @if($errors->has('password'))
                <span class="field-error">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Iniciar sesión
            </button>

            <button type="button" id="btnInstallApp" class="btn-install">
                <i class="fas fa-download"></i> Instalar aplicación
            </button>

        </form>
    </div>

    <div class="login-footer">
        &copy; {{ date('Y') }} Gavilan y Asociados
    </div>

</div>

<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
<script>
    let deferredInstallPrompt = null;
    const btnInstallApp = document.getElementById('btnInstallApp');

    window.addEventListener('beforeinstallprompt', (event) => {
        event.preventDefault();
        deferredInstallPrompt = event;
        btnInstallApp.classList.add('is-visible');
    });

    btnInstallApp.addEventListener('click', async () => {
        if (!deferredInstallPrompt) return;
        deferredInstallPrompt.prompt();
        await deferredInstallPrompt.userChoice;
        deferredInstallPrompt = null;
        btnInstallApp.classList.remove('is-visible');
    });

    window.addEventListener('appinstalled', () => {
        btnInstallApp.classList.remove('is-visible');
        deferredInstallPrompt = null;
    });
</script>
</body>
</html>