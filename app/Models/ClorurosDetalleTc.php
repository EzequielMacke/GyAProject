<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClorurosDetalleTc extends Model
{
    use HasFactory;

    protected $table = 'cloruros_detalles_tc';

    protected $fillable = [
        'cloruros_tc_id',
        'elemento',
        'nivel_pla_tc_id',
        'recubrimiento',
        'espesores',
        'espesor_cloruros',
        'porcentaje_afectado',
    ];

    protected $casts = [
        'espesores' => 'array',
    ];

    public function cloruros()
    {
        return $this->belongsTo(ClorurosTc::class, 'cloruros_tc_id');
    }

    public function nivel()
    {
        return $this->belongsTo(NivelPlaTc::class, 'nivel_pla_tc_id');
    }
}
