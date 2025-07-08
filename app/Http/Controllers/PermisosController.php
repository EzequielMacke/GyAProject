<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Modulo;
use App\Models\Permiso;
use Illuminate\Http\Request;

class PermisosController extends Controller
{
    public function index()
    {
        $permisos = Area::all();
        return view('permisos.index', compact('permisos'));
    }
    public function edit($id)
    {
        $area = Area::findOrFail($id);
        $modulos = Modulo::all();
        $permisos = Permiso::where('area_id', $id)->get()->keyBy('modulo_id');
        return view('permisos.edit', compact('area', 'modulos', 'permisos'));
    }
    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);
        foreach ($request->input('permisos', []) as $modulo_id => $permisos) {
            $permiso = Permiso::firstOrNew(['area_id' => $id, 'modulo_id' => $modulo_id]);
            $permiso->ver = isset($permisos['ver']) ? 1 : 2;
            $permiso->agregar = isset($permisos['agregar']) ? 1 : 2;
            $permiso->editar = isset($permisos['editar']) ? 1 : 2;
            $permiso->eliminar = isset($permisos['eliminar']) ? 1 : 2;
            $permiso->save();
        }
        $modulos = Modulo::all();
        foreach ($modulos as $modulo) {
            if (!isset($request->input('permisos')[$modulo->id])) {
                $permiso = Permiso::firstOrNew(['area_id' => $id, 'modulo_id' => $modulo->id]);
                $permiso->ver = 2;
                $permiso->agregar = 2;
                $permiso->editar = 2;
                $permiso->eliminar = 2;
                $permiso->save();
            }
        }
        return redirect()->route('permisos.index')->with('success', 'Permisos actualizados exitosamente.');
    }
}
