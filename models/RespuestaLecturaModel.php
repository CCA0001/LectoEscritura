<?php

class RespuestaLecturaModel {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function guardarRespuesta(
        $ID_opcionPregunta,
        $ID_intentoLectura,
        $ID_texto,
        $ID_pregunta
    ){

        $sql = "
            INSERT INTO respuestalectura
            (
                ID_opcionPregunta,
                ID_intentoLectura,
                ID_texto,
                ID_pregunta
            )
            VALUES (?, ?, ?, ?)
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "iiii",
            $ID_opcionPregunta,
            $ID_intentoLectura,
            $ID_texto,
            $ID_pregunta

        );

        return mysqli_stmt_execute($stmt);
    }
}
?>