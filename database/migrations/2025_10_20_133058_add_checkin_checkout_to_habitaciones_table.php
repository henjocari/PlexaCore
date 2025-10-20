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
        Schema::table('habitaciones', function (Blueprint $table) {
        $table->timestamp('check_in')->nullable()->after('conductor');
        $table->timestamp('check_out')->nullable()->after('check_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('habitaciones', function (Blueprint $table) {
            $table->dropColumn(['check_in', 'check_out']);
        });
    }
};
