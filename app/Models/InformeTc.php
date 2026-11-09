<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformeTc extends Model
{
    protected $table = 'informes_tc';

    protected $fillable = [
        'obra_tc_id',
        'usuario_id',
        'titulo',
        'referencia',
        'datos',
    ];

    /** Todo el formulario del generador, tal como lo valida InformeRequest. */
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
