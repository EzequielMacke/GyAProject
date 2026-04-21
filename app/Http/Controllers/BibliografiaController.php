<?php

namespace App\Http\Controllers;

use App\Models\Bibliografia;
use App\Models\BibliografiaDetalle;
use App\Models\ElementoPlantilla;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use ZipArchive;
use DOMDocument;
use DOMXPath;

class BibliografiaController extends Controller
{
    public function index()
    {
        $bibliografias = Bibliografia::with('usuario')
            ->where('estado', 1)
            ->orderBy('id')
            ->get();

        return view('bibliografia.index', compact('bibliografias'));
    }

    public function create()
    {
        $elementos = ElementoPlantilla::where('estado', 1)->orderBy('id')->get();
        return view('bibliografia.create', compact('elementos'));
    }

    private const IDS_IMAGEN  = [6, 7, 8];
    private const ID_NOTA_PIE = 9;

    public function store(Request $request)
    {
        $request->validate([
            'nombre'                 => 'required|string|max:255',
            'fuente'                 => 'required|string|max:255',
            'detalles.*.elemento_id' => 'required|exists:elemento_plantillas,id',
            'detalles.*.tamanio'     => 'nullable|numeric|min:1|max:16',
        ]);

        $bib = Bibliografia::create([
            'nombre'     => $request->nombre,
            'fuente'     => $request->fuente,
            'estado'     => 1,
            'usuario_id' => session('usuario_id'),
        ]);

        $detallesInput = $request->input('detalles', []);
        uasort($detallesInput, fn($a, $b) => ($a['orden'] ?? 0) <=> ($b['orden'] ?? 0));

        $orden = 1;
        foreach ($detallesInput as $key => $detalle) {
            $elementoId = (int) $detalle['elemento_id'];
            $esImagen   = in_array($elementoId, self::IDS_IMAGEN);

            if ($esImagen) {
                $archivo = $request->file("detalles.{$key}.descripcion");
                if ($archivo) {
                    if (empty($detalle['tamanio'])) {
                        return back()->withInput()->withErrors(['tamanio' => 'El ancho es requerido para imágenes.']);
                    }
                    $nombre = now()->format('YmdHis') . '_' . $archivo->getClientOriginalName();
                    $archivo->storeAs('bibliografia', $nombre, 'public');
                    $descripcion = $nombre;
                } else {
                    $descripcion = null;
                }
            } else {
                $descripcion = $detalle['descripcion'] ?? null;
            }

            BibliografiaDetalle::create([
                'bibliografia_id'       => $bib->id,
                'elemento_plantilla_id' => $elementoId,
                'descripcion'           => $descripcion,
                'tamanio'               => $detalle['tamanio'] ?? null,
                'estado'                => 1,
                'orden'                 => $orden++,
            ]);

            // Si el switch de nota al pie estaba activado, guardar inmediatamente después
            if (!$esImagen && array_key_exists('nota_pie', $detalle)) {
                BibliografiaDetalle::create([
                    'bibliografia_id'       => $bib->id,
                    'elemento_plantilla_id' => self::ID_NOTA_PIE,
                    'descripcion'           => $detalle['nota_pie'] ?? '',
                    'tamanio'               => null,
                    'estado'                => 1,
                    'orden'                 => $orden++,
                ]);
            }
        }

        return redirect()->route('bibliografia.index')->with('success', 'Bibliografía creada correctamente.');
    }


    public function generate()
    {
        $bibliografias = Bibliografia::with(['detalles' => fn($q) => $q->where('estado', 1)->orderBy('orden')])
            ->where('estado', 1)
            ->orderBy('id')
            ->get();

        return view('bibliografia.generate', compact('bibliografias'));
    }

    public function show($id)
    {
        $bib = Bibliografia::with(['detalles' => fn($q) => $q->where('estado', 1)->orderBy('orden'), 'usuario'])->findOrFail($id);
        return view('bibliografia.show', compact('bib'));
    }

    public function edit($id)
    {
        $bib      = Bibliografia::with(['detalles' => fn($q) => $q->where('estado', 1)->orderBy('orden')])->findOrFail($id);
        $elementos = ElementoPlantilla::where('estado', 1)->orderBy('id')->get();
        return view('bibliografia.edit', compact('bib', 'elementos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'                 => 'required|string|max:255',
            'fuente'                 => 'required|string|max:255',
            'detalles.*.elemento_id' => 'required|exists:elemento_plantillas,id',
            'detalles.*.tamanio'     => 'nullable|numeric|min:1|max:16',
        ]);

        $bib = Bibliografia::findOrFail($id);
        $bib->update(['nombre' => $request->nombre, 'fuente' => $request->fuente]);

        $detallesInput = $request->input('detalles', []);
        uasort($detallesInput, fn($a, $b) => ($a['orden'] ?? 0) <=> ($b['orden'] ?? 0));

        $orden = 1;
        foreach ($detallesInput as $key => $detalle) {
            $existingId = $detalle['id'] ?? null;
            $estado     = (int) ($detalle['estado'] ?? 1);
            $elementoId = (int) ($detalle['elemento_id'] ?? 0);
            $esImagen   = in_array($elementoId, self::IDS_IMAGEN);
            $notaPieId  = !empty($detalle['nota_pie_id']) ? (int) $detalle['nota_pie_id'] : null;
            $hasNotaPie = !$esImagen && array_key_exists('nota_pie', $detalle);

            if ($existingId) {
                $existing = BibliografiaDetalle::find($existingId);
                if (! $existing) continue;

                if ($estado === 2) {
                    $existing->update(['estado' => 2]);
                    // Eliminar también la nota al pie asociada si existía
                    if ($notaPieId) {
                        BibliografiaDetalle::find($notaPieId)?->update(['estado' => 2]);
                    }
                    continue;
                }

                if ($esImagen) {
                    $archivo = $request->file("detalles.{$key}.descripcion");
                    if ($archivo) {
                        if (empty($detalle['tamanio'])) {
                            return back()->withInput()->withErrors(['tamanio' => 'El ancho es requerido para imágenes.']);
                        }
                        $nombre = now()->format('YmdHis') . '_' . $archivo->getClientOriginalName();
                        $archivo->storeAs('bibliografia', $nombre, 'public');
                        $descripcion = $nombre;
                    } else {
                        $descripcion = $detalle['descripcion_actual'] ?? $existing->descripcion;
                    }
                } else {
                    $descripcion = $detalle['descripcion'] ?? null;
                }

                $existing->update([
                    'elemento_plantilla_id' => $elementoId,
                    'descripcion'           => $descripcion,
                    'tamanio'               => $detalle['tamanio'] ?? null,
                    'orden'                 => $orden++,
                ]);

                // Gestionar nota al pie del ítem existente
                if ($hasNotaPie) {
                    if ($notaPieId) {
                        // Actualizar la nota al pie existente
                        BibliografiaDetalle::find($notaPieId)?->update([
                            'descripcion' => $detalle['nota_pie'] ?? '',
                            'orden'       => $orden++,
                            'estado'      => 1,
                        ]);
                    } else {
                        // Crear nueva nota al pie para un ítem que antes no la tenía
                        BibliografiaDetalle::create([
                            'bibliografia_id'       => $bib->id,
                            'elemento_plantilla_id' => self::ID_NOTA_PIE,
                            'descripcion'           => $detalle['nota_pie'] ?? '',
                            'tamanio'               => null,
                            'estado'                => 1,
                            'orden'                 => $orden++,
                        ]);
                    }
                } else {
                    // Switch desactivado: eliminar nota al pie si existía
                    if ($notaPieId) {
                        BibliografiaDetalle::find($notaPieId)?->update(['estado' => 2]);
                    }
                }
            } else {
                // Ítem nuevo agregado en la edición
                if ($esImagen) {
                    $archivo = $request->file("detalles.{$key}.descripcion");
                    if ($archivo) {
                        if (empty($detalle['tamanio'])) {
                            return back()->withInput()->withErrors(['tamanio' => 'El ancho es requerido para imágenes.']);
                        }
                        $nombre = now()->format('YmdHis') . '_' . $archivo->getClientOriginalName();
                        $archivo->storeAs('bibliografia', $nombre, 'public');
                        $descripcion = $nombre;
                    } else {
                        $descripcion = null;
                    }
                } else {
                    $descripcion = $detalle['descripcion'] ?? null;
                }

                BibliografiaDetalle::create([
                    'bibliografia_id'       => $bib->id,
                    'elemento_plantilla_id' => $elementoId,
                    'descripcion'           => $descripcion,
                    'tamanio'               => $detalle['tamanio'] ?? null,
                    'estado'                => 1,
                    'orden'                 => $orden++,
                ]);

                if ($hasNotaPie) {
                    BibliografiaDetalle::create([
                        'bibliografia_id'       => $bib->id,
                        'elemento_plantilla_id' => self::ID_NOTA_PIE,
                        'descripcion'           => $detalle['nota_pie'] ?? '',
                        'tamanio'               => null,
                        'estado'                => 1,
                        'orden'                 => $orden++,
                    ]);
                }
            }
        }

        return redirect()->route('bibliografia.index')->with('success', 'Bibliografía actualizada correctamente.');
    }

    public function destroy($id)
    {
        $bib = Bibliografia::findOrFail($id);
        $bib->update(['estado' => 2]);

        return redirect()->route('bibliografia.index')->with('success', 'Bibliografía eliminada correctamente.');
    }

    public function generateWord(Request $request)
    {
        $ids = $request->input('bibliografias', []);

        if (empty($ids)) {
            return redirect()->route('bibliografia.generate');
        }

        $bibsIndexed = Bibliografia::with([
            'detalles'                    => fn($q) => $q->where('estado', 1)->orderBy('orden'),
            'detalles.elementoPlantilla',
        ])->whereIn('id', $ids)->get()->keyBy('id');

        // 1. Extraer sectPr original (encabezado, pie, márgenes)
        $plantillaPath = storage_path('app/Plantilla/Ejemplo pantilla.docx');
        $zipPlantilla  = new ZipArchive();
        $zipPlantilla->open($plantillaPath);
        $xmlOriginal = $zipPlantilla->getFromName('word/document.xml');
        $zipPlantilla->close();

        $domOriginal = new DOMDocument();
        $domOriginal->loadXML($xmlOriginal);
        $xpathOrig = new DOMXPath($domOriginal);
        $xpathOrig->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $sectPrNodes = $xpathOrig->query('//w:body/w:sectPr');
        $sectPrXml   = $sectPrNodes->length > 0 ? $domOriginal->saveXML($sectPrNodes->item(0)) : '';

        // 2. Generar contenido con PHPWord usando los estilos de cada elemento
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        foreach ($ids as $i => $id) {
            $bib = $bibsIndexed[$id] ?? null;
            if (! $bib) continue;

            $detallesArr = $bib->detalles->values();
            $totalDet    = $detallesArr->count();

            for ($di = 0; $di < $totalDet; $di++) {
                $detalle  = $detallesArr[$di];
                $estilo   = $detalle->elementoPlantilla->estilo ?? 'Normal';
                $esImagen = in_array($detalle->elemento_plantilla_id, self::IDS_IMAGEN);

                // Las notas al pie se procesan junto con su párrafo padre; saltar aquí
                if ($detalle->elemento_plantilla_id === self::ID_NOTA_PIE) continue;

                // ¿El siguiente detalle es una nota al pie asociada a éste?
                $notaPieDet = null;
                if ($di + 1 < $totalDet && $detallesArr[$di + 1]->elemento_plantilla_id === self::ID_NOTA_PIE) {
                    $notaPieDet = $detallesArr[$di + 1];
                }

                if ($esImagen && $detalle->descripcion) {
                    $imagePath = storage_path('app/public/bibliografia/' . $detalle->descripcion);
                    if (file_exists($imagePath)) {
                        $widthPt = (int) round(($detalle->tamanio ?? 16) * 72 / 2.54);

                        $rawName = preg_replace('/^\d{14}_/', '', $detalle->descripcion);
                        $caption = pathinfo($rawName, PATHINFO_FILENAME);
                        $esTabla = $detalle->elemento_plantilla_id === 8;
                        
                        if ($esTabla) {
                            $section->addText($caption, null, $estilo);
                        }

                        $imgRun = $section->addTextRun(['alignment' => 'center']);
                        $imgRun->addImage($imagePath, ['width' => $widthPt]);

                        if (! $esTabla) {
                            $section->addText($caption, null, $estilo);
                        }
                    }
                } else {
                    $lineas     = preg_split('/\r\n|\r|\n/', $detalle->descripcion ?? '');
                    $ultimaLinea = count($lineas) - 1;

                    foreach ($lineas as $li => $linea) {
                        // La nota al pie se adjunta al final de la última línea del párrafo
                        if ($notaPieDet !== null && $li === $ultimaLinea) {
                            $npTexto   = ($notaPieDet->descripcion ?? '') === ''
                                ? $bib->fuente
                                : $notaPieDet->descripcion;
                            $npEstilo  = $notaPieDet->elementoPlantilla->estilo ?? null;

                            $textRun = $section->addTextRun($estilo);
                            $textRun->addText($linea);
                            $footnote = $textRun->addFootnote();
                            $footnote->addText($npTexto, null, $npEstilo);
                        } else {
                            $section->addText($linea, null, $estilo);
                        }
                    }
                }
            }

            // Salto de página entre bibliografías (excepto la última)
            if ($i < count($ids) - 1) {
                $section->addPageBreak();
            }
        }

        // 3. Guardar temporalmente y extraer archivos del ZIP generado
        $tempGenerado = tempnam(sys_get_temp_dir(), 'phpword_') . '.docx';
        IOFactory::createWriter($phpWord)->save($tempGenerado);

        $zipGenerado  = new ZipArchive();
        $zipGenerado->open($tempGenerado);
        $xmlNuevo     = $zipGenerado->getFromName('word/document.xml');
        $relsGenerado = $zipGenerado->getFromName('word/_rels/document.xml.rels');
        // Extraer footnotes.xml si PHPWord lo generó (solo existe cuando hay notas al pie)
        $footnotesXml = $zipGenerado->getFromName('word/footnotes.xml') ?: null;

        $mediaFiles = [];
        for ($j = 0; $j < $zipGenerado->numFiles; $j++) {
            $name = $zipGenerado->getNameIndex($j);
            if (str_starts_with($name, 'word/media/')) {
                $mediaFiles[$name] = $zipGenerado->getFromIndex($j);
            }
        }
        $zipGenerado->close();
        unlink($tempGenerado);

        // 4. Eliminar el sectPr generado por PHPWord del document.xml
        $domNuevo = new DOMDocument();
        $domNuevo->loadXML($xmlNuevo);
        $xpathNuevo = new DOMXPath($domNuevo);
        $xpathNuevo->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        foreach ($xpathNuevo->query('//w:body/w:sectPr') as $node) {
            $node->parentNode->removeChild($node);
        }
        // Guardar el XML sin sectPr — el sectPr se inyecta DESPUÉS del reemplazo de rIds
        $xmlSinSectPr = $domNuevo->saveXML();

        // 5. Fusionar .rels: renumerar rIds de imágenes para no colisionar con
        //    los rIds de la plantilla (header, footer, styles, etc.)
        $nsRel = 'http://schemas.openxmlformats.org/package/2006/relationships';

        $zipTemplate = new ZipArchive();
        $zipTemplate->open($plantillaPath);
        $relsPlantilla = $zipTemplate->getFromName('word/_rels/document.xml.rels');
        $zipTemplate->close();

        $domRelsP = new DOMDocument();
        $domRelsP->loadXML($relsPlantilla);

        // Encontrar el número más alto de rId en la plantilla
        $maxId = 0;
        foreach ($domRelsP->getElementsByTagNameNS($nsRel, 'Relationship') as $rel) {
            if (preg_match('/rId(\d+)/i', $rel->getAttribute('Id'), $m)) {
                $maxId = max($maxId, (int) $m[1]);
            }
        }

        // Construir mapping oldRId → newRId e importar nodos de imagen.
        // También renombrar los archivos de media para evitar colisiones con
        // archivos que ya existen en la plantilla (ej: image1.jpeg del encabezado).
        $mapping      = [];
        $mediaNameMap = []; // zip path viejo → zip path nuevo
        $domRelsG     = new DOMDocument();
        $domRelsG->loadXML($relsGenerado);
        $rootRels = $domRelsP->documentElement;

        $footnotesRelType = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/footnotes';

        foreach ($domRelsG->getElementsByTagNameNS($nsRel, 'Relationship') as $rel) {
            $type = $rel->getAttribute('Type');

            if (str_contains($type, '/image')) {
                $oldId     = $rel->getAttribute('Id');
                $oldTarget = $rel->getAttribute('Target'); // e.g. "media/image1.jpeg"
                $ext       = strtolower(pathinfo($oldTarget, PATHINFO_EXTENSION));
                $newId     = 'rId' . (++$maxId);
                $newTarget = 'media/bibgen_' . $maxId . '.' . $ext;

                $mapping[$oldId]                    = $newId;
                $mediaNameMap['word/' . $oldTarget] = 'word/' . $newTarget;

                $newRel = $domRelsP->importNode($rel, true);
                $newRel->setAttribute('Id', $newId);
                $newRel->setAttribute('Target', $newTarget);
                $rootRels->appendChild($newRel);

            } elseif ($type === $footnotesRelType && $footnotesXml !== null) {
                // Copiar la relación de footnotes si la plantilla no la tiene ya
                $templateHasIt = false;
                foreach ($domRelsP->getElementsByTagNameNS($nsRel, 'Relationship') as $pr) {
                    if ($pr->getAttribute('Type') === $footnotesRelType) { $templateHasIt = true; break; }
                }
                if (! $templateHasIt) {
                    $rootRels->appendChild($domRelsP->importNode($rel, true));
                }
            }
        }

        // Reemplazar rIds en el body usando placeholders para evitar el bug de
        // doble-reemplazo (ej: rId1→rId3 y luego rId3→rId5 pisaría rId1 dos veces).
        $xmlBody = $xmlSinSectPr;
        $placeholders = [];
        foreach ($mapping as $oldId => $newId) {
            $ph = '___BIBIMG_' . $oldId . '___';
            $placeholders[$ph] = $newId;
            $xmlBody = str_replace(
                ['r:embed="' . $oldId . '"', 'r:id="' . $oldId . '"'],
                ['r:embed="' . $ph . '"',    'r:id="' . $ph . '"'],
                $xmlBody
            );
        }
        foreach ($placeholders as $ph => $newId) {
            $xmlBody = str_replace(
                ['r:embed="' . $ph . '"', 'r:id="' . $ph . '"'],
                ['r:embed="' . $newId . '"', 'r:id="' . $newId . '"'],
                $xmlBody
            );
        }

        // Inyectar el sectPr original DESPUÉS de los reemplazos → sus rIds quedan intactos
        $xmlFinal = str_replace('</w:body>', $sectPrXml . '</w:body>', $xmlBody);

        // 6. Construir el ZIP final a partir de la plantilla
        $tempFinal = tempnam(sys_get_temp_dir(), 'final_') . '.docx';
        copy($plantillaPath, $tempFinal);

        $zipFinal = new ZipArchive();
        $zipFinal->open($tempFinal);
        $zipFinal->addFromString('word/document.xml',            $xmlFinal);
        $zipFinal->addFromString('word/_rels/document.xml.rels', $domRelsP->saveXML());
        foreach ($mediaFiles as $oldZipPath => $content) {
            $newZipPath = $mediaNameMap[$oldZipPath] ?? $oldZipPath;
            $zipFinal->addFromString($newZipPath, $content);
        }

        // Copiar footnotes.xml al ZIP final
        if ($footnotesXml !== null) {
            $zipFinal->addFromString('word/footnotes.xml', $footnotesXml);
        }

        // Asegurar que el estilo FootnoteReference (superíndice) esté en styles.xml
        $stylesXml = $zipFinal->getFromName('word/styles.xml');
        if ($stylesXml && ! str_contains($stylesXml, 'FootnoteReference')) {
            $fnStyle = '<w:style w:type="character" w:styleId="FootnoteReference">'
                . '<w:name w:val="footnote reference"/>'
                . '<w:basedOn w:val="DefaultParagraphFont"/>'
                . '<w:uiPriority w:val="99"/><w:semiHidden/>'
                . '<w:rPr><w:vertAlign w:val="superscript"/></w:rPr>'
                . '</w:style>';
            $stylesXml = str_replace('</w:styles>', $fnStyle . '</w:styles>', $stylesXml);
            $zipFinal->addFromString('word/styles.xml', $stylesXml);
        }

        // Registrar en [Content_Types].xml las extensiones de imagen que agregamos.
        // Si la plantilla no las tenía (no usa imágenes de ese tipo), Word reporta
        // el documento como dañado y lo "repara" añadiendo los tipos faltantes.
        $mimeMap = [
            'jpeg' => 'image/jpeg',
            'jpg'  => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'bmp'  => 'image/bmp',
            'tiff' => 'image/tiff',
            'wmf'  => 'image/x-wmf',
            'emf'  => 'image/x-emf',
        ];
        $ctXml      = $zipFinal->getFromName('[Content_Types].xml');
        $registered = [];
        foreach ($mediaNameMap as $newZipPath) {
            $ext = strtolower(pathinfo($newZipPath, PATHINFO_EXTENSION));
            if (! isset($mimeMap[$ext]) || in_array($ext, $registered)) continue;
            if (str_contains($ctXml, 'Extension="' . $ext . '"')) continue;
            $ctXml = str_replace(
                '</Types>',
                '<Default Extension="' . $ext . '" ContentType="' . $mimeMap[$ext] . '"/></Types>',
                $ctXml
            );
            $registered[] = $ext;
        }

        // Registrar footnotes.xml en Content_Types si no está
        if ($footnotesXml !== null && ! str_contains($ctXml, 'footnotes.xml')) {
            $ctXml = str_replace(
                '</Types>',
                '<Override PartName="/word/footnotes.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.footnotes+xml"/></Types>',
                $ctXml
            );
        }
        $zipFinal->addFromString('[Content_Types].xml', $ctXml);

        $zipFinal->close();

        return response()->download($tempFinal, 'bibliografia.docx')->deleteFileAfterSend(true);
    }
}
