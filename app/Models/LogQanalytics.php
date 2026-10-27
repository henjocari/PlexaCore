<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogQanalytics extends Model
{
    use HasFactory;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'logs_qanalytics';

    // (Opcional) Si necesitas protección contra asignación masiva, 
    // puedes usar $guarded = [] o definir los campos en $fillable
    protected $guarded = [];
}