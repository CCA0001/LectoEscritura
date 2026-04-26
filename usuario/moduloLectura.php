<?php
    session_start();
    include("../conexion.php");

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
    <script src="traerTextosLectura.js" defer></script>
    <link rel="stylesheet" href="../css/lectura.css?v=<?php echo time(); ?>">
</head>

<body>

    <header class="navbar">
        <span class="logo">LectoEscritura — Lectura</span>
        <div class="user-rank">
            🏅 <?php echo $_SESSION['rango_actual'] ?? 'Principiante'; ?>
        </div>
        <a href="pantalla_principal_Usuario.php" class="btn-volver">← Volver</a>
    </header>

    <nav class="breadcrumb">
        📍 Inicio / Panel Usuario / Ejercicio de Lectura
    </nav>

    <div class="lectura-workspace">

        <div class="panel-derecho">
            <div class="panel-derecho-placeholder">
            </div>
        </div>

        <aside class="panel-lectura">
            <div class="panel-lectura-header">
                <h1>📖 Texto de lectura</h1>
            </div>
            <h2 id="titulo"></h2>
            <div class="panel-lectura-scroll">
                <p id="contenido"></p>
                <p class="preguntas-titulo">📝 Preguntas</p>
                <div id="preguntas"></div>
            </div>
            <div class="panel-lectura-footer">
                <button id="btnVerificar">¡Terminé!</button>
                <p id="resultado"></p>
            </div>
        </aside>

    </div>

    <section class="seccion-avanzado">

        <div class="seccion-avanzado-header">
            <h2>🎯 Textos de nivel intermedio y avanzado</h2>
            <p>Pon a prueba tu comprensión lectora con textos más exigentes.</p>
        </div>

        <!-- Las tarjetas las genera script.js dinámicamente -->
        <div id="lista-ejercicios-avanzados"></div>

    </section>

    </body>
</html>