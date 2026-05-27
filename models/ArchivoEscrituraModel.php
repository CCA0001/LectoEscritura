<?php

class ArchivoEscrituraModel{
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function obtenerArchivosPorUsuario($idUsuario){

        $sql = "
            SELECT e.*, d.nombre AS dificultad_nombre, t.nombre AS tipo_nombre, det.retroalimentacion
            FROM archivoescritura e
            LEFT JOIN niveldificultad d
                ON e.ID_dificultad = d.ID
            LEFT JOIN tipotexto t
                ON e.ID_tipoTexto = t.ID
            LEFT JOIN detallesejercicioescritura det
                ON e.ID = det.ID_archivoEscritura
            WHERE e.ID_usuario = ?
            ORDER BY e.fecha_subida DESC
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $idUsuario
        );

        mysqli_stmt_execute($stmt);

        $resultado =
            mysqli_stmt_get_result($stmt);

        $archivos = [];

        while($fila = mysqli_fetch_assoc($resultado)){
            $archivos[] = $fila;
        }

        return $archivos;
    } 
    
    public function obtenerPorIdYUsuario(
        $idArchivo,
        $idUsuario
    ){

        $sql = "
            SELECT *
            FROM archivoescritura
            WHERE ID = ?
            AND ID_usuario = ?
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $idArchivo,
            $idUsuario
        );

        mysqli_stmt_execute($stmt);

        $resultado =
            mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($resultado);
    }

    public function actualizarPuntajePromedio(
        $idArchivo,
        $puntaje
    ){

        $sql = "
            UPDATE archivoescritura
            SET puntaje_promedio = ?
            WHERE ID = ?
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "di",
            $puntaje,
            $idArchivo
        );

        return mysqli_stmt_execute($stmt);
    }

    public function guardarArchivo(
        $idUsuario,
        $url,
        $nombre_archivo,
        $idDificultad,
        $id_tipoTexto
    ){

        $sql = "
        INSERT INTO archivoescritura(
            ID_usuario,
            url_archivo,
            nombre_archivo,
            ID_dificultad,
            ID_tipoTexto 
        )
        VALUES(?,?,?,?,?)";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "issii",
            $idUsuario,
            $url,            
            $nombre_archivo,
            $idDificultad,
            $id_tipoTexto
        );

        $resultado =
            mysqli_stmt_execute($stmt);

        if($resultado){

            return mysqli_insert_id(
                $this->conexion
            );
        }

        return false;    
    }

    
}