<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tratamiento_firmas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cedula');
            $table->date('fecha_expedicion');
            $table->boolean('acepto_terminos')->default(true);
            $table->timestamps(); // Crea automáticamente created_at (fecha de firma)
        });
    }

    public function down()
    {
        Schema::dropIfExists('tratamiento_firmas');
    }
};