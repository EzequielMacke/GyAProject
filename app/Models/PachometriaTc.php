<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PachometriaTc extends Model
{
    use HasFactory;

    protected $table = 'pachometrias_tc';

    protected $fillable = [
        'obra_tc_id',
        'usuario_id',
        'orden',
        'numero',
        'tipo',
        'elemento',
        'datos',
    ];

    protected $casts = [
        'datos' => 'array',
    ];

    public function obra()
    {
        return $this->belongsTo(ObraTc::class, 'obra_tc_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }
}
