<?php
    require_once "../vista/pageTop.php";
    require_once "../vista/pageBottom.php";
?>

<?php generaPageTop("Nuevo Usuario - Resultado de Creación"); ?>

<?php
    require_once "../modelo/pdo.php";
    require_once "../utils.php";

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        $nombre = $_POST["nombre"];
        $apellidos = $_POST["apellidos"];
        $username = $_POST["username"];
        $contrasena = $_POST["contrasena"];

        $valores = $_POST;
        unset($valores["submit"]);

        $errores = [];

        foreach ($valores as $campo => $input) {
            if($campo != 'contrasena') {
                $check = validarCampoTexto($input);
            } else {
                $check = validaContrasena($input);
            }
            if (!$check) {
                array_push($errores, "Error en el campo $campo");
            }
        }

        if(count($errores) == 0) {
            $result = nuevoUsuario($nombre, $apellidos, $username, $contrasena);
            $success = $result[0];
        }

        
    }  
?>
<?php if(isset($success) && $success) : ?>
    <div class="alert alert-success">Usuario creado correctamente</div>
<?php elseif(count($errores) > 0) : ?>
    <?php foreach ($errores as $e) {
        echo "<div class='alert alert-danger'>$e</div>";
    } 
    ?>
<?php else : ?>
    <div class="alert alert-danger">ERROR al crear el usuario</div>
<?php endif ?>


<?php generaPageBottom(); ?>
