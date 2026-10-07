<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gavilan y Asociados - Novedades</title>
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
            --green:    #1e9166;
            --green-s:  #e5f6f0;
            --orange:   #d9622a;
            --orange-s: #fff0eb;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; }
        .content-wrapper *:not(i):not([class*="fa"]):not([class*="icon"]):not(.nav-icon) {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .content-wrapper { background: var(--bg) !important; }

        .ph {
            padding: 1.75rem 0 1.5rem; margin-bottom: 1.5rem;
            display: flex; align-items: flex-end; justify-content: space-between;
            gap: 1.5rem; flex-wrap: wrap;
        }
        .ph-greeting {
            font-size: 0.78rem; font-weight: 500; color: var(--muted);
            margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;
        }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }

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

        .version-block {
            width: 100%;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .version-block + .version-block { margin-top: 2rem; }

        .version-header {
            padding: 1.4rem 1.5rem;
            background: var(--surface2);
            border-bottom: 1.5px solid var(--border);
            display: flex; align-items: center; gap: 0.6rem;
            cursor: pointer; list-style: none;
        }
        .version-header::-webkit-details-marker { display: none; }
        .version-header::marker { content: ''; }
        .version-header i.fa-chevron-down {
            margin-left: auto; color: var(--muted); font-size: 0.85rem;
            transition: transform 0.18s;
        }
        details[open] > .version-header i.fa-chevron-down { transform: rotate(180deg); }
        .version-titulo {
            font-size: 1.2rem; font-weight: 700; color: var(--text);
        }
        .version-titulo .version-numero { color: var(--accent); }
        .version-fecha { font-size: 0.78rem; color: var(--muted); font-weight: 500; margin-top: 0.25rem; }

        .modulo-card {
            width: 100%;
            border-top: 1.5px solid var(--border);
        }
        .modulo-card:first-of-type { border-top: none; }
        .modulo-titulo {
            font-size: 1.02rem; font-weight: 700; color: var(--text);
            padding: 1.1rem 1.5rem;
            display: flex; align-items: center; gap: 0.6rem;
            cursor: pointer; list-style: none;
        }
        .modulo-titulo::-webkit-details-marker { display: none; }
        .modulo-titulo::marker { content: ''; }
        .modulo-titulo i.fa-chevron-down {
            margin-left: auto; color: var(--muted); font-size: 0.8rem;
            transition: transform 0.18s;
        }
        details[open] > .modulo-titulo i.fa-chevron-down { transform: rotate(180deg); }
        .modulo-titulo .modulo-icono { color: var(--accent); font-size: 0.9rem; }
        .modulo-titulo .modulo-cantidad {
            font-size: 0.72rem; font-weight: 600; color: var(--muted);
        }

        .modulo-body {
            padding: 0 1.5rem 1.3rem;
            display: flex;
            flex-direction: column;
            gap: 1.3rem;
        }

        .grupo-titulo {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.22rem 0.55rem; border-radius: 0.4rem;
            margin-bottom: 0.65rem;
        }
        .grupo-titulo.nuevo { background: var(--green-s); color: var(--green); }
        .grupo-titulo.corregido { background: var(--orange-s); color: var(--orange); }

        .lista-cambios { list-style: none; display: flex; flex-direction: column; gap: 0.55rem; }
        .lista-cambios li {
            position: relative;
            padding-left: 1.05rem;
            font-size: 0.86rem; color: var(--text2); line-height: 1.5;
        }
        .lista-cambios li::before {
            content: '';
            position: absolute; left: 0; top: 0.6em;
            width: 6px; height: 6px; border-radius: 50%;
        }
        .lista-cambios li.nuevo::before { background: var(--green); }
        .lista-cambios li.corregido::before { background: var(--orange); }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('partials.navbar')
    @include('partials.sidebar')

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">

                <div class="ph">
                    <div>
                        <div class="ph-greeting"><i class="fas fa-bullhorn"></i> Registro de cambios</div>
                        <h1 class="ph-title">Novedades del <em>sistema</em></h1>
                        <p class="ph-sub">Un repaso de las funcionalidades incorporadas y los errores corregidos en cada actualización</p>
                    </div>
                    <a href="{{ route('home') }}" class="btn">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <details class="version-block" open>
                    <summary class="version-header">
                        <div>
                            <h2 class="version-titulo">Cambios de la versión <span class="version-numero">6.3</span></h2>
                            <p class="version-fecha">Domingo 13 de septiembre de 2026</p>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </summary>

                    <details class="modulo-card" open>
                        <summary class="modulo-titulo">
                            <i class="fas fa-drafting-compass modulo-icono"></i> Planos
                            <span class="modulo-cantidad">8 cambios</span>
                            <i class="fas fa-chevron-down"></i>
                        </summary>
                        <div class="modulo-body">
                            <div class="grupo-cambios">
                                <span class="grupo-titulo nuevo">Nuevas funcionalidades</span>
                                <ul class="lista-cambios">
                                    <li class="nuevo">Se incorporó la herramienta <strong>Mover</strong>, que permite desplazarse por el plano arrastrando con un dedo. Al abrir un plano, esta herramienta queda seleccionada de forma predeterminada.</li>
                                    <li class="nuevo">Se incorporó la <strong>selección múltiple</strong>, que permite elegir varios elementos del plano a la vez para moverlos o eliminarlos en conjunto.</li>
                                    <li class="nuevo">Se incorporó una <strong>vista en cuadrícula</strong> para las fotografías de cada pin, que muestra todas las miniaturas disponibles antes de abrir una en detalle.</li>
                                    <li class="nuevo">Se agregó la opción <strong>Descargar todas</strong> en la vista de cuadrícula, para descargar de una sola vez todas las fotografías asociadas a un pin.</li>
                                    <li class="nuevo">Se agregó la opción <strong>Descargar</strong> en la vista de fotografía completa, para descargar una fotografía específica de un pin.</li>
                                </ul>
                            </div>

                            <div class="grupo-cambios">
                                <span class="grupo-titulo corregido">Corrección de errores</span>
                                <ul class="lista-cambios">
                                    <li class="corregido">Se corrigió un error por el cual, al presionar <strong>Agregar fotografía</strong> desde un pin, el menú para elegir el origen (cámara o galería) quedaba oculto detrás de la vista de la fotografía.</li>
                                    <li class="corregido">Se corrigió un error por el cual los botones flotantes de la barra superior quedaban superpuestos sobre el menú lateral de herramientas.</li>
                                    <li class="corregido">Se corrigió un error por el cual el menú lateral de herramientas quedaba parcialmente fuera de la pantalla en determinados dispositivos.</li>
                                </ul>
                            </div>
                        </div>
                    </details>

                    <details class="modulo-card" open>
                        <summary class="modulo-titulo">
                            <i class="fas fa-images modulo-icono"></i> Galería
                            <span class="modulo-cantidad">2 cambios</span>
                            <i class="fas fa-chevron-down"></i>
                        </summary>
                        <div class="modulo-body">
                            <div class="grupo-cambios">
                                <span class="grupo-titulo nuevo">Nuevas funcionalidades</span>
                                <ul class="lista-cambios">
                                    <li class="nuevo">Se incorporó la <strong>clasificación de fotografías</strong> mediante etiquetas, para organizarlas y filtrarlas con mayor facilidad.</li>
                                    <li class="nuevo">Se incorporó la <strong>vista de ubicación en el plano</strong>, que muestra en qué punto del plano fue tomada cada fotografía.</li>
                                </ul>
                            </div>
                        </div>
                    </details>
                </details>

                {{-- Version 7.0 - 2026-09-13
                Se agrego la herramienta planilla
                Las planillas disponibles hasta la fecha son ultrasonido indirecto, esclerometria, carbonatacion,
                medicion de fisuras, cloruros y resistividad. Todas se guardan automaticamente y cada punto
                se asocia a un elemento y a un nivel de la obra.

                En cuanto a la planilla de ultrasonido indirecto, permite cargar 8 lecturas, y se calcula el promedio,
                la desviación estándar y el coeficiente de variación. Se marca el punto para repetir el ensayo cuando
                la desviación estándar es mayor a 200 o el coeficiente de variación es mayor al 5%.

                En cuanto a la planilla de esclerometria, permite cargar 14 lecturas (impactos). Se calcula el promedio
                y se descartan las lecturas que quedan fuera de ±6 de ese promedio, en dos rondas sucesivas (primero
                contra el promedio inicial, y luego contra el promedio de lo que sobrevivió a la primera ronda). Con
                las lecturas finales se calcula el N corregido, usando la lectura inicial y final del yunque, y luego
                se le aplica una corrección según el ángulo de ensayo (0°, ±45°, ±90°) para obtener el N final. Si en
                total se descartan más de 2 lecturas, se marca el punto para repetir el ensayo.

                En cuanto a la planilla de carbonatacion, permite cargar el recubrimiento y 4 mediciones de espesor
                carbonatado de cada punto. Se calcula el espesor carbonatado promedio y el % afectado
                (espesor promedio / recubrimiento x 100).

                En cuanto a la planilla de medicion de fisuras permite cargar  3 medidas de espesor y 3 medidas de profundidad
                de cada fisura y el ancho del elemento. 
                Se calcula el promedio de espesor y el promedio de profundidad, y el % de sección afectada. Si la fisura se 
                marca como pasante, ese % se multiplica por 2.

                En cuanto a la planilla de cloruros, permite cargar el recubrimiento y 4 mediciones de espesor
                afectado por cloruros de cada punto. Se calcula el espesor afectado promedio y el % afectado
                (espesor promedio / recubrimiento x 100).

                En cuanto a la planilla de resistividad, permite cargar 4 lecturas del equipo (kΩ·cm) y la temperatura
                del hormigon de cada punto. Se calcula el promedio de las lecturas y se corrige por temperatura
                tomando 20 °C como referencia: corrección = promedio x 3% x (temperatura - 20). La resistividad
                corregida es el promedio mas la corrección. Con ese valor se clasifica la velocidad de corrosion:
                mayor a 20 despreciable, de 10 a 20 baja, de 5 a 10 moderada a alta, menor a 5 muy alta.

                Se agrego la herramienta pachometria. Permite cargar el detalle de armaduras de vigas, pilares
                (rectangulares o circulares) y losas, con sus medidas, recubrimiento, estribos y barras, y se dibuja
                la seccion en proporcion real (en losas, planta de 1 m x 1 m y corte A-A). Cada pachometria se
                nombra como PCH + numero. Se pueden exportar las pachometrias visibles a PDF (A4 vertical) y a DXF
                en metros y a escala real, con cada cosa en su capa (hormigon, estribos, barras, cotas, ejes).

                En los planos se agrego la descarga en DXF. Se descarga un ZIP con el .dxf, el plano como imagen
                .png de fondo y el .pdf original como respaldo. En el DXF las anotaciones quedan como entidades
                editables, cada herramienta en su propia capa, en milimetros y a tamaño real de la hoja. El archivo
                se abre en AutoCAD y la imagen de fondo tiene que estar en la misma carpeta que el .dxf.

                Se agregaron las notificaciones por correo. En Obras > Configuraciones > Notificaciones se eligen
                los usuarios que reciben un aviso cuando se carga un presupuesto aprobado. Solo se pueden elegir
                usuarios activos con correo registrado. El correo indica la obra, el nombre del presupuesto y la
                observacion si la tiene. Si un correo no se puede enviar, el presupuesto se guarda igual y el error
                queda en el log. Se agrego el campo correo a los usuarios, se carga desde la edicion del usuario.
                --}}


            </div>
        </section>
    </div>

    @include('partials.footer')
</div>
</body>
</html>
