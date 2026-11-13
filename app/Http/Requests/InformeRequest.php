<?php

namespace App\Http\Requests;

use App\Models\InformeTc;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Formulario del generador de informes. Lo usan tanto "Generar" como
 * "Guardar", así las dos acciones aceptan exactamente los mismos datos.
 */
class InformeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->reglasBase()
            + array_merge(...array_map(
                fn (string $seccion) => $this->reglasSeccionConContenido($seccion),
                InformeTc::SECCIONES_CON_CONTENIDO
            ))
            + array_merge(...array_map(
                fn (string $seccion) => $this->reglasSeccionConFotos($seccion),
                InformeTc::SECCIONES_CON_FOTOS
            ));
    }

    /**
     * Descripción y bloques de fotos de una sección; ver
     * InformeTc::SECCIONES_CON_FOTOS.
     */
    private function reglasSeccionConFotos(string $seccion): array
    {
        $fotos = $seccion . '_fotos';

        return [
            $seccion . '_descripcion' => 'nullable|string',
            $fotos => 'nullable|array',
            $fotos . '.*.ids' => 'nullable|string',
            $fotos . '.*.pie' => 'nullable|string|max:500',
        ];
    }

    /**
     * Descripción y elementos en orden (plano, bloque de fotos, planilla o texto) de
     * una sección con contenido; ver InformeTc::SECCIONES_CON_CONTENIDO.
     */
    private function reglasSeccionConContenido(string $seccion): array
    {
        $elementos = $seccion . '_elementos';

        return [
            $seccion . '_descripcion' => 'nullable|string',
            $elementos => 'nullable|array',
            $elementos . '.*.tipo' => 'required|string|in:plano,fotos,planilla,texto',
            $elementos . '.*.plano_id' => 'nullable|integer',
            $elementos . '.*.planilla' => 'nullable|string|in:' . implode(',', InformeTc::PLANILLAS),
            $elementos . '.*.capas_ocultas' => 'nullable|string',
            $elementos . '.*.ids' => 'nullable|string',
            $elementos . '.*.pie' => 'nullable|string|max:500',
            $elementos . '.*.texto' => 'nullable|string',
            // Disposición de la planilla en el informe (los datos salen siempre
            // de Planillas): JSON { columnas: [claves], filas: [ids de punto] }.
            $elementos . '.*.tabla' => 'nullable|json',
            // JPG del plano con las capas prendidas o PNG de la planilla (data URL);
            // sólo lo manda "Generar".
            $elementos . '.*.imagen' => 'nullable|string',
            // Tabla "Referencias de ensayos" del plano (PNG en data URL) con
            // los ensayos de capas prendidas; sólo lo manda "Generar".
            $elementos . '.*.referencias' => 'nullable|string',
        ];
    }

    private function reglasBase(): array
    {
        return [
            'titulo' => 'nullable|string|max:255',
            'obra' => 'nullable|string|max:255',
            'mes' => 'nullable|string|max:20',
            'anio' => 'nullable|integer',
            'peticionario' => 'nullable|string|max:255',
            'referencia' => 'nullable|string|max:255',
            'presupuesto_dia' => 'nullable|integer|between:1,31',
            'presupuesto_mes' => 'nullable|string|max:20',
            'presupuesto_anio' => 'nullable|integer',
            'motivo_presupuesto' => 'nullable|string',
            'ubicacion_obra' => 'nullable|string',
            'objetivo_alcance' => 'nullable|string',
            'documentos' => 'nullable|array',
            'documentos.*.tipo' => 'nullable|string|max:255',
            'documentos.*.nombre' => 'nullable|string|max:255',
            'documentos.*.fecha' => 'nullable|date',
            'documentos.*.remitente' => 'nullable|string|max:255',
            'informacion_recibida' => 'nullable|string',
            'caracteristicas_obra' => 'nullable|string',
            'fotos_caracteristicas' => 'nullable|array',
            'fotos_caracteristicas.*.ids' => 'nullable|string',
            'fotos_caracteristicas.*.pie' => 'nullable|string|max:500',
            'equipo_coordinacion' => 'nullable|string|max:255',
            'equipo_campo_descripcion' => 'nullable|string|max:255',
            'equipo_campo_personas' => 'nullable|string',
            'equipo_gabinete_descripcion' => 'nullable|string|max:255',
            'equipo_gabinete_personas' => 'nullable|string',
            'trabajos_descripcion' => 'nullable|string',
            'trabajos_activos' => 'nullable|array',
            'trabajos_activos.*' => 'string|in:' . implode(',', array_keys(InformeTc::TRABAJOS)),
        ];
    }
}
