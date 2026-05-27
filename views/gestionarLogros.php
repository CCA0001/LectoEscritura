<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Gestionar Logros — EVAL
    </title>

    <link
        rel="stylesheet"
        href="../public/css/pantalla_principal_Usuario.css"
    >

    <link
        rel="stylesheet"
        href="../public/css/gestionar.css"
    >

</head>

<body>

<header class="navbar">

    <span class="logo">
        EVAL
    </span>

    <a
        href="../controllers/AuthController.php?accion=logout"
        class="btn-logout"
    >
        Cerrar sesión
    </a>

</header>

<nav class="breadcrumb">

    📍 Inicio /

    <a href="../views/pantalla_principal_Admin.php">
        Panel Admin
    </a>

    / Gestionar Logros

</nav>

<div class="workspace">

    <!-- TABLA -->

    <section class="panel-tabla">

        <div class="panel-header">

            <h2>
                🏆 Logros registrados
            </h2>

        </div>

        <div
            class="tabla-scroll"
            id="contenedorTablaLogros"
        >

            <p>
                Cargando logros...
            </p>

        </div>

        <div
            class="panel-footer"
            id="totalLogros"
        >
            Total: 0
        </div>

    </section>

    <!-- FORM -->

    <section class="panel-form">

        <div class="panel-header">

            <h2>
                ➕ Agregar logro
            </h2>

        </div>

        <div class="form-body">

            <p class="subtitulo">

                Completa los campos para crear un nuevo logro.

            </p>

            <form id="formLogro">

                <div class="form-group">

                    <label for="nombre">
                        Nombre del logro
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="descripcion">
                        Descripción
                    </label>

                    <input
                        type="text"
                        id="descripcion"
                        name="descripcion"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="xp">
                        Recompensa XP
                    </label>

                    <input
                        type="number"
                        id="xp"
                        name="recompensa_xp"
                        min="0"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="estado">
                        Estado
                    </label>

                    <select
                        id="estado"
                        name="estado"
                    >

                        <option value="Activo">
                            Activo
                        </option>

                        <option value="Inactivo">
                            Inactivo
                        </option>

                    </select>

                </div>

            </form>

        </div>

        <div class="form-footer">

            <button
                type="button"
                class="btn-limpiar"
                onclick="document.getElementById('formLogro').reset()"
            >
                Limpiar
            </button>

            <button
                type="submit"
                form="formLogro"
                class="btn-guardar"
            >
                Agregar logro
            </button>

        </div>

    </section>

</div>

<script src="../public/JS/logro/cargarLogros.js"></script>

<script src="../public/JS/logro/agregarLogro.js"></script>

</body>
</html>