<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo de salidas. Se reutiliza en cualquier fecha.
 */
class Salida extends Model
{
    protected $table = 'salidas';

    protected $fillable = ['nombre', 'oculto', 'usuario_id'];

    protected $casts = [
        'oculto' => 'boolean',
    ];

    public function programaciones()
    {
        return $this->hasMany(SalidaProgramacion::class, 'salida_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }
}
