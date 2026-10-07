@php
    $p   = $presupuesto;
    $url = route('presupuesto_aprobado.index', $p->obra_id);
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo presupuesto aprobado</title>
</head>
<body style="margin:0; padding:0; background:#f0f3f7; font-family:Arial, Helvetica, sans-serif; color:#1e2835;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f0f3f7; padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                   style="max-width:560px; background:#f8fafc; border:1px solid #d8e0ea; border-radius:12px; overflow:hidden;">
                <tr>
                    <td style="height:4px; background:#2a6fdb;"></td>
                </tr>
                <tr>
                    <td style="padding:24px 28px;">
                        <div style="font-size:20px; font-weight:bold;">
                            Nuevo presupuesto <span style="color:#2a6fdb;">aprobado</span>
                        </div>
                        <div style="font-size:14px; color:#445060; margin-top:12px; line-height:1.6;">
                            Se cargó un nuevo presupuesto aprobado
                            @if($p->obra)
                                para la obra <strong style="color:#1e2835;">{{ $p->obra->nombre }}</strong>
                            @endif
                            con el nombre <strong style="color:#1e2835;">{{ $p->clave }}</strong>.
                        </div>

                        @if($p->observacion)
                            <div style="margin-top:16px; padding:12px 14px; background:#edf1f6; border-radius:8px; font-size:13px; color:#445060;">
                                <strong style="color:#1e2835;">Observación:</strong><br>
                                {{ $p->observacion }}
                            </div>
                        @endif

                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
