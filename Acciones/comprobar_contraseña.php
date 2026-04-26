<?php
session_start();
require_once("../conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $correo_buscar = trim($_POST['correo_electronico']); 
    $password_digitada = $_POST['contrasenia_hash']; 

    // para el admin
    $sql_admin = "SELECT ID, nombre_usuario, contrasenia_hash FROM admin WHERE correo_electronico = ?";
    $stmt_admin = mysqli_prepare($conexion, $sql_admin);
    mysqli_stmt_bind_param($stmt_admin, "s", $correo_buscar);
    mysqli_stmt_execute($stmt_admin);
    $resultado_admin = mysqli_stmt_get_result($stmt_admin);

    if ($admin = mysqli_fetch_assoc($resultado_admin)) {
        if (password_verify($password_digitada, $admin['contraseña'])) {
            $_SESSION['id_usuario'] = $admin['ID'];
            $_SESSION['nombre_usuario'] = $admin['nombre_usuario'];
            $_SESSION['rol'] = 'admin';
            header("Location: ../admin/dashboard.php");
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
            exit();
        }
    }

    // para el usuario
    $sql_user = "SELECT ID, nombre_usuario, contrasenia_hash FROM usuario WHERE correo_electronico = ?";
    $stmt_user = mysqli_prepare($conexion, $sql_user);
    mysqli_stmt_bind_param($stmt_user, "s", $correo_buscar);
    mysqli_stmt_execute($stmt_user);
    $resultado_user = mysqli_stmt_get_result($stmt_user);

    if ($user = mysqli_fetch_assoc($resultado_user)) {
        if (password_verify($password_digitada, $user['contrasenia_hash'])) {
            $_SESSION['id_usuario'] = $user['ID'];
            $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
            $_SESSION['rol'] = 'estudiante';
            header("Location: ../usuario/pantalla_principal_Usuario.php");
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('No hay una cuenta registrada con este correo.'); window.history.back();</script>";
        exit();
    }

    mysqli_stmt_close($stmt_admin);
    mysqli_stmt_close($stmt_user);
    mysqli_close($conexion);
}
?>