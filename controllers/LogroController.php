<?php


class LogroController{
    private $LogroModel;

    public function __construct($conexion){
        $this->LogroModel = new LogroModel($conexion);
    }

    public function listarLogros(){
        $logros = $this->LogroModel->obtenerTodosLosLogros();

        echo json_encode([

            "success" => true,

            "logros" => $logros
        ]);    
    }

public function obtenerLogro(){

    header(
        'Content-Type: application/json'
    );

    $id =
        $_GET['id'];

    $logro =
        $this->LogroModel
            ->obtenerLogroPorId($id);

    if($logro){

        echo json_encode([

            "success" => true,

            "logro" => $logro
        ]);

    }else{

        echo json_encode([

            "success" => false,

            "mensaje" =>
                "Logro no encontrado"
        ]);
    }
}

public function agregarLogros(){

    header(
        'Content-Type: application/json'
    );

    $datos =
        json_decode(
            file_get_contents("php://input"),
            true
        );

    $nombre =
        trim(
            $datos['nombre']
        );

    $descripcion =
        trim(
            $datos['descripcion']
        );

    $recompensa =
        (int)$datos['recompensa_xp'];

    $estado =
        trim(
            $datos['estado']
        );

    $idAdmin =
        $_SESSION['id_admin'];

    $resultado =
        $this->LogroModel
            ->agregarLogro(

                $nombre,

                $descripcion,

                $recompensa,

                $idAdmin,

                $estado
            );

    if($resultado){

        echo json_encode([

            "success" => true,

            "mensaje" =>
                "Logro agregado correctamente"
        ]);

    }else{

        echo json_encode([

            "success" => false,

            "mensaje" =>
                "Error al insertar logro"
        ]);
    }
}

    public function actualizarLogros(){

    header(
        'Content-Type: application/json'
    );

    $datos =
        json_decode(
            file_get_contents("php://input"),
            true
        );

    $resultado =
        $this->LogroModel
            ->actualizarLogro(

                $datos['ID'],

                $datos['nombre'],

                $datos['descripcion'],

                $datos['recompensa_xp'],

                $_SESSION['id_admin'],

                $datos['estado']
            );

    if($resultado){

        echo json_encode([

            "success" => true

        ]);

    }else{

        echo json_encode([

            "success" => false,

            "mensaje" =>
                "No se pudo actualizar"
        ]);
    }
}

    public function invertirEstado(){


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
            $datos['estadoActual']
        );

        if($estado == "Activo"){
            $nuevoEstado = "Inactivo";
        } else {
            $nuevoEstado = "Activo";
        }
        
        $resultado = $this->LogroModel
            ->invertirEstadoLogro(
                $id,
                $nuevoEstado
            );

        if($resultado){

            echo json_encode([

                "success" => true,

                "mensaje" =>
                    "Logro con estado invertido correctamente"
            ]);

        }else{

            echo json_encode([

                "success" => false,

                "mensaje" =>
                    "Error al invertir estado del logro"
            ]);
        }    
    }
    
    public function mostrarVistaActualizar(){
        $id = $_GET['id'];

        $logro = $this->LogroModel->obtenerLogroPorId($id);

        include("../views/actualizarLogro.php");
    }

    public function mostrarVistaGestionarLogros(){

        include("../views/gestionarLogros.php");     
    }
}

session_start();

require_once("../config/conexion.php");
require_once("../models/logroModel.php");

$controller = new LogroController($conexion);

$accion = $_GET['accion'] ?? 'mostrarVistaGestionarLogros';
if(method_exists($controller, $accion)){
    $controller->$accion();
} else {
    echo "Accion no valida";
}

?>