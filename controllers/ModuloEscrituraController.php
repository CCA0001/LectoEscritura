<?php
session_start();
require_once(__DIR__ . "/../config/router.php");
require_once(__DIR__ . "/../config/guardias.php");
require_once(__DIR__ . "/../models/ArchivoEscrituraModel.php");
require_once(__DIR__ . "/../models/NivelDificultadModel.php");
require_once(__DIR__ . "/../models/TipoTextoModel.php");
require_once(__DIR__ . "/../models/UsuarioModel.php");
require_once(__DIR__ . "/../services/IAEscrituraService.php");
require_once(__DIR__ . "/../models/DetalleEjercicioEscrituraModel.php");

$accion = Router::resolver(
    "mostrarModuloEscritura",
    [
        "mostrarModuloEscritura" => "usuarioPagina",
        "subirArchivo" => "usuario",
        "verArchivo" => "usuario",
        "evaluarArchivo" => "usuario",
        "obtenerDificultades" => "usuario",
        "obtenerTiposTexto" => "usuario",
        "obtenerArchivosUsuario" => "usuario"
    ]
);

// La conexión se abre DESPUÉS de autorizar.
include(__DIR__ . "/../config/conexion.php");

class ModuloEscrituraController{
    private $ArchivoEscrituraModel;
    private $NivelDificultadModel;
    private $TipoTextoModel;
    private $UsuarioModel;
    private $IAEscrituraService;
    private $DetalleEjercicioEscrituraModel;

    public function __construct($conexion){

        $this->ArchivoEscrituraModel =
            new ArchivoEscrituraModel($conexion);

        $this->NivelDificultadModel =
            new NivelDificultadModel($conexion);

        $this->TipoTextoModel =
            new TipoTextoModel($conexion);

        // Antes faltaba esta instancia y evaluarArchivo() moría con un
        // error fatal al querer sumar el XP.
        $this->UsuarioModel =
            new UsuarioModel($conexion);

        $this->DetalleEjercicioEscrituraModel =
            new DetalleEjercicioEscrituraModel($conexion);  
            
        $this->IAEscrituraService =
            new IAEscrituraService();     
    }

    public function mostrarModuloEscritura(){

        Guardias::exigirUsuario(false);

        $archivos = $this->ArchivoEscrituraModel->obtenerArchivosPorUsuario($_SESSION['id_usuario']);
        $dificultades = $this->NivelDificultadModel->obtenerTodos();
        $tipos = $this->TipoTextoModel->obtenerTodos();
    
        include(__DIR__ . "/../views/ejercicio_escritura.php");
        
    }

    public function subirArchivo(){

        header('Content-Type: application/json');

        Guardias::exigirUsuario();

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

        // Se valida el contenido real del archivo, no solo la extensión:
        // un .php renombrado a .pdf debe ser un PDF de verdad.
        $infoArchivo =
            new finfo(FILEINFO_MIME_TYPE);

        $mimeReal =
            $infoArchivo->file($tmp);

        if($mimeReal !== "application/pdf"){

            echo json_encode([
                "success" => false,
                "mensaje" => "El archivo no es un PDF válido"
            ]);

            return;
        }

        $tamanoMaximo =
            10 * 1024 * 1024;

        if($archivo['size'] > $tamanoMaximo){

            echo json_encode([
                "success" => false,
                "mensaje" => "El archivo supera el límite de 10 MB"
            ]);

            return;
        }

        // Nombre aleatorio con bytes aleatorios: uniqid() es predecible
        // y permite adivinar la URL de los trabajos de otros estudiantes.
        $nombreFinal =
            "escritura_" .
            bin2hex(random_bytes(16)) .
            ".pdf";

        // Los archivos se guardan FUERA del webroot: así no se pueden
        // descargar con solo conocer la URL. Para verlos se usa la
        // acción verArchivo, que valida que el archivo sea del usuario.
        $carpetaDestino =
            dirname(__DIR__) . "/storage/escrituras";

        if(!is_dir($carpetaDestino)){
            mkdir($carpetaDestino, 0775, true);
        }

        $rutaRelativa =
            "storage/escrituras/" . $nombreFinal;

        $rutaCompleta =
            dirname(__DIR__) . "/" . $rutaRelativa;

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

    /**
     * Entrega el PDF al usuario, verificando que le pertenezca.
     * Los archivos viven fuera del webroot, así que no se puede
     * descargar con solo adivinar la URL.
     */
    public function verArchivo(){

        Guardias::exigirUsuario();

        if(!isset($_GET['id'])){

            http_response_code(400);

            echo "ID no recibido";

            return;
        }

        $idArchivo = intval($_GET['id']);

        $archivo =
            $this->ArchivoEscrituraModel
                ->obtenerPorIdYUsuario(
                    $idArchivo,
                    $_SESSION['id_usuario']
                );

        if(!$archivo){

            http_response_code(404);

            echo "Archivo no encontrado";

            return;
        }

        $rutaCompleta =
            dirname(__DIR__) . "/" . $archivo['url_archivo'];

        if(!is_readable($rutaCompleta)){

            http_response_code(404);

            echo "Archivo no disponible";

            return;
        }

        header("Content-Type: application/pdf");
        header(
            "Content-Disposition: inline; filename=\"" .
            basename($archivo['nombre_archivo']) . "\""
        );
        header("Content-Length: " . filesize($rutaCompleta));
        header("X-Content-Type-Options: nosniff");

        readfile($rutaCompleta);
    }

    public function obtenerDificultades(){

        Guardias::exigirUsuario();

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

        Guardias::exigirUsuario();

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

        Guardias::exigirUsuario();

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

        Guardias::exigirUsuario();

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


$controller = new ModuloEscrituraController($conexion);

$controller->$accion();

?>