<?php

namespace App\Http\Controllers;

use App\Models\Problema;
use App\Models\Solucion;
use Illuminate\Http\Request;

class ProblemaController extends Controller
{
    public function index()
    {
        $problemas = Problema::with(['usuario', 'soluciones' => function ($q) {
                $q->with('usuario')->orderBy('orden');
            }])
            ->where('estado', 1)
            ->orderBy('orden')
            ->get();

        $puedeAgregarProblema   = app(\App\Services\PermisoService::class)->puede('pro', 'agregar');
        $puedeAgregarSolucion   = app(\App\Services\PermisoService::class)->puede('sol', 'agregar');
        $puedeEditarProblema    = app(\App\Services\PermisoService::class)->puede('pro', 'editar');
        $puedeEditarSolucion    = app(\App\Services\PermisoService::class)->puede('sol', 'editar');
        $puedeEliminarProblema  = app(\App\Services\PermisoService::class)->puede('pro', 'eliminar');
        $puedeEliminarSolucion  = app(\App\Services\PermisoService::class)->puede('sol', 'eliminar');

        return view('problema.index', compact(
            'problemas', 'puedeAgregarProblema', 'puedeAgregarSolucion',
            'puedeEditarProblema', 'puedeEditarSolucion',
            'puedeEliminarProblema', 'puedeEliminarSolucion'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
        ]);

        Problema::create([
            'descripcion' => $request->descripcion,
            'usuario_id'  => session('usuario_id'),
            'stamp'       => now(),
            'avance'      => 0,
            'estado'      => 1,
            'orden'       => (Problema::max('orden') ?? 0) + 1,
        ]);

        return back()->with('success', 'Problema registrado correctamente.');
    }

    public function storeSolucion(Request $request, $problema_id)
    {
        $request->validate([
            'descripcion' => 'required|string',
        ]);

        Problema::findOrFail($problema_id);

        Solucion::create([
            'problema_id' => $problema_id,
            'descripcion' => $request->descripcion,
            'usuario_id'  => session('usuario_id'),
            'stamp'       => now(),
            'avance'      => 0,
            'estado'      => 1,
            'orden'       => (Solucion::where('problema_id', $problema_id)->max('orden') ?? 0) + 1,
        ]);

        return back()->with('success', 'Solución agregada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string',
        ]);

        $problema = Problema::findOrFail($id);
        $problema->update(['descripcion' => $request->descripcion]);

        return back()->with('success', 'Problema actualizado correctamente.');
    }

    public function updateSolucion(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string',
        ]);

        $solucion = Solucion::findOrFail($id);
        $solucion->update(['descripcion' => $request->descripcion]);

        return back()->with('success', 'Solución actualizada correctamente.');
    }

    public function destroySolucion($id)
    {
        Solucion::findOrFail($id)->update(['estado' => 2]);
        return back()->with('success', 'Solución desactivada.');
    }

    public function restaurarSolucion($id)
    {
        Solucion::findOrFail($id)->update(['estado' => 1]);
        return back()->with('success', 'Solución restaurada.');
    }

    public function reordenarProblemas(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer'])['ids'];
        foreach ($ids as $orden => $id) {
            Problema::where('id', $id)->update(['orden' => $orden + 1]);
        }
        return response()->json(['ok' => true]);
    }

    public function reordenarSoluciones(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer'])['ids'];
        foreach ($ids as $orden => $id) {
            Solucion::where('id', $id)->update(['orden' => $orden + 1]);
        }
        return response()->json(['ok' => true]);
    }

    public function destroy($id)
    {
        $problema = Problema::findOrFail($id);
        $problema->update(['estado' => 2]);

        return back()->with('success', 'Problema eliminado correctamente.');
    }
}
