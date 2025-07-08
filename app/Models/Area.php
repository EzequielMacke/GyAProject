<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = ['descripcion', 'estado'];

    /**
     * Get the users for the area.
     */
    public function usuarios()
    {
        return $this->hasMany(Usuarios::class);
    }
}
