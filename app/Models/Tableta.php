<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tableta extends Model
{
    use HasFactory;

    protected $table = 'tabletas';

    protected $fillable = [
        'clave',
        'nombre',
        'modelo',
        'serie',
        'sim',
        'estado',
        'codigo_qr',
        'observacion',
    ];
}
