<?php
require_once("../config/conexion.php");
require_once("../models/UsuarioModel.php");

class UsuarioController {

    private $UsuarioModel;

    public function __construct($conexion){

        $this->UsuarioModel = new UsuarioModel($conexion);
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

    echo json_encode([

        "success" => true,

        "usuario" => [

            "nombre" =>
                $usuario['u.nombre_usuario'],

            "nivel" =>
                $usuario['d.nombre'],

            "racha" =>
                $usuario['dias_racha'],

            "xp" =>
                $usuario['puntos_xp'],

            "logros" =>
                $usuario['logros']

        ]

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