<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObraTc extends Model
{
    use HasFactory;
    protected $table = 'obras_tc';
    protected $fillable = [
        'descripcion',
        'estado',
        'usuario_id',
    ];

    public function directorios()
    {
        return $this->hasMany(DirectorioTc::class, 'obra_tc_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
}
