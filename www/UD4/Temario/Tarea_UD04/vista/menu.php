<?php
require_once(__DIR__ . '/../auth.php');
requireLogin();

$url_completa = appBaseUrl();
$tema = getTema();
$isAdmin = esAdmin();
?>
<nav class="col-md-3 col-lg-2 d-md-block <?php echo $tema === 'light' ? 'bg-light' : ''; ?> sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_completa.'index.php'?>">
                    Home
                </a>
            </li>
            <?php if ($isAdmin) { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_completa.'init.php'?>">
                    Inicializar (mysqli)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_completa.'usuarios/usuarios.php'?>">
                    Lista de usuarios (PDO)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_completa.'usuarios/nuevoUsuarioForm.php'?>">
                    Nuevo usuario (PDO)                    
                </a>
            </li>
            <?php } ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_completa.'tareas/tareas.php'?>">
                    Lista de tareas (mysqli)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_completa.'tareas/nuevaForm.php'?>">
                    Nueva tarea (mysqli)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_completa.'tareas/buscaTareas.php'?>">
                   Buscador de tareas (PDO)
                </a>
            </li>
            <li class="nav-item mt-2">
                <a class="nav-link text-danger" href="<?php echo $url_completa.'logout.php'?>">
                    Salir
                </a>
            </li>
        </ul>

        <form class="m-3 w-75" action="<?php echo $url_completa . 'tema.php'; ?>" method="POST">
            <select id="tema" name="tema" class="form-select mb-2" aria-label="Selector de tema">
                <option value="light" <?php echo $tema === 'light' ? 'selected' : ''; ?>>Claro</option>
                <option value="dark" <?php echo $tema === 'dark' ? 'selected' : ''; ?>>Oscuro</option>
                <option value="auto" <?php echo $tema === 'auto' ? 'selected' : ''; ?>>Automatico</option>
            </select>
            <button type="submit" class="btn btn-primary w-100">Aplicar</button>
        </form>
    </div>
</nav>