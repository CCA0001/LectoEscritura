<?php

/**
 * Arranque para vistas que exigen sesión de USUARIO.
 *
 * Uso:  require_once("../config/vista_usuario.php");
 */

require_once(__DIR__ . "/guardias.php");

Guardias::exigirUsuario(
    false,
    "../views/login.html"
);
