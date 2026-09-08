<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsclerometriaTc extends Model
{
    use HasFactory;

    protected $table = 'esclerometrias_tc';

    protected $fillable = [
        'obra_tc_id',
        'usuario_id',
        'fecha',
        'lectura_inicial_yunque',
        'lectura_final_yunque',
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
        return $this->hasMany(EsclerometriaDetalleTc::class, 'esclerometria_tc_id');
    }
}
