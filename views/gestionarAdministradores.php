<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestionar Administradores — EVAL</title>

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

    <span class="logo">EVAL</span>

    <a href="/Proyecto/controllers/AuthController.php?accion=logout"
       class="btn-logout">
        Cerrar sesión
    </a>

</header>

<nav class="breadcrumb">

    📍 Inicio /

    <a href="../views/pantalla_principal_Admin.php">
        Panel Admin
    </a>

    / Gestionar Administradores

</nav>

<div class="workspace">

    <!-- TABLA -->

    
    <section class="panel-tabla">

        <div class="panel-header">

            <h2>
                👥 Administradores registrados
            </h2>

        </div>

        <div
            class="tabla-scroll"
            id="contenedorTablaAdmins"
        >

            <p>
                Cargando administradores...
            </p>

        </div>

        <div
            class="panel-footer"
            id="totalAdmins"
        >
            Total: 0
        </div>

    </section>
    <!-- FORM -->

    <section class="panel-form">

        <div class="panel-header">

            <h2>
                ➕ Agregar administrador
            </h2>

        </div>

        <div class="form-body">

            <p class="subtitulo">

                Completa los campos para crear un nuevo administrador.

            </p>

            <form id="formAdmin">

                <div class="form-group">

                    <label for="nombres">
                        Nombres del administrador
                    </label>

                    <input
                        type="text"
                        id="nombres"
                        name="nombres"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="apellidos">
                        Apellidos del administrador
                    </label>

                    <input
                        type="text"
                        id="apellidos"
                        name="apellidos"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="nombre_usuario">
                        Nombre de Usuario
                    </label>

                    <input
                        type="text"
                        id="nombre_usuario"
                        name="nombre_usuario"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="contrasenia">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        id="contrasenia"
                        name="contrasenia"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="confirmar_contrasenia">
                        Confirmar Contraseña
                    </label>

                    <input
                        type="password"
                        id="confirmar_contrasenia"
                        name="confirmar_contrasenia"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="correo">
                        Correo Electrónico
                    </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
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
                onclick="document.getElementById('formAdmin').reset()"
            >
                Limpiar
            </button>

            <button
                type="submit"
                form="formAdmin"
                class="btn-guardar"
            >
                Agregar admin
            </button>

        </div>

    </section>

</div>

<script src="../public/JS/administradores/cargarAdmins.js"></script>

<script src="../public/JS/administradores/agregarAdmin.js"></script>

</body>
</html>