<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;

class InformeGeneradorController extends Controller
{
    /**
     * Arma el Word a partir de la plantilla: copia el .docx tal cual (formato
     * de página, encabezado, pie y estilos) y reemplaza solo el cuerpo del
     * document.xml con el contenido generado. No guarda nada en la base.
     */
    public function generar(Request $request)
    {
        $datos = $request->validate([
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
            'info_adicional' => 'nullable|string',
            'objetivo_alcance' => 'nullable|string',
        ]);

        $cuerpo = $this->caratula($datos)
            . $this->informacionGeneral($datos)
            . $this->objetivoAlcance($datos);

        $plantilla = storage_path('app/Plantilla/Ejemplo pantilla.docx');
        $temporal = tempnam(sys_get_temp_dir(), 'informe_') . '.docx';
        copy($plantilla, $temporal);

        $zip = new ZipArchive();
        $zip->open($temporal);
        $documento = $zip->getFromName('word/document.xml');

        // Todo lo que hay entre <w:body> y el <w:sectPr> final es contenido.
        $documento = preg_replace('/<w:body>.*?(<w:sectPr\b)/s', '<w:body>' . str_replace('$', '\$', $cuerpo) . '$1', $documento, 1);

        $zip->addFromString('word/document.xml', $documento);
        $zip->close();

        return response()->download($temporal, 'Informe preliminar.docx')->deleteFileAfterSend(true);
    }

    /**
     * Carátula, toda en estilo Caratula (incluidos los renglones vacíos).
     * Peticionario y Ref. van alineados a la izquierda, y el salto de página
     * va en el mismo renglón que Ref.
     */
    private function caratula(array $datos): string
    {
        $estilo = 'Caratula';
        $mesAnio = implode(' - ', array_filter([$datos['mes'] ?? '', $datos['anio'] ?? '']));

        return $this->vacios(8, $estilo)
            . $this->parrafo($datos['titulo'] ?? '', $estilo)
            . $this->parrafo('Obra: ' . ($datos['obra'] ?? ''), $estilo)
            . $this->parrafo($mesAnio, $estilo)
            . $this->vacios(9, $estilo)
            . $this->parrafo('Peticionario: ' . ($datos['peticionario'] ?? ''), $estilo, 'left')
            . $this->parrafo('Ref.: ' . ($datos['referencia'] ?? ''), $estilo, 'left', true);
    }

    /**
     * Información general: título en Titulo Uno y el texto en Normal. Arma la
     * misma frase que la vista previa de la interfaz; la información
     * adicional va en párrafos aparte (uno por cada renglón cargado).
     */
    private function informacionGeneral(array $datos): string
    {
        $fecha = '';
        if (! empty($datos['presupuesto_dia']) && ! empty($datos['presupuesto_mes']) && ! empty($datos['presupuesto_anio'])) {
            $fecha = $datos['presupuesto_dia'] . ' de ' . mb_strtolower($datos['presupuesto_mes']) . ' de ' . $datos['presupuesto_anio'];
        }
        $motivo = trim($datos['motivo_presupuesto'] ?? '');
        $lugar = rtrim(trim($datos['ubicacion_obra'] ?? ''), '.');

        $texto = 'En fecha ' . $fecha
            . ' hemos presentado una propuesta técnica para ' . $motivo
            . ' ubicado sobre ' . $lugar . '.';

        return $this->parrafo('Información general', 'TituloUno')
            . $this->parrafo($texto, 'Normal')
            . $this->parrafos($datos['info_adicional'] ?? '', 'Normal');
    }

    /**
     * Objetivo y alcance: título en Titulo Uno y el texto en Normal.
     */
    private function objetivoAlcance(array $datos): string
    {
        return $this->parrafo('Objetivo y Alcance', 'TituloUno')
            . $this->parrafos($datos['objetivo_alcance'] ?? '', 'Normal');
    }

    /**
     * Un párrafo por cada renglón con texto (los renglones vacíos se ignoran).
     */
    private function parrafos(string $texto, string $estilo): string
    {
        $xml = '';
        foreach (preg_split('/\R+/', trim($texto), -1, PREG_SPLIT_NO_EMPTY) as $renglon) {
            $xml .= $this->parrafo(trim($renglon), $estilo);
        }

        return $xml;
    }

    /**
     * $alineacion pisa la del estilo (left, center, right, both).
     * $saltoPagina agrega el salto al final del mismo renglón.
     */
    private function parrafo(string $texto, string $estilo, ?string $alineacion = null, bool $saltoPagina = false): string
    {
        $propiedades = '<w:pStyle w:val="' . $estilo . '"/>';
        if ($alineacion) {
            $propiedades .= '<w:jc w:val="' . $alineacion . '"/>';
        }

        $runs = '';
        if ($texto !== '') {
            $runs .= '<w:r><w:t xml:space="preserve">' . htmlspecialchars($texto, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</w:t></w:r>';
        }
        if ($saltoPagina) {
            $runs .= '<w:r><w:br w:type="page"/></w:r>';
        }

        return '<w:p><w:pPr>' . $propiedades . '</w:pPr>' . $runs . '</w:p>';
    }

    private function vacios(int $cantidad, string $estilo): string
    {
        return str_repeat($this->parrafo('', $estilo), $cantidad);
    }
}
