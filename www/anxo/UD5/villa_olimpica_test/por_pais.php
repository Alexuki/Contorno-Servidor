<?php
// por_pais.php
// Muestra los deportistas agrupados por país.
// Primero muestra botones con todos los países disponibles,
// y al hacer clic muestra los deportistas de ese país.
require_once 'config/Database.php';
require_once 'models/Deportista.php';
require_once 'models/Esquiador.php';
require_once 'models/Patinador.php';
require_once 'models/Saltador.php';
require_once 'models/DeportistaRepository.php';

// Creamos el repositorio y ovtenemos todos los paises distintos.
$repo = new DeportistaRepository();
$paises = $repo->getPaises();

// Obtenemos el país seleccionado de la URL (si hay alguno)
$paisSeleccionado = isset($_GET['pais']) ? $_GET['pais'] : '';
$deportistas = array();

// Si se ha seleccionado un país, buscamos sus deportistas
if ($paisSeleccionado) {
    $deportistas = $repo->findAll(array('pais' => $paisSeleccionado));
}

include 'views/header.php';
?>

<h2>Vista por Países</h2>

<!-- Muestra un botón por cada país existente en la base de datos -->
<!-- Cada botón enlaza a esta misma página pasando el país como parámetro GET -->
<div style="margin-bottom: 20px;">
    <?php foreach ($paises as $p): ?>
    <a href="?pais=<?php echo urlencode($p); ?>" class="btn" style="margin: 5px; <?php echo $paisSeleccionado==$p ? 'background:#f59e0b;' : ''; ?>">
        <?php echo $p; ?>
    </a>
    <?php endforeach; ?>
</div>

<!-- Si hay un país seleccionado, mostramos su equipo -->
<?php if ($paisSeleccionado): ?>
    <h3>Equipo de <?php echo $paisSeleccionado; ?></h3>
    <div class="grid">
        <?php foreach ($deportistas as $d): ?>
        <div class="card">
            <h4><?php echo $d->getNombreCompleto(); ?></h4>
            <p>Deporte: <?php echo $d->getTipoDeporte(); ?></p>
            <p>Medallas: <?php echo $d->getTotalMedallas(); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Selecciona un país para ver sus deportistas.</p>
<?php endif; ?>

<?php include 'views/footer.php'; ?>