<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TratamientoFirma extends Model
{
    use HasFactory;

    protected $table = 'tratamiento_firmas';
    
    protected $fillable = [
        'nombre', 
        'cedula', 
        'lugar_expedicion', 
        'ciudad_firma', 
        'acepto_terminos',
        'firma' 
    ];
}