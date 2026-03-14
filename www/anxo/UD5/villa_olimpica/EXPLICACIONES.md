# Explicaciones - Villa Olimpica

## 1) Por que el constructor de `Database` es privado y como se ejecuta

En `config/Database.php`, la clase `Database` usa el patron **Singleton**.

Objetivo: que exista una sola instancia de la clase y una sola conexion compartida (`self::$conn`) durante la ejecucion de la peticion.

### Por que es `private function __construct()`

Si el constructor fuera publico, cualquier parte del codigo podria hacer esto:

```php
$db1 = new Database();
$db2 = new Database();
```

Eso crearia multiples conexiones y volveria a ejecutar la logica de inicializacion (crear BBDD, crear tablas, insertar datos de ejemplo), algo ineficiente y potencialmente problematico.

Al hacerlo `private`, **se bloquea la creacion directa desde fuera de la clase**. Solo la propia clase puede construir su instancia.

### Entonces, como se ejecuta el constructor si es privado

Se ejecuta de forma indirecta desde el metodo estatico `getConnection()`:

```php
public static function getConnection() {
    if (self::$instance == null) {
        self::$instance = new Database();
    }
    return self::$conn;
}
```

Flujo real:

1. Tu codigo llama a `Database::getConnection()`.
2. Si `self::$instance` es `null`, la propia clase hace `new Database()`.
3. Ese `new Database()` SI es valido porque ocurre dentro de la misma clase.
4. Se ejecuta `__construct()` una unica vez:
   - abre conexion al servidor MySQL,
   - crea la base de datos si no existe,
   - reconecta indicando `dbname`,
   - crea tablas,
   - inserta datos de ejemplo si la tabla esta vacia.
5. En llamadas posteriores a `Database::getConnection()`, ya no entra en el `new Database()`, y reutiliza la conexion existente.

### Idea clave

- `constructor privado`: impide instancias directas externas.
- `metodo estatico getConnection()`: punto unico de acceso.
- `self::$instance`: controla que solo se cree una vez.

Ese es el motivo principal de combinar constructor privado + metodo estatico en esta clase.

### Aclaracion importante sobre `new` en metodos estaticos

Aunque `getConnection()` sea estatico, sigue siendo codigo definido dentro de la clase `Database`.
Por eso, cuando dentro de ese metodo se hace `new Database()`, PHP permite ejecutar `private function __construct()`.

Regla practica:

- `new Database()` desde fuera de la clase: no permitido si el constructor es `private`.
- `new Database()` dentro de un metodo de la propia clase (estatico o no): si permitido.

Resumen de visibilidad:

- `private`: solo accesible desde la propia clase.
- `protected`: accesible desde la clase y sus clases hijas.
- `public`: accesible desde cualquier parte.

## 2) En las tablas especificas, la PK y la FK son la misma columna

En `esquiadores`, `patinadores` y `saltadores`, la columna `deportista_id` actua a la vez como:

- `PRIMARY KEY`
- `FOREIGN KEY` que referencia `deportistas(id)`

Esto no es un error: se usa para modelar una relacion **1:1** entre la tabla base (`deportistas`) y cada tabla de detalle.

Consecuencias practicas:

- Un registro de tabla especifica siempre corresponde a un deportista existente.
- No se puede repetir el mismo `deportista_id` en la tabla especifica (por ser PK).
- Con `ON DELETE CASCADE`, si se borra el deportista base, su detalle tambien se elimina automaticamente.

Es un patron habitual cuando se separa una entidad general en subtipos (base + tablas por tipo).

### Que significa exactamente `ON DELETE CASCADE`

`ON DELETE CASCADE` se define en la **clave foranea** (tabla hija), pero su efecto se activa cuando borras en la **tabla referenciada** (tabla padre).

Direccion correcta:

- Padre -> Hija: si borras la fila padre, se borran automaticamente las filas hijas relacionadas.
- Hija -> Padre: si borras la fila hija, NO se borra la fila padre.

En este proyecto:

- Padre: `deportistas(id)`.
- Hijas: `esquiadores(deportista_id)`, `patinadores(deportista_id)`, `saltadores(deportista_id)`.

Ejemplo:

- Si borras `deportistas.id = 7`, se borra tambien el posible detalle con `deportista_id = 7` en la tabla especifica correspondiente.
- Si borras solo `esquiadores.deportista_id = 7`, el registro `deportistas.id = 7` sigue existiendo.

## 3) Que pasa con `getConnection()` al cambiar de pagina

En esta clase, `getConnection()` reutiliza la misma instancia solo **dentro de la misma peticion HTTP**.

En PHP web (Apache/PHP-FPM), cada pagina que cargas normalmente es una peticion nueva:

1. Arranca el script.
2. Se cargan clases y variables estaticas desde cero.
3. Si llamas a `Database::getConnection()`, se crea la instancia si no existe.
4. Termina la peticion y PHP libera memoria (incluyendo `self::$instance` y `self::$conn`).

Por tanto:

- En la misma pagina/peticion: se reutiliza la misma conexion singleton.
- Al ir a otra pagina (otra peticion): se crea una nueva instancia y una nueva conexion.

Importante: `static` en PHP no significa "para siempre" en toda la aplicacion web; significa "compartido durante la ejecucion actual del script".

### Nota: Singleton de aplicacion vs conexion persistente PDO

No hay que mezclar estos dos conceptos:

- Singleton de `Database` (tu codigo): reutiliza la misma conexion solo dentro de la peticion actual.
- `PDO::ATTR_PERSISTENT` (opcion de PDO): intenta reutilizar conexiones a nivel del proceso PHP entre peticiones.

Aunque actives conexiones persistentes, tu `self::$instance` y `self::$conn` siguen reiniciandose en cada nueva peticion. Lo persistente, si aplica, es el recurso de conexion gestionado internamente por el motor.

## 4) Por que `findAll` usa `WHERE 1=1` y si es necesario

En `models/DeportistaRepository.php`, el metodo `findAll` empieza la consulta con:

```sql
WHERE 1=1
```

`1=1` siempre es verdadero, asi que no filtra nada por si mismo.

Se usa como truco para construir SQL dinamico de forma comoda: despues puedes ir anadiendo condiciones con `AND ...` sin tener que comprobar si es la primera condicion.

Ejemplo de ventaja:

- Sin `WHERE 1=1`: tendrias que decidir entre empezar por `WHERE` o por `AND` segun si ya hay filtros previos.
- Con `WHERE 1=1`: siempre concatenas `AND d.tipo_deporte = ?`, `AND d.pais = ?`, etc.

Es necesario?

- No, no es obligatorio.
- Si quieres, puedes construir la consulta de otra forma (por ejemplo, guardando condiciones en un array y haciendo `implode(' AND ', $condiciones)`).
- En este proyecto, `WHERE 1=1` esta bien porque simplifica el codigo y lo hace facil de leer para filtros opcionales.

## 5) Como funciona la consulta preparada en `findAll`

En `models/DeportistaRepository.php`, `findAll` hace esto:

1. Construye el SQL base con joins.
2. Inicializa `$params = array();`.
3. Si hay filtros, anade condiciones con `AND ... = ?` y mete los valores en `$params` en el mismo orden.
4. Ejecuta:

```php
$stmt = $this->conn->prepare($sql);
$stmt->execute($params);
```

### Es correcto usar `?` en lugar de placeholders nombrados

Si, es correcto. En PDO hay dos estilos validos:

- Posicional: `? ? ?` y en `execute(...)` pasas un array normal (indexado).
- Nombrado: `:tipo`, `:pais` y en `execute(...)` pasas un array asociativo.

No se deben mezclar ambos estilos en la misma sentencia.

### En `execute($params)`, que array se pasa aqui exactamente

En este `findAll`, `$params` es un **array normal indexado** (no asociativo), porque el SQL usa `?`.

Ejemplo segun filtros activos:

```php
$params = array('esqui', 'Austria', 3);
```

PDO enlaza por posicion:

- primer `?` -> `'esqui'`
- segundo `?` -> `'Austria'`
- tercer `?` -> `3`

Si no hay filtros, `$params` queda vacio (`array()`), y `execute(array())` tambien es valido.

### Nota practica

Con `!empty($filtros['min_medallas'])`, el valor `0` se considera vacio y no se aplica ese filtro. Si algun dia quieres permitir "minimo 0" de forma explicita, conviene comprobar con `isset(...)` o `array_key_exists(...)`.

## 6) Por que en el destructor del Repository se cierra la conexion

En `models/DeportistaRepository.php`, el destructor hace:

```php
public function __destruct() {
    Database::close();
}
```

Y `Database::close()` (en `config/Database.php`) pone a `null`:

- `self::$conn`
- `self::$instance`

### Para que sirve hacerlo

- Libera recursos de forma explicita cuando el objeto Repository deja de usarse.
- Deja el estado del Singleton limpio (sin conexion y sin instancia).
- Evita mantener la conexion mas tiempo del necesario en scripts largos.

### Si no se hiciera, quedaria abierta al cambiar de pagina

En una aplicacion PHP web normal, **no** queda abierta "para siempre" al cambiar de pagina.

Cada pagina suele ser una peticion HTTP nueva:

1. Se ejecuta el script de esa peticion.
2. Al terminar, PHP libera memoria del proceso de peticion.
3. La referencia PDO se destruye y la conexion se cierra (si no es persistente).

Entonces:

- En peticiones normales, aunque no llames al destructor, la conexion se cerrara al final de la peticion.
- El destructor aporta limpieza explicita y control del ciclo de vida, pero no es la unica barrera para cerrar la conexion.

### Excepcion a tener en cuenta

Si se usan conexiones persistentes (`PDO::ATTR_PERSISTENT => true`) o procesos largos (workers, scripts CLI que no terminan), la gestion explicita de cierre cobra mas importancia, porque los recursos pueden vivir mas tiempo.

## 7) Que hace `fetchColumn()` en `SELECT COUNT(*)`

En `config/Database.php` aparece este bloque:

```php
$result = self::$conn->query("SELECT COUNT(*) FROM deportistas");
if ($result->fetchColumn() == 0) {
    $this->insertSampleData();
}
```

### Que devuelve la query

La consulta:

```sql
SELECT COUNT(*) FROM deportistas
```

devuelve **una sola fila y una sola columna** con el total de registros de la tabla `deportistas`.

Ejemplo conceptual del resultado:

| COUNT(*) |
|----------|
| 5        |

### Que hace `fetchColumn()`

`fetchColumn()` extrae el valor de una columna de la siguiente fila del `PDOStatement`.

- Si no le pasas argumento, usa la columna de indice `0` (la primera).
- En este caso, como solo hay una columna, devuelve directamente el total del `COUNT(*)`.

Por eso la condicion comprueba si el total es `0`: si no hay deportistas, se insertan datos de ejemplo.

### Nombre de la columna: cual es exactamente

Sin alias, el nombre de la columna suele ser `COUNT(*)` (dependiendo del driver/motor), pero en este codigo no importa porque se lee por posicion (`fetchColumn()` con indice `0`).

Si quieres un nombre estable y legible, usa alias:

```sql
SELECT COUNT(*) AS total FROM deportistas
```

Entonces podrias leerlo con `fetch()` y clave asociativa (`$fila['total']`), o seguir con `fetchColumn(0)`.

## 9) Uso de `fetch_all` y `fetchAll(PDO::FETCH_COLUMN)`

### `fetch_all` en MySQLi: que hace y que puede devolver

En MySQLi, `fetch_all(...)` se usa sobre un resultado (`mysqli_result`) para traer **todas las filas de una vez**.

Formas habituales:

- `fetch_all(MYSQLI_ASSOC)`: devuelve array de arrays asociativos (claves por nombre de columna).
- `fetch_all(MYSQLI_NUM)`: devuelve array de arrays numericos (indices 0, 1, 2...).
- `fetch_all(MYSQLI_BOTH)`: mezcla claves numericas y asociativas.

Ejemplo conceptual con `MYSQLI_ASSOC`:

```php
[
    ['id' => 1, 'nombre' => 'Ana'],
    ['id' => 2, 'nombre' => 'Luis']
]
```

### Por que en PDO se usa `fetchAll(PDO::FETCH_COLUMN)` en ese ejemplo

En PDO, `fetchAll(PDO::FETCH_COLUMN)` sirve para obtener **solo una columna de todas las filas**.

Si haces:

```sql
SELECT nombre FROM usuarios
```

con `fetchAll(PDO::FETCH_COLUMN)`, el resultado es una lista plana:

```php
['Ana', 'Luis', 'Marta']
```

Se usa porque es mas comodo cuando solo necesitas una columna (por ejemplo, nombres de paises para un `select`) y evita recorrer filas completas para extraer ese campo.

### Diferencia clave entre `fetch_all(MYSQLI_ASSOC)` y `fetchAll(PDO::FETCH_COLUMN)`

- `fetch_all(MYSQLI_ASSOC)` (MySQLi):
    - trae todas las columnas seleccionadas,
    - estructura de salida = lista de filas asociativas.

- `fetchAll(PDO::FETCH_COLUMN)` (PDO):
    - trae una sola columna por fila,
    - estructura de salida = lista plana de valores.

No son equivalentes directos: uno devuelve filas completas (asociativas) y el otro devuelve solo una columna.

### Se pueden usar ambas en PDO

No. En PDO no existe `fetch_all(...)` con guion bajo.

En PDO se usan metodos del `PDOStatement`, principalmente:

- `fetch()`
- `fetchAll()`
- `fetchColumn()`

Equivalencias practicas:

- MySQLi `fetch_all(MYSQLI_ASSOC)` <-> PDO `fetchAll(PDO::FETCH_ASSOC)`
- MySQLi "solo una columna de todas las filas" <-> PDO `fetchAll(PDO::FETCH_COLUMN)`

## 10) Tipos de `fetchAll` en PDO (que devuelve cada uno)

En PDO, `fetchAll()` devuelve **todas** las filas del resultado, y el formato cambia segun el modo de fetch.

### 1. `PDO::FETCH_ASSOC`

Devuelve un array de filas asociativas (clave = nombre de columna).

```php
[
    ['id' => 1, 'nombre' => 'Ana'],
    ['id' => 2, 'nombre' => 'Luis']
]
```

Es el modo mas usado en aplicaciones web.

### 2. `PDO::FETCH_NUM`

Devuelve un array de filas numericas (indices 0, 1, 2...).

```php
[
    [1, 'Ana'],
    [2, 'Luis']
]
```

### 3. `PDO::FETCH_BOTH`

Devuelve ambas representaciones en cada fila: asociativa y numerica.

```php
[
    [0 => 1, 'id' => 1, 1 => 'Ana', 'nombre' => 'Ana']
]
```

### 4. `PDO::FETCH_OBJ`

Cada fila se devuelve como objeto (`stdClass`).

```php
[
    (object) ['id' => 1, 'nombre' => 'Ana'],
    (object) ['id' => 2, 'nombre' => 'Luis']
]
```

Acceso tipico: `$fila->nombre`.

### 5. `PDO::FETCH_COLUMN`

Devuelve una lista plana con una sola columna por fila.

```php
['Ana', 'Luis', 'Marta']
```

Muy util para cargar combos/listas simples (`paises`, `nombres`, etc.).

Tambien puedes indicar la columna por indice:

```php
$valores = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);
```

### 6. `PDO::FETCH_KEY_PAIR`

La consulta debe devolver 2 columnas.
La primera se usa como clave y la segunda como valor.

```php
[
    1 => 'Ana',
    2 => 'Luis'
]
```

Muy util para mapas tipo `id => nombre`.

### 7. `PDO::FETCH_GROUP`

Agrupa por la primera columna del `SELECT`.

Ejemplo conceptual:

```php
[
    'ESP' => [ ['id' => 1, 'nombre' => 'Ana'] ],
    'FRA' => [ ['id' => 2, 'nombre' => 'Louis'] ]
]
```

### 8. `PDO::FETCH_CLASS`

Crea objetos de una clase por cada fila.
Se usa cuando quieres hidratar resultados directamente en clases del dominio.

### Resumen rapido para examen

- Listado normal de tabla: `PDO::FETCH_ASSOC`.
- Solo una columna: `PDO::FETCH_COLUMN`.
- Diccionario `id => texto`: `PDO::FETCH_KEY_PAIR`.
- Objetos: `PDO::FETCH_OBJ` o `PDO::FETCH_CLASS`.
