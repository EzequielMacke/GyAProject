<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_ensayo extends Model
{
    use HasFactory;

    protected $table = 'tipo_ensayos';

    protected $fillable = [
        'descripcion',
        'estado',
        'tipo_trabajo_id',
    ];
}
