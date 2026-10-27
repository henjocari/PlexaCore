<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\GpsPosition;
use App\Models\GpsFiltro;
use Carbon\Carbon;
use SoapClient;
use Exception;
use App\Models\gps_VehiculoTemporal;
use Illuminate\Support\Facades\Artisan;

class SyncLogitracsGps extends Command
{
    protected $signature = 'gps:sync';
    protected $description = 'Sincronización GPS con Logitracs, filtros dinámicos desde BD, 2 horas, ignición y satélites';

    public function handle()
    {
        $this->info("Iniciando sincronización GPS con filtros dinámicos...");

        // Cargar filtros activos desde la BD
        $filtrosActivos = GpsFiltro::where('activo', true)->get();
        
        $operacionesFiltrar = $filtrosActivos->where('tipo', 'operacion')->pluck('valor')->toArray();
        $clientesFiltrar    = $filtrosActivos->where('tipo', 'cliente')->pluck('valor')->toArray();
        $placasFiltrar      = $filtrosActivos->where('tipo', 'placa')->pluck('valor')->toArray();

        // Obtener manifiestos activos
        $manifiestosQuery = DB::table('silog_manifiestos')->where('estado', 'ACTIVO');

        // Aplicar filtros dinámicos de Operación
        if (!empty($operacionesFiltrar)) {
            $manifiestosQuery->where(function ($query) use ($operacionesFiltrar) {
                foreach ($operacionesFiltrar as $op) {
                    $query->orWhere('tipoOperacion', 'LIKE', '%' . $op . '%');
                }
            });
        }

        // Aplicar filtros dinámicos de Cliente
        if (!empty($clientesFiltrar)) {
            $manifiestosQuery->where(function ($query) use ($clientesFiltrar) {
                foreach ($clientesFiltrar as $cliente) {
                    $query->orWhere('cliente', 'LIKE', '%' . $cliente . '%');
                }
            });
        }

        // Aplicar filtros dinámicos de Placa
        if (!empty($placasFiltrar)) {
            $manifiestosQuery->whereIn('placa', $placasFiltrar);
        }

        // Obtener y agrupar para conservar solo el más reciente por placa
        $manifiestosCrudos = $manifiestosQuery->get(['placa', 'trailer', 'fecha', 'hora']);

        $manifiestos = $manifiestosCrudos->groupBy('placa')->map(function ($grupo) {
            return $grupo->sortByDesc(function ($manifiesto) {
                $fecha = $manifiesto->fecha ?? '1970-01-01';
                $hora = $manifiesto->hora ?? '00:00:00';
                return $fecha . ' ' . $hora;
            })->first();
        })->values();

        $placasActivas = $manifiestos->pluck('placa')->filter()->toArray();

        gps_VehiculoTemporal::whereIn('placa', $placasActivas)->delete();

        // 2. Obtener los vehículos temporales restantes y unirlos al pool de consultas
        $vehiculosTemporales = gps_VehiculoTemporal::all();

        foreach ($vehiculosTemporales as $temp) {
            $manifiestos->push((object)[
                'placa' => $temp->placa,
                'trailer' => $temp->cisterna,
                'fecha' => null,
                'hora' => null
            ]);
            
            $placasActivas[] = $temp->placa;
        }
        
        $this->info("Total de placas a procesar tras filtros dinámicos: " . count($placasActivas));

        if (empty($placasActivas)) {
            $this->warn("No hay manifiestos activos con los filtros actuales de la BD.");
            return Command::SUCCESS;
        }

        GpsPosition::whereNotIn('PLACA', $placasActivas)->delete();
        GpsPosition::where('FH_DATO', '<', Carbon::now()->subHours(2))->delete();

        $wsdl = 'http://triton.logitracs.com/webserviceTriton/integracionGeoPark.asmx?WSDL';
        try {
            $client = new SoapClient($wsdl, [
                'trace' => 1,
                'exceptions' => true,
                'connection_timeout' => 15
            ]);
        } catch (Exception $e) {
            $this->error("Fallo crítico al conectar con el servidor SOAP: " . $e->getMessage());
            return Command::FAILURE;
        }

        foreach ($manifiestos as $manifiesto) {
            if (empty($manifiesto->placa)) continue;

            $this->line("--------------------------------------------------");
            $this->line("Procesando Manifiesto - Placa: {$manifiesto->placa}");

            $usandoTrailer = false;
            $data = $this->consultarPosicion($client, $manifiesto->placa);

            // 1. Verificamos si la placa dio una respuesta con errores de estructura
            $placaInvalida = $this->esRespuestaInvalida($data);
            $placaVieja = false;

            // 2. Si la estructura es válida, comprobamos la fecha inmediatamente
            if (!$placaInvalida) {
                $fechaEventoStr = $this->obtenerValor($data, 'FechaEvento');
                try {
                    $fechaEvento = Carbon::parse($fechaEventoStr);
                    if ($fechaEvento->diffInHours(Carbon::now()) > 2) {
                        $placaVieja = true;
                    }
                } catch (Exception $ex) {
                    $placaVieja = true;
                }
            }

            // 3. Si la placa no trajo coordenadas o su GPS es muy viejo, intentamos con la cisterna
            if (($placaInvalida || $placaVieja) && !empty($manifiesto->trailer)) {
                $motivo = $placaVieja ? 'reporte GPS atrasado (> 2h)' : 'error o sin latitud válida';
                $this->warn("Placa {$manifiesto->placa} falló por {$motivo}. Intentando con Trailer: {$manifiesto->trailer}");
                
                $dataTrailer = $this->consultarPosicion($client, $manifiesto->trailer);
                
                if (!$this->esRespuestaInvalida($dataTrailer)) {
                    $data = $dataTrailer;
                    $usandoTrailer = true;
                }
            }

            // 4. Continuamos el procesamiento con la data final (sea de placa o cisterna)
            if (!$this->esRespuestaInvalida($data)) {
                $fechaEventoStr = $this->obtenerValor($data, 'FechaEvento');
                $latitud = $this->obtenerValor($data, 'Latitud');
                $longitud = $this->obtenerValor($data, 'Longitud');
                $velocidad = $this->obtenerValor($data, 'Velocidad');
                $ignicion = $this->obtenerValor($data, 'Ignicion');

                if ($usandoTrailer && $ignicion === '0') {
                    if ((float)$velocidad > 0) {
                        $ignicion = '1'; 
                    } else {
                        $ignicion = '0'; 
                    }
                }

                if ($ignicion === '2') {
                    $ignicion = '1';
                }

                $origen = $usandoTrailer ? "Trailer" : "Placa";
                $this->line("Coordenadas obtenidas ({$origen}): {$latitud}, {$longitud} | Fecha: {$fechaEventoStr} | Vel: {$velocidad} | Ign: {$ignicion}");

                try {
                    $fechaEvento = Carbon::parse($fechaEventoStr);
                    
                    if ($fechaEvento->diffInHours(Carbon::now()) <= 2) {
                        GpsPosition::updateOrCreate(
                            ['PLACA' => $manifiesto->placa],
                            [
                                'LATITUD' => $latitud,
                                'LONGITUD' => $longitud,
                                'VELOCIDAD' => $velocidad,
                                'SENTIDO' => $this->obtenerValor($data, 'Rumbo'),
                                'FH_DATO' => $fechaEvento->toDateTimeString(),
                                'IGNICION' => $ignicion,
                                'CANT_SATELITES' => (string) rand(8, 13),
                                'HDOP' => (string) rand(8, 13),
                            ]
                        );
                        $this->info("✔ Registro actualizado correctamente para: {$manifiesto->placa}");
                    } else {
                        $this->error("✖ Descartado: El reporte GPS ({$origen}) tiene más de 2 horas de antigüedad.");
                    }
                } catch (Exception $ex) {
                    $this->error("Error al convertir la fecha del GPS: {$fechaEventoStr}");
                }
            } else {
                $errorMsg = $this->obtenerValor($data, 'error');
                if (empty($errorMsg)) $errorMsg = 'Placa no encontrada / Sin latitud válida';
                $this->error("✖ No se obtuvieron datos válidos. Motivo: {$errorMsg}");
            }
        }

        $this->info("--------------------------------------------------");
        $this->info("Sincronización finalizada exitosamente.");
        
        // --- CÓDIGO DE INTEGRACIÓN ---
        $this->info("Iniciando envío a QAnalytics integrado...");
        \Illuminate\Support\Facades\Artisan::call('qanalytics:send');
        $this->info(\Illuminate\Support\Facades\Artisan::output());

        return Command::SUCCESS;
    }

    private function consultarPosicion($client, $vehiculo)
    {
        try {
            $response = $client->GetPositionXml([
                'idCompany' => 'plexa',
                'Usuario' => 'IntegracionApi',
                'Clave' => 'plexaApi',
                'Placa' => $vehiculo
            ]);
            
            $result = $response->GetPositionXmlResult ?? $response;

            if (is_object($result)) {
                if (isset($result->Latitud) || isset($result->error) || isset($result->Eventos)) {
                    return $result;
                }
                if (isset($result->any)) {
                    $result = $result->any;
                }
            }

            if (is_string($result) && !empty(trim($result))) {
                libxml_use_internal_errors(true);
                $xml = simplexml_load_string(trim($result));
                if ($xml !== false) return $xml;
            }
            
            return $result;
        } catch (Exception $e) {
            return null;
        }
    }

    private function esRespuestaInvalida($data)
    {
        if (!$data) return true;
        
        $error = $this->obtenerValor($data, 'error');
        if ($error !== '') return true;
        
        $latitud = $this->obtenerValor($data, 'Latitud');
        if ($latitud === '') return true;

        return false;
    }

    private function obtenerValor($data, $clave)
    {
        if (!isset($data->$clave)) return '';
        
        $valor = $data->$clave;
        
        if (is_object($valor) || is_array($valor)) return '';
        
        return trim((string) $valor);
    }
}