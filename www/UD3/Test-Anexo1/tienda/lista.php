<?php
    require_once("lib/bbdd.php");

    $conexion = getConexionTienda();
    seleccionarBD($conexion, "tiendaTest");
    $usuarios = getUsuarios($conexion)[1];
?>

<?php include_once("components/headPage.php") ?>

    <table>
        <th>
            <td>Nombre</td>
            <td>Apellidos</td>
            <td>Edad</td>
            <td>Provincia</td>
            <td>Editar</td>
            <td>Borrar</td>
        </th>
        <?php if($usuarios->num_rows > 0) :?>
            <?php while($row = $usuarios->fetch_assoc()) :?>
                <tr>
                    <td><?= $row["nombre"] ?></td>
                    <td><?= $row["apellidos"] ?></td>
                    <td><?= $row["edad"] ?></td>
                    <td><?= $row["provincia"] ?></td>
                    <td><a class="btn btn-primary" href="formulario_usuario.php?id=<?= $row["id"] ?>">Editar</a></td>
                    <td><a class="btn btn-primary" href="eliminar.php?id=<?= $row["id"] ?>">Eliminar</a></td>
                </tr>
            <?php endwhile ?>  
        <?php endif ?>
    </table>

    

<?php include_once("components/footerPage.php") ?>