# Carga de clases y require_once en PHP

Guia rapida para entender por que a veces una clase necesita `require_once` en un archivo y otras veces no.

## Idea principal

PHP no carga clases automaticamente (salvo que configures autoload). Por eso, una clase debe estar cargada antes de usarse.

En proyectos sin autoload, esto se resuelve con `require_once`.

## Diferencia entre pagina y clase de modelo

- Un archivo como `index.php` es un punto de entrada: se ejecuta directamente al abrir la URL.
- Un archivo como `models/DeportistaRepository.php` suele definir una clase reutilizable.

Por eso, normalmente:
- El punto de entrada carga las dependencias.
- Las clases de modelo asumen que sus dependencias ya fueron cargadas por quien las usa.

## Caso real de villa_olimpica

En `www/anxo/UD5/villa_olimpica/index.php` se hace:

```php
require_once 'config/Database.php';
require_once 'models/Deportista.php';
require_once 'models/Esquiador.php';
require_once 'models/Patinador.php';
require_once 'models/Saltador.php';
require_once 'models/DeportistaRepository.php';
```

Despues se crea el repositorio:

```php
$repo = new DeportistaRepository();
```

Y en el constructor de `DeportistaRepository` se usa:

```php
$this->conn = Database::getConnection();
```

Esto funciona porque `Database.php` ya fue cargado antes en `index.php`.

## Entonces, por que en el repositorio no hace falta require_once de Database.php

Porque ese archivo no se ejecuta solo en este flujo; se incluye desde una pagina que ya cargo `Database.php`.

## Cuando si fallaria

Fallaria si otro script hiciera esto:

```php
require_once 'models/DeportistaRepository.php';
$repo = new DeportistaRepository();
```

sin haber cargado antes `config/Database.php`.

El error tipico seria:

```text
Fatal error: Uncaught Error: Class "Database" not found
```

## Buenas practicas

- Centralizar la carga en un `bootstrap.php` para evitar repetir `require_once` en cada pagina.
- Mantener un orden de carga coherente (configuracion, modelos base, modelos hijos, repositorios).
- Usar `require_once` en vez de `require` cuando puede haber dobles inclusiones.
- Si el proyecto crece, pasar a autoload (por ejemplo con Composer).

## Mini plantilla de bootstrap

```php
<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Deportista.php';
require_once __DIR__ . '/models/Esquiador.php';
require_once __DIR__ . '/models/Patinador.php';
require_once __DIR__ . '/models/Saltador.php';
require_once __DIR__ . '/models/DeportistaRepository.php';
```

Y en cada pagina:

```php
require_once __DIR__ . '/bootstrap.php';
```
