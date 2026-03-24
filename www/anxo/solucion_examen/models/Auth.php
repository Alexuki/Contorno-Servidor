<?php
// models/Auth.php
// Gestiona toda la lógica de autenticación y autorización

class Auth {
    private static $usuarios = null;
    
    /**
     * Inicializa la lista de usuarios válidos
     */
    private static function initUsuarios() {
        if (self::$usuarios == null) {
            self::$usuarios = array(
                new Usuario('deportista', 'nieve', 'admin'),
                new Usuario('espectador', 'hielo', 'user')
            );
        }
    }
    
    /**
     * IMPLEMENTAR POR EL ALUMNO
     * Crea un bucle que recorra el array de usuarios válidos self::$usuarios y compruebe que sus campos sean
     * los mismos que los parámetros que se les pasa a la función.
     * En caso correcto se deberá iniciar sesión y crear las variables de sesión usuario y rol.
     */
    public static function login($username, $password) {
        self::initUsuarios();

        foreach (self::$usuarios as $usuario) {
            if ($usuario->getUsername() == $username && $usuario->verificarPassword($password)) {
                session_start();
                $_SESSION['usuario'] = $username;
                $_SESSION['rol'] = $usuario->getRol();
                return true;
            }
        }
        return false;
    }
    
    /**
     * IMPLEMENTAR POR EL ALUMNO
     * Verifica que haya una sesión activa.
     * Si no la hay, redirige al login.
     */
    public static function checkAuth() {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header('Location: login.php');
            exit;
        }
    }
    
    /**
     * IMPLEMENTAR POR EL ALUMNO
     * Verifica que el usuario sea administrador.
     * Si no lo es, redirige al inicio.
     */
    public static function checkAdmin() {
        self::checkAuth();
        if ($_SESSION['rol'] != 'admin') {
            header('Location: index.php');
            exit;
        }
    }
    
    /**
     * IMPLEMENTAR POR EL ALUMNO
     * Cierra la sesión y redirige al login
     */
    public static function logout() {
        session_start();
        session_destroy();
        header('Location: login.php');
        exit;
    }
    
    /**
     * IMPLEMENTAR POR EL ALUMNO
     * Devuelve el nombre del usuario logueado
     */
    public static function getUsuarioActual() {
        return isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
    }
    
    /**
     * IMPLEMENTAR POR EL ALUMNO
     * Devuelve el rol del usuario logueado
     */
    public static function getRolActual() {
        return isset($_SESSION['rol']) ? $_SESSION['rol'] : null;
    }
}
?>