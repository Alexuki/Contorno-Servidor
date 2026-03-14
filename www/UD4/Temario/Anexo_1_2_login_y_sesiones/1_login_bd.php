<?php
session_start();

function getConnection() {
    $servername ="db";
    $username = "root";
    $password = "test";  
    $dbname = "sesiones";

    try {
        //1. Conexión a base de datos
            $conPDO = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        //2. Forzar excepciones
            $conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Conexión correcta a BBDD";
    } catch (PDOException $ex) {
        die("Error en la conexión: " . $ex->getMessage());
    }
    return $conPDO;

}

if($_SERVER["REQUEST_METHOD"] == "GET") {
    //Para insertar la contraseña usaríamos esta función
    // NOTA: La variable se interpola correctamente porque el string completo está dentro de comillas dobles
    $conPDO = getConnection();

    $pass_hasheado_usuario = password_hash("abc123.", PASSWORD_DEFAULT);
    $pass_hasheado_admin = password_hash("1234", PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuario (nombre, pass) VALUES ('usuario','$pass_hasheado_usuario')";
    $sql .= ", ('admin','$pass_hasheado_admin')";
    echo $sql;
    $conPDO->exec($sql); 
}

if($_SERVER["REQUEST_METHOD"]=="POST") {
    
    $conPDO = getConnection();

    $usuarioIntroducido = $_POST["usuario"];
    $passIntroducido=$_POST['pass'];

    $consulta = "SELECT pass FROM usuario WHERE nombre = :nomeTecleado";
    $stmt = $conPDO->prepare($consulta);

    try {
        $stmt->execute(
            [
                'nomeTecleado' => $usuarioIntroducido
            ]
        );
    } catch (PDOException $ex) {
        $conPDO = null;
        die("Erro recuperando os datos da BD: " . $ex->getMessage());
    }

    $fila = $stmt->fetch();
    $contrasinalBD=$fila[0];
    

    //Primero comprobamos que haya un usuario y después comprobamos la contraseña introducida
    if ($stmt->rowCount() == 1 && password_verify($passIntroducido, $contrasinalBD)) {
        $_SESSION["usuario"] = $usuarioIntroducido;
    } else {
        echo "Error de usuario";
    }

    $stmt = null;
    $conPDO = null;
}
?>

<html>

<body>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        Usuario: <input name="usuario" id="usuario" type="text">
        Contraseña: <input name="pass" id="pass" type="password">
        <input type="submit">
    </form>
</body>

</html>