<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentoDetalle extends Model
{
    use HasFactory;

    protected $table = 'documento_detalles';

    protected $fillable = [
        'documento_id',
        'tipo_ensayo_id',
        'ruta',
        'pie',
        'identificador',
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }
}
