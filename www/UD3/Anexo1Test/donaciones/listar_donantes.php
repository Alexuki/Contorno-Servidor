<!DOCTYPE html>
<html lang="es">

    <?php
        require_once "lib/bbdd.php";
        include_once "lib/utils.php";

        $dbName = "donacionTest";
        $con = crearConexion();
        seleccionarDB($con, $dbName);

        $mensajes = [];
        
        if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete"])) {
            $id = $_GET["delete"];
            $mensajes[] = eliminarDonante($con, $id);
        }

        $stmtDonantes = listarDonantes($con);
        $stmtDonantes->setFetchMode(PDO::FETCH_ASSOC); //Devuelve true si tiene éxito o false en caso de error
    ?>

    <head>
        <meta charset="utf-8">
        <title>Lista donantes</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    </head>

    <body>
        <h1>Lista de donantes</h1>
        <?= mostrarMensajeResultadoSql($mensajes) ?>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Edad</th>
                <th>Grupo Sanguíneo</th>
                <th>Código Postal</th>
                <th>Teléfono Móvil</th>
            </tr>
        
        <?php while ($row = $stmtDonantes->fetch()) : ?> <!-- Devuelve false si hay error o cuando no hay más líneas -->
            <tr>
                <td><?= $row["nombre"] ?></td>
                <td><?= $row["apellidos"] ?></td>
                <td><?= $row["edad"] ?></td>
                <td><?= $row["grupo_sanguineo"] ?></td>
                <td><?= $row["cod_postal"] ?></td>
                <td><?= $row["movil"] ?></td>
                <td><a class="btn btn-primary" href="dar_alta_donacion.php?id=<?= $row["id"] ?>">Alta donación</a></td>
                <td><a class="btn btn-danger" href=<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>?delete=<?= $row["id"] ?>>Eliminar</a></td>
                <td>
                    <form method="POST" action="listar_donaciones.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?= $row["id"] ?>" >
                        <input type="hidden" name="nombre" value="<?= $row["nombre"] . ' ' . $row['apellidos'] ?>" >
                        <button type="submit" class="btn btn-primary">Lista donaciones</button>
                    </form>
                </td>
            </tr>
        <?php endwhile ?>
        </table>

        <?php include_once "footer.php" ?>

    </body>

</html>