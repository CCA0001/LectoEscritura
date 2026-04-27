<?php
ob_start();
require_once("../conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if (!$id) {
        echo "<script>alert('Error: ID no válido.'); window.history.back();</script>";
        exit();
    }

    $sql_check = "SELECT ID FROM textolectura WHERE ID = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) == 0) {
        echo "<script>alert('El texto no existe.'); window.history.back();</script>";
        exit();
    }

    mysqli_stmt_close($stmt_check);

    $sql = "DELETE FROM textolectura WHERE ID = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Texto eliminado correctamente.'); window.location.href='../gestionarTextos.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar: " . mysqli_error($conexion) . "'); window.history.back();</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}

ob_end_flush();
?>
