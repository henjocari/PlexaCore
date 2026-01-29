<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('3_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // Cédula del empleado
            
            // DATOS DEL VIAJE
            $table->string('origen');
            $table->string('destino');
            $table->date('fecha_viaje');
            
            // ARCHIVOS (Nulos al principio)
            $table->string('archivo_tikete')->nullable(); 
            $table->string('archivo_soporte')->nullable();
            
            // DETALLES Y ESTADO
            $table->text('descripcion')->nullable(); 
            $table->text('mensaje_admin')->nullable();
            $table->integer('estado')->default(2); // 2=Pendiente, 1=Aprobado, 0=Rechazado
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('3_tickets');
    }
};