<?php
    session_start();
    include("conexion.php");

    if (!isset($_SESSION['id_usuario'])) {
        header("Location: login.html");
        exit();
    }

    $id_usuario = $_SESSION['id_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio de Lectura — LectoEscritura</title>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="css/lectura.css?v=<?php echo time(); ?>">
</head>

<body>

<!-- ══ NAVBAR ══ -->
<header class="navbar">
    <span class="logo">LectoEscritura — Lectura</span>
    <div class="user-rank">
        🏅 <?php echo $_SESSION['rango_actual'] ?? 'Principiante'; ?>
    </div>
    <a href="pantalla_principal_Usuario.php" class="btn-volver">← Volver</a>
</header>

<!-- ══ BREADCRUMB ══ -->
<nav class="breadcrumb">
    📍 Inicio / Panel Usuario / Ejercicio de Lectura
</nav>

<!-- ══ ÁREA DE TRABAJO ══ -->
<div class="lectura-workspace">

    <!-- Fondo / zona derecha (60%) — espacio libre para tu idea -->
    <div class="panel-derecho">

        <!-- 
        ╔═══════════════════════════════════════════════════════╗
        ║  ZONA LIBRE — Aquí va tu idea                        ║
        ║                                                       ║
        ║  Este div ocupa todo el fondo del workspace.         ║
        ║  El panel de lectura flota encima a la izquierda.    ║
        ║  Puedes poner aquí:                                   ║
        ║    - Un visor de PDF / imagen                        ║
        ║    - Un área de escritura / textarea                  ║
        ║    - Un chat o asistente IA                           ║
        ║    - Un canvas interactivo                            ║
        ║    - Estadísticas, gráficas, etc.                    ║
        ╚═══════════════════════════════════════════════════════╝
        -->

        <!-- Placeholder decorativo (bórralo cuando implementes tu idea) -->
        <div class="panel-derecho-placeholder">
            <span class="placeholder-icon">✏️</span>
            <p>Este espacio está reservado para tu próxima funcionalidad</p>
        </div>

    </div>

    <!-- Panel izquierdo FLOTANTE (40%) — texto y preguntas de script.js -->
    <aside class="panel-lectura">

        <div class="panel-lectura-header">
            <h1>📖 Texto de lectura</h1>
        </div>

        <!-- Título del texto (llenado por script.js) -->
        <h2 id="titulo"></h2>

        <!-- Zona con scroll interno -->
        <div class="panel-lectura-scroll">

            <!-- Contenido del texto (llenado por script.js) -->
            <p id="contenido"></p>

            <p class="preguntas-titulo">📝 Preguntas</p>

            <!-- Preguntas (llenado por script.js) -->
            <div id="preguntas"></div>

        </div>

        <!-- Botón y resultado -->
        <div class="panel-lectura-footer">
            <button id="btnVerificar">Verificar</button>
            <p id="resultado"></p>
        </div>

    </aside>

</div><!-- /.lectura-workspace -->

</body>
</html>