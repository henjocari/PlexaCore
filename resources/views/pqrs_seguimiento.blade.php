@php
    $tipoTxt   = $p->tipo === 'Peticion' ? 'Petición' : $p->tipo;
    $estadoTxt = $p->estado === 'En Gestion' ? 'En gestión' : $p->estado;
    $pasos     = ['Radicado' => 'Radicada', 'En Gestion' => 'En gestión', 'Cerrado' => 'Resuelta'];
    $claves    = array_keys($pasos);
    $actual    = array_search($p->estado, $claves);
    if ($actual === false) { $actual = 0; }
    $resuelta  = $p->estado === 'Cerrado';
    $ubicacion = implode(', ', array_filter([$p->ciudad, $p->departamento, $p->pais]));
    $logoPlexa = is_file(public_path('img/IsotipoPlexa-recorte.png')) ? 'img/IsotipoPlexa-recorte.png' : 'img/IsotipoPlexa.png';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Seguimiento PQRS {{ $p->radicado }} | Plexa</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --pink: #E94262; --pink-dark: #CC3366; --pink-deep: #A8284A; --pink-soft: #FDEDF0;
            --navy: #1B1B1F; --muted: #8a94a6; --line: #e6eaf0; --bg: #EEF0EF;
            --ok: #2f7a25; --ok-soft: #eaf7e6;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--bg); color: #334155; font-family: "Nunito", -apple-system, "Segoe UI", Roboto, Arial, sans-serif; }
        .wrap { max-width: 760px; margin: 0 auto; padding: 24px 16px 48px; }

        .hero {
            position: relative; overflow: hidden; border-radius: 20px; color: #fff; padding: 26px 26px 24px;
            background: linear-gradient(120deg, #0d0d0f 0%, #1c1c22 48%, #4a1426 100%);
            box-shadow: 0 16px 36px rgba(15,15,18,.3);
        }
        .hero::after { content: ""; position: absolute; width: 220px; height: 220px; right: -70px; top: -90px; border-radius: 50%; background: var(--pink); filter: blur(40px); opacity: .45; }
        .brand { display: flex; align-items: center; gap: 14px; position: relative; z-index: 1; }
        .brand .silog { height: 54px; }
        .brand .plexa { height: 44px; margin-left: auto; filter: drop-shadow(0 6px 14px rgba(0,0,0,.45)); }
        .kicker { font-size: .72rem; letter-spacing: 1.6px; text-transform: uppercase; color: #FFB3C2; font-weight: 800; margin: 18px 0 4px; position: relative; z-index: 1; }
        h1 { margin: 0; font-size: 1.5rem; font-weight: 800; position: relative; z-index: 1; overflow-wrap: anywhere; }
        .rad { display: inline-flex; align-items: center; gap: 10px; margin-top: 14px; background: #fff; border-radius: 12px; padding: 10px 14px; position: relative; z-index: 1; }
        .rad small { display: block; font-size: .62rem; letter-spacing: 1.2px; text-transform: uppercase; color: var(--muted); font-weight: 800; }
        .rad code { font: 800 1.05rem ui-monospace, Menlo, Consolas, monospace; color: var(--pink-deep); }

        .card { background: #fff; border: 1px solid var(--line); border-radius: 18px; padding: 22px; margin-top: 18px; box-shadow: 0 6px 20px rgba(20,40,70,.05); }
        .card h2 { margin: 0 0 14px; font-size: 1rem; font-weight: 800; color: var(--navy); }

        .status { display: flex; align-items: center; gap: 14px; }
        .status .ico { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; }
        .status.done .ico { background: var(--ok-soft); color: var(--ok); }
        .status.open .ico { background: var(--pink-soft); color: var(--pink); }
        .status b { display: block; font-size: 1.1rem; color: var(--navy); }
        .status span { font-size: .86rem; color: var(--muted); }

        .steps { display: flex; justify-content: space-between; position: relative; margin: 26px 8px 4px; }
        .steps::before { content: ""; position: absolute; top: 17px; left: 18px; right: 18px; height: 4px; border-radius: 4px; background: #e9eef3; }
        .steps .fill { position: absolute; top: 17px; left: 18px; height: 4px; border-radius: 4px; background: linear-gradient(90deg, var(--pink), #F06E87); }
        .step { position: relative; z-index: 1; text-align: center; width: 80px; }
        .step .dot { width: 38px; height: 38px; margin: 0 auto 6px; border-radius: 50%; background: #fff; border: 3px solid #e2e8ef; color: #a3adbb; font-weight: 800; display: flex; align-items: center; justify-content: center; }
        .step.on .dot { background: var(--pink); border-color: var(--pink); color: #fff; }
        .step .lbl { font-size: .72rem; font-weight: 800; color: var(--muted); }
        .step.on .lbl { color: var(--navy); }

        .answer { border-left: 4px solid var(--ok); background: var(--ok-soft); border-radius: 0 14px 14px 0; padding: 14px 16px; color: #16283c; white-space: pre-line; line-height: 1.55; overflow-wrap: anywhere; }
        .pending { border-left: 4px solid var(--pink); background: var(--pink-soft); border-radius: 0 14px 14px 0; padding: 14px 16px; color: #5a2a37; line-height: 1.55; }

        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
        .kv { background: #f8fafc; border-radius: 12px; padding: 10px 12px; }
        .kv.full { grid-column: 1 / -1; }
        .kv small { display: block; font-size: .62rem; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); font-weight: 800; }
        .kv div { font-size: .9rem; color: var(--navy); font-weight: 700; margin-top: 2px; word-break: break-word; overflow-wrap: anywhere; white-space: pre-line; }

        .foot { text-align: center; font-size: .78rem; color: var(--muted); margin-top: 22px; line-height: 1.6; }
        @media (max-width: 560px) {
            .grid { grid-template-columns: 1fr; }
            h1 { font-size: 1.25rem; }
            .brand .silog { height: 44px; } .brand .plexa { height: 36px; }
            .step { width: 64px; } .step .lbl { font-size: .64rem; }
        }
    </style>
</head>
<body>
<div class="wrap">

    <div class="hero">
        <div class="brand">
            <img class="silog" src="{{ asset('img/silogtran-logo.svg') }}" alt="Silogtran">
            <img class="plexa" src="{{ asset($logoPlexa) }}" alt="Plexa">
        </div>
        <div class="kicker">Seguimiento de tu solicitud</div>
        <h1>{{ $p->asunto }}</h1>
        <div class="rad">
            <i class="fas fa-hashtag" style="color:var(--pink)"></i>
            <div><small>N° de radicado</small><code>{{ $p->radicado }}</code></div>
        </div>
    </div>

    {{-- Estado --}}
    <div class="card">
        <div class="status {{ $resuelta ? 'done' : 'open' }}">
            <div class="ico"><i class="fas {{ $resuelta ? 'fa-check-circle' : 'fa-hourglass-half' }}"></i></div>
            <div>
                <b>{{ $resuelta ? '¡Tu solicitud fue resuelta!' : 'Tu solicitud está ' . mb_strtolower($pasos[$p->estado] ?? $estadoTxt) }}</b>
                <span>Última actualización: {{ optional($p->updated_at)->format('d/m/Y h:i a') }}</span>
            </div>
        </div>

        <div class="steps">
            <div class="fill" style="width: calc((100% - 36px) * {{ $actual / (count($claves) - 1) }});"></div>
            @foreach($pasos as $clave => $nombre)
                <div class="step {{ $loop->index <= $actual ? 'on' : '' }}">
                    <div class="dot">@if($loop->index < $actual || ($resuelta && $loop->last))<i class="fas fa-check"></i>@else{{ $loop->iteration }}@endif</div>
                    <div class="lbl">{{ $nombre }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Respuesta --}}
    <div class="card">
        <h2><i class="fas fa-reply" style="color:var(--pink)"></i> Respuesta de nuestro equipo</h2>
        @if($p->respuesta)
            <div class="answer">{{ $p->respuesta }}</div>
        @else
            <div class="pending">Aún estamos revisando tu solicitud. Te avisaremos por correo apenas tengamos una respuesta; también puedes volver a esta página cuando quieras.</div>
        @endif
    </div>

    {{-- Detalle --}}
    <div class="card">
        <h2><i class="fas fa-file-alt" style="color:var(--pink)"></i> Detalle de la solicitud</h2>
        <div class="grid">
            <div class="kv"><small>Tipo</small><div>{{ $tipoTxt }}</div></div>
            <div class="kv"><small>Producto o servicio</small><div>{{ $p->producto_servicio ?: '—' }}</div></div>
            <div class="kv"><small>Solicitante</small><div>{{ $p->nombre }}</div></div>
            <div class="kv"><small>Fecha de radicación</small><div>{{ optional($p->created_at)->format('d/m/Y h:i a') }}</div></div>
            @if($ubicacion)<div class="kv full"><small>Ubicación</small><div>{{ $ubicacion }}</div></div>@endif
            <div class="kv full"><small>Descripción</small><div>{{ $p->descripcion }}</div></div>
        </div>
    </div>

    <div class="foot">
        Esta página es personal: no compartas el enlace.<br>
        &copy; {{ date('Y') }} Plexa · Centro PQRS
    </div>
</div>
</body>
</html>
