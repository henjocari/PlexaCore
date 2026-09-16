<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';
    public $timestamps = false;

    protected $fillable = ['paginas', 'permiso'];

    public function modulo()
    {
        return $this->belongsTo(PermisoModulo::class, 'paginas');
    }
}