<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obra: {{ $obra->nombre }}</title>
    @include('partials.head')
    <style>
        .obra-card {
            border-radius: 1.1rem;
            box-shadow: 0 2px 12px 0 rgba(40,40,40,0.09);
            border: 1.5px solid #e3e6ea;
            background: #fff;
            min-height: 420px;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            flex-direction: column;
            overflow: hidden;
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
        .opciones-row {
            margin-top: 2.5rem;
        }
        .opcion-card {
            border-radius: 1rem;
            box-shadow: 0 1px 6px 0 rgba(40,40,40,0.07);
            border: 1.2px solid #e3e6ea;
            background: #f8fafd;
            width: 100%;
            min-width: 180px;
            max-width: 220px;
            min-height: 150px;
            max-height: 150px;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.2rem 0.5rem;
            text-decoration: none;
            color: #222;
            transition: box-shadow 0.13s, border-color 0.13s, background 0.13s;
        }
        .opcion-card:hover {
            box-shadow: 0 4px 16px 0 rgba(40,40,40,0.13);
            border-color: #bdbdbd;
            background: #f1f3f6;
            color: #222;
        }
        .opcion-card span {
            font-size: 2.1rem;
            margin-bottom: 0.5rem;
        }
        @media (max-width: 575.98px) {
            .opcion-card {
                min-width: 100%;
                max-width: 100%;
            }
        }
        .titulo-box {
            background: #f4f6fb;
            border-radius: 1.1rem;
            border: 1.5px solid #e3e6ea;
            padding: 1.2rem 2rem 1.2rem 1.5rem;
            margin-bottom: 2.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            box-sizing: border-box;
        }
        .titulo-box h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #222;
            letter-spacing: 0.2px;
            margin: 0;
            text-align: left;
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
                        <h1>Obra: {{ $obra->nombre }}</h1>
                        <a href="{{ route('obras.index') }}" class="btn btn-light" title="Volver al listado"><i class="fas fa-arrow-left mr-2"></i></a>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid d-flex flex-column align-items-center" style="min-height:60vh;">
                    <div class="row w-100 justify-content-center mx-0 opciones-row">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3 d-flex align-items-stretch">
                            <a href="{{ route('directorio.index', ['obra' => $obra->id]) }}" class="opcion-card">
                                <span style="color:#6c63ff;"><i class="fas fa-folder-open"></i></span>
                                <div>Directorio</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3 d-flex align-items-stretch">
                            <a href="{{ route('presupuesto_aprobado.index', $obra->id) }}" class="opcion-card">
                                <span style="color:#fdcb6e;"><i class="fas fa-file-invoice-dollar"></i></span>
                                <div>Presupuestos Aprobados</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3 d-flex align-items-stretch">
                            <a href="#" class="opcion-card">
                                <span style="color:#0984e3;"><i class="fas fa-clipboard-list"></i></span>
                                <div>Pedidos de Insumos</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3 d-flex align-items-stretch">
                            <a href="{{ route('contacto.index', $obra->id) }}" class="opcion-card">
                                <span style="color:#00b894;"><i class="fas fa-address-book"></i></span>
                                <div>Contactos</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3 d-flex align-items-stretch">
                            <a href="#" class="opcion-card">
                                <span style="color:#e17055;"><i class="fas fa-boxes"></i></span>
                                <div>Inventario</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3 d-flex align-items-stretch">
                            <a href="{{ route('factura_venta.show', $obra->id) }}" class="opcion-card">
                                <span style="color:#00bcd4;"><i class="fas fa-file-invoice"></i></span>
                                <div>Facturación</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3 d-flex align-items-stretch">
                            <a href="#" class="opcion-card">
                                <span style="color:#636e72;"><i class="fas fa-info-circle"></i></span>
                                <div>Datos de la Obra</div>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
</body>

</html>

