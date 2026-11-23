<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salidas</title>
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
            --accent-s: #e8f0fc;
            --accent-b: #1f5bbf;
            --blue-s:   #e8f0fc;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

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
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

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

        /* ── Calendario ── */
        .cal {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            overflow: hidden;
        }
        .cal-head {
            display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;
            padding: 0.9rem 1rem;
            border-bottom: 1.5px solid var(--border);
        }
        .cal-nav { display: flex; align-items: center; gap: 0.4rem; }
        .cal-btn {
            width: 34px; height: 34px; border-radius: 0.5rem;
            border: 1.5px solid var(--border); background: #fff; color: var(--text2);
            cursor: pointer; transition: all 0.14s;
        }
        .cal-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-s); }
        .cal-mes { font-size: 1.15rem; font-weight: 700; color: var(--text); margin: 0 0 0 0.5rem; letter-spacing: -0.2px; }

        .cal-semana, .cal-grid { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); }
        .cal-semana {
            background: var(--surface2);
            border-bottom: 1.5px solid var(--border);
        }
        .cal-semana div {
            padding: 0.55rem 0.7rem;
            font-size: 0.7rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        .cal-semana div.finde { color: #b7c3d3; }

        .cal-dia {
            position: relative;
            min-height: 96px;
            padding: 0.55rem 0.65rem;
            border-right: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            background: #fff;
            display: flex; flex-direction: column; align-items: flex-start; gap: 0.3rem;
            text-decoration: none !important; color: var(--text);
            transition: background 0.12s;
        }
        .cal-dia:nth-child(7n) { border-right: none; }
        .cal-dia.finde { background: #fbfcfd; }
        .cal-dia.fuera { background: var(--surface); }
        .cal-dia.fuera .cal-num { color: #c4cfdc; }
        .cal-dia.pasado:not(.fuera) .cal-num { color: var(--muted); }

        .cal-num {
            font-size: 0.85rem; font-weight: 700; color: var(--text);
            width: 28px; height: 28px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .cal-dia.hoy .cal-num { background: var(--accent); color: #fff; }
        .cal-tag {
            font-size: 0.65rem; font-weight: 700; color: var(--accent);
            text-transform: uppercase; letter-spacing: 0.05em;
        }

        .cal-plus {
            position: absolute; right: 0.55rem; bottom: 0.55rem;
            width: 26px; height: 26px; border-radius: 0.45rem;
            background: var(--accent); color: #fff; font-size: 0.7rem;
            display: inline-flex; align-items: center; justify-content: center;
            opacity: 0; transform: scale(0.85); transition: all 0.14s;
        }
        a.cal-dia { cursor: pointer; }
        a.cal-dia:hover { background: var(--accent-s); }
        a.cal-dia:hover .cal-plus { opacity: 1; transform: scale(1); }

        .cal-cant {
            display: inline-flex; align-items: center; gap: 0.3rem;
            font-size: 0.7rem; font-weight: 700; color: var(--accent);
            background: var(--accent-s); border-radius: 0.4rem;
            padding: 0.2rem 0.45rem;
        }
        .cal-cant i { font-size: 0.6rem; }

        .cal-ayuda { font-size: 0.78rem; color: var(--muted); margin: 0.75rem 0.25rem 0; }

        /* ── Modal: cómo iniciar un día sin salidas ── */
        .modal-ini {
            position: fixed; inset: 0; z-index: 1060;
            background: rgba(15,23,42,0.45);
            display: flex; align-items: center; justify-content: center;
            padding: 1rem;
        }
        .modal-ini[hidden] { display: none; }
        .mi-caja {
            width: 100%; max-width: 440px;
            background: #fff; border-radius: 0.9rem;
            box-shadow: 0 20px 50px rgba(15,23,42,0.25);
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow: hidden;
        }
        .mi-head {
            display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;
            padding: 1rem 1.1rem 0.4rem;
        }
        .mi-titulo { font-size: 1.05rem; font-weight: 700; color: var(--text); }
        .mi-sub { font-size: 0.78rem; color: var(--muted); margin-top: 0.15rem; }
        .mi-x {
            width: 32px; height: 32px; flex-shrink: 0; border-radius: 0.5rem;
            border: none; background: var(--surface2); color: var(--text2); cursor: pointer;
        }
        .mi-x:hover { background: var(--border); }
        .mi-body { padding: 0.75rem 1.1rem 1.1rem; display: flex; flex-direction: column; gap: 0.55rem; }

        .mi-op {
            width: 100%; text-align: left;
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.7rem 0.8rem; border-radius: 0.65rem;
            border: 1.5px solid var(--border); background: #fff; color: var(--text);
            cursor: pointer; transition: all 0.14s;
            font-family: inherit;
        }
        .mi-op:hover:not(:disabled) { border-color: var(--accent); background: var(--accent-s); }
        .mi-op:disabled { opacity: 0.55; cursor: not-allowed; }
        .mi-ic {
            width: 34px; height: 34px; flex-shrink: 0; border-radius: 0.5rem;
            background: var(--accent-s); color: var(--accent);
            display: inline-flex; align-items: center; justify-content: center;
        }
        .mi-op strong, .mi-otra strong { display: block; font-size: 0.85rem; font-weight: 700; color: var(--text); }
        .mi-op small, .mi-otra small { display: block; font-size: 0.72rem; color: var(--muted); font-weight: 500; }

        .mi-otra {
            border: 1.5px solid var(--border); border-radius: 0.65rem;
            padding: 0.7rem 0.8rem;
            display: flex; flex-direction: column; gap: 0.55rem;
        }
        .mi-otra-titulo { display: flex; align-items: center; gap: 0.75rem; }
        .mi-otra .btn { height: 36px; width: 100%; justify-content: center; }
        .mi-otra .btn:disabled { opacity: 0.55; cursor: not-allowed; }

        /* Mini calendario para elegir la fecha a copiar */
        .mc-head { display: flex; align-items: center; justify-content: space-between; }
        .mc-mes { font-size: 0.85rem; font-weight: 700; color: var(--text); }
        .mc-btn {
            width: 28px; height: 28px; border-radius: 0.4rem;
            border: 1.5px solid var(--border); background: #fff; color: var(--text2);
            font-size: 0.7rem; cursor: pointer;
        }
        .mc-btn:hover { border-color: var(--accent); color: var(--accent); }
        .mc-semana, .mc-grid { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 3px; }
        .mc-semana div {
            text-align: center; font-size: 0.62rem; font-weight: 700;
            color: var(--muted); text-transform: uppercase; padding: 0.15rem 0;
        }
        .mc-dia {
            height: 38px; border-radius: 0.45rem;
            border: 1.5px solid transparent; background: transparent;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            font-family: inherit; font-size: 0.78rem; font-weight: 600; color: #b7c3d3;
            line-height: 1.1; padding: 0;
        }
        .mc-dia.fuera { visibility: hidden; }
        .mc-dia.con {
            background: var(--accent-s); color: var(--accent); cursor: pointer;
        }
        .mc-dia.con:hover { border-color: var(--accent); }
        .mc-dia .mc-n { font-size: 0.56rem; font-weight: 700; opacity: 0.85; }
        .mc-dia.sel { background: var(--accent); color: #fff; border-color: var(--accent); }
        .mc-dia.destino { border: 1.5px dashed var(--border2); color: var(--text2); }

        @media (max-width: 600px) {
            .ph-sub { display: none; }
            .cal-head { padding: 0.7rem; }
            .cal-mes { font-size: 1rem; margin-left: 0.25rem; }
            .cal-semana div { padding: 0.45rem 0; text-align: center; font-size: 0.62rem; }
            .cal-dia {
                min-height: 54px; padding: 0.35rem 0;
                align-items: center; justify-content: flex-start;
            }
            .cal-num { font-size: 0.8rem; width: 26px; height: 26px; }
            .cal-tag, .cal-plus, .cal-cant { display: none; }
            /* En celular: un puntito debajo del número si el día tiene salidas */
            .cal-dia.con-salidas::after {
                content: ""; width: 6px; height: 6px; border-radius: 50%;
                background: var(--accent);
            }
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
                            <i class="fas fa-sign-out-alt"></i>
                            Salidas
                        </div>
                        <h1 class="ph-title"><em>Salidas</em></h1>
                        <p class="ph-sub">Elegí un día del calendario para cargar sus salidas</p>
                    </div>
                    <div class="ph-right">
                        @permiso('sal', 'agregar')
                        <a href="{{ route('salidas.create', ['fecha' => now()->format('Y-m-d')]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Salidas de hoy
                        </a>
                        @endpermiso
                        <a href="{{ route('home') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="cal">
                    <div class="cal-head">
                        <div class="cal-nav">
                            <button type="button" class="cal-btn" id="cal-prev" title="Mes anterior"><i class="fas fa-chevron-left"></i></button>
                            <button type="button" class="cal-btn" id="cal-next" title="Mes siguiente"><i class="fas fa-chevron-right"></i></button>
                            <h2 class="cal-mes" id="cal-mes"></h2>
                        </div>
                        <button type="button" class="btn" id="cal-hoy"><i class="fas fa-calendar-day"></i> Hoy</button>
                    </div>
                    <div class="cal-semana" id="cal-semana"></div>
                    <div class="cal-grid" id="cal-grid"></div>
                </div>

                <p class="cal-ayuda">
                    @permiso('sal', 'agregar')
                        <i class="fas fa-info-circle"></i> Tocá un día para cargar sus salidas.
                    @endpermiso
                </p>

            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

{{-- Modal: cómo iniciar un día que todavía no tiene salidas --}}
<div class="modal-ini" id="modal-ini" hidden>
    <div class="mi-caja" role="dialog" aria-modal="true" aria-labelledby="mi-titulo">
        <div class="mi-head">
            <div>
                <div class="mi-titulo" id="mi-titulo">Cargar salidas</div>
                <div class="mi-sub">Este día todavía no tiene salidas. ¿Cómo querés empezar?</div>
            </div>
            <button type="button" class="mi-x" id="mi-cerrar" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="mi-body">
            <button type="button" class="mi-op" id="mi-vacio">
                <span class="mi-ic"><i class="far fa-file"></i></span>
                <span><strong>Vacío</strong><small>Empezar con el tablero en blanco</small></span>
            </button>
            <button type="button" class="mi-op" id="mi-anterior">
                <span class="mi-ic"><i class="fas fa-history"></i></span>
                <span><strong>Igual al día anterior</strong><small id="mi-anterior-txt"></small></span>
            </button>
            <div class="mi-otra">
                <div class="mi-otra-titulo">
                    <span class="mi-ic"><i class="fas fa-copy"></i></span>
                    <span><strong>Igual a otra fecha</strong><small>Tocá un día marcado (los que tienen salidas) y después lo modificás</small></span>
                </div>
                <div class="mc-head">
                    <button type="button" class="mc-btn" id="mc-prev" title="Mes anterior"><i class="fas fa-chevron-left"></i></button>
                    <span class="mc-mes" id="mc-mes"></span>
                    <button type="button" class="mc-btn" id="mc-next" title="Mes siguiente"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="mc-semana" id="mc-semana"></div>
                <div class="mc-grid" id="mc-grid"></div>
                <button type="button" class="btn btn-primary" id="mi-copiar" disabled>
                    <i class="fas fa-copy"></i> <span id="mi-copiar-txt">Elegí un día marcado</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const PUEDE_AGREGAR = @json((bool) app(\App\Services\PermisoService::class)->puede('sal', 'agregar'));
    const URL_CREAR     = @json(route('salidas.create'));
    const CONTEOS       = @json($conteos); // { 'AAAA-MM-DD': cantidad de salidas }
    // Fechas con salidas, de la más reciente a la más vieja
    const FECHAS_CON_SALIDAS = Object.keys(CONTEOS).filter(f => CONTEOS[f] > 0).sort().reverse();

    const MESES = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    const DIAS  = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];

    const hoy = new Date(); hoy.setHours(0, 0, 0, 0);
    const iso = d => `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;

    // Mes visible (se recuerda en la URL con #AAAA-MM para volver al mismo mes)
    let actual = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
    const m = location.hash.match(/^#(\d{4})-(\d{2})$/);
    if (m) actual = new Date(+m[1], +m[2] - 1, 1);

    const $ = id => document.getElementById(id);

    $('cal-semana').innerHTML = DIAS.map((d, i) => `<div class="${i >= 5 ? 'finde' : ''}">${d}</div>`).join('');

    function render() {
        const y = actual.getFullYear(), mo = actual.getMonth();
        $('cal-mes').textContent = `${MESES[mo]} ${y}`;
        history.replaceState(null, '', `#${y}-${String(mo + 1).padStart(2, '0')}`);

        const grid = $('cal-grid');
        grid.innerHTML = '';

        // Lunes como primer día de la semana
        const offset = (new Date(y, mo, 1).getDay() + 6) % 7;
        const inicio = new Date(y, mo, 1 - offset);
        const semanas = Math.ceil((offset + new Date(y, mo + 1, 0).getDate()) / 7);

        for (let i = 0; i < semanas * 7; i++) {
            const d = new Date(inicio.getFullYear(), inicio.getMonth(), inicio.getDate() + i);
            const cls = ['cal-dia'];
            if (d.getMonth() !== mo) cls.push('fuera');
            if (d.getTime() === hoy.getTime()) cls.push('hoy');
            if (d < hoy) cls.push('pasado');
            if (i % 7 >= 5) cls.push('finde');

            const cell = document.createElement(PUEDE_AGREGAR ? 'a' : 'div');
            cell.className = cls.join(' ');
            const n = CONTEOS[iso(d)] || 0;
            if (PUEDE_AGREGAR) {
                cell.href = urlDia(iso(d));
                cell.title = `Cargar salidas del ${d.getDate()} de ${MESES[d.getMonth()].toLowerCase()}`;
                // Día sin salidas: preguntar cómo empezar (si hay algún día de dónde copiar)
                if (!n && FECHAS_CON_SALIDAS.length) {
                    cell.addEventListener('click', e => { e.preventDefault(); abrirModal(iso(d)); });
                }
            }
            if (n) cell.classList.add('con-salidas');
            cell.innerHTML = `<span class="cal-num">${d.getDate()}</span>` +
                (d.getTime() === hoy.getTime() ? '<span class="cal-tag">Hoy</span>' : '') +
                (n ? `<span class="cal-cant"><i class="fas fa-sign-out-alt"></i> ${n} ${n === 1 ? 'salida' : 'salidas'}</span>` : '') +
                (PUEDE_AGREGAR ? '<span class="cal-plus"><i class="fas fa-plus"></i></span>' : '');
            grid.appendChild(cell);
        }
    }

    // ── Modal para iniciar un día sin salidas ──
    function urlDia(fecha, copiar) {
        const u = new URL(URL_CREAR, location.origin);
        u.searchParams.set('fecha', fecha);
        if (copiar) u.searchParams.set('copiar', copiar);
        return u.toString();
    }

    const DIAS_LARGO = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
    const desdeIso = f => { const [y, mo, d] = f.split('-').map(Number); return new Date(y, mo - 1, d); };
    const textoFecha = f => {
        const d = desdeIso(f);
        return `${DIAS_LARGO[d.getDay()]} ${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
    };
    const textoCant = n => `${n} ${n === 1 ? 'salida' : 'salidas'}`;

    let fechaModal = null;
    let fechaAnterior = null;
    let fechaCopiar = null;   // fecha elegida en el mini calendario
    let mcMes = null;         // mes visible del mini calendario

    $('mc-semana').innerHTML = DIAS.map(d => `<div>${d}</div>`).join('');

    function renderMini() {
        const y = mcMes.getFullYear(), mo = mcMes.getMonth();
        $('mc-mes').textContent = `${MESES[mo]} ${y}`;

        const grid = $('mc-grid');
        grid.innerHTML = '';
        const offset = (new Date(y, mo, 1).getDay() + 6) % 7;
        const total = new Date(y, mo + 1, 0).getDate();
        const celdas = Math.ceil((offset + total) / 7) * 7;

        for (let i = 0; i < celdas; i++) {
            const dia = i - offset + 1;
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'mc-dia';
            if (dia < 1 || dia > total) { b.classList.add('fuera'); b.disabled = true; grid.appendChild(b); continue; }

            const f = iso(new Date(y, mo, dia));
            const n = CONTEOS[f] || 0;
            b.innerHTML = `<span>${dia}</span>` + (n ? `<span class="mc-n">${n}</span>` : '');
            if (f === fechaModal) { b.classList.add('destino'); b.title = 'Día que estás cargando'; }
            if (n) {
                b.classList.add('con');
                b.title = `${textoFecha(f)} · ${textoCant(n)}`;
                if (f === fechaCopiar) b.classList.add('sel');
                b.addEventListener('click', () => { fechaCopiar = f; renderMini(); });
            } else {
                b.disabled = true;
            }
            grid.appendChild(b);
        }

        $('mi-copiar').disabled = !fechaCopiar;
        $('mi-copiar-txt').textContent = fechaCopiar
            ? `Copiar del ${textoFecha(fechaCopiar)}`
            : 'Elegí un día marcado';
    }

    $('mc-prev').addEventListener('click', () => { mcMes.setMonth(mcMes.getMonth() - 1); renderMini(); });
    $('mc-next').addEventListener('click', () => { mcMes.setMonth(mcMes.getMonth() + 1); renderMini(); });

    function abrirModal(fecha) {
        fechaModal = fecha;
        const d = desdeIso(fecha);
        $('mi-titulo').textContent = `Salidas del ${DIAS_LARGO[d.getDay()]} ${d.getDate()} de ${MESES[d.getMonth()].toLowerCase()}`;

        // Último día con salidas antes de esta fecha
        fechaAnterior = FECHAS_CON_SALIDAS.find(f => f < fecha) || null;
        $('mi-anterior').disabled = !fechaAnterior;
        $('mi-anterior-txt').textContent = fechaAnterior
            ? `Copiar del ${textoFecha(fechaAnterior)} (${textoCant(CONTEOS[fechaAnterior])})`
            : 'No hay días anteriores con salidas';

        // Mini calendario: arranca en el mes del día elegido, sin selección
        fechaCopiar = null;
        mcMes = new Date(d.getFullYear(), d.getMonth(), 1);
        renderMini();

        $('modal-ini').hidden = false;
        $('mi-vacio').focus();
    }

    function cerrarModal() {
        $('modal-ini').hidden = true;
        fechaModal = null;
    }

    $('mi-vacio').addEventListener('click', () => { location.href = urlDia(fechaModal); });
    $('mi-anterior').addEventListener('click', () => { if (fechaAnterior) location.href = urlDia(fechaModal, fechaAnterior); });
    $('mi-copiar').addEventListener('click', () => {
        if (fechaCopiar) location.href = urlDia(fechaModal, fechaCopiar);
    });
    $('mi-cerrar').addEventListener('click', cerrarModal);
    $('modal-ini').addEventListener('click', e => { if (e.target.id === 'modal-ini') cerrarModal(); });

    $('cal-prev').addEventListener('click', () => { actual.setMonth(actual.getMonth() - 1); render(); });
    $('cal-next').addEventListener('click', () => { actual.setMonth(actual.getMonth() + 1); render(); });
    $('cal-hoy').addEventListener('click', () => { actual = new Date(hoy.getFullYear(), hoy.getMonth(), 1); render(); });

    document.addEventListener('keydown', e => {
        if (!$('modal-ini').hidden) { if (e.key === 'Escape') cerrarModal(); return; }
        if (e.target.matches('input, textarea, select')) return;
        if (e.key === 'ArrowLeft')  $('cal-prev').click();
        if (e.key === 'ArrowRight') $('cal-next').click();
    });

    render();
})();
</script>
</body>
</html>
