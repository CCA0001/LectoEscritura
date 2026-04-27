<?php
ob_start();
require_once("../conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo       = isset($_POST['titulo'])        ? trim($_POST['titulo'])   : null;
    $contenido    = isset($_POST['contenido'])     ? trim($_POST['contenido']): null;
    $ID_dificultad = isset($_POST['ID_dificultad']) ? (int)$_POST['ID_dificultad'] : null;
    $ID_tipoTexto  = isset($_POST['ID_tipoTexto'])  ? (int)$_POST['ID_tipoTexto']  : null;

    if (!$titulo || !$contenido || !$ID_dificultad || !$ID_tipoTexto) {
        echo "<script>alert('Error: Todos los campos son obligatorios.'); window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO textolectura (titulo, contenido, ID_dificultad, ID_tipoTexto) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $titulo, $contenido, $ID_dificultad, $ID_tipoTexto);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Texto agregado exitosamente.'); window.location.href='../gestionarTextos.php';</script>";
    } else {
        echo "<script>alert('Error al agregar: " . mysqli_error($conexion) . "'); window.history.back();</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}

ob_end_flush();
?>
