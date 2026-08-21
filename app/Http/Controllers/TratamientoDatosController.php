<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TratamientoTexto;
use App\Models\TratamientoFirma;
use Barryvdh\DomPDF\Facade\Pdf;

class TratamientoDatosController extends Controller
{
    // =========================================================
    // 1. MOSTRAR LA VISTA DE ADMINISTRACIÓN
    // =========================================================
    public function index()
    {
        $texto = TratamientoTexto::firstOrCreate(
            ['id' => 1],
            [
                'titulo' => 'Autorización de Tratamiento de Datos',
                'subtitulo' => 'Documento de cumplimiento a la Ley 1581 de 2012 y el Decreto 1377 de 2013 de PLEXA S.A.S E.S.P.',
                'terminos_legales' => "<ul>\n<li>Consultar, verificar, reportar, suministrar y analizar la información a partir de mi hoja de vida y/o documentos personales a las centrales de información debidamente constituidas.</li>\n<li>Aplicar en cualquier momento pruebas de alcoholimetría y de detección de consumo de narcóticos o sustancias psicoactivas (en caso de ser conductor).</li>\n<li>Que dicha información pueda ser utilizada para efectos de remitir los resultados a terceros, respetando las limitaciones impuestas por las normas legales y las autoridades competentes.</li>\n</ul>",
                'color_fondo' => '#f1f5f9',
                'color_texto' => '#1e293b',
                'color_boton' => '#378E77'
            ]
        );

        $firmas = TratamientoFirma::orderBy('created_at', 'desc')->get();

        return view(
            'tratamientodedatos',
            compact('texto', 'firmas')
        );
    }


    // =========================================================
    // 2. ACTUALIZAR TEXTOS Y COLORES
    // =========================================================
    public function update(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'nullable|string',
            'terminos_legales' => 'nullable|string',
            'color_fondo' => 'required|string',
            'color_texto' => 'required|string',
            'color_boton' => 'required|string',
        ]);

        $texto = TratamientoTexto::first();

        $texto->update([
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'terminos_legales' => $request->terminos_legales,
            'color_fondo' => $request->color_fondo,
            'color_texto' => $request->color_texto,
            'color_boton' => $request->color_boton,
        ]);

        return back()->with(
            'success',
            '¡Textos y colores actualizados correctamente!'
        );
    }


    // =========================================================
    // 3. API - GUARDAR FIRMA
    // =========================================================
    public function guardarFirmaApi(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|max:50',
            'lugar_expedicion' => 'required|string|max:255',
            'ciudad_firma' => 'required|string|max:255',
            'acepto_terminos' => 'required',
            'firma' => 'required'
        ]);


        // -----------------------------------------------------
        // GUARDAR EN BASE DE DATOS
        // -----------------------------------------------------
        $firma = TratamientoFirma::create([
            'nombre' => $request->nombre,
            'cedula' => $request->cedula,
            'lugar_expedicion' => $request->lugar_expedicion,
            'ciudad_firma' => $request->ciudad_firma,

            'acepto_terminos' => (
                $request->acepto_terminos === 'on' ||
                $request->acepto_terminos == true ||
                $request->acepto_terminos == 1
            ) ? 1 : 0,

            'firma' => $request->firma,
        ]);


        // -----------------------------------------------------
        // URL PARA VISUALIZAR EL DOCUMENTO EN HTML
        // -----------------------------------------------------
        $url_documento = route(
            'firma.documento',
            ['id' => $firma->id]
        );


        // -----------------------------------------------------
        // URL PARA DESCARGAR EL PDF REAL
        // -----------------------------------------------------
        $url_descarga = route(
            'firma.descargar',
            ['id' => $firma->id]
        );


        // -----------------------------------------------------
        // DIAGNÓSTICO
        // -----------------------------------------------------
        \Log::info('==========================================');
        \Log::info('FIRMA API - NUEVA AUTORIZACIÓN');
        \Log::info('ID FIRMA: ' . $firma->id);
        \Log::info('URL DOCUMENTO: ' . $url_documento);
        \Log::info('URL DESCARGA PDF: ' . $url_descarga);
        \Log::info('==========================================');


        // -----------------------------------------------------
        // RESPUESTA PARA FLASK
        // -----------------------------------------------------
        return response()->json([
            'success' => true,

            'message' =>
                '¡Firma guardada exitosamente!',

            // URL que permite visualizar el documento HTML
            'url_pdf' =>
                $url_documento,

            // URL que genera y descarga el PDF real
            'url_descarga' =>
                $url_descarga,

            // Información de la firma guardada
            'data' =>
                $firma

        ], 201);
    }


    // =========================================================
    // 4. API - OBTENER TEXTOS
    // =========================================================
    public function obtenerTextosApi()
    {
        $texto = TratamientoTexto::first();

        return response()->json([
            'success' => true,
            'data' => $texto
        ]);
    }


    // =========================================================
    // 5. VISUALIZAR DOCUMENTO EN HTML
    // =========================================================
    public function verDocumento($id)
    {
        $firma = TratamientoFirma::findOrFail($id);

        return view(
            'documentofirmapdf',
            compact('firma')
        );
    }


    // =========================================================
    // 6. GENERAR Y DESCARGAR PDF REAL
    // =========================================================
    public function descargarDocumento($id)
    {
        $firma = TratamientoFirma::findOrFail($id);

        // -----------------------------------------------------
        // GENERAR PDF UTILIZANDO LA MISMA VISTA
        // -----------------------------------------------------
        $pdf = Pdf::loadView(
            'documentofirmapdf',
            compact('firma')
        );

        // -----------------------------------------------------
        // CONFIGURAR TAMAÑO A4
        // -----------------------------------------------------
        $pdf->setPaper(
            'a4',
            'portrait'
        );

        // -----------------------------------------------------
        // NOMBRE DEL ARCHIVO
        // -----------------------------------------------------
        $nombreArchivo =
            'autorizacion_tratamiento_datos_' .
            preg_replace(
                '/[^A-Za-z0-9_-]/',
                '_',
                $firma->cedula
            ) .
            '.pdf';

        // -----------------------------------------------------
        // DEVOLVER PDF
        // -----------------------------------------------------
        return $pdf->download(
            $nombreArchivo
        );
    }


    // =========================================================
    // 7. GENERAR PDF PARA API / FLASK
    // =========================================================
    public function obtenerPdfApi($id)
    {
        $firma = TratamientoFirma::findOrFail($id);

        // -----------------------------------------------------
        // GENERAR PDF
        // -----------------------------------------------------
        $pdf = Pdf::loadView(
            'documentofirmapdf',
            compact('firma')
        );

        // -----------------------------------------------------
        // CONFIGURAR A4
        // -----------------------------------------------------
        $pdf->setPaper(
            'a4',
            'portrait'
        );

        // -----------------------------------------------------
        // NOMBRE DEL ARCHIVO
        // -----------------------------------------------------
        $nombreArchivo =
            'autorizacion_tratamiento_datos_' .
            preg_replace(
                '/[^A-Za-z0-9_-]/',
                '_',
                $firma->cedula
            ) .
            '.pdf';

        // -----------------------------------------------------
        // DEVOLVER PDF
        // -----------------------------------------------------
        return $pdf->download(
            $nombreArchivo
        );
    }
}