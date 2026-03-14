<?php
require_once('../auth.php');
requireLogin();
require_once('../modelo/mysqli.php');
require_once('../utils.php');

$idTarea = (int) ($_POST['id_tarea'] ?? 0);
$descripcion = filtraCampo($_POST['descripcion'] ?? '');
$tarea = $idTarea > 0 ? buscaTarea($idTarea) : null;

if ($tarea === null || !puedeGestionarTarea($tarea['id_usuario'])) {
    redirectTo('tareas/tareas.php');
}

if (!isset($_FILES['fichero']) || $_FILES['fichero']['error'] !== UPLOAD_ERR_OK) {
    redirectTo('tareas/tarea.php?id=' . $idTarea);
}

$fichero = $_FILES['fichero'];
$nombreOriginal = $fichero['name'];
$size = (int) $fichero['size'];
$tmp = $fichero['tmp_name'];
$ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

$extensionesPermitidas = ['jpg', 'png', 'pdf'];
$maxBytes = 20 * 1024 * 1024;

if (!in_array($ext, $extensionesPermitidas, true) || $size > $maxBytes) {
    redirectTo('tareas/tarea.php?id=' . $idTarea);
}

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

$resultado = insertaFicheroTarea($nombreOriginal, $rutaRelativa, $descripcion, $idTarea);
if (!$resultado[0]) {
    @unlink($rutaAbs);
}

redirectTo('tareas/tarea.php?id=' . $idTarea);
