<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_seguimiento_registros', function (Blueprint $table) {
            $table->id();
            $table->string('actividad_id', 10);              // A-01 ... A-30
            $table->unsignedTinyInteger('semana');            // 0 = JULIO S1 ... 29 = DICIEMBRE S5
            $table->string('estado', 2)->nullable();          // P | E | EP | AT
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->string('nombre_usuario', 100)->nullable();
            $table->timestamp('registrado_el')->nullable();
            $table->timestamps();

            $table->unique(['actividad_id', 'semana']);
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_seguimiento_registros');
    }
};