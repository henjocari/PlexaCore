<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialHabitacion extends Model
{
    use HasFactory;
    
    protected $table = 'habitaciones_historial';

    protected $fillable = [
        'habitacion', 
        'estado', 
        'conductor', 
        'usuario', 
        'fecha',
        'c_conductor',
        'n_conductor',
        'check_in',
        'usuario_check_in',
        'check_out',
        'usuario_check_out',
        'tiempo_uso',
    ];

    // ✅ IMPORTANTE: Deshabilitar timestamps porque usas 'fecha' manual
    public $timestamps = false;

    // ✅ Castear fecha como datetime
    protected $casts = [
        'fecha' => 'datetime',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    // Relación con Usuario (opcional si tienes tabla de usuarios)
    public function usuarioRegistro()
    {
        return $this->belongsTo(\App\Models\User::class, 'usuario');
    }
}