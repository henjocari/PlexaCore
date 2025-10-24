<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 🚨 Carga la relación de Rol
        $usuario = Usuario::where('email', $credentials['email'])
                          ->with('role') 
                          ->first();

        // 1️⃣ Verifica si el usuario existe
        if (!$usuario) {
            throw ValidationException::withMessages([
                'email' => ['Correo o contraseña incorrectos.'],
            ]);
        }

        // 2️⃣ Verifica si el usuario está bloqueado
        if ($usuario->estado == 0) {
            throw ValidationException::withMessages([
                'email' => ['Usuario bloqueado. Favor comunicarse con el administrador.'],
            ]);
        }
        
        // 3️⃣ VERIFICACIÓN DE CONTRASEÑA CORREGIDA
        $passwordMatch = false;

        if ($usuario->contraseña === $credentials['password']) {
            $passwordMatch = true;
        } 
        
        if ($passwordMatch) {
            
            // Inicia sesión
            Auth::login($usuario);
            $request->session()->regenerate();

            // Redirige al inicio
            return redirect()->intended(route('index'));
        }

        // 5️⃣ Si la contraseña no coincide 
        throw ValidationException::withMessages([
            'email' => ['Correo o contraseña incorrectos.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
