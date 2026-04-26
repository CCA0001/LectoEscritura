<?php
session_start(); 

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

include("Acciones/contador_racha_inicioUsuario.php"); 

$nombre_real = $_SESSION['nombre_real'] ?? 'Estudiante';
$racha_usuario = $_SESSION['racha_usuario'] ?? 0;
$puntos_xp = $_SESSION['puntos_xp'] ?? 0;
$rango_actual = $_SESSION['rango_actual'] ?? 'Principiante';
$total_logros = $_SESSION['total_logros'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LectoEscritura</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="css/pantalla_principal_Usuario.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/dropdown_usuario.css?v=<?php echo time(); ?>">
</head>

<body>

    <header class="navbar">
        <span class="logo">EVAL</span>

        <div class="user-rank" id="userRankBtn">
            🏅 <?php echo $rango_actual; ?>
            <span class="dropdown-arrow">▼</span>
        </div>

        <a href="Acciones/cerrar_sesion.php" class="btn-logout">CERRAR SESIÓN</a>
    </header>

    <div class="user-info-panel" id="userInfoPanel">
        <div class="info-header">
            <span class="info-emoji">👤</span>
            <span class="info-name"><?php echo $nombre_real; ?></span>
        </div>
        <div class="info-row">
            <span>🏅 Rango:</span>
            <strong><?php echo $rango_actual; ?></strong>
        </div>
        <div class="info-row">
            <span>🔥 Racha:</span>
            <strong><?php echo $racha_usuario; ?> días</strong>
        </div>
        <div class="info-row">
            <span>⭐ XP total:</span>
            <strong><?php echo number_format($puntos_xp); ?> XP</strong>
        </div>
        <div class="info-row">
            <span>🏆 Logros:</span>
            <strong><?php echo $total_logros; ?> desbloqueados</strong>
        </div>
    </div>

    <nav class="breadcrumb">
        &lt; 📍 Inicio / Panel Usuario
    </nav>

    <main class="container">
        <section class="welcome">
            <h1 class="welcome-title">Bienvenido, <?php echo $nombre_real; ?></h1>
            <p>¿List@ para empezar a cultivar tu conocimiento?</p>
            <div class="streak-badge">
                <span>Racha de</span>
                <strong><?php echo $racha_usuario; ?></strong>
                <span>¡Días!</span>
            </div>
        </section>

        <div class="cards-grid">
            <div class="card">
                <h2>Ejercicios de Lectura</h2>
                <p>Practica lectura comprensiva con textos y preguntas</p>
                <div class="emoji-container">📖</div>
                <a href="index.html" class="btn" style="text-decoration: none; display: inline-block;">Comenzar</a>
            </div>

            <div class="card">
                <h2>Ejercicios de Escritura</h2>
                <p>Mejora tu escritura con actividades didácticas</p>
                <div class="emoji-container">📝</div>
                <a href="ejercicio_escritura.php" class="btn" style="text-decoration: none; display: inline-block;">Comenzar</a>
            </div>
        </div>

        <section class="bottom-panel">
            <div class="progress-section">
                <h3>Tus Progresos</h3>
                <div class="progress-item">
                    <span>📚 Lectura</span>
                    <span class="percent">70%</span>
                    <div class="progress-bar">
                        <div class="fill" style="width: 70%;"></div>
                    </div>
                </div>
                <div class="progress-item">
                    <span>📗 Escritura</span>
                    <span class="percent">30%</span>
                    <div class="progress-bar">
                        <div class="fill" style="width: 30%;"></div>
                    </div>
                </div>
            </div>

            <div class="medals-section">
                <h3>Tus Medallas</h3>
                <div class="emoji-medal">🏆</div>
                <a href="logros.php" class="btn small" style="text-decoration: none; display: inline-block;">Ver
                    logros</a>
            </div>
        </section>
    </main>

    <div id="data-helper" style="display: none;"
        data-mensaje-racha="<?php echo isset($_SESSION['mostrar_alerta_racha']) ? htmlspecialchars($_SESSION['mostrar_alerta_racha']) : ''; ?>"
        data-puntos-recientes="<?php echo $_SESSION['puntos_recientes'] ?? 0; ?>"
        data-logro-nombre="<?php echo $_SESSION['mostrar_logro']['nombre'] ?? ''; ?>"
        data-logro-xp="<?php echo $_SESSION['mostrar_logro']['xp'] ?? ''; ?>">
    </div>

    <script src="JS/racha_alerta.js"></script>
    <script src="JS/dropdown_usuario.js"></script>

    <?php 
    unset($_SESSION['mostrar_alerta_racha']);
    unset($_SESSION['puntos_recientes']);
    unset($_SESSION['mostrar_logro']);
    ?>

</body>

</html>