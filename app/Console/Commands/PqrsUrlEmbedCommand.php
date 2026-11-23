<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Genera la URL del PQRS embebido para un NIT (útil para pruebas y para
 * mostrarle a Silogtran cómo debe armarla).
 *
 *   php artisan pqrs:url-embed 900123456
 *   php artisan pqrs:url-embed 900123456 --horas=8
 */
class PqrsUrlEmbedCommand extends Command
{
    protected $signature = 'pqrs:url-embed {nit : NIT del cliente} {--horas= : Horas de vigencia del enlace (opcional)}';

    protected $description = 'Genera la URL (firmada si hay SILOGTRAN_EMBED_SECRET) del PQRS embebido para un NIT';

    public function handle(): int
    {
        $nit     = preg_replace('/[^0-9\-]/', '', (string) $this->argument('nit'));
        $secreto = (string) config('silogtran.embed_secret');
        $params  = ['nit' => $nit];

        if ($this->option('horas')) {
            $params['exp'] = (string) (time() + (int) $this->option('horas') * 3600);
        }

        if ($secreto !== '') {
            $base = isset($params['exp']) ? "{$nit}|{$params['exp']}" : $nit;
            $params['firma'] = hash_hmac('sha256', $base, $secreto);
        } else {
            $this->warn('SILOGTRAN_EMBED_SECRET está vacío: la URL funciona solo con el NIT (no recomendado en producción).');
        }

        $this->line(route('silogtran.pqrs.embed', $params));

        return self::SUCCESS;
    }
}
