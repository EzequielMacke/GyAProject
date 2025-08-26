<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obra extends Model
{
    use HasFactory;

    protected $fillable = ['nombre','direccion','contacto','numero',
    'peticionario','observacion','estado','usuario_id','fecha_carga','ruc','razon_social',
    'direccion_fac','correo_fac','correo_pet','nombre_obr','telefono_obr','correo_obr',
    'nombre_adm','telefono_adm','correo_adm'];
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class);
    }
}

