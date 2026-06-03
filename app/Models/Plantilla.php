<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    use HasFactory;

    protected $fillable = ['revision', 'nombre', 'descripcion', 'observacion', 'usuario', 'referencia'];

    protected $casts = [
        'referencia' => 'integer',
    ];

    public function usuarioRel()
    {
        return $this->belongsTo(\App\Models\Usuarios::class, 'usuario');
    }

    public function detalles()
    {
        return $this->hasMany(PlantillaDetalle::class, 'plantilla_id');
    }
}
