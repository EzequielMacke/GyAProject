<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido_para_obra extends Model
{
    use HasFactory;

    protected $fillable = ['obra_id','fecha_pedido','fecha_entrega','usuario_id','observacion',
    'total_insumo','insumo_confirmado','insumo_faltante','estado'];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }
    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }
    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }
    public function detalles()
    {
        return $this->hasMany(Pedido_para_obra_detalle::class);
    }
}

