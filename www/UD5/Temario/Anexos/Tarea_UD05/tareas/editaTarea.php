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
    <title>Actualizar tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('../vista/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <?php include_once('../vista/menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Actualizar tarea</h2>
                </div>

                <div class="container justify-content-between">
                    <?php
                        require_once('../utils.php');
                        require_once('../modelo/mysqli.php');
                        require_once('../modelo/Tarea.php');

                        $id = (int) ($_POST['id'] ?? 0);
                        $titulo = $_POST['titulo'] ?? '';
                        $descripcion = $_POST['descripcion'] ?? '';
                        $estado = $_POST['estado'] ?? '';
                        $id_usuario = (int) ($_POST['id_usuario'] ?? 0);
                        $error = false;

                        $tareaOriginal = $id > 0 ? buscaTarea($id) : null;
                        if ($tareaOriginal === null || !puedeGestionarTarea($tareaOriginal->getUsuario())) {
                            $error = true;
                            echo '<div class="alert alert-danger" role="alert">No tienes permisos para actualizar esta tarea.</div>';
                        }

                        if (!validarCampoTexto($titulo)) {
                            $error = true;
                            echo '<div class="alert alert-danger" role="alert">El campo titulo es obligatorio y debe contener al menos 3 caracteres.</div>';
                        }
                        if (!validarCampoTexto($descripcion)) {
                            $error = true;
                            echo '<div class="alert alert-danger" role="alert">El campo descripcion es obligatorio y debe contener al menos 3 caracteres.</div>';
                        }
                        if (!validarCampoTexto($estado)) {
                            $error = true;
                            echo '<div class="alert alert-danger" role="alert">El campo estado es obligatorio.</div>';
                        }

                        if (!esAdmin()) {
                            $id_usuario = $tareaOriginal->getUsuario();
                        }

                        if (!esNumeroValido((string) $id_usuario)) {
                            $error = true;
                            echo '<div class="alert alert-danger" role="alert">El usuario no es valido.</div>';
                        }

                        if (!$error) {
                            $tarea = new Tarea($id, filtraCampo($titulo), filtraCampo($descripcion), filtraCampo($estado), $id_usuario);
                            $resultado = actualizaTarea($tarea);
                            if ($resultado[0]) {
                                echo '<div class="alert alert-success" role="alert">Tarea actualizada correctamente.</div>';
                            } else {
                                echo '<div class="alert alert-danger" role="alert">Error actualizando la tarea: ' . $resultado[1] . '</div>';
                            }
                        }
                    ?>

                </div>
            </main>
        </div>
    </div>

    <?php include_once('../vista/footer.php'); ?>

</body>
</html>
