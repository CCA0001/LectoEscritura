<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("conexion.php");

$nombre_real = "Estudiante"; 
$racha_usuario = 0;
$mensaje_alerta = "";

if (isset($_SESSION['id_usuario'])) {
    $id_logueado = $_SESSION['id_usuario'];
    date_default_timezone_set('America/Bogota');
    $hoy = date("Y-m-d");
    $ayer = date("Y-m-d", strtotime("-1 day"));

    $consulta = "SELECT nombre_usuario, dias_racha, ultima_conexion FROM usuario WHERE ID = '$id_logueado'";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado && $user = mysqli_fetch_assoc($resultado)) {
        $nombre_real = $user['nombre_usuario'];
        $racha_bd = (int)$user['dias_racha'];
        $ultima_vez = $user['ultima_conexion'];

        if ($racha_bd <= 0 || empty($ultima_vez)) {
            $racha_usuario = 1;
            mysqli_query($conexion, "UPDATE usuario SET dias_racha = 1, ultima_conexion = '$hoy' WHERE ID = '$id_logueado'");
            $mensaje_alerta = "¡Bienvenido! Hoy comienzas tu primer día de racha.";
        }
        elseif ($ultima_vez == $hoy) {
            $racha_usuario = $racha_bd;
            $mensaje_alerta = "¡Hola de nuevo! Tu racha se mantiene en $racha_usuario ";
        } 
        elseif ($ultima_vez == $ayer) {
            $racha_usuario = $racha_bd + 1;
            mysqli_query($conexion, "UPDATE usuario SET dias_racha = $racha_usuario, ultima_conexion = '$hoy' WHERE ID = '$id_logueado'");
            $mensaje_alerta = "¡Genial! Has sumado un día. Racha actual: $racha_usuario ";
        } 
        else {
            $racha_usuario = 1;
            mysqli_query($conexion, "UPDATE usuario SET dias_racha = 1, ultima_conexion = '$hoy' WHERE ID = '$id_logueado'");
            $mensaje_alerta = "¡Uy! Pasó tiempo desde que nos visitas, pero hoy inicias una nueva racha de 1 día.";
        }

        $metas = [
            7 => ['id' => 2, 'nombre' => 'Racha de 7 días', 'xp' => 100],
            30 => ['id' => 8, 'nombre' => 'Racha de 30 días', 'xp' => 500]
        ];

        foreach ($metas as $dias => $datos) {
            if ($racha_usuario >= $dias) {
                $id_logro = $datos['id'];
                $check = mysqli_query($conexion, "SELECT * FROM usuariologro WHERE ID_usuario = '$id_logueado' AND ID_logro = '$id_logro'");
                
                if (mysqli_num_rows($check) == 0) {
                    mysqli_query($conexion, "INSERT INTO usuariologro (ID_usuario, ID_logro, fecha_desbloqueo) VALUES ('$id_logueado', '$id_logro', NOW())");
                    mysqli_query($conexion, "UPDATE usuario SET puntos_xp = puntos_xp + {$datos['xp']} WHERE ID = '$id_logueado'");
                    
                    $_SESSION['mostrar_logro'] = [
                        'nombre' => $datos['nombre'],
                        'xp' => $datos['xp']
                    ];
                }
            }
        }
        $_SESSION['mensaje_racha'] = $mensaje_alerta;
    }
}
?>