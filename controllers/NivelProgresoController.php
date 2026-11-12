<?php

session_start();

require_once(__DIR__ . "/../config/router.php");
require_once(__DIR__ . "/../config/guardias.php");
require_once(__DIR__ . "/../models/NivelProgresoModel.php");

$accion = Router::resolver(
    "mostrarVistaGestionarNivelesProgreso",
    [
        "mostrarVistaGestionarNivelesProgreso" => "adminPagina",
        "mostrarVistaActualizar" => "adminPagina",
        "listarNiveles" => "admin",
        "obtenerNivelPorID" => "admin",
        "agregarNivel" => "admin",
        "invertirEstadoNivel" => "admin",
        "actualizarNivel" => "admin"
    ]
);

// La conexión se abre DESPUÉS de autorizar.
require_once(__DIR__ . "/../config/conexion.php");

class NivelProgresoController {

    private $NivelProgresoModel;

    public function __construct($conexion){

        $this->NivelProgresoModel = new NivelProgresoModel($conexion);
    }

    public function listarNiveles(){

            Guardias::exigirAdmin();

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

    Guardias::exigirAdmin();

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

        Guardias::exigirAdmin();

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

        Guardias::exigirAdmin(false);

        $id = $_GET['id'];

        $nivel = $this->NivelProgresoModel
            ->obtenerNivelPorId($id);

        include(__DIR__ . "/../views/actualizarNivel.php");
    }

    public function actualizarNivel(){

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

            Guardias::exigirAdmin(false);

            include(__DIR__ . "/../views/gestionarNivelesProgreso.php");
        }

}


$controller = new NivelProgresoController($conexion);

$controller->$accion();

?>