<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $fillable = ['area_id', 'modulo_id', 'ver', 'agregar', 'editar', 'eliminar'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

}
