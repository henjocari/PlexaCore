<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <title>Plexa Core - Dashboard Tratamiento de Datos</title>
    
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .bg-plexa-gradient { background: linear-gradient(135deg, #115c48 0%, #378E77 100%); }
        .soft-card { border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .soft-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08); }
        .custom-input { border-radius: 10px; border: 2px solid #e2e8f0; background-color: #f8fafc; padding: 0.85rem 1rem; color: #1e293b; font-weight: 500; transition: all 0.3s ease; }
        .custom-input:focus { border-color: #378E77; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(55, 142, 119, 0.1); outline: none; }
        .custom-label { font-weight: 700; color: #475569; font-size: 0.9rem; margin-bottom: 0.5rem; display: block; }
        .btn-plexa { background: linear-gradient(135deg, #378E77, #248b6f); border: none; border-radius: 12px; padding: 12px 20px; font-weight: 700; color: white; box-shadow: 0 8px 15px rgba(55, 142, 119, 0.25); transition: all 0.3s ease; }
        .btn-plexa:hover { transform: translateY(-2px); box-shadow: 0 12px 20px rgba(55, 142, 119, 0.35); color: white; }
        .table-modern { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .table-modern th { border: none; color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; padding: 10px 20px; }
        .table-modern td { background: #ffffff; border: none; padding: 15px 20px; vertical-align: middle; color: #334155; font-weight: 500; }
        .table-modern tbody tr { box-shadow: 0 2px 10px rgba(0,0,0,0.02); transition: all 0.2s; }
        .table-modern tbody tr:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.06); transform: scale(1.01); }
        .table-modern td:first-child { border-radius: 12px 0 0 12px; }
        .table-modern td:last-child { border-radius: 0 12px 12px 0; }
        .avatar-circle { width: 40px; height: 40px; background-color: #e0f2fe; color: #0284c7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; margin-right: 12px; }
        .badge-success-soft { background-color: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layouts.menu')

        <div id="content-wrapper" class="d-flex flex-column" style="background-color: #f1f5f9;">
            <div id="content">
                @include('layouts.cabecera')

                <div class="container-fluid mt-4">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 font-weight-bold"><i class="fas fa-shield-alt text-primary mr-2"></i>Centro de Tratamiento de Datos</h1>
                    </div>

                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card soft-card h-100 py-2" style="border-left: 5px solid #378E77;">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #378E77;">Firmas Recibidas</div>
                                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $firmas->count() }}</div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-file-signature fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" style="border-radius: 12px; border:none; background-color: #dcfce7; color: #166534;" role="alert">
                            <i class="fas fa-check-circle mr-2"></i> <strong>¡Excelente!</strong> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card soft-card mb-4">
                                <div class="card-header py-3 bg-plexa-gradient d-flex align-items-center" style="border-radius: 16px 16px 0 0; border-bottom: none;">
                                    <i class="fas fa-pen-nib text-white mr-2" style="font-size: 1.2rem;"></i>
                                    <h6 class="m-0 font-weight-bold text-white">Configuración del Documento</h6>
                                </div>
                                <div class="card-body p-4">
                                    <form action="{{ route('tratamientodedatos.update') }}" method="POST">
                                        @csrf
                                        <div class="form-group mb-4">
                                            <label class="custom-label">Título Principal</label>
                                            <input type="text" name="titulo" class="custom-input w-100" value="{{ $texto->titulo }}" required>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="custom-label">Subtítulo / Referencia Legal</label>
                                            <textarea name="subtitulo" class="custom-input w-100" rows="3">{{ $texto->subtitulo }}</textarea>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="custom-label">Cuerpo de Términos (HTML)</label>
                                            <textarea name="terminos_legales" class="custom-input w-100" rows="5" style="font-family: monospace; font-size: 0.85rem;">{{ $texto->terminos_legales }}</textarea>
                                        </div>

                                        <hr class="mt-4 mb-3">
                                        <h6 class="font-weight-bold text-gray-800 mb-3"><i class="fas fa-palette mr-2 text-primary"></i>Personalización Visual</h6>
                                        
                                        <div class="row mb-4">
                                            <div class="col-md-4 text-center">
                                                <label class="custom-label small">Fondo Pág.</label>
                                                <input type="color" name="color_fondo" class="form-control form-control-color w-100 p-1" value="{{ $texto->color_fondo }}" style="height: 40px; border-radius: 8px;">
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <label class="custom-label small">Textos</label>
                                                <input type="color" name="color_texto" class="form-control form-control-color w-100 p-1" value="{{ $texto->color_texto }}" style="height: 40px; border-radius: 8px;">
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <label class="custom-label small">Botón</label>
                                                <input type="color" name="color_boton" class="form-control form-control-color w-100 p-1" value="{{ $texto->color_boton }}" style="height: 40px; border-radius: 8px;">
                                            </div>
                                        </div>

                                        <button type="submit" class="btn-plexa w-100 mt-2">
                                            <i class="fas fa-cloud-upload-alt mr-2"></i> Publicar Cambios
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card soft-card mb-4">
                                <div class="card-body p-4">
                                    <h5 class="font-weight-bold text-gray-800 mb-4">Base de Datos de Firmantes</h5>
                                    <div class="table-responsive">
                                        <table class="table-modern text-left">
                                            <thead>
                                                <tr>
                                                    <th>Firmante</th>
                                                    <th>Documento</th>
                                                    <th>Fecha Firma</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($firmas as $firma)
                                                    <tr onclick="abrirModalDetalle({{ $firma }})" 
                                                        style="cursor: pointer; transition: all 0.2s ease;" 
                                                        title="Clic para ver detalles del registro"
                                                        onmouseover="this.style.backgroundColor='#f8fafc'" 
                                                        onmouseout="this.style.backgroundColor='transparent'">
                                                        
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-circle"><i class="fas fa-user"></i></div>
                                                                <div>
                                                                    <div class="font-weight-bold text-gray-900">{{ $firma->nombre }}</div>
                                                                    <div class="small text-muted">ID: #{{ str_pad($firma->id, 4, '0', STR_PAD_LEFT) }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><div class="font-weight-bold"><i class="far fa-id-card text-gray-400 mr-2"></i>{{ $firma->cedula }}</div></td>
                                                        <td>
                                                            <div class="text-gray-800">{{ $firma->created_at->format('d M, Y') }}</div>
                                                            <div class="small text-muted">{{ $firma->created_at->format('h:i A') }}</div>
                                                        </td>
                                                        <td>
                                                            <span class="badge-success-soft d-inline-block"><i class="fas fa-check mr-1"></i> Autorizado</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-5">
                                                            <i class="fas fa-box-open text-gray-300 mb-3" style="font-size: 4rem;"></i>
                                                            <h5 class="font-weight-bold text-gray-500">Sin datos aún</h5>
                                                            <p class="text-muted">Las firmas aparecerán aquí.</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.pie')
        </div>
    </div>

    <div class="modal fade" id="modalDetalleFirma" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.2);">
                
                <div class="modal-header bg-plexa-gradient text-white" style="border-radius: 16px 16px 0 0; border-bottom: none;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-file-signature mr-2"></i>Detalle de Autorización</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <span class="badge badge-success mb-2 px-3 py-2" style="font-size: 0.9rem; border-radius: 20px;">
                            <i class="fas fa-check-circle mr-1"></i> Términos Aceptados
                        </span>
                        <p class="text-muted small" id="modalFechaInfo"></p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-5 text-muted font-weight-bold small">Nombre Completo:</div>
                        <div class="col-sm-7 text-dark font-weight-bold" id="modalNombre"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5 text-muted font-weight-bold small">Documento:</div>
                        <div class="col-sm-7 text-dark font-weight-bold" id="modalCedula"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5 text-muted font-weight-bold small">Expedida en:</div>
                        <div class="col-sm-7 text-dark font-weight-bold" id="modalLugarExp"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5 text-muted font-weight-bold small">Ciudad de Firma:</div>
                        <div class="col-sm-7 text-dark font-weight-bold" id="modalCiudadFirma"></div>
                    </div>

                    <div class="mt-4 pt-3 text-center" style="border-top: 1px dashed #e2e8f0;">
                        <span class="text-muted font-weight-bold small d-block mb-2">FIRMA REGISTRADA:</span>
                        <img id="modalFirmaImg" src="" alt="Firma" style="max-height: 80px; display: block; margin: 0 auto;">
                    </div>
                </div>

                <div class="modal-footer" style="background-color: #f8fafc; border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-secondary" style="border-radius: 8px;" data-dismiss="modal">Cerrar</button>
                    <a href="#" id="btnDescargarPdf" target="_blank" class="btn btn-plexa">
                        <i class="fas fa-file-pdf mr-1"></i> Descargar Documento
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        function abrirModalDetalle(firmaData) {
            // Llenar los textos
            document.getElementById('modalNombre').innerText = firmaData.nombre;
            document.getElementById('modalCedula').innerText = firmaData.cedula;
            document.getElementById('modalLugarExp').innerText = firmaData.lugar_expedicion;
            document.getElementById('modalCiudadFirma').innerText = firmaData.ciudad_firma;
            
            // Formatear fecha
            let fecha = new Date(firmaData.created_at);
            document.getElementById('modalFechaInfo').innerText = "Firmado el: " + fecha.toLocaleDateString('es-ES') + " a las " + fecha.toLocaleTimeString('es-ES');

            // Cargar imagen de la firma si existe
            let imgEl = document.getElementById('modalFirmaImg');
            if(firmaData.firma) {
                imgEl.src = firmaData.firma;
                imgEl.style.display = 'block';
            } else {
                imgEl.style.display = 'none';
            }

            // Asignar el enlace al botón de descargar
            document.getElementById('btnDescargarPdf').href = '/tratamiento-datos/documento/' + firmaData.id;

            // Mostrar el modal (usando jQuery que ya viene en tu proyecto)
            $('#modalDetalleFirma').modal('show');
        }
    </script>
</body>
</html>