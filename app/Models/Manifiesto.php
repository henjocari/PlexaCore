<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manifiesto extends Model
{
    use HasFactory;

    /**
     * Nombre exacto de la tabla en MySQL.
     */
    protected $table = 'notificacion_manifiestos';

    /**
     * Clave primaria.
     */
    protected $primaryKey = 'id';

    /**
     * Habilitado porque la tabla incluye created_at y updated_at.
     */
    public $timestamps = true;

    /**
     * Campos de la tabla exactamente como están definidos en la base de datos.
     */
    protected $fillable = [
        'manifiesto_codigo',
        'nombre',
        'cliente',
        'tipoOperacion',
        'producto',
        'fecha',
        'hora',
        'cantidad',
        'peso',
        'fleteNeto',
        'anticipo',
        'saldoPagar',
        'reteIca',
        'reteFuente',
    ];

    /**
     * Conversión automática de tipos para operar correctamente en PHP.
     */
    protected $casts = [
        'fecha'      => 'date:Y-m-d',
        'fleteNeto'  => 'float',
        'anticipo'   => 'float',
        'saldoPagar' => 'float',
        'reteIca'    => 'float',
        'reteFuente' => 'float',
    ];
}