//opcional, quería seguir con lo de encriptar las contraseñas.
<?php
ob_start(); 
require_once("../conexion.php"); 
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $correo_buscar = trim($_POST['correo_electronico']); 
    $password_digitada = $_POST['contrasenia_hash']; 

    $sql = "SELECT ID, nombre_usuario, contrasenia_hash FROM usuario WHERE correo_electronico = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $correo_buscar);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($resultado)) {
        
        if (password_verify($password_digitada, $user['contrasenia_hash'])) {
            
            $_SESSION['id_usuario'] = $user['ID']; 
            $_SESSION['nombre'] = $user['nombre_usuario'];
            
            header("Location: ../pantalla_principal_Usuario.php"); 
            exit();

        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No registrado: " . $correo_buscar . "'); window.history.back();</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}
ob_end_flush();
?>