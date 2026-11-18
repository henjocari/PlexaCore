<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SilogtranPqrs extends Model
{
    protected $table = 'silogtran_pqrs';

    protected $fillable = [
        'radicado',
        'tipo',
        'categoria',
        'prioridad',
        'nombre',
        'tipo_documento',
        'documento',
        'email',
        'telefono',
        'manifiesto',
        'asunto',
        'descripcion',
        'adjunto',
        'estado',
        'respuesta',
        'user_id',
    ];

    public const TIPOS = ['Peticion', 'Queja', 'Reclamo', 'Sugerencia'];

    public const CATEGORIAS = [
        'Operación de transporte',
        'Manifiestos',
        'Facturación',
        'Conductores',
        'Vehículos',
        'Plataforma Silogtran',
        'Otro',
    ];

    public const ESTADOS = ['Radicado', 'En Gestion', 'Cerrado'];
}
