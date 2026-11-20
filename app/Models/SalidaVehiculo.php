<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo de vehículos para salidas. Se reutiliza en cualquier fecha.
 */
class SalidaVehiculo extends Model
{
    protected $table = 'salida_vehiculos';

    protected $fillable = ['nombre', 'oculto', 'usuario_id'];

    protected $casts = [
        'oculto' => 'boolean',
    ];

    public function programaciones()
    {
        return $this->belongsToMany(
            SalidaProgramacion::class,
            'salida_programacion_vehiculos',
            'salida_vehiculo_id',
            'salida_programacion_id'
        )->withPivot('fecha');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }
}
