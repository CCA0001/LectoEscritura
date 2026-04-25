<?php
require_once("../conexion.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $usuario_input = isset($_POST['usuario_correo']) ? $_POST['usuario_correo'] : null;
    $password_plana = isset($_POST['contrasena']) ? $_POST['contrasena'] : null;

    if (!$nombre || !$usuario_input || !$password_plana) {
        die("Error: Faltan datos en el formulario.");
    }

    $correo_completo = $usuario_input . "@ucundinamarca.edu.co";
    $contrasenia_hash = password_hash($password_plana, PASSWORD_DEFAULT);
    $fecha_actual = date("Y-m-d H:i:s");
    
    $puntos = 0;
    $racha = 0;
    $nivel = 1;
    $estado = "activo";

    $sql_check = "SELECT correo_electronico FROM usuario WHERE correo_electronico = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $correo_completo);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo "<script>
                alert('El correo ya está en uso. Inicia sesión.');
                window.history.back();
              </script>";
        exit();
    }

    $sql_insert = "INSERT INTO usuario (
                        nombre_usuario, 
                        correo_electronico, 
                        contrasenia_hash, 
                        fecha_registro, 
                        puntos_xp, 
                        dias_racha, 
                        ID_nivelProgreso, 
                        estado, 
                        ultima_conexion
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_insert = mysqli_prepare($conexion, $sql_insert);
    
    mysqli_stmt_bind_param($stmt_insert, "ssssiiiss", 
        $nombre, 
        $correo_completo, 
        $contrasenia_hash, 
        $fecha_actual, 
        $puntos, 
        $racha, 
        $nivel, 
        $estado, 
        $fecha_actual
    );

    if (mysqli_stmt_execute($stmt_insert)) {
        echo "<script>
                alert('¡Registro exitoso en Eva!');
                window.location.href='../login.html';
              </script>";
    } else {
        echo "Error al registrar: " . mysqli_error($conexion);
    }

    mysqli_stmt_close($stmt_insert);
    mysqli_close($conexion);
}
?>