<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamiento extends Model
{
    use HasFactory;


    protected $fillable = ['obra_id','fecha','presupuesto_id','usuario_id','mes',
    'inicio','fin','estado','observacion'];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
    public function presupuesto()
    {
        return $this->belongsTo(PresupuestoAprobado::class);
    }
    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }
}

