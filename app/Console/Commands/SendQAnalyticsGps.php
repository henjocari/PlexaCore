<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GpsPosition;
use App\Models\QanalyticsLog; // Importamos el modelo del log
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Exception;

class SendQAnalyticsGps extends Command
{
    protected $signature = 'qanalytics:send';
    protected $description = 'Envía posiciones GPS a la API REST de QAnalytics (solo obligatorios)';

    private $user = 'PLEXA_T_VBT';
    private $pass = 'Jd#6yFMLgO';
    private $baseUrl = 'https://ww3.qanalytics.cl/Api_InsertaPosicion_General_test';

    public function handle()
    {
        $posiciones = GpsPosition::all(); 

        if ($posiciones->isEmpty()) {
            return Command::SUCCESS;
        }

        $datosPool = [];
        $registrosAActualizar = [];

        foreach ($posiciones as $pos) {
            $cacheKey = 'qanalytics_last_fh_' . $pos->PLACA;
            $ultimaFechaEnviada = Cache::get($cacheKey);

            if ($ultimaFechaEnviada === $pos->FH_DATO) {
                continue; 
            }

            $fechaUtc = Carbon::parse($pos->FH_DATO, 'America/Bogota')
                              ->setTimezone('UTC')
                              ->format('Y-m-d\TH:i:s');

            $ignicion = (int) $pos->IGNICION;
            if ($ignicion > 1) $ignicion = 0; 

            $datosPool[] = [
                'COD_VEH'    => (string) $pos->PLACA,
                'PLACA'      => preg_replace('/[^A-Z0-9]/', '', strtoupper($pos->PLACA)),
                'LAT'        => (float) $pos->LATITUD,
                'LON'        => (float) $pos->LONGITUD,
                'FH_SVR_GPS' => $fechaUtc,
                'FH_RPT_GPS' => $fechaUtc,
                'VEL'        => (int) $pos->VELOCIDAD,
                'SENT'       => $this->cardinalToDegrees($pos->SENTIDO), 
                'CANT_SAT'   => (int) ($pos->CANT_SATELITES ?? 0),
                'HDOP'       => (float) ($pos->HDOP ?? 0),
                'IGN'        => $ignicion
            ];

            $registrosAActualizar[$cacheKey] = $pos->FH_DATO;
        }

        if (empty($datosPool)) {
            $this->info("Sin registros nuevos para enviar.");
            return Command::SUCCESS;
        }

        try {
            $token = $this->obtenerToken();
            if (!$token) {
                $this->error("No se pudo obtener el token de autenticación.");
                return Command::FAILURE;
            }

            $response = Http::withoutVerifying()
                ->withToken($token)
                ->timeout(15)
                ->post("{$this->baseUrl}/inserta_posiciones", $datosPool);

            // --- AQUÍ VA EL CÓDIGO DEL LOG ---
            $statusCode = $response->status();
            $responseBody = $response->body();

            $logs = [];
            foreach ($datosPool as $dato) {
                $logs[] = [
                    'placa' => $dato['PLACA'],
                    'payload' => json_encode($dato),
                    'status_code' => $statusCode,
                    'response_body' => $responseBody,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            QanalyticsLog::insert($logs);
            QanalyticsLog::where('created_at', '<', now()->subHours(24))->delete();
            // ---------------------------------

            if ($response->successful()) {
                foreach ($registrosAActualizar as $key => $fh_dato) {
                    Cache::put($key, $fh_dato, now()->addDays(2));
                }
                $this->info("✔ Se enviaron " . count($datosPool) . " registros exitosamente a QAnalytics.");
            } else {
                $this->warn("El servidor respondió con error HTTP {$statusCode}: " . $responseBody);
            }

        } catch (Exception $e) {
            $this->error("Error de red HTTP: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function obtenerToken()
    {
        return Cache::remember('qanalytics_access_token', 150 * 60, function () {
            $response = Http::withoutVerifying()
                ->withBasicAuth($this->user, $this->pass)
                ->timeout(10)
                ->post("{$this->baseUrl}/Genera_Token");

            if ($response->successful()) {
                return $response->json('accessToken'); 
            }

            return null;
        });
    }

    private function cardinalToDegrees($sentido)
    {
        $map = [
            'N' => 0, 'NE' => 45, 'E' => 90, 'SE' => 135,
            'S' => 180, 'SO' => 225, 'O' => 270, 'NO' => 315
        ];
        return $map[strtoupper(trim($sentido))] ?? 0;
    }
}