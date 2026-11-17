<?php
    class LogroModel{
        private $conexion;
        public function __construct($conexion){
            $this->conexion = $conexion;
        }

        public function agregarLogro($nombre, $descripcion, $recompensa, $ID_admin, $estado){
            $existe = $this->comprobarExistenciaLogro($nombre);

            if($existe){
                return false;
            }

            $sql = "INSERT INTO logro (nombre, descripcion, recompensa_xp, ID_adminResponsable, estado)
                VALUES (?,?,?,?,?)";

            $stmt = mysqli_prepare($this->conexion, $sql);
            mysqli_stmt_bind_param($stmt, "ssiis", $nombre, $descripcion, $recompensa, $ID_admin, $estado);

            if(mysqli_stmt_execute($stmt)){
                return True;
            } else {
                return False;
            }
        }

        public function comprobarExistenciaLogro($nombre){
            $sql_check = "SELECT ID FROM logro WHERE nombre = ?";
            $stmt_check = mysqli_prepare($this->conexion, $sql_check);
            $resultado = mysqli_stmt_bind_param($stmt_check, "s", $nombre);

            mysqli_stmt_execute($stmt_check);

            $resultado = mysqli_stmt_get_result($stmt_check);

            return mysqli_num_rows($resultado) > 0;

        }

        public function invertirEstadoLogro($ID, $estado){
            $sql = "UPDATE logro
                SET estado = ?
                WHERE ID = ?";

            $stmt = mysqli_prepare($this->conexion, $sql);
            mysqli_stmt_bind_param($stmt, "si", $estado, $ID);  
            
            return mysqli_stmt_execute($stmt);
        }

        public function actualizarLogro($ID, $nombre, $descripcion, $recompensa, $ID_admin, $estado){
            $sql = "UPDATE logro
                SET nombre = ?, 
                descripcion = ?,  
                recompensa_xp = ?, 
                ID_adminResponsable = ?, 
                estado = ?
                WHERE ID = ?";
            
            $stmt = mysqli_prepare($this->conexion, $sql);
            mysqli_stmt_bind_param($stmt, "ssiisi", $nombre, $descripcion, $recompensa, $ID_admin, $estado, $ID);
        
            return mysqli_stmt_execute($stmt);
        
        }

        public function obtenerTodosLosLogros(){
            $sql = "SELECT * FROM logro";
            $stmt = mysqli_prepare($this->conexion, $sql);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);

            $logros = [];

            while($fila = mysqli_fetch_assoc($resultado)){
                $logros[] = $fila;
            }

            return $logros;

                }

        /**
         * Busca un logro ACTIVO por su nombre.
         *
         * Se usa para las metas de racha: antes el código tenía los IDs
         * (2 y 8) escritos a mano, así que renombrar o desactivar un logro
         * desde el panel rompía el desbloqueo.
         *
         * Devuelve null si no existe o si está inactivo.
         */
        public function obtenerLogroActivoPorNombre($nombre){

            $sql = "
                SELECT *
                FROM logro
                WHERE nombre = ?
                AND estado = 'Activo'
                LIMIT 1
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

            $fila = mysqli_fetch_assoc($resultado);

            return $fila ? $fila : null;
        }

        public function obtenerLogroPorId($id){            $sql = "
                SELECT *
                FROM logro
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
                $id
            );

            mysqli_stmt_execute(
                $stmt
            );

            $resultado =
                mysqli_stmt_get_result(
                    $stmt
                );

            return mysqli_fetch_assoc(
                $resultado
            );
        }
    }
?>