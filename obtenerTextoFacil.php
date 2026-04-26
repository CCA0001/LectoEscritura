<?php

include("conexion.php");

$stmtTextoLectura = $conexion->prepare("
    SELECT t.*, tt.nombre AS tipo_texto_nombre
    FROM textolectura t
    LEFT JOIN tipotexto tt ON t.ID_tipoTexto = tt.ID
    ORDER BY RAND() LIMIT 1
");
$stmtTextoLectura->execute();

$resultadoTextoLectura = $stmtTextoLectura->get_result();
$textoLectura = $resultadoTextoLectura->fetch_assoc();

if(!$textoLectura){
    echo json_encode(["error" => "No hay textos"]);
    exit();
}

$idTexto = $textoLectura['ID'];

$stmtPreguntas = $conexion->prepare("
    SELECT p.*, nc.nombre AS nivel_comprension_nombre
    FROM preguntalectura p
    LEFT JOIN nivelcomprensionlectora nc ON p.ID_nivelComprension = nc.ID
    WHERE p.ID_textoLectura = ?
");
$stmtPreguntas->bind_param("i", $idTexto);

if($stmtPreguntas->execute()){
    $resultadoPreguntas = $stmtPreguntas->get_result();
} else {
    echo json_encode(["error" => "No hay preguntas"]);
    exit();
}

$preguntas = [];

while ($pregunta = $resultadoPreguntas->fetch_assoc()) {

    $idPregunta = $pregunta['ID'];

    $stmtOpciones = $conexion->prepare("SELECT * FROM opcionpregunta WHERE ID_pregunta = ?");
    $stmtOpciones->bind_param("i", $idPregunta);

    if($stmtOpciones->execute()){
        $resultadoOpciones = $stmtOpciones->get_result();
    } else {
        echo json_encode(["error" => "No hay opciones de pregunta"]);
        exit();
    }

    $opciones = [];

    while ($opcion = $resultadoOpciones->fetch_assoc()) {
        $opciones[] = $opcion;
    }

    $pregunta['opciones'] = $opciones;

    $preguntas[] = $pregunta;
}

$data = [
    "texto" => $textoLectura,
    "preguntas" => $preguntas
];

echo json_encode($data);

?>