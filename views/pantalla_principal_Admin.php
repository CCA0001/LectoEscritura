<?php
session_start(); 
/*
if (!isset($_SESSION['id_admin'])) {
    header("Location: login.html");
    exit();
}

*/
$nombre_real = $_SESSION['nombre'] ?? 'Administrador';

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

        <a href="Acciones/cerrar_sesion.php" class="btn-logout">CERRAR SESIÓN</a>
    </header>

    <div class="user-info-panel" id="userInfoPanel">
        <div class="info-header">
            <span class="info-emoji">👤</span>
            <span class="info-name"><?php echo $nombre_real; ?></span>
        </div>
    </div>

    <nav class="breadcrumb">
         📍 Inicio / Panel Admin
    </nav>

    <main class="container">
        <section class="welcome">
            <h1 class="welcome-title">Bienvenido, <?php echo $nombre_real; ?></h1>
            <p>Nos alegra verlo de nuevo</p>
        </section>

        <div class="cards-grid">
            <div class="card">
                <h2>Gestiona Administradores</h2>
                <p>Crear, actualiza o desactiva administradores del sistema</p>
                <div class="emoji-container">📖</div>
                <a href="gestionarAdministradores.php" class="btn" style="text-decoration: none; display: inline-block;">Comenzar</a>
            </div>

            <div class="card">
                <h2>Gestiona Textos</h2>
                <p>Registra nuevos textos, verifica fuentes o desactiva su aparición para los usuarios</p>
                <div class="emoji-container">📝</div>
                <a href="gestionarTextos.php" class="btn" style="text-decoration: none; display: inline-block;">Comenzar</a>
            </div>
            <div class="card">
                <h2>Gestiona logros</h2>
                <p>Añade o desactiva logros personalizados</p>
                <div class="emoji-container">📝</div>
                <a href="gestionarLogros.php" class="btn" style="text-decoration: none; display: inline-block;">Comenzar</a>
            </div>
            <div class="card">
                <h2>Gestiona niveles de progreso</h2>
                <p>Crea niveles de progreso nuevos para que puedan ser alcanzados por los usuarios </p>
                <div class="emoji-container">📝</div>
                <a href="gestionarNivelesProgreso.php" class="btn" style="text-decoration: none; display: inline-block;">Comenzar</a>
            </div>        
        </div>

            <!--
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
-->

    <script src="JS/dropdown_usuario.js"></script>

</body>

</html>