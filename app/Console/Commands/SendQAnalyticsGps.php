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
    protected $description = 'Envía posiciones GPS a QAnalytics en crudo HTTP';

    public function handle()
    {
        $posiciones = GpsPosition::all(); 

        if ($posiciones->isEmpty()) {
            return Command::SUCCESS;
        }

        $xmlDatos = '';
        $registrosAActualizar = [];

        foreach ($posiciones as $pos) {
            $cacheKey = 'qanalytics_last_fh_' . $pos->PLACA;
            $ultimaFechaEnviada = Cache::get($cacheKey);

            if ($ultimaFechaEnviada === $pos->FH_DATO) {
                continue; 
            }

            $idReg = htmlspecialchars((string) $pos->ID_REG);
            $latitud = htmlspecialchars((string) $pos->LATITUD);
            $longitud = htmlspecialchars((string) $pos->LONGITUD);
            $sentido = htmlspecialchars((string) ($pos->SENTIDO ?? 0));
            $velocidad = htmlspecialchars((string) ($pos->VELOCIDAD ?? 0));
            $fhDato = htmlspecialchars(Carbon::parse($pos->FH_DATO)->format('d-m-Y H:i:s'));
            $placa = htmlspecialchars(preg_replace('/[^A-Z0-9]/', '', strtoupper($pos->PLACA)));
            $ignicion = $pos->IGNICION !== null ? (int) $pos->IGNICION : -1;

            // Formato exacto según el ejemplo de la página 9[cite: 10]
            $xmlDatos .= "<Datos>
<ID_REG>{$idReg}</ID_REG>
<LATITUD>{$latitud}</LATITUD>
<LONGITUD>{$longitud}</LONGITUD>
<SENTIDO>{$sentido}</SENTIDO>
<VELOCIDAD>{$velocidad}</VELOCIDAD>
<FH_DATO>{$fhDato}</FH_DATO>
<PLACA>{$placa}</PLACA>
<CANT_SATELITES>0</CANT_SATELITES>
<HDOP>0</HDOP>
<TEMP1>999</TEMP1>
<TEMP2>999</TEMP2>
<TEMP3>999</TEMP3>
<SENSORA_1>999</SENSORA_1>
<AP>-1</AP>
<IGNICION>{$ignicion}</IGNICION>
<PANICO>-1</PANICO>
<SENSORD_1>-1</SENSORD_1>
<TRANS>N/A</TRANS>
</Datos>
";
            $registrosAActualizar[$cacheKey] = $pos->FH_DATO;
        }

        if (empty($xmlDatos)) {
            $this->info("Sin registros nuevos para enviar.");
            return Command::SUCCESS;
        }

        // Estructura XML exacta según el ejemplo de la página 9[cite: 10]
        $soapEnvelope = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Header>
<Authentication xmlns="http://tempuri.org/">
<Usuario>ws_test</Usuario>
<Clave>$$WS17</Clave>
</Authentication>
</soap:Header>
<soap:Body>
<WM_INS_REPORTE_CLASS xmlns="http://tempuri.org/">
<Tabla>
' . $xmlDatos . '</Tabla>
</WM_INS_REPORTE_CLASS>
</soap:Body>
</soap:Envelope>';

        try {
            // Se usa el content-type y los headers exactos del manual[cite: 10]
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->withHeaders([
                    'Content-Type' => 'text/xml; charset=utf-8',
                    'SOAPAction' => '"http://tempuri.org/WM_INS_REPORTE_CLASS"'
                ])
                ->send('POST', 'http://ww3.qanalytics.cl/gps_test/service.asmx', [
                    'body' => $soapEnvelope
                ]);

            $respuestaTexto = $response->body();

            if (str_contains(strtoupper($respuestaTexto), 'INSERCIÓN CORRECTA') || str_contains(strtoupper($respuestaTexto), 'INSERCION CORRECTA')) {
                foreach ($registrosAActualizar as $key => $fh_dato) {
                    Cache::put($key, $fh_dato, now()->addDays(2));
                }
                $this->info("✔ Se enviaron " . count($registrosAActualizar) . " registros exitosamente a QAnalytics.");
            } else {
                $this->warn("El servidor respondió: " . trim(strip_tags($respuestaTexto)));
            }

        } catch (Exception $e) {
            $this->error("Error de red HTTP: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}