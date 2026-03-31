<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$baseDatos = "lectoescriturabd";

$conexion = new mysqli($servidor, $usuario, $password, $baseDatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>