<?php
ob_start();
require_once("../config/conexion.php");
session_start();
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
 
    if (!$id) {
        echo "<script>alert('Error: ID de administrador no válido.'); window.history.back();</script>";
        exit();
    }
 
    // Evitar que un admin se elimine a sí mismo
    if (isset($_SESSION['id_admin']) && $_SESSION['id_admin'] == $id) {
        echo "<script>alert('No puedes eliminar tu propia cuenta.'); window.history.back();</script>";
        exit();
    }
 
    // Verificar que el administrador existe
    $sql_check = "SELECT id FROM admin WHERE id = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
 
    if (mysqli_stmt_num_rows($stmt_check) == 0) {
        echo "<script>alert('El administrador no existe.'); window.history.back();</script>";
        exit();
    }
 
    mysqli_stmt_close($stmt_check);
 
    // Eliminar el administrador
    $sql_delete = "UPDATE `admin` SET estado=? WHERE id = ?";
    $estado_nuevo = "Inactivo";
    $stmt_delete = mysqli_prepare($conexion, $sql_delete);
    mysqli_stmt_bind_param($stmt_delete, "si", $estado_nuevo, $id);
 
    if (mysqli_stmt_execute($stmt_delete)) {
        echo "<script>alert('Administrador desactivado correctamente.'); window.location.href='../gestionarAdministradores.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar: " . mysqli_error($conexion) . "'); window.history.back();</script>";
    }
 
    mysqli_stmt_close($stmt_delete);
    mysqli_close($conexion);
}
 
ob_end_flush();
?>