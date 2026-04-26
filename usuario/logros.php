<?php include("../Acciones/logros_logica.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Logros</title>
    <link rel="stylesheet" href="../css/logros.css?v=<?php echo time(); ?>">
</head>

<body>

<header class="navbar">
    <div class="logo"> LectoEscritura</div>

    <div class="stats-panel">
        <div class="stat-item">👤 <?php echo htmlspecialchars($nombre_usuario); ?></div>
        <div class="divider"></div>
        <div class="stat-item">🏅 <span class="badge"><?php echo htmlspecialchars($nivel_usuario); ?></span></div>
        <div class="divider"></div>
        <div class="stat-item">🔥 <?php echo $racha_usuario; ?> días</div>
        <div class="divider"></div>
        <div class="stat-item">⭐ <span class="xp-badge"><?php echo number_format($xp_usuario); ?> XP</span></div>
    </div>

    <a href="pantalla_principal_Usuario.php" class="btn-volver">← Volver</a>
</header>

<main class="container">

<div class="achievements-grid">
<?php while ($logro = mysqli_fetch_assoc($resultado)): 
    $esta_bloqueado = is_null($logro['fecha_desbloqueo']);
    $clase_bloqueo = $esta_bloqueado ? "locked" : "unlocked";
    
    $icono = "🏆";
    switch ($logro['ID']) {
        case 1: $icono = "📖"; break;
        case 2: $icono = "🔥"; break;
        case 3: $icono = "✍️"; break;
        case 4: $icono = "👑"; break;
        case 5: $icono = "⭐"; break;
        case 6: $icono = "🎯"; break;
        case 7: $icono = "💪"; break;
        case 8: $icono = "🏅"; break;
        default: $icono = "🏆"; break;
    }
?>
    <div class="achievement-card <?php echo $clase_bloqueo; ?>">
        
        <div class="lock-badge">
            <?php echo $esta_bloqueado ? "🔒" : "✅"; ?>
        </div>

        <div class="achievement-icon"><?php echo $icono; ?></div>

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