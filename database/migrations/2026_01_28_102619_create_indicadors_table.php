<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('indicadors', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // 'dolar', 'glp', etc.
            $table->string('nombre');
            $table->decimal('valor', 10, 2); // Precio
            $table->string('tendencia')->default('igual'); // 'sube', 'baja', 'igual'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicadors');
    }
};
