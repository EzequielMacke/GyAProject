<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Informe</title>
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
        }

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
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; margin: 0; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        .btn {
            height: 38px; padding: 0 1rem; border-radius: 0.55rem;
            display: inline-flex; align-items: center; gap: 0.42rem;
            font-size: 0.825rem; font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface); color: var(--text2);
            text-decoration: none; cursor: pointer;
            transition: all 0.14s; white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover { background: #1f5bbf; border-color: #1f5bbf; color: #fff; }

        /* Editor: contenido de la parte a la izquierda, partes del informe a la derecha */
        .editor { display: flex; gap: 1.25rem; align-items: flex-start; }
        .editor-main { flex: 1; min-width: 0; }
        .editor-side { width: 260px; flex-shrink: 0; position: sticky; top: 1rem; }

        .card-box {
            background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 0.75rem; overflow: hidden;
        }
        .card-box-head {
            padding: 0.85rem 1.1rem; border-bottom: 1.5px solid var(--border);
            font-size: 0.72rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.6px;
        }

        .partes { list-style: none; margin: 0; padding: 0.5rem; }
        .parte {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.6rem 0.75rem; border-radius: 0.5rem;
            font-size: 0.85rem; font-weight: 600; color: var(--text2);
            cursor: pointer; transition: all 0.14s;
        }
        .parte:hover { background: var(--surface2); color: var(--text); }
        .parte.activa { background: var(--accent-s); color: var(--accent); }

        .panel { display: none; }
        .panel.activo { display: block; }
        .panel-body { padding: 1.25rem 1.1rem; }

        .vacio { padding: 3rem 1rem; text-align: center; color: var(--muted); font-size: 0.85rem; }
        .vacio i { font-size: 1.8rem; display: block; margin-bottom: 0.75rem; opacity: 0.6; }

        .campo label { font-size: 0.78rem; font-weight: 600; color: var(--text2); margin-bottom: 0.35rem; display: block; }
        .campo .form-control, .campo .form-select {
            font-size: 0.85rem; border: 1.5px solid var(--border); border-radius: 0.5rem;
        }
        .campo .opcional { font-weight: 400; color: var(--muted); }
        .campo .ayuda { display: block; margin-top: 0.35rem; font-size: 0.74rem; color: var(--muted); }
        .campo .form-control::placeholder { color: #b0bccb; }
        .campo .form-control:focus, .campo .form-select:focus {
            border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-s);
        }

        .vista-previa {
            margin-top: 1.25rem; padding: 1rem 1.1rem;
            background: #fff; border: 1.5px dashed var(--border2); border-radius: 0.6rem;
        }
        .vista-previa-head {
            font-size: 0.72rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 0.6rem;
        }
        .vista-previa p { margin: 0; font-size: 0.9rem; line-height: 1.6; color: var(--text); text-align: justify; }
        /* Lo que falta cargar se ve resaltado, para saber qué completar */
        .vista-previa .falta { background: #fff4d6; color: #a07400; border-radius: 0.25rem; padding: 0 0.25rem; }

        @media (max-width: 991px) {
            .editor { flex-direction: column-reverse; }
            .editor-side { width: 100%; position: static; }
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
                            <i class="fas fa-file-word"></i>
                            Generador de Informe
                        </div>
                        <h1 class="ph-title"><em>Informe</em></h1>
                    </div>
                    <div class="ph-right">
                        <button type="submit" form="form-informe" class="btn btn-primary" id="btn-generar">
                            <i class="fas fa-file-word"></i> Generar
                        </button>
                        <a href="{{ route('home') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="editor">
                    <div class="editor-main">
                        {{-- El botón Generar del encabezado envía este formulario (atributo form). --}}
                        <form id="form-informe" method="POST" action="{{ route('informe.generador.generar') }}" class="card-box">
                            @csrf
                            <div class="panel activo" id="panel-vacio">
                                <div class="vacio">
                                    <i class="fas fa-hand-pointer"></i>
                                    Seleccioná una parte del informe para editarla.
                                </div>
                            </div>

                            <div class="panel" id="panel-caratula">
                                <div class="card-box-head">Carátula</div>
                                <div class="panel-body">
                                    <div class="row g-3">
                                        <div class="col-12 campo">
                                            <label for="car-titulo">Título del informe</label>
                                            <input type="text" class="form-control" id="car-titulo" name="titulo" placeholder="Ej.: Evaluación estructural de losas del Sector B">
                                            <small class="ayuda">Busca que la persona que lea el título sepa de qué trata el informe sin abrirlo: qué se hizo, sobre qué elemento y en qué sector.</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="car-obra">Obra</label>
                                            <input type="text" class="form-control" id="car-obra" name="obra" placeholder="Ej.: Edificio Torre Central">
                                            <small class="ayuda">Nombre del lugar físico en el que se realizó el trabajo.</small>
                                        </div>
                                        <div class="col-md-6 campo">
                                            <label for="car-mes">Mes</label>
                                            <select class="form-select" id="car-mes" name="mes">
                                                <option value="">Seleccionar...</option>
                                                @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $mes)
                                                    <option value="{{ $mes }}">{{ $mes }}</option>
                                                @endforeach
                                            </select>
                                            <small class="ayuda">Mes en el que se desarrollaron los trabajos.</small>
                                        </div>
                                        <div class="col-md-6 campo">
                                            <label for="car-anio">Año</label>
                                            <input type="number" class="form-control" id="car-anio" name="anio" value="{{ date('Y') }}">
                                            <small class="ayuda">Año en el que se desarrollaron los trabajos.</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="car-peticionario">Peticionario</label>
                                            <input type="text" class="form-control" id="car-peticionario" name="peticionario" placeholder="Ej.: Constructora del Sur S.A.">
                                            <small class="ayuda">Nombre de la persona o entidad que financia el trabajo.</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="car-referencia">Referencia</label>
                                            <input type="text" class="form-control" id="car-referencia" name="referencia" placeholder="Ej.: INF.: 55-25">
                                            <small class="ayuda">Se usa para encontrar el informe rápidamente en el gestor de archivos. En el ejemplo, 55 es el número de informe y 25 el año.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel" id="panel-info-general">
                                <div class="card-box-head">Información general</div>
                                <div class="panel-body">
                                    <div class="row g-3">
                                        <div class="col-12 campo">
                                            <label for="ig-fp-dia">Fecha de envío del presupuesto</label>
                                            <div class="row g-2">
                                                <div class="col-3">
                                                    <select class="form-select" id="ig-fp-dia" name="presupuesto_dia" aria-label="Día">
                                                        <option value="">Día</option>
                                                        @for ($d = 1; $d <= 31; $d++)
                                                            <option value="{{ $d }}">{{ $d }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-5">
                                                    <select class="form-select" id="ig-fp-mes" name="presupuesto_mes" aria-label="Mes">
                                                        <option value="">Mes</option>
                                                        @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $mes)
                                                            <option value="{{ $mes }}">{{ $mes }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <select class="form-select" id="ig-fp-anio" name="presupuesto_anio" aria-label="Año">
                                                        @for ($a = date('Y') + 1; $a >= date('Y') - 5; $a--)
                                                            <option value="{{ $a }}" @selected($a == date('Y'))>{{ $a }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="ig-motivo-presupuesto">Motivo del presupuesto</label>
                                            <textarea class="form-control" id="ig-motivo-presupuesto" name="motivo_presupuesto" rows="3" placeholder="Ej.: la evaluación de la estructura de hormigón armado perteneciente al Edificio Torre Central"></textarea>
                                            <small class="ayuda">Para qué se envió el presupuesto. Completa la frase "hemos presentado una propuesta técnica para…".</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="ig-ubicacion-obra">Ubicación de la obra</label>
                                            <input type="text" class="form-control" id="ig-ubicacion-obra" name="ubicacion_obra" placeholder="Ej.: la Avda. Principal N° 1234 e/ Calle Uno y Calle Dos de la ciudad de San Lorenzo">
                                            <small class="ayuda">Dónde se encuentra la obra. Completa la frase "ubicado sobre…".</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="ig-info-adicional">Información adicional <span class="opcional">(opcional)</span></label>
                                            <textarea class="form-control" id="ig-info-adicional" name="info_adicional" rows="4" placeholder="Ej.: Se solicitó el servicio de verificación de la capacidad portante de la estructura existente del galpón de depósito, ante la instalación de un nuevo equipo sobre la cubierta. El estudio debe comprender todas las tareas de ingeniería necesarias para verificar la aptitud de la estructura."></textarea>
                                            <small class="ayuda">Detalle del servicio solicitado y su alcance: qué pidió el cliente, por qué motivo y qué debe abarcar el estudio. Va como un párrafo aparte, después del anterior.</small>
                                        </div>
                                    </div>

                                    <div class="vista-previa">
                                        <div class="vista-previa-head"><i class="fas fa-eye"></i> Así queda el texto</div>
                                        <p id="ig-vista-previa"></p>
                                        <p id="ig-vista-previa-adicional" class="mt-2" hidden></p>
                                    </div>
                                </div>
                            </div>

                            <div class="panel" id="panel-objetivo">
                                <div class="card-box-head">Objetivo y alcance</div>
                                <div class="panel-body">
                                    <div class="row g-3">
                                        <div class="col-12 campo">
                                            <label for="obj-texto">Objetivo y alcance del informe</label>
                                            <textarea class="form-control" id="obj-texto" name="objetivo_alcance" rows="5" placeholder="Ej. 1: El presente informe tiene por finalidad evaluar el estado estructural de las columnas de hormigón armado del galpón de depósito, a fin de determinar su condición estructural y su aptitud para continuar en servicio.&#10;&#10;Ej. 2: El presente informe tiene por objeto presentar los resultados de la evaluación de patologías en la estructura de hormigón armado en diversos sectores, a fin de determinar la trascendencia estructural de los daños visualizados."></textarea>
                                            <small class="ayuda">Qué se busca evaluar con el informe, sobre qué elementos y para qué. Cada renglón se convierte en un párrafo del Word.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <aside class="editor-side">
                        <div class="card-box">
                            <div class="card-box-head">Partes del informe</div>
                            <ul class="partes">
                                <li class="parte" data-panel="panel-caratula">
                                    <i class="fas fa-file-alt"></i> Carátula
                                </li>
                                <li class="parte" data-panel="panel-info-general">
                                    <i class="fas fa-info-circle"></i> Información general
                                </li>
                                <li class="parte" data-panel="panel-objetivo">
                                    <i class="fas fa-bullseye"></i> Objetivo y alcance
                                </li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
    @include('partials.footer')
</div>
<script>
    // Al tocar una parte del informe se muestra su panel y se marca como activa.
    document.querySelectorAll('.parte').forEach(function (parte) {
        parte.addEventListener('click', function () {
            document.querySelectorAll('.parte').forEach(p => p.classList.remove('activa'));
            document.querySelectorAll('.panel').forEach(p => p.classList.remove('activo'));
            parte.classList.add('activa');
            document.getElementById(parte.dataset.panel).classList.add('activo');
        });
    });

    // Vista previa del párrafo de Información general, se arma mientras se carga.
    (function () {
        const dia = document.getElementById('ig-fp-dia');
        const mes = document.getElementById('ig-fp-mes');
        const anio = document.getElementById('ig-fp-anio');
        const motivo = document.getElementById('ig-motivo-presupuesto');
        const ubicacion = document.getElementById('ig-ubicacion-obra');
        const adicional = document.getElementById('ig-info-adicional');
        const vista = document.getElementById('ig-vista-previa');
        const vistaAdicional = document.getElementById('ig-vista-previa-adicional');

        // Va armando el párrafo con nodos de texto (no innerHTML) para que lo
        // que escribe el usuario nunca se interprete como HTML.
        function agregar(texto) {
            vista.appendChild(document.createTextNode(texto));
        }
        function agregarDato(valor, faltante) {
            if (valor) {
                agregar(valor);
                return;
            }
            const span = document.createElement('span');
            span.className = 'falta';
            span.textContent = faltante;
            vista.appendChild(span);
        }

        function actualizar() {
            const fecha = dia.value && mes.value && anio.value
                ? dia.value + ' de ' + mes.value.toLowerCase() + ' de ' + anio.value
                : '';
            // Sin punto doble si la ubicación ya termina en punto.
            const lugar = ubicacion.value.trim().replace(/\.+$/, '');

            vista.textContent = '';
            agregar('En fecha ');
            agregarDato(fecha, '[fecha]');
            agregar(' hemos presentado una propuesta técnica para ');
            agregarDato(motivo.value.trim(), '[motivo del presupuesto]');
            agregar(' ubicado sobre ');
            agregarDato(lugar, '[ubicación de la obra]');
            agregar('.');

            // Información adicional: párrafo aparte, solo si se cargó.
            vistaAdicional.textContent = adicional.value.trim();
            vistaAdicional.hidden = !vistaAdicional.textContent;
        }

        [dia, mes, anio, motivo, ubicacion, adicional].forEach(function (campo) {
            campo.addEventListener('input', actualizar);
            campo.addEventListener('change', actualizar);
        });
        actualizar();
    })();
</script>
</body>
</html>
