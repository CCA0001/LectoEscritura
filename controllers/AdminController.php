<?php

session_start();

require_once(__DIR__ . "/../config/router.php");
require_once(__DIR__ . "/../models/AdminModel.php");

$accion = Router::resolver(
    "mostrarVistaGestionarAministradores",
    [
        "mostrarVistaGestionarAministradores" => "adminPagina",
        "mostrarVistaActualizar" => "adminPagina",
        "obtenerAdmin" => "admin",
        "listarAdministradores" => "admin",
        "agregarAdministrador" => "admin",
        "invertirEstadoAdmin" => "admin",
        "actualizarAdmin" => "admin"
    ]
);

// La conexión con la base de datos se abre DESPUÉS de autorizar: así
// una petición no autorizada se rechaza sin tocar la BD.
require_once(__DIR__ . "/../config/conexion.php");

class AdminController {

    private $AdminModel;

    public function __construct($conexion){

        $this->AdminModel = new AdminModel($conexion);
    }

    public function obtenerAdmin(){

        Guardias::exigirAdmin();

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

        Guardias::exigirAdmin();

        $admins = $this->AdminModel
            ->obtenerTodosLosAdministradores();

        echo json_encode([

            "success" => true,

            "admins" => $admins
        ]);        
    }

    public function agregarAdministrador(){

    Guardias::exigirAdmin();

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

        Guardias::exigirAdmin();

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

        Guardias::exigirAdmin();

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

        Guardias::exigirAdmin(false);

        include(__DIR__ . "/../views/actualizarAdmin.php");
    }

    public function mostrarVistaGestionarAministradores(){

        Guardias::exigirAdmin(false);

        include(__DIR__ . "/../views/gestionarAdministradores.php");
    }
}


$controller = new AdminController($conexion);

$controller->$accion();

?>