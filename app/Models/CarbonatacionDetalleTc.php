<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarbonatacionDetalleTc extends Model
{
    use HasFactory;

    protected $table = 'carbonatacion_detalles_tc';

    protected $fillable = [
        'carbonatacion_tc_id',
        'elemento',
        'nivel_pla_tc_id',
        'recubrimiento',
        'espesor_carbonatado',
        'porcentaje_afectado',
    ];

    public function carbonatacion()
    {
        return $this->belongsTo(CarbonatacionTc::class, 'carbonatacion_tc_id');
    }

    public function nivel()
    {
        return $this->belongsTo(NivelPlaTc::class, 'nivel_pla_tc_id');
    }
}
