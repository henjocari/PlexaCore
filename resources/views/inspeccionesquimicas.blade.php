<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <title>Plexa Core - Inspección de Sustancias Químicas</title>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        :root {
            --primary-blue: #0F4C81; 
            --border-color: #e3e6f0;
        }

        .fondo-plexa {
            background: #f8f9fc url("{{ asset('img/Logo_plexa.svg') }}") center/70% no-repeat fixed;
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        /* Limita el ancho del formulario para evitar que se vea demasiado grande en monitores anchos */
        .form-container-limit {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card-custom {
            background-color: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(4px);
            border-radius: 12px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .table-custom th {
            background-color: var(--primary-blue);
            color: #ffffff;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            vertical-align: middle;
            text-align: center;
        }

        .table-custom td {
            vertical-align: middle;
            background-color: #ffffff;
        }

        .section-header {
            border-left: 4px solid var(--primary-blue);
            padding-left: 10px;
            color: var(--primary-blue);
            font-weight: 700;
        }

        .editable-text {
            border-bottom: 1px dashed #4e73df;
            padding: 2px 6px;
            display: inline-block;
            cursor: text;
        }

        .editable-text:hover, .editable-text:focus {
            background-color: #eaecf4;
            outline: none;
            border-bottom: 2px solid #4e73df;
        }

        @media print {
            body { 
                background: white !important; 
                color: black !important;
            }
            #accordionSidebar, #content-wrapper > div > nav, footer, .no-print, .floating-actions {
                display: none !important;
            }
            #content-wrapper, #content, .container-fluid {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
            .card-custom {
                box-shadow: none !important;
                border: none !important;
                background: transparent !important;
            }
            input, select, textarea {
                border: none !important;
                background: transparent !important;
                box-shadow: none !important;
                appearance: none !important;
                -webkit-appearance: none !important;
            }
            .table-custom th {
                background-color: #0F4C81 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body id="page-top">

    <!-- CONTENEDOR PRINCIPAL -->
    <div id="wrapper">

        <!-- MENÚ LATERAL IZQUIERDO -->
        @include('layouts.menu')

        <!-- CONTENEDOR DERECHO (CONTENIDO + HEADER + FOOTER) -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                
                <!-- ENCABEZADO / TOPBAR -->
                @include('layouts.cabecera')
                
                <!-- ÁREA DE CONTENIDO PRINCIPAL -->
                <div class="container-fluid fondo-plexa py-4">
                    
                    <!-- ENVOLTORIO CON ANCHO CONTROLADO -->
                    <div class="form-container-limit">
                        
                        <!-- TÍTULO DE LA PÁGINA -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800 px-3 py-2 rounded shadow-sm bg-white border-left-primary">
                                <i class="fas fa-flask mr-2 text-primary"></i> Formato de Inspección de Sustancias Químicas
                            </h1>
                            <div class="no-print mt-3 mt-sm-0">
                                <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm" onclick="abrirHistorico()">
                                    <i class="fas fa-history mr-1"></i> Histórico Local
                                </button>
                                <button type="button" class="btn btn-sm btn-success shadow-sm ml-1" onclick="iniciarGuardado()">
                                    <i class="fas fa-save mr-1"></i> Guardar Registro
                                </button>
                                <button type="button" class="btn btn-sm btn-primary shadow-sm ml-1" onclick="exportarPDFVectorial()">
                                    <i class="fas fa-print mr-1"></i> Imprimir / PDF
                                </button>
                            </div>
                        </div>

                        {{-- Mensajes de Notificación --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <strong class="d-block mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Por favor corrige los siguientes errores:</strong>
                                <ul class="mb-0 pl-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- TARJETA DEL FORMATO -->
                        <div class="card card-custom mb-4" id="documento-inspeccion">
                            <div class="card-body p-4">
                                
                                <!-- ENCABEZADO DEL FORMATO -->
                                <div class="row align-items-center border-bottom pb-3 mb-4">
                                    <div class="col-md-8">
                                        <h4 class="font-weight-bold text-primary mb-1">
                                            INSPECCIÓN DE SUSTANCIAS QUÍMICAS (SGA)
                                        </h4>
                                        <p class="text-muted small mb-0 font-weight-bold">Control de Línea Base y Matriz de Incompatibilidades - PLEXA S.A.S. E.S.P.</p>
                                    </div>
                                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                        <span class="badge badge-light border p-2 text-dark font-weight-bold">
                                            Cód: <span id="consecutivo-codigo" contenteditable="true" class="editable-text text-primary">HSEQ-F-001</span>
                                        </span>
                                        <span class="badge badge-light border p-2 text-dark font-weight-bold ml-1">
                                            Versión: 3.0
                                        </span>
                                    </div>
                                </div>

                                <form action="{{ route('inspecciones.store') }}" method="POST" id="formInspeccionQuimica">
                                    @csrf
                                    <input type="hidden" name="codigo_formato" id="input_codigo_formato" value="HSEQ-F-001">

                                    <!-- DATOS BÁSICOS -->
                                    <div class="bg-light p-3 rounded mb-4 border">
                                        <div class="row">
                                            <div class="col-md-3 mb-3 mb-md-0">
                                                <label class="text-secondary small font-weight-bold">Sede / Centro *</label>
                                                <input type="text" name="sede" id="sede" class="form-control form-control-sm" value="{{ old('sede') }}" required placeholder="Ej. Planta Mamonal">
                                            </div>
                                            <div class="col-md-3 mb-3 mb-md-0">
                                                <label class="text-secondary small font-weight-bold">Fecha *</label>
                                                <input type="date" name="fecha" id="fecha" class="form-control form-control-sm" value="{{ old('fecha', date('Y-m-d')) }}" required>
                                            </div>
                                            <div class="col-md-3 mb-3 mb-md-0">
                                                <label class="text-secondary small font-weight-bold">Área Auditada *</label>
                                                <input type="text" name="area" id="area" class="form-control form-control-sm" value="{{ old('area') }}" required placeholder="Ej. Cuarto Lubricantes">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="text-secondary small font-weight-bold">Evaluador *</label>
                                                <input type="text" name="evaluador" id="evaluador" class="form-control form-control-sm" value="{{ old('evaluador') }}" required placeholder="Nombre completo">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TIPO DE INSPECCIÓN -->
                                    <div class="form-group bg-white p-3 border rounded mb-4">
                                        <label class="text-primary font-weight-bold small d-block mb-2">
                                            <i class="fas fa-tasks mr-1"></i> Tipo de Inspección
                                        </label>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="tipo1" name="tipo_inspeccion" value="Linea Base" class="custom-control-input" checked>
                                            <label class="custom-control-label font-weight-bold text-dark" for="tipo1">Línea Base (Inicial/Anual)</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="tipo2" name="tipo_inspeccion" value="Semanal" class="custom-control-input">
                                            <label class="custom-control-label font-weight-bold text-dark" for="tipo2">Rutinaria Semanal</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="tipo3" name="tipo_inspeccion" value="Mensual" class="custom-control-input">
                                            <label class="custom-control-label font-weight-bold text-dark" for="tipo3">Rutinaria Mensual</label>
                                        </div>
                                    </div>

                                    <!-- SECCIÓN 1: INVENTARIO -->
                                    <h6 class="section-header mb-3">
                                        1. INVENTARIO (DOCUMENTAL Y ETIQUETADO)
                                    </h6>
                                    <p class="text-muted small italic mb-2">Nota: Registrar productos almacenados en la zona.</p>

                                    <div class="table-responsive mb-3">
                                        <table class="table table-bordered table-sm table-custom" id="tabla-inventario">
                                            <thead>
                                                <tr>
                                                    <th style="width: 25%">Nombre Comercial del Producto</th>
                                                    <th style="width: 20%">Uso / Área</th>
                                                    <th style="width: 15%">Presentación / Vol.</th>
                                                    <th style="width: 15%">¿FDS Disponible?</th>
                                                    <th style="width: 15%">¿Etiqueta Cumple?</th>
                                                    <th class="no-print" style="width: 10%">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody id="inventory-body">
                                                <tr>
                                                    <td><input type="text" name="inv_nombre[]" class="form-control form-control-sm" placeholder="Ej. Aceite SAE 40"></td>
                                                    <td><input type="text" name="inv_uso[]" class="form-control form-control-sm" placeholder="Lubricación"></td>
                                                    <td><input type="text" name="inv_vol[]" class="form-control form-control-sm" placeholder="Tambor 55 Gal"></td>
                                                    <td>
                                                        <select name="inv_fds[]" class="form-control form-control-sm">
                                                            <option value=""></option>
                                                            <option>SÍ</option>
                                                            <option>NO</option>
                                                            <option>N/A</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="inv_etiqueta[]" class="form-control form-control-sm">
                                                            <option value=""></option>
                                                            <option>SÍ</option>
                                                            <option>NO</option>
                                                            <option>N/A</option>
                                                        </select>
                                                    </td>
                                                    <td class="no-print text-center"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary no-print mb-4" onclick="addInventoryRow()">
                                        <i class="fas fa-plus mr-1"></i> Agregar Fila de Inventario
                                    </button>

                                    <!-- SECCIÓN 2: CHECKLIST -->
                                    <h6 class="section-header mb-3">
                                        2. LISTA DE VERIFICACIÓN DE CAMPO
                                    </h6>

                                    <!-- 2.1 Lubricantes -->
                                    <div class="card mb-3 border-left-primary">
                                        <div class="card-header py-2 bg-light font-weight-bold text-primary small">
                                            2.1 Almacén y Cuarto de Aceites / Lubricantes
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-custom mb-0 checklist-table" data-seccion="Almacen_Lubricantes">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 55%">Criterio a Evaluar</th>
                                                        <th style="width: 15%">Cumple</th>
                                                        <th style="width: 30%">Observaciones / Hallazgos</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="small">¿El área cuenta con contención secundaria (diques, estibas) con capacidad del 110% del envase mayor?</td>
                                                        <td><select name="chk_cumple[]" class="form-control form-control-sm"><option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option></select></td>
                                                        <td><input type="text" name="chk_obs[]" class="form-control form-control-sm" placeholder="Observaciones..."></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="small">¿Los recipientes (tambores, galones) están en buen estado, sin fugas ni deformaciones?</td>
                                                        <td><select name="chk_cumple[]" class="form-control form-control-sm"><option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option></select></td>
                                                        <td><input type="text" name="chk_obs[]" class="form-control form-control-sm" placeholder="Observaciones..."></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="small">¿Se aplica separación física según matriz de incompatibilidad?</td>
                                                        <td><select name="chk_cumple[]" class="form-control form-control-sm"><option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option></select></td>
                                                        <td><input type="text" name="chk_obs[]" class="form-control form-control-sm" placeholder="Observaciones..."></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="small">¿Existe un Kit de Derrames para hidrocarburos completo y de fácil acceso?</td>
                                                        <td><select name="chk_cumple[]" class="form-control form-control-sm"><option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option></select></td>
                                                        <td><input type="text" name="chk_obs[]" class="form-control form-control-sm" placeholder="Observaciones..."></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- 2.2 Servicios Generales -->
                                    <div class="card mb-3 border-left-info">
                                        <div class="card-header py-2 bg-light font-weight-bold text-info small">
                                            2.2 Área de Servicios Generales (Productos de Aseo y Limpieza)
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-custom mb-0 checklist-table" data-seccion="Servicios_Generales">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 55%">Criterio a Evaluar</th>
                                                        <th style="width: 15%">Cumple</th>
                                                        <th style="width: 30%">Observaciones / Hallazgos</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="small">¿Los productos de aseo (cloro, desengrasantes) están almacenados separadamente según incompatibilidad?</td>
                                                        <td><select name="chk_cumple[]" class="form-control form-control-sm"><option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option></select></td>
                                                        <td><input type="text" name="chk_obs[]" class="form-control form-control-sm" placeholder="Observaciones..."></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="small">¿Se prohíbe y controla estrictamente el trasvase a envases de alimentos o bebidas?</td>
                                                        <td><select name="chk_cumple[]" class="form-control form-control-sm"><option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option></select></td>
                                                        <td><input type="text" name="chk_obs[]" class="form-control form-control-sm" placeholder="Observaciones..."></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="small">¿Todos los envases trasvasados cuentan con su respectiva etiqueta SGA?</td>
                                                        <td><select name="chk_cumple[]" class="form-control form-control-sm"><option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option></select></td>
                                                        <td><input type="text" name="chk_obs[]" class="form-control form-control-sm" placeholder="Observaciones..."></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- 2.3 Pinturas, Solventes y Otros (NUEVA SECCIÓN AÑADIDA) -->
                                    <div class="card mb-3 border-left-warning">
                                        <div class="card-header py-2 bg-light font-weight-bold text-warning small">
                                            2.3 Área de Almacenamiento de Pinturas, Solventes y Otros
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-custom mb-0 checklist-table" data-seccion="Pinturas_Solventes">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 55%">Criterio a Evaluar</th>
                                                        <th style="width: 15%">Cumple</th>
                                                        <th style="width: 30%">Observaciones / Hallazgos</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="small">¿Los solventes, pinturas y diluyentes se encuentran almacenados en gabinetes o áreas ignífugas/ventiladas?</td>
                                                        <td><select name="chk_cumple[]" class="form-control form-control-sm"><option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option></select></td>
                                                        <td><input type="text" name="chk_obs[]" class="form-control form-control-sm" placeholder="Observaciones..."></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="small">¿Existe ventilación adecuada (natural o mecánica) para evitar la acumulación de vapores inflamables o tóxicos?</td>
                                                        <td><select name="chk_cumple[]" class="form-control form-control-sm"><option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option></select></td>
                                                        <td><input type="text" name="chk_obs[]" class="form-control form-control-sm" placeholder="Observaciones..."></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="small">¿Se cuenta con señalización visible de prohibición de fumar, generar llama abierta o chispas en la zona?</td>
                                                        <td><select name="chk_cumple[]" class="form-control form-control-sm"><option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option></select></td>
                                                        <td><input type="text" name="chk_obs[]" class="form-control form-control-sm" placeholder="Observaciones..."></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="small">¿Los recipientes de pinturas/solventes parcial o totalmente usados permanecen herméticamente cerrados?</td>
                                                        <td><select name="chk_cumple[]" class="form-control form-control-sm"><option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option></select></td>
                                                        <td><input type="text" name="chk_obs[]" class="form-control form-control-sm" placeholder="Observaciones..."></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- SECCIÓN 3: PLAN DE ACCIÓN -->
                                    <h6 class="section-header mb-3 mt-4">
                                        3. PLAN DE ACCIÓN INMEDIATO
                                    </h6>

                                    <div class="table-responsive mb-3">
                                        <table class="table table-bordered table-sm table-custom" id="tabla-acciones">
                                            <thead>
                                                <tr>
                                                    <th style="width: 35%">Descripción del Hallazgo Crítico</th>
                                                    <th style="width: 35%">Acción Correctiva Sugerida</th>
                                                    <th style="width: 20%">Responsable</th>
                                                    <th class="no-print" style="width: 10%">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody id="action-body">
                                                <tr>
                                                    <td><input type="text" name="act_hallazgo[]" class="form-control form-control-sm" placeholder="Describa el problema..."></td>
                                                    <td><input type="text" name="act_accion[]" class="form-control form-control-sm" placeholder="¿Qué se debe hacer?"></td>
                                                    <td><input type="text" name="act_resp[]" class="form-control form-control-sm" placeholder="Nombre/Cargo"></td>
                                                    <td class="no-print text-center"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary no-print mb-4" onclick="addActionRow()">
                                        <i class="fas fa-plus mr-1"></i> Agregar Acción
                                    </button>

                                    <!-- FIRMAS -->
                                    <div class="row border-top pt-4 mt-4">
                                        <div class="col-md-6 text-center mb-3 mb-md-0">
                                            <div class="border-top border-dark pt-2 mx-auto" style="max-width: 280px;">
                                                <p class="mb-0 font-weight-bold small">Evaluador HSEQ</p>
                                                <input type="text" id="firma_evaluador" name="firma_evaluador" class="form-control form-control-sm text-center bg-transparent border-0" placeholder="Nombre y Cédula">
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <div class="border-top border-dark pt-2 mx-auto" style="max-width: 280px;">
                                                <p class="mb-0 font-weight-bold small">Responsable del Área</p>
                                                <input type="text" id="firma_responsable" name="firma_responsable" class="form-control form-control-sm text-center bg-transparent border-0" placeholder="Nombre y Cédula">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- BOTÓN DE ENVÍO -->
                                    <button type="submit" class="btn btn-primary btn-block font-weight-bold mt-4 py-2 shadow-sm no-print">
                                        ENVIAR INSPECCIÓN Y GUARDAR <i class="fas fa-paper-plane ml-1"></i>
                                    </button>
                                </form>

                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- PIE DE PÁGINA -->
            @include('layouts.pie')

        </div>

    </div>

    <!-- SCRIPTS DE JAVASCRIPT DE SB ADMIN 2 -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        // Funciones auxiliares para agregar filas dinámicas si las necesitas
        function addInventoryRow() {
            const tbody = document.getElementById('inventory-body');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" name="inv_nombre[]" class="form-control form-control-sm" placeholder="Nombre del producto"></td>
                <td><input type="text" name="inv_uso[]" class="form-control form-control-sm" placeholder="Uso / Área"></td>
                <td><input type="text" name="inv_vol[]" class="form-control form-control-sm" placeholder="Presentación"></td>
                <td>
                    <select name="inv_fds[]" class="form-control form-control-sm">
                        <option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option>
                    </select>
                </td>
                <td>
                    <select name="inv_etiqueta[]" class="form-control form-control-sm">
                        <option value=""></option><option>SÍ</option><option>NO</option><option>N/A</option>
                    </select>
                </td>
                <td class="no-print text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="fas fa-trash"></i></button>
                </td>
            `;
            tbody.appendChild(row);
        }

        function addActionRow() {
            const tbody = document.getElementById('action-body');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" name="act_hallazgo[]" class="form-control form-control-sm" placeholder="Describa el problema..."></td>
                <td><input type="text" name="act_accion[]" class="form-control form-control-sm" placeholder="¿Qué se debe hacer?"></td>
                <td><input type="text" name="act_resp[]" class="form-control form-control-sm" placeholder="Nombre/Cargo"></td>
                <td class="no-print text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()"><i class="fas fa-trash"></i></button>
                </td>
            `;
            tbody.appendChild(row);
        }
    </script>

</body>
</html>