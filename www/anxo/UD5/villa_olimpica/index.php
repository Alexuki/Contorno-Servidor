<?php
// index.php
// Página principal de la aplicación. Muestra el listado de todos los deportistas
// y permite filtrarlos por país y número de medallas.
require_once 'config/Database.php';
require_once 'models/Deportista.php';
require_once 'models/Esquiador.php';
require_once 'models/Patinador.php';
require_once 'models/Saltador.php';
require_once 'models/DeportistaRepository.php';

// Creamos una instancia del repositorio para consultar la base de datos
$repo = new DeportistaRepository();

// Preparamos un array vacío para los filtros que aplicaremos a la consulta
$filtros = array();

// Si el usuario ha enviado un filtro por país o medallas mediante GET (en la URL), lo guardamos
if (!empty($_GET['pais'])) {
    $filtros['pais'] = $_GET['pais'];
}
if (!empty($_GET['min_medallas'])) {
    $filtros['min_medallas'] = $_GET['min_medallas'];
}

// Obtenemos la lista de deportistas aplicando los filtros (si hay alguno)
$deportistas = $repo->findAll($filtros);
// Obtenemos la lista de países distintos para rellenar el desplegable de filtros
$paises = $repo->getPaises();

include 'views/header.php';
?>

<h2>Listado de Deportistas</h2>

<!-- Formulario Filtros -->
<div class="card">
    <form method="GET" style="display: flex; gap: 10px; align-items: flex-end;">
        <div class="form-group" style="flex: 1;">
            <label>País</label>
            <select name="pais">
                <option value="">Todos</option>
                <!-- Recorremos todos los países existentes para crear las opciones del desplegable -->
                <?php foreach ($paises as $p): ?>
                    <option value="<?php echo $p; ?>" <?php if(isset($_GET['pais']) && $_GET['pais']==$p) echo 'selected'; ?>>
                        <?php echo $p; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="flex: 1;">
            <label>Mín. Medallas</label>
            <input type="number" name="min_medallas" value="<?php echo isset($_GET['min_medallas']) ? $_GET['min_medallas'] : ''; ?>" min="0">
        </div>
        <div class="form-group">
            <button type="submit" class="btn">Filtrar</button>
        </div>
    </form>
</div>

<!-- Mostrar Deportistas-->
<div class="grid">
    <?php foreach ($deportistas as $d): ?>
    <div class="card">
        <h3><?php echo $d->getNombreCompleto(); ?></h3>
        <p><strong><?php echo ucfirst($d->getTipoDeporte()); ?></strong> | <?php echo $d->getPais(); ?></p>
        <p>Edad: <?php echo $d->getEdad(); ?> años</p>
        <p>
            <!-- Bucle for para mostrar círculos de color según el número de medallas -->
            Medallas: 
            <?php for($i=0; $i<$d->getMedallasOro(); $i++) echo '<span class="medalla oro"></span>'; ?>
            <?php for($i=0; $i<$d->getMedallasPlata(); $i++) echo '<span class="medalla plata"></span>'; ?>
            <?php for($i=0; $i<$d->getMedallasBronce(); $i++) echo '<span class="medalla bronce"></span>'; ?>
            (Total: <?php echo $d->getTotalMedallas(); ?>)
        </p>
        <a href="detalle.php?id=<?php echo $d->getId(); ?>" class="btn">Ver Detalle</a>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'views/footer.php'; ?>