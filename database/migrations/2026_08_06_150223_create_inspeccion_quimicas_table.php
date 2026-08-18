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
        Schema::create('inspecciones_quimicas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_formato')->default('HSEQ-F-001');
            $table->string('sede');
            $table->date('fecha');
            $table->string('area');
            $table->string('evaluador');
            $table->string('tipo_inspeccion');
            $table->json('inventario')->nullable();
            $table->json('checklist')->nullable();
            $table->json('acciones')->nullable();
            $table->string('firma_evaluador')->nullable();
            $table->string('firma_responsable')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspeccion_quimicas');
    }
};
