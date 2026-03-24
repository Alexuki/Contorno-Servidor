<?php
// detalle.php
require_once 'config/Database.php';
require_once 'models/Deportista.php';
require_once 'models/Esquiador.php';
require_once 'models/Patinador.php';
require_once 'models/Saltador.php';
require_once 'models/DeportistaRepository.php';
require_once 'models/Usuario.php';
require_once 'models/Auth.php';
Auth::checkAuth();

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$repo = new DeportistaRepository();
$deportista = $repo->findById($id);

if (!$deportista) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['eliminar'])) {
    $repo->delete($id);
    header('Location: index.php');
    exit;
}

include 'views/header.php';
?>

<div class="card">
    <h2><?php echo $deportista->getNombreCompleto(); ?></h2>
    <p><strong>Deporte:</strong> <?php echo ucfirst($deportista->getTipoDeporte()); ?></p>
    <p><strong>País:</strong> <?php echo $deportista->getPais(); ?></p>
    <p><strong>Edad:</strong> <?php echo $deportista->getEdad(); ?> años</p>
    <p><strong>Género:</strong> <?php echo $deportista->getGenero(); ?></p>
    
    <h3>Medallas</h3>
    <p>Oro: <?php echo $deportista->getMedallasOro(); ?> | 
       Plata: <?php echo $deportista->getMedallasPlata(); ?> | 
       Bronce: <?php echo $deportista->getMedallasBronce(); ?></p>
    <p><strong>Total:</strong> <?php echo $deportista->getTotalMedallas(); ?></p>
    
    <h3>Detalles Específicos</h3>
    <?php 
    $detalles = $deportista->getDetalles();
    foreach ($detalles as $key => $value) {
        if ($value) {
            echo "<p><strong>" . ucfirst($key) . ":</strong> " . $value . "</p>";
        }
    }
    ?>
    <!-- Botón Eliminar: Solo visible para Admin -->
    <?php if (Auth::getRolActual()== "admin"): ?>
    <div style="margin-top: 30px;">
        <a href="index.php" class="btn">← Volver</a>
        <form method="POST" style="display: inline; margin-left: 10px;" onsubmit="return confirm('¿Eliminar?');">
            <button type="submit" name="eliminar" class="btn btn-danger">Eliminar</button>
        </form>
    <?php endif; ?>
    </div>
</div>

<?php include 'views/footer.php'; ?>