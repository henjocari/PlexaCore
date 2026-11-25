<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Campos solicitados por Servicio al Cliente:
 * ubicaciÃ³n del solicitante (paÃ­s, departamento, ciudad, direcciÃ³n)
 * y "Producto o Servicio" de la PQRS.
 * Nullable para no afectar las PQRS radicadas antes del cambio.
 */
return new class extends Migration
{
    private array $columnas = ['pais', 'departamento', 'ciudad', 'direccion', 'producto_servicio'];

    public function up(): void
    {
        if (!Schema::hasTable('silogtran_pqrs')) {
            return;
        }

        Schema::table('silogtran_pqrs', function (Blueprint $table) {
            if (!Schema::hasColumn('silogtran_pqrs', 'pais')) {
                $table->string('pais', 80)->nullable()->after('telefono');
            }
            if (!Schema::hasColumn('silogtran_pqrs', 'departamento')) {
                $table->string('departamento', 80)->nullable()->after('pais');
            }
            if (!Schema::hasColumn('silogtran_pqrs', 'ciudad')) {
                $table->string('ciudad', 80)->nullable()->after('departamento');
            }
            if (!Schema::hasColumn('silogtran_pqrs', 'direccion')) {
                $table->string('direccion', 200)->nullable()->after('ciudad');
            }
            if (!Schema::hasColumn('silogtran_pqrs', 'producto_servicio')) {
                $table->string('producto_servicio', 60)->nullable()->after('tipo');
            }
        });
    }

    public function down(): void
    {
        $existentes = array_values(array_filter(
            $this->columnas,
            fn ($c) => Schema::hasColumn('silogtran_pqrs', $c)
        ));

        if ($existentes) {
            Schema::table('silogtran_pqrs', fn (Blueprint $table) => $table->dropColumn($existentes));
        }
    }
};