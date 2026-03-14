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
if (is_file($rutaAbs)) {
    @unlink($rutaAbs);
}

try {
    $ficheroDB->borraFichero($idFichero);
} catch (DatabaseException $e) {
    redirectTo(
        'tareas/tarea.php?id=' . $fichero->getTarea()
        . '&db_error=' . rawurlencode($e->getMessage())
        . '&db_method=' . rawurlencode($e->getMethod())
        . '&db_sql=' . rawurlencode($e->getSql())
    );
}

redirectTo('tareas/tarea.php?id=' . $fichero->getTarea());
