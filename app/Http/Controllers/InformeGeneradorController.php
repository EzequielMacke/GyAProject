<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformeRequest;
use App\Models\DirectorioTc;
use App\Models\EsclerometriaTc;
use App\Models\EtiquetaTc;
use App\Models\FotoTc;
use App\Models\InformeTc;
use App\Models\ObraTc;
use App\Models\Plano;
use App\Models\UltrasonidoIndirectoTc;
use App\Services\MiniaturaFotoService;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class InformeGeneradorController extends Controller
{
    /** Ancho útil de la hoja de la plantilla (A4 menos márgenes), en EMU. */
    private const ANCHO_UTIL_EMU = 6120000;

    /** Alto máximo de un plano en el Word (22 cm), en EMU. */
    private const ALTO_MAXIMO_PLANO_EMU = 7920000;

    /**
     * Píxeles del PNG de una planilla por cada píxel CSS (el navegador la
     * dibuja al doble para que se lea nítida); ver planillaConPie.
     */
    private const ESCALA_PLANILLA = 2;

    /** EMU por píxel CSS (96 por pulgada). */
    private const EMU_POR_PX = 9525;

    /** Separación entre fotos de un mismo bloque, en EMU. */
    private const SEPARACION_EMU = 90000;

    /** Lugar que ocupa la letra "(a)" al lado de cada foto, en EMU. */
    private const ANCHO_LETRA_EMU = 300000;

    /**
     * Fotos que se van insertando al armar el cuerpo; se agregan al .docx
     * (media + relación) recién cuando se abre la plantilla.
     *
     * @var array<int, array{rid: string, archivo: string, contenido: string}>
     */
    private array $imagenes = [];

    /**
     * Generador del informe de la obra: cada obra tiene un único informe.
     * Si ya existe se carga para editarlo; si no, el formulario arranca vacío.
     */
    public function index(ObraTc $obraTc)
    {
        return $this->formulario($obraTc, $this->informeDeLaObra($obraTc));
    }

    /**
     * Guarda el informe de la obra: lo crea la primera vez y después lo actualiza.
     */
    public function guardar(InformeRequest $request, ObraTc $obraTc)
    {
        if (! $this->verificarAcceso($obraTc)) {
            abort(403);
        }

        $informe = $this->informeDeLaObra($obraTc);

        if ($informe) {
            $informe->update($this->datosParaGuardar($request));
        } else {
            InformeTc::create(['obra_tc_id' => $obraTc->id] + $this->datosParaGuardar($request));
        }

        return redirect()->route('informe.generador', $obraTc->id)
            ->with('success', 'Informe guardado correctamente.');
    }

    /** El informe de la obra (si hubiera más de uno, el último modificado). */
    private function informeDeLaObra(ObraTc $obraTc): ?InformeTc
    {
        return InformeTc::where('obra_tc_id', $obraTc->id)->latest('updated_at')->first();
    }

    /**
     * Columnas del informe a partir del formulario validado. Las listas
     * (documentos, bloques de fotos) se guardan reindexadas, porque el
     * front numera los campos con índices que pueden tener huecos.
     */
    private function datosParaGuardar(InformeRequest $request): array
    {
        $datos = $request->validated();
        $datos['documentos'] = array_values($datos['documentos'] ?? []);
        $datos['fotos_caracteristicas'] = array_values($datos['fotos_caracteristicas'] ?? []);
        // La imagen del plano y su tabla de referencias se arman de nuevo al
        // generar: no se guardan.
        foreach (InformeTc::SECCIONES_CON_CONTENIDO as $seccion) {
            $datos[$seccion . '_elementos'] = array_map(
                fn (array $elemento) => array_diff_key($elemento, ['imagen' => true, 'referencias' => true]),
                array_values($datos[$seccion . '_elementos'] ?? [])
            );
        }
        foreach (InformeTc::SECCIONES_CON_FOTOS as $seccion) {
            $datos[$seccion . '_fotos'] = array_values($datos[$seccion . '_fotos'] ?? []);
        }

        return [
            'usuario_id' => session('usuario_id'),
            'titulo' => $datos['titulo'] ?? null,
            'referencia' => $datos['referencia'] ?? null,
            'datos' => $datos,
        ];
    }

    private function formulario(ObraTc $obraTc, ?InformeTc $informe)
    {
        if (! $this->verificarAcceso($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        if ($obraTc->estado == 2) {
            return redirect()->route('trabajo_campo.index')->with('error', 'Esta obra fue eliminada.');
        }

        // Fotos de la galería para el modal "Agregar imagen".
        $fotos = FotoTc::where('obra_tc_id', $obraTc->id)
            ->with(['plano', 'etiquetas'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (FotoTc $foto) => [
                'id' => $foto->id,
                'miniatura' => MiniaturaFotoService::existe($foto->archivo)
                    ? MiniaturaFotoService::url($foto->archivo)
                    : route('galeria_tc.miniatura', [$obraTc->id, $foto->id]),
                'plano_id' => $foto->plano_tc_id,
                'plano' => $foto->plano->descripcion ?? 'Sin plano',
                'fecha' => $foto->created_at?->format('d/m/Y'),
                'etiquetas' => $foto->etiquetas->pluck('id')->all(),
            ]);

        $planos = $fotos->filter(fn ($foto) => $foto['plano_id'])
            ->unique('plano_id')
            ->sortBy('plano')
            ->map(fn ($foto) => ['id' => $foto['plano_id'], 'descripcion' => $foto['plano']])
            ->values();

        $etiquetas = EtiquetaTc::where('obra_tc_id', $obraTc->id)
            ->orderBy('descripcion')
            ->get(['id', 'descripcion']);

        // Planos de la obra para el modal "Agregar plano" (los ya nombrados,
        // igual que en el índice de planos).
        $planosObra = Plano::where('obra_id', $obraTc->id)
            ->whereNotNull('descripcion')
            ->with(['grupo', 'subgrupo'])
            ->get()
            ->map(fn (Plano $plano) => [
                'id' => $plano->id,
                'descripcion' => $plano->descripcion,
                'carpeta' => implode(' / ', array_filter([$plano->grupo->descripcion ?? null, $plano->subgrupo->descripcion ?? null])) ?: 'Sin clasificar',
                'anotaciones' => $plano->tiene_anotaciones,
            ])
            ->sort(fn ($a, $b) => strnatcasecmp($a['carpeta'], $b['carpeta']) ?: strnatcasecmp($a['descripcion'], $b['descripcion']))
            ->values();

        // Con errores de validación se muestra lo que se acababa de enviar.
        $datos = session()->hasOldInput() ? session()->getOldInput() : ($informe->datos ?? []);

        return view('informe.generator', compact('obraTc', 'fotos', 'planos', 'etiquetas', 'planosObra', 'informe', 'datos'));
    }

    /**
     * Lo que necesita el generador para dibujar un plano con sus
     * anotaciones (el mismo PDF, rotación y estado que usa el visor).
     */
    public function plano(ObraTc $obraTc, Plano $plano)
    {
        if (! $this->verificarAcceso($obraTc) || (int) $plano->obra_id !== (int) $obraTc->id) {
            abort(404);
        }

        return response()->json([
            'url' => Storage::url('planos/' . $plano->archivo),
            'rotacion' => (int) ($plano->rotacion ?? 0),
            'estado' => $plano->estado ? json_decode($plano->estado, true) : null,
        ]);
    }

    /**
     * Datos de la planilla de esclerometría de la obra, para dibujarla como
     * tabla en el generador (los mismos del reporte PNG de Planillas).
     */
    public function planillaEsclerometria(ObraTc $obraTc)
    {
        if (! $this->verificarAcceso($obraTc)) {
            abort(404);
        }

        return response()->json(EsclerometriaTc::reporte($obraTc));
    }

    /** Datos de la planilla de ultrasonido indirecto de la obra (ídem esclerometría). */
    public function planillaUltrasonido(ObraTc $obraTc)
    {
        if (! $this->verificarAcceso($obraTc)) {
            abort(404);
        }

        return response()->json(UltrasonidoIndirectoTc::reporte($obraTc));
    }

    private function verificarAcceso(ObraTc $obraTc): bool
    {
        return DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', session('usuario_id'))
            ->exists();
    }

    /**
     * Arma el Word a partir de la plantilla: copia el .docx tal cual (formato
     * de página, encabezado, pie y estilos) y reemplaza solo el cuerpo del
     * document.xml con el contenido generado. Se arma con lo que hay en el
     * formulario en ese momento; no guarda nada en la base.
     */
    public function generar(InformeRequest $request, ObraTc $obraTc)
    {
        if (! $this->verificarAcceso($obraTc)) {
            abort(403);
        }

        $datos = $request->validated();

        $datos['fotos_caracteristicas'] = $this->bloquesDeFotos($obraTc, $datos['fotos_caracteristicas'] ?? []);
        foreach (InformeTc::SECCIONES_CON_CONTENIDO as $seccion) {
            $datos[$seccion . '_elementos'] = array_values($datos[$seccion . '_elementos'] ?? []);
        }

        // Con varias fotos achicarlas puede llevar más que el límite por defecto.
        set_time_limit(0);

        $cuerpo = $this->caratula($datos)
            . $this->indice()
            . $this->informacionGeneral($datos)
            . $this->objetivoAlcance($datos)
            . $this->datosPrevios($datos)
            . $this->equipoTecnico($datos)
            . $this->trabajosDesarrollados($datos)
            . $this->seccionConContenido($obraTc, $datos, 'relevamiento_geometrico', InformeTc::TRABAJOS['relevamiento_encofrado'], ['relevamiento_encofrado'])
            . $this->seccionConContenido($obraTc, $datos, 'ensayos_resistencia', InformeTc::GRUPOS_ENSAYOS['resistencia']['titulo'], InformeTc::GRUPOS_ENSAYOS['resistencia']['trabajos'])
            . $this->ensayoConPlanilla($obraTc, $datos, 'esclerometria')
            . $this->ensayoConPlanilla($obraTc, $datos, 'ultrasonido');

        $plantilla = storage_path('app/Plantilla/Ejemplo pantilla.docx');
        $temporal = tempnam(sys_get_temp_dir(), 'informe_') . '.docx';
        copy($plantilla, $temporal);

        $zip = new ZipArchive();
        $zip->open($temporal);
        $documento = $zip->getFromName('word/document.xml');

        // Todo lo que hay entre <w:body> y el <w:sectPr> final es contenido.
        $documento = preg_replace('/<w:body>.*?(<w:sectPr\b)/s', '<w:body>' . str_replace('$', '\$', $cuerpo) . '$1', $documento, 1);

        $zip->addFromString('word/document.xml', $documento);
        $this->actualizarCamposAlAbrir($zip);
        $this->indiceEnNegritaYMayusculas($zip);
        $this->agregarImagenes($zip);
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
     * Índice en una página sola, después de la carátula. Es un campo TOC con
     * los títulos de nivel 1 y 2; Word lo completa al abrir el documento
     * (ver actualizarCamposAlAbrir).
     */
    private function indice(): string
    {
        $campo = '<w:p><w:pPr><w:pStyle w:val="Normal"/></w:pPr>'
            . '<w:r><w:fldChar w:fldCharType="begin" w:dirty="true"/></w:r>'
            . '<w:r><w:instrText xml:space="preserve"> TOC \o "1-2" \h \z \u </w:instrText></w:r>'
            . '<w:r><w:fldChar w:fldCharType="separate"/></w:r>'
            . '<w:r><w:t>Actualizá el índice (clic derecho → Actualizar campos).</w:t></w:r>'
            . '<w:r><w:fldChar w:fldCharType="end"/></w:r>'
            . '</w:p>';

        return $this->parrafo('Contenido', 'Caratula')
            . $campo
            . $this->parrafo('', 'Normal', saltoPagina: true);
    }

    /**
     * Marca el documento para que Word actualice los campos (el índice) al
     * abrirlo. updateFields va antes de hdrShapeDefaults según el esquema.
     */
    private function actualizarCamposAlAbrir(ZipArchive $zip): void
    {
        $settings = $zip->getFromName('word/settings.xml');
        if ($settings === false || str_contains($settings, '<w:updateFields')) {
            return;
        }

        $marca = '<w:updateFields w:val="true"/>';
        $nuevo = preg_replace('/<w:(hdrShapeDefaults|footnotePr|endnotePr|compat|docVars|rsids)\b/', $marca . '$0', $settings, 1, $reemplazos);
        if (! $reemplazos) {
            $nuevo = str_replace('</w:settings>', $marca . '</w:settings>', $settings);
        }

        $zip->addFromString('word/settings.xml', $nuevo);
    }

    /**
     * Las entradas del índice las arma Word con los estilos TDC 1, 2 y 3, así
     * que la negrita y las mayúsculas se ponen en esos estilos (en la copia
     * generada, la plantilla no se toca).
     */
    private function indiceEnNegritaYMayusculas(ZipArchive $zip): void
    {
        $estilos = $zip->getFromName('word/styles.xml');
        if ($estilos === false) {
            return;
        }

        $formato = '<w:b/><w:bCs/><w:caps/>';
        $estilos = preg_replace_callback(
            '/(<w:style\b[^>]*w:styleId="TDC[123]"[^>]*>)(.*?)(<\/w:style>)/s',
            function ($m) use ($formato) {
                $contenido = $m[2];
                if (preg_match('/<w:rPr>(.*?)<\/w:rPr>/s', $contenido, $rPr)) {
                    $propias = preg_replace('/<w:(b|bCs|caps)\/>/', '', $rPr[1]);
                    $contenido = str_replace($rPr[0], '<w:rPr>' . $formato . $propias . '</w:rPr>', $contenido);
                } else {
                    $contenido .= '<w:rPr>' . $formato . '</w:rPr>';
                }

                return $m[1] . $contenido . $m[3];
            },
            $estilos
        );

        $zip->addFromString('word/styles.xml', $estilos);
    }

    /**
     * Información general: título en Titulo Uno y el texto en Normal. Arma la
     * misma frase que la vista previa de la interfaz.
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
            . $this->parrafo($texto, 'Normal');
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
     * Datos previos: título en Titulo Uno y cada subsección en Titulo Dos,
     * con su texto en Normal.
     */
    private function datosPrevios(array $datos): string
    {
        return $this->parrafo('Datos previos para la realización del estudio', 'TituloUno')
            . $this->parrafo('Documentación recibida', 'TituloDos')
            . $this->documentacionRecibida($datos['documentos'] ?? [])
            . $this->parrafo('Información recibida', 'TituloDos')
            . $this->parrafos($datos['informacion_recibida'] ?? '', 'Normal', true)
            . $this->parrafo('Características de la obra', 'TituloDos')
            . $this->parrafos($datos['caracteristicas_obra'] ?? '', 'Normal', true)
            . $this->fotos($datos['fotos_caracteristicas']);
    }

    /**
     * Equipo técnico: título en Titulo Uno; cada rubro con su nombre en
     * Normal negrita, la descripción en Normal y las personas (una por
     * renglón) en Lista con marcador.
     */
    private function equipoTecnico(array $datos): string
    {
        return $this->parrafo('Equipo Técnico', 'TituloUno', paginaNueva: true)
            . $this->parrafo('Para el desarrollo de los distintos trabajos necesarios para dar cumplimiento a los objetivos estipulados en el presente informe se contó con la participación de los siguientes profesionales en cada uno de los aspectos estudiados.', 'Normal')
            . $this->parrafo('Los trabajos desarrollados fueron los siguientes:', 'Normal')
            . $this->parrafo('Coordinación general de los trabajos', 'Normal', negrita: true)
            . $this->parrafos($datos['equipo_coordinacion'] ?? '', 'Listaconmarcador', true)
            . $this->parrafo('Trabajos de Campo', 'Normal', negrita: true)
            . $this->descripcionTrabajo($datos['equipo_campo_descripcion'] ?? '')
            . $this->parrafos($datos['equipo_campo_personas'] ?? '', 'Listaconmarcador', true)
            . $this->parrafo('Trabajos de Gabinete', 'Normal', negrita: true)
            . $this->descripcionTrabajo($datos['equipo_gabinete_descripcion'] ?? '')
            . $this->parrafos($datos['equipo_gabinete_personas'] ?? '', 'Listaconmarcador', true);
    }

    /**
     * Trabajos desarrollados, en página nueva: título en Titulo Uno, la
     * descripción en Normal y los trabajos activados (en el orden del menú)
     * en Lista con marcador.
     */
    private function trabajosDesarrollados(array $datos): string
    {
        $activos = $datos['trabajos_activos'] ?? [];

        $lista = '';
        foreach (InformeTc::TRABAJOS as $clave => $nombre) {
            if (in_array($clave, $activos, true)) {
                $lista .= $this->parrafo($this->conPuntoFinal($nombre), 'Listaconmarcador');
            }
        }

        return $this->parrafo('Trabajos desarrollados', 'TituloUno', paginaNueva: true)
            . $this->descripcionTrabajo($datos['trabajos_descripcion'] ?? '')
            . $lista;
    }

    /**
     * Sección con contenido (Relevamiento geométrico, grupo de ensayos), si
     * alguno de sus $trabajos está activado en Trabajos desarrollados: título
     * en Titulo Dos, la descripción en Normal y después los elementos
     * (planos, fotos y textos) en el orden en que se cargaron. En los grupos
     * de ensayos, cada plano lleva su tabla "Referencias de ensayos" (PNG que
     * arma el navegador al generar, con los ensayos cuya capa está prendida)
     * entre el plano y su pie.
     */
    private function seccionConContenido(ObraTc $obraTc, array $datos, string $seccion, string $titulo, array $trabajos): string
    {
        if (! array_intersect($trabajos, $datos['trabajos_activos'] ?? [])) {
            return '';
        }

        $xml = $this->parrafo($titulo, 'TituloDos')
            . $this->parrafos($datos[$seccion . '_descripcion'] ?? '', 'Normal', true);

        foreach ($datos[$seccion . '_elementos'] as $elemento) {
            $xml .= match ($elemento['tipo']) {
                'plano' => $this->planoConPie($elemento['imagen'] ?? '', $elemento['pie'] ?? '', $elemento['referencias'] ?? ''),
                'fotos' => $this->fotos($this->bloquesDeFotos($obraTc, [$elemento])),
                'texto' => $this->parrafos($elemento['texto'] ?? '', 'Normal', true),
                default => '',
            };
        }

        return $xml;
    }

    /**
     * Ensayo con planilla (esclerometría, ultrasonido), si está activado:
     * título en Titulo Tres en una página nueva (va dentro de los ensayos de resistencia), la
     * descripción en Normal, las fotografías como en Características de la
     * obra y después las planillas y textos en el orden en que se cargaron.
     * $trabajo es a la vez la clave del trabajo y el prefijo de sus campos.
     */
    private function ensayoConPlanilla(ObraTc $obraTc, array $datos, string $trabajo): string
    {
        if (! in_array($trabajo, $datos['trabajos_activos'] ?? [], true)) {
            return '';
        }

        // Cada ensayo empieza en una página nueva.
        $xml = $this->parrafo(InformeTc::TRABAJOS[$trabajo], 'TituloTres', paginaNueva: true)
            . $this->parrafos($datos[$trabajo . '_descripcion'] ?? '', 'Normal', true)
            . $this->fotos($this->bloquesDeFotos($obraTc, $datos[$trabajo . '_fotos'] ?? []));

        foreach ($datos[$trabajo . '_elementos'] as $elemento) {
            $xml .= match ($elemento['tipo']) {
                'planilla' => $this->planillaConPie($elemento['imagen'] ?? '', $elemento['pie'] ?? ''),
                'texto' => $this->parrafos($elemento['texto'] ?? '', 'Normal', true),
                default => '',
            };
        }

        return $xml;
    }

    /**
     * Una planilla: su pie en estilo Tabla arriba y la tabla (el PNG que
     * armó el navegador) en un párrafo Normal centrado. Va en su tamaño natural
     * (la letra queda como en el texto) y se achica sólo si no entra.
     */
    private function planillaConPie(string $imagen, string $pie): string
    {
        $tabla = $this->tablaPng($imagen);
        if ($tabla === '') {
            return '';
        }
        $pie = trim($pie);

        // El pie de tabla va arriba de la tabla.
        return $this->parrafo($pie !== '' ? $this->conPuntoFinal($pie) : '', 'Tabla') . $tabla;
    }

    /**
     * Tabla dibujada por el navegador (PNG en data URL, al doble: ver
     * ESCALA_PLANILLA) en un párrafo Normal centrado, en su tamaño natural
     * (la letra queda como en el texto) y achicada sólo si no entra. Con
     * $conSiguiente queda en la misma página que el párrafo que sigue.
     * Vacío si no llegó un PNG válido.
     */
    private function tablaPng(string $imagen, bool $conSiguiente = false): string
    {
        if (! preg_match('#^data:image/png;base64,(.+)$#s', $imagen, $m)) {
            return '';
        }
        $contenido = base64_decode($m[1], true);
        $tamano = $contenido !== false ? @getimagesizefromstring($contenido) : false;
        if (! $tamano || $tamano[2] !== IMAGETYPE_PNG) {
            return '';
        }

        $ancho = $tamano[0] / self::ESCALA_PLANILLA * self::EMU_POR_PX;
        $alto = $tamano[1] / self::ESCALA_PLANILLA * self::EMU_POR_PX;
        $escala = min(1, self::ANCHO_UTIL_EMU / $ancho, self::ALTO_MAXIMO_PLANO_EMU / $alto);

        return '<w:p><w:pPr><w:pStyle w:val="Normal"/>' . ($conSiguiente ? '<w:keepNext/>' : '') . '<w:jc w:val="center"/></w:pPr>'
            . $this->imagen($contenido, (int) ($ancho * $escala), (int) ($alto * $escala), 'png')
            . '</w:p>';
    }

    /**
     * Un plano (el JPG que armó el navegador con las capas prendidas) en un
     * párrafo Normal centrado, a todo el ancho útil, y su pie en estilo Figura.
     * $referencias: tabla "Referencias de ensayos" (PNG) que va entre el plano
     * y su pie; el plano y la tabla quedan en la misma página que el pie.
     */
    private function planoConPie(string $imagen, string $pie, string $referencias = ''): string
    {
        if (! preg_match('#^data:image/jpeg;base64,(.+)$#s', $imagen, $m)) {
            return '';
        }
        $contenido = base64_decode($m[1], true);
        $tamano = $contenido !== false ? @getimagesizefromstring($contenido) : false;
        if (! $tamano || $tamano[2] !== IMAGETYPE_JPEG) {
            return '';
        }

        [$ancho, $alto] = $tamano;
        $escala = min(self::ANCHO_UTIL_EMU / $ancho, self::ALTO_MAXIMO_PLANO_EMU / $alto);
        $pie = trim($pie);

        $tablaReferencias = $this->tablaPng($referencias, true);

        return '<w:p><w:pPr><w:pStyle w:val="Normal"/>' . ($tablaReferencias !== '' ? '<w:keepNext/>' : '') . '<w:jc w:val="center"/></w:pPr>'
            . $this->imagen($contenido, (int) ($ancho * $escala), (int) ($alto * $escala))
            . '</w:p>'
            . $tablaReferencias
            . $this->parrafo($pie !== '' ? $this->conPuntoFinal($pie) : '', 'Figura');
    }

    /**
     * Descripción de un rubro del equipo técnico: termina en dos puntos
     * porque a continuación va la lista de personas.
     */
    private function descripcionTrabajo(string $texto): string
    {
        $renglones = preg_split('/\R+/', trim($texto), -1, PREG_SPLIT_NO_EMPTY);
        if (! $renglones) {
            return '';
        }

        // Sólo el último renglón (el que precede a la lista) lleva los dos puntos.
        $ultimo = rtrim(trim(array_pop($renglones)), " \t.:;,");
        $renglones[] = $ultimo . ':';

        $xml = '';
        foreach ($renglones as $renglon) {
            $xml .= $this->parrafo(trim($renglon), 'Normal');
        }

        return $xml;
    }

    /**
     * Convierte lo que manda el form ("ids" separados por coma + pie) en
     * bloques con los modelos de las fotos, sólo de esta obra y en el
     * orden en que se eligieron. Los bloques sin fotos válidas se descartan.
     */
    private function bloquesDeFotos(ObraTc $obraTc, array $bloques): array
    {
        $resultado = [];
        foreach ($bloques as $bloque) {
            $ids = collect(explode(',', $bloque['ids'] ?? ''))
                ->map(fn ($id) => (int) trim($id))
                ->filter()
                ->unique()
                ->values();

            $fotos = FotoTc::where('obra_tc_id', $obraTc->id)
                ->whereIn('id', $ids)
                ->get()
                ->sortBy(fn (FotoTc $foto) => $ids->search($foto->id))
                ->values();

            if ($fotos->isNotEmpty()) {
                $resultado[] = ['fotos' => $fotos, 'pie' => trim($bloque['pie'] ?? '')];
            }
        }

        return $resultado;
    }

    /**
     * Cada bloque: las fotos en un párrafo Normal centrado (varias quedan
     * una al lado de la otra) y debajo un único pie en estilo Fotografía.
     */
    private function fotos(array $bloques): string
    {
        $xml = '';
        foreach ($bloques as $bloque) {
            $imagenes = $bloque['fotos']
                ->map(fn (FotoTc $foto) => MiniaturaFotoService::paraDocumento($foto->archivo))
                ->filter()
                ->values();

            if ($imagenes->isEmpty()) {
                continue;
            }

            // Hasta 3 por renglón (con 4 quedan 2 y 2); Word acomoda el resto solo.
            // De a varias, cada foto lleva su letra al lado: imagen(a) imagen(b)…,
            // así que se reserva lugar para ese texto.
            $cantidad = $imagenes->count();
            $varias = $cantidad > 1;
            $porRenglon = $cantidad === 4 ? 2 : min($cantidad, 3);
            $reservaLetra = $varias ? self::ANCHO_LETRA_EMU : 0;
            $anchoMaximo = (int) ((self::ANCHO_UTIL_EMU - self::SEPARACION_EMU * ($porRenglon - 1)) / $porRenglon) - $reservaLetra;
            $altoMaximo = $varias ? 2520000 : 3600000; // 7 cm de a varias, 10 cm sola

            $runs = '';
            $letras = [];
            foreach ($imagenes as $i => [$contenido, $ancho, $alto]) {
                $escala = min($anchoMaximo / $ancho, $altoMaximo / $alto);
                if ($i > 0) {
                    $runs .= '<w:r><w:t xml:space="preserve"> </w:t></w:r>';
                }
                $runs .= $this->imagen($contenido, (int) ($ancho * $escala), (int) ($alto * $escala));
                if ($varias) {
                    $letras[] = $this->letra($i);
                    $runs .= '<w:r><w:t>(' . end($letras) . ')</w:t></w:r>';
                }
            }

            // Pie: "(a, b y c) Texto del pie." cuando son varias.
            $pie = $bloque['pie'] !== '' ? $this->conPuntoFinal($bloque['pie']) : '';
            if ($varias) {
                $ultima = array_pop($letras);
                $pie = trim('(' . ($letras ? implode(', ', $letras) . ' y ' : '') . $ultima . ') ' . $pie);
            }

            $xml .= '<w:p><w:pPr><w:pStyle w:val="Normal"/><w:jc w:val="center"/></w:pPr>' . $runs . '</w:p>'
                . $this->parrafo($pie, 'Fotografa');
        }

        return $xml;
    }

    /** 0 → a, 1 → b, … 26 → aa. */
    private function letra(int $indice): string
    {
        $letra = '';
        do {
            $letra = chr(97 + $indice % 26) . $letra;
            $indice = intdiv($indice, 26) - 1;
        } while ($indice >= 0);

        return $letra;
    }

    /**
     * Run con la imagen en línea; el archivo se registra para agregarlo
     * al .docx después.
     */
    private function imagen(string $contenido, int $ancho, int $alto, string $extension = 'jpeg'): string
    {
        $numero = count($this->imagenes) + 1;
        $rid = 'rIdFotoInforme' . $numero;
        $archivo = 'foto_informe_' . $numero . '.' . $extension;
        $this->imagenes[] = ['rid' => $rid, 'archivo' => $archivo, 'contenido' => $contenido];

        // Ids altos para no chocar con las imágenes de encabezado y pie.
        $id = 90000 + $numero;

        return '<w:r><w:drawing><wp:inline distT="0" distB="0" distL="0" distR="0">'
            . '<wp:extent cx="' . $ancho . '" cy="' . $alto . '"/>'
            . '<wp:docPr id="' . $id . '" name="Foto ' . $numero . '"/>'
            . '<wp:cNvGraphicFramePr><a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" noChangeAspect="1"/></wp:cNvGraphicFramePr>'
            . '<a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">'
            . '<a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">'
            . '<pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">'
            . '<pic:nvPicPr><pic:cNvPr id="' . $id . '" name="' . $archivo . '"/><pic:cNvPicPr/></pic:nvPicPr>'
            . '<pic:blipFill><a:blip r:embed="' . $rid . '"/><a:stretch><a:fillRect/></a:stretch></pic:blipFill>'
            . '<pic:spPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="' . $ancho . '" cy="' . $alto . '"/></a:xfrm>'
            . '<a:prstGeom prst="rect"><a:avLst/></a:prstGeom></pic:spPr>'
            . '</pic:pic></a:graphicData></a:graphic></wp:inline></w:drawing></w:r>';
    }

    /**
     * Copia las fotos a word/media y agrega su relación en document.xml.rels.
     * Si la plantilla no declara las extensiones usadas (jpeg, png), se agregan.
     */
    private function agregarImagenes(ZipArchive $zip): void
    {
        if (! $this->imagenes) {
            return;
        }

        $relaciones = '';
        foreach ($this->imagenes as $imagen) {
            $zip->addFromString('word/media/' . $imagen['archivo'], $imagen['contenido']);
            $relaciones .= '<Relationship Id="' . $imagen['rid'] . '" '
                . 'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" '
                . 'Target="media/' . $imagen['archivo'] . '"/>';
        }

        $rels = $zip->getFromName('word/_rels/document.xml.rels');
        $zip->addFromString('word/_rels/document.xml.rels', str_replace('</Relationships>', $relaciones . '</Relationships>', $rels));

        $tipos = $zip->getFromName('[Content_Types].xml');
        $extensiones = array_unique(array_map(fn (array $imagen) => pathinfo($imagen['archivo'], PATHINFO_EXTENSION), $this->imagenes));
        foreach ($extensiones as $extension) {
            if (! preg_match('/Extension="' . $extension . '"/i', $tipos)) {
                $tipos = preg_replace('/(<Types[^>]*>)/', '$1<Default Extension="' . $extension . '" ContentType="image/' . $extension . '"/>', $tipos, 1);
            }
        }
        $zip->addFromString('[Content_Types].xml', $tipos);
    }

    /**
     * Un ítem de Lista con marcador por documento, con la forma
     * "Plano - Encofrado Planta Baja - Entregado por Juan Pérez en fecha 01/02/2025".
     * Los documentos sin ningún dato se ignoran.
     */
    private function documentacionRecibida(array $documentos): string
    {
        $xml = '';
        foreach ($documentos as $documento) {
            $tipo = trim($documento['tipo'] ?? '');
            $nombre = trim($documento['nombre'] ?? '');
            $remitente = trim($documento['remitente'] ?? '');
            $fecha = ! empty($documento['fecha']) ? date('d/m/Y', strtotime($documento['fecha'])) : '';

            $entrega = '';
            if ($remitente !== '' || $fecha !== '') {
                $entrega = 'Entregado'
                    . ($remitente !== '' ? ' por ' . $remitente : '')
                    . ($fecha !== '' ? ' en fecha ' . $fecha : '');
            }

            $texto = implode(' - ', array_filter([$tipo, $nombre, $entrega], fn ($parte) => $parte !== ''));
            if ($texto !== '') {
                $xml .= $this->parrafo($this->conPuntoFinal($texto), 'Listaconmarcador');
            }
        }

        return $xml;
    }

    /**
     * Un párrafo por cada renglón con texto (los renglones vacíos se ignoran).
     * Con $puntoFinal cada párrafo termina en punto.
     */
    private function parrafos(string $texto, string $estilo, bool $puntoFinal = false): string
    {
        $xml = '';
        foreach (preg_split('/\R+/', trim($texto), -1, PREG_SPLIT_NO_EMPTY) as $renglon) {
            $renglon = trim($renglon);
            $xml .= $this->parrafo($puntoFinal ? $this->conPuntoFinal($renglon) : $renglon, $estilo);
        }

        return $xml;
    }

    /**
     * Agrega el punto final si el texto no termina ya en punto (o en otro
     * signo de cierre como ? ! o …), para no duplicarlo.
     */
    private function conPuntoFinal(string $texto): string
    {
        $texto = rtrim($texto);

        return preg_match('/[.!?…]$/u', $texto) ? $texto : $texto . '.';
    }

    /**
     * $alineacion pisa la del estilo (left, center, right, both).
     * $saltoPagina agrega el salto al final del mismo renglón.
     */
    private function parrafo(string $texto, string $estilo, ?string $alineacion = null, bool $saltoPagina = false, bool $negrita = false, bool $paginaNueva = false): string
    {
        $propiedades = '<w:pStyle w:val="' . $estilo . '"/>';
        if ($paginaNueva) {
            $propiedades .= '<w:pageBreakBefore/>';
        }
        if ($alineacion) {
            $propiedades .= '<w:jc w:val="' . $alineacion . '"/>';
        }
        // Titulo Dos hereda el nivel de esquema 1 de Titulo Uno; se lo baja a 2
        // para que el índice lo muestre como subtítulo.
        if ($estilo === 'TituloDos') {
            $propiedades .= '<w:outlineLvl w:val="1"/>';
        }

        $runs = '';
        if ($texto !== '') {
            $runs .= '<w:r>' . ($negrita ? '<w:rPr><w:b/><w:bCs/></w:rPr>' : '') . '<w:t xml:space="preserve">' . htmlspecialchars($texto, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</w:t></w:r>';
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
