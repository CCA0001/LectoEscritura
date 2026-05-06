<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio de Escritura - LectoEscritura</title>
    <link rel="stylesheet" href="../public/css/escritura.css?v=<?php echo time(); ?>">
    <script src="../public/JS/retroalimentacion.js"></script>
</head>
<body>

<header class="navbar">
    <span class="logo"> EVAL - Escritura</span>
    <div class="user-rank">
        🏅 <?php echo $_SESSION['rango_actual'] ?? 'Principiante'; ?>
    </div>
    <a href="../controllers/controlador_usuario.php" class="btn-volver">Volver</a>
</header>

<nav class="breadcrumb">
   < 📍 Inicio / Panel Usuario / Ejercicio de Escritura
</nav>

<main class="container">
    <h1>Ejercicio de Escritura</h1>
    <p>Sube tu archivo PDF para ser evaluado. Recibirás retroalimentación y puntos de experiencia.</p>

    <?php if ($mensaje): ?>
    <div class="alert success"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="upload-card">
        <h2>Subir nuevo archivo</h2>
        <form action="../controllers/guardar_respuesta_escritura.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nombre del ejercicio:</label>
                <input type="text" name="nombre_archivo" placeholder="Ej: Ensayo sobre la lectura" required>
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label>Nivel de dificultad:</label>
                    <select name="ID_dificultad" required>
                        <option value="">Selecciona...</option>
                        <?php while($d = mysqli_fetch_assoc($dificultades)): ?>
                        <option value="<?php echo $d['ID']; ?>"><?php echo $d['nombre']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group half">
                    <label>Tipo de texto:</label>
                    <select name="ID_tipoTexto" required>
                        <option value="">Selecciona...</option>
                        <?php while($t = mysqli_fetch_assoc($tipos)): ?>
                        <option value="<?php echo $t['ID']; ?>"><?php echo $t['nombre']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>📎 Archivo PDF:</label>
                <input type="file" name="archivo_pdf" accept=".pdf,.docx,.txt" required>
                <small>Máximo 5MB. Solo archivos PDF.</small>
            </div>

            <button type="submit" class="btn-subir">Enviar</button>
        </form>
    </div>

    <div class="historial-card">
        <h2>Mis archivos subidos</h2>

        <?php if (mysqli_num_rows($resultado) == 0): ?>
        <p class="vacio">Aún no has subido ningún archivo. ¡Sube tu primer PDF!</p>
        <?php else: ?>
        <div class="tabla-responsive">
            <table class="tabla-archivos">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Dificultad</th>
                        <th>Fecha</th>
                        <th>Archivo</th>
                        <th>Puntaje</th>
                        <th>IA</th>
                        <th>Retroalimentación</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nombre_archivo']); ?></td>
                        <td><?php echo $row['tipo_nombre'] ?? '—'; ?></td>
                        <td><?php echo $row['dificultad_nombre'] ?? '—'; ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($row['fecha_subida'])); ?></td>
                        <td><a href="<?php echo $row['url_archivo']; ?>" target="_blank" class="btn-ver">Ver PDF</a></td>
                        <td>
                            <?php if ($row['puntaje_promedio']): ?>
                            <span class="puntaje"><?php echo $row['puntaje_promedio']; ?>/10</span>
                            <?php else: ?>
                            <span class="pendiente">Pendiente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$row['puntaje_promedio']): ?>
                            <a href="../public/ia/evaluar_escritura.php?id=<?php echo $row['ID']; ?>" class="btn-ia">🤖 Evaluar</a>
                            <?php else: ?>
                            <span class="completado"><?php echo $row['puntaje_promedio']; ?>/10</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($row['retroalimentacion'])): ?>
                            <span class="retro-tooltip" onclick="mostrarRetro('<?php echo htmlspecialchars($row['retroalimentacion']); ?>')">
                                 Ver feedback
                            </span>
                            <?php else: ?>
                            <span class="sin-retro">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['puntaje_promedio']): ?>
                            <span class="completado">Evaluado</span>
                            <?php else: ?>
                            <span class="espera">En revisión</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</main>

<div id="modalRetro" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="cerrarModal()">&times;</span>
        <h3>🤖 Retroalimentación de la IA</h3>
        <div id="retroTexto"></div>
    </div>
</div>

</body>
</html>