<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RefreshPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            
            // 🚨 PUNTO DE DEBUG CRÍTICO: Muestra el usuario con sus permisos cargados
            $usuario = Auth::user()->load('role.modulos', 'role.permisos');

            if ($usuario->role) {
                // 1. Obtener la lista de módulos
                $modulos = $usuario->role->modulos->pluck('paginas')->toArray();
                
                // 2. Obtener la lista de permisos (Usando 'permiso' como columna)
                $permisos = $usuario->role->permisos->pluck('permiso')->toArray();

                // 3. Sobrescribir las variables de sesión con los datos frescos
                Session::put('modulos_permitidos', $modulos);
                Session::put('permisos_permitidos', $permisos);

            } else {
                // Si el usuario no tiene rol, limpiar los permisos de la sesión
                Session::forget(['modulos_permitidos', 'permisos_permitidos']);
            }
        }

        return $next($request);
    }
}
