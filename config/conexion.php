<?php

require_once(__DIR__ . "/config.php");

Config::cargar();

date_default_timezone_set(
    Config::get("APP_TIMEZONE", "America/Bogota")
);

$host =
    Config::obtener(
        "DB_HOST"
    );

$usuario =
    Config::obtener(
        "DB_USER"
    );

$password =
    Config::obtener(
        "DB_PASSWORD"
    );

$database =
    Config::obtener(
        "DB_NAME"
    );

$puerto =
    (int)Config::get(
        "DB_PORT",
        3306
    );

$conexion = mysqli_init();

// El servidor MySQL de Azure exige conexión cifrada (SSL).
mysqli_ssl_set(
    $conexion,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL
);

// Con PHP 8.1+ los errores de mysqli se lanzan como excepción.
// Si no se activan, los fallos de conexión pasan silenciosamente.
mysqli_report(
    MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT
);

// Si el firewall de Azure no tiene la IP de este equipo, la conexión
// se queda colgada: se limita la espera para fallar rápido y con un
// mensaje claro en lugar de una pantalla en blanco.
mysqli_options(
    $conexion,
    MYSQLI_OPT_CONNECT_TIMEOUT,
    5
);

try {

    mysqli_real_connect(
        $conexion,
        $host,
        $usuario,
        $password,
        $database,
        $puerto,
        NULL,
        MYSQLI_CLIENT_SSL
    );

} catch(mysqli_sql_exception $error) {

    http_response_code(503);

    header("Content-Type: application/json");

    echo json_encode([
        "success" => false,
        "mensaje" =>
            "No hay conexión con la base de datos. " .
            "Verificá el allowlist de IP del servidor MySQL de Azure."
    ]);

    exit;

}
