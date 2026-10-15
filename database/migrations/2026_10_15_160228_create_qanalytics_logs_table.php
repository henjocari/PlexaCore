<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logs_qanalytics', function (Blueprint $table) {
            $table->id();
            $table->string('placa');
            $table->json('payload');
            $table->integer('status_code');
            $table->text('response_body')->nullable();
            $table->timestamps();
        });
    }
};
