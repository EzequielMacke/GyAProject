<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanoSubgrupo extends Model
{
    use HasFactory;
    protected $table = 'plano_subgrupo';
    protected $fillable = [
        'descripcion',
        'obra_id',
        'grupo_id',
        'usuario_id',
    ];

    public function obra()
    {
        return $this->belongsTo(ObraTc::class, 'obra_id');
    }

    public function grupo()
    {
        return $this->belongsTo(PlanoGrupo::class, 'grupo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }

    public function planos()
    {
        return $this->hasMany(Plano::class, 'subgrupo_id');
    }
}
