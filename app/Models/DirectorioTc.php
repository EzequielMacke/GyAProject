<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorioTc extends Model
{
    use HasFactory;
    protected $table = 'directorio_tc';
    protected $fillable = [
        'obra_tc_id',
        'usuario_id',
        'agregado_por',
    ];

    public function obraTc()
    {
        return $this->belongsTo(ObraTc::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }

    public function agregadoPor()
    {
        return $this->belongsTo(Usuarios::class, 'agregado_por');
    }
}
