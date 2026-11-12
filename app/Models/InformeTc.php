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
        'relevamiento_encofrado' => 'Relevamiento geométrico',
        'esclerometria' => 'Ensayo de esclerometría',
        'ultrasonido' => 'Ensayo de ultrasonido',
        'carbonatacion' => 'Ensayo de carbonatación',
        'extraccion_testigos' => 'Extracción de testigos',
        'pachometria' => 'Ensayo de pachometría',
        'georradar' => 'Ensayo de georradar',
        'resistividad' => 'Ensayo de resistividad',
        'potencial' => 'Ensayo de potencial de corrosión',
        'cloruros' => 'Ensayo de cloruros',
        'verificacion_estructural' => 'Verificación estructural',
    ];

    /**
     * Secciones con descripción + contenido (planos, fotos, planillas y
     * textos): guardan {seccion}_descripcion y {seccion}_elementos.
     */
    public const SECCIONES_CON_CONTENIDO = [
        'relevamiento_geometrico',
        'ensayos_resistencia',
        'esclerometria',
    ];

    /** Planillas de ensayo (de Planillas de la obra) que se pueden agregar al informe. */
    public const PLANILLAS = [
        'esclerometria',
    ];

    /**
     * Secciones con descripción + fotografías (bloques de fotos con su pie,
     * como en Características de la obra): guardan {seccion}_descripcion y
     * {seccion}_fotos.
     */
    public const SECCIONES_CON_FOTOS = [
        'esclerometria',
    ];

    /**
     * Grupos de ensayos: título del grupo y las claves de TRABAJOS que
     * incluye, en orden. Los trabajos que no están en ningún grupo
     * (relevamiento, verificación estructural) van sueltos.
     */
    public const GRUPOS_ENSAYOS = [
        'resistencia' => [
            'titulo' => 'Ensayos para determinar la resistencia del hormigón',
            'trabajos' => ['esclerometria', 'ultrasonido', 'extraccion_testigos', 'carbonatacion'],
        ],
        'armaduras' => [
            'titulo' => 'Ensayos para la verificación de armaduras',
            'trabajos' => ['pachometria', 'georradar'],
        ],
        'durabilidad' => [
            'titulo' => 'Ensayos para determinar la durabilidad',
            'trabajos' => ['resistividad', 'potencial', 'cloruros'],
        ],
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
