<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Obra</title>
    @include('partials.head')
    <style>
        .content-header {
            background: #28a745;
            color: white;
            border-radius: 0 0 20px 20px;
            margin-bottom: 30px;
            padding: 30px 0;
        }
        .content-header h1 {
            color: white;
            font-weight: 600;
            margin: 0;
        }
        .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: none;
            margin-bottom: 25px;
        }
        .form-card .card-header {
            background: #f8f9fa;
            border-radius: 15px 15px 0 0;
            border-bottom: 1px solid #e9ecef;
            padding: 20px;
        }
        .form-card .card-body {
            padding: 30px;
        }
        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 8px;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .btn-save {
            background: #28a745;
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 25px;
            padding: 15px 40px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .btn-save:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
            color: white;
        }
        .btn-back {
            background: #6c757d;
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 25px;
            padding: 15px 40px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
            color: white;
        }
        .obras-list {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: none;
            max-height: 400px;
            overflow-y: auto;
        }
        .obra-item {
            padding: 12px 20px;
            border: none;
            border-bottom: 1px solid #f1f3f4;
            transition: all 0.3s ease;
        }
        .obra-item:hover {
            background-color: #f8f9ff;
        }
        .obra-item:last-child {
            border-bottom: none;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        .readonly-field {
            background-color: #f8f9fa !important;
        }
        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .search-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .alert-custom {
            border-radius: 15px;
            border: none;
            padding: 20px;
            margin-bottom: 25px;
        }
        @media (max-width: 768px) {
            .content-header {
                text-align: center;
            }
            .form-card .card-body {
                padding: 20px;
            }
        }
    </style>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('agregar', 1)->isEmpty())
        <script>
            window.location.href = "{{ url('/home') }}";
        </script>
    @endif
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')

        <div class="content-wrapper">
            <!-- Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2">
                                <i class="fas fa-plus-circle mr-3"></i>
                                Nueva Obra
                            </h1>
                            <p class="mb-0 opacity-75">Complete la información para registrar una nueva obra</p>
                        </div>
                        <div class="col-md-4 text-md-right mt-3 mt-md-0">
                            <a href="{{ route('obras.index') }}" class="btn btn-back" id="volver-btn">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver al Listado
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Mensajes de error -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                            <h5><i class="fas fa-exclamation-triangle mr-2"></i>Por favor corrija los siguientes errores:</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('obras.store') }}" method="POST" id="obra-form">
                        @csrf

                        <!-- Información General -->
                        <div class="card form-card">
                            <div class="card-header">
                                <h4 class="mb-0">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Información General
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user" class="form-label">Creado por</label>
                                            <input type="hidden" name="user_id" value="{{ session('usuario_id') }}">
                                            <input type="text" name="user" class="form-control readonly-field" id="user"
                                                   value="{{ session('usuario_nombre') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_carga" class="form-label">Fecha de Creación</label>
                                            <input type="date" name="fecha_carga" class="form-control readonly-field"
                                                   id="fecha_carga" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nombre" class="form-label required-field">Nombre de la Obra</label>
                                            <input type="text" name="nombre" class="form-control" id="nombre"
                                                   placeholder="Ingrese el nombre de la obra" required value="{{ old('nombre') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="direccion" class="form-label">Dirección</label>
                                            <input type="text" name="direccion" class="form-control" id="direccion"
                                                   placeholder="Dirección de la obra" value="{{ old('direccion') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="observacion" class="form-label">Observaciones</label>
                                    <textarea name="observacion" class="form-control" id="observacion" rows="4"
                                              placeholder="Observaciones adicionales sobre la obra">{{ old('observacion') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Facturación -->
                        <div class="card form-card">
                            <div class="card-header">
                                <h4 class="mb-0">
                                    <i class="fas fa-file-invoice text-success mr-2"></i>
                                    Información de Facturación
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ruc" class="form-label">RUC</label>
                                            <input type="text" name="ruc" class="form-control" id="ruc"
                                                   placeholder="Número de RUC" value="{{ old('ruc') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="razon_social" class="form-label">Razón Social</label>
                                            <input type="text" name="razon_social" class="form-control" id="razon_social"
                                                   placeholder="Razón social de la empresa" value="{{ old('razon_social') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="direccion_fac" class="form-label">Dirección de Facturación</label>
                                            <input type="text" name="direccion_fac" class="form-control" id="direccion_fac"
                                                   placeholder="Dirección para facturación" value="{{ old('direccion_fac') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="correo_fac" class="form-label">Email de Facturación</label>
                                            <input type="email" name="correo_fac" class="form-control" id="correo_fac"
                                                   placeholder="correo@empresa.com" value="{{ old('correo_fac') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contacto Principal/Peticionario -->
                        <div class="card form-card">
                            <div class="card-header">
                                <h4 class="mb-0">
                                    <i class="fas fa-user-tie text-primary mr-2"></i>
                                    Contacto Principal / Peticionario
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contacto" class="form-label">Nombre del Contacto</label>
                                            <input type="text" name="contacto" class="form-control" id="contacto"
                                                   placeholder="Nombre completo" value="{{ old('contacto') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="numero" class="form-label">Teléfono</label>
                                            <input type="tel" name="numero" class="form-control" id="numero"
                                                   placeholder="Número de teléfono" value="{{ old('numero') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="correo_pet" class="form-label">Email</label>
                                            <input type="email" name="correo_pet" class="form-control" id="correo_pet"
                                                   placeholder="correo@contacto.com" value="{{ old('correo_pet') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="peticionario" class="form-label">Peticionario</label>
                                    <input type="text" name="peticionario" class="form-control" id="peticionario"
                                           placeholder="Nombre del peticionario" value="{{ old('peticionario') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Responsable de Obra -->
                        <div class="card form-card">
                            <div class="card-header">
                                <h4 class="mb-0">
                                    <i class="fas fa-hard-hat text-warning mr-2"></i>
                                    Responsable de Obra
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre_obr" class="form-label">Nombre</label>
                                            <input type="text" name="nombre_obr" class="form-control" id="nombre_obr"
                                                   placeholder="Nombre del responsable" value="{{ old('nombre_obr') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="telefono_obr" class="form-label">Teléfono</label>
                                            <input type="tel" name="telefono_obr" class="form-control" id="telefono_obr"
                                                   placeholder="Teléfono del responsable" value="{{ old('telefono_obr') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="correo_obr" class="form-label">Email</label>
                                            <input type="email" name="correo_obr" class="form-control" id="correo_obr"
                                                   placeholder="correo@responsable.com" value="{{ old('correo_obr') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Administrador de Obra -->
                        <div class="card form-card">
                            <div class="card-header">
                                <h4 class="mb-0">
                                    <i class="fas fa-user-cog text-info mr-2"></i>
                                    Administrador de Obra
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nombre_adm" class="form-label">Nombre</label>
                                            <input type="text" name="nombre_adm" class="form-control" id="nombre_adm"
                                                   placeholder="Nombre del administrador" value="{{ old('nombre_adm') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="telefono_adm" class="form-label">Teléfono</label>
                                            <input type="tel" name="telefono_adm" class="form-control" id="telefono_adm"
                                                   placeholder="Teléfono del administrador" value="{{ old('telefono_adm') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="correo_adm" class="form-label">Email</label>
                                            <input type="email" name="correo_adm" class="form-control" id="correo_adm"
                                                   placeholder="correo@administrador.com" value="{{ old('correo_adm') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="card form-card">
                            <div class="card-body text-center">
                                <button type="submit" class="btn btn-save mr-3">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar Obra
                                </button>
                                <a href="{{ route('obras.index') }}" class="btn btn-back">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Lista de Obras Recientes -->
                    @if($obras->count() > 0)
                    <div class="card form-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-list text-primary mr-2"></i>
                                Obras Recientes ({{ $obras->count() }})
                            </h4>
                        </div>
                        <div class="card-body">
                            <input type="text" id="search-obras" class="form-control search-input"
                                   placeholder="🔍 Buscar en obras recientes...">

                            <div class="obras-list">
                                @foreach ($obras->take(10) as $obra)
                                    <div class="obra-item" data-name="{{ strtolower($obra->nombre) }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $obra->nombre }}</strong>
                                                @if($obra->direccion)
                                                    <br><small class="text-muted">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $obra->direccion }}
                                                    </small>
                                                @endif
                                            </div>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($obra->fecha_carga)->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Establecer fecha actual
            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var year = today.getFullYear();
            var todayDate = year + '-' + month + '-' + day;
            document.getElementById('fecha_carga').value = todayDate;

            // Enfocar primer campo
            document.getElementById('nombre').focus();

            // Búsqueda en obras
            const searchInput = document.getElementById('search-obras');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    var filter = this.value.toLowerCase();
                    var obras = document.querySelectorAll('.obra-item');

                    obras.forEach(function(obra) {
                        var name = obra.getAttribute('data-name');
                        if (name.includes(filter)) {
                            obra.style.display = '';
                        } else {
                            obra.style.display = 'none';
                        }
                    });
                });
            }

            // Atajos de teclado
            document.addEventListener('keydown', function(event) {
                // Ctrl + S para guardar
                if (event.ctrlKey && event.key === 's') {
                    event.preventDefault();
                    document.getElementById('obra-form').submit();
                }

                // Escape para volver
                if (event.key === 'Escape') {
                    event.preventDefault();
                    document.getElementById('volver-btn').click();
                }
            });

            // Auto-dismiss alerts
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 8000);

            // Validaciones en tiempo real
            const nombreInput = document.getElementById('nombre');
            if (nombreInput) {
                nombreInput.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.style.borderColor = '#dc3545';
                    } else {
                        this.style.borderColor = '#28a745';
                    }
                });
            }
        });
    </script>
</body>
</html>
