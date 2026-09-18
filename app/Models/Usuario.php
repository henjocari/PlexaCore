<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'cedula';
    public $timestamps = false;

    protected $fillable = [
        'cedula',
        'Nombre',
        'Apellido',
        'email',
        'cel',
        'contraseña',
        'rol',
        'estado',
        'tipo_operacion',   // ✅ CLAVE para create/update
    ];

    protected $hidden = [
        'contraseña',
    ];

    /**
     * Laravel usa "password" por defecto; redirigimos al campo real.
     */
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    public function role()
    {
        return $this->belongsTo(\App\Models\Roles::class, 'rol');
    }

    public function rolInfo()
    {
        return $this->belongsTo(\App\Models\Roles::class, 'rol', 'id');
    }
}