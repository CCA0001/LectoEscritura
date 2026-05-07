
<?php
echo "<!-- El controlador se está ejecutando -->";
error_log("Controlador ejecutado");
session_start();
require_once("../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $correo_buscar = trim($_POST['correo_electronico']); 
    $password_digitada = $_POST['contrasenia_hash']; 

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
            header("Location: controlador_usuario.php");
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