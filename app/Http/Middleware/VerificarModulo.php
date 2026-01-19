<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarModulo
{
    public function handle(Request $request, Closure $next, $modulo)
    {
        $modulos = session('modulos_permitidos', []);

        if (!in_array($modulo, $modulos)) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a este módulo.');
        }

        return $next($request);
    }
}
