<?php
session_start(); 
include '../conexion.php';

$email = $_POST['correo_electronico'];
$password = $_POST['contrasenia_hash'];

$sql_busqueda = "SELECT * FROM usuario WHERE correo_electronico = '$email' AND contrasenia_hash = '$password'";
$resultado = mysqli_query($conexion, $sql_busqueda);

if ($usuario = mysqli_fetch_assoc($resultado)) {
    
  
    $_SESSION['id_usuario'] = $usuario['ID']; 
    $_SESSION['nombre_usuario'] = $usuario['nombre_usuario']; 

    $id_user = $usuario['ID']; 
    $fecha_hoy = date("Y-m-d");
    $ultima_conexion_db = $usuario['ultima_conexion'];
    $racha_actual = $usuario['dias_racha'];

    if ($ultima_conexion_db != $fecha_hoy) {
        $nueva_racha = $racha_actual + 1;
        $sql_update = "UPDATE usuario SET dias_racha = $nueva_racha, ultima_conexion = '$fecha_hoy' WHERE ID = $id_user";
        mysqli_query($conexion, $sql_update);
        echo "<script>alert('¡Día completado! Racha de: $nueva_racha ¡dias!');</script>";
    }

    
    echo "<script>window.location='../pantalla_principal_usuario.php';</script>";

} else {
    echo "<script>alert('Datos incorrectos'); window.location='login.html';</script>";
}

mysqli_close($conexion);
?>