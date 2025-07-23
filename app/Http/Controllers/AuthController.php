<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('welcome');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'password' => 'required',
        ]);

        $usuario = Usuarios::where('nombre', $request->nombre)->first();

        if (!$usuario) {
            return redirect()->back()->withErrors([
                'nombre' => 'El usuario no existe.',
            ])->withInput($request->only('nombre'));
        }

        if (!Hash::check($request->password, $usuario->contraseña)) {
            return redirect()->back()->withErrors([
                'password' => 'La contraseña no es válida.',
            ])->withInput($request->only('nombre'));
        }

        Auth::login($usuario);

        // Guardar información en la sesión
        session(['usuario_nombre' => $usuario->nombre]);
        session(['usuario_area' => $usuario->area->descripcion]);
        session(['usuario_area_id' => $usuario->area_id]);
        session(['usuario_id' => $usuario->id]);


        return view('home')->with('success', 'Inicio de sesión exitoso.');
    }

    public function logout()
    {
        return redirect()->route('welcome');
    }
}
