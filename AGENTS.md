# AGENTS.md — LectoEscritura (EVAL)

PHP 8 + MySQL (Azure) para evaluar Lectura Crítica y Comunicación Escrita, con gamificación (XP, racha, logros) y calificación de PDFs con Gemini. **Sin framework, sin Composer, sin npm, sin build, sin tests, sin CI, sin router propio.**

## Arquitectura (verificada)

```
config/          config.php (cargador .env) · router.php · guardias.php
                 sesion.php (arranque único de sesión) · conexion.php · ia.php
                 vista_admin.php · vista_usuario.php
                 INSTALACION.md · httpd-lectoescritura.conf (VirtualHost)
controllers/     8 controladores: dispatcher con lista blanca de acciones
models/          14 modelos: mysqli_prepare + bind_param
services/        IAEscrituraService.php (Gemini) · debug_gemini.txt (log, ignorado)
views/           16 vistas PHP/HTML
public/          css/ · JS/ · ejercicios/ (obsoleto) → los PDFs nuevos van a storage/
storage/escrituras/  PDFs subidos, FUERA del webroot
```

**Flujo:** `views/*.php` → `fetch("../controllers/XController.php?accion=metodo")` → `Router::resolver()` valida acción+rol → controlador → `echo json_encode([...])` → JS pinta el DOM.

## Reglas de oro del código

- **Dispatcher**: todo controlador usa `Router::resolver($defecto, [accion => rol])`. **Nunca** reintroducir `method_exists` (dejó abiertos todos los métodos públicos). Roles: `publico`, `admin`, `adminPagina`, `usuario`, `usuarioPagina`.
- **Orden de arranque**: `Sesion::iniciar()` (config/sesion.php) → `router.php` → modelos → `Router::resolver()` → **después** `conexion.php`. La conexión va al final a propósito: si la BD está caída, una petición no autorizada debe seguir devolviendo 401 y no 503.
- **Sesión**: nunca llamar `session_start()` a mano; usar `Sesion::iniciar()` (deja HttpOnly + SameSite=Lax). Tras un `session_regenerate_id()` hay que llamar `Sesion::fijarCookieDeSesion()` para no mandar dos `PHPSESSID`; el logout usa `Sesion::cerrar()`.
- **Los `require` usan `__DIR__`**, no rutas relativas al CWD: el repo funciona igual en Apache, en el servidor embebido de PHP y en la CLI.
- **Claves de sesión**: usuario → `id_usuario`; administrador → `id_admin` (también `id` y `nombre` por compatibilidad); `rol` = `usuario`|`admin`. No inventar otras.
- **Vistas**: toda vista que exija sesión incluye `config/vista_admin.php` o `config/vista_usuario.php` en la primera línea (se abren directo por URL).
- **Consultas**: solo `mysqli_prepare` + `bind_param`. No hay SQL dinámico en los modelos.
- **XSS**: todo dato de la BD que se pinte con `innerHTML` debe pasar por `escaparHTML()` / `escaparAtributo()` (`public/JS/escapar.js`). El script debe estar incluido en la vista.
- **Secretos**: solo en `.env` (ignorado por Git). `config/config.php` los lee; `Config::obtener()` falla con mensaje claro si falta.
- **Idioma**: identificadores, UI y comentarios en español.

## Comandos

```powershell
# sintaxis de todos los PHP
C:\xampp\php\php.exe -l controllers\AuthController.php

# sintaxis de todos los JS
node --check public\JS\escapar.js

# MySQL local de XAMPP (arrancarlo primero; el my.ini válido es el de mysql\bin\)
C:\xampp\mysql\bin\mysqld.exe --defaults-file=C:\xampp\mysql\bin\my.ini
C:\xampp\mysql\bin\mysql.exe -u lectoescritura -p --skip-ssl -D lectoescritura_test -e "SELECT 1;"

# servidor embebido (opcional; el webroot debe ser la raíz del repo)
C:\xampp\php\php.exe -S localhost:8000 -t .
```

No hay lint, ni typecheck, ni tests. **La verificación es manual**: abrir la página o hacer `curl` al endpoint.

## Entorno local

- XAMPP en `C:\xampp` (PHP 8.5.12, MariaDB 10.11). VirtualHost ya configurado en `C:\xampp\apache\conf\extra\lectoescritura.conf` → `http://127.0.0.1/`.
- Para `lectoescritura.local` hay que agregar `127.0.0.1 lectoescritura.local` al archivo `hosts` (**requiere permisos de administrador**).
- **El DocumentRoot debe ser la raíz del repo** (todo el direccionamiento es `../public/...`).
- **Base de datos**: MySQL local de XAMPP. Arrancarlo con `mysqld.exe --defaults-file=C:\xampp\mysql\bin\my.ini` (el `my.ini` válido es el de `mysql\bin\`, **no** el de `mysql\`). La BD `lectoescritura_test` ya está importada y el usuario de app `lectoescritura` ya existe.
- **SSL**: el MySQL local tiene `have_ssl = DISABLED` → el `.env` lleva `DB_SSL=0`. Con `DB_SSL=1` falla con *"MySQL server has gone away"*. En Azure va `DB_SSL=1`.
- **`ext-curl` no carga en Apache** (conflicto de DLLs con OpenSSL entre `php\` y `apache\bin\`). No es bloqueante: `IAEscrituraService` usa curl si está disponible y cae a streams HTTP (solo `ext-openssl`). No reintroducir una llamada directa a `curl_init()` sin fallback.
- Túnel para la demo: `cloudflared tunnel --url http://localhost:80` (o `ngrok http 80`).

## Base de datos

- Sin migraciones ni dump versionado en el repo: el esquema es implícito en los modelos. Tablas en minúsculas pegadas: `usuario`, `admin`, `textolectura`, `preguntalectura`, `opcionpregunta`, `intentolectura`, `respuestalectura`, `logro`, `usuariologro`, `nivelprogreso`, `niveldificultad`, `tipotexto`, `archivoescritura`, `detallesejercicioescritura`.
- `TextoModel::obtenerTextoFacil` = 1 texto aleatorio con `ID_dificultad = 1`; `obtenerTextoAvanzado` = 4 con dificultad 2 o 3. Ambos exigen `estado = 'Activo'`.
- La columna `opcionpregunta.es_correcta` **nunca** debe salir del servidor: se elimina con `unset()` en `OpcionPreguntaModel::obtenerOpcionesPorPregunta`. Si vuelve a `SELECT *` sin el `unset`, se filtra la clave de respuestas.

## Módulo de escritura (IA)

- Subida: PDF-only, MIME real validado con `finfo`, máx. 10 MB, nombre `escritura_<random_bytes>.pdf`, guardado en `storage/escrituras/` (**fuera del webroot**). La BD guarda la ruta relativa `storage/escrituras/...`.
- `verArchivo` sirve el PDF validando que pertenezca al usuario; nunca se enlaza la ruta directa.
- `services/IAEscrituraService.php` espera JSON estricto de Gemini con `coherencia, cohesion, gramatica, argumentacion, estructura, puntaje_promedio, retroalimentacion`; quita cercas markdown antes de decodificar.
- Cada respuesta de Gemini se vuelca en `services/debug_gemini.txt` (ignorado por Git) — revisarlo cuando la calificación falle.
- XP de escritura = `puntaje_promedio`; XP de lectura = puntaje del intento.

## Trampas conocidas

- `public/ia/evaluar_escritura.php` es **código muerto**: incluye un `config.php` inexistente y redirige a `controllers/controlador_escritura.php` inexistente. No cablear nada ahí.
- `public/JS/escritura/mostrarRetroalimentacion.js` está vacío (0 bytes). `racha_alerta.js` sí está conectado: se incluye en `pantalla_principal_Usuario.php` y lee `sessionStorage["alertaRacha"]`, que deja el login.
- `AuthController::verificarLogrosRacha` ya no usa IDs fijos: busca el logro activo por **nombre** (`LogroModel::obtenerLogroActivoPorNombre`) y toma la recompensa de `recompensa_xp`. Si se renombra "Racha de 7 días" / "Racha de 30 días" desde el panel, hay que actualizar esos nombres en el arreglo `$metas` del método: si no, el login sigue funcionando pero el logro no se desbloquea.
- `views/pantalla_principal_Admin.php` tiene un `#userInfoPanel` heredado que nunca se abre (no hay `#userRankBtn` ni incluye `dropdown_usuario.js`): es un fragmento estático, no un bug de datos.
- El CSS del dropdown (`.user-rank`, `.user-info-panel.show`, `.dropdown-arrow`) está **triplicado** en `pantalla_principal_Usuario.css`, `lectura.css` y `escritura.css`. Si se cambia el diseño, hay que tocar los tres.
- El **navbar es `position: fixed`**: en pruebas automatizadas con Puppeteer, un `page.click()` por coordenadas puede caer bajo el navbar y no disparar el botón. Usar `elemento.click()` sintético.
- `views/actualizarTexto.php` se sirve desde `/views/` (no desde el controlador): sus enlaces internos usan `../views/...` para funcionar desde cualquier origen.

## Git

- Rama de trabajo del MVP: `feature/mvp-noviembre`. `master` es la rama por defecto; el equipo suele integrar desde `develop`.
- Los PDFs de trabajos de estudiantes y `services/debug_gemini.txt` están fuera del índice de Git (`.gitignore`), pero los archivos legacy siguen en disco.
