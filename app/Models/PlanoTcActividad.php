<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanoTcActividad extends Model
{
    public $timestamps = false;

    protected $table = 'planos_tc_actividades';

    protected $fillable = [
        'obra_id',
        'usuario_id',
        'accion',
        'detalle',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function obra()
    {
        return $this->belongsTo(ObraTc::class, 'obra_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
}
