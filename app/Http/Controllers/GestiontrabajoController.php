<?php

namespace App\Http\Controllers;

use App\Models\Agendamiento;
use App\Models\Usuarios;

class GestiontrabajoController extends Controller
{
    public function index()
    {
        $agendamientos = Agendamiento::with(['presupuesto', 'obra', 'usuario'])->get();
        $usuarios = Usuarios::whereIn('area_id', [2, 4])->get();
        return view('gestiontrabajo.index', compact('agendamientos', 'usuarios'));
    }


}
