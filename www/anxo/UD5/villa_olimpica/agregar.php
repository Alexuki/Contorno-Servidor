<?php
// agregar.php
// Este archivo muestra el formulario para agregar nuevos deportistas y procesa los datos cuando se envía el formulario
require_once 'config/Database.php';
require_once 'models/Deportista.php';
require_once 'models/Esquiador.php';
require_once 'models/Patinador.php';
require_once 'models/Saltador.php';
require_once 'models/DeportistaRepository.php';

$mensaje = '';

// Comprobamos si se ha enviado el formulario (si el método de la petición es POST)
// Creamos una instancia del repositorio para poder guardar datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $repo = new DeportistaRepository();
    
    $tipo = $_POST['tipo_deporte'];
    $deportista = null;
    
    // Datos comunes
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $pais = $_POST['pais'];
    $edad = $_POST['edad'];
    $genero = $_POST['genero'];
    $oro = $_POST['oro'];
    $plata = $_POST['plata'];
    $bronce = $_POST['bronce'];
    
    // Según el tipo de deporte seleccionado, creamos un objeto de la clase correspondiente (Polimorfismo)
    if ($tipo == 'esqui') {
        $deportista = new Esquiador($nombre, $apellidos, $pais, $edad, $genero, $oro, $plata, $bronce,
                                   $_POST['disciplina'], $_POST['tipo_esqui']);
    } elseif ($tipo == 'patinaje') {
        $deportista = new Patinador($nombre, $apellidos, $pais, $edad, $genero, $oro, $plata, $bronce,
                                   $_POST['especialidad'], $_POST['distancia']);
    } elseif ($tipo == 'salto') {
        $deportista = new Saltador($nombre, $apellidos, $pais, $edad, $genero, $oro, $plata, $bronce,
                                  $_POST['tipo_salto'], $_POST['altura_maxima']);
    }
    
    // Si se ha creado correctamente el objeto (no es null), 
    // guardamos el deportista en la base de datos usando el repositorio,
    // y reparamos mensaje de éxito para mostrar al usuario
    if ($deportista) {
        $repo->save($deportista);
        $mensaje = "¡Deportista agregado correctamente!";
    }
}

include 'views/header.php';
?>

<h2>Agregar Nuevo Deportista</h2>

<!-- Si hay mensaje de éxito, lo mostramos en un recuadro verde -->
<?php if ($mensaje): ?>
<div class="card" style="background: #d1fae5; border-left: 4px solid #059669;">
    <?php echo $mensaje; ?>
</div>
<?php endif; ?>

<!-- Formulario para agregar deportista. El método POST envía los datos al mismo archivo -->
<form method="POST" class="card">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" required>
        </div>
        <div class="form-group">
            <label>Apellidos</label>
            <input type="text" name="apellidos" required>
        </div>
        <div class="form-group">
            <label>País</label>
            <input type="text" name="pais" required>
        </div>
        <div class="form-group">
            <label>Edad</label>
            <input type="number" name="edad" required>
        </div>
        <div class="form-group">
            <label>Género</label>
            <select name="genero">
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>
        </div>
        <div class="form-group">
            <label>Tipo de Deporte</label>
            <!-- Al cambiar esta selección, se ejecuta la función JavaScript mostrarCampos() -->
            <select name="tipo_deporte" id="tipo_deporte" onchange="mostrarCampos()">
                <option value="esqui">Esquí</option>
                <option value="patinaje">Patinaje</option>
                <option value="salto">Salto</option>
            </select>
        </div>
    </div>
    
    <h3 style="margin-top: 20px;">Medallas</h3>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
        <div class="form-group">
            <label>Oro</label>
            <input type="number" name="oro" value="0" min="0">
        </div>
        <div class="form-group">
            <label>Plata</label>
            <input type="number" name="plata" value="0" min="0">
        </div>
        <div class="form-group">
            <label>Bronce</label>
            <input type="number" name="bronce" value="0" min="0">
        </div>
    </div>
    
    <!-- Campos específicos. Por defecto visible Esquiador y los demás ocultos. -->
    <div id="campos_esqui" style="margin-top: 20px;">
        <h3>Datos de Esquiador</h3>
        <div class="form-group">
            <label>Disciplina</label>
            <input type="text" name="disciplina" placeholder="ej: Eslalon">
        </div>
        <div class="form-group">
            <label>Tipo de Esquí</label>
            <select name="tipo_esqui">
                <option value="libre">Libre</option>
                <option value="clasico">Clásico</option>
            </select>
        </div>
    </div>
    
    <div id="campos_patinaje" style="display: none; margin-top: 20px;">
        <h3>Datos de Patinador</h3>
        <div class="form-group">
            <label>Especialidad</label>
            <input type="text" name="especialidad" placeholder="ej: Velocidad">
        </div>
        <div class="form-group">
            <label>Distancia Preferida (m)</label>
            <input type="number" name="distancia" placeholder="1500">
        </div>
    </div>
    
    <div id="campos_salto" style="display: none; margin-top: 20px;">
        <h3>Datos de Saltador</h3>
        <div class="form-group">
            <label>Tipo de Salto</label>
            <input type="text" name="tipo_salto" placeholder="ej: Trampolín">
        </div>
        <div class="form-group">
            <label>Altura Máxima (m)</label>
            <input type="number" step="0.1" name="altura_maxima" placeholder="140.5">
        </div>
    </div>
    
    <button type="submit" class="btn" style="margin-top: 20px;">Guardar</button>
</form>

<!-- 
Este código es JavaScript (se ejecuta en el navegador del usuario, no en el servidor).
Su función es mostrar u ocultar campos del formulario según el deporte seleccionado.
Aunque no hayáis dado JavaScript en esta asignatura y no se os vaya a exigir por el momento,
es útil para mejorar la usabilidad de la web.
-->
<script>
function mostrarCampos() {
    var tipo = document.getElementById('tipo_deporte').value;
    document.getElementById('campos_esqui').style.display = (tipo == 'esqui') ? 'block' : 'none';
    document.getElementById('campos_patinaje').style.display = (tipo == 'patinaje') ? 'block' : 'none';
    document.getElementById('campos_salto').style.display = (tipo == 'salto') ? 'block' : 'none';
}
</script>

<?php include 'views/footer.php'; ?>