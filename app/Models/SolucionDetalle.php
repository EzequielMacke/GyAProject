<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolucionDetalle extends Model
{
    use HasFactory;

    protected $table = 'solucion_detalles';

    protected $fillable = [
        'solucion_id',
        'foto',
        'usuario_id',
        'estado',
    ];

    public function solucion()
    {
        return $this->belongsTo(Solucion::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
}
