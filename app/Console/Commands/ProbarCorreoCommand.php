<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Diagnóstico de correo: muestra la configuración (sin contraseña), envía un
 * correo de prueba y, si falla, muestra el error con una pista de solución.
 *
 *   php artisan correo:probar destinatario@plexa.co
 */
class ProbarCorreoCommand extends Command
{
    protected $signature = 'correo:probar {destinatario : Correo al que se envía la prueba}';

    protected $description = 'Muestra la configuración de correo y envía un correo de prueba';

    public function handle(): int
    {
        $para = trim($this->argument('destinatario'));
        $mailer = config('mail.default');
        $cfg = config("mail.mailers.{$mailer}", []);

        $usuario = (string) ($cfg['username'] ?? '');
        $this->info('Configuración de correo en uso');
        $this->table(['Clave', 'Valor'], [
            ['Mailer (MAIL_MAILER)', $mailer],
            ['Servidor (MAIL_HOST)', $cfg['host'] ?? '—'],
            ['Puerto (MAIL_PORT)', $cfg['port'] ?? '—'],
            ['Cifrado (MAIL_SCHEME)', ($cfg['scheme'] ?? null) ?: 'automático (STARTTLS en 587)'],
            ['Usuario (MAIL_USERNAME)', $usuario ?: '— (vacío)'],
            ['Contraseña (MAIL_PASSWORD)', !empty($cfg['password']) ? '****** (definida)' : '— (VACÍA)'],
            ['Remitente (MAIL_FROM_ADDRESS)', config('mail.from.address') . ' · ' . config('mail.from.name')],
            ['Buzón PQRS (PQRS_MAIL_TO)', implode(', ', (array) config('mail.pqrs_to')) ?: '— (vacío)'],
        ]);

        if ($mailer === 'log') {
            $this->warn('MAIL_MAILER está en "log": los correos se escriben en storage/logs/laravel.log y NO se envían.');
            return self::FAILURE;
        }
        if ($mailer === 'smtp' && $usuario && strcasecmp($usuario, (string) config('mail.from.address')) !== 0) {
            $this->warn("Ojo: el usuario SMTP ({$usuario}) es distinto al remitente (" . config('mail.from.address') . '). '
                . 'Office 365 y Gmail suelen rechazar eso si la cuenta no tiene permiso de "Enviar como".');
        }

        $this->line("Enviando correo de prueba a <comment>{$para}</comment>…");
        try {
            Mail::raw("Correo de prueba enviado desde Plexa Core el " . now()->format('d/m/Y H:i') . ".\n\nSi lo recibes, el envío de correos funciona.", function ($m) use ($para) {
                $m->to($para)->subject('Prueba de correo · Plexa Core');
            });
        } catch (\Throwable $e) {
            $msg = $e->getMessage();
            $this->error('ERROR: ' . $msg);
            $pista = match (true) {
                (bool) preg_match('/SendAsDenied|not authorized to send|send as/i', $msg) => 'La cuenta de MAIL_USERNAME no tiene permiso para enviar como el remitente. Usa la misma cuenta en MAIL_FROM_ADDRESS o pide el permiso "Enviar como".',
                (bool) preg_match('/535|authenticat|Username and Password not accepted|credentials/i', $msg) => 'Usuario o contraseña rechazados. En Office 365/Gmail puede requerirse una contraseña de aplicación o habilitar "SMTP AUTH" para la cuenta.',
                (bool) preg_match('/SmtpClientAuthentication is disabled/i', $msg) => 'Office 365 tiene desactivado SMTP AUTH para esa cuenta. El administrador debe activarlo en el centro de administración de Microsoft 365.',
                (bool) preg_match('/Connection could not be established|timed out|Connection refused|getaddrinfo/i', $msg) => 'No se pudo conectar al servidor. Revisa MAIL_HOST y MAIL_PORT (Office 365: smtp.office365.com:587, Gmail: smtp.gmail.com:587) o si la red/antivirus bloquea el puerto.',
                (bool) preg_match('/certificate|SSL|TLS/i', $msg) => 'Problema de cifrado. Con el puerto 587 usa MAIL_SCHEME=smtp (STARTTLS); con 465 usa MAIL_SCHEME=smtps.',
                (bool) preg_match('/only send testing emails|domain is not verified|verify a domain/i', $msg) => 'Resend: hay que verificar el dominio plexa.co en el panel de Resend.',
                default => 'Revisa los datos de MAIL_* en el .env y vuelve a ejecutar "php artisan config:cache".',
            };
            $this->warn('Pista: ' . $pista);
            $this->line('Recuerda: después de cambiar el .env ejecuta  php artisan config:cache');
            return self::FAILURE;
        }

        $this->info('ENVIADO OK. Si no llega en unos minutos, revisa la carpeta de spam.');
        return self::SUCCESS;
    }
}
