# SQL JOINS - Guía Completa

## Índice
1. [Conceptos básicos de JOIN](#conceptos-básicos-de-join)
2. [Tipos de JOIN](#tipos-de-join)
3. [Condiciones en ON vs WHERE](#condiciones-en-on-vs-where)
4. [Ejemplos prácticos](#ejemplos-prácticos)
5. [Tablas de comparación](#tablas-de-comparación)
6. [Mejores prácticas](#mejores-prácticas)

---

## Conceptos básicos de JOIN

### ¿Qué es un JOIN?

Un **JOIN** combina filas de dos o más tablas basándose en una columna relacionada entre ellas.

### Tablas de ejemplo

Usaremos estas dos tablas simples para todos los ejemplos:

**Tabla: `clientes`**
```
id | nombre   | ciudad
---|----------|--------
1  | Ana      | Madrid
2  | Juan     | Barcelona
3  | María    | Valencia
4  | Pedro    | Sevilla
```

**Tabla: `pedidos`**
```
id | cliente_id | producto    | precio
---|------------|-------------|-------
1  | 1          | Laptop      | 1000
2  | 1          | Mouse       | 20
3  | 2          | Teclado     | 50
4  | 5          | Monitor     | 300
```

**Observa:**
- Ana (id=1) tiene 2 pedidos
- Juan (id=2) tiene 1 pedido
- María (id=3) no tiene pedidos
- Pedro (id=4) no tiene pedidos
- Hay un pedido (id=4) con cliente_id=5 que no existe en la tabla clientes

---

## Tipos de JOIN

### 1. INNER JOIN

**Devuelve solo las filas que tienen coincidencias en ambas tablas.**

```sql
SELECT c.nombre, p.producto, p.precio
FROM clientes c
INNER JOIN pedidos p ON c.id = p.cliente_id
```

**Resultado:**
```
nombre | producto  | precio
-------|-----------|-------
Ana    | Laptop    | 1000
Ana    | Mouse     | 20
Juan   | Teclado   | 50
```

**Explicación:**
- ✅ Ana y Juan aparecen porque tienen pedidos
- ❌ María y Pedro NO aparecen (no tienen pedidos)
- ❌ El pedido del cliente_id=5 NO aparece (cliente no existe)

**Diagrama:**
```
Clientes          Pedidos
   ┌─────┐     ┌─────┐
   │     │     │     │
   │  ┌──┼─────┼──┐  │
   │  │  │     │  │  │
   └──┼──┘     └──┼──┘
      │           │
      └───────────┘
    Solo esta parte
```

---

### 2. LEFT JOIN (LEFT OUTER JOIN)

**Devuelve todas las filas de la tabla izquierda, y las coincidencias de la derecha. Si no hay coincidencia, NULL.**

```sql
SELECT c.nombre, p.producto, p.precio
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id
```

**Resultado:**
```
nombre | producto  | precio
-------|-----------|-------
Ana    | Laptop    | 1000
Ana    | Mouse     | 20
Juan   | Teclado   | 50
María  | NULL      | NULL
Pedro  | NULL      | NULL
```

**Explicación:**
- ✅ Todos los clientes aparecen
- ✅ Ana y Juan tienen sus pedidos
- ✅ María y Pedro aparecen con NULL (no tienen pedidos)
- ❌ El pedido del cliente_id=5 NO aparece (está en la tabla derecha)

**Diagrama:**
```
Clientes          Pedidos
   ┌─────┐     ┌─────┐
   │     │     │     │
   │  ┌──┼─────┼──┐  │
   │  │  │     │  │  │
   └──┼──┘     └──┼──┘
      │           
 Toda la izquierda
```

---

### 3. RIGHT JOIN (RIGHT OUTER JOIN)

**Devuelve todas las filas de la tabla derecha, y las coincidencias de la izquierda. Si no hay coincidencia, NULL.**

```sql
SELECT c.nombre, p.producto, p.precio
FROM clientes c
RIGHT JOIN pedidos p ON c.id = p.cliente_id
```

**Resultado:**
```
nombre | producto  | precio
-------|-----------|-------
Ana    | Laptop    | 1000
Ana    | Mouse     | 20
Juan   | Teclado   | 50
NULL   | Monitor   | 300
```

**Explicación:**
- ✅ Todos los pedidos aparecen
- ✅ Ana y Juan aparecen con sus pedidos
- ❌ María y Pedro NO aparecen (no tienen pedidos)
- ✅ El pedido del cliente_id=5 aparece con nombre NULL (cliente no existe)

**Diagrama:**
```
Clientes          Pedidos
   ┌─────┐     ┌─────┐
   │     │     │     │
   │  ┌──┼─────┼──┐  │
   │  │  │     │  │  │
   └──┼──┘     └──┼──┘
                  │
           Toda la derecha
```

---

### 4. FULL OUTER JOIN

**Devuelve todas las filas cuando hay coincidencia en cualquiera de las tablas. NULL donde no hay coincidencia.**

⚠️ **Nota:** MySQL no soporta FULL OUTER JOIN directamente, pero se puede simular con UNION.

```sql
-- Simulación en MySQL
SELECT c.nombre, p.producto, p.precio
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id

UNION

SELECT c.nombre, p.producto, p.precio
FROM clientes c
RIGHT JOIN pedidos p ON c.id = p.cliente_id
```

**Resultado:**
```
nombre | producto  | precio
-------|-----------|-------
Ana    | Laptop    | 1000
Ana    | Mouse     | 20
Juan   | Teclado   | 50
María  | NULL      | NULL
Pedro  | NULL      | NULL
NULL   | Monitor   | 300
```

**Explicación:**
- ✅ Todos los clientes aparecen (con o sin pedidos)
- ✅ Todos los pedidos aparecen (con o sin cliente)

**Diagrama:**
```
Clientes          Pedidos
   ┌─────┐     ┌─────┐
   │     │     │     │
   │  ┌──┼─────┼──┐  │
   │  │  │     │  │  │
   └──┼──┘     └──┼──┘
      │           │
 Todo lo de ambas tablas
```

---

### 5. CROSS JOIN

**Devuelve el producto cartesiano: cada fila de la primera tabla con cada fila de la segunda.**

```sql
SELECT c.nombre, p.producto
FROM clientes c
CROSS JOIN pedidos p
```

**Resultado:**
```
nombre | producto
-------|----------
Ana    | Laptop
Ana    | Mouse
Ana    | Teclado
Ana    | Monitor
Juan   | Laptop
Juan   | Mouse
Juan   | Teclado
Juan   | Monitor
María  | Laptop
María  | Mouse
...
(4 clientes × 4 pedidos = 16 filas)
```

**Explicación:**
- Cada cliente se combina con cada pedido
- Útil en casos específicos (generar combinaciones)
- ⚠️ Puede generar resultados enormes

---

## Condiciones en ON vs WHERE

### Diferencia fundamental

- **`ON`**: Define cómo se unen las tablas (condición de join)
- **`WHERE`**: Filtra el resultado después del join

---

### Caso 1: Filtrar un cliente específico

#### ❌ Condición en ON (INCORRECTO para filtrar)

```sql
SELECT c.nombre, p.producto, p.precio
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id AND c.id = 1
```

**Resultado:**
```
nombre | producto  | precio
-------|-----------|-------
Ana    | Laptop    | 1000
Ana    | Mouse     | 20
Juan   | NULL      | NULL     ← Aparece aunque no queremos
María  | NULL      | NULL     ← Aparece aunque no queremos
Pedro  | NULL      | NULL     ← Aparece aunque no queremos
```

**¿Por qué?**
- La condición `c.id = 1` en el `ON` solo afecta al JOIN
- LEFT JOIN devuelve **todos los clientes**
- Solo Ana tiene pedidos unidos, los demás tienen NULL

---

#### ✅ Condición en WHERE (CORRECTO para filtrar)

```sql
SELECT c.nombre, p.producto, p.precio
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id
WHERE c.id = 1
```

**Resultado:**
```
nombre | producto  | precio
-------|-----------|-------
Ana    | Laptop    | 1000
Ana    | Mouse     | 20
```

**¿Por qué?**
- El JOIN se hace normal (Ana con sus pedidos, otros con NULL)
- Luego `WHERE` filtra y solo deja Ana
- ✅ Resultado correcto

---

### Caso 2: Filtrar pedidos caros (precio > 100)

#### Con INNER JOIN

```sql
-- Condición en ON
SELECT c.nombre, p.producto, p.precio
FROM clientes c
INNER JOIN pedidos p ON c.id = p.cliente_id AND p.precio > 100
```

**Resultado:**
```
nombre | producto | precio
-------|----------|-------
Ana    | Laptop   | 1000
```

```sql
-- Condición en WHERE
SELECT c.nombre, p.producto, p.precio
FROM clientes c
INNER JOIN pedidos p ON c.id = p.cliente_id
WHERE p.precio > 100
```

**Resultado:**
```
nombre | producto | precio
-------|----------|-------
Ana    | Laptop   | 1000
```

**Con INNER JOIN, ambas formas dan el mismo resultado.**

---

#### Con LEFT JOIN (DIFERENCIA IMPORTANTE)

```sql
-- Condición en ON
SELECT c.nombre, p.producto, p.precio
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id AND p.precio > 100
```

**Resultado:**
```
nombre | producto | precio
-------|----------|-------
Ana    | Laptop   | 1000
Ana    | NULL     | NULL     ← Mouse no cumple condición
Juan   | NULL     | NULL     ← Teclado no cumple condición
María  | NULL     | NULL     ← Sin pedidos
Pedro  | NULL     | NULL     ← Sin pedidos
```

```sql
-- Condición en WHERE
SELECT c.nombre, p.producto, p.precio
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id
WHERE p.precio > 100
```

**Resultado:**
```
nombre | producto | precio
-------|----------|-------
Ana    | Laptop   | 1000
```

**Con LEFT JOIN:**
- `ON`: Filtra qué filas se unen, pero mantiene todos los registros de la izquierda
- `WHERE`: Filtra el resultado final, eliminando filas con NULL

---

### Regla práctica

```sql
-- Para filtrar la tabla PRINCIPAL (izquierda)
FROM tabla_principal
LEFT JOIN tabla_secundaria ON condicion_join
WHERE tabla_principal.columna = valor  -- ✅ Usa WHERE

-- Para filtrar qué se une de la tabla SECUNDARIA (derecha)
FROM tabla_principal
LEFT JOIN tabla_secundaria 
  ON condicion_join 
  AND tabla_secundaria.columna = valor  -- ✅ Usa ON
```

---

## Ejemplos prácticos

### Ejemplo 1: Clientes con y sin pedidos

**Objetivo:** Mostrar todos los clientes, indicando cuántos pedidos tienen.

```sql
SELECT 
    c.id,
    c.nombre,
    COUNT(p.id) as total_pedidos
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id
GROUP BY c.id, c.nombre
```

**Resultado:**
```
id | nombre | total_pedidos
---|--------|---------------
1  | Ana    | 2
2  | Juan   | 1
3  | María  | 0
4  | Pedro  | 0
```

---

### Ejemplo 2: Solo clientes sin pedidos

```sql
SELECT c.nombre
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id
WHERE p.id IS NULL
```

**Resultado:**
```
nombre
-------
María
Pedro
```

**Explicación:** El LEFT JOIN trae todos los clientes. Los que no tienen pedidos tienen `p.id = NULL`.

---

### Ejemplo 3: Clientes que han gastado más de 500€

```sql
SELECT 
    c.nombre,
    SUM(p.precio) as total_gastado
FROM clientes c
INNER JOIN pedidos p ON c.id = p.cliente_id
GROUP BY c.id, c.nombre
HAVING SUM(p.precio) > 500
```

**Resultado:**
```
nombre | total_gastado
-------|---------------
Ana    | 1020
```

---

### Ejemplo 4: Pedidos recientes con información del cliente

```sql
SELECT 
    c.nombre,
    c.ciudad,
    p.producto,
    p.precio
FROM pedidos p
LEFT JOIN clientes c ON p.cliente_id = c.id
WHERE p.precio > 50
ORDER BY p.precio DESC
```

**Resultado:**
```
nombre | ciudad    | producto | precio
-------|-----------|----------|-------
Ana    | Madrid    | Laptop   | 1000
NULL   | NULL      | Monitor  | 300
```

---

### Ejemplo 5: Clientes de Madrid con sus pedidos

#### ❌ Incorrecto (condición en ON)

```sql
SELECT c.nombre, c.ciudad, p.producto
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id AND c.ciudad = 'Madrid'
```

**Resultado:**
```
nombre | ciudad    | producto
-------|-----------|----------
Ana    | Madrid    | Laptop
Ana    | Madrid    | Mouse
Juan   | Barcelona | NULL      ← Aparece pero no es de Madrid
María  | Valencia  | NULL      ← Aparece pero no es de Valencia
Pedro  | Sevilla   | NULL      ← Aparece pero no es de Sevilla
```

#### ✅ Correcto (condición en WHERE)

```sql
SELECT c.nombre, c.ciudad, p.producto
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id
WHERE c.ciudad = 'Madrid'
```

**Resultado:**
```
nombre | ciudad  | producto
-------|---------|----------
Ana    | Madrid  | Laptop
Ana    | Madrid  | Mouse
```

---

## Tablas de comparación

### Tipos de JOIN

| JOIN | Filas de tabla izquierda | Filas de tabla derecha | Coincidencias requeridas |
|------|--------------------------|------------------------|--------------------------|
| **INNER JOIN** | Solo con coincidencia | Solo con coincidencia | ✅ Sí |
| **LEFT JOIN** | ✅ Todas | Solo con coincidencia | ❌ No |
| **RIGHT JOIN** | Solo con coincidencia | ✅ Todas | ❌ No |
| **FULL OUTER JOIN** | ✅ Todas | ✅ Todas | ❌ No |
| **CROSS JOIN** | ✅ Todas | ✅ Todas | ❌ No (cartesiano) |

---

### ON vs WHERE

| Aspecto | Condición en ON | Condición en WHERE |
|---------|-----------------|-------------------|
| **INNER JOIN** | Mismo resultado | Mismo resultado |
| **LEFT JOIN** | Afecta qué se une | Filtra resultado final |
| **Mantiene filas NULL** | ✅ Sí | ❌ No (las elimina) |
| **Uso principal** | Condiciones de join | Filtros del resultado |
| **Rendimiento** | Similar | Similar |

---

### Comparación visual con ejemplo

**Consulta:** Clientes con pedidos > 100€

| Tipo de consulta | Ana (2 pedidos) | Juan (1 pedido <100) | María (sin pedidos) |
|------------------|-----------------|----------------------|---------------------|
| **INNER JOIN + ON** | ✅ 1 fila (Laptop) | ❌ No aparece | ❌ No aparece |
| **INNER JOIN + WHERE** | ✅ 1 fila (Laptop) | ❌ No aparece | ❌ No aparece |
| **LEFT JOIN + ON** | ✅ 2 filas (Laptop + NULL) | ✅ 1 fila (NULL) | ✅ 1 fila (NULL) |
| **LEFT JOIN + WHERE** | ✅ 1 fila (Laptop) | ❌ No aparece | ❌ No aparece |

---

### Cuándo usar cada JOIN

| Necesitas | JOIN a usar |
|-----------|-------------|
| Solo coincidencias | `INNER JOIN` |
| Todos los registros de tabla A (con o sin relación) | `LEFT JOIN` |
| Todos los registros de tabla B (con o sin relación) | `RIGHT JOIN` |
| Todos los registros de ambas tablas | `FULL OUTER JOIN` |
| Todas las combinaciones posibles | `CROSS JOIN` |

---

### Condición en ON vs WHERE: Regla rápida

| Quieres | Dónde poner la condición |
|---------|-------------------------|
| Filtrar tabla principal (izquierda) | `WHERE` |
| Filtrar tabla secundaria (derecha) en LEFT JOIN | `ON` (para mantener NULLs) o `WHERE` (para eliminar) |
| Filtrar en INNER JOIN | `ON` o `WHERE` (es lo mismo) |
| Condición de relación entre tablas | `ON` |

---

## Mejores prácticas

### ✅ Hacer

#### 1. Usar alias de tabla para claridad

```sql
-- ✅ Claro
SELECT c.nombre, p.producto
FROM clientes c
INNER JOIN pedidos p ON c.id = p.cliente_id

-- ❌ Confuso
SELECT nombre, producto
FROM clientes
INNER JOIN pedidos ON id = cliente_id
```

#### 2. Condiciones de join en ON, filtros en WHERE

```sql
-- ✅ Correcto
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id
WHERE c.ciudad = 'Madrid'

-- ❌ Confuso
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id AND c.ciudad = 'Madrid'
```

#### 3. Especificar el tipo de JOIN explícitamente

```sql
-- ✅ Explícito
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id

-- ❌ Implícito (sintaxis antigua)
FROM clientes c, pedidos p
WHERE c.id = p.cliente_id
```

#### 4. Usar LEFT JOIN cuando necesites todos los registros principales

```sql
-- Para ver TODOS los clientes (con o sin pedidos)
SELECT c.nombre, COUNT(p.id) as total_pedidos
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id
GROUP BY c.id
```

#### 5. Validar datos antes de hacer JOIN

```sql
-- ✅ Asegurar que los datos existen
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id
WHERE c.id = :id  -- Validado previamente en PHP
```

---

### ❌ Evitar

#### 1. Poner filtros de la tabla principal en ON con LEFT JOIN

```sql
-- ❌ Trae todos los clientes aunque no sean de Madrid
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id AND c.ciudad = 'Madrid'

-- ✅ Solo trae clientes de Madrid
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id
WHERE c.ciudad = 'Madrid'
```

#### 2. Usar RIGHT JOIN (usa LEFT JOIN invirtiendo las tablas)

```sql
-- ❌ Menos intuitivo
FROM clientes c
RIGHT JOIN pedidos p ON c.id = p.cliente_id

-- ✅ Más claro
FROM pedidos p
LEFT JOIN clientes c ON p.cliente_id = c.id
```

#### 3. CROSS JOIN sin intención

```sql
-- ❌ Olvidas el ON (se convierte en CROSS JOIN)
FROM clientes c
JOIN pedidos p

-- ✅ Con condición
FROM clientes c
JOIN pedidos p ON c.id = p.cliente_id
```

#### 4. Concatenar valores en consultas JOIN

```sql
-- ❌ Vulnerable a SQL injection
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id AND c.id = $id

-- ✅ Usar prepared statements
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id
WHERE c.id = :id
```

---

## Resumen

### Tipos de JOIN

```sql
-- INNER JOIN: Solo coincidencias
SELECT * FROM A INNER JOIN B ON A.id = B.a_id

-- LEFT JOIN: Todos de A, coincidencias de B
SELECT * FROM A LEFT JOIN B ON A.id = B.a_id

-- RIGHT JOIN: Coincidencias de A, todos de B
SELECT * FROM A RIGHT JOIN B ON A.id = B.a_id

-- FULL OUTER JOIN: Todos de ambas (simulado en MySQL)
SELECT * FROM A LEFT JOIN B ON A.id = B.a_id
UNION
SELECT * FROM A RIGHT JOIN B ON A.id = B.a_id
```

---

### ON vs WHERE

```sql
-- ON: Define cómo unir tablas
-- WHERE: Filtra el resultado

-- Ejemplo LEFT JOIN:

-- Esto mantiene todos los clientes (con p.precio NULL si no cumplen)
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id AND p.precio > 100

-- Esto solo muestra clientes que tienen pedidos > 100
FROM clientes c
LEFT JOIN pedidos p ON c.id = p.cliente_id
WHERE p.precio > 100
```

---

### Regla de oro

- **INNER JOIN**: `ON` y `WHERE` son equivalentes
- **LEFT/RIGHT JOIN**: 
  - `ON` → Afecta qué se une (mantiene filas con NULL)
  - `WHERE` → Filtra resultado final (elimina filas con NULL)

---

## Referencias

- [MySQL JOIN Documentation](https://dev.mysql.com/doc/refman/8.0/en/join.html)
- [SQL JOIN Visual Explanation](https://blog.codinghorror.com/a-visual-explanation-of-sql-joins/)
- [W3Schools SQL Joins](https://www.w3schools.com/sql/sql_join.asp)
