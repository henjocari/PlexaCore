<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermisosModulo extends Model
{
    protected $table = 'permisos_modulos';
    public $timestamps = false;

    protected $fillable = ['roles', 'paginas'];

    public function role()
    {
        return $this->belongsTo(Roles::class, 'roles', 'id');
    }
}
