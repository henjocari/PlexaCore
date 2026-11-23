<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * NIT del cliente que radica la PQRS desde Silogtran (vista embebida).
 * Con este campo cada cliente solo ve sus propias solicitudes.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('silogtran_pqrs') || Schema::hasColumn('silogtran_pqrs', 'nit_cliente')) {
            return;
        }

        Schema::table('silogtran_pqrs', function (Blueprint $table) {
            $table->string('nit_cliente', 20)->nullable()->after('radicado')->index();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('silogtran_pqrs', 'nit_cliente')) {
            Schema::table('silogtran_pqrs', function (Blueprint $table) {
                $table->dropIndex(['nit_cliente']);
                $table->dropColumn('nit_cliente');
            });
        }
    }
};