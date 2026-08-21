<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorioTcAutomatico extends Model
{
    use HasFactory;
    protected $table = 'directorio_tc_automatico';
    protected $fillable = [
        'usuario_id',
        'agregado_por',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }

    public function agregadoPor()
    {
        return $this->belongsTo(Usuarios::class, 'agregado_por');
    }
}
