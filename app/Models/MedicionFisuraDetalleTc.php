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
        'dos_lados',
        'espesores_b',
        'profundidades_b',
        'pasante',
        'promedio_espesor',
        'promedio_profundidad',
        'promedio_espesor_b',
        'promedio_profundidad_b',
        'porcentaje_afectado',
    ];

    protected $casts = [
        'espesores' => 'array',
        'profundidades' => 'array',
        'dos_lados' => 'boolean',
        'espesores_b' => 'array',
        'profundidades_b' => 'array',
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
