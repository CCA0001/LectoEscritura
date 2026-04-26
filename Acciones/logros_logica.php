<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$query_user = "SELECT u.nombre_usuario, u.puntos_xp, u.dias_racha, n.nombre as nivel 
               FROM usuario u 
               LEFT JOIN nivelprogreso n ON u.ID_nivelProgreso = n.ID 
               WHERE u.ID = '$id_usuario'";
$res_user = mysqli_query($conexion, $query_user);
$usuario = mysqli_fetch_assoc($res_user);

$xp_usuario = $usuario['puntos_xp'] ?? 0;
$nombre_usuario = $usuario['nombre_usuario'];
$nivel_usuario = $usuario['nivel'] ?? 'Principiante';
$racha_usuario = $usuario['dias_racha'] ?? 0;

$query_logros = "SELECT l.ID, l.nombre, l.descripcion, l.recompensa_xp, ul.fecha_desbloqueo 
                 FROM logro l 
                 LEFT JOIN usuariologro ul ON l.ID = ul.ID_logro AND ul.ID_usuario = '$id_usuario'
                 ORDER BY ul.fecha_desbloqueo DESC, l.ID ASC";
$resultado = mysqli_query($conexion, $query_logros);

$query_contar = "SELECT COUNT(*) as total FROM usuariologro WHERE ID_usuario = '$id_usuario'";
$res_contar = mysqli_query($conexion, $query_contar);
$total_logros = mysqli_fetch_assoc($res_contar)['total'];

$query_total = "SELECT COUNT(*) as total FROM logro";
$res_total = mysqli_query($conexion, $query_total);
$total_posibles = mysqli_fetch_assoc($res_total)['total'];