<?php
session_start(); 

include("conexion.php");

$nombre_real = "Usuario";
$racha_usuario = 0;

if (isset($_SESSION['id_usuario'])) {
    
    $id_logueado = $_SESSION['id_usuario']; 

   
    $consulta = "SELECT nombre_usuario, dias_racha FROM usuario WHERE ID = '$id_logueado'";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado && $fila = mysqli_fetch_array($resultado)) {
        $racha_usuario = $fila['dias_racha'];
        $nombre_real = $fila['nombre_usuario'];
    }

} else {
    header("Location: login.html");
    exit();
}
?>