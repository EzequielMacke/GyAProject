<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicionFisuraTc extends Model
{
    use HasFactory;

    protected $table = 'medicion_fisuras_tc';

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
        return $this->hasMany(MedicionFisuraDetalleTc::class, 'medicion_fisura_tc_id');
    }
}
