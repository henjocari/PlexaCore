<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios'; // 👈 tu tabla personalizada
    protected $primaryKey = 'cedula'; // 👈 campo clave primaria
    public $timestamps = false; // 👈 tu tabla no tiene created_at/updated_at

    protected $fillable = [
        'cedula',
        'Nombre',
        'Apellido',
        'email',
        'cel',
        'contraseña',
        'rol',
        'estado'
    ];

    protected $hidden = [
        'contraseña',
    ];

    /**
     * Laravel usa "password" por defecto, así que redirigimos el campo.
     */
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    /**
     * Relación con la tabla roles.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'rol');
    }
}
