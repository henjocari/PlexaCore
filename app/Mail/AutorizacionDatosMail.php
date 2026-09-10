<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str; // Helper para formatear cadenas de texto
use App\Models\TratamientoFirma;
use Barryvdh\DomPDF\Facade\Pdf; // Fachada para la generación de archivos PDF

class AutorizacionDatosMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Propiedad pública que almacena los datos de la firma desde MySQL.
     * Al ser pública, Laravel permite acceder a ella directamente si usas vistas Blade.
     * 
     * @var TratamientoFirma
     */
    public $firma;

    /**
     * Constructor de la clase.
     * Recibe el registro de la firma recién guardado en la base de datos.
     *
     * @param TratamientoFirma $firma
     */
    public function __construct(TratamientoFirma $firma)
    {
        $this->firma = $firma;
    }

    /**
     * Construye el mensaje de correo electrónico y adjunta el documento PDF.
     *
     * @return $this
     */
    public function build()
    {
        // 1. Renderiza la vista Blade 'resources/views/documentofirmapdf.blade.php' a un binario PDF
        $pdf = Pdf::loadView('documentofirmapdf', ['firma' => $this->firma]);

        // 2. Convierte el nombre a un formato seguro (ej: "José Pérez" -> "jose_perez")
        $nombreLimpio = Str::slug($this->firma->nombre, '_');
        $nombreArchivo = 'Autorizacion_' . $nombreLimpio . '.pdf';

        // 3. Estructura el cuerpo del correo en formato HTML
        $cuerpoHtml = "
            <h2>Autorización de Tratamiento de Datos Personales</h2>
            <p>Se ha registrado y firmado una nueva autorización en el sistema.</p>
            <ul>
                <li><strong>Nombre Completo:</strong> {$this->firma->nombre}</li>
                <li><strong>Número de Cédula:</strong> {$this->firma->cedula}</li>
                <li><strong>Lugar de Expedición:</strong> {$this->firma->lugar_expedicion}</li>
                <li><strong>Ciudad de Firma:</strong> {$this->firma->ciudad_firma}</li>
            </ul>
            <p>El documento oficial adjunto contiene la representación legal en formato PDF.</p>
        ";

        // 4. Retorna la configuración completa del mensaje de correo con el archivo adjunto
        return $this->subject('Nueva Autorización de Tratamiento de Datos - ' . $this->firma->nombre)
                    ->html($cuerpoHtml)
                    ->attachData($pdf->output(), $nombreArchivo, [
                        'mime' => 'application/pdf',
                    ]);
    }
}