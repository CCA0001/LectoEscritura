<?php
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Gestionar Textos — EVAL
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

    / Gestionar Textos

</nav>

<div class="workspace">

    <!-- TABLA -->

    <section class="panel-tabla">

        <div class="panel-header">

            <h2>
                📚 Textos registrados
            </h2>

        </div>

        <div
            class="tabla-scroll"
            id="contenedorTablaTextos"
        >

            <p>
                Cargando textos...
            </p>

        </div>

        <div
            class="panel-footer"
            id="totalTextos"
        >
            Total: 0
        </div>

    </section>

    <!-- FORM -->

    <section class="panel-form">

        <div class="panel-header">

            <h2>
                ➕ Agregar texto
            </h2>

        </div>

        <div class="form-body">

            <p class="subtitulo">

                Completa los campos para registrar un nuevo texto.

            </p>

            <form id="formTexto">
                <div class="form-group">

                    <label for="dificultad">
                        Nivel de dificultad
                    </label>

                    <select
                        id="dificultad"
                        name="dificultad"
                        required
                    >
                        <option value="">
                            Cargando...
                        </option>
                    </select>

                </div>

                <div class="form-group">

                    <label for="tipo_texto">
                        Tipo de texto
                    </label>

                    <select
                        id="tipo_texto"
                        name="tipo_texto"
                        required
                    >
                        <option value="">
                            Cargando...
                        </option>
                    </select>

                </div>

                <div class="form-group">

                    <label for="titulo">
                        Título
                    </label>

                    <input
                        type="text"
                        id="titulo"
                        name="titulo"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="contenido">
                        Contenido
                    </label>

                    <textarea
                        id="contenido"
                        name="contenido"
                        required
                    ></textarea>

                </div>

                <div class="form-group">

                    <label for="fuente">
                        Fuente
                    </label>

                    <textarea
                        id="fuente"
                        name="fuente"
                        required
                    ></textarea>

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
                onclick="document.getElementById('formTexto').reset()"
            >
                Limpiar
            </button>

            <button
                type="submit"
                form="formTexto"
                class="btn-guardar"
            >
                Agregar texto
            </button>

        </div>

    </section>

</div>
<script src="../public/JS/texto/cargarDificultadYTipoTexto.js"></script>
<script src="../public/JS/texto/cargarTextos.js"></script>
<script src="../public/JS/texto/agregarTexto.js"></script>

</body>
</html>