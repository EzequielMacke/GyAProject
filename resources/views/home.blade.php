<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gavilan y Asociados - Home</title>
    @include('partials.head')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')

        <div class="content-wrapper">
            
            <section class="content">
                <div class="container-fluid d-flex flex-column align-items-center" style="min-height:60vh;">
                    <div class="row w-100 justify-content-center mx-0 opciones-row mt-5">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
                            <a href="{{ route('obras.index') }}" class="opcion-card text-center">
                                <span style="color:#6c63ff;"><i class="fas fa-building"></i></span>
                                <div style="font-size:1.2rem; font-weight:600;">Obras</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
                            <a href="{{ route('tabletas.index') }}" class="opcion-card text-center">
                                <span style="color:#8e44ad;"><i class="fas fa-tablet-alt"></i></span>
                                <div style="font-size:1.2rem; font-weight:600;">Tablets</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
                            <a href="#" class="opcion-card text-center">
                                <span style="color:#00b894;"><i class="fas fa-tools"></i></span>
                                <div style="font-size:1.2rem; font-weight:600;">Equipos</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
                            <a href="#" class="opcion-card text-center">
                                <span style="color:#fdcb6e;"><i class="fas fa-truck"></i></span>
                                <div style="font-size:1.2rem; font-weight:600;">Vehículos</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
                            <a href="#" class="opcion-card text-center">
                                <span style="color:#e17055;"><i class="fas fa-user-shield"></i></span>
                                <div style="font-size:1.2rem; font-weight:600;">Permisos</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
                            <a href="#" class="opcion-card text-center">
                                <span style="color:#636e72;"><i class="fas fa-users"></i></span>
                                <div style="font-size:1.2rem; font-weight:600;">Usuarios</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
                            <a href="#" class="opcion-card text-center">
                                <span style="color:#00bcd4;"><i class="fas fa-hammer"></i></span>
                                <div style="font-size:1.2rem; font-weight:600;">Herramientas</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
                            <a href="#" class="opcion-card text-center">
                                <span style="color:#fd79a8;"><i class="fas fa-tools"></i></span>
                                <div style="font-size:1.2rem; font-weight:600;">Mantenimiento</div>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
                            <a href="#" class="opcion-card text-center">
                                <span style="color:#0984e3;"><i class="fas fa-chart-bar"></i></span>
                                <div style="font-size:1.2rem; font-weight:600;">Reportes</div>
                            </a>
                        </div>
                    </div>
                </div>
                <style>
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
                </style>
            </section>
        </div>

        @include('partials.footer')
    </div>
</body>
</html>
