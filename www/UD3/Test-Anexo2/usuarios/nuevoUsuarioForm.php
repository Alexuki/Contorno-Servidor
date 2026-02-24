<?php
    require_once "../vista/pageTop.php";
    require_once "../vista/pageBottom.php";
?>

<?php generaPageTop("Nuevo Usuario"); ?>

<form method="post" action="nuevoUsuario.php">
    <?php
        $mode = "new";
        require_once "formUsuario.php" 
    ?>
    <button type="submit" name="submit">Crear</button>
</form>

<?php generaPageBottom(); ?>
