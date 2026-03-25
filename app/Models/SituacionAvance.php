<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SituacionAvance extends Model
{
    use HasFactory;

    protected $fillable = [
        'presupuesto_aprobado_id',
        'fecha_inicio',
        'fecha_fin',
        'estado_situacion_id',
        'plazo',
        'observacion',
    ];

    public function presupuestoAprobado()
    {
        return $this->belongsTo(PresupuestoAprobado::class);
    }

    public function estadoSituacion()
    {
        return $this->belongsTo(EstadoSituacion::class);
    }
}
