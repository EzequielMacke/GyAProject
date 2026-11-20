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

<script>
(function () {
    const PUEDE_AGREGAR = @json((bool) app(\App\Services\PermisoService::class)->puede('sal', 'agregar'));
    const URL_CREAR     = @json(route('salidas.create'));
    const CONTEOS       = @json($conteos); // { 'AAAA-MM-DD': cantidad de salidas }

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
            if (PUEDE_AGREGAR) {
                cell.href = `${URL_CREAR}?fecha=${iso(d)}`;
                cell.title = `Cargar salidas del ${d.getDate()} de ${MESES[d.getMonth()].toLowerCase()}`;
            }
            const n = CONTEOS[iso(d)] || 0;
            if (n) cell.classList.add('con-salidas');
            cell.innerHTML = `<span class="cal-num">${d.getDate()}</span>` +
                (d.getTime() === hoy.getTime() ? '<span class="cal-tag">Hoy</span>' : '') +
                (n ? `<span class="cal-cant"><i class="fas fa-sign-out-alt"></i> ${n} ${n === 1 ? 'salida' : 'salidas'}</span>` : '') +
                (PUEDE_AGREGAR ? '<span class="cal-plus"><i class="fas fa-plus"></i></span>' : '');
            grid.appendChild(cell);
        }
    }

    $('cal-prev').addEventListener('click', () => { actual.setMonth(actual.getMonth() - 1); render(); });
    $('cal-next').addEventListener('click', () => { actual.setMonth(actual.getMonth() + 1); render(); });
    $('cal-hoy').addEventListener('click', () => { actual = new Date(hoy.getFullYear(), hoy.getMonth(), 1); render(); });

    document.addEventListener('keydown', e => {
        if (e.target.matches('input, textarea')) return;
        if (e.key === 'ArrowLeft')  $('cal-prev').click();
        if (e.key === 'ArrowRight') $('cal-next').click();
    });

    render();
})();
</script>
</body>
</html>
