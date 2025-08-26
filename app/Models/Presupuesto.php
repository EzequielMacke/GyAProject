<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $table = 'presupuestos';

    protected $fillable = [
        'nombre',
        'fecha',
        'usuario_id',
        'observacion',
        'obra_id',
        'presupuesto',
        'conformidad',
        'orden_trabajo',
        'monto',
        'cotizacion',
        'moneda_id',
        'tipo_trabajo_id',
        'estado_id',
        'created_at',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }

    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }

    public function tipoTrabajo()
    {
        return $this->belongsTo(Tipo_trabajo::class, 'tipo_trabajo_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }
    public function recibos()
    {
        return $this->hasMany(Recibo::class);
    }
}
