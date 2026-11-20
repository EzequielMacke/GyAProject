<?php

namespace App\Http\Controllers;

class SalidaController extends Controller
{
    public function index()
    {
        return view('salidas.index');
    }

    public function create()
    {
        return view('salidas.create');
    }
}
