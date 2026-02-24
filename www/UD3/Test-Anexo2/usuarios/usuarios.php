<?php 
    include_once "../vista/pageTop.php";
    include_once "../vista/pageBottom.php";
    generaPageTop("USUARIOS");
?>
    <p>LISTA USUARIOS</p>
    <?php
        require_once "../modelo/pdo.php";
        $usuarios = listaUsuarios()[1];
    ?>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
        </tr>
    <?php foreach ($usuarios as $u) : ?>
        <tr>
            <td><?= $u["nombre"] ?></td>
            <td><?= $u["apellidos"] ?></td>
            <td><a class="btn" href="editar.php?<?= $u["id"] ?>">Editar</a></td>
            <td><a class="btn" href="borrar.php?<?= $u["id"] ?>">Borrar</a></td>
        </tr>
    <?php endforeach ?>
    </table>

<?php generaPageBottom(); ?>  