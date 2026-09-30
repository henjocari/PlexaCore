<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manifiestos', function (Blueprint $table) {
            $table->string('manifiesto', 50)->primary(); // Llave primaria
            $table->string('empresa')->nullable();
            $table->string('muc')->nullable();
            $table->date('fecha')->nullable()->index(); // Índice para velocidad
            $table->string('hora')->nullable();
            $table->string('estado')->nullable()->index(); // Índice para velocidad
            $table->string('origen')->nullable();
            $table->string('destino')->nullable();
            $table->string('placa')->nullable();
            $table->string('conductor')->nullable();
            $table->string('celular')->nullable();
            $table->string('remesaCodigo')->nullable();
            $table->string('contenedorCodigo')->nullable();
            $table->string('iteremconFechadevolucion')->nullable();
            $table->string('telefonoConductor')->nullable();
            $table->string('cumplidoFechaCreacion')->nullable();
            $table->text('descripcionItemRemesa')->nullable();
            $table->string('legalizacion')->nullable();
            $table->string('poseedor')->nullable();
            $table->string('kilos')->nullable();
            $table->string('saldoPagar')->nullable();
            $table->string('codigoMinisterio')->nullable();
            $table->string('reteIca')->nullable();
            $table->string('reteFuente')->nullable();
            $table->string('asiscar')->nullable();
            $table->string('anticipo')->nullable();
            $table->decimal('fleteRemesa', 15, 2)->default(0); // Crítico KPI
            $table->decimal('fleteNeto', 15, 2)->default(0); // Crítico KPI
            $table->string('manDescuento')->nullable();
            $table->string('utilidad')->nullable();
            $table->string('producto')->nullable();
            $table->string('cliente')->nullable();
            $table->string('codigoRuta')->nullable();
            $table->string('kilometraje')->nullable();
            $table->string('tiempoRuta')->nullable();
            $table->string('valorDeCombustible')->nullable();
            $table->string('tipoOperacion')->nullable();
            $table->string('trailer')->nullable();
            $table->string('conductor2')->nullable();
            $table->string('idConductor2')->nullable();
            $table->string('idConductor')->nullable();
            $table->string('idPoseedor')->nullable();
            $table->string('peso')->nullable();
            $table->decimal('cantidad', 15, 2)->default(0); // Crítico KPI
            $table->string('manifiestoGuia')->nullable();
            $table->string('manifiestoPedido')->nullable();
            $table->string('usuarioCreador')->nullable();
            $table->string('cedulaUsuarioCreador')->nullable();
            $table->string('tarifaCodigo')->nullable();
            $table->string('fleteCodigo')->nullable();
            $table->string('latitudOrigen')->nullable();
            $table->string('longitudOrigen')->nullable();
            $table->string('latitudDestino')->nullable();
            $table->string('longitudDestino')->nullable();
            $table->string('codigoRemitente')->nullable();
            $table->string('remitente')->nullable();
            $table->string('latitudRemitente')->nullable();
            $table->string('longitudRemitente')->nullable();
            $table->string('codigoDestinatario')->nullable();
            $table->string('destinatario')->nullable();
            $table->string('latitudDestinatario')->nullable();
            $table->string('longitudDestinatario')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manifiestos');
    }
};