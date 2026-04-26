<?php
session_start();
include("../conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.html");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$mensaje = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo_pdf'])) {
    $nombre_archivo = mysqli_real_escape_string($conexion, $_POST['nombre_archivo']);
    $ID_dificultad = intval($_POST['ID_dificultad']);
    $ID_tipoTexto = intval($_POST['ID_tipoTexto']);
    $archivo = $_FILES['archivo_pdf'];
    
    $tipo = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    if ($tipo != 'pdf') {
        $error = "Solo se permiten archivos PDF.";
        header("Location: ../ejercicio_escritura.php?error=" . urlencode($error));
        exit();
    } 
    else if ($archivo['size'] > 5 * 1024 * 1024) {
        $error = "El archivo no debe superar los 5MB.";
        header("Location: ../ejercicio_escritura.php?error=" . urlencode($error));
        exit();
    }
    
    $carpeta_base = __DIR__ . "/../ejercicios";
    $carpeta_destino = $carpeta_base . "/pdf_escritura";
    
    if (!is_dir($carpeta_base)) {
        mkdir($carpeta_base, 0777);
    }
    if (!is_dir($carpeta_destino)) {
        mkdir($carpeta_destino, 0777);
    }
    
    $nombre_unico = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $archivo['name']);
    $url_guardar = "ejercicios/pdf_escritura/" . $nombre_unico;
    $ruta_fisica = $carpeta_destino . "/" . $nombre_unico;


    //move_uploaded_file(origen, destino)
    if (move_uploaded_file($archivo['tmp_name'], $ruta_fisica)) {
        $sql = "INSERT INTO ejercicioescritura (ID_usuario, nombre_archivo, url_archivo, ID_dificultad, ID_tipoTexto) 
                VALUES ('$id_usuario', '$nombre_archivo', '$url_guardar', '$ID_dificultad', '$ID_tipoTexto')";
        
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "✅ ¡Archivo subido con éxito!";
            header("Location: ../ejercicio_escritura.php?mensaje=" . urlencode($mensaje));
            exit();
        } else {
            $error = "❌ Error al guardar en BD: " . mysqli_error($conexion);
            header("Location: ../ejercicio_escritura.php?error=" . urlencode($error));
            exit();
        }
    } else {
        $error = "❌ Error al subir el archivo.";
        header("Location: ../ejercicio_escritura.php?error=" . urlencode($error));
        exit();
    }
} else {
    header("Location: ../ejercicio_escritura.php");
    exit();
}
?>