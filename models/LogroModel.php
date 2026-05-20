<?php
    class logroModel{
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

            mysqli_stmt_execute($stmt);

            $resultado = mysqli_stmt_get_result($stmt);

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

        public function obtenerLogroPorId($id){
            $sql_check = "SELECT ID FROM logro WHERE ID = ?";
            $stmt_check = mysqli_prepare($this->conexion, $sql_check);
            $resultado = mysqli_stmt_bind_param($stmt_check, "i", $id);

            mysqli_stmt_execute($stmt);

            $resultado = mysqli_stmt_get_result($stmt);

            $logro = [];

            while($fila = mysqli_fetch_assoc($resultado)){
                $logro[] = $fila;
            }

            return $logro;
        }
    }
?>