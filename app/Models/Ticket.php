<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // Nombre exacto de la tabla en tu base de datos
    protected $table = '3_tickets';

    // Los campos que permitimos llenar
    protected $fillable = [
        'user_id',
        'archivo_tikete',
        'archivo_soporte',
        'descripcion',
        'estado'
    ];
}