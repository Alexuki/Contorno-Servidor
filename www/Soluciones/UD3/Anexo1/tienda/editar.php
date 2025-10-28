<?php

include "lib/base_datos.php";
include "lib/utilidades.php";

$conexion = get_conexion();
seleccionar_bd_tienda($conexion);

$id_user = 0;
$nombre = "";
$apellidos = "";
$edad = 0;
$provincia = "corunha";

if (isset($_POST["submit"])) {
    $id_user = $_POST["id_user"];
    $nombre = test_input($_POST["name"]);
    $apellidos = test_input($_POST["apellidos"]);
    $edad = test_input($_POST["edad"]);
    $provincia = test_input($_POST["provincia"]);

    editar_usuario($conexion, $id_user, $nombre, $apellidos, $edad, $provincia);
    header("Location: listar.php");
} else {
    if (isset($_GET["id"])) {
        $id_user = $_GET["id"];
        
        $user = get_usuario($conexion, $id_user);

        if ($user->num_rows > 0) {
            $row = $user->fetch_assoc();
            $id_user = $row['id'];
            $nombre = $row['nombre'];
            $apellidos = $row['apellidos'];
            $edad = $row['edad'];
            $provincia = $row['provincia'];
        }
    } else {
        $id_user = 0;
        $nombre = "";
        $apellidos = "";
        $edad = 0;
        $provincia = "corunha";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tienda IES San Clemente </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
    <h1>Editar usuario </h1>

    <p>Formulario de edición</p>
    
    <form method="post" action="<?=htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    
    Nombre: <input type="text" name="name" value=<?= $nombre ?>>
    <br><br>
    Apellidos: <input type="text" name="apellidos" value=<?= $apellidos ?>>
    <br><br>
    Edad: <input type="text" name="edad" value=<?= $edad ?>>
    <br><br>
    <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Provincia: </label>
    <select name="provincia" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">

<?php

if ($provincia == "corunha") {
    echo '<option value="corunha" selected>A Coruña</option>';
} else {
    echo '<option value="corunha">A Coruña</option>';
}

if ($provincia == "lugo") {
    echo '<option value="lugo" selected>Lugo</option>';
} else {
    echo '<option value="lugo">Lugo</option>';
}

if ($provincia == "pontevedra") {
    echo '<option value="pontevedra" selected>Pontevedra</option>';
} else {
    echo '<option value="pontevedra">Pontevedra</option>';
}

if ($provincia == "ourense") {
    echo '<option value="ourense" selected>Ourense</option>';
} else {
    echo '<option value="ourense">Ourense</option>';
}

?>
    </select> 
    <input type="hidden" name="id_user" value="<?= $id_user ?>"/>
    <input type="submit" name="submit" value="Modificar Usuario"/>
</form>
    
    <footer>
        <p>
            <a href='index.php'>Página de inicio</a>
        </p>
    </footer>
</body>

</html>
