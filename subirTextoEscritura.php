<?php
    include("conexion.php");

    $datos = json_decode(file_get_contents("php://input"), true);

    if(!$datos){
        echo json_encode(["mensaje" => "No se recibió contenido"]);
        exit();
    }

    $nombre = $datos['nombre'];
    $contenido = $datos['contenido'];

    $stmtSubirArchivo = $conexion->prepare("
        INSERT INTO ejercicioescritura (ID_usuario, contenido_texto, puntajePromedio, retroalimentacion)
        VALUES (1,?,0,?)"
    );

    $stringTest = "test";
    $stmtSubirArchivo->bind_param("ss",substr($contenido,0,5000),$stringTest);
    $stmtSubirArchivo->execute();

    echo json_encode([
        "mensaje" => "Texto subido correctamente",
        "id" => $conexion->insert_id
    ]);
    
?>