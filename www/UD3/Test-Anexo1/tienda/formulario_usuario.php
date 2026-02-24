<?php
    require_once("lib/utils.php");
    require_once("lib/bbdd.php");

    $conexion = getConexionTienda();
    seleccionarBD($conexion, "tiendaTest");

    $provincias = [
        "corunha" => "A Coruña",
        "lugo" => "Lugo",
        "ourense" => "Ourense",
        "pontevedra" => "Pontevedra"
    ];

    // Inicializar usuario con valores por defecto
    $usuarioVacio = ["id" => "", "nombre" => "", "apellidos" => "", "edad" => "", "provincia" => ""];
    $usuario = $usuarioVacio;

    // Crear o Editar usuario desde el propio formulario
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {
        $id = $_POST["id"];
        $nombre = sanearValor($_POST["nombre"]);
        $apellidos = sanearValor($_POST["apellidos"]);
        $edad = sanearValor($_POST["edad"]);
        $provincia = sanearValor($_POST["provincia"]);

        $modo = empty($id) ? "create" : "edit";

        if ($modo == "create") {
            //$result = crearUsuario($conexion, $nombre, $apellidos, $edad, $provincia);
            $result = crearUsuarioPreparada($conexion, $nombre, $apellidos, $edad, $provincia);
        }
        if ($modo == "edit") {
            $result = editarUsuario($conexion, $id, $nombre, $apellidos, $edad, $provincia);
        }

    }

    // Entrada desde el botón de editar de la lista
    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
        $id =  $_GET["id"];
        $usuarioQuery = getUsuarioPorId($conexion, $id)[1];
        $usuario = $usuarioQuery->num_rows > 0 ? $usuarioQuery->fetch_assoc() : $usuarioVacio;
    }

    cerrarConexion($conexion);

?>

<?php include_once("components/headPage.php") ?>

    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">

        <!-- input oculto para controlar si es un nuevo registro o se está editando uno existente  -->
        <input id="id" name="id" value="<?= $usuario["id"] ?>" hidden>

        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" value="<?= $usuario["nombre"] ?>" required><br>

        <label for="apellidos">Apellidos</label>
        <input id="apellidos" name="apellidos" value="<?= $usuario["apellidos"] ?>" required><br>

        <label for="edad">Edad</label>
        <input id="edad" name="edad" type="number" value="<?= $usuario["edad"] ?>" required><br>

        <label for="provincia">Provincia</label>
        <select id="provincia" name="provincia">
            <?php foreach ($provincias as $v => $n) :?>
                <option value="<?= $v ?>" <?= $usuario["provincia"] == $v ? "selected" : ""  ?> ><?= $n ?></option>
            <?php endforeach ?>
        </select><br><br>
        <button type="submit"><?= empty($usuario["id"]) ? "Crear" : "Editar" ?></button>
    </form>

<?php include_once("components/footerPage.php") ?>



