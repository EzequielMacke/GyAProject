<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTc;
use App\Models\EtiquetaDetalleTc;
use App\Models\EtiquetaTc;
use App\Models\FotoTc;
use App\Models\ObraTc;
use App\Services\MiniaturaFotoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class GaleriaTcController extends Controller
{
    private const NOMBRES_MESES = [
        '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
        '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
        '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre',
    ];

    public function index(ObraTc $obraTc)
    {
        if (! $this->verificarAcceso($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $fotos = FotoTc::where('obra_tc_id', $obraTc->id)
            ->with(['plano', 'usuario', 'etiquetas'])
            ->orderByDesc('created_at')
            ->get();

        // Si la miniatura ya existe se sirve como archivo estático; si no,
        // la primera vez pasa por la ruta que la genera y queda guardada.
        $fotos->each(function (FotoTc $foto) use ($obraTc) {
            $foto->url_miniatura = MiniaturaFotoService::existe($foto->archivo)
                ? MiniaturaFotoService::url($foto->archivo)
                : route('galeria_tc.miniatura', [$obraTc->id, $foto->id]);
        });

        $planosConFotos = $fotos->pluck('plano')
            ->filter()
            ->unique('id')
            ->sortBy('descripcion')
            ->values();

        $clasificaciones = $fotos->pluck('clasificacion')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $usuariosConFotos = $fotos->pluck('usuario')
            ->filter()
            ->unique('id')
            ->sortBy(fn ($usuario) => $usuario->nombre_completo ?: $usuario->nombre)
            ->values();

        $aniosConFotos = $fotos->pluck('created_at')
            ->filter()
            ->map(fn ($fecha) => $fecha->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

        $diasConFotos = $fotos->pluck('created_at')
            ->filter()
            ->map(fn ($fecha) => $fecha->format('d'))
            ->unique()
            ->sort()
            ->values();

        $mesesConFotos = $fotos->pluck('created_at')
            ->filter()
            ->map(fn ($fecha) => $fecha->format('m'))
            ->unique()
            ->sort()
            ->mapWithKeys(fn ($mes) => [$mes => self::NOMBRES_MESES[$mes]]);

        $etiquetasTc = EtiquetaTc::where('obra_tc_id', $obraTc->id)
            ->orderBy('descripcion')
            ->get(['id', 'descripcion']);

        return view('galeria_tc.index', compact(
            'obraTc', 'fotos', 'planosConFotos', 'clasificaciones', 'usuariosConFotos',
            'aniosConFotos', 'diasConFotos', 'mesesConFotos', 'etiquetasTc'
        ));
    }

    public function miniatura(ObraTc $obraTc, FotoTc $foto)
    {
        if (! $this->verificarAcceso($obraTc) || $foto->obra_tc_id !== $obraTc->id) {
            abort(403);
        }

        $rutaMiniatura = MiniaturaFotoService::generar($foto->archivo);

        if (! $rutaMiniatura) {
            // Si GD no pudo leer el original, se devuelve el original tal cual.
            return redirect($foto->archivo);
        }

        return response()->file(Storage::disk('public')->path($rutaMiniatura), [
            'Cache-Control' => 'private, max-age=604800',
        ]);
    }

    private function verificarAcceso(ObraTc $obraTc): bool
    {
        return DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', session('usuario_id'))
            ->exists();
    }

    public function crearEtiqueta(Request $request, ObraTc $obraTc)
    {
        if (! $this->verificarAcceso($obraTc)) {
            abort(403);
        }

        $data = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $etiqueta = EtiquetaTc::firstOrCreate([
            'obra_tc_id' => $obraTc->id,
            'descripcion' => $data['descripcion'],
        ]);

        return response()->json($etiqueta);
    }

    public function marcarFoto(Request $request, ObraTc $obraTc, FotoTc $foto)
    {
        if (! $this->verificarAcceso($obraTc) || $foto->obra_tc_id !== $obraTc->id) {
            abort(403);
        }

        $data = $request->validate([
            'etiqueta_tc_id' => 'required|integer',
        ]);

        $etiqueta = EtiquetaTc::where('obra_tc_id', $obraTc->id)->findOrFail($data['etiqueta_tc_id']);

        EtiquetaDetalleTc::firstOrCreate([
            'foto_tc_id' => $foto->id,
            'etiqueta_tc_id' => $etiqueta->id,
        ]);

        return response()->json(['success' => true]);
    }

    public function desmarcarFoto(ObraTc $obraTc, FotoTc $foto, EtiquetaTc $etiqueta)
    {
        if (! $this->verificarAcceso($obraTc) || $foto->obra_tc_id !== $obraTc->id) {
            abort(403);
        }

        EtiquetaDetalleTc::where('foto_tc_id', $foto->id)
            ->where('etiqueta_tc_id', $etiqueta->id)
            ->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Arma un ZIP con las fotos pedidas (selección puntual o todas las que
     * el cliente tiene visibles según sus filtros, ya que ese listado de
     * ids lo decide el front) y lo devuelve como descarga.
     *
     * Los ids llegan como un único string separado por comas (el front
     * envía un form normal para que el navegador maneje la descarga, y un
     * input por id chocaría con max_input_vars en descargas grandes).
     */
    public function descargar(Request $request, ObraTc $obraTc)
    {
        if (! $this->verificarAcceso($obraTc)) {
            abort(403);
        }

        $request->validate([
            'ids' => 'required|string',
            'token_descarga' => 'nullable|string|regex:/^[a-zA-Z0-9_-]{1,64}$/',
        ]);

        $ids = collect(explode(',', $request->input('ids')))
            ->map(fn ($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->values();

        $fotos = FotoTc::where('obra_tc_id', $obraTc->id)
            ->whereIn('id', $ids)
            ->get();

        if ($fotos->isEmpty()) {
            abort(404);
        }

        // Con cientos de fotos armar el ZIP puede llevar más que el límite por defecto.
        set_time_limit(0);

        $carpetaTemp = storage_path('app/temp');
        if (! is_dir($carpetaTemp)) {
            mkdir($carpetaTemp, 0755, true);
        }

        $zipNombre = 'fotos_'.Str::slug($obraTc->descripcion).'_'.now()->format('Ymd_His').'.zip';
        $zipRuta = $carpetaTemp.DIRECTORY_SEPARATOR.Str::random(8).'_'.$zipNombre;

        $zip = new ZipArchive();
        if ($zip->open($zipRuta, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'No se pudo crear el archivo ZIP.');
        }

        $disco = Storage::disk('public');
        $nombresUsados = [];

        foreach ($fotos as $foto) {
            $rutaRelativa = MiniaturaFotoService::rutaRelativa($foto->archivo);

            if (! $disco->exists($rutaRelativa)) {
                continue;
            }

            $nombreBase = basename($rutaRelativa);
            $nombreFinal = $nombreBase;
            $contador = 1;
            while (isset($nombresUsados[$nombreFinal])) {
                $nombreFinal = pathinfo($nombreBase, PATHINFO_FILENAME)."_{$contador}.".pathinfo($nombreBase, PATHINFO_EXTENSION);
                $contador++;
            }
            $nombresUsados[$nombreFinal] = true;

            // addFile lee el archivo recién al cerrar el ZIP (no lo carga
            // entero en memoria como addFromString) y las fotos se guardan
            // sin comprimir: JPG/PNG ya vienen comprimidos y recomprimirlos
            // sólo gasta tiempo de CPU.
            $zip->addFile($disco->path($rutaRelativa), $nombreFinal);
            $zip->setCompressionName($nombreFinal, ZipArchive::CM_STORE);
        }

        $zip->close();

        $respuesta = response()->download($zipRuta, $zipNombre)->deleteFileAfterSend(true);

        // El front espera esta cookie para saber que el ZIP ya está listo
        // y sacar el "Descargando…" del botón.
        if ($request->filled('token_descarga')) {
            $respuesta->headers->setCookie(cookie(
                'descarga_galeria', $request->input('token_descarga'), 1, '/', null, false, false
            ));
        }

        return $respuesta;
    }
}
