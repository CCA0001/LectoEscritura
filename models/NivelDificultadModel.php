<?php
class NivelDificultadModel {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function obtenerTodos(){
        $sql = "SELECT * from niveldificultad";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        $niveles = [];

        while($fila = mysqli_fetch_assoc($resultado)){
            $niveles[] = $fila;
        }

        return $niveles;    
    }
}