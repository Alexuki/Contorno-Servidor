<?php
$mensaje = "";
$target_dir = "uploads/";

// Crear carpeta uploads si no existe
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Procesar formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileToUpload"])) {
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Comprobar si el archivo ya existe
    if (file_exists($target_file)) {
        $mensaje = "El fichero ya existe";
        $uploadOk = 0;
    }

    // Limitar tamaño de archivo (500 KB)
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $mensaje = "El archivo es demasiado grande.";
        $uploadOk = 0;
    }

    // Limitar tipo de archivo
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $mensaje = "Solo los ficheros JPG, JPEG, PNG & GIF están permitidos.";
        $uploadOk = 0;
    }

    // Intentar subir si todo está OK
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $mensaje = "El fichero " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " ha sido subido.";
        } else {
            $mensaje = "Hubo un error subiendo el fichero";
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>3. Subir archivos</title></head>
<body>
    <h1>Subir archivos</h1>

    <?php if ($mensaje): ?>
        <p><strong><?= htmlspecialchars($mensaje) ?></strong></p>
    <?php endif; ?>

    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
        <p>Selecciona fichero para subir (JPG, JPEG, PNG, GIF, máx 500 KB):</p>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Subir imagen" name="submit">
    </form>

    <h2>Archivos en uploads/</h2>
    <?php
    $archivos = glob($target_dir . "*");
    if ($archivos) {
        foreach ($archivos as $archivo) {
            echo "<p>" . htmlspecialchars(basename($archivo)) . "</p>";
        }
    } else {
        echo "<p>(vacío)</p>";
    }
    ?>

    <p><a href="1_leerficheros.php">Volver a leer ficheros</a></p>
</body>
</html>