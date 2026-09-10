<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_seguimiento_historial', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->string('nombre_usuario', 100)->nullable();
            $table->decimal('cumplimiento_general', 5, 2)->default(0);
            $table->integer('celdas_registradas')->default(0);
            $table->json('resumen')->nullable(); // totales, % por ODS y por actividad
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_seguimiento_historial');
    }
};