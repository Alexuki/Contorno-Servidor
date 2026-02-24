<?php
    function generaPageTop($titulo) { ?>
        
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD3 (Anexo 2)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- header -->
    <?php include_once __DIR__ . "/header.php" ?>

    <div class="container-fluid">
        <div class="row">
            
            <!-- menu -->
            <?php include_once __DIR__ ."/menu.php" ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2><?= $titulo ?? "" ?></h2>
                </div>

                <div class="container justify-content-between">
                    <!-- Aquí va el contenido  -->
<?php } ?>                   

