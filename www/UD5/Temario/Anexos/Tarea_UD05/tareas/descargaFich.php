<?php
require_once('../auth.php');
requireLogin();
require_once('../modelo/mysqli.php');
require_once('../modelo/FicherosDBImp.php');
require_once('../modelo/DatabaseException.php');

$idFichero = (int) ($_GET['id'] ?? 0);

try {
    $ficheroDB = new FicherosDBImp();
    $fichero = $idFichero > 0 ? $ficheroDB->buscaFichero($idFichero) : null;
} catch (DatabaseException $e) {
    redirectTo('tareas/tareas.php');
}

$tarea = $fichero ? buscaTarea($fichero->getTarea()) : null;

if ($fichero === null || $tarea === null || !puedeGestionarTarea($tarea->getUsuario())) {
    redirectTo('tareas/tareas.php');
}

$rutaAbs = '/var/www/html/' . $fichero->getFile();
if (!is_file($rutaAbs)) {
    redirectTo('tareas/tarea.php?id=' . $fichero->getTarea());
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($fichero->getNombre()) . '"');
header('Content-Length: ' . filesize($rutaAbs));
readfile($rutaAbs);
exit();
