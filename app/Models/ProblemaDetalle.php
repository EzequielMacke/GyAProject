<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblemaDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'problema_id',
        'foto',
        'usuario_id',
        'estado',
    ];

    public function problema()
    {
        return $this->belongsTo(Problema::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
}
