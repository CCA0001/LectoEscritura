<?php

session_start();

require_once("../config/conexion.php");
require_once("../models/NivelProgresoModel.php");

class NivelProgresoController {

    private $NivelProgresoModel;

    public function __construct($conexion){

        $this->NivelProgresoModel = new NivelProgresoModel($conexion);
    }

    public function listarNiveles(){

            header(
                'Content-Type: application/json'
            );

            $niveles =
                $this->NivelProgresoModel
                    ->obtenerTodosLosNiveles();

            echo json_encode([

                "success" => true,

                "niveles" => $niveles
            ]);
        }

    public function obtenerNivelPorID(){
    header(
        'Content-Type: application/json'
    );

    $id =
        $_GET['id'];

    $nivel =
        $this->NivelProgresoModel
            ->obtenerNivelPorId($id);

    if($nivel){

        echo json_encode([

            "success" => true,

            "nivel" => $nivel
        ]);

    }else{

        echo json_encode([

            "success" => false,

            "mensaje" =>
                "Nivel no encontrado"
        ]);
    }
    }

    public function agregarNivel(){

        header(
            'Content-Type: application/json'
        );

        $datos =
            json_decode(
                file_get_contents("php://input"),
                true
            );

        $resultado =
            $this->NivelProgresoModel
                ->agregarNivel(

                    $datos['nombre'],

                    $datos['xp_requerida'],

                    $_SESSION['id_admin'],

                    $datos['descripcion'],

                    $datos['estado']
                );

        if($resultado){

            echo json_encode([

                "success" => true,
                "mensaje" => "Nivel de progreso agregado satisfactoriamente"
            ]);

        }else{

            echo json_encode([

                "success" => false,

                "mensaje" =>
                    "No se pudo agregar nivel"
            ]);
        }
    }

    public function invertirEstadoNivel(){
    
        header(
            'Content-Type: application/json'
        );

        $datos =
            json_decode(
                file_get_contents("php://input"),
                true
            );



        $id = $datos['ID'];
        $estadoActual = $datos['estado'];

        $nuevoEstado =
            ($estadoActual === "Activo")
            ? "Inactivo"
            : "Activo";

        $resultado = $this->NivelProgresoModel
            ->invertirEstadoNivel(
                $id,
                $nuevoEstado
            );

        if($resultado){

            echo json_encode([

                "success" => true,
                "mensaje" => "Estado invertido satisfactoriamente"
            ]);

        }else{

            echo json_encode([

                "success" => false,

                "mensaje" =>
                    "No se pudo invertir el estado"
            ]);
        }
    }




    public function mostrarVistaActualizar(){

        $id = $_GET['id'];

        $nivel = $this->NivelProgresoModel
            ->obtenerNivelPorId($id);

        include("../views/actualizarNivel.php");
    }

    public function actualizarNivel(){
    
        header(
            'Content-Type: application/json'
        );

        $datos =
            json_decode(
                file_get_contents("php://input"),
                true
            );


            $id = $datos['ID'];
    
            $nombre = $datos['nombre'];
    
            $xp = $datos['recompensa_xp'];
    
            $descripcion = $datos['descripcion'];
    
            $estado = $datos['estado'];
    
            $id_Admin = $_SESSION['id_admin'];
    
            if(
                $id === null ||
                !$nombre ||
                $xp === null ||
                !$descripcion ||
                !$estado
            ){
                header(
                    "Location: NivelController.php?accion=mostrarVistaActualizar"
                    . "&id=" . $id
                    . "&error=" . urlencode("Todos los campos son obligatorios.")
                );
                exit();
            }
    
            $resultado = $this->NivelProgresoModel
                ->actualizarNivel(
                    $id,
                    $nombre,
                    $xp,
                    $id_Admin,
                    $descripcion,
                    $estado
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

        public function mostrarVistaGestionarNivelesProgreso(){
            include("../views/gestionarNivelesProgreso.php");
        }

}


$controller = new NivelProgresoController($conexion);

$accion = $_GET['accion'] ?? 'mostrarVistaGestionarNivelesProgreso';

if(method_exists($controller, $accion)){

    $controller->$accion();

}else{

    echo "Acción no válida";
}

?>