<?php
ob_start();
require_once("../config/conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre              = isset($_POST['nombre'])                 ? trim($_POST['nombre'])                 : null;
    $nombre_usuario      = isset($_POST['nombre_usuario'])         ? trim($_POST['nombre_usuario'])         : null;
    $correo              = isset($_POST['correo_electronico'])     ? trim($_POST['correo_electronico'])     : null;
    $password_plana      = isset($_POST['contrasenia'])            ? $_POST['contrasenia']                 : null;
    $password_confirmar  = isset($_POST['contrasenia_confirmar'])  ? $_POST['contrasenia_confirmar']       : null;

    // Validar que no falten datos
    if (!$nombre || !$nombre_usuario || !$correo || !$password_plana || !$password_confirmar) {
        echo "<script>alert('Error: Todos los campos son obligatorios.'); window.history.back();</script>";
        exit();
    }

    // Validar longitud de contraseña
    if (strlen($password_plana) < 8) {
        echo "<script>alert('La contraseña debe tener al menos 8 caracteres.'); window.history.back();</script>";
        exit();
    }

    // Validar que las contraseñas coincidan
    if ($password_plana !== $password_confirmar) {
        echo "<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>";
        exit();
    }

    // Verificar si el correo ya está registrado
    $sql_check = "SELECT id FROM `admin` WHERE correo_electronico = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $correo);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo "<script>alert('Ya existe un administrador con ese correo.'); window.history.back();</script>";
        exit();
    }

    mysqli_stmt_close($stmt_check);

    // Insertar el nuevo administrador
    $contrasenia_hash = password_hash($password_plana, PASSWORD_DEFAULT);
    $fecha_actual     = date("Y-m-d H:i:s");
    
    $nombres_apellidos = explode(" ", $nombre);
    $nombres = $nombres_apellidos[0] . " " . $nombres_apellidos[1];
    $apellidos = $nombres_apellidos[2] . " " . $nombres_apellidos[3];

    $sql_insert = "INSERT INTO admin (nombres, apellidos, nombre_usuario, correo_electronico, contrasenia_hash, fecha_registro)
                   VALUES (?, ? , ?, ?, ?, ?)";

    $stmt_insert = mysqli_prepare($conexion, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ssssss", $nombres, $apellidos, $nombre_usuario, $correo, $contrasenia_hash, $fecha_actual);

    if (mysqli_stmt_execute($stmt_insert)) {
        echo "<script>alert('Administrador registrado exitosamente.'); window.location.href='../gestionarAdministradores.php';</script>";
    } else {
        echo "<script>alert('Error al registrar: " . mysqli_error($conexion) . "'); window.history.back();</script>";
    }

    mysqli_stmt_close($stmt_insert);
    mysqli_close($conexion);
}

ob_end_flush();
?>