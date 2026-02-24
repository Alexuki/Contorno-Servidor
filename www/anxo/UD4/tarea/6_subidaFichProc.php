<?php
require_once 'config.php';
requiereLogin();

$mensaje = "";
$uploadOk = 0;

// Solo procesar si se envió archivo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivo"])) {
    
    $target_dir = "uploads/";
    
    // Crear carpeta si no existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($_FILES["archivo"]["name"]);
    $tipoArchivo = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Tamaño máximo: 500KB
    if ($_FILES["archivo"]["size"] > 500000) {
        $mensaje = "Error: El archivo es demasiado grande (máx 500KB).";
    }
    // Formatos permitidos según rol
    elseif ($_SESSION['rol'] === 'admin') {
        // Admin: jpg, png, pdf
        if ($tipoArchivo != "jpg" && $tipoArchivo != "png" && $tipoArchivo != "pdf") {
            $mensaje = "Error: Solo JPG, PNG y PDF permitidos.";
        } else {
            $uploadOk = 1;
        }
    } else {
        // Usuario: solo jpg, png (sin pdf)
        if ($tipoArchivo != "jpg" && $tipoArchivo != "png") {
            $mensaje = "Error: Como usuario, solo puedes subir JPG y PNG.";
        } else {
            $uploadOk = 1;
        }
    }
    
    // Comprobar si ya existe
    if ($uploadOk && file_exists($target_file)) {
        $mensaje = "Error: El archivo ya existe.";
        $uploadOk = 0;
    }
    
    // Subir archivo
    if ($uploadOk && move_uploaded_file($_FILES["archivo"]["tmp_name"], $target_file)) {
        $mensaje = "Éxito: Archivo " . htmlspecialchars(basename($_FILES["archivo"]["name"])) . " subido correctamente.";
    } elseif ($uploadOk) {
        $mensaje = "Error: Hubo un problema al subir el archivo.";
    }
}
?>
<!doctype html>
<html lang="es" class="<?= htmlspecialchars($tema) ?>">
<head>
    <meta charset="utf-8">
    <title>Resultado subida</title>
    <style>
        body.claro { background: #f5f5f5; color: #333; }
        body.oscuro { background: #333; color: #f5f5f5; }
        .exito { color: green; }
        .error { color: red; }
    </style>
</head>
<body class="<?= htmlspecialchars($tema) ?>">
    <h1>Resultado de la subida</h1>
    
    <p class="<?= $uploadOk ? 'exito' : 'error' ?>"><?= htmlspecialchars($mensaje) ?></p>
    
    <p><a href="5_subidaFichForm.php">Subir otro archivo</a> | 
       <a href="4_panel.php">Volver al panel</a></p>
</body>
</html>