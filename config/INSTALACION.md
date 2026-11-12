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

2. Base de datos. Hay dos escenarios:

   **A) MySQL local de XAMPP (modo actual)**

   - Arrancar MySQL desde el panel de XAMPP, o:

     ```
     C:\xampp\mysql\bin\mysqld.exe --defaults-file=C:\xampp\mysql\bin\my.ini
     ```

   - El `my.ini` correcto es el de `mysql\bin\`, **no** el de `mysql\`.
   - El servidor local tiene SSL deshabilitado (`have_ssl = DISABLED`),
     por eso el `.env` debe llevar `DB_SSL=0`. Con `DB_SSL=1` la
     conexión falla con *"MySQL server has gone away"*.
   - Usuario de la app (ya creado, con permisos solo sobre la BD):

     ```
     C:\xampp\mysql\bin\mysql.exe -u root --skip-ssl
     ```

     ```sql
     CREATE USER IF NOT EXISTS 'lectoescritura'@'localhost' IDENTIFIED BY '...';
     GRANT ALL PRIVILEGES ON lectoescritura_test.* TO 'lectoescritura'@'localhost';
     FLUSH PRIVILEGES;
     ```

   - Importar el dump en Workbench: abrir el `.sql` y ejecutar. El dump
     usa `CREATE DATABASE IF NOT EXISTS`, no borra nada.

   **B) MySQL de Azure** (si se vuelve a usar)

   - `DB_SSL=1` en el `.env` (Azure exige conexión cifrada).
   - Hay que agregar la IP pública de quien se conecta en
     *Azure → MySQL flexible server → Networking → Firewall rules*.
     La IP se consulta en https://api.ipify.org (en esta máquina es
     `190.67.226.48`, pero cambia con la red WiFi).

   Si la configuración falla, la app responde:
   `"No hay conexión con la base de datos. Verificá el host, el puerto y
   las credenciales del .env."` tras ~5 segundos.

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
| "No hay conexión con la base de datos" | MySQL local apagado · `DB_SSL=1` con MySQL local · IP fuera del allowlist de Azure |
| "MySQL server has gone away" al conectar | `DB_SSL=1` pero el servidor no tiene SSL (caso local) |
| `Undefined function curl_init()` | La extensión `php_curl.dll` no carga en Apache. **No es bloqueante**: el servicio de IA cae automáticamente a streams HTTP (solo necesita `ext-openssl`). |
| El PDF no se sube | Carpeta `storage/escrituras` sin permisos de escritura |
| Los estilos no cargan | El DocumentRoot no es la raíz del repo |
| 404 en `public/JS/...` | Rutas relativas rotas: revisar el VirtualHost |
| "Your API key was reported as leaked" | La key de Gemini estaba versionada en Git: hay que **rotarla** en https://aistudio.google.com/apikey |
