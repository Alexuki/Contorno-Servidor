# Debug PHP con Xdebug y Docker

## Índice
1. [Cómo funciona el debug](#cómo-funciona-el-debug)
2. [Componentes del entorno](#componentes-del-entorno)
3. [Instalación: el Dockerfile explicado](#instalación-el-dockerfile-explicado)
4. [Configuración existente](#configuración-existente)
5. [Acceder al contenedor y verificar xdebug.ini](#acceder-al-contenedor-y-verificar-xdebugini)
6. [Usar el debugger paso a paso](#usar-el-debugger-paso-a-paso)
7. [Modos de activación de Xdebug](#modos-de-activación-de-xdebug)
8. [Errores comunes](#errores-comunes)
9. [Referencia rápida de puertos](#referencia-rápida-de-puertos)

---

## Cómo funciona el debug

El debug de PHP en Docker implica tres actores que se comunican:

```
Navegador  ──HTTP──►  Apache/PHP (contenedor Docker)
                             │
                        Xdebug (extensión PHP)
                             │
                         puerto 9003
                             │
                      VS Code (host)
                      extensión PHP Debug
```

1. El navegador hace una petición HTTP al servidor web (puerto **80**).
2. PHP ejecuta el script. Xdebug está instalado como extensión de PHP.
3. Xdebug abre una conexión **saliente desde el contenedor** hacia VS Code en el puerto **9003**.
4. VS Code recibe esa conexión, lee el estado del programa y permite avanzar paso a paso.

> ⚠️ El puerto **9003 no es el servidor web**. No se navega a él. Es el canal privado Xdebug → VS Code.

---

## Componentes del entorno

| Componente | Papel |
|------------|-------|
| `Dockerfile` | Instala Xdebug dentro del contenedor PHP |
| `xdebug.ini` | Configura el comportamiento de Xdebug |
| `docker-compose.yml` | Expone el puerto 80 y añade `host.docker.internal` |
| `.vscode/launch.json` | Indica a VS Code en qué puerto escuchar y cómo mapear rutas |
| Extensión **PHP Debug** (`xdebug.php-debug`) | Integra el protocolo DAP de Xdebug en VS Code |

---

## Instalación: el Dockerfile explicado

El [Dockerfile](../../Dockerfile) define paso a paso cómo se construye la imagen del contenedor PHP+Apache. Las líneas relevantes para Xdebug son las últimas, pero conviene entender el archivo completo.

```dockerfile
FROM php:8.2.23-apache
```

**`FROM`** indica la imagen base. En este caso es la imagen oficial de PHP 8.2 con Apache integrado, publicada en Docker Hub. Todo lo que venga después se ejecuta **sobre** esa imagen.

```dockerfile
ARG DEBIAN_FRONTEND=noninteractive
```

**`ARG`** declara una variable de construcción. `DEBIAN_FRONTEND=noninteractive` le dice a `apt-get` que no muestre diálogos interactivos durante la instalación de paquetes (evita que el build se quede esperando una confirmación).

```dockerfile
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
```

**`RUN`** ejecuta un comando dentro del contenedor durante el build y guarda el resultado como una nueva capa de la imagen.  
**`docker-php-ext-install`** es un script oficial de la imagen base que compila e instala extensiones nativas de PHP:

| Extensión | Para qué sirve |
|---|---|
| `mysqli` | Conectarse a MySQL con la API MySQLi (OOP y procedural) |
| `pdo` | Capa de abstracción genérica de bases de datos |
| `pdo_mysql` | Driver de PDO específico para MySQL/MariaDB |

```dockerfile
RUN apt-get update \
    && apt-get install -y sendmail libpng-dev \
    && apt-get install -y libzip-dev \
    && apt-get install -y zlib1g-dev \
    && apt-get install -y libonig-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip
```

Un único `RUN` encadenado con `&&` para instalar dependencias del sistema operativo (librerías C necesarias para compilar extensiones PHP) y luego la extensión `zip`. El `rm -rf /var/lib/apt/lists/*` borra la caché de paquetes de apt para reducir el tamaño de la imagen.

```dockerfile
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install zip
RUN docker-php-ext-install gd
```

Más extensiones PHP: `mbstring` (cadenas multibyte/Unicode), `zip` (comprimir/descomprimir), `gd` (manipulación de imágenes).

```dockerfile
RUN a2enmod rewrite
```

**`a2enmod`** (*Apache 2 enable module*) activa el módulo `rewrite` de Apache, necesario para usar URLs amigables con `.htaccess`.

```dockerfile
RUN pecl install xdebug-3.3.2
```

**`pecl`** (*PHP Extension Community Library*) es el gestor de paquetes de extensiones PHP. A diferencia de `docker-php-ext-install` (que instala extensiones incluidas en el núcleo de PHP), `pecl` descarga e instala extensiones de terceros, como Xdebug. Se instala la versión `3.3.2` de forma explícita para garantizar reproducibilidad.

```dockerfile
ADD xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
```

**`ADD`** copia un fichero del host al interior de la imagen.  
- Origen: `xdebug.ini` en la raíz del proyecto (junto al `Dockerfile`).  
- Destino: `/usr/local/etc/php/conf.d/` — directorio especial que PHP carga automáticamente al arrancar. Cualquier fichero `.ini` colocado aquí se aplica como configuración adicional de PHP.

> **Importante:** `ADD` (y `COPY`) copian el fichero **en tiempo de build**. Si modificas `xdebug.ini` en el host después de construir la imagen, el cambio **no** se refleja en el contenedor hasta que reconstruyas:
> ```bash
> docker compose build www
> docker compose up -d
> ```

---

## Acceder al contenedor y verificar xdebug.ini

### Ver los contenedores en ejecución

```bash
docker ps
```

Muestra el nombre del contenedor (columna `NAMES`), normalmente `docker-lamp-www-1` o similar.

### Abrir una shell interactiva dentro del contenedor

```bash
docker compose exec www bash
```

`exec` ejecuta un comando en un contenedor ya en marcha. `www` es el nombre del servicio definido en `docker-compose.yml`. Tras ejecutarlo tendrás un prompt dentro del contenedor como `root@<id>:/var/www/html#`.

### Navegar al directorio de configuración de PHP

```bash
cd /usr/local/etc/php/conf.d/
ls -la
```

Verás todos los `.ini` activos, incluido `xdebug.ini`.

### Leer el contenido de xdebug.ini

```bash
cat xdebug.ini
```

Resultado esperado:

```ini
zend_extension=xdebug
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
xdebug.log=/tmp/xdebug.log
```

### Verificar que Xdebug está cargado por PHP

```bash
php -m | grep xdebug
# xdebug
```

O con más detalle:

```bash
php --ri xdebug
```

Muestra la versión instalada y todas las directivas activas.

### Salir del contenedor

```bash
exit
```

### Alternativa: ejecutar comandos sin entrar al contenedor

Si solo necesitas leer un fichero puntualmente, sin abrir una shell:

```bash
docker compose exec www cat /usr/local/etc/php/conf.d/xdebug.ini
docker compose exec www php -m | grep xdebug
docker compose exec www cat /tmp/xdebug.log   # ver el log de Xdebug
```

---

## Configuración existente

### Dockerfile

```dockerfile
# Instala la extensión Xdebug en PHP
RUN pecl install xdebug-3.3.2
ADD xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
```

Xdebug se instala en tiempo de build de la imagen. Si se cambia `xdebug.ini`, hay que reconstruir la imagen:

```bash
docker compose build
docker compose up -d
```

---

### xdebug.ini

```ini
zend_extension=xdebug
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
xdebug.log=/tmp/xdebug.log
```

| Directiva | Valor | Explicación |
|-----------|-------|-------------|
| `zend_extension` | `xdebug` | Carga la extensión en PHP |
| `xdebug.mode` | `debug` | Activa el modo de depuración paso a paso |
| `xdebug.start_with_request` | `yes` | Xdebug se activa en **todas** las peticiones |
| `xdebug.client_host` | `host.docker.internal` | DNS especial que dentro del contenedor apunta al host (tu PC) |
| `xdebug.client_port` | `9003` | Puerto donde VS Code escucha las conexiones de Xdebug |
| `xdebug.log` | `/tmp/xdebug.log` | Fichero de log dentro del contenedor para diagnosticar problemas |

---

### docker-compose.yml (fragmento relevante)

```yaml
www:
    build: .
    ports:
        - "80:80"        # Servidor web accesible en localhost:80
    extra_hosts:
        - "host.docker.internal:host-gateway"   # ← Clave para el debug
```

`extra_hosts` es imprescindible en Linux. Sin él, `host.docker.internal` no se resuelve dentro del contenedor y Xdebug no puede encontrar VS Code. En macOS y Windows Docker Desktop lo añade automáticamente, pero en Linux hay que declararlo explícitamente.

---

### .vscode/launch.json

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html": "${workspaceFolder}/www"
            }
        }
    ]
}
```

| Campo | Explicación |
|-------|-------------|
| `port` | Puerto en el que VS Code escucha conexiones de Xdebug |
| `pathMappings` | Traduce rutas del contenedor a rutas del host para que VS Code abra el archivo correcto |

El `pathMapping` es crítico: dentro del contenedor los archivos están en `/var/www/html`, pero VS Code los tiene en `./www`. Sin esta traducción, los breakpoints no se reconocen.

---

## Usar el debugger paso a paso

### 1. Arrancar el entorno Docker

```bash
docker compose up -d
```

### 2. Activar el listener en VS Code

- Pulsa **F5**, o
- Ve a **Ejecutar y depurar** (icono de la barra lateral) → selecciona *"Listen for Xdebug"* → ▶ Play

VS Code mostrará en la barra inferior el mensaje **"Listening on port 9003"** y aparecerá la barra de herramientas de debug.

### 3. Poner un breakpoint

Haz clic en el margen izquierdo de cualquier línea del archivo PHP. Aparecerá un círculo rojo.

### 4. Navegar en el navegador

```
http://localhost/ruta/al/archivo.php
```

> Recuerda: el servidor web es el puerto **80**, no el 9003.

### 5. VS Code se detiene en el breakpoint

Puedes usar los controles de debug:

| Acción | Tecla | Descripción |
|--------|-------|-------------|
| Continuar | `F5` | Ejecuta hasta el siguiente breakpoint |
| Paso a paso por encima | `F10` | Ejecuta la línea actual sin entrar en funciones |
| Paso a paso por dentro | `F11` | Entra dentro de la función que se llama |
| Salir de función | `Shift+F11` | Sale de la función actual |
| Detener | `Shift+F5` | Para el listener completamente |

En el panel izquierdo puedes ver:
- **Variables**: valor actual de todas las variables en ese punto.
- **Watch**: expresiones personalizadas que quieres vigilar.
- **Call Stack**: pila de llamadas hasta el punto actual.

---

## Modos de activación de Xdebug

### Modo `yes` (el configurado actualmente)

```ini
xdebug.start_with_request=yes
```

Xdebug intenta conectar con VS Code en **cada petición**. Si VS Code no está escuchando, la petición continúa sin debug (no bloquea).

**Pros:** cómodo, no necesita configuración extra en el navegador.  
**Contras:** añade una pequeña latencia en todas las peticiones, incluso cuando no se depura.

---

### Modo `trigger` (recomendado para desarrollo normal)

```ini
xdebug.start_with_request=trigger
```

Xdebug solo se activa cuando la petición incluye un trigger especial. Se controla con la extensión de navegador **Xdebug Helper**.

Para cambiar a este modo:

1. Edita `xdebug.ini`:
   ```ini
   xdebug.start_with_request=trigger
   ```
2. Reconstruye la imagen:
   ```bash
   docker compose build && docker compose up -d
   ```
3. Instala la extensión de navegador [Xdebug Helper](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc) (Chrome) o equivalente para Firefox.
4. Activa el modo **Debug** en la extensión (icono verde con "D") antes de navegar.

**Pros:** sin latencia cuando no se depura; control explícito.  
**Contras:** hay que acordarse de activar la extensión.

---

### Tabla comparativa de modos

| `start_with_request` | Cuándo se activa | Caso de uso |
|----------------------|-----------------|-------------|
| `yes` | Siempre | Sesión de debug intensiva |
| `trigger` | Solo con cookie/query string especial | Desarrollo normal + debug ocasional |
| `no` | Nunca (solo profiling manual) | Producción / profiling |

---

## Errores comunes

### "Failed initializing connection: connection closed"

**Causa:** se intentó navegar al puerto 9003 en el navegador.  
**Solución:** navegar a `http://localhost/...` (puerto 80), no al 9003.

---

### Los breakpoints aparecen en gris/sin verificar

**Causa:** el `pathMapping` en `launch.json` no coincide con la estructura real.  
**Solución:** verificar que `/var/www/html` corresponde exactamente a `${workspaceFolder}/www`.

```json
"pathMappings": {
    "/var/www/html": "${workspaceFolder}/www"
}
```

---

### Xdebug no conecta (petición cuelga o no hay parada)

**Posibles causas:**

1. VS Code no está en modo listener (falta pulsar F5).
2. El firewall del host bloquea el puerto 9003.
3. `host.docker.internal` no se resuelve (falta el `extra_hosts` en `docker-compose.yml`).

**Diagnóstico:** leer el log dentro del contenedor:

```bash
docker compose exec www cat /tmp/xdebug.log
```

---

### Cambios en `xdebug.ini` no tienen efecto

`xdebug.ini` se copia en la imagen durante el `build`. Cambiar el archivo en el host no basta; hay que reconstruir:

```bash
docker compose build www
docker compose up -d
```

---

## Referencia rápida de puertos

| Puerto | Protocolo | Dirección | Para qué |
|--------|-----------|-----------|----------|
| `80` | HTTP | Navegador → Contenedor | Servidor web Apache / PHP |
| `3306` | MySQL | Host → Contenedor | Base de datos |
| `8000` | HTTP | Navegador → Contenedor | phpMyAdmin |
| `9003` | DAP (Xdebug) | Contenedor → Host | Canal privado Xdebug → VS Code |
