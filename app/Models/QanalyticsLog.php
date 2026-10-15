<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QanalyticsLog extends Model
{
    protected $table = 'logs_qanalytics'; // <-- Agrega esta línea
    
    protected $guarded = [];
    protected $casts = [
        'payload' => 'array',
    ];
}