<?php

include("conexion.php");

$sqlTexto = "SELECT * FROM textolectura ORDER BY RAND() LIMIT 1";
$resultTexto = $conexion->query($sqlTexto);
$texto = $resultTexto->fetch_assoc();

$idTexto = $texto['ID'];

$sqlPreguntas = "SELECT * FROM preguntalectura WHERE ID_textoLectura = $idTexto";
$resultPreguntas = $conexion->query($sqlPreguntas);

$preguntas = [];

while ($pregunta = $resultPreguntas->fetch_assoc()) {

    $idPregunta = $pregunta['ID'];

    $sqlOpciones = "SELECT * FROM opcionpregunta WHERE ID_pregunta = $idPregunta";
    $resultOpciones = $conexion->query($sqlOpciones);

    $opciones = [];

    while ($opcion = $resultOpciones->fetch_assoc()) {
        $opciones[] = $opcion;
    }

    $pregunta['opciones'] = $opciones;

    $preguntas[] = $pregunta;
}

$data = [
    "texto" => $texto,
    "preguntas" => $preguntas
];

echo json_encode($data);

?>