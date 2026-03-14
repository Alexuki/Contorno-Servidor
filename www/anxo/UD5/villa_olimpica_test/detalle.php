<?php
// detalle.php
// Muestra la información detallada de un deportista específico.
// También permite eliminar el deportista mediante un formulario POST.
require_once 'config/Database.php';
require_once 'models/Deportista.php';
require_once 'models/Esquiador.php';
require_once 'models/Patinador.php';
require_once 'models/Saltador.php';
require_once 'models/DeportistaRepository.php';

// Obtenemos el ID del deportista desde la URL (parámetro GET)
// Si no existe, usamos 0 como valor por defecto
$id = isset($_GET['id']) ? $_GET['id'] : 0;
// Creamos el repositorio y buscamos el deportista con ese ID
$repo = new DeportistaRepository();
$deportista = $repo->findById($id);

// Si no se encuentra el deportista (porque no existe el ID o es inválido),
// redirigimos a la página principal para no mostrar error
if (!$deportista) {
    header('Location: index.php');
    exit;
}

// Eliminar si el usuario ha enviado el formulario de eliminar (botón pulsado)
if (isset($_POST['eliminar'])) {
    $repo->delete($id);
    header('Location: index.php');
    exit;
}

include 'views/header.php';
?>

<!-- Mostramos los datos del deportista -->
<div class="card">
    <h2><?php echo $deportista->getNombreCompleto(); ?></h2>
    <span style="background: #f59e0b; color: white; padding: 5px 15px; border-radius: 20px;">
        <?php echo ucfirst($deportista->getTipoDeporte()); ?>
    </span>
    
    <div style="margin-top: 20px;">
        <p><strong>País:</strong> <?php echo $deportista->getPais(); ?></p>
        <p><strong>Edad:</strong> <?php echo $deportista->getEdad(); ?> años</p>
        <p><strong>Género:</strong> <?php echo $deportista->getGenero(); ?></p>
        
        <h3>Medallas</h3>
        <p>
            🥇 Oro: <?php echo $deportista->getMedallasOro(); ?><br>
            🥈 Plata: <?php echo $deportista->getMedallasPlata(); ?><br>
            🥉 Bronce: <?php echo $deportista->getMedallasBronce(); ?>
        </p>
        <p><strong>Total:</strong> <?php echo $deportista->getTotalMedallas(); ?> medallas</p>
        
        <h3>Detalles Específicos</h3>
        <?php 
        $detalles = $deportista->getDetalles();
        foreach ($detalles as $key => $value) {
            if ($value) { // Formateamos la clave (quitamos guiones bajos, ponemos primera letra mayúscula)
                echo "<p><strong>" . ucfirst(str_replace('_', ' ', $key)) . ":</strong> " . ucfirst($value) . "</p>";
            }
        }
        ?>
    </div>
    
    <div style="margin-top: 30px;">
        <a href="index.php" class="btn">← Volver</a>
        <!-- Formulario para eliminar el deportista -->
        <!-- onsubmit pregunta confirmación antes de enviar (JavaScript) -->
        <form method="POST" style="display: inline; margin-left: 10px;" onsubmit="return confirm('¿Seguro que quieres eliminar?');">
            <button type="submit" name="eliminar" class="btn btn-danger">Eliminar</button>
        </form>
    </div>
</div>

<?php include 'views/footer.php'; ?>