<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\DocumentoDetalle;
use App\Models\DocumentoTrabajoDetalle;
use App\Models\Encargado;
use App\Models\Tipo_documento;
use App\Models\Tipo_ensayo;
use App\Models\Tipo_trabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;


class DocumentosController extends Controller
{
    public function index()
    {
        // Traer documentos con relaciones para la vista
        $documentos = Documento::with([
            'tipoDocumento',
            'tipoTrabajo',
            'usuario',
            'trabajosDetalles.ensayo',
            'trabajosDetalles.encargado'
        ])->get();

        return view('documentos.index', compact('documentos'));
    }

    public function create()
    {
        $tiposTrabajo = Tipo_trabajo::where('estado', 1)->get();
        $tiposDocumento = Tipo_documento::where('estado', 1)->get();
        $ensayos = Tipo_ensayo::where('estado', 1)->get();
        $encargados = Encargado::where('estado', 1)->get();
        return view('documentos.create', compact('encargados','tiposTrabajo', 'tiposDocumento', 'ensayos'));
    }

    public function ensayosPorTipo($tipoTrabajoId)
    {
        $ensayos = Tipo_ensayo::where('tipo_trabajo_id', $tipoTrabajoId)
            ->where('estado', 1)
            ->get(['id', 'nombre']);
        return response()->json($ensayos);
    }


    public function store(Request $request)
    {
        // Guardar en documentos
        $documento = Documento::create([
            'nombre' => $request->nombre,
            'tipo_documento_id' => $request->tipo_documento,
            'tipo_trabajo_id' => $request->tipo_trabajo,
            'obra' => $request->nombre_obra,
            'mes' => $request->mes,
            'año' => $request->año,
            'peticionario' => $request->peticionario,
            'referencia' => $request->referencia ?? null,
            'fecha_presupuesto' => $request->fecha_presupuesto ?? null,
            'ubicacion' => $request->ubicacion ?? null,
            'objeto_alcance' => $request->objetivo_alcance ?? null,
            'usuario_id' => $request->usuario_id,

        ]);

        if ($request->has('ensayos')) {
            foreach ($request->ensayos as $tipo_ensayo_id) {
                $encargado_id = $request->input("encargados_trabajo.$tipo_ensayo_id");
                DocumentoTrabajoDetalle::create([
                    'documento_id' => $documento->id,
                    'tipo_ensayo_id' => $tipo_ensayo_id,
                    'encargado_id' => $encargado_id,
                ]);
            }
        }
        return redirect()->route('documentos.index')->with('success', 'Documento guardado correctamente.');
    }

    public function edit($id)
    {
        $documento = Documento::with('trabajosDetalles')->findOrFail($id);
        $tiposTrabajo = Tipo_trabajo::where('estado', 1)->get();
        $tiposDocumento = Tipo_documento::where('estado', 1)->get();
        $ensayos = Tipo_ensayo::where('estado', 1)->get();
        $encargados = Encargado::where('estado', 1)->get();
        return view('documentos.edit', compact('documento', 'tiposTrabajo', 'tiposDocumento', 'ensayos', 'encargados'));
    }


    public function update(Request $request, $id)
    {
        $documento = Documento::findOrFail($id);

        $documento->update([
            'nombre' => $request->nombre,
            'tipo_documento_id' => $request->tipo_documento,
            'tipo_trabajo_id' => $request->tipo_trabajo,
            'obra' => $request->nombre_obra,
            'mes' => $request->mes,
            'año' => $request->año,
            'peticionario' => $request->peticionario,
            'referencia' => $request->referencia ?? null,
            'fecha_presupuesto' => $request->fecha_presupuesto ?? null,
            'ubicacion' => $request->ubicacion ?? null,
            'objeto_alcance' => $request->objetivo_alcance ?? null,
            'usuario_id' => $request->usuario_id,
        ]);

        // Actualizar detalles de trabajos (puedes borrar y volver a crear o actualizar según tu lógica)
        $documento->trabajosDetalles()->delete();
        if ($request->has('ensayos')) {
            foreach ($request->ensayos as $tipo_ensayo_id) {
                $encargado_id = $request->input("encargados_trabajo.$tipo_ensayo_id");
                DocumentoTrabajoDetalle::create([
                    'documento_id' => $documento->id,
                    'tipo_ensayo_id' => $tipo_ensayo_id,
                    'encargado_id' => $encargado_id,
                ]);
            }
        }

        return redirect()->route('documentos.index')->with('success', 'Documento actualizado correctamente.');
    }

    public function detalles($id)
    {
        $documento = Documento::findOrFail($id);
        $ensayosRealizados = $documento->trabajosDetalles()->with('ensayo')->get();
        return view('documentos.detalles', compact('documento', 'ensayosRealizados'));
    }

    public function reemplazarMarcadoresInforme($documentoId)
    {
        $documento = Documento::with(['tipoTrabajo', 'trabajosDetalles.ensayo', 'trabajosDetalles.encargado'])->findOrFail($documentoId);

        // Ruta a la plantilla
        $templatePath = public_path('storage/informes_modelo/Plantilla v2.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        // Reemplazo de marcadores generales
        $templateProcessor->setValue('tipo_trabajo', $documento->tipoTrabajo->nombre ?? '');
        $templateProcessor->setValue('obra', $documento->obra ?? '');
        $templateProcessor->setValue('mes', $documento->mes ?? '');
        $templateProcessor->setValue('año', $documento->año ?? '');
        $templateProcessor->setValue('peticionario', $documento->peticionario ?? '');
        $templateProcessor->setValue('referencia', $documento->referencia ?? '');

        // Fecha formateada
        if ($documento->fecha_presupuesto) {
            $fecha = \Carbon\Carbon::parse($documento->fecha_presupuesto);
            $mes = $fecha->translatedFormat('F');
            $anio = $fecha->year;
            $dia = $fecha->day;
            $fechaFormateada = "{$dia} de {$mes} de {$anio}";
        } else {
            $fechaFormateada = '';
        }
        $templateProcessor->setValue('fecha_presupuesto', $fechaFormateada);

        $templateProcessor->setValue('ubicacion', $documento->ubicacion ?? '');
        $templateProcessor->setValue('objetivo_alcance', $documento->objeto_alcance ?? '');

        // Ensayos y encargados (del 1 al 23)
        $trabajos = $documento->trabajosDetalles;
        for ($i = 1; $i <= 23; $i++) {
            $ensayo = $trabajos[$i - 1]->ensayo->nombre ?? '';
            $encargado = $trabajos[$i - 1]->encargado->nombre ?? '';

            $templateProcessor->setValue("ensayo_$i", $ensayo ? $ensayo . '.' : '');
            $templateProcessor->setValue("encargado_$i", $encargado ? $encargado . '.' : '');
            $templateProcessor->setValue("relleno_encargado$i", $encargado ? 'Encargado: ' : '');
        }

          // Verifica si el ensayo "Relevamiento Geométrico" está en los trabajos
        $tieneRelevamientoGeometrico = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 1;
        });

        if ($tieneRelevamientoGeometrico) {
            $templateProcessor->setValue('titulo_relevamiento_geometrico', 'relevamiento geométrico');
            $templateProcessor->setValue('texto_intro_relevamiento_geometrico', 'A continuación, se presenta la planta de encofrado obtenido en el relevamiento geométrico.');
            $templateProcessor->setValue('pie_figura_relevamiento_geometrico', 'Planta de encofrado obtenido en el relevamiento geométrico.');
        } else {
            $templateProcessor->setValue('titulo_relevamiento_geometrico', '');
            $templateProcessor->setValue('texto_intro_relevamiento_geometrico', '');
            $templateProcessor->setValue('pie_figura_relevamiento_geometrico', '');
        }


        // Verifica si el ensayo "Relevamiento de Daños" está en los trabajos
        $tieneRelevamientoDanos = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 2;
        });

        if ($tieneRelevamientoDanos) {
            $templateProcessor->setValue('titulo_relevamiento_danos', 'relevamiento de daños');
            $templateProcessor->setValue('texto_intro_relevamiento_danos', 'A continuación, se presentan fotografías de los daños más significativos encontrados en la estructura.');
            for ($i = 1; $i <= 50; $i++) {
                $templateProcessor->setValue("foto_dano_$i", "Presencia de xxxxx en xxxxx.");
            }
            $templateProcessor->setValue('texto_ubicacion_danos', 'A continuación, se presentan las ubicaciones de los daños obtenidos en el relevamiento de daños realizado en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_danos', 'Ubicación de daños significativos encontrados en la estructura.');
        } else {
            $templateProcessor->setValue('titulo_relevamiento_danos', '');
            $templateProcessor->setValue('texto_intro_relevamiento_danos', '');
            for ($i = 1; $i <= 50; $i++) {
                $templateProcessor->setValue("foto_dano_$i", '');
            }
            $templateProcessor->setValue('texto_ubicacion_danos', '');
            $templateProcessor->setValue('pie_figura_ubicacion_danos', '');
        }


        // Verifica si el ensayo "Ensayo de Esclerometría" está en los trabajos
        $tieneEsclerometria = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 3;
        });

        if ($tieneEsclerometria) {
            $templateProcessor->setValue('titulo_esclerometria', 'Ensayo de Esclerometría');
            $templateProcessor->setValue('texto_intro_esclerometria', 'A continuación, se presentan fotografías de los ensayos de esclerometría realizados en la estructura.');
            $templateProcessor->setValue('literales_esclerometria_1', 'a) b)');
            $templateProcessor->setValue('literales_esclerometria_2', 'c) d)');
            $templateProcessor->setValue('foto_esclerometria', '(a, b, c y d) Ensayos de esclerometría realizados en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_esclerometria', 'A continuación, se presentan las ubicaciones de los ensayos de esclerometría realizados en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_esclerometria', 'Ubicación de ensayos de esclerometría realizados en la estructura.');
            $templateProcessor->setValue('texto_resultados_esclerometria', 'A continuación, se presentan los resultados de los ensayos de esclerometría realizados en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_esclerometria', 'Resultados obtenidos a partir de los ensayos de esclerometría.');
        } else {
            $templateProcessor->setValue('titulo_esclerometria', '');
            $templateProcessor->setValue('texto_intro_esclerometria', '');
            $templateProcessor->setValue('literales_esclerometria_1', '');
            $templateProcessor->setValue('literales_esclerometria_2', '');
            $templateProcessor->setValue('foto_esclerometria', '');
            $templateProcessor->setValue('texto_ubicacion_esclerometria', '');
            $templateProcessor->setValue('pie_figura_ubicacion_esclerometria', '');
            $templateProcessor->setValue('texto_resultados_esclerometria', '');
            $templateProcessor->setValue('pie_tabla_resultados_esclerometria', '');
        }

        // --- Ensayo de Ultrasonido ---
        $tieneUltrasonido = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 4;
        });

        if ($tieneUltrasonido) {
            $templateProcessor->setValue('titulo_ultrasonido', 'Ensayo de Ultrasonido');
            $templateProcessor->setValue('texto_intro_ultrasonido', 'A continuación, se presentan fotografías de los ensayos de ultrasonido realizados en la estructura.');
            $templateProcessor->setValue('literales_ultrasonido_1', 'a) b)');
            $templateProcessor->setValue('literales_ultrasonido_2', 'c) d)');
            $templateProcessor->setValue('foto_ultrasonido', '(a, b, c y d) Ensayos de ultrasonido realizados en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_ultrasonido', 'A continuación, se presentan las ubicaciones de los ensayos de ultrasonido realizados en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_ultrasonido', 'Ubicación de ensayos de ultrasonido realizados en la estructura.');
            $templateProcessor->setValue('texto_resultados_ultrasonido', 'A continuación, se presentan los resultados de los ensayos de ultrasonido realizados en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_ultrasonido', 'Resultados obtenidos a partir de los ensayos de ultrasonido.');
        } else {
            $templateProcessor->setValue('titulo_ultrasonido', '');
            $templateProcessor->setValue('texto_intro_ultrasonido', '');
            $templateProcessor->setValue('literales_ultrasonido_1', '');
            $templateProcessor->setValue('literales_ultrasonido_2', '');
            $templateProcessor->setValue('foto_ultrasonido', '');
            $templateProcessor->setValue('texto_ubicacion_ultrasonido', '');
            $templateProcessor->setValue('pie_figura_ubicacion_ultrasonido', '');
            $templateProcessor->setValue('texto_resultados_ultrasonido', '');
            $templateProcessor->setValue('pie_tabla_resultados_ultrasonido', '');
        }

        // --- Ensayo de Carbonatación ---
        $tieneCarbonatacion = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 5;
        });

        if ($tieneCarbonatacion) {
            $templateProcessor->setValue('titulo_carbonatacion', 'ensayo de carbonatación');
            $templateProcessor->setValue('texto_intro_carbonatacion', 'A continuación, se presentan fotografías de los ensayos de carbonatación realizados en la estructura.');
            $templateProcessor->setValue('literales_carbonatacion_1', 'a) b)');
            $templateProcessor->setValue('literales_carbonatacion_2', 'c) d)');
            $templateProcessor->setValue('foto_carbonatacion', '(a, b, c y d) Ensayos de carbonatación realizados en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_carbonatacion', 'A continuación, se presentan las ubicaciones de los ensayos de carbonatación realizados en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_carbonatacion', 'Ubicación de ensayos de carbonatación realizados en la estructura.');
            $templateProcessor->setValue('texto_resultados_carbonatacion', 'A continuación, se presentan los resultados de los ensayos de carbonatación realizados en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_carbonatacion', 'Resultados obtenidos a partir de los ensayos de carbonatación.');
        } else {
            $templateProcessor->setValue('titulo_carbonatacion', '');
            $templateProcessor->setValue('texto_intro_carbonatacion', '');
            $templateProcessor->setValue('literales_carbonatacion_1', '');
            $templateProcessor->setValue('literales_carbonatacion_2', '');
            $templateProcessor->setValue('foto_carbonatacion', '');
            $templateProcessor->setValue('texto_ubicacion_carbonatacion', '');
            $templateProcessor->setValue('pie_figura_ubicacion_carbonatacion', '');
            $templateProcessor->setValue('texto_resultados_carbonatacion', '');
            $templateProcessor->setValue('pie_tabla_resultados_carbonatacion', '');
        }

        // --- Inspección de Armaduras ---
        $tieneInspeccionArmaduras = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 6;
        });

        if ($tieneInspeccionArmaduras) {
            $templateProcessor->setValue('titulo_armaduras', 'inspección de armaduras');
            $templateProcessor->setValue('texto_intro_armaduras', 'A continuación, se presentan fotografías de las inspecciones de armaduras realizadas en la estructura.');
            $templateProcessor->setValue('literales_armaduras_1', 'a) b)');
            $templateProcessor->setValue('literales_armaduras_2', 'c) d)');
            $templateProcessor->setValue('foto_armaduras', '(a, b, c y d) Inspecciones de armaduras realizadas en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_armaduras', 'A continuación, se presentan las ubicaciones de las inspecciones de armaduras realizadas en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_armaduras', 'Ubicación de las inspecciones de armaduras realizadas en la estructura.');
            $templateProcessor->setValue('texto_resultados_armaduras', 'A continuación, se presentan los resultados de las inspecciones de armaduras realizadas en la estructura.');
            $templateProcessor->setValue('pie_figura_resultados_armaduras', 'Detalles de armaduras obtenidas en las inspecciones.');
        } else {
            $templateProcessor->setValue('titulo_armaduras', '');
            $templateProcessor->setValue('texto_intro_armaduras', '');
            $templateProcessor->setValue('literales_armaduras_1', '');
            $templateProcessor->setValue('literales_armaduras_2', '');
            $templateProcessor->setValue('foto_armaduras', '');
            $templateProcessor->setValue('texto_ubicacion_armaduras', '');
            $templateProcessor->setValue('pie_figura_ubicacion_armaduras', '');
            $templateProcessor->setValue('texto_resultados_armaduras', '');
            $templateProcessor->setValue('pie_figura_resultados_armaduras', '');
        }

        // --- Extracción de Testigos ---
        $tieneExtraccionTestigos = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 7;
        });

        if ($tieneExtraccionTestigos) {
            $templateProcessor->setValue('titulo_testigos', 'extraccion de testigos');
            $templateProcessor->setValue('texto_intro_testigos', 'A continuación, se presentan fotografías de las extracciones de testigos realizadas en la estructura.');
            $templateProcessor->setValue('literales_testigos_1', 'a) b)');
            $templateProcessor->setValue('literales_testigos_2', 'c) d)');
            $templateProcessor->setValue('foto_testigos', '(a, b, c y d) Extracciones de testigos realizadas en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_testigos', 'A continuación, se presentan las ubicaciones de las extracciones de testigos realizadas en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_testigos', 'Ubicación de las extracciones de testigos realizadas en la estructura.');
            $templateProcessor->setValue('texto_resultados_testigos', 'A continuación, se presentan los resultados de las extracciones de testigos realizadas en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_testigos', 'Resultados de ensayos realizados en los testigos.');
        } else {
            $templateProcessor->setValue('titulo_testigos', '');
            $templateProcessor->setValue('texto_intro_testigos', '');
            $templateProcessor->setValue('literales_testigos_1', '');
            $templateProcessor->setValue('literales_testigos_2', '');
            $templateProcessor->setValue('foto_testigos', '');
            $templateProcessor->setValue('texto_ubicacion_testigos', '');
            $templateProcessor->setValue('pie_figura_ubicacion_testigos', '');
            $templateProcessor->setValue('texto_resultados_testigos', '');
            $templateProcessor->setValue('pie_tabla_resultados_testigos', '');
        }


        // --- Medición de Nivel ---
        $tieneMedicionNivel = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 8; // Ajusta el ID según corresponda
        });

        if ($tieneMedicionNivel) {
            $templateProcessor->setValue('titulo_nivel', 'medición de nivel');
            $templateProcessor->setValue('texto_intro_nivel', 'A continuación, se presentan fotografías de las mediciones de nivel realizadas en la estructura.');
            $templateProcessor->setValue('literales_nivel_1', 'a) b)');
            $templateProcessor->setValue('literales_nivel_2', 'c) d)');
            $templateProcessor->setValue('foto_nivel', '(a, b, c y d) Mediciones de nivel realizadas en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_nivel', 'A continuación, se presentan las ubicaciones de las mediciones de nivel realizadas en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_nivel', 'Ubicación de mediciones de nivel realizadas en la estructura.');
            $templateProcessor->setValue('texto_curvas_nivel', 'A continuación, se presentan las curvas de nivel obtenidas a partir de las mediciones realizadas en la estructura.');
            $templateProcessor->setValue('pie_figura_curvas_nivel', 'Curvas de nivel obtenidas.');
        } else {
            $templateProcessor->setValue('titulo_nivel', '');
            $templateProcessor->setValue('texto_intro_nivel', '');
            $templateProcessor->setValue('literales_nivel_1', '');
            $templateProcessor->setValue('literales_nivel_2', '');
            $templateProcessor->setValue('foto_nivel', '');
            $templateProcessor->setValue('texto_ubicacion_nivel', '');
            $templateProcessor->setValue('pie_figura_ubicacion_nivel', '');
            $templateProcessor->setValue('texto_curvas_nivel', '');
            $templateProcessor->setValue('pie_figura_curvas_nivel', '');
        }



        // --- Medición de Inclinación ---
        $tieneMedicionInclinacion = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 9; // Ajusta el ID según corresponda
        });

        if ($tieneMedicionInclinacion) {
            $templateProcessor->setValue('titulo_inclinacion', 'medición de inclinación');
            $templateProcessor->setValue('texto_intro_inclinacion', 'A continuación, se presentan fotografías de las mediciones de inclinación realizadas en la estructura.');
            $templateProcessor->setValue('literales_inclinacion_1', 'a) b)');
            $templateProcessor->setValue('literales_inclinacion_2', 'c) d)');
            $templateProcessor->setValue('foto_inclinacion', '(a, b, c y d) Mediciones de inclinación realizadas en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_inclinacion', 'A continuación, se presentan las ubicaciones de las mediciones de inclinación realizadas en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_inclinacion', 'Ubicación de mediciones de inclinación realizadas en la estructura.');
            $templateProcessor->setValue('texto_resultados_inclinacion', 'A continuación, se presentan los resultados de la medición de inclinación realizadas en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_inclinacion', 'Resultados obtenidos a partir de la medición de inclinación.');
        } else {
            $templateProcessor->setValue('titulo_inclinacion', '');
            $templateProcessor->setValue('texto_intro_inclinacion', '');
            $templateProcessor->setValue('literales_inclinacion_1', '');
            $templateProcessor->setValue('literales_inclinacion_2', '');
            $templateProcessor->setValue('foto_inclinacion', '');
            $templateProcessor->setValue('texto_ubicacion_inclinacion', '');
            $templateProcessor->setValue('pie_figura_ubicacion_inclinacion', '');
            $templateProcessor->setValue('texto_resultados_inclinacion', '');
            $templateProcessor->setValue('pie_tabla_resultados_inclinacion', '');
        }


        // --- Medición de Fisuras ---
        $tieneMedicionFisuras = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 10; // Ajusta el ID según corresponda
        });

        if ($tieneMedicionFisuras) {
            $templateProcessor->setValue('titulo_fisuras', 'medición de fisuras');
            $templateProcessor->setValue('texto_intro_fisuras', 'A continuación, se presentan fotografías de las mediciones de fisuras realizadas en la estructura.');
            $templateProcessor->setValue('literales_fisuras_1', 'a) b)');
            $templateProcessor->setValue('literales_fisuras_2', 'c) d)');
            $templateProcessor->setValue('foto_fisuras', '(a, b, c y d) Mediciones de fisuras realizadas en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_fisuras', 'A continuación, se presentan las ubicaciones de las mediciones de fisuras realizadas en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_fisuras', 'Ubicación e identificación de mediciones de fisuras realizadas en la estructura.');
            $templateProcessor->setValue('texto_resultados_fisuras', 'A continuación, se presentan los resultados de la medición de fisuras realizadas en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_fisuras', 'Resultados obtenidos a partir de la medición de fisuras.');
        } else {
            $templateProcessor->setValue('titulo_fisuras', '');
            $templateProcessor->setValue('texto_intro_fisuras', '');
            $templateProcessor->setValue('literales_fisuras_1', '');
            $templateProcessor->setValue('literales_fisuras_2', '');
            $templateProcessor->setValue('foto_fisuras', '');
            $templateProcessor->setValue('texto_ubicacion_fisuras', '');
            $templateProcessor->setValue('pie_figura_ubicacion_fisuras', '');
            $templateProcessor->setValue('texto_resultados_fisuras', '');
            $templateProcessor->setValue('pie_tabla_resultados_fisuras', '');
        }

        // --- Monitoreo de Fisuras ---
        $tieneMonitoreoFisuras = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 11; // Ajusta el ID según corresponda
        });

        if ($tieneMonitoreoFisuras) {
            $templateProcessor->setValue('titulo_monitoreo_fisuras', 'monitoreo de fisuras');
            $templateProcessor->setValue('texto_intro_monitoreo_fisuras', 'A continuación, se presentan fotografías del monitoreo de fisuras realizados en la estructura.');
            $templateProcessor->setValue('literales_monitoreo_fisuras_1', 'a) b)');
            $templateProcessor->setValue('literales_monitoreo_fisuras_2', 'c) d)');
            $templateProcessor->setValue('foto_monitoreo_fisuras', '(a, b, c y d) Monitoreo de fisuras realizados en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_monitoreo_fisuras', 'A continuación, se presentan las ubicaciones del monitoreo de fisuras realizados en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_monitoreo_fisuras', 'Ubicación e identificación del monitoreo de fisuras realizados en la estructura.');
            $templateProcessor->setValue('texto_resultados_monitoreo_fisuras', 'A continuación, se presentan los resultados del monitoreo de fisuras realizados en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_monitoreo_fisuras', 'Resultados obtenidos a partir del monitoreo de fisuras.');
        } else {
            $templateProcessor->setValue('titulo_monitoreo_fisuras', '');
            $templateProcessor->setValue('texto_intro_monitoreo_fisuras', '');
            $templateProcessor->setValue('literales_monitoreo_fisuras_1', '');
            $templateProcessor->setValue('literales_monitoreo_fisuras_2', '');
            $templateProcessor->setValue('foto_monitoreo_fisuras', '');
            $templateProcessor->setValue('texto_ubicacion_monitoreo_fisuras', '');
            $templateProcessor->setValue('pie_figura_ubicacion_monitoreo_fisuras', '');
            $templateProcessor->setValue('texto_resultados_monitoreo_fisuras', '');
            $templateProcessor->setValue('pie_tabla_resultados_monitoreo_fisuras', '');
        }


        // --- Prueba de Carga Estática ---
        $tienePruebaCarga = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 12; // Ajusta el ID según corresponda
        });

        if ($tienePruebaCarga) {
            $templateProcessor->setValue('titulo_prueba_carga', 'prueba de carga estática');
            $templateProcessor->setValue('texto_intro_prueba_carga', 'A continuación, se presentan fotografías de la prueba de carga realizada en la estructura.');
            $templateProcessor->setValue('literales_prueba_carga_1', 'a) b)');
            $templateProcessor->setValue('literales_prueba_carga_2', 'c) d)');
            $templateProcessor->setValue('foto_prueba_carga', '(a, b, c y d) Prueba de carga realizada en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_prueba_carga', 'A continuación, se presentan las ubicaciones de la prueba de carga realizada en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_prueba_carga', 'Ubicación de la prueba de carga realizada en la estructura.');
            $templateProcessor->setValue('texto_resultados_prueba_carga', 'A continuación, se presentan los resultados de la prueba de carga realizada en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_prueba_carga', 'Resultados obtenidos a partir de la prueba de carga.');
        } else {
            $templateProcessor->setValue('titulo_prueba_carga', '');
            $templateProcessor->setValue('texto_intro_prueba_carga', '');
            $templateProcessor->setValue('literales_prueba_carga_1', '');
            $templateProcessor->setValue('literales_prueba_carga_2', '');
            $templateProcessor->setValue('foto_prueba_carga', '');
            $templateProcessor->setValue('texto_ubicacion_prueba_carga', '');
            $templateProcessor->setValue('pie_figura_ubicacion_prueba_carga', '');
            $templateProcessor->setValue('texto_resultados_prueba_carga', '');
            $templateProcessor->setValue('pie_tabla_resultados_prueba_carga', '');
        }

        // --- Medición de Frecuencia de Vibración ---
        $tieneFrecuenciaVibracion = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 13; // Ajusta el ID según corresponda
        });

        if ($tieneFrecuenciaVibracion) {
            $templateProcessor->setValue('titulo_frecuencia_vibracion', 'medición de frecuencia de vibración');
            $templateProcessor->setValue('texto_intro_frecuencia_vibracion', 'A continuación, se presentan fotografías de las mediciones de frecuencia de vibración realizadas en la estructura.');
            $templateProcessor->setValue('literales_frecuencia_vibracion_1', 'a) b)');
            $templateProcessor->setValue('literales_frecuencia_vibracion_2', 'c) d)');
            $templateProcessor->setValue('foto_frecuencia_vibracion', '(a, b, c y d) Mediciones de frecuencia de vibración realizadas en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_frecuencia_vibracion', 'A continuación, se presentan las ubicaciones de las mediciones de frecuencia de vibración realizada en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_frecuencia_vibracion', 'Ubicación de las mediciones de frecuencia de vibración realizada en la estructura.');
            $templateProcessor->setValue('texto_resultados_frecuencia_vibracion', 'A continuación, se presentan los resultados de las mediciones de frecuencia de vibraciones realizada en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_frecuencia_vibracion', 'Resultados obtenidos a partir de las mediciones de frecuencia de vibraciones.');
        } else {
            $templateProcessor->setValue('titulo_frecuencia_vibracion', '');
            $templateProcessor->setValue('texto_intro_frecuencia_vibracion', '');
            $templateProcessor->setValue('literales_frecuencia_vibracion_1', '');
            $templateProcessor->setValue('literales_frecuencia_vibracion_2', '');
            $templateProcessor->setValue('foto_frecuencia_vibracion', '');
            $templateProcessor->setValue('texto_ubicacion_frecuencia_vibracion', '');
            $templateProcessor->setValue('pie_figura_ubicacion_frecuencia_vibracion', '');
            $templateProcessor->setValue('texto_resultados_frecuencia_vibracion', '');
            $templateProcessor->setValue('pie_tabla_resultados_frecuencia_vibracion', '');
        }

        // --- Verificación de Fundación ---
        $tieneVerificacionFundacion = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 14; // Ajusta el ID según corresponda
        });

        if ($tieneVerificacionFundacion) {
            $templateProcessor->setValue('titulo_verificacion_fundacion', 'verificación de fundación');
            $templateProcessor->setValue('texto_intro_verificacion_fundacion', 'A continuación, se presentan fotografías de las verificaciones de fundación realizadas en la estructura.');
            $templateProcessor->setValue('literales_verificacion_fundacion_1', 'a) b)');
            $templateProcessor->setValue('literales_verificacion_fundacion_2', 'c) d)');
            $templateProcessor->setValue('foto_verificacion_fundacion', '(a, b, c y d) Verificaciones de fundación realizadas en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_verificacion_fundacion', 'A continuación, se presentan las ubicaciones de las verificaciones de fundación realizada en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_verificacion_fundacion', 'Ubicación de las verificaciones de fundación realizada en la estructura.');
            $templateProcessor->setValue('texto_resultados_verificacion_fundacion', 'A continuación, se presentan los resultados de las verificaciones de fundación realizada en la estructura.');
            $templateProcessor->setValue('pie_figura_resultados_verificacion_fundacion', 'Resultados obtenidos a partir de las verificaciones de fundación.');
        } else {
            $templateProcessor->setValue('titulo_verificacion_fundacion', '');
            $templateProcessor->setValue('texto_intro_verificacion_fundacion', '');
            $templateProcessor->setValue('literales_verificacion_fundacion_1', '');
            $templateProcessor->setValue('literales_verificacion_fundacion_2', '');
            $templateProcessor->setValue('foto_verificacion_fundacion', '');
            $templateProcessor->setValue('texto_ubicacion_verificacion_fundacion', '');
            $templateProcessor->setValue('pie_figura_ubicacion_verificacion_fundacion', '');
            $templateProcessor->setValue('texto_resultados_verificacion_fundacion', '');
            $templateProcessor->setValue('pie_figura_resultados_verificacion_fundacion', '');
        }




        // --- Integridad en Pilotes ---
        $tieneIntegridadPilotes = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 15; // Ajusta el ID según corresponda
        });

        if ($tieneIntegridadPilotes) {
            $templateProcessor->setValue('titulo_integridad_pilotes', 'integridad en pilotes');
            $templateProcessor->setValue('texto_intro_integridad_pilotes', 'A continuación, se presentan fotografías de los ensayos de integridad realizados en la estructura.');
            $templateProcessor->setValue('literales_integridad_pilotes_1', 'a) b)');
            $templateProcessor->setValue('literales_integridad_pilotes_2', 'c) d)');
            $templateProcessor->setValue('foto_integridad_pilotes', '(a, b, c y d) Ensayos de integridad realizados en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_integridad_pilotes', 'A continuación, se presentan las ubicaciones de los ensayos de integridad realizados en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_integridad_pilotes', 'Ubicación de los ensayos de integridad realizados en la estructura.');
            $templateProcessor->setValue('texto_resultados_integridad_pilotes', 'A continuación, se presentan los resultados de los ensayos de integridad realizados en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_integridad_pilotes', 'Resultados obtenidos a partir de los ensayos de integridad.');
        } else {
            $templateProcessor->setValue('titulo_integridad_pilotes', '');
            $templateProcessor->setValue('texto_intro_integridad_pilotes', '');
            $templateProcessor->setValue('literales_integridad_pilotes_1', '');
            $templateProcessor->setValue('literales_integridad_pilotes_2', '');
            $templateProcessor->setValue('foto_integridad_pilotes', '');
            $templateProcessor->setValue('texto_ubicacion_integridad_pilotes', '');
            $templateProcessor->setValue('pie_figura_ubicacion_integridad_pilotes', '');
            $templateProcessor->setValue('texto_resultados_integridad_pilotes', '');
            $templateProcessor->setValue('pie_tabla_resultados_integridad_pilotes', '');
        }

        // --- Prueba de Carga Dinámica en Pilotes ---
        $tieneCargaDinamicaPilotes = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 16; // Ajusta el ID según corresponda
        });

        if ($tieneCargaDinamicaPilotes) {
            $templateProcessor->setValue('titulo_carga_dinamica_pilotes', 'prueba de carga dinámica en pilotes');
            $templateProcessor->setValue('texto_intro_carga_dinamica_pilotes', 'A continuación, se presentan fotografías de la prueba de carga realizada en la estructura.');
            $templateProcessor->setValue('literales_carga_dinamica_pilotes_1', 'a) b)');
            $templateProcessor->setValue('literales_carga_dinamica_pilotes_2', 'c) d)');
            $templateProcessor->setValue('foto_carga_dinamica_pilotes', '(a, b, c y d) Prueba de carga realizada en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_carga_dinamica_pilotes', 'A continuación, se presentan la ubicación de la prueba de carga realizada en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_carga_dinamica_pilotes', 'Ubicación de la prueba de carga realizada en la estructura.');
            $templateProcessor->setValue('texto_resultados_carga_dinamica_pilotes', 'A continuación, se presentan los resultados de la prueba de carga realizada en la estructura.');
            $templateProcessor->setValue('pie_figura_resultados_carga_dinamica_pilotes', 'Resultados obtenidos a partir de la prueba de carga.');
        } else {
            $templateProcessor->setValue('titulo_carga_dinamica_pilotes', '');
            $templateProcessor->setValue('texto_intro_carga_dinamica_pilotes', '');
            $templateProcessor->setValue('literales_carga_dinamica_pilotes_1', '');
            $templateProcessor->setValue('literales_carga_dinamica_pilotes_2', '');
            $templateProcessor->setValue('foto_carga_dinamica_pilotes', '');
            $templateProcessor->setValue('texto_ubicacion_carga_dinamica_pilotes', '');
            $templateProcessor->setValue('pie_figura_ubicacion_carga_dinamica_pilotes', '');
            $templateProcessor->setValue('texto_resultados_carga_dinamica_pilotes', '');
            $templateProcessor->setValue('pie_figura_resultados_carga_dinamica_pilotes', '');
        }

        // --- Ensayos de Resistividad ---
        $tieneResistividad = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 17; // Ajusta el ID según corresponda
        });

        if ($tieneResistividad) {
            $templateProcessor->setValue('titulo_resistividad', 'ensayos de resistividad');
            $templateProcessor->setValue('texto_intro_resistividad', 'A continuación, se presentan fotografías de los ensayos de resistividad realizados en la estructura.');
            $templateProcessor->setValue('literales_resistividad_1', 'a) b)');
            $templateProcessor->setValue('literales_resistividad_2', 'c) d)');
            $templateProcessor->setValue('foto_resistividad', '(a, b, c y d) Ensayos de resistividad realizados en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_resistividad', 'A continuación, se presentan las ubicaciones de los ensayos de resistividad realizados en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_resistividad', 'Ubicación de los ensayos de resistividad realizados en la estructura.');
            $templateProcessor->setValue('texto_resultados_resistividad', 'A continuación, se presentan los resultados de los ensayos de resistividad realizados en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_resistividad', 'Resultados obtenidos a partir de los ensayos de resistividad.');
        } else {
            $templateProcessor->setValue('titulo_resistividad', '');
            $templateProcessor->setValue('texto_intro_resistividad', '');
            $templateProcessor->setValue('literales_resistividad_1', '');
            $templateProcessor->setValue('literales_resistividad_2', '');
            $templateProcessor->setValue('foto_resistividad', '');
            $templateProcessor->setValue('texto_ubicacion_resistividad', '');
            $templateProcessor->setValue('pie_figura_ubicacion_resistividad', '');
            $templateProcessor->setValue('texto_resultados_resistividad', '');
            $templateProcessor->setValue('pie_tabla_resultados_resistividad', '');
        }

        // --- Ensayos de Potencial de Corrosión ---
        $tienePotencialCorrosion = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 18; // Ajusta el ID según corresponda
        });

        if ($tienePotencialCorrosion) {
            $templateProcessor->setValue('titulo_potencial_corrosion', 'ensayos de potencial de corrosión');
            $templateProcessor->setValue('texto_intro_potencial_corrosion', 'A continuación, se presentan fotografías de los ensayos de potencial de corrosión realizados en la estructura.');
            $templateProcessor->setValue('literales_potencial_corrosion_1', 'a) b)');
            $templateProcessor->setValue('literales_potencial_corrosion_2', 'c) d)');
            $templateProcessor->setValue('foto_potencial_corrosion', '(a, b, c y d) Ensayos de potencial de corrosión realizados en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_potencial_corrosion', 'A continuación, se presentan las ubicaciones de los ensayos de potencial de corrosión realizados en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_potencial_corrosion', 'Ubicación de los ensayos de potencial de corrosión realizados en la estructura.');
            $templateProcessor->setValue('texto_resultados_potencial_corrosion', 'A continuación, se presentan los resultados de los ensayos de potencial de corrosión realizados en la estructura.');
            $templateProcessor->setValue('pie_figura_resultados_potencial_corrosion', 'Resultados obtenidos a partir de los ensayos de potencial de corrosión.');
        } else {
            $templateProcessor->setValue('titulo_potencial_corrosion', '');
            $templateProcessor->setValue('texto_intro_potencial_corrosion', '');
            $templateProcessor->setValue('literales_potencial_corrosion_1', '');
            $templateProcessor->setValue('literales_potencial_corrosion_2', '');
            $templateProcessor->setValue('foto_potencial_corrosion', '');
            $templateProcessor->setValue('texto_ubicacion_potencial_corrosion', '');
            $templateProcessor->setValue('pie_figura_ubicacion_potencial_corrosion', '');
            $templateProcessor->setValue('texto_resultados_potencial_corrosion', '');
            $templateProcessor->setValue('pie_figura_resultados_potencial_corrosion', '');
        }

        // --- Ensayos de Cloruros ---
        $tieneCloruros = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 19; // Ajusta el ID según corresponda
        });

        if ($tieneCloruros) {
            $templateProcessor->setValue('titulo_cloruros', 'ensayos de cloruros');
            $templateProcessor->setValue('texto_intro_cloruros', 'A continuación, se presentan fotografías de los ensayos de cloruros realizados en la estructura.');
            $templateProcessor->setValue('literales_cloruros_1', 'a) b)');
            $templateProcessor->setValue('literales_cloruros_2', 'c) d)');
            $templateProcessor->setValue('foto_cloruros', '(a, b, c y d) Ensayos de cloruros realizados en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_cloruros', 'A continuación, se presentan las ubicaciones de los ensayos de cloruros realizados en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_cloruros', 'Ubicación de los ensayos de cloruros realizados en la estructura.');
            $templateProcessor->setValue('texto_resultados_cloruros', 'A continuación, se presentan los resultados de los ensayos de cloruros realizados en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_cloruros', 'Resultados obtenidos a partir de los ensayos de cloruros.');
        } else {
            $templateProcessor->setValue('titulo_cloruros', '');
            $templateProcessor->setValue('texto_intro_cloruros', '');
            $templateProcessor->setValue('literales_cloruros_1', '');
            $templateProcessor->setValue('literales_cloruros_2', '');
            $templateProcessor->setValue('foto_cloruros', '');
            $templateProcessor->setValue('texto_ubicacion_cloruros', '');
            $templateProcessor->setValue('pie_figura_ubicacion_cloruros', '');
            $templateProcessor->setValue('texto_resultados_cloruros', '');
            $templateProcessor->setValue('pie_tabla_resultados_cloruros', '');
        }



        // --- Inspección de Armaduras con Georradar ---
        $tieneArmadurasGeorradar = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 20; // Ajusta el ID según corresponda
        });

        if ($tieneArmadurasGeorradar) {
            $templateProcessor->setValue('titulo_armaduras_georradar', 'inspección de armaduras con georradar');
            $templateProcessor->setValue('texto_intro_armaduras_georradar', 'A continuación, se presentan fotografías de las inspecciones de armaduras con georradar realizadas en la estructura.');
            $templateProcessor->setValue('literales_armaduras_georradar_1', 'a) b)');
            $templateProcessor->setValue('literales_armaduras_georradar_2', 'c) d)');
            $templateProcessor->setValue('foto_armaduras_georradar', '(a, b, c y d) Inspecciones de armaduras con georradar realizadas en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_armaduras_georradar', 'A continuación, se presentan las ubicaciones de las inspecciones de armaduras con georradar realizadas en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_armaduras_georradar', 'Ubicación de las inspecciones de armaduras con georradar realizadas en la estructura.');
            $templateProcessor->setValue('texto_resultados_armaduras_georradar', 'A continuación, se presentan los resultados de las inspecciones de armaduras con georradar realizadas en la estructura.');
            $templateProcessor->setValue('pie_figura_resultados_armaduras_georradar', 'Resultados obtenidos a partir de las inspecciones de armaduras con georradar.');
        } else {
            $templateProcessor->setValue('titulo_armaduras_georradar', '');
            $templateProcessor->setValue('texto_intro_armaduras_georradar', '');
            $templateProcessor->setValue('literales_armaduras_georradar_1', '');
            $templateProcessor->setValue('literales_armaduras_georradar_2', '');
            $templateProcessor->setValue('foto_armaduras_georradar', '');
            $templateProcessor->setValue('texto_ubicacion_armaduras_georradar', '');
            $templateProcessor->setValue('pie_figura_ubicacion_armaduras_georradar', '');
            $templateProcessor->setValue('texto_resultados_armaduras_georradar', '');
            $templateProcessor->setValue('pie_figura_resultados_armaduras_georradar', '');
        }

        // --- Ensayos de Termografía ---
        $tieneTermografia = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 21; // Ajusta el ID según corresponda
        });

        if ($tieneTermografia) {
            $templateProcessor->setValue('titulo_termografia', 'ensayos de termografía');
            $templateProcessor->setValue('texto_intro_termografia', 'A continuación, se presentan fotografías de los ensayos de termografía realizados en la estructura.');
            $templateProcessor->setValue('literales_termografia_1', 'a) b)');
            $templateProcessor->setValue('literales_termografia_2', 'c) d)');
            $templateProcessor->setValue('foto_termografia', '(a, b, c y d) Ensayos de termografía realizados en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_termografia', 'A continuación, se presentan las ubicaciones de los ensayos de termografía realizados en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_termografia', 'Ubicación de los ensayos de termografía realizados en la estructura.');
            $templateProcessor->setValue('texto_resultados_termografia', 'A continuación, se presentan los resultados de los ensayos de termografía realizados en la estructura.');
            $templateProcessor->setValue('pie_figura_resultados_termografia', 'Resultados obtenidos a partir de los ensayos de termografía.');
        } else {
            $templateProcessor->setValue('titulo_termografia', '');
            $templateProcessor->setValue('texto_intro_termografia', '');
            $templateProcessor->setValue('literales_termografia_1', '');
            $templateProcessor->setValue('literales_termografia_2', '');
            $templateProcessor->setValue('foto_termografia', '');
            $templateProcessor->setValue('texto_ubicacion_termografia', '');
            $templateProcessor->setValue('pie_figura_ubicacion_termografia', '');
            $templateProcessor->setValue('texto_resultados_termografia', '');
            $templateProcessor->setValue('pie_figura_resultados_termografia', '');
        }

        // --- Inspección con Dron ---
        $tieneDron = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 22; // Ajusta el ID según corresponda
        });

        if ($tieneDron) {
            $templateProcessor->setValue('titulo_dron', 'inspección con dron');
            $templateProcessor->setValue('texto_intro_dron', 'A continuación, se presentan fotografías de la inspección con dron realizada en la estructura.');
            $templateProcessor->setValue('literales_dron_1', 'a) b)');
            $templateProcessor->setValue('literales_dron_2', 'c) d)');
            $templateProcessor->setValue('foto_dron', '(a, b, c y d) Inspección con dron realizada en la estructura.');
        } else {
            $templateProcessor->setValue('titulo_dron', '');
            $templateProcessor->setValue('texto_intro_dron', '');
            $templateProcessor->setValue('literales_dron_1', '');
            $templateProcessor->setValue('literales_dron_2', '');
            $templateProcessor->setValue('foto_dron', '');
        }

        // --- Ensayo de Permeabilidad ---
        $tienePermeabilidad = $trabajos->contains(function($detalle) {
            return $detalle->ensayo && $detalle->ensayo->id === 23; // Ajusta el ID según corresponda
        });

        if ($tienePermeabilidad) {
            $templateProcessor->setValue('titulo_permeabilidad', 'ensayo de permeabilidad');
            $templateProcessor->setValue('texto_intro_permeabilidad', 'A continuación, se presentan fotografías de los ensayos de permeabilidad realizados en la estructura.');
            $templateProcessor->setValue('literales_permeabilidad_1', 'a) b)');
            $templateProcessor->setValue('literales_permeabilidad_2', 'c) d)');
            $templateProcessor->setValue('foto_permeabilidad', '(a, b, c y d) Ensayos de permeabilidad realizados en la estructura.');
            $templateProcessor->setValue('texto_ubicacion_permeabilidad', 'A continuación, se presentan las ubicaciones de los ensayos de permeabilidad realizados en la estructura.');
            $templateProcessor->setValue('pie_figura_ubicacion_permeabilidad', 'Ubicación de los ensayos de permeabilidad realizados en la estructura.');
            $templateProcessor->setValue('texto_resultados_permeabilidad', 'A continuación, se presentan los resultados de los ensayos de permeabilidad realizados en la estructura.');
            $templateProcessor->setValue('pie_tabla_resultados_permeabilidad', 'Resultados obtenidos a partir de los ensayos de permeabilidad.');
        } else {
            $templateProcessor->setValue('titulo_permeabilidad', '');
            $templateProcessor->setValue('texto_intro_permeabilidad', '');
            $templateProcessor->setValue('literales_permeabilidad_1', '');
            $templateProcessor->setValue('literales_permeabilidad_2', '');
            $templateProcessor->setValue('foto_permeabilidad', '');
            $templateProcessor->setValue('texto_ubicacion_permeabilidad', '');
            $templateProcessor->setValue('pie_figura_ubicacion_permeabilidad', '');
            $templateProcessor->setValue('texto_resultados_permeabilidad', '');
            $templateProcessor->setValue('pie_tabla_resultados_permeabilidad', '');
        }

        // Guardar y devolver el archivo generado
        $outputPath = storage_path('app/public/documentos/informe_'.$documento->nombre.'.docx');
        $templateProcessor->saveAs($outputPath);

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }






}
