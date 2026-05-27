<?php

session_start();

require_once("../config/conexion.php");

require_once("../models/TextoModel.php");
require_once("../models/PreguntaModel.php");
require_once("../models/OpcionPreguntaModel.php");
require_once("../models/NivelDificultadModel.php");
require_once("../models/TipoTextoModel.php");


class TextoController {

    private $TextoModel;
    private $PreguntaModel;
    private $OpcionModel;
    private $dificultad;
    private $tipo;

    public function __construct($conexion){

        $this->TextoModel =
            new TextoModel($conexion);

        $this->PreguntaModel =
            new PreguntaModel($conexion);

        $this->OpcionModel =
            new OpcionPreguntaModel($conexion);
        

        $this->dificultad =
            new NivelDificultadModel($conexion);
        
        $this->tipo =
            new TipoTextoModel($conexion);
        }

    public function listarTextos(){

        header(
            'Content-Type: application/json'
        );

        $textos =
            $this->TextoModel
                ->obtenerTodosLosTextos();

        echo json_encode([

            "success" => true,

            "textos" => $textos
        ]);        
    }

    public function obtenerCombos()
    {

        $dificultades =
            $this->dificultad
                ->obtenerTodos();

        $tiposTexto =
            $this->tipo
                ->obtenerTodos();

        header(
            'Content-Type: application/json'
        );

        echo json_encode([
            'dificultades' => $dificultades,
            'tiposTexto' => $tiposTexto
        ]);

    }

    public function agregarTextoManualmente(){

        header(
            'Content-Type: application/json'
        );

        $datos =
            json_decode(
                file_get_contents("php://input"),
                true
            );

        $dificultad =
            trim(
                $datos['dificultad']
            );
        $tipo_texto =
            trim($datos['tipo_texto']);

        $titulo =
            trim(
                $datos['titulo']
            );

        $contenido =
            $datos['contenido'];

        $fuente =
            trim(
                $datos['fuente']
            );

        $estado =
            trim(
                $datos['estado']
            );

        $idAdmin =
            $_SESSION['id_admin'];

        $resultado =
            $this->TextoModel
                ->agregarTextoManualmente(

                    $dificultad,

                    $tipo_texto,

                    $titulo,

                    $contenido,

                    $fuente,
                    $idAdmin,

                    $estado
                );

        if($resultado){

            echo json_encode([

                "success" => true,

                "mensaje" =>
                    "Texto agregado correctamente"
            ]);

        }else{

            echo json_encode([

                "success" => false,

                "mensaje" =>
                    "Error al insertar Texto"
            ]);
        }
    }

    public function invertirEstadoTexto(){

    header(
        'Content-Type: application/json'
    );

    $datos =
        json_decode(
            file_get_contents("php://input"),
            true
        );

    $id =
        trim(
            $datos['ID']
        );   
    
    $estado =
        trim(
            $datos['estado']
        );

        if($estado == "Activo"){
            $nuevoEstado = "Inactivo";
        } else {
            $nuevoEstado = "Activo";
        }
        
        $resultado = $this->TextoModel
            ->invertirEstadoTexto(
                $id,
                $nuevoEstado
            );

        if($resultado){

            echo json_encode([

                "success" => true,

                "mensaje" =>
                    "Texto con estado invertido correctamente"
            ]);

        }else{

            echo json_encode([

                "success" => false,

                "mensaje" =>
                    "Error al invertir estado del Texto"
            ]);
        }    
    }


    public function mostrarVistaActualizar(){

        $id = $_GET['id'];

        $texto = $this->TextoModel
            ->obtenerTextoPorId($id);

        include("../views/actualizarTexto.php");
    }

    public function mostrarVistaGestionarTextos(){
        include("../views/gestionarTextos.php");
    }

    public function actualizarTexto(){

        $dificultad = isset($_POST['dificultad'])
            ? trim($_POST['dificultad'])
            : null;

        $tipo_texto = isset($_POST['tipo_texto'])
            ? trim($_POST['tipo_texto'])
            : null;
    
        $titulo = isset($_POST['titulo'])
            ? trim($_POST['titulo'])
            : null;

        $contenido = isset($_POST['contenido'])
            ? trim($_POST['contenido'])
            : null;

            
        $fuente = isset($_POST['fuente'])
            ? trim($_POST['fuente'])
            : 'null';

        $estado = isset($_POST['estado'])
            ? trim($_POST['estado'])
            : null;

        
        $id_admin = $_SESSION['id_admin'];
        $id = isset($_POST['ID']) ? trim($_POST['ID']) : null;
        if(
            !$dificultad ||
            !$tipo_texto ||
            !$titulo ||
            !$contenido ||
            !$fuente ||
            !$estado
        ){
            echo "Todos los campos son obligatorios";
            return;
        }


        $resultado = $this->TextoModel
            ->actualizarTexto(
                $dificultad,
                $tipo_texto,
                $titulo,
                $contenido,
                $fuente,
                $id_admin,
                $estado,
                $id
            );
        if($resultado){

            header(
                "Location: TextoController.php?accion=listarTextos"
            );

            exit();

        } else {

            echo "Error al actualizar";
        }
    }


}

$controller = new TextoController($conexion);

$accion = $_GET['accion'] ?? 'mostrarVistaGestionarTextos';

if(method_exists($controller, $accion)){

    $controller->$accion();

}else{

    echo "Acción no válida";
}

?>