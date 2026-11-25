@php
    $p = $pqrs;
    $tipoTxt = $p->tipo === 'Peticion' ? 'Petición' : $p->tipo;
    $esMasc  = $p->tipo === 'Reclamo';

    // Color por tipo de solicitud
    $colorTipo = [
        'Peticion'   => ['#378E77', '#eaf5f1'],
        'Queja'      => ['#c98a0e', '#fdf5e3'],
        'Reclamo'    => ['#c0392b', '#fdecec'],
        'Sugerencia' => ['#3b82c4', '#e8f1fa'],
    ][$p->tipo] ?? ['#378E77', '#eaf5f1'];

    $colorPrioridad = ['Alta' => '#c0392b', 'Media' => '#c98a0e', 'Baja' => '#64748b'][$p->prioridad] ?? '#64748b';

    $estadoTxt = $p->estado === 'En Gestion' ? 'En gestión' : $p->estado;
    $colorEstado = [
        'Radicado'   => ['#2c6aa3', '#e8f1fa'],
        'En Gestion' => ['#b07a0c', '#fdf5e3'],
        'Cerrado'    => ['#2f7a25', '#eaf7e6'],
    ][$p->estado] ?? ['#2c6aa3', '#e8f1fa'];

    if ($destino === 'empresa') {
        $titulo = ($esMasc ? 'Nuevo ' : 'Nueva ') . mb_strtolower($tipoTxt) . ($esMasc ? ' radicado' : ' radicada');
        $intro  = 'Se registró una nueva solicitud en el Centro PQRS de Silogtran. A continuación encontrarás el detalle completo' . ($p->adjunto ? ' y el soporte adjunto.' : '.');
    } elseif ($destino === 'solicitante') {
        $titulo = '¡Hemos recibido tu solicitud!';
        $intro  = 'Hola <strong>' . e($p->nombre) . '</strong>, tu ' . mb_strtolower($tipoTxt) . ($esMasc ? ' fue radicado' : ' fue radicada') . ' correctamente. Nuestro equipo ' . ($esMasc ? 'lo' : 'la') . ' revisará y te daremos respuesta a este mismo correo. Guarda tu número de radicado para hacer seguimiento.';
    } else {
        if ($p->estado === 'Cerrado') {
            $titulo = '¡Tu solicitud fue resuelta!';
            $intro  = 'Hola <strong>' . e($p->nombre) . '</strong>, tu solicitud fue <strong>resuelta</strong>. Abajo encontrarás la respuesta de nuestro equipo.';
        } else {
            $titulo = 'Tu solicitud tiene novedades';
            $intro  = 'Hola <strong>' . e($p->nombre) . '</strong>, el estado de tu solicitud cambió a <strong>' . e($estadoTxt) . '</strong>.';
        }
    }

    // Logo de Plexa (versión liviana para correo). Se incrusta en el mensaje.
    $logoPath = public_path('img/logo.png');
    $logo = (isset($message) && is_file($logoPath)) ? $message->embed($logoPath) : null;
@endphp
<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ $titulo }}</title>
    <!--[if mso]><style>table,td{font-family:Arial,Helvetica,sans-serif !important;}</style><![endif]-->
    <style>
        @media only screen and (max-width: 620px) {
            .container { width: 100% !important; }
            .px { padding-left: 20px !important; padding-right: 20px !important; }
            .col { display: block !important; width: auto !important; }
            .h1 { font-size: 22px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#eef2f5; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">

<!-- Texto de vista previa (bandeja de entrada) -->
<div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; color:#eef2f5;">
    {{ $p->radicado }} · {{ $tipoTxt }} · {{ \Illuminate\Support\Str::limit($p->asunto, 80) }}
</div>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#eef2f5;">
    <tr>
        <td align="center" style="padding:32px 12px;">

            <table role="presentation" class="container" width="600" cellpadding="0" cellspacing="0" border="0" style="width:600px; max-width:600px;">

                <!-- LOGO -->
                <tr>
                    <td align="center" style="padding:0 0 18px;">
                        @if($logo)
                        <img src="{{ $logo }}" alt="PLEXA" width="170" style="display:block; width:170px; max-width:170px; height:auto; border:0;">
                        @else
                            <span style="font-family:Arial,Helvetica,sans-serif; font-size:26px; font-weight:bold; letter-spacing:3px; color:#378E77;">PLEXA</span>
                        @endif
                    </td>
                </tr>

                <!-- HEADER -->
                <tr>
                    <td bgcolor="#378E77" style="background-color:#378E77; background-image:linear-gradient(120deg,#378E77 0%,#248b6f 45%,#16283c 100%); border-radius:18px 18px 0 0; padding:34px 40px 30px;" class="px">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="font-family:Arial,Helvetica,sans-serif; font-size:12px; letter-spacing:2px; text-transform:uppercase; color:#c9f2e4; font-weight:bold; padding-bottom:10px;">
                                    Silogtran &nbsp;·&nbsp; Centro PQRS
                                </td>
                            </tr>
                            <tr>
                                <td class="h1" style="font-family:Arial,Helvetica,sans-serif; font-size:26px; line-height:32px; font-weight:bold; color:#ffffff;">
                                    {{ $titulo }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top:18px;">
                                    <!-- Radicado -->
                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="background-color:#ffffff; border-radius:12px; padding:12px 18px;">
                                                <span style="font-family:Arial,Helvetica,sans-serif; font-size:10px; letter-spacing:1.5px; text-transform:uppercase; color:#8a94a6; font-weight:bold; display:block;">N° de radicado</span>
                                                <span style="font-family:'Courier New',Courier,monospace; font-size:19px; font-weight:bold; color:#1d6b57; letter-spacing:.5px;">{{ $p->radicado }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- BODY -->
                <tr>
                    <td bgcolor="#ffffff" style="background-color:#ffffff; padding:32px 40px 10px;" class="px">

                        <p style="margin:0 0 22px; font-family:Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#334155;">
                            {!! $intro !!}
                        </p>

                        <!-- Chips: tipo / prioridad / estado -->
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="background-color:{{ $colorTipo[1] }}; border-radius:30px; padding:7px 14px; font-family:Arial,Helvetica,sans-serif; font-size:12px; font-weight:bold; color:{{ $colorTipo[0] }};">
                                    ● {{ $tipoTxt }}
                                </td>
                                <td width="8" style="font-size:0; line-height:0;">&nbsp;</td>
                                <td style="background-color:#f4f6f9; border-radius:30px; padding:7px 14px; font-family:Arial,Helvetica,sans-serif; font-size:12px; font-weight:bold; color:{{ $colorPrioridad }};">
                                    Prioridad {{ $p->prioridad }}
                                </td>
                                <td width="8" style="font-size:0; line-height:0;">&nbsp;</td>
                                <td style="background-color:{{ $colorEstado[1] }}; border-radius:30px; padding:7px 14px; font-family:Arial,Helvetica,sans-serif; font-size:12px; font-weight:bold; color:{{ $colorEstado[0] }};">
                                    {{ $estadoTxt }}
                                </td>
                            </tr>
                        </table>

                        @if($destino === 'actualizacion' && $p->respuesta)
                            <!-- Respuesta del equipo -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                                <tr>
                                    <td style="border-left:4px solid #378E77; background-color:#eaf5f1; border-radius:0 12px 12px 0; padding:16px 20px;">
                                        <span style="font-family:Arial,Helvetica,sans-serif; font-size:11px; letter-spacing:1.2px; text-transform:uppercase; color:#378E77; font-weight:bold;">Respuesta de nuestro equipo</span>
                                        <p style="margin:8px 0 0; font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#16283c;">{!! nl2br(e($p->respuesta)) !!}</p>
                                    </td>
                                </tr>
                            </table>
                        @endif

                        <!-- Asunto + descripción -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e6eaf0; border-radius:14px; margin-bottom:22px;">
                            <tr>
                                <td style="padding:18px 20px 6px; font-family:Arial,Helvetica,sans-serif; font-size:11px; letter-spacing:1.2px; text-transform:uppercase; color:#8a94a6; font-weight:bold;">
                                    Asunto
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0 20px 14px; font-family:Arial,Helvetica,sans-serif; font-size:16px; line-height:22px; color:#16283c; font-weight:bold;">
                                    {{ $p->asunto }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0 20px;"><div style="border-top:1px solid #eef1f5; font-size:0; line-height:0;">&nbsp;</div></td>
                            </tr>
                            <tr>
                                <td style="padding:14px 20px 18px; font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#475569;">
                                    {!! nl2br(e($p->descripcion)) !!}
                                </td>
                            </tr>
                        </table>

                        <!-- Detalle en dos columnas -->
                        @php
                            $campos = [
                                ['Solicitante', $p->nombre . ($p->nit_cliente ? ' · Cliente NIT ' . $p->nit_cliente : '')],
                                ['Documento', $p->tipo_documento . ' ' . $p->documento],
                                ['Correo', $p->email],
                                ['Celular', $p->telefono ?: '—'],
                                ['Producto o servicio', $p->producto_servicio ?: '—'],
                                ['Ubicación', implode(', ', array_filter([$p->ciudad, $p->departamento, $p->pais])) ?: '—'],
                                ['Dirección', $p->direccion ?: '—'],
                                ['Categoría', $p->categoria],
                                ['Manifiesto / remesa', $p->manifiesto ?: '—'],
                                ['Fecha de radicación', optional($p->created_at)->format('d/m/Y h:i a')],
                                ['Adjunto', $p->adjunto ? ($destino === 'empresa' ? 'Incluido en este correo' : 'Recibido') : 'Sin adjunto'],
                            ];
                        @endphp
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc; border-radius:14px; margin-bottom:26px;">
                            @foreach(array_chunk($campos, 2) as $fila)
                                <tr>
                                    @foreach($fila as $c)
                                        <td class="col" width="50%" valign="top" style="padding:14px 20px; {{ !$loop->parent->last ? 'border-bottom:1px solid #eef1f5;' : '' }}">
                                            <span style="display:block; font-family:Arial,Helvetica,sans-serif; font-size:10px; letter-spacing:1.2px; text-transform:uppercase; color:#8a94a6; font-weight:bold;">{{ $c[0] }}</span>
                                            <span style="display:block; margin-top:4px; font-family:Arial,Helvetica,sans-serif; font-size:14px; color:#16283c; font-weight:bold; word-break:break-word;">{{ $c[1] }}</span>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>

                        @if($destino === 'empresa')
                            <!-- Botones -->
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto 30px;">
                                <tr>
                                    <td align="center" bgcolor="#378E77" style="border-radius:12px; background-color:#378E77;">
                                        <a href="{{ route('silogtran.pqrs', ['busqueda' => $p->radicado]) }}" target="_blank"
                                           style="display:inline-block; padding:14px 28px; font-family:Arial,Helvetica,sans-serif; font-size:14px; font-weight:bold; color:#ffffff; text-decoration:none; border-radius:12px;">
                                            Gestionar en Plexa Core &rarr;
                                        </a>
                                    </td>
                                    <td width="10">&nbsp;</td>
                                    <td align="center" style="border-radius:12px; border:2px solid #378E77;">
                                        <a href="mailto:{{ $p->email }}?subject={{ rawurlencode('Respuesta PQRS ' . $p->radicado) }}"
                                           style="display:inline-block; padding:12px 22px; font-family:Arial,Helvetica,sans-serif; font-size:14px; font-weight:bold; color:#378E77; text-decoration:none; border-radius:12px;">
                                            Responder
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        @else
                            <!-- Botón de seguimiento (enlace firmado, sin login) -->
                            @php $urlSeguimiento = \App\Http\Controllers\SilogtranPqrsController::urlSeguimiento($p); @endphp
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto 26px;">
                                <tr>
                                    <td align="center" bgcolor="#378E77" style="border-radius:12px; background-color:#378E77;">
                                        <a href="{{ $urlSeguimiento }}" target="_blank"
                                           style="display:inline-block; padding:14px 28px; font-family:Arial,Helvetica,sans-serif; font-size:14px; font-weight:bold; color:#ffffff; text-decoration:none; border-radius:12px;">
                                            {{ $p->estado === 'Cerrado' ? 'Ver la respuesta completa' : 'Ver el estado de mi solicitud' }} &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Línea de tiempo -->
                            @php
                                $pasos = ['Radicado', 'En Gestion', 'Cerrado'];
                                $actual = array_search($p->estado, $pasos);
                                if ($actual === false) { $actual = 0; }
                            @endphp
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:30px;">
                                <tr>
                                    @foreach($pasos as $i => $paso)
                                        <td align="center" width="33%" style="font-family:Arial,Helvetica,sans-serif;">
                                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
                                                <tr>
                                                    <td align="center" width="34" height="34" style="width:34px; height:34px; border-radius:17px; font-size:14px; font-weight:bold; {{ $i <= $actual ? 'background-color:#378E77; color:#ffffff;' : 'background-color:#e9eef3; color:#a3adbb;' }}">
                                                        {{ ($i < $actual || ($p->estado === 'Cerrado' && $i === $actual)) ? '✓' : $i + 1 }}
                                                    </td>
                                                </tr>
                                            </table>
                                            <span style="display:block; margin-top:8px; font-size:12px; font-weight:bold; color:{{ $i <= $actual ? '#16283c' : '#a3adbb' }};">
                                                {{ $paso === 'En Gestion' ? 'En gestión' : $paso }}
                                            </span>
                                        </td>
                                    @endforeach
                                </tr>
                            </table>
                        @endif
                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td bgcolor="#16283c" style="background-color:#16283c; border-radius:0 0 18px 18px; padding:24px 40px;" class="px">
                        <p style="margin:0 0 6px; font-family:Arial,Helvetica,sans-serif; font-size:13px; color:#ffffff; font-weight:bold;">Plexa</p>
                        <p style="margin:0; font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:#9fb0c3;">
                            @if($destino === 'empresa')
                                Correo generado automáticamente desde el Centro PQRS de Silogtran. Puedes responder este mensaje y la respuesta llegará directamente al solicitante.
                            @else
                                Este es un correo automático, por favor no respondas a esta dirección. Guarda este correo: con el botón de arriba puedes consultar en cualquier momento si tu solicitud ya fue resuelta. Si necesitas agregar información, radica una nueva solicitud indicando tu número de radicado.
                            @endif
                        </p>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding:18px 0 0; font-family:Arial,Helvetica,sans-serif; font-size:11px; color:#94a3b8;">
                        &copy; {{ date('Y') }} Plexa. Todos los derechos reservados.
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>