<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresupuestoAprobado extends Model
{
    use HasFactory;
    protected $table = 'presupuesto_aprobados';
    protected $fillable = [
        'fecha_carga',
        'fecha_aprobacion',
        'gestionado_por',
        'fecha_gestion',
        'usuario_id',
        'validado_por',
        'obra_id',
        'presupuesto',
        'ubicacion',
        'observacion',
        'monto_total',
        'estado',
        'tipo_trabajo',
        'orden_trabajo',
        'anticipo',
        'clave',
    ];
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
    public function usuarioValidado()
    {
        return $this->belongsTo(Usuarios::class, 'validado_por');
    }
    public function usuarioGestion()
    {
        return $this->belongsTo(Usuarios::class, 'gestionado_por');
    }
    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }
}
