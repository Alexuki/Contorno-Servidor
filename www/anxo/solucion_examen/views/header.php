<?php
// views/header.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mini Villa Olímpica</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f0f9ff; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        header { background: #1e3a8a; color: white; padding: 20px; text-align: center; }
        .menu { background: white; padding: 15px; text-align: center; border-bottom: 1px solid #ddd; }
        .menu a { margin: 0 15px; text-decoration: none; color: #1e3a8a; font-weight: bold; }
        .card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 10px 20px; background: #1e3a8a; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn-danger { background: #dc2626; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #e0f2fe; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    </style>
</head>
<body>
    <header>
        <h1>🏔️ Mini Villa Olímpica</h1>
    </header>
    <div class="menu">
        <a href="index.php">🏠 Inicio</a>
        <!-- Solo mostrar "Agregar" si es Admin -->
        <?php if (Auth::getRolActual() == 'admin'): ?>
        <a href="agregar.php">➕ Agregar Deportista</a>
        <?php endif; ?>
        <!-- IMPLEMENTAR POR EL ALUMNO -->
        <!-- Se debe mostrar el nombre del usuario y su rol-->
        <!-- Se debe incluir un botón "Cerrar sesión" que redirija a logout.php -->
        <?php echo Auth::getUsuarioActual() . "-" . Auth::getRolActual(); ?>
        <a href="logout.php" class="logout-btn">🚪 Cerrar sesión</a>
    </div>

    <div class="container">