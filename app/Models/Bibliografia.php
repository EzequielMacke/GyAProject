<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bibliografia extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'fuente', 'estado', 'usuario_id'];

    protected $casts = [
        'estado' => 'integer',
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuarios::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(BibliografiaDetalle::class)->orderBy('orden');
    }
}
