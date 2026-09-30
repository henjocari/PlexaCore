<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SyncSilogtran extends Command
{
    protected $signature = 'silogtran:sync';
    protected $description = 'Sincroniza manifiestos desde Silogtran a MySQL en bloques seguros de 4 días';

    public function handle()
    {
        $this->info('Iniciando sincronización con Silogtran...');

        // Determinar fecha inicial para la consulta
        if (DB::table('silog_manifiestos')->count() === 0) {
            $fechaInicialSync = '2025-01-01';
            $this->info("Base de datos vacía. Descarga histórica desde: {$fechaInicialSync}");
        } else {
            // Buscar el manifiesto ACTIVO más antiguo para barrer cambios de estado
            $ultimoActivo = DB::table('silog_manifiestos')
                ->where('estado', 'ACTIVO')
                ->orderBy('fecha', 'asc')
                ->first();

            $fechaInicialSync = $ultimoActivo ? $ultimoActivo->fecha : DB::table('silog_manifiestos')->max('fecha');
            $this->info("Actualización diferencial desde: {$fechaInicialSync}");
        }

        $fechaFinalSync = Carbon::now()->format('Y-m-d');

        if ($fechaInicialSync > $fechaFinalSync) {
            $this->info('Todo está actualizado al día de hoy.');
            return;
        }

        // Login API
        $responseToken = Http::asForm()->timeout(15)->post('https://plexa.colombiasoftware.net/index.php?api=Servicio.Seguridad.login', [
            'usuario_login' => 'TMS',
            'usuario_password' => 'Plexa802-1'
        ]);

        if (!$responseToken->successful() || !$responseToken->json('success')) {
            $this->error('Error de autenticación con Silogtran.');
            return;
        }
        $token = $responseToken->json('data.token');

        $start = Carbon::parse($fechaInicialSync);
        $end = Carbon::parse($fechaFinalSync);

        $columnas = [
            'empresa', 'muc', 'fecha', 'hora', 'estado', 'origen', 'destino', 'placa', 
            'conductor', 'celular', 'remesaCodigo', 'contenedorCodigo', 'iteremconFechadevolucion', 
            'telefonoConductor', 'cumplidoFechaCreacion', 'descripcionItemRemesa', 'legalizacion', 
            'poseedor', 'kilos', 'saldoPagar', 'codigoMinisterio', 'reteIca', 'reteFuente', 
            'asiscar', 'anticipo', 'fleteRemesa', 'fleteNeto', 'manDescuento', 'utilidad', 
            'producto', 'cliente', 'codigoRuta', 'kilometraje', 'tiempoRuta', 'valorDeCombustible', 
            'tipoOperacion', 'trailer', 'conductor2', 'idConductor2', 'idConductor', 'idPoseedor', 
            'peso', 'cantidad', 'manifiestoGuia', 'manifiestoPedido', 'usuarioCreador', 
            'cedulaUsuarioCreador', 'tarifaCodigo', 'fleteCodigo', 'latitudOrigen', 'longitudOrigen', 
            'latitudDestino', 'longitudDestino', 'codigoRemitente', 'remitente', 'latitudRemitente', 
            'longitudRemitente', 'codigoDestinatario', 'destinatario', 'latitudDestinatario', 'longitudDestinatario'
        ];

        // Ciclo en bloques de 4 días para no superar el límite de 300 registros de la API
        while ($start->lte($end)) {
            $chunkStart = $start->copy();
            $chunkEnd = $start->copy()->addDays(3); // Bloques de 4 días exactos (0, 1, 2, 3)
            
            if ($chunkEnd->gt($end)) {
                $chunkEnd = $end->copy();
            }

            $this->info("Consultando bloque seguro: {$chunkStart->format('Y-m-d')} al {$chunkEnd->format('Y-m-d')}");

            $response = Http::withHeaders([
                'Authorization' => $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->timeout(60)->post('https://plexa.colombiasoftware.net/index.php?api=Servicio.ApiPlexa.consultarInformes', [
                'modulo' => "3",
                'fecha_inicial' => $chunkStart->format('Y-m-d'),
                'fecha_final' => $chunkEnd->format('Y-m-d'),
                'codigo' => "",
                'cliente_codigo' => "",
                'limite' => "300"
            ]);

            if ($response->successful() && $response->json('success')) {
                $manifiestosBloque = $response->json('data.manifiestos') ?? [];
                $upsertData = [];
                
                foreach ($manifiestosBloque as $m) {
                    if (empty($m['manifiesto'])) continue;

                    $row = ['manifiesto' => $m['manifiesto']];
                    foreach ($columnas as $col) {
                        if ($col === 'tipoOperacion') {
                            $row[$col] = $m['tipoOperacion'] ?? $m['tipooperacion'] ?? 'SIN DEFINIR';
                        } else {
                            $row[$col] = $m[$col] ?? null;
                        }
                    }
                    
                    $row['cantidad'] = (float)($m['cantidad'] ?? 0);
                    $row['fleteRemesa'] = (float)($m['fleteRemesa'] ?? 0);
                    $row['fleteNeto'] = (float)($m['fleteNeto'] ?? 0);

                    $upsertData[] = $row;
                }

                if (count($upsertData) > 0) {
                    DB::table('silog_manifiestos')->upsert($upsertData, ['manifiesto'], $columnas);
                    $this->info(count($upsertData) . " registros sincronizados en este bloque.");
                }
            }

            // Avanzar al siguiente bloque de 4 días
            $start = $chunkEnd->copy()->addDay();
        }

        $this->info('Sincronización diferencial de 4 días finalizada con éxito.');
    }
}