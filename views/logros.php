<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Mis Logros
    </title>

    <link
        rel="stylesheet"
        href="../public/css/logros.css"
    >

</head>

<body>

<header class="navbar">

    <div class="logo">
        LectoEscritura
    </div>

    <div class="stats-panel">

        <div
            class="stat-item"
            id="nombreUsuario"
        >
            👤 ...
        </div>

        <div class="divider"></div>

        <div
            class="stat-item"
            id="nivelUsuario"
        >
            🏅 ...
        </div>

        <div class="divider"></div>

        <div
            class="stat-item"
            id="rachaUsuario"
        >
            🔥 ...
        </div>

        <div class="divider"></div>

        <div
            class="stat-item"
            id="xpUsuario"
        >
            ⭐ ...
        </div>

    </div>

    <a
        href="../views/pantalla_principal_Usuario.php"
        class="btn-volver"
    >
        Volver
    </a>

</header>

<main class="container">

    <section>

        <h2>
            🔓 Tus logros desbloqueados
        </h2>

        <div
            id="logrosDesbloqueados"
            class="achievements-grid"
        >
            <p>
                Cargando...
            </p>
        </div>

    </section>

    <section>

        <h2>
            🏆 Todos los logros
        </h2>

        <div
            id="todosLosLogros"
            class="achievements-grid"
        >
            <p>
                Cargando...
            </p>
        </div>

    </section>

</main>

<script src="../public/JS/logro/cargarLogrosUsuario.js"></script>

</body>
</html>