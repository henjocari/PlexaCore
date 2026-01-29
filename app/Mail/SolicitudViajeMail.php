<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudViajeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;
    public $tipo; // 'jefe' o 'empleado'

    // Recibimos los datos y A QUIÉN va dirigido
    public function __construct($datos, $tipo = 'empleado')
    {
        $this->datos = $datos;
        $this->tipo = $tipo;
    }

    public function build()
    {
        $asunto = ($this->tipo == 'jefe') 
            ? '🔔 Nueva Solicitud por Aprobar' 
            : '✅ Solicitud Recibida Exitosamente';

        return $this->subject($asunto)
                    ->view('emails.solicitud_viaje');
    }
}