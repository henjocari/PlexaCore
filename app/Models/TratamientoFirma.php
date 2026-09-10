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
        'acepto_terminos',
        'firma',
        'autorizo_personales',
        'autorizo_sensibles',
        'fecha_autorizacion',
        'url_pdf',
    ];

    protected $casts = [
        'fecha_autorizacion' => 'date',
    ];
}