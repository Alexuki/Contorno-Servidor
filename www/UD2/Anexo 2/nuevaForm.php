<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD2. Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>
    <!--header-->
    <?php include_once("header.php"); ?>
    <div class="container-fluid">
        <div class="row">
            <!--menu-->
            <?php include_once("menu.php"); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap
align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Nueva tarea</h2>
                </div>

                <div class="container justify-content-between">

                    <form class="mb-5 w-50" method="POST" action="nueva.php">
                        <div class="mb-3">
                            <label for="id" class="form-label">Identificador</label>
                            <input id="id" name="id" class="form-control" type="text" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <input id="description" name="descripcion" class="form-control" type="text" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Estado</label>
                            <select id="status" name="estado" class="form-select" required>
                                <option value=" " selected disabled>Seleccionar estado</option>
                                <option value="en_proceso">En Proceso</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="completada">Completada</option>
                                
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>

                </div>

            </main>
        </div>
    </div>
    <!--footer-->
    <?php include_once("footer.php"); ?>
</body>

</html>