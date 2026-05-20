<?php
class NivelProgresoModel {

    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function obtenerTodosLosNiveles(){

        $sql = "
            SELECT *
            FROM nivelprogreso
            ORDER BY nombre ASC
        ";

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

    public function comprobarExistenciaNivel($nombre){

        $sql = "
            SELECT ID
            FROM nivelprogreso
            WHERE nombre = ?
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $nombre
        );

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        return mysqli_num_rows($resultado) > 0;

    }

public function agregarNivel(
        $nombre,
        $xp_requerida,
        $id_Admin,
        $descripcion,
        $estado
    ){

        $existe = $this->comprobarExistenciaNivel($nivel);

        if($existe){
            return false;
        }

        $sql = "
            INSERT INTO nivelprogreso
            (
                nombre,
                puntos_requeridos,
                ID_adminResponsable,
                descripcion,
                estado
            )
            VALUES (?, ?, ?, ?, ?)
        ";        
        mysqli_stmt_bind_param(
            $stmt,
            "siiss",
            $nombre,
            $xp_requerida,
            $id_Admin,
            $descripcion,
            $estado,
        );

        return mysqli_stmt_execute($stmt);
    }

    public function actualizarNivel(
        $id,
        $nombre,
        $xp_requerida,
        $id_Admin,
        $descripcion,
        $estado
    ){

        $sql = "
            UPDATE nivelprogreso
            SET
                nombre = ?,
                puntos_requeridos = ?,
                id_adminResponsable = ?,
                descripcion = ?,
                estado = ?
            WHERE ID = ?
        ";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "siissi",
            $nombre,
            $xp_requerida,
            $id_Admin,
            $descripcion,
            $estado,
            $id
        );

        return mysqli_stmt_execute($stmt);
    }    
    
    public function invertirEstadoNivel($id, $estado){

        $sql = "
            UPDATE nivelprogreso
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

    public function obtenerNivelPorId($id){

        $sql = "
            SELECT *
            FROM nivelprogreso
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