<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Actualizar Logro — EVAL
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

    <a href="pantalla_principal_Admin.php">
        Panel Admin
    </a>

    /

    <a href="gestionarLogros.php">
        Gestionar Logros
    </a>

    / Actualizar Logro

</nav>

<div class="actualizar-wrapper">

    <div class="actualizar-card">

        <div class="actualizar-header">

            <div class="actualizar-header-icon">
                🏆
            </div>

            <div>

                <h1 class="actualizar-titulo">
                    Actualizar Logro
                </h1>

            </div>

        </div>

        <form
            id="formActualizarLogro"
            class="actualizar-form"
        >

            <input
                type="hidden"
                id="ID"
                name="ID"
            >

            <div class="actualizar-row">

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

                    <span class="hint">
                        Nombre identificador
                    </span>

                </div>

                <div class="form-group">

                    <label for="recompensa_xp">
                        Recompensa XP
                    </label>

                    <input
                        type="number"
                        id="recompensa_xp"
                        name="recompensa_xp"
                        min="0"
                        required
                    >

                </div>

            </div>

            <div class="form-group">

                <label for="descripcion">
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="3"
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

            <div
                id="contenedorError"
                class="alerta alerta-error"
                style="display:none;"
            ></div>

            <div class="actualizar-footer">

                <a
                    href="gestionarLogros.php"
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

<script src="../public/JS/logro/cargarLogroActualizar.js"></script>
<script src="../public/JS/logro/actualizarLogro.js"></script>

</body>
</html>