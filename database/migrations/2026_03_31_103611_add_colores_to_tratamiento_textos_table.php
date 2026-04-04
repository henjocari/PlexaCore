<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tratamiento_textos', function (Blueprint $table) {
            $table->string('color_fondo')->default('#f1f5f9');
            $table->string('color_texto')->default('#1e293b');
            $table->string('color_boton')->default('#378E77');
        });
    }

    public function down()
    {
        Schema::table('tratamiento_textos', function (Blueprint $table) {
            $table->dropColumn(['color_fondo', 'color_texto', 'color_boton']);
        });
    }
};