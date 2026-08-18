<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanoActividad extends Model
{
    public $timestamps = false;

    protected $table = 'plano_actividades';

    protected $fillable = [
        'plano_id',
        'usuario_id',
        'accion',
        'tool',
        'detalle',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'plano_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
}
