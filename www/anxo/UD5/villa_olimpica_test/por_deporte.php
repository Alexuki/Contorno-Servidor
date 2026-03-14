<?php
// por_deporte.php
// Muestra los deportistas filtrados por tipo de deporte (Esquí, Patinaje o Salto).
require_once 'config/Database.php';
require_once 'models/Deportista.php';
require_once 'models/Esquiador.php';
require_once 'models/Patinador.php';
require_once 'models/Saltador.php';
require_once 'models/DeportistaRepository.php';

// Obtenemos el tipo de deporte seleccionado de la URL, por defecto 'esqui'
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'esqui';

// Creamos el repositorio y buscamos deportistas filtrando por tipo
$repo = new DeportistaRepository();
$deportistas = $repo->findAll(array('tipo' => $tipo));

include 'views/header.php';
?>

<h2>Deportistas por Disciplina</h2>

<!-- Botones para cambiar entre tipos de deporte. -->
<div style="margin-bottom: 20px;">
    <a href="?tipo=esqui" class="btn" style="<?php echo $tipo=='esqui' ? 'background:#f59e0b;' : ''; ?>">⛷️ Esquí</a>
    <a href="?tipo=patinaje" class="btn" style="<?php echo $tipo=='patinaje' ? 'background:#f59e0b;' : ''; ?>">⛸️ Patinaje</a>
    <a href="?tipo=salto" class="btn" style="<?php echo $tipo=='salto' ? 'background:#f59e0b;' : ''; ?>">🎿 Saltos</a>
</div>

<h3><?php echo ucfirst($tipo); ?> - <?php echo count($deportistas); ?> deportistas</h3>

<!-- Tabla para mostrar los datos de forma ordenada -->
<table>
    <tr>
        <th>Nombre</th>
        <th>País</th>
        <th>Detalles</th>
        <th>Medallas</th>
        <th>Acción</th>
    </tr>
    <?php foreach ($deportistas as $d): ?>
    <tr>
        <td><?php echo $d->getNombreCompleto(); ?></td>
        <td><?php echo $d->getPais(); ?></td>
        <td>
            <?php 
            $detalles = $d->getDetalles();
            foreach ($detalles as $key => $value) {
                if ($value) {
                    echo ucfirst(str_replace('_', ' ', $key)) . ": " . ucfirst($value) . "<br>";
                }
            }
            ?>
        </td>
        <td><?php echo $d->getTotalMedallas(); ?></td>
        <td><a href="detalle.php?id=<?php echo $d->getId(); ?>" class="btn">Ver</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include 'views/footer.php'; ?>