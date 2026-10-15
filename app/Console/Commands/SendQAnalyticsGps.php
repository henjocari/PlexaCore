<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GpsPosition;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Exception;

class SendQAnalyticsGps extends Command
{
    protected $signature = 'qanalytics:send';
    protected $description = 'Envía posiciones GPS a la API REST de QAnalytics (solo obligatorios)';

    // Credenciales proporcionadas en la nueva documentación[cite: 15]
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

            // Convertir la fecha local a UTC (GMT 0) según requerimiento[cite: 15]
            $fechaUtc = Carbon::parse($pos->FH_DATO, 'America/Bogota')
                              ->setTimezone('UTC')
                              ->format('Y-m-d\TH:i:s');

            $ignicion = (int) $pos->IGNICION;
            if ($ignicion > 1) $ignicion = 0; // IGN solo acepta 0 o 1[cite: 15]

            // Solo campos obligatorios según documentación[cite: 15]
            $datosPool[] = [
                'COD_VEH'    => (string) $pos->PLACA,
                'PLACA'      => preg_replace('/[^A-Z0-9]/', '', strtoupper($pos->PLACA)),
                'LAT'        => (float) $pos->LATITUD,
                'LON'        => (float) $pos->LONGITUD,
                'FH_SVR_GPS' => $fechaUtc,
                'FH_RPT_GPS' => $fechaUtc,
                'VEL'        => (int) $pos->VELOCIDAD,
                'SENT'       => $this->cardinalToDegrees($pos->SENTIDO), // Convertir a grados 0-360[cite: 15]
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

            // Enviar datos al endpoint de inserción con el Bearer token[cite: 15]
            $response = Http::withoutVerifying()
                ->withToken($token)
                ->timeout(15)
                ->post("{$this->baseUrl}/inserta_posiciones", $datosPool);

            if ($response->successful()) {
                foreach ($registrosAActualizar as $key => $fh_dato) {
                    Cache::put($key, $fh_dato, now()->addDays(2));
                }
                $this->info("✔ Se enviaron " . count($datosPool) . " registros exitosamente a QAnalytics.");
            } else {
                $this->warn("El servidor respondió con error HTTP {$response->status()}: " . $response->body());
            }

        } catch (Exception $e) {
            $this->error("Error de red HTTP: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function obtenerToken()
    {
        // El token dura 3 horas[cite: 15]. Lo guardamos en caché por 2.5 horas (150 mins) para reutilizarlo.
        return Cache::remember('qanalytics_access_token', 150 * 60, function () {
            // Autenticación Básica al endpoint Genera_Token[cite: 15]
            $response = Http::withoutVerifying()
                ->withBasicAuth($this->user, $this->pass)
                ->timeout(10)
                ->post("{$this->baseUrl}/Genera_Token");

            if ($response->successful()) {
                return $response->json('accessToken'); // Retorna el JWT[cite: 15]
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