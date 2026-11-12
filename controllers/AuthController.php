<?php

session_start();

require_once(__DIR__ . "/../config/router.php");
require_once(__DIR__ . "/../config/guardias.php");
require_once(__DIR__ . "/../models/UsuarioModel.php");
require_once(__DIR__ . "/../models/AdminModel.php");
require_once(__DIR__ . "/../models/NivelProgresoModel.php");
require_once(__DIR__ . "/../models/UsuarioLogroModel.php");

$accion = Router::resolver(
    "loginUsuario",
    [
        "loginUsuario" => "publico",
        "registerUsuario" => "publico",
        "loginAdmin" => "publico",
        "logout" => "publico"
    ]
);

// La conexión se abre DESPUÉS de resolver la acción.
require_once(__DIR__ . "/../config/conexion.php");

class AuthController
{

    private $UsuarioModel;
    private $AdminModel;
    private $NivelProgresoModel;
    private $UsuarioLogroModel;

    public function __construct($conexion)
    {
        $this->UsuarioModel =
            new UsuarioModel($conexion);

        $this->AdminModel =
            new AdminModel($conexion);
        $this->NivelProgresoModel =
            new NivelProgresoModel($conexion);    
        $this->UsuarioLogroModel =
            new UsuarioLogroModel($conexion);    
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

        if($contrasenia !== $confirmar_contrasenia){
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

        // Se regenera el identificador de sesión para evitar el secuestro
        // de sesión (session fixation) al cambiar de usuario anónimo a
        // usuario autenticado.
        session_regenerate_id(true);

        $_SESSION['id_usuario'] =
            $usuario['ID'];

        $_SESSION['nombre_usuario'] =
            $usuario['nombre_usuario'];

        $_SESSION['rol'] =
            "usuario";

        $datosRacha = $this->procesarRacha($usuario);

        echo json_encode([
            "success" => true,
            "mensaje" =>
                "Inicio de sesión exitoso",
            "racha" => $datosRacha
        ]);




    }

    private function procesarRacha($usuario){

        $hoy =
            date("Y-m-d");

        $ayer = date("Y-m-d", strtotime("-1 day"));

        $ultimaConexion = $usuario['ultima_conexion'];

        $racha = (int)$usuario['dias_racha'];
        $xp = (int)$usuario['puntos_xp'];

        if($ultimaConexion !== $hoy){
            if($ultimaConexion === $ayer){
                $racha++;
            }else{
                $racha = 1;
            }

            $xp += 10;

            $this->UsuarioModel->actualizarRachaYXP($usuario['ID'], $racha, $xp, $hoy);

            $this->verificarLogrosRacha(
                $usuario['ID'],
                $racha
            );
        }
        $this->actualizarNivel(
            $usuario['ID'],
            $xp
        );

        return[
            "racha" => $racha,
            "xp_diario" => 10,
        ];
        
    }

    private function actualizarNivel(
    $idUsuario,
    $xp
    ){

    $nivel =
        $this->NivelProgresoModel
            ->obtenerNivelPorXP(
                $xp
            );

    if(!$nivel){
        return;
    }

    $this->UsuarioModel
        ->actualizarNivel(

            $idUsuario,
            $nivel['ID']
        );
    }

    public function verificarLogrosRacha($idUsuario, $racha){
        $metas = [
            7 => [
                "id" => 2,
                "xp" => 105
            ],

            30 => [
                "id" => 8,
                "xp" => 500
            ]
        ];

        if(!isset($metas[$racha])){
            return null;
        }

        $meta = $metas[$racha];

        $yaTiene = $this->UsuarioLogroModel->usuarioTieneLogro(
            $idUsuario, $meta['id']
        );

        if($yaTiene){
            return null;
        }

        // Ojo: el segundo parámetro de desbloquearLogro es el ID del logro,
        // no el XP. Antes se enviaba el XP y se creaban registros inválidos.
        $this->UsuarioLogroModel->desbloquearLogro(
            $idUsuario,
            $meta['id']
        );

        // La recompensa del logro se suma como XP extra.
        $this->UsuarioModel->sumarXp(
            $idUsuario,
            $meta['xp']
        );

        return [
            "logro_id" => $meta['id'],
            "xp" => $meta['xp']
        ];
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

        session_regenerate_id(true);

        // Clave de sesión unificada: el resto de la aplicación
        // (controladores admin y vistas) lee 'id_admin' y 'nombre'.
        $_SESSION['id_admin'] =
            $usuario['ID'];

        $_SESSION['id'] =
            $usuario['ID'];

        $_SESSION['nombre'] =
            $usuario['nombre_usuario'];

        $_SESSION['nombre_usuario'] =
            $usuario['nombre_usuario'];

        $_SESSION['rol'] =
            "admin";

        echo json_encode([
            "success" => true,
            "mensaje" =>
                "Inicio de sesión exitoso"
        ]);
    }   
}

$controller = new AuthController($conexion);

$controller->$accion();


?>