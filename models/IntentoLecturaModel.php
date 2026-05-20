<?php

class IntentoLecturaModel{
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function guardarIntentoLectura(
        $idUsuario,
        $puntaje,
        $respuestas_correctas,
        $total_preguntas,
        $tiempo,
        $puntaje_literal,
        $puntaje_inferencial,
        $puntaje_critico
    ){

        $sql = "INSERT INTO intentolectura(
            ID_usuario, puntaje_total, respuestas_correctas, total_preguntas, tiempo_minutos, puntaje_literal, puntaje_inferencial, puntaje_critico)
            VALUES (?,?,?,?,?,?,?,?)
            ";

            $stmt = mysqli_prepare(
                $this->conexion,
                $sql
            );

            mysqli_stmt_bind_param(
                $stmt,
                "iiiidiii",
                $idUsuario,
                $puntaje,
                $respuestas_correctas,
                $total_preguntas,
                $tiempo,
                $puntaje_literal,
                $puntaje_inferencial,
                $puntaje_critico
            );

            if(mysqli_stmt_execute($stmt)){

                return mysqli_insert_id($this->conexion);

            }

            return false;
            }
}
    

?>