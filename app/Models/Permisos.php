<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    protected $table = 'permisos';
    public $timestamps = false;

    protected $fillable = ['paginas', 'permiso'];
}
