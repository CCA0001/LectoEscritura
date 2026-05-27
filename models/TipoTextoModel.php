<?php
class TipoTextoModel {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function obtenerTodos(){
        $sql = "SELECT * from tipotexto";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        $tipos = [];

        while($fila = mysqli_fetch_assoc($resultado)){
            $tipos[] = $fila;
        }

        return $tipos;    
    }
}