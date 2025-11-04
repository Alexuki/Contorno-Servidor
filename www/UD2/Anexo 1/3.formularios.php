<html>

    <head>
        <title>Anexo 1</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container-fluid">
            <h1>Anexo 1</h1>

            <h3>Tarea 1. Formularios y Strings</h3> 
            <p>Crea un formulario que solicite nombre y apellido. Cuando se reciben los datos, se debe mostrar la información.</p>

            <form method="GET" action="3.formularios1.php">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="nombre" required>
                <label for="surname">Apellido</label>
                <input type="text" id="surname" name="apellido" required>
                <br><br>
                <input type="submit" value="enviar"> 
            </form>
            <br>

            <h3>Tarea 2. Envío de formularios</h3> 
            <p>Crea un formulario para solicitar una de las siguientes bebidas.</p>

            <table>
                <tr>
                    <th>Bebida</th>
                    <th>PVP</th>
                </tr>
                <tr>
                    <td>Coca Cola</td>
                    <td>1 €</td>
                </tr>
                <tr>
                    <td>Pepsi Cola</td>
                    <td>0,80 €</td>
                </tr>
                <tr>
                    <td>Fanta Naranja</td>
                    <td>0,90 €</td>
                </tr>
                <tr>
                    <td>Trina Manzana</td>
                    <td>1,10 €</td>
                </tr>
            </table>

            <form method="POST" action="3.formularios2.php">
                <label for="drinks">Bebidas</label>
                <select id="drinks" name="bebida">
                    <option value="Coca Cola">Coca Cola - 1.00€</option>
                    <option value="Pepsi Cola">Pepsi Cola - 0.80€</option>
                    <option value="Fanta Naranja">Fanta Naranja - 0.90€</option>
                    <option value="Trina Manzana">Trina Manzana - 1.10€</option>
                </select>
                <label for="qty">Cantidad</label>
                <input type="number" id="qty" name="cantidad" min="1">
                <input type="submit" value="Enviar">
            </form>

        </div>
    </body>
</html> 