<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solucion extends Model
{
    use HasFactory;

    protected $table = 'soluciones';

    protected $fillable = [
        'problema_id',
        'descripcion',
        'observacion',
        'stamp',
        'avance',
        'estado',
        'orden',
        'usuario_id',
    ];

    public function problema()
    {
        return $this->belongsTo(Problema::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }

    public function detalles()
    {
        return $this->hasMany(SolucionDetalle::class);
    }
}
