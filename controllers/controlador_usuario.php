<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.html");
    exit();
}

include("../controllers/contador_racha_inicioUsuario.php");

$nombre_real = $_SESSION['nombre_real'] ?? 'Estudiante';
$racha_usuario = $_SESSION['racha_usuario'] ?? 0;
$puntos_xp = $_SESSION['puntos_xp'] ?? 0;
$rango_actual = $_SESSION['rango_actual'] ?? 'Principiante';
$total_logros = $_SESSION['total_logros'] ?? 0;

include("../views/pantalla_principal_Usuario.php");
?>