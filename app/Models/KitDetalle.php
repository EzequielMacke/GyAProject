<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'kit_id',
        'insumo_id',
        'cantidad',
        'unidad_medida_id',
    ];

    public function kit()
    {
        return $this->belongsTo(Kit::class, 'kit_id');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }
}
