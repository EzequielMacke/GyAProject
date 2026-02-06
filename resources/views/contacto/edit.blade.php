<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contacto</title>
    @include('partials.head')
    <style>
        body { background: #f5f6fa; }
        .card-custom { border-radius: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); background: #fff; margin-top: 30px; margin-bottom: 30px; }
        .form-control, .form-select { border-radius: 10px; }
        .input-group-text { border-radius: 10px 0 0 10px; }
        .btn-primary, .btn-warning { border-radius: 10px; }
        .form-section-title { font-size: 1.1rem; font-weight: 600; color: #495057; margin-bottom: 10px; margin-top: 20px; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')
        <div class="content-wrapper" style="min-height: 100vh; background: transparent;">
            <div class="container-fluid">
                <div class="card card-custom p-4 w-100" style="min-width:0;">
                    <form action="{{ route('contacto.update', $contacto->id) }}" method="POST" class="w-100">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-8">
                                <h2 class="mb-0">Editar Contacto</h2>
                            </div>
                            <div class="col-4 text-end">
                                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Guardar</button>
                                <a href="{{ route('contacto.index', $obra) }}" class="btn btn-warning px-4 ms-2" id="volver-btn"><i class="fas fa-arrow-left me-2"></i>Volver</a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <input type="hidden" name="obra_id" value="{{ $obra ?? '' }}">
                        <div class="form-section-title">Datos del contacto</div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label for="nombre" class="form-label">Nombre</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="nombre" class="form-control" id="nombre" required placeholder="Ej: Juan Pérez" value="{{ old('nombre', $contacto->nombre) }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="tipo_contacto" class="form-label">Tipo de contacto</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                    <input type="text" name="tipo_contacto" class="form-control" id="tipo_contacto" placeholder="Ej: Administrativo, Contacto de obra, Peticionario, etc." value="{{ old('tipo_contacto', $contacto->tipo_contacto) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" name="telefono" class="form-control" id="telefono" placeholder="Ej: +595-123456" value="{{ old('telefono', $contacto->telefono) }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Ej: correo@ejemplo.com" value="{{ old('email', $contacto->email) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label for="presupuesto_id" class="form-label">Presupuesto vinculado</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-file-invoice-dollar"></i></span>
                                    <select name="presupuesto_id" id="presupuesto_id" class="form-select">
                                        <option value="">Sin vincular</option>
                                        @foreach($presupuestos as $presupuesto)
                                            <option value="{{ $presupuesto->id }}" {{ old('presupuesto_id', $contacto->presupuesto_id) == $presupuesto->id ? 'selected' : '' }}>{{ $presupuesto->clave }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-section-title">Observaciones</div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="observacion" class="form-label">Observación</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                    <textarea name="observacion" class="form-control" id="observacion" rows="4" placeholder="Ej: Llamar solo por la mañana">{{ old('observacion', $contacto->observacion) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials.footer')
    </div>
</body>
</html>
