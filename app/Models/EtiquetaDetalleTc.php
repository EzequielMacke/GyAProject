<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtiquetaDetalleTc extends Model
{
    use HasFactory;

    protected $table = 'etiqueta_detalles_tc';

    protected $fillable = [
        'foto_tc_id',
        'etiqueta_tc_id',
    ];

    public function foto()
    {
        return $this->belongsTo(FotoTc::class, 'foto_tc_id');
    }

    public function etiqueta()
    {
        return $this->belongsTo(EtiquetaTc::class, 'etiqueta_tc_id');
    }
}
