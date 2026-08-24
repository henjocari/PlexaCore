<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autorización - {{ $firma->nombre }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            font-size: 13px;
            padding: 10px;
            background: #fff;
            color: #000;
        }
        .a4-paper {
            max-width: 850px;
            margin: 0 auto;
            padding: 25px 35px;
            line-height: 1.4;
        }
        .pdf-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .pdf-header th, .pdf-header td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
        }
        .pdf-title {
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }
        .pdf-meta {
            font-size: 11px;
            line-height: 1.3;
        }
        .pdf-text {
            text-align: justify;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .pdf-list {
            text-align: justify;
            font-size: 13px;
            margin-bottom: 10px;
            padding-left: 20px;
        }
        .pdf-list li {
            margin-bottom: 5px;
        }
        .inline-input {
            border: none;
            border-bottom: 1px solid #000;
            background: transparent;
            font-weight: bold;
            padding: 0 5px;
            display: inline-block;
            min-height: 18px;
        }
        .auto-text {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-area {
            margin-top: 15px;
            font-size: 13px;
            page-break-inside: avoid;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin-bottom: 5px;
        }
        .logo-img {
            max-width: 80px;
            height: auto;
        }
        .firma-img {
            max-height: 60px;
            width: auto;
            display: block;
            margin-bottom: 2px;
        }
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

    <div class="a4-paper">

        <table class="pdf-header">
            <tr>
                <td rowspan="2" style="width: 25%;">
                    <!-- INTENTO 1: Usar el Base64 pasado desde el controlador -->
                    @if(isset($logoBase64) && $logoBase64)
                        <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo Plexa">
                    @else
                        @php
                            $rutaLogo = public_path('img/formato.png');
                            $rutaLogo = str_replace('\\', '/', $rutaLogo);
                            $logoBase64Directo = null;
                            if (file_exists($rutaLogo)) {
                                $data = file_get_contents($rutaLogo);
                                if ($data !== false) {
                                    $logoBase64Directo = 'data:image/png;base64,' . base64_encode($data);
                                }
                            }
                        @endphp
                        @if($logoBase64Directo)
                            <img src="{{ $logoBase64Directo }}" class="logo-img" alt="">
                        @else
                            <!-- INTENTO 3: Usar URL pública (asset) -->
                            <img src="{{ asset('img/formato.png') }}" class="logo-img" alt="Logo Plexa" style="max-width:80px;">
                        @endif
                    @endif
                </td>
                <td rowspan="2" style="width: 50%;" class="pdf-title">AUTORIZACIÓN DE TRATAMIENTO DE DATOS PERSONALES</td>
                <td style="width: 25%;" class="pdf-meta"><strong>FORMATO</strong><br>SGI-PS1-P1-F8</td>
            </tr>
            <tr>
                <td class="pdf-meta"><strong>Versión:</strong> 1<br>20 DE ENERO DE 2022</td>
            </tr>
        </table>

        <div class="pdf-text">
            Yo <span class="inline-input" style="width: 250px;">{{ $firma->nombre }}</span>
            identificado (a) con cédula de ciudadanía No. <span class="inline-input" style="width: 120px;">{{ $firma->cedula }}</span>
            expedida en <span class="inline-input" style="width: 150px;">{{ $firma->lugar_expedicion }}</span>
            dando cumplimiento a lo dispuesto en la Ley 1581 de 2012, "Por el cual se dictan disposiciones generales para la protección de datos personales" y de conformidad con lo señalado en el Decreto 1377 de 2013, con la firma de este documento manifiesto que he sido informado por PLEXA SAS ESP de lo siguiente:
        </div>

        <ol class="pdf-list">
            <li>Consultar, verificar, reportar suministrar, analizar la información a partir de mi hoja de vida y/o documentos personales, a partir de mi solicitud de empleo durante la vigencia de mi contrato de trabajo en cualquier momento y/o prestación de algún servicio, a las centrales de información debidamente constituidas.</li>
            <li>Aplicar en cualquier momento pruebas de alcoholimetría y de detección de consumo de narcóticos o sustancias psicoactivas (en caso de ser conductor).</li>
            <li>De igual manera que dicha información pueda ser utilizada para efectos de remitir los resultados a terceros, todo ello respetando las limitaciones impuestas por las normas legales, la constitución y las autoridades competentes.</li>
        </ol>

        <div class="pdf-text">
            Teniendo en cuenta lo anterior, autorizo de manera voluntaria, previa, explícita, informada e inequívoca a PLEXA SAS ESP para tratar mis datos personales y tomar mi huella y fotografía de acuerdo con su Política de Tratamiento de Datos Personales para los fines relacionados con su objeto y en especial para fines legales, contractuales.
        </div>

        <div class="pdf-text">
            La información obtenida para el Tratamiento de mis datos personales la he suministrado de forma voluntaria y es verídica.
        </div>

        <div class="pdf-text">
            Se firma en la ciudad de <span class="inline-input" style="width: 120px;">{{ $firma->ciudad_firma }}</span>
            a los <span class="auto-text">{{ $firma->created_at->format('d') }}</span> días del mes de <span class="auto-text">{{ ucfirst($firma->created_at->locale('es')->isoFormat('MMMM')) }}</span> del <span class="auto-text">{{ $firma->created_at->format('Y') }}</span>
        </div>

        <div class="signature-area">
            @if(isset($firmaBase64Pdf) && $firmaBase64Pdf)
                <img src="{{ $firmaBase64Pdf }}" class="firma-img" alt="Firma">
            @else
                <p style="color:#999; font-size:12px;">Firma no disponible</p>
            @endif
            <div class="signature-line"></div>
            <strong>FIRMA</strong><br>
            Nombre: <span style="color: #64748b; font-weight: bold;">{{ $firma->nombre }}</span><br>
            C.C. <span style="color: #64748b; font-weight: bold;">{{ $firma->cedula }}</span>
            De <span style="color: #64748b; font-weight: bold;">{{ $firma->lugar_expedicion }}</span>
        </div>

    </div>
</body>
</html>