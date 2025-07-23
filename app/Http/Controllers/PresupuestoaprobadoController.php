<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

class PresupuestoaprobadoController extends Controller
{
    public function index()
    {
        $presupuestos = PresupuestoAprobado::with('usuario')->get();
        $estados = config('constantes.estado_de_presupuestos');
        $estados_label = config('constantes.estado_de_presupuestos_btn');
        $tipo_trabajo = config('constantes.tipo_trabajo');
        return view('presupuesto_aprobado.index', compact('presupuestos', 'estados', 'estados_label','tipo_trabajo'));
    }
    public function create()
    {
        $obras = Obra::all();
        return view('presupuesto_aprobado.create', compact('obras'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|max:255|unique:presupuesto_aprobados,clave',
        ]);
        $presupuestoPath = $request->file('presupuesto')->store('public/presupuestos');
        $conformidadPath = $request->file('conformidad') ? $request->file('conformidad')->store('public/conformidades') : null;

        $finalPresupuestoPath = $presupuestoPath;
        if ($conformidadPath) {
            if (!Storage::exists('public/presupuestos')) {
                Storage::makeDirectory('public/presupuestos');
            }

            $mergedPdfPath = 'public/presupuestos/' . uniqid() . '.pdf';
            $this->mergePdfs(storage_path('app/' . $presupuestoPath), storage_path('app/' . $conformidadPath), storage_path('app/' . $mergedPdfPath));
            $finalPresupuestoPath = $mergedPdfPath;
        }

        $monto_total = str_replace('.', '', $request->monto_total);
        PresupuestoAprobado::create([
            'fecha_carga' => $request->fecha_carga,
            'usuario_id' => Auth::id(),
            'obra_id' => $request->obra_id,
            'presupuesto' => $finalPresupuestoPath,
            'ubicacion' => $request->ubicacion,
            'clave' => $request->clave,
            'monto_total' => $monto_total,
            'observacion' => $request->observacion,
            'estado' => 1,
            'tipo_trabajo' => $request->tipo_trabajo,
        ]);

        return redirect()->route('presupuesto_aprobado.index')->with('success', 'Presupuesto aprobado guardado exitosamente.');
    }
    private function mergePdfs($presupuestoPath, $conformidadPath, $outputPath)
    {
        $pdf = new Fpdi();

        // Add the first PDF
        $pageCount = $pdf->setSourceFile($presupuestoPath);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);
        }

        // Add the second PDF
        $pageCount = $pdf->setSourceFile($conformidadPath);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);
        }

        $pdf->Output($outputPath, 'F');
    }

    public function edit($id)
    {
        $presupuesto = PresupuestoAprobado::findOrFail($id);

        if ($presupuesto->estado == 2) {
            return redirect('/home')->with('error', 'No se puede editar un presupuesto aprobado.');
        }

        return view('presupuesto_aprobado.edit', compact('presupuesto'));
    }
    public function update(Request $request, $id)
    {
        $monto_total = str_replace('.', '', $request->monto_total);
        $presupuesto = PresupuestoAprobado::findOrFail($id);
        $presupuesto->ubicacion = $request->ubicacion;
        $presupuesto->clave = $request->clave;
        $presupuesto->tipo_trabajo = $request->tipo_trabajo;
        $presupuesto->monto_total = $monto_total;
        $presupuesto->observacion = $request->observacion;

        $presupuestoPath = $presupuesto->presupuesto;
        $conformidadPath = $presupuesto->conformidad;

        if ($request->hasFile('presupuesto')) {
            $presupuestoPath = $request->file('presupuesto')->store('public/presupuestos');
        }

        if ($request->hasFile('conformidad')) {
            $conformidadPath = $request->file('conformidad')->store('public/conformidades');
        }

        $finalPresupuestoPath = $presupuestoPath;
        if ($conformidadPath) {
            if (!Storage::exists('public/presupuestos')) {
                Storage::makeDirectory('public/presupuestos');
            }

            $mergedPdfPath = 'public/presupuestos/' . uniqid() . '.pdf';
            $this->mergePdfs(storage_path('app/' . $presupuestoPath), storage_path('app/' . $conformidadPath), storage_path('app/' . $mergedPdfPath));
            $finalPresupuestoPath = $mergedPdfPath;
        }

        $presupuesto->presupuesto = $finalPresupuestoPath;
        $presupuesto->save();

        return redirect()->route('presupuesto_aprobado.index')->with('success', 'Presupuesto actualizado correctamente');
    }

}
