<?php
require_once("lib/base_datos.php");
require_once("lib/utilidades.php");

$mensajes = "";
$previewFoto = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    if (
        empty($_POST["nombre"]) ||
        empty($_POST["precio"]) ||
        empty($_POST["unidades"]) ||
        !isset($_FILES["foto"]) ||
        $_FILES["foto"]["error"] !== UPLOAD_ERR_OK
    ) {
        $mensajes = "Faltan datos obligatorios o hay un error en la subida de la imagen.<br>";
    } else {
        $nombre = test_input($_POST["nombre"]);
        $descripcion = test_input($_POST["descripcion"] ?? "");
        $precio = test_input($_POST["precio"]);
        $unidades = test_input($_POST["unidades"]);
        $fichero = $_FILES["foto"];

        $extensionesPermitidas = ["png", "jpg", "jpeg", "gif"];
        $mimePermitidos = ["image/png", "image/jpeg", "image/gif"];
        $maxBytes = 5 * 1024 * 1024;

        $extension = strtolower(pathinfo($fichero["name"], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionesPermitidas, true)) {
            $mensajes .= "Extension no permitida. Solo png, jpg, jpeg o gif.<br>";
        }

        if ($fichero["size"] > $maxBytes) {
            $mensajes .= "La imagen supera el tamano maximo de 5MB.<br>";
        }

        if (!is_numeric($precio) || !is_numeric($unidades)) {
            $mensajes .= "Precio y unidades deben ser numericos.<br>";
        }

        $tipoMime = mime_content_type($fichero["tmp_name"]);
        if ($tipoMime === false || !in_array($tipoMime, $mimePermitidos, true)) {
            $mensajes .= "El fichero subido no es una imagen valida.<br>";
        }

        if ($mensajes === "") {
            $fotoBinaria = file_get_contents($fichero["tmp_name"]);

            if ($fotoBinaria === false) {
                $mensajes = "No se pudo leer el contenido binario de la imagen.<br>";
            } else {
                $nombreBd = "tienda";
                $conexion = get_conexion();
                seleccionar_bd($conexion, $nombreBd);

                dar_alta_producto(
                    $conexion,
                    $nombre,
                    $descripcion,
                    (float) $precio,
                    (int) $unidades,
                    $fotoBinaria
                );

                cerrar_conexion($conexion);

                // Para mostrar binario en HTML se usa data URI + base64.
                $previewFoto = "data:" . $tipoMime . ";base64," . base64_encode($fotoBinaria);
                $mensajes = "Producto dado de alta correctamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tienda IES San Clemente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <h1>Alta de producto</h1>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>

    <?= $mensajes; ?>

    <p>Formulario de alta de productos</p>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        Nombre: <input type="text" name="nombre" required>
        <br><br>
        Descripcion: <input type="text" name="descripcion">
        <br><br>
        Precio: <input type="number" step="0.01" min="0" name="precio" required>
        <br><br>
        Unidades: <input type="number" min="0" name="unidades" required>
        <br><br>
        Foto: <input type="file" name="foto" accept=".png,.jpg,.jpeg,.gif" required>
        <small>(maximo 5MB)</small>
        <br><br>
        <input type="submit" name="submit" value="Submit">
    </form>

    <?php if ($previewFoto !== "") { ?>
        <h2>Vista previa de la imagen guardada</h2>
        <img src="<?= $previewFoto; ?>" alt="Foto del producto" style="max-width: 320px; height: auto;">
    <?php } ?>

    <footer>
        <p>
            <a href='index.php'>Pagina de inicio</a>
        </p>
    </footer>
</body>

</html>
