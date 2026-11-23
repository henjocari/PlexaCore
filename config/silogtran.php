<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PQRS embebido en Silogtran
    |--------------------------------------------------------------------------
    |
    | Silogtran muestra el Centro PQRS dentro de un iframe con el NIT del
    | cliente en la URL:
    |
    |   https://intranet.plexa.co/silogtran/pqrs/embed?nit=900123456&firma=...
    |
    | embed_secret: clave compartida con Silogtran. Si está definida, la URL
    |   debe traer "firma" = hash_hmac('sha256', nit, clave) y, si trae "exp"
    |   (timestamp de vencimiento), la firma es sobre "nit|exp". Sin la firma
    |   correcta la página no abre: así nadie puede cambiar el NIT a mano y
    |   ver las PQRS de otro cliente.
    |   Si se deja vacía, basta con el NIT (solo recomendable en pruebas).
    |
    | embed_origins: dominios que pueden mostrar la página en un iframe
    |   (separados por espacio). Cualquier otro sitio queda bloqueado.
    |
    */

    'embed_secret' => env('SILOGTRAN_EMBED_SECRET', ''),

    'embed_origins' => env('SILOGTRAN_EMBED_ORIGINS', 'https://plexa.colombiasoftware.net'),

];
