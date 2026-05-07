<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["mensaje" => "No autorizado"]);
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$datos = json_decode(file_get_contents("php://input"), true);

if (!$datos || empty($datos['textos'])) {
    echo json_encode(["mensaje" => "No se recibieron respuestas"]);
    exit();
}

$todosLosTextos = $datos['textos'];

// ── Contadores globales ───────────────────────────────────
$correctasPorNivel     = ['literal' => 0, 'inferencial' => 0, 'critico' => 0];
$totalPorNivel         = ['literal' => 0, 'inferencial' => 0, 'critico' => 0];
$totalCorrectasGlobal  = 0;
$totalPreguntasGlobal  = 0;
$todasLasOpciones      = []; // Acumula todos los ID de opciones seleccionadas

// Usamos el ID del primer texto como referencia del intento
// (el intento agrupa todas las respuestas de la sesión)
$idTextoReferencia = $todosLosTextos[0]['idTexto'];

// ── Procesar cada texto y sus respuestas ─────────────────
foreach ($todosLosTextos as $textoData) {
    $idTexto   = $textoData['idTexto'];
    $respuestas = $textoData['respuestas'];

    foreach ($respuestas as $idPregunta => $idOpcion) {

        if (!$idOpcion) continue; // Ignorar preguntas sin respuesta

        $stmt = $conexion->prepare("
            SELECT o.es_correcta, p.ID_nivelComprension
            FROM opcionpregunta o
            JOIN preguntalectura p ON o.ID_pregunta = p.ID
            WHERE o.ID = ? AND o.ID_pregunta = ?
        ");
        $stmt->bind_param("ii", $idOpcion, $idPregunta);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();

        if (!$fila) continue;

        switch ($fila['ID_nivelComprension']) {
            case 1: $nivel = 'literal';      break;
            case 2: $nivel = 'inferencial';  break;
            case 3: $nivel = 'critico';      break;
            default: $nivel = 'literal';
        }

        $totalPorNivel[$nivel]++;
        $totalPreguntasGlobal++;

        if ($fila['es_correcta']) {
            $correctasPorNivel[$nivel]++;
            $totalCorrectasGlobal++;
        }

        $todasLasOpciones[] = (int)$idOpcion;
    }
}

if ($totalPreguntasGlobal === 0) {
    echo json_encode(["mensaje" => "No se respondió ninguna pregunta"]);
    exit();
}

// ── Calcular puntajes ─────────────────────────────────────
$puntajeGlobal = round(($totalCorrectasGlobal / $totalPreguntasGlobal) * 100);

$puntajeLiteral     = $totalPorNivel['literal']     > 0 ? round(($correctasPorNivel['literal']     / $totalPorNivel['literal'])     * 100) : null;
$puntajeInferencial = $totalPorNivel['inferencial'] > 0 ? round(($correctasPorNivel['inferencial'] / $totalPorNivel['inferencial']) * 100) : null;
$puntajeCritico     = $totalPorNivel['critico']     > 0 ? round(($correctasPorNivel['critico']     / $totalPorNivel['critico'])     * 100) : null;

// ── 1) UN SOLO INSERT en intentolectura ───────────────────
$stmtIntento = $conexion->prepare("
    INSERT INTO intentolectura
        (ID_usuario, ID_texto, puntaje_total, respuestas_correctas,
         total_preguntas, `tiempo(m)`, puntaje_literal, puntaje_inferencial, puntaje_critico)
    VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?)
");
$stmtIntento->bind_param(
    "iidiiddd",
    $id_usuario,
    $idTextoReferencia,
    $puntajeGlobal,
    $totalCorrectasGlobal,
    $totalPreguntasGlobal,
    $puntajeLiteral,
    $puntajeInferencial,
    $puntajeCritico
);
$stmtIntento->execute();

// ── 2) Obtener el ID del intento recién creado ────────────
$idIntento = $conexion->insert_id;

// ── 3) Insertar TODAS las respuestas con ese mismo ID ─────
$stmtRespuesta = $conexion->prepare("
    INSERT INTO respuestalectura (ID_opcionPregunta, ID_intentoLectura)
    VALUES (?, ?)
");

foreach ($todasLasOpciones as $idOpcion) {
    $stmtRespuesta->bind_param("ii", $idOpcion, $idIntento);
    $stmtRespuesta->execute();
}

echo json_encode([
    "mensaje"  => "Respuestas guardadas correctamente",
    "puntaje"  => $puntajeGlobal,
    "correctas" => $totalCorrectasGlobal,
    "total"    => $totalPreguntasGlobal
]);
?>