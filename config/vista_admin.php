<?php

/**
 * Arranque para vistas que exigen sesión de ADMINISTRADOR.
 *
 * Las vistas se pueden abrir directamente por URL (ej.
 * /views/gestionarTextos.php), así que la guarda debe vivir también
 * en la vista, no solo en el controlador.
 *
 * Uso:  require_once("../config/vista_admin.php");
 */

require_once(__DIR__ . "/guardias.php");

Guardias::exigirAdmin(
    false,
    "../views/login_admin.php"
);
