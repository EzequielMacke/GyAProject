<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    public function show()
    {
        return view('mantenimiento.show');
    }
}
