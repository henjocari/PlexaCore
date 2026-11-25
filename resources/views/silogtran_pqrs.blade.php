@php
    // Modo embebido: la página se muestra dentro de Silogtran (iframe) filtrada por el NIT del cliente
    $embed       = $embed ?? false;
    $nitCliente  = $nitCliente ?? null;
    $embedParams = $embedParams ?? [];
    $radicadoOk  = $radicadoOk ?? null;

    // Datos del modal de éxito (interno: sesión · embebido: ?radicado= validado contra el NIT)
    $exitoRadicado = $radicadoOk->radicado ?? session('pqrs_radicado');
    $exitoTipo     = $radicadoOk->tipo ?? session('pqrs_tipo');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Plexa Core | Silogtran PQRS</title>

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --px-green: #E94262;
            --px-green-dark: #CC3366;
            --px-green-deep: #A8284A;
            --px-green-soft: #FDEDF0;
            --px-leaf: #F06E87;
            --px-navy: #1B1B1F;
            --px-muted: #8a94a6;
            --px-line: #e6eaf0;
            --px-bg: #EEF0EF;
            --px-danger: #c0392b;
            --px-warn: #f6c23e;
            --px-info: #3b82c4;
            --ease-spring: cubic-bezier(.34, 1.56, .64, 1);
            --ease-out: cubic-bezier(.22, 1, .36, 1);
        }

        body { background: var(--px-bg); }
        .pq-wrap { max-width: 1280px; margin: 0 auto; padding: 8px 20px 60px; }

        /* ================= HERO ================= */
        .pq-hero {
            position: relative; overflow: hidden; border-radius: 22px; color: #fff;
            padding: 34px 34px 30px; margin-bottom: 26px;
            background: linear-gradient(120deg, #0d0d0f 0%, #1c1c22 48%, #4a1426 100%);
            background-size: 220% 220%;
            animation: pqGradient 14s ease infinite;
            box-shadow: 0 18px 40px rgba(15,15,18,.35);
        }
        @keyframes pqGradient { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

        .pq-blob { position: absolute; border-radius: 50%; filter: blur(30px); opacity: .45; background: var(--px-green); pointer-events: none; }
        .pq-blob.b2 { background: #307ABE; opacity: .35; }
        .pq-blob.b1 { width: 240px; height: 240px; right: -60px; top: -80px; animation: pqFloat 9s ease-in-out infinite; }
        .pq-blob.b2 { width: 130px; height: 130px; right: 220px; bottom: -60px; animation: pqFloat 11s ease-in-out infinite reverse; }
        .pq-blob.b3 { width: 70px; height: 70px; left: 45%; top: 18px; opacity: .12; animation: pqFloat 7s ease-in-out infinite; }
        @keyframes pqFloat { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(-18px, 14px) scale(1.08); } }

        /* Logo de la empresa (esquina superior derecha del encabezado) */
        .pq-company {
            position: absolute; right: 30px; top: 24px; z-index: 2;
            animation: pqCompanyIn .9s var(--ease-spring) .2s both, pqCompanyFloat 6s ease-in-out 1.1s infinite;
        }
        /* Logo sin recuadro: la imagen es transparente; la sombra lo separa del fondo oscuro */
        .pq-company img { display: block; height: 80px; width: auto; filter: drop-shadow(0 6px 16px rgba(0,0,0,.45)); }
        @keyframes pqCompanyIn { from { opacity: 0; transform: translateY(-10px) scale(.85); } to { opacity: 1; transform: none; } }
        @keyframes pqCompanyFloat { 0%,100% { transform: translateY(0); } 100% { transform: translateY(-4px); } }

        .pq-crumb { font-size: .76rem; letter-spacing: .5px; opacity: .8; margin-bottom: 6px; }
        .pq-crumb b { color: #FFB3C2; }
        .pq-brand { display: flex; align-items: center; gap: 22px; margin-bottom: 4px; position: relative; z-index: 1; }
        .pq-logo { height: 92px; width: auto; flex-shrink: 0; filter: drop-shadow(0 6px 18px rgba(233,66,98,.35)); animation: pqLogoIn 1s var(--ease-spring) both; }
        @keyframes pqLogoIn { from { opacity: 0; transform: scale(.7) rotate(-8deg); } to { opacity: 1; transform: none; } }
        .pq-brand-sep { width: 2px; align-self: stretch; background: linear-gradient(transparent, rgba(255,255,255,.35), transparent); }
        .pq-title { font-size: 1.9rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 12px; }
        .pq-title .tag { font-size: .7rem; font-weight: 800; letter-spacing: 1px; padding: 5px 10px; border-radius: 30px; background: var(--px-green); border: 1px solid rgba(255,255,255,.25); }

        /* ===== Tema Silogtran: solo la sección "Silogtran" del menú, solo en esta página.
           El resto del menú conserva el verde de Plexa. ===== */
        #accordionSidebar .menu-silogtran {
            background: linear-gradient(135deg, #0d0d0f 0%, #1c1c22 60%, #3a1020 100%);
            margin-left: .6rem; margin-right: .6rem;
            animation: pqMenuIn .6s var(--ease-out) both;
        }
        #accordionSidebar .menu-silogtran-head { border-radius: 12px 12px 0 0; padding-top: .7rem; color: rgba(255,179,194,.85) !important; }
        #accordionSidebar .menu-silogtran-item { border-radius: 0 0 12px 12px; padding-bottom: .3rem; box-shadow: 0 10px 22px rgba(0,0,0,.25); }
        #accordionSidebar .menu-silogtran-item .nav-link,
        #accordionSidebar .menu-silogtran-item .nav-link i { color: #fff; }
        #accordionSidebar .menu-silogtran-item .nav-link:hover i { color: #FFB3C2; }
        #accordionSidebar .menu-silogtran .collapse-inner .collapse-item.active { color: #E94262; font-weight: 800; background: #FDEDF0; }
        #accordionSidebar .menu-silogtran .collapse-inner .collapse-item.active i { color: #E94262 !important; }
        @keyframes pqMenuIn { from { opacity: 0; transform: translateX(-10px); } to { opacity: 1; transform: none; } }
        /* Menú contraído (solo íconos) */
        #accordionSidebar.toggled .menu-silogtran { margin-left: .35rem; margin-right: .35rem; }
        .scroll-to-top { background: rgba(233,66,98,.75); }
        .page-item.active .page-link { background: #E94262; border-color: #E94262; }
        .page-link { color: #CC3366; }
        .page-link:hover { color: #A8284A; }
        .pq-sub { opacity: .85; margin: 6px 0 22px; font-size: .92rem; max-width: 620px; }

        .pq-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; position: relative; z-index: 1; }
        .pq-stat {
            background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.22); border-radius: 14px;
            padding: 14px 16px; backdrop-filter: blur(6px); transition: transform .35s var(--ease-spring), background .3s;
            opacity: 0; transform: translateY(16px); animation: pqRise .7s var(--ease-out) forwards;
        }
        .pq-stat:nth-child(2) { animation-delay: .08s; } .pq-stat:nth-child(3) { animation-delay: .16s; } .pq-stat:nth-child(4) { animation-delay: .24s; }
        .pq-stat:hover { transform: translateY(-4px) scale(1.02); background: rgba(255,255,255,.2); }
        .pq-stat .n { font-size: 1.7rem; font-weight: 800; line-height: 1; }
        .pq-stat .l { font-size: .7rem; text-transform: uppercase; letter-spacing: .8px; opacity: .8; margin-top: 6px; }
        .pq-stat i { float: right; opacity: .55; font-size: 1.2rem; }
        @keyframes pqRise { to { opacity: 1; transform: translateY(0); } }

        /* ================= CARD ================= */
        .pq-card {
            background: #fff; border: 1px solid var(--px-line); border-radius: 20px;
            box-shadow: 0 6px 24px rgba(20,40,70,.06); margin-bottom: 26px;
            opacity: 0; transform: translateY(24px); animation: pqRise .8s var(--ease-out) .15s forwards;
        }
        .pq-card-head { padding: 20px 26px; border-bottom: 1px solid #eef1f5; display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
        .pq-card-ico { width: 44px; height: 44px; border-radius: 12px; background: var(--px-green-soft); color: var(--px-green); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
        .pq-card-title { font-weight: 800; color: var(--px-navy); margin: 0; font-size: 1.05rem; }
        .pq-card-sub { font-size: .78rem; color: var(--px-muted); margin: 0; }
        .pq-card-body { padding: 26px; }

        /* ================= STEPPER ================= */
        .pq-steps { display: flex; justify-content: space-between; position: relative; margin: 0 6px 34px; }
        .pq-steps::before, .pq-steps .fill {
            content: ""; position: absolute; top: 21px; left: 22px; right: 22px; height: 4px; border-radius: 4px; background: #e9eef3;
        }
        .pq-steps .fill { right: auto; width: 0; background: linear-gradient(90deg, var(--px-green), var(--px-leaf)); transition: width .6s var(--ease-out); z-index: 1; }
        .pq-step { position: relative; z-index: 2; text-align: center; width: 90px; cursor: default; }
        .pq-step .dot {
            width: 46px; height: 46px; margin: 0 auto 8px; border-radius: 50%; background: #fff; border: 3px solid #e2e8ef;
            display: flex; align-items: center; justify-content: center; color: #a3adbb; font-weight: 800;
            transition: all .45s var(--ease-spring);
        }
        .pq-step .lbl { font-size: .72rem; font-weight: 700; color: var(--px-muted); letter-spacing: .3px; transition: color .3s; }
        .pq-step.active .dot { border-color: var(--px-green); color: var(--px-green); transform: scale(1.12); box-shadow: 0 0 0 6px rgba(233,66,98,.14); animation: pqPulse 2s infinite; }
        .pq-step.active .lbl { color: var(--px-green); }
        .pq-step.done .dot { background: var(--px-green); border-color: var(--px-green); color: #fff; }
        .pq-step.done { cursor: pointer; }
        .pq-step.done .lbl { color: var(--px-navy); }
        @keyframes pqPulse { 0%,100% { box-shadow: 0 0 0 6px rgba(233,66,98,.14); } 50% { box-shadow: 0 0 0 11px rgba(233,66,98,.04); } }

        /* ================= PANELS ================= */
        .pq-panel { display: none; }
        .pq-panel.show { display: block; animation: pqSlideIn .55s var(--ease-out); }
        .pq-panel.show.back { animation-name: pqSlideBack; }
        @keyframes pqSlideIn { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: none; } }
        @keyframes pqSlideBack { from { opacity: 0; transform: translateX(-40px); } to { opacity: 1; transform: none; } }
        .pq-panel h5 { font-weight: 800; color: var(--px-navy); font-size: 1.05rem; margin-bottom: 4px; }
        .pq-panel .hint { color: var(--px-muted); font-size: .84rem; margin-bottom: 22px; }

        /* Tipo cards */
        .pq-types { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
        .pq-type { position: relative; cursor: pointer; margin: 0; }
        .pq-type input { position: absolute; opacity: 0; pointer-events: none; }
        .pq-type .box {
            height: 100%; border: 2px solid var(--px-line); border-radius: 18px; padding: 22px 18px; text-align: center; background: #fff;
            transition: transform .4s var(--ease-spring), border-color .3s, box-shadow .3s; position: relative; overflow: hidden;
            opacity: 0; animation: pqPop .6s var(--ease-spring) forwards;
        }
        .pq-type:nth-child(2) .box { animation-delay: .07s; } .pq-type:nth-child(3) .box { animation-delay: .14s; } .pq-type:nth-child(4) .box { animation-delay: .21s; }
        @keyframes pqPop { from { opacity: 0; transform: scale(.85) translateY(14px); } to { opacity: 1; transform: none; } }
        .pq-type .ico {
            width: 62px; height: 62px; margin: 0 auto 12px; border-radius: 18px; display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; transition: transform .5s var(--ease-spring);
        }
        .pq-type .name { font-weight: 800; color: var(--px-navy); font-size: 1rem; }
        .pq-type .desc { font-size: .76rem; color: var(--px-muted); margin-top: 4px; line-height: 1.35; }
        .pq-type .check {
            position: absolute; top: 12px; right: 12px; width: 24px; height: 24px; border-radius: 50%; background: var(--px-green); color: #fff;
            font-size: .7rem; display: flex; align-items: center; justify-content: center; transform: scale(0); transition: transform .4s var(--ease-spring);
        }
        .pq-type:hover .box { transform: translateY(-6px); box-shadow: 0 14px 30px rgba(20,40,70,.10); }
        .pq-type:hover .ico { transform: rotate(-8deg) scale(1.1); }
        .pq-type input:checked + .box { border-color: var(--c); box-shadow: 0 14px 32px var(--cs); transform: translateY(-6px); }
        .pq-type input:checked + .box .check { transform: scale(1); background: var(--c); }
        .pq-type input:checked + .box .ico { animation: pqWiggle .6s ease; }
        .pq-type input:focus-visible + .box { outline: 3px solid rgba(233,66,98,.35); outline-offset: 3px; }
        @keyframes pqWiggle { 0%,100% { transform: rotate(0) scale(1.1); } 25% { transform: rotate(-12deg) scale(1.15); } 75% { transform: rotate(10deg) scale(1.15); } }
        .pq-type .ripple { position: absolute; border-radius: 50%; transform: scale(0); background: var(--cs); animation: pqRipple .7s ease-out; pointer-events: none; }
        @keyframes pqRipple { to { transform: scale(4); opacity: 0; } }

        .t-p { --c: #E94262; --cs: rgba(233,66,98,.22); } .t-p .ico { background: #FDEDF0; color: #E94262; }
        .t-q { --c: #e0a020; --cs: rgba(224,160,32,.22); } .t-q .ico { background: #fdf5e3; color: #d39516; }
        .t-r { --c: #c0392b; --cs: rgba(192,57,43,.20); } .t-r .ico { background: #fdecec; color: #c0392b; }
        .t-s { --c: #3b82c4; --cs: rgba(59,130,196,.20); } .t-s .ico { background: #e8f1fa; color: #3b82c4; }

        /* Floating inputs */
        .pq-field { position: relative; margin-bottom: 20px; }
        .pq-field .in {
            width: 100%; border: 1.5px solid #dfe4ea; border-radius: 12px; padding: 20px 40px 8px 42px; font-size: .9rem; color: var(--px-navy);
            background: #fff; transition: border-color .25s, box-shadow .25s, transform .25s; appearance: none;
        }
        .pq-field textarea.in { padding-top: 26px; min-height: 150px; resize: vertical; }
        .pq-field select.in { cursor: pointer; }
        .pq-field label {
            position: absolute; left: 42px; top: 15px; font-size: .88rem; color: #94a0b2; pointer-events: none;
            transition: all .22s var(--ease-out); margin: 0;
        }
        .pq-field .ic { position: absolute; left: 15px; top: 16px; color: #a3adbb; transition: color .25s, transform .3s var(--ease-spring); }
        .pq-field .ok { position: absolute; right: 14px; top: 16px; color: var(--px-green); transform: scale(0); transition: transform .35s var(--ease-spring); }
        .pq-field .in:focus { outline: none; border-color: var(--px-green); box-shadow: 0 0 0 4px rgba(233,66,98,.12); }
        .pq-field .in:focus ~ .ic { color: var(--px-green); transform: scale(1.15); }
        .pq-field .in:focus ~ label,
        .pq-field .in:not(:placeholder-shown) ~ label,
        .pq-field select.in ~ label { top: 6px; font-size: .66rem; font-weight: 800; letter-spacing: .6px; text-transform: uppercase; color: var(--px-green); }
        .pq-field.valid .ok { transform: scale(1); }
        .pq-field.invalid .in { border-color: var(--px-danger); box-shadow: 0 0 0 4px rgba(192,57,43,.08); }
        .pq-field.invalid label, .pq-field.invalid .ic { color: var(--px-danger) !important; }
        .pq-field .err { font-size: .72rem; color: var(--px-danger); margin-top: 5px; display: none; padding-left: 4px; }
        .pq-field.invalid .err { display: block; animation: pqFadeDown .3s ease; }
        .shake { animation: pqShake .45s ease; }
        @keyframes pqShake { 10%,90% { transform: translateX(-2px); } 20%,80% { transform: translateX(4px); } 30%,50%,70% { transform: translateX(-7px); } 40%,60% { transform: translateX(7px); } }
        @keyframes pqFadeDown { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: none; } }

        .pq-counter { position: absolute; right: 12px; bottom: 10px; display: flex; align-items: center; gap: 6px; font-size: .7rem; color: var(--px-muted); }
        .pq-counter svg { transform: rotate(-90deg); }
        .pq-counter circle { fill: none; stroke-width: 3; }
        .pq-counter .bg { stroke: #e9eef3; }
        .pq-counter .fg { stroke: var(--px-green); stroke-linecap: round; transition: stroke-dashoffset .3s, stroke .3s; }

        /* Chips categoría */
        .pq-label { font-size: .68rem; font-weight: 800; letter-spacing: .6px; color: var(--px-muted); text-transform: uppercase; margin-bottom: 10px; display: block; }
        .pq-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 22px; }
        .pq-chip input { display: none; }
        .pq-chip span {
            display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 30px; border: 1.5px solid #dfe4ea;
            font-size: .8rem; font-weight: 700; color: #536176; cursor: pointer; background: #fff; transition: all .3s var(--ease-spring);
        }
        .pq-chip span:hover { border-color: var(--px-green); color: var(--px-green); transform: translateY(-2px); }
        .pq-chip input:checked + span { background: var(--px-green); border-color: var(--px-green); color: #fff; transform: scale(1.05); box-shadow: 0 6px 14px rgba(233,66,98,.3); }
        .pq-chips.invalid span { border-color: rgba(192,57,43,.5); }

        /* Prioridad segmented */
        .pq-seg { position: relative; display: inline-flex; background: #f1f4f7; border-radius: 12px; padding: 4px; margin-bottom: 22px; }
        .pq-seg input { display: none; }
        .pq-seg label { position: relative; z-index: 2; margin: 0; padding: 8px 22px; font-size: .8rem; font-weight: 800; color: #6b778a; cursor: pointer; transition: color .3s; }
        .pq-seg input:checked + label { color: #fff; }
        .pq-seg .glider { position: absolute; z-index: 1; top: 4px; bottom: 4px; left: 4px; border-radius: 9px; background: var(--px-green); transition: all .45s var(--ease-spring); box-shadow: 0 4px 12px rgba(0,0,0,.12); }

        /* Dropzone */
        .pq-drop {
            border: 2px dashed #cfd8e1; border-radius: 16px; padding: 24px; text-align: center; cursor: pointer; transition: all .3s; background: #fafcfd; position: relative;
        }
        .pq-drop i.big { font-size: 2rem; color: var(--px-green); margin-bottom: 8px; display: block; transition: transform .4s var(--ease-spring); }
        .pq-drop:hover, .pq-drop.over { border-color: var(--px-green); background: var(--px-green-soft); }
        .pq-drop:hover i.big, .pq-drop.over i.big { transform: translateY(-6px) scale(1.1); }
        .pq-drop.over { transform: scale(1.01); }
        .pq-drop .t { font-weight: 700; color: var(--px-navy); font-size: .88rem; }
        .pq-drop .s { font-size: .74rem; color: var(--px-muted); }
        .pq-drop input { display: none; }
        .pq-file { display: none; align-items: center; gap: 12px; margin-top: 12px; padding: 12px 14px; border-radius: 12px; background: var(--px-green-soft); animation: pqPop .45s var(--ease-spring); }
        .pq-file.show { display: flex; }
        .pq-file .fi { width: 38px; height: 38px; border-radius: 10px; background: #fff; display: flex; align-items: center; justify-content: center; color: var(--px-green); }
        .pq-file .fn { font-weight: 700; font-size: .82rem; color: var(--px-navy); word-break: break-all; }
        .pq-file .fs { font-size: .7rem; color: var(--px-muted); }
        .pq-file .bar { height: 4px; border-radius: 4px; background: #F9D3DB; overflow: hidden; margin-top: 5px; }
        .pq-file .bar span { display: block; height: 100%; width: 0; background: var(--px-green); transition: width .9s var(--ease-out); }
        .pq-file .rm { margin-left: auto; border: none; background: #fff; width: 30px; height: 30px; border-radius: 50%; color: var(--px-danger); transition: transform .3s; }
        .pq-file .rm:hover { transform: rotate(90deg) scale(1.1); }

        /* Review */
        .pq-review { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin-bottom: 20px; }
        .pq-rv { background: #f8fafc; border: 1px solid #eef1f5; border-radius: 14px; padding: 12px 16px; opacity: 0; animation: pqRise .5s var(--ease-out) forwards; }
        .pq-rv.full { grid-column: 1 / -1; }
        .pq-rv .k { font-size: .66rem; font-weight: 800; letter-spacing: .6px; text-transform: uppercase; color: var(--px-muted); }
        .pq-rv .v { font-size: .88rem; color: var(--px-navy); font-weight: 600; margin-top: 2px; white-space: pre-line; word-break: break-word; }

        .pq-switch { display: flex; gap: 12px; align-items: flex-start; cursor: pointer; padding: 14px 16px; border-radius: 14px; border: 1.5px solid #dfe4ea; transition: all .3s; margin: 0; }
        .pq-switch:hover { border-color: var(--px-green); }
        .pq-switch input { display: none; }
        .pq-switch .tg { flex-shrink: 0; width: 44px; height: 24px; border-radius: 30px; background: #d4dbe3; position: relative; transition: background .3s; }
        .pq-switch .tg::after { content: ""; position: absolute; top: 3px; left: 3px; width: 18px; height: 18px; border-radius: 50%; background: #fff; transition: transform .4s var(--ease-spring); box-shadow: 0 2px 4px rgba(0,0,0,.2); }
        .pq-switch input:checked + .tg { background: var(--px-green); }
        .pq-switch input:checked + .tg::after { transform: translateX(20px); }
        .pq-switch .tx { font-size: .82rem; color: #536176; }
        .pq-switch.invalid { border-color: var(--px-danger); }

        /* Buttons */
        .pq-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; padding-top: 20px; border-top: 1px solid #eef1f5; }
        .pq-btn { border: none; border-radius: 12px; padding: 12px 22px; font-weight: 800; font-size: .86rem; cursor: pointer; transition: all .3s var(--ease-spring); display: inline-flex; align-items: center; gap: 8px; position: relative; overflow: hidden; }
        .pq-btn-primary { background: linear-gradient(135deg, var(--px-green), var(--px-green-dark)); color: #fff; box-shadow: 0 8px 18px rgba(233,66,98,.3); }
        .pq-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(233,66,98,.38); color: #fff; }
        .pq-btn-primary:active { transform: scale(.97); }
        .pq-btn-primary i { transition: transform .3s; }
        .pq-btn-primary:hover i.fa-arrow-right { transform: translateX(4px); }
        .pq-btn-primary::after { content: ""; position: absolute; top: 0; left: -120%; width: 60%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,.35), transparent); transform: skewX(-20deg); }
        .pq-btn-primary:hover::after { animation: pqShine .8s ease; }
        @keyframes pqShine { to { left: 140%; } }
        .pq-btn-ghost { background: #fff; color: #536176; border: 1.5px solid #dfe4ea; }
        .pq-btn-ghost:hover { background: #f6f8fa; transform: translateX(-2px); }
        .pq-btn[disabled] { opacity: .75; cursor: wait; }
        .pq-btn .spin { width: 16px; height: 16px; border: 2.5px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: pqSpin .7s linear infinite; display: none; }
        .pq-btn.loading .spin { display: inline-block; }
        .pq-btn.loading .lbl-ico { display: none; }
        @keyframes pqSpin { to { transform: rotate(360deg); } }
        .pq-step-count { font-size: .76rem; color: var(--px-muted); font-weight: 700; }

        /* ================= LISTADO ================= */
        .pq-filters { display: flex; gap: 10px; flex-wrap: wrap; margin-left: auto; }
        .pq-filters .f { border: 1.5px solid #dfe4ea; border-radius: 10px; padding: 7px 12px; font-size: .8rem; color: var(--px-navy); transition: border-color .25s, box-shadow .25s; }
        .pq-filters .f:focus { outline: none; border-color: var(--px-green); box-shadow: 0 0 0 3px rgba(233,66,98,.12); }
        .pq-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .pq-table th { font-size: .68rem; letter-spacing: .8px; text-transform: uppercase; color: var(--px-muted); padding: 11px 14px; background: #f8fafc; border-bottom: 1px solid #eef1f5; white-space: nowrap; }
        .pq-table td { padding: 13px 14px; border-bottom: 1px solid #f2f4f7; font-size: .84rem; color: #334155; vertical-align: middle; }
        .pq-table tbody tr { opacity: 0; animation: pqRise .5s var(--ease-out) forwards; transition: background .25s; }
        .pq-table tbody tr:hover { background: #FFF6F8; }
        .pq-rad { font-family: ui-monospace, Menlo, Consolas, monospace; font-weight: 700; color: var(--px-green-deep); font-size: .78rem; white-space: nowrap; }
        .pq-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 30px; font-size: .7rem; font-weight: 800; white-space: nowrap; }
        .pq-badge .d { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
        .b-radicado { background: #e8f1fa; color: #2c6aa3; } .b-radicado .d { animation: pqBlink 1.4s infinite; }
        .b-gestion { background: #fdf5e3; color: #b07a0c; } .b-gestion .d { animation: pqBlink 1s infinite; }
        .b-cerrado { background: #eaf7e6; color: #2f7a25; }
        @keyframes pqBlink { 0%,100% { opacity: 1; } 50% { opacity: .25; } }
        .ty { font-weight: 800; font-size: .76rem; }
        .ty-Peticion { color: #E94262; } .ty-Queja { color: #d39516; } .ty-Reclamo { color: #c0392b; } .ty-Sugerencia { color: #3b82c4; }
        .pr-Alta { color: var(--px-danger); font-weight: 800; } .pr-Media { color: #b07a0c; font-weight: 700; } .pr-Baja { color: var(--px-muted); font-weight: 700; }
        .pq-act { border: none; background: var(--px-green-soft); color: var(--px-green); width: 34px; height: 34px; border-radius: 10px; transition: all .3s var(--ease-spring); }
        .pq-act:hover { background: var(--px-green); color: #fff; transform: rotate(-8deg) scale(1.1); }
        .pq-empty { text-align: center; padding: 40px 10px; color: var(--px-muted); }
        .pq-empty i { font-size: 2.6rem; color: #F6C6D0; margin-bottom: 10px; animation: pqFloat 4s ease-in-out infinite; display: inline-block; }

        /* ================= SUCCESS ================= */
        .pq-success-overlay { position: fixed; inset: 0; background: rgba(15,15,18,.6); backdrop-filter: blur(4px); z-index: 2000; display: flex; align-items: center; justify-content: center; padding: 16px; animation: pqFadeIn .35s ease; }
        @keyframes pqFadeIn { from { opacity: 0; } to { opacity: 1; } }
        .pq-success { background: #fff; border-radius: 24px; padding: 36px 30px 28px; text-align: center; max-width: 430px; width: 100%; animation: pqPop .6s var(--ease-spring); position: relative; z-index: 2; }
        .pq-check svg { width: 96px; height: 96px; }
        .pq-check .c { stroke: var(--px-green); stroke-width: 3; fill: none; stroke-dasharray: 166; stroke-dashoffset: 166; animation: pqDraw .7s var(--ease-out) .15s forwards; }
        .pq-check .k { stroke: var(--px-green); stroke-width: 4; fill: none; stroke-linecap: round; stroke-linejoin: round; stroke-dasharray: 48; stroke-dashoffset: 48; animation: pqDraw .45s var(--ease-out) .75s forwards; }
        .pq-check { display: inline-block; border-radius: 50%; animation: pqGlow 1.2s ease 1s; }
        @keyframes pqDraw { to { stroke-dashoffset: 0; } }
        @keyframes pqGlow { 0% { box-shadow: 0 0 0 0 rgba(233,66,98,.45); } 100% { box-shadow: 0 0 0 26px rgba(233,66,98,0); } }
        .pq-success h4 { font-weight: 800; color: var(--px-navy); margin: 14px 0 6px; }
        .pq-success p { color: var(--px-muted); font-size: .88rem; }
        .pq-radbox { display: flex; align-items: center; justify-content: center; gap: 10px; background: var(--px-green-soft); border: 1.5px dashed var(--px-green); border-radius: 14px; padding: 12px; margin: 14px 0 20px; }
        .pq-radbox code { font-size: 1.02rem; color: var(--px-green-deep); font-weight: 800; background: none; }
        .pq-radbox button { border: none; background: var(--px-green); color: #fff; width: 32px; height: 32px; border-radius: 9px; transition: transform .3s var(--ease-spring); }
        .pq-radbox button:hover { transform: scale(1.12); }
        #pqConfetti { position: fixed; inset: 0; pointer-events: none; z-index: 2001; }

        /* Modal gestion */
        .pq-modal .modal-content { border: none; border-radius: 20px; overflow: hidden; }
        .pq-modal.fade .modal-dialog { transform: scale(.85) translateY(30px); transition: transform .45s var(--ease-spring); }
        .pq-modal.show .modal-dialog { transform: none; }
        .pq-modal .mh { padding: 20px 24px; background: linear-gradient(120deg, var(--px-green), var(--px-green-dark)); color: #fff; display: flex; align-items: center; gap: 12px; }
        .pq-modal .mh h5 { margin: 0; font-weight: 800; font-size: 1rem; }
        .pq-modal .mh small { opacity: .85; }
        .pq-modal .mh .x { margin-left: auto; background: rgba(255,255,255,.2); border: none; color: #fff; width: 34px; height: 34px; border-radius: 50%; transition: transform .3s; }
        .pq-modal .mh .x:hover { transform: rotate(90deg); }
        .pq-modal .mb { padding: 22px 24px; }
        .pq-modal .mf { padding: 14px 24px; background: #f8fafc; display: flex; justify-content: flex-end; gap: 10px; }
        .pq-detail { background: #f8fafc; border-radius: 12px; padding: 12px 14px; font-size: .84rem; color: #334155; margin-bottom: 16px; white-space: pre-line; max-height: 180px; overflow: auto; }

        .pq-alert { border: none; border-radius: 14px; padding: 14px 18px; display: flex; gap: 10px; align-items: center; animation: pqFadeDown .5s ease; font-size: .88rem; }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 991px) {
            .pq-types { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .pq-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 575px) {
            .pq-wrap { padding: 0 12px 40px; }
            .pq-hero { padding: 24px 20px; border-radius: 18px; }
            .pq-title { font-size: 1.45rem; }
            .pq-brand { flex-direction: column; align-items: flex-start; gap: 12px; }
            .pq-logo { height: 70px; }
            .pq-company { top: 18px; right: 16px; }
            .pq-company img { height: 48px; }
            .pq-card-body { padding: 18px; }
            .pq-step { width: 62px; }
            .pq-step .lbl { font-size: .62rem; }
            .pq-review { grid-template-columns: 1fr; }
            .pq-seg label { padding: 8px 14px; }
            .pq-actions .pq-btn { padding: 11px 16px; }
            .pq-step-count { display: none; }
        }
        @media (prefers-reduced-motion: reduce) {
            *:not(#pqIntro):not(#pqIntro *):not(#pqOutro):not(#pqOutro *),
            *:not(#pqIntro *):not(#pqOutro *)::before,
            *:not(#pqIntro *):not(#pqOutro *)::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }

        /* ================= INTRO DE ENTRADA (Plexa -> Silogtran) ================= */
        #pqIntro { position: fixed; inset: 0; z-index: 3000; display: none; overflow: hidden; cursor: pointer; will-change: transform; }
        html.pq-intro-on #pqIntro { display: block; }
        html.pq-intro-on body { overflow: hidden; }
        /* La página espera quieta hasta que se levanta el telón */
        html.pq-intro-on #wrapper, html.pq-intro-on #wrapper * { animation-play-state: paused !important; }

        .pi-green { position: absolute; inset: 0; background: #378E77; }
        .pi-dark {
            position: absolute; inset: 0;
            background: radial-gradient(circle at 50% 46%, #3a1222 0%, #18171c 45%, #0b0b0d 100%);
            clip-path: circle(0% at 50% 50%);
            animation: piReveal 1s cubic-bezier(.76, 0, .24, 1) .1s forwards;
        }
        @keyframes piReveal { to { clip-path: circle(80% at 50% 50%); } }

        .pi-glow {
            position: absolute; left: 50%; top: 46%; width: 520px; height: 520px; margin: -260px 0 0 -260px; border-radius: 50%;
            background: radial-gradient(circle, rgba(233,66,98,.45) 0%, rgba(233,66,98,0) 65%);
            opacity: 0; transform: scale(.4); animation: piGlow 1.6s ease .5s forwards;
        }
        @keyframes piGlow { 60% { opacity: 1; transform: scale(1.05); } to { opacity: .75; transform: scale(1); } }

        .pi-ring {
            position: absolute; left: 50%; top: 46%; width: 150px; height: 150px; margin: -75px 0 0 -75px; border-radius: 50%;
            border: 2px solid #E94262; opacity: 0; transform: scale(.3);
            animation: piRing 1.4s cubic-bezier(.22, 1, .36, 1) .55s forwards;
        }
        .pi-ring.r2 { border-color: #307ABE; animation-delay: .75s; }
        .pi-ring.r3 { border-color: #FFB3C2; animation-delay: .95s; }
        @keyframes piRing { 0% { opacity: .9; transform: scale(.3); } 100% { opacity: 0; transform: scale(5.5); } }

        .pi-center { position: absolute; left: 50%; top: 46%; transform: translate(-50%, -50%); display: flex; flex-direction: column; align-items: center; transition: transform .7s cubic-bezier(.76, 0, .24, 1), opacity .5s ease; }
        .pi-logo {
            height: 150px; width: auto; opacity: 0; transform: scale(.55) translateY(24px); filter: blur(12px);
            animation: piLogo 1s cubic-bezier(.34, 1.56, .64, 1) .6s forwards;
        }
        @keyframes piLogo { to { opacity: 1; transform: none; filter: blur(0) drop-shadow(0 10px 30px rgba(233,66,98,.45)); } }
        .pi-text {
            margin-top: 18px; color: rgba(255,255,255,.85); font-size: .78rem; font-weight: 800; letter-spacing: 5px; text-transform: uppercase;
            opacity: 0; transform: translateY(12px); animation: piUp .6s ease 1.05s forwards;
        }
        @keyframes piUp { to { opacity: 1; transform: none; } }
        .pi-bar { margin-top: 16px; width: 190px; height: 3px; border-radius: 3px; background: rgba(255,255,255,.12); overflow: hidden; opacity: 0; animation: piUp .4s ease 1.05s forwards; }
        .pi-bar span { display: block; height: 100%; width: 0; border-radius: 3px; background: linear-gradient(90deg, #E94262, #F06E87, #307ABE); animation: piFill .75s cubic-bezier(.65, 0, .35, 1) 1.15s forwards; }
        @keyframes piFill { to { width: 100%; } }

        /* Borde rosa del telón al subir */
        .pi-edge { position: absolute; left: 0; right: 0; bottom: 0; height: 4px; background: linear-gradient(90deg, #E94262, #F06E87, #307ABE, #E94262); box-shadow: 0 0 24px 6px rgba(233,66,98,.55); opacity: 0; transition: opacity .2s; }

        #pqIntro.pi-exit { animation: piExit .85s cubic-bezier(.76, 0, .24, 1) forwards; }
        #pqIntro.pi-exit .pi-edge { opacity: 1; }
        #pqIntro.pi-exit .pi-center { transform: translate(-50%, -80%) scale(.92); opacity: 0; }
        @keyframes piExit { to { transform: translateY(-100%); } }

        /* ================= SALIDA (Silogtran -> Plexa) ================= */
        #pqOutro { position: fixed; inset: 0; z-index: 3000; pointer-events: none; visibility: hidden; overflow: hidden; }
        #pqOutro.go { visibility: visible; pointer-events: all; cursor: progress; }
        .po-dark {
            position: absolute; inset: 0; transform: translateY(-100%);
            background: radial-gradient(circle at 50% 46%, #3a1222 0%, #18171c 45%, #0b0b0d 100%);
        }
        .po-dark::after { content: ""; position: absolute; left: 0; right: 0; bottom: -2px; height: 4px; background: linear-gradient(90deg, #E94262, #F06E87, #307ABE, #E94262); box-shadow: 0 0 24px 6px rgba(233,66,98,.55); }
        #pqOutro.go .po-dark { animation: poDrop .5s cubic-bezier(.76, 0, .24, 1) forwards; }
        @keyframes poDrop { to { transform: translateY(0); } }

        .po-silog { position: absolute; left: 50%; top: 46%; height: 120px; transform: translate(-50%, -50%) scale(.85); opacity: 0; filter: drop-shadow(0 10px 30px rgba(233,66,98,.45)); }
        #pqOutro.go .po-silog { animation: poSilog .7s ease .3s forwards; }
        @keyframes poSilog { 0% { opacity: 0; transform: translate(-50%, -50%) scale(.85); } 40% { opacity: 1; transform: translate(-50%, -50%) scale(1); } 100% { opacity: 0; transform: translate(-50%, -50%) scale(.6); filter: blur(8px); } }

        .po-green { position: absolute; inset: 0; background: #378E77; clip-path: circle(0% at 50% 46%); }
        #pqOutro.go .po-green { animation: poGreen .65s cubic-bezier(.76, 0, .24, 1) .6s forwards; }
        @keyframes poGreen { to { clip-path: circle(80% at 50% 46%); } }

        .po-plexa {
            position: absolute; left: 50%; top: 46%; transform: translate(-50%, -50%) scale(.6); opacity: 0;
            background: #fff; border-radius: 22px; padding: 20px 30px; box-shadow: 0 18px 40px rgba(0,0,0,.18);
        }
        .po-plexa img { display: block; width: 200px; height: auto; }
        #pqOutro.go .po-plexa { animation: poPlexa .55s cubic-bezier(.34, 1.56, .64, 1) .9s forwards; }
        @keyframes poPlexa { to { opacity: 1; transform: translate(-50%, -50%) scale(1); } }

        @media (max-width: 575px) {
            .po-silog { height: 90px; }
            .po-plexa img { width: 160px; }
            .pi-logo { height: 110px; }
            .pi-glow { width: 360px; height: 360px; margin: -180px 0 0 -180px; }
        }
    </style>

    @if($embed)
        {{-- Ajustes para verse dentro del iframe de Silogtran (sin menú ni cabecera de PlexaCore) --}}
        <style>
            body { background: var(--px-bg); }
            .pq-wrap { padding-top: 16px; }
            .pq-nit { display: inline-flex; align-items: center; gap: 6px; margin-left: 8px; padding: 3px 10px; border-radius: 30px; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.25); font-weight: 700; color: #fff; }
        </style>
    @else
    {{-- Decide antes de pintar si se muestra la intro (evita parpadeos) --}}
    <script>
        (function () {
            var omitir = @json(session()->has('pqrs_radicado') || session()->has('success') || session()->has('error') || $errors->any() || count(request()->query()) > 0);
            try {
                // Solo se omite después de radicar/gestionar o al usar filtros y búsqueda
                if (omitir) return;
                document.documentElement.classList.add('pq-intro-on');
            } catch (e) {}
        })();
    </script>
    @endif
</head>

<body id="page-top">

@unless($embed)
{{-- ================= INTRO ================= --}}
<div id="pqIntro" aria-hidden="true" title="Clic para saltar">
    <div class="pi-green"></div>
    <div class="pi-dark"></div>
    <div class="pi-glow"></div>
    <span class="pi-ring"></span><span class="pi-ring r2"></span><span class="pi-ring r3"></span>
    <div class="pi-center">
        <img src="{{ asset('img/silogtran-logo.svg') }}" alt="" class="pi-logo">
        <div class="pi-text">Centro PQRS</div>
        <div class="pi-bar"><span></span></div>
    </div>
    <div class="pi-edge"></div>
</div>
@endunless

<div id="wrapper">
    @unless($embed) @include('layouts.menu') @endunless

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @unless($embed) @include('layouts.cabecera') @endunless

            <div class="pq-wrap">

                {{-- ================= HERO ================= --}}
                <div class="pq-hero">
                    <span class="pq-blob b1"></span><span class="pq-blob b2"></span><span class="pq-blob b3"></span>
                    @php
                        // Logo recortado (sin margen transparente); si no está en el servidor se usa el original
                        $logoPlexa = is_file(public_path('img/IsotipoPlexa-recorte.png')) ? 'img/IsotipoPlexa-recorte.png' : 'img/IsotipoPlexa.png';
                    @endphp
                    <div class="pq-company" title="Plexa"><img src="{{ asset($logoPlexa) }}" alt="Plexa"></div>

                    <div class="pq-brand">
                        <img src="{{ asset('img/silogtran-logo.svg') }}" alt="Silogtran" class="pq-logo">
                        <span class="pq-brand-sep d-none d-sm-block"></span>
                        <div>
                            <div class="pq-crumb">Silogtran / <b>PQRS</b>
                                @if($embed)<span class="pq-nit"><i class="fas fa-building"></i> NIT {{ $nitCliente }}</span>@endif
                            </div>
                            <h1 class="pq-title"><i class="fas fa-headset"></i> Centro PQRS <span class="tag">SILOGTRAN</span></h1>
                        </div>
                    </div>
                    <p class="pq-sub">Radica peticiones, quejas, reclamos y sugerencias relacionadas con la operación de transporte. Te asignaremos un número de radicado para su seguimiento.</p>

                    <div class="pq-stats">
                        <div class="pq-stat"><i class="fas fa-inbox"></i><div class="n" data-count="{{ $stats['total'] }}">0</div><div class="l">Total radicadas</div></div>
                        <div class="pq-stat"><i class="fas fa-paper-plane"></i><div class="n" data-count="{{ $stats['radicado'] }}">0</div><div class="l">Radicadas</div></div>
                        <div class="pq-stat"><i class="fas fa-spinner"></i><div class="n" data-count="{{ $stats['gestion'] }}">0</div><div class="l">En gestión</div></div>
                        <div class="pq-stat"><i class="fas fa-check-double"></i><div class="n" data-count="{{ $stats['cerrado'] }}">0</div><div class="l">Cerradas</div></div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="pq-alert mb-4" style="background:#eaf7e6; color:#2f7a25;"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="pq-alert mb-4" style="background:#fdecec; color:#c0392b;"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="pq-alert mb-4" style="background:#fdecec; color:#c0392b;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>Revisa la información: {{ implode(' ', $errors->all()) }}</div>
                    </div>
                @endif

                {{-- ================= FORMULARIO ================= --}}
                <div class="pq-card">
                    <div class="pq-card-head">
                        <div class="pq-card-ico"><i class="fas fa-file-signature"></i></div>
                        <div>
                            <h6 class="pq-card-title">Nueva solicitud PQRS</h6>
                            <p class="pq-card-sub">Completa los 4 pasos. Los campos con * son obligatorios.</p>
                        </div>
                    </div>

                    <div class="pq-card-body">
                        <div class="pq-steps" id="pqSteps">
                            <div class="fill" id="pqFill"></div>
                            <div class="pq-step active" data-step="1"><div class="dot"><span>1</span></div><div class="lbl">Tipo</div></div>
                            <div class="pq-step" data-step="2"><div class="dot"><span>2</span></div><div class="lbl">Tus datos</div></div>
                            <div class="pq-step" data-step="3"><div class="dot"><span>3</span></div><div class="lbl">Detalle</div></div>
                            <div class="pq-step" data-step="4"><div class="dot"><span>4</span></div><div class="lbl">Confirmar</div></div>
                        </div>

                        <form id="pqForm" action="{{ $embed ? route('silogtran.pqrs.embed.store', $embedParams) : route('silogtran.pqrs.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf

                            {{-- PASO 1 --}}
                            <div class="pq-panel show" data-panel="1">
                                <h5>¿Qué deseas radicar?</h5>
                                <p class="hint">Selecciona el tipo de solicitud que mejor describe tu caso.</p>

                                <div class="pq-types" id="pqTypes">
                                    @php
                                        $tipos = [
                                            ['Peticion',   't-p', 'fa-hand-paper',      'Petición',   'Solicitud de información, documentos o un servicio.'],
                                            ['Queja',      't-q', 'fa-frown',           'Queja',      'Inconformidad con la atención o el servicio prestado.'],
                                            ['Reclamo',    't-r', 'fa-exclamation-triangle', 'Reclamo', 'Exiges corrección por un incumplimiento o error.'],
                                            ['Sugerencia', 't-s', 'fa-lightbulb',       'Sugerencia', 'Una idea para mejorar nuestros procesos.'],
                                        ];
                                    @endphp
                                    @foreach($tipos as $t)
                                        <label class="pq-type {{ $t[1] }}">
                                            <input type="radio" name="tipo" value="{{ $t[0] }}" {{ old('tipo') === $t[0] ? 'checked' : '' }}>
                                            <div class="box">
                                                <span class="check"><i class="fas fa-check"></i></span>
                                                <div class="ico"><i class="fas {{ $t[2] }}"></i></div>
                                                <div class="name">{{ $t[3] }}</div>
                                                <div class="desc">{{ $t[4] }}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="text-danger small mt-2" id="errTipo" style="display:none;"><i class="fas fa-info-circle"></i> Selecciona un tipo de solicitud para continuar.</div>
                            </div>

                            {{-- PASO 2 --}}
                            <div class="pq-panel" data-panel="2">
                                <h5>Datos del solicitante</h5>
                                <p class="hint">Usaremos esta información para notificarte la respuesta.</p>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pq-field">
                                            <input class="in" type="text" name="nombre" id="f_nombre" placeholder=" " maxlength="150" required
                                                   value="{{ old('nombre', trim(($usuario->Nombre ?? '') . ' ' . ($usuario->Apellido ?? ''))) }}">
                                            <i class="fas fa-user ic"></i><label for="f_nombre">Nombre completo *</label><i class="fas fa-check-circle ok"></i>
                                            <div class="err">Ingresa tu nombre completo.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="pq-field">
                                            <select class="in" name="tipo_documento" id="f_tdoc" required>
                                                @foreach(['CC' => 'Cédula de ciudadanía', 'CE' => 'Cédula de extranjería', 'NIT' => 'NIT', 'PAS' => 'Pasaporte'] as $k => $v)
                                                    <option value="{{ $k }}" {{ old('tipo_documento', $embed ? 'NIT' : 'CC') === $k ? 'selected' : '' }}>{{ $v }}</option>
                                                @endforeach
                                            </select>
                                            <i class="fas fa-id-badge ic"></i><label for="f_tdoc">Tipo de documento *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="pq-field">
                                            <input class="in" type="text" name="documento" id="f_doc" placeholder=" " maxlength="30" required inputmode="numeric"
                                                   value="{{ old('documento', $embed ? $nitCliente : ($usuario->cedula ?? '')) }}">
                                            <i class="fas fa-id-card ic"></i><label for="f_doc">Número de documento *</label><i class="fas fa-check-circle ok"></i>
                                            <div class="err">Ingresa un número de documento válido.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="pq-field">
                                            <input class="in" type="email" name="email" id="f_email" placeholder=" " maxlength="150" required
                                                   value="{{ old('email', $usuario->email ?? '') }}">
                                            <i class="fas fa-envelope ic"></i><label for="f_email">Correo electrónico *</label><i class="fas fa-check-circle ok"></i>
                                            <div class="err">Ingresa un correo electrónico válido.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="pq-field">
                                            <input class="in" type="tel" name="telefono" id="f_tel" placeholder=" " maxlength="30"
                                                   value="{{ old('telefono', $usuario->cel ?? '') }}">
                                            <i class="fas fa-mobile-alt ic"></i><label for="f_tel">Celular</label><i class="fas fa-check-circle ok"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="pq-field">
                                            <input class="in" type="text" name="pais" id="f_pais" placeholder=" " maxlength="80" required list="pqPaises" autocomplete="country-name"
                                                   value="{{ old('pais', 'Colombia') }}">
                                            <i class="fas fa-globe-americas ic"></i><label for="f_pais">País *</label><i class="fas fa-check-circle ok"></i>
                                            <div class="err">Indica el país.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="pq-field">
                                            <input class="in" type="text" name="departamento" id="f_depto" placeholder=" " maxlength="80" required list="pqDeptos" autocomplete="address-level1"
                                                   value="{{ old('departamento') }}">
                                            <i class="fas fa-map ic"></i><label for="f_depto">Departamento *</label><i class="fas fa-check-circle ok"></i>
                                            <div class="err">Indica el departamento.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="pq-field">
                                            <input class="in" type="text" name="ciudad" id="f_ciudad" placeholder=" " maxlength="80" required autocomplete="address-level2"
                                                   value="{{ old('ciudad') }}">
                                            <i class="fas fa-city ic"></i><label for="f_ciudad">Ciudad *</label><i class="fas fa-check-circle ok"></i>
                                            <div class="err">Indica la ciudad.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="pq-field">
                                            <input class="in" type="text" name="direccion" id="f_dir" placeholder=" " maxlength="200" autocomplete="street-address"
                                                   value="{{ old('direccion') }}">
                                            <i class="fas fa-map-marker-alt ic"></i><label for="f_dir">Dirección</label><i class="fas fa-check-circle ok"></i>
                                        </div>
                                    </div>
                                </div>
                                {{-- Sugerencias (se puede escribir cualquier otro valor) --}}
                                <datalist id="pqPaises">
                                    @foreach(['Colombia', 'Venezuela', 'Ecuador', 'Perú', 'Panamá', 'México', 'Estados Unidos', 'España'] as $pa)
                                        <option value="{{ $pa }}">
                                    @endforeach
                                </datalist>
                                <datalist id="pqDeptos">
                                    @foreach(\App\Models\SilogtranPqrs::DEPARTAMENTOS_CO as $dep)
                                        <option value="{{ $dep }}">
                                    @endforeach
                                </datalist>
                            </div>

                            {{-- PASO 3 --}}
                            <div class="pq-panel" data-panel="3">
                                <h5>Cuéntanos el detalle</h5>
                                <p class="hint">Entre más claro sea tu relato, más rápido podremos darte respuesta.</p>

                                <span class="pq-label">Producto o servicio *</span>
                                <div class="pq-chips" id="pqProductos">
                                    @php
                                        $iconosProd = [
                                            'Comercialización GLP' => 'fa-fire', 'Comercialización Gas Natural' => 'fa-burn',
                                            'Transporte GLP' => 'fa-truck', 'Transporte de Hidrocarburos' => 'fa-oil-can',
                                            'Operaciones en Planta' => 'fa-industry', 'Administrativos' => 'fa-briefcase',
                                            'Cotizaciones de servicios' => 'fa-file-invoice-dollar',
                                        ];
                                    @endphp
                                    @foreach(\App\Models\SilogtranPqrs::PRODUCTOS as $prod)
                                        <label class="pq-chip mb-0">
                                            <input type="radio" name="producto_servicio" value="{{ $prod }}" {{ old('producto_servicio') === $prod ? 'checked' : '' }}>
                                            <span><i class="fas {{ $iconosProd[$prod] ?? 'fa-tag' }}"></i>{{ $prod }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <span class="pq-label">Categoría *</span>
                                <div class="pq-chips" id="pqChips">
                                    @php
                                        $iconosCat = [
                                            'Operación de transporte' => 'fa-route', 'Manifiestos' => 'fa-file-invoice',
                                            'Facturación' => 'fa-file-invoice-dollar', 'Conductores' => 'fa-id-card-alt',
                                            'Vehículos' => 'fa-truck', 'Plataforma Silogtran' => 'fa-desktop', 'Otro' => 'fa-ellipsis-h',
                                        ];
                                    @endphp
                                    @foreach($categorias as $cat)
                                        <label class="pq-chip mb-0">
                                            <input type="radio" name="categoria" value="{{ $cat }}" {{ old('categoria') === $cat ? 'checked' : '' }}>
                                            <span><i class="fas {{ $iconosCat[$cat] ?? 'fa-tag' }}"></i>{{ $cat }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="pq-label">Prioridad *</span>
                                        <div class="pq-seg" id="pqSeg">
                                            <span class="glider" id="pqGlider"></span>
                                            @foreach(['Baja', 'Media', 'Alta'] as $p)
                                                <input type="radio" name="prioridad" id="pr_{{ $p }}" value="{{ $p }}" {{ old('prioridad', 'Media') === $p ? 'checked' : '' }}>
                                                <label for="pr_{{ $p }}">{{ $p }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="pq-field">
                                            <input class="in" type="text" name="manifiesto" id="f_manif" placeholder=" " maxlength="50" value="{{ old('manifiesto') }}">
                                            <i class="fas fa-hashtag ic"></i><label for="f_manif">N° de manifiesto / remesa (opcional)</label><i class="fas fa-check-circle ok"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="pq-field">
                                    <input class="in" type="text" name="asunto" id="f_asunto" placeholder=" " maxlength="150" required value="{{ old('asunto') }}">
                                    <i class="fas fa-heading ic"></i><label for="f_asunto">Asunto *</label><i class="fas fa-check-circle ok"></i>
                                    <div class="err">Escribe un asunto breve.</div>
                                </div>

                                <div class="pq-field">
                                    <textarea class="in" name="descripcion" id="f_desc" placeholder=" " maxlength="3000" required>{{ old('descripcion') }}</textarea>
                                    <i class="fas fa-align-left ic"></i><label for="f_desc">Descripción detallada * (mín. 20 caracteres)</label>
                                    <div class="pq-counter">
                                        <span id="pqCount">0 / 3000</span>
                                        <svg width="22" height="22"><circle class="bg" cx="11" cy="11" r="8"></circle><circle class="fg" id="pqRing" cx="11" cy="11" r="8" stroke-dasharray="50.27" stroke-dashoffset="50.27"></circle></svg>
                                    </div>
                                    <div class="err">La descripción debe tener al menos 20 caracteres.</div>
                                </div>

                                <span class="pq-label">Adjunto (opcional)</span>
                                <label class="pq-drop mb-0" id="pqDrop">
                                    <input type="file" name="adjunto" id="f_file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                                    <i class="fas fa-cloud-upload-alt big"></i>
                                    <div class="t">Arrastra tu archivo aquí o <u>haz clic para buscar</u></div>
                                    <div class="s">PDF, imágenes, Word o Excel · máximo 10 MB</div>
                                </label>
                                <div class="pq-file" id="pqFile">
                                    <div class="fi"><i class="fas fa-file-alt"></i></div>
                                    <div style="flex:1; min-width:0;">
                                        <div class="fn" id="pqFileName"></div>
                                        <div class="fs" id="pqFileSize"></div>
                                        <div class="bar"><span id="pqFileBar"></span></div>
                                    </div>
                                    <button type="button" class="rm" id="pqFileRm" title="Quitar archivo"><i class="fas fa-times"></i></button>
                                </div>
                            </div>

                            {{-- PASO 4 --}}
                            <div class="pq-panel" data-panel="4">
                                <h5>Revisa y confirma</h5>
                                <p class="hint">Verifica que todo esté correcto antes de radicar.</p>

                                <div class="pq-review" id="pqReview"></div>

                                <label class="pq-switch" id="pqAcepta">
                                    <input type="checkbox" name="acepta" value="1" {{ old('acepta') ? 'checked' : '' }}>
                                    <span class="tg"></span>
                                    <span class="tx">Autorizo el tratamiento de mis datos personales conforme a la política de Plexa y declaro que la información suministrada es veraz.</span>
                                </label>
                            </div>

                            <div class="pq-actions">
                                <button type="button" class="pq-btn pq-btn-ghost" id="pqPrev" style="visibility:hidden;"><i class="fas fa-arrow-left"></i> Atrás</button>
                                <span class="pq-step-count" id="pqStepCount">Paso 1 de 4</span>
                                <button type="button" class="pq-btn pq-btn-primary" id="pqNext">Continuar <i class="fas fa-arrow-right"></i></button>
                                <button type="submit" class="pq-btn pq-btn-primary" id="pqSubmit" style="display:none;">
                                    <span class="spin"></span><i class="fas fa-paper-plane lbl-ico"></i> Radicar PQRS
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ================= LISTADO ================= --}}
                <div class="pq-card" style="animation-delay:.3s;">
                    <div class="pq-card-head">
                        <div class="pq-card-ico"><i class="fas fa-list-alt"></i></div>
                        <div>
                            <h6 class="pq-card-title">Solicitudes radicadas</h6>
                            <p class="pq-card-sub">Seguimiento de las PQRS registradas en Silogtran.</p>
                        </div>
                        <form method="GET" action="{{ $embed ? route('silogtran.pqrs.embed') : route('silogtran.pqrs') }}" class="pq-filters" id="pqFilters">
                            @foreach($embedParams as $k => $v)
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endforeach
                            <input type="text" name="busqueda" class="f" placeholder="Buscar radicado, nombre..." value="{{ request('busqueda') }}">
                            <select name="tipo" class="f" onchange="this.form.submit()">
                                <option value="">Todos los tipos</option>
                                @foreach(['Peticion' => 'Petición', 'Queja' => 'Queja', 'Reclamo' => 'Reclamo', 'Sugerencia' => 'Sugerencia'] as $k => $v)
                                    <option value="{{ $k }}" {{ request('tipo') === $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            <select name="estado" class="f" onchange="this.form.submit()">
                                <option value="">Todos los estados</option>
                                @foreach(['Radicado' => 'Radicado', 'En Gestion' => 'En gestión', 'Cerrado' => 'Cerrado'] as $k => $v)
                                    <option value="{{ $k }}" {{ request('estado') === $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="pq-table">
                            <thead>
                                <tr>
                                    <th>Radicado</th><th>Tipo</th><th>Solicitante</th><th>Asunto</th><th>Categoría</th><th>Prioridad</th><th>Fecha</th><th>Estado</th><th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $puedeGestionar = !$embed && in_array((int) ($usuario->rol ?? 0), [1, 2]); @endphp
                                @forelse($pqrs as $i => $p)
                                    @php
                                        $badge = ['Radicado' => 'b-radicado', 'En Gestion' => 'b-gestion', 'Cerrado' => 'b-cerrado'][$p->estado] ?? 'b-radicado';
                                    @endphp
                                    <tr style="animation-delay: {{ 0.05 * $loop->index + 0.35 }}s;">
                                        <td><span class="pq-rad">{{ $p->radicado }}</span></td>
                                        <td><span class="ty ty-{{ $p->tipo }}">{{ $p->tipo === 'Peticion' ? 'Petición' : $p->tipo }}</span></td>
                                        <td>
                                            <div class="font-weight-bold" style="color:#1B1B1F;">{{ $p->nombre }}</div>
                                            <div class="small text-muted">{{ $p->tipo_documento }} {{ $p->documento }}</div>
                                            @if(!$embed && $p->nit_cliente)
                                                <div class="small" style="color:#A8284A;" title="Radicada desde Silogtran"><i class="fas fa-building"></i> Cliente NIT {{ $p->nit_cliente }}</div>
                                            @endif
                                        </td>
                                        <td style="max-width:240px;">{{ \Illuminate\Support\Str::limit($p->asunto, 60) }}</td>
                                        <td class="small">
                                            @if($p->producto_servicio)<div class="font-weight-bold" style="color:#1B1B1F;">{{ $p->producto_servicio }}</div>@endif
                                            {{ $p->categoria }}
                                        </td>
                                        <td><span class="pr-{{ $p->prioridad }}">{{ $p->prioridad }}</span></td>
                                        <td class="small text-nowrap">{{ optional($p->created_at)->format('d/m/Y H:i') }}</td>
                                        <td><span class="pq-badge {{ $badge }}"><span class="d"></span>{{ $p->estado === 'En Gestion' ? 'En gestión' : $p->estado }}</span></td>
                                        <td class="text-nowrap">
                                            @if($p->adjunto)
                                                <a href="{{ asset('archivos_pqrs/' . $p->adjunto) }}" target="_blank" class="pq-act d-inline-flex align-items-center justify-content-center" title="Ver adjunto"><i class="fas fa-paperclip"></i></a>
                                            @endif
                                            <button type="button" class="pq-act js-ver" title="{{ $puedeGestionar ? 'Gestionar' : 'Ver detalle' }}"
                                                data-id="{{ $p->id }}"
                                                data-radicado="{{ $p->radicado }}"
                                                data-asunto="{{ $p->asunto }}"
                                                data-descripcion="{{ $p->descripcion }}"
                                                data-info="{{ implode(' · ', array_filter([
                                                    $p->producto_servicio,
                                                    $p->email,
                                                    $p->telefono ? 'Cel. ' . $p->telefono : null,
                                                    implode(', ', array_filter([$p->ciudad, $p->departamento, $p->pais])) ?: null,
                                                    $p->direccion,
                                                ])) }}"
                                                data-estado="{{ $p->estado }}"
                                                data-respuesta="{{ $p->respuesta }}">
                                                <i class="fas {{ $puedeGestionar ? 'fa-pen' : 'fa-eye' }}"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr style="animation-delay:.3s;">
                                        <td colspan="9">
                                            <div class="pq-empty">
                                                <i class="fas fa-inbox"></i>
                                                <div class="font-weight-bold">Aún no hay solicitudes</div>
                                                <div class="small">Las PQRS que radiques aparecerán aquí.</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($pqrs->hasPages())
                        <div class="d-flex justify-content-center py-3">{{ $pqrs->links('pagination::bootstrap-4') }}</div>
                    @endif
                </div>

            </div>
        </div>
        @unless($embed) @include('layouts.pie') @endunless
    </div>
</div>

@unless($embed)
{{-- ================= SALIDA ================= --}}
<div id="pqOutro" aria-hidden="true">
    <div class="po-dark"></div>
    <img src="{{ asset('img/silogtran-logo.svg') }}" alt="" class="po-silog">
    <div class="po-green"></div>
    <div class="po-plexa"><img src="{{ asset('img/Logo_plexa.svg') }}" alt=""></div>
</div>
@endunless

{{-- ================= MODAL GESTIÓN ================= --}}
<div class="modal fade pq-modal" id="pqModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" id="pqModalForm">
                @csrf
                <div class="mh">
                    <i class="fas fa-clipboard-check fa-lg"></i>
                    <div><h5 id="pqmRad">PQRS</h5><small id="pqmAsunto"></small></div>
                    <button type="button" class="x" data-dismiss="modal" aria-label="Cerrar">&times;</button>
                </div>
                <div class="mb">
                    <span class="pq-label">Producto · contacto · ubicación</span>
                    <div class="pq-detail" id="pqmInfo"></div>
                    <span class="pq-label">Descripción</span>
                    <div class="pq-detail" id="pqmDesc"></div>

                    @if($puedeGestionar)
                        <span class="pq-label">Estado</span>
                        <div class="pq-chips mb-3">
                            @foreach(['Radicado' => 'fa-paper-plane', 'En Gestion' => 'fa-spinner', 'Cerrado' => 'fa-check-double'] as $e => $ic)
                                <label class="pq-chip mb-0">
                                    <input type="radio" name="estado" value="{{ $e }}">
                                    <span><i class="fas {{ $ic }}"></i>{{ $e === 'En Gestion' ? 'En gestión' : $e }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="pq-field mb-0">
                            <textarea class="in" name="respuesta" id="pqmResp" placeholder=" " maxlength="3000" style="min-height:110px;"></textarea>
                            <i class="fas fa-reply ic"></i><label for="pqmResp">Respuesta / observación</label>
                        </div>
                    @else
                        <span class="pq-label">Respuesta</span>
                        <div class="pq-detail" id="pqmRespView">Sin respuesta todavía.</div>
                    @endif
                </div>
                <div class="mf">
                    <button type="button" class="pq-btn pq-btn-ghost" data-dismiss="modal">Cerrar</button>
                    @if($puedeGestionar)
                        <button type="submit" class="pq-btn pq-btn-primary"><i class="fas fa-save"></i> Guardar</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================= ÉXITO ================= --}}
@if($exitoRadicado)
    <canvas id="pqConfetti"></canvas>
    <div class="pq-success-overlay" id="pqSuccess">
        <div class="pq-success">
            <div class="pq-check">
                <svg viewBox="0 0 56 56"><circle class="c" cx="28" cy="28" r="26"></circle><path class="k" d="M16 29 l8 8 l16 -18"></path></svg>
            </div>
            <h4>¡PQRS radicada con éxito!</h4>
            <p>Tu {{ $exitoTipo === 'Peticion' ? 'petición' : strtolower((string) $exitoTipo) }} fue {{ $exitoTipo === 'Reclamo' ? 'registrado' : 'registrada' }}. Guarda este número para hacer seguimiento:</p>
            <div class="pq-radbox">
                <code id="pqRadNum">{{ $exitoRadicado }}</code>
                <button type="button" id="pqCopy" title="Copiar"><i class="fas fa-copy"></i></button>
            </div>
            <button type="button" class="pq-btn pq-btn-primary" id="pqSuccessClose">Entendido <i class="fas fa-check"></i></button>
        </div>
    </div>
@endif

<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<script>
(function () {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- Intro de entrada ---------- */
    const html = document.documentElement;
    const intro = document.getElementById('pqIntro');
    let introActiva = html.classList.contains('pq-intro-on');
    function terminarIntro() {
        if (!introActiva) return;
        introActiva = false;
        clearTimeout(introTimer);
        intro.classList.add('pi-exit');
        // Las animaciones de la página arrancan mientras sube el telón
        setTimeout(() => { html.classList.remove('pq-intro-on'); iniciarContadores(); }, 120);
        setTimeout(() => intro.remove(), 900);
    }
    const introTimer = introActiva ? setTimeout(terminarIntro, 2000) : null;
    if (introActiva) {
        intro.addEventListener('click', terminarIntro);
        document.addEventListener('keydown', e => { if (e.key === 'Escape' || e.key === 'Enter' || e.key === ' ') terminarIntro(); }, { once: true });
    } else if (intro) {
        intro.remove();
    }

    /* ---------- Salida: al ir a otra parte del menú ---------- */
    const outro = document.getElementById('pqOutro');   // no existe en la vista embebida
    let saliendo = false;
    document.addEventListener('click', e => {
        const a = e.target.closest('a[href]');
        if (!outro || !a || saliendo || e.defaultPrevented) return;
        if (e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;   // nueva pestaña, etc.
        if (a.target && a.target !== '_self') return;
        if (a.hasAttribute('download') || a.hasAttribute('data-toggle') || a.hasAttribute('data-dismiss')) return;
        const raw = a.getAttribute('href') || '';
        if (raw === '' || raw.startsWith('#') || raw.startsWith('javascript:')) return;
        let url;
        try { url = new URL(a.href, location.href); } catch (err) { return; }
        if (url.origin !== location.origin) return;                // enlaces externos / mailto
        if (url.pathname === location.pathname) return;            // paginación o filtros del mismo PQRS
        e.preventDefault();
        saliendo = true;
        outro.classList.add('go');
        setTimeout(() => { location.href = url.href; }, 1250);
    });
    // Si el usuario vuelve con "atrás", quitar el telón
    window.addEventListener('pageshow', ev => {
        if (ev.persisted && outro) { saliendo = false; outro.classList.remove('go'); }
    });

    /* ---------- Contadores animados ---------- */
    function iniciarContadores() {
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count, 10) || 0;
        if (reduceMotion || target === 0) { el.textContent = target; return; }
        const dur = 1200, t0 = performance.now();
        const tick = now => {
            const p = Math.min((now - t0) / dur, 1);
            el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
            if (p < 1) requestAnimationFrame(tick);
        };
        setTimeout(() => requestAnimationFrame(tick), 300);
    });
    }
    if (!introActiva) iniciarContadores();

    /* ---------- Wizard ---------- */
    const form = document.getElementById('pqForm');
    const panels = [...form.querySelectorAll('.pq-panel')];
    const steps = [...document.querySelectorAll('.pq-step')];
    const fill = document.getElementById('pqFill');
    const btnPrev = document.getElementById('pqPrev');
    const btnNext = document.getElementById('pqNext');
    const btnSubmit = document.getElementById('pqSubmit');
    const stepCount = document.getElementById('pqStepCount');
    const total = panels.length;
    let current = 1;

    function goTo(n, back) {
        current = n;
        panels.forEach(p => {
            const on = parseInt(p.dataset.panel, 10) === n;
            p.classList.remove('show', 'back');
            if (on) { void p.offsetWidth; p.classList.add('show'); if (back) p.classList.add('back'); }
        });
        steps.forEach(s => {
            const i = parseInt(s.dataset.step, 10);
            s.classList.toggle('active', i === n);
            s.classList.toggle('done', i < n);
            s.querySelector('.dot').innerHTML = i < n ? '<i class="fas fa-check"></i>' : '<span>' + i + '</span>';
        });
        const wrap = document.getElementById('pqSteps');
        const usable = wrap.clientWidth - 44;
        fill.style.width = (usable * (n - 1) / (total - 1)) + 'px';
        btnPrev.style.visibility = n === 1 ? 'hidden' : 'visible';
        btnNext.style.display = n === total ? 'none' : '';
        btnSubmit.style.display = n === total ? '' : 'none';
        stepCount.textContent = 'Paso ' + n + ' de ' + total;
        if (n === 3) moveGlider();
        if (n === 4) buildReview();
        const top = document.querySelector('.pq-card').getBoundingClientRect().top + window.scrollY - 90;
        if (window.scrollY > top) window.scrollTo({ top, behavior: reduceMotion ? 'auto' : 'smooth' });
    }
    window.addEventListener('resize', () => goToFillOnly());
    function goToFillOnly() {
        const usable = document.getElementById('pqSteps').clientWidth - 44;
        fill.style.width = (usable * (current - 1) / (total - 1)) + 'px';
    }

    steps.forEach(s => s.addEventListener('click', () => {
        const i = parseInt(s.dataset.step, 10);
        if (i < current) goTo(i, true);
    }));
    btnPrev.addEventListener('click', () => goTo(current - 1, true));
    btnNext.addEventListener('click', () => { if (validateStep(current)) goTo(current + 1); });

    /* ---------- Validación ---------- */
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
    const rules = {
        f_nombre: v => v.trim().length >= 3,
        f_doc:    v => /^[0-9A-Za-z.\-]{4,30}$/.test(v.trim()),
        f_email:  v => emailRe.test(v.trim()),
        f_pais:   v => v.trim().length >= 2,
        f_depto:  v => v.trim().length >= 2,
        f_ciudad: v => v.trim().length >= 2,
        f_asunto: v => v.trim().length >= 4,
        f_desc:   v => v.trim().length >= 20,
    };

    function checkField(input, showError) {
        const field = input.closest('.pq-field');
        const rule = rules[input.id];
        const val = input.value;
        const ok = rule ? rule(val) : val.trim() !== '';
        field.classList.toggle('valid', ok && val.trim() !== '');
        if (showError) field.classList.toggle('invalid', !ok && !!rule);
        else if (ok) field.classList.remove('invalid');
        return ok || !rule;
    }

    function shake(el) { el.classList.remove('shake'); void el.offsetWidth; el.classList.add('shake'); }

    form.querySelectorAll('.pq-field .in').forEach(inp => {
        inp.addEventListener('input', () => checkField(inp, false));
        inp.addEventListener('blur', () => { if (inp.value !== '') checkField(inp, true); });
        if (inp.value) checkField(inp, false);
    });

    function validateStep(n) {
        let ok = true;
        if (n === 1) {
            const sel = form.querySelector('input[name="tipo"]:checked');
            document.getElementById('errTipo').style.display = sel ? 'none' : 'block';
            if (!sel) { shake(document.getElementById('pqTypes')); ok = false; }
        }
        if (n === 2 || n === 3) {
            panels[n - 1].querySelectorAll('.pq-field .in').forEach(inp => {
                if (rules[inp.id] && !checkField(inp, true)) { ok = false; shake(inp.closest('.pq-field')); }
            });
        }
        if (n === 3) {
            const prods = document.getElementById('pqProductos');
            const prod = form.querySelector('input[name="producto_servicio"]:checked');
            prods.classList.toggle('invalid', !prod);
            if (!prod) { ok = false; shake(prods); }

            const chips = document.getElementById('pqChips');
            const cat = form.querySelector('input[name="categoria"]:checked');
            chips.classList.toggle('invalid', !cat);
            if (!cat) { ok = false; shake(chips); }
        }
        if (n === 4) {
            const ac = document.getElementById('pqAcepta');
            const chk = ac.querySelector('input').checked;
            ac.classList.toggle('invalid', !chk);
            if (!chk) { ok = false; shake(ac); }
        }
        return ok;
    }

    /* ---------- Tipo: ripple ---------- */
    form.querySelectorAll('.pq-type').forEach(card => {
        card.addEventListener('click', e => {
            if (reduceMotion) return;
            const box = card.querySelector('.box');
            const r = box.getBoundingClientRect();
            const size = Math.max(r.width, r.height);
            const s = document.createElement('span');
            s.className = 'ripple';
            s.style.width = s.style.height = size + 'px';
            s.style.left = ((e.clientX || r.left + r.width / 2) - r.left - size / 2) + 'px';
            s.style.top = ((e.clientY || r.top + r.height / 2) - r.top - size / 2) + 'px';
            box.appendChild(s);
            setTimeout(() => s.remove(), 700);
        });
        card.querySelector('input').addEventListener('change', () => {
            document.getElementById('errTipo').style.display = 'none';
            setTimeout(() => { if (current === 1) goTo(2); }, reduceMotion ? 0 : 450);
        });
    });
    form.querySelectorAll('input[name="categoria"]').forEach(r =>
        r.addEventListener('change', () => document.getElementById('pqChips').classList.remove('invalid')));
    form.querySelectorAll('input[name="producto_servicio"]').forEach(r =>
        r.addEventListener('change', () => document.getElementById('pqProductos').classList.remove('invalid')));
    document.querySelector('#pqAcepta input').addEventListener('change', function () {
        if (this.checked) document.getElementById('pqAcepta').classList.remove('invalid');
    });

    /* ---------- Prioridad (glider) ---------- */
    const glider = document.getElementById('pqGlider');
    const prioColors = { Baja: '#8a94a6', Media: '#e0a020', Alta: '#c0392b' };
    function moveGlider() {
        const sel = form.querySelector('input[name="prioridad"]:checked');
        if (!sel) return;
        const lbl = form.querySelector('label[for="' + sel.id + '"]');
        glider.style.width = lbl.offsetWidth + 'px';
        glider.style.left = lbl.offsetLeft + 'px';
        glider.style.background = prioColors[sel.value];
    }
    form.querySelectorAll('input[name="prioridad"]').forEach(r => r.addEventListener('change', moveGlider));

    /* ---------- Contador descripción ---------- */
    const desc = document.getElementById('f_desc');
    const ring = document.getElementById('pqRing');
    const count = document.getElementById('pqCount');
    const C = 50.27;
    function updCount() {
        const len = desc.value.length;
        count.textContent = len + ' / 3000';
        ring.style.strokeDashoffset = C - C * Math.min(len / 3000, 1);
        ring.style.stroke = len < 20 ? '#e0a020' : (len > 2800 ? '#c0392b' : '#E94262');
    }
    desc.addEventListener('input', updCount); updCount();

    /* ---------- Dropzone ---------- */
    const drop = document.getElementById('pqDrop');
    const fileIn = document.getElementById('f_file');
    const fileBox = document.getElementById('pqFile');
    const allowed = ['pdf','jpg','jpeg','png','doc','docx','xls','xlsx'];
    ['dragenter','dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('over'); }));
    ['dragleave','drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('over'); }));
    drop.addEventListener('drop', e => {
        if (e.dataTransfer.files.length) {
            const dt = new DataTransfer(); dt.items.add(e.dataTransfer.files[0]);
            fileIn.files = dt.files; showFile();
        }
    });
    fileIn.addEventListener('change', showFile);
    document.getElementById('pqFileRm').addEventListener('click', () => {
        fileIn.value = ''; fileBox.classList.remove('show'); drop.style.display = '';
    });
    function showFile() {
        const f = fileIn.files[0];
        if (!f) return;
        const ext = f.name.split('.').pop().toLowerCase();
        if (!allowed.includes(ext) || f.size > 10 * 1024 * 1024) {
            fileIn.value = '';
            shake(drop);
            Swal.fire({ icon: 'warning', title: 'Archivo no válido', text: 'Solo PDF, imágenes, Word o Excel de máximo 10 MB.', confirmButtonColor: '#E94262' });
            return;
        }
        const icons = { pdf: 'fa-file-pdf', jpg: 'fa-file-image', jpeg: 'fa-file-image', png: 'fa-file-image', doc: 'fa-file-word', docx: 'fa-file-word', xls: 'fa-file-excel', xlsx: 'fa-file-excel' };
        fileBox.querySelector('.fi i').className = 'fas ' + (icons[ext] || 'fa-file-alt');
        document.getElementById('pqFileName').textContent = f.name;
        document.getElementById('pqFileSize').textContent = (f.size / 1024 / 1024).toFixed(2) + ' MB';
        const bar = document.getElementById('pqFileBar');
        bar.style.width = '0';
        drop.style.display = 'none';
        fileBox.classList.remove('show'); void fileBox.offsetWidth; fileBox.classList.add('show');
        setTimeout(() => bar.style.width = '100%', 60);
    }

    /* ---------- Resumen ---------- */
    function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
    function buildReview() {
        const v = name => (form.querySelector('[name="' + name + '"]:checked') || form.querySelector('[name="' + name + '"]') || {}).value || '';
        const tipoTxt = { Peticion: 'Petición' }[v('tipo')] || v('tipo');
        const tdoc = form.querySelector('[name="tipo_documento"]');
        const items = [
            ['Tipo', tipoTxt], ['Producto o servicio', v('producto_servicio')],
            ['Categoría', v('categoria')], ['Solicitante', v('nombre')],
            ['Documento', tdoc.value + ' ' + v('documento')], ['Correo', v('email')],
            ['Celular', v('telefono') || '—'], ['Ubicación', [v('ciudad'), v('departamento'), v('pais')].filter(Boolean).join(', ')],
            ['Dirección', v('direccion') || '—', true],
            ['Prioridad', v('prioridad')], ['Manifiesto', v('manifiesto') || '—'],
            ['Asunto', v('asunto'), true], ['Descripción', v('descripcion'), true],
            ['Adjunto', fileIn.files[0] ? fileIn.files[0].name : 'Sin adjunto', true],
        ];
        document.getElementById('pqReview').innerHTML = items.map((it, i) =>
            '<div class="pq-rv' + (it[2] ? ' full' : '') + '" style="animation-delay:' + (i * 0.05) + 's"><div class="k">' + esc(it[0]) + '</div><div class="v">' + esc(it[1]) + '</div></div>'
        ).join('');
    }

    /* ---------- Envío ---------- */
    form.addEventListener('submit', e => {
        for (let i = 1; i <= total; i++) {
            if (!validateStep(i)) { e.preventDefault(); if (i !== current) goTo(i, i < current); return; }
        }
        btnSubmit.classList.add('loading');
        btnSubmit.disabled = true;
    });
    form.addEventListener('keydown', e => {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && current < total) { e.preventDefault(); btnNext.click(); }
    });

    /* ---------- Si hubo errores del servidor, saltar al paso correspondiente ---------- */
    @if($errors->any())
        (function () {
            const errs = @json(array_keys($errors->getMessages()));
            const map = { tipo: 1, nombre: 2, tipo_documento: 2, documento: 2, email: 2, telefono: 2, pais: 2, departamento: 2, ciudad: 2, direccion: 2, producto_servicio: 3, categoria: 3, prioridad: 3, manifiesto: 3, asunto: 3, descripcion: 3, adjunto: 3, acepta: 4 };
            const first = Math.min.apply(null, errs.map(k => map[k] || 4));
            goTo(first);
            const ids = { nombre: 'f_nombre', documento: 'f_doc', email: 'f_email', pais: 'f_pais', departamento: 'f_depto', ciudad: 'f_ciudad', asunto: 'f_asunto', descripcion: 'f_desc' };
            errs.forEach(k => { if (ids[k]) document.getElementById(ids[k]).closest('.pq-field').classList.add('invalid'); });
        })();
    @else
        if (form.querySelector('input[name="tipo"]:checked') === null) goTo(1);
    @endif

    /* ---------- Modal gestionar / ver ---------- */
    const baseGestion = @json(url('/silogtran/pqrs'));
    document.querySelectorAll('.js-ver').forEach(btn => btn.addEventListener('click', () => {
        const d = btn.dataset;
        document.getElementById('pqmRad').textContent = d.radicado;
        document.getElementById('pqmAsunto').textContent = d.asunto;
        document.getElementById('pqmDesc').textContent = d.descripcion;
        document.getElementById('pqmInfo').textContent = d.info || '—';
        document.getElementById('pqModalForm').action = baseGestion + '/' + d.id + '/gestionar';
        const est = document.querySelector('#pqModalForm input[name="estado"][value="' + d.estado + '"]');
        if (est) est.checked = true;
        const resp = document.getElementById('pqmResp');
        if (resp) resp.value = d.respuesta || '';
        const respView = document.getElementById('pqmRespView');
        if (respView) respView.textContent = d.respuesta || 'Sin respuesta todavía.';
        $('#pqModal').modal('show');
    }));

    /* ---------- Éxito + confeti ---------- */
    const success = document.getElementById('pqSuccess');
    if (success) {
        document.getElementById('pqCopy').addEventListener('click', function () {
            const txt = document.getElementById('pqRadNum').textContent.trim();
            (navigator.clipboard ? navigator.clipboard.writeText(txt) : Promise.reject()).then(() => {
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => this.innerHTML = '<i class="fas fa-copy"></i>', 1500);
            }).catch(() => {});
        });
        document.getElementById('pqSuccessClose').addEventListener('click', () => {
            success.style.transition = 'opacity .3s'; success.style.opacity = 0;
            setTimeout(() => { success.remove(); const c = document.getElementById('pqConfetti'); if (c) c.remove(); }, 300);
        });
        if (!reduceMotion) confetti();
    }

    function confetti() {
        const cv = document.getElementById('pqConfetti');
        const ctx = cv.getContext('2d');
        const resize = () => { cv.width = innerWidth; cv.height = innerHeight; };
        resize(); addEventListener('resize', resize);
        const colors = ['#E94262', '#CC3366', '#F06E87', '#FFB3C2', '#307ABE', '#f6c23e'];
        const parts = Array.from({ length: 160 }, () => ({
            x: innerWidth / 2 + (Math.random() - .5) * 120,
            y: innerHeight / 2 - 40,
            vx: (Math.random() - .5) * 16,
            vy: -Math.random() * 15 - 5,
            s: Math.random() * 7 + 4,
            r: Math.random() * Math.PI,
            vr: (Math.random() - .5) * .3,
            c: colors[Math.floor(Math.random() * colors.length)],
        }));
        const t0 = performance.now();
        (function frame(now) {
            ctx.clearRect(0, 0, cv.width, cv.height);
            const life = (now - t0) / 3200;
            parts.forEach(p => {
                p.vy += .35; p.vx *= .99; p.x += p.vx; p.y += p.vy; p.r += p.vr;
                ctx.save(); ctx.globalAlpha = Math.max(1 - life, 0);
                ctx.translate(p.x, p.y); ctx.rotate(p.r); ctx.fillStyle = p.c;
                ctx.fillRect(-p.s / 2, -p.s / 4, p.s, p.s / 2); ctx.restore();
            });
            if (life < 1 && document.body.contains(cv)) requestAnimationFrame(frame);
            else ctx.clearRect(0, 0, cv.width, cv.height);
        })(t0);
    }
})();
</script>
</body>
</html>