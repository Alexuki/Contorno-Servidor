<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Formulario</title>
    </head>

    <body>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST["name"];
                $apellido = $_POST["surname"];
                
                // Mostrar los datos procesados
                echo "<p>Nombre: " . htmlspecialchars($name) . "</p>";
                echo "<p>Apellido: " . htmlspecialchars($apellido) . "</p>";
                echo '<p><a href="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">Volver al formulario</a></p>';
            } else {
                // Mostrar el formulario solo si no es POST
        ?>
                <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" required>
                    <label for="surname">Apellido</label>
                    <input type="text" name="surname" required>
                    <input type="submit" value="Enviar">
                </form>
        <?php
            }
        ?>
    </body>
</html>