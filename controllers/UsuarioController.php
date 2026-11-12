<?php
require_once(__DIR__ . "/../config/router.php");
require_once(__DIR__ . "/../config/guardias.php");
require_once(__DIR__ . "/../models/UsuarioModel.php");
require_once(__DIR__ . "/../models/LogroModel.php");

$accion = Router::resolver(
    "obtenerPerfil",
    [
        "obtenerPerfil" => "usuario",
        "obtenerLogrosUsuario" => "usuario"
    ]
);

// La conexión se abre DESPUÉS de autorizar.
require_once(__DIR__ . "/../config/conexion.php");

class UsuarioController {

    private $UsuarioModel;
    private $LogroModel;

    public function __construct($conexion){

        $this->UsuarioModel = new UsuarioModel($conexion);
        $this->LogroModel = new LogroModel($conexion);
    }

public function obtenerPerfil()
{
    Guardias::exigirUsuario();

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

    Guardias::exigirUsuario();

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

$controller->$accion();

?>