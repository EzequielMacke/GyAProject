<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    protected $table = 'monedas'; // Actualizado para coincidir con la migración

    protected $fillable = [
        'nombre',
        'simbolo',
    ];
}
