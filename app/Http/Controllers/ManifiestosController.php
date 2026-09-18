<?php

namespace App\Http\Controllers;

use App\Models\Manifiesto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ManifiestosController extends Controller
{
    /**
     * Carga la vista de manifiestos con los datos de la base de datos.
     * Si el usuario tiene tipo_operacion fijado, SOLO ve ese tipo.
     */
    public function index()
    {
        $usuario     = auth()->user();
        $tipoUsuario = $usuario->tipo_operacion ?? null;

        // ✅ Si el usuario tiene tipo fijado, SOLO ve ese tipo (restricción real en BD)
        if ($tipoUsuario && $tipoUsuario !== '' && $tipoUsuario !== 'Todos') {
            $manifiestos = Manifiesto::where('tipoOperacion', $tipoUsuario)
                ->orderBy('id', 'desc')
                ->get();
            $tipoFijo = $tipoUsuario;
        } else {
            $manifiestos = Manifiesto::orderBy('id', 'desc')->get();
            $tipoFijo = null;
        }

        // Tipos base que SIEMPRE deben aparecer + los que existan en BD (sin duplicados por mayúsculas)
        $tiposBase = ['PGR', 'Transporte Liquido', 'GLP'];
        $tiposBD   = Manifiesto::distinct()
            ->whereNotNull('tipoOperacion')
            ->where('tipoOperacion', '!=', '')
            ->pluck('tipoOperacion')
            ->toArray();

        $tiposOperacion = collect(array_merge($tiposBase, $tiposBD))
            ->unique(fn($t) => mb_strtolower($t))
            ->sort()
            ->values();

        return view('manifiestos', compact('manifiestos', 'tiposOperacion', 'tipoFijo'));
    }

    /**
     * 🧾 Genera y muestra el recibo / liquidación de un manifiesto en PDF.
     */
    public function recibo($id)
    {
        $manifiesto = Manifiesto::findOrFail($id);

        $data = $manifiesto->toArray();

        // La plantilla comprobante.blade.php espera la clave 'manifiesto'
        $data['manifiesto'] = $data['manifiesto'] ?? $data['manifiesto_codigo'] ?? '';

        // Normalización de valores
        $data['fleteNeto']  = (float)($data['fleteNeto']  ?? 0);
        $data['anticipo']   = (float)($data['anticipo']   ?? 0);
        $data['reteFuente'] = (float)($data['reteFuente'] ?? 0);
        $data['reteIca']    = (float)($data['reteIca']    ?? 0);

        // ✅ FOPAT VERDADERO: fleteNeto * 0.001
        $data['fopat'] = isset($data['fopat']) && $data['fopat'] !== null
            ? (float)$data['fopat']
            : round($data['fleteNeto'] * 0.001, 2);

        // Cálculos
        $data['saldo'] = $data['fleteNeto'] - $data['anticipo'];
        $data['saldoPagarCalculado'] = $data['fleteNeto']
            - $data['anticipo']
            - $data['reteFuente']
            - $data['reteIca']
            - $data['fopat'];

        $pdf = Pdf::loadView('pdf.comprobante', [
            'manifiesto'     => $data,
            'nombrePoseedor' => $data['nombre'] ?? '',
        ])->setPaper('letter', 'portrait');

        $nombrePdf = 'comprobante_' . ($data['manifiesto'] ?? $id) . '.pdf';

        if (request()->query('download')) {
            return $pdf->download($nombrePdf);
        }

        return $pdf->stream($nombrePdf);
    }
}