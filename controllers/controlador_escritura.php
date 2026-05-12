<?php
session_start();
include("../config/conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.html");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$mensaje = $_GET['mensaje'] ?? '';
$error = $_GET['error'] ?? '';

$query = "SELECT e.*, d.nombre as dificultad_nombre, t.nombre as tipo_nombre, 
                 e.puntaje_promedio, det.retroalimentacion
          FROM archivoescritura e
          LEFT JOIN niveldificultad d ON e.ID_dificultad = d.ID
          LEFT JOIN tipotexto t ON e.ID_tipoTexto = t.ID
          LEFT JOIN detallesejercicioescritura det ON e.ID = det.ID_archivoEscritura
          WHERE e.ID_usuario = '$id_usuario' 
          ORDER BY e.fecha_subida DESC";
$resultado = mysqli_query($conexion, $query);

$dificultades = mysqli_query($conexion, "SELECT * FROM niveldificultad");
$tipos = mysqli_query($conexion, "SELECT * FROM tipotexto");

include("../views/ejercicio_escritura.php");
?>