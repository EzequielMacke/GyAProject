<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTc;
use App\Models\EtiquetaDetalleTc;
use App\Models\EtiquetaTc;
use App\Models\FotoTc;
use App\Models\ObraTc;
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
     */
    public function descargar(Request $request, ObraTc $obraTc)
    {
        if (! $this->verificarAcceso($obraTc)) {
            abort(403);
        }

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $fotos = FotoTc::where('obra_tc_id', $obraTc->id)
            ->whereIn('id', $request->input('ids'))
            ->get();

        if ($fotos->isEmpty()) {
            abort(404);
        }

        $carpetaTemp = storage_path('app/temp');
        if (! is_dir($carpetaTemp)) {
            mkdir($carpetaTemp, 0755, true);
        }

        $zipNombre = 'fotos_'.Str::slug($obraTc->descripcion).'_'.now()->format('Ymd_His').'.zip';
        $zipRuta = $carpetaTemp.DIRECTORY_SEPARATOR.$zipNombre;

        $zip = new ZipArchive();
        $zip->open($zipRuta, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $nombresUsados = [];

        foreach ($fotos as $foto) {
            $rutaRelativa = ltrim(str_replace('/storage/', '', $foto->archivo), '/');

            if (! Storage::disk('public')->exists($rutaRelativa)) {
                continue;
            }

            $nombreBase = basename($rutaRelativa);
            $nombreFinal = $nombreBase;
            $contador = 1;
            while (in_array($nombreFinal, $nombresUsados, true)) {
                $nombreFinal = pathinfo($nombreBase, PATHINFO_FILENAME)."_{$contador}.".pathinfo($nombreBase, PATHINFO_EXTENSION);
                $contador++;
            }
            $nombresUsados[] = $nombreFinal;

            $zip->addFromString($nombreFinal, Storage::disk('public')->get($rutaRelativa));
        }

        $zip->close();

        return response()->download($zipRuta, $zipNombre)->deleteFileAfterSend(true);
    }
}
