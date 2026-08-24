<?php

namespace App\Http\Controllers;

use App\Models\TratamientoFirma;
use App\Models\TratamientoTexto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class TratamientoDatosController extends Controller
{
    public function index()
    {
        $firmas = TratamientoFirma::orderBy('created_at', 'desc')->get();
        $texto = TratamientoTexto::first();
        if (!$texto) {
            $texto = TratamientoTexto::create([
                'titulo' => 'AUTORIZACIÓN DE TRATAMIENTO DE DATOS PERSONALES',
                'subtitulo' => 'Ley 1581 de 2012 y Decreto 1377 de 2013',
                'terminos_legales' => '...',
                'color_fondo' => '#ffffff',
                'color_texto' => '#000000',
                'color_boton' => '#378E77',
            ]);
        }
        return view('tratamientodedatos', compact('firmas', 'texto'));
    }

    public function update(Request $request)
    {
        $texto = TratamientoTexto::first();
        if (!$texto) {
            $texto = new TratamientoTexto();
        }
        $texto->titulo = $request->input('titulo');
        $texto->subtitulo = $request->input('subtitulo');
        $texto->terminos_legales = $request->input('terminos_legales');
        $texto->color_fondo = $request->input('color_fondo');
        $texto->color_texto = $request->input('color_texto');
        $texto->color_boton = $request->input('color_boton');
        $texto->save();
        return back()->with('success', 'Texto actualizado correctamente.');
    }

    public function guardarFirmaApi(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre'            => 'required|string|max:255',
                'cedula'            => 'required|string|max:20',
                'lugar_expedicion'  => 'required|string|max:255',
                'ciudad_firma'      => 'required|string|max:255',
                'acepto_terminos'   => 'required|in:1,true,on,True',
                'firma'             => 'required|string',
            ]);

            $firmaBase64 = $this->normalizarFirma($validated['firma']);

            $firma = TratamientoFirma::create([
                'nombre'            => $validated['nombre'],
                'cedula'            => $validated['cedula'],
                'lugar_expedicion'  => $validated['lugar_expedicion'],
                'ciudad_firma'      => $validated['ciudad_firma'],
                'acepto_terminos'   => 1,
                'firma'             => $firmaBase64,
            ]);

            // 🔍 LOG: Verificar si el logo existe
            $logoBase64 = $this->getLogoBase64();
            Log::info('Logo Base64: ' . ($logoBase64 ? 'CARGADO' : 'NO CARGADO'));

            $pdf = Pdf::loadView('documentofirmapdf', [
                'firma'          => $firma,
                'esPdf'          => true,
                'logoBase64'     => $logoBase64,
                'firmaBase64Pdf' => $firma->firma,
            ]);

            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top'    => 10,
                'margin_bottom' => 10,
                'margin_left'   => 15,
                'margin_right'  => 15,
            ]);

            $urlVer = route('firma.documento', ['id' => $firma->id]);
            $urlDescargar = route('firma.descargar', ['id' => $firma->id]);

            return response()->json([
                'success'       => true,
                'message'       => 'Autorización guardada exitosamente.',
                'url_pdf'       => $urlVer,
                'url_descarga'  => $urlDescargar,
                'data'          => [
                    'id'        => $firma->id,
                    'nombre'    => $firma->nombre,
                    'cedula'    => $firma->cedula,
                    'fecha'     => $firma->created_at,
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en guardarFirmaApi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verDocumento($id)
    {
        try {
            $firma = TratamientoFirma::findOrFail($id);
            $logoBase64 = $this->getLogoBase64();
            $firmaBase64Pdf = $this->normalizarFirma($firma->firma);

            $pdf = Pdf::loadView('documentofirmapdf', [
                'firma'          => $firma,
                'esPdf'          => true,
                'logoBase64'     => $logoBase64,
                'firmaBase64Pdf' => $firmaBase64Pdf,
            ]);

            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top'    => 10,
                'margin_bottom' => 10,
                'margin_left'   => 15,
                'margin_right'  => 15,
            ]);

            return $pdf->stream('autorizacion_' . $id . '.pdf');

        } catch (\Exception $e) {
            Log::error('Error al ver documento: ' . $e->getMessage());
            abort(404, 'Documento no encontrado');
        }
    }

    public function descargarDocumento($id)
    {
        try {
            $firma = TratamientoFirma::findOrFail($id);
            $logoBase64 = $this->getLogoBase64();
            $firmaBase64Pdf = $this->normalizarFirma($firma->firma);

            $pdf = Pdf::loadView('documentofirmapdf', [
                'firma'          => $firma,
                'esPdf'          => true,
                'logoBase64'     => $logoBase64,
                'firmaBase64Pdf' => $firmaBase64Pdf,
            ]);

            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top'    => 10,
                'margin_bottom' => 10,
                'margin_left'   => 15,
                'margin_right'  => 15,
            ]);

            return $pdf->download('autorizacion_' . $id . '.pdf');

        } catch (\Exception $e) {
            Log::error('Error al descargar documento: ' . $e->getMessage());
            abort(404, 'Documento no encontrado');
        }
    }

    private function normalizarFirma($firma)
    {
        if (!$firma) {
            return null;
        }
        if (str_starts_with($firma, 'data:image/')) {
            return $firma;
        }
        if (str_starts_with($firma, 'data:in:')) {
            return str_replace('data:in:', 'data:image/png;base64,', $firma);
        }
        if (str_starts_with($firma, 'data:image')) {
            return str_replace('data:image', 'data:image/', $firma);
        }
        if (base64_decode($firma, true) !== false) {
            return 'data:image/png;base64,' . $firma;
        }
        return $firma;
    }

    private function getLogoBase64()
    {
        // 🔍 1. Intentar con la ruta correcta
        $path = public_path('img/formato.png');
        Log::info("🔍 Buscando logo en: " . $path);
        
        if (file_exists($path)) {
            $data = file_get_contents($path);
            if ($data !== false) {
                $base64 = 'data:image/png;base64,' . base64_encode($data);
                Log::info("✅ Logo convertido a Base64. Tamaño: " . strlen($base64) . " bytes");
                return $base64;
            }
        }

        // 🔍 2. Si no funciona, intentar con base_path
        $path = base_path('public/img/formato.png');
        if (file_exists($path)) {
            $data = file_get_contents($path);
            if ($data !== false) {
                $base64 = 'data:image/png;base64,' . base64_encode($data);
                Log::info("✅ Logo convertido a Base64 (base_path). Tamaño: " . strlen($base64) . " bytes");
                return $base64;
            }
        }

        Log::error("❌ Logo NO encontrado en ninguna ruta");
        return null;
    }

    public function obtenerTextosApi()
    {
        $texto = TratamientoTexto::first();
        return response()->json([
            'titulo' => $texto ? $texto->titulo : 'Autorización de Tratamiento de Datos',
        ]);
    }
}