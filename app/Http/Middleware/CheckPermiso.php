<?php

namespace App\Http\Middleware;

use App\Models\Modulo;
use App\Models\Permiso;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermiso
{
    /**
     * Verifica si el área del usuario tiene permiso sobre un módulo y acción.
     *
     * Uso en rutas: ->middleware('permiso:nombre_modulo,accion')
     * Acciones: ver | agregar | editar | eliminar  (default: ver)
     */
    public function handle(Request $request, Closure $next, string $modulo, string $accion = 'ver'): Response
    {
        $areaId = session('usuario_area_id');

        if (!$areaId) {
            return $next($request);
        }

        $moduloId = Modulo::where('nombre', $modulo)->value('id');

        $tiene = $moduloId && Permiso::where('area_id', $areaId)
            ->where('modulo_id', $moduloId)
            ->where($accion, 1)
            ->exists();

        if (!$tiene) {
            return redirect()->route('home')
                ->with('sin_permiso', 'No tenés permiso para acceder a esa sección.');
        }

        return $next($request);
    }
}
