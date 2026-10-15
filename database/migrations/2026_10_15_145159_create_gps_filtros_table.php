<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gps_filtros', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['cliente', 'placa', 'operacion']);
            $table->string('valor');
            $table->boolean('activo')->default(true); // Te permite apagar un filtro sin borrarlo
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gps_filtros');
    }
};