<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarModulo
{
    /**
     * Bloquea la ruta si el rol no tiene el módulo permitido.
     * Uso: ->middleware(VerificarModulo::class . ':Nombre Modulo')
     */
    public function handle(Request $request, Closure $next, $modulo)
    {
        $permitidos = session('modulos_permitidos', []);

        if (!in_array($modulo, $permitidos)) {
            return redirect()->route('index')
                ->with('error', 'No tienes permiso para acceder al módulo: ' . $modulo);
        }

        return $next($request);
    }
}