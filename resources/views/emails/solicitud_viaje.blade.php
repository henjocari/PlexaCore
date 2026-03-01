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
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    {{-- ENCABEZADO DIFERENTE SEGÚN QUIEN SEA --}}
    @if($tipo == 'jefe')
        <h2 style="color: #d9534f;">🔔 Requiere Aprobación</h2>
        <p>El empleado <strong>{{ $datos['empleado'] }}</strong> ha registrado una nueva solicitud de viaje.</p>
    @else
        <h2 style="color: #378E77;">✈️ Solicitud Enviada</h2>
        <p>Hola <strong>{{ $datos['empleado'] }}</strong>, hemos recibido tu solicitud de viaje de forma exitosa.</p>
    @endif

    <hr style="border: 1px solid #eee;">
    
    <h3>Detalles del Viaje:</h3>
    <ul style="background-color: #f9f9f9; padding: 15px 35px; border-radius: 5px;">
        <li><strong>Pasajero:</strong> {{ $datos['pasajero'] }}</li>
        <li><strong>Ruta:</strong> {{ $datos['origen'] }} ➔ {{ $datos['destino'] }}</li>
        <li><strong>Tipo de Viaje:</strong> {{ $datos['tipo'] }}</li>
        <li><strong>Fecha de Salida:</strong> {{ $datos['fecha_ida'] }} ({{ $datos['jornada_ida'] ?? 'N/A' }})</li>
        
        @if(!empty($datos['fecha_regreso']))
            <li><strong>Fecha de Regreso:</strong> {{ $datos['fecha_regreso'] }} ({{ $datos['jornada_regreso'] ?? 'N/A' }})</li>
        @endif
        
        @if(!empty($datos['hospedaje']))
            <li style="margin-top: 10px;"><strong>Detalle de Hoteles:</strong><br>
                <span style="white-space: pre-wrap; color: #555;">{{ str_replace(' | ', "\n", $datos['hospedaje']) }}</span>
            </li>
        @endif
    </ul>

    {{-- BOTÓN SOLO PARA EL JEFE --}}
    @if($tipo == 'jefe')
        <p>Por favor, ingresa a la plataforma para aprobar (requiere adjuntar tiquete) o rechazar esta solicitud:</p>
        <a href="{{ $datos['url'] }}" class="boton">
            Ir a Gestionar Viajes
        </a>
    @else
        <p>Te notificaremos por este medio cuando tu jefe apruebe o rechace el viaje y te adjuntaremos el tiquete.</p>
    @endif

</body>
</html>