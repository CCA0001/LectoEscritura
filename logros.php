<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$query = "SELECT l.ID, l.nombre, l.descripcion, l.recompensa_xp, ul.fecha_desbloqueo 
          FROM logro l 
          LEFT JOIN usuariologro ul ON l.ID = ul.ID_logro AND ul.ID_usuario = '$id_usuario'";

$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/logros.css?v=<?php echo time(); ?>">
    <title>Logros - LectoEscritura</title>
</head>

<body>

    <header class="navbar">
        <span class="logo">LectoEscritura</span>
        <a href="pantalla_principal_Usuario.php" class="btn-logout">Volver</a>
    </header>

    <nav class="breadcrumb">
        &lt; Inicio / Panel Usuarios / Logros
    </nav>

    <main class="container">
        <h1 class="welcome-title">🏅 Mis Logros</h1>
        <p>Completa misiones para ganar medallas y XP. ¡Mucha suerte!</p>

        <div class="achievements-grid">
            <?php while($logro = mysqli_fetch_assoc($resultado)): 
                $esta_bloqueado = is_null($logro['fecha_desbloqueo']);
                $clase_bloqueo = $esta_bloqueado ? "locked" : "";
            ?>
            <div class="achievement-card <?php echo $clase_bloqueo; ?>">
                <div class="lock-badge">
                    <?php echo $esta_bloqueado ? "🔒" : "✅"; ?>
                </div>

                <span class="achievement-icon">
                    <?php 
                            switch($logro['ID']) {
                                case 1: echo "📖"; break;
                                case 2: echo "🔥"; break;
                                case 4: echo "✍️"; break;
                                case 8: echo "👑"; break;
                                default: echo "🏆"; break;
                            }
                        ?>
                </span>

                <h3><?php echo $logro['nombre']; ?></h3>
                <p><?php echo $logro['descripcion']; ?></p>
                <span class="xp-badge">+<?php echo $logro['recompensa_xp']; ?> XP</span>
            </div>
            <?php endwhile; ?>
        </div>
    </main>

</body>

</html>