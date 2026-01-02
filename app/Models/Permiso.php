<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';
    public $timestamps = false;

    protected $fillable = ['rol', 'permiso'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'rol');
    }
}
