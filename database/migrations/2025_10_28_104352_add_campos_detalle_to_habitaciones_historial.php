<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('habitaciones_historial', function (Blueprint $table) {
            $table->string('c_conductor')->nullable();
            $table->string('n_conductor')->nullable();
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->string('usuario_check_in')->nullable();
            $table->string('usuario_check_out')->nullable();
            $table->string('tiempo_uso')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('habitaciones_historial', function (Blueprint $table) {
            $table->dropColumn([
                'c_conductor',
                'n_conductor',
                'check_in',
                'check_out',
                'usuario_check_in',
                'usuario_check_out',
                'tiempo_uso',
            ]);
        });
    }
};
