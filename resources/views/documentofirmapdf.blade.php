<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autorización - {{ $firma->nombre }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            padding: 10px;
            background: #fff;
            color: #000;
        }
        .a4-paper {
            max-width: 850px;
            margin: 0 auto;
            padding: 20px 30px;
            line-height: 1.5;
        }
        .page-break { page-break-after: always; }
        .pdf-header { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .pdf-header th, .pdf-header td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
        }
        .pdf-title { font-weight: bold; font-size: 12px; text-transform: uppercase; }
        .pdf-meta { font-size: 10px; line-height: 1.3; }
        .pdf-text { text-align: justify; font-size: 12px; margin-bottom: 10px; }
        .pdf-list { text-align: justify; font-size: 12px; margin-bottom: 10px; padding-left: 20px; }
        .pdf-list li { margin-bottom: 5px; }
        .pdf-list-arrows { list-style: none; text-align: justify; font-size: 12px; margin-bottom: 10px; padding-left: 5px; }
        .pdf-list-arrows li { margin-bottom: 5px; }
        .autorizacion-line { margin-top: 8px; padding: 6px 0; font-size: 12px; }
        .checkbox-marcado {
            display: inline-block;
            width: 15px; height: 15px;
            border: 1.5px solid #000;
            background-color: #fff;
            color: #000;
            text-align: center;
            line-height: 13px;
            font-size: 11px;
            font-weight: bold;
            vertical-align: middle;
            margin-right: 4px;
        }
        .checkbox-text { font-weight: bold; margin-right: 18px; }
        .signature-area { margin-top: 15px; font-size: 12px; page-break-inside: avoid; }
        .signature-line { border-top: 1px solid #000; width: 220px; margin-bottom: 5px; margin-top: 2px; }
        .logo-img { max-width: 80px; height: auto; }
        .firma-img { max-height: 70px; width: auto; display: block; margin-bottom: 2px; }
        @if(isset($esPdf) && $esPdf)
            .no-print { display: none !important; }
        @endif
        @media print {
            .no-print { display: none !important; }
            body { background: #fff; padding: 0; }
            .a4-paper { box-shadow: none; border: none; padding: 10px; }
        }
    </style>
</head>
<body>

@php
    $fechaAut = $firma->fecha_autorizacion
        ? \Illuminate\Support\Carbon::parse($firma->fecha_autorizacion)->format('d/m/Y')
        : $firma->created_at->format('d/m/Y');
    $p = $firma->autorizo_personales ?? '';
    $s = $firma->autorizo_sensibles ?? '';
@endphp

<div class="a4-paper">

    <!-- ==================== PÁGINA 1 ==================== -->
    <table class="pdf-header">
        <tr>
            <td rowspan="3" style="width: 20%;">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo Plexa">
                @else
                    <span style="color:#999; font-size:10px;">Logo no disponible</span>
                @endif
            </td>
            <td rowspan="3" style="width: 50%;" class="pdf-title">AVISO DE PRIVACIDAD Y AUTORIZACIÓN PARA EL TRATAMIENTO DE DATOS PERSONALES DE COLABORADORES / CANDIDATOS</td>
            <td style="width: 30%;" class="pdf-meta"><strong>Código:</strong> F-GH-01<br><strong>Versión:</strong> 2</td>
        </tr>
        <tr>
            <td class="pdf-meta" style="font-weight: bold;">01 DE SEPTIEMBRE DE 2026</td>
            <td rowspan="2" class="pdf-meta"><strong>Página:</strong> 1 de 3</td>
        </tr>
        <tr>
            <td class="pdf-meta" style="font-weight: bold;">GESTIÓN HUMANA</td>
        </tr>
    </table>

    <div class="pdf-text">
        En cumplimiento de la Ley 1581 de 2012, del decreto 1377 de 2013, del decreto 1074 de 2015 y demás normativa complementaria de protección de datos personales en Colombia, <strong>PLEXA SAS ESP</strong> (en adelante, el Responsable), identificada con NIT 860.515.802-1, con domicilio comercial en Calle 113 No. 7-21 Oficina 903 Torre A, en la ciudad de Bogotá, Colombia, número de contacto (1) 629 2026, organización que será el Responsable del tratamiento de datos personales.
    </div>

    <div class="pdf-text">
        <strong>✓ Autorizo</strong> de manera previa, expresa, voluntaria e informada al Responsable y al (los) Encargado(s) que se mencionen en la presente autorización, al tratamiento de los datos personales que Yo <strong style="text-decoration: underline;">{{ $firma->nombre }}</strong> "como aparece en el pie de firma" facilite para las siguientes finalidades:
    </div>

    <div class="pdf-text" style="font-weight: bold; margin-bottom: 4px;">Procesos de selección y vacantes de empleo:</div>
    <ol class="pdf-list">
        <li><strong>Postulación y participación de la vacante:</strong> Información personal necesaria para envío de pruebas técnicas y psicotécnicas, entrevistas y visitas domiciliares.</li>
        <li><strong>Ejecución del Contrato:</strong> Datos para formalizar y desarrollar la relación laboral (nombre, documento de identificación, datos bancarios, formación, etc).</li>
        <li><strong>Obligaciones Legales:</strong> Cumplimiento de deberes como cotizaciones a la Seguridad Social, retenciones fiscales, registro de jornada, prevención de riesgos laborales, estudios de seguridad.</li>
        <li><strong>Gestión de Recursos Humanos:</strong> Planificación de horarios, vacaciones, formación, evaluación del desempeño, control de acceso y protección de bienes. Gestión de la seguridad y prevención de fraude.</li>
        <li><strong>Salud y Seguridad en el trabajo:</strong> Tratamiento estrictamente limitado de la información necesaria relativa a la aptitud laboral del empleado y a la aplicación de las medidas de prevención y adaptación del puesto recomendadas por el servicio de salud laboral, sin acceso a diagnósticos ni patologías, con el único fin de cumplir las obligaciones legales y reglamentarias en materia de salud y seguridad en el trabajo.</li>
    </ol>

    <div class="pdf-text" style="font-weight: bold; margin-bottom: 2px;">Autorizo:</div>
    <div class="autorizacion-line">
        <span class="checkbox-marcado">@if($p === 'SI')✓@endif</span><span class="checkbox-text">SI</span>
        <span class="checkbox-marcado">@if($p === 'NO')✓@endif</span><span class="checkbox-text">NO</span>
    </div>

    <!-- ==================== PÁGINA 2 ==================== -->
    <div class="page-break"></div>

    <table class="pdf-header">
        <tr>
            <td rowspan="3" style="width: 20%;">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo Plexa">
                @else
                    <span style="color:#999; font-size:10px;">Logo no disponible</span>
                @endif
            </td>
            <td rowspan="3" style="width: 50%;" class="pdf-title">AVISO DE PRIVACIDAD Y AUTORIZACIÓN PARA EL TRATAMIENTO DE DATOS PERSONALES DE COLABORADORES / CANDIDATOS</td>
            <td style="width: 30%;" class="pdf-meta"><strong>Código:</strong> F-GH-01<br><strong>Versión:</strong> 2</td>
        </tr>
        <tr>
            <td class="pdf-meta" style="font-weight: bold;">01 DE SEPTIEMBRE DE 2026</td>
            <td rowspan="2" class="pdf-meta"><strong>Página:</strong> 2 de 3</td>
        </tr>
        <tr>
            <td class="pdf-meta" style="font-weight: bold;">GESTIÓN HUMANA</td>
        </tr>
    </table>

    <div class="pdf-text" style="font-weight: bold;">TRATAMIENTO DATOS SENSIBLES:</div>

    <div class="pdf-text">
        <strong>✓ Autorizo</strong> de manera previa, expresa, voluntaria e informada al Responsable, al tratamiento de mis datos personales de <strong>carácter sensible</strong> que Yo <strong style="text-decoration: underline;">{{ $firma->nombre }}</strong> "como aparece en el pie de firma" facilite para las siguientes finalidades:
    </div>

    <div class="pdf-text">
        <strong>Naturaleza de los Datos:</strong> Que, para la ejecución del contrato laboral, PLEXA S.A.S E.S.P requiere recolectar y tratar datos de carácter sensible, tales como: huellas dactilares, reconocimiento facial, fotografías, videos de seguridad, videos eventos organizacionales, videos para contenido en redes sociales de la compañía, datos de salud (historias clínicas, exámenes ocupacionales); para los candidatos PLEXA S.A.S E.S.P realizará el tratamiento de datos personales sensibles únicamente y exclusivamente para el proceso de selección.
    </div>

    <div class="pdf-text" style="font-weight: bold;">Finalidades del Tratamiento: Que mis datos sensibles serán tratados para:</div>
    <ul class="pdf-list-arrows">
        <li>➢ Dar cumplimiento a las obligaciones de seguridad social y salud ocupacional (SGSST).</li>
        <li>➢ Realizar controles de acceso biométrico a las instalaciones.</li>
        <li>➢ Gestión de nómina, prestaciones sociales y beneficios extralegales.</li>
        <li>➢ Procesos de seguridad y videovigilancia.</li>
        <li>➢ Mantener registro fotográfico, grabaciones de video y/o audio de los eventos y capacitaciones realizados por la organización, videos para contenido en redes sociales de la compañía.</li>
    </ul>

    <div class="pdf-text">
        <strong>Carácter Facultativo:</strong> Es importante señalar que el suministro de datos relacionados con información de <strong>Datos Sensibles</strong>, entendidos como aquellos que afectan la intimidad o que puedan generar algún tipo de discriminación, así como datos concernientes a menores de edad, es de carácter facultativo.
    </div>
    <div class="pdf-text">
        Que se me ha informado expresamente que, por tratarse de datos sensibles, no estoy obligado(a) a autorizar su tratamiento, salvo que exista un deber legal o contractual que lo exija.
    </div>
    <div class="pdf-text">
        La información médica detallada será tratada exclusivamente por el servicio de salud laboral, en el marco de la medicina del trabajo, y no será comunicada al empleador.
    </div>

    <div class="pdf-text" style="font-weight: bold; margin-bottom: 2px;">Autorizo:</div>
    <div class="autorizacion-line">
        <span class="checkbox-marcado">@if($s === 'SI')✓@endif</span><span class="checkbox-text">SI</span>
        <span class="checkbox-marcado">@if($s === 'NO')✓@endif</span><span class="checkbox-text">NO</span>
    </div>

    <div class="pdf-text">
        <strong>Protección de Derechos:</strong> Ejercicio de derechos y disfrute de beneficios laborales, así como protección de intereses vitales.
    </div>

    <!-- ==================== PÁGINA 3 ==================== -->
    <div class="page-break"></div>

    <table class="pdf-header">
        <tr>
            <td rowspan="3" style="width: 20%;">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo Plexa">
                @else
                    <span style="color:#999; font-size:10px;">Logo no disponible</span>
                @endif
            </td>
            <td rowspan="3" style="width: 50%;" class="pdf-title">AVISO DE PRIVACIDAD Y AUTORIZACIÓN PARA EL TRATAMIENTO DE DATOS PERSONALES DE COLABORADORES / CANDIDATOS</td>
            <td style="width: 30%;" class="pdf-meta"><strong>Código:</strong> F-GH-01<br><strong>Versión:</strong> 2</td>
        </tr>
        <tr>
            <td class="pdf-meta" style="font-weight: bold;">01 DE SEPTIEMBRE DE 2026</td>
            <td rowspan="2" class="pdf-meta"><strong>Página:</strong> 3 de 3</td>
        </tr>
        <tr>
            <td class="pdf-meta" style="font-weight: bold;">GESTIÓN HUMANA</td>
        </tr>
    </table>

    <div class="pdf-text">
        <strong>Período de retención:</strong> Conservaremos sus datos durante el tiempo que se tenga el vínculo laboral, sí se trata de un exempleado máximo 10 años tras la finalización del contrato únicamente para efectos de tipo legal.
    </div>

    <div class="pdf-text">
        Los datos recolectados por el Responsable serán tratados de acuerdo con lo dispuesto en la Política de Privacidad del Responsable, disponible para consulta en el sitio web oficial www.plexa.co, la cual he podido consultar previamente.
    </div>

    <div class="pdf-text">
        El Responsable encarga el servicio de consulta de listas restrictivas y antecedentes judiciales, disciplinarios, comerciales y de comportamiento social que hace parte del estudio de seguridad, dicho encargo se delega a RISK INTERNATIONAL S.A.S, identificado con NIT 900.352.786-5 de Colombia, como Encargado para consultar, validar, almacenar, procesar, tratar y reportar mis datos personales.
    </div>

    <div class="pdf-text">
        Manifiesto conocer mis derechos como titular de los datos personales, entre ellos: acceder, rectificar, actualizar, revocar tratamiento, y/o suprimir mis datos personales, salvo que exista un deber legal o contractual que lo impida. Para ejercer cualquiera de estos derechos, podré dirigirme al canal habilitado: protecciondedatos@plexa.co o enviar comunicación escrita al domicilio del responsable ya indicado anteriormente. Asimismo, tengo derecho a presentar una reclamación ante la Superintendencia de Industria y Comercio, autoridad de control competente en Colombia.
    </div>

    <div class="pdf-text">
        Con la suscripción del presente documento y como titular de la información que he suministrado a PLEXA S.A.S ESP, y en señal de aceptación se firma.
    </div>

    <div class="signature-area">
        <div style="margin-bottom: 10px;">
            <strong>NOMBRE COMPLETO DEL TITULAR:</strong> {{ $firma->nombre }}
        </div>
        <div style="margin-bottom: 10px;">
            <strong>DOCUMENTO DE IDENTIDAD NO.:</strong> {{ $firma->cedula }}
        </div>
        <div style="margin-bottom: 10px;">
            <strong>FECHA DE AUTORIZACIÓN:</strong> {{ $fechaAut }}
        </div>
        <div style="margin-top: 15px;">
            <strong>FIRMA TITULAR:</strong><br>
            @if(isset($firmaBase64Pdf) && $firmaBase64Pdf)
                <img src="{{ $firmaBase64Pdf }}" class="firma-img" alt="Firma">
            @else
                <p style="color:#999; font-size:11px;">Firma no disponible</p>
            @endif
            <div class="signature-line"></div>
            <strong>{{ $firma->nombre }}</strong><br>
            C.C. {{ $firma->cedula }}
        </div>
    </div>

</div>
</body>
</html>