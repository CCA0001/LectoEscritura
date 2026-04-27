<?php
ob_start();
require_once("../conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre  = isset($_POST['nombre'])             ? trim($_POST['nombre'])          : null;
    $puntos  = isset($_POST['puntos_requeridos'])  ? (int)$_POST['puntos_requeridos'] : null;

    if (!$nombre || $puntos === null) {
        echo "<script>alert('Error: Todos los campos son obligatorios.'); window.history.back();</script>";
        exit();
    }

    if ($puntos < 0) {
        echo "<script>alert('Error: Los puntos requeridos no pueden ser negativos.'); window.history.back();</script>";
        exit();
    }

    $sql_check = "SELECT ID FROM nivelprogreso WHERE nombre = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $nombre);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo "<script>alert('Ya existe un nivel con ese nombre.'); window.history.back();</script>";
        exit();
    }

    mysqli_stmt_close($stmt_check);

    $sql = "INSERT INTO nivelprogreso (nombre, puntos_requeridos) VALUES (?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "si", $nombre, $puntos);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Nivel agregado exitosamente.'); window.location.href='../gestionarNivelesProgreso.php';</script>";
    } else {
        echo "<script>alert('Error al agregar: " . mysqli_error($conexion) . "'); window.history.back();</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}

ob_end_flush();
?>
