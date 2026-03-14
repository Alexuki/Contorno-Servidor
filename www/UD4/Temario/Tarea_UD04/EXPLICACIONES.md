# Explicaciones Tarea_UD04

Este documento recopila dudas y aclaraciones que vayan surgiendo durante el desarrollo de `Tarea_UD04`.

## 1) Por que se comprueba `session_status()` antes de `session_start()`

En `auth.php` se usa:

```php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
```

### Motivo

`auth.php` se incluye desde muchas paginas. Esta comprobacion evita intentar iniciar la sesion mas de una vez cuando ya esta activa.

### Que pasa si se llama siempre a `session_start()`

En un proyecto con multiples includes, puede provocar avisos o comportamiento no deseado, por ejemplo:

- `session_start(): Ignoring session_start() because a session is already active`
- problemas de cabeceras si se intenta iniciar sesion demasiado tarde

### Regla practica

- Si tienes un unico punto de entrada y controlas que se llame una sola vez al principio, `session_start()` directo puede valer.
- Si tienes paginas PHP independientes con includes compartidos (como este proyecto), es mejor protegerlo con `session_status()`.

---

## 2) "Hay que llamar siempre a session_start" vs usar session_status

La recomendacion habitual de PHP es correcta: en cada peticion donde vayas a usar `$_SESSION`, la sesion debe estar iniciada.

Eso no significa que haya que ejecutar `session_start()` de forma ciega varias veces en la misma peticion.

En este proyecto usamos:

```php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
```

Este patron es compatible con la recomendacion porque:

- Si la sesion no esta activa, la inicia.
- Si ya esta activa, evita una segunda llamada innecesaria.
- Despues de ese bloque, `$_SESSION` queda disponible para leer/escribir.

Por tanto, el enfoque actual de `auth.php` es correcto y robusto para un proyecto con multiples includes.
