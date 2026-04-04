<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tratamiento_textos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('subtitulo')->nullable();
            $table->longText('terminos_legales')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tratamiento_textos');
    }
};