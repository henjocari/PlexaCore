<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    // Un rol tiene muchos usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'rol');
    }

    // Un rol tiene muchos permisos
    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'rol');
    }
}
