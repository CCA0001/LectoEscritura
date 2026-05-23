<?php

class AdminModel {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function obtenerTodosLosAdministradores(){

        $sql = "
            SELECT
                ID,
                nombres,
                apellidos,
                nombre_usuario,
                correo_electronico,
                fecha_registro,
                estado
            FROM admin
            ORDER BY ID ASC
        ";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        $admins = [];

        while($fila = mysqli_fetch_assoc($resultado)){
            $admins[] = $fila;
        }

        return $admins;
    }

    public function comprobarExistenciaCorreo($correo){

        $sql = "
            SELECT ID
            FROM admin
            WHERE correo_electronico = ?
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $correo
        );

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        return mysqli_num_rows($resultado) > 0;
    }

    public function agregarAdministrador(
        $nombres,
        $apellidos,
        $nombre_usuario,
        $correo,
        $contrasenia_hash,
        $estado,
    ){

        $existe = $this->comprobarExistenciaCorreo($correo);

        if($existe){
            return false;
        }

        $sql = "
            INSERT INTO admin
            (
                nombres,
                apellidos,
                nombre_usuario,
                correo_electronico,
                contrasenia_hash,
                estado
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $nombres,
            $apellidos,
            $nombre_usuario,
            $correo,
            $contrasenia_hash,
            $estado
        );

        return mysqli_stmt_execute($stmt);
    }

    public function invertirEstadoAdmin($ID, $estado){
        $sql = "UPDATE `admin`
                SET estado = ?
                WHERE ID = ?";

        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "si", $estado, $ID);  
            
        return mysqli_stmt_execute($stmt);
    }

    public function obtenerAdminPorId($id){
        $sql_check = "SELECT ID FROM `admin` WHERE ID = ?";
        $stmt_check = mysqli_prepare($this->conexion, $sql_check);
        $resultado = mysqli_stmt_bind_param($stmt_check, "i", $id);

        mysqli_stmt_execute($stmt_check);

        $resultado = mysqli_stmt_get_result($stmt_check);

        $admin = [];

        while($fila = mysqli_fetch_assoc($resultado)){
            $admin[] = $fila;
        }

        return $admin;
    }


    public function actualizarAdmin($ID, $nombres, $apellidos, $nombre_usuario, $contrasenia_hash, $estado){
        $sql = "UPDATE `admin`
            SET nombres = ?, 
            apellidos = ?,  
            nombre_usuario = ?, 
            contrasenia_hash = ?,
            correo_electronico = ?, 
            estado = ?
            WHERE ID = ?";
            
            $stmt = mysqli_prepare($this->conexion, $sql);
            mysqli_stmt_bind_param($stmt, "ssiisi", $nombres, $apellidos, $nombre_usuario, $contrasenia_hash, $estado, $ID);
        
            return mysqli_stmt_execute($stmt);
        
        }
}

?>