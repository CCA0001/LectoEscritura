<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$query_user = "SELECT u.nombre_usuario, u.puntos_xp, u.dias_racha, n.nombre as nivel 
               FROM usuario u 
               LEFT JOIN nivelprogreso n ON u.ID_nivelProgreso = n.ID 
               WHERE u.ID = '$id_usuario'";
$res_user = mysqli_query($conexion, $query_user);
$usuario = mysqli_fetch_assoc($res_user);
$xp_usuario = $usuario['puntos_xp'] ?? 0;
$nombre_usuario = $usuario['nombre_usuario'];
$nivel_usuario = $usuario['nivel'] ?? 'Principiante';
$racha_usuario = $usuario['dias_racha'] ?? 0;

$query_logros = "SELECT l.ID, l.nombre, l.descripcion, l.recompensa_xp, ul.fecha_desbloqueo 
                 FROM logro l 
                 LEFT JOIN usuariologro ul ON l.ID = ul.ID_logro AND ul.ID_usuario = '$id_usuario'
                 ORDER BY ul.fecha_desbloqueo DESC, l.ID ASC";
$resultado = mysqli_query($conexion, $query_logros);

$query_contar = "SELECT COUNT(*) as total FROM usuariologro WHERE ID_usuario = '$id_usuario'";
$res_contar = mysqli_query($conexion, $query_contar);
$total_logros = mysqli_fetch_assoc($res_contar)['total'];

$query_total = "SELECT COUNT(*) as total FROM logro";
$res_total = mysqli_query($conexion, $query_total);
$total_posibles = mysqli_fetch_assoc($res_total)['total'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mis Logros - LectoEscritura</title>
    <link rel="stylesheet" href="css/logros.css?v=<?php echo time(); ?>">
</head>

<body>

    <header class="navbar">
        <div class="logo">
             LectoEscritura
        </div>

        <div class="stats-panel">
            <div class="stat-item">
                👤 <?php echo htmlspecialchars($nombre_usuario); ?>
            </div>
            <div class="divider"></div>
            <div class="stat-item">
                🏅 <span class="badge"><?php echo htmlspecialchars($nivel_usuario); ?></span>
            </div>
            <div class="divider"></div>
            <div class="stat-item">
                🔥 <?php echo $racha_usuario; ?> días
            </div>
            <div class="divider"></div>
            <div class="stat-item">
                ⭐ <span class="xp-badge"><?php echo number_format($xp_usuario); ?> XP</span>
            </div>
        </div>

        <a href="pantalla_principal_Usuario.php" class="btn-volver">← Volver</a>
    </header>

    <nav class="breadcrumb">
        📍 Inicio / Panel Usuario / Mis Logros
    </nav>

    <main class="container">
        <div class="page-header">
            <h1>  Mis Logros</h1>
            <p>Completa misiones para ganar medallas y XP. ¡Mucha suerte!</p>
            <div class="progress-stats">
                <div class="stats-card">
                    <span class="stats-number"><?php echo $total_logros; ?>/<?php echo $total_posibles; ?></span>
                    <span class="stats-label">Logros desbloqueados</span>
                    <div class="progress-bar-mini">
                        <div class="progress-fill"
                            style="width: <?php echo ($total_logros / $total_posibles) * 100; ?>%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="achievements-grid">
            <?php while ($logro = mysqli_fetch_assoc($resultado)):
                $esta_bloqueado = is_null($logro['fecha_desbloqueo']);
                $clase_bloqueo = $esta_bloqueado ? "locked" : "unlocked";
            ?>
            <div class="achievement-card <?php echo $clase_bloqueo; ?>">
                <div class="lock-badge">
                    <?php echo $esta_bloqueado ? "🔒" : "✅"; ?>
                </div>

                <div class="achievement-icon">
                    <?php
                        switch ($logro['ID']) {
                            case 1:
                                echo "📖";
                                break;
                            case 2:
                                echo "🔥";
                                break;
                            case 4:
                                echo "✍️";
                                break;
                            case 8:
                                echo "👑";
                                break;
                            default:
                                echo "🏆";
                                break;
                        }
                        ?>
                </div>

                <h3><?php echo htmlspecialchars($logro['nombre']); ?></h3>
                <p><?php echo htmlspecialchars($logro['descripcion']); ?></p>
                <div class="xp-badge">+<?php echo $logro['recompensa_xp']; ?> XP</div>

                <?php if (!$esta_bloqueado): ?>
                <div class="date-unlocked">
                    🗓️ <?php echo date("d/m/Y", strtotime($logro['fecha_desbloqueo'])); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </main>

</body>

</html>