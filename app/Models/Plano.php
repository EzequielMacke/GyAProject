<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;
    protected $table = 'planos';
    protected $fillable = [
        'descripcion',
        'obra_id',
        'grupo_id',
        'subgrupo_id',
        'archivo',
        'archivo_original',
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

    public function subgrupo()
    {
        return $this->belongsTo(PlanoSubgrupo::class, 'subgrupo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
}
