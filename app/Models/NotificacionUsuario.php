<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificacionUsuario extends Model
{
    public const PRESUPUESTO_APROBADO = 'presupuesto_aprobado';

    protected $table = 'notificacion_usuarios';

    protected $fillable = [
        'tipo',
        'usuario_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }

    /**
     * Correos de los usuarios activos suscriptos a un tipo de notificación.
     */
    public static function correosPorTipo(string $tipo): array
    {
        return Usuarios::whereIn('id', static::where('tipo', $tipo)->select('usuario_id'))
            ->where('estado', 1)
            ->whereNotNull('correo')
            ->where('correo', '!=', '')
            ->pluck('correo')
            ->unique()
            ->values()
            ->all();
    }
}
