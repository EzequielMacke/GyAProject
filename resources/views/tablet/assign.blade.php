<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Tablet</title>
    @include('partials.head')
    <style>
        body { background: #f5f6fa; }
        .card-custom { border-radius: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); background: #fff; margin-top: 30px; margin-bottom: 30px; }
        .form-control, .form-select { border-radius: 10px; }
        .input-group-text { border-radius: 10px 0 0 10px; }
        .btn-primary, .btn-warning { border-radius: 10px; }
        .form-section-title { font-size: 1.1rem; font-weight: 600; color: #495057; margin-bottom: 10px; margin-top: 20px; }
        .qr-cam { width: 320px; height: 320px; margin: 2rem auto 1rem auto; border: 3px solid #2f8f4a; border-radius: 12px; background: #fff; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <div class="content-wrapper" style="min-height: 100vh; background: transparent;">
            <div class="container-fluid px-0">
                <div class="card card-custom p-4 w-100" style="max-width: 540px; margin: 40px auto;">
                    <form method="POST" action="{{ route('tabletas.assign.retiro', ['clave' => $tableta->clave]) }}" class="w-100">
                        <div class="row mb-3">
                            <div class="col-8">
                                <h2 class="mb-0"><i class="fas fa-tablet-alt me-2"></i>Asignar Tablet</h2>
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
                        @csrf
                        <div class="form-section-title">Datos de la Tablet</div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label class="form-label"><i class="fas fa-key me-1"></i>Clave</label>
                                <input type="text" class="form-control" value="{{ $tableta->clave ?? '' }}" readonly>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label"><i class="fas fa-signature me-1"></i>Nombre</label>
                                <input type="text" class="form-control" value="{{ $tableta->nombre ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label class="form-label"><i class="fas fa-tablet me-1"></i>Modelo</label>
                                <input type="text" class="form-control" value="{{ $tableta->modelo ?? '' }}" readonly>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label"><i class="fas fa-barcode me-1"></i>Serie</label>
                                <input type="text" class="form-control" value="{{ $tableta->serie ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-2">
                                <label class="form-label"><i class="fas fa-sim-card me-1"></i>SIM</label>
                                <input type="text" class="form-control" value="{{ $tableta->sim ?? '' }}" readonly>
                            </div>
                        </div>
                        @if(!empty($tableta->observacion))
                        <div class="row mb-3">
                            <div class="col-md-12 mb-2">
                                <label class="form-label"><i class="fas fa-sticky-note me-1"></i>Observación</label>
                                <textarea class="form-control" rows="2" readonly>{{ $tableta->observacion }}</textarea>
                            </div>
                        </div>
                        @endif
                        <div class="form-section-title">Asignación</div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-2">
                                <label for="usuario" class="form-label"><i class="fas fa-user me-1"></i>Seleccionar usuario</label>
                                <select name="usuario" id="usuario" class="form-select select2-usuarios" required style="width:100%;">
                                    <option value="">-- Seleccione --</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-section-title">Escanear QR de Administrador</div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-12 d-flex flex-column align-items-center">
                                <div class="qr-reader-wrapper qr-reader-box">
                                    <div style="position:relative; width:100%; height:100%;">
                                        <div id="reader" class="qr-reader-custom"></div>
                                        <button type="button" id="btn-cambiar-camara" title="Cambiar cámara" style="position:absolute; top:10px; right:10px; z-index:10; background:#fff; border:1.5px solid #43a047; color:#43a047; border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px #43a04722; font-size:1.2em; cursor:pointer; padding:0;">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                        <div class="scanner-frame">
                                            <div class="scanner-corner tl"></div>
                                            <div class="scanner-corner tr"></div>
                                            <div class="scanner-corner bl"></div>
                                            <div class="scanner-corner br"></div>
                                            <div class="scanner-line" id="scanner-line"></div>
                                            <div class="aprobado-overlay" id="aprobado-overlay" style="display:none;">
                                                <span>APROBADO</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="qr_admin" id="qr_admin">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success px-5 py-2 fw-bold" id="btn-asignar" disabled style="font-size:1.2rem;"><i class="fas fa-check-circle me-2"></i>Confirmar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        .card-custom {
            max-width: 540px !important;
        }
        /* Área del lector QR con medidas fijas y borde */
        .qr-reader-box {
            position: relative;
            width: 370px;
            height: 370px;
            background: #fff;
            border: 2.5px solid #43a047;
            /* Sin border-radius */
            box-shadow: 0 2px 16px rgba(67,160,71,0.10);
            margin: 0 auto 18px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #reader video {
            width: 360px !important;
            height: 360px !important;
            object-fit: cover;
            /* Sin border-radius */
            box-shadow: 0 2px 12px rgba(47,143,74,0.10);
            margin: 0 auto;
            display: block;
            transition: filter 0.3s;
        }
        @media (max-width: 600px) {
            .qr-reader-box {
                width: 80vw;
                height: 80vw;
                min-width: 220px;
                min-height: 220px;
                max-width: 98vw;
                max-height: 98vw;
                /* Sin border-radius */
            }
            #reader video {
                width: 76vw !important;
                height: 76vw !important;
                /* Sin border-radius */
            }
        }
        /* Marco visual tipo escáner de película */
        .scanner-frame {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            pointer-events: none;
        }
        .scanner-corner {
            position: absolute;
            width: 32px; height: 32px;
            border: 4px solid #43a047;
        }
        .scanner-corner.tl { top: 0; left: 0; border-right: none; border-bottom: none; }
        .scanner-corner.tr { top: 0; right: 0; border-left: none; border-bottom: none; }
        .scanner-corner.bl { bottom: 0; left: 0; border-right: none; border-top: none; }
        .scanner-corner.br { bottom: 0; right: 0; border-left: none; border-top: none; }
        .scanner-line {
            position: absolute;
            left: 10px; right: 10px;
            height: 3px;
            background: linear-gradient(90deg, #ff5252 0%, #ff1744 100%);
            box-shadow: 0 0 8px #ff5252;
            animation: scanner-move 2s linear infinite;
            transition: none;
        }
        .scanner-line.paused {
            animation: none !important;
        }
        @keyframes scanner-move {
            0% { top: 10px; }
            100% { top: calc(100% - 10px); }
        }
        .aprobado-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.18);
            z-index: 2;
        }
        .aprobado-overlay span {
            font-size: 2.2rem;
            font-weight: bold;
            color: #43a047;
            background: rgba(255,255,255,0.85);
            padding: 18px 38px;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(67,160,71,0.13);
            letter-spacing: 0.12em;
        }
		.select2-container--default .select2-selection--single {
			border-radius: 12px;
			border: 1.5px solid #e0e0e0;
			background: #f8fafb;
			font-size: 1.08rem;
			height: 44px;
			padding: 6px 12px;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			color: #388e3c;
			line-height: 30px;
		}
		.select2-container--default .select2-selection--single .select2-selection__arrow {
			height: 44px;
		}
        
        
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Solicitar permiso de cámara al cargar la página
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .catch(function(err) {
                        // El usuario negó el permiso o no hay cámara
                    });
            }
            // Inicializar select2 para usuarios
            $('.select2-usuarios').select2({
                width: '100%',
                placeholder: '-- Seleccione --',
                allowClear: true
            });
            const btnAsignar = document.getElementById('btn-asignar');
            const qrAdminInput = document.getElementById('qr_admin');
            btnAsignar.disabled = true;
            let html5Qr = new Html5Qrcode('reader');
            let cameras = [];
            let currentCameraIdx = 0;
            function onScanSuccess(decodedText, decodedResult) {
                qrAdminInput.value = decodedText;
                const video = document.querySelector('#reader video');
                const scannerLine = document.getElementById('scanner-line');
                const aprobadoOverlay = document.getElementById('aprobado-overlay');
                if (decodedText === '9XQ2Z7LJ4B1V6KTP') {
                    btnAsignar.disabled = false;
                    if (video) video.style.filter = 'blur(6px)';
                    if (scannerLine) scannerLine.classList.add('paused');
                    if (aprobadoOverlay) aprobadoOverlay.style.display = 'flex';
                } else {
                    btnAsignar.disabled = true;
                    if (video) video.style.filter = '';
                    if (scannerLine) scannerLine.classList.remove('paused');
                    if (aprobadoOverlay) aprobadoOverlay.style.display = 'none';
                }
            }
            function startCamera(idx) {
                if (!cameras.length) return;
                // Si ya está corriendo, detener antes de iniciar la nueva cámara
                html5Qr.getState && html5Qr.getState() === 2
                    ? html5Qr.stop().then(() => {
                        html5Qr.start(
                            cameras[idx].id,
                            {
                                fps: 10,
                                qrbox: 360
                            },
                            onScanSuccess
                        );
                    })
                    : html5Qr.start(
                        cameras[idx].id,
                        {
                            fps: 10,
                            qrbox: 360
                        },
                        onScanSuccess
                    );
            }
            Html5Qrcode.getCameras().then(foundCameras => {
                cameras = foundCameras;
                if (cameras && cameras.length) {
                    currentCameraIdx = 0;
                    startCamera(currentCameraIdx);
                }
            }).catch(err => {
                // No hay cámaras disponibles
            });
            document.getElementById('usuario').addEventListener('change', function() {
                btnAsignar.disabled = true;
                qrAdminInput.value = '';
                html5Qr.stop().then(() => {
                    if (cameras && cameras.length) {
                        startCamera(currentCameraIdx);
                    }
                });
            });
            document.getElementById('btn-cambiar-camara').addEventListener('click', function() {
                if (!cameras.length) return;
                currentCameraIdx = (currentCameraIdx + 1) % cameras.length;
                startCamera(currentCameraIdx);
            });
        });
    </script>
</body>
</html>
