<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BibliografiaDetalle extends Model
{
    use HasFactory;

    protected $fillable = ['bibliografia_id', 'elemento_plantilla_id', 'descripcion', 'tamanio', 'estado', 'orden'];

    protected $casts = [
        'estado' => 'integer',
        'tamanio' => 'float',
        'orden' => 'integer',
    ];

    public function bibliografia()
    {
        return $this->belongsTo(Bibliografia::class);
    }

    public function elementoPlantilla()
    {
        return $this->belongsTo(ElementoPlantilla::class);
    }
}
