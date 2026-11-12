# AGENTS.md — LectoEscritura (EVAL)

Plain PHP 8 + MySQL web app for reading/writing evaluation with gamification (XP, streaks, badges) and Gemini-based PDF grading. **No framework, no Composer, no npm, no build step, no tests, no CI, no `.htaccess`, no router.**

## Run it

- Serve the **repository root as the document root** (Apache/XAMPP). Everything depends on this: pages live one level deep (`views/`, `controllers/`) and reference assets as `../public/...`.
  - `php -S localhost:8000` from the root mostly works, but PHP's built-in server sets CWD to the docroot, so **PDF uploads land in the wrong place** (`ModuloEscrituraController::subirArchivo` uses a CWD-relative `../public/ejercicios/...`). Prefer Apache/XAMPP.
- Entry points (no index): `views/login.html` (user), `views/login_admin.php` (admin), `views/pantalla_principal_Usuario.php`, `views/pantalla_principal_Admin.php`.
- Verification is manual: open the page, or `curl` a controller endpoint. There is no lint/typecheck/test command to run.

## Request flow

- Pages: `views/*.php` (mostly HTML with a `<script>` block per page).
- API: `controllers/<Name>Controller.php?accion=<method>` — each controller dispatches with `method_exists($controller, $accion)` and a default action (`$_GET['accion'] ?? '<default>'`). Any public method of the controller is reachable via `accion`, so add a guard for new sensitive methods.
- Responses are raw `echo json_encode([...])` with `success` / `mensaje` keys; the client is vanilla `fetch()` in `public/JS/**` (no bundler, no modules).
- Auth is PHP session based. Session keys differ by role: users use `$_SESSION['id_usuario']`, admins use `$_SESSION['id']`. `views/pantalla_principal_Admin.php` reads `$_SESSION['nombre']` (never set) and its auth check is commented out.

## Configuration (hardcoded, committed)

- `config/conexion.php` — Azure MySQL host, user, password, DB `lectoescritura_test`; SSL is mandatory (`mysqli_ssl_set` + `MYSQLI_CLIENT_SSL`). Edit here to point at another DB; there is no `.env`.
- `config/ia.php` — Gemini API key + `GEMINI_MODEL` (`gemini-2.5-flash`).
- Both secrets are in git history; don't introduce new ones — reuse these two files.

## Database

- No migrations, no SQL dump, no schema files in the repo. Schema is implicit in the models.
- Table names are lowercase-concatenated, e.g. `usuario`, `admin`, `textolectura`, `preguntalectura`, `opcionpregunta`, `intentolectura`, `respuestalectura`, `logro`, `usuariologro`, `nivelprogreso`, `niveldificultad`, `tipotexto`, `archivoescritura`, `detallesejercicioescritura`.
- Models use `mysqli_prepare` + `bind_param`. Keep that pattern; raw string interpolation into SQL exists only in legacy code (see below).
- `TextoModel::obtenerTextoFacil` = 1 random text with `ID_dificultad = 1`; `obtenerTextoAvanzado` = 4 random texts with difficulty 2 or 3. Both require `estado = 'Activo'`.

## Writing module (AI grading)

- Upload: PDF only (`$_FILES['archivo_pdf']`), stored in `public/ejercicios/pdf_escritura/` as `escritura_<uniqid>.pdf`; the DB stores the repo-relative path (`public/ejercicios/...`).
- Grading: `services/IAEscrituraService.php` sends the PDF (base64 inline) to Gemini and expects **strict JSON** with `coherencia, cohesion, gramatica, argumentacion, estructura, puntaje_promedio, retroalimentacion`. Markdown fences are stripped before `json_decode`; a parse failure returns `{"error": ...}`.
- Every Gemini response is dumped to `services/debug_gemini.txt` — check it when grading misbehaves.
- XP for writing comes from `puntaje_promedio`; reading XP comes from the reading score (`UsuarioModel::sumarXp`).

## Known traps (verified)

- `controllers/ModuloEscrituraController::evaluarArchivo` calls `$this->UsuarioModel->sumarXp(...)` but `UsuarioModel` is never instantiated on that controller → fatal error at the end of a grading request.
- `public/ia/evaluar_escritura.php` is **dead legacy code**: it includes a non-existent `config.php` and redirects to a non-existent `controllers/controlador_escritura.php`. Don't wire anything to it.
- Case mismatches that only work on Windows: `controllers/LogroController.php` requires `../models/logroModel.php` (file is `LogroModel.php`), and views reference `../public/JS/auth/logout.js` (directory is `Auth/`). They will 404 on a case-sensitive (Linux) deploy.
- `views/pantalla_principal_Admin.php` links `../css/dropdown_usuario.css`, which does not exist.
- `AuthController::verificarLogrosRacha($idUsuario, $racha)` is called with 3 args but declared with 2 — the `$xp` argument is dropped.
- `registerUsuario` password check uses `if(!$contrasenia == $confirmar_contrasenia)` (truthiness, not strict compare).

## Conventions

- One controller per feature area (`controllers/`), one model per table (`models/`), views in `views/`, JS in `public/JS/<area>/`, CSS in `public/css/`. JS file names mirror the endpoint they call (e.g. `public/JS/logro/agregarLogro.js` → `LogroController.php?accion=agregarLogro`).
- Includes are relative (`require_once("../models/XModel.php")`); do not switch to absolute/namespace-based paths — the relative scheme is what makes the "docroot = repo root" layout work.
- The codebase is written in Spanish (identifiers, UI strings, comments). Match that when adding code.
- Git: `master` is the default branch; work branches seen in history: `develop`, `main`, `nhara`; changes land via PRs merged from `develop`.
