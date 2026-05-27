<?php

class OpcionPreguntaModel {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function obtenerOpcionesPorPregunta($idPregunta){

            $sql = "
                SELECT *
                FROM opcionpregunta
                WHERE ID_pregunta = ?
            ";

            $stmt = mysqli_prepare(
                $this->conexion,
                $sql
            );

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $idPregunta
            );

            mysqli_stmt_execute($stmt);

            $resultado = mysqli_stmt_get_result($stmt);

            $opciones = [];

            while($fila = mysqli_fetch_assoc($resultado)){
                $opciones[] = $fila;
            }

            return $opciones;
        }

        public function esOpcionCorrecta($idOpcion){
        $sql = "
            SELECT es_correcta
            FROM opcionpregunta
            WHERE ID = ?
        ";

        $stmt =
            mysqli_prepare(
                $this->conexion,
                $sql
            );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $idOpcion
        );

        mysqli_stmt_execute($stmt);

        $resultado =
            mysqli_stmt_get_result($stmt);

        $fila =
            mysqli_fetch_assoc($resultado);

        return $fila['es_correcta'] == 1;
    }    
}

?>