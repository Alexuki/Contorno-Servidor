<?php
require_once(__DIR__ . '/../auth.php');
$usuarioSesion = getUsuarioSesion();
?>
<header class="bg-primary text-white py-3 px-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1">Gestion de tareas</h1>
            <p class="mb-0">Tarea UD4</p>
        </div>
        <?php if ($usuarioSesion !== null) { ?>
            <div class="text-end">
                <div>Usuario: <strong><?php echo htmlspecialchars($usuarioSesion->getUsername()); ?></strong></div>
                <div>Rol: <?php echo ($usuarioSesion->getRol() === 1) ? 'Administrador' : 'Usuario'; ?></div>
            </div>
        <?php } ?>
    </div>
</header>