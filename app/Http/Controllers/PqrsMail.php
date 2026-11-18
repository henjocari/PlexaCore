<?php

namespace App\Mail;

use App\Models\SilogtranPqrs;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PqrsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pqrs;
    public $destino; // 'empresa' | 'solicitante' | 'actualizacion'

    public function __construct(SilogtranPqrs $pqrs, string $destino = 'empresa')
    {
        $this->pqrs = $pqrs;
        $this->destino = $destino;
    }

    public function build()
    {
        $tipo = $this->pqrs->tipo === 'Peticion' ? 'Petición' : $this->pqrs->tipo;
        $nuevo = $this->pqrs->tipo === 'Reclamo' ? 'Nuevo' : 'Nueva';

        $asunto = match ($this->destino) {
            'solicitante'   => "✅ Recibimos tu " . mb_strtolower($tipo) . " · Radicado {$this->pqrs->radicado}",
            'actualizacion' => "🔔 Tu PQRS {$this->pqrs->radicado} fue actualizada",
            default         => "📩 {$nuevo} " . mb_strtolower($tipo) . " [{$this->pqrs->prioridad}] · {$this->pqrs->radicado}",
        };

        $mail = $this->subject($asunto)->view('emails.pqrs');

        // Al buzón de la empresa se le adjunta el soporte que subió el solicitante
        if ($this->destino === 'empresa' && $this->pqrs->adjunto) {
            $ruta = public_path('archivos_pqrs/' . $this->pqrs->adjunto);
            if (is_file($ruta)) {
                $mail->attach($ruta);
            }
        }

        // Para que el solicitante pueda responder directo al buzón de PQRS
        if ($this->destino === 'empresa') {
            $mail->replyTo($this->pqrs->email, $this->pqrs->nombre);
        }

        return $mail;
    }
}