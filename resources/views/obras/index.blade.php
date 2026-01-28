<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Obras</title>
    @include('partials.head')
    <style>
        .titulo-box {
            background: #f4f6fb;
            border-radius: 1.1rem;
            border: 1.5px solid #e3e6ea;
            padding: 1.2rem 2rem 1.2rem 1.5rem;
            margin-bottom: 2.2rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            box-sizing: border-box;
        }
        .titulo-box .titulo-lista {
            font-size: 1.1rem;
            font-weight: 600;
            color: #222;
            letter-spacing: 0.2px;
            margin: 0 0 0.7rem 0;
            text-align: left;
        }
        .titulo-box .acciones {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.7rem;
        }
        @media (max-width: 767.98px) {
            .titulo-box {
                flex-direction: column;
                align-items: stretch;
                padding: 1.1rem 1rem;
            }
            .titulo-box .acciones {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }
            .input-group {
                min-width: 100%;
                max-width: 100%;
            }
        }
        .obra-card {
            border-radius: 1.1rem;
            box-shadow: 0 2px 12px 0 rgba(40,40,40,0.09);
            transition: box-shadow 0.15s, border-color 0.13s, background 0.13s;
            border: 1.5px solid #e3e6ea;
            background: #fff;
            cursor: pointer;
            min-height: 420px;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .obra-card:hover {
            box-shadow: 0 8px 24px 0 rgba(40,40,40,0.16);
            border-color: #bdbdbd;
            background: #f8fafd;
        }
        .obra-card .card-image {
            background: #e9ecef;
            height: 200px;
            width: 100%;
            object-fit: cover;
            display: block;
            padding: 0;
            border: none;
        }
        .obra-card .card-text {
            padding: 1.1rem 1.2rem 0.7rem 1.2rem;
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }
        .obra-card .date {
            color: #bdbdbd;
            font-size: 0.92rem;
            margin-bottom: 0.2rem;
        }
        .obra-card .card-title {
            font-size: 1.13rem;
            color: #222;
            font-weight: 700;
            margin-bottom: 0.2rem;
            letter-spacing: 0.1px;
        }
        .obra-card .card-desc {
            color: #444;
            font-size: 0.97rem;
            margin-bottom: 0.1rem;
            flex: 1 1 auto;
        }
        .obra-card .card-stats {
            display: flex;
            border-top: 1px solid #ececec;
            background: #f7f8fa;
            padding: 0.7rem 1.2rem;
            justify-content: space-between;
        }
        .obra-card .stat {
            text-align: center;
            flex: 1 1 0;
        }
        .obra-card .stat.border {
            border-left: 1px solid #ececec;
            border-right: 1px solid #ececec;
        }
        .obra-card .stat .value {
            font-size: 1.08rem;
            font-weight: 700;
            color: #222;
        }
        .obra-card .stat .type {
            font-size: 0.85rem;
            color: #bdbdbd;
            font-weight: 500;
            letter-spacing: 0.2px;
        }
        .search-bar {
            border-radius: 1.2rem;
            border: 1px solid #ececec;
            padding-left: 1rem;
            font-size: 0.98rem;
            background: #fff;
            color: #222;
            box-shadow: none;
            transition: border 0.13s;
        }
        .search-bar:focus {
            border: 1.5px solid #bdbdbd;
            outline: none;
        }
        .agregar-obra-btn {
            border-radius: 1.2rem;
            font-weight: 500;
            font-size: 0.98rem;
            padding: 0.38rem 1.1rem;
            background: #222;
            color: #fff;
            border: none;
            box-shadow: none;
            transition: background 0.13s;
        }
        .agregar-obra-btn:hover {
            background: #444;
            color: #fff;
        }
    </style>
    <style>
        /* Loader animación */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #222;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            animation: spin 0.8s linear infinite;
            margin: 40px auto;
            display: none;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
                    <div class="titulo-box">
                        <div class="titulo-lista"><h1>Listado de Obras</h1></div>
                        <div class="acciones">
                            <div class="input-group" style="min-width:340px;max-width:540px;">
                                <input type="search" id="search" name="search" class="form-control" placeholder="Buscar obras...">
                                <button id="btn-buscar" class="btn btn-primary" type="button" title="Buscar"><i class="fas fa-search"></i></button>
                                <a href="{{ route('obras.create') }}" class="btn btn-light" id="agregar-obra-btn" title="Agregar Obra"><i class="fas fa-plus"></i></a>
                                <a href="{{ route('home') }}" class="btn btn-light" title="Volver al menú"><i class="fas fa-arrow-left"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="row" id="obras-cards">
                                            <div id="loader-busqueda" class="loader"></div>
                        @forelse ($obras->reverse() as $obra)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <a href="{{ route('obras.show', $obra->id) }}" class="obra-card text-decoration-none" tabindex="0" title="Ver detalles de la obra" style="display:block;">
                                <div class="card-image">
                                    @php
                                        // Coordenadas aleatorias dentro de Paraguay
                                        $lat = rand(-27000000, -19000000) / 1000000.0; // entre -27 y -19
                                        $lng = rand(-59000000, -54000000) / 1000000.0; // entre -59 y -54
                                    @endphp
                                    <iframe
                                        width="100%"
                                        height="200"
                                        frameborder="0"
                                        style="border:0;display:block;"
                                        src="https://www.google.com/maps?q={{ $lat }},{{ $lng }}&hl=es&z=8&output=embed"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                                <div class="card-text">
                                    <h2 class="card-title">{{ $obra->nombre }}</h2>
                                    <span class="date">{{ \Carbon\Carbon::parse($obra->fecha_carga)->diffForHumans() }}</span>
                                    <p class="card-desc">{{ $obra->direccion }}<br><span style="color:#888;font-size:0.93em;">{{ $obra->contacto }} ({{ $obra->numero }})</span></p>
                                </div>
                                <div class="card-stats">
                                    <div class="stat">
                                        <div class="value">{{ rand(1,5) }}</div>
                                        <div class="type">Presupuestos</div>
                                    </div>
                                    <div class="stat border">
                                        <div class="value">{{ rand(10,50) }}</div>
                                        <div class="type">Pedidos</div>
                                    </div>
                                    <div class="stat">
                                        <div class="value">{{ rand(2,10) }}</div>
                                        <div class="type">Usuarios</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">No hay obras registradas.</div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
</body>
<script>
    // Filtrado en vivo y animación de carga
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const btnBuscar = document.getElementById('btn-buscar');
        const loader = document.getElementById('loader-busqueda');
        const cardsContainer = document.getElementById('obras-cards');
        const allCards = Array.from(cardsContainer.children);

        function filtrarObras() {
            const texto = searchInput.value.trim().toLowerCase();
            allCards.forEach(card => {
                const contenido = card.textContent.toLowerCase();
                card.style.display = contenido.includes(texto) ? '' : 'none';
            });
        }

        function mostrarLoaderYFiltrar() {
            // Ocultar todas las tarjetas antes de mostrar el loader
            allCards.forEach(card => { card.style.display = 'none'; });
            loader.style.display = 'block';
            cardsContainer.style.opacity = '0.5';
            setTimeout(() => {
                filtrarObras();
                loader.style.display = 'none';
                cardsContainer.style.opacity = '1';
            }, 600); // Duración de la animación
        }

        // Buscar al presionar el botón
        btnBuscar.addEventListener('click', mostrarLoaderYFiltrar);

        // Buscar al presionar Enter en el input
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                mostrarLoaderYFiltrar();
            }
        });

        // (Filtrado en vivo desactivado, solo buscar con botón o Enter)
    });
</script>
</html>

