<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtiquetaTc extends Model
{
    use HasFactory;

    protected $table = 'etiquetas_tc';

    protected $fillable = [
        'obra_tc_id',
        'descripcion',
    ];

    public function obra()
    {
        return $this->belongsTo(ObraTc::class, 'obra_tc_id');
    }

    public function detalles()
    {
        return $this->hasMany(EtiquetaDetalleTc::class, 'etiqueta_tc_id');
    }
}
