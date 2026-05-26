<?php

session_start();

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

    public function logout(){

        header(
            'Content-Type: application/json'
        );

        session_unset();

        session_destroy();

        echo json_encode([

            "success" => true
        ]);
    }

    public function registerUsuario(){
        $datos = json_decode(
            file_get_contents(
                "php://input"
            ),
            true
        );

        $nombre = trim($datos['nombre']);
        $correo = trim($datos['correo']);
        $contrasenia = trim($datos['contrasenia']);
        $confirmar_contrasenia = trim($datos['confirmar_contrasenia']);

        $usuario = $this->UsuarioModel->buscarPorCorreo($correo);

        if($usuario){
            echo json_encode([
                "success" => false,
                "mensaje" =>
                    "Correo ya existente. Intenta iniciar sesión o probar con otro correo."
            ]);

            return;            
        }

        if(!$contrasenia == $confirmar_contrasenia){
            echo json_encode([
                "success" => false,
                "mensaje" =>
                    "Las contraseñas no coinciden."
            ]);  
            
            return;
        }

        if(strlen($contrasenia) < 8){

            echo json_encode([

                "success" => false,

                "mensaje" =>
                    "Contraseña debe ser superior a 8 caracteres."
            ]);

            return;
        }

        $contrasenia_hash = password_hash(
            $contrasenia, PASSWORD_DEFAULT
        );

        $resultado = $this->UsuarioModel->crearUsuario($nombre, $correo, $contrasenia_hash);
        if($resultado){

            echo json_encode([

                "success" => true,

                "mensaje" =>
                    "Usuario registrado correctamente"
            ]);

        }else{

            echo json_encode([

                "success" => false,

                "mensaje" =>
                    "Error al registrar usuario"
            ]);
        }

    }

    public function loginUsuario()
    {

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

        $fechaActual =
            date("Y-m-d");

        $this->UsuarioModel->actualizarUltimaVez($usuario['ID'], $fechaActual);

    }


    public function loginAdmin()
    {

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
            $this->AdminModel
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

        $_SESSION['id'] =
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