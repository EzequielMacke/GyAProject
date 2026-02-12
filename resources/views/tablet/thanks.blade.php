<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Gracias!</title>
    @include('partials.head')
    <style>
        body {
            background: linear-gradient(135deg, #e3f0fc 0%, #bbdefb 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .thanks-container {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 4px 32px rgba(33,150,243,0.13);
            padding: 38px 28px 32px 28px;
            text-align: center;
            max-width: 370px;
            width: 92vw;
            animation: pop-in 0.7s cubic-bezier(.68,-0.55,.27,1.55);
        }
        @keyframes pop-in {
            0% { transform: scale(0.7); opacity: 0; }
            80% { transform: scale(1.08); opacity: 1; }
            100% { transform: scale(1); }
        }
        .thanks-icon {
            font-size: 4.5rem;
            color: #1976d2;
            margin-bottom: 18px;
            animation: tech-bounce 1.2s infinite alternate;
        }
        @keyframes tech-bounce {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(-14px) scale(1.08); }
        }
        .thanks-title {
            font-size: 2.1rem;
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 10px;
            letter-spacing: 0.04em;
        }
        .thanks-text {
            font-size: 1.15rem;
            color: #1565c0;
            margin-bottom: 18px;
        }
        .thanks-footer {
            font-size: 1rem;
            color: #2196f3;
            margin-top: 18px;
            opacity: 0.8;
            animation: fadein 2s 1.2s both;
        }
        @keyframes fadein {
            from { opacity: 0; }
            to { opacity: 0.8; }
        }
        .thanks-circuit {
            position: absolute;
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #1976d2 60%, #90caf9 100%);
            border-radius: 12px;
            opacity: 0.13;
            z-index: 0;
            animation: circuit-float 3.5s infinite alternate ease-in-out;
        }
        .circuit1 { left: -22px; top: -22px; transform: rotate(-18deg); }
        .circuit2 { right: -22px; top: 18px; transform: rotate(22deg); animation-delay: 1.2s; }
        .circuit3 { left: 32px; bottom: -22px; transform: rotate(12deg); animation-delay: 2.1s; }
        @keyframes circuit-float {
            0% { transform: translateY(0) scale(1) rotate(var(--angle,0deg)); }
            100% { transform: translateY(-12px) scale(1.08) rotate(var(--angle,0deg)); }
        }
    </style>
</head>
<body>
    <div class="thanks-container" style="position:relative;">
        <div class="thanks-circuit circuit1"></div>
        <div class="thanks-circuit circuit2"></div>
        <div class="thanks-circuit circuit3"></div>
        <div class="thanks-icon">
            <i class="fas fa-microchip"></i>
        </div>
        <div class="thanks-title">¡Gracias!</div>
        <div class="thanks-text">
            Gracias por utilizar el sistema.<br>
            Tu acción se registró correctamente.<br>
            <span style="font-size:1.5rem;"><i class="fas fa-laptop-code"></i></span>
        </div>
        @if (!empty($mensaje))
        <div class="alert alert-danger" style="margin: 10px 0 0 0; font-size:1.08rem; color:#1565c0; background: #e3f2fd; border-radius: 10px; padding: 10px 12px; animation: fadein 1s; border: 1.5px solid #1976d2;">
            <i class="fas fa-exclamation-triangle"></i> {{ $mensaje }}
        </div>
        @endif
        <div class="thanks-footer">
            <i class="fas fa-microchip"></i> Gavilan y Asociados S.A.
        </div>
    </div>
</body>
</html>
