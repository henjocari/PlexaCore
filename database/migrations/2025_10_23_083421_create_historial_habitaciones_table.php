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
        Schema::create('historial_habitaciones', function (Blueprint $table) {
        $table->id();
        $table->string('habitacion');
        $table->string('estado');
        $table->string('conductor')->nullable();
        $table->string('usuario');
        $table->timestamp('fecha')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_habitaciones');
    }
};
