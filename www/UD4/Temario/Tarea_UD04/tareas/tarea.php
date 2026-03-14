<?php
require_once('../auth.php');
requireLogin();
require_once('../modelo/mysqli.php');

$tema = getTema();
$idTarea = (int) ($_GET['id'] ?? 0);
$tarea = $idTarea > 0 ? buscaTarea($idTarea) : null;

if ($tarea !== null && !puedeGestionarTarea($tarea['id_usuario'])) {
    $tarea = null;
}

$ficherosResultado = $tarea ? listaFicherosDeTarea($idTarea) : [false, 'Tarea no valida'];
$ficheros = ($ficherosResultado[0]) ? $ficherosResultado[1] : [];
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="<?php echo $tema; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('../vista/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <?php include_once('../vista/menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Detalle de tarea</h2>
                </div>

                <div class="container justify-content-between">
                    <?php if ($tarea === null) { ?>
                        <div class="alert alert-danger" role="alert">No existe la tarea o no tienes permisos.</div>
                    <?php } else { ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($tarea['titulo']); ?></h5>
                                <p class="card-text mb-1"><strong>Descripcion:</strong> <?php echo htmlspecialchars($tarea['descripcion']); ?></p>
                                <p class="card-text mb-1"><strong>Estado:</strong> <?php echo htmlspecialchars($tarea['estado']); ?></p>
                                <p class="card-text mb-0"><strong>ID Usuario:</strong> <?php echo (int) $tarea['id_usuario']; ?></p>
                            </div>
                        </div>

                        <a class="btn btn-primary mb-3" href="subidaFichForm.php?id_tarea=<?php echo $idTarea; ?>">Anadir archivo</a>

                        <h5>Ficheros adjuntos</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($ficheros) === 0) { ?>
                                        <tr><td colspan="100">No hay ficheros adjuntos.</td></tr>
                                    <?php } else { ?>
                                        <?php foreach ($ficheros as $fichero) { ?>
                                            <tr>
                                                <td><?php echo (int) $fichero['id']; ?></td>
                                                <td><?php echo htmlspecialchars($fichero['nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($fichero['descripcion']); ?></td>
                                                <td>
                                                    <a class="btn btn-sm btn-outline-primary" href="descargaFich.php?id=<?php echo (int) $fichero['id']; ?>">Descargar</a>
                                                    <a class="btn btn-sm btn-outline-danger ms-2" href="borraFich.php?id=<?php echo (int) $fichero['id']; ?>">Borrar</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('../vista/footer.php'); ?>

</body>
</html>
