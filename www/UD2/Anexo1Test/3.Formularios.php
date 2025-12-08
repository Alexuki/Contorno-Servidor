<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <title>Formularios</title>
    </head>

    <body>

        <!-- Formulario 1 procesando en la misma página -->

        <?php if($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)): ?>
            <?php
                $nombre = htmlspecialchars($_GET['nombre'] ?? '');
                $apellidos = htmlspecialchars($_GET['apellidos'] ?? '');
            ?>
            <ul>
                <li>Nombre: <?= $nombre ?></li>
                <li>Apellidos: <?= $apellidos ?></li>
                <li>Nombre y Apellidos: <?= $nombre . " " . $apellidos ?></li>
                <li>Tu nombre tiene <?= strlen($nombre) ?> caracteres.</li>
                <li>Los 3 primeros caracteres de tu nombre son: <?= substr($nombre, 0 , 3) ?>.</li>
                <li>La letra "A" se encuentra en la posición <?= strpos($apellidos, "a") ?> en tus apellidos.</li>
                <li>Tu nombre en mayúsculas: <?= mb_strtoupper($nombre) ?></li>
            </ul>
            <a href="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" class="btn btn-primary">Volver</a>
        <?php else: ?>
            <form method="get" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                <label for="nombre">Nombre</label>
                <input name="nombre">
                <br><br>
                <label for="apellidos">Apellidos</label>
                <input name="apellidos">
                <br><br>
                <button type="submit">Enviar</button>
            </form>
        <?php endif; ?>
        <br><hr>


        <!-- Formulario 2 procesado en otra página -->

        <form method="post" action="3.Formularios2Bebidas.php">
            <label for="bebida">Bebida:</label>
            <select name="bebida" id="bebida">
                <option value="cocaCola">Coca Cola</option>
                <option value="pepsiCola">Pepsi Cola</option>
                <option value="error">No válido</option>
            </select>
            <br><br>
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" min="1" value="1" required>
            <br><br>
            <button type="submit">Solicitar</button>
        </form>

    </body>
</html>