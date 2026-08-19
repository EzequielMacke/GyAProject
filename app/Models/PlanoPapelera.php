<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanoPapelera extends Model
{
    public $timestamps = false;

    protected $table = 'planos_papelera';

    protected $fillable = [
        'plano_id_original',
        'descripcion',
        'obra_id',
        'grupo_id',
        'subgrupo_id',
        'archivo',
        'archivo_original',
        'usuario_id',
        'rotacion',
        'estado',
        'actividades',
        'creado_originalmente_at',
        'eliminado_por',
        'eliminado_at',
    ];

    protected $casts = [
        'creado_originalmente_at' => 'datetime',
        'eliminado_at' => 'datetime',
    ];

    public function obra()
    {
        return $this->belongsTo(ObraTc::class, 'obra_id');
    }

    public function grupo()
    {
        return $this->belongsTo(PlanoGrupo::class, 'grupo_id');
    }

    public function subgrupo()
    {
        return $this->belongsTo(PlanoSubgrupo::class, 'subgrupo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }

    public function eliminadoPor()
    {
        return $this->belongsTo(Usuarios::class, 'eliminado_por');
    }
}
