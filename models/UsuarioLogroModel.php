<?php

class UsuarioLogroModel {

    private $conexion;

    public function __construct($conexion){

        $this->conexion = $conexion;
    }

public function usuarioTieneLogro(
    $idUsuario,
    $idLogro
){

    $sql = "
        SELECT ID
        FROM usuariologro
        WHERE ID_usuario = ?
        AND ID_logro = ?
    ";

    $stmt =
        mysqli_prepare(
            $this->conexion,
            $sql
        );

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $idUsuario,
        $idLogro
    );

    mysqli_stmt_execute($stmt);

    $resultado =
        mysqli_stmt_get_result($stmt);

    return mysqli_num_rows(
        $resultado
    ) > 0;

}

public function desbloquearLogro(
    $idUsuario,
    $idLogro
){

    $sql = "
        INSERT INTO usuariologro
        (
            ID_usuario,
            ID_logro
        )
        VALUES (?, ?)
    ";

    $stmt =
        mysqli_prepare(
            $this->conexion,
            $sql
        );

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $idUsuario,
        $idLogro
    );

    return mysqli_stmt_execute(
        $stmt
    );
}
}