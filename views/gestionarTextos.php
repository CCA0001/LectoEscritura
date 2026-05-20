<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Gestionar Textos — EVAL</title>

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

    / Gestionar Textos

</nav>

<div class="workspace">

    <!-- TABLA -->

    <section class="panel-tabla">

        <div class="panel-header">
            <h2>📚 Textos registrados</h2>
        </div>

        <div class="tabla-scroll">

            <?php if(empty($textos)): ?>

                <div class="tabla-vacia">
                    <p>No hay textos registrados aún.</p>
                </div>

            <?php else: ?>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nivel Dificultad</th>
                        <th>Tipo Texto</th>
                        <th>Título</th>
                        <th>Fuente</th>
                        <th>ID Generacion IA</th>
                        <th>ID Admin Responsable</th>
                        <th>Estado</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>

                    </tr>
                </thead>

                <tbody>

                    <?php foreach($textos as $texto): ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($texto['ID']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($texto['ID_dificultad']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($texto['ID_tipoTexto']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($texto['titulo']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($texto['fuente']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($texto['ID_generacionIA']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($texto['ID_adminResponsable']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($texto['estado']); ?>
                        </td>    
                        <td>
                            <?php echo htmlspecialchars($texto['fecha_registro']); ?>
                        </td>  
                        <td>


                            <form
                                method="POST"
                                action="../controllers/TextoController.php?accion=invertirEstadoTexto"
                            >

                                <input
                                    type="hidden"
                                    name="ID"
                                    value="<?php echo $texto['ID']; ?>"
                                >

                                <input
                                    type="hidden"
                                    name="estado"
                                    value="<?php echo $texto['estado']; ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn-estado"
                                >
                                    Cambiar estado
                                </button>

                            </form>


                            <a
                                href="../controllers/TextoController.php?accion=mostrarVistaActualizar&id=<?php echo $texto['ID']; ?>"
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

    </section>

    <!-- FORMULARIO -->

    <section class="panel-form">

        <div class="panel-header">
            <h2>➕ Agregar Texto</h2>
        </div>

        <div class="form-body">

            <form
                method="POST"
                action="../controllers/TextoController.php?accion=agregarTextoManualmente"
                id="formTexto"
            >

                <div class="form-group">

                    <label>Nivel de Dificultad</label>

                    <input
                        type="text"
                        name="dificultad"
                        required
                    >

                </div>
                <div class="form-group">

                    <label>Tipo de Texto</label>

                    <input
                        type="text"
                        name="tipo_texto"
                        required
                    >
                </div>             
                <div class="form-group">

                    <label>Título</label>

                    <input
                        type="text"
                        name="titulo"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Contenido</label>

                    <textarea
                        name="contenido"
                        required
                    ></textarea>

                </div>

                <div class="form-group">

                    <label>Fuente</label>

                    <textarea
                        name="fuente"
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
                onclick="document.getElementById('formTexto').reset()"
            >
                Limpiar
            </button>

            <button
                type="submit"
                form="formTexto"
                class="btn-guardar"
            >
                Registrar texto
            </button>

        </div>

    </section>

</div>

</body>
</html>