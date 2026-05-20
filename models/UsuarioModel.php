<?php

class UsuarioModel {

    private $conexion;

    public function __construct($conexion){

        $this->conexion = $conexion;
    }

    public function buscarPorCorreo($correo){

        $sql = "
            SELECT *
            FROM usuario
            WHERE correo = ?
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
}

?>