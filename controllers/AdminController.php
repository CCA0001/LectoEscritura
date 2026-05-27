<?php

session_start();

require_once("../config/conexion.php");
require_once("../models/AdminModel.php");

class AdminController {

    private $AdminModel;

    public function __construct($conexion){

        $this->AdminModel = new AdminModel($conexion);
    }

    public function obtenerAdmin(){
        $id = $_GET['id'];

        $adminActual = $this->AdminModel->obtenerAdminPorId($id);


        if($adminActual){
            echo json_encode([
                "success" => true,
                "admin" => $adminActual
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "mensaje" => "Error trayendo el admin"
            ]);
        }
    }

    public function listarAdministradores(){

        $admins = $this->AdminModel
            ->obtenerTodosLosAdministradores();

        echo json_encode([

            "success" => true,

            "admins" => $admins
        ]);        
    }

    public function agregarAdministrador(){

    header(
        'Content-Type: application/json'
    );

    $datos =
        json_decode(
            file_get_contents("php://input"),
            true
        );

        $nombres = $datos['nombres'];

        $apellidos = $datos['apellidos'];

        $nombre_usuario = trim($datos['nombre_usuario']);

        $correo = trim($datos['correo']);

        $password = $datos['contrasenia'];

        $confirmar_password = $datos['confirmar_contrasenia'];

        $estado = $datos['estado'];
  
        if(
            !$nombres ||
            !$apellidos ||
            !$nombre_usuario ||
            !$correo ||
            !$password ||
            !$confirmar_password ||
            !$estado
        ){

            echo json_encode([

                "success" => false,

                "mensaje" =>
                    "Todos los campos son obligatorios"
            ]);

            return;
        }

        if(strlen($password) < 8){

            echo json_encode([

                "success" => false,

                "mensaje" =>
                    "Contraseña debe ser superior a 8 caracteres"
            ]);

            return;
        }

        if($password !== $confirmar_password){

            echo json_encode([

                "success" => false,

                "mensaje" =>
                    "Las contraseñas no coinciden"
            ]);

            return;
        }

        $contrasenia_hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $resultado = $this->AdminModel
            ->agregarAdministrador(
                $nombres,
                $apellidos,
                $nombre_usuario,
                $correo,
                $contrasenia_hash,
                $estado
            );

        if($resultado){

            echo json_encode([

                "success" => true,

                "mensaje" =>
                    "admin agregado correctamente"
            ]);

        }else{

            echo json_encode([

                "success" => false,

                "mensaje" =>
                    "Error al insertar admin"
            ]);
        }
        }

    public function invertirEstadoAdmin(){


        header(
        'Content-Type: application/json'
        );

        $datos =
        json_decode(
            file_get_contents("php://input"),
            true
        );

        $id = $datos['ID'];
        $estadoActual = $datos['estadoActual'];

        $nuevoEstado =
            $estadoActual === "Activo"
            ? "Inactivo"
            : "Activo";

        $resultado = $this->AdminModel
            ->invertirEstadoAdmin(
                $id,
                $nuevoEstado
            );

        if($resultado){

            echo json_encode([

                "success" => true,

                "mensaje" =>
                    "Admin con estado invertido correctamente"
            ]);

        }else{

            echo json_encode([

                "success" => false,

                "mensaje" =>
                    "Error al invertir estado del admin"
            ]);
        }
    }

    public function actualizarAdmin(){
            
        header(
            'Content-Type: application/json'
        );

        $datos =
            json_decode(
                file_get_contents("php://input"),
                true
            );

        $adminActual = $this->AdminModel->obtenerAdminPorId($datos['ID']);

        if(!empty($datos['contrasenia'])){
            if($datos['contrasenia'] == $datos['confirmar_contrasenia']){
                    $contrasenia_hash = password_hash(
                        $datos['contrasenia'],
                        PASSWORD_DEFAULT
                );

                $contrasenia = $contrasenia_hash;
            } else {
                echo json_encode([
                    "success" => false,
                    "mensaje" => "Las contraseñas no coinciden"
                ]);
            }
        } else {
            $contrasenia = $adminActual['contrasenia_hash'];
        }

        $resultado =
            $this->AdminModel
                ->actualizarAdmin(

                    $datos['ID'],

                    $datos['nombres'],

                    $datos['apellidos'],

                    $datos['nombre_usuario'],

                    $contrasenia,

                    $datos['correo'],

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

    public function mostrarVistaActualizar(){

        include("../views/actualizarAdmin.php");
    }

    public function mostrarVistaGestionarAministradores(){

        include("../views/gestionarAdministradores.php");
    }
}


$controller = new AdminController($conexion);

$accion = $_GET['accion'] ?? 'mostrarVistaGestionarAministradores';

if(method_exists($controller, $accion)){

    $controller->$accion();

}else{

    echo "Acción no válida";
}

?>