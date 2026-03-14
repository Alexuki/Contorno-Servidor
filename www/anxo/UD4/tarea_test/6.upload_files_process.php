<?php
    require_once "config.php";
    requireLogin();

    $message = "";
    $Ok = false;
    $target_dir = "uploads/";
    $admin_exts = ["jpg", "png", "pdf"];
    $user_exts = ["jpg", "png"];


    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {

        if (!is_dir($target_dir)) {
            mkdir($target_dir);
        }

        $fileName = basename($_FILES["file"]["name"]);
        $fileSize = $_FILES["file"]["size"];
        $fileTmpPath = $_FILES["file"]["tmp_name"];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $target_file = $target_dir . $fileName;
        $rol = getRol();

        if (!file_exists($target_file)) {

            if ($fileSize > 500_000) {
                $message = "Error: Archivo superior a 500 KB";
            } elseif ($rol == "admin") {
                if (!in_array($ext, $admin_exts)) {
                    $message = "Error: Formato no admitido para admin. Admitidos: " . implode(", ", $admin_exts);
                } else {
                    $Ok = true;
                }
            } else {
                if (!in_array($ext, $user_exts)) {
                    $message = "Error: Formato no admitido para user. Admitidos: " . implode(", ", $user_exts);
                } else {
                    $Ok = true;
                }
            }
        } else {
            $message = "Error: El archivo ya existe";
        }

        if($Ok && move_uploaded_file($fileTmpPath, $target_file)) {
            $message = "Archivo subido con éxito";
        } elseif($Ok) {
            $Ok = false;
            $message = "Error al subir el archivo";
        }

    }

?>

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
        
        <p class="<?= $Ok ? 'exito' : 'error' ?>"><?= htmlspecialchars($message) ?></p>
        
        <p><a href="5.upload_files.php">Subir otro archivo</a> | 
        <a href="4.panel.php">Volver al panel</a></p>
    </body>

</html>