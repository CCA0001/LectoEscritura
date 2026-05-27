<?php

session_start();

require_once("../config/conexion.php");
require_once("../models/UsuarioModel.php");
require_once("../models/AdminModel.php");
require_once("../models/NivelProgresoModel.php");
require_once("../models/UsuarioLogroModel.php");

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

        if(!$contrasenia == $confirmar_contrasenia){
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

        $_SESSION['id_usuario'] =
            $usuario['ID'];

        $_SESSION['nombre_usuario'] =
            $usuario['nombre_usuario'];

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
                $racha,
                $xp
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

        $this->UsuarioLogroModel->desbloquearLogro( $idUsuario, $meta['xp']);

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

        $_SESSION['id'] =
            $usuario['ID'];

        $_SESSION['nombre_usuario'] =
            $usuario['nombre_usuario'];

        echo json_encode([
            "success" => true,
            "mensaje" =>
                "Inicio de sesión exitoso"
        ]);
    }   
}

$controller = new AuthController($conexion);

$accion = $_GET['accion'] ?? 'loginUsuario';

if(method_exists($controller, $accion)){

    $controller->$accion();

}else{

    echo "Acción no válida";
}


?>