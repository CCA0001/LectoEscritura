<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['correo_electronico'];
    $password = $_POST['contrasenia_hash'];

    $sql_busqueda = "SELECT * FROM usuario WHERE correo_electronico = '$email' AND contrasenia_hash = '$password'";
    $resultado = mysqli_query($conexion, $sql_busqueda);

    if ($usuario = mysqli_fetch_assoc($resultado)) {
        // Usamos 'ID' tal cual aparece en tu imagen
        $id_user = $usuario['ID']; 
        $fecha_hoy = date("Y-m-d");
        
        // Nombres exactos de tus columnas según la foto:
        $ultima_conexion_db = $usuario['ultima_conexion'];
        $racha_actual = (int)$usuario['dias_racha'];

        if ($ultima_conexion_db != $fecha_hoy) {
            $nueva_racha = $racha_actual + 1;
            
            // UPDATE con los nombres de tus capturas
            $sql_update = "UPDATE usuario SET dias_racha = $nueva_racha, ultima_conexion = '$fecha_hoy' WHERE ID = $id_user";
            
            if (mysqli_query($conexion, $sql_update)) {
                echo "<script>alert('¡Racha actualizada! Ahora tienes $nueva_racha días 🔥');</script>";
            } else {
                die("Error al actualizar: " . mysqli_error($conexion));
            }
        } else {
            echo "<script>alert('Ya sumaste tu racha de hoy. Sigues en $racha_actual días.');</script>";
        }

        // Redirige al archivo de tus compañeros (cámbialo si no es index.html)
        echo "<script>window.location='index.html';</script>";

    } else {
        echo "<script>alert('Correo o contraseña incorrectos.'); window.location='index.php';</script>";
    }
}
mysqli_close($conexion);
?>