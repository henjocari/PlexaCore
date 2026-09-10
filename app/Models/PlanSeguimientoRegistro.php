<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSeguimientoRegistro extends Model
{
    protected $table = 'plan_seguimiento_registros';

    protected $fillable = [
        'actividad_id',
        'semana',
        'estado',
        'usuario_id',
        'nombre_usuario',
        'registrado_el',
    ];

    protected $casts = [
        'semana'        => 'integer',
        'usuario_id'    => 'integer',
        'registrado_el' => 'datetime',
    ];
}