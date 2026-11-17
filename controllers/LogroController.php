<?php


class LogroController{
    private $LogroModel;

    public function __construct($conexion){
        $this->LogroModel = new LogroModel($conexion);
    }

    public function listarLogros(){

        Guardias::exigirAdmin();

        $logros = $this->LogroModel->obtenerTodosLosLogros();

        echo json_encode([

            "success" => true,

            "logros" => $logros
        ]);    
    }

public function obtenerLogro(){

    Guardias::exigirAdmin();

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

    Guardias::exigirAdmin();

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

    Guardias::exigirAdmin();

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

        Guardias::exigirAdmin(false);

        $id = $_GET['id'];

        $logro = $this->LogroModel->obtenerLogroPorId($id);

        include(__DIR__ . "/../views/actualizarLogro.php");
    }

    public function mostrarVistaGestionarLogros(){

        Guardias::exigirAdmin(false);

        include(__DIR__ . "/../views/gestionarLogros.php");     
    }
}

// Sesión con HttpOnly + SameSite (ver config/sesion.php).
require_once(__DIR__ . "/../config/sesion.php");

Sesion::iniciar();

require_once(__DIR__ . "/../config/router.php");
require_once(__DIR__ . "/../config/guardias.php");
// Ojo: el archivo real se llama LogroModel.php. En Windows la diferencia
// de mayúsculas pasa desapercibida, pero en Linux el require falla.
require_once(__DIR__ . "/../models/LogroModel.php");

$accion = Router::resolver(
    "mostrarVistaGestionarLogros",
    [
        "mostrarVistaGestionarLogros" => "adminPagina",
        "mostrarVistaActualizar" => "adminPagina",
        "listarLogros" => "admin",
        "obtenerLogro" => "admin",
        "agregarLogros" => "admin",
        "actualizarLogros" => "admin",
        "invertirEstado" => "admin"
    ]
);

// La conexión se abre DESPUÉS de autorizar.
require_once(__DIR__ . "/../config/conexion.php");

$controller = new LogroController($conexion);

$controller->$accion();

?>