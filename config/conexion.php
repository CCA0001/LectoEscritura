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
    Config::get(
        "DB_PASSWORD",
        ""
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

// Azure MySQL exige SSL; el MySQL local (XAMPP) lo tiene deshabilitado
// y la conexión falla si se lo fuerzan. Por eso es configurable.
$usarSsl =
    strtolower(
        (string)Config::get(
            "DB_SSL",
            "0"
        )
    ) === "1";

$conexion = mysqli_init();

if($usarSsl) {

    mysqli_ssl_set(
        $conexion,
        NULL,
        NULL,
        NULL,
        NULL,
        NULL
    );
}

// Con PHP 8.1+ los errores de mysqli se lanzan como excepción.
// Si no se activan, los fallos de conexión pasan silenciosamente.
mysqli_report(
    MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT
);

// Si el servidor no responde (firewall de Azure), la conexión se queda
// colgada: se limita la espera para fallar rápido y con un mensaje
// claro en lugar de una pantalla en blanco.
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
        $usarSsl ? MYSQLI_CLIENT_SSL : 0
    );

    // La BD usa utf8mb4 (el dump de Azure declara utf8mb3 en la base
    // pero las tablas son utf8mb4). Fijarlo evita que se trunquen los
    // acentos y los emojis al guardar.
    mysqli_set_charset(
        $conexion,
        "utf8mb4"
    );

} catch(mysqli_sql_exception $error) {

    http_response_code(503);

    header("Content-Type: application/json");

    echo json_encode([
        "success" => false,
        "mensaje" =>
            "No hay conexión con la base de datos. " .
            "Verificá el host, el puerto y las credenciales del .env."
    ]);

    exit;

}
