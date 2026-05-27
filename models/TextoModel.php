<?php

class TextoModel {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }


    public function obtenerTodosLosTextos(){

        $sql = "
            SELECT *
            FROM textolectura
            ORDER BY ID DESC
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        $textos = [];

        while($fila = mysqli_fetch_assoc($resultado)){
            $textos[] = $fila;
        }

        return $textos;
    }


    public function agregarTextoManualmente(
        $id_dificultad,
        $id_tipoTexto,
        $titulo,
        $contenido,
        $fuente,
        $id_admin,
        $estado
    ){

        $sql = "
            INSERT INTO textolectura
            (
                ID_dificultad,
                ID_tipoTexto,
                titulo,
                contenido,
                fuente,
                ID_adminResponsable,
                estado
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "iisssis",
            $id_dificultad,
            $id_tipoTexto,
            $titulo,
            $contenido,
            $fuente,
            $id_admin,
            $estado
        );

        return mysqli_stmt_execute($stmt);
    }

    public function invertirEstadoTexto($id, $estado){

        $sql = "
            UPDATE textolectura
            SET estado = ?
            WHERE ID = ?
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $estado,
            $id
        );

        return mysqli_stmt_execute($stmt);
    }

    public function obtenerTextoPorId($id){

        $sql = "
            SELECT *
            FROM textolectura
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

    public function actualizarTexto(
        $id_dificultad,
        $id_tipoTexto,
        $titulo,
        $contenido,
        $fuente,
        $id_admin,
        $estado,
        $id
        ){

        $sql = "
            UPDATE textolectura
            SET
                ID_dificultad = ?,
                ID_tipoTexto = ?,
                titulo = ?,
                contenido = ?,
                fuente = ?,
                ID_adminResponsable = ?,
                estado = ?
            WHERE ID = ?
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "iisssisi",
            $id_dificultad,
            $id_tipoTexto,
            $titulo,
            $contenido,
            $fuente,
            $id_admin,
            $estado,
            $id
        );

        return mysqli_stmt_execute($stmt);
    }

    public function obtenerTextoFacil(){
        $sql = " SELECT * FROM textolectura
            WHERE ID_dificultad = 1
            AND estado = 'Activo'
            ORDER BY RAND()
            LIMIT 1
            ";

        $resultado = mysqli_query(
            $this->conexion,
            $sql
        );

        return mysqli_fetch_assoc($resultado);
    }

    public function obtenerTextoAvanzado(){
        $sql = "SELECT *
        FROM textolectura
        WHERE (ID_dificultad = 2 OR ID_dificultad = 3)
        AND estado = 'Activo'
        ORDER BY RAND()
        LIMIT 4";
        
        $resultado = mysqli_query(
            $this->conexion,
            $sql
        );

        $textos = [];

        while($fila = mysqli_fetch_assoc($resultado)){
            $textos[] = $fila;
        }

        return $textos; 
        }
    }

?>