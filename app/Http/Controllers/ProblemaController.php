<?php

namespace App\Http\Controllers;

use App\Models\Problema;
use App\Models\ProblemaDetalle;
use App\Models\Solucion;
use App\Models\SolucionDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function show($id)
    {
        $problema = Problema::with([
            'usuario',
            'detalles.usuario',
            'soluciones' => function ($q) {
                $q->with(['usuario', 'detalles.usuario'])->orderBy('orden');
            },
        ])->findOrFail($id);

        return view('problema.show', compact('problema'));
    }

    public function detalle($id)
    {
        $problema = Problema::with(['usuario', 'detalles.usuario'])->findOrFail($id);

        $permisoService = app(\App\Services\PermisoService::class);
        $puedeEditarProblema  = $permisoService->puede('pro', 'editar');
        $puedeAgregarProblema = $permisoService->puede('pro', 'agregar');
        $puedeEliminarProblema = $permisoService->puede('pro', 'eliminar');

        return view('problema.detail_problemas', compact(
            'problema', 'puedeEditarProblema', 'puedeAgregarProblema', 'puedeEliminarProblema'
        ));
    }

    public function updateObservacion(Request $request, $id)
    {
        $request->validate(['observacion' => 'nullable|string']);
        Problema::findOrFail($id)->update(['observacion' => $request->observacion]);
        return back()->with('success', 'Observación guardada correctamente.');
    }

    public function storeFoto(Request $request, $id)
    {
        $request->validate([
            'fotos'   => 'required|array',
            'fotos.*' => 'required|image|max:5120',
        ]);

        Problema::findOrFail($id);

        if (!Storage::exists('public/problemas')) {
            Storage::makeDirectory('public/problemas');
        }

        foreach ($request->file('fotos') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/problemas', $filename);
            ProblemaDetalle::create([
                'problema_id' => $id,
                'foto'        => $filename,
                'usuario_id'  => session('usuario_id'),
                'estado'      => 1,
            ]);
        }

        return back()->with('success', 'Foto(s) cargada(s) correctamente.');
    }

    public function destroyFoto($id)
    {
        $detalle = ProblemaDetalle::findOrFail($id);
        Storage::delete('public/problemas/' . $detalle->foto);
        $detalle->delete();
        return back()->with('success', 'Foto eliminada correctamente.');
    }

    public function detalleSolucion($id)
    {
        $solucion = Solucion::with(['usuario', 'problema', 'detalles.usuario'])->findOrFail($id);

        $permisoService = app(\App\Services\PermisoService::class);
        $puedeEditarSolucion   = $permisoService->puede('sol', 'editar');
        $puedeAgregarSolucion  = $permisoService->puede('sol', 'agregar');
        $puedeEliminarSolucion = $permisoService->puede('sol', 'eliminar');

        return view('problema.detail_soluciones', compact(
            'solucion', 'puedeEditarSolucion', 'puedeAgregarSolucion', 'puedeEliminarSolucion'
        ));
    }

    public function updateObservacionSolucion(Request $request, $id)
    {
        $solucion = Solucion::findOrFail($id);
        if ($solucion->estado == 2) {
            return back()->with('error', 'No se puede editar una solución inactiva.');
        }
        $request->validate(['observacion' => 'nullable|string']);
        $solucion->update(['observacion' => $request->observacion]);
        return back()->with('success', 'Observación guardada correctamente.');
    }

    public function storeFotoSolucion(Request $request, $id)
    {
        $request->validate([
            'fotos'   => 'required|array',
            'fotos.*' => 'required|image|max:5120',
        ]);

        $solucion = Solucion::findOrFail($id);
        if ($solucion->estado == 2) {
            return back()->with('error', 'No se pueden agregar fotos a una solución inactiva.');
        }

        if (!Storage::exists('public/soluciones')) {
            Storage::makeDirectory('public/soluciones');
        }

        foreach ($request->file('fotos') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/soluciones', $filename);
            SolucionDetalle::create([
                'solucion_id' => $id,
                'foto'        => $filename,
                'usuario_id'  => session('usuario_id'),
                'estado'      => 1,
            ]);
        }

        return back()->with('success', 'Foto(s) cargada(s) correctamente.');
    }

    public function destroyFotoSolucion($id)
    {
        $detalle = SolucionDetalle::findOrFail($id);
        Storage::delete('public/soluciones/' . $detalle->foto);
        $detalle->delete();
        return back()->with('success', 'Foto eliminada correctamente.');
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
