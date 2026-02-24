<?php
    include_once("lib/base_datos.php");
    include_once("lib/utilidades.php");
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tienda IES San Clemente</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    </head>

    <body>
        <h1>Lista de usuarios</h1>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
                crossorigin="anonymous">
        </script>

    <table class="table">
        <thead class="thead-light">
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Apellidos</th>
                <th scope="col">Edad</th>
                <th scope="col">Provincia</th>
                <th scope="col">Editar</th>
                <th scope="col">Borrar</th>
            </tr>
        </thead>

        <tbody>
            <?php
                /* Se mostrará una lista de los usuarios y cada uno tendrá botones de editar y eliminar.
                Si se recuperan resultados, fetch_assoc es un puntero en un
                array asociativo que, cada vez que se llama, recupera el
                primer valor.
                Cada fila es un array asociativo en que las claves son campos de la tabla. Cuando finaliza de recorrer los registros, devuelve null.
                Para los botones, se envía el id en la consulta. No creamos un formulario para enviar el id a la página encargada de la
                edición o del borrado. Se envía en la URL el parámetro por GET.
                */

                $nombre_bd = "tienda";

                $conexion = get_conexion();
                seleccionar_bd($conexion, $nombre_bd);

                $resultados = listar_usuarios($conexion);

                if (!is_bool($resultados) && $resultados->num_rows > 0) {
                    while ($row = $resultados->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['nombre'] . "</td> ";
                        echo "<td>" . $row['apellidos'] . "</td> ";
                        echo "<td>" . $row['edad'] . "</td> ";
                        echo "<td>" . $row['provincia'] . "</td> ";
                        echo "<td> <a class='btn btn-primary' href=editar.php?id=" . $row['id'] . ">Editar</a> </td>";
                        echo "<td> <a class='btn btn-primary' href=borrar.php?id=" . $row['id'] . ">Borrar</a> </td>";
                        echo "</tr> ";
                    }
                }

                cerrar_conexion($conexion);
            ?>
        </tbody>

        <footer>
            <p>
                <a href='index.php'>Página de inicio</a>
            </p>
        </footer>
        
    </body>

</html>
