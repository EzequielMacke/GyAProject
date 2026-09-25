<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResistividadTc extends Model
{
    use HasFactory;

    protected $table = 'resistividades_tc';

    protected $fillable = [
        'obra_tc_id',
        'usuario_id',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function obra()
    {
        return $this->belongsTo(ObraTc::class, 'obra_tc_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }

    public function detalles()
    {
        return $this->hasMany(ResistividadDetalleTc::class, 'resistividad_tc_id');
    }
}
