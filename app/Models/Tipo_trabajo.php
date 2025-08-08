<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_trabajo extends Model
{
    use HasFactory;

    protected $table = 'tipo_trabajos';

    protected $fillable = [
        'nombre',
        'estado',
    ];

}
