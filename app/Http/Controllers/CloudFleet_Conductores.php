<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use App\Models\Conductor;


class CloudFleet_Conductores extends Controller
{
    public function obtenerTodos()
    {
        $apiKey = env('CLOUDFLEET_API_KEY');
        $url = "https://fleet.cloudfleet.com/api/v1/people/";
        $conductores = [];

        do {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json; charset=utf-8',
            ])->get($url);

            if (!$response->successful()) {
                return response()->json([
                    'error' => 'Error al conectar con la API CloudFleet',
                    'status' => $response->status(),
                    'body' => $response->body()
                ], $response->status());
            }

            $data = $response->json();

            if (is_array($data)) {
                $conductores = array_merge($conductores, $data);
            }

            $url = $response->header('X-NextPage');

        } while ($url);

        // Filtrar conductores activos
        $conductorescloud = array_filter($conductores, function ($conductor) {
            return isset($conductor['position']) && $conductor['position'] === 'CONDUCTOR';
        });

        function sanitize($value) {
            return is_scalar($value) || is_null($value) ? $value : null;
        }
        // Guardar o actualizar en la base de datos
        foreach ($conductorescloud as $c) {

            if (empty($c['personalId'])) {
                \Log::warning('Conductor omitido por falta de cedula', ['data' => $c]);
                continue;
            }
        
            try {
                Conductor::updateOrCreate(
                    ['cedula' => sanitize($c['personalId'] ?? null)],
                    [
                        'cedula'   => sanitize($c['personalId'] ?? null),
                        'nombre'   => sanitize($c['firstName'] ?? null),
                        'apellido' => sanitize($c['lastName'] ?? null),
                        'email'    => sanitize($c['email'] ?? null),
                        'celular'  => sanitize($c['landlinePhone'] ?? null),
                        'tipo'     => sanitize($c['position'] ?? null),
                        'estado'   => sanitize($c['isActive'] ?? null),
                    ]
                );
            } catch (\Exception $e) {
                \Log::error('Error al guardar conductor', [
                    'cedula' => $c['personalId'] ?? null,
                    'error'  => $e->getMessage(),
                    'data'   => $c
                ]);
            }
        }

        //Abrir Tabla de conductores
        return redirect()->route('tablas');

        // Retornar los datos a la vista
        /*
        return view('conductores', [
            'conductores' => $conductorescloud
        ]);
        */
    }
}


#wKNdZk1.ounuSss-zRbc5Yv-pMp9rd_VqdZGku_Bp