<?php

session_start();

require_once(__DIR__ . "/../config/router.php");
require_once(__DIR__ . "/../config/guardias.php");

require_once(__DIR__ . "/../models/TextoModel.php");
require_once(__DIR__ . "/../models/PreguntaModel.php");
require_once(__DIR__ . "/../models/OpcionPreguntaModel.php");
require_once(__DIR__ . "/../models/IntentoLecturaModel.php");
require_once(__DIR__ . "/../models/RespuestaLecturaModel.php");
require_once(__DIR__ . "/../models/UsuarioModel.php");

$accion = Router::resolver(
    "mostrarModuloLectura",
    [
        "mostrarModuloLectura" => "usuarioPagina",
        "obtenerTextoFacilAleatorio" => "usuario",
        "obtenerTextosDificilesAleatorios" => "usuario",
        "guardarIntentoLectura" => "usuario"
    ]
);

// La conexión se abre DESPUÉS de autorizar.
require_once(__DIR__ . "/../config/conexion.php");

class ModuloLecturaController {

    private $TextoModel;
    private $PreguntaModel;
    private $OpcionPreguntaModel;
    private $IntentoLecturaModel;
    private $RespuestaLecturaModel;
    private $UsuarioModel;

    public function __construct($conexion){

        $this->TextoModel =
            new TextoModel($conexion);

        $this->PreguntaModel =
            new PreguntaModel($conexion);

        $this->OpcionPreguntaModel =
            new OpcionPreguntaModel($conexion);

        $this->IntentoLecturaModel = 
            new IntentoLecturaModel($conexion);
        
        $this->RespuestaLecturaModel =
            new RespuestaLecturaModel($conexion);

        $this->UsuarioModel =
            new UsuarioModel($conexion);
    }    
    
    public function obtenerTextoFacilAleatorio(){

        Guardias::exigirUsuario();

        $texto = $this->TextoModel->obtenerTextoFacil();

        $preguntas = $this->PreguntaModel->obtenerPreguntasPorTexto(
            $texto['ID']
        );

        foreach($preguntas as &$pregunta){
            $pregunta['opciones'] =
                $this->OpcionPreguntaModel->obtenerOpcionesPorPregunta(
                    $pregunta['ID']
                );
        }

        $texto['preguntas'] = $preguntas;

        header('Content-Type: application/json');

        echo json_encode([
            "texto" => $texto,
            "preguntas" => $preguntas
        ]);    
    }

    public function obtenerTextosDificilesAleatorios(){

        Guardias::exigirUsuario();

        $textos = $this->TextoModel->obtenerTextoAvanzado();

        foreach($textos as &$texto){
            $preguntas = 
                $this->PreguntaModel->obtenerPreguntasPorTexto(
                    $texto['ID']
                );

        foreach($preguntas as &$pregunta){
            $pregunta['opciones'] =
                $this->OpcionPreguntaModel->obtenerOpcionesPorPregunta(
                    $pregunta['ID']
                );
        }

        $texto['preguntas'] = $preguntas;
        }

        header('Content-Type: application/json');

        echo json_encode([
            "textos" => $textos
        ]);
    }

    public function guardarIntentoLectura(){

        Guardias::exigirUsuario();

        $datos =
            json_decode(
                file_get_contents("php://input"),
                true
            );

        $totalCorrectas = 0;
        $totalPreguntas = 0;

        $totalLiteral = 0;
        $totalInferencial = 0;
        $totalCritico = 0;

        $correctasLiteral = 0;
        $correctasInferencial = 0;
        $correctasCritico = 0;

        $todasLasRespuestas = [];

        foreach($datos['textos'] as $textoRespondido){

            $idTexto =
                $textoRespondido['idTexto'];

            $respuestas =
                $textoRespondido['respuestas'];

            foreach($respuestas as $idPregunta => $idOpcion){

                $totalPreguntas++;

                $pregunta =
                    $this->PreguntaModel
                        ->obtenerPreguntaPorID(
                            $idPregunta
                        );

                $nivel =
                    $pregunta['ID_nivelComprension'];

                switch($nivel){

                    case 1:

                        $totalLiteral++;
                        break;

                    case 2:

                        $totalInferencial++;
                        break;

                    case 3:

                        $totalCritico++;
                        break;
                }

                if(!$idOpcion){
                    continue;
                }

                $esCorrecta =
                    $this->OpcionPreguntaModel
                        ->esOpcionCorrecta(
                            $idOpcion
                        );

                if($esCorrecta){

                    $totalCorrectas++;

                    switch($nivel){

                        case 1:

                            $correctasLiteral++;
                            break;

                        case 2:

                            $correctasInferencial++;
                            break;

                        case 3:

                            $correctasCritico++;
                            break;
                    }
                }

                $todasLasRespuestas[] = [

                    "idTexto" => $idTexto,

                    "idPregunta" => $idPregunta,

                    "idOpcion" => $idOpcion
                ];
            }
        }

        $puntajeGeneral =
            $totalPreguntas > 0
            ? round(($totalCorrectas / $totalPreguntas) * 100)
            : 0;

        $puntajeLiteral =
            $totalLiteral > 0
            ? round(($correctasLiteral / $totalLiteral) * 100)
            : 0;

        $puntajeInferencial =
            $totalInferencial > 0
            ? round(($correctasInferencial / $totalInferencial) * 100)
            : 0;

        $puntajeCritico =
            $totalCritico > 0
            ? round(($correctasCritico / $totalCritico) * 100)
            : 0;

        $idIntento =
            $this->IntentoLecturaModel
                ->guardarIntentoLectura(

                    $_SESSION['id_usuario'],

                    $puntajeGeneral,

                    $totalCorrectas,

                    $totalPreguntas,

                    0,

                    $puntajeLiteral,

                    $puntajeInferencial,

                    $puntajeCritico
                );      
                
        foreach($todasLasRespuestas as $respuesta){

            $this->RespuestaLecturaModel
                ->guardarRespuesta(

                    $respuesta['idOpcion'],

                    $idIntento,

                    $respuesta['idTexto'],

                    $respuesta['idPregunta']
                );

        }
        $this->UsuarioModel->sumarXp($_SESSION['id_usuario'],$puntajeGeneral);

        header('Content-Type: application/json');
        
        echo json_encode([

            "success" => true,

            "idIntento" => $idIntento,

            "puntajeGeneral" => $puntajeGeneral,

            "puntajeLiteral" => $puntajeLiteral,

            "puntajeInferencial" => $puntajeInferencial,

            "puntajeCritico" => $puntajeCritico,

            "totalCorrectas" => $totalCorrectas,

            "totalPreguntas" => $totalPreguntas,

            "mensaje" => "Intento guardado correctamente",

            "xp" => "Felicidades! Obtuviste ". strval($puntajeGeneral). " de experiencia por tu esfuerzo!"
        ]);
    }


    public function mostrarModuloLectura(){

        Guardias::exigirUsuario(false);

        include(__DIR__ . "/../views/moduloLectura.php");
    }        
    
}

$accion = Router::resolver(
    "mostrarModuloLectura",
    [
        "mostrarModuloLectura",
        "obtenerTextoFacilAleatorio",
        "obtenerTextosDificilesAleatorios",
        "guardarIntentoLectura"
    ],
    "usuario"
);

$controller = new ModuloLecturaController($conexion);

$controller->$accion();

?>