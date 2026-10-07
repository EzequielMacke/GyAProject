<?php

namespace App\Services;

use App\Models\NotificacionUsuario;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificacionService
{
    /**
     * Envía el correo a cada usuario suscripto al tipo de notificación.
     * Un fallo de envío se registra en el log y no interrumpe la operación que lo dispara.
     */
    public function enviar(string $tipo, Mailable $mail): void
    {
        foreach (NotificacionUsuario::correosPorTipo($tipo) as $correo) {
            try {
                Mail::to($correo)->send(clone $mail);
            } catch (\Throwable $e) {
                Log::error("No se pudo enviar la notificación '{$tipo}' a {$correo}: " . $e->getMessage());
            }
        }
    }
}
