<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantillaDetalle extends Model
{
    use HasFactory;

    protected $fillable = ['plantilla_id', 'ruta'];

    public function plantilla()
    {
        return $this->belongsTo(Plantilla::class, 'plantilla_id');
    }
}
