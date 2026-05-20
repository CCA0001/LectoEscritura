<?php

session_start();

require_once("../config/conexion.php");

require_once("../models/TextoModel.php");


class TextoController {

    private $TextoModel;
    private $PreguntaModel;
    private $OpcionModel;

    public function __construct($conexion){

        $this->TextoModel =
            new TextoModel($conexion);

        $this->PreguntaModel =
            new PreguntaModel($conexion);

        $this->OpcionModel =
            new OpcionRespuestaModel($conexion);
        }

    public function listarTextos(){

        $textos = $this->TextoModel
            ->obtenerTodosLosTextos();

        include("../views/gestionarTextos.php");
    }

    public function agregarTextoManualmente(){

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
            : 'Activo';

        $estado = isset($_POST['estado'])
            ? trim($_POST['estado'])
            : 'Activo';

        $id_admin = $_SESSION['id_admin'];

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
            ->agregarTextoManualmente(
                $dificultad,
                $tipo_texto,
                $titulo,
                $contenido,
                $fuente,
                $id_admin,
                $estado
            );

        if($resultado){

            header(
                "Location: TextoController.php?accion=listarTextos"
            );

            exit();

        } else {

            echo "Error al agregar texto";
        }
    }

    public function invertirEstadoTexto(){

        $id = $_POST['ID'];

        $estadoActual = $_POST['estado'];

        $nuevoEstado =
            ($estadoActual === "Activo")
            ? "Inactivo"
            : "Activo";

        $resultado = $this->TextoModel
            ->invertirEstadoTexto(
                $id,
                $nuevoEstado
            );

        if($resultado){

            header(
                "Location: TextoController.php?accion=listarTextos"
            );

            exit();

        } else {

            echo "Error al cambiar estado";
        }
    }


    public function mostrarVistaActualizar(){

        $id = $_GET['id'];

        $texto = $this->TextoModel
            ->obtenerTextoPorId($id);

        include("../views/actualizarTexto.php");
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

$accion = $_GET['accion'] ?? 'listarTextos';

if(method_exists($controller, $accion)){

    $controller->$accion();

}else{

    echo "Acción no válida";
}

?>