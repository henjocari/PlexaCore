<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSeguimientoHistorial extends Model
{
    protected $table = 'plan_seguimiento_historial';

    protected $fillable = [
        'usuario_id',
        'nombre_usuario',
        'cumplimiento_general',
        'celdas_registradas',
        'resumen',
    ];

    protected $casts = [
        'resumen'              => 'array',
        'cumplimiento_general' => 'float',
        'celdas_registradas'   => 'integer',
    ];
}