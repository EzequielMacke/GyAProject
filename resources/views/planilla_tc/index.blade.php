<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planillas</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
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
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── PAGE HEADER ── */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex; align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .ph-crumb { display: flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 500; color: var(--muted); margin-bottom: 0.5rem; flex-wrap: wrap; }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        /* ── BUTTONS ── */
        .alert-offline { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; background: var(--accent-s); color: var(--accent-b); border: 1px solid #b9d3f5; }
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
        .btn-primary:disabled { opacity: 0.6; cursor: default; }

        /* ── MODAL ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-caja {
            background: #fff; border-radius: 1rem;
            width: 100%; max-width: 420px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.18);
            overflow: hidden;
            animation: modalIn 0.2s ease both;
        }
        @keyframes modalIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .modal-head {
            padding: 1.4rem 1.75rem 1.2rem;
            border-bottom: 1.5px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-head-title { font-size: 1rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 0.5rem; }
        .modal-head-title i { color: var(--accent); }
        .modal-head-title.danger i { color: #c0392b; }
        .modal-close { background: none; border: none; cursor: pointer; color: var(--muted); font-size: 1rem; padding: 0.25rem; border-radius: 0.35rem; transition: color 0.14s; }
        .modal-close:hover { color: var(--text); }
        .modal-body { padding: 1.25rem 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 0.6rem; }
        .modal-body p { font-size: 0.83rem; color: var(--muted); padding: 0 0.5rem; }
        .modal-body p strong { color: var(--text); }
        .modal-foot { padding: 1rem 1.75rem 1.4rem; display: flex; justify-content: flex-end; gap: 0.5rem; }
        .btn-cancel { height: 36px; padding: 0 1rem; border-radius: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text2); cursor: pointer; transition: all 0.14s; }
        .btn-cancel:hover { background: var(--surface2); }
        .btn-confirmar-eliminar {
            height: 36px; padding: 0 1.1rem; border-radius: 0.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 600;
            border: 1.5px solid #c0392b; background: #c0392b; color: #fff; cursor: pointer;
            display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.14s;
        }
        .btn-confirmar-eliminar:hover { background: #a93226; border-color: #a93226; }
        .btn-confirmar-eliminar:disabled { opacity: 0.6; cursor: default; }

        .tipo-planilla-btn {
            display: flex; align-items: center; gap: 0.85rem;
            width: 100%; text-align: left;
            padding: 0.85rem 1rem; border-radius: 0.7rem;
            border: 1.5px solid var(--border); background: var(--surface);
            cursor: pointer; transition: all 0.14s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .tipo-planilla-btn:hover { border-color: var(--accent); background: var(--accent-s); }
        .tipo-planilla-btn:disabled { opacity: 0.6; cursor: default; }
        .tipo-planilla-btn:disabled:hover { border-color: var(--border); background: var(--surface); }
        .tipo-planilla-btn .planilla-icon { width: 38px; height: 38px; font-size: 0.95rem; }
        .tipo-planilla-nombre { font-size: 0.87rem; font-weight: 700; color: var(--text); }

        /* ── EMPTY STATE ── */
        .empty-planillas {
            text-align: center; padding: 3rem 1.5rem;
            color: var(--muted); font-size: 0.85rem;
            border: 1.5px dashed var(--border2); border-radius: 0.85rem;
        }
        .empty-planillas i { font-size: 1.6rem; color: var(--border2); margin-bottom: 0.75rem; display: block; }

        /* ── GRID DE PLANILLAS ── */
        .planillas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1rem;
        }

        .planilla-card {
            background: var(--surface); border: 1.5px solid var(--border); border-radius: 0.85rem;
            padding: 1.25rem; text-decoration: none;
            display: flex; flex-direction: column; gap: 0.9rem;
            transition: all 0.14s; position: relative;
            animation: cardIn 0.18s ease both;
        }
        @keyframes cardIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }
        .planilla-card:hover { border-color: var(--accent); box-shadow: 0 4px 14px rgba(42,111,219,0.1); transform: translateY(-2px); }

        .planilla-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem; }
        .planilla-icon {
            width: 42px; height: 42px; border-radius: 0.6rem;
            background: var(--accent-s); color: var(--accent-b);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.05rem; flex-shrink: 0;
        }
        .planilla-delete-btn {
            position: relative; z-index: 2;
            background: none; border: none; cursor: pointer;
            color: var(--muted); font-size: 0.82rem; padding: 0.4rem; border-radius: 0.45rem;
            transition: color 0.14s, background 0.14s; flex-shrink: 0;
        }
        .planilla-delete-btn:hover { color: #c0392b; background: #fff0f0; }

        .planilla-nombre { font-size: 0.92rem; font-weight: 700; color: var(--text); }
        .planilla-resumen { font-size: 0.76rem; color: var(--muted); margin-top: 0.2rem; }
        .planilla-resumen.pendiente-local { color: #b26a00; font-weight: 600; }

        .planilla-reporte-btn {
            position: relative; z-index: 2; align-self: flex-start;
            display: inline-flex; align-items: center; gap: 0.4rem;
            height: 32px; padding: 0 0.75rem; border-radius: 0.5rem;
            border: 1.5px solid var(--border); background: #fff; color: var(--text2);
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.76rem; font-weight: 600;
            cursor: pointer; transition: all 0.14s;
        }
        .planilla-reporte-btn:hover { border-color: var(--accent); color: var(--accent-b); background: var(--accent-s); }
        .planilla-reporte-btn:disabled { opacity: 0.6; cursor: default; }

        .planilla-card-link {
            position: absolute; inset: 0; z-index: 1;
            border-radius: inherit;
        }

        /* ── MOBILE ── */
        @media (max-width: 640px) {
            .ph { padding: 1rem 0 0.75rem; gap: 0.75rem; margin-bottom: 1rem; }
            .ph-title { font-size: 1.3rem; }
            .ph-right { width: 100%; }
            .planillas-grid { grid-template-columns: 1fr; }
        }
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
                            <a href="{{ route('trabajo_campo.index') }}">Trabajo de Campo</a>
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('obras_tc.index', $obraTc->id) }}">{{ $obraTc->descripcion ?? '-' }}</a>
                            <i class="fas fa-chevron-right"></i>
                            Planillas
                        </div>
                        <h1 class="ph-title"><em>Planillas</em></h1>
                        <p class="ph-sub">{{ $obraTc->descripcion ?? '-' }}</p>
                    </div>
                    <div class="ph-right">
                        @php $tiposPendientes = array_filter($tiposPlanillas, fn ($t) => ! $t['cargada']); @endphp
                        @permiso('ens_tc', 'agregar')
                        @if(count($tiposPendientes) > 0)
                        <button type="button" class="btn btn-primary" onclick="abrirModalAgregarPlanilla()">
                            <i class="fas fa-plus"></i> Agregar planilla
                        </button>
                        @endif
                        @endpermiso
                        @permiso('ens_tc', 'ver')
                        <button type="button" class="btn" id="btn-descargar-offline" onclick="descargarPlanillasOffline()">
                            <i class="fas fa-cloud-download-alt"></i> Descargar para trabajar sin conexión
                        </button>
                        @endpermiso
                        <a href="{{ route('obras_tc.index', $obraTc->id) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="alert-offline" id="alert-descarga-offline" style="display:none">
                    <i class="fas fa-cloud-download-alt"></i>
                    <span id="texto-descarga-offline"></span>
                </div>

                @permiso('ens_tc', 'ver')
                @php $tiposCargados = array_filter($tiposPlanillas, fn ($t) => $t['cargada']); @endphp

                {{-- Se renderizan todas las planillas: las que el servidor todavía no
                     tiene quedan ocultas y se muestran si hay una versión guardada en
                     el dispositivo sin subir (creada sin conexión). Ver más abajo. --}}
                <div class="planillas-grid" @if(count($tiposCargados) === 0) style="display:none" @endif>
                    @foreach($tiposPlanillas as $tipo)
                        <div class="planilla-card" data-pagina="{{ $tipo['ruta'] }}" data-codigo="{{ $tipo['codigo'] }}" @if(! $tipo['cargada']) data-solo-local="1" style="display:none" @endif>
                            <div class="planilla-card-top">
                                <div class="planilla-icon"><i class="fas {{ $tipo['icono'] }}"></i></div>
                                @permiso('ens_tc', 'eliminar')
                                <button type="button" class="planilla-delete-btn" title="Eliminar planilla" data-ruta="{{ $tipo['ruta_eliminar'] }}" data-nombre="{{ $tipo['nombre'] }}" onclick="confirmarEliminarPlanilla(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endpermiso
                            </div>
                            <div>
                                <div class="planilla-nombre">{{ $tipo['nombre'] }}</div>
                                <div class="planilla-resumen">{{ $tipo['resumen'] }}</div>
                            </div>
                            @if(isset($tipo['ruta_reporte']))
                            <button type="button" class="planilla-reporte-btn" title="Generar reporte PNG" data-tipo="{{ $tipo['codigo'] }}" data-ruta="{{ $tipo['ruta_reporte'] }}" onclick="generarReporte(this)">
                                <i class="fas fa-file-image"></i> Reporte
                            </button>
                            @endif
                            <a href="{{ $tipo['ruta'] }}" class="planilla-card-link" aria-label="Abrir {{ $tipo['nombre'] }}"></a>
                        </div>
                    @endforeach
                </div>
                <div class="empty-planillas" @if(count($tiposCargados) > 0) style="display:none" @endif>
                    <i class="fas fa-clipboard-list"></i>
                    Todavía no hay ninguna planilla cargada para esta obra.
                </div>
                @endpermiso

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL AGREGAR PLANILLA
══════════════════════════════════════════════════════ --}}
@permiso('ens_tc', 'agregar')
<div class="modal-overlay" id="modal-agregar-planilla">
    <div class="modal-caja">
        <div class="modal-head">
            <div class="modal-head-title"><i class="fas fa-plus"></i> Agregar planilla</div>
            <button class="modal-close" onclick="cerrarModalAgregarPlanilla()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p>Elegí qué tipo de planilla querés agregar a esta obra.</p>
            @foreach($tiposPendientes as $tipo)
                <button type="button" class="tipo-planilla-btn" data-ruta="{{ $tipo['ruta_crear'] }}" data-pagina="{{ $tipo['ruta'] }}" onclick="crearPlanilla(this)">
                    <div class="planilla-icon"><i class="fas {{ $tipo['icono'] }}"></i></div>
                    <span class="tipo-planilla-nombre">{{ $tipo['nombre'] }}</span>
                </button>
            @endforeach
        </div>
    </div>
</div>
@endpermiso

{{-- ══════════════════════════════════════════════════════
     MODAL ELIMINAR PLANILLA
══════════════════════════════════════════════════════ --}}
@permiso('ens_tc', 'eliminar')
<div class="modal-overlay" id="modal-eliminar-planilla">
    <div class="modal-caja">
        <div class="modal-head">
            <div class="modal-head-title danger"><i class="fas fa-triangle-exclamation"></i> Eliminar planilla</div>
            <button class="modal-close" onclick="cerrarModalEliminarPlanilla()" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p>¿Seguro que querés eliminar la planilla de <strong id="eliminar-planilla-nombre"></strong>? Se van a borrar todos los puntos de ensayo cargados. Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="cerrarModalEliminarPlanilla()">Cancelar</button>
            <button type="button" class="btn-confirmar-eliminar" id="btn-confirmar-eliminar-planilla" onclick="eliminarPlanillaConfirmado()">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
    </div>
</div>
@endpermiso

<script>
    const CSRF_TOKEN = @json(csrf_token());

    const modalAgregarPlanilla = document.getElementById('modal-agregar-planilla');

    function abrirModalAgregarPlanilla() {
        modalAgregarPlanilla?.classList.add('active');
    }
    function cerrarModalAgregarPlanilla() {
        modalAgregarPlanilla?.classList.remove('active');
    }
    modalAgregarPlanilla?.addEventListener('click', function (e) {
        if (e.target === this) cerrarModalAgregarPlanilla();
    });

    async function crearPlanilla(boton) {
        document.querySelectorAll('.tipo-planilla-btn').forEach(b => b.disabled = true);
        try {
            const respuesta = await fetch(boton.dataset.ruta, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
            });
            if (!respuesta.ok) throw new Error('No se pudo crear la planilla');
            location.reload();
        } catch (e) {
            // Sin conexión: se abre la planilla igual (si se descargó para
            // trabajar sin conexión). Al subir el primer guardado el
            // servidor la crea, porque el guardado hace updateOrCreate.
            if (!navigator.onLine || e instanceof TypeError) {
                location.href = boton.dataset.pagina;
                return;
            }
            alert('No se pudo agregar la planilla. Intentá de nuevo.');
            document.querySelectorAll('.tipo-planilla-btn').forEach(b => b.disabled = false);
        }
    }

    const modalEliminarPlanilla = document.getElementById('modal-eliminar-planilla');
    let rutaEliminarPlanilla = null;

    let paginaEliminarPlanilla = null;
    let eliminarSoloLocal = false;

    function confirmarEliminarPlanilla(boton) {
        const card = boton.closest('.planilla-card');
        paginaEliminarPlanilla = card?.dataset.pagina || null;
        eliminarSoloLocal = card?.dataset.soloLocal === '1';
        rutaEliminarPlanilla = boton.dataset.ruta;
        document.getElementById('eliminar-planilla-nombre').textContent = boton.dataset.nombre;
        modalEliminarPlanilla?.classList.add('active');
    }
    function cerrarModalEliminarPlanilla() {
        modalEliminarPlanilla?.classList.remove('active');
        rutaEliminarPlanilla = null;
    }
    modalEliminarPlanilla?.addEventListener('click', function (e) {
        if (e.target === this) cerrarModalEliminarPlanilla();
    });

    async function eliminarPlanillaConfirmado() {
        if (!rutaEliminarPlanilla) return;
        const boton = document.getElementById('btn-confirmar-eliminar-planilla');
        boton.disabled = true;
        try {
            // Se descarta también la versión guardada en el dispositivo; si no,
            // se volvería a subir (y a crear) apenas haya conexión.
            if (paginaEliminarPlanilla) await PlanillaOffline.descartar(paginaEliminarPlanilla);
            if (eliminarSoloLocal) {
                location.reload();
                return;
            }
            const respuesta = await fetch(rutaEliminarPlanilla, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
            });
            if (!respuesta.ok) throw new Error('No se pudo eliminar la planilla');
            location.reload();
        } catch (e) {
            alert('No se pudo eliminar la planilla. Intentá de nuevo.');
            boton.disabled = false;
        }
    }

    /* ─── Planillas guardadas en el dispositivo sin subir ────────
       El listado lo arma el servidor, así que una planilla creada sin
       conexión no figura hasta que se sube. Acá se consulta lo pendiente
       en el dispositivo (public/js/planilla-offline.js: la clave es la URL
       de guardado, que es la misma que la de la página) y se muestran
       esas tarjetas marcadas como pendientes de subir. */
    const mismaRuta = (a, b) => new URL(a, location.href).pathname === new URL(b, location.href).pathname;
    let hayTarjetasLocales = false;

    async function mostrarPlanillasPendientes() {
        const pendientes = await PlanillaOffline.listarPendientes();
        const tarjetas = Array.from(document.querySelectorAll('.planilla-card[data-pagina]'));
        let visibles = tarjetas.filter(card => card.style.display !== 'none').length;

        tarjetas.forEach(function (card) {
            if (! pendientes.some(p => mismaRuta(p.url, card.dataset.pagina))) return;

            const resumen = card.querySelector('.planilla-resumen');
            resumen.classList.add('pendiente-local');
            resumen.innerHTML = '<i class="fas fa-cloud-arrow-up"></i> Guardada en el dispositivo, pendiente de subir';
            hayTarjetasLocales = true;

            if (card.dataset.soloLocal === '1' && card.style.display === 'none') {
                card.style.display = '';
                card.querySelector('.planilla-reporte-btn')?.remove(); // el servidor todavía no tiene datos para el reporte
                document.querySelectorAll(`.tipo-planilla-btn[data-pagina="${card.dataset.pagina}"]`).forEach(b => b.remove());
                visibles++;
            }
        });

        if (visibles > 0) {
            document.querySelector('.planillas-grid')?.style.removeProperty('display');
            const vacio = document.querySelector('.empty-planillas');
            if (vacio) vacio.style.display = 'none';
        }
    }
    mostrarPlanillasPendientes();

    // Cuando se terminan de subir, se recarga para mostrar lo que tiene el servidor.
    window.addEventListener('planilla-offline:estado', async function (e) {
        if (! hayTarjetasLocales || e.detail.estado !== 'guardado') return;
        if ((await PlanillaOffline.listarPendientes()).length === 0) location.reload();
    });

    /* ─── Descargar planillas para trabajar sin conexión ────────
       Igual que en planos_tc/index: le pasa las URLs al Service Worker
       (public/sw.js), que las descarga y guarda en sus caches. Incluye
       las 6 planillas aunque todavía no estén creadas, para poder
       agregarlas en obra sin señal. */
    @php $descargaOffline = \App\Services\DescargaOfflineService::planillas($obraTc); @endphp
    const PAGINAS_PARA_OFFLINE = @json($descargaOffline['paginas']);
    const RECURSOS_PARA_OFFLINE = @json($descargaOffline['recursos']);

    async function descargarPlanillasOffline() {
        if (!('serviceWorker' in navigator)) {
            alert('Este navegador no permite guardar páginas para uso sin conexión.');
            return;
        }

        const registro = await navigator.serviceWorker.ready;
        const controlador = registro.active;
        if (!controlador) {
            alert('Todavía no está listo el guardado offline. Recargá la página e intentá de nuevo en un momento.');
            return;
        }

        const boton = document.getElementById('btn-descargar-offline');
        const alerta = document.getElementById('alert-descarga-offline');
        const texto = document.getElementById('texto-descarga-offline');

        boton.disabled = true;
        alerta.style.display = 'flex';
        // El total incluye el listado y el script offline, no solo las
        // planillas: se muestra como porcentaje para no confundir.
        texto.textContent = 'Descargando planillas… 0%';

        const canal = new MessageChannel();
        canal.port1.onmessage = (event) => {
            const { listos, fallidos, total, terminado } = event.data;

            if (!terminado) {
                texto.textContent = `Descargando planillas… ${Math.round((listos + fallidos) / total * 100)}%`;
                return;
            }

            boton.disabled = false;
            texto.textContent = fallidos === 0
                ? 'Listo: las planillas de esta obra quedaron guardadas para trabajar sin conexión.'
                : `No se pudieron descargar ${fallidos} de ${total} archivos: probá de nuevo con mejor señal.`;

            setTimeout(() => { alerta.style.display = 'none'; }, 6000);
        };

        controlador.postMessage({
            tipo: 'precachear',
            paginas: PAGINAS_PARA_OFFLINE,
            recursos: RECURSOS_PARA_OFFLINE,
        }, [canal.port2]);
    }

    /* ─── Reporte PNG de planillas ───────────────────────────── */
    const FUENTE_REPORTE = "'EB Garamond', Garamond, serif";

    const REPORTES = {
        esclerometria: {
            titulo: datos => `Reporte de Esclerometría — ${datos.obra || ''}`,
            columnas: ['Nivel', 'Elemento', 'Identificación', 'Dirección', 'Índice esclerométrico corregido'],
            fila: p => [p.nivel, p.elemento, p.identificacion, p.direccion, p.n_final],
            prefijoArchivo: 'reporte-esclerometria',
        },
        ultrasonido_indirecto: {
            titulo: datos => `Reporte de Ultrasonido Indirecto — ${datos.obra || ''}`,
            columnas: ['Nivel', 'Elemento', 'Identificación', 'Velocidad (m/s)', 'Calidad del hormigón'],
            fila: p => [p.nivel, p.elemento, p.identificacion, p.velocidad, p.compactacion],
            prefijoArchivo: 'reporte-ultrasonido-indirecto',
        },
        carbonatacion: {
            titulo: datos => `Reporte de Carbonatación — ${datos.obra || ''}`,
            columnas: ['Nivel', 'Elemento', 'Identificación', 'Recubrimiento (mm)', 'Espesor carbonatado (mm)', '% afectado'],
            fila: p => [p.nivel, p.elemento, p.identificacion, p.recubrimiento, p.espesor_carbonatado, p.porcentaje_afectado],
            prefijoArchivo: 'reporte-carbonatacion',
        },
        cloruros: {
            titulo: datos => `Reporte de Cloruros — ${datos.obra || ''}`,
            dibujar: (titulo, puntos) => dibujarReporteCloruros(titulo, puntos),
            prefijoArchivo: 'reporte-cloruros',
        },
        medicion_fisura: {
            titulo: datos => `Reporte de Medición de Fisuras — ${datos.obra || ''}`,
            columnas: ['Nivel', 'Identificación', 'Espesor promedio (mm)', 'Profundidad promedio (mm)', 'Ancho del elemento', 'Sección afectada'],
            fila: p => [p.nivel, p.identificacion, p.promedio_espesor, p.promedio_profundidad, p.ancho, p.porcentaje_afectado],
            prefijoArchivo: 'reporte-medicion-fisura',
        },
        resistividad: {
            titulo: datos => `Reporte de Resistividad — ${datos.obra || ''}`,
            columnas: ['Nivel', 'Elemento', 'Identificación', 'Resistividad (kΩ·cm)', 'Velocidad de corrosión', 'Riesgo'],
            fila: p => [p.nivel, p.elemento, p.identificacion, p.resistividad, p.velocidad_corrosion, p.riesgo],
            prefijoArchivo: 'reporte-resistividad',
        },
    };

    async function generarReporte(boton) {
        const config = REPORTES[boton.dataset.tipo];
        const iconoOriginal = boton.innerHTML;
        boton.disabled = true;
        boton.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Generando...';
        try {
            const respuesta = await fetch(boton.dataset.ruta, {
                headers: { 'Accept': 'application/json' },
            });
            if (!respuesta.ok) throw new Error('No se pudo obtener el reporte');
            const datos = await respuesta.json();

            if (!datos.puntos || datos.puntos.length === 0) {
                alert('Todavía no hay puntos ensayados para generar el reporte.');
                return;
            }

            await Promise.all([
                document.fonts.load(`700 20px ${FUENTE_REPORTE}`),
                document.fonts.load(`400 15px ${FUENTE_REPORTE}`),
                document.fonts.load(`700 15px ${FUENTE_REPORTE}`),
            ]);

            const dataUrl = config.dibujar
                ? config.dibujar(config.titulo(datos), datos.puntos)
                : dibujarReportePng(config.titulo(datos), config.columnas, datos.puntos.map(config.fila));
            const link = document.createElement('a');
            link.href = dataUrl;
            link.download = `${config.prefijoArchivo}-${(datos.obra || 'obra').replace(/[^a-z0-9]+/gi, '-').toLowerCase()}.png`;
            document.body.appendChild(link);
            link.click();
            link.remove();
        } catch (e) {
            alert('No se pudo generar el reporte. Intentá de nuevo.');
        } finally {
            boton.disabled = false;
            boton.innerHTML = iconoOriginal;
        }
    }

    function dibujarReportePng(titulo, columnas, filas) {
        const escala = 2;
        const padding = 28;
        const colPaddingX = 18;
        const filaAltura = 38;
        const encabezadoAltura = 44;
        const tituloAltura = 44;

        const medidor = document.createElement('canvas').getContext('2d');

        function anchoTexto(texto, negrita, tamano) {
            medidor.font = `${negrita ? '700' : '400'} ${tamano}px ${FUENTE_REPORTE}`;
            return medidor.measureText(String(texto)).width;
        }

        const anchosCol = columnas.map((titulo, i) => {
            let maximo = anchoTexto(titulo, true, 15);
            filas.forEach(fila => {
                maximo = Math.max(maximo, anchoTexto(fila[i], false, 15));
            });
            return maximo + colPaddingX * 2;
        });

        const anchoTabla = anchosCol.reduce((s, w) => s + w, 0);
        const anchoLienzo = anchoTabla + padding * 2;
        const alturaLienzo = padding * 2 + tituloAltura + encabezadoAltura + filas.length * filaAltura;
        const centroX = anchoLienzo / 2;

        const canvas = document.createElement('canvas');
        canvas.width = anchoLienzo * escala;
        canvas.height = alturaLienzo * escala;
        const ctx = canvas.getContext('2d');
        ctx.scale(escala, escala);

        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, anchoLienzo, alturaLienzo);

        let y = padding;
        ctx.fillStyle = '#000000';
        ctx.font = `700 20px ${FUENTE_REPORTE}`;
        ctx.textBaseline = 'top';
        ctx.textAlign = 'center';
        ctx.fillText(titulo, centroX, y);
        ctx.textAlign = 'left';
        y += tituloAltura;
        const yTablaInicio = y;

        // Encabezado
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(padding, y, anchoTabla, encabezadoAltura);
        ctx.strokeStyle = '#000000';
        ctx.lineWidth = 1;
        ctx.strokeRect(padding, y, anchoTabla, encabezadoAltura);

        ctx.font = `700 15px ${FUENTE_REPORTE}`;
        ctx.fillStyle = '#000000';
        ctx.textBaseline = 'middle';
        ctx.textAlign = 'center';
        let x = padding;
        columnas.forEach((titulo, i) => {
            ctx.fillText(titulo, x + anchosCol[i] / 2, y + encabezadoAltura / 2);
            x += anchosCol[i];
        });
        y += encabezadoAltura;

        // Filas
        ctx.font = `400 15px ${FUENTE_REPORTE}`;
        filas.forEach((fila, i) => {
            if (i % 2 === 1) {
                ctx.fillStyle = '#f0f0f0';
                ctx.fillRect(padding, y, anchoTabla, filaAltura);
            }
            ctx.strokeStyle = '#000000';
            ctx.strokeRect(padding, y, anchoTabla, filaAltura);

            ctx.fillStyle = '#000000';
            let xFila = padding;
            fila.forEach((valor, c) => {
                ctx.fillText(String(valor), xFila + anchosCol[c] / 2, y + filaAltura / 2);
                xFila += anchosCol[c];
            });
            y += filaAltura;
        });

        // Líneas verticales entre columnas
        ctx.strokeStyle = '#000000';
        let xLinea = padding;
        ctx.beginPath();
        anchosCol.forEach(ancho => {
            ctx.moveTo(xLinea, yTablaInicio);
            ctx.lineTo(xLinea, y);
            xLinea += ancho;
        });
        ctx.moveTo(xLinea, yTablaInicio);
        ctx.lineTo(xLinea, y);
        ctx.stroke();

        ctx.textAlign = 'left';
        return canvas.toDataURL('image/png');
    }

    /* ─── Reporte de cloruros ────────────────────────────────────
       Un bloque por punto ensayado: a la izquierda la tabla de las
       3 perforaciones (profundidad, peso del polvo y % Cl del
       laboratorio) y a la derecha una barra horizontal por
       perforación, alineada con su fila. Todos los gráficos usan la
       misma escala para poder comparar los puntos entre sí. */
    function dibujarReporteCloruros(titulo, puntos) {
        const escala = 2;
        const padding = 28;
        const colPaddingX = 18;
        const filaAltura = 34;
        const encabezadoAltura = 40;
        const tituloAltura = 44;
        const cabeceraPuntoAltura = 30;
        const separacionPuntos = 26;
        const separacionGrafico = 30;
        const anchoGrafico = 300;
        const margenValor = 64;
        const altoBarra = 14;
        const colorBarra = '#2f5f98';
        const CANTIDAD_PERFORACIONES = 3;

        const formatear = (valor, minDec, maxDec) => valor === null || valor === undefined
            ? '-'
            : Number(valor).toLocaleString('es-AR', { minimumFractionDigits: minDec, maximumFractionDigits: maxDec });

        const columnas = ['Perforación', 'Profundidad (mm)', 'Peso muestra (g)', 'Cloruros (% Cl)'];
        const filasPorPunto = puntos.map(p => Array.from({ length: CANTIDAD_PERFORACIONES }, (_, i) => {
            const m = (p.muestras || [])[i] || {};
            return [String(i + 1), formatear(m.profundidad, 0, 2), formatear(m.peso, 0, 2), formatear(m.cloruro, 2, 3)];
        }));

        const valoresCloruro = puntos.flatMap(p => (p.muestras || []).map(m => m?.cloruro)).filter(v => v !== null && v !== undefined);
        const maximo = valoresCloruro.length > 0 ? Math.max(...valoresCloruro) : 0;
        const paso = [0.02, 0.05, 0.1, 0.2, 0.25, 0.5, 1, 2, 5].find(p => maximo / p <= 5) || 10;
        const escalaMaxima = Math.max(paso, Math.ceil(maximo / paso) * paso);
        const ticks = [];
        for (let t = 0; t <= escalaMaxima + paso / 1000; t += paso) ticks.push(t);
        const decimalesTick = paso < 0.1 ? 2 : (paso < 1 ? (paso === 0.25 ? 2 : 1) : 0);

        const medidor = document.createElement('canvas').getContext('2d');
        function anchoTexto(texto, negrita, tamano) {
            medidor.font = `${negrita ? '700' : '400'} ${tamano}px ${FUENTE_REPORTE}`;
            return medidor.measureText(String(texto)).width;
        }

        const anchosCol = columnas.map((col, i) => {
            let maximoCol = anchoTexto(col, true, 15);
            filasPorPunto.forEach(filas => filas.forEach(fila => {
                maximoCol = Math.max(maximoCol, anchoTexto(fila[i], false, 15));
            }));
            return maximoCol + colPaddingX * 2;
        });
        const anchoTabla = anchosCol.reduce((s, w) => s + w, 0);
        const altoTabla = encabezadoAltura + CANTIDAD_PERFORACIONES * filaAltura;
        const altoBloque = cabeceraPuntoAltura + altoTabla;

        const anchoLienzo = padding * 2 + anchoTabla + separacionGrafico + anchoGrafico + margenValor;
        const alturaLienzo = padding * 2 + tituloAltura + puntos.length * altoBloque + (puntos.length - 1) * separacionPuntos;

        const canvas = document.createElement('canvas');
        canvas.width = anchoLienzo * escala;
        canvas.height = alturaLienzo * escala;
        const ctx = canvas.getContext('2d');
        ctx.scale(escala, escala);

        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, anchoLienzo, alturaLienzo);

        ctx.fillStyle = '#000000';
        ctx.font = `700 20px ${FUENTE_REPORTE}`;
        ctx.textBaseline = 'top';
        ctx.textAlign = 'center';
        ctx.fillText(titulo, anchoLienzo / 2, padding);

        let y = padding + tituloAltura;
        const xGrafico = padding + anchoTabla + separacionGrafico;
        const xDeValor = v => xGrafico + (v / escalaMaxima) * anchoGrafico;

        puntos.forEach((punto, indicePunto) => {
            // Cabecera del punto
            ctx.fillStyle = '#000000';
            ctx.textAlign = 'left';
            ctx.textBaseline = 'middle';
            ctx.font = `700 16px ${FUENTE_REPORTE}`;
            const cabecera = `${punto.identificacion}  ·  Nivel: ${punto.nivel}  ·  Elemento: ${punto.elemento}`;
            ctx.fillText(cabecera, padding, y + cabeceraPuntoAltura / 2 - 2);
            y += cabeceraPuntoAltura;
            const yTabla = y;

            // Encabezado de la tabla
            ctx.strokeStyle = '#000000';
            ctx.lineWidth = 1;
            ctx.strokeRect(padding, y, anchoTabla, encabezadoAltura);
            ctx.font = `700 15px ${FUENTE_REPORTE}`;
            ctx.textAlign = 'center';
            let x = padding;
            columnas.forEach((col, i) => {
                ctx.fillText(col, x + anchosCol[i] / 2, y + encabezadoAltura / 2);
                x += anchosCol[i];
            });

            // Escala del gráfico, en la misma franja que el encabezado
            ctx.font = `400 13px ${FUENTE_REPORTE}`;
            ticks.forEach(t => {
                ctx.fillStyle = '#444444';
                ctx.fillText(formatear(t, decimalesTick, decimalesTick), xDeValor(t), y + encabezadoAltura / 2 + 4);
            });
            ctx.textAlign = 'left';
            ctx.fillText('% Cl', xDeValor(escalaMaxima) + 14, y + encabezadoAltura / 2 + 4);

            const yFilas = y + encabezadoAltura;
            const yFin = yFilas + CANTIDAD_PERFORACIONES * filaAltura;

            // Grilla del gráfico (recesiva) y línea base en 0
            ctx.lineWidth = 1;
            ticks.forEach((t, i) => {
                ctx.strokeStyle = i === 0 ? '#000000' : '#dddddd';
                ctx.beginPath();
                ctx.moveTo(Math.round(xDeValor(t)) + 0.5, yFilas);
                ctx.lineTo(Math.round(xDeValor(t)) + 0.5, yFin);
                ctx.stroke();
            });

            y = yFilas;
            filasPorPunto[indicePunto].forEach((fila, i) => {
                if (i % 2 === 1) {
                    ctx.fillStyle = '#f0f0f0';
                    ctx.fillRect(padding, y, anchoTabla, filaAltura);
                }
                ctx.strokeStyle = '#000000';
                ctx.strokeRect(padding, y, anchoTabla, filaAltura);

                ctx.fillStyle = '#000000';
                ctx.font = `400 15px ${FUENTE_REPORTE}`;
                ctx.textAlign = 'center';
                let xFila = padding;
                fila.forEach((valor, c) => {
                    ctx.fillText(String(valor), xFila + anchosCol[c] / 2, y + filaAltura / 2);
                    xFila += anchosCol[c];
                });

                // Barra horizontal de la perforación
                const valor = (punto.muestras || [])[i]?.cloruro;
                if (valor !== null && valor !== undefined) {
                    const ancho = Math.max(1, xDeValor(valor) - xGrafico);
                    const yBarra = y + (filaAltura - altoBarra) / 2;
                    const radio = Math.min(4, ancho);
                    ctx.fillStyle = colorBarra;
                    ctx.beginPath();
                    ctx.moveTo(xGrafico + 1, yBarra);
                    ctx.lineTo(xGrafico + ancho - radio, yBarra);
                    ctx.quadraticCurveTo(xGrafico + ancho, yBarra, xGrafico + ancho, yBarra + radio);
                    ctx.lineTo(xGrafico + ancho, yBarra + altoBarra - radio);
                    ctx.quadraticCurveTo(xGrafico + ancho, yBarra + altoBarra, xGrafico + ancho - radio, yBarra + altoBarra);
                    ctx.lineTo(xGrafico + 1, yBarra + altoBarra);
                    ctx.closePath();
                    ctx.fill();

                    ctx.fillStyle = '#000000';
                    ctx.font = `700 13px ${FUENTE_REPORTE}`;
                    ctx.textAlign = 'left';
                    ctx.fillText(`${formatear(valor, 2, 3)}%`, xGrafico + ancho + 6, y + filaAltura / 2);
                }
                y += filaAltura;
            });

            // Líneas verticales entre columnas de la tabla
            ctx.strokeStyle = '#000000';
            let xLinea = padding;
            ctx.beginPath();
            anchosCol.forEach(ancho => {
                ctx.moveTo(xLinea, yTabla);
                ctx.lineTo(xLinea, yFin);
                xLinea += ancho;
            });
            ctx.moveTo(xLinea, yTabla);
            ctx.lineTo(xLinea, yFin);
            ctx.stroke();

            y = yFin + separacionPuntos;
        });

        ctx.textAlign = 'left';
        return canvas.toDataURL('image/png');
    }
</script>
</body>
</html>
