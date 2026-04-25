<?php
session_start(); 

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

include("Acciones/contador_racha_inicioUsuario.php"); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/pantalla_principal_Usuario.css?v=<?php echo time(); ?>">
    <title>LectoEscritura</title>
</head>
<body>

    <header class="navbar">
        <span class="logo">LectoEscritura</span>
        <a href="Acciones/cerrar_sesion.php" class="btn-logout">CERRAR SESIÓN</a>
    </header>

    <nav class="breadcrumb">
        &lt; Inicio / Panel Usuario
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
                <a href="logros.php" class="btn small" style="text-decoration: none; display: inline-block;">Ingresa</a>
            </div>
        </section>
    </main>
<script>
    const alertaMostrada = sessionStorage.getItem('rachaMostrada');

    <?php if (isset($_SESSION['mensaje_racha']) && !empty($_SESSION['mensaje_racha'])): ?>
        if (!alertaMostrada) {
            Swal.fire({
                title: '¡Hola De Nuevo!',
                text: '<?php echo $_SESSION['mensaje_racha']; ?>',
                icon: 'success',
                confirmButtonColor: '#4a8d6e',
                confirmButtonText: '¡A estudiar!'
            }).then(() => {
                sessionStorage.setItem('rachaMostrada', 'true');
                <?php unset($_SESSION['mensaje_racha']); ?>
            });
        }
    <?php endif; ?>

    <?php if (isset($_SESSION['mostrar_logro'])): ?>
        Swal.fire({
            title: '¡NUEVO LOGRO DESBLOQUEADO!',
            html: 'Ganaste la medalla: <b><?php echo $_SESSION['mostrar_logro']['nombre']; ?></b><br>+<b><?php echo $_SESSION['mostrar_logro']['xp']; ?> XP</b>',
            icon: 'success',
            iconColor: '#ffbb33',
            confirmButtonText: '¡Genial!',
            confirmButtonColor: '#4a8d6e'
        });
        <?php unset($_SESSION['mostrar_logro']); ?>
    <?php endif; ?>
</script>
</body>
</html>