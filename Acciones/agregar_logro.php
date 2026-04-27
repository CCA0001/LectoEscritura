<?php
ob_start();
require_once("../conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre       = isset($_POST['nombre'])        ? trim($_POST['nombre'])       : null;
    $descripcion  = isset($_POST['descripcion'])   ? trim($_POST['descripcion'])  : null;
    $recompensa   = isset($_POST['recompensa_xp']) ? (int)$_POST['recompensa_xp'] : null;

    if (!$nombre || !$descripcion || $recompensa === null) {
        echo "<script>alert('Error: Todos los campos son obligatorios.'); window.history.back();</script>";
        exit();
    }

    if ($recompensa < 0) {
        echo "<script>alert('Error: La recompensa XP no puede ser negativa.'); window.history.back();</script>";
        exit();
    }

    $sql_check = "SELECT ID FROM logro WHERE nombre = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $nombre);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo "<script>alert('Ya existe un logro con ese nombre.'); window.history.back();</script>";
        exit();
    }

    mysqli_stmt_close($stmt_check);

    $sql = "INSERT INTO logro (nombre, descripcion, recompensa_xp) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $nombre, $descripcion, $recompensa);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Logro agregado exitosamente.'); window.location.href='../gestionarLogros.php';</script>";
    } else {
        echo "<script>alert('Error al agregar: " . mysqli_error($conexion) . "'); window.history.back();</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}

ob_end_flush();
?>
