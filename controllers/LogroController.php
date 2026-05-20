<?php


class LogroController{
    private $LogroModel;

    public function __construct($conexion){
        $this->LogroModel = new LogroModel($conexion);
    }

    public function listarLogros(){
        $logros = $this->LogroModel->obtenerTodosLosLogros();

        include("../views/gestionarLogros.php");
    }

    public function agregarLogros(){
        $nombre = isset($_POST['nombre'])        ? trim($_POST['nombre'])       : null;
        $descripcion = isset($_POST['descripcion'])   ? trim($_POST['descripcion'])  : null;
        $recompensa  = isset($_POST['recompensa_xp']) ? (int)$_POST['recompensa_xp'] : null;
        $idAdmin = $_SESSION['id_admin'];
        $estado = isset($_POST['estado']) ? trim($_POST['estado']) : null;

        $resultado = $this->LogroModel->agregarLogro(
            $nombre,
            $descripcion,
            $recompensa,
            $idAdmin,
            $estado
        );

        if($resultado){
            header("Location: LogroController.php");
            exit();

        } else {
            echo "Error -  Insertar";
        }
    }

    public function actualizarLogros(){
        $id = isset($_POST['ID'])        ? trim($_POST['ID'])       : null;
        $nombre = isset($_POST['nombre'])        ? trim($_POST['nombre'])       : null;
        $descripcion = isset($_POST['descripcion'])   ? trim($_POST['descripcion'])  : null;
        $recompensa  = isset($_POST['recompensa_xp']) ? (int)$_POST['recompensa_xp'] : null;
        $estado = isset($_POST['estado']) ? trim($_POST['estado']) : null;
        $idAdmin = $_SESSION['id_admin'];

        $resultado = $this->LogroModel->actualizarLogro(
            $id,
            $nombre,
            $descripcion,
            $recompensa,
            $idAdmin,
            $estado

        );

        if($resultado){
            header("Location: LogroController.php");
            exit();

        } else {
            echo "Error - Actualizar";
        }
    }

    public function invertirEstado(){
        $id = $_POST['ID'];
        $estadoActual = $_POST['estado'];

        $nuevoEstado =
            $estadoActual === "Activo"
            ? "Inactivo"
            : "Activo";

        $resultado = $this->LogroModel
            ->invertirEstadoLogro(
                $id,
                $nuevoEstado
            );

        if($resultado){

            header("Location: LogroController.php");
            exit();

        } else {

            echo "Error - Invertir estado";
        }    
    }
    
    public function mostrarVistaActualizar(){
        $id = $_GET['id'];

        $logro = $this->LogroModel->obtenerLogroPorId($id);

        include("../views/actualizarLogro.php");
    }
}

session_start();

require_once("../config/conexion.php");
require_once("../models/logroModel.php");

$controller = new LogroController($conexion);

$accion = $_GET['accion'] ?? 'listarLogros';
if(method_exists($controller, $accion)){
    $controller->$accion();
} else {
    echo "Accion no valida";
}

?>