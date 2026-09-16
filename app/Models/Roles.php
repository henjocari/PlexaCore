<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PermisosModulo;

class Roles extends Model
{
    protected $table = 'permisos_roles';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    /**
     * Módulos/pantallas asignados a este rol.
     */
    public function modulos()
    {
        return $this->hasMany(PermisosModulo::class, 'roles', 'id');
    }

    /**
     * Usuarios que tienen este rol.
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'rol', 'id');
    }

    /**
     * ¿El rol puede ver esta pantalla?
     */
    public function tienePagina($pagina)
    {
        return $this->modulos()->where('paginas', $pagina)->exists();
    }
}