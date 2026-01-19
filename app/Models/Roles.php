<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PermisosModulo;
use App\Models\Permisos;

class Roles extends Model
{
    protected $table = 'permisos_roles';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    // Relación con los módulos asignados a este rol
    public function modulos()
    {
        return $this->hasMany(PermisosModulo::class, 'roles', 'id');
    }

    // Relación con los permisos a través de los módulos
    public function permisos()
    {
        return $this->hasManyThrough(
            Permisos::class,        // Modelo final
            PermisosModulo::class,  // Modelo intermedio
            'roles',               // FK en permisos_modulos apuntando a Roles
            'paginas',              // FK en permisos apuntando a PermisoModulo
            'id',                  // PK de Roles
            'id'                   // PK de PermisoModulo
        );
    }
}
