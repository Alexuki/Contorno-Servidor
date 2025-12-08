<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD2. Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!--header-->
    <?php include_once("header.php") ?>
    <div class="container-fluid">
        <div class="row">
            <!--menu-->
            <?php include_once("menu.php") ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Formulario Nueva Tarea</h2>
                </div>

                <div class="container">

                    <form class="mb-5" method="POST" action="nueva.php">

                        <div class="mb-3">
                            <label class="form-label" for="id">Identificador</label>
                            <input class="form-control" id="id" name="id" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" for="descripcion">Descripcion</label>
                            <input class="form-control" id="descripcion" name="descripcion" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="estado">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="" selected disabled>Seleccionar estado</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="en_proceso">En Proceso</option>
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
    <?php include_once("footer.php") ?>
</body>

</html>