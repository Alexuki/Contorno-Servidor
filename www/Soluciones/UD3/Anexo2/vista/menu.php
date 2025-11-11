<?php $url_completa= 'http://'.$_SERVER['HTTP_HOST']."/Soluciones/UD3/Anexo2/"; ?>
<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $url_completa.'index.php'?>">
                    Home
                </a>
            </li>
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
            
        </ul>
    </div>
</nav>