<!DOCTYPE html>

<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Habitaciones - Hotel</title>

  <!-- Bootstrap -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Archivo CSS propio -->

  <link rel="stylesheet" href="/public/css/style.css">
</head>
<body class="p-4">

  <div class="container">
    <h2 class="text-center mb-4"> Gestión de Habitaciones </h2>


<div class="row" id="habitaciones-container"></div>

<hr class="my-5">

<h4>Inventario de Habitaciones</h4>
<div class="table-responsive">
  <table class="table table-striped text-center" id="tablaInventario">
    <thead class="table-dark">
      <tr>
        <th># Habitación</th>
        <th>Conductor</th>
        <th>Estado</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>


  </div>

  <!-- Bootstrap JS -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Archivo JS propio -->

  <script src="/public/js/script.js"></script>

</body>
</html>
