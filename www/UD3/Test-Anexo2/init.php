<?php 
    include_once "vista/pageTop.php";
    include_once "vista/pageBottom.php";
    generaPageTop("INIT");
?>
    <p>PÁGINA INIT</p>
    <?php
        require_once "modelo/mysqli.php";

        $resultados = [];
        $resultados[] = creaDB();
        $resultados[] = createTablaUsuarios();
        $resultados[] = createTablaTareas();
    ?>
    <?php foreach($resultados as $r) : ?>
        <div class="alert alert-<?= $r[0] ? 'success' : 'warning' ?>" role="alert"><?= $r[1] ?></div>
    <?php endforeach ?>

<?php generaPageBottom(); ?>   