<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use App\Models\Roles;

class UsuarioController extends Controller
{
      public function index()
    {
        $usuarios = Usuario::with('rolInfo')->get();
        $roles = Roles::all();

        return view('usuarios', compact('usuarios', 'roles'));
    }

    // Crear usuario nuevo
    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|numeric|unique:usuarios,cedula',
            'Nombre' => 'required|string|max:50',
            'Apellido' => 'required|string|max:50',
            'email' => 'nullable|email|max:250',
            'cel' => 'nullable|string|max:30',
            'contraseña' => 'required|string|min:6',
            'rol' => 'required|integer',
            'estado' => 'required|boolean'
        ]);

        Usuario::create([
            'cedula' => $request->cedula,
            'Nombre' => $request->Nombre,
            'Apellido' => $request->Apellido,
            'email' => $request->email,
            'cel' => $request->cel,
            // 🔐 Encriptamos la contraseña para usar autenticación
            'contraseña' => Hash::make($request->contraseña),
            'rol' => $request->rol,
            'estado' => $request->estado,
        ]);

        return redirect()->back()->with('success', 'Usuario creado correctamente.');
    }

    // Editar usuario existente
    public function update(Request $request, $cedula)
    {
        $usuario = Usuario::findOrFail($cedula);

        $request->validate([
            'Nombre' => 'required|string|max:50',
            'Apellido' => 'required|string|max:50',
            'email' => 'nullable|email|max:250',
            'cel' => 'nullable|string|max:30',
            'rol' => 'required|integer',
            'estado' => 'required|boolean',
        ]);

        $usuario->update([
            'Nombre' => $request->Nombre,
            'Apellido' => $request->Apellido,
            'email' => $request->email,
            'cel' => $request->cel,
            'rol' => $request->rol,
            'estado' => $request->estado,
        ]);

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }

    // Cambiar estado (activar/inactivar)
    public function toggle($cedula)
    {
        $usuario = Usuario::where('cedula', $cedula)->firstOrFail();
    
        // Evitar que un usuario se desactive a sí mismo
        if (auth()->user()->cedula == $usuario->cedula && $request->estado == 0) {
            return back()->with('error', 'No puedes inactivar tu propio usuario.');
        }       
    
        // Cambiar el estado
        $usuario->estado = !$usuario->estado;
        $usuario->save();
    
        return back()->with('success', 'El estado del usuario ha sido actualizado.');
    }
    
}
