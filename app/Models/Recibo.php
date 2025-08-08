<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $table = 'recibos';

    protected $fillable = [
        'factura_id',
        'numero',
        'concepto',
        'fecha',
        'monto',
        'moneda_id',
        'cotizacion',
        'usuario_id',
    ];

    // Relaciones
    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
}
