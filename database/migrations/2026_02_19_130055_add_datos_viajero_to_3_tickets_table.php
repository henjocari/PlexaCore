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
        Schema::table('3_tickets', function (Blueprint $table) {
            $table->string('beneficiario_nombre')->nullable()->after('user_id');
            $table->string('beneficiario_cedula')->nullable()->after('beneficiario_nombre');
            $table->date('beneficiario_fecha_nac')->nullable()->after('beneficiario_cedula');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('3_tickets', function (Blueprint $table) {
            //
        });
    }
};
