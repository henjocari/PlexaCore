<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // EL NOMBRE DE LA TABLA (Asegúrate que sea el correcto)
    protected $table = '3_tickets'; 

    protected $fillable = [
        'user_id',
        'origen',
        'destino',
        'fecha_viaje',
        'fecha_regreso', 
        'tipo_viaje',    
        'descripcion',
        'estado',
        'archivo_tikete',
        'mensaje_admin'
    ];
}