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

    /**
     * Trabajos que se pueden activar dentro de "Trabajos desarrollados";
     * cada uno activado aparece como subparte del informe. El orden es el
     * del menú.
     */
    public const TRABAJOS = [
        'relevamiento_encofrado' => 'Relevamiento de encofrado',
        'esclerometria' => 'Ensayo de esclerometría',
        'ultrasonido' => 'Ensayo de ultrasonido',
        'carbonatacion' => 'Ensayo de carbonatación',
        'extraccion_testigos' => 'Extracción de testigos',
        'pachometria' => 'Ensayo de pachometría',
        'verificacion_estructural' => 'Verificación estructural',
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
