<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obra extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'observacion',
        'estado',
        'usuario_id',
        'fecha_carga'
    ];
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
    public function presupuestos()
    {
        return $this->hasMany(PresupuestoAprobado::class, 'obra_id');
    }
    public function directorios()
    {
        return $this->hasMany(Directorio::class, 'obra_id');
    }
}

