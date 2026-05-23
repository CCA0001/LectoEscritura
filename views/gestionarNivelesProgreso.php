<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Gestionar Niveles de Progreso
    </title>

    <link rel="stylesheet" href="../public/css/gestionar.css">

    <link rel="stylesheet" href="../public/css/pantalla_principal_Usuario.css">
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

    <section class="panel-tabla">

        <div class="panel-header">

            <h2>
                📈 Niveles registrados
            </h2>

        </div>

        <div
            class="tabla-scroll"
            id="contenedorNiveles"
        >

            <p>
                Cargando niveles...
            </p>

        </div>

    </section>

    <section class="panel-form">

        <div class="panel-header">

            <h2>
                ➕ Agregar Nivel
            </h2>

        </div>

        <form
            id="formNivel"
        >

            <div class="form-group">

                <label>
                    Nombre
                </label>

                <input
                    type="text"
                    id="nombre"
                    required
                >

            </div>

            <div class="form-group">

                <label>
                    XP requerida
                </label>

                <input
                    type="number"
                    id="xp_requerida"
                    required
                >

            </div>

            <div class="form-group">

                <label>
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                ></textarea>

            </div>

            <div class="form-group">

                <label>
                    Estado
                </label>

                <select
                    id="estado"
                >

                    <option value="Activo">
                        Activo
                    </option>

                    <option value="Inactivo">
                        Inactivo
                    </option>

                </select>

            </div>

            <button
                type="submit"
            >
                Agregar nivel
            </button>

        </form>

    </section>

</div>

<script src="../public/JS/nivelProgreso/cargarNivelesProgreso.js"></script>
<script src="../public/JS/nivelProgreso/agregarNivel.js"></script>


</body>
</html>