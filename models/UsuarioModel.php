<?php

class UsuarioModel {

    private $conexion;

    public function __construct($conexion){

        $this->conexion = $conexion;
    }

    public function buscarPorCorreo($correo){

        $sql = "
            SELECT ID, nombre_usuario, contrasenia_hash
            FROM usuario
            WHERE correo_electronico = ?
        ";

        $stmt =
            mysqli_prepare(
                $this->conexion,
                $sql
            );

        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $correo
        );

        mysqli_stmt_execute($stmt);

        $resultado =
            mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($resultado);
    }

    public function crearUsuario(
        $nombre,
        $correo,
        $passwordHash
    ){

        $sql = "
            INSERT INTO usuario(
                nombre,
                correo,
                password
            )
            VALUES(?,?,?)
        ";

        $stmt =
            mysqli_prepare(
                $this->conexion,
                $sql
            );

        mysqli_stmt_bind_param(
            $stmt,
            "sss",
            $nombre,
            $correo,
            $passwordHash
        );

        mysqli_stmt_execute($stmt);

        return mysqli_insert_id(
            $this->conexion
        );
    }

    public function obtenerPerfilCompleto($id){
        sql = "SELECT u.nombre_usuario, u.puntos_xp, dias_racha, d.nombre FROM usuario u LEFT JOIN nivelprogreso d ON u.ID_nivelProgreso = d.ID 
        WHERE u.ID=?";
        $stmt =
            mysqli_prepare(
                $this->conexion,
                $sql
            );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $id
        );

        mysqli_stmt_execute($stmt);

        $resultado =
            mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($resultado);


    }
}

?>