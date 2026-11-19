<?php

namespace App\Console\Commands;

use App\Support\SincronizarModulos;
use Illuminate\Console\Command;

class SincronizarModulosCommand extends Command
{
    protected $signature = 'modulos:sincronizar';

    protected $description = 'Registra en permisos_modulos (Super Administrador) los módulos usados en routes/web.php';

    public function handle(): int
    {
        $modulos = SincronizarModulos::modulosEnRutas();
        $this->info('Módulos encontrados en las rutas: ' . count($modulos));

        $nuevos = SincronizarModulos::ejecutar(true);

        if ($nuevos) {
            $this->info('Agregados para el Super Administrador:');
            foreach ($nuevos as $m) {
                $this->line("  + {$m}");
            }
        } else {
            $this->info('No había módulos nuevos: todo está al día.');
        }

        return self::SUCCESS;
    }
}
