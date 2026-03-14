<?php
require_once('../auth.php');
requireLogin();
$tema = getTema();
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="<?php echo $tema; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('../vista/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <?php include_once('../vista/menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Nueva tarea</h2>
                </div>

                <div class="container justify-content-between">
                    <form action="nueva.php" method="POST" class="mb-5 w-50">
                        <?php include_once('formTarea.php'); ?>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('../vista/footer.php'); ?>

</body>
</html>
