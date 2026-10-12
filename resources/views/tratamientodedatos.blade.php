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
        .btn-plexa { background: linear-gradient(135deg, #378E77, #248b6f); border: none; border-radius: 12px; padding: 12px 20px; font-weight: 700; color: white; box-shadow: 0 8px 15px rgba(55, 142, 119, 0.25); transition: all 0.3s ease; }
        .btn-plexa:hover { transform: translateY(-2px); box-shadow: 0 12px 20px rgba(55, 142, 119, 0.35); color: white; }
        
        .table-modern { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .table-modern th { border: none; color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; padding: 10px 20px; }
        .table-modern td { background: #ffffff; border: none; padding: 15px 20px; vertical-align: middle; color: #334155; font-weight: 500; }
        .table-modern tbody tr { box-shadow: 0 2px 10px rgba(0,0,0,0.02); transition: box-shadow 0.2s ease; cursor: pointer; }
        .table-modern td:first-child { border-radius: 12px 0 0 12px; }
        .table-modern td:last-child { border-radius: 0 12px 12px 0; }
        .avatar-circle { width: 40px; height: 40px; background-color: #e0f2fe; color: #0284c7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; margin-right: 12px; transition: all .3s ease; }
        .table-modern tbody tr:hover { box-shadow: 0 8px 25px rgba(55, 142, 119, 0.18) !important; }
        .table-modern tbody tr:hover .avatar-circle { transform: scale(1.1) rotate(4deg); background-color: #378E77; color: #fff; box-shadow: 0 4px 12px rgba(55, 142, 119, .4); }
        
        .badge-success-soft { background-color: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; }
        .badge-aut { padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.72rem; display: inline-block; margin-bottom: 3px; margin-right: 3px; }
        .badge-aut-si { background-color: #dcfce7; color: #166534; }
        .badge-aut-no { background-color: #fee2e2; color: #991b1b; }
        .badge-aut-na { background-color: #f1f5f9; color: #64748b; }

        /* Estilo de Pestañas Tipo Carpeta */
        .tabs-navigation { display: flex; align-items: flex-end; width: 100%; border-bottom: 4px solid #378E77; margin-bottom: 20px; gap: 5px; }
        .tab-btn { padding: 12px 30px; background: #e2e8f0; color: #64748b; border: 1px solid #cbd5e1; border-bottom: none; border-radius: 16px 16px 0 0; font-weight: 700; font-size: 0.95rem; cursor: pointer; position: relative; margin-bottom: 0; z-index: 1; transition: background-color 0.2s; }
        .tab-btn:hover:not(.active) { background: #cbd5e1; color: #1e293b; }
        .tab-btn.active { background: #378E77; color: white; border-color: #378E77; margin-bottom: -4px; padding-bottom: 16px; z-index: 2; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        /* Paginación */
        .pagination { margin-bottom: 0; }
        .page-link { color: #378E77; border-radius: 8px; margin: 0 3px; border: 1px solid #e2e8f0; padding: 6px 12px; font-weight: 600; font-size: .85rem; transition: all .2s ease; }
        .page-item.active .page-link { background: #378E77; border-color: #378E77; color: #fff; }
        .page-link:hover { background: #e8f7f1; color: #248b6f; }
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
                                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #378E77;">Total Firmas</div>
                                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $firmasTerceros->total() + $firmasColaboradores->total() }}</div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-file-signature fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            
                            <!-- Pestañas de Navegación -->
                            <div class="tabs-navigation">
                                <button type="button" class="tab-btn {{ $tabActiva == 'terceros' ? 'active' : '' }}" onclick="switchTab('terceros')">
                                    <i class="fas fa-truck"></i> Terceros
                                </button>
                                <button type="button" class="tab-btn {{ $tabActiva == 'colaboradores' ? 'active' : '' }}" onclick="switchTab('colaboradores')">
                                    <i class="fas fa-users"></i> Colaboradores / Candidatos
                                </button>
                            </div>

                            <div class="card soft-card mb-4">
                                <div class="card-body p-4">
                                    
                                    <!-- ==================== TABLA TERCEROS ==================== -->
                                    <div id="tab-terceros" class="tab-content {{ $tabActiva == 'terceros' ? 'active' : '' }}">
                                        <h5 class="font-weight-bold text-gray-800 mb-4">Base de Datos: Terceros</h5>
                                        <div class="table-responsive">
                                            <table class="table-modern text-left">
                                                <thead>
                                                    <tr>
                                                        <th>Razón Social / Firmante</th>
                                                        <th>NIT / Cédula</th>
                                                        <th>Autorizaciones</th>
                                                        <th>Fecha Firma</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($firmasTerceros as $firmaT)
                                                        <!-- PASAMOS SOLO EL ID AL JS -->
                                                        <tr onclick="abrirModalTercero({{ $firmaT->id }})">
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-circle"><i class="fas fa-building"></i></div>
                                                                    <div>
                                                                        <div class="font-weight-bold text-gray-900">{{ $firmaT->nombre_razon_social }}</div>
                                                                        <div class="small text-muted">ID: #{{ str_pad($firmaT->id, 4, '0', STR_PAD_LEFT) }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><div class="font-weight-bold"><i class="far fa-id-card text-gray-400 mr-2"></i>{{ $firmaT->identificacion }}</div></td>
                                                            <td>
                                                                @php
                                                                    $g = $firmaT->autorizacion_general ?? null;
                                                                    $s = $firmaT->autorizacion_sensibles ?? null;
                                                                    $claseG = ($g === 'SI') ? 'badge-aut-si' : (($g === 'NO') ? 'badge-aut-no' : 'badge-aut-na');
                                                                    $claseS = ($s === 'SI') ? 'badge-aut-si' : (($s === 'NO') ? 'badge-aut-no' : 'badge-aut-na');
                                                                @endphp
                                                                <span class="badge-aut {{ $claseG }}">General: {{ $g ?? '—' }}</span><br>
                                                                <span class="badge-aut {{ $claseS }}">Sensibles: {{ $s ?? '—' }}</span>
                                                            </td>
                                                            <td>
                                                                <div class="text-gray-800">{{ \Carbon\Carbon::parse($firmaT->created_at)->format('d M, Y') }}</div>
                                                                <div class="small text-muted">{{ \Carbon\Carbon::parse($firmaT->created_at)->format('h:i A') }}</div>
                                                            </td>
                                                            <td><span class="badge-success-soft d-inline-block"><i class="fas fa-check mr-1"></i> Autorizado</span></td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center py-5">
                                                                <i class="fas fa-box-open text-gray-300 mb-3" style="font-size: 3rem;"></i>
                                                                <h6 class="font-weight-bold text-gray-500">Sin datos aún</h6>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        @if($firmasTerceros->hasPages())
                                            <div class="mt-4 d-flex justify-content-between align-items-center">
                                                <div class="small text-muted">Mostrando {{ $firmasTerceros->firstItem() }} a {{ $firmasTerceros->lastItem() }} de {{ $firmasTerceros->total() }}</div>
                                                <div>{{ $firmasTerceros->appends(['ter_page' => $firmasTerceros->currentPage()])->links('pagination::bootstrap-4') }}</div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- ==================== TABLA COLABORADORES ==================== -->
                                    <div id="tab-colaboradores" class="tab-content {{ $tabActiva == 'colaboradores' ? 'active' : '' }}">
                                        <h5 class="font-weight-bold text-gray-800 mb-4">Base de Datos: Colaboradores / Candidatos</h5>
                                        <div class="table-responsive">
                                            <table class="table-modern text-left">
                                                <thead>
                                                    <tr>
                                                        <th>Firmante</th>
                                                        <th>Documento</th>
                                                        <th>Autorizaciones</th>
                                                        <th>Fecha Firma</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($firmasColaboradores as $firmaC)
                                                        <!-- PASAMOS SOLO EL ID AL JS -->
                                                        <tr onclick="abrirModalColaborador({{ $firmaC->id }})">
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-circle"><i class="fas fa-user"></i></div>
                                                                    <div>
                                                                        <div class="font-weight-bold text-gray-900">{{ $firmaC->nombre }}</div>
                                                                        <div class="small text-muted">ID: #{{ str_pad($firmaC->id, 4, '0', STR_PAD_LEFT) }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><div class="font-weight-bold"><i class="far fa-id-card text-gray-400 mr-2"></i>{{ $firmaC->cedula }}</div></td>
                                                            <td>
                                                                @php
                                                                    $p = $firmaC->autorizo_personales ?? null;
                                                                    $s = $firmaC->autorizo_sensibles ?? null;
                                                                    $claseP = ($p === 'SI') ? 'badge-aut-si' : (($p === 'NO') ? 'badge-aut-no' : 'badge-aut-na');
                                                                    $claseS = ($s === 'SI') ? 'badge-aut-si' : (($s === 'NO') ? 'badge-aut-no' : 'badge-aut-na');
                                                                @endphp
                                                                <span class="badge-aut {{ $claseP }}">Personales: {{ $p ?? '—' }}</span>
                                                                <span class="badge-aut {{ $claseS }}">Sensibles: {{ $s ?? '—' }}</span>
                                                            </td>
                                                            <td>
                                                                <div class="text-gray-800">{{ \Carbon\Carbon::parse($firmaC->created_at)->format('d M, Y') }}</div>
                                                                <div class="small text-muted">{{ \Carbon\Carbon::parse($firmaC->created_at)->format('h:i A') }}</div>
                                                            </td>
                                                            <td><span class="badge-success-soft d-inline-block"><i class="fas fa-check mr-1"></i> Autorizado</span></td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center py-5">
                                                                <i class="fas fa-box-open text-gray-300 mb-3" style="font-size: 3rem;"></i>
                                                                <h6 class="font-weight-bold text-gray-500">Sin datos aún</h6>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        @if($firmasColaboradores->hasPages())
                                            <div class="mt-4 d-flex justify-content-between align-items-center">
                                                <div class="small text-muted">Mostrando {{ $firmasColaboradores->firstItem() }} a {{ $firmasColaboradores->lastItem() }} de {{ $firmasColaboradores->total() }}</div>
                                                <div>{{ $firmasColaboradores->appends(['col_page' => $firmasColaboradores->currentPage()])->links('pagination::bootstrap-4') }}</div>
                                            </div>
                                        @endif
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

    <!-- ==================== MODAL TERCEROS ==================== -->
    <div class="modal fade" id="modalDetalleTercero" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.2);">
                <div class="modal-header bg-plexa-gradient text-white" style="border-radius: 16px 16px 0 0; border-bottom: none;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-truck mr-2"></i>Detalle Tercero</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4"><span class="badge badge-success mb-2 px-3 py-2" style="border-radius: 20px;"><i class="fas fa-check-circle mr-1"></i> Autorización Aceptada</span><p class="text-muted small" id="tFechaInfo"></p></div>
                    <div class="row mb-2"><div class="col-5 text-muted small font-weight-bold">Razón Social:</div><div class="col-7 font-weight-bold text-dark" id="tNombre"></div></div>
                    <div class="row mb-2"><div class="col-5 text-muted small font-weight-bold">NIT/CC:</div><div class="col-7 font-weight-bold text-dark" id="tIdent"></div></div>
                    <div class="row mb-2"><div class="col-5 text-muted small font-weight-bold">Calidad Actúa:</div><div class="col-7" id="tCalidad"></div></div>
                    <hr>
                    <div class="row mb-2"><div class="col-5 text-muted small font-weight-bold">Autorización General:</div><div class="col-7" id="tAuthGen"></div></div>
                    <div class="row mb-2"><div class="col-5 text-muted small font-weight-bold">Datos Sensibles:</div><div class="col-7" id="tAuthSen"></div></div>
                    <div class="row mb-2"><div class="col-5 text-muted small font-weight-bold">Transmisión RISK:</div><div class="col-7" id="tAuthTrans"></div></div>
                    
                    <div class="mt-4 pt-3 text-center" style="border-top: 1px dashed #e2e8f0;">
                        <span class="text-muted font-weight-bold small d-block mb-2">FIRMA REGISTRADA:</span>
                        <img id="tFirmaImg" src="" style="max-height: 80px; display: block; margin: 0 auto;">
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #f8fafc; border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-secondary" style="border-radius: 8px;" data-dismiss="modal">Cerrar</button>
                    <!-- EL ENLACE AHORA TIENE target="_blank" -->
                    <a href="#" id="tBtnDescargar" class="btn btn-plexa" target="_blank" rel="noopener noreferrer"><i class="fas fa-file-pdf mr-1"></i> Descargar Documento</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL COLABORADORES ==================== -->
    <div class="modal fade" id="modalDetalleColaborador" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.2);">
                <div class="modal-header bg-plexa-gradient text-white" style="border-radius: 16px 16px 0 0; border-bottom: none;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-users mr-2"></i>Detalle Colaborador</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4"><span class="badge badge-success mb-2 px-3 py-2" style="border-radius: 20px;"><i class="fas fa-check-circle mr-1"></i> Autorización Aceptada</span><p class="text-muted small" id="cFechaInfo"></p></div>
                    <div class="row mb-2"><div class="col-5 text-muted small font-weight-bold">Nombre Completo:</div><div class="col-7 font-weight-bold text-dark" id="cNombre"></div></div>
                    <div class="row mb-2"><div class="col-5 text-muted small font-weight-bold">Cédula:</div><div class="col-7 font-weight-bold text-dark" id="cCedula"></div></div>
                    <hr>
                    <div class="row mb-2"><div class="col-5 text-muted small font-weight-bold">Autoriza Personales:</div><div class="col-7" id="cAuthPer"></div></div>
                    <div class="row mb-2"><div class="col-5 text-muted small font-weight-bold">Autoriza Sensibles:</div><div class="col-7" id="cAuthSen"></div></div>
                    
                    <div class="mt-4 pt-3 text-center" style="border-top: 1px dashed #e2e8f0;">
                        <span class="text-muted font-weight-bold small d-block mb-2">FIRMA REGISTRADA:</span>
                        <img id="cFirmaImg" src="" style="max-height: 80px; display: block; margin: 0 auto;">
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #f8fafc; border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-secondary" style="border-radius: 8px;" data-dismiss="modal">Cerrar</button>
                    <!-- EL ENLACE AHORA TIENE target="_blank" -->
                    <a href="#" id="cBtnDescargar" class="btn btn-plexa" target="_blank" rel="noopener noreferrer"><i class="fas fa-file-pdf mr-1"></i> Descargar Documento</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        // PASAMOS LOS ARREGLOS LIMPIOS DESDE PHP A JAVASCRIPT
        const dataTerceros = @json($firmasTerceros->items());
        const dataColaboradores = @json($firmasColaboradores->items());

        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            if(tab === 'terceros') {
                document.querySelectorAll('.tab-btn')[0].classList.add('active');
                document.getElementById('tab-terceros').classList.add('active');
            } else {
                document.querySelectorAll('.tab-btn')[1].classList.add('active');
                document.getElementById('tab-colaboradores').classList.add('active');
            }
        }

        function badgeSINO(valor) {
            if (!valor) return '<span class="badge-aut badge-aut-na">—</span>';
            return valor === 'SI'
                ? '<span class="badge-aut badge-aut-si"><i class="fas fa-check mr-1"></i>SI</span>'
                : '<span class="badge-aut badge-aut-no"><i class="fas fa-times mr-1"></i>NO</span>';
        }

        function formatearFecha(fechaCadena) {
            if (!fechaCadena) return '—';
            let fecha = new Date(fechaCadena);
            return fecha.toLocaleDateString('es-ES') + " a las " + fecha.toLocaleTimeString('es-ES');
        }

        // ==========================================
        // Modal Terceros (Limpio y Seguro)
        // ==========================================
        function abrirModalTercero(id) {
            let data = dataTerceros.find(item => item.id == id);
            if(!data) return;

            // ASIGNAR URL EXACTA DE DESCARGA
            document.getElementById('tBtnDescargar').href = "/descargar-tercero/" + data.id;

            document.getElementById('tNombre').innerText = data.nombre_razon_social || '—';
            document.getElementById('tIdent').innerText = data.identificacion || '—';
            document.getElementById('tCalidad').innerText = data.calidad_actua || '—';
            document.getElementById('tAuthGen').innerHTML = badgeSINO(data.autorizacion_general);
            document.getElementById('tAuthSen').innerHTML = badgeSINO(data.autorizacion_sensibles);
            document.getElementById('tAuthTrans').innerHTML = badgeSINO(data.autorizacion_transmision);
            document.getElementById('tFechaInfo').innerText = "Firmado el: " + formatearFecha(data.created_at);

            let imgEl = document.getElementById('tFirmaImg');
            if (data.firma && typeof data.firma === 'string' && data.firma.includes('base64')) {
                imgEl.src = data.firma; imgEl.style.display = 'block';
            } else { imgEl.style.display = 'none'; }
            
            $('#modalDetalleTercero').modal('show');
        }

        // ==========================================
        // Modal Colaboradores (Limpio y Seguro)
        // ==========================================
        function abrirModalColaborador(id) {
            let data = dataColaboradores.find(item => item.id == id);
            if(!data) return;

            // ASIGNAR URL EXACTA DE DESCARGA
            document.getElementById('cBtnDescargar').href = "/descargar-colaborador/" + data.id;

            document.getElementById('cNombre').innerText = data.nombre || '—';
            document.getElementById('cCedula').innerText = data.cedula || '—';
            document.getElementById('cAuthPer').innerHTML = badgeSINO(data.autorizo_personales);
            document.getElementById('cAuthSen').innerHTML = badgeSINO(data.autorizo_sensibles);
            document.getElementById('cFechaInfo').innerText = "Firmado el: " + formatearFecha(data.created_at);

            let imgEl = document.getElementById('cFirmaImg');
            if (data.firma && typeof data.firma === 'string' && data.firma.includes('base64')) {
                imgEl.src = data.firma; imgEl.style.display = 'block';
            } else { imgEl.style.display = 'none'; }
            
            $('#modalDetalleColaborador').modal('show');
        }
    </script>
</body>
</html>