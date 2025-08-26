<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Presupuestos</title>
    @include('partials.head')
    <style>
        .filter-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .form-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        .btn-generate {
            background: linear-gradient(45deg, #dc3545, #c82333);
            border: none;
            color: white;
            font-weight: bold;
            padding: 15px 50px;
            font-size: 18px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        .btn-generate:hover {
            background: linear-gradient(45deg, #c82333, #a71e2a);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(220, 53, 69, 0.4);
        }
        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .quick-date-btn {
            transition: all 0.2s ease;
        }
        .quick-date-btn:hover {
            transform: scale(1.05);
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
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-file-pdf text-danger"></i>
                                Reporte de Presupuestos
                            </h1>
                            <p class="text-muted">Configure los filtros y genere el reporte completo en PDF</p>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-sm-right">
                                <a href="{{ route('presupuestos.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Volver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="card filter-card">
                                <div class="card-header bg-primary text-white text-center">
                                    <h3 class="card-title mb-0">
                                        <i class="fas fa-filter mr-2"></i>
                                        Configuración del Reporte
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form id="reportForm" action="{{ route('presupuestos.generar.reporte', 'completo') }}" method="GET" target="_blank">

                                        <!-- Rango de Fechas -->
                                        <div class="form-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-calendar-alt text-primary mr-2"></i>
                                                Rango de Fechas
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fecha_inicio">
                                                            <i class="fas fa-play text-success mr-1"></i>
                                                            Fecha Inicio:
                                                        </label>
                                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fecha_fin">
                                                            <i class="fas fa-stop text-danger mr-1"></i>
                                                            Fecha Fin:
                                                        </label>
                                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <small class="text-muted d-block mb-2">Rangos rápidos:</small>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-outline-primary quick-date-btn" onclick="setQuickDate('today')">Hoy</button>
                                                        <button type="button" class="btn btn-outline-primary quick-date-btn" onclick="setQuickDate('week')">Esta Semana</button>
                                                        <button type="button" class="btn btn-outline-primary quick-date-btn" onclick="setQuickDate('month')">Este Mes</button>
                                                        <button type="button" class="btn btn-outline-primary quick-date-btn" onclick="setQuickDate('year')">Este Año</button>
                                                        <button type="button" class="btn btn-outline-primary quick-date-btn" onclick="setQuickDate('all')">Todo</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Filtros por Categorías -->
                                        <div class="form-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-filter text-success mr-2"></i>
                                                Filtros por Categorías
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="obra_id">
                                                            <i class="fas fa-building text-secondary mr-1"></i>
                                                            Obra:
                                                        </label>
                                                        <select class="form-control" id="obra_id" name="obra_id">
                                                            <option value="">Todas las obras</option>
                                                            @foreach($obras as $obra)
                                                                <option value="{{ $obra->id }}">{{ $obra->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="estado_id">
                                                            <i class="fas fa-flag text-warning mr-1"></i>
                                                            Estado:
                                                        </label>
                                                        <select class="form-control" id="estado_id" name="estado_id">
                                                            <option value="">Todos los estados</option>
                                                            @foreach($estados as $estado)
                                                                <option value="{{ $estado->id }}">{{ $estado->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="tipo_trabajo_id">
                                                            <i class="fas fa-tools text-info mr-1"></i>
                                                            Tipo de Trabajo:
                                                        </label>
                                                        <select class="form-control" id="tipo_trabajo_id" name="tipo_trabajo_id">
                                                            <option value="">Todos los tipos</option>
                                                            @foreach($tipos_trabajo as $tipo)
                                                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Filtros Financieros -->
                                        <div class="form-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-dollar-sign text-warning mr-2"></i>
                                                Filtros por Monto
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="monto_min">
                                                            <i class="fas fa-arrow-up text-success mr-1"></i>
                                                            Monto Mínimo (Gs.):
                                                        </label>
                                                        <input type="number" class="form-control" id="monto_min" name="monto_min" placeholder="0" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="monto_max">
                                                            <i class="fas fa-arrow-down text-danger mr-1"></i>
                                                            Monto Máximo (Gs.):
                                                        </label>
                                                        <input type="number" class="form-control" id="monto_max" name="monto_max" placeholder="Sin límite" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Opciones del Reporte -->
                                        <div class="form-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-cogs text-info mr-2"></i>
                                                Contenido del Reporte
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="incluir_facturas" name="incluir_facturas" checked>
                                                            <label class="custom-control-label" for="incluir_facturas">
                                                                <i class="fas fa-file-invoice text-success mr-1"></i>
                                                                Incluir información de facturas
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="incluir_recibos" name="incluir_recibos" checked>
                                                            <label class="custom-control-label" for="incluir_recibos">
                                                                <i class="fas fa-receipt text-info mr-1"></i>
                                                                Incluir información de cobros
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="incluir_saldos" name="incluir_saldos" checked>
                                                            <label class="custom-control-label" for="incluir_saldos">
                                                                <i class="fas fa-calculator text-warning mr-1"></i>
                                                                Incluir cálculo de saldos
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="incluir_totales" name="incluir_totales" checked>
                                                            <label class="custom-control-label" for="incluir_totales">
                                                                <i class="fas fa-sum text-primary mr-1"></i>
                                                                Incluir totales generales
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botón Generar -->
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-generate">
                                                <i class="fas fa-file-pdf mr-2"></i>
                                                GENERAR REPORTE PDF
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        // Inicializar fechas por defecto
        document.addEventListener('DOMContentLoaded', function() {
            setQuickDate('month');
        });

        function setQuickDate(period) {
            const today = new Date();
            const startInput = document.getElementById('fecha_inicio');
            const endInput = document.getElementById('fecha_fin');

            let startDate, endDate = today;

            switch(period) {
                case 'today':
                    startDate = today;
                    break;
                case 'week':
                    startDate = new Date(today);
                    startDate.setDate(today.getDate() - today.getDay());
                    break;
                case 'month':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    break;
                case 'year':
                    startDate = new Date(today.getFullYear(), 0, 1);
                    break;
                case 'all':
                    startDate = new Date('2020-01-01'); // Fecha muy anterior
                    endDate = new Date(); // Hasta hoy
                    break;
            }

            startInput.value = startDate.toISOString().split('T')[0];
            endInput.value = endDate.toISOString().split('T')[0];
        }

        // Validación y envío del formulario
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            const fechaInicio = new Date(document.getElementById('fecha_inicio').value);
            const fechaFin = new Date(document.getElementById('fecha_fin').value);

            if (fechaInicio > fechaFin) {
                e.preventDefault();
                alert('La fecha de inicio no puede ser mayor que la fecha fin');
                return false;
            }

            // Cambiar botón a estado de carga
            const btn = e.target.querySelector('button[type="submit"]');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>GENERANDO PDF...';
            btn.disabled = true;

            // Restaurar botón después de 5 segundos
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }, 5000);
        });
    </script>
</body>
</html>
