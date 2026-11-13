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
        'ultrasonido',
    ];

    /** Planillas de ensayo (de Planillas de la obra) que se pueden agregar al informe. */
    public const PLANILLAS = [
        'esclerometria',
        'ultrasonido',
    ];

    /**
     * Secciones con descripción + fotografías (bloques de fotos con su pie,
     * como en Características de la obra): guardan {seccion}_descripcion y
     * {seccion}_fotos.
     */
    public const SECCIONES_CON_FOTOS = [
        'esclerometria',
        'ultrasonido',
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

    /**
     * Cómo se marca cada ensayo en los planos (los mismos de ENSAYOS en
     * planos_tc/plano.blade.php): letra con su color, ícono en
     * public/img/iconos y la capa (tool) del plano. Con esto se arma la
     * tabla "Referencias de ensayos" de cada plano de los grupos de ensayos
     * en el Word, con los ensayos cuya capa está prendida en ese plano.
     */
    public const ICONOS_ENSAYOS = [
        'esclerometria' => ['nombre' => 'Esclerometría', 'letra' => 'E', 'color' => 'D800C9', 'icono' => 'esclerometria.svg', 'capa' => 'esclerometria'],
        'ultrasonido' => ['nombre' => 'Ultrasonido', 'letra' => 'U', 'color' => 'E00000', 'icono' => 'ultrasonido.svg', 'capa' => 'ultrasonido'],
        'extraccion_testigos' => ['nombre' => 'Extracción de testigos', 'letra' => 'T', 'color' => '0A5C26', 'icono' => 'testigos.svg', 'capa' => 'testigos'],
        'carbonatacion' => ['nombre' => 'Carbonatación', 'letra' => 'C', 'color' => '1F4FD8', 'icono' => 'carbonatacion.svg', 'capa' => 'carbonatacion'],
        'pachometria' => ['nombre' => 'Pachometría', 'letra' => 'Pch', 'color' => '1F4FD8', 'icono' => 'pachometria.svg', 'capa' => 'pachometria'],
        'georradar' => ['nombre' => 'Georradar', 'letra' => 'Geo', 'color' => '006400', 'icono' => 'georradar.svg', 'capa' => 'georradar'],
        'resistividad' => ['nombre' => 'Resistividad', 'letra' => 'R', 'color' => 'CF9C26', 'icono' => 'Resistividad.svg', 'capa' => 'resistividad'],
        'potencial' => ['nombre' => 'Potencial de corrosión', 'letra' => 'P', 'color' => 'EA0D0D', 'icono' => 'Potencial.svg', 'capa' => 'potencial'],
        'cloruros' => ['nombre' => 'Cloruros', 'letra' => 'CL', 'color' => 'EA0D0D', 'icono' => 'Cloruros.svg', 'capa' => 'cloruros'],
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
