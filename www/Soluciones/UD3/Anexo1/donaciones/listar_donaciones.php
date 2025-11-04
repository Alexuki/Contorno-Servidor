<?php

require_once "lib/base_datos.php";
require_once "lib/utilidades.php";
require_once "lib/donaciones.php";

$conexion = get_conexion();
seleccionar_bd($conexion,"donacion");

if (isset($_GET["id"])) {
    $id_user = test_input($_GET["id"]);
    $donaciones = get_donaciones($conexion, $id_user);
}

cerrar_conexion($conexion);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Donación Sangre</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
  </script>

  <h1>Listado de donaciones</h1>
  <table class='table table-hover table-sm text-center'>
        <thead class='thead-light'>
          <tr>
              <th scope='col'>Nombre</th>
              <th scope='col'>Apellidos</th>
              <th scope='col'>Fecha de donación</th>
              <th scope='col'>Fecha próxima donación</th>
          </tr>
          </thead>
          <tbody>
 
<?php
    imprimir_donaciones($donaciones);
?>
  </tbody>
  </table>
  <footer>
      <p><a href='index.php'>Página de inicio</a></p>
  </footer>
</body>
</html>
