<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use Illuminate\Http\Request;

class ObraInfoController extends Controller
{
    public function show($id)
    {
        $obra = Obra::with([
            'usuario',
            'presupuestos.facturasVenta.recibosVenta',
            'presupuestos.usuario',
        ])->findOrFail($id);

        $estados      = config('constantes.estado_de_presupuestos');
        $estados_btn  = config('constantes.estado_de_presupuestos_btn');
        $tipos        = config('constantes.tipo_trabajo');
        $estadosObra  = config('constantes.estado_obras');

        return view('datos_obra.informacion', compact('obra', 'estados', 'estados_btn', 'tipos', 'estadosObra'));
    }
}
