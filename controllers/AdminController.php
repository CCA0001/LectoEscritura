<?php

session_start();

require_once("../config/conexion.php");
require_once("../models/AdminModel.php");

class AdminController {

    private $AdminModel;

    public function __construct($conexion){

        $this->AdminModel = new AdminModel($conexion);
    }

    public function listarAdministradores(){

        $admins = $this->AdminModel
            ->obtenerTodosLosAdministradores();

        include("../views/gestionarAdministradores.php");
    }

    public function agregarAdministrador(){

        $nombre = isset($_POST['nombre'])
            ? trim($_POST['nombre'])
            : null;

        $nombre_usuario = isset($_POST['nombre_usuario'])
            ? trim($_POST['nombre_usuario'])
            : null;

        $correo = isset($_POST['correo_electronico'])
            ? trim($_POST['correo_electronico'])
            : null;

        $password = isset($_POST['contrasenia'])
            ? $_POST['contrasenia']
            : null;

        $password_confirmar = isset($_POST['contrasenia_confirmar'])
            ? $_POST['contrasenia_confirmar']
            : null;

        $estado = isset($_POST['estado'])
            ? $_POST['estado']
            : null;
        if(
            !$nombre ||
            !$nombre_usuario ||
            !$correo ||
            !$password ||
            !$password_confirmar ||
            !$estado
        ){

            echo "Todos los campos son obligatorios";
            return;
        }

        if(strlen($password) < 8){

            echo "La contraseña debe tener mínimo 8 caracteres";
            return;
        }

        if($password !== $password_confirmar){

            echo "Las contraseñas no coinciden";
            return;
        }

        $partes = explode(" ", $nombre);

        $nombres = $partes[0] ?? '';
        $apellidos = $partes[1] ?? '';

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

            header(
                "Location: AdminController.php?accion=listarAdministradores"
            );

            exit();

        } else {

            echo "Ya existe un administrador con ese correo";
        }
    }

    public function invertirEstadoAdmin(){

        $id = $_POST['ID'];
        $estadoActual = $_POST['estado'];

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

            header(
                "Location: AdminController.php?accion=listarAdministradores"
            );

            exit();

        } else {

            echo "Error al desactivar administrador";
        }
    }

    public function mostrarVistaActualizar(){
        $id = $_GET['id'];

        $admin = $this->AdminModel->obtenerAdminPorId($id);

        include("../views/actualizarAdmin.php");
    }
}

$controller = new AdminController($conexion);

$accion = $_GET['accion'] ?? 'listarAdministradores';

if(method_exists($controller, $accion)){

    $controller->$accion();

}else{

    echo "Acción no válida";
}

?>