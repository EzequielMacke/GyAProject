<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTc;
use App\Models\ObraTc;
use App\Models\PachometriaTc;
use App\Services\PermisoService;
use Illuminate\Http\Request;

class PachometriaTcController extends Controller
{
    public function index(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        // Lo que necesita el frontend para reconstruir cada tarjeta.
        $pachometrias = PachometriaTc::where('obra_tc_id', $obraTc->id)
            ->orderBy('orden')
            ->orderBy('id')
            ->get()
            ->map(fn (PachometriaTc $p) => [
                'id' => $p->id,
                'datos' => $p->datos ?? [],
            ])
            ->values();

        $permisoService = app(PermisoService::class);
        $puedeAgregar = $permisoService->puede('pch_tc', 'agregar');
        $puedeEditar = $permisoService->puede('pch_tc', 'editar');
        $puedeEliminar = $permisoService->puede('pch_tc', 'eliminar');

        return view('pachometria_tc.index', compact('obraTc', 'pachometrias', 'puedeAgregar', 'puedeEditar', 'puedeEliminar'));
    }

    // Se llama apenas se agrega una tarjeta, para tener su id y poder
    // ir guardando los cambios con update().
    public function store(Request $request, ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $data = $this->validar($request);

        $pachometria = PachometriaTc::create($this->columnas($data) + [
            'obra_tc_id' => $obraTc->id,
            'usuario_id' => session('usuario_id'),
        ]);

        return response()->json(['id' => $pachometria->id], 201);
    }

    // Autoguardado: recibe la tarjeta completa y la reemplaza.
    public function update(Request $request, ObraTc $obraTc, PachometriaTc $pachometria)
    {
        if (! $this->tieneAccesoAObra($obraTc) || (int) $pachometria->obra_tc_id !== (int) $obraTc->id) {
            return response()->json(['message' => 'No tenés acceso a esta pachometría.'], 403);
        }

        $pachometria->update($this->columnas($this->validar($request)));

        return response()->json(['ok' => true]);
    }

    public function destroy(ObraTc $obraTc, PachometriaTc $pachometria)
    {
        if (! $this->tieneAccesoAObra($obraTc) || (int) $pachometria->obra_tc_id !== (int) $obraTc->id) {
            return response()->json(['message' => 'No tenés acceso a esta pachometría.'], 403);
        }

        $pachometria->delete();

        return response()->json(['ok' => true]);
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'datos' => 'present|array',
            'datos.campos' => 'nullable|array',
            'datos.campos.*' => 'nullable|string|max:255',
            'datos.listas' => 'nullable|array',
            'datos.listas.*' => 'array',
            'datos.listas.*.*' => 'array',
            'datos.listas.*.*.*' => 'nullable|string|max:50',
        ]);
    }

    /* Además del JSON completo, se copian a columnas propias los datos
       que sirven para ordenar y listar sin abrir el JSON. */
    private function columnas(array $data): array
    {
        $campos = $data['datos']['campos'] ?? [];
        $entero = fn ($valor) => is_numeric($valor) && (int) $valor > 0 ? (int) $valor : null;
        $tipo = $campos['tipo'] ?? null;

        return [
            'orden' => $entero($campos['idx'] ?? null),
            'numero' => $entero($campos['numero'] ?? null),
            'tipo' => in_array($tipo, ['viga', 'pilar', 'losa'], true) ? $tipo : null,
            'elemento' => isset($campos['elemento']) ? mb_substr(trim($campos['elemento']), 0, 60) ?: null : null,
            'datos' => $data['datos'],
        ];
    }

    private function tieneAccesoAObra(ObraTc $obraTc): bool
    {
        return DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', session('usuario_id'))
            ->exists();
    }
}
