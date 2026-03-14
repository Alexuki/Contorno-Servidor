# Permisos de Ficheros en PHP con Docker

## El problema

Cuando PHP intenta crear o escribir un fichero con `fopen($ruta, "w")`, el sistema operativo comprueba si el **usuario que ejecuta el proceso** tiene permiso de escritura en el directorio destino.

En un entorno Docker con Apache+PHP, ese usuario es normalmente **`www-data`**, no tu usuario personal del sistema anfitrión.

Si el directorio fue creado por tu usuario (`alex`), los permisos típicos son:

```
drwxr-xr-x  alex  alex  ficheros/
```

Desglosado:

| Quién  | Permisos | Significado             |
|--------|----------|-------------------------|
| Propietario (`alex`) | `rwx` | Lectura, escritura y ejecución |
| Grupo (`alex`)       | `r-x` | Lectura y ejecución (sin escritura) |
| Otros (`www-data`)   | `r-x` | Lectura y ejecución (sin escritura) |

`www-data` cae en la categoría **otros**, por lo que **no puede escribir** → `fopen` falla y se ejecuta el `die()`.

---

## La solución

Dar permiso de escritura a **otros** (`o`) en el directorio:

```bash
chmod o+w ruta/al/directorio/
```

El resultado queda así:

```
drwxr-xrwx  alex  alex  ficheros/
```

Ahora `www-data` (y cualquier otro usuario) puede crear y modificar ficheros en ese directorio.

---

## Entendiendo `chmod`

```
chmod  [quién][operación][permiso]  ruta
```

| Quién | Descripción               |
|-------|---------------------------|
| `u`   | Usuario propietario       |
| `g`   | Grupo propietario         |
| `o`   | Otros (resto de usuarios) |
| `a`   | Todos (u + g + o)         |

| Operación | Descripción  |
|-----------|--------------|
| `+`       | Añadir       |
| `-`       | Quitar       |
| `=`       | Asignar      |

| Permiso | Descripción  |
|---------|--------------|
| `r`     | Lectura      |
| `w`     | Escritura    |
| `x`     | Ejecución    |

### Ejemplos comunes

```bash
chmod o+w directorio/      # Dar escritura a otros
chmod o-w directorio/      # Quitar escritura a otros
chmod 777 directorio/      # Todos los permisos a todos (evitar en producción)
chmod -R o+w directorio/   # Aplicar recursivamente a subdirectorios
```

---

## Permisos en producción vs desarrollo

| Entorno     | Recomendación                                                     |
|-------------|-------------------------------------------------------------------|
| Desarrollo  | `chmod o+w` es suficiente y cómodo                               |
| Producción  | Usar `chown www-data:www-data directorio/` para que el propietario sea directamente `www-data` |

En producción es preferible cambiar el propietario del directorio a `www-data` en lugar de abrir permisos a todos:

```bash
chown www-data:www-data ruta/al/directorio/
chmod 755 ruta/al/directorio/
```

---

## Verificar permisos

```bash
ls -la ruta/al/directorio/
```

También se puede comprobar desde PHP qué usuario está ejecutando el servidor:

```php
echo exec('whoami');  // Normalmente muestra: www-data
```

---

## Quién es `www-data`

`www-data` es el usuario del sistema operativo bajo el que corre el servidor web (Apache o Nginx) dentro del contenedor Docker. No es un usuario humano, sino una **cuenta de servicio** creada específicamente para que el servidor web tenga los mínimos privilegios necesarios.

- No tiene contraseña ni shell de login.
- Su UID es normalmente `33` en sistemas Debian/Ubuntu.
- Existe dentro del contenedor, pero también en el sistema anfitrión (Linux lo crea automáticamente al instalar Apache).
- Por eso, cuando PHP sube o crea un fichero, el propietario en el host aparece como `www-data`.

```bash
# Ver sus datos en el sistema anfitrión
id www-data
# uid=33(www-data) gid=33(www-data) groups=33(www-data)
```

---

## Problema: no puedo borrar archivos subidos desde el host

Cuando PHP sube un archivo con `move_uploaded_file()`, el fichero resultante pertenece a `www-data`:

```
-rw-r--r--  1 www-data  www-data  37508  00 manzana.jpg
```

Tu usuario (`alex`) no es el propietario, por lo que el gestor de archivos o el terminal te deniegan el borrado.

### Solución 1 — Borrar puntualmente con `sudo`

```bash
sudo rm "ruta/al/fichero"
```

Útil para casos puntuales sin cambiar configuración del sistema.

### Solución 2 — Añadir tu usuario al grupo `www-data` (recomendada para desarrollo)

```bash
sudo usermod -aG www-data alex
```

Después **cierra sesión y vuelve a entrar** (o reinicia) para que el cambio surta efecto. Verifica con:

```bash
groups alex
# alex www-data ...
```

A partir de ese momento tu usuario pertenece al grupo `www-data` y puede gestionar los archivos creados por el servidor web.

> **Nota:** esta solución solo afecta al sistema anfitrión. Dentro del contenedor el comportamiento de PHP no cambia.

### Solución 3 — Cambiar el propietario del directorio `uploads/` (entornos de producción)

```bash
sudo chown -R www-data:www-data ruta/uploads/
sudo chmod 755 ruta/uploads/
```

Con esto `www-data` es propietario y puede escribir, pero tú como `alex` solo podrás borrar ficheros si usas `sudo` o si aplicas la Solución 2.

---

## Resumen comparativo de soluciones

| Solución | Cuándo usarla | Inconveniente |
|---|---|---|
| `sudo rm` | Borrado puntual | Hay que hacerlo siempre con `sudo` |
| `usermod -aG www-data alex` | Desarrollo local | Requiere cerrar sesión; da al usuario acceso al grupo `www-data` |
| `chown www-data` en `uploads/` | Producción | El usuario no puede borrar sin `sudo` |

---

## Qué es la umask

La **umask** (*user file creation mask*) es una máscara que el sistema operativo **resta automáticamente** a los permisos solicitados cada vez que se crea un fichero o directorio. Es un mecanismo de seguridad para evitar que los recursos nuevos tengan más permisos de los necesarios.

### Cómo funciona

Los permisos reales que obtiene un recurso son:

```
permisos_reales = permisos_solicitados - umask
```

Con la umask más habitual en sistemas Linux (`0022`):

| Solicitado | Umask | Resultado real |
|---|---|---|
| `0777` (rwxrwxrwx) | `0022` | `0755` (rwxr-xr-x) |
| `0666` (rw-rw-rw-) | `0022` | `0644` (rw-r--r--) |

Por eso `mkdir($dir, 0777)` en PHP produce un directorio `drwxr-xr-x` y no `drwxrwxrwx`: la umask del proceso Apache recorta los permisos.

### `mkdir` vs `chmod` en PHP

`mkdir()` **respeta la umask**, pero `chmod()` **no**. La solución para garantizar los permisos exactos es llamar a `chmod()` justo después de `mkdir()`:

```php
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
    chmod($target_dir, 0755); // fuerza los permisos reales, ignora la umask
}
```

### Ver la umask actual

```bash
umask          # muestra en octal, ej: 0022
umask -S       # muestra en simbólico, ej: u=rwx,g=rx,o=rx
```

Dentro del contenedor Docker:

```bash
docker exec -it <nombre_contenedor> sh -c "umask"
```

### Cambiar la umask temporalmente (solo sesión actual)

```bash
umask 0002    # ahora otros del grupo también pueden escribir
umask 0022    # vuelve al valor habitual
```

### Cambiar la umask de forma permanente

Añade la línea al fichero de perfil del usuario (`~/.bashrc`, `~/.profile` o `/etc/profile` para todos los usuarios):

```bash
echo "umask 0002" >> ~/.bashrc
source ~/.bashrc
```

> **Nota para Docker:** la umask se hereda del proceso padre. Si el contenedor tiene umask `0022`, `mkdir()` en PHP siempre producirá `755` aunque pidas `777`. Usa `chmod()` después de `mkdir()` para evitar sorpresas.
