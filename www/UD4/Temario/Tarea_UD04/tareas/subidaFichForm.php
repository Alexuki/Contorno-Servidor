<?php
require_once('../auth.php');
requireLogin();
require_once('../modelo/mysqli.php');

$tema = getTema();
$idTarea = (int) ($_GET['id_tarea'] ?? 0);
$tarea = $idTarea > 0 ? buscaTarea($idTarea) : null;

if ($tarea !== null && !puedeGestionarTarea($tarea['id_usuario'])) {
    $tarea = null;
}
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="<?php echo $tema; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir fichero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('../vista/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <?php include_once('../vista/menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Subir fichero</h2>
                </div>

                <div class="container justify-content-between">
                    <?php if ($tarea === null) { ?>
                        <div class="alert alert-danger" role="alert">No existe la tarea o no tienes permisos.</div>
                    <?php } else { ?>
                        <form action="subidaFichProc.php" method="POST" enctype="multipart/form-data" class="mb-5 w-50">
                            <input type="hidden" name="id_tarea" value="<?php echo $idTarea; ?>">
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripcion</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                            </div>
                            <div class="mb-3">
                                <label for="fichero" class="form-label">Fichero (jpg, png, pdf - max 20MB)</label>
                                <input type="file" class="form-control" id="fichero" name="fichero" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Subir</button>
                        </form>
                    <?php } ?>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('../vista/footer.php'); ?>

</body>
</html>
