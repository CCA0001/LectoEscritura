<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("conexion.php");

$id_logueado = $_SESSION['id_usuario'] ?? null;

if (!$id_logueado) {
    $_SESSION['nombre_real'] = 'Estudiante';
    $_SESSION['racha_usuario'] = 0;
    $_SESSION['puntos_xp'] = 0; 
    $_SESSION['total_logros'] = 0; 
    return;
}

date_default_timezone_set('America/Bogota');
$hoy = date("Y-m-d");
$ayer = date("Y-m-d", strtotime("-1 day"));

$res = mysqli_query($conexion, "SELECT * FROM usuario WHERE ID = '$id_logueado'");

if ($user = mysqli_fetch_assoc($res)) {
    $_SESSION['nombre_real'] = $user['nombre_usuario'];
    
    $racha_bd = (int)($user['dias_racha'] ?? 0);
    $ultima_vez = $user['ultima_conexion'];
    $xp_actual = (int)($user['puntos_xp'] ?? 0);
    
    $_SESSION['puntos_xp'] = $xp_actual;
    
    $query_logros = "SELECT COUNT(*) as total FROM usuariologro WHERE ID_usuario = '$id_logueado'";
    $res_logros = mysqli_query($conexion, $query_logros);
    $total_logros = mysqli_fetch_assoc($res_logros)['total'];
    $_SESSION['total_logros'] = $total_logros;
    
    $es_primera_vez = ($ultima_vez == NULL || $ultima_vez == '' || $ultima_vez != $hoy);
    
    if ($es_primera_vez) {
        if ($ultima_vez == NULL || $ultima_vez == '') {
            $nueva_racha = 1;
            $mensaje_racha = "🎉 ¡Bienvenido! Comienzas tu racha con 10 XP.";
        }
        else if ($ultima_vez == $ayer) {
            $nueva_racha = $racha_bd + 1;
            $mensaje_racha = "🔥 ¡Racha de $nueva_racha días! Ganaste 10 XP.";
        }
        else {
            $nueva_racha = 1;
            $mensaje_racha = "🔄 ¡Racha reiniciada! Ganaste 10 XP.";
        }
        
        $xp_actual += 10;
        $_SESSION['puntos_xp'] = $xp_actual;
        $sql = "UPDATE usuario SET 
                dias_racha = $nueva_racha, 
                ultima_conexion = '$hoy', 
                puntos_xp = $xp_actual 
                WHERE ID = '$id_logueado'";
        
        if (mysqli_query($conexion, $sql)) {
            $_SESSION['mostrar_alerta_racha'] = $mensaje_racha;
            $_SESSION['puntos_recientes'] = 10;
            $_SESSION['racha_usuario'] = $nueva_racha;
            
            $metas = [
                7 => ['id' => 2, 'nombre' => 'Racha de 1 Semana', 'xp' => 100],
                30 => ['id' => 8, 'nombre' => 'Maestro de Constancia', 'xp' => 500]
            ];
            
            if (isset($metas[$nueva_racha])) {
                $m = $metas[$nueva_racha];
                $check = mysqli_query($conexion, "SELECT * FROM usuariologro WHERE ID_usuario = '$id_logueado' AND ID_logro = '{$m['id']}'");
                if (mysqli_num_rows($check) == 0) {
                    mysqli_query($conexion, "INSERT INTO usuariologro (ID_usuario, ID_logro, fecha_desbloqueo) VALUES ('$id_logueado', '{$m['id']}', NOW())");
                    $xp_actual += $m['xp'];
                    $_SESSION['puntos_xp'] = $xp_actual; 
                    mysqli_query($conexion, "UPDATE usuario SET puntos_xp = $xp_actual WHERE ID = '$id_logueado'");
                    $_SESSION['mostrar_logro'] = ['nombre' => $m['nombre'], 'xp' => $m['xp']];
                    
                    $total_logros++;
                    $_SESSION['total_logros'] = $total_logros;
                }
            }
        } else {
            $_SESSION['racha_usuario'] = $racha_bd;
        }
    } else {
        $_SESSION['racha_usuario'] = $racha_bd;
    }
    
    $q_nivel = "SELECT ID, nombre FROM nivelprogreso WHERE puntos_requeridos <= $xp_actual ORDER BY puntos_requeridos DESC LIMIT 1";
    $res_n = mysqli_query($conexion, $q_nivel);
    if ($n_info = mysqli_fetch_assoc($res_n)) {
        $_SESSION['rango_actual'] = $n_info['nombre'];
        mysqli_query($conexion, "UPDATE usuario SET ID_nivelProgreso = '{$n_info['ID']}' WHERE ID = '$id_logueado'");
    } else {
        $_SESSION['rango_actual'] = "Principiante";
    }
    
} else {
    $_SESSION['nombre_real'] = 'Estudiante';
    $_SESSION['racha_usuario'] = 0;
    $_SESSION['puntos_xp'] = 0;
    $_SESSION['total_logros'] = 0;
}
?>