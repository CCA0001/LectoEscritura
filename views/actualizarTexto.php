<?php

require_once(__DIR__ . "/../config/vista_admin.php");

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
        Actualizar Texto — EVAL
    </title>

    <link
        rel="stylesheet"
        href="../public/css/pantalla_principal_Usuario.css"
    >

    <link
        rel="stylesheet"
        href="../public/css/gestionar.css"
    >

    <link
        rel="stylesheet"
        href="../public/css/actualizar.css"
    >

</head>

<body>

<header class="navbar">

    <span class="logo">
        EVAL
    </span>

</header>

<nav class="breadcrumb">

    📍 Inicio /

    <a href="../views/pantalla_principal_Admin.php">
        Panel Admin
    </a>

    /

    <a href="../views/gestionarTextos.php">
        Gestionar Textos
    </a>

    / Actualizar Texto

</nav>

<div class="actualizar-wrapper">

    <div class="actualizar-card">

        <div class="actualizar-header">

            <div class="actualizar-header-icon">
                📚
            </div>

            <div>

                <h1 class="actualizar-titulo">
                    Actualizar Texto
                </h1>

                <p class="actualizar-subtitulo">
                    Editá el contenido del texto de lectura. Los cambios
                    quedan disponibles de inmediato para los estudiantes.
                </p>

            </div>

        </div>

        <form
            id="formActualizarTexto"
            class="actualizar-form"
        >

            <input
                type="hidden"
                id="ID"
                name="ID"
            >

            <div class="actualizar-row">

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

            </div>

            <div class="form-group">

                <label for="titulo">
                    Título
                </label>

                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    maxlength="200"
                    required
                >

                <span class="hint">
                    Título visible para el estudiante
                </span>

            </div>

            <div class="form-group">

                <label for="contenido">
                    Contenido
                </label>

                <textarea
                    id="contenido"
                    name="contenido"
                    rows="10"
                    maxlength="2000"
                    required
                ></textarea>

                <span class="hint">
                    Texto que el estudiante va a leer (máx. 2000 caracteres)
                </span>

            </div>

            <div class="form-group">

                <label for="fuente">
                    Fuente
                </label>

                <input
                    type="text"
                    id="fuente"
                    name="fuente"
                    maxlength="200"
                    placeholder="Ej: ICFES Saber TyT 2024"
                >

                <span class="hint">
                    De dónde salió el texto (opcional)
                </span>

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

                <span class="hint">
                    Solo los textos activos aparecen en el módulo de lectura
                </span>

            </div>

            <div
                id="contenedorError"
                class="alerta alerta-error"
                style="display:none;"
            ></div>

            <div class="actualizar-footer">

                <a
                    href="../views/gestionarTextos.php"
                    class="btn-cancelar"
                >
                    ← Cancelar
                </a>

                <button
                    type="button"
                    class="btn-limpiar"
                    id="btnRestaurar"
                >
                    Restaurar
                </button>

                <button
                    type="submit"
                    class="btn-guardar"
                >
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>

<script src="../public/JS/escapar.js"></script>
<script src="../public/JS/texto/cargarTextoActualizar.js"></script>
<script src="../public/JS/texto/actualizarTexto.js"></script>

</body>
</html>
