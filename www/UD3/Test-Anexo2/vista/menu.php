<?php 
define("URL_COMPLETA", "http://{$_SERVER["HTTP_HOST"]}/UD3/Test-Anexo2/"); 
define("URL_BASE", "/UD3/Test-Anexo2/" )
?>

<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <!-- Home -->
            <!-- Inicializar (mysqli) -->
            <!-- Lista de usuarios (PDO) -->
            <!-- Nuevo usuario (PDO) -->
            <!-- Lista de tareas (mysqli) -->
            <!-- Nueva tarea (mysqli) -->
            <!-- Buscador de tareas (PDO) -->
            <li class="nav-item">
                <a class="nav-link" href="<?= URL_BASE ?>index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= URL_BASE ?>init.php">Inicializar BD</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= URL_BASE ?>usuarios/usuarios.php">Listar usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= URL_BASE ?>usuarios/nuevoUsuarioForm.php">Nuevo usuario</a>
            </li>
        </ul>
    </div>
</nav>