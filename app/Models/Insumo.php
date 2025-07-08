<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'estado', 'usuario_id'];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
}

