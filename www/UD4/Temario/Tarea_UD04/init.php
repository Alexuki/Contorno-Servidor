<?php
require_once('auth.php');

$usuarioSesion = getUsuarioSesion();
// Permite inicializar en primer arranque sin login; si hay sesion, solo admin.
if ($usuarioSesion !== null && !esAdmin()) {
    redirectTo('index.php');
}

$tema = getTema();
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="<?php echo $tema; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD4 Tarea - Init</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('vista/header.php'); ?>

    <div class="container-fluid">
        <div class="row">

        <?php if (usuarioAutenticado()) { include_once('vista/menu.php'); } ?>

            <main class="<?php echo usuarioAutenticado() ? 'col-md-9 ms-sm-auto col-lg-10' : 'col-12'; ?> px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Inicializacion</h2>
                </div>

                <div class="container justify-content-between">
                    <?php
                        require_once('modelo/mysqli.php');
                        require_once('modelo/pdo.php');
                        require_once('utils.php');

                        $resultado = creaDB();
                        mostrarAlert($resultado);

                        $resultado = createTablaUsuarios();
                        mostrarAlert($resultado);

                        $resultado = createTablaTareas();
                        mostrarAlert($resultado);

                        $resultado = createTablaFicheros();
                        mostrarAlert($resultado);

                        // Usuario admin inicial para poder entrar por primera vez.
                        try {
                            $con = conectaPDO();
                            $stmt = $con->query('SELECT COUNT(*) AS total_admin FROM usuarios WHERE rol = 1');
                            $totalAdmin = (int) $stmt->fetch()['total_admin'];
                            if ($totalAdmin === 0) {
                                $ins = nuevoUsuario('Admin', 'Sistema', 'admin', 'admin12345', 1);
                                if ($ins[0]) {
                                    mostrarAlert([true, 'Usuario admin inicial creado (admin / admin12345).']);
                                } else {
                                    mostrarAlert([false, 'No se pudo crear el usuario admin inicial: ' . $ins[1]]);
                                }
                            }
                        } catch (Throwable $e) {
                            mostrarAlert([false, 'No se pudo comprobar/crear el usuario admin inicial: ' . $e->getMessage()]);
                        }
                    ?>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('vista/footer.php'); ?>

</body>
</html>
