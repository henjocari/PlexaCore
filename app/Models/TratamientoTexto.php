<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TratamientoTexto extends Model
{
    use HasFactory;

    protected $table = 'tratamiento_textos';
    
    protected $fillable = [
        'titulo', 
        'subtitulo', 
        'terminos_legales',
        'color_fondo',
        'color_texto',
        'color_boton'
    ];
}