<?php

namespace App\Support;

use App\Http\Middleware\VerificarModulo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/**
 * Registra automáticamente en `permisos_modulos` los módulos declarados en routes/web.php.
 *
 * Basta con proteger una ruta así:
 *     ->middleware(VerificarModulo::class . ':Nombre del Módulo')
 * y el módulo "Nombre del Módulo" se inserta para el Super Administrador (rol 1),
 * quedando disponible en la pantalla de Usuarios para asignarlo a los demás roles.
 *
 * Solo agrega, nunca borra. Para no consultar la BD en cada petición, guarda una
 * huella de la lista de módulos y solo vuelve a sincronizar cuando esa lista cambia.
 */
class SincronizarModulos
{
    /** Rol que recibe automáticamente cada módulo nuevo. */
    public const ROL_SUPER_ADMIN = 1;

    private const CACHE_KEY = 'permisos_modulos_rutas_hash';

    /**
     * Nombres de módulo usados en las rutas (VerificarModulo:Nombre).
     */
    public static function modulosEnRutas(): array
    {
        $prefijo = VerificarModulo::class . ':';
        $modulos = [];

        foreach (Route::getRoutes() as $ruta) {
            foreach ((array) $ruta->middleware() as $mw) {
                if (is_string($mw) && str_starts_with($mw, $prefijo)) {
                    $nombre = trim(substr($mw, strlen($prefijo)));
                    if ($nombre !== '') {
                        $modulos[$nombre] = true;
                    }
                }
            }
        }

        $modulos = array_keys($modulos);
        sort($modulos);

        return $modulos;
    }

    /**
     * Inserta los módulos que falten. Devuelve los nombres agregados.
     *
     * @param bool $forzar true = ignora la huella guardada y revisa la BD igual.
     */
    public static function ejecutar(bool $forzar = false): array
    {
        $modulos = self::modulosEnRutas();
        $huella  = md5(implode('|', $modulos));
        $cache   = Cache::store('file');

        if (!$forzar && $cache->get(self::CACHE_KEY) === $huella) {
            return [];
        }

        if (!Schema::hasTable('permisos_modulos')) {
            return [];
        }

        $existentes = DB::table('permisos_modulos')
            ->where('roles', self::ROL_SUPER_ADMIN)
            ->pluck('paginas')
            ->all();

        $nuevos = array_values(array_diff($modulos, $existentes));

        if ($nuevos) {
            DB::table('permisos_modulos')->insert(array_map(
                fn ($m) => ['roles' => self::ROL_SUPER_ADMIN, 'paginas' => $m],
                $nuevos
            ));
            Log::info('Módulos registrados automáticamente en permisos_modulos: ' . implode(', ', $nuevos));
        }

        $cache->forever(self::CACHE_KEY, $huella);

        return $nuevos;
    }
}
