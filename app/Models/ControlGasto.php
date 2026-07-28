<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlGasto extends Model
{
    use HasFactory;
    protected $table = 'control_gastos';
    protected $fillable = [
        'obra_id',
        'presupuesto_aprobado_id',
        'ingenieros',
        'tecnicos',
        'mano_obra',
        'otros',
        'observacion',
    ];

    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }

    public function presupuestoAprobado()
    {
        return $this->belongsTo(PresupuestoAprobado::class);
    }
}
