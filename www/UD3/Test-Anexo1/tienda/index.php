<?php
    require_once("lib/bbdd.php");

    $dbName = "tiendaTest";
    $conexion = getConexionTienda();
    crearBD($conexion, $dbName);
    seleccionarBD($conexion, $dbName);
    crearTablaUsuarios($conexion);
    cerrarConexion($conexion);
?>

<?php include_once("components/headPage.php") ?>
    <p>CONTENIDO DE LA PÁGINA INDEX</p>
<?php include_once("components/footerPage.php") ?>
