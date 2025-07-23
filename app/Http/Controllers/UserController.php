<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Usuarios::create([
            'nombre' => $request->nombre,
            'password' => Hash::make($request->password),
            'estado' => $request->estado,
            'area_id' => $request->area_id,
        ]);

        return redirect()->route('home');
    }
}
