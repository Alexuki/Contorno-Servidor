<?php
// index.php
require_once 'config/Database.php';
require_once 'models/Deportista.php';
require_once 'models/Esquiador.php';
require_once 'models/Patinador.php';
require_once 'models/Saltador.php';
require_once 'models/DeportistaRepository.php';
require_once 'models/Usuario.php';
require_once 'models/Auth.php';
Auth::checkAuth();

$repo = new DeportistaRepository();
$filtros = array();

if (!empty($_GET['pais'])) {
    $filtros['pais'] = $_GET['pais'];
}

$deportistas = $repo->findAll($filtros);
$paises = $repo->getPaises();

// -- EXAMEN -- 
// CALCULAR MEJOR DEPORTISTA
$mejorDeportista = null;
$maximaPuntuacion = -1;

foreach ($deportistas as $deportista) {
    $puntuacion = $deportista->calcularPuntuacion();
    if ($puntuacion > $maximaPuntuacion) {
        $maximaPuntuacion = $puntuacion;
        $mejorDeportista = $deportista;
    }
}

// -- EXAMEN --
// Obtener datos del medallero 
$medallero = $repo->getMedallero();

include 'views/header.php';
?>

<h2>Listado de Deportistas</h2>

<div class="card">
    <form method="GET" style="display: flex; gap: 10px; align-items: flex-end;">
        <div class="form-group" style="flex: 1;">
            <label>Filtrar por País</label>
            <select name="pais">
                <option value="">Todos</option>
                <?php foreach ($paises as $p): ?>
                    <option value="<?php echo $p; ?>" <?php if(isset($_GET['pais']) && $_GET['pais']==$p) echo 'selected'; ?>>
                        <?php echo $p; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn">Filtrar</button>
        <a href="index.php" class="btn" style="background: #666;">Limpiar</a>
    </form>
</div>

<!-- MEJOR DEPORTISTA 
 IMPLEMENTAR POR EL ALUMNO-->

<?php if ($mejorDeportista): ?>
<div class="card">
    <strong> Mejor Deportista:</strong> 
    <?php echo $mejorDeportista->getNombreCompleto(); ?> 
    (<?php echo $mejorDeportista->getTipoDeporte(); ?>) - 
    <?php echo $mejorDeportista->getPais(); ?> - 
    <strong><?php echo $mejorDeportista->calcularPuntuacion(); ?> pts</strong>
</div>
<?php endif; ?>

<table>
    <tr>
        <th>Nombre</th>
        <th>País</th>
        <th>Deporte</th>
        <th>Medallas Totales</th>
        <th>Detalles</th>
    </tr>
    <?php foreach ($deportistas as $d): ?>
    <tr>
        <td><?php echo $d->getNombreCompleto(); ?></td>
        <td><?php echo $d->getPais(); ?></td>
        <td><?php echo ucfirst($d->getTipoDeporte()); ?></td>
        <td><?php echo $d->getTotalMedallas(); ?></td>
        <td>
            <a href="detalle.php?id=<?php echo $d->getId(); ?>" class="btn">Ver</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>



<!-- MEDALLERO 
 IMPLEMENTAR POR EL ALUMNO-->
<div class="card" style="margin-top: 40px;">
    <h2>🏆 Medallero por Países</h2>
    <p style="color: #666; font-size: 0.9em;">Puntuación: Oro=5pts | Plata=2pts | Bronce=1pto</p>
    
    <table>
        <tr>
            <th>Posición</th>
            <th>País</th>
            <th style="text-align: center;">🥇 Oro</th>
            <th style="text-align: center;">🥈 Plata</th>
            <th style="text-align: center;">🥉 Bronce</th>
            <th style="text-align: center;">Puntuación</th>
        </tr>
        <!-- Zona de implementación de MEDALLERO -->
         <?php 
        $posicion = 1;
        foreach ($medallero as $pais): 
        ?>
        <tr>
            <td><?php echo $posicion++; ?>º</td>
            <td><?php echo $pais['pais']; ?></td>
            <td style="text-align: center;"><?php echo $pais['total_oro']; ?></td>
            <td style="text-align: center;"><?php echo $pais['total_plata']; ?></td>
            <td style="text-align: center;"><?php echo $pais['total_bronce']; ?></td>
            <td style="text-align: center;"><strong><?php echo $pais['puntuacion']; ?></strong></td>
         </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include 'views/footer.php'; ?>