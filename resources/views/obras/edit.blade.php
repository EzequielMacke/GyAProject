<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Obra - {{ $obra->nombre }}</title>
    @include('partials.head')
    <style>
        .content-header {
            background: #ffc107;
            color: #212529;
            border-radius: 0 0 20px 20px;
            margin-bottom: 30px;
            padding: 30px 0;
        }
        .content-header h1 {
            color: #212529;
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
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }
        .btn-update {
            background: #ffc107;
            border: none;
            color: #212529;
            font-weight: 600;
            border-radius: 25px;
            padding: 15px 40px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .btn-update:hover {
            background: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
            color: #212529;
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
        .btn-show {
            background: #007bff;
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 25px;
            padding: 15px 40px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .btn-show:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
            color: white;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        .readonly-field {
            background-color: #f8f9fa !important;
        }
        .alert-custom {
            border-radius: 15px;
            border: none;
            padding: 20px;
            margin-bottom: 25px;
        }
        .info-badge {
            background: #17a2b8;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            margin-left: 10px;
        }
        .changes-indicator {
            position: fixed;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 25px;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        @media (max-width: 768px) {
            .content-header {
                text-align: center;
            }
            .form-card .card-body {
                padding: 20px;
            }
            .changes-indicator {
                position: static;
                transform: none;
                margin: 10px;
                text-align: center;
            }
        }
    </style>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('editar', 1)->isEmpty())
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
                                <i class="fas fa-edit mr-3"></i>
                                Editar Obra
                            </h1>
                            <p class="mb-0 opacity-75">
                                ID: #{{ $obra->id }} | {{ $obra->nombre }}
                                <span class="info-badge">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Creada: {{ \Carbon\Carbon::parse($obra->fecha_carga)->format('d/m/Y') }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-right mt-3 mt-md-0">
                            <a href="{{ route('obras.show', $obra->id) }}" class="btn btn-show mr-2">
                                <i class="fas fa-eye mr-2"></i>
                                Ver Obra
                            </a>
                            <a href="{{ route('obras.index') }}" class="btn btn-back" id="volver-btn">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver al Listado
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicator de cambios -->
            <div class="changes-indicator" id="changes-indicator">
                <i class="fas fa-save mr-2"></i>
                Hay cambios sin guardar
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

                    <form action="{{ route('obras.update', $obra->id) }}" method="POST" id="obra-form">
                        @csrf
                        @method('PUT')

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
                                            <input type="text" name="user" class="form-control readonly-field" id="user"
                                                   value="{{ $obra->usuario->nombre }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_carga" class="form-label">Fecha de Creación</label>
                                            <input type="date" name="fecha_carga" class="form-control readonly-field"
                                                   id="fecha_carga" value="{{ $obra->fecha_carga }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nombre" class="form-label required-field">Nombre de la Obra</label>
                                            <input type="text" name="nombre" class="form-control" id="nombre"
                                                   placeholder="Ingrese el nombre de la obra" required value="{{ $obra->nombre }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="direccion" class="form-label">Dirección</label>
                                            <input type="text" name="direccion" class="form-control" id="direccion"
                                                   placeholder="Dirección de la obra" value="{{ $obra->direccion }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="observacion" class="form-label">Observaciones</label>
                                    <textarea name="observacion" class="form-control" id="observacion" rows="4"
                                              placeholder="Observaciones adicionales sobre la obra">{{ $obra->observacion }}</textarea>
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
                                                   placeholder="Número de RUC" value="{{ $obra->ruc }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="razon_social" class="form-label">Razón Social</label>
                                            <input type="text" name="razon_social" class="form-control" id="razon_social"
                                                   placeholder="Razón social de la empresa" value="{{ $obra->razon_social }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="direccion_fac" class="form-label">Dirección de Facturación</label>
                                            <input type="text" name="direccion_fac" class="form-control" id="direccion_fac"
                                                   placeholder="Dirección para facturación" value="{{ $obra->direccion_fac }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="correo_fac" class="form-label">Email de Facturación</label>
                                            <input type="email" name="correo_fac" class="form-control" id="correo_fac"
                                                   placeholder="correo@empresa.com" value="{{ $obra->correo_fac }}">
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
                                                   placeholder="Nombre completo" value="{{ $obra->contacto }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="numero" class="form-label">Teléfono</label>
                                            <input type="tel" name="numero" class="form-control" id="numero"
                                                   placeholder="Número de teléfono" value="{{ $obra->numero }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="correo_pet" class="form-label">Email</label>
                                            <input type="email" name="correo_pet" class="form-control" id="correo_pet"
                                                   placeholder="correo@contacto.com" value="{{ $obra->correo_pet }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="peticionario" class="form-label">Peticionario</label>
                                    <input type="text" name="peticionario" class="form-control" id="peticionario"
                                           placeholder="Nombre del peticionario" value="{{ $obra->peticionario }}">
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
                                                   placeholder="Nombre del responsable" value="{{ $obra->nombre_obr }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="telefono_obr" class="form-label">Teléfono</label>
                                            <input type="tel" name="telefono_obr" class="form-control" id="telefono_obr"
                                                   placeholder="Teléfono del responsable" value="{{ $obra->telefono_obr }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="correo_obr" class="form-label">Email</label>
                                            <input type="email" name="correo_obr" class="form-control" id="correo_obr"
                                                   placeholder="correo@responsable.com" value="{{ $obra->correo_obr }}">
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
                                                   placeholder="Nombre del administrador" value="{{ $obra->nombre_adm }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="telefono_adm" class="form-label">Teléfono</label>
                                            <input type="tel" name="telefono_adm" class="form-control" id="telefono_adm"
                                                   placeholder="Teléfono del administrador" value="{{ $obra->telefono_adm }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="correo_adm" class="form-label">Email</label>
                                            <input type="email" name="correo_adm" class="form-control" id="correo_adm"
                                                   placeholder="correo@administrador.com" value="{{ $obra->correo_adm }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="card form-card">
                            <div class="card-body text-center">
                                <button type="submit" class="btn btn-update mr-3" id="save-btn">
                                    <i class="fas fa-save mr-2"></i>
                                    Actualizar Obra
                                </button>
                                <a href="{{ route('obras.show', $obra->id) }}" class="btn btn-show mr-3">
                                    <i class="fas fa-eye mr-2"></i>
                                    Ver Obra
                                </a>
                                <a href="{{ route('obras.index') }}" class="btn btn-back">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Guardar valores originales para detectar cambios
            const originalValues = {};
            const form = document.getElementById('obra-form');
            const inputs = form.querySelectorAll('input, textarea, select');
            const changesIndicator = document.getElementById('changes-indicator');

            inputs.forEach(input => {
                if (!input.readOnly) {
                    originalValues[input.name] = input.value;
                }
            });

            // Detectar cambios en tiempo real
            inputs.forEach(input => {
                if (!input.readOnly) {
                    input.addEventListener('input', function() {
                        checkForChanges();
                    });
                }
            });

            function checkForChanges() {
                let hasChanges = false;
                inputs.forEach(input => {
                    if (!input.readOnly && originalValues[input.name] !== input.value) {
                        hasChanges = true;
                    }
                });

                if (hasChanges) {
                    changesIndicator.style.display = 'block';
                    document.getElementById('save-btn').style.background = '#28a745';
                    document.getElementById('save-btn').innerHTML = '<i class="fas fa-save mr-2"></i>Guardar Cambios';
                } else {
                    changesIndicator.style.display = 'none';
                    document.getElementById('save-btn').style.background = '#ffc107';
                    document.getElementById('save-btn').innerHTML = '<i class="fas fa-save mr-2"></i>Actualizar Obra';
                }
            }

            // Enfocar primer campo editable
            document.getElementById('nombre').focus();

            // Atajos de teclado
            document.addEventListener('keydown', function(event) {
                // Ctrl + S para guardar
                if (event.ctrlKey && event.key === 's') {
                    event.preventDefault();
                    form.submit();
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

            // Validaciones visuales
            const requiredFields = document.querySelectorAll('input[required]');
            requiredFields.forEach(field => {
                field.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.style.borderColor = '#dc3545';
                    } else {
                        this.style.borderColor = '#28a745';
                    }
                });
            });

            // Confirmación antes de salir si hay cambios
            window.addEventListener('beforeunload', function(e) {
                let hasChanges = false;
                inputs.forEach(input => {
                    if (!input.readOnly && originalValues[input.name] !== input.value) {
                        hasChanges = true;
                    }
                });

                if (hasChanges) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });
        });
    </script>
</body>
</html>
