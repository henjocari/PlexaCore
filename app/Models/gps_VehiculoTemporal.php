<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class gps_VehiculoTemporal extends Model
{
    protected $table = 'gps_vehiculos_temporales';
    protected $fillable = ['placa', 'cisterna'];
}