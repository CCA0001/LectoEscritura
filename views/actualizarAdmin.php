<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Actualizar Administrador — EVAL
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

    <a href="gestionarAdministradores.php">
        Gestionar Administradores
    </a>

    / Actualizar Administrador

</nav>

<div class="actualizar-wrapper">

    <div class="actualizar-card">

        <div class="actualizar-header">

            <div class="actualizar-header-icon">
                👨‍💼
            </div>

            <div>

                <h1 class="actualizar-titulo">
                    Actualizar Administrador
                </h1>

                <p
                    class="actualizar-subtitulo"
                    id="subtituloAdmin"
                >
                    Cargando administrador...
                </p>

            </div>

        </div>

        <form
            id="formActualizarAdmin"
            class="actualizar-form"
        >

            <input
                type="hidden"
                id="ID"
                name="ID"
            >

            <div class="actualizar-row">

                <div class="form-group">

                    <label for="nombres">
                        Nombres
                    </label>

                    <input
                        type="text"
                        id="nombres"
                        name="nombres"
                        required
                    >

                    <span class="hint">
                        Nombres del administrador
                    </span>

                </div>

                <div class="form-group">

                    <label for="apellidos">
                        Apellidos
                    </label>

                    <input
                        type="text"
                        id="apellidos"
                        name="apellidos"
                        required
                    >

                </div>

            </div>

            <div class="actualizar-row">

                <div class="form-group">

                    <label for="nombre_usuario">
                        Nombre de usuario
                    </label>

                    <input
                        type="text"
                        id="nombre_usuario"
                        name="nombre_usuario"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="correo">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        required
                    >

                </div>

            </div>

            <div class="actualizar-row">

                <div class="form-group">

                    <label for="contrasenia">
                        Nueva contraseña
                    </label>

                    <input
                        type="password"
                        id="contrasenia"
                        name="contrasenia"
                    >

                    <span class="hint">
                        Déjalo vacío para mantener la actual
                    </span>

                </div>

                <div class="form-group">

                    <label for="confirmar_contrasenia">
                        Confirmar contraseña
                    </label>

                    <input
                        type="password"
                        id="confirmar_contrasenia"
                        name="confirmar_contrasenia"
                    >

                </div>

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
                    href="gestionarAdministradores.php"
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

<script src="../public/JS/administradores/cargarAdminActualizar.js"></script>
<script src="../public/JS/administradores/actualizarAdmin.js"></script>

</body>
</html>