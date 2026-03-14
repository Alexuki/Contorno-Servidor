<?php
require_once('../auth.php');
requireLogin(true);
$tema = getTema();
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="<?php echo $tema; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('../vista/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <?php include_once('../vista/menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Borrar tarea</h2>
                </div>

                <div class="container justify-content-between">
                    <?php
                        require_once('../modelo/mysqli.php');
                        $id = (int) ($_GET['id'] ?? 0);
                        if ($id > 0) {
                            $resultado = borraTarea($id);
                            if ($resultado[0]) {
                                echo '<div class="alert alert-success" role="alert">Tarea borrada correctamente.</div>';
                            } else {
                                echo '<div class="alert alert-danger" role="alert">No se pudo borrar la tarea: ' . $resultado[1] . '</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger" role="alert">ID de tarea no valido.</div>';
                        }
                    ?>

                </div>
            </main>
        </div>
    </div>

    <?php include_once('../vista/footer.php'); ?>

</body>
</html>
