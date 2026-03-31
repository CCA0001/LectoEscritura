<?php
$host = "mysql-duolinguistico-dev-lcnc28032510.mysql.database.azure.com";
$usuario = "admin_data";
$password = "Database123";
$database = "lectoescritura_test";

$conexion = mysqli_init();

mysqli_ssl_set($conexion, NULL, NULL, NULL, NULL, NULL);

mysqli_real_connect(
    $conexion,
    $host,
    $usuario,
    $password,
    $database,
    3306,
    NULL,
    MYSQLI_CLIENT_SSL
);

// Verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>