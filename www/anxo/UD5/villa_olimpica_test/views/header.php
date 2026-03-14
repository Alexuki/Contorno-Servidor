<?php
// views/header.php
?>
<!DOCTYPE html>
<html lang="es">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Olímpica - Juegos de Invierno</title>
    <style>
        :root {
            --primary: #1e3a8a;
            --secondary: #3b82f6;
            --accent: #f59e0b;
            --snow: #f0f9ff;
            --ice: #e0f2fe;
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background: var(--snow); }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        header { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 2rem; text-align: center; }
        .menu { background: white; padding: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .menu a { margin: 0 1rem; text-decoration: none; color: var(--primary); font-weight: bold; }
        .menu a:hover { color: var(--accent); }
        .card { background: white; border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }
        .btn { display: inline-block; padding: 0.5rem 1rem; background: var(--primary); color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn:hover { background: var(--secondary); }
        .btn-danger { background: #dc2626; }
        .medalla { display: inline-block; width: 20px; height: 20px; border-radius: 50%; margin: 0 2px; }
        .oro { background: gold; }
        .plata { background: silver; }
        .bronce { background: #cd7f32; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: var(--ice); }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.25rem; font-weight: bold; }
        input, select { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; }
        .stats { display: flex; gap: 2rem; margin-bottom: 2rem; }
        .stat-box { background: white; padding: 1rem; border-radius: 8px; text-align: center; flex: 1; }
        .stat-number { font-size: 2rem; font-weight: bold; color: var(--primary); }
    </style>
</head>

<body>
    <header>
        <h1>🏔️ Villa Olímpica de Invierno</h1>
        <p>Gestión de Deportistas</p>
    </header>
    <div class="menu">
        <a href="index.php">🏠 Inicio</a>
        <a href="por_deporte.php">⛷️ Por Deporte</a>
        <a href="por_pais.php">🌍 Por País</a>
        <a href="agregar.php">➕ Agregar Deportista</a>
    </div>
    <div class="container">