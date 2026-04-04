<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TratamientoTexto;
use App\Models\TratamientoFirma;

class TratamientoDatosController extends Controller
{
    // 1. Mostrar la vista con los textos y colores editables
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

        return view('tratamientodedatos', compact('texto', 'firmas'));
    }

    // 2. Guardar los cambios (Textos y Colores)
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

        return back()->with('success', '¡Textos y colores actualizados correctamente!');
    }

    // 3. API para recibir los datos desde PlexaWeb (Python) ACTUALIZADO
    public function guardarFirmaApi(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|max:50',
            'lugar_expedicion' => 'required|string|max:255', // <-- Nuevo
            'ciudad_firma' => 'required|string|max:255',     // <-- Nuevo
            'acepto_terminos' => 'required',
            'firma' => 'required'                            // <-- Nuevo (Imagen base64)
        ]);

        $firma = TratamientoFirma::create([
            'nombre' => $request->nombre,
            'cedula' => $request->cedula,
            'lugar_expedicion' => $request->lugar_expedicion,
            'ciudad_firma' => $request->ciudad_firma,
            'acepto_terminos' => ($request->acepto_terminos === 'on' || $request->acepto_terminos == true || $request->acepto_terminos == 1) ? 1 : 0,
            'firma' => $request->firma,
        ]);

        return response()->json([
            'success' => true,
            'message' => '¡Firma guardada exitosamente!',
            'data' => $firma
        ], 201);
    }

    // 4. API para enviar los textos y colores a PlexaWeb (Python)
    public function obtenerTextosApi()
    {
        $texto = TratamientoTexto::first();
        
        return response()->json([
            'success' => true,
            'data' => $texto
        ]);
    }

    // 5. NUEVO: Función para generar la vista de impresión/descarga individual
    public function verDocumento($id)
    {
        $firma = TratamientoFirma::findOrFail($id);
        return view('documentofirmapdf', compact('firma'));
    }
}