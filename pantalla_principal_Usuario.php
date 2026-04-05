<?php
include("contador_racha_inicioUsuario.php"); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pantalla_principal_Usuario.css">
    <title>LectoEscritura</title>
</head>
<body>

    <header class="navbar">
        <span class="logo">LectoEscritura</span>
        <a href="cerrar_sesion.php" class="btn-logout">CERRAR SESIÓN</a>
    </header>

    <nav class="breadcrumb">
        &lt; Inicio / Panel Usuario
    </nav>

    <main class="container">
        <section class="welcome">
            <h1 class="welcome-title">Bienvenido, <?php echo $nombre_real; ?></h1>
            <p>¿Listo para empezar a cultivar tu conocimiento?</p>
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
        <a href="index.html" class="btn" style="text-decoration: none; display: inline-block;">Comenzar lectura</a>
    </div>

    <div class="card">
        <h2>Ejercicios de Escritura</h2>
        <p>Mejora tu escritura con actividades didácticas</p>
        <div class="emoji-container">📝</div>
        <a href="??" class="btn" style="text-decoration: none; display: inline-block;">Comenzar escritura</a>
    </div>
</div>

        <section class="bottom-panel">
            <div class="progress-section">
                <h3>Tus Progresos</h3>
                <div class="progress-item">
                    <span>📚 Lectura</span>
                    <span class="percent">70%</span>
                    <div class="progress-bar"><div class="fill" style="width: 70%;"></div></div>
                </div>
                <div class="progress-item">
                    <span>📗 Escritura</span>
                    <span class="percent">30%</span>
                    <div class="progress-bar"><div class="fill" style="width: 30%;"></div></div>
                </div>
            </div>

            <div class="medals-section">
                <h3>Tus Medallas</h3>
                <div class="emoji-medal">🏆</div>
                <button class="btn small">Ingresa</button>
            </div>
        </section>
    </main>

</body>
</html>