<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoTc extends Model
{
    use HasFactory;

    protected $table = 'fotos_tc';

    protected $fillable = [
        'obra_tc_id',
        'plano_tc_id',
        'clasificacion',
        'archivo',
        'pos_x',
        'pos_y',
        'usuario_id',
    ];

    public function obra()
    {
        return $this->belongsTo(ObraTc::class, 'obra_tc_id');
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'plano_tc_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
}
