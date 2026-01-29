<!DOCTYPE html>
<html>
<link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
<body>
    <h3 style="color: #378E77;">Estado de tu Viaje</h3>
    <p>Hola <strong>{{ $datos['nombre'] }}</strong>,</p>
    
    @if($datos['estado'] == 1)
        <div style="padding:10px; background:#d4edda; color:#155724;">
            ✅ <strong>APROBADO:</strong> Hemos adjuntado tu tiquete a este correo.
        </div>
    @else
        <div style="padding:10px; background:#f8d7da; color:#721c24;">
            ❌ <strong>RECHAZADO:</strong> Tu solicitud no fue aprobada.
        </div>
    @endif
    
    <p><strong>Mensaje:</strong> {{ $datos['mensaje'] }}</p>
</body>
</html>