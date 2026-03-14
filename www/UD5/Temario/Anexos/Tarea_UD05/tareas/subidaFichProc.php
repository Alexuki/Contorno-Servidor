<?php
require_once('../auth.php');
requireLogin();
require_once('../modelo/mysqli.php');
require_once('../modelo/Fichero.php');
require_once('../modelo/FicherosDBImp.php');
require_once('../modelo/DatabaseException.php');
require_once('../utils.php');

$idTarea = (int) ($_POST['id_tarea'] ?? 0);
$descripcion = filtraCampo($_POST['descripcion'] ?? '');
$tarea = $idTarea > 0 ? buscaTarea($idTarea) : null;

if ($tarea === null || !puedeGestionarTarea($tarea->getUsuario())) {
    redirectTo('tareas/tareas.php');
}

$upload = $_FILES['fichero'] ?? [];
$errores = Fichero::validarCampos($descripcion, $upload);
if (count($errores) > 0) {
    redirectTo('tareas/tarea.php?id=' . $idTarea);
}

$nombreOriginal = (string) $upload['name'];
$tmp = (string) $upload['tmp_name'];
$ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

$dirAbs = '/var/www/html/files';
if (!is_dir($dirAbs)) {
    mkdir($dirAbs, 0777, true);
}

if (!is_writable($dirAbs)) {
    redirectTo('tareas/tarea.php?id=' . $idTarea);
}

$codigoAleatorio = bin2hex(random_bytes(8));
$nombreAutogenerado = $codigoAleatorio . '.' . $ext;
$rutaAbs = $dirAbs . '/' . $nombreAutogenerado;
$rutaRelativa = 'files/' . $nombreAutogenerado;

if (!move_uploaded_file($tmp, $rutaAbs)) {
    redirectTo('tareas/tarea.php?id=' . $idTarea);
}

try {
    $ficheroDB = new FicherosDBImp();
    $fichero = new Fichero(0, $nombreOriginal, $rutaRelativa, $descripcion, $idTarea);
    $ok = $ficheroDB->nuevoFichero($fichero);
    if (!$ok) {
        @unlink($rutaAbs);
    }
} catch (DatabaseException $e) {
    @unlink($rutaAbs);
    redirectTo(
        'tareas/tarea.php?id=' . $idTarea
        . '&db_error=' . rawurlencode($e->getMessage())
        . '&db_method=' . rawurlencode($e->getMethod())
        . '&db_sql=' . rawurlencode($e->getSql())
    );
}

redirectTo('tareas/tarea.php?id=' . $idTarea);
