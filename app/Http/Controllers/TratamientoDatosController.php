<?php

namespace App\Http\Controllers;

use App\Models\TratamientoFirma;
use App\Models\TratamientoTexto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TratamientoDatosController extends Controller
{
    // =========================================================
    // 1. INTRANET: VISTA PRINCIPAL
    // =========================================================
    public function index(Request $request)
    {
        $tabActiva = $request->has('col_page') ? 'colaboradores' : 'terceros';

        $firmasColaboradores = DB::table('tratamiento_firmas')
                                ->orderBy('created_at', 'desc')
                                ->paginate(10, ['*'], 'col_page');

        $firmasTerceros = DB::table('tratamiento_firmas_terceros')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10, ['*'], 'ter_page');

        return view('tratamientodedatos', compact('firmasColaboradores', 'firmasTerceros', 'tabActiva'));
    }

    // =========================================================
    // 2. INTRANET: DESCARGAS DINÁMICAS (PDF AL VUELO)
    // =========================================================
    public function descargarColaborador($id)
    {
        $firma = DB::table('tratamiento_firmas')->where('id', $id)->first();
        if (!$firma) abort(404, 'Documento no encontrado.');

        $htmlPdf = $this->generarPlantillaPdf($firma->nombre, $firma->cedula, $firma->fecha_autorizacion, $firma->autorizo_personales, $firma->autorizo_sensibles, $firma->firma);
        
        $pdf = Pdf::loadHtml($htmlPdf);
        $pdf->setPaper('letter', 'portrait');

        return $pdf->download('Autorizacion_Colaborador_' . $firma->cedula . '.pdf');
    }

    public function descargarTercero($id)
    {
        $firma = DB::table('tratamiento_firmas_terceros')->where('id', $id)->first();
        if (!$firma) abort(404, 'Documento no encontrado.');

        $datos = json_decode(json_encode($firma), true);
        $htmlPdf = $this->generarPlantillaPdfTerceros($datos, $firma->autorizacion_sensibles, $firma->autorizacion_transmision);
        
        $pdf = Pdf::loadHtml($htmlPdf);
        $pdf->setPaper('letter', 'portrait');

        return $pdf->download('Autorizacion_Terceros_' . $firma->identificacion . '.pdf');
    }

    // =========================================================
    // 3. RUTAS API / EXTERNAS ORIGINALES (Mantenidas para no romper nada)
    // =========================================================
    public function guardarFirmaApi(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'cedula' => 'required|string|max:20',
                'lugar_expedicion' => 'required|string|max:255',
                'ciudad_firma' => 'required|string|max:255',
                'acepto_terminos' => 'required|in:1,true,on,True',
                'firma' => 'required|string',
            ]);

            $firmaBase64 = $validated['firma'];
            if (strpos($firmaBase64, 'base64,') !== false) {
                $firmaBase64 = explode('base64,', $firmaBase64)[1];
            }
            $firmaDecodificada = base64_decode($firmaBase64);
            if ($firmaDecodificada === false) throw new \Exception('Firma inválida');

            $nombreFirma = 'firma_' . time() . '_' . Str::random(10) . '.png';
            $rutaFirma = 'firmas/' . $nombreFirma;
            Storage::disk('public')->put($rutaFirma, $firmaDecodificada);

            $firma = TratamientoFirma::create([
                'nombre' => $validated['nombre'],
                'cedula' => $validated['cedula'],
                'lugar_expedicion' => $validated['lugar_expedicion'],
                'ciudad_firma' => $validated['ciudad_firma'],
                'acepto_terminos' => 1,
                'firma' => Storage::url($rutaFirma),
            ]);

            $logoBase64 = $this->getLogoBase64();
            $firmaBase64Pdf = $this->getFirmaBase64($firma);

            $pdf = Pdf::loadView('documentofirmapdf', [
                'firma' => $firma,
                'esPdf' => true,
                'logoBase64' => $logoBase64,
                'firmaBase64Pdf' => $firmaBase64Pdf,
            ])->setPaper('A4', 'portrait')->setOptions([
                'defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                'margin_top' => 10, 'margin_bottom' => 10, 'margin_left' => 15, 'margin_right' => 15,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Autorización guardada exitosamente.',
                'url_pdf' => route('firma.documento', ['id' => $firma->id]),
                'url_descarga' => route('firma.descargar', ['id' => $firma->id]),
                'data' => [
                    'id' => $firma->id, 'nombre' => $firma->nombre, 'cedula' => $firma->cedula, 'fecha' => $firma->created_at,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error en guardarFirmaApi: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()], 500);
        }
    }

    public function verDocumento($id)
    {
        try {
            $firma = TratamientoFirma::findOrFail($id);
            $pdf = Pdf::loadView('documentofirmapdf', [
                'firma' => $firma, 'esPdf' => true, 'logoBase64' => $this->getLogoBase64(), 'firmaBase64Pdf' => $this->getFirmaBase64($firma),
            ])->setPaper('A4', 'portrait');
            return $pdf->stream('autorizacion_' . $id . '.pdf');
        } catch (\Exception $e) { abort(404, 'Documento no encontrado'); }
    }

    public function descargarDocumento($id)
    {
        try {
            $firma = TratamientoFirma::findOrFail($id);
            $pdf = Pdf::loadView('documentofirmapdf', [
                'firma' => $firma, 'esPdf' => true, 'logoBase64' => $this->getLogoBase64(), 'firmaBase64Pdf' => $this->getFirmaBase64($firma),
            ])->setPaper('A4', 'portrait');
            return $pdf->download('autorizacion_' . $id . '.pdf');
        } catch (\Exception $e) { abort(404, 'Documento no encontrado'); }
    }

    public function documento($id)
    {
        try {
            $firma = TratamientoFirma::findOrFail($id);
            $pdf = Pdf::loadView('documentofirmapdf', [
                'firma' => $firma, 'logoBase64' => $this->getLogoBase64(),
            ])->setPaper('letter', 'portrait');
            return $pdf->download('Autorizacion_Tratamiento_' . $firma->cedula . '.pdf');
        } catch (\Exception $e) { abort(404, 'Documento no encontrado'); }
    }

    public function obtenerTextosApi()
    {
        return response()->json(['titulo' => 'Autorización de Tratamiento de Datos']);
    }

    // =========================================================
    // 4. FUNCIONES AUXILIARES Y GENERACIÓN HTML A PDF
    // =========================================================
    
    // Función ÚNICA para obtener el logo (Previene el error Cannot redeclare)
    private function getLogoBase64()
    {
        $path = public_path('img/formato.png');
        if (file_exists($path)) {
            $data = file_get_contents($path);
            return 'data:image/png;base64,' . base64_encode($data);
        }
        return '';
    }

    private function getFirmaBase64($firma)
    {
        if (!$firma->firma) return null;
        $relativePath = str_replace('/storage/', 'storage/', $firma->firma);
        $path = public_path($relativePath);
        if (!file_exists($path)) {
            $filename = basename($firma->firma);
            $path = Storage::disk('public')->path('firmas/' . $filename);
        }
        if (file_exists($path)) {
            return 'data:image/png;base64,' . base64_encode(file_get_contents($path));
        }
        return null;
    }

    private function procesarFirmaBase64($firmaDataRaw)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $firmaDataRaw, $type)) {
            $firmaDataRaw = substr($firmaDataRaw, strpos($firmaDataRaw, ',') + 1);
            return 'data:image/png;base64,' . base64_encode(base64_decode($firmaDataRaw));
        }
        return $firmaDataRaw; 
    }

    private function generarPlantillaPdf($nombre, $cedula, $fecha_autorizacion, $autorizoPersonales, $autorizoSensibles, $firmaDataRaw)
    {
        $logoBase64 = $this->getLogoBase64();
        $firmaBase64 = $this->procesarFirmaBase64($firmaDataRaw);
        $fechaAutorizacionFormat = date('d/m/Y', strtotime($fecha_autorizacion));
        $tamanoLetraPdf = 12;    
        $interlineadoPdf = 1.4;  

        $siPersonales = $autorizoPersonales === 'SI' ? '☑' : '☐';
        $noPersonales = $autorizoPersonales === 'NO' ? '☑' : '☐';
        $siSensibles  = $autorizoSensibles  === 'SI' ? '☑' : '☐';
        $noSensibles  = $autorizoSensibles  === 'NO' ? '☑' : '☐';

        $generarEncabezado = function($pagina) use ($logoBase64) {
            return '<table class="pdf-header"><tr><td rowspan="3" style="width: 20%;"><img src="' . $logoBase64 . '" style="max-width: 80px; height: auto;" alt="Logo Plexa"></td><td style="width: 50%;" class="pdf-title">AVISO DE PRIVACIDAD Y AUTORIZACIÓN PARA EL TRATAMIENTO DE DATOS PERSONALES DE COLABORADORES / CANDIDATOS</td><td style="width: 30%;" class="pdf-meta"><strong>Código:</strong> F-GH-01<br><strong>Versión:</strong> 2</td></tr><tr><td class="pdf-meta" style="font-weight: bold;">01 DE SEPTIEMBRE DE 2026</td><td rowspan="2" class="pdf-meta"><strong>Página:</strong> ' . $pagina . ' de 3</td></tr><tr><td class="pdf-meta" style="font-weight: bold;">GESTIÓN HUMANA</td></tr></table>';
        };

        $cssPdf = 'body { font-family: "DejaVu Sans", sans-serif; color: #000; font-size: ' . $tamanoLetraPdf . 'px; line-height: ' . $interlineadoPdf . '; padding: 10px; } .pdf-header { width: 100%; border-collapse: collapse; margin-bottom: 20px; } .pdf-header th, .pdf-header td { border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; } .pdf-title { font-weight: bold; font-size: ' . ($tamanoLetraPdf + 1) . 'px; text-transform: uppercase; } .pdf-meta { font-size: ' . ($tamanoLetraPdf - 3) . 'px; line-height: 1.3; } .pdf-text { text-align: justify; font-size: ' . $tamanoLetraPdf . 'px; margin-bottom: 12px; } .pdf-list { text-align: justify; font-size: ' . $tamanoLetraPdf . 'px; margin-bottom: 12px; padding-left: 25px; } .pdf-list li { margin-bottom: 6px; } .pdf-list-arrows { list-style: none; text-align: justify; font-size: ' . $tamanoLetraPdf . 'px; margin-bottom: 12px; padding-left: 10px; } .pdf-list-arrows li { margin-bottom: 6px; padding-left: 18px; position: relative; } .pdf-list-arrows li::before { content: "➢"; position: absolute; left: 0; top: 0; } .checkbox-box { font-family: "DejaVu Sans", sans-serif; font-size: ' . ($tamanoLetraPdf + 4) . 'px; vertical-align: middle; margin-right: 6px; } .page-break { page-break-after: always; } .signature-area { margin-top: 20px; font-size: ' . $tamanoLetraPdf . 'px; page-break-inside: avoid; } .sig-img { max-width: 170px; height: auto; margin-bottom: 2px; }';

        return '
            <html>
            <head><meta charset="utf-8"><style>' . $cssPdf . '</style></head>
            <body>
                <!-- PÁGINA 1 -->
                ' . $generarEncabezado('1') . '
                <div class="pdf-text">En cumplimiento de la Ley 1581 de 2012, del decreto 1377 de 2013, del decreto 1074 de 2015 y demás normativa complementaria de protección de datos personales en Colombia, <strong>PLEXA SAS ESP</strong> (en adelante, el Responsable), identificada con NIT 860.515.802-1, con domicilio comercial en Calle 113 No. 7-21 Oficina 903 Torre A, en la ciudad de Bogotá, Colombia, número de contacto (1) 629 2026, organización que será el Responsable del tratamiento de datos personales.</div>
                <div class="pdf-text"><strong>✓ Autorizo</strong> de manera previa, expresa, voluntaria e informada al Responsable y al (los) Encargado(s) que se mencionen en la presente autorización, al tratamiento de los datos personales que Yo <strong>' . $nombre . '</strong> "como aparece en el pie de firma" facilite para las siguientes finalidades:</div>
                <div class="pdf-text" style="font-weight: bold; margin-bottom: 6px;">Procesos de selección y vacantes de empleo:</div>
                <ol class="pdf-list">
                    <li><strong>Postulación y participación de la vacante:</strong> Información personal necesaria para envío de pruebas técnicas y psicotécnicas, entrevistas y visitas domiciliares.</li>
                    <li><strong>Ejecución del Contrato:</strong> Datos para formalizar y desarrollar la relación laboral (nombre, documento de identificación, datos bancarios, formación, etc).</li>
                    <li><strong>Obligaciones Legales:</strong> Cumplimiento de deberes como cotizaciones a la Seguridad Social, retenciones fiscales, registro de jornada, prevención de riesgos laborales, estudios de seguridad.</li>
                    <li><strong>Gestión de Recursos Humanos:</strong> Planificación de horarios, vacaciones, formación, evaluación del desempeño, control de acceso y protección de bienes. Gestión de la seguridad y prevención de fraude.</li>
                    <li><strong>Salud y Seguridad en el trabajo:</strong> Tratamiento estrictamente limitado de la información necesaria relativa a la aptitud laboral del empleado y a la aplicación de las medidas de prevención y adaptación del puesto recomendadas por el servicio de salud laboral, sin acceso a diagnósticos ni patologías, con el único fin de cumplir las obligaciones legales y reglamentarias en materia de salud y seguridad en el trabajo.</li>
                </ol>
                <div class="pdf-text" style="font-weight: bold; margin-bottom: 5px;">Autorizo:</div>
                <div class="pdf-text"><span class="checkbox-box">' . $siPersonales . '</span> SI &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="checkbox-box">' . $noPersonales . '</span> NO</div>
                <div class="page-break"></div>

                <!-- PÁGINA 2 -->
                ' . $generarEncabezado('2') . '
                <div class="pdf-text" style="font-weight: bold;">TRATAMIENTO DATOS SENSIBLES:</div>
                <div class="pdf-text"><strong>✓ Autorizo</strong> de manera previa, expresa, voluntaria e informada al Responsable, al tratamiento de mis datos personales de <strong>carácter sensible</strong> que Yo <strong>' . $nombre . '</strong> "como aparece en el pie de firma" facilite para las siguientes finalidades:</div>
                <div class="pdf-text"><strong>Naturaleza de los Datos:</strong> Que, para la ejecución del contrato laboral, PLEXA S.A.S E.S.P requiere recolectar y tratar datos de carácter sensible, tales como: huellas dactilares, reconocimiento facial, fotografías, videos de seguridad, videos eventos organizacionales, videos para contenido en redes sociales de la compañía, datos de salud (historias clínicas, exámenes ocupacionales); para los candidatos PLEXA S.A.S E.S.P realizará el tratamiento de datos personales sensibles únicamente y exclusivamente para el proceso de selección.</div>
                <div class="pdf-text" style="font-weight: bold;">Finalidades del Tratamiento: Que mis datos sensibles serán tratados para:</div>
                <ul class="pdf-list-arrows">
                    <li>Dar cumplimiento a las obligaciones de seguridad social y salud ocupacional (SGSST).</li>
                    <li>Realizar controles de acceso biométrico a las instalaciones.</li>
                    <li>Gestión de nómina, prestaciones sociales y beneficios extralegales.</li>
                    <li>Procesos de seguridad y videovigilancia.</li>
                    <li>Mantener registro fotográfico, grabaciones de video y/o audio de los eventos y capacitaciones realizados por la organización, videos para contenido en redes sociales de la compañía.</li>
                </ul>
                <div class="pdf-text"><strong>Carácter Facultativo:</strong> Es importante señalar que el suministro de datos relacionados con información de <strong>Datos Sensibles</strong>, entendidos como aquellos que afectan la intimidad o que puedan generar algún tipo de discriminación, así como datos concernientes a menores de edad, es de carácter facultativo.</div>
                <div class="pdf-text">Que se me ha informado expresamente que, por tratarse de datos sensibles, no estoy obligado(a) a autorizar su tratamiento, salvo que exista un deber legal o contractual que lo exija.</div>
                <div class="pdf-text">La información médica detallada será tratada exclusivamente por el servicio de salud laboral, en el marco de la medicina del trabajo, y no será comunicada al empleador.</div>
                <div class="pdf-text" style="font-weight: bold; margin-bottom: 5px;">Autorizo:</div>
                <div class="pdf-text"><span class="checkbox-box">' . $siSensibles . '</span> SI &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="checkbox-box">' . $noSensibles . '</span> NO</div>
                <div class="pdf-text"><strong>Protección de Derechos:</strong> Ejercicio de derechos y disfrute de beneficios laborales, así como protección de intereses vitales.</div>
                <div class="page-break"></div>

                <!-- PÁGINA 3 -->
                ' . $generarEncabezado('3') . '
                <div class="pdf-text"><strong>Período de retención:</strong> Conservaremos sus datos durante el tiempo que se tenga el vínculo laboral, sí se trata de un exempleado máximo 10 años tras la finalización del contrato únicamente para efectos de tipo legal.</div>
                <div class="pdf-text">Los datos recolectados por el Responsable serán tratados de acuerdo con lo dispuesto en la Política de Privacidad del Responsable, disponible para consulta en el sitio web oficial www.plexa.co, la cual he podido consultar previamente.</div>
                <div class="pdf-text">El Responsable encarga el servicio de consulta de listas restrictivas y antecedentes judiciales, disciplinarios, comerciales y de comportamiento social que hace parte del estudio de seguridad, dicho encargo se delega a RISK INTERNATIONAL S.A.S, identificado con NIT 900.352.786-5 de Colombia, como Encargado para consultar, validar, almacenar, procesar, tratar y reportar mis datos personales.</div>
                <div class="pdf-text">Manifiesto conocer mis derechos como titular de los datos personales, entre ellos: acceder, rectificar, actualizar, revocar tratamiento, y/o suprimir mis datos personales, salvo que exista un deber legal o contractual que lo impida. Para ejercer cualquiera de estos derechos, podré dirigirme al canal habilitado: protecciondedatos@plexa.co o enviar comunicación escrita al domicilio del responsable ya indicado anteriormente. Asimismo, tengo derecho a presentar una reclamación ante la Superintendencia de Industria y Comercio, autoridad de control competente en Colombia.</div>
                <div class="pdf-text">Con la suscripción del presente documento y como titular de la información que he suministrado a PLEXA S.A.S ESP, y en señal de aceptación se firma.</div>
                
                <div style="margin-top: 25px; margin-bottom: 18px;">
                    <div style="font-weight: bold; margin-bottom: 2px;">NOMBRE COMPLETO DEL TITULAR:</div>
                    <div style="border-bottom: 1px solid #000; width: 100%; font-weight: bold; font-size: '.($tamanoLetraPdf + 1).'px; padding-bottom: 3px;">' . $nombre . '</div>
                </div>

                <div style="margin-bottom: 18px;">
                    <div style="font-weight: bold; margin-bottom: 2px;">DOCUMENTO DE IDENTIDAD NO.:</div>
                    <div style="border-bottom: 1px solid #000; width: 100%; font-weight: bold; font-size: '.($tamanoLetraPdf + 1).'px; padding-bottom: 3px;">' . $cedula . '</div>
                </div>

                <div style="margin-bottom: 18px;">
                    <div style="font-weight: bold; margin-bottom: 2px;">FECHA DE AUTORIZACIÓN:</div>
                    <div style="border-bottom: 1px solid #000; width: 100%; font-weight: bold; font-size: '.($tamanoLetraPdf + 1).'px; padding-bottom: 3px;">' . $fechaAutorizacionFormat . '</div>
                </div>

                <div class="signature-area" style="margin-top: 30px;">
                    <div style="font-weight: bold; margin-bottom: 5px;">FIRMA TITULAR:</div>
                    <img src="' . $firmaBase64 . '" class="sig-img" style="margin-top: 5px;"><br>
                    <div style="border-top: 1px solid #000; width: 250px; margin-top: 5px;"></div>
                </div>
            </body>
            </html>';
    }

    private function generarPlantillaPdfTerceros($datos, $authSensibles, $authTransmision)
    {
        $tamanoLetraPdf = 12;
        $interlineadoPdf = 1.4;

        $logoBase64 = $this->getLogoBase64();
        $firmaBase64 = $this->procesarFirmaBase64($datos['firma']);

        $gSi = $datos['autorizacion_general'] === 'SI' ? '☑' : '☐';
        $gNo = $datos['autorizacion_general'] === 'NO' ? '☑' : '☐';
        $sSi = $authSensibles === 'SI' ? '☑' : '☐';
        $sNo = $authSensibles === 'NO' ? '☑' : '☐';
        $tSi = $authTransmision === 'SI' ? '☑' : '☐';
        $tNo = $authTransmision === 'NO' ? '☑' : '☐';

        $calidades = explode(', ', $datos['calidad_actua'] ?? '');
        $calidades = array_map('trim', $calidades);

        $chkPropietario = in_array('Propietario', $calidades) ? '☑' : '☐';
        $chkPoseedor = in_array('Poseedor o tenedor', $calidades) ? '☑' : '☐';
        $chkConductor = in_array('Conductor', $calidades) ? '☑' : '☐';
        $chkContratista = in_array('Contratista', $calidades) ? '☑' : '☐';
        $chkProveedor = in_array('Proveedor', $calidades) ? '☑' : '☐';
        $chkRepresentante = in_array('Representante legal', $calidades) ? '☑' : '☐';

        $defaultCalidades = ['Propietario', 'Poseedor o tenedor', 'Conductor', 'Contratista', 'Proveedor', 'Representante legal'];
        $otrosArr = array_diff($calidades, $defaultCalidades);
        $otroStr = implode(', ', $otrosArr);
        $chkOtro = !empty($otroStr) ? '☑' : '☐';

        $fechaFormat = date('d/m/Y', strtotime($datos['fecha_autorizacion'] ?? date('Y-m-d'))); 

        $generarEncabezado = function($pagina) use ($logoBase64) {
            return '<table class="pdf-header"><tr><td rowspan="3" style="width: 20%;"><img src="'.$logoBase64.'" style="max-width: 80px; height: auto;" alt="Logo Plexa"></td><td style="width: 50%;" class="pdf-title">AVISO DE PRIVACIDAD Y AUTORIZACIÓN PARA EL TRATAMIENTO DE DATOS PERSONALES DE TERCEROS</td><td style="width: 30%;" class="pdf-meta"><strong>Código:</strong> F-GL-03<br><strong>Versión:</strong> 1</td></tr><tr><td class="pdf-meta" style="font-weight: bold;">01 DE SEPTIEMBRE DE 2026</td><td rowspan="2" class="pdf-meta"><strong>Página:</strong> '.$pagina.' de 5</td></tr><tr><td class="pdf-meta" style="font-weight: bold;">GESTIÓN LOGÍSTICA</td></tr></table>';
        };

        // Reducimos las márgenes verticales para ganar espacio sin tocar la letra del contenido general
        $cssPdf = '@page { margin: 30px 40px; } body { font-family: "DejaVu Sans", sans-serif; color: #000; font-size: '.$tamanoLetraPdf.'px; line-height: '.$interlineadoPdf.'; } .pdf-header { width: 100%; border-collapse: collapse; margin-bottom: 12px; } .pdf-header th, .pdf-header td { border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; } .pdf-title { font-weight: bold; font-size: '.($tamanoLetraPdf + 1).'px; text-transform: uppercase; } .pdf-meta { font-size: '.($tamanoLetraPdf - 3).'px; line-height: 1.3; } .pdf-text { text-align: justify; font-size: '.$tamanoLetraPdf.'px; margin-bottom: 10px; } .checkbox-box { font-family: "DejaVu Sans", sans-serif; font-size: '.($tamanoLetraPdf + 3).'px; vertical-align: middle; margin-right: 4px; } .page-break { page-break-after: always; }';

        return '
        <html>
        <head><meta charset="utf-8"><style>'.$cssPdf.'</style></head>
        <body>
            <!-- PAGINA 1 -->
            '.$generarEncabezado('1').'
            <div class="pdf-text" style="font-weight: bold;">1. RESPONSABLE DEL TRATAMIENTO</div>
            <div class="pdf-text">PLEXA S.A.S. E.S.P., identificada con NIT 860.515.802-1, con domicilio en Bogotá D. C., dirección Calle 113 No. 7-21, Oficina 903, Torre A y teléfono (601) 629 2026, actúa como Responsable del tratamiento de datos personales. El canal indicado en este formato para consultas y reclamos es protecciondedatos@plexa.co. La Política de Tratamiento de Información se encuentra disponible en www.plexa.co. Estos datos deberán validarse internamente antes de la aprobación del formato.</div>
            <div class="pdf-text">El tratamiento se realizará conforme al artículo 15 de la Constitución Política, la Ley 1581 de 2012, el Capítulo 25 del Decreto 1074 de 2015 y las instrucciones aplicables de la Superintendencia de Industria y Comercio, así como las normas que los modifiquen, adicionen o sustituyan.</div>
            
            <div class="pdf-text" style="font-weight: bold; margin-top: 15px;">2. ALCANCE Y AUTORIZACIÓN</div>
            <div class="pdf-text">Este formato se aplica a las personas naturales o jurídicas cuyos datos sean tratados durante la vinculación, habilitación, operación, seguimiento o desvinculación de propietarios, poseedores o tenedores de vehículos; conductores; propietarios o poseedores de cabezotes, tanques, tráileres o remolques; empresas transportadoras; contratistas; subcontratistas; proveedores; representantes legales; apoderados; contactos operativos; personal suministrado por terceros; beneficiarios y demás titulares relacionados con la operación de PLEXA S.A.S. E.S.P. Cuando intervenga una persona jurídica, la protección recae sobre los datos personales de las personas naturales o jurídicas vinculadas o que actúan en su nombre.</div>
            <div class="pdf-text">Como titular, autorizo de manera previa, expresa, libre, voluntaria, informada y verificable a PLEXA S.A.S. E.S.P., como Responsable, para recolectar, almacenar, usar, consultar, circular, transmitir, transferir, actualizar, corregir, conservar y suprimir mis datos personales, directamente o mediante Encargados debidamente autorizados, exclusivamente para las finalidades informadas en este documento.</div>
            
            <div class="pdf-text" style="font-weight: bold; margin-top: 15px; margin-bottom: 5px;">AUTORIZACIÓN GENERAL:</div>
            <div class="pdf-text"><span class="checkbox-box">'.$gSi.'</span> SI &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="checkbox-box">'.$gNo.'</span> NO</div>
            
            <div class="pdf-text" style="font-weight: bold; margin-top: 15px;">3. DATOS PERSONALES TRATADOS</div>
            <div class="pdf-text">Según la calidad del titular y cuando sean necesarios, podrán tratarse datos de identificación y contacto; información contractual, tributaria, financiera y bancaria; información del vehículo; licencias y permisos; afiliaciones y pagos al Sistema de Seguridad Social; certificaciones y capacitaciones; información de tránsito; registros de ingreso y salida; fotografías; grabaciones de videovigilancia; firma manuscrita o electrónica; ubicación o trazabilidad operacional; y resultados de verificaciones y consultas autorizadas. PLEXA S.A.S. E.S.P. aplicará los principios de finalidad, necesidad, pertinencia, proporcionalidad, acceso restringido, seguridad y circulación restringida.</div>
            <div class="page-break"></div>

            <!-- PAGINA 2 -->
            '.$generarEncabezado('2').'
            <div class="pdf-text" style="font-weight: bold;">4. FINALIDADES DEL TRATAMIENTO</div>
            <div class="pdf-text"><strong>4.1. Vinculación y conocimiento del tercero:</strong> Identificar y validar al titular y la calidad en que actúa; revisar la documentación presentada; verificar identidad, referencias, experiencia, idoneidad, licencias, permisos, certificaciones y autorizaciones; realizar estudios de seguridad, confiabilidad y debida diligencia; consultar listas vinculantes, restrictivas, sancionatorias o de control legalmente autorizadas; aplicar controles asociados a SARLAFT, PTEE, BASC y programas internos aplicables; y prevenir fraude, corrupción, soborno, lavado de activos, financiación del terrorismo y financiación de la proliferación de armas de destrucción masiva.</div>
            <div class="pdf-text"><strong>4.2. Gestión operativa y logística:</strong> Programar y controlar servicios de transporte; asignar vehículos, tanques, tráileres, conductores, rutas y operaciones; elaborar manifiestos, remesas, órdenes de cargue y demás documentos operacionales; gestionar cargues, descargues, despachos, entregas y cumplidos; verificar la trazabilidad de vehículos, conductores y mercancías; contactar al tercero durante la operación; atender novedades, contingencias y emergencias; controlar el acceso a instalaciones, patios, plantas, oficinas y zonas restringidas; y hacer seguimiento a obligaciones contractuales, operativas y documentales.</div>
            <div class="pdf-text"><strong>4.3. Seguridad vial y transporte:</strong> Verificar licencias de conducción, categorías, restricciones y vigencias; consultar información autorizada en RUNT, SIMIT, RNDC y demás fuentes oficiales aplicables; validar antecedentes de tránsito, comparendos y condiciones de habilitación; gestionar información del Plan Estratégico de Seguridad Vial; evaluar competencias, capacitaciones y certificaciones; controlar los requisitos para el transporte de hidrocarburos y mercancías peligrosas; e investigar incidentes, accidentes, siniestros y eventos viales.</div>
            <div class="pdf-text"><strong>4.4. Seguridad y salud en el trabajo:</strong> Verificar afiliaciones y pagos al Sistema de Seguridad Social; confirmar conceptos de aptitud médica ocupacional cuando resulten aplicables; gestionar únicamente restricciones o recomendaciones necesarias para la operación; registrar inducciones, capacitaciones, permisos y requisitos de seguridad; y gestionar accidentes, incidentes, emergencias y reportes asociados. PLEXA S.A.S. E.S.P. no accederá a historias clínicas completas ni tratará diagnósticos innecesarios; cuando corresponda, tratará únicamente el concepto de aptitud, las restricciones o las recomendaciones relacionadas con la actividad.</div>
            <div class="pdf-text"><strong>4.5. Seguridad física y BASC:</strong> Controlar el ingreso y salida de personas y vehículos; gestionar registros de visitantes; captar y conservar imágenes mediante videovigilancia; prevenir accesos no autorizados, contaminación de carga, hurto, fraude y actividades ilícitas; realizar investigaciones internas de seguridad; verificar antecedentes y condiciones de confiabilidad dentro de los límites legales; y proteger instalaciones, vehículos, mercancías, personas, terceros y clientes.</div>
            <div class="page-break"></div>

            <!-- PAGINA 3 -->
            '.$generarEncabezado('3').'
            <div class="pdf-text"><strong>4.6. Gestión administrativa, contractual y financiera:</strong> Elaborar, ejecutar y controlar contratos; gestionar pagos, retenciones, facturación, información tributaria y certificados bancarios; atender auditorías, reclamaciones, requerimientos y controversias; gestionar seguros, pólizas, siniestros y reclamaciones; conservar evidencias de la relación comercial y contractual; y cumplir obligaciones contables, fiscales, regulatorias y de archivo.</div>
            <div class="pdf-text"><strong>4.7. Comunicaciones y atención:</strong> Enviar comunicaciones operativas, contractuales, administrativas y de seguridad; informar cambios en procedimientos, requisitos y condiciones de operación; gestionar peticiones, quejas, reclamos, solicitudes y consultas; y atender requerimientos de autoridades administrativas, judiciales y de control.</div>
            
            <div class="pdf-text" style="font-weight: bold; margin-top: 15px;">5. CONSULTAS Y VERIFICACIONES</div>
            <div class="pdf-text">Autorizo a PLEXA S.A.S. E.S.P., dentro de los límites legales y para las finalidades indicadas, a verificar mi identidad y la información asociada a las placas de los vehículos, cabezotes, tanques, tráileres o remolques vinculados a la operación, cuando aplique; consultar RUNT, SIMIT y RNDC; consultar antecedentes judiciales, disciplinarios y fiscales; revisar listas vinculantes y restrictivas; efectuar estudios de seguridad; validar referencias comerciales y operativas, documentos, licencias, permisos y certificaciones; y consultar información pública o de acceso autorizado. Para las consultas de números de identificación y placas, PLEXA S.A.S. E.S.P. podrá transmitir a RISK INTERNATIONAL S.A.S., en calidad de Encargado del tratamiento, únicamente los datos necesarios para realizar las validaciones de identidad, seguridad, confiabilidad, vinculación y habilitación operacional requeridas. Las consultas se efectuarán mediante fuentes oficiales, autorizadas o legítimas. Esta autorización no permite acceder de forma ilimitada a información reservada, eludir controles de acceso ni utilizar los datos para finalidades propias o diferentes de las instrucciones impartidas por PLEXA S.A.S. E.S.P.</div>
            
            <div class="pdf-text" style="font-weight: bold; margin-top: 15px;">6. DATOS SENSIBLES</div>
            <div class="pdf-text">Cuando resulte necesario podrán tratarse datos biométricos, huellas, fotografías utilizadas para identificación, imágenes de videovigilancia, información sobre salud o aptitud médica y otra información cuya divulgación pueda afectar la intimidad del titular. No toda fotografía ni toda información del tercero es automáticamente sensible; su clasificación depende de su naturaleza, uso y capacidad de afectar la intimidad o generar discriminación.</div>
            <div class="pdf-text">El titular no está obligado a autorizar el tratamiento de datos sensibles, salvo cuando exista una obligación o excepción legal. PLEXA S.A.S. E.S.P. informará cuáles datos son sensibles y su finalidad, limitará el tratamiento a lo estrictamente necesario y aplicará medidas reforzadas de seguridad, acceso restringido y confidencialidad.</div>
            
            <div class="pdf-text" style="font-weight: bold; margin-top: 15px; margin-bottom: 5px;">AUTORIZACIÓN PARA DATOS SENSIBLES, CUANDO APLIQUE:</div>
            <div class="pdf-text"><span class="checkbox-box">'.$sSi.'</span> SÍ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="checkbox-box">'.$sNo.'</span> NO</div>
            <div class="page-break"></div>

            <!-- PAGINA 4 -->
            '.$generarEncabezado('4').'
            <div class="pdf-text" style="font-weight: bold;">7. TRANSMISIÓN, TRANSFERENCIA Y CIRCULACIÓN</div>
            <div class="pdf-text">Cláusula especial de transmisión de información a RISK INTERNATIONAL S.A.S. El titular autoriza de manera previa, expresa, libre, voluntaria, informada y verificable a PLEXA S.A.S. E.S.P., en calidad de Responsable del tratamiento, para transmitir a RISK INTERNATIONAL S.A.S., quien actuará como Encargado del tratamiento por cuenta y bajo las instrucciones de PLEXA S.A.S. E.S.P., los datos estrictamente necesarios para ejecutar consultas, verificaciones y estudios de seguridad relacionados con la vinculación, habilitación, permanencia, seguimiento y control de terceros y de los vehículos o equipos asociados a la operación. La transmisión podrá comprender, cuando aplique, nombres y apellidos, tipo y número de identificación, datos de contacto indispensables, calidad en la que actúa el titular, placas o identificadores de vehículos, cabezotes, tanques, tráileres o remolques, licencias, permisos, certificaciones y demás datos mínimos requeridos para validar identidad, autenticidad documental, antecedentes de acceso legal, listas vinculantes o restrictivas, condiciones de seguridad, confiabilidad y habilitación operacional.</div>
            <div class="pdf-text">RISK INTERNATIONAL S.A.S. únicamente podrá consultar fuentes oficiales, públicas, autorizadas o legítimamente accesibles y no podrá acceder a información reservada, eludir controles de acceso ni obtener datos por medios engañosos, fraudulentos o contrarios a la ley. La información transmitida deberá utilizarse exclusivamente para prestar a PLEXA S.A.S. E.S.P. los servicios contratados y no podrá emplearse para fines propios, comerciales, publicitarios, de perfilamiento no autorizado ni para adoptar decisiones distintas de las instruidas por PLEXA S.A.S. E.S.P. RISK INTERNATIONAL S.A.S. deberá garantizar la confidencialidad, integridad, disponibilidad y circulación restringida de los datos; implementar medidas administrativas, humanas y técnicas apropiadas; limitar el acceso al personal autorizado; conservar trazabilidad de las consultas; informar oportunamente incidentes de seguridad, accesos no autorizados o requerimientos de autoridades; apoyar la atención de consultas, reclamos, solicitudes de actualización, rectificación, supresión o revocatoria; y devolver, eliminar, anonimizar o bloquear la información cuando finalice el servicio o cuando lo instruya PLEXA S.A.S. E.S.P., salvo que exista un deber legal de conservación.</div>
            <div class="pdf-text">RISK INTERNATIONAL S.A.S. no podrá transferir, comunicar ni subcontratar el tratamiento a otro tercero sin autorización previa de PLEXA S.A.S. E.S.P. y sin imponerle obligaciones equivalentes de protección de datos. La transmisión no implica la pérdida de la calidad de Responsable de PLEXA S.A.S. E.S.P., quien conservará la dirección y control del tratamiento, deberá verificar la existencia del correspondiente contrato de transmisión y atenderá directamente los derechos del titular a través de los canales informados en este documento. Esta autorización no permite una entrega indiscriminada de información y se limita a los datos, finalidades y periodos necesarios para la operación y para el cumplimiento de obligaciones legales, contractuales, regulatorias y de seguridad.</div>
            
            <div class="pdf-text" style="font-weight: bold; margin-top: 15px; margin-bottom: 5px;">ACEPTACIÓN DE LA TRANSMISIÓN A RISK INTERNATIONAL S.A.S.:</div>
            <div class="pdf-text"><span class="checkbox-box">'.$tSi.'</span> SÍ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="checkbox-box">'.$tNo.'</span> NO</div>
            <div class="page-break"></div>

            <!-- PAGINA 5 -->
            '.$generarEncabezado('5').'
            <div class="pdf-text" style="font-weight: bold;">8. DERECHOS DEL TITULAR Y PROCEDIMIENTO</div>
            <div class="pdf-text">El titular podrá conocer, actualizar y rectificar sus datos; solicitar prueba de la autorización, salvo las excepciones legales; ser informado sobre el uso dado a sus datos; presentar consultas y reclamos; solicitar la supresión cuando proceda; revocar la autorización cuando sea legalmente posible; acceder gratuitamente a sus datos; abstenerse de responder preguntas sobre datos sensibles, salvo las excepciones legales; y presentar queja ante la Superintendencia de Industria y Comercio después de agotar el trámite ante PLEXA S.A.S. E.S.P. Las solicitudes podrán presentarse al correo protecciondedatos@plexa.co o mediante comunicación escrita.</div>
            
            <div class="pdf-text" style="font-weight: bold; margin-top: 15px;">9. VIGENCIA Y CONSERVACIÓN</div>
            <div class="pdf-text">Los datos se conservarán durante la vinculación y ejecución de la relación contractual u operacional y, posteriormente, durante los periodos necesarios para atender obligaciones legales, contractuales, contables, tributarias, regulatorias, de seguridad y archivo. Cumplida la finalidad y cuando no exista deber de conservación, se procederá a la supresión, anonimización o bloqueo seguro, según corresponda.</div>
            
            <div class="pdf-text" style="font-weight: bold; margin-top: 15px;">10. INDEPENDENCIA DE LA RELACIÓN LABORAL</div>
            <div class="pdf-text">Esta autorización no crea una relación laboral entre PLEXA S.A.S. E.S.P. y el tercero. Los conductores, auxiliares o demás personas suministradas por contratistas continúan bajo la dirección y responsabilidad del empleador o contratista correspondiente.</div>
            
            <!-- CONTENEDOR FINAL: Con fuente un poco más pequeña (10.5px) y márgenes comprimidos para que entre en la Pag 5 -->
            <div style="page-break-inside: avoid; margin-top: 8px; font-size: 10px;">
                <div style="font-weight: bold; font-size: 11px; margin-bottom: 8px;">11. IDENTIFICACIÓN, ACEPTACIÓN Y FIRMA</div>

                <table style="width: 100%; margin-bottom: 5px; border-collapse: collapse; font-size: 10.5px;">
                    <tr>
                        <td style="width: 42%; font-weight: bold; vertical-align: bottom;">NOMBRE COMPLETO O RAZÓN SOCIAL:</td>
                        <td style="width: 58%; border-bottom: 1px solid #000; text-align: left; font-weight: bold; padding-left: 5px; vertical-align: bottom;">'.e($datos['nombre_razon_social']).'</td>
                    </tr>
                </table>

                <table style="width: 100%; margin-bottom: 5px; border-collapse: collapse; font-size: 10.5px;">
                    <tr>
                        <td style="width: 45%; font-weight: bold; vertical-align: bottom;">TIPO Y NÚMERO DE IDENTIFICACIÓN O NIT:</td>
                        <td style="width: 55%; border-bottom: 1px solid #000; text-align: left; font-weight: bold; padding-left: 5px; vertical-align: bottom;">'.e($datos['identificacion']).'</td>
                    </tr>
                </table>

                <table style="width: 100%; margin-bottom: 5px; border-collapse: collapse; font-size: 10.5px;">
                    <tr>
                        <td style="width: 58%; font-weight: bold; vertical-align: bottom;">NOMBRE DEL REPRESENTANTE LEGAL, CUANDO APLIQUE:</td>
                        <td style="width: 42%; border-bottom: 1px solid #000; text-align: left; font-weight: bold; padding-left: 5px; vertical-align: bottom;">'.e($datos['representante_legal'] ?? '').'</td>
                    </tr>
                </table>

                <table style="width: 100%; margin-bottom: 5px; border-collapse: collapse; font-size: 10.5px;">
                    <tr>
                        <td style="width: 58%; font-weight: bold; vertical-align: bottom;">CÉDULA DEL REPRESENTANTE LEGAL, CUANDO APLIQUE:</td>
                        <td style="width: 42%; border-bottom: 1px solid #000; text-align: left; font-weight: bold; padding-left: 5px; vertical-align: bottom;">'.e($datos['cedula_representante'] ?? '').'</td>
                    </tr>
                </table>

                <div style="font-weight: bold; font-size: 10.5px; margin-top: 10px; margin-bottom: 4px;">CALIDAD EN LA QUE ACTÚA:</div>
                
                <!-- CHECKBOXES EN UNA SOLA FILA PERFECTA (Uso de tabla de 1 fila y 6 columnas) -->
                <table style="width: 100%; margin-bottom: 5px; border-collapse: collapse; font-size: 9.5px;">
                    <tr>
                        <td style="width: 16%; white-space: nowrap;"><span class="checkbox-box" style="font-size:12px;">'.$chkPropietario.'</span> Propietario</td>
                        <td style="width: 21%; white-space: nowrap;"><span class="checkbox-box" style="font-size:12px;">'.$chkPoseedor.'</span> Poseedor o tenedor</td>
                        <td style="width: 14%; white-space: nowrap;"><span class="checkbox-box" style="font-size:12px;">'.$chkConductor.'</span> Conductor</td>
                        <td style="width: 15%; white-space: nowrap;"><span class="checkbox-box" style="font-size:12px;">'.$chkContratista.'</span> Contratista</td>
                        <td style="width: 14%; white-space: nowrap;"><span class="checkbox-box" style="font-size:12px;">'.$chkProveedor.'</span> Proveedor</td>
                        <td style="width: 20%; white-space: nowrap;"><span class="checkbox-box" style="font-size:12px;">'.$chkRepresentante.'</span> Representante legal</td>
                    </tr>
                </table>

                <table style="width: 100%; margin-bottom: 6px; border-collapse: collapse; font-size: 10.5px;">
                    <tr>
                        <td style="width: 8%;"><span class="checkbox-box" style="font-size:12px;">'.$chkOtro.'</span> Otro</td>
                        <td style="width: 6%; font-weight:bold; vertical-align: bottom;">¿Cuál?</td>
                        <td style="width: 86%; border-bottom: 1px solid #000; text-align: left; padding-left: 10px; font-weight:bold; vertical-align: bottom;">'.e($otroStr).'</td>
                    </tr>
                </table>

                <table style="width: 100%; margin-bottom: 6px; border-collapse: collapse; font-size: 10.5px;">
                    <tr>
                        <td style="width: 10%; font-weight: bold; vertical-align: bottom;">DIRECCIÓN:</td>
                        <td style="width: 45%; border-bottom: 1px solid #000; text-align: left; font-weight: bold; padding-left: 5px; vertical-align: bottom;">'.e($datos['direccion']).'</td>
                        <td style="width: 10%; font-weight: bold; text-align: right; padding-right: 5px; vertical-align: bottom;">CIUDAD:</td>
                        <td style="width: 35%; border-bottom: 1px solid #000; text-align: left; font-weight: bold; padding-left: 5px; vertical-align: bottom;">'.e($datos['ciudad']).'</td>
                    </tr>
                </table>

                <table style="width: 100%; margin-bottom: 6px; border-collapse: collapse; font-size: 10.5px;">
                    <tr>
                        <td style="width: 10%; font-weight: bold; vertical-align: bottom;">TELÉFONO:</td>
                        <td style="width: 32%; border-bottom: 1px solid #000; text-align: left; font-weight: bold; padding-left: 5px; vertical-align: bottom;">'.e($datos['telefono']).'</td>
                        <td style="width: 25%; font-weight: bold; text-align: right; padding-right: 5px; vertical-align: bottom;">CORREO ELECTRÓNICO:</td>
                        <td style="width: 33%; border-bottom: 1px solid #000; text-align: left; font-weight: bold; padding-left: 5px; vertical-align: bottom;">'.e($datos['correo']).'</td>
                    </tr>
                </table>

                <table style="width: 100%; margin-bottom: 10px; border-collapse: collapse; font-size: 10.5px;">
                    <tr>
                        <td style="width: 25%; font-weight: bold; vertical-align: bottom;">FECHA DE AUTORIZACIÓN:</td>
                        <td style="width: 30%; border-bottom: 1px solid #000; text-align: left; font-weight: bold; padding-left: 5px; vertical-align: bottom;">'.$fechaFormat.'</td>
                        <td style="width: 45%;"></td>
                    </tr>
                </table>

                <div style="margin-top: 8px;">
                    <div style="font-weight: bold; font-size: 10.5px; margin-bottom: 2px;">FIRMA DEL TITULAR O REPRESENTANTE AUTORIZADO:</div>
                    <img src="'.$firmaBase64.'" style="max-width: 140px; height: auto; margin-top: 2px;"><br>
                    <div style="border-top: 1px solid #000; width: 250px; margin-top: 2px;"></div>
                </div>
            </div>
        </body>
        </html>';
    }
}