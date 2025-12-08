<footer>
    <p><a href='index.php'>Página de inicio</a></p>
    <?php 
        if(isset($con)) {
            cerrarConexion($con);
        } 
    ?>
</footer>