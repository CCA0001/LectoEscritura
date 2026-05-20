<?php

class PreguntaModel {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function obtenerPreguntasPorTexto($idTexto){

        $sql = "
            SELECT *
            FROM preguntalectura
            WHERE ID_textoLectura = ?
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $idTexto
        );

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        $preguntas = [];

        while($fila = mysqli_fetch_assoc($resultado)){
            $preguntas[] = $fila;
        }

        return $preguntas;
    }

    public function obtenerPreguntaPorID($id){

        $sql = "
            SELECT *
            FROM preguntalectura
            WHERE ID = ?
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $id
        );

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($resultado);
    }
}
?>