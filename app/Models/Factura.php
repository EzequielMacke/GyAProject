<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'numero',
        'presupuesto_id',
        'concepto',
        'monto',
        'cotizacion',
        'moneda_id',
        'fecha',
        'usuario_id',
    ];

    // Relaciones
    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
    public function recibos()
    {
        return $this->hasMany(Recibo::class);
    }
}
