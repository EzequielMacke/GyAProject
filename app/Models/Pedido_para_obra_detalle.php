<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido_para_obra_detalle extends Model
{
    use HasFactory;

    protected $fillable = ['pedido_para_obra_id','insumo_id','cantidad','medida','confirmado','usuario_id'];


    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }
    public function pedobra()
    {
        return $this->belongsTo(Pedido_para_obra::class);
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}

