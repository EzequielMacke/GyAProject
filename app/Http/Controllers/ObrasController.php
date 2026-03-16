<?php

namespace App\Http\Controllers;

use App\Models\Directorio;
use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObrasController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        // Obtener solo las obras donde el usuario está en el directorio
        $obras = Obra::whereHas('directorios', function($query) use ($userId) {
            $query->where('usuario_id', $userId);
        })
        ->where('estado', 1)
        ->with(['presupuestos', 'directorios', 'contactos', 'pedidosParaObra'])
        ->get();
        $obrasData = $obras->reverse()->values()->map(function($obra) {
            $lat = null; $lng = null;
            if (!empty($obra->direccion) && preg_match('/maps\?q=([-0-9.]+),([-0-9.]+)/', $obra->direccion, $m)) {
                $lat = $m[1]; $lng = $m[2];
            }
            return [
                'id'                 => $obra->id,
                'nombre'             => $obra->nombre,
                'url'                => route('obras.show', $obra->id),
                'map_url'            => $lat ? "https://maps.google.com/maps?q={$lat},{$lng}&z=15&output=embed" : null,
                'created_at_fmt'     => $obra->created_at->format('d/m/Y H:i'),
                'created_at_human'   => $obra->created_at->diffForHumans(),
                'updated_at_fmt'     => $obra->updated_at->format('d/m/Y H:i'),
                'updated_at_human'   => $obra->updated_at->diffForHumans(),
                'presupuestos_count' => $obra->presupuestos->count(),
                'pedidos_count'      => $obra->pedidosParaObra->count(),
                'directorios_count'  => $obra->directorios->count(),
                'search'             => strtolower(
                    $obra->nombre . ' ' .
                    $obra->presupuestos->pluck('clave')->implode(' ') . ' ' .
                    $obra->presupuestos->pluck('orden_trabajo')->implode(' ') . ' ' .
                    $obra->contactos->pluck('nombre')->implode(' ')
                ),
                'search_fields'      => [
                    'nombre'      => strtolower($obra->nombre),
                    'contactos'   => strtolower($obra->contactos->pluck('nombre')->implode(' ')),
                    'ordenes'     => strtolower($obra->presupuestos->pluck('orden_trabajo')->implode(' ')),
                    'presupuestos'=> strtolower($obra->presupuestos->pluck('clave')->implode(' ')),
                ],
            ];
        });

        $estados = config('constantes.estado_obras');
        $estados_pre = config('constantes.estado_de_presupuestos');
        $presupuestos = PresupuestoAprobado::all();
        $tipo_trabajo = config('constantes.tipo_trabajo');
        return view('obras.index', compact('obras', 'obrasData', 'estados','presupuestos','tipo_trabajo','estados_pre'));
    }

    public function create()
    {
        $obras = Obra::all();
        return view('obras.create', compact('obras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:obras,nombre',
        ]);

        $obra = Obra::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'fecha_carga' => now(),
            'usuario_id' => Auth::id(),
            'observacion' => $request->observacion,
            'estado' => 1,
        ]);

        Directorio::create([
            'obra_id' => $obra->id,
            'usuario_id' => 1,
            'fecha' => now(),
        ]);

        if (Auth::id() !== 1) {
            Directorio::create([
                'obra_id' => $obra->id,
                'usuario_id' => Auth::id(),
                'fecha' => now(),
            ]);
        }

        return redirect()->route('obras.index')->with('success', 'Obra creada exitosamente.');
    }

    public function edit($id)
    {
        $obra = Obra::findOrFail($id);
        return view('obras.edit', compact('obra'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:obras,nombre,' . $id,
        ]);

        $obra = Obra::findOrFail($id);
        $obra->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'observacion'=> $request->observacion,
        ]);
        return redirect()->route('obras.index')->with('success', 'Obra actualizada exitosamente.');
    }
    
    public function show($id)
    {
        $obra = Obra::findOrFail($id);
        return view('obras.show', compact('obra'));
    }


    public function destroy($id)
    {
        $obra = Obra::findOrFail($id);
        $obra->estado = 2;
        $obra->save();
        return redirect()->route('obras.index')->with('success', 'Obra eliminada correctamente.');
    }
    

}
