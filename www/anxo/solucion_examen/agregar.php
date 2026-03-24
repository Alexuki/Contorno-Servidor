<?php
// agregar.php
require_once 'config/Database.php';
require_once 'models/Deportista.php';
require_once 'models/Esquiador.php';
require_once 'models/Patinador.php';
require_once 'models/Saltador.php';
require_once 'models/DeportistaRepository.php';
require_once 'models/Usuario.php';
require_once 'models/Auth.php';
Auth::checkAdmin();

$mensaje = '';

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
    
    // Crear objeto según tipo
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
    
    if ($deportista) {
        $repo->save($deportista);
        $mensaje = "Deportista agregado correctamente";
    }
}

include 'views/header.php';
?>

<h2>Agregar Nuevo Deportista</h2>

<?php if ($mensaje): ?>
<div class="card" style="background: #d1fae5;">
    <?php echo $mensaje; ?>
</div>
<?php endif; ?>

<!-- Formulario Agregar Deportista -->
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
    
    <!-- Campos específicos -->
    <div id="campos_esqui" style="margin-top: 20px;">
        <h3>Datos de Esquiador</h3>
        <input type="text" name="disciplina" placeholder="Disciplina (ej: Eslalon)">
        <select name="tipo_esqui" style="margin-top: 10px;">
            <option value="libre">Libre</option>
            <option value="clasico">Clásico</option>
        </select>
    </div>
    
    <div id="campos_patinaje" style="display: none; margin-top: 20px;">
        <h3>Datos de Patinador</h3>
        <input type="text" name="especialidad" placeholder="Especialidad">
        <input type="number" name="distancia" placeholder="Distancia preferida (m)" style="margin-top: 10px;">
    </div>
    
    <div id="campos_salto" style="display: none; margin-top: 20px;">
        <h3>Datos de Saltador</h3>
        <input type="text" name="tipo_salto" placeholder="Tipo de salto">
        <input type="number" step="0.1" name="altura_maxima" placeholder="Altura máxima (m)" style="margin-top: 10px;">
    </div>
    
    <button type="submit" class="btn" style="margin-top: 20px;">Guardar</button>
</form>

<!-- Función Java Script que despliega los distintos campos -->
<script>
function mostrarCampos() {
    var tipo = document.getElementById('tipo_deporte').value;
    document.getElementById('campos_esqui').style.display = (tipo == 'esqui') ? 'block' : 'none';
    document.getElementById('campos_patinaje').style.display = (tipo == 'patinaje') ? 'block' : 'none';
    document.getElementById('campos_salto').style.display = (tipo == 'salto') ? 'block' : 'none';
}
</script>

<?php include 'views/footer.php'; ?>