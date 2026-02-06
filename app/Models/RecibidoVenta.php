<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecibidoVenta extends Model
{
    use HasFactory;

    protected $table = 'recibo_ventas';

    protected $fillable = [
        'nro_recibo',
        'fecha_emision',
        'concepto',
        'monto',
        'factura_id',
        'presupuesto_aprobado_id',
        'usuario_id',
        'obra_id',
    ];

    /**
     * Relación: RecibidoVenta pertenece a una FacturaVenta
     */
    public function facturaVenta()
    {
        return $this->belongsTo(FacturaVenta::class, 'factura_id');
    }

    /**
     * Relación: RecibidoVenta pertenece a un PresupuestoAprobado
     */
    public function presupuestoAprobado()
    {
        return $this->belongsTo(PresupuestoAprobado::class, 'presupuesto_aprobado_id');
    }

    /**
     * Relación: RecibidoVenta pertenece a un Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }

    /**
     * Relación: RecibidoVenta pertenece a una Obra
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'obra_id');
    }
}
