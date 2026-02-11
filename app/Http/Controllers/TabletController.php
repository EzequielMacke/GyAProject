<?php

namespace App\Http\Controllers;

use App\Models\Tableta;
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
        return view('tablet.assign', compact('clave'));
    }
    
    
}
