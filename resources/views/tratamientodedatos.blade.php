<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <title>Plexa Core - Tratamiento de Datos</title>
    
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        /* ==========================================
           ANIMACIONES CORE
           ========================================== */
        @keyframes slideUpFade {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-item { opacity: 0; animation: slideUpFade 0.6s ease-out forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        /* ==========================================
           DISEÑO VERTICAL (DOCUMENTO)
           ========================================== */
        .document-wrapper {
            padding: 2rem 1rem;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .td-card-vertical {
            max-width: 850px; 
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
            margin-bottom: 3rem;
        }

        /* CABECERA DEL DOCUMENTO */
        .td-header-panel {
            background: linear-gradient(135deg, #378E77, #1b634e);
            color: white;
            padding: 4rem 3rem;
            text-align: center;
            position: relative;
        }

        .td-header-panel::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            border-width: 20px 20px 0;
            border-style: solid;
            border-color: #1b634e transparent transparent transparent;
        }

        .td-icon { font-size: 3.5rem; margin-bottom: 1rem; color: #ffffff; }
        .td-title { font-size: 2.2rem; font-weight: 800; margin-bottom: 1rem; color: white;}
        .td-subtitle { font-size: 1.1rem; color: rgba(255,255,255,0.9); line-height: 1.6; max-width: 650px; margin: 0 auto;}

        /* CUERPO DEL DOCUMENTO */
        .td-body-panel {
            padding: 4rem 4rem;
        }

        /* CAMPOS FLOTANTES */
        .floating-group { position: relative; margin-bottom: 3rem; }
        
        .floating-input {
            width: 100%;
            padding: 10px 0;
            font-size: 1.15rem;
            color: #1e293b;
            background: transparent;
            border: none;
            border-bottom: 2px solid #cbd5e1;
            transition: all 0.3s ease;
        }
        
        .floating-input:focus { outline: none; border-bottom-color: #378E77; }
        
        .floating-label {
            position: absolute;
            top: 10px; left: 0;
            font-size: 1.15rem; color: #94a3b8;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .floating-input:focus ~ .floating-label,
        .floating-input:not(:placeholder-shown) ~ .floating-label,
        .floating-input[type="date"] ~ .floating-label {
            top: -22px; font-size: 0.9rem; color: #378E77; font-weight: 700;
        }

        .error-msg {
            color: #ef4444;
            font-size: 0.85rem;
            font-weight: 600;
            position: absolute;
            bottom: -22px;
            left: 0;
            display: none;
            animation: fadeIn 0.3s ease;
        }

        /* CUADRO LEGAL */
        .td-legal-box {
            background: #f8fafc;
            border-left: 5px solid #378E77;
            padding: 2rem;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #475569;
            margin-bottom: 3rem;
            line-height: 1.7;
        }
        .td-legal-box h4 { color: #1e293b; font-weight: 800; margin-bottom: 1rem; }
        .td-legal-box ul { padding-left: 1.5rem; margin-top: 1rem; }
        .td-legal-box li { margin-bottom: 0.8rem; }

        /* CHECKBOX Y BOTÓN */
        .custom-check { display: none; }
        .custom-check-label {
            position: relative; padding-left: 45px; cursor: pointer;
            font-size: 1rem; color: #334155; font-weight: 600; display: block; line-height: 1.5;
        }
        .custom-check-label::before {
            content: ''; position: absolute; left: 0; top: 0;
            width: 26px; height: 26px; border: 2px solid #cbd5e1; border-radius: 6px;
            background: white; transition: all 0.2s ease;
        }
        .custom-check-label::after {
            content: '\f00c'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
            position: absolute; left: 6px; top: 4px; font-size: 15px; color: white;
            opacity: 0; transform: scale(0); transition: all 0.2s ease;
        }
        .custom-check:checked + .custom-check-label::before {
            background: #378E77; border-color: #378E77; transform: scale(1.05);
        }
        .custom-check:checked + .custom-check-label::after { opacity: 1; transform: scale(1); }

        .btn-smart {
            background: #e2e8f0; color: #94a3b8; border: none;
            padding: 18px 30px; font-size: 1.15rem; font-weight: 700;
            border-radius: 12px; cursor: not-allowed; width: 100%;
            transition: all 0.3s ease; display: flex; justify-content: center; align-items: center; gap: 10px;
        }
        .btn-smart.unlocked {
            background: #378E77; color: white; cursor: pointer;
            box-shadow: 0 10px 25px rgba(55, 142, 119, 0.3);
        }
        .btn-smart.unlocked:hover { background: #266b58; transform: translateY(-2px); }

        @media (max-width: 768px) { .td-body-panel { padding: 2rem; } }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layouts.menu')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.cabecera')

                <div class="container-fluid document-wrapper">
                    
                    <div class="td-card-vertical animate-item">
                        
                        <div class="td-header-panel">
                            <i class="fas fa-file-contract td-icon"></i>
                            <h1 class="td-title">Autorización de Tratamiento de Datos</h1>
                            <p class="td-subtitle">Documento de cumplimiento a la Ley 1581 de 2012 y el Decreto 1377 de 2013 de PLEXA S.A.S E.S.P.</p>
                        </div>

                        <div class="td-body-panel">
                            <form action="#" method="POST" id="formDatosWeb">
                                
                                <div class="animate-item delay-1">
                                    <div class="floating-group">
                                        <input type="text" name="nombre" class="floating-input" placeholder=" " required>
                                        <label class="floating-label">1. Nombre Completo de quien autoriza</label>
                                    </div>
                                </div>

                                <div class="row animate-item delay-2">
                                    <div class="col-md-6">
                                        <div class="floating-group">
                                            <input type="text" inputmode="numeric" pattern="[0-9]*" name="cedula" id="cedulaInput" class="floating-input" placeholder=" " required>
                                            <label class="floating-label">2. Número de Documento</label>
                                            <span class="error-msg" id="cedulaError"><i class="fas fa-exclamation-circle mr-1"></i> Solo se permiten números</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="floating-group">
                                            <input type="date" name="fecha_expedicion" class="floating-input" required>
                                            <label class="floating-label">3. Fecha de Expedición</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="td-legal-box animate-item delay-3">
                                    <h4>Términos Legales</h4>
                                    <p>Con la firma y/o aceptación de este documento, manifiesto que he sido informado por <strong>PLEXA SAS ESP</strong> de lo siguiente:</p>
                                    <ul>
                                        <li>Consultar, verificar, reportar, suministrar y analizar la información a partir de mi hoja de vida y/o documentos personales a las centrales de información debidamente constituidas.</li>
                                        <li>Aplicar en cualquier momento pruebas de alcoholimetría y de detección de consumo de narcóticos o sustancias psicoactivas (en caso de ser conductor).</li>
                                        <li>Que dicha información pueda ser utilizada para efectos de remitir los resultados a terceros, respetando las limitaciones impuestas por las normas legales y las autoridades competentes.</li>
                                    </ul>
                                </div>

                                <div class="animate-item delay-4" style="margin-bottom: 2.5rem;">
                                    <input type="checkbox" id="acepto_terminos" name="acepto_terminos" class="custom-check" required>
                                    <label for="acepto_terminos" class="custom-check-label">
                                        Autorizo de manera voluntaria, previa, explícita, informada e inequívoca a PLEXA SAS ESP para tratar mis datos personales de acuerdo con su Política.
                                    </label>
                                </div>

                                <div class="animate-item delay-4">
                                    <button type="submit" id="btn-submit-anim" class="btn-smart" disabled>
                                        <i class="fas fa-lock"></i> Acepta los términos para firmar
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>
                    
                </div>
            </div>
            @include('layouts.pie')
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        // 1. VALIDACIÓN DE NÚMEROS EN CÉDULA
        const cedulaInput = document.getElementById('cedulaInput');
        const cedulaError = document.getElementById('cedulaError');

        cedulaInput.addEventListener('input', function(e) {
            const hasLetters = /[^0-9]/g.test(this.value);
            if(hasLetters) {
                cedulaError.style.display = 'block';
                this.value = this.value.replace(/[^0-9]/g, '');
                setTimeout(() => { cedulaError.style.display = 'none'; }, 2500);
            } else {
                cedulaError.style.display = 'none';
            }
        });

        // 2. BOTÓN INTERACTIVO
        document.getElementById('acepto_terminos').addEventListener('change', function() {
            const btn = document.getElementById('btn-submit-anim');
            if(this.checked) {
                btn.disabled = false;
                btn.classList.add('unlocked');
                btn.innerHTML = '<i class="fas fa-signature"></i> Firmar Digitalmente';
            } else {
                btn.disabled = true;
                btn.classList.remove('unlocked');
                btn.innerHTML = '<i class="fas fa-lock"></i> Acepta los términos para firmar';
            }
        });
    </script>
</body>
</html>