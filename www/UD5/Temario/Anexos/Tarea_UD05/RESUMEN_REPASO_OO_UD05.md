# Resumen De Repaso UD05 (POO + Interfaces + Excepciones)

Este resumen compara el enfoque anterior (arrays y parametros sueltos) con el actual (objetos, interfaz y excepciones especificas).

## 1. Comparativa Rapida (Antes vs Despues)

| Bloque | Antes | Despues |
|---|---|---|
| Entidades | Datos en arrays asociativos | Clases `Usuario`, `Tarea`, `Fichero` |
| Encapsulacion | Campos accedidos como `$x['campo']` | Propiedades privadas + `getters/setters` |
| Alta/edicion usuarios | Parametros sueltos | `Usuario $usuario` |
| Alta/edicion tareas | Parametros sueltos | `Tarea $tarea` |
| Ficheros en BD | Funciones sueltas en `mysqli.php` | `FicherosDBInt` + `FicherosDBImp` |
| Validacion subida | Logica repartida en controlador | `Fichero::validarCampos(...)` |
| Gestion errores DB ficheros | Mensajes genericos | `DatabaseException(method, sql)` |
| Vistas/controladores | Arrays en vistas | Objetos (`$obj->get...()`) |

## 2. Clases Creadas Y Responsabilidad

| Clase | Archivo | Responsabilidad |
|---|---|---|
| `Usuario` | `modelo/Usuario.php` | Entidad de usuario (`id, username, nombre, apellidos, contrasena, rol`) |
| `Tarea` | `modelo/Tarea.php` | Entidad de tarea (`id, titulo, descripcion, estado, usuario`) |
| `Fichero` | `modelo/Fichero.php` | Entidad de fichero + validacion + constantes |
| `DatabaseException` | `modelo/DatabaseException.php` | Excepcion de acceso a BD con `method` y `sql` |
| `FicherosDBInt` | `modelo/FicherosDBInt.php` | Contrato de acceso a ficheros |
| `FicherosDBImp` | `modelo/FicherosDBImp.php` | Implementacion real de acceso a ficheros |

## 3. Firmas Clave Para Memorizar

### Usuarios (PDO)

```php
nuevoUsuario(Usuario $usuario)
actualizaUsuario(Usuario $usuario)
borraUsuario(Usuario $usuario)
buscaUsuario($id): ?Usuario
listaUsuarios(): [bool, array<Usuario>]
```

### Tareas (mysqli + PDO lista)

```php
nuevaTarea(Tarea $tarea)
actualizaTarea(Tarea $tarea)
borraTarea(Tarea $tarea)
buscaTarea($id): ?Tarea
listaTareasPDO($id_usuario = null, $estado = null): [bool, array<Tarea>]
```

### Ficheros (interfaz + implementacion)

```php
// FicherosDBInt
listaFicheros($id_tarea): array
buscaFichero($id): Fichero
borraFichero($id): bool
nuevoFichero($fichero): bool
```

## 4. Constantes Y Validacion De Fichero

En `Fichero`:

```php
public const FORMATOS = ['jpg', 'png', 'pdf'];
public const MAX_SIZE = 20971520; // 20MB
```

Validacion centralizada:

```php
Fichero::validarCampos(string $descripcion, array $upload): array
```

Devuelve array asociativo de errores. Ejemplo de claves: `descripcion`, `file`, `size`.

## 5. Flujo De Errores De BD En Ficheros

1. `FicherosDBImp` detecta error de conexion/consulta.
2. Lanza `DatabaseException` con:
   - mensaje
   - metodo (`getMethod()`)
   - SQL (`getSql()`)
3. Controlador captura y redirige con datos de error.
4. Vista de detalle (`tareas/tarea.php`) los muestra.

## 6. Cambios Tipicos En Vistas

### Antes

```php
echo $usuario['username'];
echo $tarea['titulo'];
```

### Despues

```php
echo $usuario->getUsername();
echo $tarea->getTitulo();
```

## 7. Errores Tipicos De Examen (Y Como Evitarlos)

- Olvidar `require_once` de la clase antes de usarla.
- Mantener arrays en una vista cuando el modelo ya devuelve objetos.
- Pasar parametros sueltos a un metodo que ahora exige objeto.
- No validar `$_FILES` con `UPLOAD_ERR_OK`.
- Duplicar validaciones de fichero en vez de usar `Fichero::validarCampos`.
- Capturar excepciones sin mostrar contexto minimo (`method/sql`) cuando se pide.

## 8. Chuleta De Conversion Rapida

- Crear usuario:
  - `new Usuario(0, $username, $nombre, $apellidos, $contrasena, $rol)`
- Crear tarea:
  - `new Tarea($id, $titulo, $descripcion, $estado, $idUsuario)`
- Crear fichero:
  - `new Fichero(0, $nombreOriginal, $rutaRelativa, $descripcion, $idTarea)`

## 9. Idea Clave Para Recordar

La refactorizacion mueve el proyecto de "datos sin estructura" a "modelo de dominio":

- los controladores coordinan,
- el modelo persiste,
- las entidades representan datos,
- y la parte de ficheros queda desacoplada con interfaz + implementacion.
