<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gps_positions', function (Blueprint $table) {
            $table->id('ID_REG'); // Autoincremental
            $table->string('LATITUD')->nullable(); // Desde Logitracs
            $table->string('LONGITUD')->nullable(); // Desde Logitracs
            $table->string('VELOCIDAD')->nullable(); // Desde Logitracs
            $table->string('SENTIDO')->nullable(); // Rumbo
            $table->dateTime('FH_DATO')->nullable(); // FechaEvento
            $table->string('PLACA')->unique(); // Placa del manifiesto
            $table->string('CANT_SATELITES')->nullable();
            $table->string('HDOP')->nullable();
            $table->string('TEMP1')->nullable();
            $table->string('TEMP2')->nullable();
            $table->string('TEMP3')->nullable();
            $table->string('SENSORA_1')->nullable();
            $table->string('AP')->nullable();
            $table->string('IGNICION')->nullable(); // Desde Logitracs
            $table->string('PANICO')->nullable();
            $table->string('SENSORD_1')->nullable();
            $table->string('TRANS')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gps_positions');
    }
};