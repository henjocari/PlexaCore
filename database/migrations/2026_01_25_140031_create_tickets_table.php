<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Creamos la tabla '3_tickets' para mantener el orden de tu sistema
        Schema::create('3_tickets', function (Blueprint $table) {
            $table->id(); // ID único del ticket

            // 1. Quién sube el ticket
            // Nota: Asumimos que la 'cedula' en la tabla 'usuarios' es un número grande.
            $table->unsignedBigInteger('user_id'); 

            // 2. Los Archivos (Guardaremos el nombre del archivo aquí)
            $table->string('archivo_tikete');  // Para el Tikete
            $table->string('archivo_soporte'); // Para el Soporte

            // 3. Información Adicional
            $table->text('descripcion')->nullable(); // Descripción opcional
            $table->integer('estado')->default(1);   // 1=Activo/Enviado, 0=Eliminado

            $table->timestamps(); // Fechas de creación y actualización

            // OPCIONAL: Integridad referencial (Descomenta si quieres que sea estricto)
            // Esto conecta 'user_id' con 'cedula' en la tabla 'usuarios'.
            // $table->foreign('user_id')->references('cedula')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('3_tickets');
    }
};