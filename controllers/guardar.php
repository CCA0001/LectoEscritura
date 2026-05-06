<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/login.html");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$datos = json_decode(file_get_contents("php://input"), true);

if (!$datos) {
    echo json_encode(["mensaje" => "No se recibieron respuestas"]);
    exit();
}

$idTexto = $datos['idTexto'];
$respuestas = $datos['respuestas'];

$correctasPorNivelComprension = ['literal' => 0, 'inferencial' => 0, 'critico' => 0];
$totalPreguntasPorNivelComprension = ['literal' => 0, 'inferencial' => 0, 'critico' => 0];
$totalRespuestasCorrectas = 0;

foreach ($respuestas as $idPregunta => $idOpcion) {

    $stmtVerificarSiEsCorrecta = $conexion->prepare("
        SELECT o.es_correcta, p.ID_nivelComprension
        FROM opcionpregunta o
        JOIN preguntalectura p ON o.ID_pregunta = p.ID
        WHERE o.ID = ? AND o.ID_pregunta = ?
    ");

    $stmtVerificarSiEsCorrecta->bind_param("ii", $idOpcion, $idPregunta);
    $stmtVerificarSiEsCorrecta->execute();
    $resultadoVerificacion = $stmtVerificarSiEsCorrecta->get_result()->fetch_assoc();

    switch ($resultadoVerificacion['ID_nivelComprension']) {
        case 1:
            $nivel = 'literal';
            break;
        case 2:
            $nivel = 'inferencial';
            break;
        case 3:
            $nivel = 'critico';
            break;
        default:
            $nivel = 'literal';
    }

    $esCorrecta = $resultadoVerificacion['es_correcta'];

    $totalPreguntasPorNivelComprension[$nivel]++;

    if ($esCorrecta) {
        $correctasPorNivelComprension[$nivel]++;
        $totalRespuestasCorrectas++;
    }
}

$totalPreguntas = count($respuestas);
$puntajeGlobal = round(($totalRespuestasCorrectas / $totalPreguntas) * 100);

$puntajeLiteral = ($totalPreguntasPorNivelComprension['literal'] > 0) 
    ? round(($correctasPorNivelComprension['literal'] / $totalPreguntasPorNivelComprension['literal']) * 100) 
    : null;

$puntajeInferencial = ($totalPreguntasPorNivelComprension['inferencial'] > 0) 
    ? round(($correctasPorNivelComprension['inferencial'] / $totalPreguntasPorNivelComprension['inferencial']) * 100) 
    : null;

$puntajeCritico = ($totalPreguntasPorNivelComprension['critico'] > 0) 
    ? round(($correctasPorNivelComprension['critico'] / $totalPreguntasPorNivelComprension['critico']) * 100) 
    : null;

$stmtInsertarResumenIntento = $conexion->prepare("
    INSERT INTO intentolectura 
        (ID_usuario, ID_texto, puntaje_total, respuestas_correctas, 
         total_preguntas, `tiempo(m)`, puntaje_literal, puntaje_inferencial, puntaje_critico)
    VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?)
");

$stmtInsertarResumenIntento->bind_param(
    "iidiiddd",
    $id_usuario,
    $idTexto,
    $puntajeGlobal,
    $totalRespuestasCorrectas,
    $totalPreguntas,
    $puntajeLiteral,
    $puntajeInferencial,
    $puntajeCritico
);

$stmtInsertarResumenIntento->execute();

echo json_encode([
    "mensaje" => "Respuestas guardadas correctamente",
    "puntaje" => $puntajeGlobal,
    "correctas" => $totalRespuestasCorrectas,
    "total" => $totalPreguntas
]);
?>