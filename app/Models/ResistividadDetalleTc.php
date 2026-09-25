<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResistividadDetalleTc extends Model
{
    use HasFactory;

    protected $table = 'resistividad_detalles_tc';

    protected $fillable = [
        'resistividad_tc_id',
        'elemento',
        'nivel_pla_tc_id',
        'lecturas',
        'temperatura',
        'promedio',
        'correccion',
        'resistividad_final',
        'velocidad_corrosion',
    ];

    protected $casts = [
        'lecturas' => 'array',
    ];

    public function resistividad()
    {
        return $this->belongsTo(ResistividadTc::class, 'resistividad_tc_id');
    }

    public function nivel()
    {
        return $this->belongsTo(NivelPlaTc::class, 'nivel_pla_tc_id');
    }
}
