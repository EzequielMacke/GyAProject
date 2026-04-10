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
    public function index(Request $request, $obra = null)
    {
        $query = PresupuestoAprobado::with('usuario');
        $obraModel = null;
        if ($obra) {
            $obraModel = Obra::findOrFail($obra);
            $query->where('obra_id', $obra);
        }
        $presupuestos = $query->get();
        $estados = config('constantes.estado_de_presupuestos');
        $estados_label = config('constantes.estado_de_presupuestos_btn');
        $tipo_trabajo = config('constantes.tipo_trabajo');
        return view('presupuesto_aprobado.index', compact('presupuestos', 'estados', 'estados_label', 'tipo_trabajo') + ['obra' => $obraModel]);
    }

    public function create($obra = null)
    {
        $obras = Obra::all();
        $selectedObra = null;
        if ($obra) {
            $selectedObra = Obra::find($obra);
        }
        return view('presupuesto_aprobado.create', compact('obras', 'selectedObra', 'obra'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|max:255|unique:presupuesto_aprobados,clave',
            'presupuesto' => 'required|file|mimes:pdf',
            'conformidad' => 'nullable|file|mimes:pdf',
            'ubicacion' => 'required|string|max:255',
            'tipo_trabajo' => 'required',
            'monto_total' => 'nullable|string'
        ]);

        // Fecha de carga: si no viene, usar hoy
        $fecha_carga = $request->fecha_carga ?? now()->toDateString();

        $presupuestoFile = $request->file('presupuesto');
        $presupuestoPath = $presupuestoFile->store('public/presupuestos');
        $presupuestoName = basename($presupuestoPath);
        $conformidadName = null;
        $conformidadPath = $request->file('conformidad') ? $request->file('conformidad')->store('public/conformidades') : null;
        if ($conformidadPath) {
            $conformidadName = basename($conformidadPath);
        }

        $finalPresupuestoName = $presupuestoName;
        if ($conformidadPath) {
            if (!Storage::exists('public/presupuestos')) {
                Storage::makeDirectory('public/presupuestos');
            }
            $mergedPdfName = uniqid() . '.pdf';
            $mergedPdfPath = 'public/presupuestos/' . $mergedPdfName;
            $this->mergePdfs(storage_path('app/' . $presupuestoPath), storage_path('app/' . $conformidadPath), storage_path('app/' . $mergedPdfPath));
            $finalPresupuestoName = $mergedPdfName;
        }

        $monto_total = $request->monto_total ? str_replace('.', '', $request->monto_total) : null;

        $presupuesto = PresupuestoAprobado::create([
            'fecha_carga' => $fecha_carga,
            'usuario_id' => Auth::id(),
            'obra_id' => $request->obra_id ?? null,
            'presupuesto' => $finalPresupuestoName,
            'ubicacion' => $request->ubicacion,
            'clave' => $request->clave,
            'monto_total' => $monto_total,
            'observacion' => $request->observacion,
            'estado' => 1,
            'tipo_trabajo' => $request->tipo_trabajo,
        ]);

        return redirect()->route('presupuesto_aprobado.index', $request->obra_id)->with('success', 'Presupuesto aprobado guardado exitosamente.');
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
        $obra = $presupuesto->obra_id ? Obra::findOrFail($presupuesto->obra_id) : null;
        return view('presupuesto_aprobado.edit', compact('presupuesto', 'obra'));
    }
    public function update(Request $request, $id)
    {
        $presupuesto = PresupuestoAprobado::findOrFail($id);
        $request->validate([
            'clave' => 'required|string|max:255|unique:presupuesto_aprobados,clave,' . $presupuesto->id,
            'presupuesto' => 'nullable|file|mimes:pdf',
            'ubicacion' => 'required|string|max:255',
            'tipo_trabajo' => 'required',
            'monto_total' => 'nullable|string'
        ]);

        $presupuesto->ubicacion = $request->ubicacion;
        $presupuesto->clave = $request->clave;
        $presupuesto->tipo_trabajo = $request->tipo_trabajo;
        $presupuesto->monto_total = $request->monto_total ? str_replace('.', '', $request->monto_total) : null;
        $presupuesto->observacion = $request->observacion;

        $presupuestoPath = 'public/presupuestos/' . $presupuesto->presupuesto;
        $conformidadPath = $presupuesto->conformidad ? 'public/conformidades/' . $presupuesto->conformidad : null;

        if ($request->hasFile('presupuesto')) {
            $presupuestoFile = $request->file('presupuesto');
            $presupuestoPath = $presupuestoFile->store('public/presupuestos');
            $presupuestoName = basename($presupuestoPath);
        }

        if ($request->hasFile('conformidad')) {
            $conformidadPath = $request->file('conformidad')->store('public/conformidades');
            $conformidadName = basename($conformidadPath);
        }

        $finalPresupuestoName = $presupuestoName ?? $presupuesto->presupuesto;
        if ($conformidadPath) {
            if (!Storage::exists('public/presupuestos')) {
                Storage::makeDirectory('public/presupuestos');
            }
            $mergedPdfName = uniqid() . '.pdf';
            $mergedPdfPath = 'public/presupuestos/' . $mergedPdfName;
            $this->mergePdfs(storage_path('app/' . $presupuestoPath), storage_path('app/' . $conformidadPath), storage_path('app/' . $mergedPdfPath));
            $finalPresupuestoName = $mergedPdfName;
        }

        $presupuesto->presupuesto = $finalPresupuestoName;
        $presupuesto->save();

        return redirect()->route('presupuesto_aprobado.index', $presupuesto->obra_id)->with('success', 'Presupuesto actualizado correctamente');
    }

}
