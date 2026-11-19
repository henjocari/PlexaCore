<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('silogtran_pqrs'))
        Schema::create('silogtran_pqrs', function (Blueprint $table) {
            $table->id();
            $table->string('radicado', 30)->unique();
            $table->string('tipo', 20);                 // Peticion | Queja | Reclamo | Sugerencia
            $table->string('categoria', 60);
            $table->string('prioridad', 10)->default('Media');
            $table->string('nombre', 150);
            $table->string('tipo_documento', 10);
            $table->string('documento', 30);
            $table->string('email', 150);
            $table->string('telefono', 30)->nullable();
            $table->string('manifiesto', 50)->nullable();
            $table->string('asunto', 150);
            $table->text('descripcion');
            $table->string('adjunto')->nullable();
            $table->string('estado', 20)->default('Radicado'); // Radicado | En Gestion | Cerrado
            $table->text('respuesta')->nullable();
            $table->string('user_id', 30)->nullable();
            $table->timestamps();
        });

        // Registrar el módulo "PQRS" para el Super Administrador (rol 1)
        if (Schema::hasTable('permisos_modulos')) {
            $existe = DB::table('permisos_modulos')
                ->where('roles', 1)->where('paginas', 'PQRS')->exists();
            if (!$existe) {
                DB::table('permisos_modulos')->insert(['roles' => 1, 'paginas' => 'PQRS']);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('silogtran_pqrs');

        if (Schema::hasTable('permisos_modulos')) {
            DB::table('permisos_modulos')->where('paginas', 'PQRS')->delete();
        }
    }
};