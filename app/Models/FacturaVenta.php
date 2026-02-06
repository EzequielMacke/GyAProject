<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaVenta extends Model
{
    use HasFactory;

    protected $table = 'factura_ventas';

    protected $fillable = [
        'nro_factura',
        'fecha_emision',
        'concepto',
        'monto',
        'saldo',
        'presupuesto_aprobado_id',
        'usuario_id',
        'obra_id',
    ];


    /**
     * Relación: FacturaVenta pertenece a un PresupuestoAprobado
     */
    public function presupuestoAprobado()
    {
        return $this->belongsTo(PresupuestoAprobado::class, 'presupuesto_aprobado_id');
    }

    /**
     * Relación: FacturaVenta pertenece a un Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }

    /**
     * Relación: FacturaVenta pertenece a una Obra
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'obra_id');
    }

    /**
     * Relación: FacturaVenta tiene muchos RecibidoVenta
     */
    public function recibosVenta()
    {
        return $this->hasMany(RecibidoVenta::class, 'factura_id');
    }
}
