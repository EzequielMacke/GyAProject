<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UltrasonidoIndirectoDetalleTc extends Model
{
    use HasFactory;

    protected $table = 'ultrasonido_indirecto_detalles_tc';

    protected $fillable = [
        'ultrasonido_indirecto_tc_id',
        'elemento',
        'velocidades',
        'promedio',
        'desviacion_estandar',
        'coeficiente_variacion',
        'repetir_ensayo',
    ];

    protected $casts = [
        'velocidades' => 'array',
        'repetir_ensayo' => 'boolean',
    ];

    public function ultrasonidoIndirecto()
    {
        return $this->belongsTo(UltrasonidoIndirectoTc::class, 'ultrasonido_indirecto_tc_id');
    }
}
