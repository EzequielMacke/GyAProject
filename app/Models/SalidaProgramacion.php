<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Una salida puesta en el tablero de una fecha, con sus vehículos y personal.
 */
class SalidaProgramacion extends Model
{
    protected $table = 'salida_programaciones';

    protected $fillable = ['fecha', 'salida_id', 'usuario_id'];

    protected $casts = [
        'fecha' => 'date:Y-m-d',
    ];

    public function salida()
    {
        return $this->belongsTo(Salida::class, 'salida_id');
    }

    public function vehiculos()
    {
        return $this->belongsToMany(
            SalidaVehiculo::class,
            'salida_programacion_vehiculos',
            'salida_programacion_id',
            'salida_vehiculo_id'
        )->withPivot('fecha');
    }

    public function personales()
    {
        return $this->belongsToMany(
            SalidaPersonal::class,
            'salida_programacion_personales',
            'salida_programacion_id',
            'salida_personal_id'
        )->withPivot('fecha');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }
}
