# Guía de Trabajo con Git - Doble Repositorio Remoto

## Configuración Inicial

### 1. Renombrar el remoto original a "upstream"
```bash
git remote rename origin upstream
```

### 2. Agregar tu repositorio de GitHub como "origin"
```bash
git remote add origin https://github.com/TU_USUARIO/TU_REPOSITORIO.git
```

### 3. Configurar la rama main para que rastree tu repositorio
```bash
git branch --set-upstream-to=origin/main main
```

### 4. Verificar la configuración de remotos
```bash
git remote -v
```

Deberías ver algo como:
```
origin    https://github.com/TU_USUARIO/TU_REPOSITORIO.git (fetch)
origin    https://github.com/TU_USUARIO/TU_REPOSITORIO.git (push)
upstream  https://gitlab.com/ORIGINAL/PROYECTO.git (fetch)
upstream  https://gitlab.com/ORIGINAL/PROYECTO.git (push)
```

---

## Configuración de Usuario Git (si es necesario)

```bash
git config --global user.name "Tu Nombre"
git config --global user.email "tu_email@ejemplo.com"
```

---

## Flujo de Trabajo Diario

### Trabajar con TU repositorio (origin)

#### Subir cambios a tu GitHub
```bash
git add .
git commit -m "Descripción de los cambios"
git push                    # O: git push origin main
```

#### Traer cambios de tu GitHub
```bash
git pull                    # O: git pull origin main
```

---

## Sincronizar con el Repositorio Original (upstream)

### Traer cambios del repositorio original
```bash
git pull upstream main
```

### Sincronizar tu repositorio con los cambios traídos
```bash
git push origin main
```

---

## Comandos Útiles

### Ver el estado del repositorio
```bash
git status
```

### Ver historial de commits
```bash
git log --oneline
```

### Ver a qué remoto está vinculada tu rama
```bash
git branch -vv
```

### Ver diferencias antes de commit
```bash
git diff
```

---

## Flujo Completo Recomendado

1. **Antes de empezar a trabajar** (mantener actualizado):
   ```bash
   git pull upstream main    # Traer cambios del original
   git push origin main      # Actualizar tu GitHub
   ```

2. **Trabajar en tus cambios**:
   ```bash
   # ... modificas archivos ...
   git add .
   git commit -m "Descripción clara de los cambios"
   ```

3. **Subir tus cambios**:
   ```bash
   git push                  # Sube a tu GitHub
   ```

4. **Periódicamente sincronizar** (cada semana o cuando sepas que hay actualizaciones):
   ```bash
   git pull upstream main    # Traer del original
   git push                  # Actualizar tu GitHub
   ```

---

## Resumen de Remotos

| Remoto | Descripción | Uso Principal |
|--------|-------------|---------------|
| `origin` | Tu repositorio en GitHub | Push/Pull diario |
| `upstream` | Repositorio original | Pull para actualizaciones |

---

## Notas Importantes

- ⚠️ **Siempre haz commit de tus cambios antes de hacer pull** del repositorio original
- 💡 Si hay conflictos al hacer `git pull upstream main`, Git te pedirá que los resuelvas manualmente
- 🔄 El comando `git pull` es equivalente a `git fetch` + `git merge`
- 📝 Usa mensajes de commit descriptivos y en presente: "Añade funcionalidad X" en lugar de "Añadí X"

---

## Ejemplo Práctico

```bash
# Día 1: Configuración inicial
git remote rename origin upstream
git remote add origin https://github.com/Alexuki/Contorno-Servidor.git
git branch --set-upstream-to=origin/main main

# Día 2: Trabajo normal
git add .
git commit -m "Añade ejercicios de la UD4"
git push

# Día 3: Sincronizar con el original
git pull upstream main
git push

# Continuar trabajando...
```
