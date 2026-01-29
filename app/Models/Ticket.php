<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // Nombre exacto de tu tabla
    protected $table = '3_tickets';

    protected $fillable = [
        'user_id',
        'origen',        
        'destino',       
        'fecha_viaje',   
        'descripcion',
        'archivo_tikete',
        'archivo_soporte',
        'mensaje_admin',
        'estado'
    ];
}