<?php
    require_once("lib/utils.php");
    require_once("lib/bbdd.php");

    $conexion = getConexionTienda();
    seleccionarBD($conexion, "tiendaTest");

    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
        $id =  $_GET["id"];
        $result = eliminarUsuario($conexion, $id)[0];
    }
    cerrarConexion($conexion);

?>

<?php include_once("components/headPage.php") ?>

<?php if($result) :?>
    <div class="alert alert-success">Usuario eliminado.</div>
<?php else :?>
    <div class="alert alert-danger">Operación de borrado no completada.</div>
<?php endif ?>

   

<?php include_once("components/footerPage.php") ?>



