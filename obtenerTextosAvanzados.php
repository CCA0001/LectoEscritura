<?php

include("conexion.php");

// Trae todos los textos de dificultad intermedia (ID=2) y avanzada (ID=3)
// junto con el nombre de la dificultad para mostrarlo como badge.
// Sin LIMIT — devuelve todos los que existan en la BD.
$stmtTextos = $conexion->prepare("
    SELECT t.*, d.nombre AS dificultad_nombre
    FROM textolectura t
    JOIN niveldificultad d ON t.ID_dificultad = d.ID
    WHERE t.ID_dificultad IN (2, 3)
    ORDER BY t.ID_dificultad ASC, t.ID ASC
");

$stmtTextos->execute();
$resultadoTextos = $stmtTextos->get_result();

if ($resultadoTextos->num_rows === 0) {
    echo json_encode(["textos" => [], "mensaje" => "No hay textos de nivel intermedio o avanzado"]);
    exit();
}

$textos = [];

while ($texto = $resultadoTextos->fetch_assoc()) {

    $idTexto = $texto['ID'];

    // Preguntas de este texto
    $stmtPreguntas = $conexion->prepare("
        SELECT * FROM preguntalectura
        WHERE ID_textoLectura = ?
    ");
    $stmtPreguntas->bind_param("i", $idTexto);
    $stmtPreguntas->execute();
    $resultadoPreguntas = $stmtPreguntas->get_result();

    $preguntas = [];

    while ($pregunta = $resultadoPreguntas->fetch_assoc()) {

        $idPregunta = $pregunta['ID'];

        // Opciones de esta pregunta
        $stmtOpciones = $conexion->prepare("
            SELECT * FROM opcionpregunta
            WHERE ID_pregunta = ?
        ");
        $stmtOpciones->bind_param("i", $idPregunta);
        $stmtOpciones->execute();
        $resultadoOpciones = $stmtOpciones->get_result();

        $opciones = [];
        while ($opcion = $resultadoOpciones->fetch_assoc()) {
            $opciones[] = $opcion;
        }

        $pregunta['opciones'] = $opciones;
        $preguntas[] = $pregunta;
    }

    $texto['preguntas'] = $preguntas;
    $textos[] = $texto;
}

echo json_encode(["textos" => $textos]);

?>