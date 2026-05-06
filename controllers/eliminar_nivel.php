<?php
ob_start();
require_once("../config/conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if (!$id) {
        echo "<script>alert('Error: ID no válido.'); window.history.back();</script>";
        exit();
    }

    $sql_check = "SELECT ID FROM nivelprogreso WHERE ID = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) == 0) {
        echo "<script>alert('El nivel no existe.'); window.history.back();</script>";
        exit();
    }

    mysqli_stmt_close($stmt_check);

    // Verificar si hay usuarios usando este nivel
    $sql_uso = "SELECT COUNT(*) as total FROM usuario WHERE ID_nivelProgreso = ?";
    $stmt_uso = mysqli_prepare($conexion, $sql_uso);
    mysqli_stmt_bind_param($stmt_uso, "i", $id);
    mysqli_stmt_execute($stmt_uso);
    $res_uso = mysqli_stmt_get_result($stmt_uso);
    $uso = mysqli_fetch_assoc($res_uso);

    if ($uso['total'] > 0) {
        echo "<script>alert('No se puede eliminar: hay " . $uso['total'] . " usuario(s) con este nivel asignado.'); window.history.back();</script>";
        exit();
    }

    mysqli_stmt_close($stmt_uso);

    $sql = "DELETE FROM nivelprogreso WHERE ID = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Nivel eliminado correctamente.'); window.location.href='../gestionarNivelesProgreso.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar: " . mysqli_error($conexion) . "'); window.history.back();</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}

ob_end_flush();
?>
