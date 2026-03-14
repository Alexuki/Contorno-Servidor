<?php
    $file_id = "fileToUpload";
    $target_dir = "uploads/";
    $accepted_types = ["jpg", "jpeg", "gif"];
    $accepted_max_size = 500_000; //Bytes

    $file_original_name = $_FILES[$file_id]["name"]; // 00 manzana.jpg
    $file_tmp_name = $_FILES[$file_id]["tmp_name"]; // /tmp/phpEjD13y
    $file_size = $_FILES[$file_id]["size"]; //Bytes  // 37508


    $file_path = $target_dir . basename($file_original_name); // uploads/ 00 manzana.jpg
    $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION)); // jpg

    // Crear el directorio de subida si no existe para evitar error en move_uploaded_file
    // mkdir respeta la umask del sistema, por eso se usa chmod después para forzar los permisos reales
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
        chmod($target_dir, 0777);
    }

    if(!file_exists($file_path)) {

        if(!in_array($file_extension, $accepted_types)) {
            echo "Formato de archivo no aceptado. Solo se admiten: " . implode(", ", $accepted_types);
        } else {

            if ($file_size < $accepted_max_size) {
                if (move_uploaded_file($file_tmp_name, $file_path)) {
                    echo "Fichero " . htmlspecialchars($file_original_name) . " subido";
                } else {
                    echo "Error al subir el fichero.";
                }

            } else {
                echo "El archivo es muy grande";
            }

        }

    } else {
        echo "El archivo ya existe";
    }

?>