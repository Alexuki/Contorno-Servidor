<?php
require_once('../auth.php');
requireLogin();
require_once('../modelo/mysqli.php');

$idFichero = (int) ($_GET['id'] ?? 0);
$fichero = $idFichero > 0 ? buscaFichero($idFichero) : null;

if ($fichero === null || !puedeGestionarTarea($fichero['id_usuario'])) {
    redirectTo('tareas/tareas.php');
}

$rutaAbs = '/var/www/html/' . $fichero['file'];
if (!is_file($rutaAbs)) {
    redirectTo('tareas/tarea.php?id=' . (int) $fichero['id_tarea']);
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($fichero['nombre']) . '"');
header('Content-Length: ' . filesize($rutaAbs));
readfile($rutaAbs);
exit();
