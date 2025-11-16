<?php
    include_once("lib/base_datos.php");
    include_once("lib/utilidades.php");

    /*
    Se recuperarán los datos de la BBDD para el id seleccionado y
    se mostrarán en un formaulario como el de dar de alta un nuevo usuario
    para que el usuario pueda editarlos.
    */

    $nombre_bd = "tienda";

    $conexion = get_conexion();
    seleccionar_bd($conexion, $nombre_bd);

    // Parámetros por defecto
    $id_user = 0;
    $nombre = "";
    $apellidos = "";
    $edad = 0;
    $provincia = "corunha";

    
    if (isset($_POST["submit"])) {
        // Si el usuario ha modificado los datos y pulsado el botón de "Submit"

        $id_user = $_POST["id_user"];
        $nombre = test_input($_POST["name"]);
        $apellidos = test_input($_POST["apellidos"]);
        $edad = test_input($_POST["edad"]);
        $provincia = test_input($_POST["provincia"]);

        editar_usuario($conexion, $id_user, $nombre, $apellidos, $edad, $provincia);
        header("Location: listar.php");
    } else {
        // Si se acaba ded cargar la página, cargar los datos de usuario mediante el id recibido
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
        }
    }
?>

<!DOCTYPE html>
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
        
        <!-- En el formulario de editar se rellenan los inputs con los valores recuperados de la BBDD-->
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
                    $provincias = [
                        'corunha' => 'A Coruña',
                        'lugo' => 'Lugo',
                        'pontevedra' => 'Pontevedra',
                        'ourense' => 'Ourense'
                    ];
                    
                    foreach ($provincias as $valor => $etiqueta) {
                        $selected = ($provincia == $valor) ? 'selected' : '';
                        echo "<option value='$valor' $selected>$etiqueta</option>";
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
