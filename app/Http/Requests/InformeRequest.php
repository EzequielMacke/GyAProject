<?php

namespace App\Http\Requests;

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
        ];
    }
}
