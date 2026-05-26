<?php
require_once("../config/conexion.php");
require_once("../models/UsuarioModel.php");
require_once("../models/LogroModel.php");

class UsuarioController {

    private $UsuarioModel;
    private $LogroModel;

    public function __construct($conexion){

        $this->UsuarioModel = new UsuarioModel($conexion);
        $this->LogroModel = new LogroModel($conexion);
    }

public function obtenerPerfil()
{
    session_start();

    $idUsuario =
        $_SESSION['id_usuario'];

    $usuario =
        $this->UsuarioModel
            ->obtenerPerfilCompleto(
                $idUsuario
            );

    $logros = 
        $this->UsuarioModel->obtenerLogrosUsuario($idUsuario);

    
    echo json_encode([

        "success" => true,

        "usuario" => [

            "nombre" =>
                $usuario['nombre_usuario'],

            "nivel" =>
                $usuario['nivel'],

            "racha" =>
                $usuario['dias_racha'],

            "xp" =>
                $usuario['puntos_xp'],

            "logros" =>
                $logros

        ]

    ]);
}

public function obtenerLogrosUsuario(){

    session_start();

    header('Content-Type: application/json');

    $idUsuario =
        $_SESSION['id_usuario'];

    $todos =
        $this->LogroModel
            ->obtenerTodosLosLogros();

    $desbloqueados =
        $this->UsuarioModel
            ->obtenerLogrosUsuario(
                $idUsuario
            );

    echo json_encode([

        "success" => true,

        "todos" => $todos,

        "desbloqueados" => $desbloqueados
    ]);

}

}

$controller = new UsuarioController($conexion);

$accion = $_GET['accion'] ?? 'obtenerPerfil';

if(method_exists($controller, $accion)){

    $controller->$accion();

}else{

    echo "Acción no válida";
}

?>