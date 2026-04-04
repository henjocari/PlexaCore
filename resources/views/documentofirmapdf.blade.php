<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autorización - {{ $firma->nombre }}</title>
    <style>
        body { 
            background: #525659; 
            font-family: 'Verdana', sans-serif; 
            display: flex; 
            justify-content: center; 
            padding: 40px; 
        }
        
        /* EL MISMO DISEÑO A4 DEL ORIGINAL */
        .a4-paper {
            background: #ffffff;
            width: 850px;
            margin: 0 auto;
            padding: 60px 70px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            color: #000000;
            line-height: 1.6;
        }

        .pdf-header { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .pdf-header th, .pdf-header td { border: 1px solid #000000; padding: 12px; text-align: center; vertical-align: middle; }
        .pdf-title { font-weight: bold; font-size: 16px; text-transform: uppercase; }
        .pdf-meta { font-size: 12px; line-height: 1.4; }
        
        .pdf-text { text-align: justify; font-size: 14px; margin-bottom: 20px; }
        .pdf-list { text-align: justify; font-size: 14px; margin-bottom: 20px; padding-left: 25px; }
        .pdf-list li { margin-bottom: 10px; }

        /* EL MISMO ESTILO DE "RAYA LLENADA" QUE EL ORIGINAL */
        .inline-input {
            border-bottom: 1px solid #000;
            background: rgba(55, 142, 119, 0.05);
            font-family: inherit;
            font-size: 14px;
            text-align: center;
            color: #000;
            font-weight: bold;
            padding: 2px 5px;
            display: inline-block;
        }

        .auto-text { font-weight: bold; text-decoration: underline; }
        
        .signature-area { margin-top: 50px; font-size: 14px; }
        .signature-line { border-top: 1px solid #000; width: 300px; margin-bottom: 8px; margin-top: 5px; }

        .btn-print { background: #378E77; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; font-weight: bold; margin-bottom: 20px; width: 100%; font-size: 16px; font-family: 'Verdana', sans-serif;}
        .btn-print:hover { background: #2a6b59; }

        @media print {
            .no-print { display: none; }
            body { background: #fff; padding: 0; display: block; }
            .a4-paper { box-shadow: none; border: none; width: 100%; padding: 0; margin: 0; }
        }
    </style>
</head>
<body>
    <div>
        <button class="no-print btn-print" onclick="window.print()">🖨️ HAZ CLIC AQUÍ PARA GUARDAR COMO PDF</button>
        
        <div class="a4-paper">
            
            <table class="pdf-header">
                <tr>
                    <td rowspan="2" style="width: 25%;">
                        <img src="{{ asset('resources/img/formato.png') }}" style="max-width: 100px; height: auto;" alt="Logo Plexa">
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
                    <img src="{{ $firma->firma }}" style="max-height: 120px; display: block; margin-bottom: 5px;">
                @endif
                <div class="signature-line"></div>
                <strong>FIRMA</strong><br>
                Nombre: <span style="color: #000;">{{ $firma->nombre }}</span><br>
                C.C. <span style="color: #000;">{{ $firma->cedula }}</span> 
                De <span style="color: #000;">{{ $firma->lugar_expedicion }}</span>
            </div>

        </div>
    </div>
</body>
</html>