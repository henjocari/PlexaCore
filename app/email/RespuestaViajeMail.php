<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RespuestaViajeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $datos;

    public function __construct($datos) { $this->datos = $datos; }

    public function build() {
        $email = $this->subject('Actualización Solicitud de Viaje')->view('emails.respuesta_viaje');
        
        // ADJUNTAR TIQUETE SI EXISTE
        if($this->datos['estado'] == 1 && !empty($this->datos['ruta_archivo'])) {
            $email->attach($this->datos['ruta_archivo']);
        }
        return $email;
    }
}