<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $fillable = [
        'obra_id',
        'presupuesto_id',
        'nombre',
        'telefono',
        'email',
        'observacion',
        'tipo_contacto',
    ];

    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }

    public function presupuesto()
    {
        return $this->belongsTo(PresupuestoAprobado::class, 'presupuesto_id');
    }
}
