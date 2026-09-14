<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicionFisuraDetalleTc extends Model
{
    use HasFactory;

    protected $table = 'medicion_fisura_detalles_tc';

    protected $fillable = [
        'medicion_fisura_tc_id',
        'elemento',
        'nivel_pla_tc_id',
        'ancho',
        'espesores',
        'profundidades',
        'pasante',
        'promedio_espesor',
        'promedio_profundidad',
        'porcentaje_afectado',
    ];

    protected $casts = [
        'espesores' => 'array',
        'profundidades' => 'array',
        'pasante' => 'boolean',
    ];

    public function medicionFisura()
    {
        return $this->belongsTo(MedicionFisuraTc::class, 'medicion_fisura_tc_id');
    }

    public function nivel()
    {
        return $this->belongsTo(NivelPlaTc::class, 'nivel_pla_tc_id');
    }
}
