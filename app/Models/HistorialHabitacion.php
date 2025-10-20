<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialHabitacion extends Model
{
    use HasFactory;
    
    protected $table = 'historial_habitaciones';

    protected $fillable = [
        'habitacion', 
        'estado', 
        'conductor', 
        'usuario', 
        'fecha',
    ];

    // ✅ IMPORTANTE: Deshabilitar timestamps porque usas 'fecha' manual
    public $timestamps = false;

    // ✅ Castear fecha como datetime
    protected $casts = [
        'fecha' => 'datetime',
    ];

    // Relación con Usuario (opcional si tienes tabla de usuarios)
    public function usuarioRegistro()
    {
        return $this->belongsTo(\App\Models\User::class, 'usuario');
    }
}