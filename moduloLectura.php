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

    <div></div>


</div><!-- /.lectura-workspace -->


<!-- ══════════════════════════════════════
     SECCIÓN AVANZADO / INTERMEDIO
     Las tarjetas se generan dinámicamente
     con PHP o JS — pueden ser n textos.
══════════════════════════════════════ -->
    <section class="seccion-avanzado">
    
        <div class="seccion-avanzado-header">
            <h2>🎯 Textos de nivel intermedio y avanzado</h2>
            <p>Pon a prueba tu comprensión lectora con textos más exigentes.</p>
        </div>
    
        <!--
        ══════════════════════════════════════════════════════════════
        CONTENEDOR DINÁMICO
        Aquí PHP (o JS) inyectará una tarjeta por cada texto
        de dificultad intermedia o avanzada que traiga la BD.
    
        Cuando me pidas el código, cada tarjeta generada
        seguirá esta estructura:
    
        <article class="ejercicio-avanzado">
    
            <div class="ejercicio-avanzado-header">
                <span class="badge-dificultad intermedio">Intermedio</span>
                ó
                <span class="badge-dificultad avanzado">Avanzado</span>
                <h3>Título del texto</h3>
            </div>
    
            <div class="ejercicio-avanzado-body">
                <p class="ejercicio-texto">Texto largo aquí...</p>
                <p class="ejercicio-preguntas-label">📝 Preguntas</p>
                <div class="ejercicio-preguntas-lista">
                    <div class="ejercicio-pregunta">
                        <h4>1. ¿Pregunta?</h4>
                        <label><input type="radio" name="ejN-pM" value="ID_opcion"> Opción</label>
                        ...
                    </div>
                    ...
                </div>
            </div>
    
            <div class="ejercicio-avanzado-footer">
                <button class="btn-verificar-avanzado">¡Terminé!</button>
                <p class="resultado-avanzado"></p>
            </div>
    
        </article>
    
        N puede ser cualquier número — el CSS se adapta solo.
        ══════════════════════════════════════════════════════════════
        -->
        <div id="lista-ejercicios-avanzados">
            <!-- Las tarjetas van aquí, generadas por PHP o JS -->
        </div>
    
    </section><!-- /.seccion-avanzado -->
</body>
</html>