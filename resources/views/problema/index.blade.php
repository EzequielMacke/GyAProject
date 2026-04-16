<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Problemas</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
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
            --orange:   #d9622a;
            --orange-s: #fff0eb;
            --green:    #1e9166;
            --green-s:  #e5f6f0;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* PAGE HEADER */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex; align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .ph-crumb {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.72rem; font-weight: 500; color: var(--muted);
            margin-bottom: 0.5rem;
        }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
        .ph-title em { font-style: normal; color: var(--orange); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        /* BUTTONS */
        .btn {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface); color: var(--text2);
            text-decoration: none; cursor: pointer;
            transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-b); border-color: var(--accent-b); color: #fff; }
        .btn-sm { height: 30px; padding: 0 0.7rem; font-size: 0.75rem; border-radius: 0.45rem; }
        .btn-green { background: var(--green); border-color: var(--green); color: #fff; }
        .btn-green:hover { background: #187a58; border-color: #187a58; color: #fff; }

        /* SEARCH */
        .search-wrap { position: relative; }
        .search-wrap i { position: absolute; left: 0.78rem; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 0.72rem; pointer-events: none; }
        .search-bar {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.83rem;
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.55rem; padding: 0.5rem 0.9rem 0.5rem 2.1rem;
            color: var(--text); width: 220px; outline: none; height: 38px;
            transition: border-color 0.15s, width 0.22s, box-shadow 0.15s;
        }
        .search-bar::placeholder { color: var(--muted); }
        .search-bar:focus { border-color: var(--accent); width: 270px; box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        /* LISTA */
        .list-wrap {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
        }

        .list-header {
            display: grid;
            grid-template-columns: 1fr 180px 160px 120px;
            padding: 0.65rem 1.25rem;
            background: var(--surface2);
            border-bottom: 1.5px solid var(--border);
            font-size: 0.72rem; font-weight: 700;
            color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.05em;
        }

        .problema-block {
            border-bottom: 1px solid var(--border);
            border-left: 4px solid transparent;
        }
        .problema-block:last-child { border-bottom: none; }

        .problema-row {
            display: grid;
            grid-template-columns: 1fr 180px 160px 120px;
            align-items: center;
            padding: 1rem 1.25rem;
            transition: background 0.12s;
        }
        .problema-row:hover { background: var(--surface2); }

        .prob-descripcion {
            font-size: 0.875rem; font-weight: 600;
            color: var(--text); line-height: 1.45;
            padding-right: 1rem;
        }

        .prob-autor {
            display: flex; align-items: center;
            gap: 0.5rem; font-size: 0.8rem; color: var(--text2);
        }
        .prob-autor-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: var(--orange-s); color: var(--orange);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem; font-weight: 700; flex-shrink: 0;
        }

        .prob-fecha {
            font-size: 0.8rem; color: var(--muted);
            display: flex; align-items: center; gap: 0.4rem;
        }

        .prob-acciones {
            display: flex; justify-content: flex-end;
        }

        /* SOLUCIONES */
        .soluciones-list {
            padding: 0 1.25rem 0.85rem 2.5rem;
        }
        .solucion-item {
            display: flex; align-items: flex-start;
            gap: 0.5rem; padding: 0.3rem 0;
            font-size: 0.8rem; color: var(--text2);
            border-top: 1px dashed var(--border);
        }
        .solucion-item:first-child { border-top: none; }
        .solucion-viñeta {
            color: var(--green); font-weight: 700;
            font-size: 0.75rem; margin-top: 0.05rem;
            flex-shrink: 0; letter-spacing: -1px;
        }
        .solucion-texto { flex: 1; line-height: 1.4; }
        .solucion-autor {
            font-size: 0.72rem; color: var(--muted);
            white-space: nowrap; flex-shrink: 0;
        }
        .solucion-item.inactiva .solucion-texto {
            text-decoration: line-through; color: var(--muted); opacity: 0.7;
        }
        .solucion-item.inactiva .solucion-viñeta { opacity: 0.4; }
        .btn-sol-accion {
            background: none; border: none; cursor: pointer;
            font-size: 0.72rem; padding: 0.15rem 0.3rem;
            border-radius: 0.3rem; flex-shrink: 0;
            transition: color 0.14s, background 0.14s;
        }
        .btn-sol-x    { color: #c0392b; }
        .btn-sol-x:hover    { background: #fde0e0; color: #a93226; }
        .btn-sol-check { color: var(--green); }
        .btn-sol-check:hover { background: var(--green-s); color: #187a58; }

        /* EMPTY STATE */
        .empty-state { padding: 3.5rem 1.5rem; text-align: center; }
        .empty-icon {
            width: 52px; height: 52px; border-radius: 0.75rem;
            background: var(--orange-s); color: var(--orange);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin-bottom: 1rem;
        }
        .empty-title { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 0.35rem; }
        .empty-sub { font-size: 0.82rem; color: var(--muted); }

        /* ALERT */
        .alert {
            padding: 0.75rem 1rem; border-radius: 0.55rem;
            font-size: 0.83rem; font-weight: 500;
            margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .alert-success { background: var(--green-s); color: var(--green); border: 1px solid #b6e8d6; }

        /* MODAL */
        .modal-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(10,18,30,0.45);
            z-index: 1050; align-items: center; justify-content: center;
        }
        .modal-backdrop.open { display: flex; }

        .modal-box {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.95rem;
            width: 100%; max-width: 480px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.15);
            animation: modalIn 0.18s ease both;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: translateY(12px) scale(0.98); }
            to   { opacity: 1; transform: none; }
        }

        .modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.1rem 1.35rem 0.9rem;
            border-bottom: 1.5px solid var(--border);
        }
        .modal-title {
            font-size: 0.98rem; font-weight: 700; color: var(--text);
            display: flex; align-items: center; gap: 0.5rem;
        }
        .modal-title i { color: var(--orange); }
        .modal-title.sol i { color: var(--green); }
        .modal-close {
            background: none; border: none; cursor: pointer;
            color: var(--muted); font-size: 1rem; padding: 0.2rem;
            border-radius: 0.35rem; transition: color 0.14s;
        }
        .modal-close:hover { color: var(--text); }

        .modal-body { padding: 1.25rem 1.35rem; }

        .form-group { margin-bottom: 1rem; }
        .form-label {
            display: block; font-size: 0.78rem; font-weight: 600;
            color: var(--text2); margin-bottom: 0.4rem;
        }
        .form-control {
            width: 100%; font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem; background: var(--surface);
            border: 1.5px solid var(--border); border-radius: 0.55rem;
            padding: 0.6rem 0.85rem; color: var(--text); outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            resize: vertical;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,111,219,0.1); }

        .modal-footer {
            display: flex; align-items: center; justify-content: flex-end;
            gap: 0.5rem;
            padding: 0.9rem 1.35rem 1.1rem;
            border-top: 1.5px solid var(--border);
        }

        /* DANGER SOFT BUTTON */
        .btn-danger-soft {
            background: #fff0f0; border-color: #f5c2c2; color: #c0392b;
        }
        .btn-danger-soft:hover { background: #fde0e0; border-color: #e07070; color: #a93226; }

        /* DRAG HANDLE */
        .drag-handle {
            color: var(--border2); cursor: grab; font-size: 0.78rem;
            padding: 0.1rem 0.3rem 0.1rem 0; flex-shrink: 0;
            transition: color 0.14s;
        }
        .drag-handle:hover { color: var(--muted); }
        .drag-handle:active { cursor: grabbing; }
        .sortable-ghost { opacity: 0.4; background: var(--accent-s) !important; }
        .sortable-chosen { background: var(--surface2); }

        /* EDIT PENCIL */
        .btn-edit-desc {
            background: none; border: none; cursor: pointer;
            color: var(--muted); font-size: 0.72rem;
            padding: 0.2rem 0.35rem; border-radius: 0.35rem;
            margin-left: 0.35rem;
            transition: color 0.14s, background 0.14s;
            vertical-align: middle;
        }
        .btn-edit-desc:hover { color: var(--accent); background: var(--accent-s); }
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
                            <a href="{{ route('home') }}">Inicio</a>
                            <i class="fas fa-chevron-right"></i>
                            <i class="fas fa-exclamation-triangle"></i>
                            Reporte de Problemas
                        </div>
                        <h1 class="ph-title">Reporte de <em>Problemas</em></h1>
                        <p class="ph-sub">{{ $problemas->count() }} {{ $problemas->count() === 1 ? 'registro' : 'registros' }} encontrados</p>
                    </div>
                    <div class="ph-right">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search-bar" id="buscador" placeholder="Buscar problema..." autocomplete="off">
                        </div>
                        @if($puedeAgregarProblema)
                        <button type="button" class="btn btn-primary" onclick="abrirModalProblema()">
                            <i class="fas fa-plus"></i> Nuevo
                        </button>
                        @endif
                        <a href="{{ route('home') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                <div class="list-wrap">
                    @if($problemas->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-exclamation-triangle"></i></div>
                            <div class="empty-title">Sin problemas registrados</div>
                            <div class="empty-sub">Todavía no hay ningún problema cargado en el sistema.</div>
                        </div>
                    @else
                        <div class="list-header">
                            <div>Descripción</div>
                            <div>Publicado por</div>
                            <div>Fecha</div>
                            <div></div>
                        </div>
                        <div id="lista-problemas">
                            @php $totalProblemas = $problemas->count(); @endphp
                            @foreach($problemas as $problema)
                            @php
                                $hue = $totalProblemas > 1
                                    ? round(($loop->index / ($totalProblemas - 1)) * 220)
                                    : 0;
                                $urgColor = "hsl({$hue}, 72%, 48%)";
                            @endphp
                            <div class="problema-block" data-id="{{ $problema->id }}" data-search="{{ strtolower($problema->descripcion . ' ' . $problema->usuario?->nombre) }}" style="border-left-color: {{ $urgColor }}">
                                <div class="problema-row">
                                    <div class="prob-descripcion" style="display:flex;align-items:baseline;gap:0.25rem;">
                                        @if($puedeEditarProblema)
                                        <span class="drag-handle" title="Arrastrar para reordenar"><i class="fas fa-grip-vertical"></i></span>
                                        @endif
                                        <span>{{ $problema->descripcion }}
                                        @if($puedeEditarProblema)
                                        <button type="button" class="btn-edit-desc"
                                            onclick="abrirModalEditar({{ $problema->id }}, {{ json_encode($problema->descripcion) }})"
                                            title="Editar descripción">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        @endif
                                        </span>
                                    </div>
                                    <div class="prob-autor">
                                        <div class="prob-autor-avatar">
                                            {{ strtoupper(substr($problema->usuario?->nombre ?? '?', 0, 2)) }}
                                        </div>
                                        {{ $problema->usuario?->nombre ?? '—' }}
                                    </div>
                                    <div class="prob-fecha">
                                        <i class="fas fa-clock"></i>
                                        {{ $problema->stamp ? \Carbon\Carbon::parse($problema->stamp)->format('d/m/Y H:i') : '—' }}
                                    </div>
                                    <div class="prob-acciones">
                                        @if($puedeAgregarSolucion)
                                        <button type="button" class="btn btn-sm btn-green"
                                            onclick="abrirModalSolucion({{ $problema->id }})">
                                            <i class="fas fa-plus"></i> Solución
                                        </button>
                                        @endif
                                        @if($puedeEliminarProblema)
                                        <button type="button" class="btn btn-sm btn-danger-soft" title="Eliminar problema"
                                            onclick="abrirModalConfirmarEliminar({{ $problema->id }}, {{ json_encode(Str::limit($problema->descripcion, 60)) }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>

                                @if($problema->soluciones->isNotEmpty())
                                <div class="soluciones-list">
                                    @foreach($problema->soluciones as $solucion)
                                    <div class="solucion-item {{ $solucion->estado == 2 ? 'inactiva' : '' }}" data-id="{{ $solucion->id }}">
                                        @if($puedeEditarSolucion)
                                        <span class="drag-handle drag-handle-sol" title="Arrastrar para reordenar"><i class="fas fa-grip-vertical"></i></span>
                                        @endif
                                        <span class="solucion-viñeta">&rsaquo;&rsaquo;</span>
                                        <span class="solucion-texto">{{ $solucion->descripcion }}</span>
                                        <span class="solucion-autor">{{ $solucion->usuario?->nombre ?? '—' }}</span>
                                        @if($puedeEditarSolucion && $solucion->estado == 1)
                                        <button type="button" class="btn-edit-desc"
                                            onclick="abrirModalEditarSolucion({{ $solucion->id }}, {{ json_encode($solucion->descripcion) }})"
                                            title="Editar solución">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        @endif
                                        @if($puedeEliminarSolucion)
                                            @if($solucion->estado == 1)
                                            <form method="POST" action="{{ route('soluciones.destroy', $solucion->id) }}" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-sol-accion btn-sol-x" title="Desactivar solución">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form method="POST" action="{{ route('soluciones.restaurar', $solucion->id) }}" style="display:inline;">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn-sol-accion btn-sol-check" title="Restaurar solución">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- Modal: Nuevo Problema --}}
<div class="modal-backdrop" id="modal-problema">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title"><i class="fas fa-exclamation-triangle"></i> Nuevo Problema</span>
            <button type="button" class="modal-close" onclick="cerrarModal('modal-problema')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('problemas.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="desc-problema">Descripción del problema</label>
                    <textarea class="form-control" id="desc-problema" name="descripcion"
                        rows="4" placeholder="Describí el problema..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="cerrarModal('modal-problema')">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Confirmar eliminación de Problema --}}
<div class="modal-backdrop" id="modal-confirmar-eliminar">
    <div class="modal-box" style="max-width:400px;">
        <div class="modal-header">
            <span class="modal-title"><i class="fas fa-trash-alt" style="color:var(--orange)"></i> Eliminar problema</span>
            <button type="button" class="modal-close" onclick="cerrarModal('modal-confirmar-eliminar')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p style="font-size:0.875rem;color:var(--text2);line-height:1.5;">
                ¿Estás seguro que querés eliminar este problema?
            </p>
            <p id="confirmar-eliminar-desc" style="font-size:0.82rem;color:var(--muted);margin-top:0.5rem;font-style:italic;"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" onclick="cerrarModal('modal-confirmar-eliminar')">Cancelar</button>
            <button type="button" class="btn btn-danger-soft" onclick="confirmarEliminar()">
                <i class="fas fa-trash-alt"></i> Eliminar
            </button>
        </div>
    </div>
</div>

<form method="POST" id="form-eliminar-problema" action="" style="display:none;">
    @csrf
    @method('DELETE')
</form>

{{-- Modal: Editar Problema --}}
<div class="modal-backdrop" id="modal-editar-problema">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title"><i class="fas fa-pencil-alt"></i> Editar Problema</span>
            <button type="button" class="modal-close" onclick="cerrarModal('modal-editar-problema')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="form-editar-problema" action="">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="desc-editar-problema">Descripción del problema</label>
                    <textarea class="form-control" id="desc-editar-problema" name="descripcion"
                        rows="4" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="cerrarModal('modal-editar-problema')">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Editar Solución --}}
<div class="modal-backdrop" id="modal-editar-solucion">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title sol"><i class="fas fa-pencil-alt"></i> Editar Solución</span>
            <button type="button" class="modal-close" onclick="cerrarModal('modal-editar-solucion')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="form-editar-solucion" action="">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="desc-editar-solucion">Descripción de la solución</label>
                    <textarea class="form-control" id="desc-editar-solucion" name="descripcion"
                        rows="4" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="cerrarModal('modal-editar-solucion')">Cancelar</button>
                <button type="submit" class="btn btn-green">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Nueva Solución --}}
<div class="modal-backdrop" id="modal-solucion">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title sol"><i class="fas fa-check-circle"></i> Nueva Solución</span>
            <button type="button" class="modal-close" onclick="cerrarModal('modal-solucion')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="form-solucion" action="">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="desc-solucion">Descripción de la solución</label>
                    <textarea class="form-control" id="desc-solucion" name="descripcion"
                        rows="4" placeholder="Describí la solución..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="cerrarModal('modal-solucion')">Cancelar</button>
                <button type="submit" class="btn btn-green">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalProblema() {
        document.getElementById('modal-problema').classList.add('open');
    }

    function abrirModalConfirmarEliminar(problemaId, descripcion) {
        const base = "{{ url('/problemas') }}";
        document.getElementById('form-eliminar-problema').action = base + '/' + problemaId;
        document.getElementById('confirmar-eliminar-desc').textContent = descripcion;
        document.getElementById('modal-confirmar-eliminar').classList.add('open');
    }

    function confirmarEliminar() {
        document.getElementById('form-eliminar-problema').submit();
    }

    function abrirModalEditarSolucion(solucionId, descripcion) {
        const base = "{{ url('/soluciones') }}";
        document.getElementById('form-editar-solucion').action = base + '/' + solucionId;
        document.getElementById('desc-editar-solucion').value = descripcion;
        document.getElementById('modal-editar-solucion').classList.add('open');
    }

    function abrirModalEditar(problemaId, descripcion) {
        const base = "{{ url('/problemas') }}";
        document.getElementById('form-editar-problema').action = base + '/' + problemaId;
        document.getElementById('desc-editar-problema').value = descripcion;
        document.getElementById('modal-editar-problema').classList.add('open');
    }

    function abrirModalSolucion(problemaId) {
        const base = "{{ url('/problemas') }}";
        document.getElementById('form-solucion').action = base + '/' + problemaId + '/soluciones';
        document.getElementById('modal-solucion').classList.add('open');
    }

    function cerrarModal(id) {
        document.getElementById(id).classList.remove('open');
    }

    // Cerrar al hacer click fuera del modal
    document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
        backdrop.addEventListener('click', function(e) {
            if (e.target === backdrop) cerrarModal(backdrop.id);
        });
    });

    // Drag & drop — Problemas
    @if($puedeEditarProblema)
    const listaProblemas = document.getElementById('lista-problemas');
    if (listaProblemas) {
        Sortable.create(listaProblemas, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function () {
                const ids = [...listaProblemas.querySelectorAll('.problema-block')]
                    .map(el => parseInt(el.dataset.id));
                fetch("{{ route('problemas.reordenar') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ ids })
                });
            }
        });
    }
    @endif

    // Drag & drop — Soluciones
    @if($puedeEditarSolucion)
    document.querySelectorAll('.soluciones-list').forEach(function (list) {
        Sortable.create(list, {
            handle: '.drag-handle-sol',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function () {
                const ids = [...list.querySelectorAll('.solucion-item')]
                    .map(el => parseInt(el.dataset.id));
                fetch("{{ route('soluciones.reordenar') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ ids })
                });
            }
        });
    });
    @endif

    // Buscador
    document.getElementById('buscador')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#lista-problemas .problema-block').forEach(row => {
            row.style.display = row.dataset.search.includes(q) ? '' : 'none';
        });
    });
</script>
</body>
</html>
