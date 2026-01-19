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
    Schema::table('2_precios_glp', function (Blueprint $table) {
        // Crea la relación con la tabla de usuarios
        $table->unsignedBigInteger('user_id')->nullable()->after('archivo_pdf');
        $table->foreign('user_id')->references('id')->on('users');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('2_precios_glp', function (Blueprint $table) {
            //
        });
    }
};
