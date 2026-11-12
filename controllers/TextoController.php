<?php

session_start();

require_once(__DIR__ . "/../config/router.php");
require_once(__DIR__ . "/../config/guardias.php");

require_once(__DIR__ . "/../models/TextoModel.php");
require_once(__DIR__ . "/../models/PreguntaModel.php");
require_once(__DIR__ . "/../models/OpcionPreguntaModel.php");
require_once(__DIR__ . "/../models/NivelDificultadModel.php");
require_once(__DIR__ . "/../models/TipoTextoModel.php");

$accion = Router::resolver(
    "mostrarVistaGestionarTextos",
    [
        "mostrarVistaGestionarTextos" => "adminPagina",
        "mostrarVistaActualizar" => "adminPagina",
        "obtenerTextoPorId" => "admin",
        "listarTextos" => "admin",
        "obtenerCombos" => "admin",
        "agregarTextoManualmente" => "admin",
        "invertirEstadoTexto" => "admin",
        "actualizarTexto" => "admin"
    ]
);

// La conexión se abre DESPUÉS de autorizar.
require_once(__DIR__ . "/../config/conexion.php");


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

        Guardias::exigirAdmin();

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

        Guardias::exigirAdmin();

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

        Guardias::exigirAdmin();

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

    Guardias::exigirAdmin();

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


    /**
     * Devuelve un texto en JSON. Lo usa el formulario de edición para
     * llenarse solo, igual que el de logros y el de niveles.
     */
    public function obtenerTextoPorId(){

        Guardias::exigirAdmin();

        if(!isset($_GET['id'])){

            echo json_encode([
                "success" => false,
                "mensaje" => "ID no recibido"
            ]);

            return;
        }

        $texto =
            $this->TextoModel
                ->obtenerTextoPorId(
                    intval($_GET['id'])
                );

        if(!$texto){

            echo json_encode([
                "success" => false,
                "mensaje" => "Texto no encontrado"
            ]);

            return;
        }

        header('Content-Type: application/json');

        echo json_encode([
            "success" => true,
            "texto" => $texto
        ]);
    }

    public function mostrarVistaActualizar(){

        Guardias::exigirAdmin(false);

        $id = $_GET['id'];

        $texto = $this->TextoModel
            ->obtenerTextoPorId($id);

        include(__DIR__ . "/../views/actualizarTexto.php");
    }

    public function mostrarVistaGestionarTextos(){

        Guardias::exigirAdmin(false);

        include(__DIR__ . "/../views/gestionarTextos.php");
    }

    public function actualizarTexto(){

        Guardias::exigirAdmin();

        header('Content-Type: application/json');

        $datos =
            json_decode(
                file_get_contents(
                    "php://input"
                ),
                true
            );

        if(!$datos){

            echo json_encode([
                "success" => false,
                "mensaje" => "No se recibieron datos"
            ]);

            return;
        }

        $dificultad = isset($datos['dificultad'])
            ? intval($datos['dificultad'])
            : null;

        $tipo_texto = isset($datos['tipo_texto'])
            ? intval($datos['tipo_texto'])
            : null;

        $titulo = isset($datos['titulo'])
            ? trim($datos['titulo'])
            : null;

        $contenido = isset($datos['contenido'])
            ? trim($datos['contenido'])
            : null;

        // La fuente es opcional: si viene vacía se guarda como NULL y
        // no como la cadena "null".
        $fuente = isset($datos['fuente'])
            ? trim($datos['fuente'])
            : "";

        if($fuente === "") {
            $fuente = null;
        }

        $estado = isset($datos['estado'])
            ? trim($datos['estado'])
            : null;

        $id = isset($datos['ID'])
            ? intval($datos['ID'])
            : null;

        if(
            !$dificultad ||
            !$tipo_texto ||
            !$titulo ||
            !$contenido ||
            !$estado ||
            !$id
        ){
            echo json_encode([
                "success" => false,
                "mensaje" => "Todos los campos son obligatorios"
            ]);

            return;
        }

        $id_admin = $_SESSION['id_admin'];

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

            echo json_encode([
                "success" => true,
                "mensaje" => "Texto actualizado correctamente"
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "mensaje" => "Error al actualizar"
            ]);
        }
    }


}

$controller = new TextoController($conexion);

$controller->$accion();

?>