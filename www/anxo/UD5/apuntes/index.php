<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Menú - Programación Orientada a Objetos en PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
            line-height: 1.6;
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 0.5rem;
        }
        h2 {
            color: #555;
            margin-top: 2rem;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin: 0.5rem 0;
            padding: 0.5rem;
            background: #f5f5f5;
            border-radius: 4px;
        }
        li:hover {
            background: #e0e0e0;
        }
        a {
            text-decoration: none;
            color: #2196F3;
            font-weight: bold;
        }
        a:hover {
            color: #0D47A1;
        }
        .descripcion {
            color: #666;
            font-size: 0.9rem;
            margin-left: 1rem;
        }
        .tema {
            background: #4CAF50;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <h1>📚 Programación Orientada a Objetos en PHP</h1>
    <p>Selecciona un tema para ver el ejemplo:</p>

    <div class="tema">🚀 Fundamentos</div>
    <ul>
        <li>
            <a href="1_clases_objetos.php">1. Clases y Objetos</a>
            <span class="descripcion">— Atributos, métodos, instancias </span>
        </li>
        <li>
            <a href="2_modificadores_acceso.php">2. Modificadores de Acceso</a>
            <span class="descripcion">— public, protected, private</span>
        </li>
        <li>
            <a href="3_this_instanceof.php">3. $this e instanceof</a>
            <span class="descripcion">— Referencia al objeto actual y verificación de tipo</span>
        </li>
    </ul>

    <div class="tema">⚡ Ciclo de Vida</div>
    <ul>
        <li>
            <a href="4_constructor_destructor.php">4. Constructor y Destructor</a>
            <span class="descripcion">— __construct() y __destruct()</span>
        </li>
    </ul>

    <div class="tema">🌳 Herencia</div>
    <ul>
        <li>
            <a href="5_herencia.php">5. Herencia Básica</a>
            <span class="descripcion">— extends, heredar propiedades y métodos</span>
        </li>
        <li>
            <a href="6_herencia_protected.php">6. Protected en Herencia</a>
            <span class="descripcion">— Acceso protegido entre clases padre e hija</span>
        </li>
        <li>
            <a href="7_sobrescribir_metodos.php">7. Sobrescribir Métodos</a>
            <span class="descripcion">— Override de métodos heredados</span>
        </li>
        <li>
            <a href="8_final.php">8. Final</a>
            <span class="descripcion">— Clases y métodos que no se pueden heredar/sobrescribir</span>
        </li>
    </ul>

    <div class="tema">🔷 Abstracción y Documentación</div>
    <ul>
        <li>
            <a href="9_clases_abstractas.php">9. Clases Abstractas</a>
            <span class="descripcion">— abstract, métodos obligatorios en hijas</span>
        </li>
        <li>
            <a href="10_clases_anonimas.php">10. Clases Anónimas</a>
            <span class="descripcion">— Clases sin nombre para uso único</span>
        </li>
        <li>
            <a href="11_documentacion.php">11. Documentación</a>
            <span class="descripcion">— PHPDoc, comentarios estructurados</span>
        </li>
    </ul>

    <div class="tema">🔌 Interfaces y Traits </div>
<ul>
    <li>
        <a href="12_interfaces.php">12. Interfaces</a>
        <span class="descripcion">— Define métodos obligatorios, implements</span>
    </li>
    <li>
        <a href="13_interfaces_vs_abstractas.php">13. Interfaces vs Clases Abstractas</a>
        <span class="descripcion">— Diferencias prácticas y cuándo usar cada una</span>
    </li>
    <li>
        <a href="14_multiples_interfaces.php">14. Múltiples Interfaces</a>
        <span class="descripcion">— Una clase puede implementar varias interfaces</span>
    </li>
    <li>
        <a href="15_traits.php">15. Traits</a>
        <span class="descripcion">— Reutilizar código en múltiples clases</span>
    </li>
</ul>

<div class="tema">🔧 Constantes, Estáticos y Avanzado</div>
<ul>
    <li>
        <a href="16_constantes.php">16. Constantes de Clase</a>
        <span class="descripcion">— const, self::, ::</span>
    </li>
    <li>
        <a href="17_estaticos.php">17. Métodos y Propiedades Estáticas</a>
        <span class="descripcion">— static, self::, sin instanciar</span>
    </li>
    <li>
        <a href="18_stdclass.php">18. Objetos Vacíos (stdClass)</a>
        <span class="descripcion">— Propiedades dinámicas, JSON</span>
    </li>
    <li>
        <a href="19_excepciones.php">19. Errores y Excepciones</a>
        <span class="descripcion">— throw, try-catch</span>
    </li>
</ul>
<footer style="margin-top: 3rem; padding-top: 1rem; border-top: 1px solid #ddd; color: #999; text-align: center;">
        <p>UD - Desarrollo Web en Entorno Servidor</p>
    </footer>
</body>
</html>