<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo de personal para salidas. Se reutiliza en cualquier fecha.
 */
class SalidaPersonal extends Model
{
    protected $table = 'salida_personales';

    protected $fillable = ['nombre', 'oculto', 'usuario_id'];

    protected $casts = [
        'oculto' => 'boolean',
    ];

    public function programaciones()
    {
        return $this->belongsToMany(
            SalidaProgramacion::class,
            'salida_programacion_personales',
            'salida_personal_id',
            'salida_programacion_id'
        )->withPivot('fecha');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }
}
