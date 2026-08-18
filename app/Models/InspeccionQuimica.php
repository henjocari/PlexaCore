<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InspeccionQuimica extends Model
{
    use HasFactory;

    protected $table = 'inspecciones_quimicas';

    protected $guarded = [];

    protected $casts = [
        'inventario' => 'array',
        'checklist' => 'array',
        'acciones' => 'array',
    ];
}
