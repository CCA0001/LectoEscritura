<?php
require_once("../config/conexion.php");
require_once("../models/UsuarioModel.php");
require_once("../models/AdminModel.php");

class AuthController
{

    private $UsuarioModel;
    private $AdminModel;

    public function __construct($conexion)
    {
        $this->UsuarioModel =
            new UsuarioModel($conexion);

        $this->AdminModel =
            new AdminModel($conexion);
    }

    public function loginUsuario()
    {
        session_start();

        $datos = json_decode(
            file_get_contents(
                "php://input"
            ),
            true
        );

        $correo =
            trim(
                $datos['correo']
            );

        $contrasenia =
            $datos['contrasenia'];

        $usuario =
            $this->UsuarioModel
                ->buscarPorCorreo(
                    $correo
                );

        if(!$usuario){

            echo json_encode([
                "success" => false,
                "mensaje" =>
                    "Usuario no encontrado"
            ]);

            return;
        }

        if(
            !password_verify(
                $contrasenia,
                $usuario['contrasenia_hash']
            )
        ){

            echo json_encode([
                "success" => false,
                "mensaje" =>
                    "Contraseña incorrecta"
            ]);

            return;
        }

        $_SESSION['id_usuario'] =
            $usuario['ID'];

        $_SESSION['nombre_usuario'] =
            $usuario['nombre_usuario'];

        echo json_encode([
            "success" => true,
            "mensaje" =>
                "Inicio de sesión exitoso"
        ]);
    }
}

$controller = new AuthController($conexion);

$accion = $_GET['accion'] ?? 'loginUsuario';

if(method_exists($controller, $accion)){

    $controller->$accion();

}else{

    echo "Acción no válida";
}


?>