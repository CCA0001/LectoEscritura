<?php
session_start();
include("../conexion.php");
include("config.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.html");
    exit();
}

$id_archivo = intval($_GET['id']);
$sql = "SELECT * FROM archivoescritura WHERE ID = $id_archivo AND ID_usuario = '{$_SESSION['id_usuario']}'";
$resultado = mysqli_query($conexion, $sql);
$archivo = mysqli_fetch_assoc($resultado);

if (!$archivo) {
    header("Location: ../usuario/ejercicio_escritura.php?error=Archivo no encontrado");
    exit();
}

$ruta_completa = __DIR__ . "/../" . $archivo['url_archivo'];
$evaluacion = evaluarPDFconGemini($ruta_completa, $api_key);

if (isset($evaluacion['error'])) {
    $error_msg = urlencode($evaluacion['error']);
    header("Location: ../usuario/ejercicio_escritura.php?error=$error_msg");
} else {
    $id_archivo_db = $archivo['ID'];
    
    $sql_insert = "INSERT INTO detallesejercicioescritura 
                   (ID_archivoEscritura, puntaje_coherencia, puntaje_cohesion, puntaje_gramatica, 
                    puntaje_argumentacion, puntaje_estructura, retroalimentacion, fecha_registrada) 
                   VALUES (
                       '$id_archivo_db',
                       '{$evaluacion['coherencia']}',
                       '{$evaluacion['cohesion']}',
                       '{$evaluacion['gramatica']}',
                       '{$evaluacion['argumentacion']}',
                       '{$evaluacion['estructura']}',
                       '{$evaluacion['retroalimentacion']}',
                       NOW()
                   )";
    mysqli_query($conexion, $sql_insert);
    
    $sql_update = "UPDATE archivoescritura SET puntaje_promedio = '{$evaluacion['puntaje_promedio']}' WHERE ID = $id_archivo_db";
    mysqli_query($conexion, $sql_update);
    
    header("Location: ../usuario/ejercicio_escritura.php?mensaje=Evaluacion completada. Puntaje: {$evaluacion['puntaje_promedio']}/10");
}
exit();
?>