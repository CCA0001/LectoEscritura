<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("conexion.php");


if (isset($_POST['respuesta'])) {
    $respuesta = $_POST['respuesta'];

  
    $sql = "INSERT INTO resultados (Respuesta) VALUES ('$respuesta')";

    if ($conexion->query($sql) === TRUE) {
        echo "Respuesta guardada correctamente";
    } else {
        echo "Error al guardar: " . $conexion->error;
    }

} else {
    echo "No se recibió ninguna respuesta";
}
?>