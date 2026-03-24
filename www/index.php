<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba Docker LAMP - Funcionando!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
        }
        .status {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .php-test {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        pre {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🐳 Docker LAMP Stack</h1>
        
        <div class="status success">
            ✓ ¡Servidor Apache funcionando correctamente!
        </div>
        
        <div class="info">
            <h3>📋 Información del servidor:</h3>
            <p><strong>Fecha y hora actual:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            <p><strong>Sistema operativo:</strong> <?php echo php_uname(); ?></p>
            <p><strong>Versión de PHP:</strong> <?php echo phpversion(); ?></p>
        </div>
        
        <div class="php-test">
            <h3>🔧 Prueba de PHP:</h3>
            <?php
                echo "<p><strong>PHP está funcionando correctamente</strong></p>";
                
                // Prueba de conexión a MySQL (si está configurado)
                if (function_exists('mysqli_connect')) {
                    echo "<p>✓ MySQLi está habilitado</p>";
                } else {
                    echo "<p>⚠ MySQLi no está disponible</p>";
                }
            ?>
        </div>
        
        <h3>📁 Estructura de archivos:</h3>
        <pre>
docker-lamp/
├── www/
│   └── test.php (este archivo)
├── docker-compose.yml
├── mysql/
└── [otros archivos de configuración]
        </pre>
        
        <h3>🚀 Próximos pasos:</h3>
        <ol>
            <li>Crear archivos PHP adicionales</li>
            <li>Configurar conexión a base de datos</li>
            <li>Desarrollar tu aplicación</li>
        </ol>
        
        <p style="text-align: center; margin-top: 30px; color: #666;">
            <small>Si ves esta página, tu stack LAMP está funcionando correctamente 🎉</small>
        </p>
    </div>
</body>
</html>