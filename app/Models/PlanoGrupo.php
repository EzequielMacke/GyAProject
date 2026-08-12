<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanoGrupo extends Model
{
    use HasFactory;
    protected $table = 'plano_grupo';
    protected $fillable = [
        'descripcion',
        'obra_id',
        'usuario_id',
    ];

    public function obra()
    {
        return $this->belongsTo(ObraTc::class, 'obra_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }

    public function subgrupos()
    {
        return $this->hasMany(PlanoSubgrupo::class, 'grupo_id');
    }
}
