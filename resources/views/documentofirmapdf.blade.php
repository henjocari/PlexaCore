<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autorización - {{ $firma->nombre }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --color-boton: #378E77;
        }

        body {
            background-color: #f1f5f9; 
            font-family: 'Verdana', sans-serif;
            color: #000000;
            margin: 0;
            padding: 40px 20px;
        }

        /* ESTILOS DEL DOCUMENTO A4 EXACTOS */
        .a4-paper {
            background: #ffffff;
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            padding: 50px 70px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            line-height: 1.5;
            box-sizing: border-box;
        }

        .pdf-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .pdf-header th, .pdf-header td {
            border: 1px solid #000000;
            padding: 10px;
            text-align: center;
            vertical-align: middle;
        }

        .pdf-title {
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
        }

        .pdf-meta {
            font-size: 12px;
            line-height: 1.4;
        }

        .pdf-text {
            text-align: justify;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .pdf-list {
            text-align: justify;
            font-size: 14px;
            margin-bottom: 15px;
            padding-left: 25px;
        }

        .pdf-list li {
            margin-bottom: 10px;
        }

        /* Espacios rellenados transparentes para un look natural */
        .inline-input {
            border: none;
            border-bottom: 1px solid #000;
            background: transparent; 
            font-family: inherit;
            font-size: 14px;
            text-align: center;
            color: #000;
            font-weight: bold;
            padding: 0px 5px;
            display: inline-block;
            min-height: 20px;
        }

        .auto-text {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Bloque de firma ajustado para evitar saltos de página */
        .signature-area {
            margin-top: 30px;
            font-size: 14px;
            page-break-inside: avoid; /* Evita que se corte la firma a la mitad al imprimir */
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 300px; 
            margin-bottom: 5px;
            margin-top: 0px;
        }

        /* BOTÓN FLOTANTE */
        .btn-print-container {
            max-width: 850px;
            margin: 0 auto 20px auto;
            text-align: right;
        }

        .btn-print {
            background: var(--color-boton);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            font-family: 'Verdana', sans-serif;
            box-shadow: 0 4px 6px rgba(55,142,119,0.2);
            transition: background 0.3s;
        }

        .btn-print:hover { background: #2a6b59; }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; padding: 0; }
            .a4-paper { box-shadow: none; border: none; padding: 0; margin: 0; max-width: 100%; }
        }
    </style>
</head>
<body>

    <div class="btn-print-container no-print">
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Guardar como PDF / Imprimir
        </button>
    </div>

    <div class="a4-paper">
        
        <table class="pdf-header">
            <tr>
                <td rowspan="2" style="width: 25%;">
                    <img src="/img/formato.png" style="max-width: 100px; height: auto;" alt="Logo Plexa">
                    <div style="font-size: 10px; margin-top: 5px; color: #378E77; font-weight: bold;"></div>
                </td>
                <td rowspan="2" style="width: 50%;" class="pdf-title">AUTORIZACIÓN DE TRATAMIENTO DE DATOS PERSONALES</td>
                <td style="width: 25%;" class="pdf-meta"><strong>FORMATO</strong><br>SGI-PS1-P1-F8</td>
            </tr>
            <tr>
                <td class="pdf-meta"><strong>Versión:</strong> 1<br>20 DE ENERO DE 2022</td>
            </tr>
        </table>

        <div class="pdf-text">
            Yo <span class="inline-input" style="width: 350px;">{{ $firma->nombre }}</span> 
            identificado (a) con cédula de ciudadanía No. <span class="inline-input" style="width: 150px;">{{ $firma->cedula }}</span> 
            expedida en <span class="inline-input" style="width: 180px;">{{ $firma->lugar_expedicion }}</span> 
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
            Se firma en la ciudad de <span class="inline-input" style="width: 150px;">{{ $firma->ciudad_firma }}</span> 
            a los <span class="auto-text">{{ $firma->created_at->format('d') }}</span> días del mes de <span class="auto-text">{{ $firma->created_at->translatedFormat('F') }}</span> del <span class="auto-text">{{ $firma->created_at->format('Y') }}</span>
        </div>

        <div class="signature-area">
            @if($firma->firma)
                <img src="{{ $firma->firma }}" style="max-height: 75px; width: auto; display: block; margin-bottom: 2px;">
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