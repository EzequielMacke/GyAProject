<?php

namespace App\Http\Controllers;

use App\Models\Tableta;
use App\Models\TabletaUso;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class TabletController extends Controller
{
    public function index()
    {
        $tabletas = Tableta::all();
        return view('tablet.index', compact('tabletas'));
    }

        public function create()
    {
        return view('tablet.create');
    }

    public function store(Request $request)
    {   
        Tableta::create([
            'clave' => $request->input('clave'),
            'nombre' => $request->input('nombre'),
            'modelo' => $request->input('modelo'),
            'serie' => $request->input('serie'),
            'sim' => $request->input('sim'),
            'estado' => 1,
            'observacion' => $request->input('observacion'),
        ]);
        return redirect()->route('tabletas.index')->with('success', 'Tableta agregada correctamente.');
    }

    public function generarQrs()
    {
        $tabletas = Tableta::whereNull('codigo_qr')->get();
        foreach ($tabletas as $tableta) {
            // Generar la URL completa para el QR
            $baseUrl = config('app.url') ?? request()->getSchemeAndHttpHost();
            $qr = $baseUrl . '/tabletas/assign/' . ($tableta->clave ?? $tableta->id);
            $tableta->codigo_qr = $qr;
            $tableta->save();
        }
        $qrs = Tableta::all();
        return view('tablet.generate',compact('qrs'));
    }

    public function assignShow($clave)
    {
        $tableta = Tableta::where('clave', $clave)->firstOrFail();
        $ultimoUso = TabletaUso::where('tableta_id', $tableta->id)
            ->orderBy('fecha_retiro', 'desc')
            ->first();
        if ($ultimoUso && !$ultimoUso->fecha_devolucion) {
            $usuarioRetiro = Usuarios::find($ultimoUso->usuario_id);
            return view('tablet.return', compact('tableta', 'usuarioRetiro'));
        } else {
            $usuarios = Usuarios::where('estado', 1)->get();
            return view('tablet.assign', compact('tableta', 'usuarios'));
        }
    }

    public function assignRetiro(Request $request, $clave)
    {
        $tableta = Tableta::where('clave', $clave)->firstOrFail();
        $usuarioId = $request->input('usuario');
        if (!$usuarioId) {
            return back()->withErrors(['usuario' => 'Debe seleccionar un usuario.']);
        }
        // Registrar el retiro
        $tabletaUso = new TabletaUso();
        $tabletaUso->tableta_id = $tableta->id;
        $tabletaUso->usuario_id = $usuarioId;
        $tabletaUso->fecha_retiro = now();
        $tabletaUso->save();
        return redirect()->route('tabletas.thanks')->with('success', 'Retiro registrado correctamente.');
    }

    public function devolucion(Request $request, $clave)
    {
        $tableta = Tableta::where('clave', $clave)->firstOrFail();
        $ultimoUso = TabletaUso::where('tableta_id', $tableta->id)
            ->orderBy('fecha_retiro', 'desc')
            ->first();
        if ($ultimoUso && !$ultimoUso->fecha_devolucion) {
            $ultimoUso->fecha_devolucion = now();
            $ultimoUso->save();
            return redirect()->route('tabletas.thanks')->with('success', 'Devolución registrada correctamente.');
        } else {
            return back()->withErrors(['devolucion' => 'No hay retiro pendiente para esta tableta.']);
        }
    }

    public function thanks()
    {
        return view('tablet.thanks');
    }
}
