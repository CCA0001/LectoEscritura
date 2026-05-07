<?php
session_start();
include '../config/conexion.php';

$email = mysqli_real_escape_string($conexion, $_POST['correo_electronico']);
$password = $_POST['contrasenia_hash'];

$sql_busqueda = "SELECT * FROM usuario WHERE correo_electronico = '$email'";
$resultado = mysqli_query($conexion, $sql_busqueda);

if ($usuario = mysqli_fetch_assoc($resultado)) {
    
    if (password_verify($password, $usuario['contrasenia_hash'])) {
        
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
            $_SESSION['mensaje_racha'] = "¡Día completado! Racha de: $nueva_racha días!";
        }

        header("Location: ../views/pantalla_principal_Usuario.php");
        exit();
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.location='../views/login.html';</script>";
        exit();
    }
} else {
    echo "<script>alert('Usuario no encontrado'); window.location='../views/login.html';</script>";
    exit();
}
?>