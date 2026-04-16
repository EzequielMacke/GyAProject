<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problema extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'descripcion',
        'stamp',
        'avance',
        'estado',
        'orden',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }

    public function detalles()
    {
        return $this->hasMany(ProblemaDetalle::class);
    }

    public function soluciones()
    {
        return $this->hasMany(Solucion::class);
    }
}
