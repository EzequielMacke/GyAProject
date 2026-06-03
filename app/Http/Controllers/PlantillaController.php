<?php

namespace App\Http\Controllers;

use App\Models\Plantilla;
use App\Models\PlantillaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PlantillaController extends Controller
{
    public function index()
    {
        $referenciasUsadas = Plantilla::where('referencia', '!=', 0)->pluck('referencia');

        $plantillas = Plantilla::with(['usuarioRel', 'detalles'])
            ->whereNotIn('id', $referenciasUsadas)
            ->orderBy('id')
            ->get();

        $todas = Plantilla::with('detalles')->get()->keyBy('id');

        $cadenas = [];
        foreach ($plantillas as $pla) {
            $chain   = [];
            $current = $pla;
            while ($current) {
                $chain[] = $current;
                if ($current->referencia == 0) break;
                $current = $todas->get($current->referencia);
            }
            $cadenas[$pla->id] = array_reverse($chain); // oldest → newest
        }

        return view('plantilla.index', compact('plantillas', 'cadenas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'archivos'  => 'required|array|min:1',
            'archivos.*'=> 'required|file',
        ]);

        $plantilla = Plantilla::create([
            'revision'    => 'Rev - 001',
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion ?? '',
            'observacion' => $request->observacion ?? '',
            'usuario'     => session('usuario_id'),
            'referencia'  => 0,
        ]);

        if (!Storage::disk('public')->exists('plantilla')) {
            Storage::disk('public')->makeDirectory('plantilla');
        }

        foreach ($request->file('archivos') as $archivo) {
            $nombreOriginal = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
            $extension      = $archivo->getClientOriginalExtension();
            $stamp          = now()->format('YmdHis');
            $nombreFinal    = $nombreOriginal . '_' . $stamp . ($extension ? '.' . $extension : '');

            Storage::disk('public')->putFileAs('plantilla', $archivo, $nombreFinal);

            PlantillaDetalle::create([
                'plantilla_id' => $plantilla->id,
                'ruta'         => $nombreFinal,
            ]);
        }

        return redirect()->route('plantilla.index')->with('success', 'Plantilla creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Plantilla::findOrFail($id)->update([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion ?? '',
            'observacion' => $request->observacion ?? '',
        ]);

        return redirect()->route('plantilla.index')->with('success', 'Plantilla actualizada correctamente.');
    }

    public function storeRevision(Request $request, $id)
    {
        $request->validate([
            'nombre'     => 'required|string|max:255',
            'archivos'   => 'required|array|min:1',
            'archivos.*' => 'required|file',
        ]);

        $original = Plantilla::findOrFail($id);

        preg_match('/(\d+)$/', $original->revision, $m);
        $nextNum      = isset($m[1]) ? (int)$m[1] + 1 : 2;
        $nextRevision = 'Rev - ' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        $nueva = Plantilla::create([
            'revision'    => $nextRevision,
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion ?? '',
            'observacion' => $request->observacion ?? '',
            'usuario'     => session('usuario_id'),
            'referencia'  => $original->id,
        ]);

        if (!Storage::disk('public')->exists('plantilla')) {
            Storage::disk('public')->makeDirectory('plantilla');
        }

        foreach ($request->file('archivos') as $archivo) {
            $nombreOriginal = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
            $extension      = $archivo->getClientOriginalExtension();
            $stamp          = now()->format('YmdHis');
            $nombreFinal    = $nombreOriginal . '_' . $stamp . ($extension ? '.' . $extension : '');

            Storage::disk('public')->putFileAs('plantilla', $archivo, $nombreFinal);

            PlantillaDetalle::create([
                'plantilla_id' => $nueva->id,
                'ruta'         => $nombreFinal,
            ]);
        }

        return redirect()->route('plantilla.index')->with('success', 'Nueva revisión creada correctamente.');
    }

    public function download($id)
    {
        $plantilla = Plantilla::with('detalles')->findOrFail($id);
        $detalles  = $plantilla->detalles;

        if ($detalles->isEmpty()) {
            return back()->with('error', 'Esta plantilla no tiene archivos.');
        }

        if ($detalles->count() === 1) {
            $ruta = storage_path('app/public/plantilla/' . $detalles->first()->ruta);
            return response()->download($ruta);
        }

        $zipPath = tempnam(sys_get_temp_dir(), 'pla_') . '.zip';
        $zip     = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE);

        foreach ($detalles as $detalle) {
            $ruta = storage_path('app/public/plantilla/' . $detalle->ruta);
            if (file_exists($ruta)) {
                $zip->addFile($ruta, $detalle->ruta);
            }
        }
        $zip->close();

        return response()->download($zipPath, $plantilla->nombre . '.zip')->deleteFileAfterSend(true);
    }

    public function destroy($id)
    {
        Plantilla::findOrFail($id)->delete();

        return redirect()->route('plantilla.index')->with('success', 'Plantilla eliminada correctamente.');
    }
}
