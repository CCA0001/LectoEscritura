<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        LectoEscritura
    </title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link
        rel="stylesheet"
        href="../public/css/pantalla_principal_Usuario.css"
    >

    <link
        rel="stylesheet"
        href="../public/css/dropdown_usuario.css"
    >

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

    <a
        href="#"
        id="btnLogout"
        class="btn-logout"
    >
        CERRAR SESIÓN
    </a>

</header>

<!-- Información del usuario -->

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

    📍 Inicio / Panel Usuario

</nav>

<main class="container">

    <section class="welcome">

        <h1
            class="welcome-title"
            id="tituloBienvenida"
        >
            Bienvenido
        </h1>

        <p>
            ¿List@ para empezar a cultivar tu conocimiento?
        </p>

        <div class="streak-badge">

            <span>
                Racha de
            </span>

            <strong
                id="rachaBadge"
            >
                0
            </strong>

            <span>
                ¡Días!
            </span>

        </div>

    </section>

    <div class="cards-grid">

        <div class="card">

            <h2>
                Ejercicios de Lectura
            </h2>

            <p>
                Practica lectura comprensiva con textos y preguntas
            </p>

            <div class="emoji-container">
                📖
            </div>

            <a
                href="../views/moduloLectura.html"
                class="btn"
            >
                Comenzar
            </a>

        </div>

        <div class="card">

            <h2>
                Ejercicios de Escritura
            </h2>

            <p>
                Mejora tu escritura con actividades didácticas
            </p>

            <div class="emoji-container">
                📝
            </div>

            <a
                href="../views/moduloEscritura.html"
                class="btn"
            >
                Comenzar
            </a>

        </div>

    </div>

    <section class="bottom-panel">

        <div class="progress-section">

            <h3>
                Tus Progresos
            </h3>

            <div class="progress-item">

                <span>
                    📚 Lectura
                </span>

                <span
                    class="percent"
                    id="porcentajeLectura"
                >
                    0%
                </span>

                <div class="progress-bar">

                    <div
                        class="fill"
                        id="barraLectura"
                        style="width: 0%;"
                    ></div>

                </div>

            </div>

            <div class="progress-item">

                <span>
                    📗 Escritura
                </span>

                <span
                    class="percent"
                    id="porcentajeEscritura"
                >
                    0%
                </span>

                <div class="progress-bar">

                    <div
                        class="fill"
                        id="barraEscritura"
                        style="width: 0%;"
                    ></div>

                </div>

            </div>

        </div>

        <div class="medals-section">

            <h3>
                Tus Medallas
            </h3>

            <div class="emoji-medal">
                🏆
            </div>

            <a
                href="../views/logros.html"
                class="btn small"
            >
                Ver logros
            </a>

        </div>

    </section>

</main>

<script src="../public/JS/usuario/cargarPerfilUsuario.js"></script>

<script src="../public/JS/auth/logout.js"></script>

<script src="../public/JS/dropdown_usuario.js"></script>

</body>

</html>