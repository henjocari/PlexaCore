<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NuevoTicketMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;

    // Recibimos la información del ticket desde el controlador
    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    // Aquí "armamos" el correo
    public function build()
    {
        return $this->subject('🎟️ Nuevo Ticket de Soporte - PlexaCore')
                    ->view('emails.nuevo_ticket'); // Esta vista ya la creaste antes
    }
}