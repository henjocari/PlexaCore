<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsFiltro extends Model
{
    protected $table = 'gps_filtros';
    protected $fillable = ['tipo', 'valor', 'activo'];
}