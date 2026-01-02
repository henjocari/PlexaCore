<?php

namespace App\Http\Controllers;

use App\Models\HistorialHabitacion;
use App\Models\Conductor;
use Illuminate\Http\Request;

class HistorialHabitacionController extends Controller
{
    public function index()
    {
        $historial = HistorialHabitacion::orderBy('fecha', 'desc')->paginate(15);
        return view('historialhabitaciones', compact('historial'));
    }

    public function export()
    {
        $historial = HistorialHabitacion::orderBy('fecha', 'desc')->get();

        $fileName = 'historial_habitaciones' . date('Y-m-d_His') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($historial) {
            $file = fopen('php://output', 'w');

            // Encabezados con BOM para UTF-8 (para que Excel lea bien las tildes)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados de columnas
            fputcsv($file, [
                'ID',
                'Habitación',
                'Estado',
                'Cédula Conductor',
                'Nombre Conductor',
                'Usuario',
                'Fecha'
            ], ';'); // Separador punto y coma para Excel en español

            // Datos
            foreach ($historial as $registro) {
                $conductor = Conductor::where('cedula', $registro->conductor)->first();
                $nombreConductor = $conductor ? $conductor->nombre . ' ' . $conductor->apellido : 'N/A';

                fputcsv($file, [
                    $registro->id,
                    $registro->habitacion,
                    $registro->estado,
                    $registro->conductor,
                    $nombreConductor,
                    $registro->usuario,
                    \Carbon\Carbon::parse($registro->fecha)->format('d/m/Y H:i:s')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExcel()
    {
        $historial = HistorialHabitacion::orderBy('fecha', 'desc')->get();

        $fileName = 'historial_habitaciones' . date('Y-m-d_His') . '.xls';

        $headers = [
            "Content-type" => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($historial) {
            echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
            echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Historial</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
            echo '</head>';
            echo '<body>';
            echo '<table border="1">';
            
            // Encabezados
            echo '<thead>';
            echo '<tr>';
            echo '<th style="background-color: #4472C4; color: white; font-weight: bold;">ID</th>';
            echo '<th style="background-color: #4472C4; color: white; font-weight: bold;">Habitación</th>';
            echo '<th style="background-color: #4472C4; color: white; font-weight: bold;">Cédula Conductor</th>';
            echo '<th style="background-color: #4472C4; color: white; font-weight: bold;">Nombre Conductor</th>';
            echo '<th style="background-color: #4472C4; color: white; font-weight: bold;">Check In</th>';
            echo '<th style="background-color: #4472C4; color: white; font-weight: bold;">Usuario Check In</th>';
            echo '<th style="background-color: #4472C4; color: white; font-weight: bold;">Check Out</th>';
            echo '<th style="background-color: #4472C4; color: white; font-weight: bold;">Usuario Check Out</th>';
            echo '<th style="background-color: #4472C4; color: white; font-weight: bold;">Tiempo Uso</th>';
            echo '</tr>';
            echo '</thead>';
            
            // Datos
            echo '<tbody>';
            foreach ($historial as $registro) {
                $conductor = Conductor::where('cedula', $registro->conductor)->first();
                $nombreConductor = $conductor ? $conductor->nombre . ' ' . $conductor->apellido : 'N/A';
                
                echo '<tr>';
                echo '<td>' . $registro->id . '</td>';
                echo '<td>' . $registro->habitacion . '</td>';
                echo '<td>' . $registro->conductor . '</td>';
                echo '<td>' . $nombreConductor . '</td>';
                echo '<td>' . ($registro->check_in ? \Carbon\Carbon::parse($registro->check_in)->format('d/m/Y H:i:s') : '') . '</td>';
                echo '<td>' . $registro->usuario_check_in . '</td>';
                echo '<td>' . ($registro->check_out ? \Carbon\Carbon::parse($registro->check_out)->format('d/m/Y H:i:s') : '') . '</td>';
                echo '<td>' . $registro->usuario_check_out . '</td>';
                echo '<td>';
                    if ($registro->tiempo_uso) {
                        $segundos = $registro->tiempo_uso;
                        
                        // Calculamos los componentes
                        $d = floor($segundos / 86400);
                        $h = floor(($segundos % 86400) / 3600);
                        $m = floor(($segundos % 3600) / 60);
                        $s = $segundos % 60;

                        // Si hay días, los mostramos explícitamente para que Excel no se confunda
                        if ($d > 0) {
                            echo "{$d}d {$h}h {$m}m {$s}s";
                        } else {
                            // Si es menos de un día, usamos un formato que Excel reconoce fácilmente como hora
                            echo sprintf('%02d:%02d:%02d', $h, $m, $s);
                        }
                    }
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            
            echo '</table>';
            echo '</body>';
            echo '</html>';
        };

        return response()->stream($callback, 200, $headers);
    }
}