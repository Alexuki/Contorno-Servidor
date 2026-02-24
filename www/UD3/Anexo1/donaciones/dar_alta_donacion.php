<?php

require_once "lib/base_datos.php";
require_once "lib/utilidades.php";

$conexion = get_conexion();
seleccionar_bd($conexion,"donacion");

$mensajes = array();

if (isset($_POST['enviar'])) {
    $id = test_input($_POST["id"]);
    $fechaDonacion = test_input($_POST["fecha"]);
    $resultado = dar_alta_donacion($conexion, $id, $fechaDonacion);

    if ($resultado == true) {
        $mensajes[] = array("success", "Donación registrada");
    } else {
        $mensajes[] = array("error", "No puede donar, no se han cumplido 4 meses desde su última donación");
    }
} elseif (isset($_GET['id'])) {
    $id = $_GET["id"];
}
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
  <h1>Alta donacion</h1>

  <?= get_mensajes_html_format($mensajes); ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Fecha: <input type="date" name="fecha">
    <input type="hidden" name="id" value="<?= $id?>"/>
    <br><br>
    <input type="submit" name="enviar" value="Enviar"> 
  </form>
  <footer>
      <p><a href='index.php'>Página de inicio</a></p>
  </footer>

  <?php cerrar_conexion($conexion);?>

</body>

</html>