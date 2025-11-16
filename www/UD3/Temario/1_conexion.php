<?php

// Parámetros de conexión:
// El primer parámetro es el hostname. Puede ser "localhost"
// en entornos como LAMP. Si usamos Docker, el nombre del servicio
// que contiene la BBDD, indicado en el docker-compose
$servername = "db";
$user = "root";
$pass = "test";
$dbName = "dbname";



/** MYSQL Orientado a Objetos **/

//1. Crear conexión
$conexion = new mysqli($servername, $user, $pass, $dbName);

//2. Comprobar la conexión
if($conexion->connect_error) {
    // No continuar la ejecución con el método die. Por eso no necesita else.
    die("Fallo de conexión: " . $conexion->connect_error);
}
echo "Conexión correcta";

// Realizar operaciones en BBDD

//3. Cerrar conexión al finalizar las operaciones
$conexion->close();



/** MYSQL Orientado a Objetos. Control de errores con el número de error **/

//1. Crear conexión. Si anteponemos "@", si en esa expresión se genera un fallo, no se muestra.
// De esta forma no se muestra en la aplicación un Warning, pero sí se muestra el error controlado en el catch
@$conexion = new mysqli($servername, $user, $pass, $dbName);

//2. Comprobar la conexión
$errorNum = $conexion->connect_errno; // Devuelve número de error o null si no hay error
$errorMensaje = $conexion->connect_error;
if($errorNum != null) {
    die("Fallo de conexión: " . $errorMensaje . " Con número: " . $errorNum);
}
echo "Conexión correcta";

// Realizar operaciones en BBDD

//3. Cerrar conexión al finalizar las operaciones
$conexion->close();



/** MYSQL Procedimental **/

//1. Crear conexión con un procedimiento
$conexion_2 = mysqli_connect($servername, $user, $pass, $dbName);

//2. Comprobar la conexión. Al ser un procedimeinto, si todo fue bien,
//la variable será verdadera (tendrá algo incrustado). Si no, será false.
if(!$conexion_2) {
    die("Fallo de conexión".mysqli_connect_error()); //Procedimiento para ver el error
}
echo "Conexión procedimental correcta";

//3. Cerrar conexión
mysqli_close($conexion_2);


/** PDO **/

try {
    //1. Crear conexión
    // Indicar el tipo de BBDD. Admite otras aparte de MySQL
    $conexionPDO = new PDO("mysql:host=$servername;dbname=$dbName", $user, $pass);

    //2. Forzar excepciones
    // Indicar que los PDO estarán en modo error y lanzarán excepciones.
    // setAttribute(modo_error que lo lance si se produce (no warnings por ejemplo), lanza_excepcion si hay error)
    $conexionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión PDO correcta";
} catch(PDOException $e) {
    echo "Fallo de conexión: " . $e->getMessage();
}

//3. Cerrar conexión
$conexionPDO = null;

?>