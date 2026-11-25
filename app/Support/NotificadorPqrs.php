<?php

namespace App\Support;

use App\Mail\PqrsMail;
use App\Models\SilogtranPqrs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Envío de todos los correos del PQRS en un solo lugar:
 *  - radicada():    aviso al buzón interno (PQRS_MAIL_TO) + acuse al solicitante
 *  - actualizada(): aviso al solicitante cuando cambia el estado o la respuesta
 *
 * Nunca lanza excepciones (la PQRS ya quedó guardada), pero deja registro de
 * cada envío en el log y expone los fallos para mostrarlos en pantalla.
 */
class NotificadorPqrs
{
    /** @var array<int, array{a: string, que: string}> */
    public array $enviados = [];

    /** @var array<int, array{a: string, que: string, error: string}> */
    public array $fallos = [];

    private function __construct(private SilogtranPqrs $pqrs) {}

    public static function radicada(SilogtranPqrs $pqrs): self
    {
        $n = new self($pqrs);

        // 1) Buzón interno
        $buzon = array_values(array_filter((array) config('mail.pqrs_to', [])));
        if ($buzon) {
            $n->enviar($buzon, 'empresa', 'aviso al buzón PQRS');
        } else {
            $n->fallar('buzón PQRS', 'aviso al buzón PQRS', 'PQRS_MAIL_TO está vacío en el .env (recuerda ejecutar "php artisan config:cache" después de cambiarlo).');
        }

        // 2) Acuse al solicitante
        if (config('mail.pqrs_acuse', true)) {
            $n->enviar([$pqrs->email], 'solicitante', 'acuse al solicitante');
        }

        return $n;
    }

    public static function actualizada(SilogtranPqrs $pqrs): self
    {
        $n = new self($pqrs);
        $n->enviar([$pqrs->email], 'actualizacion', 'aviso de actualización al solicitante');

        return $n;
    }

    private function enviar(array $para, string $destino, string $que): void
    {
        $a = implode(', ', $para);
        $rad = $this->pqrs->radicado;

        // Con MAIL_MAILER=log el correo no sale: se escribe en el log. Se reporta como fallo.
        if (config('mail.default') === 'log') {
            $this->fallar($a, $que, 'MAIL_MAILER está en "log": el correo se guardó en storage/logs/laravel.log y no se envió.');
            return;
        }

        try {
            Mail::to($para)->send(new PqrsMail($this->pqrs, $destino));
            $this->enviados[] = ['a' => $a, 'que' => $que];
            Log::info("PQRS {$rad}: {$que} enviado a {$a}");
        } catch (\Throwable $e) {
            $this->fallar($a, $que, $e->getMessage());
        }
    }

    private function fallar(string $a, string $que, string $error): void
    {
        $this->fallos[] = ['a' => $a, 'que' => $que, 'error' => $error];
        Log::error("PQRS {$this->pqrs->radicado} - falló {$que} a {$a}: {$error}");
    }

    public function todoOk(): bool
    {
        return !$this->fallos;
    }

    /** ¿Falló el correo que le llega al solicitante? */
    public function falloSolicitante(): bool
    {
        foreach ($this->fallos as $f) {
            if (str_contains($f['que'], 'solicitante')) {
                return true;
            }
        }
        return false;
    }

    /** Resumen para mostrar en pantalla (con detalle técnico solo para administradores). */
    public function resumen(bool $conDetalle): ?string
    {
        if (!$this->fallos) {
            return null;
        }
        $lineas = array_map(fn ($f) => $conDetalle
            ? "No se envió el {$f['que']} ({$f['a']}): " . mb_strimwidth($f['error'], 0, 220, '…')
            : "No se pudo enviar el {$f['que']}.",
            $this->fallos);

        return implode(' · ', $lineas) . ($conDetalle ? ' — Diagnóstico: php artisan correo:probar tu@correo' : '');
    }
}
