<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarbonatacionTc extends Model
{
    use HasFactory;

    protected $table = 'carbonataciones_tc';

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
        return $this->hasMany(CarbonatacionDetalleTc::class, 'carbonatacion_tc_id');
    }
}
