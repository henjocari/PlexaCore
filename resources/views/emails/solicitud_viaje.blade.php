<!DOCTYPE html>
<html>
<link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
<head>
    <style>
        .boton {
            background-color: #378E77; /* Color Plexa */
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin-top: 15px;
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">

    {{-- ENCABEZADO DIFERENTE SEGÚN QUIEN SEA --}}
    @if($tipo == 'jefe')
        <h2 style="color: #d9534f;">🔔 Requiere Aprobación</h2>
        <p>El empleado <strong>{{ $datos['empleado'] }}</strong> ha solicitado un viaje.</p>
    @else
        <h2 style="color: #378E77;">✈️ Solicitud Enviada</h2>
        <p>Hola <strong>{{ $datos['empleado'] }}</strong>, hemos recibido tu solicitud.</p>
    @endif

    <hr>
    
    <h3>Detalles del Viaje:</h3>
    <ul>
        <li><strong>Destino:</strong> {{ $datos['destino'] }}</li>
        <li><strong>Fecha:</strong> {{ $datos['fecha'] }}</li>
    </ul>

    {{-- BOTÓN SOLO PARA EL JEFE --}}
    @if($tipo == 'jefe')
        <p>Por favor, ingresa a la plataforma para aprobar o rechazar:</p>
        <a href="{{ $datos['url'] }}" class="boton">
            Ir a Gestionar Viajes
        </a>
    @else
        <p>Te notificaremos cuando tu jefe responda.</p>
    @endif

</body>
</html>