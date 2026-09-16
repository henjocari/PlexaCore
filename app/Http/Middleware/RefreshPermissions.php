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
            
            $usuario = Auth::user();
            $rolId   = $usuario->rol; // campo de la tabla usuarios que apunta a permisos_roles

            if ($rolId) {
                // ✅ ÚNICA FUENTE: tabla permisos_modulos
                $modulos = \App\Models\PermisosModulo::where('roles', $rolId)
                    ->pluck('paginas')
                    ->toArray();

                Session::put('modulos_permitidos', $modulos);
            } else {
                Session::forget('modulos_permitidos');
            }
        }

        return $next($request);
    }
}