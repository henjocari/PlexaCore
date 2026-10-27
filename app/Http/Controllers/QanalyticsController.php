<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GpsFiltro;
use App\Models\LogQanalytics;
use App\Models\gps_VehiculoTemporal;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QanalyticsController extends Controller
{
    public function index(Request $request)
    {
        $filtros = GpsFiltro::all();
        $temporales = gps_VehiculoTemporal::orderBy('created_at', 'desc')->get();

        // Lógica de Logs con Filtro por Placa
        $queryLogs = LogQanalytics::query();
        
        if ($request->filled('buscar_placa')) {
            $queryLogs->where('placa', 'like', '%' . strtoupper(trim($request->buscar_placa)) . '%');
        }
        
        // Paginación manteniendo los parámetros de la URL (importante para que el filtro no se pierda al cambiar de página)
        $logs = $queryLogs->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // 1. Consultar el historial de Placas EXCLUYENDO mediante subconsulta las que están ACTIVAS
        $placas = DB::table('silog_manifiestos')
            ->whereNotNull('placa')
            ->where('placa', '!=', '')
            ->whereNotIn('placa', function($query) {
                $query->select('placa')
                      ->from('silog_manifiestos')
                      ->where('estado', 'ACTIVO')
                      ->whereNotNull('placa');
            })
            ->distinct()
            ->pluck('placa');

        // 2. Consultar el historial de Cisternas EXCLUYENDO mediante subconsulta las que están ACTIVAS
        $cisternas = DB::table('silog_manifiestos')
            ->whereNotNull('trailer')
            ->where('trailer', '!=', '')
            ->whereNotIn('trailer', function($query) {
                $query->select('trailer')
                      ->from('silog_manifiestos')
                      ->where('estado', 'ACTIVO')
                      ->whereNotNull('trailer');
            })
            ->distinct()
            ->pluck('trailer');

        return view('qanalytics', compact('filtros', 'logs', 'temporales', 'placas', 'cisternas'));
    }

    public function exportarLogs(Request $request)
    {
        $queryLogs = LogQanalytics::query();
        
        if ($request->filled('buscar_placa')) {
            $queryLogs->where('placa', 'like', '%' . strtoupper(trim($request->buscar_placa)) . '%');
        }

        $logs = $queryLogs->orderBy('created_at', 'desc')->get();

        $fileName = 'logs_qanalytics_' . date('Y_m_d_H_i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Usamos StreamedResponse para generar el CSV sin consumir mucha memoria
        $callback = function() use($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'PLACA', 'PAYLOAD', 'STATUS CODE', 'RESPONSE BODY', 'FECHA CREACION']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->placa,
                    $log->payload,
                    $log->status_code,
                    $log->response_body,
                    $log->created_at
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    // 3. Crear el método storeTemporal separado
    public function storeTemporal(Request $request)
    {
        $request->validate([
            'placa' => 'required|string|max:10',
            'cisterna' => 'required|string|max:10'
        ]);

        $placa = strtoupper(trim($request->placa));
        $cisterna = strtoupper(trim($request->cisterna));

        // 1. Validar si ya están ACTIVOS en manifiestos (Esto SÍ bloquea)
        $estaActivaPlaca = DB::table('silog_manifiestos')->where('placa', $placa)->where('estado', 'ACTIVO')->exists();
        if ($estaActivaPlaca) {
            return back()->with('error_swal', "La placa {$placa} ya tiene un manifiesto ACTIVO.");
        }

        $estaActivaCisterna = DB::table('silog_manifiestos')->where('trailer', $cisterna)->where('estado', 'ACTIVO')->exists();
        if ($estaActivaCisterna) {
            return back()->with('error_swal', "La cisterna {$cisterna} ya tiene un manifiesto ACTIVO.");
        }

        // 2. Comprobar si existen en el historial (Para lanzar la alerta, pero NO bloquea)
        $existePlaca = DB::table('silog_manifiestos')->where('placa', $placa)->exists();
        $existeCisterna = DB::table('silog_manifiestos')->where('trailer', $cisterna)->exists();

        // 3. Guardar el registro temporal de todos modos
        gps_VehiculoTemporal::updateOrCreate(
            ['placa' => $placa],
            ['cisterna' => $cisterna]
        );

        // 4. Retornar el mensaje dependiendo de si existían o no
        if (!$existePlaca || !$existeCisterna) {
            $msg = "Guardado con éxito, pero VERIFIQUE la información: ";
            if (!$existePlaca && !$existeCisterna) $msg .= "Ni la placa {$placa} ni la cisterna {$cisterna} existen en el historial de Manifiestos.";
            elseif (!$existePlaca) $msg .= "La placa {$placa} no existe en el historial.";
            else $msg .= "La cisterna {$cisterna} no existe en el historial.";

            return redirect()->route('qanalytics')->with('warning_swal', $msg);
        }

        return redirect()->route('qanalytics')->with('success', 'Vehículo temporal agregado para seguimiento GPS.');
    }

    public function destroyTemporal($id)
    {
        gps_VehiculoTemporal::findOrFail($id)->delete();
        return redirect()->route('qanalytics')->with('success', 'Vehículo temporal eliminado.');
    }

    public function storeFiltro(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string|max:255',
            'valor' => 'nullable|string|max:255',
            'activo' => 'required|boolean',
        ]);

        // Evitar que el campo 'valor' se inserte como NULL
        $data = $request->only(['tipo', 'valor', 'activo']);
        $data['valor'] = $data['valor'] ?? '';

        GpsFiltro::create($data);

        return redirect()->route('qanalytics')->with('success', 'Filtro creado exitosamente.');
    }

    public function updateFiltro(Request $request, $id)
    {
        $request->validate([
            'tipo' => 'required|string|max:255',
            'valor' => 'nullable|string|max:255',
            'activo' => 'required|boolean',
        ]);

        $filtro = GpsFiltro::findOrFail($id);

        // Evitar que el campo 'valor' se actualice a NULL
        $data = $request->only(['tipo', 'valor', 'activo']);
        $data['valor'] = $data['valor'] ?? '';

        $filtro->update($data);

        return redirect()->route('qanalytics')->with('success', 'Filtro actualizado exitosamente.');
    }

    public function destroyFiltro($id)
    {
        $filtro = GpsFiltro::findOrFail($id);
        $filtro->delete();

        return redirect()->route('qanalytics')->with('success', 'Filtro eliminado exitosamente.');
    }
}