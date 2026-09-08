<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsclerometriaDetalleTc extends Model
{
    use HasFactory;

    protected $table = 'esclerometria_detalles_tc';

    protected $fillable = [
        'esclerometria_tc_id',
        'elemento',
        'direccion',
        'impactos',
        'promedio_inicial',
        'validos',
        'promedio_final',
        'n_corregido',
        'correccion_angulo',
        'n_final',
    ];

    protected $casts = [
        'impactos' => 'array',
    ];

    public function esclerometria()
    {
        return $this->belongsTo(EsclerometriaTc::class, 'esclerometria_tc_id');
    }
}
