<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obra: {{ $obra->nombre }}</title>
    @include('partials.head')
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
            --red:      #d94040;
            --red-s:    #fdeaea;
            --slate:    #4e6070;
            --slate-s:  #edf1f4;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .content-wrapper { background: var(--bg) !important; }

        /* ══════════════════════════════
           PAGE HEADER
        ══════════════════════════════ */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .ph-crumb {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 0.5rem;
        }

        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }

        .ph-title {
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.4px;
            line-height: 1.1;
            word-break: break-word;
        }

        .ph-title em { font-style: normal; color: var(--accent); }

        .ph-sub {
            font-size: 0.8rem;
            color: var(--muted);
            margin-top: 0.3rem;
        }

        .ph-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        /* ── Buttons ── */
        .btn {
            height: 38px;
            padding: 0 1rem;
            border-radius: 0.55rem;
            display: inline-flex;
            align-items: center;
            gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem;
            font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface);
            color: var(--text2);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.14s;
            white-space: nowrap;
        }

        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }

        .btn-danger-soft {
            background: var(--red-s);
            border-color: #f5bcbc;
            color: var(--red);
        }
        .btn-danger-soft:hover {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
        }

        .btn-edit {
            background: var(--accent-s);
            border-color: #b8d0f8;
            color: var(--accent);
        }
        .btn-edit:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        /* ══════════════════════════════
           OPTIONS GRID
        ══════════════════════════════ */
        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .opcion-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            padding: 1.5rem 1rem 1.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text);
            text-align: center;
            transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            animation: cardIn 0.22s ease both;
        }

        .opcion-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0,0,0,0.09);
            border-color: var(--border2);
            color: var(--text);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }

        .opcion-icon {
            width: 48px; height: 48px;
            border-radius: 0.65rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .opcion-label {
            font-size: 0.83rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1.3;
        }

        /* colour variants */
        .ic-purple { background: #eeecf9; color: #7c6fcd; }
        .ic-yellow  { background: #fef9ec; color: #d4920a; }
        .ic-blue    { background: var(--accent-s); color: var(--accent); }
        .ic-green   { background: var(--green-s); color: var(--green); }
        .ic-orange  { background: #fff0eb; color: #d9622a; }
        .ic-teal    { background: #e5f7fa; color: #0891a8; }
        .ic-slate   { background: var(--slate-s); color: var(--slate); }

        /* ══════════════════════════════
           MODAL
        ══════════════════════════════ */
        .modal-content {
            border-radius: 0.85rem;
            border: 1.5px solid var(--border);
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
            overflow: hidden;
        }

        .modal-header {
            background: var(--red-s);
            border-bottom: 1px solid #f5bcbc;
            padding: 1rem 1.25rem;
        }

        .modal-header .modal-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--red);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-body {
            padding: 1.75rem 1.5rem;
            text-align: center;
        }

        .modal-body .modal-icon {
            width: 52px; height: 52px;
            background: var(--red-s);
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            color: var(--red);
            margin-bottom: 1rem;
        }

        .modal-body p { font-size: 0.88rem; color: var(--text2); line-height: 1.5; }
        .modal-body strong { color: var(--text); }

        .modal-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
            background: var(--bg);
            display: flex;
            justify-content: center;
            gap: 0.6rem;
        }

        .btn-modal-cancel {
            background: var(--surface);
            border-color: var(--border2);
            color: var(--text2);
        }
        .btn-modal-cancel:hover { background: var(--surface2); color: var(--text); }

        .btn-modal-delete {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
        }
        .btn-modal-delete:hover { background: #bf3535; border-color: #bf3535; color: #fff; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('partials.navbar')
    @include('partials.sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">

                {{-- Header --}}
                <div class="ph">
                    <div>
                        <div class="ph-crumb">
                            <i class="fas fa-hard-hat"></i>
                            <a href="{{ route('obras.index') }}">Obras</a>
                            <i class="fas fa-chevron-right"></i>
                            {{ $obra->nombre }}
                        </div>
                        <h1 class="ph-title"><em>{{ $obra->nombre }}</em></h1>
                        <p class="ph-sub">Panel de gestión de la obra</p>
                    </div>
                    <div class="ph-right">
                        @permiso('obr', 'editar')
                        <a href="{{ route('obras.edit', $obra->id) }}" class="btn btn-edit">
                            <i class="fas fa-pen"></i> Editar
                        </a>
                        @endpermiso
                        @permiso('obr', 'eliminar')
                        <button type="button" class="btn btn-danger-soft" data-bs-toggle="modal" data-bs-target="#modalEliminarObra">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                        @endpermiso
                        <a href="{{ route('obras.index') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="options-grid">

                    @permiso('dir', 'ver')
                    <a href="{{ route('directorio.index', ['obra' => $obra->id]) }}" class="opcion-card" style="animation-delay:0.04s">
                        <div class="opcion-icon ic-purple"><i class="fas fa-folder-open"></i></div>
                        <span class="opcion-label">Directorio</span>
                    </a>
                    @endpermiso

                    @permiso('pre_apr', 'ver')
                    <a href="{{ route('presupuesto_aprobado.index', $obra->id) }}" class="opcion-card" style="animation-delay:0.08s">
                        <div class="opcion-icon ic-yellow"><i class="fas fa-file-invoice-dollar"></i></div>
                        <span class="opcion-label">Presupuestos Aprobados</span>
                    </a>
                    @endpermiso

                    @permiso('ped_ins', 'ver')
                    <a href="{{ route('pedidobra.index', $obra->id) }}" class="opcion-card" style="animation-delay:0.12s">
                        <div class="opcion-icon ic-blue"><i class="fas fa-clipboard-list"></i></div>
                        <span class="opcion-label">Pedidos de Insumos</span>
                    </a>
                    @endpermiso

                    @permiso('con', 'ver')
                    <a href="{{ route('contacto.index', $obra->id) }}" class="opcion-card" style="animation-delay:0.16s">
                        <div class="opcion-icon ic-green"><i class="fas fa-address-book"></i></div>
                        <span class="opcion-label">Contactos</span>
                    </a>
                    @endpermiso

                    @permiso('inv', 'ver')
                    <a href="#" class="opcion-card" style="animation-delay:0.20s">
                        <div class="opcion-icon ic-orange"><i class="fas fa-boxes"></i></div>
                        <span class="opcion-label">Inventario</span>
                    </a>
                    @endpermiso

                    @permiso('fac', 'ver')
                    <a href="{{ route('factura_venta.show', $obra->id) }}" class="opcion-card" style="animation-delay:0.24s">
                        <div class="opcion-icon ic-teal"><i class="fas fa-file-invoice"></i></div>
                        <span class="opcion-label">Facturación</span>
                    </a>
                    @endpermiso

                    @permiso('dat', 'ver')
                    <a href="{{ route('obra_info.show', $obra->id) }}" class="opcion-card" style="animation-delay:0.28s">
                        <div class="opcion-icon ic-slate"><i class="fas fa-info-circle"></i></div>
                        <span class="opcion-label">Datos de la Obra</span>
                    </a>
                    @endpermiso

                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- Modal eliminar --}}
<div class="modal fade" id="modalEliminarObra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirmar eliminación</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="modal-icon"><i class="fas fa-trash-alt"></i></div>
                <p>¿Estás seguro que querés eliminar la obra</p>
                <p><strong>"{{ $obra->nombre }}"</strong>?</p>
                <p style="margin-top:0.5rem; font-size:0.8rem; color:var(--muted);">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                @permiso('obr', 'eliminar')
                <form action="{{ route('obras.destroy', $obra->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-modal-delete"><i class="fas fa-trash-alt"></i> Eliminar definitivamente</button>
                </form>
                @endpermiso
            </div>
        </div>
    </div>
</div>

</body>
</html>