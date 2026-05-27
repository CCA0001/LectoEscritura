<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVAL — LectoEscritura</title>
    <link rel="stylesheet" href="../public/css/lectura.css?v=<?php echo time(); ?>">
</head>

<body>


    <header class="navbar">

        <span class="logo">
            EVAL
        </span>

        <div
            class="user-rank"
            id="userRankBtn"
        >

            🏅

            <span id="rangoUsuario">
                Cargando...
            </span>

            <span class="dropdown-arrow">
                ▼
            </span>

        </div>
        <a href="../views/pantalla_principal_Usuario.php" class="btn-volver">Volver</a>

    </header>

    <h1 hidden id="tituloBienvenida"></h1>
    <strong hidden id="rachaBadge">0</strong>
    <div
        class="user-info-panel"
        id="userInfoPanel"
    >

        <div class="info-header">

            <span class="info-emoji">
                👤
            </span>

            <span
                class="info-name"
                id="nombreUsuario"
            >
                Cargando...
            </span>

        </div>

        <div class="info-row">

            <span>
                🏅 Rango:
            </span>

            <strong id="rangoPanel">
                -
            </strong>

        </div>

        <div class="info-row">

            <span>
                🔥 Racha:
            </span>

            <strong id="rachaUsuario">
                -
            </strong>

        </div>

        <div class="info-row">

            <span>
                ⭐ XP total:
            </span>

            <strong id="xpUsuario">
                -
            </strong>

        </div>

        <div class="info-row">

            <span>
                🏆 Logros:
            </span>

            <strong id="logrosUsuario">
                -
            </strong>

        </div>

    </div>
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

        <div id="lista-ejercicios-avanzados"></div>
        
        <div class="seccion-avanzado-footer">
            <button id="btnTermineAvanzado">¡Terminé!</button>
            <p id="resultado-avanzado-global"></p>
        </div>

    </section>

    </body>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../public/JS/usuario/cargarPerfilUsuario.js"></script>
    <script src="../public/JS/dropdown_usuario.js"></script>
    <script src="../public/JS/lectura/cargarTextoFacil.js"></script>
    <script src="../public/JS/lectura/cargarTextosDificiles.js"></script>
    <script src="../public/JS/lectura/guardarLecturaFacil.js"></script>
    <script src="../public/JS/lectura/guardarLecturaAvanzada.js"></script>
</html>