<?php

session_start();

require_once("../config/conexion.php");
require_once("../models/NivelProgresoModel.php");

class NivelProgresoController {

    private $NivelModel;

    public function __construct($conexion){

        $this->NivelModel = new NivelProgresoModel($conexion);
    }

    public function listarNiveles(){

        $niveles = $this->NivelModel
            ->obtenerTodosLosNiveles();

        include("../views/gestionarNivelesProgreso.php");
    }

    public function agregarNivel(){

        $nivel = isset($_POST['nivel'])
            ? (int)$_POST['nivel']
            : null;

        $xp = isset($_POST['xp_requerida'])
            ? (int)$_POST['xp_requerida']
            : null;

        $id_Admin = $_SESSION['id_admin'];

        $descripcion = isset($_POST['descripcion'])
            ? trim($_POST['descripcion'])
            : null;

        $estado = isset($_POST['estado'])
            ? trim($_POST['estado'])
            : 'Activo';

        if(
            $nivel === null ||
            $xp === null ||
            !$estado ||
            !$descripcion
        ){
            echo "Todos los campos son obligatorios";
            return;
        }

        $resultado = $this->NivelModel
            ->agregarNivel(
                $nivel,
                $xp,
                $id_Admin,
                $descripcion,
                $estado
            );

        if($resultado){

            header(
                "Location: NivelProgresoController.php?accion=listarNiveles"
            );
            exit();
        }
    }

    public function invertirEstadoNivel(){
        $id = $_POST['ID'];

        $estadoActual = $_POST['estado'];

        $nuevoEstado =
            ($estadoActual === "Activo")
            ? "Inactivo"
            : "Activo";

        $resultado = $this->NivelModel
            ->invertirEstadoNivel(
                $id,
                $nuevoEstado
            );

        if($resultado){

            header(
                "Location: NivelController.php?accion=listarNiveles"
            );

            exit();

        } else {

            echo "Error al cambiar estado";
        }
    }




    public function mostrarVistaActualizar(){

        $id = $_GET['id'];

        $nivel = $this->NivelModel
            ->obtenerNivelPorId($id);

        include("../views/actualizarNivel.php");
    }
}


$controller = new NivelProgresoController($conexion);

$accion = $_GET['accion'] ?? 'listarNiveles';

if(method_exists($controller, $accion)){

    $controller->$accion();

}else{

    echo "Acción no válida";
}

?>