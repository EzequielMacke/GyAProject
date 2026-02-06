<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'obra_id',
        'usuario_id',
        'fecha',
    ];

    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
}
