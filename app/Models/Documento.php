<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'nombre',
        'tipo_trabajo_id',
        'tipo_documento_id',
        'obra',
        'mes',
        'año',
        'peticionario',
        'referencia',
        'fecha_presupuesto',
        'ubicacion',
        'objeto_alcance',
        'usuario_id',

    ];

    public function detalles()
    {
        return $this->hasMany(DocumentoDetalle::class, 'documento_id');
    }
    public function tipoDocumento()
    {
        return $this->belongsTo(Tipo_documento::class, 'tipo_documento_id');
    }

    public function tipoTrabajo()
    {
        return $this->belongsTo(Tipo_trabajo::class, 'tipo_trabajo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }

    public function trabajosDetalles()
    {
        return $this->hasMany(DocumentoTrabajoDetalle::class, 'documento_id');
    }
}
