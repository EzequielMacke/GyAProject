<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabletaUso extends Model
{
    use HasFactory;

    protected $table = 'tableta_usos';

    protected $fillable = [
        'tableta_id',
        'usuario_id',
        'fecha_retiro',
        'fecha_devolucion',
    ];

    public function tableta()
    {
        return $this->belongsTo(Tableta::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }
}
