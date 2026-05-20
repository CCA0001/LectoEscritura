<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestionar Administradores — EVAL</title>

    <link rel="stylesheet"
          href="../public/css/pantalla_principal_Usuario.css?v=<?php echo time(); ?>">

    <link rel="stylesheet"
          href="../public/css/gestionar.css?v=<?php echo time(); ?>">
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

    <a href="pantalla_principal_Admin.php">
        Panel Admin
    </a>

    / Gestionar Administradores

</nav>

<div class="workspace">

    <!-- TABLA -->

    <section class="panel-tabla">

        <div class="panel-header">
            <h2>👥 Administradores registrados</h2>
        </div>

        <div class="tabla-scroll">

            <?php if(empty($admins)): ?>

                <div class="tabla-vacia">
                    <p>No hay administradores registrados aún.</p>
                </div>

            <?php else: ?>

            <table>

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach($admins as $admin): ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($admin['ID']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($admin['nombre_usuario']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($admin['correo_electronico']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($admin['estado']); ?>
                        </td>

                        <td>
                            <?php
                                echo date(
                                    'd/m/Y',
                                    strtotime($admin['fecha_registro'])
                                );
                            ?>
                        </td>

                        <td>

                            <form
                                method="POST"
                                action="../controllers/AdminController.php?accion=invertirEstadoAdmin"
                                onsubmit="return confirm('Cambiar estado del administrador?')"
                            >

                                <input
                                    type="hidden"
                                    name="ID"
                                    value="<?php echo (int)$admin['ID']; ?>"
                                >
                                <input
                                    type="hidden"
                                    name="estado"
                                    value="<?php echo $admin['estado']; ?>"
                                >
                                <button
                                    type="submit"
                                    class="btn-eliminar"
                                >
                                    Invertir estado
                                </button>

                            </form>

                            <a
                                href="../controllers/AdminController.php?accion=mostrarVistaActualizar&id=<?php echo $admin['ID']; ?>"
                                class="btn-estado"
                            >
                                Actualizar
                            </a>
                        </td>

                    </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

            <?php endif; ?>

        </div>

        <div class="panel-footer">

            Total:

            <strong>
                <?php echo count($admins); ?>
            </strong>

            administrador(es)

        </div>

    </section>

    <!-- FORMULARIO -->

    <section class="panel-form">

        <div class="panel-header">
            <h2>➕ Agregar administrador</h2>
        </div>

        <div class="form-body">

            <form
                method="POST"
                action="../controllers/AdminController.php?accion=agregarAdministrador"
                id="formAdmin"
            >

                <div class="form-group">

                    <label>Nombre completo</label>

                    <input
                        type="text"
                        name="nombre"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Nombre usuario</label>

                    <input
                        type="text"
                        name="nombre_usuario"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Correo electrónico</label>

                    <input
                        type="email"
                        name="correo_electronico"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Contraseña</label>

                    <input
                        type="password"
                        name="contrasenia"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Confirmar contraseña</label>

                    <input
                        type="password"
                        name="contrasenia_confirmar"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Estado</label>

                    <input
                        type="text"
                        name="estado"
                        required
                    >

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
                Registrar administrador
            </button>

        </div>

    </section>

</div>

</body>
</html>