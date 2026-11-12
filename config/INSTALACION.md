# ==========================================================
# LectoEscritura (EVAL) — instrucciones de entorno local
# ==========================================================

## 1. Requisitos

- XAMPP (Apache + PHP 8). En esta máquina: `C:\xampp`
- Node.js (solo opcional, para herramientas)
- Extensión PHP `fileinfo` habilitada (necesaria para validar PDFs)

## 2. Configuración

1. Copiar la plantilla de entorno y completarla:

   ```
   copy .env.example .env
   ```

   `.env` **no** se versiona. Ya existe un `.env` funcional en este equipo.

2. El servidor MySQL de Azure exige dos cosas:
   - **SSL** (ya está forzado en `config/conexion.php`).
   - **Allowlist de IP**: hay que agregar la IP pública de quien se conecta
     en *Azure → MySQL flexible server → Networking → Firewall rules*.
     La IP pública actual de esta máquina es `190.67.226.48` (cambia con
     la red WiFi; se consulta en https://api.ipify.org).

   Si la IP no está en el allowlist, la app responde:
   `"No hay conexión con la base de datos..."` tras ~5 segundos.

## 3. Levantar la app

### Opción A — Apache (recomendada, replica el hosting real)

1. Copiar el VirtualHost incluido en este repo a la config de Apache:

   ```
   copy config\httpd-lectoescritura.conf C:\xampp\apache\conf\extra\lectoescritura.conf
   ```

2. Agregar al final de `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

   ```
   Include conf/extra/lectoescritura.conf
   ```

3. Reiniciar Apache desde el panel de XAMPP.

4. Abrir `http://lectoescritura.local/views/login.html`.
   Si el dominio no resuelve, agregarlo al archivo
   `C:\Windows\System32\drivers\etc\hosts`:
   `127.0.0.1 lectoescritura.local`

### Opción B — Servidor embebido de PHP (rápido, con limitaciones)

```
C:\xampp\php\php.exe -S localhost:8000 -t .
```

- Funciona para desarrollo rápido.
- Las rutas relativas siguen resolviendo bien.
- **Limitación**: el servidor embebido no sirve archivos fuera del
  DocumentRoot, así que la previsualización de PDFs cargados
  (acción `verArchivo`) no funciona con esta opción.

## 4. Túnel para presentar la demo

El túnel expone el puerto local 80 (Apache) hacia una URL pública.
Opciones gratuitas:

- **Cloudflare Tunnel** (recomendado, URL estable y sin cuenta):

  ```
  winget install --id Cloudflare.cloudflared
  cloudflared tunnel --url http://localhost:80
  ```

  Imprime una URL `https://algo.trycloudflare.com`.

- **ngrok** (requiere cuenta con nivel gratuito):

  ```
  ngrok http 80
  ```

> ⚠️ Con el túnel abierto, la app queda accesible desde Internet.
> Antes de presentar, verificar que el panel de administración exige
> sesión (ya está protegido con `config/guardias.php`).

## 5. Comprobaciones rápidas

```
:: sintaxis de todos los PHP
C:\xampp\php\php.exe -l controllers\AuthController.php

:: sintaxis de todos los JS
node --check public\JS\escapar.js
```

Prueba manual de seguridad (debe devolver **401**):

```
curl -i "http://localhost/views/gestionarTextos.php"      # 302 a login_admin
curl -i "http://localhost/controllers/AdminController.php?accion=agregarAdministrador"
```

## 6. Problemas frecuentes

| Síntoma | Causa probable |
|---|---|
| "No hay conexión con la base de datos" | IP fuera del allowlist de Azure |
| La app carga pero los datos no | Revisar `.env` (DB_NAME, DB_PASSWORD) |
| El PDF no se sube | Carpeta `storage/escrituras` sin permisos de escritura |
| Los estilos no cargan | El DocumentRoot no es la raíz del repo |
| 404 en `public/JS/...` | Rutas relativas rotas: revisar el VirtualHost |
