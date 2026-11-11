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
        .parte[hidden] { display: none; }
        .subparte { padding-left: 1.9rem; font-size: 0.8rem; font-weight: 500; }

        /* Botones de Trabajos desarrollados: apagados por defecto */
        .trabajos-toggles { display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .trabajo-toggle { margin: 0; cursor: pointer; }
        .trabajo-toggle input { position: absolute; opacity: 0; pointer-events: none; }
        .trabajo-toggle span {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.45rem 0.8rem; border: 1.5px solid var(--border); border-radius: 999px;
            font-size: 0.8rem; font-weight: 600; color: var(--text2); background: var(--surface);
            transition: all 0.14s; user-select: none;
        }
        .trabajo-toggle span i { display: none; }
        .trabajo-toggle:hover span { border-color: var(--accent); }
        .trabajo-toggle input:checked + span { background: var(--accent); border-color: var(--accent); color: #fff; }
        .trabajo-toggle input:checked + span i { display: inline; }
        .trabajo-toggle input:focus-visible + span { box-shadow: 0 0 0 3px var(--accent-s); }
        .grupo + .grupo { margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border); }
        .grupo-titulo { font-size: 0.85rem; font-weight: 700; color: var(--text); margin-bottom: 0.75rem; }
        .documento {
            border: 1px solid var(--border); border-radius: 0.5rem;
            padding: 0.85rem; margin-bottom: 0.75rem;
        }
        .documento .btn-quitar { margin-top: 0.6rem; color: var(--muted); }

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

        /* Fotos de Características de la obra */
        .bloque-foto {
            border: 1px solid var(--border); border-radius: 0.5rem;
            padding: 0.85rem; margin-top: 0.75rem; background: #fff;
        }
        .bloque-foto-imagenes { display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; margin-bottom: 0.75rem; }
        .bloque-foto-imagenes img { height: 110px; max-width: 100%; border-radius: 0.35rem; object-fit: cover; }
        .bloque-foto-letra { align-self: flex-end; margin: 0 0.5rem 0 -0.25rem; font-size: 0.8rem; color: var(--text2); }
        .bloque-foto-pie { display: flex; gap: 0.5rem; align-items: center; }
        .bloque-foto-pie .form-control { flex: 1; }
        .bloque-foto .btn-quitar-bloque { color: var(--muted); }
        .bloque-foto-aviso { font-size: 0.78rem; color: #a07400; background: #fff4d6; border-radius: 0.35rem; padding: 0.35rem 0.6rem; margin-bottom: 0.6rem; }

        .alert { padding: 0.75rem 1rem; border-radius: 0.55rem; font-size: 0.83rem; font-weight: 500; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .alert-success { background: #e5f6f0; color: #1e9166; border: 1px solid #b6e8d6; }
        .alert-danger  { background: #fff0f0; color: #c0392b; border: 1px solid #f5c2c2; }

        .editor-side .card-box + .card-box { margin-top: 1rem; }
        .generar-box { padding: 0.85rem 1.1rem; display: flex; flex-direction: column; gap: 0.5rem; }
        .btn-generar { width: 100%; justify-content: center; background: #1e9166; border-color: #1e9166; color: #fff; }
        .btn-generar:hover { background: #187a56; border-color: #187a56; color: #fff; }
        .generar-box .ayuda { font-size: 0.74rem; color: var(--muted); line-height: 1.35; }
        #btn-agregar-imagen { margin-top: 0.75rem; }

        .modal-fotos {
            position: fixed; inset: 0; z-index: 1060; display: none;
            background: rgba(15, 23, 35, 0.55); padding: 1.5rem;
            align-items: center; justify-content: center;
        }
        .modal-fotos.abierto { display: flex; }
        .modal-fotos-caja {
            /* Tamaño fijo según la pantalla: no cambia al filtrar o elegir fotos. */
            background: var(--surface); border-radius: 0.75rem;
            width: min(1100px, 100%); height: 100%; display: flex; flex-direction: column; overflow: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .modal-fotos-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.85rem 1.1rem; border-bottom: 1.5px solid var(--border);
            font-weight: 700; color: var(--text);
        }
        .modal-fotos-cerrar { border: 0; background: none; font-size: 1.1rem; color: var(--muted); cursor: pointer; }
        .modal-fotos-filtros {
            display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;
            padding: 0.75rem 1.1rem; border-bottom: 1px solid var(--border);
        }
        .filtro-multi { position: relative; }
        .filtro-multi > summary {
            list-style: none; cursor: pointer; height: 36px; padding: 0 0.85rem;
            display: inline-flex; align-items: center; gap: 0.5rem;
            border: 1.5px solid var(--border); border-radius: 0.5rem; background: #fff;
            font-size: 0.8rem; font-weight: 600; color: var(--text2); min-width: 190px;
        }
        .filtro-multi > summary::-webkit-details-marker { display: none; }
        .filtro-multi > summary span { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 220px; }
        .filtro-multi-panel {
            position: absolute; top: calc(100% + 4px); left: 0; z-index: 5;
            background: #fff; border: 1.5px solid var(--border); border-radius: 0.5rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12); padding: 0.4rem; width: 280px;
            max-height: 300px; overflow-y: auto;
        }
        .filtro-multi-panel input[type="text"] {
            width: 100%; height: 32px; margin-bottom: 0.3rem; padding: 0 0.6rem;
            border: 1.5px solid var(--border); border-radius: 0.4rem; font-size: 0.8rem;
        }
        .filtro-multi-panel label {
            display: flex; align-items: center; gap: 0.5rem; margin: 0;
            padding: 0.35rem 0.5rem; border-radius: 0.35rem; font-size: 0.8rem; font-weight: 500; cursor: pointer;
        }
        .filtro-multi-panel label:hover { background: var(--surface2); }
        .modal-fotos-grilla {
            flex: 1; overflow-y: auto; padding: 1rem 1.1rem;
            display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.75rem;
            /* Sin esto, con muchas fotos las filas se achican para entrar en el
               alto del modal (las tarjetas tienen overflow hidden) en vez de scrollear. */
            grid-auto-rows: max-content;
            align-content: start; min-height: 200px;
        }
        .modal-foto {
            position: relative; border: 2px solid var(--border); border-radius: 0.5rem;
            overflow: hidden; cursor: pointer; background: #fff; transition: border-color 0.12s;
        }
        .modal-foto img { width: 100%; height: 120px; object-fit: cover; display: block; }
        .modal-foto-info { padding: 0.35rem 0.5rem; font-size: 0.72rem; color: var(--text2); }
        .modal-foto-info div { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .modal-foto-info .fecha { color: var(--muted); }
        .modal-foto.seleccionada { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-s); }
        .modal-foto-orden {
            position: absolute; top: 6px; right: 6px; width: 24px; height: 24px; border-radius: 50%;
            background: var(--accent); color: #fff; font-size: 0.75rem; font-weight: 700;
            display: none; align-items: center; justify-content: center;
        }
        .modal-foto.seleccionada .modal-foto-orden { display: flex; }
        .modal-fotos-vacio { grid-column: 1 / -1; }
        .modal-fotos-pie {
            display: flex; flex-wrap: wrap; gap: 0.6rem; align-items: center;
            padding: 0.85rem 1.1rem; border-top: 1.5px solid var(--border);
        }
        .modal-fotos-pie .form-control { flex: 1; min-width: 220px; font-size: 0.85rem; border: 1.5px solid var(--border); border-radius: 0.5rem; }
        .modal-fotos-contador { font-size: 0.8rem; color: var(--muted); white-space: nowrap; }

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
                            <a href="{{ route('obras_tc.index', $obraTc->id) }}">{{ $obraTc->descripcion }}</a>
                            <i class="fas fa-chevron-right"></i>
                            <i class="fas fa-file-word"></i>
                            Generador de Informe
                        </div>
                        <h1 class="ph-title"><em>Informe</em>{{ $informe ? ': ' . ($informe->titulo ?: 'Sin título') : ' nuevo' }}</h1>
                    </div>
                    <div class="ph-right">
                        {{-- Guardar envía el mismo formulario a otra ruta (formaction). --}}
                        <button type="submit" form="form-informe" class="btn btn-primary" id="btn-guardar"
                                formaction="{{ route('informe.generador.guardar', $obraTc->id) }}">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('obras_tc.index', $obraTc->id) }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
                @endif
                <div class="editor">
                    <div class="editor-main">
                        {{-- El botón "Generar Word" del panel lateral envía este formulario (atributo form). --}}
                        <form id="form-informe" method="POST" action="{{ route('informe.generador.generar', $obraTc->id) }}" class="card-box">
                            @csrf
                            {{-- Valor guardado (o recién enviado, si volvió con errores) de cada campo. --}}
                            @php $v = fn (string $campo, $porDefecto = '') => $datos[$campo] ?? $porDefecto; @endphp
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
                                            <input type="text" class="form-control" id="car-titulo" name="titulo" value="{{ $v('titulo') }}" placeholder="Ej.: Evaluación estructural de losas del Sector B">
                                            <small class="ayuda">Busca que la persona que lea el título sepa de qué trata el informe sin abrirlo: qué se hizo, sobre qué elemento y en qué sector.</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="car-obra">Obra</label>
                                            <input type="text" class="form-control" id="car-obra" name="obra" value="{{ $v('obra', $obraTc->descripcion) }}" placeholder="Ej.: Edificio Torre Central">
                                            <small class="ayuda">Nombre del lugar físico en el que se realizó el trabajo.</small>
                                        </div>
                                        <div class="col-md-6 campo">
                                            <label for="car-mes">Mes</label>
                                            <select class="form-select" id="car-mes" name="mes">
                                                <option value="">Seleccionar...</option>
                                                @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $mes)
                                                    <option value="{{ $mes }}" @selected($v('mes') === $mes)>{{ $mes }}</option>
                                                @endforeach
                                            </select>
                                            <small class="ayuda">Mes en el que se desarrollaron los trabajos.</small>
                                        </div>
                                        <div class="col-md-6 campo">
                                            <label for="car-anio">Año</label>
                                            <input type="number" class="form-control" id="car-anio" name="anio" value="{{ $v('anio', date('Y')) }}">
                                            <small class="ayuda">Año en el que se desarrollaron los trabajos.</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="car-peticionario">Peticionario</label>
                                            <input type="text" class="form-control" id="car-peticionario" name="peticionario" value="{{ $v('peticionario') }}" placeholder="Ej.: Constructora del Sur S.A.">
                                            <small class="ayuda">Nombre de la persona o entidad que financia el trabajo.</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="car-referencia">Referencia</label>
                                            <input type="text" class="form-control" id="car-referencia" name="referencia" value="{{ $v('referencia') }}" placeholder="Ej.: INF.: 55-25">
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
                                                            <option value="{{ $d }}" @selected((int) $v('presupuesto_dia') === $d)>{{ $d }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-5">
                                                    <select class="form-select" id="ig-fp-mes" name="presupuesto_mes" aria-label="Mes">
                                                        <option value="">Mes</option>
                                                        @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $mes)
                                                            <option value="{{ $mes }}" @selected($v('presupuesto_mes') === $mes)>{{ $mes }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <select class="form-select" id="ig-fp-anio" name="presupuesto_anio" aria-label="Año">
                                                        {{-- Si el informe guardado tiene un año fuera del rango, se agrega. --}}
                                                        @php
                                                            $anioPresupuesto = (int) $v('presupuesto_anio', date('Y'));
                                                            $aniosPresupuesto = collect(range(date('Y') + 1, date('Y') - 5))->push($anioPresupuesto)->unique()->sortDesc();
                                                        @endphp
                                                        @foreach ($aniosPresupuesto as $a)
                                                            <option value="{{ $a }}" @selected($a === $anioPresupuesto)>{{ $a }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="ig-motivo-presupuesto">Motivo del presupuesto</label>
                                            <textarea class="form-control" id="ig-motivo-presupuesto" name="motivo_presupuesto" rows="3" placeholder="Ej.: la evaluación de la estructura de hormigón armado perteneciente al Edificio Torre Central">{{ $v('motivo_presupuesto') }}</textarea>
                                            <small class="ayuda">Para qué se envió el presupuesto. Completa la frase "hemos presentado una propuesta técnica para…".</small>
                                        </div>
                                        <div class="col-12 campo">
                                            <label for="ig-ubicacion-obra">Ubicación de la obra</label>
                                            <input type="text" class="form-control" id="ig-ubicacion-obra" name="ubicacion_obra" value="{{ $v('ubicacion_obra') }}" placeholder="Ej.: la Avda. Principal N° 1234 e/ Calle Uno y Calle Dos de la ciudad de San Lorenzo">
                                            <small class="ayuda">Dónde se encuentra la obra. Completa la frase "ubicado sobre…".</small>
                                        </div>
                                    </div>

                                    <div class="vista-previa">
                                        <div class="vista-previa-head"><i class="fas fa-eye"></i> Así queda el texto</div>
                                        <p id="ig-vista-previa"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="panel" id="panel-objetivo">
                                <div class="card-box-head">Objetivo y alcance</div>
                                <div class="panel-body">
                                    <div class="row g-3">
                                        <div class="col-12 campo">
                                            <label for="obj-texto">Objetivo y alcance del informe</label>
                                            <textarea class="form-control" id="obj-texto" name="objetivo_alcance" rows="5" placeholder="Ej. 1: El presente informe tiene por finalidad evaluar el estado estructural de las columnas de hormigón armado del galpón de depósito, a fin de determinar su condición estructural y su aptitud para continuar en servicio.&#10;&#10;Ej. 2: El presente informe tiene por objeto presentar los resultados de la evaluación de patologías en la estructura de hormigón armado en diversos sectores, a fin de determinar la trascendencia estructural de los daños visualizados.">{{ $v('objetivo_alcance') }}</textarea>
                                            <small class="ayuda">Qué se busca evaluar con el informe, sobre qué elementos y para qué. Cada renglón se convierte en un párrafo del Word.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel" id="panel-datos-previos">
                                <div class="card-box-head">Datos previos para la realización del estudio</div>
                                <div class="panel-body">
                                    <div class="grupo">
                                        <div class="grupo-titulo">Documentación recibida</div>
                                        <small class="ayuda d-block mb-2">Detalle de los documentos que envió el cliente: planos, memorias, planillas, fotografías, estudios, etc.</small>
                                        <div id="documentos"></div>
                                        <button type="button" class="btn btn-sm" id="btn-agregar-documento">
                                            <i class="fas fa-plus"></i> Agregar documento
                                        </button>
                                        <datalist id="tipos-documento">
                                            @foreach (['Plano', 'Memoria de cálculo', 'Memoria descriptiva', 'Planilla', 'Fotografías', 'Estudio de suelos', 'Informe'] as $tipo)
                                                <option value="{{ $tipo }}">
                                            @endforeach
                                        </datalist>
                                        {{-- Molde de un documento; __i__ se reemplaza por un índice único al agregarlo. --}}
                                        <template id="molde-documento">
                                            <div class="documento">
                                                <div class="row g-2">
                                                    <div class="col-md-4 campo">
                                                        <label for="doc-tipo-__i__">Tipo de documento</label>
                                                        <input type="text" class="form-control" id="doc-tipo-__i__" name="documentos[__i__][tipo]" list="tipos-documento" placeholder="Ej.: Plano">
                                                    </div>
                                                    <div class="col-md-8 campo">
                                                        <label for="doc-nombre-__i__">Nombre del archivo</label>
                                                        <input type="text" class="form-control" id="doc-nombre-__i__" name="documentos[__i__][nombre]" placeholder="Ej.: Encofrado Planta Baja">
                                                    </div>
                                                    <div class="col-md-4 campo">
                                                        <label for="doc-fecha-__i__">Fecha de recepción</label>
                                                        <input type="date" class="form-control" id="doc-fecha-__i__" name="documentos[__i__][fecha]">
                                                    </div>
                                                    <div class="col-md-8 campo">
                                                        <label for="doc-remitente-__i__">Enviado por</label>
                                                        <input type="text" class="form-control" id="doc-remitente-__i__" name="documentos[__i__][remitente]" placeholder="Ej.: Juan Pérez">
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-quitar" title="Quitar documento">
                                                    <i class="fas fa-trash-alt"></i> Quitar
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="grupo">
                                        <div class="grupo-titulo">Información recibida</div>
                                        <div class="row g-3">
                                            <div class="col-12 campo">
                                                <textarea class="form-control" id="dp-informacion" name="informacion_recibida" rows="5" aria-label="Información recibida">{{ $v('informacion_recibida') }}</textarea>
                                                <small class="ayuda">Breve descripción de la información que dio el cliente: por qué decide hacer el estudio, cuándo empezó a notar las patologías y, en síntesis, el contexto de lo que relató. Cada renglón se convierte en un párrafo del Word.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grupo">
                                        <div class="grupo-titulo">Características de la obra</div>
                                        <div class="row g-3">
                                            <div class="col-12 campo">
                                                <textarea class="form-control" id="dp-caracteristicas" name="caracteristicas_obra" rows="5" aria-label="Características de la obra">{{ $v('caracteristicas_obra') }}</textarea>
                                                <small class="ayuda">Descripción de la estructura: cómo está constituida, si es nueva o existente, si está terminada o en construcción, etc. Cada renglón se convierte en un párrafo del Word.</small>
                                                <div id="bloques-fotos"></div>
                                                <button type="button" class="btn btn-sm" id="btn-agregar-imagen">
                                                    <i class="fas fa-image"></i> Agregar imagen
                                                </button>
                                                <small class="ayuda">Cada vez que agregás imágenes se crea un bloque: si elegís varias fotos van juntas con un solo pie. Van al Word después del texto, en el orden en que se agregan.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Equipo técnico. --}}
                            <div class="panel" id="panel-equipo-tecnico">
                                <div class="card-box-head">Equipo Técnico</div>
                                <div class="panel-body">
                                    <div class="row g-3">
                                        <div class="col-12 campo">
                                            <label for="et-coordinacion">Coordinación general de los trabajos</label>
                                            <input type="text" class="form-control" id="et-coordinacion" name="equipo_coordinacion" value="{{ $v('equipo_coordinacion') }}" placeholder="Ej.: Prof. Dr. Ing. Sergio Gavilán">
                                            <small class="ayuda">Nombre de la persona que va a firmar el documento.</small>
                                        </div>
                                        <div class="col-12">
                                            <div class="grupo-titulo mb-2">Trabajos de campo</div>
                                            <div class="row g-3">
                                                <div class="col-12 campo">
                                                    <label for="et-campo-descripcion">Descripción</label>
                                                    <input type="text" class="form-control" id="et-campo-descripcion" name="equipo_campo_descripcion" value="{{ $v('equipo_campo_descripcion') }}" placeholder="Ej.: Relevamiento y Ensayos no destructivos">
                                                    <small class="ayuda">Qué trabajos se hicieron en campo.</small>
                                                </div>
                                                <div class="col-12 campo">
                                                    <label for="et-campo-personas">Personas</label>
                                                    <textarea class="form-control" id="et-campo-personas" name="equipo_campo_personas" rows="3" placeholder="Ej.: Ing. Juan Pérez&#10;Ing. María González">{{ $v('equipo_campo_personas') }}</textarea>
                                                    <small class="ayuda">Una persona por renglón (separadas con Enter).</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="grupo-titulo mb-2">Trabajos de gabinete</div>
                                            <div class="row g-3">
                                                <div class="col-12 campo">
                                                    <label for="et-gabinete-descripcion">Descripción</label>
                                                    <input type="text" class="form-control" id="et-gabinete-descripcion" name="equipo_gabinete_descripcion" value="{{ $v('equipo_gabinete_descripcion') }}" placeholder="Ej.: Procesamiento de datos y elaboración del informe">
                                                    <small class="ayuda">Qué trabajos se hicieron en gabinete.</small>
                                                </div>
                                                <div class="col-12 campo">
                                                    <label for="et-gabinete-personas">Personas</label>
                                                    <textarea class="form-control" id="et-gabinete-personas" name="equipo_gabinete_personas" rows="3" placeholder="Ej.: Ing. Juan Pérez&#10;Ing. María González">{{ $v('equipo_gabinete_personas') }}</textarea>
                                                    <small class="ayuda">Una persona por renglón (separadas con Enter).</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Trabajos desarrollados (Titulo Uno, página nueva en el Word).
                                 Cada trabajo activado aparece como subparte del informe. --}}
                            @php $trabajosActivos = (array) $v('trabajos_activos', []); @endphp
                            <div class="panel" id="panel-trabajos-desarrollados">
                                <div class="card-box-head">Trabajos desarrollados</div>
                                <div class="panel-body">
                                    <div class="campo">
                                        <label for="td-descripcion">Descripción</label>
                                        <textarea class="form-control" id="td-descripcion" name="trabajos_descripcion" rows="4" placeholder="Ej.: Los trabajos y ensayos ejecutados a fin de evaluar el estado del hormigón fueron los siguientes:">{{ $v('trabajos_descripcion') }}</textarea>
                                        <small class="ayuda">Frase que introduce la lista de los trabajos realizados para un determinado objetivo.</small>
                                    </div>
                                    <div class="grupo-titulo mt-4 mb-2">Trabajos realizados</div>
                                    <div class="trabajos-toggles">
                                        @foreach (\App\Models\InformeTc::TRABAJOS as $clave => $nombre)
                                            <label class="trabajo-toggle">
                                                <input type="checkbox" name="trabajos_activos[]" value="{{ $clave }}" data-trabajo="{{ $clave }}" @checked(in_array($clave, $trabajosActivos, true))>
                                                <span><i class="fas fa-check"></i> {{ $nombre }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <small class="ayuda d-block mt-2">Los trabajos activados se agregan a las partes del informe.</small>
                                </div>
                            </div>

                            @foreach (\App\Models\InformeTc::TRABAJOS as $clave => $nombre)
                                {{-- Por ahora vacío. --}}
                                <div class="panel" id="panel-trabajo-{{ $clave }}">
                                    <div class="card-box-head">{{ $nombre }}</div>
                                    <div class="panel-body">
                                        <small class="ayuda">Todavía no hay contenido para esta sección.</small>
                                    </div>
                                </div>
                            @endforeach
                        </form>
                    </div>

                    <aside class="editor-side">
                        {{-- Separado de "Guardar" para no confundirlos: Generar solo descarga el Word. --}}
                        <div class="card-box">
                            <div class="card-box-head">Documento Word</div>
                            <div class="generar-box">
                                <button type="submit" form="form-informe" class="btn btn-generar" id="btn-generar">
                                    <i class="fas fa-file-word"></i> Generar Word
                                </button>
                                <small class="ayuda">Descarga el informe con lo cargado en pantalla. No guarda los cambios.</small>
                            </div>
                        </div>
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
                                <li class="parte" data-panel="panel-datos-previos">
                                    <i class="fas fa-folder-open"></i> Datos previos para la realización del estudio
                                </li>
                                <li class="parte" data-panel="panel-equipo-tecnico">
                                    <i class="fas fa-users"></i> Equipo Técnico
                                </li>
                                <li class="parte" data-panel="panel-trabajos-desarrollados">
                                    <i class="fas fa-tools"></i> Trabajos desarrollados
                                </li>
                                @foreach (\App\Models\InformeTc::TRABAJOS as $clave => $nombre)
                                    <li class="parte subparte" data-panel="panel-trabajo-{{ $clave }}" data-subparte="{{ $clave }}" @unless (in_array($clave, $trabajosActivos, true)) hidden @endunless>
                                        <i class="fas fa-angle-right"></i> {{ $nombre }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
    @include('partials.footer')
</div>

{{-- Modal para elegir fotos de la galería de la obra --}}
<div class="modal-fotos" id="modal-fotos" role="dialog" aria-modal="true" aria-labelledby="modal-fotos-titulo">
    <div class="modal-fotos-caja">
        <div class="modal-fotos-head">
            <span id="modal-fotos-titulo"><i class="fas fa-images"></i> Agregar imagen</span>
            <button type="button" class="modal-fotos-cerrar" data-cerrar-modal title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-fotos-filtros">
            <details class="filtro-multi" id="filtro-planos">
                <summary><span>Todos los planos</span><i class="fas fa-chevron-down"></i></summary>
                <div class="filtro-multi-panel">
                    <input type="text" placeholder="Buscar plano..." data-buscar>
                    @foreach ($planos as $plano)
                        <label data-texto="{{ Str::lower($plano['descripcion']) }}">
                            <input type="checkbox" value="{{ $plano['id'] }}"> <span>{{ $plano['descripcion'] }}</span>
                        </label>
                    @endforeach
                </div>
            </details>
            <details class="filtro-multi" id="filtro-etiquetas">
                <summary><span>Toda etiqueta</span><i class="fas fa-chevron-down"></i></summary>
                <div class="filtro-multi-panel">
                    <input type="text" placeholder="Buscar etiqueta..." data-buscar>
                    <label data-texto="sin etiquetas">
                        <input type="checkbox" value="__sin__"> <span>Sin etiquetas</span>
                    </label>
                    @foreach ($etiquetas as $etiqueta)
                        <label data-texto="{{ Str::lower($etiqueta->descripcion) }}">
                            <input type="checkbox" value="{{ $etiqueta->id }}"> <span>{{ $etiqueta->descripcion }}</span>
                        </label>
                    @endforeach
                </div>
            </details>
            <button type="button" class="btn btn-sm" id="btn-limpiar-filtros-fotos">
                <i class="fas fa-filter-circle-xmark"></i> Limpiar
            </button>
        </div>
        <div class="modal-fotos-grilla" id="modal-fotos-grilla"></div>
        <div class="modal-fotos-pie">
            <span class="modal-fotos-contador" id="modal-fotos-contador">Ninguna foto seleccionada</span>
            <input type="text" class="form-control" id="modal-fotos-pie" maxlength="500" placeholder="Pie de foto (ej.: Vista general de la fachada principal)">
            <button type="button" class="btn" data-cerrar-modal>Cancelar</button>
            <button type="button" class="btn btn-primary" id="btn-confirmar-fotos" disabled>
                <i class="fas fa-plus"></i> Agregar
            </button>
        </div>
    </div>
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

    // Trabajos desarrollados: cada botón activado muestra su subparte en el
    // menú. Si se apaga el que se está viendo, se vuelve a Trabajos desarrollados.
    document.querySelectorAll('[data-trabajo]').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            const subparte = document.querySelector('[data-subparte="' + toggle.dataset.trabajo + '"]');
            subparte.hidden = ! toggle.checked;
            if (! toggle.checked && subparte.classList.contains('activa')) {
                document.querySelector('[data-panel="panel-trabajos-desarrollados"]').click();
            }
        });
    });

    // Documentación recibida: cada documento sale del molde con un índice
    // propio, así los campos llegan agrupados como documentos[i][...].
    (function () {
        const lista = document.getElementById('documentos');
        const molde = document.getElementById('molde-documento').innerHTML;
        let indice = 0;

        // doc: documento guardado para precargar sus campos (opcional).
        function agregar(doc) {
            const i = indice++;
            lista.insertAdjacentHTML('beforeend', molde.replaceAll('__i__', i));
            if (doc && typeof doc === 'object' && !(doc instanceof Event)) {
                ['tipo', 'nombre', 'fecha', 'remitente'].forEach(function (campo) {
                    document.getElementById('doc-' + campo + '-' + i).value = doc[campo] || '';
                });
            }
        }

        lista.addEventListener('click', function (e) {
            const quitar = e.target.closest('.btn-quitar');
            if (quitar) {
                quitar.closest('.documento').remove();
            }
        });

        document.getElementById('btn-agregar-documento').addEventListener('click', () => agregar());

        const guardados = Object.values(@json($v('documentos', [])) || {});
        if (guardados.length) {
            guardados.forEach(agregar);
        } else {
            agregar();
        }
    })();

    // Vista previa del párrafo de Información general, se arma mientras se carga.
    (function () {
        const dia = document.getElementById('ig-fp-dia');
        const mes = document.getElementById('ig-fp-mes');
        const anio = document.getElementById('ig-fp-anio');
        const motivo = document.getElementById('ig-motivo-presupuesto');
        const ubicacion = document.getElementById('ig-ubicacion-obra');
        const vista = document.getElementById('ig-vista-previa');

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
        }

        [dia, mes, anio, motivo, ubicacion].forEach(function (campo) {
            campo.addEventListener('input', actualizar);
            campo.addEventListener('change', actualizar);
        });
        actualizar();
    })();

    // Fotos de Características de la obra: el modal muestra la galería de
    // la obra (filtrable por plano y etiqueta); cada confirmación crea un
    // bloque con las fotos elegidas y un único pie.
    (function () {
        const FOTOS = @json($fotos);
        const modal = document.getElementById('modal-fotos');
        const grilla = document.getElementById('modal-fotos-grilla');
        const contador = document.getElementById('modal-fotos-contador');
        const inputPie = document.getElementById('modal-fotos-pie');
        const btnConfirmar = document.getElementById('btn-confirmar-fotos');
        const filtroPlanos = document.getElementById('filtro-planos');
        const filtroEtiquetas = document.getElementById('filtro-etiquetas');
        const bloques = document.getElementById('bloques-fotos');
        let seleccion = [];
        let indiceBloque = 0;

        // Tarjetas armadas una sola vez; los filtros sólo las ocultan.
        const tarjetas = FOTOS.map(function (foto) {
            const tarjeta = document.createElement('div');
            tarjeta.className = 'modal-foto';
            tarjeta.dataset.id = foto.id;

            const img = document.createElement('img');
            img.src = foto.miniatura;
            img.alt = 'Foto';
            img.loading = 'lazy';

            const orden = document.createElement('span');
            orden.className = 'modal-foto-orden';

            const info = document.createElement('div');
            info.className = 'modal-foto-info';
            const plano = document.createElement('div');
            plano.textContent = foto.plano;
            const fecha = document.createElement('div');
            fecha.className = 'fecha';
            fecha.textContent = foto.fecha || '';
            info.append(plano, fecha);

            tarjeta.append(img, orden, info);
            tarjeta.addEventListener('click', function () { alternar(foto); });
            grilla.appendChild(tarjeta);
            return { foto: foto, el: tarjeta };
        });

        const vacio = document.createElement('div');
        vacio.className = 'vacio modal-fotos-vacio';
        vacio.innerHTML = '<i class="fas fa-images"></i>';
        vacio.appendChild(document.createTextNode(FOTOS.length ? 'No hay fotos que coincidan con el filtro.' : 'Esta obra todavía no tiene fotos en la galería.'));
        grilla.appendChild(vacio);

        function marcados(filtro) {
            return Array.from(filtro.querySelectorAll('input[type="checkbox"]:checked'));
        }

        function actualizarTextoFiltro(filtro, textoVacio, separador = ', ') {
            const elegidos = marcados(filtro).map(c => c.nextElementSibling.textContent.trim());
            filtro.querySelector('summary span').textContent = elegidos.length ? elegidos.join(separador) : textoVacio;
        }

        function filtrar() {
            const planos = marcados(filtroPlanos).map(c => c.value);
            const etiquetas = marcados(filtroEtiquetas).map(c => c.value);
            let visibles = 0;

            tarjetas.forEach(function ({ foto, el }) {
                // Planos: la foto está en alguno de los marcados (cada foto tiene un solo plano).
                const pasaPlano = !planos.length || planos.includes(String(foto.plano_id));
                // Etiquetas: la foto tiene TODAS las marcadas (ej. Fisura + Planta alta).
                const pasaEtiqueta = etiquetas.every(function (e) {
                    return e === '__sin__' ? foto.etiquetas.length === 0 : foto.etiquetas.includes(Number(e));
                });
                const visible = pasaPlano && pasaEtiqueta;
                el.hidden = !visible;
                if (visible) visibles++;
            });

            vacio.hidden = visibles > 0;
            actualizarTextoFiltro(filtroPlanos, 'Todos los planos');
            actualizarTextoFiltro(filtroEtiquetas, 'Toda etiqueta', ' + ');
        }

        // "Sin etiquetas" no se puede combinar con etiquetas (no daría ninguna foto):
        // marcar uno desmarca el otro.
        filtroEtiquetas.addEventListener('change', function (e) {
            if (!e.target.checked) return;
            const esSin = e.target.value === '__sin__';
            marcados(filtroEtiquetas).forEach(function (c) {
                if (c !== e.target && (c.value === '__sin__') !== esSin) c.checked = false;
            });
        });

        [filtroPlanos, filtroEtiquetas].forEach(function (filtro) {
            filtro.addEventListener('change', filtrar);
            filtro.querySelector('[data-buscar]').addEventListener('input', function () {
                const texto = this.value.trim().toLowerCase();
                filtro.querySelectorAll('label[data-texto]').forEach(function (label) {
                    label.hidden = texto !== '' && !label.dataset.texto.includes(texto);
                });
            });
        });

        // Un solo desplegable abierto a la vez, y se cierra al tocar afuera.
        document.addEventListener('click', function (e) {
            [filtroPlanos, filtroEtiquetas].forEach(function (filtro) {
                if (!filtro.contains(e.target)) filtro.open = false;
            });
        });

        document.getElementById('btn-limpiar-filtros-fotos').addEventListener('click', function () {
            [filtroPlanos, filtroEtiquetas].forEach(function (filtro) {
                marcados(filtro).forEach(c => { c.checked = false; });
            });
            filtrar();
        });

        function alternar(foto) {
            const posicion = seleccion.indexOf(foto);
            if (posicion === -1) {
                seleccion.push(foto);
            } else {
                seleccion.splice(posicion, 1);
            }
            pintarSeleccion();
        }

        function pintarSeleccion() {
            tarjetas.forEach(function ({ foto, el }) {
                const posicion = seleccion.indexOf(foto);
                el.classList.toggle('seleccionada', posicion !== -1);
                el.querySelector('.modal-foto-orden').textContent = posicion !== -1 ? posicion + 1 : '';
            });
            contador.textContent = seleccion.length === 0
                ? 'Ninguna foto seleccionada'
                : seleccion.length === 1 ? '1 foto seleccionada' : seleccion.length + ' fotos seleccionadas (un solo pie)';
            btnConfirmar.disabled = seleccion.length === 0;
        }

        function abrir() {
            seleccion = [];
            inputPie.value = '';
            pintarSeleccion();
            filtrar();
            modal.classList.add('abierto');
        }

        function cerrar() {
            modal.classList.remove('abierto');
        }

        document.getElementById('btn-agregar-imagen').addEventListener('click', abrir);
        modal.querySelectorAll('[data-cerrar-modal]').forEach(b => b.addEventListener('click', cerrar));
        modal.addEventListener('click', function (e) {
            if (e.target === modal) cerrar();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('abierto')) cerrar();
        });
        // Enter en el pie confirma, sin enviar el formulario del informe.
        inputPie.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (!btnConfirmar.disabled) btnConfirmar.click();
            }
        });

        btnConfirmar.addEventListener('click', function () {
            if (!seleccion.length) return;
            agregarBloque(seleccion.slice(), inputPie.value.trim());
            cerrar();
        });

        // faltantes: fotos del bloque guardado que ya no están en la galería.
        function agregarBloque(fotos, pie, faltantes) {
            const i = indiceBloque++;
            const bloque = document.createElement('div');
            bloque.className = 'bloque-foto';

            if (faltantes) {
                const aviso = document.createElement('div');
                aviso.className = 'bloque-foto-aviso';
                aviso.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ';
                aviso.appendChild(document.createTextNode(faltantes === 1
                    ? 'Una foto de este bloque se borró de la galería y ya no va al Word.'
                    : faltantes + ' fotos de este bloque se borraron de la galería y ya no van al Word.'));
                bloque.appendChild(aviso);
            }

            const imagenes = document.createElement('div');
            imagenes.className = 'bloque-foto-imagenes';
            // De a varias, cada foto lleva su letra (a), (b)… igual que en el Word.
            fotos.forEach(function (foto, n) {
                const img = document.createElement('img');
                img.src = foto.miniatura;
                img.alt = 'Foto';
                imagenes.appendChild(img);
                if (fotos.length > 1) {
                    const letra = document.createElement('span');
                    letra.className = 'bloque-foto-letra';
                    letra.textContent = '(' + String.fromCharCode(97 + n) + ')';
                    imagenes.appendChild(letra);
                }
            });

            const ids = document.createElement('input');
            ids.type = 'hidden';
            ids.name = 'fotos_caracteristicas[' + i + '][ids]';
            ids.value = fotos.map(f => f.id).join(',');

            const fila = document.createElement('div');
            fila.className = 'bloque-foto-pie';
            const inputBloque = document.createElement('input');
            inputBloque.type = 'text';
            inputBloque.className = 'form-control';
            inputBloque.name = 'fotos_caracteristicas[' + i + '][pie]';
            inputBloque.maxLength = 500;
            inputBloque.placeholder = 'Pie de foto';
            inputBloque.value = pie;
            inputBloque.setAttribute('aria-label', 'Pie de foto');

            const quitar = document.createElement('button');
            quitar.type = 'button';
            quitar.className = 'btn btn-sm btn-quitar-bloque';
            quitar.title = 'Quitar imagen';
            quitar.innerHTML = '<i class="fas fa-trash-alt"></i> Quitar';
            quitar.addEventListener('click', () => bloque.remove());

            fila.append(inputBloque, quitar);
            bloque.append(imagenes, ids, fila);
            bloques.appendChild(bloque);
        }

        // Bloques del informe guardado, con las fotos que siguen existiendo.
        Object.values(@json($v('fotos_caracteristicas', [])) || {}).forEach(function (guardado) {
            const ids = String(guardado.ids || '').split(',').map(Number).filter(Boolean);
            const fotos = ids.map(id => FOTOS.find(f => f.id === id)).filter(Boolean);
            const faltantes = ids.length - fotos.length;
            if (fotos.length || faltantes) {
                agregarBloque(fotos, guardado.pie || '', faltantes);
            }
        });
    })();
</script>
</body>
</html>
