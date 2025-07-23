<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentoTrabajoDetalle extends Model
{
    use HasFactory;

    protected $table = 'documento_trabajo_detalles';

    protected $fillable = [
        'documento_id',
        'tipo_ensayo_id',
        'encargado_id',

    ];

    // Relación con Documento
    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }

    // Relación con Ensayo
    public function ensayo()
    {
        return $this->belongsTo(Tipo_ensayo::class, 'tipo_ensayo_id');
    }

    // Relación con Encargado
    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'encargado_id');
    }
}
