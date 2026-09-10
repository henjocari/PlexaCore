<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('notificacion_manifiestos', 'fopat')) {
            // 1. Crea la columna fopat al lado de reteFuente
            Schema::table('notificacion_manifiestos', function (Blueprint $table) {
                $table->decimal('fopat', 15, 2)->nullable()->after('reteFuente');
            });

            // 2. ✅ Recalcula el valor VERDADERO para TODOS los registros que ya están ahí
            DB::table('notificacion_manifiestos')->update([
                'fopat' => DB::raw('fleteNeto * 0.001'),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('notificacion_manifiestos', function (Blueprint $table) {
            $table->dropColumn('fopat');
        });
    }
};