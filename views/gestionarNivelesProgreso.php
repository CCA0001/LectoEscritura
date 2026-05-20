<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Gestionar Niveles — EVAL</title>

    <link rel="stylesheet"
          href="../public/css/pantalla_principal_Usuario.css?v=<?php echo time(); ?>">

    <link rel="stylesheet"
          href="../public/css/gestionar.css?v=<?php echo time(); ?>">

</head>
<body>

<header class="navbar">

    <span class="logo">EVAL</span>

</header>

<nav class="breadcrumb">

    📍 Inicio /

    <a href="pantalla_principal_Admin.php">
        Panel Admin
    </a>

    / Gestionar Niveles

</nav>

<div class="workspace">

    <!-- TABLA -->

    <section class="panel-tabla">

        <div class="panel-header">
            <h2>⭐ Niveles registrados</h2>
        </div>

        <div class="tabla-scroll">

            <?php if(empty($niveles)): ?>

                <div class="tabla-vacia">
                    <p>No hay niveles registrados aún.</p>
                </div>

            <?php else: ?>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nivel</th>
                        <th>XP</th>
                        <th>ID Admin Responsable</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach($niveles as $nivel): ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($nivel['ID']); ?>
                        </td>

                        <td>
                            Nivel <?php echo htmlspecialchars($nivel['nombre']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($nivel['puntos_requeridos']); ?> XP
                        </td>

                        <td>
                            <?php echo htmlspecialchars($nivel['ID_adminResponsable']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($nivel['descripcion']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($nivel['estado']); ?>
                        </td>

                        <td>

                            <!-- CAMBIAR ESTADO -->

                            <form
                                method="POST"
                                action="../controllers/NivelController.php?accion=invertirEstadoNivel"
                                onsubmit="return confirm('¿Cambiar estado del nivel?')"
                            >

                                <input
                                    type="hidden"
                                    name="ID"
                                    value="<?php echo $nivel['ID']; ?>"
                                >

                                <input
                                    type="hidden"
                                    name="estado"
                                    value="<?php echo $nivel['estado']; ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn-eliminar"
                                >
                                    Cambiar estado
                                </button>

                            </form>

                            <a
                                href="../controllers/NivelController.php?accion=mostrarVistaActualizar&id=<?php echo $nivel['ID']; ?>"
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
                <?php echo count($niveles); ?>
            </strong>

            nivel(es)

        </div>

    </section>

    <!-- FORMULARIO -->

    <section class="panel-form">

        <div class="panel-header">
            <h2>➕ Agregar Nivel</h2>
        </div>

        <div class="form-body">

            <form
                method="POST"
                action="../controllers/NivelController.php?accion=agregarNivel"
                id="formNivel"
            >

                <div class="form-group">

                    <label>Nombre de nivel</label>

                    <input
                        type="text"
                        name="nombre"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>XP requerida</label>

                    <input
                        type="number"
                        name="xp_requerida"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Descripción</label>

                    <textarea
                        name="descripcion"
                        required
                    ></textarea>

                </div>

                <div class="form-group">

                    <label>Estado</label>

                    <select name="estado">

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
                onclick="document.getElementById('formNivel').reset()"
            >
                Limpiar
            </button>

            <button
                type="submit"
                form="formNivel"
                class="btn-guardar"
            >
                Registrar nivel
            </button>

        </div>

    </section>

</div>

</body>
</html>