<?php

class DetalleEjercicioEscrituraModel{
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

public function guardarEvaluacion(
    $idArchivo,
    $coherencia,
    $cohesion,
    $gramatica,
    $argumentacion,
    $estructura,
    $retroalimentacion
){

    $sql = "
        INSERT INTO detallesejercicioescritura(
            ID_archivoEscritura,
            puntaje_coherencia,
            puntaje_cohesion,
            puntaje_gramatica,
            puntaje_argumentacion,
            puntaje_estructura,
            retroalimentacion,
            fecha_registrada
        )
        VALUES(?,?,?,?,?,?,?,NOW())
    ";

    $stmt = mysqli_prepare(
        $this->conexion,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iiiiiis",
        $idArchivo,
        $coherencia,
        $cohesion,
        $gramatica,
        $argumentacion,
        $estructura,
        $retroalimentacion
    );

    return mysqli_stmt_execute($stmt);
}
}

?>