<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsPosition extends Model
{
    protected $table = 'gps_positions';
    protected $primaryKey = 'ID_REG';
    
    protected $fillable = [
        'LATITUD', 'LONGITUD', 'VELOCIDAD', 'SENTIDO', 
        'FH_DATO', 'PLACA', 'CANT_SATELITES', 'HDOP', 
        'TEMP1', 'TEMP2', 'TEMP3', 'SENSORA_1', 'AP', 
        'IGNICION', 'PANICO', 'SENSORD_1', 'TRANS'
    ];
}