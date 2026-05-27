<?php
session_start();
include("../config/conexion.php");
require_once("../models/ArchivoEscrituraModel.php");
require_once("../models/NivelDificultadModel.php");
require_once("../models/TipoTextoModel.php");
require_once("../services/IAEscrituraService.php");
require_once("../models/DetalleEjercicioEscrituraModel.php");

class ModuloEscrituraController{
    private $ArchivoEscrituraModel;
    private $NivelDificultadModel;
    private $TipoTextoModel;
    private $IAEscrituraService;
    private $DetalleEjercicioEscrituraModel;

    public function __construct($conexion){

        $this->ArchivoEscrituraModel =
            new ArchivoEscrituraModel($conexion);

        $this->NivelDificultadModel =
            new NivelDificultadModel($conexion);

        $this->TipoTextoModel =
            new TipoTextoModel($conexion);

        $this->DetalleEjercicioEscrituraModel =
            new DetalleEjercicioEscrituraModel($conexion);  
            
        $this->IAEscrituraService =
            new IAEscrituraService();    
    }

    public function mostrarModuloEscritura(){
        $archivos = $this->ArchivoEscrituraModel->obtenerArchivosPorUsuario($_SESSION['id_usuario']);
        $dificultades = $this->NivelDificultadModel->obtenerTodos();
        $tipos = $this->TipoTextoModel->obtenerTodos();
    
        include("../views/ejercicio_escritura.php");
        
    }

    public function subirArchivo(){

        header('Content-Type: application/json');

        if(!isset($_SESSION['id_usuario'])){

            echo json_encode([
                "success" => false,
                "mensaje" => "Usuario no autenticado"
            ]);

            return;
        }

        if(!isset($_FILES['archivo_pdf'])){

            echo json_encode([
                "success" => false,
                "mensaje" => "No se recibió archivo"
            ]);

            return;
        }

        $archivo =
            $_FILES['archivo_pdf'];

        if($archivo['error'] !== 0){

            echo json_encode([
                "success" => false,
                "mensaje" => "Error al subir archivo"
            ]);

            return;
        }

        $nombreOriginal =
            $archivo['name'];

        $tmp =
            $archivo['tmp_name'];

        $extension =
            strtolower(
                pathinfo(
                    $nombreOriginal,
                    PATHINFO_EXTENSION
                )
            );

        $extensionesPermitidas = [

            "pdf"
        ];

        if(
            !in_array(
                $extension,
                $extensionesPermitidas
            )
        ){

            echo json_encode([
                "success" => false,
                "mensaje" => "Formato no permitido"
            ]);

            return;
        }

        $nombreFinal =
            uniqid("escritura_") .
            "." .
            $extension;

        $rutaRelativa =
            "public/ejercicios/pdf_escritura" .
            $nombreFinal;

        $rutaCompleta =
            "../" .
            $rutaRelativa;

        if(
            !move_uploaded_file(
                $tmp,
                $rutaCompleta
            )
        ){

            echo json_encode([
                "success" => false,
                "mensaje" => "No se pudo mover el archivo"
            ]);

            return;
        }

        $idArchivo =
            $this->ArchivoEscrituraModel
                ->guardarArchivo(

                    $_SESSION['id_usuario'],
                    $rutaRelativa,
                    $nombreOriginal,
                    $_POST['ID_dificultad'],
                    $_POST['ID_tipoTexto']


                );

        if(!$idArchivo){

            echo json_encode([
                "success" => false,
                "mensaje" => "Error al guardar en DB"
            ]);

            return;
        }

        echo json_encode([

            "success" => true,

            "mensaje" =>
                "Archivo subido correctamente",

            "idArchivo" =>
                $idArchivo
        ]);
    }

    public function obtenerDificultades(){
        $dificultades =
            $this->NivelDificultadModel
                ->obtenerTodos();

        header('Content-Type: application/json');

        echo json_encode([
            "success" => true,
            "dificultades" => $dificultades
        ]);    
    }

    public function obtenerTiposTexto(){

        $tipos =
            $this->TipoTextoModel
                ->obtenerTodos();

        header('Content-Type: application/json');

        echo json_encode([
            "success" => true,
            "tipos" => $tipos
        ]);
    }

    public function obtenerArchivosUsuario(){

        if(!isset($_SESSION['id_usuario'])){

            echo json_encode([
                "success" => false,
                "mensaje" => "Usuario no autenticado"
            ]);

            header("Location: ../views/login.html");
            exit();
        }

        $archivos =
            $this->ArchivoEscrituraModel
                ->obtenerArchivosPorUsuario(
                    $_SESSION['id_usuario']
                );

        header('Content-Type: application/json');

        echo json_encode([
            "success" => true,
            "archivos" => $archivos
        ]);
    }  
    
    public function evaluarArchivo(){

        header('Content-Type: application/json');

        if(!isset($_SESSION['id_usuario'])){

            echo json_encode([
                "success" => false,
                "mensaje" => "Usuario no autenticado"
            ]);

            header("Location: ../views/login.html");
            exit();

            return;
        }
    
        if(!isset($_GET['id'])){

            echo json_encode([
                "success" => false,
                "mensaje" => "ID no recibido"
            ]);

            return;
        }

        $idArchivo = intval($_GET['id']);

        $archivo =
            $this->ArchivoEscrituraModel
                ->obtenerPorIdYUsuario($idArchivo, $_SESSION['id_usuario']);

        if(!$archivo){

            echo json_encode([
                "success" => false,
                "mensaje" => "Archivo no encontrado"
            ]);

            return;
        }

        $rutaCompleta = __DIR__."/../".$archivo['url_archivo'];


        $resultadoIA =
            $this->IAEscrituraService
                ->evaluarArchivo(
                    $rutaCompleta
                );

        if(isset($resultadoIA['error'])){

            echo json_encode([
                "success" => false,
                "mensaje" => $resultadoIA['error']
            ]);

            return;
        }
        
        $this->DetalleEjercicioEscrituraModel
            ->guardarEvaluacion(

                $idArchivo,

                $resultadoIA['coherencia'],

                $resultadoIA['cohesion'],

                $resultadoIA['gramatica'],

                $resultadoIA['argumentacion'],

                $resultadoIA['estructura'],

                $resultadoIA['retroalimentacion']
            );

        $this->ArchivoEscrituraModel
            ->actualizarPuntajePromedio(

                $idArchivo,

                $resultadoIA['puntaje_promedio']
            );   

        $this->UsuarioModel->sumarXp($_SESSION['id_usuario'],$resultadoIA['puntaje_promedio']);

        echo json_encode([
            "success" => true,
            "mensaje" => "Archivo evaluado correctamente",
            "puntaje" => $resultadoIA['puntaje_promedio']
        ]);
    }
}


    if (!isset($_SESSION['id_usuario'])) {
            header("Location: ../views/login.html");
            exit();
    }


$controller = new ModuloEscrituraController($conexion);

$accion = $_GET['accion'] ?? 'mostrarModuloEscritura';
if(method_exists($controller, $accion)){

    $controller->$accion();

}else{

    echo "Acción no válida";
}

?>